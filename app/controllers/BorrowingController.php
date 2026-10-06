<?php
class BorrowingController extends Controller {
    private $borrowingModel;
    private $toolModel;
    private $activityLogModel;

    public function __construct() {
        if (!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        $this->borrowingModel = $this->model('Borrowing');
        $this->toolModel = $this->model('Tool');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    // List borrowings
    public function index() {
        if (hasRole([3])) {
            $borrowings = $this->borrowingModel->getBorrowingsByUser($_SESSION['user_id']);
        } else {
            $borrowings = $this->borrowingModel->getBorrowings();
        }

        $data = [
            'title'      => 'Daftar Peminjaman',
            'borrowings' => $borrowings
        ];

        $this->view('borrowings/index', $data);
    }

    public function history() {
        // Only Peminjam needs this specific history view page
        if (!hasRole([3])) {
            $this->redirect('borrowing');
            return;
        }

        $borrowings = $this->borrowingModel->getBorrowingHistoryByUser($_SESSION['user_id']);

        $data = [
            'title'      => 'Riwayat Peminjaman',
            'borrowings' => $borrowings
        ];

        $this->view('borrowings/history', $data);
    }

    // Show borrowing detail
    public function show($id = null) {
        if (!$id) {
            $this->redirect('borrowing');
            return;
        }

        $borrowing = $this->borrowingModel->getBorrowingById($id);
        if (!$borrowing) {
            flash('message', 'Peminjaman tidak ditemukan', 'danger');
            $this->redirect('borrowing');
            return;
        }

        // Peminjam hanya bisa lihat miliknya
        if (hasRole([3]) && $borrowing->user_id != $_SESSION['user_id']) {
            flash('message', 'Akses ditolak', 'danger');
            $this->redirect('borrowing');
            return;
        }

        $details = $this->borrowingModel->getBorrowingDetails($id);

        $data = [
            'title'     => 'Detail Peminjaman',
            'borrowing' => $borrowing,
            'details'   => $details
        ];

        $this->view('borrowings/show', $data);
    }

    // Create borrowing form
    public function create() {
        // Get available tools
        $tools = $this->toolModel->getTools();

        $data = [
            'title'          => 'Ajukan Peminjaman',
            'tools'          => $tools,
            'borrow_date'    => date('Y-m-d'),
            'due_date'       => date('Y-m-d', strtotime('+7 days')),
            'purpose'        => '',
            'notes'          => '',
            'selected_tools' => [],
            'purpose_err'    => '',
            'date_err'       => '',
            'tools_err'      => ''
        ];

        $this->view('borrowings/create', $data);
    }

    // Store borrowing
    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('borrowing');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
        $tools = $this->toolModel->getTools();

        $toolIds = $_POST['tool_id'] ?? [];
        $quantities = $_POST['qty'] ?? [];

        $data = [
            'title'          => 'Ajukan Peminjaman',
            'tools'          => $tools,
            'borrow_date'    => trim($_POST['borrow_date']),
            'due_date'       => trim($_POST['due_date']),
            'purpose'        => trim($_POST['purpose']),
            'notes'          => trim($_POST['notes'] ?? ''),
            'selected_tools' => $toolIds,
            'purpose_err'    => '',
            'date_err'       => '',
            'tools_err'      => ''
        ];

        // Validasi
        if (empty($data['purpose'])) {
            $data['purpose_err'] = 'Tujuan peminjaman wajib diisi';
        }
        if (empty($data['borrow_date']) || empty($data['due_date'])) {
            $data['date_err'] = 'Tanggal wajib diisi';
        } elseif (strtotime($data['due_date']) <= strtotime($data['borrow_date'])) {
            $data['date_err'] = 'Tanggal kembali harus setelah tanggal pinjam';
        }
        if (empty($toolIds)) {
            $data['tools_err'] = 'Pilih minimal 1 alat';
        }

        // Validate quantities
        $validItems = [];
        if (!empty($toolIds)) {
            foreach ($toolIds as $index => $toolId) {
                $qty = (int)($quantities[$index] ?? 0);
                if ($toolId && $qty > 0) {
                    $tool = $this->toolModel->getToolById($toolId);
                    if ($tool && $qty > $tool->available_qty) {
                        $data['tools_err'] = "Stok {$tool->name} tidak cukup (tersedia: {$tool->available_qty})";
                        break;
                    }
                    $validItems[] = ['tool_id' => $toolId, 'quantity' => $qty];
                }
            }
        }

        if (empty($validItems) && empty($data['tools_err'])) {
            $data['tools_err'] = 'Pilih minimal 1 alat dengan jumlah valid';
        }

        if (!empty($data['purpose_err']) || !empty($data['date_err']) || !empty($data['tools_err'])) {
            $this->view('borrowings/create', $data);
            return;
        }

        // Create borrowing with transaction
        try {
            $this->borrowingModel->beginTransaction();

            $borrowingData = [
                'code'        => generateCode('BRW'),
                'user_id'     => $_SESSION['user_id'],
                'borrow_date' => $data['borrow_date'],
                'due_date'    => $data['due_date'],
                'purpose'     => $data['purpose'],
                'notes'       => $data['notes']
            ];

            $this->borrowingModel->createBorrowing($borrowingData);
            $borrowingId = $this->borrowingModel->getLastInsertId();

            foreach ($validItems as $item) {
                $this->borrowingModel->addBorrowingDetail([
                    'borrowing_id' => $borrowingId,
                    'tool_id'      => $item['tool_id'],
                    'quantity'     => $item['quantity'],
                    'notes'        => ''
                ]);
            }

            $this->borrowingModel->commit();

            $this->activityLogModel->log('CREATE', 'borrowings', "Peminjaman {$borrowingData['code']} dibuat", $borrowingId);

            flash('message', 'Peminjaman berhasil diajukan');
            $this->redirect('borrowing/show/' . $borrowingId);

        } catch (Exception $e) {
            $this->borrowingModel->rollBack();
            flash('message', 'Gagal membuat peminjaman: ' . $e->getMessage(), 'danger');
            $this->redirect('borrowing');
        }
    }

    // Approve borrowing
    public function approve($id = null) {
        AuthMiddleware::isPetugas();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('borrowing');
            return;
        }

        $borrowing = $this->borrowingModel->getBorrowingById($id);
        if (!$borrowing) {
            flash('message', 'Peminjaman tidak ditemukan', 'danger');
            $this->redirect('borrowing');
            return;
        }

        if ($this->borrowingModel->updateStatus($id, 'DISETUJUI', $_SESSION['user_id'])) {
            $this->activityLogModel->log('APPROVE', 'borrowings', "Peminjaman {$borrowing->code} disetujui", $id);
            flash('message', 'Peminjaman berhasil disetujui');
        } else {
            flash('message', 'Gagal menyetujui peminjaman', 'danger');
        }
        $this->redirect('borrowing/show/' . $id);
    }

    // Reject borrowing
    public function reject($id = null) {
        AuthMiddleware::isPetugas();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('borrowing');
            return;
        }

        $borrowing = $this->borrowingModel->getBorrowingById($id);
        if (!$borrowing) {
            flash('message', 'Peminjaman tidak ditemukan', 'danger');
            $this->redirect('borrowing');
            return;
        }

        if ($this->borrowingModel->updateStatus($id, 'DITOLAK', $_SESSION['user_id'])) {
            $this->activityLogModel->log('REJECT', 'borrowings', "Peminjaman {$borrowing->code} ditolak", $id);
            flash('message', 'Peminjaman ditolak');
        } else {
            flash('message', 'Gagal menolak peminjaman', 'danger');
        }
        $this->redirect('borrowing/show/' . $id);
    }

    // Handover — serah terima alat (DISETUJUI → DIPINJAM)
    public function handover($id = null) {
        AuthMiddleware::isPetugas();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('borrowing');
            return;
        }

        $borrowing = $this->borrowingModel->getBorrowingById($id);
        if (!$borrowing) {
            flash('message', 'Peminjaman tidak ditemukan', 'danger');
            $this->redirect('borrowing');
            return;
        }

        try {
            $this->borrowingModel->beginTransaction();

            // Update status
            if (!$this->borrowingModel->updateStatus($id, 'DIPINJAM')) {
                throw new Exception('Gagal mengubah status');
            }

            // Kurangi stok alat
            $details = $this->borrowingModel->getBorrowingDetails($id);
            foreach ($details as $detail) {
                $this->toolModel->updateAvailableQty($detail->tool_id, -$detail->quantity);
            }

            $this->borrowingModel->commit();

            $this->activityLogModel->log('HANDOVER', 'borrowings', "Serah terima peminjaman {$borrowing->code}", $id);
            flash('message', 'Serah terima berhasil. Alat sudah diserahkan.');
        } catch (Exception $e) {
            $this->borrowingModel->rollBack();
            flash('message', 'Gagal melakukan serah terima: ' . $e->getMessage(), 'danger');
        }

        $this->redirect('borrowing/show/' . $id);
    }

    // Cancel borrowing
    public function cancel($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('borrowing');
            return;
        }

        $borrowing = $this->borrowingModel->getBorrowingById($id);
        if (!$borrowing) {
            flash('message', 'Peminjaman tidak ditemukan', 'danger');
            $this->redirect('borrowing');
            return;
        }

        // Peminjam hanya bisa batalkan miliknya
        if (hasRole([3]) && $borrowing->user_id != $_SESSION['user_id']) {
            flash('message', 'Akses ditolak', 'danger');
            $this->redirect('borrowing');
            return;
        }

        if ($this->borrowingModel->updateStatus($id, 'DIBATALKAN')) {
            $this->activityLogModel->log('CANCEL', 'borrowings', "Peminjaman {$borrowing->code} dibatalkan", $id);
            flash('message', 'Peminjaman berhasil dibatalkan');
        } else {
            flash('message', 'Gagal membatalkan peminjaman', 'danger');
        }
        $this->redirect('borrowing/show/' . $id);
    }

    // ============================================================
    // QUICK BORROW: Langsung dari halaman detail alat
    // ============================================================
    public function quick($toolId = null) {
        if (!$toolId) {
            $this->redirect('tool');
            return;
        }

        $tool = $this->toolModel->getToolById($toolId);
        if (!$tool || $tool->status !== 'TERSEDIA' || $tool->available_qty <= 0) {
            flash('message', 'Alat tidak tersedia untuk dipinjam', 'danger');
            $this->redirect('tool');
            return;
        }

        $data = [
            'title'       => 'Ajukan Peminjaman - ' . $tool->name,
            'tool'        => $tool,
            'borrow_date' => date('Y-m-d'),
            'due_date'    => date('Y-m-d', strtotime('+7 days')),
            'purpose'     => '',
            'qty'         => 1,
            'notes'       => '',
            'purpose_err' => '',
            'date_err'    => '',
            'qty_err'     => '',
        ];

        $this->view('borrowings/quick', $data);
    }

    public function quickStore() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('tool');
            return;
        }

        $toolId      = (int)($_POST['tool_id'] ?? 0);
        $qty         = (int)($_POST['qty'] ?? 1);
        $borrowDate  = trim($_POST['borrow_date'] ?? '');
        $dueDate     = trim($_POST['due_date'] ?? '');
        $purpose     = trim($_POST['purpose'] ?? '');
        $notes       = trim($_POST['notes'] ?? '');

        $tool = $this->toolModel->getToolById($toolId);

        $data = [
            'title'       => 'Ajukan Peminjaman - ' . ($tool->name ?? ''),
            'tool'        => $tool,
            'borrow_date' => $borrowDate,
            'due_date'    => $dueDate,
            'purpose'     => $purpose,
            'qty'         => $qty,
            'notes'       => $notes,
            'purpose_err' => '',
            'date_err'    => '',
            'qty_err'     => '',
        ];

        // Validasi
        if (empty($purpose)) {
            $data['purpose_err'] = 'Tujuan peminjaman wajib diisi';
        }
        if (empty($borrowDate) || empty($dueDate)) {
            $data['date_err'] = 'Tanggal wajib diisi';
        } elseif (strtotime($dueDate) <= strtotime($borrowDate)) {
            $data['date_err'] = 'Tanggal kembali harus setelah tanggal pinjam';
        }
        if ($qty < 1) {
            $data['qty_err'] = 'Jumlah minimal 1';
        } elseif ($tool && $qty > $tool->available_qty) {
            $data['qty_err'] = "Stok tidak cukup (tersedia: {$tool->available_qty})";
        }

        if ($data['purpose_err'] || $data['date_err'] || $data['qty_err']) {
            $this->view('borrowings/quick', $data);
            return;
        }

        // Simpan ke DB
        $db = new Database();

        // Generate kode unik
        $db->query("SELECT COUNT(*) as total FROM borrowings");
        $total = $db->single()->total ?? 0;
        $code  = 'PJ-' . str_pad($total + 1, 3, '0', STR_PAD_LEFT);

        $db->query("INSERT INTO borrowings (code, user_id, borrow_date, due_date, purpose, notes, status, created_at)
                    VALUES (:code, :user_id, :borrow_date, :due_date, :purpose, :notes, 'MENUNGGU', NOW())");
        $db->bind(':code',       $code);
        $db->bind(':user_id',    $_SESSION['user_id']);
        $db->bind(':borrow_date', $borrowDate);
        $db->bind(':due_date',   $dueDate);
        $db->bind(':purpose',    $purpose);
        $db->bind(':notes',      $notes);

        if (!$db->execute()) {
            flash('message', 'Gagal mengajukan peminjaman', 'danger');
            $this->view('borrowings/quick', $data);
            return;
        }

        $borrowingId = $db->lastInsertId();

        // Simpan detail alat
        $db->query("INSERT INTO borrowing_details (borrowing_id, tool_id, quantity) VALUES (:bid, :tid, :qty)");
        $db->bind(':bid', $borrowingId);
        $db->bind(':tid', $toolId);
        $db->bind(':qty', $qty);
        $db->execute();

        // Kurangi stok
        $db->query("UPDATE tools SET available_qty = available_qty - :qty WHERE id = :id");
        $db->bind(':qty', $qty);
        $db->bind(':id',  $toolId);
        $db->execute();

        $this->activityLogModel->log('CREATE', 'borrowings', "Peminjaman $code diajukan", $borrowingId);

        flash('message', "Peminjaman {$code} berhasil diajukan! Menunggu persetujuan.");
        $this->redirect('borrowing');
    }
}

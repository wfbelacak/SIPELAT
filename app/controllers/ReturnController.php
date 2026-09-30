<?php
class ReturnController extends Controller {
    private $returnModel;
    private $borrowingModel;
    private $toolModel;
    private $activityLogModel;

    public function __construct() {
        if (!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        $this->returnModel = $this->model('ReturnModel');
        $this->borrowingModel = $this->model('Borrowing');
        $this->toolModel = $this->model('Tool');
        $this->activityLogModel = $this->model('ActivityLog');
    }

    // List returns
    public function index() {
        if (hasRole([3])) {
            $returns = $this->returnModel->getReturnsByUser($_SESSION['user_id']);
        } else {
            $returns = $this->returnModel->getReturns();
        }

        $data = [
            'title'   => 'Daftar Pengembalian',
            'returns' => $returns
        ];

        $this->view('returns/index', $data);
    }

    // Show return detail
    public function show($id = null) {
        if (!$id) {
            $this->redirect('return');
            return;
        }

        $return = $this->returnModel->getReturnById($id);
        if (!$return) {
            flash('message', 'Pengembalian tidak ditemukan', 'danger');
            $this->redirect('return');
            return;
        }

        if (hasRole([3]) && $return->user_id != $_SESSION['user_id']) {
            flash('message', 'Akses ditolak', 'danger');
            $this->redirect('return');
            return;
        }

        $details = $this->returnModel->getReturnDetails($id);

        $data = [
            'title'   => 'Detail Pengembalian',
            'return'  => $return,
            'details' => $details
        ];

        $this->view('returns/show', $data);
    }

    // Create return form (from a borrowing)
    public function create($borrowingId = null) {
        AuthMiddleware::isPetugas();

        if (!$borrowingId) {
            $this->redirect('borrowing');
            return;
        }

        $borrowing = $this->borrowingModel->getBorrowingById($borrowingId);
        if (!$borrowing || $borrowing->status !== 'DIPINJAM') {
            flash('message', 'Peminjaman tidak valid untuk pengembalian', 'danger');
            $this->redirect('borrowing');
            return;
        }

        // Check if return already exists
        $existingReturn = $this->returnModel->getReturnByBorrowingId($borrowingId);
        if ($existingReturn) {
            flash('message', 'Pengembalian sudah diproses', 'warning');
            $this->redirect('return/show/' . $existingReturn->id);
            return;
        }

        $details = $this->borrowingModel->getBorrowingDetails($borrowingId);
        
        // Calculate late days
        $lateDays = 0;
        $today = date('Y-m-d');
        if (strtotime($today) > strtotime($borrowing->due_date)) {
            $lateDays = floor((strtotime($today) - strtotime($borrowing->due_date)) / 86400);
        }

        $data = [
            'title'        => 'Proses Pengembalian',
            'borrowing'    => $borrowing,
            'details'      => $details,
            'return_date'  => $today,
            'late_days'    => $lateDays,
            'notes'        => ''
        ];

        $this->view('returns/create', $data);
    }

    // Store return
    public function store() {
        AuthMiddleware::isPetugas();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('return');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $borrowingId = trim($_POST['borrowing_id']);
        $borrowing = $this->borrowingModel->getBorrowingById($borrowingId);

        if (!$borrowing || $borrowing->status !== 'DIPINJAM') {
            flash('message', 'Peminjaman tidak valid', 'danger');
            $this->redirect('borrowing');
            return;
        }

        $details = $this->borrowingModel->getBorrowingDetails($borrowingId);
        $finePerDay = 5000; // Rp 5.000 per hari per item

        $returnDate = trim($_POST['return_date']);
        $lateDays = 0;
        if (strtotime($returnDate) > strtotime($borrowing->due_date)) {
            $lateDays = floor((strtotime($returnDate) - strtotime($borrowing->due_date)) / 86400);
        }

        try {
            $this->returnModel->beginTransaction();

            $totalFine = 0;
            $returnItems = [];

            foreach ($details as $index => $detail) {
                $condition = $_POST['condition'][$index] ?? 'BAIK';
                $damageNote = $_POST['damage_note'][$index] ?? '';
                
                // Calculate fine: late fine + damage fine
                $itemFine = 0;
                if ($lateDays > 0) {
                    $itemFine += $lateDays * $finePerDay * $detail->quantity;
                }
                if (in_array($condition, ['RUSAK_RINGAN', 'RUSAK_BERAT', 'HILANG'])) {
                    // Additional damage fine
                    $damageFines = ['RUSAK_RINGAN' => 25000, 'RUSAK_BERAT' => 50000, 'HILANG' => 100000];
                    $itemFine += ($damageFines[$condition] ?? 0) * $detail->quantity;
                }
                
                $totalFine += $itemFine;

                $returnItems[] = [
                    'tool_id'     => $detail->tool_id,
                    'quantity'    => $detail->quantity,
                    'condition'   => $condition,
                    'damage_note' => $damageNote,
                    'fine'        => $itemFine
                ];
            }

            // Create return record
            $returnData = [
                'borrowing_id' => $borrowingId,
                'received_by'  => $_SESSION['user_id'],
                'return_date'  => $returnDate,
                'late_days'    => $lateDays,
                'total_fine'   => $totalFine,
                'notes'        => trim($_POST['notes'] ?? '')
            ];

            $this->returnModel->createReturn($returnData);
            $returnId = $this->returnModel->getLastInsertId();

            // Add return details and update tool stock
            foreach ($returnItems as $item) {
                $item['return_id'] = $returnId;
                $this->returnModel->addReturnDetail($item);

                // Restore stock only for items in good condition
                if ($item['condition'] === 'BAIK') {
                    $this->toolModel->updateAvailableQty($item['tool_id'], $item['quantity']);
                } elseif ($item['condition'] === 'RUSAK_RINGAN') {
                    // Still return to stock but might need maintenance
                    $this->toolModel->updateAvailableQty($item['tool_id'], $item['quantity']);
                }
                // RUSAK_BERAT and HILANG: don't restore stock
            }

            // Update borrowing status to DIKEMBALIKAN
            $this->borrowingModel->updateStatus($borrowingId, 'DIKEMBALIKAN');

            $this->returnModel->commit();

            $this->activityLogModel->log('CREATE', 'returns', "Pengembalian untuk peminjaman {$borrowing->code} diproses", $returnId);

            flash('message', 'Pengembalian berhasil diproses');
            $this->redirect('return/show/' . $returnId);

        } catch (Exception $e) {
            $this->returnModel->rollBack();
            flash('message', 'Gagal memproses pengembalian: ' . $e->getMessage(), 'danger');
            $this->redirect('borrowing/show/' . $borrowingId);
        }
    }

    // Complete return (DIKEMBALIKAN → SELESAI)
    public function complete($id = null) {
        AuthMiddleware::isPetugas();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('return');
            return;
        }

        $return = $this->returnModel->getReturnById($id);
        if (!$return) {
            flash('message', 'Pengembalian tidak ditemukan', 'danger');
            $this->redirect('return');
            return;
        }

        try {
            $this->returnModel->beginTransaction();

            // Update return status
            $this->returnModel->updateStatus($id, 'SELESAI');
            
            // Update borrowing status
            $this->borrowingModel->updateStatus($return->borrowing_id, 'SELESAI');

            $this->returnModel->commit();

            $this->activityLogModel->log('COMPLETE', 'returns', "Pengembalian {$return->borrowing_code} diselesaikan", $id);

            flash('message', 'Pengembalian berhasil diselesaikan');
        } catch (Exception $e) {
            $this->returnModel->rollBack();
            flash('message', 'Gagal menyelesaikan pengembalian', 'danger');
        }

        $this->redirect('return/show/' . $id);
    }
}

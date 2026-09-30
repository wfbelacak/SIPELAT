<?php
class DashboardController extends Controller {
    private $db;
    
    public function __construct() {
        if (!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        $this->db = new Database();
    }

    public function index() {
        $role = $_SESSION['user_role'];

        if (in_array($role, [1, 2])) {
            // ============================================
            // DATA UNTUK ADMIN DAN PETUGAS
            // ============================================

            // 1. Statistik Utama (Top Cards)
            // Total Alat
            $this->db->query("SELECT SUM(quantity) as total FROM tools");
            $total_alat = $this->db->single()->total ?? 0;

            // Alat Dipinjam
            $this->db->query("SELECT SUM(quantity - available_qty) as dipinjam FROM tools");
            $alat_dipinjam = $this->db->single()->dipinjam ?? 0;

            // Menunggu Persetujuan
            $this->db->query("SELECT COUNT(*) as count FROM borrowings WHERE status = 'MENUNGGU'");
            $menunggu = $this->db->single()->count ?? 0;

            // Overdue (Terlambat)
            $this->db->query("SELECT COUNT(*) as count FROM borrowings WHERE status = 'DIPINJAM' AND due_date < CURDATE()");
            $overdue = $this->db->single()->count ?? 0;

            // 2. Data Grafik Peminjaman vs Pengembalian (7 Hari Terakhir)
            $chartDates = [];
            $borrowData = [];
            $returnData = [];
            
            for ($i = 6; $i >= 0; $i--) {
                $date = date('Y-m-d', strtotime("-$i days"));
                $chartDates[] = date('d M', strtotime($date));

                // Borrowings on this date
                $this->db->query("SELECT COUNT(*) as count FROM borrowings WHERE DATE(borrow_date) = :d");
                $this->db->bind(':d', $date);
                $borrowData[] = $this->db->single()->count ?? 0;

                // Returns on this date
                $this->db->query("SELECT COUNT(*) as count FROM returns WHERE DATE(return_date) = :d");
                $this->db->bind(':d', $date);
                $returnData[] = $this->db->single()->count ?? 0;
            }

            // 3. Data Donut Chart (Status Alat)
            // Tersedia
            $this->db->query("SELECT SUM(available_qty) as count FROM tools WHERE status = 'TERSEDIA'");
            $tersedia = $this->db->single()->count ?? 0;
            
            // Maintenance
            $this->db->query("SELECT SUM(quantity) as count FROM tools WHERE status = 'MAINTENANCE'");
            $maintenance = $this->db->single()->count ?? 0;
            
            // Rusak
            $this->db->query("SELECT SUM(quantity) as count FROM tools WHERE status = 'RUSAK'");
            $rusak = $this->db->single()->count ?? 0;

            // Tidak Aktif
            $this->db->query("SELECT SUM(quantity) as count FROM tools WHERE status = 'TIDAK_AKTIF'");
            $tidak_aktif = $this->db->single()->count ?? 0;

            // 4. Tabel Peminjaman Terbaru (atau menunggu)
            if ($role == 1) {
                // Admin lihat yang terbaru
                $this->db->query("SELECT b.*, u.name as user_name FROM borrowings b JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC LIMIT 5");
            } else {
                // Petugas lebih fokus ke yang menunggu persetujuan
                $this->db->query("SELECT b.*, u.name as user_name FROM borrowings b JOIN users u ON b.user_id = u.id WHERE b.status = 'MENUNGGU' ORDER BY b.created_at DESC LIMIT 5");
            }
            $recent_borrowings = $this->db->resultSet();

            $data = [
                'title'         => 'Dashboard',
                'total_alat'    => $total_alat,
                'alat_dipinjam' => $alat_dipinjam,
                'menunggu'      => $menunggu,
                'overdue'       => $overdue,
                'chart_dates'   => json_encode($chartDates),
                'chart_borrow'  => json_encode($borrowData),
                'chart_return'  => json_encode($returnData),
                'donut_data'    => json_encode([$tersedia, $alat_dipinjam, $maintenance, $rusak, $tidak_aktif]),
                'recent_table'  => $recent_borrowings
            ];

        } else {
            // ============================================
            // DATA UNTUK PEMINJAM
            // ============================================
            $user_id = $_SESSION['user_id'];

            // 1. Statistik Personal
            // Total Peminjaman
            $this->db->query("SELECT COUNT(*) as count FROM borrowings WHERE user_id = :uid");
            $this->db->bind(':uid', $user_id);
            $total_peminjaman = $this->db->single()->count ?? 0;

            // Peminjaman Aktif (DISETUJUI / DIPINJAM)
            $this->db->query("SELECT COUNT(*) as count FROM borrowings WHERE user_id = :uid AND status IN ('DISETUJUI', 'DIPINJAM')");
            $this->db->bind(':uid', $user_id);
            $aktif = $this->db->single()->count ?? 0;

            // Menunggu Persetujuan (MENUNGGU)
            $this->db->query("SELECT COUNT(*) as count FROM borrowings WHERE user_id = :uid AND status = 'MENUNGGU'");
            $this->db->bind(':uid', $user_id);
            $menunggu = $this->db->single()->count ?? 0;

            // Selesai (DIKEMBALIKAN / SELESAI)
            $this->db->query("SELECT COUNT(*) as count FROM borrowings WHERE user_id = :uid AND status IN ('DIKEMBALIKAN', 'SELESAI')");
            $this->db->bind(':uid', $user_id);
            $selesai = $this->db->single()->count ?? 0;

            // 2. Daftar Alat Tersedia (Tampil 4-5 di Dashboard)
            $this->db->query("SELECT tools.*, categories.name as category_name FROM tools LEFT JOIN categories ON tools.category_id = categories.id WHERE tools.status = 'TERSEDIA' LIMIT 4");
            $available_tools = $this->db->resultSet();

            $data = [
                'title'            => 'Beranda',
                'total_peminjaman' => $total_peminjaman,
                'aktif'            => $aktif,
                'menunggu'         => $menunggu,
                'selesai'          => $selesai,
                'available_tools'  => $available_tools
            ];
        }

        $this->view('dashboard/index', $data);
    }
}

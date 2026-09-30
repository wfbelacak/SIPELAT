<?php
class ReportController extends Controller {
    private $db;

    public function __construct() {
        if (!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        // Admin dan Petugas bisa melihat laporan
        AuthMiddleware::isPetugas();
        $this->db = new Database();
    }

    public function index() {
        // Ambil filter tanggal dari request, default: bulan ini
        $start_date = $_GET['start_date'] ?? date('Y-m-01');
        $end_date = $_GET['end_date'] ?? date('Y-m-t');

        // Statistik Peminjaman
        $this->db->query("
            SELECT 
                COUNT(*) as total_borrowings,
                SUM(CASE WHEN status = 'SELESAI' THEN 1 ELSE 0 END) as completed,
                SUM(CASE WHEN status = 'DIPINJAM' THEN 1 ELSE 0 END) as active,
                SUM(CASE WHEN status = 'DIBATALKAN' OR status = 'DITOLAK' THEN 1 ELSE 0 END) as failed
            FROM borrowings 
            WHERE borrow_date BETWEEN :start AND :end
        ");
        $this->db->bind(':start', $start_date);
        $this->db->bind(':end', $end_date);
        $borrow_stats = $this->db->single();

        // Statistik Pengembalian & Denda
        $this->db->query("
            SELECT 
                COUNT(*) as total_returns,
                SUM(CASE WHEN late_days > 0 THEN 1 ELSE 0 END) as late_returns,
                SUM(total_fine) as total_fines
            FROM returns 
            WHERE return_date BETWEEN :start AND :end
        ");
        $this->db->bind(':start', $start_date);
        $this->db->bind(':end', $end_date);
        $return_stats = $this->db->single();

        // Alat Paling Sering Dipinjam
        $this->db->query("
            SELECT t.code, t.name, SUM(bd.quantity) as total_borrowed
            FROM tools t
            JOIN borrowing_details bd ON t.id = bd.tool_id
            JOIN borrowings b ON bd.borrowing_id = b.id 
            WHERE b.borrow_date BETWEEN :start AND :end
            GROUP BY t.id
            ORDER BY total_borrowed DESC
            LIMIT 10
        ");
        $this->db->bind(':start', $start_date);
        $this->db->bind(':end', $end_date);
        $popular_tools = $this->db->resultSet();

        $data = [
            'title'         => 'Laporan Sistem',
            'start_date'    => $start_date,
            'end_date'      => $end_date,
            'borrow_stats'  => $borrow_stats,
            'return_stats'  => $return_stats,
            'popular_tools' => $popular_tools
        ];

        $this->view('reports/index', $data);
    }
}

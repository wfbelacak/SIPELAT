<?php
class HomeController extends Controller {
    private $db;
    private $toolModel;

    public function __construct() {
        $this->db = new Database();
        $this->toolModel = $this->model('Tool'); // We can use the Tool model if we want, but DB is fine
    }

    public function index() {
        // 1. Statistik Global/Platform (Untuk tampilan Hero/Landing)
        $this->db->query("SELECT SUM(quantity) as count FROM tools");
        $total_alat = $this->db->single()->count ?? 0;

        $this->db->query("SELECT SUM(available_qty) as count FROM tools WHERE status = 'TERSEDIA'");
        $alat_tersedia = $this->db->single()->count ?? 0;

        $this->db->query("SELECT COUNT(*) as count FROM borrowings WHERE status = 'DIPINJAM'");
        $sedang_dipinjam = $this->db->single()->count ?? 0;

        $this->db->query("SELECT COUNT(*) as count FROM borrowings");
        $total_peminjaman_global = $this->db->single()->count ?? 0;

        // 2. Daftar Alat Populer (Katalog di Home)
        // Let's get actual tools, maybe ordered by most borrowed, but for now just LIMIT 5
        $this->db->query("SELECT tools.*, categories.name as category_name FROM tools LEFT JOIN categories ON tools.category_id = categories.id LIMIT 5");
        $popular_tools = $this->db->resultSet();

        $data = [
            'title'                   => 'Beranda',
            'total_alat'              => $total_alat,
            'alat_tersedia'           => $alat_tersedia,
            'sedang_dipinjam'         => $sedang_dipinjam,
            'total_peminjaman_global' => $total_peminjaman_global,
            'popular_tools'           => $popular_tools
        ];

        $this->view('home/index', $data);
    }
}

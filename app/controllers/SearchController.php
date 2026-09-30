<?php
class SearchController extends Controller {
    private $db;

    public function __construct() {
        if (!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        $this->db = new Database();
    }

    public function index() {
        $keyword = trim($_GET['q'] ?? '');
        
        $data = [
            'title' => 'Hasil Pencarian Global',
            'keyword' => $keyword,
            'tools' => [],
            'users' => [],
            'borrowings' => []
        ];

        if ($keyword !== '') {
            $like = "%$keyword%";

            // 1. Cari Alat (Tools)
            $this->db->query("SELECT tools.*, categories.name as category_name FROM tools LEFT JOIN categories ON tools.category_id = categories.id WHERE tools.name LIKE :k OR tools.code LIKE :k OR categories.name LIKE :k");
            $this->db->bind(':k', $like);
            $data['tools'] = $this->db->resultSet();

            // 2. Cari Pengguna (hanya Admin/Petugas)
            if (hasRole([1, 2])) {
                $this->db->query("SELECT * FROM users WHERE username LIKE :k OR email LIKE :k OR role_id IN (SELECT id FROM roles WHERE name LIKE :k)");
                $this->db->bind(':k', $like);
                $data['users'] = $this->db->resultSet();
            }

            // 3. Cari Peminjaman
            if (hasRole([1, 2])) {
                $this->db->query("
                    SELECT b.*, u.username, 
                           (SELECT t.name FROM borrowing_details bd JOIN tools t ON bd.tool_id = t.id WHERE bd.borrowing_id = b.id LIMIT 1) as tool_name,
                           (SELECT SUM(quantity) FROM borrowing_details WHERE borrowing_id = b.id) as qty
                    FROM borrowings b
                    JOIN users u ON b.user_id = u.id
                    WHERE u.username LIKE :k OR b.status LIKE :k OR b.code LIKE :k
                       OR b.id IN (SELECT bd.borrowing_id FROM borrowing_details bd JOIN tools t ON bd.tool_id = t.id WHERE t.name LIKE :k)
                    ORDER BY b.created_at DESC
                ");
                $this->db->bind(':k', $like);
                $data['borrowings'] = $this->db->resultSet();
            } else {
                $this->db->query("
                    SELECT b.*, u.username, 
                           (SELECT t.name FROM borrowing_details bd JOIN tools t ON bd.tool_id = t.id WHERE bd.borrowing_id = b.id LIMIT 1) as tool_name,
                           (SELECT SUM(quantity) FROM borrowing_details WHERE borrowing_id = b.id) as qty
                    FROM borrowings b
                    JOIN users u ON b.user_id = u.id
                    WHERE b.user_id = :uid 
                      AND (b.status LIKE :k OR b.code LIKE :k OR b.id IN (SELECT bd.borrowing_id FROM borrowing_details bd JOIN tools t ON bd.tool_id = t.id WHERE t.name LIKE :k))
                    ORDER BY b.created_at DESC
                ");
                $this->db->bind(':k', $like);
                $this->db->bind(':uid', $_SESSION['user_id']);
                $data['borrowings'] = $this->db->resultSet();
            }
        }

        $this->view('search/index', $data);
    }
}

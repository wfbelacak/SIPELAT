<?php
class CategoryController extends Controller {
    private $categoryModel;

    public function __construct() {
        if (!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        AuthMiddleware::isAdmin();
        $this->categoryModel = $this->model('Category');
    }

    public function index() {
        $categories = $this->categoryModel->getCategories();

        $data = [
            'title'      => 'Kelola Kategori',
            'categories' => $categories
        ];

        $this->view('categories/index', $data);
    }

    public function create() {
        $data = [
            'title'           => 'Tambah Kategori',
            'name'            => '',
            'description'     => '',
            'status'          => 'AKTIF',
            'name_err'        => '',
            'description_err' => ''
        ];

        $this->view('categories/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('category');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'title'           => 'Tambah Kategori',
            'name'            => trim($_POST['name']),
            'description'     => trim($_POST['description']),
            'status'          => trim($_POST['status']),
            'name_err'        => '',
            'description_err' => ''
        ];

        // Validasi
        if (empty($data['name'])) {
            $data['name_err'] = 'Nama kategori wajib diisi';
        }

        if (!empty($data['name_err'])) {
            $this->view('categories/create', $data);
            return;
        }

        if ($this->categoryModel->addCategory($data)) {
            flash('message', 'Kategori berhasil ditambahkan');
            $this->redirect('category');
        } else {
            flash('message', 'Gagal menambahkan kategori', 'danger');
            $this->redirect('category');
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $this->redirect('category');
            return;
        }

        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            flash('message', 'Kategori tidak ditemukan', 'danger');
            $this->redirect('category');
            return;
        }

        $data = [
            'title'           => 'Edit Kategori',
            'id'              => $category->id,
            'name'            => $category->name,
            'description'     => $category->description,
            'status'          => $category->status,
            'name_err'        => '',
            'description_err' => ''
        ];

        $this->view('categories/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('category');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'title'           => 'Edit Kategori',
            'id'              => trim($_POST['id']),
            'name'            => trim($_POST['name']),
            'description'     => trim($_POST['description']),
            'status'          => trim($_POST['status']),
            'name_err'        => '',
            'description_err' => ''
        ];

        // Validasi
        if (empty($data['name'])) {
            $data['name_err'] = 'Nama kategori wajib diisi';
        }

        if (!empty($data['name_err'])) {
            $this->view('categories/edit', $data);
            return;
        }

        if ($this->categoryModel->updateCategory($data)) {
            flash('message', 'Kategori berhasil diperbarui');
            $this->redirect('category');
        } else {
            flash('message', 'Gagal memperbarui kategori', 'danger');
            $this->redirect('category');
        }
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('category');
            return;
        }

        if ($this->categoryModel->deleteCategory($id)) {
            flash('message', 'Kategori berhasil dihapus');
        } else {
            flash('message', 'Gagal menghapus kategori', 'danger');
        }
        $this->redirect('category');
    }
}

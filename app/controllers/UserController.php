<?php
class UserController extends Controller {
    private $userModel;

    public function __construct() {
        if (!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        AuthMiddleware::isAdmin();
        $this->userModel = $this->model('User');
    }

    public function index() {
        $users = $this->userModel->getUsers();

        $data = [
            'title' => 'Kelola Pengguna',
            'users' => $users
        ];

        $this->view('users/index', $data);
    }

    public function create() {
        $data = [
            'title'        => 'Tambah Pengguna',
            'role_id'      => '',
            'name'         => '',
            'username'     => '',
            'email'        => '',
            'password'     => '',
            'phone'        => '',
            'address'      => '',
            'status'       => 'AKTIF',
            'name_err'     => '',
            'username_err' => '',
            'email_err'    => '',
            'password_err' => '',
            'role_err'     => ''
        ];

        $this->view('users/create', $data);
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('user');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'title'        => 'Tambah Pengguna',
            'role_id'      => trim($_POST['role_id']),
            'name'         => trim($_POST['name']),
            'username'     => trim($_POST['username']),
            'email'        => trim($_POST['email']),
            'password'     => trim($_POST['password']),
            'phone'        => trim($_POST['phone']),
            'address'      => trim($_POST['address']),
            'status'       => trim($_POST['status']),
            'name_err'     => '',
            'username_err' => '',
            'email_err'    => '',
            'password_err' => '',
            'role_err'     => ''
        ];

        // Validasi
        if (empty($data['name'])) {
            $data['name_err'] = 'Nama wajib diisi';
        }
        if (empty($data['username'])) {
            $data['username_err'] = 'Username wajib diisi';
        } elseif ($this->userModel->findUserByUsername($data['username'])) {
            $data['username_err'] = 'Username sudah digunakan';
        }
        if (empty($data['email'])) {
            $data['email_err'] = 'Email wajib diisi';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['email_err'] = 'Format email tidak valid';
        } elseif ($this->userModel->findUserByEmail($data['email'])) {
            $data['email_err'] = 'Email sudah digunakan';
        }
        if (empty($data['password'])) {
            $data['password_err'] = 'Password wajib diisi';
        } elseif (strlen($data['password']) < 6) {
            $data['password_err'] = 'Password minimal 6 karakter';
        }
        if (empty($data['role_id'])) {
            $data['role_err'] = 'Role wajib dipilih';
        }

        if (!empty($data['name_err']) || !empty($data['username_err']) || 
            !empty($data['email_err']) || !empty($data['password_err']) || !empty($data['role_err'])) {
            $this->view('users/create', $data);
            return;
        }

        if ($this->userModel->addUser($data)) {
            flash('message', 'Pengguna berhasil ditambahkan');
            $this->redirect('user');
        } else {
            flash('message', 'Gagal menambahkan pengguna', 'danger');
            $this->redirect('user');
        }
    }

    public function edit($id = null) {
        if (!$id) {
            $this->redirect('user');
            return;
        }

        $user = $this->userModel->getUserById($id);
        if (!$user) {
            flash('message', 'Pengguna tidak ditemukan', 'danger');
            $this->redirect('user');
            return;
        }

        $data = [
            'title'        => 'Edit Pengguna',
            'id'           => $user->id,
            'role_id'      => $user->role_id,
            'name'         => $user->name,
            'username'     => $user->username,
            'email'        => $user->email,
            'password'     => '',
            'phone'        => $user->phone,
            'address'      => $user->address,
            'status'       => $user->status,
            'name_err'     => '',
            'username_err' => '',
            'email_err'    => '',
            'password_err' => '',
            'role_err'     => ''
        ];

        $this->view('users/edit', $data);
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('user');
            return;
        }

        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

        $data = [
            'title'        => 'Edit Pengguna',
            'id'           => trim($_POST['id']),
            'role_id'      => trim($_POST['role_id']),
            'name'         => trim($_POST['name']),
            'username'     => trim($_POST['username']),
            'email'        => trim($_POST['email']),
            'password'     => trim($_POST['password']),
            'phone'        => trim($_POST['phone']),
            'address'      => trim($_POST['address']),
            'status'       => trim($_POST['status']),
            'name_err'     => '',
            'username_err' => '',
            'email_err'    => '',
            'password_err' => '',
            'role_err'     => ''
        ];

        // Validasi
        if (empty($data['name'])) {
            $data['name_err'] = 'Nama wajib diisi';
        }
        if (empty($data['username'])) {
            $data['username_err'] = 'Username wajib diisi';
        } else {
            $existingUser = $this->userModel->findUserByUsername($data['username']);
            if ($existingUser && $existingUser->id != $data['id']) {
                $data['username_err'] = 'Username sudah digunakan';
            }
        }
        if (empty($data['email'])) {
            $data['email_err'] = 'Email wajib diisi';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $data['email_err'] = 'Format email tidak valid';
        } else {
            $existingEmail = $this->userModel->findUserByEmail($data['email']);
            if ($existingEmail && $existingEmail->id != $data['id']) {
                $data['email_err'] = 'Email sudah digunakan';
            }
        }
        if (!empty($data['password']) && strlen($data['password']) < 6) {
            $data['password_err'] = 'Password minimal 6 karakter';
        }
        if (empty($data['role_id'])) {
            $data['role_err'] = 'Role wajib dipilih';
        }

        if (!empty($data['name_err']) || !empty($data['username_err']) || 
            !empty($data['email_err']) || !empty($data['password_err']) || !empty($data['role_err'])) {
            $this->view('users/edit', $data);
            return;
        }

        if ($this->userModel->updateUser($data)) {
            flash('message', 'Pengguna berhasil diperbarui');
            $this->redirect('user');
        } else {
            flash('message', 'Gagal memperbarui pengguna', 'danger');
            $this->redirect('user');
        }
    }

    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            $this->redirect('user');
            return;
        }

        // Prevent deleting own account
        if ($id == $_SESSION['user_id']) {
            flash('message', 'Tidak dapat menghapus akun sendiri', 'danger');
            $this->redirect('user');
            return;
        }

        if ($this->userModel->deleteUser($id)) {
            flash('message', 'Pengguna berhasil dihapus');
        } else {
            flash('message', 'Gagal menghapus pengguna', 'danger');
        }
        $this->redirect('user');
    }
}

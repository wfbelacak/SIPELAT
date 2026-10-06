<?php
class AuthController extends Controller {
    private $userModel;

    public function __construct(){
        $this->userModel = $this->model('User');
    }

    public function index(){
        // Jika sudah login, redirect ke dashboard
        if(isset($_SESSION['user_id'])){
            $this->redirect('dashboard');
        }

        $data = [
            'username' => '',
            'password' => '',
            'username_err' => '',
            'password_err' => ''
        ];

        $this->view('auth/login', $data);
    }

    public function login(){
        // Check for POST
        if($_SERVER['REQUEST_METHOD'] == 'POST'){
            
            // Removed deprecated FILTER_SANITIZE_STRING
            
            $data = [
                'username' => trim($_POST['username']),
                'password' => trim($_POST['password']),
                'username_err' => '',
                'password_err' => ''
            ];

            // Validate Username
            if(empty($data['username'])){
                $data['username_err'] = 'Silakan masukkan username';
            }

            // Validate Password
            if(empty($data['password'])){
                $data['password_err'] = 'Silakan masukkan password';
            }

            // Cek user/email
            if($this->userModel->findUserByUsername($data['username'])){
                // User found
            } else {
                $data['username_err'] = 'User tidak ditemukan';
            }

            // Make sure errors are empty
            if(empty($data['username_err']) && empty($data['password_err'])){
                // Validated
                // Cek dan set user ter-login
                $loggedInUser = $this->userModel->login($data['username'], $data['password']);

                if($loggedInUser){
                    // Cek status aktif
                    if($loggedInUser->status == 'NONAKTIF') {
                        $data['username_err'] = 'Akun anda nonaktif';
                        $this->view('auth/login', $data);
                        return;
                    }

                    // Update last login
                    $this->userModel->updateLastLogin($loggedInUser->id);

                    // Buat Session
                    $this->createUserSession($loggedInUser);
                } else {
                    $data['password_err'] = 'Password salah';
                    $this->view('auth/login', $data);
                }
            } else {
                // Load view with errors
                $this->view('auth/login', $data);
            }

        } else {
            // Redirect jika bukan POST
            $this->redirect('auth');
        }
    }

    public function createUserSession($user){
        $_SESSION['user_id'] = $user->id;
        $_SESSION['user_email'] = $user->email;
        $_SESSION['user_name'] = $user->name;
        $_SESSION['user_role'] = $user->role_id;
        $_SESSION['username'] = $user->username;
        $_SESSION['user_avatar'] = $user->avatar ?? null;
        
        session_regenerate_id(true); // Security: cegah session fixation
        
        $this->redirect('dashboard');
    }

    public function logout(){
        unset($_SESSION['user_id']);
        unset($_SESSION['user_email']);
        unset($_SESSION['user_name']);
        unset($_SESSION['user_role']);
        unset($_SESSION['username']);
        session_destroy();
        
        $this->redirect('auth');
    }

    // ============================================================
    // REGISTER
    // ============================================================
    public function register(){
        if(isset($_SESSION['user_id'])){
            $this->redirect('dashboard');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST'){

            $data = [
                'name'                 => trim($_POST['name'] ?? ''),
                'username'             => trim($_POST['username'] ?? ''),
                'email'                => trim($_POST['email'] ?? ''),
                'password'             => $_POST['password'] ?? '',
                'confirm_password'     => $_POST['confirm_password'] ?? '',
                'name_err'             => '',
                'username_err'         => '',
                'email_err'            => '',
                'password_err'         => '',
                'confirm_password_err' => '',
            ];

            // Validasi Nama
            if(empty($data['name'])){
                $data['name_err'] = 'Nama lengkap wajib diisi';
            }

            // Validasi Username
            if(empty($data['username'])){
                $data['username_err'] = 'Username wajib diisi';
            } elseif(strlen($data['username']) < 3){
                $data['username_err'] = 'Username minimal 3 karakter';
            } elseif($this->userModel->findUserByUsername($data['username'])){
                $data['username_err'] = 'Username sudah digunakan';
            }

            // Validasi Email
            if(empty($data['email'])){
                $data['email_err'] = 'Email wajib diisi';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)){
                $data['email_err'] = 'Format email tidak valid';
            } elseif($this->userModel->findUserByEmail($data['email'])){
                $data['email_err'] = 'Email sudah terdaftar';
            }

            // Validasi Password
            if(empty($data['password'])){
                $data['password_err'] = 'Password wajib diisi';
            } elseif(strlen($data['password']) < 6){
                $data['password_err'] = 'Password minimal 6 karakter';
            }

            // Validasi Konfirmasi Password
            if(empty($data['confirm_password'])){
                $data['confirm_password_err'] = 'Konfirmasi password wajib diisi';
            } elseif($data['password'] !== $data['confirm_password']){
                $data['confirm_password_err'] = 'Konfirmasi password tidak cocok';
            }

            // Cek error
            if(empty($data['name_err']) && empty($data['username_err']) && empty($data['email_err']) && empty($data['password_err']) && empty($data['confirm_password_err'])){
                // Simpan user baru dengan role Peminjam (role_id = 3)
                $userData = [
                    'role_id'  => 3,
                    'name'     => $data['name'],
                    'username' => $data['username'],
                    'email'    => $data['email'],
                    'password' => $data['password'],
                    'phone'    => '',
                    'address'  => '',
                    'status'   => 'AKTIF',
                ];

                if($this->userModel->addUser($userData)){
                    // Auto-login: ambil data user yang baru dibuat lalu buat session
                    $newUser = $this->userModel->findUserByUsername($data['username']);
                    if($newUser){
                        $this->userModel->updateLastLogin($newUser->id);
                        $this->createUserSession($newUser);
                    } else {
                        flash('message', 'Pendaftaran berhasil! Silakan login.');
                        $this->redirect('auth');
                    }
                } else {
                    flash('message', 'Terjadi kesalahan saat mendaftar', 'danger');
                    $this->view('auth/register', $data);
                }
            } else {
                $this->view('auth/register', $data);
            }

        } else {
            // GET — tampilkan form
            $data = [
                'name'                 => '',
                'username'             => '',
                'email'                => '',
                'password'             => '',
                'confirm_password'     => '',
                'name_err'             => '',
                'username_err'         => '',
                'email_err'            => '',
                'password_err'         => '',
                'confirm_password_err' => '',
            ];
            $this->view('auth/register', $data);
        }
    }
}

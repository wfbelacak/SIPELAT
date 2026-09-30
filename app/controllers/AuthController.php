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
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
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
}

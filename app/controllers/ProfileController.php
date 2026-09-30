<?php
class ProfileController extends Controller {
    private $userModel;

    public function __construct() {
        if(!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        $this->userModel = $this->model('User');
    }

    public function index() {
        $user = $this->userModel->getUserById($_SESSION['user_id']);
        
        $data = [
            'title' => 'Pengaturan Profil',
            'user' => $user
        ];
        
        $this->view('profile/index', $data);
    }

    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user_id = $_SESSION['user_id'];
            $name = trim($_POST['name']);
            $password = $_POST['password'];
            $password_confirm = $_POST['password_confirm'];
            
            // Handle Password Change
            if(!empty($password)) {
                if($password !== $password_confirm) {
                    flash('error', 'Konfirmasi password tidak cocok!', 'danger');
                    $this->redirect('profile');
                    return;
                }
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                $this->userModel->updatePassword($user_id, $hashed_password);
            }
            
            // Handle Avatar Upload
            $avatar = $_POST['current_avatar'];
            if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png'];
                $filename = $_FILES['avatar']['name'];
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                
                if(in_array(strtolower($ext), $allowed)) {
                    $new_filename = 'avatar_' . $user_id . '_' . time() . '.' . $ext;
                    $upload_path = '../public/assets/img/avatars/';
                    
                    if(!is_dir($upload_path)) {
                        mkdir($upload_path, 0777, true);
                    }
                    
                    if(move_uploaded_file($_FILES['avatar']['tmp_name'], $upload_path . $new_filename)) {
                        $avatar = $new_filename;
                        $_SESSION['user_avatar'] = $avatar;
                    }
                } else {
                    flash('error', 'Format gambar tidak diizinkan. Gunakan JPG atau PNG.', 'danger');
                    $this->redirect('profile');
                    return;
                }
            }

            // Update Name and Avatar
            if($this->userModel->updateProfile($user_id, $name, $avatar)) {
                $_SESSION['user_name'] = $name;
                flash('success', 'Profil berhasil diperbarui!');
            } else {
                flash('error', 'Gagal memperbarui profil.', 'danger');
            }
            
            $this->redirect('profile');
        } else {
            $this->redirect('profile');
        }
    }
}

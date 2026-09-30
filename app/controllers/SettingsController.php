<?php
class SettingsController extends Controller {
    private $settingModel;

    public function __construct() {
        // Hanya Admin yang boleh akses pengaturan
        if(!AuthMiddleware::isAdmin()) {
            flash('error', 'Anda tidak memiliki akses ke halaman Pengaturan.', 'danger');
            $this->redirect('dashboard');
        }
        $this->settingModel = $this->model('Setting');
    }

    public function index() {
        $settings = $this->settingModel->getAllGrouped();
        
        $data = [
            'title' => 'Pengaturan Sistem',
            'settings' => $settings
        ];
        
        $this->view('settings/index', $data);
    }

    public function update() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitasi input
            $updateData = [];
            foreach($_POST as $key => $value) {
                // Ignore keys that shouldn't be in DB
                if($key !== 'submit') {
                    $updateData[$key] = trim($value);
                }
            }

            if($this->settingModel->updateBatch($updateData)) {
                flash('success', 'Pengaturan berhasil diperbarui!');
            } else {
                flash('error', 'Gagal memperbarui pengaturan.', 'danger');
            }
            
            $this->redirect('settings');
        } else {
            $this->redirect('settings');
        }
    }
}

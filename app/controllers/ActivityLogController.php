<?php
class ActivityLogController extends Controller {
    private $logModel;

    public function __construct() {
        if (!AuthMiddleware::isLoggedIn()) {
            $this->redirect('auth');
        }
        // Hanya Admin yang bisa melihat log aktivitas
        AuthMiddleware::isAdmin();
        $this->logModel = $this->model('ActivityLog');
    }

    public function index() {
        $logs = $this->logModel->getLogs(500); // Ambil 500 log terakhir

        $data = [
            'title' => 'Log Aktivitas',
            'logs'  => $logs
        ];

        $this->view('activity_logs/index', $data);
    }
}

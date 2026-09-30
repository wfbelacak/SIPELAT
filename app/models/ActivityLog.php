<?php
class ActivityLog {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Log an activity
    public function log($action, $module, $description, $referenceId = null) {
        $this->db->query('INSERT INTO activity_logs (user_id, action, module, reference_id, description, ip_address, user_agent) 
                          VALUES (:user_id, :action, :module, :reference_id, :description, :ip_address, :user_agent)');
        
        $this->db->bind(':user_id', $_SESSION['user_id'] ?? null);
        $this->db->bind(':action', $action);
        $this->db->bind(':module', $module);
        $this->db->bind(':reference_id', $referenceId);
        $this->db->bind(':description', $description);
        $this->db->bind(':ip_address', $_SERVER['REMOTE_ADDR'] ?? null);
        $this->db->bind(':user_agent', $_SERVER['HTTP_USER_AGENT'] ?? null);

        return $this->db->execute();
    }

    // Get all logs
    public function getLogs($limit = 100) {
        $this->db->query('SELECT al.*, u.name as user_name 
                          FROM activity_logs al 
                          LEFT JOIN users u ON al.user_id = u.id 
                          ORDER BY al.created_at DESC 
                          LIMIT ' . (int)$limit);
        return $this->db->resultSet();
    }

    // Get logs by module
    public function getLogsByModule($module) {
        $this->db->query('SELECT al.*, u.name as user_name 
                          FROM activity_logs al 
                          LEFT JOIN users u ON al.user_id = u.id 
                          WHERE al.module = :module 
                          ORDER BY al.created_at DESC');
        $this->db->bind(':module', $module);
        return $this->db->resultSet();
    }
}

<?php
class Setting {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all settings as key-value pairs
    public function getAll() {
        $this->db->query("SELECT * FROM settings");
        $results = $this->db->resultSet();
        
        $settings = [];
        foreach($results as $row) {
            $settings[$row->setting_key] = $row->setting_value;
        }
        return $settings;
    }

    // Get all settings grouped by setting_group
    public function getAllGrouped() {
        $this->db->query("SELECT * FROM settings ORDER BY setting_group ASC, id ASC");
        $results = $this->db->resultSet();
        
        $grouped = [];
        foreach($results as $row) {
            $grouped[$row->setting_group][] = $row;
        }
        return $grouped;
    }

    // Update multiple settings at once
    public function updateBatch($data) {
        $success = true;
        foreach($data as $key => $value) {
            $this->db->query("UPDATE settings SET setting_value = :value WHERE setting_key = :key");
            $this->db->bind(':value', $value);
            $this->db->bind(':key', $key);
            if(!$this->db->execute()) {
                $success = false;
            }
        }
        return $success;
    }

    // Get single setting
    public function get($key) {
        $this->db->query("SELECT setting_value FROM settings WHERE setting_key = :key");
        $this->db->bind(':key', $key);
        $result = $this->db->single();
        return $result ? $result->setting_value : null;
    }
}

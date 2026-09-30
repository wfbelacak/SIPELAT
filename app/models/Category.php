<?php
class Category {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getCategories() {
        $this->db->query('SELECT * FROM categories');
        return $this->db->resultSet();
    }

    public function getCategoryById($id) {
        $this->db->query('SELECT * FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function addCategory($data) {
        $this->db->query('INSERT INTO categories (name, description, status) VALUES (:name, :description, :status)');
        
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function updateCategory($data) {
        $this->db->query('UPDATE categories SET name = :name, description = :description, status = :status WHERE id = :id');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function deleteCategory($id) {
        $this->db->query('DELETE FROM categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

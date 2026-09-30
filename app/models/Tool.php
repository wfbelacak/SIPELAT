<?php
class Tool {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getTools() {
        $this->db->query('SELECT tools.*, categories.name as category_name FROM tools JOIN categories ON tools.category_id = categories.id');
        return $this->db->resultSet();
    }

    
    public function searchTools($keyword) {
        $this->db->query("SELECT tools.*, categories.name as category_name FROM tools JOIN categories ON tools.category_id = categories.id WHERE tools.name LIKE :keyword OR tools.code LIKE :keyword OR categories.name LIKE :keyword");
        $this->db->bind(':keyword', "%$keyword%");
        return $this->db->resultSet();
    }

    public function getToolById($id) {
        $this->db->query('SELECT tools.*, categories.name as category_name FROM tools JOIN categories ON tools.category_id = categories.id WHERE tools.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getToolByCode($code) {
        $this->db->query('SELECT * FROM tools WHERE code = :code');
        $this->db->bind(':code', $code);
        return $this->db->single();
    }

    public function addTool($data) {
        $this->db->query('INSERT INTO tools (category_id, code, name, description, quantity, available_qty, `condition`, location, image, status) VALUES (:category_id, :code, :name, :description, :quantity, :available_qty, :condition, :location, :image, :status)');

        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':available_qty', $data['quantity']); // Default available = quantity
        $this->db->bind(':condition', $data['condition']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':image', $data['image'] ?? null);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function updateTool($data) {
        $this->db->query('UPDATE tools SET category_id = :category_id, code = :code, name = :name, description = :description, quantity = :quantity, `condition` = :condition, location = :location, image = :image, status = :status WHERE id = :id');

        $this->db->bind(':id', $data['id']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':condition', $data['condition']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':image', $data['image'] ?? null);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function updateAvailableQty($id, $qty_change) {
        // Jika peminjaman qty_change negatif, jika pengembalian qty_change positif
        $this->db->query('UPDATE tools SET available_qty = available_qty + :qty_change WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':qty_change', $qty_change);
        return $this->db->execute();
    }

    public function deleteTool($id) {
        $this->db->query('DELETE FROM tools WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

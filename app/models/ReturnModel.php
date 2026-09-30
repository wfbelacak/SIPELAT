<?php
class ReturnModel {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all returns
    public function getReturns() {
        $this->db->query('SELECT r.*, b.code as borrowing_code, u.name as user_name,
                          rv.name as received_by_name
                          FROM returns r
                          JOIN borrowings b ON r.borrowing_id = b.id
                          JOIN users u ON b.user_id = u.id
                          LEFT JOIN users rv ON r.received_by = rv.id
                          ORDER BY r.created_at DESC');
        return $this->db->resultSet();
    }

    // Get returns by user
    public function getReturnsByUser($userId) {
        $this->db->query('SELECT r.*, b.code as borrowing_code, u.name as user_name,
                          rv.name as received_by_name
                          FROM returns r
                          JOIN borrowings b ON r.borrowing_id = b.id
                          JOIN users u ON b.user_id = u.id
                          LEFT JOIN users rv ON r.received_by = rv.id
                          WHERE b.user_id = :user_id
                          ORDER BY r.created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Get return by ID
    public function getReturnById($id) {
        $this->db->query('SELECT r.*, b.code as borrowing_code, b.borrow_date, b.due_date, 
                          b.purpose, b.user_id, u.name as user_name, u.email, u.phone,
                          rv.name as received_by_name
                          FROM returns r
                          JOIN borrowings b ON r.borrowing_id = b.id
                          JOIN users u ON b.user_id = u.id
                          LEFT JOIN users rv ON r.received_by = rv.id
                          WHERE r.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get return by borrowing ID
    public function getReturnByBorrowingId($borrowingId) {
        $this->db->query('SELECT * FROM returns WHERE borrowing_id = :borrowing_id');
        $this->db->bind(':borrowing_id', $borrowingId);
        return $this->db->single();
    }

    // Get return details
    public function getReturnDetails($returnId) {
        $this->db->query('SELECT rd.*, t.code as tool_code, t.name as tool_name
                          FROM return_details rd
                          JOIN tools t ON rd.tool_id = t.id
                          WHERE rd.return_id = :return_id');
        $this->db->bind(':return_id', $returnId);
        return $this->db->resultSet();
    }

    // Create return record
    public function createReturn($data) {
        $this->db->query('INSERT INTO returns (borrowing_id, received_by, return_date, late_days, total_fine, status, notes)
                          VALUES (:borrowing_id, :received_by, :return_date, :late_days, :total_fine, :status, :notes)');
        
        $this->db->bind(':borrowing_id', $data['borrowing_id']);
        $this->db->bind(':received_by', $data['received_by']);
        $this->db->bind(':return_date', $data['return_date']);
        $this->db->bind(':late_days', $data['late_days']);
        $this->db->bind(':total_fine', $data['total_fine']);
        $this->db->bind(':status', 'DIPERIKSA');
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    // Get last insert ID
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    // Add return detail
    public function addReturnDetail($data) {
        $this->db->query('INSERT INTO return_details (return_id, tool_id, quantity, `condition`, damage_note, fine)
                          VALUES (:return_id, :tool_id, :quantity, :condition, :damage_note, :fine)');
        
        $this->db->bind(':return_id', $data['return_id']);
        $this->db->bind(':tool_id', $data['tool_id']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':condition', $data['condition']);
        $this->db->bind(':damage_note', $data['damage_note']);
        $this->db->bind(':fine', $data['fine']);

        return $this->db->execute();
    }

    // Update return status
    public function updateStatus($id, $status) {
        $this->db->query('UPDATE returns SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }

    // Begin transaction
    public function beginTransaction() {
        return $this->db->beginTransaction();
    }

    // Commit
    public function commit() {
        return $this->db->commit();
    }

    // Rollback
    public function rollBack() {
        return $this->db->rollBack();
    }
}

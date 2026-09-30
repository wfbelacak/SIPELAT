<?php
class Borrowing {
    private $db;

    // State transition rules
    private $allowedTransitions = [
        'DRAFT'        => ['MENUNGGU', 'DIBATALKAN'],
        'MENUNGGU'     => ['DISETUJUI', 'DITOLAK', 'DIBATALKAN'],
        'DISETUJUI'    => ['DIPINJAM'],
        'DITOLAK'      => [],
        'DIPINJAM'     => ['DIKEMBALIKAN'],
        'DIKEMBALIKAN' => ['SELESAI'],
        'SELESAI'      => [],
        'DIBATALKAN'   => []
    ];

    public function __construct() {
        $this->db = new Database();
    }

    // Get all borrowings
    public function getBorrowings() {
        $this->db->query('SELECT b.*, u.name as user_name, a.name as approved_by_name 
                          FROM borrowings b 
                          JOIN users u ON b.user_id = u.id 
                          LEFT JOIN users a ON b.approved_by = a.id 
                          ORDER BY b.created_at DESC');
        return $this->db->resultSet();
    }

    // Get borrowings by user ID
    public function getBorrowingsByUser($userId) {
        $this->db->query('SELECT b.*, u.name as user_name, a.name as approved_by_name 
                          FROM borrowings b 
                          JOIN users u ON b.user_id = u.id 
                          LEFT JOIN users a ON b.approved_by = a.id 
                          WHERE b.user_id = :user_id 
                          ORDER BY b.created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    // Get borrowing by ID
    public function getBorrowingById($id) {
        $this->db->query('SELECT b.*, u.name as user_name, u.username, u.email, u.phone,
                          a.name as approved_by_name
                          FROM borrowings b 
                          JOIN users u ON b.user_id = u.id 
                          LEFT JOIN users a ON b.approved_by = a.id 
                          WHERE b.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get borrowing details (items)
    public function getBorrowingDetails($borrowingId) {
        $this->db->query('SELECT bd.*, t.code as tool_code, t.name as tool_name, t.available_qty
                          FROM borrowing_details bd 
                          JOIN tools t ON bd.tool_id = t.id 
                          WHERE bd.borrowing_id = :borrowing_id');
        $this->db->bind(':borrowing_id', $borrowingId);
        return $this->db->resultSet();
    }

    // Create borrowing
    public function createBorrowing($data) {
        $this->db->query('INSERT INTO borrowings (code, user_id, borrow_date, due_date, purpose, status, notes) 
                          VALUES (:code, :user_id, :borrow_date, :due_date, :purpose, :status, :notes)');
        
        $this->db->bind(':code', $data['code']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':borrow_date', $data['borrow_date']);
        $this->db->bind(':due_date', $data['due_date']);
        $this->db->bind(':purpose', $data['purpose']);
        $this->db->bind(':status', 'MENUNGGU');
        $this->db->bind(':notes', $data['notes']);

        return $this->db->execute();
    }

    // Get last insert ID
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    // Add borrowing detail
    public function addBorrowingDetail($data) {
        $this->db->query('INSERT INTO borrowing_details (borrowing_id, tool_id, quantity, notes) 
                          VALUES (:borrowing_id, :tool_id, :quantity, :notes)');
        
        $this->db->bind(':borrowing_id', $data['borrowing_id']);
        $this->db->bind(':tool_id', $data['tool_id']);
        $this->db->bind(':quantity', $data['quantity']);
        $this->db->bind(':notes', $data['notes'] ?? '');

        return $this->db->execute();
    }

    // Check if transition is allowed
    public function canTransition($currentStatus, $newStatus) {
        return in_array($newStatus, $this->allowedTransitions[$currentStatus] ?? []);
    }

    // Update status with validation
    public function updateStatus($id, $newStatus, $approvedBy = null) {
        $borrowing = $this->getBorrowingById($id);
        if (!$borrowing) return false;

        if (!$this->canTransition($borrowing->status, $newStatus)) {
            return false;
        }

        if ($approvedBy && in_array($newStatus, ['DISETUJUI', 'DITOLAK'])) {
            $this->db->query('UPDATE borrowings SET status = :status, approved_by = :approved_by, approved_at = NOW() WHERE id = :id');
            $this->db->bind(':approved_by', $approvedBy);
        } else {
            $this->db->query('UPDATE borrowings SET status = :status WHERE id = :id');
        }

        $this->db->bind(':id', $id);
        $this->db->bind(':status', $newStatus);

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

    // Get active borrowings for a tool (to check availability)
    public function getActiveBorrowingsForTool($toolId) {
        $this->db->query('SELECT SUM(bd.quantity) as total_borrowed 
                          FROM borrowing_details bd 
                          JOIN borrowings b ON bd.borrowing_id = b.id 
                          WHERE bd.tool_id = :tool_id 
                          AND b.status IN ("DISETUJUI", "DIPINJAM")');
        $this->db->bind(':tool_id', $toolId);
        $result = $this->db->single();
        return $result->total_borrowed ?? 0;
    }
}

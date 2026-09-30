<?php
class User {
    private $db;

    public function __construct(){
        $this->db = new Database();
    }

    // Find user by username
    public function findUserByUsername($username){
        $this->db->query("SELECT * FROM users WHERE username = :username");
        $this->db->bind(':username', $username);
        
        $row = $this->db->single();

        if($this->db->rowCount() > 0){
            return $row;
        } else {
            return false;
        }
    }

    // Login user
    public function login($username, $password){
        $row = $this->findUserByUsername($username);

        if($row == false) return false;

        $hashed_password = $row->password;
        
        if(password_verify($password, $hashed_password)){
            return $row;
        } else {
            return false;
        }
    }
    
    // Update last login
    public function updateLastLogin($id) {
        $this->db->query("UPDATE users SET last_login = NOW() WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    // Get user by ID
    public function getUserById($id){
        $this->db->query("SELECT users.*, roles.name as role_name FROM users JOIN roles ON users.role_id = roles.id WHERE users.id = :id");
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    // Get all users with role name
    public function getUsers() {
        $this->db->query('SELECT users.*, roles.name as role_name FROM users JOIN roles ON users.role_id = roles.id ORDER BY users.created_at DESC');
        return $this->db->resultSet();
    }

    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query("SELECT * FROM users WHERE email = :email");
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        return ($this->db->rowCount() > 0) ? $row : false;
    }

    // Add user
    public function addUser($data) {
        $this->db->query('INSERT INTO users (role_id, name, username, email, password, phone, address, status) VALUES (:role_id, :name, :username, :email, :password, :phone, :address, :status)');
        
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    // Update user
    public function updateUser($data) {
        // If password is provided, update it too
        if (!empty($data['password'])) {
            $this->db->query('UPDATE users SET role_id = :role_id, name = :name, username = :username, email = :email, password = :password, phone = :phone, address = :address, status = :status WHERE id = :id');
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        } else {
            $this->db->query('UPDATE users SET role_id = :role_id, name = :name, username = :username, email = :email, phone = :phone, address = :address, status = :status WHERE id = :id');
        }
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':role_id', $data['role_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':status', $data['status']);

        return $this->db->execute();
    }

    public function updateProfile($id, $name, $avatar) {
        $this->db->query("UPDATE users SET name = :name, avatar = :avatar WHERE id = :id");
        $this->db->bind(':name', $name);
        $this->db->bind(':avatar', $avatar);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updatePassword($id, $hashed_password) {
        $this->db->query("UPDATE users SET password = :password WHERE id = :id");
        $this->db->bind(':password', $hashed_password);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Delete user
    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
}

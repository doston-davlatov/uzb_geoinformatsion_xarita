<?php
// models/User.php
class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO users 
            (first_name, last_name, email, username, password, role, mahalla_id, profile_picture) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        $hashedPassword = Security::hashPassword($data['password']);
        
        return $stmt->execute([
            Security::sanitize($data['first_name']),
            Security::sanitize($data['last_name']),
            Security::sanitize($data['email']),
            Security::sanitize($data['username']),
            $hashedPassword,
            $data['role'],
            $data['mahalla_id'] ?? null,
            $data['profile_picture'] ?? 'default.png'
        ]);
    }
    
    public function findByUsername($username) {
        $stmt = $this->db->prepare("
            SELECT u.*, m.nomi as mahalla_nomi 
            FROM users u 
            LEFT JOIN mahallelar m ON u.mahalla_id = m.id 
            WHERE u.username = ?
        ");
        
        $stmt->execute([Security::sanitize($username)]);
        return $stmt->fetch();
    }
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([Security::sanitize($email)]);
        return $stmt->fetch();
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT u.*, m.nomi as mahalla_nomi 
            FROM users u 
            LEFT JOIN mahallelar m ON u.mahalla_id = m.id 
            WHERE u.id = ?
        ");
        
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'password' && !empty($value)) {
                $fields[] = "password = ?";
                $values[] = Security::hashPassword($value);
            } elseif ($key !== 'id') {
                $fields[] = "$key = ?";
                $values[] = Security::sanitize($value);
            }
        }
        
        $values[] = $id;
        
        $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($values);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getAll($role = null) {
        $sql = "
            SELECT u.*, m.nomi as mahalla_nomi, 
                   v.nomi as viloyat_nomi, t.nomi as tuman_nomi 
            FROM users u 
            LEFT JOIN mahallelar m ON u.mahalla_id = m.id 
            LEFT JOIN tumanlar t ON m.tuman_id = t.id 
            LEFT JOIN viloyatlar v ON t.viloyat_id = v.id 
            WHERE 1=1
        ";
        
        $params = [];
        
        if ($role) {
            $sql .= " AND u.role = ?";
            $params[] = $role;
        }
        
        $sql .= " ORDER BY u.created_at DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    public function getOperators() {
        return $this->getAll('operator');
    }
    
    public function getAdmins() {
        return $this->getAll('admin');
    }
    
    public function checkFailedAttempts($ip) {
        $stmt = $this->db->prepare("
            SELECT attempts, last_attempt 
            FROM failed_attempts 
            WHERE ip_address = ? 
            AND last_attempt > DATE_SUB(NOW(), INTERVAL 1 HOUR)
        ");
        
        $stmt->execute([$ip]);
        $result = $stmt->fetch();
        
        return $result ? $result['attempts'] : 0;
    }
    
    public function recordFailedAttempt($ip) {
        $stmt = $this->db->prepare("
            INSERT INTO failed_attempts (ip_address, attempts) 
            VALUES (?, 1) 
            ON DUPLICATE KEY UPDATE 
            attempts = attempts + 1, 
            last_attempt = NOW()
        ");
        
        return $stmt->execute([$ip]);
    }
    
    public function resetFailedAttempts($ip) {
        $stmt = $this->db->prepare("
            DELETE FROM failed_attempts 
            WHERE ip_address = ?
        ");
        
        return $stmt->execute([$ip]);
    }
}
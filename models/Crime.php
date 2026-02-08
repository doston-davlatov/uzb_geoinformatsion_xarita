<?php
// models/Crime.php
class Crime {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO crimes 
            (jk_modda, qismi, bandi, ogrilik_turi, sodir_vaqti, 
             viloyat_id, tuman_id, mahalla_id, jinoyat_matni, lat, lng) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            Security::sanitize($data['jk_modda']),
            Security::sanitize($data['qismi'] ?? ''),
            Security::sanitize($data['bandi'] ?? ''),
            Security::sanitize($data['ogrilik_turi']),
            $data['sodir_vaqti'] ?? date('Y-m-d H:i:s'),
            $data['viloyat_id'],
            $data['tuman_id'],
            $data['mahalla_id'],
            Security::sanitize($data['jinoyat_matni'] ?? ''),
            $data['lat'] ?? null,
            $data['lng'] ?? null
        ]);
    }
    
    public function getById($id) {
        $stmt = $this->db->prepare("
            SELECT c.*, 
                   v.nomi as viloyat_nomi, 
                   t.nomi as tuman_nomi,
                   m.nomi as mahalla_nomi
            FROM crimes c
            LEFT JOIN viloyatlar v ON c.viloyat_id = v.id
            LEFT JOIN tumanlar t ON c.tuman_id = t.id
            LEFT JOIN mahallelar m ON c.mahalla_id = m.id
            WHERE c.id = ?
        ");
        
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getByMahalla($mahallaId, $limit = 100) {
        $stmt = $this->db->prepare("
            SELECT c.* 
            FROM crimes c
            WHERE c.mahalla_id = ?
            ORDER BY c.sodir_vaqti DESC
            LIMIT ?
        ");
        
        $stmt->execute([$mahallaId, $limit]);
        return $stmt->fetchAll();
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            $fields[] = "$key = ?";
            $values[] = Security::sanitize($value);
        }
        
        $values[] = $id;
        
        $sql = "UPDATE crimes SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($values);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM crimes WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function getStatistics($filters = []) {
        $sql = "SELECT ";
        $params = [];
        
        // Dynamic statistic queries based on filters
        if (empty($filters)) {
            $sql .= "
                (SELECT COUNT(*) FROM crimes) as total_crimes,
                (SELECT COUNT(*) FROM crimes WHERE YEAR(sodir_vaqti) = YEAR(CURDATE())) as current_year,
                (SELECT COUNT(*) FROM crimes WHERE MONTH(sodir_vaqti) = MONTH(CURDATE())) as current_month
            ";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetch();
    }
}
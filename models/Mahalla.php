<?php
// models/Mahalla.php
class Mahalla {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance();
    }
    
    public function create($data) {
        $stmt = $this->db->prepare("
            INSERT INTO mahallelar 
            (viloyat_id, tuman_id, nomi, polygon, operator_id, markaz_lat, markaz_lng) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        
        return $stmt->execute([
            $data['viloyat_id'],
            $data['tuman_id'],
            Security::sanitize($data['nomi']),
            json_encode($data['polygon'] ?? null),
            $data['operator_id'] ?? null,
            $data['markaz_lat'] ?? null,
            $data['markaz_lng'] ?? null
        ]);
    }
    
    public function findById($id) {
        $stmt = $this->db->prepare("
            SELECT m.*, 
                   v.nomi as viloyat_nomi, 
                   t.nomi as tuman_nomi,
                   u.username as operator_username,
                   CONCAT(u.first_name, ' ', u.last_name) as operator_name
            FROM mahallelar m
            LEFT JOIN viloyatlar v ON m.viloyat_id = v.id
            LEFT JOIN tumanlar t ON m.tuman_id = t.id
            LEFT JOIN users u ON m.operator_id = u.id
            WHERE m.id = ?
        ");
        
        $stmt->execute([$id]);
        return $stmt->fetch();
    }
    
    public function getAll($filters = []) {
        $sql = "
            SELECT m.*, 
                   v.nomi as viloyat_nomi, 
                   t.nomi as tuman_nomi,
                   u.username as operator_username
            FROM mahallelar m
            LEFT JOIN viloyatlar v ON m.viloyat_id = v.id
            LEFT JOIN tumanlar t ON m.tuman_id = t.id
            LEFT JOIN users u ON m.operator_id = u.id
            WHERE 1=1
        ";
        
        $params = [];
        
        if (!empty($filters['viloyat_id'])) {
            $sql .= " AND m.viloyat_id = ?";
            $params[] = $filters['viloyat_id'];
        }
        
        if (!empty($filters['tuman_id'])) {
            $sql .= " AND m.tuman_id = ?";
            $params[] = $filters['tuman_id'];
        }
        
        if (!empty($filters['operator_id'])) {
            $sql .= " AND m.operator_id = ?";
            $params[] = $filters['operator_id'];
        }
        
        $sql .= " ORDER BY v.nomi, t.nomi, m.nomi";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        
        return $stmt->fetchAll();
    }
    
    public function update($id, $data) {
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if ($key === 'polygon') {
                $fields[] = "$key = ?";
                $values[] = json_encode($value);
            } else {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        $values[] = $id;
        
        $sql = "UPDATE mahallelar SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        return $stmt->execute($values);
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM mahallelar WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function assignOperator($mahallaId, $operatorId) {
        $stmt = $this->db->prepare("
            UPDATE mahallelar 
            SET operator_id = ? 
            WHERE id = ?
        ");
        
        return $stmt->execute([$operatorId, $mahallaId]);
    }
    
    public function getStatistics($mahallaId) {
        $stats = [];
        
        // Jinoyatlar soni
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM crimes 
            WHERE mahalla_id = ?
        ");
        $stmt->execute([$mahallaId]);
        $stats['jinoyatlar'] = $stmt->fetchColumn();
        
        // Faol nizok oilalar
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM nizokash_oilalar 
            WHERE mahalla_id = ? AND status = 'faol'
        ");
        $stmt->execute([$mahallaId]);
        $stats['nizok_oilalar'] = $stmt->fetchColumn();
        
        // Order olganlar
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM order_olganlar 
            WHERE mahalla_id = ?
        ");
        $stmt->execute([$mahallaId]);
        $stats['orderlar'] = $stmt->fetchColumn();
        
        // Jinoyat o'choqlari
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM jinoyat_ochoklari 
            WHERE mahalla_id = ?
        ");
        $stmt->execute([$mahallaId]);
        $stats['ochoklar'] = $stmt->fetchColumn();
        
        // Muammoli joylar
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM muammoli_joylar 
            WHERE mahalla_id = ?
        ");
        $stmt->execute([$mahallaId]);
        $stats['muammoli_joylar'] = $stmt->fetchColumn();
        
        // Ovloq joylar
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count 
            FROM ovloq_joylar 
            WHERE mahalla_id = ?
        ");
        $stmt->execute([$mahallaId]);
        $stats['ovloq_joylar'] = $stmt->fetchColumn();
        
        return $stats;
    }
    
    public function getCrimesByYear($mahallaId, $years = 5) {
        $currentYear = date('Y');
        $startYear = $currentYear - $years + 1;
        
        $stmt = $this->db->prepare("
            SELECT YEAR(sodir_vaqti) as yil, COUNT(*) as count 
            FROM crimes 
            WHERE mahalla_id = ? 
            AND YEAR(sodir_vaqti) BETWEEN ? AND ?
            GROUP BY YEAR(sodir_vaqti)
            ORDER BY yil
        ");
        
        $stmt->execute([$mahallaId, $startYear, $currentYear]);
        return $stmt->fetchAll();
    }
}
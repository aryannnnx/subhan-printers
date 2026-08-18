<?php
// ============================================
// SUBHAN PRINTERS - Quote Model
// ============================================

class Quote {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    public function create($data) {
        try {
            $quoteNumber = $this->generateQuoteNumber();
            
            // ✅ Added user_id column
            $sql = "INSERT INTO quotes (
                quote_number,
                user_id,
                customer_name,
                customer_email,
                customer_phone,
                customer_company,
                customer_address,
                project_type,
                quantity,
                specifications,
                deadline,
                budget,
                notes,
                source,
                status,
                created_at
            ) VALUES (
                :quote_number,
                :user_id,
                :customer_name,
                :customer_email,
                :customer_phone,
                :customer_company,
                :customer_address,
                :project_type,
                :quantity,
                :specifications,
                :deadline,
                :budget,
                :notes,
                :source,
                'pending',
                NOW()
            )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':quote_number' => $quoteNumber,
                ':user_id' => $data['user_id'] ?? null,  // ← ADDED
                ':customer_name' => $data['customer_name'],
                ':customer_email' => $data['customer_email'],
                ':customer_phone' => $data['customer_phone'],
                ':customer_company' => $data['customer_company'] ?? null,
                ':customer_address' => $data['customer_address'] ?? null,
                ':project_type' => $data['project_type'] ?? 'General',
                ':quantity' => $data['quantity'] ?? 0,
                ':specifications' => $data['specifications'] ?? null,
                ':deadline' => $data['deadline'] ?? null,
                ':budget' => $data['budget'] ?? null,
                ':notes' => $data['notes'] ?? null,
                ':source' => $data['source'] ?? 'website'
            ]);
            
            $quoteId = $this->db->lastInsertId();
            
            return [
                'success' => true,
                'quote_id' => $quoteId,
                'quote_number' => $quoteNumber
            ];
            
        } catch (PDOException $e) {
            error_log("Quote creation error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    public function getById($id) {
        try {
            $sql = "SELECT * FROM quotes WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get quote by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    public function getByQuoteNumber($quoteNumber) {
        try {
            $sql = "SELECT * FROM quotes WHERE quote_number = :quote_number";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':quote_number' => $quoteNumber]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get quote by number error: " . $e->getMessage());
            return null;
        }
    }
    
    // ✅ NEW: Get quotes by user ID (for dashboard)
    public function getByUserId($userId, $limit = 10) {
        try {
            $sql = "SELECT * FROM quotes 
                    WHERE user_id = :user_id 
                    ORDER BY created_at DESC 
                    LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':user_id' => $userId,
                ':limit' => $limit
            ]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get quotes by user ID error: " . $e->getMessage());
            return [];
        }
    }
    /**
 * Get all quotes with filters (admin)
 * 
 * @param array $filters Filter criteria
 * @param int $limit Maximum number of results
 * @param int $offset Offset for pagination
 * @return array List of quotes
 */
public function getAll($filters = [], $limit = 50, $offset = 0) {
    try {
        $sql = "SELECT * FROM quotes WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (customer_name LIKE :search OR quote_number LIKE :search OR customer_email LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        if (!empty($filters['date_from'])) {
            $sql .= " AND created_at >= :date_from";
            $params[':date_from'] = $filters['date_from'];
        }
        
        if (!empty($filters['date_to'])) {
            $sql .= " AND created_at <= :date_to";
            $params[':date_to'] = $filters['date_to'];
        }
        
        $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
        
    } catch (PDOException $e) {
        error_log("Get all quotes error: " . $e->getMessage());
        return [];
    }
}
    
    public function getByEmail($email, $limit = 10) {
        try {
            $sql = "SELECT * FROM quotes 
                    WHERE customer_email = :email 
                    ORDER BY created_at DESC 
                    LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':email' => $email,
                ':limit' => $limit
            ]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get quotes by email error: " . $e->getMessage());
            return [];
        }
    }
    /**
 * Get quote count by status
 * 
 * @param string|null $status Status filter (optional)
 * @return int Number of quotes
 */
public function getCountByStatus($status = null) {
    try {
        $sql = "SELECT COUNT(*) as count FROM quotes";
        $params = [];
        
        if ($status) {
            $sql .= " WHERE status = :status";
            $params[':status'] = $status;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return (int)$result['count'];
        
    } catch (PDOException $e) {
        error_log("Get count by status error: " . $e->getMessage());
        return 0;
    }
}
    
    private function generateQuoteNumber() {
        $date = date('ym');
        $random = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
        return 'Q' . $date . $random;
    }
}

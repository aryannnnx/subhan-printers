<?php
// ============================================
// SUBHAN PRINTERS - Newsletter Model
// ============================================

class Newsletter {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Subscribe email
     */
    public function subscribe($email, $source = 'website') {
        try {
            // Check if already subscribed
            $existing = $this->getByEmail($email);
            
            if ($existing) {
                if ($existing['status'] === 'active') {
                    return ['success' => false, 'error' => 'Already subscribed'];
                } else {
                    // Reactivate
                    $sql = "UPDATE newsletter SET 
                            status = 'active', 
                            unsubscribed_at = NULL,
                            source = :source,
                            subscribed_at = NOW()
                            WHERE email = :email";
                    $stmt = $this->db->prepare($sql);
                    $stmt->execute([
                        ':email' => $email,
                        ':source' => $source
                    ]);
                    return ['success' => true, 'message' => 'Subscribed successfully'];
                }
            }
            
            // New subscription
            $ip = $_SERVER['REMOTE_ADDR'] ?? null;
            
            $sql = "INSERT INTO newsletter (email, ip_address, source, subscribed_at) 
                    VALUES (:email, :ip, :source, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':email' => $email,
                ':ip' => $ip,
                ':source' => $source
            ]);
            
            return ['success' => true, 'message' => 'Subscribed successfully'];
            
        } catch (PDOException $e) {
            error_log("Newsletter subscription error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Unsubscribe email
     */
    public function unsubscribe($email) {
        try {
            $sql = "UPDATE newsletter SET 
                    status = 'unsubscribed', 
                    unsubscribed_at = NOW() 
                    WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Unsubscribe error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get subscriber by email
     */
    public function getByEmail($email) {
        $sql = "SELECT * FROM newsletter WHERE email = :email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch();
    }
    
    /**
     * Get all active subscribers
     */
    public function getActiveSubscribers($limit = 100, $offset = 0) {
        $sql = "SELECT * FROM newsletter 
                WHERE status = 'active' 
                ORDER BY subscribed_at DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':limit' => $limit,
            ':offset' => $offset
        ]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get all subscribers (admin)
     */
    public function getAll($filters = [], $limit = 50, $offset = 0) {
        $sql = "SELECT * FROM newsletter WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND email LIKE :search";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        $sql .= " ORDER BY subscribed_at DESC LIMIT :limit OFFSET :offset";
        $params[':limit'] = $limit;
        $params[':offset'] = $offset;
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }
    
    /**
     * ✅ NEW: Count active subscribers
     */
    public function countActive() {
        try {
            $sql = "SELECT COUNT(*) as count FROM newsletter WHERE status = 'active'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $result = $stmt->fetch();
            return (int)$result['count'];
            
        } catch (PDOException $e) {
            error_log("Count active subscribers error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get recent subscribers
     */
    public function getRecent($limit = 10) {
        $sql = "SELECT * FROM newsletter 
                WHERE status = 'active' 
                ORDER BY subscribed_at DESC 
                LIMIT :limit";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':limit' => $limit]);
        return $stmt->fetchAll();
    }
}
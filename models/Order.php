<?php
// models/Order.php
// Handles customer orders

class Order {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Create new order
     */
    public function create($data) {
        try {
            $orderNumber = $this->generateOrderNumber();
            
            $sql = "INSERT INTO orders (
                order_number, user_id, customer_name, customer_email, customer_phone,
                customer_address, customer_city, product_type, quantity, size,
                paper_type, finishing, description, custom_specs, has_design,
                design_file_url, design_notes, subtotal, tax, delivery_charges,
                total, currency, status, payment_status, delivery_method,
                notes, source
            ) VALUES (
                :order_number, :user_id, :customer_name, :customer_email, :customer_phone,
                :customer_address, :customer_city, :product_type, :quantity, :size,
                :paper_type, :finishing, :description, :custom_specs, :has_design,
                :design_file_url, :design_notes, :subtotal, :tax, :delivery_charges,
                :total, :currency, :status, :payment_status, :delivery_method,
                :notes, :source
            )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':order_number' => $orderNumber,
                ':user_id' => $data['user_id'] ?? null,
                ':customer_name' => $data['customer_name'],
                ':customer_email' => $data['customer_email'],
                ':customer_phone' => $data['customer_phone'],
                ':customer_address' => $data['customer_address'] ?? null,
                ':customer_city' => $data['customer_city'] ?? null,
                ':product_type' => $data['product_type'],
                ':quantity' => $data['quantity'],
                ':size' => $data['size'] ?? null,
                ':paper_type' => $data['paper_type'] ?? null,
                ':finishing' => isset($data['finishing']) ? json_encode($data['finishing']) : null,
                ':description' => $data['description'] ?? null,
                ':custom_specs' => isset($data['custom_specs']) ? json_encode($data['custom_specs']) : null,
                ':has_design' => $data['has_design'] ?? 0,
                ':design_file_url' => $data['design_file_url'] ?? null,
                ':design_notes' => $data['design_notes'] ?? null,
                ':subtotal' => $data['subtotal'] ?? 0,
                ':tax' => $data['tax'] ?? 0,
                ':delivery_charges' => $data['delivery_charges'] ?? 0,
                ':total' => $data['total'] ?? 0,
                ':currency' => $data['currency'] ?? 'PKR',
                ':status' => $data['status'] ?? 'pending',
                ':payment_status' => $data['payment_status'] ?? 'unpaid',
                ':delivery_method' => $data['delivery_method'] ?? null,
                ':notes' => $data['notes'] ?? null,
                ':source' => $data['source'] ?? 'website'
            ]);
            
            $orderId = $this->db->lastInsertId();
            
            // Add to timeline
            $this->addTimelineEntry($orderId, 'pending', 'Order created via website');
            
            return [
                'success' => true,
                'order_id' => $orderId,
                'order_number' => $orderNumber
            ];
            
        } catch (PDOException $e) {
            error_log("Order creation error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get order by ID
     */
    public function getById($id) {
        $sql = "SELECT * FROM orders WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }
    
    /**
     * Get order by order number
     */
    public function getByOrderNumber($orderNumber) {
        $sql = "SELECT * FROM orders WHERE order_number = :order_number";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_number' => $orderNumber]);
        return $stmt->fetch();
    }
    
    /**
     * Get orders for a user
     */
    public function getByUser($userId, $limit = 50, $offset = 0) {
        $sql = "SELECT * FROM orders 
                WHERE user_id = :user_id 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':user_id' => $userId,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get orders by email (for guest users)
     */
    public function getByEmail($email, $limit = 50, $offset = 0) {
        $sql = "SELECT * FROM orders 
                WHERE customer_email = :email 
                ORDER BY created_at DESC 
                LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            ':email' => $email,
            ':limit' => $limit,
            ':offset' => $offset
        ]);
        return $stmt->fetchAll();
    }
    
    /**
     * 
     * Get all orders with filters (admin)
     */
    public function getAll($filters = [], $limit = 50, $offset = 0) {
        $sql = "SELECT * FROM orders WHERE 1=1";
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        
        if (!empty($filters['payment_status'])) {
            $sql .= " AND payment_status = :payment_status";
            $params[':payment_status'] = $filters['payment_status'];
        }
        
        if (!empty($filters['email'])) {
            $sql .= " AND customer_email = :email";
            $params[':email'] = $filters['email'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (customer_name LIKE :search OR order_number LIKE :search OR customer_email LIKE :search)";
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
    }
    
    /**
     * Update order
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            $allowedFields = [
                'status', 'payment_status', 'notes', 'tracking_number',
                'estimated_delivery', 'delivered_at', 'customer_address',
                'customer_city', 'customer_phone', 'customer_name'
            ];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $fields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            if (empty($fields)) {
                return ['success' => false, 'error' => 'No fields to update'];
            }
            
            $sql = "UPDATE orders SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Order update error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update order status with timeline entry
     */
    public function updateStatus($id, $status, $note = null) {
        try {
            // Get current status
            $current = $this->getById($id);
            if (!$current) {
                return ['success' => false, 'error' => 'Order not found'];
            }
            
            $oldStatus = $current['status'];
            
            // Update status
            $sql = "UPDATE orders SET status = :status WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':status' => $status, ':id' => $id]);
            
            // Add timeline entry
            $this->addTimelineEntry($id, $status, $note);
            
            return [
                'success' => true,
                'old_status' => $oldStatus,
                'new_status' => $status
            ];
            
        } catch (PDOException $e) {
            error_log("Order status update error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Add timeline entry
     */
    public function addTimelineEntry($orderId, $status, $note = null) {
        try {
            $sql = "INSERT INTO order_timeline (order_id, status, note) 
                    VALUES (:order_id, :status, :note)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':order_id' => $orderId,
                ':status' => $status,
                ':note' => $note
            ]);
            return true;
        } catch (PDOException $e) {
            error_log("Timeline error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get order timeline
     */
    public function getTimeline($orderId) {
        $sql = "SELECT * FROM order_timeline 
                WHERE order_id = :order_id 
                ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':order_id' => $orderId]);
        return $stmt->fetchAll();
    }
    
    /**
     * Get order count by status
     */
  /**
 * Get order count by status
 * 
 * @param string|null $status Status filter (optional)
 * @return int Number of orders
 */
public function getCountByStatus($status = null) {
    try {
        $sql = "SELECT COUNT(*) as count FROM orders";
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
    
    /**
     * Get total revenue
     */
    public function getTotalRevenue() {
        $sql = "SELECT SUM(total) as total FROM orders WHERE status != 'cancelled' AND status != 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch()['total'] ?? 0;
    }
    
    
    /**
     * Generate order number
     */
    private function generateOrderNumber() {
        $date = date('ymd');
        $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
        return 'SP' . $date . $random;
    }
}
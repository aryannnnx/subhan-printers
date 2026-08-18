<?php
/**
 * Service Model
 * Handles all service-related database operations
 */

require_once __DIR__ . '/../config/database.php';

class Service {
    private $db;
    private $conn;
    
    public function __construct() {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }
    
    /**
     * Get all active services
     */
    public function getActiveServices() {
        try {
            $stmt = $this->conn->prepare("
                SELECT s.*, c.label as category_label, c.color as category_color, c.icon as category_icon
                FROM services s
                LEFT JOIN service_categories c ON s.category = c.slug
                WHERE s.is_active = 1
                ORDER BY c.sort_order, s.sort_order
            ");
            $stmt->execute();
            $services = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Decode features JSON
            foreach ($services as &$service) {
                $service['features'] = !empty($service['features']) 
                    ? json_decode($service['features'], true) 
                    : [];
            }
            
            return $services;
        } catch (PDOException $e) {
            error_log("Error fetching services: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get service by ID
     */
    public function getServiceById($id) {
        try {
            $stmt = $this->conn->prepare("
                SELECT s.*, c.label as category_label
                FROM services s
                LEFT JOIN service_categories c ON s.category = c.slug
                WHERE s.id = ?
            ");
            $stmt->execute([$id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($service) {
                $service['features'] = !empty($service['features']) 
                    ? json_decode($service['features'], true) 
                    : [];
            }
            
            return $service;
        } catch (PDOException $e) {
            error_log("Error fetching service: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get all categories
     */
    public function getCategories() {
        try {
            $stmt = $this->conn->prepare("
                SELECT * FROM service_categories 
                WHERE is_active = 1 
                ORDER BY sort_order
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Error fetching categories: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Add new service
     */
    public function addService($data) {
        try {
            $stmt = $this->conn->prepare("
                INSERT INTO services (
                    name, description, price, price_text, badge, 
                    image, category, features, sort_order, is_active
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            
            $features = isset($data['features']) ? json_encode($data['features']) : '[]';
            
            return $stmt->execute([
                $data['name'],
                $data['description'] ?? '',
                $data['price'] ?? '',
                $data['price_text'] ?? '',
                $data['badge'] ?? '',
                $data['image'] ?? '',
                $data['category'],
                $features,
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? 1
            ]);
        } catch (PDOException $e) {
            error_log("Error adding service: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update service
     */
    public function updateService($id, $data) {
        try {
            $stmt = $this->conn->prepare("
                UPDATE services 
                SET name = ?, description = ?, price = ?, price_text = ?, 
                    badge = ?, image = ?, category = ?, features = ?, 
                    sort_order = ?, is_active = ?
                WHERE id = ?
            ");
            
            $features = isset($data['features']) ? json_encode($data['features']) : '[]';
            
            return $stmt->execute([
                $data['name'],
                $data['description'] ?? '',
                $data['price'] ?? '',
                $data['price_text'] ?? '',
                $data['badge'] ?? '',
                $data['image'] ?? '',
                $data['category'],
                $features,
                $data['sort_order'] ?? 0,
                $data['is_active'] ?? 1,
                $id
            ]);
        } catch (PDOException $e) {
            error_log("Error updating service: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete service
     */
    public function deleteService($id) {
        try {
            // Get image path first
            $stmt = $this->conn->prepare("SELECT image FROM services WHERE id = ?");
            $stmt->execute([$id]);
            $service = $stmt->fetch(PDO::FETCH_ASSOC);
            
            // Delete image file if exists
            if ($service && !empty($service['image'])) {
                $imagePath = $_SERVER['DOCUMENT_ROOT'] . '/' . $service['image'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            // Delete service
            $stmt = $this->conn->prepare("DELETE FROM services WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Error deleting service: " . $e->getMessage());
            return false;
        }
    }
}
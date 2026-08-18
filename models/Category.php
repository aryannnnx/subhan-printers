<?php
// models/Category.php

class Category {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Get all categories
     */
    public function getAll($activeOnly = false) {
        try {
            $sql = "SELECT * FROM categories";
            if ($activeOnly) {
                $sql .= " WHERE is_active = 1";
            }
            $sql .= " ORDER BY display_order ASC, name ASC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get categories error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get category by ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM categories WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get category error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get category by slug
     */
    public function getBySlug($slug) {
        try {
            $sql = "SELECT * FROM categories WHERE slug = :slug";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':slug' => $slug]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get category by slug error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create new category
     */
    public function create($data) {
        try {
            $slug = $this->generateSlug($data['name']);
            
            // Check if slug exists
            $existing = $this->getBySlug($slug);
            if ($existing) {
                $slug = $slug . '-' . time();
            }
            
            $sql = "INSERT INTO categories (name, slug, icon, description, display_order, is_active) 
                    VALUES (:name, :slug, :icon, :description, :display_order, :is_active)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':slug' => $slug,
                ':icon' => $data['icon'] ?? null,
                ':description' => $data['description'] ?? null,
                ':display_order' => $data['display_order'] ?? 0,
                ':is_active' => $data['is_active'] ?? 1
            ]);
            
            return ['success' => true, 'id' => $this->db->lastInsertId()];
            
        } catch (PDOException $e) {
            error_log("Category creation error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update category
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            $allowedFields = ['name', 'icon', 'description', 'display_order', 'is_active'];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $fields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            // Update slug if name changed
            if (isset($data['name'])) {
                $slug = $this->generateSlug($data['name']);
                $fields[] = "slug = :slug";
                $params[':slug'] = $slug;
            }
            
            if (empty($fields)) {
                return ['success' => false, 'error' => 'No fields to update'];
            }
            
            $sql = "UPDATE categories SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Category update error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Delete category (soft delete)
     */
    public function delete($id) {
        try {
            $sql = "UPDATE categories SET is_active = 0 WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Hard delete category
     */
    public function deletePermanent($id) {
        try {
            // Check if category is in use before deleting
            if ($this->isInUse($id)) {
                return ['success' => false, 'error' => 'Category is in use and cannot be deleted'];
            }
            
            $sql = "DELETE FROM categories WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Check if category is in use
     */
    public function isInUse($id) {
        try {
            // Get category slug
            $stmt = $this->db->prepare("SELECT slug FROM categories WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $category = $stmt->fetch();
            
            if (!$category) {
                return false;
            }
            
            $slug = $category['slug'];
            
            // Check in products table
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM products WHERE category = :slug AND is_active = 1");
            $stmt->execute([':slug' => $slug]);
            $productCount = $stmt->fetch()['count'] ?? 0;
            
            // Check in portfolio table
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM portfolio WHERE category = :slug AND is_active = 1");
            $stmt->execute([':slug' => $slug]);
            $portfolioCount = $stmt->fetch()['count'] ?? 0;
            
            return ($productCount + $portfolioCount) > 0;
            
        } catch (PDOException $e) {
            error_log("Check category in use error: " . $e->getMessage());
            return true; // Return true to be safe
        }
    }
    
    /**
     * Get usage count for category
     */
    public function getUsageCount($id) {
        try {
            $stmt = $this->db->prepare("SELECT slug FROM categories WHERE id = :id");
            $stmt->execute([':id' => $id]);
            $category = $stmt->fetch();
            
            if (!$category) {
                return 0;
            }
            
            $slug = $category['slug'];
            
            // Count in products
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM products WHERE category = :slug AND is_active = 1");
            $stmt->execute([':slug' => $slug]);
            $productCount = $stmt->fetch()['count'] ?? 0;
            
            // Count in portfolio
            $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM portfolio WHERE category = :slug AND is_active = 1");
            $stmt->execute([':slug' => $slug]);
            $portfolioCount = $stmt->fetch()['count'] ?? 0;
            
            return [
                'products' => $productCount,
                'portfolio' => $portfolioCount,
                'total' => $productCount + $portfolioCount
            ];
            
        } catch (PDOException $e) {
            error_log("Get usage count error: " . $e->getMessage());
            return ['products' => 0, 'portfolio' => 0, 'total' => 0];
        }
    }
    
    /**
     * Get categories as array for dropdown
     */
    public function getDropdown() {
        $categories = $this->getAll(true);
        $dropdown = [];
        foreach ($categories as $cat) {
            $dropdown[$cat['slug']] = $cat['name'];
        }
        return $dropdown;
    }
    
    /**
     * Generate slug from name
     */
    private function generateSlug($name) {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
    
    /**
     * Count products in category
     */
    public function countProducts($categoryId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM products WHERE category = (SELECT slug FROM categories WHERE id = :id) AND is_active = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $categoryId]);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            return 0;
        }
    }
}
?>
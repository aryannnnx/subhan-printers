<?php
// ============================================
// SUBHAN PRINTERS - Portfolio Model
// ============================================

class Portfolio {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Get all portfolio items with images
     */
    public function getAll($filters = []) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM portfolio p
                    LEFT JOIN categories c ON p.category = c.slug
                    WHERE p.is_active = 1";
            $params = [];
            
            if (!empty($filters['category'])) {
                $sql .= " AND p.category = :category";
                $params[':category'] = $filters['category'];
            }
            
            if (!empty($filters['featured'])) {
                $sql .= " AND p.featured = 1";
            }
            
            $sql .= " ORDER BY p.featured DESC, p.id DESC";
            
            if (!empty($filters['limit'])) {
                $sql .= " LIMIT :limit";
                $params[':limit'] = (int)$filters['limit'];
            }
            
            $stmt = $this->db->prepare($sql);
            
            foreach ($params as $key => &$value) {
                if ($key === ':limit') {
                    $stmt->bindParam($key, $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindParam($key, $value);
                }
            }
            
            $stmt->execute();
            $items = $stmt->fetchAll();
            
            // Fetch images for each item
            foreach ($items as &$item) {
                $item['images'] = $this->getImages($item['id']);
                $item['primary_image'] = $this->getPrimaryImage($item['id']);
            }
            
            return $items;
            
        } catch (PDOException $e) {
            error_log("Get portfolio error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get portfolio item by ID
     */

    /**
 * Count items by category
 * 
 * @param string $category Category slug
 * @return int Number of items in this category
 */
public function countByCategory($category) {
    try {
        $sql = "SELECT COUNT(*) as count FROM portfolio WHERE category = :category AND is_active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':category' => $category]);
        $result = $stmt->fetch();
        return $result['count'] ?? 0;
    } catch (PDOException $e) {
        error_log("Count by category error: " . $e->getMessage());
        return 0;
    }
}
    public function getById($id) {
        try {
            $sql = "SELECT p.*, c.name as category_name 
                    FROM portfolio p
                    LEFT JOIN categories c ON p.category = c.slug
                    WHERE p.id = :id AND p.is_active = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $item = $stmt->fetch();
            
            if ($item) {
                $item['images'] = $this->getImages($id);
                $item['primary_image'] = $this->getPrimaryImage($id);
            }
            
            return $item;
        } catch (PDOException $e) {
            error_log("Get portfolio by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get images for a portfolio item
     */
    public function getImages($portfolioId) {
        try {
            $sql = "SELECT * FROM portfolio_images 
                    WHERE portfolio_id = :portfolio_id 
                    ORDER BY is_primary DESC, display_order ASC, id ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':portfolio_id' => $portfolioId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get images error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get primary image for a portfolio item
     */
    public function getPrimaryImage($portfolioId) {
        try {
            $sql = "SELECT * FROM portfolio_images 
                    WHERE portfolio_id = :portfolio_id AND is_primary = 1 
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':portfolio_id' => $portfolioId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get primary image error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Create new portfolio item
     */
    public function create($data) {
        try {
            $slug = $this->generateSlug($data['title']);
            
            // Check if slug exists
            $existing = $this->getBySlug($slug);
            if ($existing) {
                $slug = $slug . '-' . time();
            }
            
            $sql = "INSERT INTO portfolio (
                title, slug, subtitle, category, description, client_name, 
                tags, featured, is_active, display_order, created_at
            ) VALUES (
                :title, :slug, :subtitle, :category, :description, :client_name,
                :tags, :featured, :is_active, :display_order, NOW()
            )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':title' => $data['title'],
                ':slug' => $slug,
                ':subtitle' => $data['subtitle'] ?? null,
                ':category' => $data['category'] ?? 'general',
                ':description' => $data['description'] ?? null,
                ':client_name' => $data['client_name'] ?? null,
                ':tags' => isset($data['tags']) ? json_encode($data['tags']) : null,
                ':featured' => $data['featured'] ?? 1,  // Default: show on homepage
                ':is_active' => $data['is_active'] ?? 1,
                ':display_order' => $data['display_order'] ?? 0
            ]);
            
            return ['success' => true, 'id' => $this->db->lastInsertId()];
            
        } catch (PDOException $e) {
            error_log("Portfolio creation error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update portfolio item
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            $allowedFields = [
                'title', 'subtitle', 'category', 'description', 'client_name',
                'tags', 'featured', 'is_active', 'display_order'
            ];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    if ($key === 'tags' && is_array($value)) {
                        $value = json_encode($value);
                    }
                    $fields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            if (isset($data['title'])) {
                $slug = $this->generateSlug($data['title']);
                $fields[] = "slug = :slug";
                $params[':slug'] = $slug;
            }
            
            $fields[] = "updated_at = NOW()";
            
            if (empty($fields)) {
                return ['success' => false, 'error' => 'No fields to update'];
            }
            
            $sql = "UPDATE portfolio SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Portfolio update error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Add image to portfolio item
     */
    public function addImage($portfolioId, $url, $caption = null, $isPrimary = false) {
        try {
            if ($isPrimary) {
                $sql = "UPDATE portfolio_images SET is_primary = 0 WHERE portfolio_id = :portfolio_id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':portfolio_id' => $portfolioId]);
            }
            
            $sql = "INSERT INTO portfolio_images (portfolio_id, url, alt_text, is_primary, display_order) 
                    VALUES (:portfolio_id, :url, :alt_text, :is_primary, 0)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':portfolio_id' => $portfolioId,
                ':url' => $url,
                ':alt_text' => $caption,
                ':is_primary' => $isPrimary ? 1 : 0
            ]);
            
            return ['success' => true, 'id' => $this->db->lastInsertId()];
            
        } catch (PDOException $e) {
            error_log("Add image error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Delete portfolio item (soft delete)
     */
    public function delete($id) {
        try {
            $sql = "UPDATE portfolio SET is_active = 0 WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Delete portfolio error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Delete image from portfolio
     */
    public function deleteImage($imageId) {
        try {
            $sql = "SELECT * FROM portfolio_images WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $imageId]);
            $image = $stmt->fetch();
            
            if ($image) {
                $filePath = __DIR__ . '/../' . ltrim($image['url'], '/');
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
                
                $sql = "DELETE FROM portfolio_images WHERE id = :id";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':id' => $imageId]);
            }
            
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Delete image error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get portfolio by slug
     */
    public function getBySlug($slug) {
        try {
            $sql = "SELECT * FROM portfolio WHERE slug = :slug AND is_active = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':slug' => $slug]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Get by slug error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get categories with counts
     */
    public function getCategories() {
        try {
            $sql = "SELECT category, COUNT(*) as count 
                    FROM portfolio 
                    WHERE is_active = 1 
                    GROUP BY category";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get categories error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Generate slug from title
     */
    private function generateSlug($title) {
        $slug = strtolower($title);
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        return $slug;
    }
}
?>
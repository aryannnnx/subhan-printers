<?php
// ============================================
// SUBHAN PRINTERS - Product Model
// Handles products AND services
// ============================================

class Product {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Get all products/services with optional filters
     * 
     * @param array $filters Filter criteria (category, featured, limit)
     * @return array List of products
     */
    public function getAll($filters = []) {
        try {
            $sql = "SELECT * FROM products WHERE is_active = 1";
            $params = [];
            
            if (!empty($filters['category'])) {
                $sql .= " AND category = :category";
                $params[':category'] = $filters['category'];
            }
            
            if (!empty($filters['featured'])) {
                $sql .= " AND featured = 1";
            }
            
            if (!empty($filters['search'])) {
                $sql .= " AND (name LIKE :search OR description LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            $sql .= " ORDER BY display_order ASC, id ASC";
            
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
            $products = $stmt->fetchAll();
            
            // Process images - add fallback if image_url is empty
            foreach ($products as &$product) {
                if (empty($product['image_url'])) {
                    $product['image_url'] = 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
                }
            }
            
            return $products;
            
        } catch (PDOException $e) {
            error_log("Get all products error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get product by ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM products WHERE id = :id AND is_active = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $product = $stmt->fetch();
            
            if ($product && empty($product['image_url'])) {
                $product['image_url'] = 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
            }
            
            return $product;
        } catch (PDOException $e) {
            error_log("Get product by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get product by slug
     */
    public function getBySlug($slug) {
        try {
            $sql = "SELECT * FROM products WHERE slug = :slug AND is_active = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':slug' => $slug]);
            $product = $stmt->fetch();
            
            if ($product && empty($product['image_url'])) {
                $product['image_url'] = 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
            }
            
            return $product;
        } catch (PDOException $e) {
            error_log("Get product by slug error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get products for pricing table
     */
    public function getPricingTable() {
        try {
            $sql = "SELECT 
                        id,
                        name,
                        slug,
                        starting_price,
                        price_text,
                        min_quantity,
                        turnaround,
                        category,
                        image_url
                    FROM products 
                    WHERE is_active = 1 AND show_in_pricing = 1
                    ORDER BY display_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $products = $stmt->fetchAll();
            
            foreach ($products as &$product) {
                if (empty($product['image_url'])) {
                    $product['image_url'] = 'https://placehold.co/300x180/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
                }
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Get pricing table error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get product categories with counts
     */
    public function getCategories() {
        try {
            $sql = "SELECT category, COUNT(*) as count 
                    FROM products 
                    WHERE is_active = 1 
                    GROUP BY category 
                    ORDER BY category";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get categories error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get featured products
     */
    public function getFeatured($limit = 8) {
        return $this->getAll(['featured' => true, 'limit' => $limit]);
    }
    
    /**
     * Get products by category
     */
    public function getByCategory($category, $limit = 20) {
        return $this->getAll(['category' => $category, 'limit' => $limit]);
    }
    
    /**
     * Create new product (admin)
     */
    public function create($data) {
        try {
            $slug = $this->generateSlug($data['name']);
            
            $sql = "INSERT INTO products (
                name, slug, description, icon, starting_price, price_text,
                min_quantity, turnaround, category, features, badge,
                image_url, is_active, display_order, show_in_pricing, created_at
            ) VALUES (
                :name, :slug, :description, :icon, :starting_price, :price_text,
                :min_quantity, :turnaround, :category, :features, :badge,
                :image_url, :is_active, :display_order, :show_in_pricing, NOW()
            )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'],
                ':slug' => $slug,
                ':description' => $data['description'] ?? null,
                ':icon' => $data['icon'] ?? null,
                ':starting_price' => $data['starting_price'] ?? 0,
                ':price_text' => $data['price_text'] ?? null,
                ':min_quantity' => $data['min_quantity'] ?? null,
                ':turnaround' => $data['turnaround'] ?? null,
                ':category' => $data['category'] ?? 'general',
                ':features' => isset($data['features']) ? json_encode($data['features']) : null,
                ':badge' => $data['badge'] ?? null,
                ':image_url' => $data['image_url'] ?? null,
                ':is_active' => $data['is_active'] ?? 1,
                ':display_order' => $data['display_order'] ?? 0,
                ':show_in_pricing' => $data['show_in_pricing'] ?? 1
            ]);
            
            return ['success' => true, 'id' => $this->db->lastInsertId()];
            
        } catch (PDOException $e) {
            error_log("Product creation error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update product (admin)
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            $allowedFields = [
                'name', 'description', 'icon', 'starting_price', 'price_text',
                'min_quantity', 'turnaround', 'category', 'features', 'badge',
                'image_url', 'is_active', 'display_order', 'show_in_pricing'
            ];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $fields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            // Update slug if name changed
            if (isset($data['name'])) {
                $fields[] = "slug = :slug";
                $params[':slug'] = $this->generateSlug($data['name']);
            }
            
            // Add updated_at
            $fields[] = "updated_at = NOW()";
            
            if (empty($fields)) {
                return ['success' => false, 'error' => 'No fields to update'];
            }
            
            $sql = "UPDATE products SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Product update error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update product image only
     */
    public function updateImage($id, $imageUrl) {
        try {
            $sql = "UPDATE products SET image_url = :image_url, updated_at = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':image_url' => $imageUrl,
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Update image error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Delete product (soft delete)
     */
    public function delete($id) {
        try {
            $product = $this->getById($id);
            if ($product && !empty($product['image_url']) && strpos($product['image_url'], 'placehold.co') === false) {
                $imagePath = __DIR__ . '/../' . $product['image_url'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $sql = "UPDATE products SET is_active = 0 WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Delete product error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Hard delete product (permanent)
     */
    public function deletePermanent($id) {
        try {
            $product = $this->getById($id);
            if ($product && !empty($product['image_url']) && strpos($product['image_url'], 'placehold.co') === false) {
                $imagePath = __DIR__ . '/../' . $product['image_url'];
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            $sql = "DELETE FROM products WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Permanent delete error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Generate slug from name
     */
    private function generateSlug($name) {
        $slug = strtolower($name);
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Check if slug exists, append number if needed
        $sql = "SELECT COUNT(*) as count FROM products WHERE slug = :slug";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':slug' => $slug]);
        $count = $stmt->fetch()['count'] ?? 0;
        
        if ($count > 0) {
            $sql = "SELECT COUNT(*) as count FROM products WHERE slug LIKE :slug_pattern";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':slug_pattern' => $slug . '%']);
            $count = $stmt->fetch()['count'] ?? 0;
            $slug = $slug . '-' . ($count + 1);
        }
        
        return $slug;
    }
    
    /**
     * Get product count
     */
    public function getCount($filters = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM products WHERE is_active = 1";
            $params = [];
            
            if (!empty($filters['category'])) {
                $sql .= " AND category = :category";
                $params[':category'] = $filters['category'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
        } catch (PDOException $e) {
            error_log("Get count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Get all categories list (for admin dropdown)
     */
    public function getAllCategories() {
        return [
            'wedding' => 'Wedding Cards',
            'packaging' => 'Packaging',
            'flex' => 'Flex & Banners',
            'brochures' => 'Brochures & Flyers',
            'stickers' => 'Stickers & Labels',
            'design' => 'Logo & Brand Design',
            'stationery' => 'Stationery',
            'posters' => 'Posters & Prints',
            'business-cards' => 'Business Cards',
            'menus' => 'Restaurant Menus',
            'corrugated' => 'Corrugated Boxes',
            'offset' => 'Offset Printing',
            'general' => 'General'
        ];
    }
    
    /**
     * Get product with image fallback
     */
    public function getWithImage($id) {
        $product = $this->getById($id);
        if ($product && empty($product['image_url'])) {
            $product['image_url'] = 'https://placehold.co/300x180/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
        }
        return $product;
    }
    
    /**
     * Toggle featured status
     */
    public function toggleFeatured($id) {
        try {
            $product = $this->getById($id);
            if (!$product) {
                return ['success' => false, 'error' => 'Product not found'];
            }
            
            $newStatus = $product['featured'] ? 0 : 1;
            $sql = "UPDATE products SET featured = :featured WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':featured' => $newStatus,
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Toggle featured error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get products by multiple IDs
     */
    public function getByIds($ids) {
        try {
            if (empty($ids)) {
                return [];
            }
            
            $placeholders = implode(',', array_fill(0, count($ids), '?'));
            $sql = "SELECT * FROM products WHERE id IN ($placeholders) AND is_active = 1 ORDER BY FIELD(id, $placeholders)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute(array_merge($ids, $ids));
            $products = $stmt->fetchAll();
            
            foreach ($products as &$product) {
                if (empty($product['image_url'])) {
                    $product['image_url'] = 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
                }
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Get products by IDs error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get random products
     */
    public function getRandom($limit = 4) {
        try {
            $sql = "SELECT * FROM products WHERE is_active = 1 ORDER BY RAND() LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll();
            
            foreach ($products as &$product) {
                if (empty($product['image_url'])) {
                    $product['image_url'] = 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
                }
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Get random products error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get products by price range
     */
    public function getByPriceRange($min, $max, $limit = 20) {
        try {
            $sql = "SELECT * FROM products 
                    WHERE is_active = 1 
                    AND starting_price BETWEEN :min AND :max 
                    ORDER BY starting_price ASC 
                    LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':min', $min);
            $stmt->bindParam(':max', $max);
            $stmt->bindParam(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $products = $stmt->fetchAll();
            
            foreach ($products as &$product) {
                if (empty($product['image_url'])) {
                    $product['image_url'] = 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
                }
            }
            
            return $products;
        } catch (PDOException $e) {
            error_log("Get products by price range error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get product statistics
     */
    public function getStats() {
        try {
            $stats = [];
            
            // Total products
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM products WHERE is_active = 1");
            $stats['total'] = $stmt->fetch()['total'] ?? 0;
            
            // Featured count
            $stmt = $this->db->query("SELECT COUNT(*) as featured FROM products WHERE is_active = 1 AND featured = 1");
            $stats['featured'] = $stmt->fetch()['featured'] ?? 0;
            
            // Categories count
            $stmt = $this->db->query("SELECT COUNT(DISTINCT category) as categories FROM products WHERE is_active = 1 AND category IS NOT NULL");
            $stats['categories'] = $stmt->fetch()['categories'] ?? 0;
            
            // Price range
            $stmt = $this->db->query("SELECT MIN(starting_price) as min_price, MAX(starting_price) as max_price FROM products WHERE is_active = 1");
            $priceRange = $stmt->fetch();
            $stats['min_price'] = $priceRange['min_price'] ?? 0;
            $stats['max_price'] = $priceRange['max_price'] ?? 0;
            
            return $stats;
        } catch (PDOException $e) {
            error_log("Get stats error: " . $e->getMessage());
            return [];
        }
    }
}
?>
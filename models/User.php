<?php
// ============================================
// SUBHAN PRINTERS - User Model
// ============================================

class User {
    private $db;
    
    public function __construct() {
        $this->db = getDB();
    }
    
    /**
     * Get user by email
     */
    public function getByEmail($email) {
        try {
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if ($user) {
                unset($user['password_hash']);
            }
            return $user;
        } catch (PDOException $e) {
            error_log("Get user by email error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Get user by ID
     */
    public function getById($id) {
        try {
            $sql = "SELECT * FROM users WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $user = $stmt->fetch();
            if ($user) {
                unset($user['password_hash']);
            }
            return $user;
        } catch (PDOException $e) {
            error_log("Get user by ID error: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Login user with email and password
     */
    public function login($email, $password) {
        try {
            // Get user with password hash
            $sql = "SELECT * FROM users WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $user = $stmt->fetch();
            
            if (!$user) {
                return ['success' => false, 'error' => 'User not found'];
            }
            
            if ($user['is_active'] != 1) {
                return ['success' => false, 'error' => 'Account is deactivated'];
            }
            
            // Check if user has password (for Google users, password might be null)
            if (empty($user['password_hash'])) {
                return ['success' => false, 'error' => 'This account uses Google Sign-In. Please use Google to login.'];
            }
            
            if (!password_verify($password, $user['password_hash'])) {
                return ['success' => false, 'error' => 'Invalid password'];
            }
            
            $this->updateLoginTime($user['id']);
            unset($user['password_hash']);
            return ['success' => true, 'user' => $user];
            
        } catch (Exception $e) {
            return ['success' => false, 'error' => 'Login failed: ' . $e->getMessage()];
        }
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email) {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':email' => $email]);
            $result = $stmt->fetch();
            return $result['count'] > 0;
        } catch (PDOException $e) {
            error_log("Email exists error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Create a new user
     */
    public function create($data) {
        try {
            // Handle password - can be null for Google/Firebase users
            $passwordHash = null;
            if (!empty($data['password'])) {
                $passwordHash = password_hash($data['password'], PASSWORD_DEFAULT);
            }
            
            $sql = "INSERT INTO users (
                name, email, phone, password_hash, avatar, role, is_active, created_at, last_login
            ) VALUES (
                :name, :email, :phone, :password_hash, :avatar, :role, :is_active, NOW(), NOW()
            )";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':name' => $data['name'] ?? 'User',
                ':email' => $data['email'],
                ':phone' => $data['phone'] ?? null,
                ':password_hash' => $passwordHash,
                ':avatar' => $data['avatar'] ?? null,
                ':role' => $data['role'] ?? 'customer',
                ':is_active' => $data['is_active'] ?? 1
            ]);
            
            $id = $this->db->lastInsertId();
            
            // Update login time for new user
            $this->updateLoginTime($id);
            
            return ['success' => true, 'id' => $id];
            
        } catch (PDOException $e) {
            error_log("User creation error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update user login time
     */
    public function updateLoginTime($id) {
        try {
            $sql = "UPDATE users SET last_login = NOW() WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return true;
        } catch (PDOException $e) {
            error_log("Update login time error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user profile
     */
    public function update($id, $data) {
        try {
            $fields = [];
            $params = [':id' => $id];
            
            $allowedFields = ['name', 'phone', 'avatar', 'role', 'is_active'];
            
            foreach ($data as $key => $value) {
                if (in_array($key, $allowedFields)) {
                    $fields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            if (empty($fields)) {
                return ['success' => false, 'error' => 'No fields to update'];
            }
            
            $sql = "UPDATE users SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("User update error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Update user password
     */
    public function updatePassword($id, $newPassword) {
        try {
            $hashed = password_hash($newPassword, PASSWORD_DEFAULT);
            
            $sql = "UPDATE users SET password_hash = :hash WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':hash' => $hashed,
                ':id' => $id
            ]);
            
            return ['success' => true];
            
        } catch (PDOException $e) {
            error_log("Password update error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Check if user has password set (for Google users)
     */
    public function hasPassword($id) {
        try {
            $sql = "SELECT password_hash FROM users WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();
            return !empty($result['password_hash']);
        } catch (PDOException $e) {
            error_log("Check password error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update user avatar
     */
    public function updateAvatar($id, $avatarUrl) {
        try {
            $sql = "UPDATE users SET avatar = :avatar WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':avatar' => $avatarUrl,
                ':id' => $id
            ]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Update avatar error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get all users (admin)
     */
    public function getAll($filters = [], $limit = 50, $offset = 0) {
        try {
            $sql = "SELECT id, name, email, phone, role, is_active, created_at, last_login, avatar 
                    FROM users WHERE 1=1";
            $params = [];
            
            if (!empty($filters['role'])) {
                $sql .= " AND role = :role";
                $params[':role'] = $filters['role'];
            }
            
            if (!empty($filters['search'])) {
                $sql .= " AND (name LIKE :search OR email LIKE :search)";
                $params[':search'] = '%' . $filters['search'] . '%';
            }
            
            if (isset($filters['is_active'])) {
                $sql .= " AND is_active = :is_active";
                $params[':is_active'] = $filters['is_active'];
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
            $params[':limit'] = $limit;
            $params[':offset'] = $offset;
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
            
        } catch (PDOException $e) {
            error_log("Get all users error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Get user count
     */
    public function count($filters = []) {
        try {
            $sql = "SELECT COUNT(*) as count FROM users WHERE 1=1";
            $params = [];
            
            if (!empty($filters['role'])) {
                $sql .= " AND role = :role";
                $params[':role'] = $filters['role'];
            }
            
            if (isset($filters['is_active'])) {
                $sql .= " AND is_active = :is_active";
                $params[':is_active'] = $filters['is_active'];
            }
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result['count'] ?? 0;
            
        } catch (PDOException $e) {
            error_log("Count users error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Delete user (soft delete by deactivating)
     */
    public function delete($id) {
        try {
            $sql = "UPDATE users SET is_active = 0 WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Delete user error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Restore user (reactivate)
     */
    public function restore($id) {
        try {
            $sql = "UPDATE users SET is_active = 1 WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (PDOException $e) {
            error_log("Restore user error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Get users by role
     */
    public function getByRole($role, $limit = 100) {
        try {
            $sql = "SELECT id, name, email, avatar FROM users 
                    WHERE role = :role AND is_active = 1 
                    ORDER BY name ASC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':role' => $role,
                ':limit' => $limit
            ]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Get by role error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Search users
     */
    public function search($query, $limit = 20) {
        try {
            $sql = "SELECT id, name, email, avatar, role FROM users 
                    WHERE (name LIKE :query OR email LIKE :query) AND is_active = 1
                    ORDER BY name ASC LIMIT :limit";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':query' => '%' . $query . '%',
                ':limit' => $limit
            ]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Search users error: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Check if user is admin
     */
    public function isAdmin($id) {
        try {
            $sql = "SELECT role FROM users WHERE id = :id AND is_active = 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            $result = $stmt->fetch();
            return $result && $result['role'] === 'admin';
        } catch (PDOException $e) {
            error_log("Check admin error: " . $e->getMessage());
            return false;
        }
    }
}
?>
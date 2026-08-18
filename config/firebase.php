<?php
// ============================================
// SUBHAN PRINTERS - Firebase Configuration
// ============================================

class FirebaseAuth {
    /**
     * @var string Firebase Project ID
     */
    private $projectId;
    
    /**
     * @var string Firebase Private Key
     */
    private $privateKey;
    
    /**
     * @var string Firebase Client Email
     */
    private $clientEmail;
    
    /**
     * Constructor - Load Firebase config
     */
    public function __construct() {
        $this->projectId = getenv('FIREBASE_PROJECT_ID');
        $this->privateKey = getenv('FIREBASE_PRIVATE_KEY');
        $this->clientEmail = getenv('FIREBASE_CLIENT_EMAIL');
    }
    
    /**
     * Verify Firebase ID Token
     * 
     * @param string $idToken The Firebase ID token from client
     * @return array User data or error
     */
    public function verifyIdToken(string $idToken): array {
        try {
            // For production, use Firebase's Google Public Keys
            // This is a simplified JWT verification
            
            // Decode JWT header to get algorithm and key ID
            $parts = explode('.', $idToken);
            if (count($parts) !== 3) {
                return ['success' => false, 'error' => 'Invalid token format'];
            }
            
            list($headerB64, $payloadB64, $signature) = $parts;
            
            $header = json_decode(base64_decode(strtr($headerB64, '-_', '+/')), true);
            $payload = json_decode(base64_decode(strtr($payloadB64, '-_', '+/')), true);
            
            // Validate token
            if (!$this->validateToken($payload)) {
                return ['success' => false, 'error' => 'Invalid token'];
            }
            
            // Return user data
            return [
                'success' => true,
                'uid' => $payload['sub'],
                'email' => $payload['email'] ?? null,
                'name' => $payload['name'] ?? null,
                'picture' => $payload['picture'] ?? null,
                'email_verified' => $payload['email_verified'] ?? false,
                'issuer' => $payload['iss'] ?? null,
                'audience' => $payload['aud'] ?? null,
                'expires_at' => $payload['exp'] ?? null,
            ];
            
        } catch (Exception $e) {
            error_log("Firebase auth error: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
    
    /**
     * Validate JWT token claims
     * 
     * @param array $payload Decoded token payload
     * @return bool
     */
    private function validateToken(array $payload): bool {
        // Check issuer
        $expectedIssuer = "https://securetoken.google.com/" . $this->projectId;
        if (!isset($payload['iss']) || $payload['iss'] !== $expectedIssuer) {
            return false;
        }
        
        // Check audience
        if (!isset($payload['aud']) || $payload['aud'] !== $this->projectId) {
            return false;
        }
        
        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) {
            return false;
        }
        
        // Check issued at (not from future)
        if (isset($payload['iat']) && $payload['iat'] > time() + 60) {
            return false;
        }
        
        return true;
    }
    
    /**
     * Get Firebase user by UID (requires Admin SDK)
     * This is a placeholder - implement with firebase/php-jwt or Google API
     * 
     * @param string $uid Firebase UID
     * @return array|null
     */
    public function getUserByUid(string $uid): ?array {
        // For production, use Firebase Admin SDK with Service Account
        // or call Firebase REST API
        // This is a simplified version
        return null;
    }
}

/**
 * Helper function to verify Firebase token
 * 
 * @param string $token Firebase ID token
 * @return array
 */
function verifyFirebaseToken(string $token): array {
    $firebase = new FirebaseAuth();
    return $firebase->verifyIdToken($token);
}

/**
 * Middleware to check if user is authenticated via Firebase
 */
function authMiddleware(): array {
    // Get token from Authorization header
    $headers = getallheaders();
    $authHeader = $headers['Authorization'] ?? '';
    
    if (empty($authHeader) || !str_starts_with($authHeader, 'Bearer ')) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => 'Unauthorized']);
        exit;
    }
    
    $token = substr($authHeader, 7);
    $result = verifyFirebaseToken($token);
    
    if (!$result['success']) {
        http_response_code(401);
        echo json_encode(['success' => false, 'error' => $result['error'] ?? 'Invalid token']);
        exit;
    }
    
    return $result;
}
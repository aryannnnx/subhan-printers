<?php
// ============================================
// SUBHAN PRINTERS - Session Management
// ============================================

// ✅ Define session timeout (30 minutes)
define('SESSION_TIMEOUT', 1800); // 30 minutes in seconds

/**
 * Start session if not already started
 */
function start_session(): void {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
}

/**
 * ✅ Check if session is expired
 */
function is_session_expired(): bool {
    start_session();
    
    if (!isset($_SESSION['user_id'])) {
        return false;
    }
    
    $loginTime = $_SESSION['logged_in_at'] ?? 0;
    $timeout = SESSION_TIMEOUT; // 30 minutes
    
    return (time() - $loginTime) > $timeout;
}

/**
 * ✅ Check session and auto-logout if expired
 */
function check_session_timeout(): void {
    start_session();
    
    if (is_session_expired()) {
        // Clear session
        clear_user_session();
        
        // Set flash message (using function from functions.php)
        if (function_exists('set_flash')) {
            set_flash('error', 'Your session has expired. Please login again.');
        }
        
        // Redirect to login (using function from functions.php)
        if (function_exists('redirect')) {
            redirect('/SP/login');
        } else {
            header('Location: /SP/login');
            exit;
        }
        exit;
    }
    
    // Refresh timeout on activity
    if (isset($_SESSION['user_id'])) {
        $_SESSION['logged_in_at'] = time();
    }
}

/**
 * Set user session after login
 */
function set_user_session($user): void {
    start_session();
    session_regenerate_id(true);
    
    $_SESSION['user_id'] = $user['id'] ?? null;
    $_SESSION['user_name'] = $user['name'] ?? 'User';
    $_SESSION['user_email'] = $user['email'] ?? null;
    $_SESSION['user_role'] = $user['role'] ?? 'customer';
    $_SESSION['user_avatar'] = $user['avatar'] ?? null;
    $_SESSION['logged_in_at'] = time(); // ✅ Set login time
    $_SESSION['ip_address'] = $_SERVER['REMOTE_ADDR'] ?? null;
    $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'] ?? null;
}

/**
 * Clear user session (logout)
 */
function clear_user_session(): void {
    start_session();
    
    // Unset user-specific session variables
    unset($_SESSION['user_id']);
    unset($_SESSION['user_name']);
    unset($_SESSION['user_email']);
    unset($_SESSION['user_role']);
    unset($_SESSION['user_avatar']);
    unset($_SESSION['logged_in_at']);
    unset($_SESSION['ip_address']);
    unset($_SESSION['user_agent']);
    
    // Regenerate session ID for security
    session_regenerate_id(true);
}

/**
 * Set session data
 */
function session_set($key, $value): void {
    start_session();
    $_SESSION[$key] = $value;
}

/**
 * Get session data
 */
function session_get($key, $default = null) {
    start_session();
    return $_SESSION[$key] ?? $default;
}

/**
 * Check if session key exists
 */
function session_has($key): bool {
    start_session();
    return isset($_SESSION[$key]);
}

/**
 * Remove session data
 */
function session_remove($key): void {
    start_session();
    unset($_SESSION[$key]);
}

/**
 * Destroy session completely
 */
function destroy_session(): void {
    start_session();
    
    // Unset all session variables
    $_SESSION = [];
    
    // If session cookie exists, delete it
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params["path"],
            $params["domain"],
            $params["secure"] ?? false,
            $params["httponly"] ?? false
        );
    }
    
    // Finally, destroy the session
    session_destroy();
}

/**
 * Regenerate session ID
 */
function session_regenerate(): void {
    start_session();
    session_regenerate_id(true);
}

/**
 * Require login (with optional role check)
 */
function require_login($role = null): void {
    start_session();
    
    if (!isset($_SESSION['user_id'])) {
        // Use flash function from functions.php
        if (function_exists('set_flash')) {
            set_flash('error', 'Please login to continue');
        }
        // Use redirect function from functions.php
        if (function_exists('redirect')) {
            redirect('/SP/login');
        } else {
            header('Location: /SP/login');
            exit;
        }
        exit;
    }
    
    if ($role && $_SESSION['user_role'] !== $role) {
        if (function_exists('set_flash')) {
            set_flash('error', 'You do not have permission to access this page');
        }
        if (function_exists('redirect')) {
            redirect('/SP/dashboard');
        } else {
            header('Location: /SP/dashboard');
            exit;
        }
        exit;
    }
}

/**
 * Get session flash message
 */
function session_flash($key, $message = null) {
    start_session();
    
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return;
    }
    
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    
    return null;
}

/**
 * Check if session has flash message
 */
function session_has_flash($key): bool {
    start_session();
    return isset($_SESSION['flash'][$key]);
}

/**
 * Get session user ID
 */
function session_user_id() {
    return session_get('user_id');
}

/**
 * Get session user name
 */
function session_user_name() {
    return session_get('user_name', 'Guest');
}

/**
 * Get session user email
 */
function session_user_email() {
    return session_get('user_email');
}

/**
 * Get session user role
 */
function session_user_role() {
    return session_get('user_role', 'customer');
}

/**
 * Check if session is expired (with custom timeout)
 */
function session_expired($timeout = 3600): bool {
    start_session();
    $loginTime = $_SESSION['logged_in_at'] ?? 0;
    return (time() - $loginTime) > $timeout;
}

/**
 * Session timeout check with auto-logout
 */
function session_timeout_check($timeout = 3600): void {
    start_session();
    
    if (isset($_SESSION['user_id']) && session_expired($timeout)) {
        clear_user_session();
        if (function_exists('set_flash')) {
            set_flash('error', 'Your session has expired. Please login again.');
        }
        if (function_exists('redirect')) {
            redirect('/SP/login');
        } else {
            header('Location: /SP/login');
            exit;
        }
        exit;
    }
    
    // Refresh timeout on activity
    if (isset($_SESSION['user_id'])) {
        $_SESSION['logged_in_at'] = time();
    }
}

/**
 * Get session data as array (excluding sensitive data)
 */
function get_session_data(): array {
    start_session();
    
    $data = [];
    $allowedKeys = ['user_id', 'user_name', 'user_email', 'user_role', 'user_avatar'];
    
    foreach ($allowedKeys as $key) {
        if (isset($_SESSION[$key])) {
            $data[$key] = $_SESSION[$key];
        }
    }
    
    return $data;
}

// ============================================
// NOTE: The following functions are already defined in functions.php
// and should NOT be redeclared here:
// - set_flash()
// - get_flash() 
// - redirect()
// - base_url()
// - is_logged_in()
// - is_admin()
// - has_role()
// - get_current_user_data()
// ============================================
?>
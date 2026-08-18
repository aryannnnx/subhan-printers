<?php
// ============================================
// SUBHAN PRINTERS - Helper Functions
// ============================================

/**
 * Get base URL
 */
function base_url($path = ''): string {
    $base = getenv('APP_URL') ?: 'http://localhost:8080/SP';
    return rtrim($base, '/') . '/' . ltrim($path, '/');
}

/**
 * Redirect to a URL
 */
function redirect($url, $statusCode = 302): void {
    header('Location: ' . $url, true, $statusCode);
    exit;
}

/**
 * Redirect back to previous page
 */
function redirect_back($fallback = '/'): void {
    $referer = $_SERVER['HTTP_REFERER'] ?? '';
    if ($referer && strpos($referer, $_SERVER['HTTP_HOST']) !== false) {
        redirect($referer);
    } else {
        redirect($fallback);
    }
}

/**
 * Get current page name
 */
function current_page(): string {
    $url = $_SERVER['REQUEST_URI'];
    $url = strtok($url, '?');
    $url = trim($url, '/');
    return $url ?: 'home';
}

/**
 * Check if current page matches
 */
function is_current_page($page): bool {
    return current_page() === $page;
}

/**
 * Escape HTML output
 */
function e($string): string {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

/**
 * Escape and echo
 */
function ee($string): void {
    echo e($string);
}

/**
 * Generate CSRF token
 */
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token
 */
function csrf_verify($token): bool {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Generate CSRF hidden field
 */
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

/**
 * Sanitize input string
 */
function sanitize($input): string {
    return trim(strip_tags($input));
}

/**
 * Sanitize array recursively
 */
function sanitize_array($array) {
    if (is_array($array)) {
        return array_map('sanitize_array', $array);
    }
    return sanitize($array);
}

/**
 * Get current user data from session
 */
function get_current_user_data(): ?array {
    if (isset($_SESSION['user_id'])) {
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'] ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'role' => $_SESSION['user_role'] ?? 'customer'
        ];
    }
    return null;
}

/**
 * Check if user is logged in
 */
function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is admin
 */
function is_admin(): bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

/**
 * Check if user has specific role
 */
function has_role($role): bool {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === $role;
}

/**
 * Format currency (PKR)
 */
function format_currency($amount, $currency = 'PKR'): string {
    return $currency . ' ' . number_format($amount, 0);
}

/**
 * Format date
 */
function format_date($date, $format = 'F j, Y'): string {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Format datetime
 */
function format_datetime($date, $format = 'F j, Y H:i'): string {
    if (empty($date)) return '';
    return date($format, strtotime($date));
}

/**
 * Time ago (human readable)
 */
function time_ago($datetime): string {
    $timestamp = strtotime($datetime);
    $diff = time() - $timestamp;
    
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . ' minutes ago';
    if ($diff < 86400) return floor($diff / 3600) . ' hours ago';
    if ($diff < 604800) return floor($diff / 86400) . ' days ago';
    if ($diff < 2592000) return floor($diff / 604800) . ' weeks ago';
    if ($diff < 31536000) return floor($diff / 2592000) . ' months ago';
    return floor($diff / 31536000) . ' years ago';
}

/**
 * Generate random string
 */
function random_string($length = 10): string {
    return bin2hex(random_bytes($length));
}

/**
 * Truncate text
 */
function truncate($text, $length = 100, $suffix = '...'): string {
    if (strlen($text) <= $length) {
        return $text;
    }
    return substr($text, 0, $length) . $suffix;
}

/**
 * Get status badge HTML
 */
function status_badge($status): string {
    $colors = [
        'pending' => 'warning',
        'quoted' => 'info',
        'approved' => 'primary',
        'in_production' => 'warning',
        'quality_check' => 'info',
        'ready' => 'success',
        'delivered' => 'success',
        'cancelled' => 'danger',
        'active' => 'success',
        'inactive' => 'danger'
    ];
    
    $color = $colors[$status] ?? 'secondary';
    $label = ucwords(str_replace('_', ' ', $status));
    
    return "<span class='badge badge-$color'>$label</span>";
}

/**
 * Get status color for CSS
 */
function status_color($status): string {
    $colors = [
        'pending' => '#f59e0b',
        'quoted' => '#3b82f6',
        'approved' => '#8b5cf6',
        'in_production' => '#f59e0b',
        'quality_check' => '#8b5cf6',
        'ready' => '#22c55e',
        'delivered' => '#22c55e',
        'cancelled' => '#ef4444',
        'active' => '#22c55e',
        'inactive' => '#ef4444'
    ];
    return $colors[$status] ?? '#6b7280';
}

/**
 * Get current URL
 */
function current_url(): string {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $uri = $_SERVER['REQUEST_URI'];
    return $protocol . '://' . $host . $uri;
}

/**
 * Get all form data (POST or GET)
 */
function get_form_data(): array {
    $data = [];
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $data = $_POST;
    } else {
        $data = $_GET;
    }
    return sanitize_array($data);
}

/**
 * Check if request is AJAX
 */
function is_ajax(): bool {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
           strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
}

/**
 * Return JSON response
 */
function json_response($data, $statusCode = 200): void {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;
}

/**
 * Return success JSON response
 */
function json_success($data = [], $message = 'Success'): void {
    json_response([
        'success' => true,
        'message' => $message,
        'data' => $data
    ]);
}

/**
 * Return error JSON response
 */
function json_error($message = 'Error', $code = 400): void {
    json_response([
        'success' => false,
        'message' => $message
    ], $code);
}

/**
 * Set flash message
 */
function set_flash($key, $message): void {
    $_SESSION['flash'][$key] = $message;
}

/**
 * Get flash message and remove it
 */
function get_flash($key) {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

/**
 * Check if flash message exists
 */
function has_flash($key): bool {
    return isset($_SESSION['flash'][$key]);
}

/**
 * Display flash message
 */
function display_flash($key, $type = 'info'): void {
    if (has_flash($key)) {
        $message = get_flash($key);
        echo "<div class='alert alert-$type alert-dismissible fade show' role='alert'>";
        echo $message;
        echo "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>";
        echo "</div>";
    }
}

/**
 * Log message to file
 */
function log_message($message, $level = 'info'): void {
    $logDir = __DIR__ . '/../logs';
    if (!is_dir($logDir)) {
        mkdir($logDir, 0755, true);
    }
    
    $logFile = $logDir . '/app.log';
    $timestamp = date('Y-m-d H:i:s');
    $log = "[$timestamp] [$level] $message" . PHP_EOL;
    file_put_contents($logFile, $log, FILE_APPEND);
}

/**
 * Get IP address
 */
function get_ip(): string {
    $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    
    $headers = [
        'HTTP_CLIENT_IP',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_X_CLUSTER_CLIENT_IP',
        'HTTP_FORWARDED_FOR',
        'HTTP_FORWARDED'
    ];
    
    foreach ($headers as $header) {
        if (isset($_SERVER[$header]) && $_SERVER[$header]) {
            $ips = explode(',', $_SERVER[$header]);
            $ip = trim($ips[0]);
            break;
        }
    }
    
    return $ip;
}

/**
 * Get user agent
 */
function get_user_agent(): string {
    return $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
}

/**
 * Generate order number
 */
function generate_order_number(): string {
    $date = date('ymd');
    $random = str_pad(mt_rand(1, 9999), 4, '0', STR_PAD_LEFT);
    return 'SP' . $date . $random;
}

/**
 * Generate quote number
 */
function generate_quote_number(): string {
    $date = date('ym');
    $random = str_pad(mt_rand(1, 999), 3, '0', STR_PAD_LEFT);
    return 'Q' . $date . $random;
}

/**
 * Calculate total with tax
 */
function calculate_total($subtotal, $taxRate = 0, $deliveryCharges = 0): array {
    $tax = $subtotal * ($taxRate / 100);
    $total = $subtotal + $tax + $deliveryCharges;
    
    return [
        'subtotal' => $subtotal,
        'tax' => $tax,
        'tax_rate' => $taxRate,
        'delivery_charges' => $deliveryCharges,
        'total' => $total
    ];
}

/**
 * Validate phone number (Pakistan)
 */
function validate_phone($phone): bool {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    return preg_match('/^(03[0-9]{9}|0[1-9][0-9]{7,11})$/', $phone);
}

/**
 * Validate email
 */
function validate_email($email): bool {
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Convert string to slug
 */
function slugify($string): string {
    $string = strtolower($string);
    $string = preg_replace('/[^a-z0-9-]/', '-', $string);
    $string = preg_replace('/-+/', '-', $string);
    return trim($string, '-');
}

/**
 * Get file extension from URL
 */
function get_file_extension($filename): string {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}

/**
 * Get file size human readable
 */
function file_size_human($bytes, $decimals = 2): string {
    $size = ['B', 'KB', 'MB', 'GB', 'TB'];
    $factor = floor((strlen($bytes) - 1) / 3);
    return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . ' ' . $size[$factor];
}

/**
 * Check if file is image
 */
function is_image($filename): bool {
    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'];
    return in_array(get_file_extension($filename), $extensions);
}

/**
 * Get category name by slug
 */
function get_category_name($slug): string {
    $categories = [
        'wedding' => 'Wedding Cards',
        'packaging' => 'Box Packaging',
        'flex' => 'Flex & Banners',
        'brochures' => 'Brochures',
        'stickers' => 'Stickers & Labels',
        'logo' => 'Logo Design',
        'branding' => 'Branding',
        'stationery' => 'Stationery'
    ];
    return $categories[$slug] ?? ucfirst($slug);
}

/**
 * Get all categories for dropdown
 */
function get_all_categories(): array {
    return [
        'wedding' => 'Wedding Cards',
        'packaging' => 'Box Packaging',
        'flex' => 'Flex & Banners',
        'brochures' => 'Brochures',
        'stickers' => 'Stickers & Labels',
        'logo' => 'Logo Design',
        'branding' => 'Branding',
        'stationery' => 'Stationery'
    ];
}

/**
 * Get product types for dropdown
 */
function get_product_types(): array {
    return [
        'wedding_cards' => 'Wedding Cards',
        'box_packaging' => 'Box Packaging',
        'flex_banners' => 'Flex Banners',
        'business_cards' => 'Business Cards',
        'brochures' => 'Brochures',
        'stickers' => 'Stickers',
        'logo_design' => 'Logo Design',
        'corrugated_boxes' => 'Corrugated Boxes'
    ];
}

/**
 * Get delivery methods
 */
function get_delivery_methods(): array {
    return [
        'pickup' => 'Pickup from Shop',
        'courier' => 'Courier (TCS/Leopard)',
        'delivery' => 'Home Delivery'
    ];
}

/**
 * Get order statuses
 */
function get_order_statuses(): array {
    return [
        'pending' => 'Pending',
        'quoted' => 'Quoted',
        'approved' => 'Approved',
        'in_production' => 'In Production',
        'quality_check' => 'Quality Check',
        'ready' => 'Ready for Delivery',
        'delivered' => 'Delivered',
        'cancelled' => 'Cancelled'
    ];
}

/**
 * Get quote statuses
 */
function get_quote_statuses(): array {
    return [
        'pending' => 'Pending',
        'quoted' => 'Quoted',
        'follow_up' => 'Follow Up',
        'converted' => 'Converted to Order',
        'lost' => 'Lost'
    ];
}

/**
 * Get payment statuses
 */
function get_payment_statuses(): array {
    return [
        'unpaid' => 'Unpaid',
        'deposit_paid' => 'Deposit Paid',
        'partial_paid' => 'Partial Paid',
        'paid' => 'Paid'
    ];
}
<?php
// ============================================
// PAGES: User Dashboard - Subhan Printers
// ============================================

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load functions FIRST
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/session.php';

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: /SP/login');
    exit;
}

// Set page variables
$pageTitle = 'Dashboard | Subhan Printers';
$currentPage = 'dashboard';
$pageStyles = 'dashboard.css';
$pageScripts = '';  // No separate JS file needed

// Load required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';
require_once __DIR__ . '/../models/Quote.php';

// Include header
require_once __DIR__ . '/../templates/header.php';

// Get user data
$userId = $_SESSION['user_id'];
$userModel = new User();
$orderModel = new Order();
$quoteModel = new Quote();

$user = $userModel->getById($userId);
$orders = $orderModel->getByUser($userId, 10);

// Get quotes by user ID
$quotes = $quoteModel->getByUserId($userId, 10);

// If no quotes found by user_id, try by email (fallback)
if (empty($quotes)) {
    $userEmail = trim($user['email'] ?? '');
    $quotes = $quoteModel->getByEmail($userEmail, 10);
}

$totalQuotes = count($quotes);

// Order status counts
$orderStatuses = [
    'pending' => 0,
    'quoted' => 0,
    'approved' => 0,
    'in_production' => 0,
    'quality_check' => 0,
    'ready' => 0,
    'delivered' => 0,
    'cancelled' => 0
];

foreach ($orders as $order) {
    $status = $order['status'] ?? 'pending';
    if (isset($orderStatuses[$status])) {
        $orderStatuses[$status]++;
    }
}

// Calculate order stats
$totalSpent = 0;
foreach ($orders as $order) {
    if ($order['status'] === 'delivered' || $order['status'] === 'ready') {
        $totalSpent += (float)($order['total'] ?? 0);
    }
}

// Get recent activity
$recentActivity = [];
foreach ($orders as $order) {
    $recentActivity[] = [
        'type' => 'order',
        'title' => 'Order #' . $order['order_number'],
        'status' => $order['status'],
        'date' => $order['created_at'],
        'icon' => 'fa-shopping-bag'
    ];
}
foreach ($quotes as $quote) {
    $recentActivity[] = [
        'type' => 'quote',
        'title' => 'Quote #' . $quote['quote_number'],
        'status' => $quote['status'],
        'date' => $quote['created_at'],
        'icon' => 'fa-file-invoice'
    ];
}

// Sort by date (newest first)
usort($recentActivity, function($a, $b) {
    return strtotime($b['date']) - strtotime($a['date']);
});
$recentActivity = array_slice($recentActivity, 0, 10);

// Get upcoming deadlines
$upcomingDeadlines = [];
foreach ($orders as $order) {
    if (!empty($order['estimated_delivery']) && $order['status'] !== 'delivered' && $order['status'] !== 'cancelled') {
        $upcomingDeadlines[] = [
            'order_number' => $order['order_number'],
            'delivery_date' => $order['estimated_delivery'],
            'days_left' => ceil((strtotime($order['estimated_delivery']) - time()) / 86400)
        ];
    }
}
usort($upcomingDeadlines, function($a, $b) {
    return $a['days_left'] - $b['days_left'];
});
$upcomingDeadlines = array_slice($upcomingDeadlines, 0, 5);

$statusColors = [
    'pending' => '#f59e0b',
    'quoted' => '#3b82f6',
    'approved' => '#8b5cf6',
    'in_production' => '#f59e0b',
    'quality_check' => '#8b5cf6',
    'ready' => '#22c55e',
    'delivered' => '#22c55e',
    'cancelled' => '#ef4444'
];

$statusLabels = [
    'pending' => 'Pending',
    'quoted' => 'Quoted',
    'approved' => 'Approved',
    'in_production' => 'In Production',
    'quality_check' => 'Quality Check',
    'ready' => 'Ready',
    'delivered' => 'Delivered',
    'cancelled' => 'Cancelled'
];

// FIX: Calculate total documents count (orders + quotes)
$totalDocuments = count($orders) + $totalQuotes;
// FIX: Get user initials for avatar
$userInitials = strtoupper(substr($user['name'] ?? 'U', 0, 1));
// FIX: Get user role with proper label
$userRole = ucfirst($user['role'] ?? 'customer');
// FIX: Get current date
$currentDate = date('M d, Y');
// FIX: Get online status
$onlineStatus = 'Online';
?>

<!-- ==========================================
     DASHBOARD STYLES (Inline for speed)
     ========================================== -->
<style>
:root {
    --db-green: #22c55e;
    --db-primary: #8b5cf6;
    --db-accent: #f59e0b;
    --db-red: #ef4444;
    --db-blue: #3b82f6;
}

.dashboard-wrap {
    padding-top: 100px;
    min-height: 100vh;
    background: #0a0a14;
}

.dash-container {
    max-width: 1280px;
    margin: 0 auto;
    padding: 0 24px 60px;
}

/* ─── HEADER ─── */
.dash-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    flex-wrap: wrap;
    gap: 16px;
    padding: 24px 0 32px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.dash-header-left h1 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 800;
    color: #e8e8f0;
    margin-bottom: 4px;
}

.dash-header-left p {
    color: #8888aa;
    font-size: 0.9rem;
}

.dash-header-right {
    display: flex;
    gap: 12px;
    align-items: center;
    flex-wrap: wrap;
}

/* ─── BADGE (FIX: No more "docs!" hardcoded) ─── */
.dash-badge {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(139,92,246,0.12);
    border: 1px solid rgba(139,92,246,0.2);
    border-radius: 100px;
    color: #8b5cf6;
    font-size: 0.8rem;
    font-weight: 600;
}

.dash-badge-docs {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: rgba(34,197,94,0.12);
    border: 1px solid rgba(34,197,94,0.2);
    border-radius: 100px;
    color: #22c55e;
    font-size: 0.8rem;
    font-weight: 600;
}

/* ─── STATS ─── */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 16px;
    padding: 32px 0;
}

.stat-card {
    background: #12121f;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    padding: 20px 24px;
    transition: all 0.3s ease;
}

.stat-card:hover {
    border-color: rgba(139,92,246,0.3);
    transform: translateY(-2px);
}

.stat-card .stat-icon {
    width: 44px;
    height: 44px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.2rem;
    margin-bottom: 12px;
}

.stat-card .stat-value {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 800;
    color: #e8e8f0;
}

.stat-card .stat-label {
    color: #8888aa;
    font-size: 0.82rem;
    margin-top: 2px;
}

/* ─── QUICK ACTIONS ─── */
.quick-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
    padding: 16px 0 32px;
}

.quick-action-btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.85rem;
    text-decoration: none;
    font-family: inherit;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
}

.quick-action-btn.primary {
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    color: #fff;
}

.quick-action-btn.primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139,92,246,0.4);
}

.quick-action-btn.secondary {
    background: #1a1a2e;
    color: #e8e8f0;
    border: 1px solid rgba(255,255,255,0.06);
}

.quick-action-btn.secondary:hover {
    border-color: rgba(255,255,255,0.15);
    background: #24243a;
}

.quick-action-btn.green {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #fff;
}

.quick-action-btn.green:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(34,197,94,0.4);
}

/* ─── GRID ─── */
.dash-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 24px;
}

.dash-card {
    background: #12121f;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    overflow: hidden;
    transition: all 0.3s ease;
}

.dash-card:hover {
    border-color: rgba(255,255,255,0.08);
}

.dash-card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 24px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.dash-card-header h3 {
    font-weight: 700;
    color: #e8e8f0;
    font-size: 1rem;
    display: flex;
    align-items: center;
    gap: 8px;
}

.dash-card-header .view-all {
    color: #8b5cf6;
    font-size: 0.8rem;
    font-weight: 600;
    text-decoration: none;
    transition: color 0.3s ease;
}

.dash-card-header .view-all:hover {
    color: #a78bfa;
}

.dash-card-body {
    padding: 20px 24px;
}

/* ─── ORDER ITEMS ─── */
.order-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 14px 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.order-item:last-child {
    border-bottom: none;
}

.order-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.order-info .order-number {
    font-weight: 600;
    color: #e8e8f0;
    font-size: 0.9rem;
}

.order-info .order-details {
    font-size: 0.8rem;
    color: #8888aa;
}

.order-status {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 12px;
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.status-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    display: inline-block;
}

.order-price {
    font-weight: 700;
    color: #f59e0b;
    font-size: 0.9rem;
}

/* ─── ACTIVITY ─── */
.activity-item {
    display: flex;
    gap: 14px;
    padding: 12px 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
    align-items: flex-start;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-icon {
    width: 36px;
    height: 36px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
    flex-shrink: 0;
}

.activity-content {
    flex: 1;
}

.activity-content .activity-title {
    font-weight: 600;
    color: #e8e8f0;
    font-size: 0.85rem;
}

.activity-content .activity-time {
    font-size: 0.75rem;
    color: #8888aa;
}

.activity-status {
    font-size: 0.7rem;
    font-weight: 600;
    padding: 2px 10px;
    border-radius: 100px;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

/* ─── PROFILE CARD ─── */
.profile-card {
    text-align: center;
    padding: 24px;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    font-weight: 700;
    color: #fff;
    margin: 0 auto 16px;
    box-shadow: 0 8px 30px rgba(139,92,246,0.3);
}

.profile-name {
    font-weight: 700;
    color: #e8e8f0;
    font-size: 1.1rem;
}

.profile-email {
    color: #8888aa;
    font-size: 0.85rem;
}

.profile-role {
    display: inline-block;
    padding: 4px 12px;
    background: rgba(139,92,246,0.12);
    color: #8b5cf6;
    border-radius: 100px;
    font-size: 0.7rem;
    font-weight: 600;
    margin-top: 6px;
}

.profile-stats {
    display: grid;
    grid-template-columns: 1fr 1fr 1fr;
    gap: 8px;
    margin-top: 16px;
    padding-top: 16px;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.profile-stat .ps-value {
    font-weight: 700;
    color: #e8e8f0;
    font-size: 1.1rem;
}

.profile-stat .ps-label {
    font-size: 0.7rem;
    color: #8888aa;
}

/* ─── DEADLINES ─── */
.deadline-item {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.deadline-item:last-child {
    border-bottom: none;
}

.deadline-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.deadline-order {
    font-weight: 600;
    color: #e8e8f0;
    font-size: 0.85rem;
}

.deadline-date {
    font-size: 0.75rem;
    color: #8888aa;
}

.deadline-days {
    font-weight: 700;
    font-size: 0.8rem;
    padding: 4px 12px;
    border-radius: 100px;
}

.deadline-days.urgent {
    color: #ef4444;
    background: rgba(239,68,68,0.12);
}

.deadline-days.soon {
    color: #f59e0b;
    background: rgba(245,158,11,0.12);
}

.deadline-days.safe {
    color: #22c55e;
    background: rgba(34,197,94,0.12);
}

/* ─── EMPTY STATE ─── */
.empty-state {
    text-align: center;
    padding: 40px 20px;
}

.empty-state .empty-icon {
    font-size: 3rem;
    color: #8888aa;
    opacity: 0.3;
    margin-bottom: 16px;
}

.empty-state .empty-title {
    font-weight: 600;
    color: #e8e8f0;
    font-size: 1rem;
}

.empty-state .empty-desc {
    color: #8888aa;
    font-size: 0.85rem;
    margin-top: 4px;
}

/* ─── DOCUMENT COUNT BADGE (FIX: Shows proper count) ─── */
.doc-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 14px;
    background: rgba(34,197,94,0.12);
    border: 1px solid rgba(34,197,94,0.15);
    border-radius: 100px;
    color: #22c55e;
    font-size: 0.75rem;
    font-weight: 600;
}

.doc-badge i {
    font-size: 0.7rem;
}

/* ─── RESPONSIVE ─── */
@media (max-width: 1024px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    .dash-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .dash-header {
        flex-direction: column;
    }
    .dash-header-right {
        width: 100%;
    }
    .stats-grid {
        grid-template-columns: 1fr 1fr;
    }
    .quick-actions {
        flex-direction: column;
    }
    .quick-action-btn {
        width: 100%;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .stats-grid {
        grid-template-columns: 1fr;
    }
    .dash-card-body {
        padding: 12px 16px;
    }
    .order-item {
        flex-wrap: wrap;
        gap: 8px;
    }
    .profile-stats {
        grid-template-columns: 1fr 1fr;
    }
    .dash-header-left h1 {
        font-size: 1.5rem;
    }
}
</style>

<!-- ==========================================
     DASHBOARD CONTENT
     ========================================== -->
<div class="dashboard-wrap">
    <div class="dash-container">

        <!-- ── HEADER ── -->
        <div class="dash-header">
            <div class="dash-header-left">
                <h1>👋 Welcome back, <?php echo htmlspecialchars($user['name'] ?? 'User'); ?></h1>
                <p>Here's what's happening with your print projects.</p>
            </div>
            <div class="dash-header-right">
                <!-- Status Badge -->
                <span class="dash-badge">
                    <i class="fas fa-circle" style="color:#22c55e;font-size:0.5rem;"></i>
                    <?php echo $onlineStatus; ?>
                </span>
                
                <!-- Date Badge -->
                <span class="dash-badge">
                    <i class="fas fa-calendar-alt"></i>
                    <?php echo $currentDate; ?>
                </span>

                <!-- FIX: DOCUMENT COUNT BADGE - Replaces "docs!" -->
                <span class="dash-badge-docs">
                    <i class="fas fa-file-alt"></i>
                    <?php echo $totalDocuments; ?> Document<?php echo $totalDocuments !== 1 ? 's' : ''; ?>
                </span>
            </div>
        </div>

        <!-- ── STATS ── -->
        <div class="stats-grid">
            <!-- Total Orders -->
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">
                    <i class="fas fa-shopping-bag" style="color:#fff;"></i>
                </div>
                <div class="stat-value"><?php echo count($orders); ?></div>
                <div class="stat-label">Total Orders</div>
            </div>

            <!-- Total Quotes -->
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)">
                    <i class="fas fa-file-invoice" style="color:#fff;"></i>
                </div>
                <div class="stat-value"><?php echo $totalQuotes; ?></div>
                <div class="stat-label">Total Quotes</div>
            </div>

            <!-- Completed Orders -->
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#22c55e,#16a34a)">
                    <i class="fas fa-check-circle" style="color:#fff;"></i>
                </div>
                <div class="stat-value"><?php echo $orderStatuses['delivered'] ?? 0; ?></div>
                <div class="stat-label">Completed Orders</div>
            </div>

            <!-- Total Spent -->
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">
                    <i class="fas fa-rupee-sign" style="color:#fff;"></i>
                </div>
                <div class="stat-value">₨ <?php echo number_format($totalSpent, 0); ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
        </div>

        <!-- ── QUICK ACTIONS ── -->
        <div class="quick-actions">
            <a href="/SP/order" class="quick-action-btn primary">
                <i class="fas fa-plus-circle"></i> New Order
            </a>
            <a href="/SP/contact" class="quick-action-btn green">
                <i class="fas fa-file-invoice-dollar"></i> Request Quote
            </a>
            <a href="/SP/portfolio" class="quick-action-btn secondary">
                <i class="fas fa-images"></i> Browse Portfolio
            </a>
        </div>

        <!-- ── MAIN GRID ── -->
        <div class="dash-grid">

            <!-- ── LEFT COLUMN ── -->
            <div>

                <!-- Recent Orders -->
                <div class="dash-card">
                    <div class="dash-card-header">
                        <h3><i class="fas fa-shopping-bag" style="color:#8b5cf6;margin-right:8px;"></i> Recent Orders</h3>
                        <?php if (count($orders) > 0): ?>
                            <a href="/SP/orders" class="view-all">View All →</a>
                        <?php endif; ?>
                    </div>
                    <div class="dash-card-body">
                        <?php if (count($orders) > 0): ?>
                            <?php foreach (array_slice($orders, 0, 5) as $order): ?>
                                <div class="order-item">
                                    <div class="order-info">
                                        <span class="order-number">#<?php echo htmlspecialchars($order['order_number']); ?></span>
                                        <span class="order-details">
                                            <?php echo ucwords(str_replace('_', ' ', $order['product_type'] ?? '')); ?>
                                            · <?php echo $order['quantity'] ?? 0; ?> pieces
                                        </span>
                                    </div>
                                    <div style="display:flex;align-items:center;gap:12px;flex-wrap:wrap;">
                                        <span class="order-status" style="background:<?php echo $statusColors[$order['status'] ?? 'pending'] ?? '#6b7280'; ?>20;color:<?php echo $statusColors[$order['status'] ?? 'pending'] ?? '#6b7280'; ?>;">
                                            <span class="status-dot" style="background:<?php echo $statusColors[$order['status'] ?? 'pending'] ?? '#6b7280'; ?>;"></span>
                                            <?php echo $statusLabels[$order['status'] ?? 'pending'] ?? ucfirst($order['status'] ?? ''); ?>
                                        </span>
                                        <span class="order-price">₨ <?php echo number_format($order['total'] ?? 0, 0); ?></span>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-shopping-bag"></i></div>
                                <div class="empty-title">No orders yet</div>
                                <div class="empty-desc">Place your first order and track it here.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="dash-card" style="margin-top:24px;">
                    <div class="dash-card-header">
                        <h3><i class="fas fa-clock" style="color:#f59e0b;margin-right:8px;"></i> Recent Activity</h3>
                    </div>
                    <div class="dash-card-body">
                        <?php if (count($recentActivity) > 0): ?>
                            <?php foreach (array_slice($recentActivity, 0, 6) as $activity): ?>
                                <div class="activity-item">
                                    <div class="activity-icon" style="background:<?php echo $activity['type'] === 'order' ? 'rgba(139,92,246,0.12)' : 'rgba(245,158,11,0.12)'; ?>;color:<?php echo $activity['type'] === 'order' ? '#8b5cf6' : '#f59e0b'; ?>;">
                                        <i class="fas <?php echo $activity['icon']; ?>"></i>
                                    </div>
                                    <div class="activity-content">
                                        <div class="activity-title"><?php echo htmlspecialchars($activity['title']); ?></div>
                                        <div class="activity-time"><?php echo time_ago($activity['date']); ?></div>
                                    </div>
                                    <span class="activity-status" style="color:<?php echo $statusColors[$activity['status'] ?? 'pending'] ?? '#6b7280'; ?>;">
                                        <?php echo $statusLabels[$activity['status'] ?? 'pending'] ?? ucfirst($activity['status'] ?? ''); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-clock"></i></div>
                                <div class="empty-title">No activity yet</div>
                                <div class="empty-desc">Your recent activity will appear here.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

            <!-- ── RIGHT COLUMN ── -->
            <div>

                <!-- Profile -->
                <div class="dash-card">
                    <div class="dash-card-body profile-card">
                        <div class="profile-avatar">
                            <?php echo $userInitials; ?>
                        </div>
                        <div class="profile-name"><?php echo htmlspecialchars($user['name'] ?? 'User'); ?></div>
                        <div class="profile-email"><?php echo htmlspecialchars($user['email'] ?? ''); ?></div>
                        <span class="profile-role"><?php echo $userRole; ?></span>
                        
                        <!-- FIX: Added document count badge to profile -->
                        <div style="margin-top: 10px; display: flex; gap: 8px; justify-content: center; flex-wrap: wrap;">
                            <span class="doc-badge">
                                <i class="fas fa-file-alt"></i> 
                                <?php echo $totalDocuments; ?> Documents
                            </span>
                            <span class="doc-badge" style="color:#8b5cf6; border-color: rgba(139,92,246,0.15); background: rgba(139,92,246,0.08);">
                                <i class="fas fa-shopping-bag"></i> 
                                <?php echo count($orders); ?> Orders
                            </span>
                        </div>

                        <div class="profile-stats">
                            <div class="profile-stat">
                                <div class="ps-value"><?php echo count($orders); ?></div>
                                <div class="ps-label">Orders</div>
                            </div>
                            <div class="profile-stat">
                                <div class="ps-value"><?php echo $totalQuotes; ?></div>
                                <div class="ps-label">Quotes</div>
                            </div>
                            <div class="profile-stat">
                                <div class="ps-value"><?php echo $orderStatuses['delivered'] ?? 0; ?></div>
                                <div class="ps-label">Completed</div>
                            </div>
                        </div>
                        <div style="margin-top:16px;display:flex;gap:8px;justify-content:center;flex-wrap:wrap;">
                            <a href="/SP/profile" class="quick-action-btn secondary" style="font-size:0.75rem;padding:8px 16px;">
                                <i class="fas fa-user-edit"></i> Edit Profile
                            </a>
                            <a href="/SP/logout" class="quick-action-btn secondary" style="font-size:0.75rem;padding:8px 16px;color:#ef4444;border-color:rgba(239,68,68,0.2);">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Upcoming Deadlines -->
                <div class="dash-card" style="margin-top:24px;">
                    <div class="dash-card-header">
                        <h3><i class="fas fa-calendar-alt" style="color:#3b82f6;margin-right:8px;"></i> Upcoming Deadlines</h3>
                    </div>
                    <div class="dash-card-body">
                        <?php if (count($upcomingDeadlines) > 0): ?>
                            <?php foreach ($upcomingDeadlines as $deadline): ?>
                                <div class="deadline-item">
                                    <div class="deadline-info">
                                        <span class="deadline-order">#<?php echo htmlspecialchars($deadline['order_number']); ?></span>
                                        <span class="deadline-date"><?php echo date('M d, Y', strtotime($deadline['delivery_date'])); ?></span>
                                    </div>
                                    <span class="deadline-days <?php echo $deadline['days_left'] <= 2 ? 'urgent' : ($deadline['days_left'] <= 5 ? 'soon' : 'safe'); ?>">
                                        <?php echo $deadline['days_left'] <= 0 ? '⚠️ Overdue' : ($deadline['days_left'] . ' days left'); ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-icon"><i class="fas fa-calendar-check"></i></div>
                                <div class="empty-title">No upcoming deadlines</div>
                                <div class="empty-desc">Your orders will appear here with delivery dates.</div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div>

        </div><!-- /dash-grid -->

    </div><!-- /dash-container -->
</div><!-- /dashboard-wrap -->

<!-- ==========================================
     FOOTER - Matching Index Page Style
     ========================================== -->
<footer>
    <div class="container">
        <div class="footer-grid">
            <!-- Brand -->
            <div>
                <div class="footer-logo">
                    <img src="/SP/images/logo.png" alt="Subhan Printers" width="36" onerror="this.style.display='none'" />
                    Subhan <span>Printers</span>
                </div>
                <p class="footer-desc">
                    Your trusted partner for professional graphic designing and printing services in Gawalmandi, Lahore, Pakistan.
                </p>
                <div class="footer-socials">
                    <a href="#" class="footer-social" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" class="footer-social" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="https://wa.me/923001234567" target="_blank" rel="noopener" class="footer-social" aria-label="WhatsApp"><i class="fab fa-whatsapp"></i></a>
                    <a href="mailto:info@subhanprinters.com" class="footer-social" aria-label="Email"><i class="fas fa-envelope"></i></a>
                </div>
            </div>

            <!-- Quick links -->
            <div>
                <h4 class="footer-col-title">Quick Links</h4>
                <ul class="footer-links">
                    <li><a href="/SP/"><i class="fas fa-chevron-right"></i> Home</a></li>
                    <li><a href="/SP/services"><i class="fas fa-chevron-right"></i> Services</a></li>
                    <li><a href="/SP/portfolio"><i class="fas fa-chevron-right"></i> Portfolio</a></li>
                    <li><a href="/SP/about"><i class="fas fa-chevron-right"></i> About Us</a></li>
                    <li><a href="/SP/contact"><i class="fas fa-chevron-right"></i> Contact</a></li>
                </ul>
            </div>

            <!-- Services -->
            <div>
                <h4 class="footer-col-title">Our Services</h4>
                <ul class="footer-links">
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Graphic Designing</a></li>
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Printing Solutions</a></li>
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Packaging Designs</a></li>
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Corporate Branding</a></li>
                    <li><a href="/SP/services"><i class="fas fa-check" style="color:#8b5cf6;"></i> Wedding Cards</a></li>
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h4 class="footer-col-title">Contact Info</h4>
                <div class="footer-contact-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span>Hamza Center, Gawalmandi, Lahore, Pakistan</span>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-phone"></i>
                    <a href="tel:+923001234567">+92 300 1234567</a>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-envelope"></i>
                    <a href="mailto:info@subhanprinters.com">info@subhanprinters.com</a>
                </div>
                <div class="footer-contact-item">
                    <i class="fas fa-clock"></i>
                    <span>Mon–Sat: 9 AM – 8 PM</span>
                </div>
            </div>
        </div>

        <!-- Payment logos -->
        <div class="footer-payments">
            <span class="footer-payments-label">We Accept</span>
            <div class="footer-payments-logos">
                <div class="pay-logo-img" title="Mastercard">
                    <img src="/SP/images/pngwing.com.png" alt="Mastercard" />
                </div>
                <div class="pay-logo-img" title="VISA">
                    <img src="/SP/images/pngwing.com%20(1).png" alt="Visa" />
                </div>
                <div class="pay-logo-img" title="Sadapay">
                    <img src="/SP/images/Sadapay-Logo.png" alt="Sadapay" />
                </div>
                <div class="pay-logo-img" title="JazzCash">
                    <img src="/SP/images/jazzcash.png" alt="JazzCash" />
                </div>
                <div class="easypaisa-bg-wrapper" title="EasyPaisa">
                    <div class="easypaisa-logo-img">
                        <img src="/SP/images/easypaisa.png" alt="EasyPaisa" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <p class="footer-bottom-text">
                © <?php echo date('Y'); ?> <strong style="color:#e8e8f0;">Subhan Printers</strong>. All rights reserved. | Hamza Center, Gawalmandi, Pakistan
            </p>
            <div class="footer-bottom-links">
                <a href="/SP/privacy">Privacy Policy</a>
                <a href="/SP/terms">Terms of Service</a>
                <a href="/SP/contact">Contact</a>
            </div>
        </div>
    </div>
</footer>

<!-- WhatsApp floating button -->
<a href="https://wa.me/923001234567" target="_blank" rel="noopener" class="wa-float" aria-label="Chat with us on WhatsApp">
    <i class="fab fa-whatsapp" aria-hidden="true"></i>
</a>

<!-- Session Timer -->
<div style="position:fixed;bottom:80px;right:20px;z-index:999;background:rgba(18,18,31,0.9);border:1px solid rgba(255,255,255,0.06);border-radius:12px;padding:12px 16px;font-size:0.8rem;color:#8888aa;backdrop-filter:blur(10px);">
    <i class="fas fa-clock" style="color:#f59e0b;"></i>
    Session expires in: <span id="session-timer" style="color:#e8e8f0;font-weight:600;">30:00</span>
</div>

<script>
// Session Timer
const sessionTimeout = <?php echo SESSION_TIMEOUT; ?>; // 1800 seconds
let timeLeft = sessionTimeout;

function updateTimer() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    const timerEl = document.getElementById('session-timer');
    if (timerEl) {
        timerEl.textContent = String(minutes).padStart(2, '0') + ':' + String(seconds).padStart(2, '0');
    }
    
    if (timeLeft <= 0) {
        // Auto logout when timer reaches 0
        window.location.href = '/SP/login?expired=true';
    }
    timeLeft--;
}

updateTimer();
setInterval(updateTimer, 1000);
</script>

<?php
// End of dashboard.php
?>
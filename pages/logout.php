<?php
// ============================================
// PAGES: Logout - Subhan Printers
// ============================================

// Start session
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load functions
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/session.php';

// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? 'User';

// If user is not logged in, redirect to login
if (!$isLoggedIn) {
    header('Location: ' . base_url('login'));
    exit;
}

// Check if this is a GET request (showing logout page) or POST (confirming logout)
$confirmed = isset($_GET['confirm']) && $_GET['confirm'] === 'yes';

if ($confirmed) {
    // Clear all session data
    clear_user_session();
    destroy_session();
    
    // Set success message
    set_flash('success', 'You have been logged out successfully!');
    
    // Redirect to login page
    header('Location: ' . base_url('login'));
    exit;
}

// Set page variables
$pageTitle = 'Logout | Subhan Printers';
$currentPage = 'logout';
$pageStyles = 'logout.css';
$pageScripts = 'logout.js';

// Include header
require_once __DIR__ . '/../templates/header.php';
?>

<!-- ==========================================
     LOGOUT PAGE STYLES
     ========================================== -->
<style>
.logout-wrapper {
    min-height: 80vh;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 120px 20px 60px;
    background: var(--db-bg, #0a0a14);
}

.logout-container {
    max-width: 500px;
    width: 100%;
    background: var(--db-surface, #12121f);
    border: 1px solid var(--db-border, rgba(255,255,255,0.06));
    border-radius: 24px;
    padding: 48px 40px;
    text-align: center;
    box-shadow: 0 20px 60px rgba(0,0,0,0.5);
}

.logout-icon {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
    font-size: 2.4rem;
    color: #fff;
    box-shadow: 0 8px 32px rgba(239,68,68,0.3);
}

.logout-title {
    font-family: 'Playfair Display', serif;
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--db-text, #e8e8f0);
    margin-bottom: 8px;
}

.logout-subtitle {
    color: var(--db-muted, #8888aa);
    font-size: 1rem;
    margin-bottom: 8px;
}

.logout-user {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(139,92,246,0.12);
    border: 1px solid rgba(139,92,246,0.2);
    border-radius: 100px;
    color: var(--db-primary, #8b5cf6);
    font-weight: 600;
    font-size: 0.9rem;
    margin-bottom: 32px;
}

.logout-message {
    color: var(--db-muted, #8888aa);
    font-size: 0.9rem;
    line-height: 1.7;
    margin-bottom: 32px;
}

.logout-buttons {
    display: flex;
    gap: 12px;
    justify-content: center;
    flex-wrap: wrap;
}

.btn-logout-confirm {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 32px;
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    font-family: inherit;
    background: linear-gradient(135deg, #ef4444, #dc2626);
    color: #fff;
    box-shadow: 0 4px 20px rgba(239,68,68,0.3);
}

.btn-logout-confirm:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 30px rgba(239,68,68,0.5);
}

.btn-logout-cancel {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 14px 32px;
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.95rem;
    border: none;
    cursor: pointer;
    transition: all 0.3s ease;
    text-decoration: none;
    font-family: inherit;
    background: var(--db-surface-2, #1a1a2e);
    color: var(--db-text, #e8e8f0);
    border: 1px solid var(--db-border, rgba(255,255,255,0.06));
}

.btn-logout-cancel:hover {
    border-color: rgba(139,92,246,0.3);
    background: rgba(139,92,246,0.05);
}

.logout-footer {
    margin-top: 24px;
    padding-top: 24px;
    border-top: 1px solid var(--db-border, rgba(255,255,255,0.06));
    display: flex;
    justify-content: center;
    gap: 16px;
    font-size: 0.8rem;
    color: var(--db-muted, #8888aa);
}

.logout-footer a {
    color: var(--db-primary, #8b5cf6);
    text-decoration: none;
    transition: color 0.3s ease;
}

.logout-footer a:hover {
    color: var(--db-accent, #f59e0b);
}

@media (max-width: 480px) {
    .logout-container {
        padding: 32px 24px;
    }
    .logout-title {
        font-size: 1.4rem;
    }
    .logout-buttons {
        flex-direction: column;
        width: 100%;
    }
    .btn-logout-confirm,
    .btn-logout-cancel {
        width: 100%;
        justify-content: center;
    }
}
</style>

<!-- ==========================================
     LOGOUT PAGE CONTENT
     ========================================== -->
<div class="logout-wrapper">
    <div class="logout-container">
        
        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        
        <h1 class="logout-title">Logout Confirmation</h1>
        
        <p class="logout-subtitle">Are you sure you want to log out?</p>
        
        <div class="logout-user">
            <i class="fas fa-user-circle" style="margin-right:8px;"></i>
            <?php echo htmlspecialchars($userName); ?>
        </div>
        
        <p class="logout-message">
            You will be redirected to the login page after logging out.
            Your session will be terminated and you'll need to sign in again.
        </p>
        
        <div class="logout-buttons">
            <a href="<?php echo base_url('logout?confirm=yes'); ?>" class="btn-logout-confirm">
                <i class="fas fa-sign-out-alt"></i> Yes, Logout
            </a>
            <a href="<?php echo base_url('dashboard'); ?>" class="btn-logout-cancel">
                <i class="fas fa-times"></i> Cancel, Go Back
            </a>
        </div>
        
        <div class="logout-footer">
            <a href="<?php echo base_url(); ?>">
                <i class="fas fa-home"></i> Home
            </a>
            <a href="<?php echo base_url('dashboard'); ?>">
                <i class="fas fa-th-large"></i> Dashboard
            </a>
            <a href="<?php echo base_url('contact'); ?>">
                <i class="fas fa-envelope"></i> Contact
            </a>
        </div>
        
    </div>
</div>

<?php
// Include footer
require_once __DIR__ . '/../templates/footer.php';
?>
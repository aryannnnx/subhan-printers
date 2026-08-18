<?php
// ============================================
// PAGES: User Profile - Subhan Printers
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
$pageTitle = 'My Profile | Subhan Printers';
$currentPage = 'profile';
$pageStyles = 'profile.css';
$pageScripts = '';

// Load required files
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Order.php';

// Include header
require_once __DIR__ . '/../templates/header.php';

// Get user data
$userId = $_SESSION['user_id'];
$userModel = new User();
$orderModel = new Order();

$user = $userModel->getById($userId);
$orders = $orderModel->getByUser($userId, 10);

// Handle form submission
$message = '';
$messageType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Update profile
    if (!empty($name)) {
        $updateData = ['name' => $name];
        if (!empty($phone)) {
            $updateData['phone'] = $phone;
        }
        
        $result = $userModel->update($userId, $updateData);
        if ($result['success']) {
            $_SESSION['user_name'] = $name;
            $message = 'Profile updated successfully!';
            $messageType = 'success';
            // Refresh user data
            $user = $userModel->getById($userId);
        } else {
            $message = 'Failed to update profile.';
            $messageType = 'error';
        }
    }
    
    // Change password
    if (!empty($currentPassword) && !empty($newPassword) && !empty($confirmPassword)) {
        // Verify current password
        $userCheck = $userModel->getById($userId);
        if (password_verify($currentPassword, $userCheck['password_hash'])) {
            if ($newPassword === $confirmPassword) {
                if (strlen($newPassword) >= 6) {
                    $result = $userModel->updatePassword($userId, $newPassword);
                    if ($result['success']) {
                        $message = 'Password changed successfully!';
                        $messageType = 'success';
                    } else {
                        $message = 'Failed to change password.';
                        $messageType = 'error';
                    }
                } else {
                    $message = 'New password must be at least 6 characters.';
                    $messageType = 'error';
                }
            } else {
                $message = 'New passwords do not match.';
                $messageType = 'error';
            }
        } else {
            $message = 'Current password is incorrect.';
            $messageType = 'error';
        }
    }
}
?>

<!-- ==========================================
     PROFILE PAGE STYLES
     ========================================== -->
<style>
:root {
    --db-green: #22c55e;
    --db-primary: #8b5cf6;
    --db-accent: #f59e0b;
    --db-red: #ef4444;
    --db-blue: #3b82f6;
}

.profile-wrap {
    padding-top: 100px;
    min-height: 100vh;
    background: #0a0a14;
}

.profile-container {
    max-width: 900px;
    margin: 0 auto;
    padding: 0 24px 60px;
}

.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    padding: 24px 0 32px;
    border-bottom: 1px solid rgba(255,255,255,0.06);
}

.profile-header h1 {
    font-family: 'Playfair Display', serif;
    font-size: 2rem;
    font-weight: 800;
    color: #e8e8f0;
}

.profile-header p {
    color: #8888aa;
    font-size: 0.9rem;
}

.profile-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
    margin-top: 32px;
}

.profile-card {
    background: #12121f;
    border: 1px solid rgba(255,255,255,0.06);
    border-radius: 16px;
    padding: 28px 24px;
}

.profile-card-title {
    font-weight: 700;
    color: #e8e8f0;
    font-size: 1.1rem;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
}

.profile-card-title i {
    color: #8b5cf6;
}

.form-group {
    margin-bottom: 16px;
}

.form-group label {
    display: block;
    font-size: 0.82rem;
    font-weight: 600;
    color: #8888aa;
    margin-bottom: 6px;
}

.form-group input {
    width: 100%;
    padding: 12px 16px;
    background: #1a1a2e;
    border: 1px solid rgba(255,255,255,0.08);
    border-radius: 10px;
    color: #e8e8f0;
    font-size: 0.95rem;
    transition: 0.3s ease;
    font-family: inherit;
}

.form-group input:focus {
    outline: none;
    border-color: #8b5cf6;
    box-shadow: 0 0 0 3px rgba(139,92,246,0.15);
}

.form-group input:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.form-group .input-hint {
    font-size: 0.75rem;
    color: #8888aa;
    margin-top: 4px;
}

.btn-primary {
    padding: 12px 28px;
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    color: #fff;
    border: none;
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.9rem;
    cursor: pointer;
    transition: 0.3s ease;
    font-family: inherit;
}

.btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(139,92,246,0.4);
}

.btn-secondary {
    padding: 10px 24px;
    background: transparent;
    color: #8888aa;
    border: 1px solid rgba(255,255,255,0.1);
    border-radius: 100px;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    transition: 0.3s ease;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 8px;
    font-family: inherit;
}

.btn-secondary:hover {
    border-color: rgba(139,92,246,0.3);
    color: #e8e8f0;
}

.alert {
    padding: 14px 18px;
    border-radius: 10px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 10px;
    font-size: 0.9rem;
}

.alert-success {
    background: rgba(34,197,94,0.1);
    border: 1px solid rgba(34,197,94,0.2);
    color: #22c55e;
}

.alert-error {
    background: rgba(239,68,68,0.1);
    border: 1px solid rgba(239,68,68,0.2);
    color: #ef4444;
}

.alert i {
    font-size: 1.1rem;
}

.profile-avatar-section {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.profile-avatar-large {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    background: linear-gradient(135deg, #8b5cf6, #6d28d9);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.2rem;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}

.profile-avatar-large img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.profile-avatar-info h3 {
    font-weight: 700;
    color: #e8e8f0;
    font-size: 1.2rem;
}

.profile-avatar-info p {
    color: #8888aa;
    font-size: 0.85rem;
}

.order-summary {
    margin-top: 20px;
    padding-top: 20px;
    border-top: 1px solid rgba(255,255,255,0.06);
}

.order-summary-item {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 0.85rem;
    color: #8888aa;
    border-bottom: 1px solid rgba(255,255,255,0.04);
}

.order-summary-item:last-child {
    border-bottom: none;
}

.order-summary-item span:last-child {
    color: #e8e8f0;
    font-weight: 600;
}

.divider {
    height: 1px;
    background: rgba(255,255,255,0.06);
    margin: 24px 0;
}

/* Back to Dashboard */
.back-link {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    color: #8b5cf6;
    text-decoration: none;
    font-size: 0.9rem;
    font-weight: 500;
    transition: 0.3s ease;
    margin-bottom: 16px;
}

.back-link:hover {
    color: #a78bfa;
    transform: translateX(-4px);
}

@media (max-width: 768px) {
    .profile-grid {
        grid-template-columns: 1fr;
    }
    .profile-header {
        flex-direction: column;
        align-items: flex-start;
    }
    .profile-avatar-section {
        flex-wrap: wrap;
    }
}

@media (max-width: 480px) {
    .profile-container {
        padding: 0 16px 40px;
    }
    .profile-card {
        padding: 20px 16px;
    }
}
</style>

<!-- ==========================================
     PROFILE CONTENT
     ========================================== -->
<div class="profile-wrap">
    <div class="profile-container">

        <!-- Back Link -->
        <a href="/SP/dashboard" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>

        <!-- Header -->
        <div class="profile-header">
            <div>
                <h1>My Profile</h1>
                <p>Manage your account settings and preferences</p>
            </div>
            <a href="/SP/logout" class="btn-secondary" style="color:#ef4444;border-color:rgba(239,68,68,0.2);">
                <i class="fas fa-sign-out-alt"></i> Logout
            </a>
        </div>

        <!-- Alert Messages -->
        <?php if (!empty($message)): ?>
            <div class="alert alert-<?php echo $messageType; ?>">
                <i class="fas fa-<?php echo $messageType === 'success' ? 'check-circle' : 'exclamation-circle'; ?>"></i>
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php endif; ?>

        <!-- Profile Grid -->
        <div class="profile-grid">

            <!-- LEFT: Edit Profile -->
            <div class="profile-card">
                <div class="profile-card-title">
                    <i class="fas fa-user-edit"></i> Edit Profile
                </div>

                <form method="POST" action="">
                    <div class="profile-avatar-section">
                        <div class="profile-avatar-large">
                            <?php 
                            $avatar = $user['avatar'] ?? '';
                            if (!empty($avatar)): ?>
                                <img src="<?php echo htmlspecialchars($avatar); ?>" alt="<?php echo htmlspecialchars($user['name'] ?? 'User'); ?>">
                            <?php else: ?>
                                <?php echo strtoupper(substr($user['name'] ?? 'U', 0, 1)); ?>
                            <?php endif; ?>
                        </div>
                        <div class="profile-avatar-info">
                            <h3><?php echo htmlspecialchars($user['name'] ?? 'User'); ?></h3>
                            <p><?php echo htmlspecialchars($user['email'] ?? ''); ?></p>
                            <span style="display:inline-block;padding:2px 12px;background:rgba(139,92,246,0.12);color:#8b5cf6;border-radius:100px;font-size:0.7rem;font-weight:600;margin-top:4px;">
                                <?php echo ucfirst($user['role'] ?? 'customer'); ?>
                            </span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input type="email" id="email" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" disabled>
                        <div class="input-hint">Email cannot be changed. Contact support if needed.</div>
                    </div>

                    <div class="form-group">
                        <label for="phone">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" placeholder="+92 300 1234567">
                    </div>

                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i> Update Profile
                    </button>
                </form>
            </div>

            <!-- RIGHT: Change Password + Stats -->
            <div>

                <!-- Change Password -->
                <div class="profile-card">
                    <div class="profile-card-title">
                        <i class="fas fa-lock"></i> Change Password
                    </div>

                    <form method="POST" action="">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" id="current_password" name="current_password" placeholder="Enter current password">
                        </div>

                        <div class="form-group">
                            <label for="new_password">New Password</label>
                            <input type="password" id="new_password" name="new_password" placeholder="Enter new password">
                            <div class="input-hint">Must be at least 6 characters</div>
                        </div>

                        <div class="form-group">
                            <label for="confirm_password">Confirm New Password</label>
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                        </div>

                        <button type="submit" class="btn-primary" style="background:linear-gradient(135deg,#f59e0b,#d97706);">
                            <i class="fas fa-key"></i> Change Password
                        </button>
                    </form>
                </div>

                <!-- Account Stats -->
                <div class="profile-card" style="margin-top:24px;">
                    <div class="profile-card-title">
                        <i class="fas fa-chart-pie"></i> Account Summary
                    </div>

                    <div class="order-summary">
                        <div class="order-summary-item">
                            <span>Total Orders</span>
                            <span><?php echo count($orders); ?></span>
                        </div>
                        <div class="order-summary-item">
                            <span>Member Since</span>
                            <span><?php echo date('M d, Y', strtotime($user['created_at'] ?? 'now')); ?></span>
                        </div>
                        <div class="order-summary-item">
                            <span>Account Type</span>
                            <span><?php echo ucfirst($user['role'] ?? 'customer'); ?></span>
                        </div>
                        <div class="order-summary-item">
                            <span>Status</span>
                            <span style="color:#22c55e;"><i class="fas fa-circle" style="font-size:0.5rem;"></i> Active</span>
                        </div>
                    </div>

                    <div style="display:flex;gap:10px;margin-top:16px;flex-wrap:wrap;">
                        <a href="/SP/orders" class="btn-secondary" style="flex:1;justify-content:center;">
                            <i class="fas fa-shopping-bag"></i> My Orders
                        </a>
                        <a href="/SP/dashboard" class="btn-secondary" style="flex:1;justify-content:center;">
                            <i class="fas fa-th-large"></i> Dashboard
                        </a>
                    </div>
                </div>

            </div>

        </div>

    </div>
</div>

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

<?php
// End of profile.php
?>
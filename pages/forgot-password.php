<?php
// ============================================
// PAGES: Forgot Password - Subhan Printers
// ============================================

// Set page variables
$pageTitle = 'Forgot Password | Subhan Printers – Reset Your Account';
$currentPage = 'forgot-password';
$pageStyles = 'forgot-password.css';
$pageScripts = 'forgot-password.js';
$pageDescription = 'Reset your Subhan Printers password - Recover your account access.';

// Include header (SIMPLE version - no login/register links)
require_once __DIR__ . '/../templates/header-simple.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: ' . base_url('dashboard'));
    exit;
}

// Get flash messages
$error = get_flash('error');
$success = get_flash('success');
$oldInput = get_flash('old_input') ?? [];
?>

<!-- ==========================================
     MAIN LAYOUT
     ========================================== -->
<main class="main-wrapper">
    <div class="forgot-container">

        <!-- Left Side -->
        <div class="left">

            <div class="logo-wrap">
                <img src="images/logo.png" alt="Logo" class="logo">
                <span class="logo-text"><span class="brand-first">Subhan</span> <span class="brand-second">Printers</span></span>
            </div>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <?php if ($success): ?>
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                <?php echo htmlspecialchars($success); ?>
            </div>
            <?php endif; ?>

            <!-- Reset Password Form -->
            <div id="resetFormSection" <?php echo $success ? 'style="display:none;"' : ''; ?>>
                <h1 class="welcome">Forgot <span>Password?</span></h1>

                <p class="subtitle">
                    No worries! Enter your email address and we'll send you a link to reset your password.
                </p>

                <form id="forgotForm" method="POST" action="<?php echo base_url('api/auth?action=forgot-password'); ?>" novalidate>

                    <div class="form-group">
                        <div class="input-wrap">
                            <input type="email" id="emailInput" name="email" placeholder="Email Address" autocomplete="email" required value="<?php echo htmlspecialchars($oldInput['email'] ?? ''); ?>">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="input-error" id="emailError">
                            <i class="fas fa-exclamation-circle"></i>
                            <span>Please enter a valid email address</span>
                        </div>
                    </div>

                    <button type="submit" class="reset-btn" id="resetBtn">
                        <i class="fas fa-paper-plane"></i> Send Reset Link
                    </button>

                    <div class="back-to-login">
                        <a href="<?php echo base_url('login'); ?>">
                            <i class="fas fa-arrow-left"></i> Back to Login
                        </a>
                    </div>

                </form>
            </div>

            <!-- Success Message -->
            <div class="success-message" id="successMessage" <?php echo $success ? 'class="success-message show"' : ''; ?>>
                <div class="success-icon">
                    <i class="fas fa-check"></i>
                </div>
                <h2>Check Your Email</h2>
                <p>We've sent a password reset link to <strong id="sentEmail"><?php echo htmlspecialchars($oldInput['email'] ?? ''); ?></strong>. Please check your inbox and follow the instructions to reset your password.</p>
                <button class="resend-btn" id="resendBtn">
                    <i class="fas fa-redo"></i> Resend Email
                </button>
                <div class="back-to-login" style="margin-top:25px;">
                    <a href="<?php echo base_url('login'); ?>">
                        <i class="fas fa-arrow-left"></i> Back to Login
                    </a>
                </div>
            </div>

        </div>

        <!-- Right Side -->
        <div class="right">
            <div class="right-bg"></div>
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>

            <div class="brand-box">
                <img src="images/logo.png" alt="Subhan Printers" class="brand-logo">
                <h2 class="brand-title">SUBHAN PRINTERS</h2>
                <p class="brand-description">
                    Professional graphic designing and printing services — wedding cards, packaging, flex banners & more since 1998.
                </p>

                <div class="brand-stats">
                    <div class="stat-item">
                        <div class="stat-num">25+</div>
                        <div class="stat-label">Years</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">10K+</div>
                        <div class="stat-label">Orders</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">5★</div>
                        <div class="stat-label">Rating</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Toast -->
<div class="toast" id="toast">
    <div class="toast-icon"><i class="fas fa-check"></i></div>
    <div class="toast-message">Reset link sent successfully!</div>
</div>

<!-- ==========================================
     JAVASCRIPT - Form handling
     ========================================== -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    // ==========================================
    // Header scroll effect
    // ==========================================
    const header = document.getElementById('headerHome');
    if (header) {
        window.addEventListener('scroll', function() {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
    }

    // ==========================================
    // Hamburger menu
    // ==========================================
    const hamburger = document.getElementById('hamburgerMenu');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileOverlay = document.getElementById('mobileOverlay');

    if (hamburger && mobileMenu && mobileOverlay) {
        function toggleMobile() {
            hamburger.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            mobileOverlay.classList.toggle('active');
            document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
        }

        hamburger.addEventListener('click', toggleMobile);
        mobileOverlay.addEventListener('click', toggleMobile);

        document.querySelectorAll('.mobile-menu a:not(#mobilePortfolioToggle)').forEach(function(link) {
            link.addEventListener('click', function() {
                if (mobileMenu.classList.contains('active')) {
                    toggleMobile();
                }
            });
        });

        // Mobile portfolio dropdown
        const mobilePortfolioToggle = document.getElementById('mobilePortfolioToggle');
        const mobilePortfolioDropdown = document.getElementById('mobilePortfolioDropdown');
        if (mobilePortfolioToggle && mobilePortfolioDropdown) {
            mobilePortfolioToggle.addEventListener('click', function(e) {
                e.preventDefault();
                mobilePortfolioDropdown.classList.toggle('show');
                const icon = this.querySelector('.fa-chevron-down');
                if (icon) {
                    icon.style.transform = mobilePortfolioDropdown.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
                }
            });
        }
    }

    // ==========================================
    // Form validation & AJAX submission
    // ==========================================
    const form = document.getElementById('forgotForm');
    const emailInput = document.getElementById('emailInput');
    const emailError = document.getElementById('emailError');
    const resetBtn = document.getElementById('resetBtn');
    const resetFormSection = document.getElementById('resetFormSection');
    const successMessage = document.getElementById('successMessage');
    const sentEmail = document.getElementById('sentEmail');
    const resendBtn = document.getElementById('resendBtn');

    function validateEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function showError(input, errorEl) {
        if (input) input.style.borderColor = '#ef4444';
        if (errorEl) errorEl.classList.add('show');
    }

    function clearError(input, errorEl) {
        if (input) input.style.borderColor = '';
        if (errorEl) errorEl.classList.remove('show');
    }

    if (emailInput) {
        emailInput.addEventListener('input', function() {
            clearError(this, emailError);
        });
    }

    // ==========================================
    // Toast notifications
    // ==========================================
    function showToast(message, type) {
        type = type || 'success';
        const toast = document.getElementById('toast');
        if (!toast) return;

        const icon = toast.querySelector('.toast-icon i');
        toast.className = 'toast ' + type;
        var msgEl = toast.querySelector('.toast-message');
        if (msgEl) msgEl.textContent = message;

        if (icon) {
            icon.className = type === 'success' ? 'fas fa-check' : 'fas fa-exclamation-triangle';
        }

        toast.classList.add('show');
        setTimeout(function() {
            toast.classList.remove('show');
        }, 4000);
    }

    // ==========================================
    // Form submission
    // ==========================================
    if (form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const email = emailInput.value.trim();

            if (!validateEmail(email)) {
                showError(emailInput, emailError);
                return;
            }

            // Show loading
            resetBtn.classList.add('loading');
            resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            // Send to API
            fetch('<?php echo base_url("api/auth?action=forgot-password"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(result) {
                resetBtn.classList.remove('loading');
                resetBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Reset Link';

                if (result.success) {
                    // Show success message
                    if (sentEmail) sentEmail.textContent = email;
                    if (resetFormSection) resetFormSection.style.display = 'none';
                    if (successMessage) successMessage.classList.add('show');
                    showToast(result.message || 'Reset link sent successfully!', 'success');
                } else {
                    showToast(result.message || 'Failed to send reset link. Please try again.', 'error');
                }
            })
            .catch(function(error) {
                resetBtn.classList.remove('loading');
                resetBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Reset Link';
                showToast('Network error. Please try again.', 'error');
                console.error('Forgot password error:', error);
            });
        });
    }

    // ==========================================
    // Resend button
    // ==========================================
    if (resendBtn) {
        resendBtn.addEventListener('click', function() {
            const email = emailInput ? emailInput.value.trim() : '';

            if (!email || !validateEmail(email)) {
                showToast('Please enter a valid email address', 'error');
                return;
            }

            resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

            fetch('<?php echo base_url("api/auth?action=forgot-password"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ email: email })
            })
            .then(function(response) {
                return response.json();
            })
            .then(function(result) {
                resendBtn.innerHTML = '<i class="fas fa-redo"></i> Resend Email';
                if (result.success) {
                    showToast('Reset link resent successfully!', 'success');
                } else {
                    showToast(result.message || 'Failed to resend. Please try again.', 'error');
                }
            })
            .catch(function(error) {
                resendBtn.innerHTML = '<i class="fas fa-redo"></i> Resend Email';
                showToast('Network error. Please try again.', 'error');
            });
        });
    }

    // ==========================================
    // Input focus effects
    // ==========================================
    document.querySelectorAll('.form-group input').forEach(function(input) {
        input.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
            this.parentElement.style.transition = 'transform .2s ease';
        });
        input.addEventListener('blur', function() {
            this.parentElement.style.transform = 'scale(1)';
        });
    });

});
</script>

</body>
</html>
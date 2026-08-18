<?php
// ============================================
// PAGES: Login - Subhan Printers
// ============================================

// Load functions FIRST
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/session.php';

// Check for logout success message
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    echo '<div class="alert alert-success" style="text-align:center;padding:12px;background:rgba(34,197,94,0.1);border:1px solid rgba(34,197,94,0.3);border-radius:8px;color:#22c55e;margin-bottom:16px;">
        <i class="fas fa-check-circle"></i> You have been logged out successfully!
    </div>';
}

// Set page variables
$pageTitle = 'Login | Subhan Printers – Access Your Account';
$currentPage = 'login';
$pageStyles = 'login.css';
$pageScripts = 'login.js';
$pageDescription = 'Login to Subhan Printers - Access your orders, quotations and printing projects.';

// Include header (SIMPLE version - no login/register links)
require_once __DIR__ . '/../templates/header-simple.php';

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['user_id'])) {
    header('Location: /SP/dashboard');
    exit;
}

// Get flash messages
$error = get_flash('error');
$oldInput = get_flash('old_input') ?? [];
?>


<!-- ==========================================
     MAIN LOGIN AREA
     ========================================== -->
<main class="main-wrapper">
    <div class="login-container">

        <!-- LEFT: FORM -->
        <div class="left">
            <div class="logo-wrap">
                <img src="/SP/images/logo.png" alt="Logo" class="logo">
                <span class="logo-text">
                    <span class="brand-first">Subhan</span> <span class="brand-second">Printers</span>
                </span>
            </div>

            <h2 class="welcome">Welcome <span>Back</span></h2>
            <p class="subtitle">Access your orders, quotations and printing projects instantly.</p>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form id="loginForm" method="POST" action="/SP/api/auth?action=login" novalidate>
                <!-- Email -->
                <div class="form-group">
                    <div class="input-wrap">
                        <input type="email" id="emailInput" name="email" placeholder="Email Address" autocomplete="email" value="<?php echo htmlspecialchars($oldInput['email'] ?? ''); ?>">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="input-error" id="emailError">
                        <i class="fas fa-exclamation-circle"></i><span>Please enter a valid email address</span>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <div class="input-wrap">
                        <input type="password" id="passwordInput" name="password" placeholder="Password" autocomplete="current-password">
                        <i class="fas fa-lock"></i>
                        <button type="button" class="password-toggle" id="passToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="input-error" id="passwordError">
                        <i class="fas fa-exclamation-circle"></i><span>Password must be at least 6 characters</span>
                    </div>
                </div>

                <!-- Options -->
                <div class="options">
                    <label class="remember-me">
                        <input type="checkbox" id="rememberMe" name="remember_me" <?php echo isset($oldInput['remember_me']) ? 'checked' : ''; ?>>
                        <span>Remember Me</span>
                    </label>
                    <a href="/SP/forgot-password">Forgot Password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="login-btn" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

                <div class="divider">OR CONTINUE WITH</div>

                <!-- Google Sign In -->
                <button type="button" class="google-btn" id="googleBtn">
                    <svg class="google-icon" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </button>

                <div class="signup">
                    Don't have an account? <a href="/SP/register">Create Account</a>
                </div>
            </form>
        </div>

        <!-- RIGHT: BRAND PANEL -->
        <div class="right">
            <div class="right-bg"></div>
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="brand-box">
                <img src="/SP/images/logo.png" alt="Subhan Printers" class="brand-logo">
                <h2 class="brand-title">SUBHAN PRINTERS</h2>
                <p class="brand-description">
                    Professional graphic designing and printing services — wedding cards, packaging, flex banners &amp; more since 1998.
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

<!-- Toast Notification -->
<div class="toast" id="toast">
    <div class="toast-icon"><i class="fas fa-check" id="toastIcon"></i></div>
    <div class="toast-message" id="toastMessage"></div>
</div>

<!-- ==========================================
     JAVASCRIPT - PHP LOGIN + FIREBASE GOOGLE
     ========================================== -->
<script type="module">
// ==========================================
// FIREBASE IMPORTS (Only for Google Sign-In)
// ==========================================
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

// ── Firebase Config ──
const firebaseConfig = {
    apiKey: "AIzaSyBMxz3V0yaaWIJh-Wg3fviHKYkS5Xo4uh0",
    authDomain: "subhanprinters.firebaseapp.com",
    projectId: "subhanprinters",
    storageBucket: "subhanprinters.firebasestorage.app",
    messagingSenderId: "71236166247",
    appId: "1:71236166247:web:8f68e3281ed26016c4aac2",
    measurementId: "G-R1MG8H1YB8"
};

// ── Initialize Firebase ──
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const provider = new GoogleAuthProvider();

// ── DOM Elements ──
const toast = document.getElementById('toast');
const toastIcon = document.getElementById('toastIcon');
const toastMessage = document.getElementById('toastMessage');

// ── Toast Helper ──
function showToast(message, type) {
    type = type || 'success';
    if (!toast) return;
    
    toast.className = 'toast ' + type;
    toastMessage.textContent = message;
    toastIcon.className = type === 'success' ? 'fas fa-check' : 'fas fa-exclamation-triangle';
    
    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}

// ── Password Toggle ──
const passToggle = document.getElementById('passToggle');
const passwordInput = document.getElementById('passwordInput');

if (passToggle && passwordInput) {
    passToggle.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        this.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    });
}

// ── Show/Hide Errors ──
const emailInput = document.getElementById('emailInput');
const emailError = document.getElementById('emailError');
const passwordError = document.getElementById('passwordError');

function showError(input, errorEl) {
    if (input) input.style.borderColor = '#ef4444';
    if (errorEl) errorEl.classList.add('show');
}

function clearError(input, errorEl) {
    if (input) input.style.borderColor = '';
    if (errorEl) errorEl.classList.remove('show');
}

if (emailInput && emailError) {
    emailInput.addEventListener('input', () => clearError(emailInput, emailError));
}

const passInput = document.getElementById('passwordInput');
if (passInput && passwordError) {
    passInput.addEventListener('input', () => clearError(passInput, passwordError));
}

// ==========================================
// EMAIL/PASSWORD LOGIN - PHP BACKEND
// ==========================================
const form = document.getElementById('loginForm');
const loginBtn = document.getElementById('loginBtn');

if (form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        const email = emailInput.value.trim();
        const password = passInput.value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        let valid = true;

        if (!emailRegex.test(email)) {
            showError(emailInput, emailError);
            valid = false;
        } else {
            clearError(emailInput, emailError);
        }

        if (password.length < 6) {
            showError(passInput, passwordError);
            valid = false;
        } else {
            clearError(passInput, passwordError);
        }

        if (!valid) return;

        loginBtn.classList.add('loading');
        loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';

        try {
            const response = await fetch('/SP/api/auth?action=login', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email: email, password: password })
            });

            const result = await response.json();

            if (result.success) {
                showToast('Welcome back, ' + (result.user.name || 'User') + '!', 'success');
                setTimeout(() => {
                    window.location.href = '/SP/dashboard';
                }, 1500);
            } else {
                showToast(result.message || 'Invalid email or password', 'error');
            }
        } catch (error) {
            showToast('Network error. Please try again.', 'error');
        } finally {
            loginBtn.classList.remove('loading');
            loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
        }
    });
}

// ==========================================
// ✅ GOOGLE SIGN-IN - FIREBASE (NEW)
// ==========================================
const googleBtn = document.getElementById('googleBtn');

if (googleBtn) {
    googleBtn.addEventListener('click', async function() {
        googleBtn.disabled = true;
        googleBtn.textContent = 'Signing in...';

        try {
            const result = await signInWithPopup(auth, provider);
            const user = result.user;

            // Get Firebase ID token
            const idToken = await user.getIdToken();

            // Send to PHP backend to create session
            const response = await fetch('/SP/api/auth?action=firebase', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    id_token: idToken,
                    name: user.displayName,
                    email: user.email,
                    avatar: user.photoURL
                })
            });

            const data = await response.json();

            if (data.success) {
                showToast('Welcome, ' + (user.displayName || user.email || 'User') + '!', 'success');
                setTimeout(() => {
                    window.location.href = '/SP/dashboard';
                }, 1500);
            } else {
                showToast(data.message || 'Google sign-in failed', 'error');
            }
        } catch (error) {
            let message = 'Google sign-in failed. Please try again.';
            if (error.code === 'auth/popup-closed-by-user') {
                message = 'Sign-in popup was closed.';
            } else if (error.code === 'auth/popup-blocked') {
                message = 'Popup blocked. Allow popups for this site.';
            }
            showToast(message, 'error');
        } finally {
            googleBtn.disabled = false;
            googleBtn.innerHTML = `
                <svg class="google-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue with Google
            `;
        }
    });
}

// ── Header Scroll Effect ──
const header = document.getElementById('headerHome');
if (header) {
    window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 50);
    });
}

// ── Mobile Menu ──
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
    
    document.querySelectorAll('.mobile-menu a').forEach(link => {
        link.addEventListener('click', () => {
            if (mobileMenu.classList.contains('active')) toggleMobile();
        });
    });
}

console.log('✅ Login loaded: PHP Email/Password + Firebase Google');
</script>

</body>
</html>
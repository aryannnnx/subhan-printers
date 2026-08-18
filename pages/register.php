<?php
// ============================================
// PAGES: Register - Subhan Printers
// ============================================

// Set page variables
$pageTitle = 'Register | Subhan Printers – Create Account';
$currentPage = 'register';
$pageStyles = 'register.css';
$pageScripts = 'register.js';
$pageDescription = 'Create your Subhan Printers account - Start ordering professional printing services.';

// Load functions FIRST
require_once __DIR__ . '/../includes/functions.php';

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
$oldInput = get_flash('old_input') ?? [];
?>

<!-- ==========================================
     MAIN LAYOUT
     ========================================== -->
<main class="main-wrapper">
    <div class="register-container">

        <!-- Left Side - Form -->
        <div class="left">

            <div class="logo-wrap">
                <img src="/SP/images/logo.png" alt="Logo" class="logo">
                <span class="logo-text"><span class="brand-first">Subhan</span> <span class="brand-second">Printers</span></span>
            </div>

            <h1 class="welcome">Create <span>Account</span></h1>

            <p class="subtitle">
                Join Subhan Printers and start ordering professional printing services today.
            </p>

            <?php if ($error): ?>
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?php echo htmlspecialchars($error); ?>
            </div>
            <?php endif; ?>

            <form id="registerForm" method="POST" action="/SP/api/auth?action=register" novalidate>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="text" id="nameInput" name="name" placeholder="Full Name" autocomplete="name" required value="<?php echo htmlspecialchars($oldInput['name'] ?? ''); ?>">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="input-error" id="nameError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Please enter your full name</span>
                    </div>
                </div>

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

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="password" id="passwordInput" name="password" placeholder="Password" autocomplete="new-password" required>
                        <i class="fas fa-lock"></i>
                        <button type="button" class="password-toggle" id="passToggle" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="strength-text" id="strengthText">Password strength</div>
                    </div>
                    <div class="input-error" id="passwordError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Password must be at least 6 characters</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="password" id="confirmPasswordInput" name="password_confirm" placeholder="Confirm Password" autocomplete="new-password" required>
                        <i class="fas fa-lock"></i>
                        <button type="button" class="password-toggle" id="confirmPassToggle" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="input-error" id="confirmPasswordError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Passwords do not match</span>
                    </div>
                </div>

                <label class="terms-check">
                    <input type="checkbox" id="termsCheck" name="terms" required>
                    <span>I agree to the <a href="<?php echo base_url('privacy'); ?>">Terms of Service</a> and <a href="<?php echo base_url('privacy'); ?>">Privacy Policy</a></span>
                </label>

                <button type="submit" class="register-btn" id="registerBtn">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>

                <div class="divider">OR CONTINUE WITH</div>

                <button type="button" class="google-btn" id="googleBtn">
                    <svg class="google-icon" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue With Google
                </button>

                <div class="login-link">
                    Already have an account?
                    <a href="<?php echo base_url('login'); ?>">Sign In</a>
                </div>

            </form>

        </div>

        <!-- Right Side - Brand -->
        <div class="right">
            <div class="right-bg"></div>
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>

            <div class="brand-box">
                <img src="/SP/images/logo.png" alt="Subhan Printers" class="brand-logo">
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

<!-- Toast Notification -->
<div class="toast" id="toast">
    <div class="toast-icon"><i class="fas fa-check" id="toastIcon"></i></div>
    <div class="toast-message" id="toastMessage"></div>
</div>

<!-- ==========================================
     JAVASCRIPT - Form handling + Firebase Google
     ========================================== -->
<script type="module">
// ==========================================
// FIREBASE IMPORTS (For Google Sign-In)
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
    
    toast.classList.add('show');
    setTimeout(() => {
        toast.classList.remove('show');
    }, 4000);
}

// ── Password Toggle ──
const passToggle = document.getElementById('passToggle');
const passwordInput = document.getElementById('passwordInput');
const confirmPassToggle = document.getElementById('confirmPassToggle');
const confirmPassInput = document.getElementById('confirmPasswordInput');

function setupPasswordToggle(toggleBtn, inputField) {
    if (!toggleBtn || !inputField) return;
    toggleBtn.addEventListener('click', function() {
        const isPassword = inputField.type === 'password';
        inputField.type = isPassword ? 'text' : 'password';
        this.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
    });
}

setupPasswordToggle(passToggle, passwordInput);
setupPasswordToggle(confirmPassToggle, confirmPassInput);

// ── Password Strength Meter ──
const strengthFill = document.getElementById('strengthFill');
const strengthText = document.getElementById('strengthText');
const passwordStrength = document.getElementById('passwordStrength');

if (passwordInput && strengthFill && strengthText && passwordStrength) {
    passwordInput.addEventListener('input', function() {
        const val = this.value;
        if (val.length === 0) {
            passwordStrength.classList.remove('show');
            return;
        }
        passwordStrength.classList.add('show');

        let score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^A-Za-z0-9]/.test(val)) score++;

        const levels = [
            { width: '20%', color: '#ef4444', text: 'Very Weak' },
            { width: '40%', color: '#f97316', text: 'Weak' },
            { width: '60%', color: '#eab308', text: 'Fair' },
            { width: '80%', color: '#84cc16', text: 'Strong' },
            { width: '100%', color: '#10b981', text: 'Very Strong' }
        ];

        const level = levels[Math.min(score, 4)];
        strengthFill.style.width = level.width;
        strengthFill.style.background = level.color;
        strengthText.textContent = level.text;
        strengthText.style.color = level.color;
    });
}

// ── Show/Hide Errors ──
const nameInput = document.getElementById('nameInput');
const emailInput = document.getElementById('emailInput');
const nameError = document.getElementById('nameError');
const emailError = document.getElementById('emailError');
const passwordError = document.getElementById('passwordError');
const confirmPasswordError = document.getElementById('confirmPasswordError');

function showError(input, errorEl) {
    if (input) input.style.borderColor = '#ef4444';
    if (errorEl) errorEl.classList.add('show');
}

function clearError(input, errorEl) {
    if (input) input.style.borderColor = '';
    if (errorEl) errorEl.classList.remove('show');
}

// Clear errors on input
[nameInput, emailInput, passwordInput, confirmPassInput].forEach((input, i) => {
    if (input) {
        const errors = [nameError, emailError, passwordError, confirmPasswordError];
        input.addEventListener('input', () => {
            if (errors[i]) clearError(input, errors[i]);
        });
    }
});

// ── Google Sign-In ──
const googleBtn = document.getElementById('googleBtn');

if (googleBtn) {
    googleBtn.addEventListener('click', async function() {
        googleBtn.disabled = true;
        googleBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connecting...';

        try {
            const result = await signInWithPopup(auth, provider);
            const user = result.user;

            // Get Firebase ID token
            const idToken = await user.getIdToken();

            // Send to PHP backend to create account/session
            const response = await fetch('/SP/api/auth?action=firebase-register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ 
                    id_token: idToken,
                    name: user.displayName || 'User',
                    email: user.email,
                    avatar: user.photoURL || null
                })
            });

            const data = await response.json();

            if (data.success) {
                showToast('Account created successfully! Welcome, ' + (user.displayName || user.email || 'User') + '!', 'success');
                setTimeout(() => {
                    window.location.href = '/SP/dashboard';
                }, 1500);
            } else {
                showToast(data.message || 'Registration failed. Please try again.', 'error');
            }
        } catch (error) {
            let message = 'Google sign-in failed. Please try again.';
            if (error.code === 'auth/popup-closed-by-user') {
                message = 'Sign-in popup was closed. Please try again.';
            } else if (error.code === 'auth/popup-blocked') {
                message = 'Popup blocked. Please allow popups for this site.';
            } else if (error.code === 'auth/account-exists-with-different-credential') {
                message = 'An account already exists with this email. Please login instead.';
            }
            showToast(message, 'error');
            console.error('Google Sign-In Error:', error);
        } finally {
            googleBtn.disabled = false;
            googleBtn.innerHTML = `
                <svg class="google-icon" viewBox="0 0 24 24">
                    <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                    <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                    <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                    <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                </svg>
                Continue With Google
            `;
        }
    });
}

// ── Form Validation & Submission ──
const form = document.getElementById('registerForm');
const registerBtn = document.getElementById('registerBtn');
const termsCheck = document.getElementById('termsCheck');

function validateEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
}

if (form) {
    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        let valid = true;

        // Validate name
        if (!nameInput || nameInput.value.trim().length < 2) {
            showError(nameInput, nameError);
            valid = false;
        }

        // Validate email
        if (!emailInput || !validateEmail(emailInput.value.trim())) {
            showError(emailInput, emailError);
            valid = false;
        }

        // Validate password
        if (!passwordInput || passwordInput.value.length < 6) {
            showError(passwordInput, passwordError);
            valid = false;
        }

        // Validate confirm password
        if (!confirmPassInput || confirmPassInput.value !== passwordInput.value || confirmPassInput.value === '') {
            showError(confirmPassInput, confirmPasswordError);
            valid = false;
        }

        // Validate terms
        if (!termsCheck || !termsCheck.checked) {
            showToast('Please accept the Terms of Service', 'error');
            valid = false;
        }

        if (!valid) return;

        // Prepare form data
        const formData = new FormData(form);
        const data = {
            name: formData.get('name'),
            email: formData.get('email'),
            password: formData.get('password'),
            password_confirm: formData.get('password_confirm')
        };

        // Show loading state
        registerBtn.classList.add('loading');
        registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';

        try {
            const response = await fetch('/SP/api/auth?action=register', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const result = await response.json();

            if (result.success) {
                showToast('Account created successfully! Redirecting...', 'success');
                setTimeout(() => {
                    window.location.href = '/SP/dashboard';
                }, 1500);
            } else {
                // Show validation errors
                if (result.errors) {
                    for (const field in result.errors) {
                        if (field === 'name') showError(nameInput, nameError);
                        if (field === 'email') showError(emailInput, emailError);
                        if (field === 'password') showError(passwordInput, passwordError);
                        if (field === 'password_confirm') showError(confirmPassInput, confirmPasswordError);
                    }
                    const firstError = result.errors[Object.keys(result.errors)[0]];
                    showToast(firstError ? firstError[0] : result.message, 'error');
                } else {
                    showToast(result.message || 'Registration failed', 'error');
                }
            }
        } catch (error) {
            showToast('Network error. Please try again.', 'error');
            console.error('Registration error:', error);
        } finally {
            registerBtn.classList.remove('loading');
            registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Create Account';
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

// ── Input focus effects ──
document.querySelectorAll('.form-group input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
        this.parentElement.style.transition = 'transform .2s ease';
    });
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});

console.log('✅ Register page loaded: Email/Password + Firebase Google');
</script>

</body>
</html>
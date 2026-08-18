// ============================================
// LOGIN - Pure PHP Backend (No Firebase)
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    console.log('✅ login.js loaded (PHP version)');

    // ── Toast Helper ──
    function showToast(message, type = 'success') {
        const toast = document.getElementById('toast');
        if (!toast) return;
        
        const icon = toast.querySelector('.toast-icon i');
        const msgEl = toast.querySelector('.toast-message');
        
        toast.className = 'toast ' + type;
        if (msgEl) msgEl.textContent = message;
        if (icon) {
            icon.className = type === 'success' ? 'fas fa-check' : 'fas fa-exclamation-triangle';
        }
        toast.classList.add('show');
        setTimeout(() => toast.classList.remove('show'), 4000);
    }

    // ── Password Toggle ──
    const passToggle = document.getElementById('passToggle');
    const passInput = document.getElementById('passwordInput');
    
    if (passToggle && passInput) {
        passToggle.addEventListener('click', function() {
            const isPassword = passInput.type === 'password';
            passInput.type = isPassword ? 'text' : 'password';
            this.innerHTML = isPassword ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });
    }

    // ── Login Form Submit ──
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', async function(e) {
            e.preventDefault();

            console.log('🔍 Login form submitted');

            const emailInput = document.getElementById('emailInput');
            const passwordInput = document.getElementById('passwordInput');
            const emailError = document.getElementById('emailError');
            const passwordError = document.getElementById('passwordError');
            const loginBtn = document.getElementById('loginBtn');

            if (!emailInput || !passwordInput || !loginBtn) {
                console.error('❌ Form elements not found!');
                return;
            }

            const email = emailInput.value.trim();
            const password = passwordInput.value;

            console.log('📧 Email:', email);
            console.log('🔑 Password length:', password.length);

            // ── Validate ──
            let valid = true;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

            if (!emailRegex.test(email)) {
                if (emailError) emailError.classList.add('show');
                valid = false;
            } else {
                if (emailError) emailError.classList.remove('show');
            }

            if (password.length < 6) {
                if (passwordError) passwordError.classList.add('show');
                valid = false;
            } else {
                if (passwordError) passwordError.classList.remove('show');
            }

            if (!valid) {
                console.log('❌ Validation failed');
                return;
            }

            // ── Show Loading ──
            loginBtn.classList.add('loading');
            loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';

            try {
                const requestData = {
                    email: email,
                    password: password
                };

                console.log('📤 Sending to PHP API:', requestData);

                // ✅ Send to PHP API (NOT Firebase!)
                const response = await fetch('/SP/api/auth?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                });

                console.log('📥 Response status:', response.status);

                const result = await response.json();
                console.log('📥 Response data:', result);

                if (result.success) {
    // ✅ Show session expiry info
    const timeoutMinutes = result.session_timeout / 60;
    showToast(`Welcome back, ${result.user.name}! Session expires in ${timeoutMinutes} minutes.`, 'success');
    
    const redirectUrl = result.redirect || '/SP/dashboard';
    
    setTimeout(() => {
        window.location.href = redirectUrl;
    }, 1500);
}else {
                    showToast(result.message || 'Invalid email or password', 'error');
                }
            } catch (error) {
                console.error('❌ Login error:', error);
                showToast('Network error. Please try again.', 'error');
            } finally {
                loginBtn.classList.remove('loading');
                loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
            }
        });
    } else {
        console.error('❌ Login form not found!');
    }

    // ── Google Sign-In ──
    const googleBtn = document.getElementById('googleBtn');
    if (googleBtn) {
        googleBtn.addEventListener('click', function() {
            showToast('Google Sign-In coming soon!', 'info');
        });
    }

    // ── Header Scroll Effect ──
    const header = document.getElementById('headerHome');
    if (header) {
        window.addEventListener('scroll', function() {
            header.classList.toggle('scrolled', window.scrollY > 50);
        }, { passive: true });
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

        document.querySelectorAll('.mobile-menu a').forEach(function(link) {
            link.addEventListener('click', function() {
                if (mobileMenu.classList.contains('active')) {
                    toggleMobile();
                }
            });
        });
    }
});
// ============================================
// SUBHAN PRINTERS - Forgot Password JS
// ============================================

// Header scroll effect
// ✅ FIXED: Added null check
const header = document.getElementById('headerHome');
if (header) {
    window.addEventListener('scroll', () => {
        header.classList.toggle('scrolled', window.scrollY > 50);
    }, { passive: true });
}

// Hamburger menu
// ✅ FIXED: Added null checks
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

    document.querySelectorAll('.mobile-menu a:not(#mobilePortfolioToggle)').forEach(link => {
        link.addEventListener('click', () => {
            if (mobileMenu.classList.contains('active')) toggleMobile();
        });
    });

    const mobilePortfolioToggle = document.getElementById('mobilePortfolioToggle');
    const mobilePortfolioDropdown = document.getElementById('mobilePortfolioDropdown');
    if (mobilePortfolioToggle && mobilePortfolioDropdown) {
        mobilePortfolioToggle.addEventListener('click', (e) => {
            e.preventDefault();
            mobilePortfolioDropdown.classList.toggle('show');
            const icon = mobilePortfolioToggle.querySelector('.fa-chevron-down');
            if (icon) {
                icon.style.transform = mobilePortfolioDropdown.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
            }
        });
    }
}

// Form validation
// ✅ FIXED: Added null checks
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

// Email input listener
if (emailInput && emailError) {
    emailInput.addEventListener('input', () => clearError(emailInput, emailError));
}

// Form submit
if (form && emailInput && resetBtn) {
    form.addEventListener('submit', (e) => {
        e.preventDefault();

        if (!validateEmail(emailInput.value.trim())) {
            showError(emailInput, emailError);
            return;
        }

        resetBtn.classList.add('loading');
        resetBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

        // Send to API
        const data = { email: emailInput.value.trim() };

        fetch('/SP/api/auth?action=forgot-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            resetBtn.classList.remove('loading');
            resetBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Reset Link';

            if (result.success) {
                // Show success message
                if (sentEmail) sentEmail.textContent = emailInput.value.trim();
                if (resetFormSection) resetFormSection.style.display = 'none';
                if (successMessage) successMessage.classList.add('show');
                showToast(result.message || 'Password reset link sent successfully!', 'success');
            } else {
                showToast(result.message || 'Failed to send reset link. Please try again.', 'error');
            }
        })
        .catch(error => {
            resetBtn.classList.remove('loading');
            resetBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Reset Link';
            showToast('Network error. Please try again.', 'error');
            console.error('Forgot password error:', error);
        });
    });
}

// Resend button
if (resendBtn && emailInput) {
    resendBtn.addEventListener('click', () => {
        const email = emailInput.value.trim();

        if (!email || !validateEmail(email)) {
            showToast('Please enter a valid email address', 'error');
            return;
        }

        resendBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';

        fetch('/SP/api/auth?action=forgot-password', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ email: email })
        })
        .then(response => response.json())
        .then(result => {
            resendBtn.innerHTML = '<i class="fas fa-redo"></i> Resend Email';
            if (result.success) {
                showToast('Reset link resent successfully!', 'success');
            } else {
                showToast(result.message || 'Failed to resend. Please try again.', 'error');
            }
        })
        .catch(error => {
            resendBtn.innerHTML = '<i class="fas fa-redo"></i> Resend Email';
            showToast('Network error. Please try again.', 'error');
            console.error('Resend error:', error);
        });
    });
}

// Toast notification
function showToast(message, type = 'success') {
    const toast = document.getElementById('toast');
    if (!toast) return;

    const icon = toast.querySelector('.toast-icon i');
    const msgEl = toast.querySelector('.toast-message');

    toast.className = 'toast ' + type;
    if (msgEl) msgEl.textContent = message;
    if (icon) {
        icon.className = type === 'success' ? 'fas fa-check' : 'fas fa-times';
    }
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3500);
}

// Input focus effects
if (emailInput) {
    emailInput.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
        this.parentElement.style.transition = 'transform .2s ease';
    });
    emailInput.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
}

console.log('✅ forgot-password.js loaded successfully!');
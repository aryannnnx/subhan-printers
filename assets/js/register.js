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

// Password toggles
// ✅ FIXED: Added null checks
const passToggle = document.getElementById('passToggle');
const passInput = document.getElementById('passwordInput');
const confirmPassToggle = document.getElementById('confirmPassToggle');
const confirmPassInput = document.getElementById('confirmPasswordInput');

function setupPasswordToggle(toggleBtn, inputField) {
    if (!toggleBtn || !inputField) return;
    toggleBtn.addEventListener('click', () => {
        const type = inputField.type === 'password' ? 'text' : 'password';
        inputField.type = type;
        toggleBtn.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
}

setupPasswordToggle(passToggle, passInput);
setupPasswordToggle(confirmPassToggle, confirmPassInput);

// Password strength meter
// ✅ FIXED: Added null checks
const strengthFill = document.getElementById('strengthFill');
const strengthText = document.getElementById('strengthText');
const passwordStrength = document.getElementById('passwordStrength');

if (passInput && strengthFill && strengthText && passwordStrength) {
    passInput.addEventListener('input', () => {
        const val = passInput.value;
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

// Form validation
// ✅ FIXED: Added null checks
const form = document.getElementById('registerForm');
const nameInput = document.getElementById('nameInput');
const emailInput = document.getElementById('emailInput');
const nameError = document.getElementById('nameError');
const emailError = document.getElementById('emailError');
const passwordError = document.getElementById('passwordError');
const confirmPasswordError = document.getElementById('confirmPasswordError');
const registerBtn = document.getElementById('registerBtn');
const termsCheck = document.getElementById('termsCheck');

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

// Clear errors on input
if (nameInput && emailInput && passInput && confirmPassInput) {
    const inputs = [nameInput, emailInput, passInput, confirmPassInput];
    const errors = [nameError, emailError, passwordError, confirmPasswordError];
    inputs.forEach((input, i) => {
        if (input && errors[i]) {
            input.addEventListener('input', () => clearError(input, errors[i]));
        }
    });
}

// Form submit
if (form && nameInput && emailInput && passInput && confirmPassInput && registerBtn) {
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        let valid = true;

        if (nameInput.value.trim().length < 2) {
            showError(nameInput, nameError);
            valid = false;
        }
        
        if (!validateEmail(emailInput.value.trim())) {
            showError(emailInput, emailError);
            valid = false;
        }
        
        if (passInput.value.length < 6) {
            showError(passInput, passwordError);
            valid = false;
        }
        
        if (confirmPassInput.value !== passInput.value || confirmPassInput.value === '') {
            showError(confirmPassInput, confirmPasswordError);
            valid = false;
        }
        
        if (!termsCheck || !termsCheck.checked) {
            showToast('Please accept the Terms of Service', 'error');
            valid = false;
        }

        if (valid) {
            registerBtn.classList.add('loading');
            registerBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Creating account...';
            
            // Send to API
            const formData = new FormData(form);
            const data = {
                name: formData.get('name'),
                email: formData.get('email'),
                password: formData.get('password'),
                password_confirm: formData.get('password_confirm')
            };

            fetch('/SP/api/auth?action=register', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(result => {
                registerBtn.classList.remove('loading');
                registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Create Account';

                if (result.success) {
                    showToast('Account created successfully! Redirecting...', 'success');
                    setTimeout(() => {
                        window.location.href = '/SP/dashboard';
                    }, 2000);
                } else {
                    showToast(result.message || 'Registration failed', 'error');
                }
            })
            .catch(error => {
                registerBtn.classList.remove('loading');
                registerBtn.innerHTML = '<i class="fas fa-user-plus"></i> Create Account';
                showToast('Network error. Please try again.', 'error');
                console.error('Registration error:', error);
            });
        }
    });
}

// Google Sign-Up
// ✅ FIXED: Added null check
const googleBtn = document.getElementById('googleBtn');
if (googleBtn) {
    googleBtn.addEventListener('click', () => {
        showToast('Google Sign-Up coming soon!', 'success');
    });
}

// Toast
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
document.querySelectorAll('.form-group input').forEach(input => {
    input.addEventListener('focus', function() {
        this.parentElement.style.transform = 'scale(1.02)';
        this.parentElement.style.transition = 'transform .2s ease';
    });
    input.addEventListener('blur', function() {
        this.parentElement.style.transform = 'scale(1)';
    });
});

console.log('✅ register.js loaded successfully!');
// ============================================
// DASHBOARD JS - Subhan Printers
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    'use strict';

    console.log('✅ Dashboard JS loaded');

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

    // ── Toast Helper ──
    function showToast(message, type) {
        type = type || 'success';
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
        setTimeout(function() {
            toast.classList.remove('show');
        }, 4000);
    }

    console.log('✅ Dashboard JS ready');

});
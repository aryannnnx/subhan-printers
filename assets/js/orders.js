(function() {
    'use strict';

    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

    /* ── Navbar ── */
    // ✅ FIXED: Added null check
    const nav = $('#navbar');
    if (nav) {
        window.addEventListener('scroll', () => {
            nav.classList.toggle('scrolled', window.scrollY > 60);
        }, { passive: true });
        if (window.scrollY > 60) nav.classList.add('scrolled');
    }

    /* ── Hamburger ── */
    // ✅ FIXED: Added null checks
    const hbtn = $('#hamburger-btn');
    const mm = $('#mobile-menu');
    
    if (hbtn && mm) {
        hbtn.addEventListener('click', () => {
            const o = mm.classList.toggle('open');
            hbtn.classList.toggle('open', o);
            hbtn.setAttribute('aria-expanded', String(o));
        });
        
        $$('a', mm).forEach(a => a.addEventListener('click', () => {
            mm.classList.remove('open');
            hbtn.classList.remove('open');
            hbtn.setAttribute('aria-expanded', 'false');
        }));
    }

    /* ── Dropdown ── */
    const dd = $('#portfolioDropdown');
    const dt = $('#portfolioToggle');
    if (dd && dt) {
        dt.addEventListener('click', e => {
            e.stopPropagation();
            const o = dd.classList.toggle('open');
            dt.setAttribute('aria-expanded', String(o));
        });
        document.addEventListener('click', () => {
            dd.classList.remove('open');
            dt.setAttribute('aria-expanded', 'false');
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                dd.classList.remove('open');
                dt.setAttribute('aria-expanded', 'false');
            }
        });
    }

    /* ── Smooth scroll ── */
    $$('a[href^="#"]').forEach(a => {
        a.addEventListener('click', e => {
            const t = $(a.getAttribute('href'));
            if (t) {
                e.preventDefault();
                t.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        });
    });

    /* ── Reveal on scroll ── */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(en => {
            if (en.isIntersecting) {
                en.target.classList.add('visible');
                ro.unobserve(en.target);
            }
        });
    }, { threshold: .08, rootMargin: '0px 0px -40px 0px' });
    
    $$('.reveal').forEach(el => ro.observe(el));

    /* ── Footer year ── */
    const yr = $('#footer-year');
    if (yr) yr.textContent = new Date().getFullYear();

    console.log('✅ orders.js loaded successfully!');

})();
(function () {
    'use strict';

    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

    /* ── Navbar scroll ── */
    // ✅ FIXED: Added null check
    const navbar = $('#navbar');
    const handleScroll = () => {
    if (navbar) {
        navbar.classList.toggle('scrolled', window.scrollY > 60);
    }
};
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

   

    /* ── Hamburger / mobile menu ── */
    // ✅ FIXED: Added null checks
    const hamburger = $('#hamburger-btn');
    const mobileMenu = $('#mobile-menu');
    
    if (hamburger && mobileMenu) {
        hamburger.addEventListener('click', () => {
            const open = mobileMenu.classList.toggle('open');
            hamburger.classList.toggle('open', open);
            hamburger.setAttribute('aria-expanded', String(open));
        });
        $$('a', mobileMenu).forEach(a => a.addEventListener('click', () => {
            mobileMenu.classList.remove('open');
            hamburger.classList.remove('open');
            hamburger.setAttribute('aria-expanded', 'false');
        }));
    }

    /* ── Portfolio dropdown ── */
    (function () {
        const dd = $('#portfolioDropdown');
        const toggle = $('#portfolioToggle');
        if (!dd || !toggle) return;
        toggle.addEventListener('click', e => {
            e.stopPropagation();
            const open = dd.classList.toggle('open');
            toggle.setAttribute('aria-expanded', String(open));
        });
        document.addEventListener('click', () => {
            dd.classList.remove('open');
            toggle.setAttribute('aria-expanded', 'false');
        });
        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') {
                dd.classList.remove('open');
                toggle.setAttribute('aria-expanded', 'false');
            }
        });
    }());

    /* ── Reveal on scroll ── */
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => {
            if (e.isIntersecting) {
                e.target.classList.add('visible');
                obs.unobserve(e.target);
            }
        });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    $$('.reveal').forEach(el => obs.observe(el));

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

    /* ── Category tab filter ── */
    const tabs = $$('.svc-tab');
    const catSections = $$('.cat-section');
    const allCards = $$('.svc-card');

    function filterByTab(filterValue) {
        // First, show everything
        catSections.forEach(sec => { sec.style.display = ''; });
        allCards.forEach(card => { card.style.display = ''; });

        // If filter is not 'all', hide non-matching sections AND non-matching cards
        if (filterValue !== 'all') {
            // Hide sections that don't match the category
            catSections.forEach(sec => {
                const sectionCat = sec.dataset.cat;
                if (sectionCat !== filterValue) {
                    sec.style.display = 'none';
                }
            });

            // Hide cards that don't match the category
            allCards.forEach(card => {
                const cardCats = (card.dataset.cat || '').split(' ');
                if (!cardCats.includes(filterValue)) {
                    card.style.display = 'none';
                }
            });
        }
    }

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            // Update active state
            tabs.forEach(t => {
                t.classList.remove('active');
                t.setAttribute('aria-selected', 'false');
            });
            tab.classList.add('active');
            tab.setAttribute('aria-selected', 'true');

            const filterValue = tab.dataset.tab;
            filterByTab(filterValue);
        });
    });

    // Initialize with 'all' filter
    filterByTab('all');

    /* ── FAQ accordion ── */
    $$('[data-faq]').forEach(item => {
        const btn = item.querySelector('.faq-question');
        if (btn) {
            btn.addEventListener('click', () => {
                const isOpen = item.classList.contains('open');
                // close all others
                $$('[data-faq].open').forEach(o => {
                    o.classList.remove('open');
                    const q = o.querySelector('.faq-question');
                    if (q) q.setAttribute('aria-expanded', 'false');
                });
                if (!isOpen) {
                    item.classList.add('open');
                    btn.setAttribute('aria-expanded', 'true');
                } else {
                    btn.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });

    /* ── Footer year ── */
    const yr = $('#footer-year');
    if (yr) yr.textContent = new Date().getFullYear();

    /* ── Fix for card heights on window resize ── */
    let resizeTimer;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimer);
        resizeTimer = setTimeout(function() {
            // Re-apply current filter to ensure proper layout
            const activeTab = $('.svc-tab.active');
            if (activeTab) {
                filterByTab(activeTab.dataset.tab);
            }
        }, 250);
    });

    console.log('✅ services.js loaded successfully!');

}());
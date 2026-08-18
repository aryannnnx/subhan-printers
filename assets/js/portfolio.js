(function() {
    'use strict';

    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

    /* ── Navbar scroll ── */
    // ✅ FIXED: Added null check
    const nav = $('#navbar');
    if (nav) {
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 60);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ── Hamburger ── */
    // ✅ FIXED: Added null checks
    const hbtn = $('#hamburger-btn');
    const mmenu = $('#mobile-menu');
    
    if (hbtn && mmenu) {
        hbtn.addEventListener('click', () => {
            const o = mmenu.classList.toggle('open');
            hbtn.classList.toggle('open', o);
            hbtn.setAttribute('aria-expanded', String(o));
        });
        
        $$('a', mmenu).forEach(a => a.addEventListener('click', () => {
            mmenu.classList.remove('open');
            hbtn.classList.remove('open');
            hbtn.setAttribute('aria-expanded', 'false');
        }));
    }

    /* ── Portfolio dropdown ── */
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

    /* ── Portfolio filter ── */
    const tabs = $$('.f-tab');
    const cards = $$('.p-card');
    const countEl = $('#visible-count');

    function applyFilter(filter) {
        let count = 0;
        cards.forEach(c => {
            const cats = (c.dataset.cat || '').split(' ');
            const show = filter === 'all' || cats.includes(filter);
            c.classList.toggle('visible', show);
            if (show) count++;
        });
        if (countEl) countEl.textContent = count;
    }

    tabs.forEach(btn => {
        btn.addEventListener('click', () => {
            tabs.forEach(b => {
                b.classList.remove('active');
                b.setAttribute('aria-selected', 'false');
            });
            btn.classList.add('active');
            btn.setAttribute('aria-selected', 'true');
            applyFilter(btn.dataset.filter);
        });
    });

    /* Auto-filter from URL param: portfolio.php?cat=packaging */
    const urlCat = new URLSearchParams(window.location.search).get('cat');
    if (urlCat) {
        const matchBtn = tabs.find(b => b.dataset.filter === urlCat);
        if (matchBtn) matchBtn.click();
    }

    /* ── Lightbox ── */
    const lb = $('#lightbox');
    const lbImg = $('#lb-img');
    const lbCat = $('#lb-cat');
    const lbTitle = $('#lb-title');
    const lbDesc = $('#lb-desc');
    const lbClose = $('#lb-close');
    const lbPrev = $('#lb-prev');
    const lbNext = $('#lb-next');

    let activeIdx = 0;

    // Only initialize lightbox if elements exist
    if (lb && lbImg && lbCat && lbTitle && lbDesc && lbClose && lbPrev && lbNext) {
        
        function visibleCards() {
            return cards.filter(c => c.classList.contains('visible'));
        }

        function openLightbox(card) {
            const vc = visibleCards();
            activeIdx = vc.indexOf(card);
            populateLb(card);
            lb.classList.add('open');
            document.body.style.overflow = 'hidden';
        }

        function populateLb(card) {
            lbImg.src = card.dataset.img || '';
            lbImg.alt = card.dataset.title || '';
            
            const catEl = card.querySelector('.p-card-cat');
            lbCat.textContent = catEl ? catEl.textContent : '';
            lbTitle.textContent = card.dataset.title || '';
            lbDesc.textContent = card.dataset.desc || '';
            
            const vc = visibleCards();
            lbPrev.style.opacity = activeIdx <= 0 ? '.3' : '1';
            lbNext.style.opacity = activeIdx >= vc.length - 1 ? '.3' : '1';
        }

        function closeLightbox() {
            lb.classList.remove('open');
            document.body.style.overflow = '';
        }

        cards.forEach(c => {
            c.addEventListener('click', () => openLightbox(c));
            c.addEventListener('keydown', e => {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    openLightbox(c);
                }
            });
        });

        lbClose.addEventListener('click', closeLightbox);
        lb.addEventListener('click', e => {
            if (e.target === lb) closeLightbox();
        });

        lbPrev.addEventListener('click', () => {
            if (activeIdx <= 0) return;
            activeIdx--;
            populateLb(visibleCards()[activeIdx]);
        });

        lbNext.addEventListener('click', () => {
            const vc = visibleCards();
            if (activeIdx >= vc.length - 1) return;
            activeIdx++;
            populateLb(vc[activeIdx]);
        });

        document.addEventListener('keydown', e => {
            if (!lb.classList.contains('open')) return;
            if (e.key === 'Escape') closeLightbox();
            if (e.key === 'ArrowLeft') lbPrev.click();
            if (e.key === 'ArrowRight') lbNext.click();
        });
    }

    /* ── Footer year ── */
    const yr = $('#footer-year');
    if (yr) yr.textContent = new Date().getFullYear();

    console.log('✅ portfolio.js loaded successfully!');

})();
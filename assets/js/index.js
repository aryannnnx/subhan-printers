(function () {
  'use strict';

  /* ── Helpers ─────────────────────────────────────────── */
  const $ = (sel, ctx = document) => ctx.querySelector(sel);
  const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

  /* ── Portfolio dropdown ──────────────────────────────── */
  (function initDropdown () {
    const dropdown = $('#portfolioDropdown');
    const toggle   = $('#portfolioToggle');
    if (!dropdown || !toggle) return;

    toggle.addEventListener('click', (e) => {
      e.stopPropagation();
      const isOpen = dropdown.classList.toggle('open');
      toggle.setAttribute('aria-expanded', String(isOpen));
    });

    document.addEventListener('click', () => {
      dropdown.classList.remove('open');
      toggle.setAttribute('aria-expanded', 'false');
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        dropdown.classList.remove('open');
        toggle.setAttribute('aria-expanded', 'false');
      }
    });
  }());

  /* ── Navbar: scroll effect ───────────────────────────── */
  // ✅ FIXED: Added null check
  const navbar = $('#navbar');
  function handleNavScroll () {
    if (navbar) {
      navbar.classList.toggle('scrolled', window.scrollY > 60);
    }
  }
  window.addEventListener('scroll', handleNavScroll, { passive: true });
  handleNavScroll();

  /* ── Mobile menu ─────────────────────────────────────── */
  // ✅ FIXED: Added null check
  const hamburger  = $('#hamburger-btn');
  const mobileMenu = $('#mobile-menu');

  if (hamburger && mobileMenu) {
    hamburger.addEventListener('click', () => {
      const isOpen = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', isOpen);
      hamburger.setAttribute('aria-expanded', String(isOpen));
    });

    $$('a', mobileMenu).forEach(a => {
      a.addEventListener('click', () => {
        mobileMenu.classList.remove('open');
        hamburger.classList.remove('open');
        hamburger.setAttribute('aria-expanded', 'false');
      });
    });
  }

  /* ── Smooth scroll for in-page anchors ───────────────── */
  $$('a[href^="#"]').forEach(link => {
    link.addEventListener('click', e => {
      const target = $(link.getAttribute('href'));
      if (target) {
        e.preventDefault();
        target.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });
  });

  /* ── Reveal on scroll (IntersectionObserver) ─────────── */
  const revealObserver = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        revealObserver.unobserve(entry.target);
      }
    });
  }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });

  $$('.reveal').forEach(el => revealObserver.observe(el));

  /* ── Portfolio filter ────────────────────────────────── */
  const filterBtns    = $$('.filter-btn');
  const portfolioItems = $$('.portfolio-item');

  if (filterBtns.length > 0 && portfolioItems.length > 0) {
    function filterPortfolio (category) {
      portfolioItems.forEach(item => {
        const match = category === 'all' || item.dataset.category === category;
        item.classList.toggle('hidden', !match);
      });
    }

    filterBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        filterBtns.forEach(b => { b.classList.remove('active'); b.setAttribute('aria-selected','false'); });
        btn.classList.add('active');
        btn.setAttribute('aria-selected','true');
        filterPortfolio(btn.dataset.filter);
      });
    });

    portfolioItems.forEach(item => {
      item.addEventListener('keydown', e => {
        if (e.key === 'Enter' || e.key === ' ') {
          e.preventDefault();
          console.info('[Portfolio] Open item:', item.dataset.id);
        }
      });
    });
  }

  /* ── Category card navigation ────────────────────────── */
  $$('.cat-card[data-category]').forEach(card => {
    card.addEventListener('click', () => {
      console.info('[Category] Navigate to:', card.dataset.category);
    });
  });

  $$('.cat3-card[data-cat3]').forEach(card => {
    card.addEventListener('click', () => {
      console.info('[Cat3] Navigate to:', card.dataset.cat3);
    });
  });

  /* ── Comparison slider ───────────────────────────────── */
  (function initComparisonSlider () {
    const container    = $('#cs-container');
    const rightWrapper = $('#cs-right-wrapper');
    const handle       = $('#cs-handle');
    if (!container || !rightWrapper || !handle) return;

    let dragging = false;

    function setPosition (clientX) {
      const rect   = container.getBoundingClientRect();
      const pct    = Math.min(100, Math.max(0, ((clientX - rect.left) / rect.width) * 100));
      rightWrapper.style.width = pct + '%';
      handle.style.left        = pct + '%';
    }

    function onStart (e) {
      dragging = true;
      document.body.style.cursor    = 'col-resize';
      document.body.style.userSelect = 'none';
      setPosition(e.touches ? e.touches[0].clientX : e.clientX);
      e.preventDefault();
    }
    function onMove (e) {
      if (!dragging) return;
      setPosition(e.touches ? e.touches[0].clientX : e.clientX);
    }
    function onEnd () {
      dragging = false;
      document.body.style.cursor     = '';
      document.body.style.userSelect = '';
    }

    container.addEventListener('mousedown',  onStart);
    container.addEventListener('touchstart', onStart, { passive: false });
    window.addEventListener('mousemove',  onMove);
    window.addEventListener('touchmove',  onMove, { passive: false });
    window.addEventListener('mouseup',    onEnd);
    window.addEventListener('touchend',   onEnd);
    window.addEventListener('touchcancel',onEnd);

    container.querySelectorAll('img').forEach(img => img.addEventListener('dragstart', e => e.preventDefault()));

    setPosition(container.getBoundingClientRect().left + container.offsetWidth / 2);
  }());

  /* ── Testimonial scroll track ────────────────────────── */
  (function initTestimonialScroll () {
    const track     = $('#t-scroll-track');
    const btnLeft   = $('#t-scroll-left');
    const btnRight  = $('#t-scroll-right');
    if (!track || !btnLeft || !btnRight) return;

    const SCROLL_BY = 300;

    btnLeft.addEventListener('click', () => track.scrollBy({ left: -SCROLL_BY, behavior: 'smooth' }));
    btnRight.addEventListener('click',() => track.scrollBy({ left:  SCROLL_BY, behavior: 'smooth' }));

    function updateArrows () {
      btnLeft.style.opacity  = track.scrollLeft <= 0 ? '0.4' : '1';
      btnRight.style.opacity = track.scrollLeft >= track.scrollWidth - track.clientWidth - 5 ? '0.4' : '1';
    }
    track.addEventListener('scroll', updateArrows, { passive: true });
    updateArrows();
  }());

  /* ── Newsletter form ─────────────────────────────────── */
  // ── Newsletter Form ──
const newsletterForm = document.getElementById('newsletter-form');
const newsletterMsg = document.getElementById('newsletter-msg');

if (newsletterForm) {
    newsletterForm.addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const emailInput = this.querySelector('input[type="email"]');
        const email = emailInput.value.trim();
        
        // Validate email
        if (!email || !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            if (newsletterMsg) {
                newsletterMsg.textContent = 'Please enter a valid email address.';
                newsletterMsg.style.color = '#ef4444';
            }
            return;
        }
        
        // Show loading
        if (newsletterMsg) {
            newsletterMsg.textContent = 'Subscribing...';
            newsletterMsg.style.color = '#8888aa';
        }
        
        try {
            const response = await fetch('/SP/api/newsletter', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    email: email, 
                    source: 'website' 
                })
            });
            
            // Parse response
            const data = await response.json();
            console.log('Newsletter response:', data);
            
            if (newsletterMsg) {
                if (data.success) {
                    newsletterMsg.textContent = '✅ ' + (data.message || 'Subscribed successfully!');
                    newsletterMsg.style.color = '#22c55e';
                    this.reset();
                } else {
                    newsletterMsg.textContent = '❌ ' + (data.message || 'Subscription failed. Please try again.');
                    newsletterMsg.style.color = '#ef4444';
                }
            }
        } catch (error) {
            console.error('Newsletter error:', error);
            if (newsletterMsg) {
                newsletterMsg.textContent = '❌ Network error. Please try again.';
                newsletterMsg.style.color = '#ef4444';
            }
        }
    });
}

  /* ── Footer year ─────────────────────────────────────── */
  const yearEl = $('#footer-year');
  if (yearEl) yearEl.textContent = new Date().getFullYear();

  /* ── Hero image slider ───────────────────────────────── */
  (function initHeroSlider () {
    const slides    = $$('.hero-slide');
    const dotsWrap  = $('#heroSliderDots');
    const titleEl   = $('#heroSlideTitle');
    const subEl     = $('#heroSlideSub');
    if (!slides.length || !dotsWrap) return;

    let current  = 0;
    let timer    = null;
    const DELAY  = 3500;

    slides.forEach((_, i) => {
      const dot = document.createElement('button');
      dot.className = 'hsd' + (i === 0 ? ' active' : '');
      dot.setAttribute('aria-label', 'Go to slide ' + (i + 1));
      dot.addEventListener('click', () => { goTo(i); resetTimer(); });
      dotsWrap.appendChild(dot);
    });

    const dots = $$('.hsd', dotsWrap);

    function goTo (idx) {
      slides[current].classList.remove('active');
      dots[current].classList.remove('active');

      current = (idx + slides.length) % slides.length;

      slides[current].classList.add('active');
      dots[current].classList.add('active');

      const s = slides[current];
      if (titleEl && subEl) {
        titleEl.style.opacity = '0';
        subEl.style.opacity   = '0';
        setTimeout(() => {
          titleEl.innerHTML    = s.dataset.title || '';
          subEl.textContent    = s.dataset.sub   || '';
          titleEl.style.opacity = '1';
          subEl.style.opacity   = '1';
        }, 300);
      }
    }

    function next () { goTo(current + 1); }

    function resetTimer () {
      clearInterval(timer);
      timer = setInterval(next, DELAY);
    }

    const wrap = $('#heroSliderWrap');
    if (wrap) {
      wrap.addEventListener('mouseenter', () => clearInterval(timer));
      wrap.addEventListener('mouseleave', resetTimer);
    }

    resetTimer();
  }());

  console.log('✅ Subhan Printers JavaScript loaded successfully!');

}());
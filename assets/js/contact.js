(function() {
    'use strict';

    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

    /* ── Navbar ── */
    const nav = $('#navbar');
    if (nav) {
        const onScroll = () => nav.classList.toggle('scrolled', window.scrollY > 60);
        window.addEventListener('scroll', onScroll, { passive: true });
        onScroll();
    }

    /* ── Hamburger ── */
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

    /* ── Reveal ── */
    const ro = new IntersectionObserver(entries => {
        entries.forEach(en => {
            if (en.isIntersecting) {
                en.target.classList.add('visible');
                ro.unobserve(en.target);
            }
        });
    }, { threshold: .08, rootMargin: '0px 0px -40px 0px' });
    
    $$('.reveal').forEach(el => ro.observe(el));

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

    /* ── Business Hours Table ── */
    (function() {
        const hoursEl = $('#hours-table');
        if (!hoursEl) return;
        const days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        const schedule = {
            0: ['10:00 AM', '6:00 PM'],
            1: ['9:00 AM', '8:00 PM'],
            2: ['9:00 AM', '8:00 PM'],
            3: ['9:00 AM', '8:00 PM'],
            4: ['9:00 AM', '8:00 PM'],
            5: ['9:00 AM', '8:00 PM'],
            6: ['9:00 AM', '8:00 PM'],
        };
        const todayIdx = new Date().getDay();
        let html = '';
        Object.entries(schedule).forEach(([d, hrs]) => {
            const isToday = parseInt(d) === todayIdx;
            html += `<div class="hours-row">
                <span class="day ${isToday ? 'today' : ''}">${isToday ? 'Today (' + days[d] + ')' : days[d]}</span>
                <span class="time">${hrs[0]} – ${hrs[1]}</span>
            </div>`;
        });
        hoursEl.innerHTML = html;
    }());

    /* ── Budget Slider ── */
    (function() {
        const slider = $('#budget-slider');
        const valEl = $('#budget-val');
        if (!slider || !valEl) return;
        const fmt = v => v >= 200000 ? '₨ 200,000+' : '₨ ' + Number(v).toLocaleString('en-PK');
        slider.addEventListener('input', () => {
            valEl.textContent = fmt(slider.value);
        });
    }());

    /* ── Urgency Buttons ── */
    $$('.urg-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            $$('.urg-btn').forEach(b => b.classList.remove('selected'));
            btn.classList.add('selected');
            const h = $('#urgency-val');
            if (h) h.value = btn.dataset.urg;
        });
    });

    /* ── Char Counter ── */
    (function() {
        const ta = $('#details');
        const counter = $('#details-counter');
        if (!ta || !counter) return;
        ta.addEventListener('input', () => {
            const l = ta.value.length;
            const max = 1000;
            counter.textContent = l + ' / ' + max;
            counter.className = 'char-counter' + (l > 900 ? ' over' : l > 700 ? ' warn' : '');
        });
    }());

    /* ── File Upload ── */
    (function() {
        const zone = $('#file-drop-zone');
        const input = $('#file-input');
        const list = $('#file-list');
        if (!zone || !input || !list) return;
        let files = [];

        function renderFiles() {
            list.innerHTML = '';
            files.forEach((f, i) => {
                const item = document.createElement('div');
                item.className = 'file-item';
                item.innerHTML = `<i class="fas fa-file"></i> <span>${f.name}</span> <span style="color:var(--clr-muted);font-size:.72rem">(${(f.size / 1024).toFixed(0)}KB)</span> <button type="button" class="remove-file" aria-label="Remove ${f.name}" data-idx="${i}"><i class="fas fa-times"></i></button>`;
                list.appendChild(item);
            });
            $$('.remove-file', list).forEach(btn => {
                btn.addEventListener('click', () => {
                    files.splice(parseInt(btn.dataset.idx), 1);
                    renderFiles();
                });
            });
        }

        input.addEventListener('change', () => {
            files = [...files, ...Array.from(input.files)].slice(0, 10);
            renderFiles();
            input.value = '';
        });
        zone.addEventListener('dragover', e => {
            e.preventDefault();
            zone.classList.add('drag-over');
        });
        zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone.addEventListener('drop', e => {
            e.preventDefault();
            zone.classList.remove('drag-over');
            files = [...files, ...Array.from(e.dataTransfer.files)].slice(0, 10);
            renderFiles();
        });
    }());

    /* ── Real-time Field Validation ── */
    function validateField(input, errId, rule) {
        if (!input) return false;
        const valid = rule(input.value.trim());
        const errEl = $('#' + errId);
        const okEl = $('#' + input.id + '-ok');
        input.classList.toggle('success', valid);
        input.classList.toggle('error', !valid && input.value.trim() !== '');
        if (errEl) errEl.classList.toggle('show', !valid && input.value.trim() !== '');
        if (okEl) {
            okEl.classList.toggle('show-ok', valid);
            okEl.classList.toggle('show-err', !valid && input.value.trim() !== '');
        }
        return valid;
    }

    const fieldRules = {
        fname: [v => v.length >= 2, 'fname-err'],
        lname: [v => v.length >= 2, 'lname-err'],
        phone: [v => /^[\d\s\+\-]{7,15}$/.test(v), 'phone-err'],
        email: [v => v === '' || /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v), 'email-err'],
        details: [v => v.length >= 20, 'details-err'],
    };
    Object.entries(fieldRules).forEach(([id, [rule, err]]) => {
        const el = $('#' + id);
        if (el) el.addEventListener('blur', () => validateField(el, err, rule));
    });

    /* ── Multi-step Form Logic ── */
    let currentStep = 1;
    const TOTAL = 3;

    function setStep(n) {
        for (let i = 1; i <= TOTAL; i++) {
            const p = $('#panel-' + i);
            if (p) p.classList.toggle('active', i === n);
        }
        $$('.f-step').forEach(s => {
            const sn = parseInt(s.dataset.step);
            s.classList.remove('active', 'done');
            if (sn === n) s.classList.add('active');
            if (sn < n) s.classList.add('done');
        });
        ['line-1-2', 'line-2-3'].forEach((id, i) => {
            const el = $('#' + id);
            if (el) el.classList.toggle('done', n > i + 1);
        });
        const fill = $('#progress-fill');
        if (fill) fill.style.width = ((n / TOTAL) * 100) + '%';
        currentStep = n;
        if (n === 3) buildSummary();
    }

    function validateStep1() {
        const ok = [
            validateField($('#fname'), 'fname-err', fieldRules.fname[0]),
            validateField($('#lname'), 'lname-err', fieldRules.lname[0]),
            validateField($('#phone'), 'phone-err', fieldRules.phone[0]),
            validateField($('#email'), 'email-err', fieldRules.email[0]),
        ];
        return ok.every(Boolean);
    }

    function validateStep2() {
        return validateField($('#details'), 'details-err', fieldRules.details[0]);
    }

    const next1 = $('#next-1');
    const next2 = $('#next-2');
    const back2 = $('#back-2');
    const back3 = $('#back-3');

    if (next1) next1.addEventListener('click', () => { if (validateStep1()) setStep(2); });
    if (next2) next2.addEventListener('click', () => { if (validateStep2()) setStep(3); });
    if (back2) back2.addEventListener('click', () => setStep(1));
    if (back3) back3.addEventListener('click', () => setStep(2));

    function buildSummary() {
        const el = $('#summary-content');
        if (!el) return;
        const fname = $('#fname')?.value || '';
        const lname = $('#lname')?.value || '';
        const phone = $('#phone')?.value || '';
        const svcs = $$('input[name="services[]"]:checked').map(c => c.value).join(', ') || '—';
        const qty = $('#qty')?.value || '—';
        const budget = $('#budget-val')?.textContent || '—';
        const urg = $('#urgency-val')?.value || '—';
        el.innerHTML = `
            <b>Name:</b> ${fname} ${lname}<br>
            <b>Phone:</b> ${phone}<br>
            <b>Services:</b> ${svcs}<br>
            <b>Quantity:</b> ${qty}<br>
            <b>Budget:</b> ${budget}<br>
            <b>Urgency:</b> ${urg}
        `;
    }

    /* ── Form Submit ── */
    /* ── Form Submit ── */
const form = $('#quote-form');
if (form) {
    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const consent = $('#consent');
        const cerr = $('#consent-err');
        if (!consent || !consent.checked) {
            if (cerr) cerr.classList.add('show');
            return;
        }
        if (cerr) cerr.classList.remove('show');

        // Collect data
        const formData = {
            customer_name: ($('#fname')?.value || '') + ' ' + ($('#lname')?.value || ''),
            customer_email: $('#email')?.value || '',
            customer_phone: $('#phone')?.value || '',
            customer_company: $('#company')?.value || '',
            customer_address: $('#city')?.value || '',
            project_type: $$('input[name="services[]"]:checked').map(c => c.value).join(', ') || 'General',
            quantity: parseInt($('#qty')?.value) || 0,
            specifications: $('#details')?.value || '',
            deadline: $('#urgency-val')?.value || '',
            budget: parseInt($('#budget-slider')?.value) || 0,
            notes: 'Size: ' + ($('#size')?.value || '') + ' | Reference: ' + ($('#reference')?.value || '') + ' | How heard: ' + ($('#how-hear')?.value || ''),
            source: 'website'
        };

        const btn = $('#submit-btn');
        if (!btn) return;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
        btn.disabled = true;

        fetch('/SP/api/quote', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(result => {
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Quote Request';
            btn.disabled = false;

            if (result.success) {
                const refEl = $('#success-ref');
                if (refEl) refEl.textContent = 'REF: #' + (result.quote_number || 'SP-' + Date.now().toString().slice(-6));
                
                form.style.display = 'none';
                $$('.form-steps, .form-header, .form-progress-bar', form.parentElement).forEach(el => {
                    if (el) el.style.display = 'none';
                });
                const succ = $('#form-success');
                if (succ) {
                    succ.style.display = 'block';
                    succ.classList.add('show');
                }
                showToast('Quote request sent successfully!', 'success');
            } else {
                showToast(result.message || 'Failed to submit. Please try again.', 'error');
            }
        })
        .catch(error => {
            btn.innerHTML = '<i class="fas fa-paper-plane"></i> Send Quote Request';
            btn.disabled = false;
            showToast('Network error. Please try again.', 'error');
            console.error('Quote submission error:', error);
        });
    });
}
    const newQuoteBtn = $('#new-quote-btn');
    if (newQuoteBtn) {
        newQuoteBtn.addEventListener('click', () => {
            location.reload();
        });
    }

    /* ── FAQ Accordion ── */
    $$('[data-faq]').forEach(item => {
        const btn = item.querySelector('.faq-q');
        if (!btn) return;
        btn.addEventListener('click', () => {
            const isOpen = item.classList.contains('open');
            $$('[data-faq]').forEach(i => {
                i.classList.remove('open');
                const q = i.querySelector('.faq-q');
                if (q) q.setAttribute('aria-expanded', 'false');
            });
            if (!isOpen) {
                item.classList.add('open');
                btn.setAttribute('aria-expanded', 'true');
            }
        });
    });

    /* ── Footer Year ── */
    const yr = $('#footer-year');
    if (yr) yr.textContent = new Date().getFullYear();

    /* ── Toast Helper ── */
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

    console.log('✅ contact.js loaded successfully!');

})();
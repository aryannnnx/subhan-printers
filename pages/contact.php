<?php
// ============================================
// PAGES: Contact Us - Subhan Printers
// ============================================

// Start session if not started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Load functions
require_once __DIR__ . '/../includes/functions.php';
require_once __DIR__ . '/../includes/session.php';

// ============================================
// REMOVED: Login restriction - Anyone can view contact page
// ============================================
// No login check - contact page is public

// Set page variables
$pageTitle = 'Contact Us | Subhan Printers – Lahore';
$currentPage = 'contact';
$pageStyles = 'contact.css';
$pageScripts = 'contact.js';
$pageDescription = 'Contact Subhan Printers – Get a free quote for wedding cards, packaging, flex banners, brochures and graphic design.';

// Include header
require_once __DIR__ . '/../templates/header.php';

// Business hours data
$businessHours = [
    ['day' => 'Monday', 'hours' => '11:00 AM – 8:00 PM'],
    ['day' => 'Tuesday', 'hours' => '11:00 AM – 8:00 PM'],
    ['day' => 'Wednesday', 'hours' => '11:00 AM – 8:00 PM'],
    ['day' => 'Thursday', 'hours' => '11:00 AM – 8:00 PM'],
    ['day' => 'Friday', 'hours' => '11:00 AM – 8:00 PM'],
    ['day' => 'Saturday', 'hours' => '10:00 AM – 9:00 PM'],
    ['day' => 'Sunday', 'hours' => 'Closed']
];

// Service options
$serviceOptions = [
    ['value' => 'wedding', 'label' => 'Wedding Cards', 'icon' => 'fa-heart'],
    ['value' => 'packaging', 'label' => 'Box Packaging', 'icon' => 'fa-box'],
    ['value' => 'flex', 'label' => 'Flex / Banners', 'icon' => 'fa-image'],
    ['value' => 'brochures', 'label' => 'Brochures / Flyers', 'icon' => 'fa-file-alt'],
    ['value' => 'stickers', 'label' => 'Stickers / Labels', 'icon' => 'fa-tags'],
    ['value' => 'design', 'label' => 'Graphic Design', 'icon' => 'fa-palette'],
    ['value' => 'business-cards', 'label' => 'Business Cards', 'icon' => 'fa-id-card'],
    ['value' => 'other', 'label' => 'Other / Custom', 'icon' => 'fa-ellipsis-h']
];

// FAQ data
$faqs = [
    [
        'question' => 'How quickly will I get a quote?',
        'answer' => 'For standard requests we respond within 1 hour during business hours. For complex packaging jobs it may take 2–3 hours. WhatsApp is the fastest channel for instant responses.'
    ],
    [
        'question' => 'Do I need to visit your shop?',
        'answer' => 'Not at all! You can manage everything remotely via WhatsApp, email, or this form. We deliver across Pakistan. You\'re always welcome to visit our Gawalmandi shop if you prefer in-person consultation.'
    ],
    [
        'question' => 'What if I don\'t have a design file?',
        'answer' => 'No problem. Our in-house designers can create artwork from scratch. Share a brief description, reference images, or a sketch and we\'ll handle the design. Design fees start from ₨ 1,500.'
    ],
    [
        'question' => 'How do I pay for my order?',
        'answer' => 'We accept cash, bank transfer, JazzCash, EasyPaisa, and card at our shop. For remote orders 50% advance is required before production. Balance is paid on delivery or collection.'
    ],
    [
        'question' => 'Can I get a sample before bulk printing?',
        'answer' => 'Yes. We highly recommend physical samples for packaging and wedding cards. Sample fee applies and is deducted from your final order value. Samples are usually ready in 1–2 days.'
    ],
    [
        'question' => 'Do you deliver outside Lahore?',
        'answer' => 'Yes! We ship to all major cities via TCS and Leopard Couriers. Delivery takes 2–4 business days after dispatch. Express and same-day delivery within Lahore is also available.'
    ]
];
?>

<main>

    <!-- ═══ HERO ═══ -->
    <section id="contact-hero" aria-label="Contact hero">
        <div class="hero-bg-mesh" aria-hidden="true"></div>
        <div class="hero-grid-lines" aria-hidden="true"></div>
        <div class="orb orb-1" aria-hidden="true"></div>
        <div class="orb orb-2" aria-hidden="true"></div>
        <div class="orb orb-3" aria-hidden="true"></div>
        <div class="container hero-content">

            <!-- Left -->
            <div class="reveal">
                <div class="hero-eyebrow">Let's Work Together</div>
                <h1 class="hero-title">Get in <span class="highlight">Touch</span><br>With Us</h1>
                <p class="hero-desc">
                    Based in Gawalmandi, Lahore — we're ready to bring your print projects to life.
                    Fill in the smart quote form or reach us instantly on WhatsApp.
                </p>
                <div class="hero-quick-links">
                    <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="quick-link">
                        <i class="fab fa-whatsapp" style="color:#25d366"></i> WhatsApp Now
                    </a>
                    <a href="tel:+923004197033" class="quick-link">
                        <i class="fas fa-phone" style="color:#3b82f6"></i> Call Us
                    </a>
                    <a href="mailto:subhanprinters2025@gmail.com" class="quick-link">
                        <i class="fas fa-envelope" style="color:var(--clr-primary)"></i> Email Us
                    </a>
                    <a href="#contact-map" class="quick-link">
                        <i class="fas fa-map-marker-alt" style="color:var(--clr-accent)"></i> Directions
                    </a>
                </div>
            </div>

            <!-- Right — contact info cards -->
            <div class="hero-cards-grid reveal reveal-d2">
                <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="hero-ccard hcc-1">
                    <div class="hcc-icon" style="background:linear-gradient(135deg,#25d366,#128c7e)"><i class="fab fa-whatsapp"></i></div>
                    <div class="hcc-title">WhatsApp</div>
                    <div class="hcc-val">+92 300 41797033</div>
                    <div class="hcc-badge badge-green"><span class="status-dot dot-online"></span> Online now</div>
                </a>
                <a href="tel:+923004197033" class="hero-ccard hcc-2">
                    <div class="hcc-icon" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)"><i class="fas fa-phone"></i></div>
                    <div class="hcc-title">Phone</div>
                    <div class="hcc-val">+92 300 4197033</div>
                    <div class="hcc-badge badge-blue"><i class="fas fa-clock"></i> 9 AM – 8 PM</div>
                </a>
                <a href="mailto:subhanprinters2025@gmail.com" class="hero-ccard hcc-3">
                    <div class="hcc-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)"><i class="fas fa-envelope"></i></div>
                    <div class="hcc-title">Email</div>
                    <div class="hcc-val">subhanprinters2025@gmail.com</div>
                    <div class="hcc-badge badge-blue"><i class="fas fa-reply"></i> Reply within 2hr</div>
                </a>
                <a href="#contact-map" class="hero-ccard hcc-4">
                    <div class="hcc-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><i class="fas fa-map-marker-alt"></i></div>
                    <div class="hcc-title">Visit Us</div>
                    <div class="hcc-val">Abaseen Center, Gawalmandi, Lahore</div>
                    <div class="hcc-badge badge-green"><i class="fas fa-store"></i> Walk-ins welcome</div>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══ MAIN CONTACT ═══ -->
    <section id="contact-main" class="section" aria-label="Contact form and info">
        <div class="container">
            <div class="contact-layout">

                <!-- ── MULTI-STEP FORM ── -->
                <div class="form-card reveal">
                    <div class="form-header">
                        <h3>Request a Free Quote</h3>
                        <p>Complete all 3 steps and we'll respond within 1 hour</p>
                    </div>

                    <!-- progress bar -->
                    <div class="form-progress-bar"><div class="form-progress-fill" id="progress-fill" style="width:33%"></div></div>

                    <!-- step indicators -->
                    <div class="form-steps" id="form-steps">
                        <div class="f-step active" data-step="1">
                            <div class="f-step-num">1</div>
                            <div class="f-step-label">Your Info</div>
                        </div>
                        <div class="f-step-line" id="line-1-2"></div>
                        <div class="f-step" data-step="2">
                            <div class="f-step-num">2</div>
                            <div class="f-step-label">Project Details</div>
                        </div>
                        <div class="f-step-line" id="line-2-3"></div>
                        <div class="f-step" data-step="3">
                            <div class="f-step-num">3</div>
                            <div class="f-step-label">Files &amp; Submit</div>
                        </div>
                    </div>

                    <form id="quote-form" novalidate>

                        <!-- ── STEP 1: Personal Info ── -->
                        <div class="form-panel active" id="panel-1">
                            <div class="field-row">
                                <div class="field-group">
                                    <label class="field-label" for="fname">First Name <span>required</span></label>
                                    <div class="field-wrap">
                                        <input class="field-input" type="text" id="fname" name="fname" placeholder="Ahmad" autocomplete="given-name" required/>
                                        <i class="fas fa-check field-icon" id="fname-ok"></i>
                                    </div>
                                    <div class="field-err" id="fname-err">Please enter your first name</div>
                                </div>
                                <div class="field-group">
                                    <label class="field-label" for="lname">Last Name <span>required</span></label>
                                    <div class="field-wrap">
                                        <input class="field-input" type="text" id="lname" name="lname" placeholder="Khan" autocomplete="family-name" required/>
                                        <i class="fas fa-check field-icon" id="lname-ok"></i>
                                    </div>
                                    <div class="field-err" id="lname-err">Please enter your last name</div>
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field-group">
                                    <label class="field-label" for="phone">Phone / WhatsApp <span>required</span></label>
                                    <div class="field-wrap">
                                        <input class="field-input" type="tel" id="phone" name="phone" placeholder="+92 300 0000000" autocomplete="tel" required/>
                                        <i class="fas fa-check field-icon" id="phone-ok"></i>
                                    </div>
                                    <div class="field-err" id="phone-err">Please enter a valid phone number</div>
                                </div>
                                <div class="field-group">
                                    <label class="field-label" for="email">Email Address <span>optional</span></label>
                                    <div class="field-wrap">
                                        <input class="field-input" type="email" id="email" name="email" placeholder="ahmad@example.com" autocomplete="email"/>
                                        <i class="fas fa-check field-icon" id="email-ok"></i>
                                    </div>
                                    <div class="field-err" id="email-err">Please enter a valid email</div>
                                </div>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="company">Company / Business Name <span>optional</span></label>
                                <input class="field-input" type="text" id="company" name="company" placeholder="Your brand or business name"/>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="city">City</label>
                                <select class="field-input" id="city" name="city">
                                    <option value="">Select your city</option>
                                    <option value="lahore">Lahore</option>
                                    <option value="karachi">Karachi</option>
                                    <option value="islamabad">Islamabad / Rawalpindi</option>
                                    <option value="faisalabad">Faisalabad</option>
                                    <option value="multan">Multan</option>
                                    <option value="peshawar">Peshawar</option>
                                    <option value="other">Other City</option>
                                </select>
                            </div>
                            <div class="form-nav">
                                <span></span>
                                <button type="button" class="btn btn-primary" id="next-1">
                                    Next: Project Details <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ── STEP 2: Project Details ── -->
                        <div class="form-panel" id="panel-2">
                            <div class="field-group">
                                <label class="field-label">Services Needed <span>select all that apply</span></label>
                                <div class="service-checks">
                                    <?php foreach ($serviceOptions as $service): ?>
                                    <div class="svc-check-item">
                                        <input type="checkbox" id="svc-<?php echo $service['value']; ?>" name="services[]" value="<?php echo $service['value']; ?>">
                                        <label class="svc-check-label" for="svc-<?php echo $service['value']; ?>"><i class="fas <?php echo $service['icon']; ?>"></i> <?php echo $service['label']; ?></label>
                                    </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="field-row">
                                <div class="field-group">
                                    <label class="field-label" for="qty">Quantity / Print Run</label>
                                    <input class="field-input" type="number" id="qty" name="qty" placeholder="e.g. 500" min="1"/>
                                </div>
                                <div class="field-group">
                                    <label class="field-label" for="size">Size / Format</label>
                                    <select class="field-input" id="size" name="size">
                                        <option value="">Select if known</option>
                                        <option>A4</option><option>A5</option><option>A3</option>
                                        <option>Business Card (90×55mm)</option>
                                        <option>Custom Size</option>
                                        <option>Not sure – advise me</option>
                                    </select>
                                </div>
                            </div>
                            <div class="field-group">
                                <label class="field-label">Estimated Budget</label>
                                <div class="budget-slider-wrap">
                                    <input type="range" class="budget-track" id="budget-slider" min="1000" max="200000" step="1000" value="10000"/>
                                    <div class="budget-display">
                                        <div class="budget-val" id="budget-val">₨ 10,000</div>
                                        <span style="font-size:.78rem;color:var(--clr-muted)">Drag to adjust</span>
                                    </div>
                                    <div class="budget-labels"><span>₨ 1,000</span><span>₨ 200,000+</span></div>
                                </div>
                            </div>
                            <div class="field-group">
                                <label class="field-label">Urgency / Deadline</label>
                                <div class="urgency-options">
                                    <button type="button" class="urg-btn" data-urg="flexible">Flexible</button>
                                    <button type="button" class="urg-btn" data-urg="1-2weeks">1–2 Weeks</button>
                                    <button type="button" class="urg-btn" data-urg="3-5days">3–5 Days</button>
                                    <button type="button" class="urg-btn rush" data-urg="48hr">48hr Rush ⚡</button>
                                    <button type="button" class="urg-btn rush" data-urg="24hr">24hr Rush 🔥</button>
                                </div>
                                <input type="hidden" id="urgency-val" name="urgency" value=""/>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="details">Project Details <span>required</span></label>
                                <textarea class="field-input" id="details" name="details" rows="4"
                                    placeholder="Describe your project — paper type, finish, colours, quantity, any special requirements..." maxlength="1000" required></textarea>
                                <div class="char-counter" id="details-counter">0 / 1000</div>
                                <div class="field-err" id="details-err">Please describe your project (min 20 characters)</div>
                            </div>
                            <div class="form-nav">
                                <button type="button" class="form-back" id="back-2"><i class="fas fa-arrow-left"></i> Back</button>
                                <button type="button" class="btn btn-primary" id="next-2">
                                    Next: Upload Files <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>
                        </div>

                        <!-- ── STEP 3: Files & Submit ── -->
                        <div class="form-panel" id="panel-3">
                            <div class="field-group">
                                <label class="field-label">Upload Your Files <span>optional</span></label>
                                <div class="file-upload-area" id="file-drop-zone">
                                    <input type="file" id="file-input" multiple accept=".jpg,.jpeg,.png,.pdf,.ai,.psd,.cdr,.eps,.tiff,.webp" aria-label="Upload design files"/>
                                    <div class="file-upload-icon"><i class="fas fa-cloud-upload-alt"></i></div>
                                    <div class="file-upload-text">Drag & Drop files here</div>
                                    <div class="file-upload-hint">AI, PDF, PSD, CDR, PNG, JPG — Max 10MB each</div>
                                </div>
                                <div class="file-list" id="file-list"></div>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="reference">Reference / Inspiration Links <span>optional</span></label>
                                <input class="field-input" type="url" id="reference" name="reference" placeholder="https://pinterest.com/your-board or any reference URL"/>
                            </div>
                            <div class="field-group">
                                <label class="field-label" for="how-hear">How did you hear about us?</label>
                                <select class="field-input" id="how-hear" name="how_hear">
                                    <option value="">Select an option</option>
                                    <option>Google Search</option>
                                    <option>Instagram</option>
                                    <option>Facebook</option>
                                    <option>WhatsApp / Referral</option>
                                    <option>Friend / Family</option>
                                    <option>Walked Past the Shop</option>
                                    <option>Other</option>
                                </select>
                            </div>
                            <!-- summary box -->
                            <div id="form-summary" style="background:rgba(139,92,246,.08);border:1px solid rgba(139,92,246,.2);border-radius:var(--radius-lg);padding:18px;margin-bottom:20px;font-size:.82rem;color:var(--clr-muted);line-height:1.8">
                                <div style="font-weight:700;color:var(--clr-white);margin-bottom:8px"><i class="fas fa-clipboard-list" style="color:var(--clr-primary)"></i> Your Quote Summary</div>
                                <div id="summary-content"></div>
                            </div>
                            <!-- consent -->
                            <div class="field-group" style="display:flex;align-items:flex-start;gap:10px">
                                <input type="checkbox" id="consent" name="consent" style="margin-top:3px;accent-color:var(--clr-primary);width:16px;height:16px;flex-shrink:0" required/>
                                <label for="consent" style="font-size:.82rem;color:var(--clr-muted);cursor:pointer">
                                    I agree that Subhan Printers may contact me via WhatsApp/email regarding this quote. I understand my data will not be shared with third parties.
                                </label>
                            </div>
                            <div class="field-err" id="consent-err">Please accept to continue</div>
                            <div class="form-nav">
                                <button type="button" class="form-back" id="back-3"><i class="fas fa-arrow-left"></i> Back</button>
                                <button type="submit" class="btn btn-primary btn-lg" id="submit-btn">
                                    <i class="fas fa-paper-plane"></i> Send Quote Request
                                </button>
                            </div>
                        </div>

                    </form>

                    <!-- SUCCESS STATE -->
                    <div class="form-success" id="form-success" aria-live="polite">
                        <div class="success-icon"><i class="fas fa-check"></i></div>
                        <div class="success-title">Quote Request Sent!</div>
                        <p class="success-desc">Thank you! We've received your request and will respond via WhatsApp within 1 hour during business hours.</p>
                        <div class="success-ref" id="success-ref">REF: #SP-000000</div>
                        <div style="display:flex;gap:12px;justify-content:center;flex-wrap:wrap">
                            <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="btn btn-green">
                                <i class="fab fa-whatsapp"></i> Follow Up on WhatsApp
                            </a>
                            <button class="btn btn-outline" id="new-quote-btn" type="button">Submit Another</button>
                        </div>
                    </div>

                </div><!-- /.form-card -->

                <!-- ── RIGHT INFO PANEL ── -->
                <div class="info-panel reveal reveal-d2">

                    <!-- Live chat card -->
                    <div class="live-chat-card">
                        <div class="chat-header">
                            <div class="chat-avatar">
                                <i class="fab fa-whatsapp"></i>
                                <div class="chat-online-dot"></div>
                            </div>
                            <div>
                                <div class="chat-name">Subhan Printers Support</div>
                                <div class="chat-status"><span class="status-dot dot-online"></span> Online – typically replies instantly</div>
                            </div>
                        </div>
                        <div class="chat-msg">
                            "Hi! 👋 I'm here to help with your print project. Send us your requirements and we'll send a quote within the hour!"
                        </div>
                        <a href="https://wa.me/923004197033?text=Hi!%20I%20need%20a%20print%20quote" target="_blank" rel="noopener" class="btn btn-green" style="width:100%;justify-content:center">
                            <i class="fab fa-whatsapp"></i> Start WhatsApp Chat
                        </a>
                    </div>

                    <!-- Contact details -->
                    <div class="info-card">
                        <div class="info-card-head">
                            <div class="info-card-icon" style="background:linear-gradient(135deg,var(--clr-primary),var(--clr-primary-2))"><i class="fas fa-address-book"></i></div>
                            <div><div class="info-card-title">Contact Details</div><div class="info-card-sub">Multiple ways to reach us</div></div>
                        </div>
                        <div class="info-item"><i class="fas fa-map-marker-alt"></i><div><strong>Our Location</strong>Hamza Center, Gawalmandi, Lahore, Pakistan</div></div>
                        <div class="info-item"><i class="fas fa-phone"></i><div><strong>Phone</strong><a href="tel:+923001234567">+92 3004197033</a></div></div>
                        <div class="info-item"><i class="fab fa-whatsapp"></i><div><strong>WhatsApp</strong><a href="https://wa.me/923004197033">+92 300 4197033</a></div></div>
                        <div class="info-item"><i class="fas fa-envelope"></i><div><strong>Email</strong><a href="subhanprinters2025@gmail.com">subhanprinters2025@gmail.com</a></div></div>
                    </div>

                    <!-- Business hours -->
                    <div class="info-card">
                        <div class="info-card-head">
                            <div class="info-card-icon" style="background:linear-gradient(135deg,var(--clr-accent),var(--clr-accent-2))"><i class="fas fa-clock"></i></div>
                            <div><div class="info-card-title">Business Hours</div><div class="info-card-sub">Lahore local time (PKT)</div></div>
                        </div>
                        <div id="hours-table">
                            <?php foreach ($businessHours as $hours): ?>
                            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid var(--clr-border);font-size:.82rem;color:var(--clr-muted)">
                                <span><?php echo $hours['day']; ?></span>
                                <span><?php echo $hours['hours']; ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Trust badges -->
                    <div class="trust-badges">
                        <div class="trust-badge">
                            <i class="fas fa-shield-alt" style="color:var(--clr-green)"></i>
                            <span>100% Satisfaction Guarantee</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fas fa-redo" style="color:var(--clr-primary)"></i>
                            <span>Free Reprints on Our Error</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fas fa-truck" style="color:#3b82f6"></i>
                            <span>Nationwide Delivery</span>
                        </div>
                        <div class="trust-badge">
                            <i class="fas fa-lock" style="color:var(--clr-accent)"></i>
                            <span>Secure &amp; Confidential</span>
                        </div>
                    </div>

                </div><!-- /.info-panel -->

            </div><!-- /.contact-layout -->
        </div>
    </section>

    <!-- ═══ MAP ═══ -->
    <div id="contact-map" aria-label="Our location on map">
        <div class="map-wrapper">
            <iframe class="map-frame"
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3402.2047896573316!2d74.31491!3d31.5656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzHCsDMzJzU2LjIiTiA3NMKwMTgnNTMuNyJF!5e0!3m2!1sen!2s!4v1234567890"
                allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                title="Subhan Printers location in Gawalmandi, Lahore">
            </iframe>
            <div class="map-overlay-card">
                <h4>🏪 Subhan Printers</h4>
                <p>Abaseen Center, Gawalmandi<br>Lahore, Pakistan</p>
                <a href="https://maps.google.com/?q=Gawalmandi,Lahore" target="_blank" rel="noopener" class="map-directions-btn">
                    Get Directions <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- ═══ SOCIAL CHANNELS ═══ -->
    <section id="contact-socials" class="section" aria-label="Connect with us">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Connect With Us</div>
                <h2 class="section-title">Find Us <span class="highlight">Everywhere</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">Follow us for daily print inspiration, offers, and behind-the-scenes content</p>
            </div>
            <div class="socials-grid">
                <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="social-card sc-wa reveal">
                    <div class="social-icon" style="background:linear-gradient(135deg,#25d366,#128c7e)"><i class="fab fa-whatsapp"></i></div>
                    <div class="social-name">WhatsApp</div>
                    <div class="social-handle">+92 300 4197033</div>
                    <span class="social-action" style="background:rgba(37,211,102,.15);color:#25d366">Message Us →</span>
                </a>
                <a href="https://www.instagram.com/subhanprintersx._/?hl=en" target="_blank" rel="noopener" class="social-card sc-ig reveal reveal-d1">
                    <div class="social-icon" style="background:linear-gradient(135deg,#e4405f,#fd1d1d,#fcb045)"><i class="fab fa-instagram"></i></div>
                    <div class="social-name">Instagram</div>
                    <div class="social-handle">@subhanprinters</div>
                    <span class="social-action" style="background:rgba(228,64,95,.15);color:#e4405f">Follow Us →</span>
                </a>
                <a href="https://www.facebook.com/profile.php?id=61575770012188" target="_blank" rel="noopener" class="social-card sc-fb reveal reveal-d2">
                    <div class="social-icon" style="background:linear-gradient(135deg,#1877f2,#0e5ecc)"><i class="fab fa-facebook-f"></i></div>
                    <div class="social-name">Facebook</div>
                    <div class="social-handle">Subhan Printers</div>
                    <span class="social-action" style="background:rgba(24,119,242,.15);color:#1877f2">Like Page →</span>
                </a>
                <a href="mailto:info@subhanprinters.com" class="social-card sc-em reveal reveal-d3">
                    <div class="social-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)"><i class="fas fa-envelope"></i></div>
                    <div class="social-name">Email</div>
                    <div class="social-handle">subhanprinters2025@gmail.com</div>
                    <span class="social-action" style="background:rgba(139,92,246,.15);color:var(--clr-primary)">Send Email →</span>
                </a>
            </div>
        </div>
    </section>

    <!-- ═══ FAQ ═══ -->
    <section id="contact-faq" class="section" aria-label="Contact FAQ">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">FAQ</div>
                <h2 class="section-title">Quick <span class="highlight">Answers</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">Common questions before you reach out</p>
            </div>
            <div class="faq-grid">
                <?php foreach ($faqs as $index => $faq): ?>
                <div class="faq-item reveal <?php echo $index > 0 ? 'reveal-d' . ($index % 3 + 1) : ''; ?>" data-faq>
                    <button class="faq-q" aria-expanded="false">
                        <?php echo $faq['question']; ?>
                        <div class="faq-icon"><i class="fas fa-plus"></i></div>
                    </button>
                    <div class="faq-a"><p><?php echo $faq['answer']; ?></p></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

</main>

<?php
// ============================================================
// FOOTER - Include footer
// ============================================================
require_once __DIR__ . '/../templates/footer.php';
?>
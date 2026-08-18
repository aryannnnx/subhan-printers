<?php
// ============================================
// PAGES: Order Page - Subhan Printers
// ============================================

// Set page variables
$pageTitle = 'Order Now | Subhan Printers – Lahore';
$currentPage = 'order';
$pageStyles = 'orders.css';
$pageScripts = 'orders.js';

// Include header
require_once __DIR__ . '/../templates/header.php';

// Load models
require_once __DIR__ . '/../models/Product.php';

// Fetch products from database
$productModel = new Product();
$products = $productModel->getAll(['limit' => 50]);

// If no products in database, use static data
if (empty($products)) {
    $products = [
        [
            'id' => 1,
            'name' => 'Wedding Cards',
            'slug' => 'wedding-cards',
            'description' => 'Elegant invitations with gold foil, embossing and premium paper',
            'icon' => 'fa-heart',
            'starting_price' => '3500',
            'price_text' => '/ 50 cards',
            'badge' => '⭐ Popular',
            'category' => 'wedding',
            'min_quantity' => '50',
            'turnaround' => '3-5 days',
            'whatsapp_text' => 'Hi! I want to order Wedding Cards. Please send me a quote.'
        ],
        [
            'id' => 2,
            'name' => 'Box Packaging',
            'slug' => 'box-packaging',
            'description' => 'Custom printed boxes for cosmetics, food, gifts, retail and more',
            'icon' => 'fa-box-open',
            'starting_price' => '25',
            'price_text' => '/ piece (bulk)',
            'badge' => '🔥 Bestseller',
            'category' => 'packaging',
            'min_quantity' => '100',
            'turnaround' => '5-7 days',
            'whatsapp_text' => 'Hi! I want to order Custom Box Packaging. Please send me a quote.'
        ],
        [
            'id' => 3,
            'name' => 'Flex Banners',
            'slug' => 'flex-banners',
            'description' => 'Outdoor flex, pull-up standees and shop signage — any size',
            'icon' => 'fa-image',
            'starting_price' => '120',
            'price_text' => '/ sq. ft.',
            'badge' => '',
            'category' => 'flex',
            'min_quantity' => '1',
            'turnaround' => 'Same day',
            'whatsapp_text' => 'Hi! I want to order Flex Banners. Please send me a quote.'
        ],
        [
            'id' => 4,
            'name' => 'Business Cards',
            'slug' => 'business-cards',
            'description' => '350gsm premium business cards with matte, gloss or soft-touch finish',
            'icon' => 'fa-id-card',
            'starting_price' => '1800',
            'price_text' => '/ 100 pcs',
            'badge' => '',
            'category' => 'stationery',
            'min_quantity' => '100',
            'turnaround' => '2-3 days',
            'whatsapp_text' => 'Hi! I want to order Business Cards. Please send me a quote.'
        ],
        [
            'id' => 5,
            'name' => 'Brochures & Flyers',
            'slug' => 'brochures-flyers',
            'description' => 'Tri-fold, bi-fold A4/A5 brochures and full-colour flyers',
            'icon' => 'fa-file-alt',
            'starting_price' => '3500',
            'price_text' => '/ 500 pcs',
            'badge' => '',
            'category' => 'brochures',
            'min_quantity' => '500',
            'turnaround' => '2-4 days',
            'whatsapp_text' => 'Hi! I want to order Brochures. Please send me a quote.'
        ],
        [
            'id' => 6,
            'name' => 'Stickers & Labels',
            'slug' => 'stickers-labels',
            'description' => 'Die-cut product labels, holographic stickers and waterproof vinyl',
            'icon' => 'fa-tags',
            'starting_price' => '5',
            'price_text' => '/ sticker (bulk)',
            'badge' => '',
            'category' => 'stickers',
            'min_quantity' => '50',
            'turnaround' => '2-3 days',
            'whatsapp_text' => 'Hi! I want to order Stickers and Labels. Please send me a quote.'
        ],
        [
            'id' => 7,
            'name' => 'Logo & Brand Design',
            'slug' => 'logo-brand-design',
            'description' => 'Professional logo design with complete brand kit and all file formats',
            'icon' => 'fa-crown',
            'starting_price' => '2999',
            'price_text' => '/ project',
            'badge' => '',
            'category' => 'design',
            'min_quantity' => '1',
            'turnaround' => '2-3 days',
            'whatsapp_text' => 'Hi! I want Logo and Brand Design. Please send me a quote.'
        ],
        [
            'id' => 8,
            'name' => 'Corrugated Boxes',
            'slug' => 'corrugated-boxes',
            'description' => 'Heavy-duty corrugated shipping boxes for e-commerce and wholesale',
            'icon' => 'fa-archive',
            'starting_price' => '35',
            'price_text' => '/ piece (bulk)',
            'badge' => '',
            'category' => 'packaging',
            'min_quantity' => '100',
            'turnaround' => '7-10 days',
            'whatsapp_text' => 'Hi! I want Corrugated Boxes. Please send me a quote.'
        ]
    ];
}

// Pricing table data
$pricingData = [
    ['product' => 'Wedding Cards', 'icon' => 'fa-heart', 'color' => '#ec4899', 'price' => '₨ 3,500', 'min_qty' => '50 cards', 'turnaround' => '3–5 days', 'turnaround_class' => 'pt-medium'],
    ['product' => 'Box Packaging', 'icon' => 'fa-box-open', 'color' => '#f59e0b', 'price' => '₨ 25 / pc', 'min_qty' => '100 boxes', 'turnaround' => '5–7 days', 'turnaround_class' => 'pt-slow'],
    ['product' => 'Flex Banners', 'icon' => 'fa-image', 'color' => '#22c55e', 'price' => '₨ 120 / sq.ft', 'min_qty' => '1 piece', 'turnaround' => 'Same day', 'turnaround_class' => 'pt-fast'],
    ['product' => 'Business Cards', 'icon' => 'fa-id-card', 'color' => '#3b82f6', 'price' => '₨ 1,800', 'min_qty' => '100 pcs', 'turnaround' => '2–3 days', 'turnaround_class' => 'pt-fast'],
    ['product' => 'Brochures', 'icon' => 'fa-file-alt', 'color' => '#8b5cf6', 'price' => '₨ 3,500', 'min_qty' => '500 pcs', 'turnaround' => '2–4 days', 'turnaround_class' => 'pt-medium'],
    ['product' => 'Stickers / Labels', 'icon' => 'fa-tags', 'color' => '#06b6d4', 'price' => '₨ 5 / pc', 'min_qty' => '50 pcs', 'turnaround' => '2–3 days', 'turnaround_class' => 'pt-fast'],
    ['product' => 'Logo Design', 'icon' => 'fa-crown', 'color' => '#f59e0b', 'price' => '₨ 2,999', 'min_qty' => '1 project', 'turnaround' => '2–3 days', 'turnaround_class' => 'pt-medium'],
    ['product' => 'Corrugated Boxes', 'icon' => 'fa-archive', 'color' => '#ef4444', 'price' => '₨ 35 / pc', 'min_qty' => '100 boxes', 'turnaround' => '7–10 days', 'turnaround_class' => 'pt-slow']
];

// Icon color mapping
$iconColors = [
    'fa-heart' => '#ec4899',
    'fa-box-open' => '#f59e0b',
    'fa-image' => '#22c55e',
    'fa-id-card' => '#3b82f6',
    'fa-file-alt' => '#8b5cf6',
    'fa-tags' => '#06b6d4',
    'fa-crown' => '#f59e0b',
    'fa-archive' => '#ef4444'
];
?>

<main>

    <!-- ═══ HERO ═══ -->
    <section id="order-hero" aria-label="Order hero">
        <div class="hero-bg" aria-hidden="true"></div>
        <div class="hero-grid-lines" aria-hidden="true"></div>
        <div class="orb o1" aria-hidden="true"></div>
        <div class="orb o2" aria-hidden="true"></div>
        <div class="orb o3" aria-hidden="true"></div>

        <div class="hero-content reveal">
            <div class="hero-eyebrow">Subhan Printers · Lahore</div>
            <h1 class="hero-title">
                Start Your Print
                <span class="gold">Order Now.</span>
            </h1>
            <p class="hero-desc">
                Premium printing in Lahore — wedding cards, packaging, flex banners, brochures, stickers and more.
                Get a quote in minutes. Delivery across Pakistan.
            </p>

            <!-- BIG CTA BUTTONS -->
            <div class="hero-cta-group">
                <div class="big-cta-row">
                    <a href="https://wa.me/923264651885?text=Hi!%20I%20want%20to%20place%20a%20print%20order%20with%20Subhan%20Printers"
                        target="_blank" rel="noopener" class="big-cta big-cta-wa" aria-label="Order on WhatsApp">
                        <i class="fab fa-whatsapp"></i>
                        <span>
                            Order on WhatsApp
                            <span class="big-cta-sub">Fastest · Reply in minutes</span>
                        </span>
                    </a>
                    <a href="<?php echo base_url('contact'); ?>#quote-form" class="big-cta big-cta-quote" aria-label="Get a free quote">
                        <i class="fas fa-file-invoice-dollar"></i>
                        <span>
                            Get a Free Quote
                            <span class="big-cta-sub">Fill the smart form · 1hr response</span>
                        </span>
                    </a>
                </div>

                <div class="hero-secondary">
                    <a href="tel:+923001234567" class="hero-sec-link">
                        <i class="fas fa-phone" style="color:#3b82f6"></i> Call: +92 300 1234567
                    </a>
                    <a href="mailto:info@subhanprinters.com" class="hero-sec-link">
                        <i class="fas fa-envelope" style="color:var(--clr-primary)"></i> Email Us
                    </a>
                    <a href="#quick-order" class="hero-sec-link">
                        <i class="fas fa-shopping-bag" style="color:var(--clr-accent)"></i> Browse Products
                    </a>
                    <a href="<?php echo base_url('portfolio'); ?>" class="hero-sec-link">
                        <i class="fas fa-images" style="color:var(--clr-green)"></i> View Portfolio
                    </a>
                </div>
            </div>

            <div class="trust-pills">
                <span class="trust-pill"><i class="fas fa-shield-alt"></i> 100% Satisfaction Guarantee</span>
                <span class="trust-pill"><i class="fas fa-redo"></i> Free Reprints on Our Error</span>
                <span class="trust-pill"><i class="fas fa-truck"></i> Nationwide Delivery</span>
                <span class="trust-pill"><i class="fas fa-clock"></i> Same-Day Printing Available</span>
                <span class="trust-pill"><i class="fas fa-star" style="color:var(--clr-accent)"></i> 4.9/5 Rating · 5000+ Clients</span>
            </div>
        </div>

        <div class="scroll-hint" aria-hidden="true">
            <span>Scroll</span>
            <div class="scroll-line"></div>
        </div>
    </section>

    <!-- ═══ QUICK ORDER CARDS ═══ -->
    <section id="quick-order" class="section" aria-label="Quick order by product">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">What Do You Need?</div>
                <h2 class="section-title">Order by <span class="highlight">Product</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">Click any product below to start your order directly on WhatsApp</p>
            </div>

            <div class="quick-order-grid">
                <?php 
                $delayClasses = ['', 'reveal-d1', 'reveal-d2', 'reveal-d3'];
                $idx = 0;
                foreach ($products as $product):
                    $delay = $delayClasses[$idx % 4];
                    $icon = $product['icon'] ?? 'fa-cube';
                    $iconColor = $iconColors[$icon] ?? '#8b5cf6';
                    $badge = $product['badge'] ?? '';
                    $price = number_format($product['starting_price'], 0);
                    $priceText = $product['price_text'] ?? '';
                    $whatsappText = $product['whatsapp_text'] ?? 'Hi! I want to order ' . $product['name'] . '. Please send me a quote.';
                ?>
                <div class="qo-card reveal <?php echo $delay; ?>">
                    <?php if ($badge): ?>
                    <span class="qo-popular"><?php echo $badge; ?></span>
                    <?php endif; ?>
                    <div class="qo-icon" style="background:linear-gradient(135deg,<?php echo $iconColor; ?>,<?php echo $iconColor; ?>80)">
                        <i class="fas <?php echo $icon; ?>"></i>
                    </div>
                    <div class="qo-title"><?php echo $product['name']; ?></div>
                    <div class="qo-desc"><?php echo $product['description']; ?></div>
                    <div class="qo-price">From ₨ <?php echo $price; ?> <span><?php echo $priceText; ?></span></div>
                    <a href="https://wa.me/923264651885?text=<?php echo urlencode($whatsappText); ?>" target="_blank" rel="noopener" class="qo-btn">
                        Order Now <i class="fab fa-whatsapp"></i>
                    </a>
                </div>
                <?php 
                $idx++;
                endforeach; 
                ?>
            </div>
        </div>
    </section>

    <!-- ═══ HOW TO ORDER ═══ -->
    <section id="how-to-order" class="section" aria-label="How to order">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Simple Process</div>
                <h2 class="section-title">How to <span class="highlight">Place Your Order</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">4 easy steps from idea to delivery</p>
            </div>
            <div class="hto-grid">
                <div class="hto-step reveal">
                    <div class="hto-num" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">1</div>
                    <span class="hto-icon"><i class="fas fa-comments" style="color:var(--clr-primary)"></i></span>
                    <div class="hto-title">Contact Us</div>
                    <div class="hto-desc">WhatsApp, call, email or fill the quote form. Tell us what you need — product, quantity, size and any special requirements.</div>
                </div>
                <div class="hto-step reveal reveal-d1">
                    <div class="hto-num" style="background:linear-gradient(135deg,#f59e0b,#b45309)">2</div>
                    <span class="hto-icon"><i class="fas fa-file-invoice-dollar" style="color:var(--clr-accent)"></i></span>
                    <div class="hto-title">Receive Quote</div>
                    <div class="hto-desc">We send you a transparent price quote within 1 hour. Approve it and pay 50% advance to confirm your order.</div>
                </div>
                <div class="hto-step reveal reveal-d2">
                    <div class="hto-num" style="background:linear-gradient(135deg,#ec4899,#be185d)">3</div>
                    <span class="hto-icon"><i class="fas fa-magic" style="color:#ec4899"></i></span>
                    <div class="hto-title">We Design &amp; Print</div>
                    <div class="hto-desc">We send you a proof for approval. Once approved, your job goes to press. You receive regular WhatsApp updates.</div>
                </div>
                <div class="hto-step reveal reveal-d3">
                    <div class="hto-num" style="background:linear-gradient(135deg,#22c55e,#15803d)">4</div>
                    <span class="hto-icon"><i class="fas fa-truck" style="color:var(--clr-green)"></i></span>
                    <div class="hto-title">Pickup or Delivery</div>
                    <div class="hto-desc">Collect from our Gawalmandi shop or get it shipped via TCS / Leopard to any city in Pakistan. Pay balance on delivery.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ ORDER CHANNELS ═══ -->
    <section id="order-channels" class="section" aria-label="How to reach us">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Choose Your Channel</div>
                <h2 class="section-title">3 Ways to <span class="highlight">Order</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">Pick whichever is easiest for you — we're always ready</p>
            </div>
            <div class="channels-grid">

                <!-- WhatsApp -->
                <div class="channel-card cc-wa reveal">
                    <div class="channel-icon" style="background:linear-gradient(135deg,#25d366,#128c7e)"><i class="fab fa-whatsapp"></i></div>
                    <div class="channel-title">WhatsApp</div>
                    <p class="channel-desc">The fastest way to order. Send a message, share images, get a quote and place your order — all in one chat.</p>
                    <div class="channel-detail">
                        <div class="channel-detail-item"><i class="fas fa-circle dot-green pulse-dot" style="font-size:.5rem;color:var(--clr-green)"></i><span><strong>Online Now</strong> — typically replies instantly</span></div>
                        <div class="channel-detail-item"><i class="fas fa-clock" style="color:var(--clr-green)"></i><span>Available 9 AM – 8 PM, Mon–Sat</span></div>
                        <div class="channel-detail-item"><i class="fas fa-file" style="color:var(--clr-green)"></i><span>Share files, images and references directly</span></div>
                        <div class="channel-detail-item"><i class="fas fa-check-circle" style="color:var(--clr-green)"></i><span>Proof approval via chat</span></div>
                    </div>
                    <a href="https://wa.me/923001234567?text=Hi!%20I%20want%20to%20place%20a%20print%20order." target="_blank" rel="noopener" class="channel-cta" style="background:linear-gradient(135deg,#25d366,#128c7e);color:#fff;box-shadow:0 4px 20px rgba(37,211,102,.4)">
                        <i class="fab fa-whatsapp"></i> Chat Now: +92 300 1234567
                    </a>
                </div>

                <!-- Call -->
                <div class="channel-card cc-call reveal reveal-d2">
                    <div class="channel-icon" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)"><i class="fas fa-phone"></i></div>
                    <div class="channel-title">Call Us</div>
                    <p class="channel-desc">Prefer to talk? Call us directly. Our team is ready to discuss your requirements and give you an instant quote over the phone.</p>
                    <div class="channel-detail">
                        <div class="channel-detail-item"><i class="fas fa-clock" style="color:#3b82f6"></i><span>Mon–Sat: 9:00 AM – 8:00 PM</span></div>
                        <div class="channel-detail-item"><i class="fas fa-clock" style="color:#3b82f6"></i><span>Sunday: 10:00 AM – 6:00 PM</span></div>
                        <div class="channel-detail-item"><i class="fas fa-phone" style="color:#3b82f6"></i><span>+92 300 1234567</span></div>
                        <div class="channel-detail-item"><i class="fas fa-comments" style="color:#3b82f6"></i><span>Instant pricing discussion</span></div>
                    </div>
                    <a href="tel:+923001234567" class="channel-cta" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8);color:#fff;box-shadow:0 4px 20px rgba(59,130,246,.4)">
                        <i class="fas fa-phone"></i> Call Now
                    </a>
                </div>

                <!-- Visit / Form -->
                <div class="channel-card cc-visit reveal reveal-d4">
                    <div class="channel-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706)"><i class="fas fa-store"></i></div>
                    <div class="channel-title">Visit / Quote Form</div>
                    <p class="channel-desc">Walk into our Gawalmandi shop for an in-person consultation, or fill our detailed quote form online and we'll respond within 1 hour.</p>
                    <div class="channel-detail">
                        <div class="channel-detail-item"><i class="fas fa-map-marker-alt" style="color:var(--clr-accent)"></i><span>Hamza Center, Gawalmandi, Lahore</span></div>
                        <div class="channel-detail-item"><i class="fas fa-clock" style="color:var(--clr-accent)"></i><span>Mon–Sat: 9 AM – 8 PM</span></div>
                        <div class="channel-detail-item"><i class="fas fa-envelope" style="color:var(--clr-accent)"></i><span>info@subhanprinters.com</span></div>
                        <div class="channel-detail-item"><i class="fas fa-reply" style="color:var(--clr-accent)"></i><span>Email / form reply within 2 hours</span></div>
                    </div>
                    <a href="<?php echo base_url('contact'); ?>" class="channel-cta" style="background:linear-gradient(135deg,#f59e0b,#d97706);color:#111;box-shadow:0 4px 20px rgba(245,158,11,.4)">
                        <i class="fas fa-file-invoice-dollar"></i> Fill Quote Form
                    </a>
                </div>

            </div>
        </div>
    </section>

    <!-- ═══ PRICING SNAPSHOT ═══ -->
    <section id="order-pricing" class="section" aria-label="Pricing snapshot">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Transparent Pricing</div>
                <h2 class="section-title">Quick Price <span class="highlight">Reference</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">Starting prices — final quote depends on quantity, size and finishing options</p>
            </div>
            <div style="overflow-x:auto;margin-top:8px">
                <table class="pricing-table reveal" aria-label="Product pricing table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Starting Price</th>
                            <th>Min Qty</th>
                            <th>Turnaround</th>
                            <th>Order</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pricingData as $item): ?>
                        <tr>
                            <td><i class="fas <?php echo $item['icon']; ?>" style="color:<?php echo $item['color']; ?>"></i> <?php echo $item['product']; ?></td>
                            <td class="pt-price"><?php echo $item['price']; ?></td>
                            <td><?php echo $item['min_qty']; ?></td>
                            <td><span class="pt-turnaround <?php echo $item['turnaround_class']; ?>"><?php echo $item['turnaround']; ?></span></td>
                            <td><a href="https://wa.me/923001234567?text=I%20want%20<?php echo urlencode($item['product']); ?>" target="_blank" rel="noopener" class="pt-action"><i class="fab fa-whatsapp"></i> Order</a></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <p style="text-align:center;font-size:.82rem;color:var(--clr-muted);margin-top:16px">
                <i class="fas fa-info-circle" style="color:var(--clr-primary)"></i>
                All prices are starting prices. Final quote depends on quantity, size, paper type and finishing. Contact us for exact pricing.
            </p>
        </div>
    </section>

    <!-- ═══ GUARANTEES ═══ -->
    <section id="order-guarantees" class="section" aria-label="Our guarantees">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Our Promise</div>
                <h2 class="section-title">Order with <span class="highlight">Confidence</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">Every order backed by our 25-year reputation and iron-clad guarantees</p>
            </div>
            <div class="guarantees-grid">
                <div class="guar-card reveal">
                    <span class="guar-icon">🛡️</span>
                    <div class="guar-title">100% Quality Guarantee</div>
                    <div class="guar-desc">If your print doesn't match the approved proof, we reprint it free of charge — no arguments, no hassle.</div>
                </div>
                <div class="guar-card reveal reveal-d1">
                    <span class="guar-icon">🔄</span>
                    <div class="guar-title">Free Reprints on Our Error</div>
                    <div class="guar-desc">Any mistake on our side — colour, size, finish — we correct it at zero cost to you. Always.</div>
                </div>
                <div class="guar-card reveal reveal-d2">
                    <span class="guar-icon">🚚</span>
                    <div class="guar-title">On-Time Delivery</div>
                    <div class="guar-desc">We commit to a delivery date and we keep it. If we're late due to our fault, you get a discount on your next order.</div>
                </div>
                <div class="guar-card reveal reveal-d3">
                    <span class="guar-icon">💬</span>
                    <div class="guar-title">Proof Before Print</div>
                    <div class="guar-desc">We always send a digital proof for your approval before going to press. We never print without your sign-off.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ FINAL BIG CTA ═══ -->
    <section id="order-final-cta" aria-label="Final call to action">
        <div class="fcta-blob fb1" aria-hidden="true"></div>
        <div class="fcta-blob fb2" aria-hidden="true"></div>
        <div class="fcta-blob fb3" aria-hidden="true"></div>
        <div class="container fcta-inner">
            <div class="reveal">
                <div class="tag" style="color:rgba(255,255,255,.8);background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);margin-bottom:24px">
                    Ready? Let's Do This.
                </div>
                <div class="fcta-title">
                    Start Your <span class="gold">Print Order</span><br>Right Now
                </div>
                <p class="fcta-desc">
                    Over 5,000 satisfied clients across Pakistan trust Subhan Printers.
                    Join them — send us a WhatsApp and get your quote in minutes.
                </p>
                <div class="fcta-btns">
                    <a href="https://wa.me/923001234567?text=Hi!%20I%20want%20to%20place%20a%20print%20order%20with%20Subhan%20Printers" target="_blank" rel="noopener" class="big-cta big-cta-wa">
                        <i class="fab fa-whatsapp"></i>
                        <span>
                            Order on WhatsApp
                            <span class="big-cta-sub">Fastest response · Reply in minutes</span>
                        </span>
                    </a>
                    <a href="<?php echo base_url('contact'); ?>" class="btn btn-lg" style="background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.3);padding:22px 40px;font-size:1rem">
                        <i class="fas fa-file-invoice-dollar"></i> Fill Quote Form
                    </a>
                </div>
                <div class="fcta-trust">
                    <div class="fcta-trust-item"><i class="fas fa-shield-alt"></i> 100% Satisfaction</div>
                    <div class="fcta-trust-item"><i class="fas fa-redo"></i> Free Reprints</div>
                    <div class="fcta-trust-item"><i class="fas fa-truck"></i> Nationwide Delivery</div>
                    <div class="fcta-trust-item"><i class="fas fa-lock"></i> Secure Payment</div>
                    <div class="fcta-trust-item"><i class="fas fa-star"></i> 4.9/5 Rating</div>
                </div>
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
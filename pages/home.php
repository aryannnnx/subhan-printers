<?php
// ============================================
// PAGES: Homepage - Subhan Printers
// ============================================

// Set page variables
$pageTitle = 'Subhan Printers | Professional Printing Services – Lahore';
$currentPage = 'home';
$pageStyles = 'index.css';
$pageScripts = 'index.js';

// Include header
require_once __DIR__ . '/../templates/header.php';

// Load models
require_once __DIR__ . '/../models/Portfolio.php';
require_once __DIR__ . '/../models/Product.php';

// Fetch data from database
$portfolioModel = new Portfolio();
$productModel = new Product();

// ============================================
// GET FEATURED PORTFOLIO ITEMS
// ============================================
$featuredPortfolio = $portfolioModel->getAll([
    'featured' => true,
    'limit' => 6
]);

// If no featured items, get any 6
if (empty($featuredPortfolio)) {
    $featuredPortfolio = $portfolioModel->getAll(['limit' => 6]);
}

// ============================================
// GET FEATURED PRODUCTS FOR HOMEPAGE
// ============================================
// First try to get featured products
$featuredProducts = $productModel->getAll([
    'featured' => true,
    'limit' => 16
]);

// If no featured products, get any 16
if (empty($featuredProducts)) {
    $featuredProducts = $productModel->getAll(['limit' => 16]);
}

// ============================================
// GET CATEGORIES FOR HOMEPAGE
// ============================================
$categories = [
    ['slug' => 'logo-branding', 'title' => 'Logo & Branding', 'subtitle' => 'Graphic Designing', 'image' => '/SP/images/design.avif'],
    ['slug' => 'website-app', 'title' => 'UV DTF STEEKERS', 'subtitle' => 'DTF Printing', 'image' => '/SP/images/dtf1.webp'],
    ['slug' => 'business-advertising', 'title' => 'Custom Boxes & Packging', 'subtitle' => 'Offest Printing', 'image' => '/SP/images/custom-boxes.png'],
    ['slug' => 'art-illustration', 'title' => 'Brand Identity', 'subtitle' => 'Digital Printing', 'image' => '/SP/images/digital.webp'],
];
?>

<style>
    /* ============================================================
       CLIENTS SECTION - TWO ROWS
       ============================================================ */
    #clients {
        background: var(--clr-surface);
        padding: 80px 0;
        overflow: hidden;
        position: relative;
    }

    .clients-row {
        position: relative;
    }

    .clients-row::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 80px;
        background: linear-gradient(to right, var(--clr-surface), transparent);
        z-index: 2;
        pointer-events: none;
    }

    .clients-row::after {
        content: '';
        position: absolute;
        right: 0;
        top: 0;
        bottom: 0;
        width: 80px;
        background: linear-gradient(to left, var(--clr-surface), transparent);
        z-index: 2;
        pointer-events: none;
    }

    .clients-marquee-wrapper {
        width: 100%;
        overflow: hidden;
        padding: 20px 0;
        position: relative;
        background: var(--clr-surface);
    }

    .clients-marquee-track {
        display: flex;
        gap: 25px;
        animation: scrollClients 25s linear infinite;
        width: max-content;
    }

    .clients-marquee-track.rev {
        animation: scrollClientsRev 25s linear infinite;
    }

    .clients-marquee-wrapper:hover .clients-marquee-track {
        animation-play-state: paused;
    }

    .client-logo-item {
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 150px;
        height: 80px;
        background: var(--bg-card);
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 12px 20px;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }

    .client-logo-item:hover {
        border-color: rgba(139, 92, 246, 0.4);
        transform: translateY(-4px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.2);
    }

    .client-logo-item img {
        max-width: 100%;
        max-height: 65px;
        object-fit: contain;
        transition: all 0.3s ease;
        mix-blend-mode: multiply;
        filter: brightness(1) contrast(1.1);
    }

    [data-theme="dark"] .client-logo-item img {
        filter: brightness(0.9) contrast(1.2) saturate(0.8);
    }

    [data-theme="light"] .client-logo-item img {
        filter: brightness(1) contrast(1.1) saturate(0.9);
    }

    .client-logo-item:hover img {
        transform: scale(1.05);
    }

    .client-logo-text {
        font-size: 0.9rem;
        font-weight: 700;
        color: var(--text-primary);
        letter-spacing: 0.5px;
        font-family: 'DM Sans', sans-serif;
        text-align: center;
    }

    @keyframes scrollClients {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }

    @keyframes scrollClientsRev {
        0% { transform: translateX(-50%); }
        100% { transform: translateX(0); }
    }

    /* Responsive */
    @media (max-width: 1200px) {
        .client-logo-item { min-width: 130px; height: 70px; padding: 10px 16px; }
        .client-logo-item img { max-height: 42px; }
        .clients-marquee-track { gap: 20px; animation: scrollClients 22s linear infinite; }
        .clients-marquee-track.rev { animation: scrollClientsRev 22s linear infinite; }
    }

    @media (max-width: 992px) {
        .client-logo-item { min-width: 110px; height: 60px; padding: 8px 12px; }
        .client-logo-item img { max-height: 35px; }
        .clients-marquee-track { gap: 16px; animation: scrollClients 18s linear infinite; }
        .clients-marquee-track.rev { animation: scrollClientsRev 18s linear infinite; }
        .clients-row::before, .clients-row::after { width: 50px; }
    }

    @media (max-width: 768px) {
        .client-logo-item { min-width: 90px; height: 50px; padding: 6px 10px; }
        .client-logo-item img { max-height: 30px; }
        .clients-marquee-track { gap: 12px; animation: scrollClients 15s linear infinite; }
        .clients-marquee-track.rev { animation: scrollClientsRev 15s linear infinite; }
        .clients-row::before, .clients-row::after { width: 30px; }
    }

    @media (max-width: 480px) {
        .client-logo-item { min-width: 70px; height: 40px; padding: 4px 8px; }
        .client-logo-item img { max-height: 24px; }
        .clients-marquee-track { gap: 8px; animation: scrollClients 12s linear infinite; }
        .clients-marquee-track.rev { animation: scrollClientsRev 12s linear infinite; }
        .client-logo-text { font-size: 0.6rem; }
        .clients-row::before, .clients-row::after { width: 20px; }
    }
</style>

<main>
    <!-- ============================================================
         HERO SECTION
         ============================================================ -->
    <section id="hero" aria-label="Hero">
        <div class="hero-bg" aria-hidden="true"></div>
        <div class="hero-grid" aria-hidden="true"></div>

        <div class="container hero-inner">
            <!-- Left content -->
            <div class="hero-left">
                <div class="hero-eyebrow" aria-hidden="true">Gawalmandi, Lahore</div>
                <h1 class="hero-title">
                    Bring Your Designs
                    <span class="line-gold">to Life.</span>
                </h1>
                <p class="hero-desc">
                    Professional graphic designing and printing services in Lahore, Pakistan. Custom
                    boxes, product packaging, digital printing, stickers,
                    promotional materials,
                    &amp; more — delivered with precision .quality, and attention to detail.
                </p>
                <div class="hero-actions">
                    <a href="<?php echo base_url('contact'); ?>" class="btn btn-accent btn-lg">
                        <i class="fas fa-shopping-cart" aria-hidden="true"></i> Get a Quote
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a href="<?php echo base_url('portfolio'); ?>" class="btn btn-outline btn-lg">
                        <i class="fas fa-images" aria-hidden="true"></i> View Portfolio
                    </a>
                </div>
                <div class="hero-stats" aria-label="Statistics">
                    <div>
                        <div class="hero-stat-num">3000+</div>
                        <div class="hero-stat-label">Happy Clients</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">15K+</div>
                        <div class="hero-stat-label">Projects Done</div>
                    </div>
                    <div>
                        <div class="hero-stat-num">25+</div>
                        <div class="hero-stat-label">Years Experience</div>
                    </div>
                </div>
            </div>

            <!-- Right visual — image slider card -->
            <div class="hero-visual" aria-hidden="true">
                <div class="hero-float-1 hero-float"></div>
                <div class="hero-float-2 hero-float"></div>
                <div class="hero-card">
                    <div class="hero-slider-wrap" id="heroSliderWrap">
                        <div class="hero-slide active" data-title="Graphic Design &amp; Print" data-sub="Wedding Cards · Flex · Packaging">
                            <img src="images/HERO.jpg"  alt="Product packaging design" onerror="this.src='https://placehold.co/480x300/1a1a2e/8b5cf6?text=Packaging+Design'" />
                        </div>
                        <div class="hero-slide" data-title="Wedding Card Collection" data-sub="Elegant · Traditional · Luxury">
                            <img src="images/fabric embroidery.png" alt="Wedding card designs" onerror="this.src='https://placehold.co/480x300/1a1a2e/ec4899?text=Wedding+Cards'" />
                        </div>
                        <div class="hero-slide" data-title="Box &amp; Packaging" data-sub="Custom · Premium · Bulk Orders">
                            <img src="images/custom boxes.webp" alt="Box packaging design" onerror="this.src='https://placehold.co/480x300/1a1a2e/f59e0b?text=Box+Packaging'" />
                        </div>
                        <div class="hero-slide" data-title="Flex &amp; Banners" data-sub="Large Format · Vivid Colours · Fast">
                            <img src="images/portfolio2.webp" alt="Flex banner printing" onerror="this.src='https://placehold.co/480x300/1a1a2e/22c55e?text=Flex+Banners'" />
                        </div>
                        <div class="hero-slide" data-title="Brochures &amp; Flyers" data-sub="Corporate · Restaurant · Retail">
                            <img src="images/handhero.png " alt="Brochure design" onerror="this.src='https://placehold.co/480x300/1a1a2e/3b82f6?text=Brochures'" />
                        </div>
                        <div class="hero-slide" data-title="Brochures &amp; Flyers" data-sub="Corporate · Restaurant · Retail">
                            <img src="images/d1.png"  alt="Brochure design" onerror="this.src='https://placehold.co/480x300/1a1a2e/3b82f6?text=Brochures'" />
                        </div>
                        <div class="hero-slider-dots" id="heroSliderDots"></div>
                    </div>
                    <div class="hero-card-meta">
                        <div>
                            <div class="hero-card-title" id="heroSlideTitle">Graphic Design &amp; Print</div>
                            <div class="hero-card-sub" id="heroSlideSub">Wedding Cards · Flex · Packaging</div>
                        </div>
                        <i class="fas fa-check-circle" style="color:var(--clr-green);font-size:1.4rem;flex-shrink:0" aria-hidden="true"></i>
                    </div>
                    <div class="hero-badge" aria-hidden="true">Best<br>Quality</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         DESIGN CATEGORIES
         ============================================================ -->
    <section id="categories" class="section" aria-label="Design Categories">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">
                    <i class="fas fa-crown" aria-hidden="true"></i> Creative Solutions
                </div>
                <h2 class="section-title">Printing for <span class="highlight">What You Need</span></h2>
                <p class="section-desc">Explore our collection of stunning designs and quality printing projects</p>
            </div>
        </div>

        <div class="categories-grid-wrapper">
            <div class="categories-grid categories-grid-4">
                <?php foreach ($categories as $category): ?>
                    <a href="<?php echo base_url('services'); ?>?category=<?php echo $category['slug']; ?>" 
                       class="cat-card-link" 
                       style="text-decoration: none; display: block;">
                        <article class="cat-card" data-category="<?php echo $category['slug']; ?>">
                            <div class="cat-card-image-wrap">
                                <img class="cat-card-img" src="<?php echo $category['image']; ?>"
                                    alt="<?php echo $category['title']; ?>" loading="lazy" />
                                <div class="cat-card-overlay">
                                    <span class="cat-card-overlay-text">Explore</span>
                                </div>
                            </div>
                            <div class="cat-card-body">
                                <h3 class="cat-card-title"><?php echo $category['title']; ?></h3>
                                <p class="cat-card-sub"><?php echo $category['subtitle']; ?></p>
                                <span class="cat-card-arrow">Explore <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
                            </div>
                        </article>
                    </a>
                <?php endforeach; ?>
            </div>

            <div style="text-align:center;margin-top:48px" class="reveal">
                <a href="<?php echo base_url('services'); ?>" class="btn btn-outline btn-lg">
                    View All Category <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PORTFOLIO SECTION
         ============================================================ -->
    <section id="portfolio" class="section" aria-label="Portfolio">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Our Work</div>
                <h2 class="section-title">Featured <span class="highlight">Portfolio</span></h2>
                <p class="section-desc">Explore our collection of stunning designs and quality printing projects</p>
            </div>

            <div class="portfolio-grid" id="portfolio-grid">
                <?php 
                $delayClasses = ['', 'reveal-delay-1', 'reveal-delay-2', 'reveal-delay-3'];
                $index = 0;
                
                $portfolioItems = $portfolioModel->getAll(['limit' => 6]);
                
                if (!empty($portfolioItems) && is_array($portfolioItems)):
                    foreach ($portfolioItems as $item): 
                        $delay = $delayClasses[$index % 4];
                        
                        $imageUrl = 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($item['title'] ?? 'Portfolio');
                        if (!empty($item['primary_image'])) {
                            $imageUrl = $item['primary_image']['url'];
                        } elseif (!empty($item['images']) && is_array($item['images']) && !empty($item['images'][0]['url'])) {
                            $imageUrl = $item['images'][0]['url'];
                        }
                        
                        $category = $item['category'] ?? '';
                        $categoryName = $item['category_name'] ?? $category;
                ?>
                <article class="portfolio-item reveal <?php echo $delay; ?>" 
                         data-category="<?php echo htmlspecialchars($category); ?>" 
                         data-id="<?php echo $item['id'] ?? ''; ?>"
                         data-title="<?php echo htmlspecialchars($item['title'] ?? ''); ?>"
                         data-desc="<?php echo htmlspecialchars($item['description'] ?? ''); ?>"
                         data-img="<?php echo htmlspecialchars($imageUrl); ?>"
                         tabindex="0" role="button" 
                         aria-label="View <?php echo htmlspecialchars($item['title'] ?? 'Project'); ?>">
                    <img class="portfolio-img" src="<?php echo htmlspecialchars($imageUrl); ?>" 
                         alt="<?php echo htmlspecialchars($item['title'] ?? 'Portfolio'); ?>"
                         loading="lazy" 
                         onerror="this.src='https://placehold.co/600x400/1a1a2e/8b5cf6?text=<?php echo urlencode($item['title'] ?? ''); ?>'" />
                    <div class="portfolio-overlay">
                        <div class="portfolio-category"><?php echo ucfirst(htmlspecialchars($categoryName)); ?></div>
                        <div class="portfolio-title"><?php echo htmlspecialchars($item['title'] ?? ''); ?></div>
                        <div class="portfolio-sub"><?php echo htmlspecialchars($item['subtitle'] ?? ''); ?></div>
                    </div>
                    <div class="portfolio-overlay-hover" aria-hidden="true">
                        <i class="fas fa-search-plus" style="font-size:2rem;color:#fff"></i>
                    </div>
                </article>
                <?php 
                    $index++;
                    endforeach; 
                else:
                ?>
                <div style="grid-column:1/-1;text-align:center;padding:40px;color:#888;">
                    <i class="fas fa-images" style="font-size:2rem;display:block;margin-bottom:10px;"></i>
                    No portfolio items available. Please add some in the admin panel.
                </div>
                <?php endif; ?>
            </div>

            <div style="text-align:center;margin-top:48px" class="reveal">
                <a href="<?php echo base_url('portfolio'); ?>" class="btn btn-outline btn-lg">
                    View Full Portfolio <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PRODUCTS SHOWCASE - ONLY FEATURED
         ============================================================ -->
    <section id="products" class="section" aria-label="Products">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Our Products</div>
                <h2 class="section-title">What We <span class="highlight">Print</span></h2>
                <p class="section-desc">From small stickers to corrugation boxes — we print everything</p>
            </div>

            <div class="products-grid">
                <?php
                $productDelay = ['', 'reveal-delay-1', 'reveal-delay-2', 'reveal-delay-3', 'reveal-delay-4'];
                $idx = 0;
                foreach ($featuredProducts as $product):
                    $delay = $productDelay[$idx % 5];
                    $imageUrl = !empty($product['image_url']) ? $product['image_url'] : 'https://placehold.co/300x180/1a1a2e/8b5cf6?text=' . urlencode($product['name']);
                ?>
                <div class="product-card reveal <?php echo $delay; ?>" data-product-id="<?php echo $product['id']; ?>">
                    <img src="<?php echo $imageUrl; ?>" alt="<?php echo $product['name']; ?>" loading="lazy" width="300" height="180" />
                    <div class="product-caption"><?php echo $product['name']; ?></div>
                </div>
                <?php
                $idx++;
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- ============================================================
         GRAPHIC DESIGN SERVICES
         ============================================================ -->
    <section id="design-services" class="section" aria-label="Graphic Design Services">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag"><i class="fas fa-palette" aria-hidden="true"></i> Creative Solutions</div>
                <h2 class="section-title">Professional <span class="highlight">Graphic Designing</span> Services</h2>
                <p class="section-desc">Transform your ideas into stunning visuals. From logos to complete branding, we bring your vision to life.</p>
            </div>

            <div class="ds-stats">
                <div class="ds-stat reveal"><div class="ds-stat-num">500+</div><div class="ds-stat-label">Designs Delivered</div></div>
                <div class="ds-stat reveal reveal-delay-2"><div class="ds-stat-num">98%</div><div class="ds-stat-label">Client Satisfaction</div></div>
                <div class="ds-stat reveal reveal-delay-3"><div class="ds-stat-num">24/7</div><div class="ds-stat-label">Support Available</div></div>
                <div class="ds-stat reveal reveal-delay-4"><div class="ds-stat-num">2–3d</div><div class="ds-stat-label">Fast Delivery</div></div>
            </div>

            <div class="ds-grid">
                <div class="ds-card reveal" data-service-id="logo-brand-identity">
                    <div class="ds-card-header" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">
                        <i class="fas fa-crown" style="color:#fff" aria-hidden="true"></i>
                    </div>
                    <div class="ds-card-body">
                        <h3 class="ds-card-title">Logo &amp; Brand Identity</h3>
                        <p class="ds-card-desc">Unique, memorable logos that tell your brand's story and leave lasting impressions.</p>
                        <div class="ds-card-price">Starting from ₨ 2,999</div>
                    </div>
                </div>
                <div class="ds-card reveal reveal-delay-1" data-service-id="social-media">
                    <div class="ds-card-header" style="background:linear-gradient(135deg,#3b82f6,#06b6d4)">
                        <i class="fas fa-hashtag" style="color:#fff" aria-hidden="true"></i>
                    </div>
                    <div class="ds-card-body">
                        <h3 class="ds-card-title">Social Media Graphics</h3>
                        <p class="ds-card-desc">Engaging posts, stories, and covers that boost your online presence and engagement.</p>
                        <div class="ds-card-price">Starting from ₨ 1,499</div>
                    </div>
                </div>
                <div class="ds-card reveal reveal-delay-2" data-service-id="packaging-design">
                    <div class="ds-card-header" style="background:linear-gradient(135deg,#22c55e,#14b8a6)">
                        <i class="fas fa-box-open" style="color:#fff" aria-hidden="true"></i>
                    </div>
                    <div class="ds-card-body">
                        <h3 class="ds-card-title">Packaging Design</h3>
                        <p class="ds-card-desc">Eye-catching product packaging that stands out on shelves and delights customers.</p>
                        <div class="ds-card-price">Starting from ₨ 4,999</div>
                    </div>
                </div>
                <div class="ds-card reveal reveal-delay-1" data-service-id="print-design">
                    <div class="ds-card-header" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">
                        <i class="fas fa-print" style="color:#fff" aria-hidden="true"></i>
                    </div>
                    <div class="ds-card-body">
                        <h3 class="ds-card-title">Print Design</h3>
                        <p class="ds-card-desc">Brochures, flyers, business cards, and more — ready for professional printing.</p>
                        <div class="ds-card-price">Starting from ₨ 2,499</div>
                    </div>
                </div>
                <div class="ds-card reveal reveal-delay-2" data-service-id="web-app-ui">
                    <div class="ds-card-header" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">
                        <i class="fas fa-laptop-code" style="color:#fff" aria-hidden="true"></i>
                    </div>
                    <div class="ds-card-body">
                        <h3 class="ds-card-title">Web &amp; App UI Design</h3>
                        <p class="ds-card-desc">Beautiful, user-friendly interfaces that enhance digital experiences.</p>
                        <div class="ds-card-price">Starting from ₨ 7,999</div>
                    </div>
                </div>
                <div class="ds-card reveal reveal-delay-3" data-service-id="illustration">
                    <div class="ds-card-header" style="background:linear-gradient(135deg,#ec4899,#f43f5e)">
                        <i class="fas fa-paintbrush" style="color:#fff" aria-hidden="true"></i>
                    </div>
                    <div class="ds-card-body">
                        <h3 class="ds-card-title">Custom Illustrations</h3>
                        <p class="ds-card-desc">Unique hand-drawn and digital illustrations for any project or purpose.</p>
                        <div class="ds-card-price">Starting from ₨ 3,999</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         HERO BANNER 2 — Manufacturing
         ============================================================ -->
    <section id="hero2" aria-label="Manufacturing">
        <div class="hero2-bg" role="img" aria-label="Subhan Printers manufacturing facility"></div>
        <div class="hero2-overlay" aria-hidden="true"></div>
        <div class="hero2-inner reveal">
            <h2 class="hero2-title">
                Complete Custom Box Manufacturing<br>under One Roof in <span>Lahore.</span>
            </h2>
            <p class="hero2-desc">
                From designing, to printing plates, offset printing, lamination, die-cutting and pasting —
                we do it all in our own manufacturing facility. State-of-the-art equipment and expert
                manpower for any custom printing job.
            </p>
            <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="btn btn-accent btn-lg">
                Shop Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </section>

    <!-- Category cards below hero2 -->
    <div class="cat3-grid container" style="margin-bottom:0">
        <div class="cat3-card" data-cat3="custom-boxes">
            <div class="cat3-icon">
                <svg viewBox="0 0 56 56" aria-hidden="true">
                    <rect x="12" y="8" width="32" height="36" rx="2" stroke-linejoin="round" />
                    <line x1="12" y1="18" x2="44" y2="18" />
                    <path d="M22 8 V4 a2 2 0 0 1 2-2 h8 a2 2 0 0 1 2 2 v4" />
                    <rect x="20" y="24" width="16" height="10" rx="1" stroke-linejoin="round" />
                </svg>
            </div>
            <h3 class="cat3-title">Custom Boxes</h3>
            <p class="cat3-sub">Premium quality custom printed boxes</p>
            <span class="cat3-link">Explore <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </div>
        <div class="cat3-card" data-cat3="custom-bags">
            <div class="cat3-icon">
                <svg viewBox="0 0 56 56" aria-hidden="true">
                    <path d="M16 18 L16 12 a12 12 0 0 1 24 0 v6" />
                    <rect x="8" y="18" width="40" height="32" rx="2" stroke-linejoin="round" />
                    <line x1="8" y1="26" x2="48" y2="26" />
                    <path d="M24 18 v-6 a4 4 0 0 1 8 0 v6" />
                </svg>
            </div>
            <h3 class="cat3-title">Custom Printed Bags</h3>
            <p class="cat3-sub">Branded paper &amp; craft bags</p>
            <span class="cat3-link">Explore <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </div>
        <div class="cat3-card" data-cat3="food-packaging">
            <div class="cat3-icon">
                <svg viewBox="0 0 56 56" aria-hidden="true">
                    <rect x="10" y="30" width="36" height="16" rx="3" stroke-linejoin="round" />
                    <path d="M12 30 Q12 22 28 22 Q44 22 44 30" />
                    <rect x="18" y="24" width="20" height="6" rx="2" stroke-linejoin="round" />
                    <line x1="10" y1="38" x2="46" y2="38" stroke-dasharray="3 3" />
                    <rect x="20" y="10" width="16" height="10" rx="2" stroke-linejoin="round" />
                </svg>
            </div>
            <h3 class="cat3-title">Food Packaging Boxes</h3>
            <p class="cat3-sub">Food-safe packaging solutions</p>
            <span class="cat3-link">Explore <i class="fas fa-arrow-right" aria-hidden="true"></i></span>
        </div>
    </div>

    <!-- ============================================================
         HOW IT WORKS
         ============================================================ -->
    <section id="process" class="section" aria-label="How It Works">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Our Process</div>
                <h2 class="section-title">How It <span class="highlight">Works</span></h2>
                <p class="section-desc">From concept to delivery, we turn your ideas into perfectly printed reality</p>
            </div>
            <div class="process-grid">
                <div class="process-card reveal">
                    <div class="process-num p1" aria-hidden="true">1</div>
                    <div class="process-icon" aria-hidden="true"><i class="fas fa-upload" style="color:var(--clr-primary)"></i></div>
                    <h3 class="process-title">Choose &amp; Upload</h3>
                    <p class="process-desc">Select your design or upload your artwork — we'll handle the rest.</p>
                </div>
                <div class="process-card reveal reveal-delay-2">
                    <div class="process-num p2" aria-hidden="true">2</div>
                    <div class="process-icon" aria-hidden="true"><i class="fas fa-file-invoice-dollar" style="color:#3b82f6"></i></div>
                    <h3 class="process-title">Get Quote &amp; Confirm</h3>
                    <p class="process-desc">Instant pricing with transparent confirmation — no hidden costs.</p>
                </div>
                <div class="process-card reveal reveal-delay-3">
                    <div class="process-num p3" aria-hidden="true">3</div>
                    <div class="process-icon" aria-hidden="true"><i class="fas fa-print" style="color:var(--clr-accent)"></i></div>
                    <h3 class="process-title">Printing Starts</h3>
                    <p class="process-desc">Our experts print your designs using premium inks and technology.</p>
                </div>
                <div class="process-card reveal reveal-delay-4">
                    <div class="process-num p4" aria-hidden="true">4</div>
                    <div class="process-icon" aria-hidden="true"><i class="fas fa-truck" style="color:var(--clr-green)"></i></div>
                    <h3 class="process-title">Pickup or Delivery</h3>
                    <p class="process-desc">Collect from our shop or enjoy fast home delivery — your choice!</p>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         BEFORE / AFTER COMPARISON SLIDER
         ============================================================ -->
    <section id="comparison" class="section" aria-label="Design vs Implementation">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">See the Difference</div>
                <h2 class="section-title">Design <span class="highlight">vs</span> Final Box</h2>
                <p class="section-desc">Drag the handle to compare the original design with the finished product</p>
            </div>

            <div class="comparison-wrap reveal">
                <div class="comparison-header">
                    <h3>Design vs Final Implementation</h3>
                    <p>Drag the divider left or right to compare</p>
                </div>

                <div class="comparison-slider-container" id="cs-container" role="img" aria-label="Before and after comparison slider">
                    <img class="cs-img" id="cs-left" src="images/1.png" alt="Simple design concept" onerror="this.src='https://placehold.co/900x460/1a1a2e/8b5cf6?text=Simple+Design'" />
                    <div class="cs-right-wrapper" id="cs-right-wrapper">
                        <img id="cs-right" src="images/Untitled design (1).png" alt="Final implementation on box" onerror="this.src='https://placehold.co/900x460/0d1117/f59e0b?text=Final+Implementation'" />
                    </div>
                    <div class="cs-handle" id="cs-handle" aria-hidden="true">
                        <div class="cs-handle-btn">◀▶</div>
                    </div>
                    <span class="cs-label cs-label-left" aria-hidden="true">🎨 Simple Design</span>
                    <span class="cs-label cs-label-right" aria-hidden="true">📦 On Box</span>
                </div>

                <div class="comparison-footer" aria-hidden="true">
                    ✨ Drag the white handle left or right to compare concept vs physical packaging
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PACKAGING HIGHLIGHT
         ============================================================ -->
    <section id="packaging-highlight" class="section" aria-label="Packaging Features">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag tag-light">
                    <i class="fas fa-box" style="margin-right:6px;"></i> Why Us
                </div>
                <h2 class="section-title-light">Get All Kinds of <span class="highlight-light">Packaging</span></h2>
                <p class="section-desc-light">Custom design · All sizes &amp; styles · Fast turnaround</p>
            </div>

            <div class="packaging-grid">
                <div class="packaging-card-light reveal">
                    <div class="packaging-card-image-light">
                        <img src="images/gif1.gif" alt="Custom design, sizes and styles animation" loading="lazy" />
                    </div>
                    <div class="packaging-card-content-light">
                        <div class="packaging-card-title-light">🎨 Custom Design, Sizes &amp; Styles</div>
                        <div class="packaging-card-sub-light">Tailored packaging solutions for every need</div>
                    </div>
                </div>

                <div class="packaging-card-light reveal reveal-delay-2">
                    <div class="packaging-card-image-light">
                        <img src="images/gif2.gif" alt="High quality offset printing animation" loading="lazy" />
                    </div>
                    <div class="packaging-card-content-light">
                        <div class="packaging-card-title-light">📦 High Quality Offset Printing For Bulk Orders</div>
                        <div class="packaging-card-sub-light">Consistent colour · large format · wholesale pricing</div>
                    </div>
                </div>

                <div class="packaging-card-light reveal reveal-delay-4">
                    <div class="packaging-card-image-light">
                        <img src="images/gif3.gif" alt="Fast shipping animation" loading="lazy" />
                    </div>
                    <div class="packaging-card-content-light">
                        <div class="packaging-card-title-light">🚚 Fast Shipping 2–5 Business Days</div>
                        <div class="packaging-card-sub-light">Tracked nationwide · express delivery available</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         CLIENTS MARQUEE
         ============================================================ -->
    <section id="clients" class="section" aria-label="Our Clients">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Trusted Partners</div>
                <h2 class="section-title">Our <span class="highlight">Clients</span></h2>
                <p class="section-desc">We are proud to serve these amazing brands and businesses</p>
            </div>
        </div>

        <!-- Row 1 -->
        <div class="clients-row">
            <div class="clients-marquee-wrapper">
                <div class="clients-marquee-track">
                    <?php
                    $clientLogos = [
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo15.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo16.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo17.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo18.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo19.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo20.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo21.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo3.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo23.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo24.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo25.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo16.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo27.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo28.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo29.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo31.png'],
                    ];

                    for ($loop = 0; $loop < 3; $loop++):
                        foreach ($clientLogos as $logo):
                    ?>
                    <div class="client-logo-item">
                        <img src="<?php echo $logo['image']; ?>" alt="<?php echo htmlspecialchars($logo['name']); ?>" loading="lazy" onerror="this.style.display='none';this.parentElement.querySelector('.client-logo-text').style.display='block';" />
                        <span class="client-logo-text" style="display:none;"><?php echo htmlspecialchars($logo['name']); ?></span>
                    </div>
                    <?php
                        endforeach;
                    endfor;
                    ?>
                </div>
            </div>
        </div>

        <!-- Row 2 -->
        <div class="clients-row" style="margin-top:20px;">
            <div class="clients-marquee-wrapper">
                <div class="clients-marquee-track rev">
                    <?php
                    $clientLogos2 = [
                        ['name' => 'BURAQ', 'image' => 'images/LOGO/logo1.png'],
                        ['name' => 'FASHIONFINAL', 'image' => 'images/LOGO/logo2.png'],
                        ['name' => 'CHARLOS', 'image' => 'images/LOGO/logo22.png'],
                        ['name' => 'DOROPLUS', 'image' => 'images/LOGO/logo4.png'],
                        ['name' => 'SHAMPOO+', 'image' => 'images/LOGO/logo5.png'],
                        ['name' => 'DRRINSEREFIL', 'image' => 'images/LOGO/logo6.png'],
                        ['name' => 'ELITECABLES4C', 'image' => 'images/LOGO/logo7.png'],
                        ['name' => 'EURO-', 'image' => 'images/LOGO/logo8.png'],
                        ['name' => 'SAKURA', 'image' => 'images/LOGO/logo9.png'],
                        ['name' => 'FASHION', 'image' => 'images/LOGO/logo10.png'],
                        ['name' => 'ELITE', 'image' => 'images/LOGO/logo11.png'],
                        ['name' => 'FASHION', 'image' => 'images/LOGO/logo12.png'],
                        ['name' => 'FASHION', 'image' => 'images/LOGO/logo13.png'],
                        ['name' => 'FASHION', 'image' => 'images/LOGO/logo14.png'],
                        ['name' => 'Ayyan', 'image' => 'images/LOGO/logo30.png'],
                    ];

                    for ($loop = 0; $loop < 3; $loop++):
                        foreach ($clientLogos2 as $logo):
                    ?>
                    <div class="client-logo-item">
                        <img src="<?php echo $logo['image']; ?>" alt="<?php echo htmlspecialchars($logo['name']); ?>" loading="lazy" onerror="this.style.display='none';this.parentElement.querySelector('.client-logo-text').style.display='block';" />
                        <span class="client-logo-text" style="display:none;"><?php echo htmlspecialchars($logo['name']); ?></span>
                    </div>
                    <?php
                        endforeach;
                    endfor;
                    ?>
                </div>
            </div>
        </div>

        <div style="text-align:center;margin-top:40px" class="container">
            <a href="<?php echo base_url('clients'); ?>" class="btn btn-primary btn-lg reveal">
                View All Clients <i class="fas fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>
    </section>

    <!-- ============================================================
         NEWSLETTER
         ============================================================ -->
    <section id="newsletter" aria-label="Newsletter Signup">
        <div class="newsletter-inner">
            <div class="newsletter-icon" aria-hidden="true"><i class="fas fa-envelope"></i></div>
            <h2 class="newsletter-title">Stay Updated with Our <span>Newsletter</span></h2>
            <p class="newsletter-desc">Get exclusive offers, design inspiration, and printing trends directly to your inbox.</p>
            <form class="newsletter-form" id="newsletter-form" novalidate aria-label="Email newsletter signup">
                <input type="email" name="email" placeholder="Enter your email address" required autocomplete="email" aria-label="Email address" />
                <button type="submit" class="btn btn-primary">Subscribe <i class="fas fa-paper-plane" aria-hidden="true"></i></button>
            </form>
            <p id="newsletter-msg" style="margin-top:16px;font-size:0.85rem;color:rgba(255,255,255,0.7)" aria-live="polite"></p>
        </div>
    </section>

    <!-- ============================================================
         TESTIMONIALS
         ============================================================ -->
    <section id="testimonials" class="section" aria-label="Client Testimonials">
        <div class="testimonials-bg-blobs" aria-hidden="true">
            <div class="t-blob t-blob-1"></div>
            <div class="t-blob t-blob-2"></div>
        </div>
        <div class="container" style="position:relative;z-index:1">
            <div class="text-center reveal">
                <div class="tag" style="color:rgba(255,255,255,0.7);background:rgba(255,255,255,0.08);border-color:rgba(255,255,255,0.15)">
                    <i class="fas fa-star" style="color:var(--clr-accent)" aria-hidden="true"></i> 500+ Happy Clients
                </div>
                <h2 class="section-title">What Our <span class="highlight">Clients Say</span></h2>
                <p class="section-desc" style="margin:0 auto">
                    Don't just take our word for it — hear from real clients who trusted us with their printing &amp; design needs
                </p>
            </div>

            <div class="featured-testimonial reveal">
                <div class="ft-inner">
                    <img class="ft-avatar" src="https://randomuser.me/api/portraits/women/68.jpg" alt="Ayesha Khan, satisfied client" loading="lazy" />
                    <div>
                        <div class="ft-stars" aria-label="5 stars">
                            <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i>
                            <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i>
                            <i class="fas fa-star" aria-hidden="true"></i>
                        </div>
                        <p class="ft-quote">
                            Absolutely blown away by the quality! Subhan Printers delivered our wedding cards earlier than expected
                            and the design was beyond beautiful. Highly recommended!
                        </p>
                        <p class="ft-name">Ayesha Khan</p>
                        <p class="ft-role">Bride &amp; Client — Lahore, Pakistan</p>
                    </div>
                </div>
            </div>

            <div class="t-grid">
                <div class="t-card reveal">
                    <div class="t-card-head">
                        <img class="t-avatar" src="https://randomuser.me/api/portraits/men/32.jpg" alt="Bilal Ahmed" loading="lazy" />
                        <div>
                            <div class="t-name">Bilal Ahmed</div>
                            <div class="t-role">Marketing Agency</div>
                        </div>
                    </div>
                    <div class="t-stars" aria-label="5 stars">
                        <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i>
                        <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i>
                        <i class="fas fa-star" aria-hidden="true"></i>
                    </div>
                    <p class="t-text">Best printing service in town! Got 5000 business cards printed, the quality is premium and the price was very reasonable. Fast delivery too!</p>
                    <div class="t-badge">📦 Box Packaging · 5000 pcs</div>
                </div>

                <div class="t-card reveal reveal-delay-2">
                    <div class="t-card-head">
                        <img class="t-avatar" src="https://randomuser.me/api/portraits/women/45.jpg" alt="Fatima Zafar" loading="lazy" />
                        <div>
                            <div class="t-name">Fatima Zafar</div>
                            <div class="t-role">E-commerce Owner</div>
                        </div>
                    </div>
                    <div class="t-stars" aria-label="5 stars">
                        <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i>
                        <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i>
                        <i class="fas fa-star-half-alt" aria-hidden="true"></i>
                    </div>
                    <p class="t-text">The packaging boxes for my perfume brand turned out absolutely stunning! Their design team understood exactly what I wanted. Will definitely order again!</p>
                    <div class="t-badge">✨ Packaging Design · Verified</div>
                </div>

                <div class="t-card reveal reveal-delay-4">
                    <div class="t-card-head">
                        <img class="t-avatar" src="https://randomuser.me/api/portraits/men/75.jpg" alt="Usman Chaudhry" loading="lazy" />
                        <div>
                            <div class="t-name">Usman Chaudhry</div>
                            <div class="t-role">Restaurant Owner</div>
                        </div>
                    </div>
                    <div class="t-stars" aria-label="5 stars">
                        <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i>
                        <i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i>
                        <i class="fas fa-star" aria-hidden="true"></i>
                    </div>
                    <p class="t-text">Got our restaurant menus and flyers printed here. The paper quality is excellent and the colours are vibrant. They delivered 2 days early!</p>
                    <div class="t-badge" style="color:var(--clr-green)">✔ Delivered in 3 days</div>
                </div>
            </div>

            <div class="t-scroll-section reveal">
                <h3>More <span style="color:var(--clr-primary)">Success Stories</span></h3>
                <div class="t-scroll-wrap">
                    <div class="t-scroll-track" id="t-scroll-track">
                        <div class="t-mini-card">
                            <div class="t-mini-head">
                                <div class="t-mini-avatar">SA</div>
                                <div>
                                    <div class="t-mini-name">Sana Arif</div>
                                    <div class="t-mini-stars"><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i></div>
                                </div>
                            </div>
                            <p class="t-mini-text">Excellent work on our corporate branding package. Very professional team!</p>
                            <div class="t-mini-tag">Branding Design</div>
                        </div>
                        <div class="t-mini-card">
                            <div class="t-mini-head">
                                <div class="t-mini-avatar" style="background:linear-gradient(135deg,#3b82f6,#06b6d4)">HR</div>
                                <div>
                                    <div class="t-mini-name">Hamza Riaz</div>
                                    <div class="t-mini-stars"><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i></div>
                                </div>
                            </div>
                            <p class="t-mini-text">Flex banners for our event were top quality. Will definitely work again!</p>
                            <div class="t-mini-tag">Flex Printing</div>
                        </div>
                        <div class="t-mini-card">
                            <div class="t-mini-head">
                                <div class="t-mini-avatar" style="background:linear-gradient(135deg,#22c55e,#14b8a6)">NM</div>
                                <div>
                                    <div class="t-mini-name">Nida Mirza</div>
                                    <div class="t-mini-stars"><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i></div>
                                </div>
                            </div>
                            <p class="t-mini-text">Amazing quality for our product labels. The colours are so vibrant!</p>
                            <div class="t-mini-tag">Labels &amp; Stickers</div>
                        </div>
                        <div class="t-mini-card">
                            <div class="t-mini-head">
                                <div class="t-mini-avatar" style="background:linear-gradient(135deg,#f59e0b,#ef4444)">KM</div>
                                <div>
                                    <div class="t-mini-name">Kamran Malik</div>
                                    <div class="t-mini-stars"><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i></div>
                                </div>
                            </div>
                            <p class="t-mini-text">Best printing experience in Gawalmandi. Highly professional and affordable!</p>
                            <div class="t-mini-tag">Business Cards</div>
                        </div>
                        <div class="t-mini-card">
                            <div class="t-mini-head">
                                <div class="t-mini-avatar" style="background:linear-gradient(135deg,#6366f1,#8b5cf6)">ZH</div>
                                <div>
                                    <div class="t-mini-name">Zainab Hassan</div>
                                    <div class="t-mini-stars"><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i><i class="fas fa-star" aria-hidden="true"></i></div>
                                </div>
                            </div>
                            <p class="t-mini-text">Thank you cards for my boutique turned out gorgeous! Amazing service!</p>
                            <div class="t-mini-tag">Thank You Cards</div>
                        </div>
                    </div>
                    <button class="t-scroll-btn t-scroll-btn-left" id="t-scroll-left" aria-label="Scroll testimonials left">
                        <i class="fas fa-chevron-left" aria-hidden="true"></i>
                    </button>
                    <button class="t-scroll-btn t-scroll-btn-right" id="t-scroll-right" aria-label="Scroll testimonials right">
                        <i class="fas fa-chevron-right" aria-hidden="true"></i>
                    </button>
                </div>
            </div>

            <div class="t-trust">
                <div class="t-trust-item"><i class="fas fa-shield-alt" aria-hidden="true"></i> 100% Verified Reviews</div>
                <div class="t-trust-item"><i class="fas fa-users" aria-hidden="true"></i> 500+ Happy Clients</div>
                <div class="t-trust-item"><i class="fas fa-trophy" aria-hidden="true"></i> 4.9/5 Average Rating</div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         CONTACT SECTION
         ============================================================ -->
    <section id="contact" class="section" aria-label="Contact Us">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Get In Touch</div>
                <h2 class="section-title">Ready to Print Your <span class="highlight">Next Project?</span></h2>
                <p class="section-desc" style="margin:0 auto">
                    Let's make your ideas shine. Contact us today for a free quote and bring your vision to life!
                </p>
            </div>

            <div class="contact-methods">
                <a href="https://wa.me/92923004197033" target="_blank" rel="noopener" class="contact-card reveal" aria-label="Chat on WhatsApp">
                    <div class="contact-card-icon cc-green" aria-hidden="true"><i class="fab fa-whatsapp"></i></div>
                    <h4>WhatsApp</h4>
                    <p>+923004197033</p>
                </a>
                <a href="tel:+923001234567" class="contact-card reveal reveal-delay-2" aria-label="Call us">
                    <div class="contact-card-icon cc-blue" aria-hidden="true"><i class="fas fa-phone"></i></div>
                    <h4>Phone</h4>
                    <p>+923004197033</p>
                </a>
                <a href="subhanprinters2025@gmail.com" class="contact-card reveal reveal-delay-4" aria-label="Email us">
                    <div class="contact-card-icon cc-purple" aria-hidden="true"><i class="fas fa-envelope"></i></div>
                    <h4>Email</h4>
                    <p>subhanprinters2025@gmail.com</p>
                </a>
            </div>

            <div style="text-align:center;margin-bottom:40px" class="reveal">
                <div style="display:flex;gap:16px;justify-content:center;flex-wrap:wrap">
                    <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="btn btn-green btn-lg">
                        <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
                        <i class="fas fa-arrow-right" aria-hidden="true"></i>
                    </a>
                    <a href="tel:+923004197033" class="btn btn-outline btn-lg">
                        <i class="fas fa-phone" aria-hidden="true"></i> Call Now
                    </a>
                </div>
            </div>

            <div class="contact-info reveal">
                <div class="contact-info-row">
                    <i class="fas fa-map-marker-alt" aria-hidden="true"></i>
                    <p><strong>Visit Us:</strong> Abaseen Center, Gawalmandi, Lahore, Pakistan</p>
                </div>
                <div class="contact-info-row">
                    <i class="fas fa-clock" aria-hidden="true"></i>
                    <p><strong>Hours:</strong> Mon–Fri: 11:00 AM – 8:00 PM &nbsp;|&nbsp; Sat: 10:00 AM – 9:00 PM</p>
                </div>
            </div>
        </div>
    </section>

</main>

<!-- ============================================================
     MAP
     ============================================================ -->
<div id="map" aria-label="Google Maps location of Subhan Printers">
    <iframe
        src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3402.2047896573316!2d74.31491!3d31.5656!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzHCsDMzJzU2LjIiTiA3NMKwMTgnNTMuNyJF!5e0!3m2!1sen!2s!4v1234567890"
        allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
        title="Subhan Printers location on Google Maps">
    </iframe>
</div>

<?php
// ============================================================
// FOOTER - Include footer
// ============================================================
require_once __DIR__ . '/../templates/footer.php';
?>
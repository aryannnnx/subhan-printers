<?php
// ============================================
// PAGES: Services Page - Subhan Printers
// ============================================

// Set page variables
$pageTitle = 'Services | Subhan Printers – Lahore';
$currentPage = 'services';
$pageStyles = 'services.css';
$pageScripts = 'services.js';

// Include header
require_once __DIR__ . '/../templates/header.php';

// Load models
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../models/Portfolio.php';

// Fetch data from database
$productModel = new Product();
$categoryModel = new Category();

// Get all products/services
$allServices = $productModel->getAll(['limit' => 50]);

// Get categories for display
$categories = $categoryModel->getAll(true);
$categoryMap = [];
foreach ($categories as $cat) {
    $categoryMap[$cat['slug']] = $cat['name'];
}

// Group services by category
$servicesByCategory = [];
$categoryCounts = [];

foreach ($allServices as $service) {
    $cat = $service['category'] ?? 'general';
    if (!isset($servicesByCategory[$cat])) {
        $servicesByCategory[$cat] = [];
        $categoryCounts[$cat] = 0;
    }
    $servicesByCategory[$cat][] = $service;
    $categoryCounts[$cat]++;
}

// Service categories with counts and icons
$serviceCategories = [
    'print' => ['label' => 'Printing Services', 'sub' => 'Offset · Digital · Large Format', 'icon' => 'fa-print', 'color' => '#8b5cf6', 'count' => $categoryCounts['print'] ?? 0],
    'packaging' => ['label' => 'Packaging Services', 'sub' => 'Custom Boxes · Labels · Bags', 'icon' => 'fa-box', 'color' => '#f59e0b', 'count' => $categoryCounts['packaging'] ?? 0],
    'design' => ['label' => 'Graphic Design', 'sub' => 'Logo · Branding · Social Media', 'icon' => 'fa-palette', 'color' => '#ec4899', 'count' => $categoryCounts['design'] ?? 0],
    'wedding' => ['label' => 'Wedding Services', 'sub' => 'Cards · Envelopes · Thank You', 'icon' => 'fa-heart', 'color' => '#ec4899', 'count' => $categoryCounts['wedding'] ?? 0],
    'large-format' => ['label' => 'Large Format Printing', 'sub' => 'Flex · Banners · Standees', 'icon' => 'fa-expand', 'color' => '#0ea5e9', 'count' => $categoryCounts['large-format'] ?? 0],
    'stationery' => ['label' => 'Stationery', 'sub' => 'Business Cards · Envelopes', 'icon' => 'fa-sticky-note', 'color' => '#6366f1', 'count' => $categoryCounts['stationery'] ?? 0],
    'stickers' => ['label' => 'Stickers & Labels', 'sub' => 'Custom Stickers · Labels · Tags', 'icon' => 'fa-tags', 'color' => '#6366f1', 'count' => $categoryCounts['stickers'] ?? 0],
    '' => ['label' => 'Stickers & Labels', 'sub' => 'Custom Stickers · Labels · Tags', 'icon' => 'fa-tags', 'color' => '#6366f1', 'count' => $categoryCounts['stationery'] ?? 0],


];

// If no services in database, use static data
if (empty($allServices)) {
    $servicesByCategory = [
        'print' => [
            ['id' => 1, 'name' => 'Offset Printing', 'description' => 'High-quality offset printing for bulk orders. Perfect for large volumes with consistent colour accuracy.', 'price' => '₨ 2,500', 'price_text' => '/ 500 pcs', 'badge' => 'Most Popular', 'image' => 'images/services/offset.jpg', 'features' => ['High Quality', 'Bulk Pricing', 'Colour Accurate']],
            ['id' => 2, 'name' => 'Digital Printing', 'description' => 'Fast turnaround digital printing for small runs. Ideal for prototypes, short runs, and urgent jobs.', 'price' => '₨ 150', 'price_text' => '/ piece', 'badge' => 'Low MOQ', 'image' => 'images/services/digital.jpg', 'features' => ['Low Minimum', 'Fast Turnaround', 'High Detail']],
        ],
        'packaging' => [
            ['id' => 3, 'name' => 'Custom Box Packaging', 'description' => 'Complete custom box manufacturing from design to die-cutting. Premium materials and finishes.', 'price' => '₨ 25', 'price_text' => '/ piece (bulk)', 'badge' => '🔥 Best Seller', 'image' => 'images/services/custom-boxes.jpg', 'features' => ['Custom Design', 'Premium Materials', 'Bulk Orders']],
        ],
        'stickers' => [
            ['id' => 4, 'name' => 'Labels and Tags', 'description' => 'Premium custom tags for clothing, gifts, retail products, and branding purposes.', 'price' => '₨ 0', 'price_text' => '2000 Tags', 'badge' => 'Popular', 'image' => 'images/services/labels.jpg', 'features' => ['Premium Quality', 'Customizable', 'Fast Delivery']],
            ['id' => 5, 'name' => 'Garments Boxes', 'description' => 'Premium garment boxes for clothing brands. Custom sizes with logo printing.', 'price' => '₨ 150', 'price_text' => '/ piece', 'badge' => 'Popular', 'image' => 'images/services/garments.jpg', 'features' => ['Premium Quality', 'Custom Sizes', 'Brand Printing']],
        ],
    ];

    // Update counts
    foreach ($serviceCategories as $key => &$cat) {
        $cat['count'] = count($servicesByCategory[$key] ?? []);
    }
}

// Helper function for slug
if (!function_exists('slugify')) {
    function slugify($text)
    {
        $text = preg_replace('/[^a-zA-Z0-9-]/', '-', strtolower($text));
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }
}
?>

<main>

    <!-- ============================================================
         PAGE HERO
         ============================================================ -->
    <section id="svc-hero" aria-label="Services hero">
        <div class="svc-hero-bg" aria-hidden="true"></div>
        <div class="svc-hero-grid" aria-hidden="true"></div>
        <div class="container svc-hero-inner">

            <div class="reveal">
                <div class="svc-hero-eyebrow">Everything We Offer</div>
                <h1 class="svc-hero-title">
                    All Our <span>Printing</span><br>Services
                </h1>
                <p class="svc-hero-desc">
                    From a single wedding card to bulk corrugated boxes — we handle every printing
                    and design need with speed, quality, and care. Based in Gawalmandi, Lahore.
                </p>
                <div class="svc-hero-actions">
                    <a href="<?php echo base_url('contact'); ?>" class="btn btn-primary btn-lg">
                        <i class="fas fa-shopping-cart" aria-hidden="true"></i> Get Free Quote
                    </a>
                    <a href="#svc-main" class="btn btn-outline btn-lg">
                        Explore Services <i class="fas fa-arrow-down" aria-hidden="true"></i>
                    </a>
                </div>
            </div>

            <div class="svc-hero-stats reveal reveal-delay-2">
                <div class="svc-stat-card">
                    <div class="svc-stat-num" style="color:var(--clr-primary)">5000+</div>
                    <div class="svc-stat-label">Happy Clients</div>
                </div>
                <div class="svc-stat-card">
                    <div class="svc-stat-num" style="color:var(--clr-accent)">10K+</div>
                    <div class="svc-stat-label">Projects Done</div>
                </div>
                <div class="svc-stat-card">
                    <div class="svc-stat-num" style="color:var(--clr-green)">25+</div>
                    <div class="svc-stat-label">Years Experience</div>
                </div>
                <div class="svc-stat-card">
                    <div class="svc-stat-num" style="color:#3b82f6">24hr</div>
                    <div class="svc-stat-label">Rush Delivery</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         STICKY CATEGORY TABS
         ============================================================ -->
    <div id="svc-tabs" role="tablist" aria-label="Service categories">
        <div class="container">
            <div class="svc-tabs-inner">
                <button class="svc-tab active" data-tab="all" role="tab" aria-selected="true">
                    <i class="fas fa-th-large" aria-hidden="true"></i> All Services
                </button>
                <?php foreach ($serviceCategories as $key => $cat): ?>
                    <?php if ($cat['count'] > 0): ?>
                        <button class="svc-tab" data-tab="<?php echo $key; ?>" role="tab" aria-selected="false">
                            <i class="fas <?php echo $cat['icon']; ?>" aria-hidden="true"></i>
                            <?php echo $cat['label']; ?>
                            <span class="svc-tab-count"><?php echo $cat['count']; ?></span>
                        </button>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ============================================================
         ALL SERVICES — SPLIT LAYOUT (IMAGE LEFT, TEXT RIGHT)
         ============================================================ -->
    <section id="svc-main" class="section" aria-label="All services">
        <div class="container">

            <?php foreach ($serviceCategories as $catKey => $catData): ?>
                <?php if (empty($servicesByCategory[$catKey]))
                    continue; ?>

                <div class="cat-section" data-cat="<?php echo $catKey; ?>">
                    <div class="cat-section-head reveal">
                        <div class="cat-section-icon" style="background:<?php echo $catData['color']; ?>">
                            <i class="fas <?php echo $catData['icon']; ?>" aria-hidden="true"></i>
                        </div>
                        <div>
                            <div class="cat-section-title"><?php echo $catData['label']; ?></div>
                            <div class="cat-section-sub"><?php echo $catData['sub']; ?></div>
                        </div>
                        <span class="cat-section-count"><?php echo count($servicesByCategory[$catKey] ?? []); ?>
                            Services</span>
                    </div>

                    <div class="svc-grid">
                        <?php
                        $delayClasses = ['', 'reveal-delay-1', 'reveal-delay-2', 'reveal-delay-3'];
                        $idx = 0;
                        foreach (($servicesByCategory[$catKey] ?? []) as $service):
                            $delay = $delayClasses[$idx % 4];
                            $isWide = $idx === 0;
                            $price = !empty($service['starting_price']) ? '₨ ' . number_format($service['starting_price'], 0) : '₨ 0';
                            $priceText = $service['price_text'] ?? '';
                            $badge = $service['badge'] ?? '';

                            // Image handling with fallback
                            $image = !empty($service['image_url'])
                                ? $service['image_url']
                                : (!empty($service['image']) ? $service['image'] : 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($service['name'] ?? 'Service'));

                            if (strpos($image, '/SP/') !== 0 && strpos($image, 'http') !== 0) {
                                $image = '/SP/' . ltrim($image, '/');
                            }

                            $features = $service['features'] ?? [];
                            $serviceId = $service['id'] ?? $idx + 1;
                            $catColor = $catData['color'] ?? '#8b5cf6';
                            ?>
                            <!-- SPLIT LAYOUT CARD -->
                            <div class="svc-card <?php echo $isWide ? 'svc-card-wide' : ''; ?> reveal <?php echo $delay; ?>"
                                data-service="<?php echo slugify($service['name'] ?? ''); ?>" data-cat="<?php echo $catKey; ?>"
                                data-id="<?php echo $serviceId; ?>" onclick="openProductModal(<?php echo $serviceId; ?>)"
                                style="--card-color: <?php echo $catColor; ?>">

                                <!-- LEFT: Image (50%) -->
                                <div class="svc-card-img-wrap">
                                    <img class="svc-card-img" src="<?php echo htmlspecialchars($image); ?>"
                                        alt="<?php echo htmlspecialchars($service['name'] ?? 'Service'); ?>" loading="lazy"
                                        onerror="this.src='https://placehold.co/600x400/1a1a2e/<?php echo str_replace('#', '', $catColor); ?>?text=<?php echo urlencode($service['name'] ?? 'Service'); ?>'" />

                                    <!-- Gradient Overlay -->
                                    <div class="svc-img-overlay"></div>

                                    <!-- Badge -->
                                    <?php if ($badge): ?>
                                        <span class="svc-card-badge" style="background:<?php echo $catColor; ?>;">
                                            <?php echo htmlspecialchars($badge); ?>
                                        </span>
                                    <?php endif; ?>

                                    <!-- Hover Overlay -->
                                    <div class="svc-card-overlay">
                                        <div class="svc-overlay-icon">
                                            <i class="fas fa-search-plus"></i>
                                        </div>
                                        <span class="svc-overlay-text">View Details</span>
                                    </div>
                                </div>

                                <!-- RIGHT: Content (50%) -->
                                <div class="svc-card-body">
                                    <div class="svc-card-header">
                                        <div class="svc-card-icon" style="background:<?php echo $catColor; ?>;">
                                            <i class="fas <?php echo $catData['icon']; ?>" aria-hidden="true"></i>
                                        </div>
                                        <div class="svc-card-title"><?php echo htmlspecialchars($service['name'] ?? ''); ?>
                                        </div>
                                    </div>

                                    <p class="svc-card-desc"><?php echo htmlspecialchars($service['description'] ?? ''); ?></p>

                                    <?php if (!empty($features)): ?>
                                        <div class="svc-card-features">
                                            <?php foreach ($features as $feature): ?>
                                                <div class="svc-card-feature">
                                                    <i class="fas fa-check-circle" aria-hidden="true"></i>
                                                    <span><?php echo htmlspecialchars($feature); ?></span>
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <div class="svc-card-footer">
                                        <div class="svc-card-price">
                                            <span class="price-amount"><?php echo htmlspecialchars($price); ?></span>
                                            <span class="price-text"><?php echo htmlspecialchars($priceText); ?></span>
                                        </div>
                                        <button class="svc-card-cta"
                                            onclick="event.stopPropagation(); openProductModal(<?php echo $serviceId; ?>)">
                                            View Details <i class="fas fa-arrow-right" aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php
                            $idx++;
                        endforeach;
                        ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </section>

    <!-- ============================================================
         PRODUCT PREVIEW MODAL
         ============================================================ -->
    <div id="productModal" class="product-modal" onclick="if(event.target === this) closeProductModal()">
        <div class="product-modal-content">
            <button class="product-modal-close" onclick="closeProductModal()">
                <i class="fas fa-times"></i>
            </button>
            <div class="product-modal-body" id="productModalBody">
                <div class="product-modal-loading">
                    <i class="fas fa-spinner fa-spin"></i> Loading...
                </div>
            </div>
        </div>
    </div>

    <!-- ============================================================
         HOW IT WORKS
         ============================================================ -->
    <section id="svc-process" class="section" aria-label="How it works">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Simple Process</div>
                <h2 class="section-title">How to <span class="highlight">Order</span></h2>
                <p class="section-desc">From idea to delivery in 5 easy steps</p>
            </div>
            <div class="process-strip" style="margin-top:56px">
                <div class="ps-step reveal">
                    <div class="ps-num" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">1</div>
                    <div class="ps-icon"><i class="fas fa-comments" style="color:var(--clr-primary)"
                            aria-hidden="true"></i></div>
                    <div class="ps-title">Share Your Idea</div>
                    <div class="ps-desc">Tell us what you need via WhatsApp, call, or visit our shop in Gawalmandi.
                    </div>
                </div>
                <div class="ps-step reveal reveal-delay-1">
                    <div class="ps-num" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">2</div>
                    <div class="ps-icon"><i class="fas fa-file-invoice-dollar" style="color:#3b82f6"
                            aria-hidden="true"></i></div>
                    <div class="ps-title">Get Your Quote</div>
                    <div class="ps-desc">We give you an instant price. Negotiate, finalise, and pay to confirm order.
                    </div>
                </div>
                <div class="ps-step reveal reveal-delay-2">
                    <div class="ps-num" style="background:linear-gradient(135deg,#f59e0b,#b45309)">3</div>
                    <div class="ps-icon"><i class="fas fa-magic" style="color:var(--clr-accent)" aria-hidden="true"></i>
                    </div>
                    <div class="ps-title">We Design &amp; Print</div>
                    <div class="ps-desc">Our designers create your artwork and our machines produce the final prints.
                    </div>
                </div>
                <div class="ps-step reveal reveal-delay-3">
                    <div class="ps-num" style="background:linear-gradient(135deg,#22c55e,#15803d)">4</div>
                    <div class="ps-icon"><i class="fas fa-truck" style="color:var(--clr-green)" aria-hidden="true"></i>
                    </div>
                    <div class="ps-title">Pickup or Deliver</div>
                    <div class="ps-desc">Collect from our shop or get your order delivered to your door across Pakistan.
                    </div>
                </div>
                <div class="ps-step reveal reveal-delay-4">
                    <div class="ps-num" style="background:linear-gradient(135deg,#ec4899,#be185d)">5</div>
                    <div class="ps-icon"><i class="fas fa-star" style="color:#ec4899" aria-hidden="true"></i></div>
                    <div class="ps-title">Leave a Review</div>
                    <div class="ps-desc">Share your experience and get 10% off your next order. We love happy clients!
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         MATERIALS & PAPER TYPES
         ============================================================ -->
    <section id="svc-materials" class="section" aria-label="Materials">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">What We Use</div>
                <h2 class="section-title">Premium <span class="highlight">Materials</span></h2>
                <p class="section-desc">We use only high-grade substrates and inks for every job</p>
            </div>
            <div class="materials-grid">
                <div class="mat-card reveal">
                    <div class="mat-icon"><i class="fas fa-layer-group" aria-hidden="true"></i></div>
                    <div class="mat-title">Art Paper &amp; Board</div>
                    <div class="mat-desc">Coated for sharp images and vivid colour reproduction. Used for brochures,
                        leaflets, and packaging.</div>
                    <div class="mat-tags">
                        <span class="mat-tag">130gsm</span>
                        <span class="mat-tag">170gsm</span>
                        <span class="mat-tag">300gsm</span>
                        <span class="mat-tag">350gsm</span>
                    </div>
                </div>
                <div class="mat-card reveal reveal-delay-1">
                    <div class="mat-icon" style="background:linear-gradient(135deg,#f59e0b,#b45309)"><i
                            class="fas fa-scroll" aria-hidden="true"></i></div>
                    <div class="mat-title">Kraft &amp; Brown Board</div>
                    <div class="mat-desc">Eco-friendly natural paper for bags, tags, and sustainable packaging. Rustic
                        and premium feel.</div>
                    <div class="mat-tags">
                        <span class="mat-tag">Natural</span>
                        <span class="mat-tag">White Kraft</span>
                        <span class="mat-tag">Recycled</span>
                    </div>
                </div>
                <div class="mat-card reveal reveal-delay-2">
                    <div class="mat-icon" style="background:linear-gradient(135deg,#22c55e,#15803d)"><i
                            class="fas fa-box" aria-hidden="true"></i></div>
                    <div class="mat-title">Corrugated Flute</div>
                    <div class="mat-desc">Strong and lightweight for shipping and storage boxes. Available in E, B, and
                        C flute configurations.</div>
                    <div class="mat-tags">
                        <span class="mat-tag">E Flute</span>
                        <span class="mat-tag">B Flute</span>
                        <span class="mat-tag">C Flute</span>
                        <span class="mat-tag">Double Wall</span>
                    </div>
                </div>
                <div class="mat-card reveal reveal-delay-3">
                    <div class="mat-icon" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)"><i
                            class="fas fa-gem" aria-hidden="true"></i></div>
                    <div class="mat-title">Finishing Options</div>
                    <div class="mat-desc">Transform ordinary print into premium with our speciality finishing services.
                    </div>
                    <div class="mat-tags">
                        <span class="mat-tag">Gold Foil</span>
                        <span class="mat-tag">Silver Foil</span>
                        <span class="mat-tag">Spot UV</span>
                        <span class="mat-tag">Emboss</span>
                        <span class="mat-tag">Soft Touch</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PRICING TABLE
         ============================================================ -->
    <section id="svc-pricing" class="section" aria-label="Pricing">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Transparent Pricing</div>
                <h2 class="section-title">Design <span class="highlight">Packages</span></h2>
                <p class="section-desc">Fixed packages for graphic design. Printing priced separately based on quantity
                    and specs.</p>
            </div>
            <div class="pricing-grid">
                <!-- Basic -->
                <div class="pricing-card reveal">
                    <div class="pricing-head">
                        <div class="pricing-badge basic">Starter</div>
                        <div class="pricing-name">Basic Design</div>
                        <div class="pricing-price"><sup>₨</sup>2,999</div>
                        <div class="pricing-period">one-time project fee</div>
                    </div>
                    <div class="pricing-body">
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>1
                                design concept</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>2
                                revisions</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>PNG
                                &amp; PDF files</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>3–5
                                day delivery</span></div>
                        <div class="pricing-feature excluded"><i class="fas fa-times"
                                aria-hidden="true"></i><span>Source files (AI/PSD)</span></div>
                        <div class="pricing-feature excluded"><i class="fas fa-times" aria-hidden="true"></i><span>Brand
                                style guide</span></div>
                        <div class="pricing-feature excluded"><i class="fas fa-times"
                                aria-hidden="true"></i><span>Social media kit</span></div>
                    </div>
                    <div class="pricing-foot">
                        <a href="https://wa.me/923004197033?text=I want the Basic Design package" target="_blank"
                            rel="noopener" class="btn btn-outline" style="width:100%;justify-content:center">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Popular -->
                <div class="pricing-card featured reveal reveal-delay-2">
                    <div class="pricing-head">
                        <div class="pricing-badge popular">⭐ Most Popular</div>
                        <div class="pricing-name">Professional</div>
                        <div class="pricing-price"><sup>₨</sup>6,999</div>
                        <div class="pricing-period">one-time project fee</div>
                    </div>
                    <div class="pricing-body">
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>3
                                design concepts</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check"
                                aria-hidden="true"></i><span>Unlimited revisions</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>All
                                file formats</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>48hr
                                delivery</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check"
                                aria-hidden="true"></i><span>Source files (AI/PSD)</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>Brand
                                style guide</span></div>
                        <div class="pricing-feature excluded"><i class="fas fa-times"
                                aria-hidden="true"></i><span>Social media kit</span></div>
                    </div>
                    <div class="pricing-foot">
                        <a href="https://wa.me/923004197033?text=I want the Professional Design package" target="_blank"
                            rel="noopener" class="btn btn-primary" style="width:100%;justify-content:center">
                            Get Started
                        </a>
                    </div>
                </div>

                <!-- Pro -->
                <div class="pricing-card reveal reveal-delay-4">
                    <div class="pricing-head">
                        <div class="pricing-badge pro">Premium</div>
                        <div class="pricing-name">Brand Package</div>
                        <div class="pricing-price"><sup>₨</sup>14,999</div>
                        <div class="pricing-period">complete brand kit</div>
                    </div>
                    <div class="pricing-body">
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>5
                                design concepts</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check"
                                aria-hidden="true"></i><span>Unlimited revisions</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>All
                                file formats</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>24hr
                                rush delivery</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check"
                                aria-hidden="true"></i><span>Source files (AI/PSD)</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check"
                                aria-hidden="true"></i><span>Complete brand guide</span></div>
                        <div class="pricing-feature included"><i class="fas fa-check"
                                aria-hidden="true"></i><span>Social media kit (10 posts)</span></div>
                    </div>
                    <div class="pricing-foot">
                        <a href="https://wa.me/923004197033?text=I want the Brand Package" target="_blank"
                            rel="noopener" class="btn btn-accent" style="width:100%;justify-content:center">
                            Get Started
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FAQ
         ============================================================ -->
    <section id="svc-faq" class="section" aria-label="Frequently Asked Questions">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">FAQ</div>
                <h2 class="section-title">Frequently Asked <span class="highlight">Questions</span></h2>
                <p class="section-desc">Everything you need to know before placing your order</p>
            </div>
            <div class="faq-grid">
                <?php
                $faqs = [
                    ['question' => 'What is the minimum order quantity?', 'answer' => 'For offset printing the minimum is usually 500 pieces for cost-effective pricing. For digital printing, we can print even 1 piece with no minimum. Packaging boxes start from 100 pieces. Contact us for custom quotes on any quantity.'],
                    ['question' => 'How long does printing take?', 'answer' => 'Standard turnaround is 3–5 business days for most jobs. Rush orders (24–48 hours) are available at an additional cost. Large packaging orders may take 7–14 days. We will give you an exact timeline when you confirm your order.'],
                    ['question' => 'Do you provide delivery across Pakistan?', 'answer' => 'Yes! We deliver to all major cities across Pakistan via TCS, Leopards, and other courier services. Delivery charges depend on weight and destination. Lahore customers can also pick up from our shop in Gawalmandi free of charge.'],
                    ['question' => 'What file formats do you accept?', 'answer' => 'We accept AI, PDF, PSD, CDR, EPS, PNG (300 DPI minimum), and TIFF. For best results, provide files in CMYK colour mode at 300 DPI with 3mm bleed. If you don\'t have a design, our team can create one for you.'],
                    ['question' => 'Can I get a sample before bulk printing?', 'answer' => 'Yes, we can print a sample proof for you before proceeding with the bulk order. Sample printing charges apply and will be deducted from your final order. This is highly recommended for packaging and wedding cards.'],
                    ['question' => 'What payment methods do you accept?', 'answer' => 'We accept cash, bank transfer, JazzCash, EasyPaisa, and card payments at our shop. For online orders, 50% advance is required before production starts and the remaining 50% upon delivery or pickup.'],
                ];
                $delay = 0;
                foreach ($faqs as $faq):
                    $delayClass = $delay === 0 ? '' : ($delay === 1 ? ' reveal-delay-1' : ($delay === 2 ? ' reveal-delay-2' : ' reveal-delay-3'));
                    ?>
                    <div class="faq-item reveal<?php echo $delayClass; ?>" data-faq>
                        <button class="faq-question" aria-expanded="false">
                            <?php echo $faq['question']; ?>
                            <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
                        </button>
                        <div class="faq-answer">
                            <p><?php echo $faq['answer']; ?></p>
                        </div>
                    </div>
                    <?php
                    $delay++;
                endforeach;
                ?>
            </div>
        </div>
    </section>

    <!-- ============================================================
         CTA BANNER
         ============================================================ -->
    <section id="svc-cta" aria-label="Call to action">
        <div class="svc-cta-blob svc-cta-blob-1" aria-hidden="true"></div>
        <div class="svc-cta-blob svc-cta-blob-2" aria-hidden="true"></div>
        <div class="container svc-cta-inner reveal">
            <div class="svc-cta-title">
                Ready to Start Your<br>Printing Project?
            </div>
            <p class="svc-cta-desc">
                Get a free quote in minutes. WhatsApp us your requirements and we'll
                get back to you within 1 hour during business hours.
            </p>
            <div class="svc-cta-btns">
                <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="btn btn-green btn-lg">
                    <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
                </a>
                <a href="tel:+923004197033" class="btn btn-lg"
                    style="background:rgba(255,255,255,0.15);color:#fff;border:1.5px solid rgba(255,255,255,0.3)">
                    <i class="fas fa-phone" aria-hidden="true"></i> Call Now
                </a>
                <a href="<?php echo base_url('contact'); ?>" class="btn btn-lg"
                    style="background:rgba(255,255,255,0.1);color:#fff;border:1.5px solid rgba(255,255,255,0.2)">
                    <i class="fas fa-envelope" aria-hidden="true"></i> Send Message
                </a>
            </div>
            <div class="svc-cta-trust">
                <div class="svc-cta-trust-item"><i class="fas fa-shield-alt" aria-hidden="true"></i> 100% Quality
                    Guarantee</div>
                <div class="svc-cta-trust-item"><i class="fas fa-redo" aria-hidden="true"></i> Free Revisions on Design
                </div>
                <div class="svc-cta-trust-item"><i class="fas fa-truck" aria-hidden="true"></i> Fast Nationwide Delivery
                </div>
                <div class="svc-cta-trust-item"><i class="fas fa-clock" aria-hidden="true"></i> 1-Hour Response Time
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

<!-- ============================================================
     PRODUCT MODAL JAVASCRIPT
     ============================================================ -->
<script>
    // ============================================================
    // PRODUCT DATA FROM PHP
    // ============================================================
    const productData = <?php
    $allProducts = [];
    foreach ($servicesByCategory as $cat => $items) {
        foreach ($items as $item) {
            // ============================================
            // FIX: Use 'starting_price' from database
            // ============================================
            $price = !empty($item['starting_price']) ? '₨ ' . number_format($item['starting_price'], 0) : '₨ 0';

            $image = !empty($item['image_url'])
                ? $item['image_url']
                : (!empty($item['image']) ? $item['image'] : 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($item['name'] ?? 'Product'));

            if (strpos($image, '/SP/') !== 0 && strpos($image, 'http') !== 0) {
                $image = '/SP/' . ltrim($image, '/');
            }

            $allProducts[] = [
                'id' => $item['id'] ?? rand(100, 999),
                'name' => $item['name'] ?? 'Product',
                'description' => $item['description'] ?? '',
                'price' => $price,  // ✅ FIXED - using 'starting_price'
                'price_text' => $item['price_text'] ?? '',
                'badge' => $item['badge'] ?? '',
                'image' => $image,
                'category' => $cat,
                'features' => $item['features'] ?? []
            ];
        }
    }
    echo json_encode($allProducts);
    ?>;

    // ============================================================
    // OPEN PRODUCT MODAL
    // ============================================================
    function openProductModal(productId) {
        const modal = document.getElementById('productModal');
        const body = document.getElementById('productModalBody');

        // Find product by ID
        const product = productData.find(p => p.id === productId);

        if (!product) {
            body.innerHTML = '<div class="product-modal-error"><i class="fas fa-exclamation-circle"></i> Product not found</div>';
            modal.classList.add('active');
            return;
        }

        // Build features HTML
        const featuresHtml = product.features && product.features.length > 0
            ? product.features.map(f => `<div class="modal-feature"><i class="fas fa-check-circle"></i> ${f}</div>`).join('')
            : '';

        // Build modal content
        body.innerHTML = `
        <div class="modal-product-layout">
            <div class="modal-product-image">
                <img src="${product.image}" alt="${product.name}" onerror="this.src='https://placehold.co/600x400/1a1a2e/8b5cf6?text=${encodeURIComponent(product.name)}'">
                ${product.badge ? `<span class="modal-product-badge">${product.badge}</span>` : ''}
            </div>
            <div class="modal-product-info">
                <div class="modal-product-category">${product.category.charAt(0).toUpperCase() + product.category.slice(1)}</div>
                <h2 class="modal-product-title">${product.name}</h2>
                <p class="modal-product-desc">${product.description}</p>
                ${featuresHtml ? `<div class="modal-features-grid">${featuresHtml}</div>` : ''}
                <div class="modal-product-price">
                    <span class="modal-price-amount">${product.price}</span>
                    <span class="modal-price-text">${product.price_text}</span>
                </div>
                <div class="modal-product-actions">
                    <a href="https://wa.me/923001234567?text=I'm%20interested%20in%20${encodeURIComponent(product.name)}" target="_blank" rel="noopener" class="btn btn-green">
                        <i class="fab fa-whatsapp"></i> Order Now
                    </a>
                    <a href="tel:+923001234567" class="btn btn-outline">
                        <i class="fas fa-phone"></i> Call Now
                    </a>
                </div>
                <div class="modal-product-trust">
                    <span><i class="fas fa-shield-alt"></i> Quality Guaranteed</span>
                    <span><i class="fas fa-truck"></i> Fast Delivery</span>
                    <span><i class="fas fa-star"></i> 4.9★ Rating</span>
                </div>
            </div>
        </div>
    `;

        // Show modal
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    // ============================================================
    // CLOSE PRODUCT MODAL
    // ============================================================
    function closeProductModal() {
        const modal = document.getElementById('productModal');
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }

    // ============================================================
    // CLOSE MODAL ON ESCAPE KEY
    // ============================================================
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') {
            closeProductModal();
        }
    });
</script>

<!-- ============================================================
     COMPLETE CSS STYLES - SPLIT LAYOUT
     ============================================================ -->
<style>
    /* ============================================================
   PRODUCT MODAL STYLES
   ============================================================ */
    .product-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.85);
        backdrop-filter: blur(12px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }

    .product-modal.active {
        display: flex;
        animation: modalFadeIn 0.3s ease;
    }

    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .product-modal-content {
        background: #0d0d14;
        border: 1px solid rgba(255, 255, 255, 0.08);
        border-radius: 24px;
        max-width: 900px;
        width: 100%;
        max-height: 90vh;
        overflow-y: auto;
        position: relative;
        box-shadow: 0 40px 80px rgba(0, 0, 0, 0.6);
    }

    .product-modal-content::-webkit-scrollbar {
        width: 4px;
    }

    .product-modal-content::-webkit-scrollbar-thumb {
        background: #8b5cf6;
        border-radius: 4px;
    }

    .product-modal-close {
        position: sticky;
        top: 12px;
        right: 12px;
        float: right;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: #fff;
        font-size: 1.2rem;
        cursor: pointer;
        transition: all 0.3s ease;
        z-index: 10;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 12px 12px 0 0;
    }

    .product-modal-close:hover {
        background: rgba(239, 68, 68, 0.2);
        border-color: #ef4444;
        transform: rotate(90deg);
    }

    .product-modal-body {
        padding: 20px 32px 32px;
    }

    .modal-product-layout {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 32px;
        align-items: start;
    }

    .modal-product-image {
        position: relative;
        border-radius: 16px;
        overflow: hidden;
        background: #1a1a2e;
    }

    .modal-product-image img {
        width: 100%;
        height: 320px;
        object-fit: cover;
        display: block;
    }

    .modal-product-badge {
        position: absolute;
        top: 16px;
        left: 16px;
        background: linear-gradient(135deg, #8b5cf6, #6d28d9);
        color: #fff;
        padding: 4px 14px;
        border-radius: 100px;
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
    }

    .modal-product-category {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.1em;
        color: #8b5cf6;
        margin-bottom: 8px;
    }

    .modal-product-title {
        font-family: 'Playfair Display', serif;
        font-size: 1.8rem;
        font-weight: 800;
        color: #fff;
        margin-bottom: 12px;
    }

    .modal-product-desc {
        font-size: 0.95rem;
        color: #8888aa;
        line-height: 1.7;
        margin-bottom: 20px;
    }

    .modal-features-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-bottom: 20px;
    }

    .modal-feature {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 0.85rem;
        color: #ccc;
        padding: 6px 0;
    }

    .modal-feature i {
        color: #22c55e;
        font-size: 0.75rem;
    }

    .modal-product-price {
        display: flex;
        align-items: baseline;
        gap: 8px;
        padding: 16px 0;
        border-top: 1px solid rgba(255, 255, 255, 0.06);
        border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        margin-bottom: 20px;
    }

    .modal-price-amount {
        font-family: 'Playfair Display', serif;
        font-size: 2rem;
        font-weight: 800;
        color: #f59e0b;
    }

    .modal-price-text {
        font-size: 0.85rem;
        color: #8888aa;
    }

    .modal-product-actions {
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .modal-product-actions .btn {
        flex: 1;
        justify-content: center;
        min-width: 140px;
    }

    .modal-product-trust {
        display: flex;
        gap: 20px;
        margin-top: 16px;
        flex-wrap: wrap;
    }

    .modal-product-trust span {
        font-size: 0.78rem;
        color: #666;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .modal-product-trust span i {
        color: #8b5cf6;
    }

    .modal-product-loading,
    .modal-product-error {
        text-align: center;
        padding: 60px 20px;
        color: #8888aa;
    }

    .modal-product-error {
        color: #ef4444;
    }

    .modal-product-loading i,
    .modal-product-error i {
        font-size: 2rem;
        margin-bottom: 12px;
        display: block;
    }

    /* ============================================================
   SPLIT LAYOUT SERVICE CARD - IMAGE LEFT, TEXT RIGHT
   ============================================================ */

    /* Card Container */
    /* ============================================================
   SPLIT LAYOUT SERVICE CARD - FIXED SPACING
   ============================================================ */

    /* Card Container */
    .svc-card {
        cursor: pointer;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        background: var(--clr-surface);
        border: 1px solid var(--clr-border);
        border-radius: var(--radius-xl);
        overflow: hidden;
        display: flex;
        flex-direction: row;
        height: 100%;
        min-height: 260px;
        max-height: 320px;
    }

    .svc-card:hover {
        transform: translateY(-6px);
        border-color: var(--card-color, rgba(139, 92, 246, 0.4));
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.4);
    }

    /* ============================================================
   IMAGE - LEFT HALF (50%)
   ============================================================ */
    .svc-card-img-wrap {
        position: relative;
        overflow: hidden;
        width: 45%;
        flex-shrink: 0;
        background: var(--clr-surface-2);
    }

    .svc-card-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        display: block;
        transition: transform 0.6s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .svc-card:hover .svc-card-img {
        transform: scale(1.06);
    }

    /* Image Gradient Overlay */
    .svc-img-overlay {
        position: absolute;
        inset: 0;
        background: linear-gradient(to right,
                rgba(0, 0, 0, 0.4) 0%,
                transparent 60%);
        opacity: 0.5;
        transition: opacity 0.5s ease;
        z-index: 1;
    }

    .svc-card:hover .svc-img-overlay {
        opacity: 0.3;
    }

    /* ============================================================
   BADGE - Top Left of Image
   ============================================================ */
    .svc-card-badge {
        position: absolute;
        top: 12px;
        left: 12px;
        color: #fff;
        font-size: 0.6rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.08em;
        padding: 4px 12px;
        border-radius: 100px;
        z-index: 2;
        background: var(--card-color, #8b5cf6);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    /* ============================================================
   HOVER OVERLAY - Center of Image
   ============================================================ */
    .svc-card-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.5);
        backdrop-filter: blur(4px);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        gap: 6px;
        z-index: 3;
    }

    .svc-card:hover .svc-card-overlay {
        opacity: 1;
    }

    .svc-overlay-icon {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.12);
        backdrop-filter: blur(4px);
        border: 2px solid rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        transform: translateY(15px) scale(0.8);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .svc-overlay-icon i {
        font-size: 1.2rem;
        color: #fff;
    }

    .svc-card:hover .svc-overlay-icon {
        transform: translateY(0) scale(1);
    }

    .svc-overlay-text {
        font-size: 0.75rem;
        color: #fff;
        font-weight: 600;
        transform: translateY(15px);
        transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1) 0.1s;
        letter-spacing: 0.05em;
    }

    .svc-card:hover .svc-overlay-text {
        transform: translateY(0);
    }

    /* ============================================================
   TEXT CONTENT - RIGHT HALF (55%)
   ============================================================ */
    .svc-card-body {
        width: 55%;
        padding: 18px 20px 14px;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        background: var(--clr-surface);
        flex: 1;
    }

    /* Header with Icon */
    .svc-card-header {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 4px;
    }

    .svc-card-icon {
        width: 36px;
        height: 36px;
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.9rem;
        color: #fff;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    .svc-card:hover .svc-card-icon {
        transform: scale(1.1) rotate(-5deg);
    }

    /* Title */
    .svc-card-title {
        font-weight: 700;
        color: var(--clr-white);
        font-size: 1rem;
        line-height: 1.2;
        margin: 0;
    }

    /* Description */
    .svc-card-desc {
        font-size: 0.78rem;
        color: var(--clr-muted);
        line-height: 1.5;
        margin: 4px 0 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
        flex: 1;
    }

    /* Features */
    .svc-card-features {
        display: flex;
        flex-direction: column;
        gap: 3px;
        margin-bottom: 8px;
    }

    .svc-card-feature {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 0.7rem;
        color: var(--clr-muted);
        transition: all 0.3s ease;
    }

    .svc-card-feature i {
        color: var(--card-color, #22c55e);
        font-size: 0.6rem;
        flex-shrink: 0;
    }

    .svc-card:hover .svc-card-feature {
        color: var(--clr-text);
    }

    /* ============================================================
   CARD FOOTER - Bottom of Right Side - FIXED
   ============================================================ */
    .svc-card-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 10px;
        border-top: 1px solid var(--clr-border);
        margin-top: auto;
        gap: 10px;
    }

    .svc-card-price {
        display: flex;
        flex-direction: column;
        flex-shrink: 0;
    }

    .price-amount {
        font-family: var(--font-display);
        font-size: 0.95rem;
        font-weight: 700;
        color: var(--card-color, var(--clr-primary));
    }

    .price-text {
        font-size: 0.6rem;
        color: var(--clr-muted);
        font-weight: 400;
    }

    .svc-card-cta {
        background: none;
        border: none;
        color: var(--card-color, #8b5cf6);
        font-weight: 600;
        font-size: 0.75rem;
        cursor: pointer;
        padding: 4px 12px;
        border-radius: 8px;
        transition: all 0.3s ease;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        font-family: inherit;
        position: relative;
        flex-shrink: 0;
        white-space: nowrap;
    }

    .svc-card-cta::after {
        content: '';
        position: absolute;
        bottom: 2px;
        left: 0;
        width: 0;
        height: 2px;
        background: var(--card-color, #8b5cf6);
        transition: width 0.3s ease;
    }

    .svc-card-cta:hover::after {
        width: 100%;
    }

    .svc-card-cta:hover {
        color: #a78bfa;
    }

    .svc-card-cta i {
        transition: transform 0.3s ease;
        font-size: 0.7rem;
    }

    .svc-card-cta:hover i {
        transform: translateX(3px);
    }

    /* ============================================================
   WIDE CARD VARIANT
   ============================================================ */
    .svc-card-wide .svc-card-img-wrap {
        width: 50%;
    }

    .svc-card-wide .svc-card-body {
        width: 50%;
    }

    /* ============================================================
   SERVICE GRID
   ============================================================ */
    .svc-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 24px;
    }

    /* ============================================================
   RESPONSIVE
   ============================================================ */
    @media (max-width: 992px) {
        .svc-card {
            flex-direction: column;
            min-height: auto;
            max-height: none;
        }

        .svc-card-img-wrap {
            width: 100% !important;
            height: 200px;
        }

        .svc-card-body {
            width: 100% !important;
            padding: 16px 18px 14px;
        }

        .svc-card-wide .svc-card-img-wrap {
            height: 220px;
        }

        .svc-img-overlay {
            background: linear-gradient(to top,
                    rgba(0, 0, 0, 0.6) 0%,
                    transparent 60%);
        }

        .svc-grid {
            grid-template-columns: 1fr;
            max-width: 560px;
            margin: 0 auto;
        }
    }

    @media (max-width: 768px) {
        .svc-card-img-wrap {
            height: 170px;
        }

        .svc-card-body {
            padding: 14px 16px 12px;
        }

        .svc-card-title {
            font-size: 0.9rem;
        }

        .svc-card-desc {
            font-size: 0.72rem;
            -webkit-line-clamp: 2;
        }

        .svc-card-features {
            gap: 2px;
        }

        .svc-card-feature {
            font-size: 0.65rem;
        }

        .svc-overlay-icon {
            width: 36px;
            height: 36px;
        }

        .svc-overlay-icon i {
            font-size: 0.9rem;
        }

        .svc-overlay-text {
            font-size: 0.65rem;
        }

        .svc-card-footer {
            flex-wrap: wrap;
            gap: 6px;
            padding-top: 8px;
        }

        .svc-card-cta {
            font-size: 0.7rem;
            padding: 4px 10px;
        }

        .price-amount {
            font-size: 0.85rem;
        }
    }

    @media (max-width: 480px) {
        .svc-card-img-wrap {
            height: 140px;
        }

        .svc-card-badge {
            font-size: 0.5rem;
            padding: 3px 8px;
            top: 8px;
            left: 8px;
        }

        .svc-card-icon {
            width: 28px;
            height: 28px;
            font-size: 0.7rem;
        }

        .svc-card-title {
            font-size: 0.8rem;
        }

        .svc-card-desc {
            font-size: 0.65rem;
            -webkit-line-clamp: 1;
            margin: 2px 0 4px;
        }

        .svc-card-body {
            padding: 10px 12px 10px;
        }

        .svc-card-footer {
            flex-direction: column;
            align-items: stretch;
            gap: 4px;
            padding-top: 6px;
        }

        .svc-card-price {
            flex-direction: row;
            align-items: baseline;
            gap: 6px;
        }

        .price-amount {
            font-size: 0.8rem;
        }

        .price-text {
            font-size: 0.55rem;
        }

        .svc-card-cta {
            justify-content: center;
            font-size: 0.65rem;
            padding: 4px 8px;
        }
    }
</style>
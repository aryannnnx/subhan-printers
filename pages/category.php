<?php
// ============================================
// PAGES: Category Page - Subhan Printers
// ============================================

// Set page variables
$pageTitle = 'Categories | Subhan Printers – Lahore';
$currentPage = 'categories';
$pageStyles = 'categories.css';
$pageScripts = 'categories.js';

// Include header
require_once __DIR__ . '/../templates/header.php';

// Load models
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/Product.php';
require_once __DIR__ . '/../models/Portfolio.php';

// Initialize database connection
$database = new Database();
$db = $database->getConnection();

// Fetch data with database connection
$productModel = new Product($db);
$portfolioModel = new Portfolio($db);

// Get all products to determine categories
$allProducts = $productModel->getAll(['limit' => 100]);

// Build categories from product data
$categoryMap = [];
$categoryCounts = [];

foreach ($allProducts as $product) {
    $cat = $product['category'] ?? 'general';
    if (!isset($categoryMap[$cat])) {
        $categoryMap[$cat] = [
            'slug' => $cat,
            'name' => ucfirst(str_replace('-', ' ', $cat)),
            'count' => 0,
            'products' => []
        ];
    }
    $categoryMap[$cat]['count']++;
    $categoryMap[$cat]['products'][] = $product;
}

// Define category details (icons, colors, descriptions)
$categoryDetails = [
    'print' => [
        'icon' => 'fa-print',
        'color' => '#8b5cf6',
        'description' => 'High-quality printing services for all your needs',
        'image' => 'images/categories/print.jpg'
    ],
    'packaging' => [
        'icon' => 'fa-box',
        'color' => '#f59e0b',
        'description' => 'Custom packaging solutions for your products',
        'image' => 'images/categories/packaging.jpg'
    ],
    'design' => [
        'icon' => 'fa-palette',
        'color' => '#ec4899',
        'description' => 'Professional graphic design services',
        'image' => 'images/categories/design.jpg'
    ],
    'wedding' => [
        'icon' => 'fa-heart',
        'color' => '#ec4899',
        'description' => 'Beautiful wedding cards and stationery',
        'image' => 'images/categories/wedding.jpg'
    ],
    'large-format' => [
        'icon' => 'fa-expand',
        'color' => '#0ea5e9',
        'description' => 'Large format printing for banners and displays',
        'image' => 'images/categories/large-format.jpg'
    ],
    'stationery' => [
        'icon' => 'fa-sticky-note',
        'color' => '#6366f1',
        'description' => 'Premium stationery and business cards',
        'image' => 'images/categories/stationery.jpg'
    ],
    'stickers' => [
        'icon' => 'fa-tags',
        'color' => '#6366f1',
        'description' => 'Custom stickers and labels for branding',
        'image' => 'images/categories/stickers.jpg'
    ],
    'general' => [
        'icon' => 'fa-folder',
        'color' => '#6b7280',
        'description' => 'Various printing services',
        'image' => 'images/categories/general.jpg'
    ]
];

// Build final categories array
$categories = [];
foreach ($categoryMap as $slug => $data) {
    $details = $categoryDetails[$slug] ?? $categoryDetails['general'];
    $categories[] = [
        'id' => array_search($slug, array_keys($categoryMap)) + 1,
        'slug' => $slug,
        'name' => $data['name'],
        'icon' => $details['icon'],
        'color' => $details['color'],
        'description' => $details['description'],
        'count' => $data['count'],
        'image' => $details['image'],
        'products' => $data['products']
    ];
}

// Sort categories by count (highest first)
usort($categories, function($a, $b) {
    return $b['count'] - $a['count'];
});

// If no categories exist (no products), use default categories
if (empty($categories)) {
    $categories = [
        [
            'id' => 1,
            'slug' => 'digital-printing',
            'name' => 'Digital Printing',
            'icon' => 'fa-print',
            'color' => '#8b5cf6',
            'description' => 'High-quality digital printing for all your needs',
            'count' => 0,
            'image' => 'images/categories/digital-printing.jpg'
        ],
        [
            'id' => 2,
            'slug' => 'logo-branding',
            'name' => 'Logo & Branding',
            'icon' => 'fa-pen-fancy',
            'color' => '#ec4899',
            'description' => 'Create a memorable brand identity',
            'count' => 0,
            'image' => 'images/categories/logo-branding.jpg'
        ],
        [
            'id' => 3,
            'slug' => 'uv-dtf-stickers',
            'name' => 'UV DTF Stickers',
            'icon' => 'fa-tags',
            'color' => '#f59e0b',
            'description' => 'Premium UV DTF stickers and transfers',
            'count' => 0,
            'image' => 'images/categories/uv-dtf-stickers.jpg'
        ],
        [
            'id' => 4,
            'slug' => 'custom-boxes',
            'name' => 'Custom Boxes & Packaging',
            'icon' => 'fa-box',
            'color' => '#22c55e',
            'description' => 'Custom packaging solutions for your products',
            'count' => 0,
            'image' => 'images/categories/custom-boxes.jpg'
        ],
        [
            'id' => 5,
            'slug' => 'brand-identity',
            'name' => 'Brand Identity',
            'icon' => 'fa-building',
            'color' => '#3b82f6',
            'description' => 'Complete brand identity solutions',
            'count' => 0,
            'image' => 'images/categories/brand-identity.jpg'
        ],
        [
            'id' => 6,
            'slug' => 'graphic-designing',
            'name' => 'Graphic Designing',
            'icon' => 'fa-palette',
            'color' => '#a855f7',
            'description' => 'Professional graphic design services',
            'count' => 0,
            'image' => 'images/categories/graphic-designing.jpg'
        ],
        [
            'id' => 7,
            'slug' => 'dtf-printing',
            'name' => 'DTF Printing',
            'icon' => 'fa-tshirt',
            'color' => '#ef4444',
            'description' => 'Direct to Film printing for garments',
            'count' => 0,
            'image' => 'images/categories/dtf-printing.jpg'
        ],
        [
            'id' => 8,
            'slug' => 'offset-printing',
            'name' => 'Offset Printing',
            'icon' => 'fa-industry',
            'color' => '#06b6d4',
            'description' => 'Professional offset printing services',
            'count' => 0,
            'image' => 'images/categories/offset-printing.jpg'
        ]
    ];
}

// Get portfolio items for the portfolio section
$allPortfolio = $portfolioModel->getAll(['limit' => 20]);

// Helper function for slug
if (!function_exists('slugify')) {
    function slugify($text) {
        $text = preg_replace('/[^a-zA-Z0-9-]/', '-', strtolower($text));
        $text = preg_replace('/-+/', '-', $text);
        return trim($text, '-');
    }
}

// Helper function for base_url
if (!function_exists('base_url')) {
    function base_url($path = '') {
        return '/' . ltrim($path, '/');
    }
}
?>

<main>

    <!-- ============================================================
         PAGE HERO
         ============================================================ -->
    <section id="cat-hero" aria-label="Categories hero">
        <div class="cat-hero-bg" aria-hidden="true"></div>
        <div class="container cat-hero-inner">
            <div class="reveal">
                <div class="cat-hero-eyebrow">Explore Our Services</div>
                <h1 class="cat-hero-title">
                    Printing for <span>What You</span><br>Need
                </h1>
                <p class="cat-hero-desc">
                    Explore our collection of stunning designs and quality printing projects
                    across multiple categories. Find the perfect solution for your printing needs.
                </p>
                <div class="cat-hero-actions">
                    <a href="#cat-grid" class="btn btn-primary btn-lg">
                        <i class="fas fa-th-large" aria-hidden="true"></i> Browse Categories
                    </a>
                    <a href="<?php echo base_url('contact'); ?>" class="btn btn-outline btn-lg">
                        <i class="fas fa-headset" aria-hidden="true"></i> Need Help?
                    </a>
                </div>
            </div>
        </div>
        <!-- Decorative elements -->
        <div class="cat-hero-shapes" aria-hidden="true">
            <div class="cat-shape cat-shape-1"></div>
            <div class="cat-shape cat-shape-2"></div>
            <div class="cat-shape cat-shape-3"></div>
        </div>
    </section>

    <!-- ============================================================
         CATEGORY FILTERS
         ============================================================ -->
    <div id="cat-filters" class="cat-filters-section">
        <div class="container">
            <div class="cat-filters-inner">
                <button class="cat-filter-btn active" data-filter="all">
                    <i class="fas fa-th-large"></i> All Categories
                </button>
                <?php foreach ($categories as $cat): ?>
                <button class="cat-filter-btn" data-filter="<?php echo $cat['slug']; ?>">
                    <i class="fas <?php echo $cat['icon'] ?? 'fa-folder'; ?>"></i>
                    <?php echo htmlspecialchars($cat['name']); ?>
                    <span class="cat-filter-count"><?php echo $cat['count']; ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- ============================================================
         CATEGORY GRID
         ============================================================ -->
    <section id="cat-grid" class="section" aria-label="Categories">
        <div class="container">
            <div class="cat-grid-header reveal">
                <div>
                    <div class="tag">Categories</div>
                    <h2 class="section-title">Browse Our <span class="highlight">Services</span></h2>
                    <p class="section-desc">Find the perfect printing solution for your needs</p>
                </div>
                <div class="cat-grid-actions">
                    <button class="cat-view-btn active" data-view="grid" aria-label="Grid view">
                        <i class="fas fa-th"></i>
                    </button>
                    <button class="cat-view-btn" data-view="list" aria-label="List view">
                        <i class="fas fa-list"></i>
                    </button>
                </div>
            </div>

            <!-- Grid View -->
            <div class="cat-grid-container" id="categoryGrid">
                <?php 
                $delayClasses = ['', 'reveal-delay-1', 'reveal-delay-2', 'reveal-delay-3'];
                $idx = 0;
                foreach ($categories as $cat): 
                    $delay = $delayClasses[$idx % 4];
                    $image = !empty($cat['image']) 
                        ? $cat['image'] 
                        : 'https://placehold.co/600x400/1a1a2e/' . str_replace('#', '', $cat['color'] ?? '8b5cf6') . '?text=' . urlencode($cat['name'] ?? 'Category');
                    
                    if (strpos($image, '/SP/') !== 0 && strpos($image, 'http') !== 0) {
                        $image = '/SP/' . ltrim($image, '/');
                    }
                    
                    $color = $cat['color'] ?? '#8b5cf6';
                    $icon = $cat['icon'] ?? 'fa-folder';
                    $count = $cat['count'] ?? 0;
                ?>
                <div class="cat-card reveal <?php echo $delay; ?>" 
                     data-category="<?php echo $cat['slug']; ?>"
                     style="--cat-color: <?php echo $color; ?>">
                    
                    <div class="cat-card-inner" onclick="window.location.href='<?php echo base_url('category/' . $cat['slug']); ?>'">
                        <!-- Image Section -->
                        <div class="cat-card-image">
                            <img src="<?php echo htmlspecialchars($image); ?>" 
                                 alt="<?php echo htmlspecialchars($cat['name'] ?? 'Category'); ?>"
                                 loading="lazy"
                                 onerror="this.src='https://placehold.co/600x400/1a1a2e/<?php echo str_replace('#', '', $color); ?>?text=<?php echo urlencode($cat['name'] ?? 'Category'); ?>'">
                            
                            <!-- Overlay -->
                            <div class="cat-card-overlay">
                                <div class="cat-card-overlay-content">
                                    <i class="fas fa-arrow-right"></i>
                                    <span>Explore Category</span>
                                </div>
                            </div>
                            
                            <!-- Category Icon Badge -->
                            <div class="cat-card-badge" style="background:<?php echo $color; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            
                            <!-- Product Count Badge -->
                            <?php if ($count > 0): ?>
                            <div class="cat-card-count-badge" style="background:<?php echo $color; ?>;">
                                <?php echo $count; ?>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Content Section -->
                        <div class="cat-card-content">
                            <div class="cat-card-header">
                                <h3 class="cat-card-title"><?php echo htmlspecialchars($cat['name'] ?? ''); ?></h3>
                            </div>
                            <p class="cat-card-desc"><?php echo htmlspecialchars($cat['description'] ?? ''); ?></p>
                            <div class="cat-card-footer">
                                <span class="cat-card-link">
                                    <?php echo $count > 0 ? 'View ' . $count . ' Services' : 'Coming Soon'; ?>
                                    <i class="fas fa-arrow-right"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <?php 
                $idx++;
                endforeach; 
                ?>
            </div>

            <!-- List View (Hidden by default) -->
            <div class="cat-list-container" id="categoryList" style="display:none;">
                <?php foreach ($categories as $cat): 
                    $color = $cat['color'] ?? '#8b5cf6';
                    $icon = $cat['icon'] ?? 'fa-folder';
                    $count = $cat['count'] ?? 0;
                ?>
                <div class="cat-list-item" data-category="<?php echo $cat['slug']; ?>">
                    <div class="cat-list-item-inner" onclick="window.location.href='<?php echo base_url('category/' . $cat['slug']); ?>'">
                        <div class="cat-list-icon" style="background:<?php echo $color; ?>">
                            <i class="fas <?php echo $icon; ?>"></i>
                        </div>
                        <div class="cat-list-content">
                            <h4 class="cat-list-title"><?php echo htmlspecialchars($cat['name'] ?? ''); ?></h4>
                            <p class="cat-list-desc"><?php echo htmlspecialchars($cat['description'] ?? ''); ?></p>
                        </div>
                        <div class="cat-list-meta">
                            <span class="cat-list-count"><?php echo $count; ?> Products</span>
                            <i class="fas fa-chevron-right" style="color:<?php echo $color; ?>;"></i>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ============================================================
         FEATURED CATEGORIES CAROUSEL
         ============================================================ -->
    <section id="cat-featured" class="section" aria-label="Featured products">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Featured Products</div>
                <h2 class="section-title">Popular <span class="highlight">Categories</span></h2>
                <p class="section-desc">Most sought-after services by our customers</p>
            </div>

            <div class="featured-carousel-wrapper">
                <button class="featured-arrow featured-prev" id="featuredPrev">
                    <i class="fas fa-chevron-left"></i>
                </button>
                
                <div class="featured-carousel" id="featuredCarousel">
                    <?php 
                    $featuredCategories = array_slice(array_filter($categories, function($cat) {
                        return $cat['count'] > 0;
                    }), 0, 6);
                    
                    if (empty($featuredCategories)) {
                        $featuredCategories = array_slice($categories, 0, 6);
                    }
                    
                    foreach ($featuredCategories as $cat):
                        $color = $cat['color'] ?? '#8b5cf6';
                        $icon = $cat['icon'] ?? 'fa-folder';
                    ?>
                    <div class="featured-slide">
                        <div class="featured-card" style="border-color: <?php echo $color; ?>;">
                            <div class="featured-card-icon" style="background:<?php echo $color; ?>">
                                <i class="fas <?php echo $icon; ?>"></i>
                            </div>
                            <h4 class="featured-card-title"><?php echo htmlspecialchars($cat['name'] ?? ''); ?></h4>
                            <p class="featured-card-desc"><?php echo htmlspecialchars($cat['description'] ?? ''); ?></p>
                            <div class="featured-card-products">
                                <span class="featured-product-count"><?php echo $cat['count']; ?> Products</span>
                            </div>
                            <a href="<?php echo base_url('category/' . $cat['slug']); ?>" class="featured-card-link">
                                Learn More <i class="fas fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <button class="featured-arrow featured-next" id="featuredNext">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>
    </section>

    <!-- ============================================================
         PORTFOLIO SHOWCASE
         ============================================================ -->
    <?php if (!empty($allPortfolio)): ?>
    <section id="cat-portfolio" class="section" aria-label="Portfolio">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Our Work</div>
                <h2 class="section-title">Recent <span class="highlight">Projects</span></h2>
                <p class="section-desc">Check out some of our latest printing and design projects</p>
            </div>
            
            <div class="portfolio-mini-grid">
                <?php 
                $portfolioItems = array_slice($allPortfolio, 0, 6);
                foreach ($portfolioItems as $item):
                    $image = !empty($item['image_url']) 
                        ? $item['image_url'] 
                        : (!empty($item['image']) ? $item['image'] : 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($item['title'] ?? 'Portfolio'));
                    
                    if (strpos($image, '/SP/') !== 0 && strpos($image, 'http') !== 0) {
                        $image = '/SP/' . ltrim($image, '/');
                    }
                ?>
                <div class="portfolio-mini-item">
                    <div class="portfolio-mini-image">
                        <img src="<?php echo htmlspecialchars($image); ?>" 
                             alt="<?php echo htmlspecialchars($item['title'] ?? 'Portfolio'); ?>"
                             loading="lazy">
                        <div class="portfolio-mini-overlay">
                            <span class="portfolio-mini-title"><?php echo htmlspecialchars($item['title'] ?? 'Project'); ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            
            <?php if (count($allPortfolio) > 6): ?>
            <div class="text-center" style="margin-top: 32px;">
                <a href="<?php echo base_url('portfolio'); ?>" class="btn btn-outline">
                    View All Projects <i class="fas fa-arrow-right"></i>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </section>
    <?php endif; ?>

    <!-- ============================================================
         HOW IT WORKS
         ============================================================ -->
    <section id="cat-process" class="section" aria-label="How it works">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Simple Process</div>
                <h2 class="section-title">How to <span class="highlight">Get Started</span></h2>
                <p class="section-desc">From choosing a category to delivery in 4 easy steps</p>
            </div>
            <div class="process-strip" style="margin-top:56px">
                <div class="ps-step reveal">
                    <div class="ps-num" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">1</div>
                    <div class="ps-icon"><i class="fas fa-search" style="color:var(--clr-primary)" aria-hidden="true"></i></div>
                    <div class="ps-title">Browse Categories</div>
                    <div class="ps-desc">Explore our wide range of printing and design services.</div>
                </div>
                <div class="ps-step reveal reveal-delay-1">
                    <div class="ps-num" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">2</div>
                    <div class="ps-icon"><i class="fas fa-comments" style="color:#3b82f6" aria-hidden="true"></i></div>
                    <div class="ps-title">Contact Us</div>
                    <div class="ps-desc">Share your requirements via WhatsApp, call, or visit our shop.</div>
                </div>
                <div class="ps-step reveal reveal-delay-2">
                    <div class="ps-num" style="background:linear-gradient(135deg,#f59e0b,#b45309)">3</div>
                    <div class="ps-icon"><i class="fas fa-magic" style="color:var(--clr-accent)" aria-hidden="true"></i></div>
                    <div class="ps-title">Get It Done</div>
                    <div class="ps-desc">We design and print your order with premium quality.</div>
                </div>
                <div class="ps-step reveal reveal-delay-3">
                    <div class="ps-num" style="background:linear-gradient(135deg,#22c55e,#15803d)">4</div>
                    <div class="ps-icon"><i class="fas fa-truck" style="color:var(--clr-green)" aria-hidden="true"></i></div>
                    <div class="ps-title">Delivery</div>
                    <div class="ps-desc">Collect from our shop or get it delivered to your door.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ============================================================
         CTA BANNER
         ============================================================ -->
    <section id="cat-cta" aria-label="Call to action">
        <div class="cat-cta-blob cat-cta-blob-1" aria-hidden="true"></div>
        <div class="cat-cta-blob cat-cta-blob-2" aria-hidden="true"></div>
        <div class="container cat-cta-inner reveal">
            <div class="cat-cta-title">
                Don't See What You're<br>Looking For?
            </div>
            <p class="cat-cta-desc">
                We offer custom printing solutions tailored to your specific needs.
                Contact us today and let's bring your vision to life.
            </p>
            <div class="cat-cta-btns">
                <a href="https://wa.me/923001234567" target="_blank" rel="noopener" class="btn btn-green btn-lg">
                    <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat with Us
                </a>
                <a href="<?php echo base_url('contact'); ?>" class="btn btn-lg" style="background:rgba(255,255,255,0.1);color:#fff;border:1.5px solid rgba(255,255,255,0.2)">
                    <i class="fas fa-envelope" aria-hidden="true"></i> Send Message
                </a>
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
     JAVASCRIPT
     ============================================================ -->
<script>
// ============================================================
// CATEGORY FILTER
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.cat-filter-btn');
    const catCards = document.querySelectorAll('.cat-card');
    const catListItems = document.querySelectorAll('.cat-list-item');
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const filter = this.dataset.filter;
            
            // Filter cards
            catCards.forEach(card => {
                if (filter === 'all' || card.dataset.category === filter) {
                    card.style.display = '';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
            
            // Filter list items
            catListItems.forEach(item => {
                if (filter === 'all' || item.dataset.category === filter) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    });
});

// ============================================================
// VIEW TOGGLE (GRID / LIST)
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const viewBtns = document.querySelectorAll('.cat-view-btn');
    const gridContainer = document.getElementById('categoryGrid');
    const listContainer = document.getElementById('categoryList');
    
    viewBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            viewBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            const view = this.dataset.view;
            
            if (view === 'grid') {
                gridContainer.style.display = 'grid';
                listContainer.style.display = 'none';
            } else {
                gridContainer.style.display = 'none';
                listContainer.style.display = 'block';
            }
        });
    });
});

// ============================================================
// FEATURED CAROUSEL
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    const carousel = document.getElementById('featuredCarousel');
    const prevBtn = document.getElementById('featuredPrev');
    const nextBtn = document.getElementById('featuredNext');
    const slides = carousel.querySelectorAll('.featured-slide');
    let currentIndex = 0;
    const totalSlides = slides.length;
    let visibleSlides = 3;
    let autoPlayInterval = null;
    
    // Determine visible slides based on screen width
    function getVisibleSlides() {
        if (window.innerWidth < 768) return 1;
        if (window.innerWidth < 1024) return 2;
        return 3;
    }
    
    function updateCarousel() {
        visibleSlides = getVisibleSlides();
        const slideWidth = 100 / visibleSlides;
        
        slides.forEach(slide => {
            slide.style.minWidth = slideWidth + '%';
            slide.style.maxWidth = slideWidth + '%';
        });
        
        const offset = -currentIndex * slideWidth;
        carousel.style.transform = `translateX(${offset}%)`;
        carousel.style.transition = 'transform 0.5s cubic-bezier(0.4, 0, 0.2, 1)';
        
        // Update button states
        prevBtn.disabled = currentIndex === 0;
        nextBtn.disabled = currentIndex >= totalSlides - visibleSlides;
    }
    
    function nextSlide() {
        const maxIndex = totalSlides - visibleSlides;
        if (currentIndex < maxIndex) {
            currentIndex++;
            updateCarousel();
        } else {
            currentIndex = 0;
            updateCarousel();
        }
    }
    
    function prevSlide() {
        if (currentIndex > 0) {
            currentIndex--;
            updateCarousel();
        } else {
            currentIndex = totalSlides - visibleSlides;
            updateCarousel();
        }
    }
    
    // Event listeners
    prevBtn.addEventListener('click', function() {
        prevSlide();
        resetAutoPlay();
    });
    
    nextBtn.addEventListener('click', function() {
        nextSlide();
        resetAutoPlay();
    });
    
    // Auto-play
    function startAutoPlay() {
        if (autoPlayInterval) clearInterval(autoPlayInterval);
        autoPlayInterval = setInterval(nextSlide, 4000);
    }
    
    function resetAutoPlay() {
        clearInterval(autoPlayInterval);
        startAutoPlay();
    }
    
    // Pause on hover
    const wrapper = document.querySelector('.featured-carousel-wrapper');
    if (wrapper) {
        wrapper.addEventListener('mouseenter', () => clearInterval(autoPlayInterval));
        wrapper.addEventListener('mouseleave', startAutoPlay);
    }
    
    // Window resize
    let resizeTimeout;
    window.addEventListener('resize', function() {
        clearTimeout(resizeTimeout);
        resizeTimeout = setTimeout(() => {
            updateCarousel();
        }, 250);
    });
    
    // Initialize
    if (totalSlides > 0) {
        updateCarousel();
        setTimeout(startAutoPlay, 3000);
    }
    
    // Touch support
    let touchStartX = 0;
    let touchEndX = 0;
    
    carousel.addEventListener('touchstart', function(e) {
        touchStartX = e.changedTouches[0].screenX;
    }, { passive: true });
    
    carousel.addEventListener('touchend', function(e) {
        touchEndX = e.changedTouches[0].screenX;
        const diff = touchStartX - touchEndX;
        if (Math.abs(diff) > 50) {
            if (diff > 0) {
                nextSlide();
            } else {
                prevSlide();
            }
            resetAutoPlay();
        }
    }, { passive: true });
});
</script>

<!-- ============================================================
     ADDITIONAL CSS
     ============================================================ -->
<style>
/* ============================================================
   CATEGORY FILTER COUNT BADGE
   ============================================================ */
.cat-filter-count {
    display: inline-block;
    font-size: 0.6rem;
    background: rgba(255,255,255,0.1);
    padding: 1px 8px;
    border-radius: 100px;
    margin-left: 4px;
}

.cat-filter-btn.active .cat-filter-count {
    background: rgba(255,255,255,0.2);
}

/* ============================================================
   CATEGORY CARD COUNT BADGE
   ============================================================ */
.cat-card-count-badge {
    position: absolute;
    top: 16px;
    left: 16px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    z-index: 2;
}

/* ============================================================
   PORTFOLIO MINI GRID
   ============================================================ */
.portfolio-mini-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 16px;
    margin-top: 32px;
}

.portfolio-mini-item {
    border-radius: var(--radius-lg);
    overflow: hidden;
    cursor: pointer;
    transition: all 0.3s ease;
}

.portfolio-mini-item:hover {
    transform: translateY(-4px);
    box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

.portfolio-mini-image {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    background: var(--clr-surface-2);
}

.portfolio-mini-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.portfolio-mini-item:hover .portfolio-mini-image img {
    transform: scale(1.08);
}

.portfolio-mini-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.7) 0%, transparent 100%);
    display: flex;
    align-items: flex-end;
    padding: 16px;
    opacity: 0;
    transition: opacity 0.3s ease;
}

.portfolio-mini-item:hover .portfolio-mini-overlay {
    opacity: 1;
}

.portfolio-mini-title {
    color: #fff;
    font-size: 0.85rem;
    font-weight: 600;
}

/* ============================================================
   FEATURED CARD PRODUCTS
   ============================================================ */
.featured-card-products {
    margin: 12px 0 16px;
}

.featured-product-count {
    display: inline-block;
    font-size: 0.75rem;
    color: var(--clr-muted);
    background: rgba(255,255,255,0.06);
    padding: 4px 12px;
    border-radius: 100px;
}

/* ============================================================
   RESPONSIVE UPDATES
   ============================================================ */
@media (max-width: 992px) {
    .portfolio-mini-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 768px) {
    .portfolio-mini-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}

@media (max-width: 480px) {
    .portfolio-mini-grid {
        grid-template-columns: 1fr;
        max-width: 300px;
        margin: 32px auto 0;
    }
    
    .cat-card-count-badge {
        width: 24px;
        height: 24px;
        font-size: 0.6rem;
        top: 12px;
        left: 12px;
    }
}
</style>
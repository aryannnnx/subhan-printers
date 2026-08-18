<?php
// ============================================
// PAGES: Portfolio Page - Subhan Printers
// ============================================

// Set page variables
$pageTitle = 'Portfolio | Subhan Printers – Our Work Gallery';
$currentPage = 'portfolio';
$pageStyles = 'portfolio.css';
$pageScripts = 'portfolio.js';

// Include header
require_once __DIR__ . '/../templates/header.php';

// ============================================
// LOAD MODELS
// ============================================
require_once __DIR__ . '/../models/Portfolio.php';
require_once __DIR__ . '/../models/Category.php';
require_once __DIR__ . '/../includes/functions.php';

// Fetch data from database
$portfolioModel = new Portfolio();
$categoryModel = new Category();

// ============================================
// GET ALL PORTFOLIO ITEMS
// ============================================
$allItems = $portfolioModel->getAll(['limit' => 999]);

// Get categories with counts
$categories = $portfolioModel->getCategories();

// If no categories, get from category model
if (empty($categories)) {
    $cats = $categoryModel->getAll(true);
    $categories = [];
    foreach ($cats as $cat) {
        $categories[] = ['category' => $cat['slug'], 'count' => 0];
    }
}

// If still empty, use fallback
if (empty($categories)) {
    $categories = [
        ['category' => 'wedding', 'count' => 0],
        ['category' => 'packaging', 'count' => 0],
        ['category' => 'flex', 'count' => 0],
        ['category' => 'brochures', 'count' => 0],
        ['category' => 'stickers', 'count' => 0],
        ['category' => 'design', 'count' => 0]
    ];
}

// Get count by category
$categoryCounts = [];
foreach ($categories as $cat) {
    $catName = is_array($cat) ? ($cat['category'] ?? '') : '';
    if (!empty($catName)) {
        $categoryCounts[$catName] = $portfolioModel->countByCategory($catName);
    }
}
?>

<main>
    <!-- PAGE HERO -->
    <section id="port-hero" aria-label="Portfolio hero">
        <div class="hero-bg" aria-hidden="true"></div>
        <div class="hero-grid-lines" aria-hidden="true"></div>
        <div class="container hero-inner">
            <nav class="breadcrumb reveal" aria-label="Breadcrumb">
                <a href="<?php echo base_url(); ?>">Home</a>
                <i class="fas fa-chevron-right"></i>
                <span style="color:var(--clr-white)">Portfolio</span>
            </nav>
            <h1 class="hero-title reveal">
                Our <span class="highlight">Portfolio</span> of<br>Print &amp; Design Work
            </h1>
            <p class="hero-desc reveal reveal-d1">
                Over 10,000 projects delivered across Pakistan — wedding cards, luxury packaging, flex banners, branding, and more. Every project crafted with precision and passion.
            </p>
            <div class="hero-actions reveal reveal-d2">
                <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="btn btn-green btn-lg">
                    <i class="fab fa-whatsapp"></i> Start Your Project
                </a>
                <a href="#port-main" class="btn btn-outline btn-lg">
                    Browse Work <i class="fas fa-arrow-down"></i>
                </a>
            </div>
            <div class="stats-strip reveal reveal-d3">
                <div class="stat-card"><div class="stat-num" style="color:var(--clr-accent)">10K+</div><div class="stat-label">Projects Done</div></div>
                <div class="stat-card"><div class="stat-num" style="color:var(--clr-primary)">5000+</div><div class="stat-label">Happy Clients</div></div>
                <div class="stat-card"><div class="stat-num" style="color:var(--clr-green)">25+</div><div class="stat-label">Years Experience</div></div>
                <div class="stat-card"><div class="stat-num" style="color:#3b82f6">4.9★</div><div class="stat-label">Average Rating</div></div>
            </div>
        </div>
    </section>

    <!-- FILTER TABS -->
    <div id="port-tabs" role="tablist" aria-label="Filter by category">
        <div class="container">
            <div class="tabs-scroll">
                <button class="f-tab active" data-filter="all" role="tab" aria-selected="true">
                    <i class="fas fa-th-large"></i> All <span class="f-count"><?php echo count($allItems); ?></span>
                </button>
                <?php foreach ($categories as $cat):
                    $catName = is_array($cat) ? ($cat['category'] ?? '') : '';
                    if (empty($catName)) continue;
                    
                    $catDisplay = ucfirst(str_replace('-', ' ', $catName));
                    $count = $categoryCounts[$catName] ?? 0;
                    
                    $icon = 'folder';
                    $icons = [
                        'wedding' => 'heart',
                        'packaging' => 'box',
                        'flex' => 'image',
                        'brochures' => 'file-alt',
                        'stickers' => 'tags',
                        'design' => 'crown'
                    ];
                    $icon = $icons[$catName] ?? 'folder';
                ?>
                <button class="f-tab" data-filter="<?php echo htmlspecialchars($catName); ?>" role="tab" aria-selected="false">
                    <i class="fas fa-<?php echo $icon; ?>"></i>
                    <?php echo htmlspecialchars($catDisplay); ?>
                    <span class="f-count"><?php echo $count; ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- PORTFOLIO GRID -->
    <section id="port-main" class="section" aria-label="Portfolio projects">
        <div class="container">

            <div class="port-toolbar reveal">
                <div class="results-txt">Showing <strong id="visible-count"><?php echo count($allItems); ?></strong> projects</div>
                <div class="sort-wrap">
                    <label for="sort-sel">Sort:</label>
                    <select id="sort-sel">
                        <option value="default">Default</option>
                        <option value="newest">Newest</option>
                        <option value="az">A – Z</option>
                    </select>
                </div>
            </div>

            <div class="port-grid" id="the-grid">
                <?php 
                $delayClasses = ['', 'reveal-d1', 'reveal-d2', 'reveal-d3'];
                $idx = 0;
                $isFirst = true;
                
                if (!empty($allItems) && is_array($allItems)):
                    foreach ($allItems as $item): 
                        $delay = $delayClasses[$idx % 4];
                        $isWide = $isFirst && isset($item['featured']) && $item['featured'] === true;
                        
                        // Get image URL
                        $imageUrl = 'https://placehold.co/600x400/1a1a2e/8b5cf6?text=' . urlencode($item['title'] ?? 'Project');
                        if (!empty($item['primary_image'])) {
                            $imageUrl = $item['primary_image']['url'];
                        } elseif (!empty($item['images']) && is_array($item['images']) && !empty($item['images'][0]['url'])) {
                            $imageUrl = $item['images'][0]['url'];
                        }
                        
                        // Get tags safely
                        $tags = isset($item['tags']) && is_array($item['tags']) ? $item['tags'] : [];
                        if (!empty($item['tags']) && is_string($item['tags'])) {
                            $tags = json_decode($item['tags'], true) ?: [];
                        }
                        
                        $category = $item['category'] ?? 'general';
                        $badge = $item['category_name'] ?? ucfirst($category);
                        
                        $badgeColors = [
                            'wedding' => 'rgba(236,72,153,.85)',
                            'packaging' => 'rgba(245,158,11,.85)',
                            'flex' => 'rgba(34,197,94,.85)',
                            'brochures' => 'rgba(59,130,246,.85)',
                            'stickers' => 'rgba(99,102,241,.85)',
                            'design' => 'rgba(139,92,246,.85)'
                        ];
                        $badgeColor = $badgeColors[$category] ?? 'rgba(139,92,246,.85)';
                        $textColor = $category === 'packaging' ? '#111' : '#fff';
                ?>
                <article class="p-card <?php echo $isWide ? 'wide ' : ''; ?>visible reveal <?php echo $delay; ?>" 
                         data-cat="<?php echo htmlspecialchars($category); ?>" 
                         data-id="<?php echo $item['id'] ?? 'p-' . $idx; ?>"
                         data-title="<?php echo htmlspecialchars($item['title'] ?? ''); ?>"
                         data-sub="<?php echo htmlspecialchars($item['subtitle'] ?? ''); ?>"
                         data-desc="<?php echo htmlspecialchars($item['description'] ?? ''); ?>"
                         data-img="<?php echo htmlspecialchars($imageUrl); ?>"
                         onclick="openLightbox(this)"
                         tabindex="0" aria-label="View <?php echo htmlspecialchars($item['title'] ?? 'Project'); ?>">
                    
                    <?php if (!empty($badge)): ?>
                    <span class="p-card-badge" style="background:<?php echo $badgeColor; ?>;color:<?php echo $textColor; ?>"><?php echo htmlspecialchars($badge); ?></span>
                    <?php endif; ?>
                    
                    <?php if ($isWide && isset($item['featured']) && $item['featured'] === true): ?>
                    <span class="p-card-popular">⭐ Featured</span>
                    <?php endif; ?>
                    
                    <img class="p-card-img" src="<?php echo htmlspecialchars($imageUrl); ?>" 
                         alt="<?php echo htmlspecialchars($item['title'] ?? 'Portfolio item'); ?>" 
                         loading="lazy" 
                         onerror="this.src='https://placehold.co/600x400/1a1a2e/8b5cf6?text=<?php echo urlencode($item['title'] ?? ''); ?>'" />
                    <div class="p-card-overlay"></div>
                    <div class="p-card-zoom"><i class="fas fa-expand-alt"></i></div>
                    <div class="p-card-body">
                        <div class="p-card-cat"><?php echo ucfirst(htmlspecialchars($category)); ?></div>
                        <div class="p-card-title"><?php echo htmlspecialchars($item['title'] ?? ''); ?></div>
                        <div class="p-card-sub"><?php echo htmlspecialchars($item['subtitle'] ?? ''); ?></div>
                        <?php if (!empty($tags)): ?>
                        <div class="p-card-tags">
                            <?php foreach (array_slice($tags, 0, 3) as $tag): ?>
                            <span class="p-tag"><?php echo htmlspecialchars($tag); ?></span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </article>
                <?php 
                    $idx++;
                    $isFirst = false;
                    endforeach; 
                else: 
                ?>
                <div style="grid-column:1/-1;text-align:center;padding:60px 20px;color:#888;">
                    <i class="fas fa-images" style="font-size:3rem;display:block;margin-bottom:16px;"></i>
                    <h3 style="color:#fff;">No Portfolio Items Found</h3>
                    <p>Please add portfolio items from the admin panel.</p>
                </div>
                <?php endif; ?>
            </div>

            <div style="text-align:center;margin-top:48px" class="reveal">
                <a href="https://wa.me/923004197033?text=Hi!%20I%20want%20to%20discuss%20a%20print%20project" target="_blank" rel="noopener" class="btn btn-primary btn-lg">
                    <i class="fab fa-whatsapp"></i> Like What You See? Let's Talk
                </a>
            </div>
        </div>
    </section>
</main>

<!-- ============================================================
     LIGHTBOX / MODAL
     ============================================================ -->
<div id="lightbox" class="lightbox" onclick="closeLightbox(event)">
    <div class="lightbox-content">
        <button class="lightbox-close" onclick="closeLightbox()">
            <i class="fas fa-times"></i>
        </button>
        <button class="lightbox-nav lightbox-prev" onclick="changeSlide(-1)">
            <i class="fas fa-chevron-left"></i>
        </button>
        <button class="lightbox-nav lightbox-next" onclick="changeSlide(1)">
            <i class="fas fa-chevron-right"></i>
        </button>
        <div class="lightbox-inner">
            <img id="lightbox-img" src="" alt="Project preview">
            <div class="lightbox-info">
                <div class="lightbox-category" id="lightbox-category"></div>
                <h2 class="lightbox-title" id="lightbox-title"></h2>
                <p class="lightbox-desc" id="lightbox-desc"></p>
                <div class="lightbox-tags" id="lightbox-tags"></div>
                <div class="lightbox-actions">
                    <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="btn btn-green">
                        <i class="fab fa-whatsapp"></i> Order Similar
                    </a>
                    <a href="<?php echo base_url('services'); ?>" class="btn btn-outline">View Services</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// ============================================================
// FOOTER - Include footer
// ============================================================
require_once __DIR__ . '/../templates/footer.php';
?>

<!-- ============================================================
     LIGHTBOX JAVASCRIPT
     ============================================================ -->
<script>
// Store all portfolio items for navigation
let lightboxItems = [];
let currentIndex = 0;

// Get all portfolio items from the page
document.addEventListener('DOMContentLoaded', function() {
    const cards = document.querySelectorAll('.p-card');
    cards.forEach((card, index) => {
        const img = card.dataset.img || card.querySelector('.p-card-img')?.src;
        const title = card.dataset.title || '';
        const desc = card.dataset.desc || '';
        const sub = card.dataset.sub || '';
        const cat = card.dataset.cat || '';
        
        lightboxItems.push({
            img: img,
            title: title,
            desc: desc,
            sub: sub,
            cat: cat
        });
    });
});

// Open lightbox
function openLightbox(element) {
    const img = element.dataset.img || element.querySelector('.p-card-img')?.src;
    const title = element.dataset.title || '';
    const desc = element.dataset.desc || '';
    const sub = element.dataset.sub || '';
    const cat = element.dataset.cat || '';
    
    // Find current index
    currentIndex = lightboxItems.findIndex(item => item.img === img);
    if (currentIndex === -1) currentIndex = 0;
    
    // Set image
    document.getElementById('lightbox-img').src = img;
    document.getElementById('lightbox-img').alt = title;
    
    // Set info
    document.getElementById('lightbox-category').textContent = cat.charAt(0).toUpperCase() + cat.slice(1);
    document.getElementById('lightbox-title').textContent = title;
    document.getElementById('lightbox-desc').textContent = desc || sub;
    
    // Set tags
    const tagsContainer = document.getElementById('lightbox-tags');
    tagsContainer.innerHTML = '';
    if (sub) {
        const tag = document.createElement('span');
        tag.className = 'lightbox-tag';
        tag.textContent = sub;
        tagsContainer.appendChild(tag);
    }
    
    // Show lightbox
    document.getElementById('lightbox').classList.add('open');
    document.body.style.overflow = 'hidden';
}

// Close lightbox
function closeLightbox(event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById('lightbox').classList.remove('open');
    document.body.style.overflow = '';
}

// Change slide
function changeSlide(direction) {
    const total = lightboxItems.length;
    if (total === 0) return;
    
    currentIndex = (currentIndex + direction + total) % total;
    const item = lightboxItems[currentIndex];
    
    if (item) {
        document.getElementById('lightbox-img').src = item.img;
        document.getElementById('lightbox-img').alt = item.title;
        document.getElementById('lightbox-category').textContent = item.cat.charAt(0).toUpperCase() + item.cat.slice(1);
        document.getElementById('lightbox-title').textContent = item.title;
        document.getElementById('lightbox-desc').textContent = item.desc || item.sub;
        
        const tagsContainer = document.getElementById('lightbox-tags');
        tagsContainer.innerHTML = '';
        if (item.sub) {
            const tag = document.createElement('span');
            tag.className = 'lightbox-tag';
            tag.textContent = item.sub;
            tagsContainer.appendChild(tag);
        }
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    if (!document.getElementById('lightbox').classList.contains('open')) return;
    
    if (e.key === 'Escape') {
        closeLightbox();
    } else if (e.key === 'ArrowLeft') {
        changeSlide(-1);
    } else if (e.key === 'ArrowRight') {
        changeSlide(1);
    }
});
</script>

<!-- ============================================================
     LIGHTBOX CSS
     ============================================================ -->
<style>
/* Lightbox / Modal */
.lightbox {
    position: fixed;
    inset: 0;
    z-index: 9999;
    background: rgba(0, 0, 0, 0.92);
    backdrop-filter: blur(12px);
    display: none;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.lightbox.open {
    display: flex;
    animation: lightboxFadeIn 0.3s ease;
}

@keyframes lightboxFadeIn {
    from { opacity: 0; transform: scale(0.95); }
    to { opacity: 1; transform: scale(1); }
}

.lightbox-content {
    position: relative;
    max-width: 1000px;
    width: 100%;
    max-height: 90vh;
    background: var(--clr-surface);
    border: 1px solid var(--clr-border);
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.lightbox-close {
    position: absolute;
    top: 16px;
    right: 16px;
    z-index: 10;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-close:hover {
    background: var(--clr-primary);
    transform: rotate(90deg);
}

.lightbox-nav {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    z-index: 10;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: rgba(0, 0, 0, 0.7);
    border: 1px solid rgba(255, 255, 255, 0.1);
    color: #fff;
    font-size: 1.2rem;
    cursor: pointer;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
}

.lightbox-nav:hover {
    background: var(--clr-primary);
}

.lightbox-prev {
    left: 16px;
}

.lightbox-next {
    right: 16px;
}

.lightbox-inner {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0;
    max-height: 85vh;
}

.lightbox-inner img {
    width: 100%;
    height: 500px;
    object-fit: cover;
    display: block;
}

.lightbox-info {
    padding: 32px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow-y: auto;
}

.lightbox-category {
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.1em;
    color: var(--clr-accent);
    margin-bottom: 8px;
}

.lightbox-title {
    font-family: var(--font-display);
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--clr-white);
    margin-bottom: 12px;
}

.lightbox-desc {
    font-size: 0.95rem;
    color: var(--clr-muted);
    line-height: 1.7;
    margin-bottom: 16px;
}

.lightbox-tags {
    display: flex;
    gap: 8px;
    flex-wrap: wrap;
    margin-bottom: 20px;
}

.lightbox-tag {
    font-size: 0.7rem;
    font-weight: 600;
    background: rgba(139, 92, 246, 0.15);
    border: 1px solid rgba(139, 92, 246, 0.2);
    color: var(--clr-primary);
    padding: 4px 12px;
    border-radius: 100px;
}

.lightbox-actions {
    display: flex;
    gap: 12px;
    flex-wrap: wrap;
}

.lightbox-actions .btn {
    flex: 1;
    justify-content: center;
    min-width: 140px;
}

@media (max-width: 768px) {
    .lightbox-inner {
        grid-template-columns: 1fr;
    }
    
    .lightbox-inner img {
        height: 280px;
    }
    
    .lightbox-info {
        padding: 24px;
    }
    
    .lightbox-title {
        font-size: 1.4rem;
    }
    
    .lightbox-nav {
        width: 40px;
        height: 40px;
        font-size: 0.9rem;
    }
    
    .lightbox-prev {
        left: 8px;
    }
    
    .lightbox-next {
        right: 8px;
    }
}

@media (max-width: 480px) {
    .lightbox-inner img {
        height: 200px;
    }
    
    .lightbox-actions {
        flex-direction: column;
    }
    
    .lightbox-actions .btn {
        flex: none;
        width: 100%;
    }
}

/* Card zoom icon */
.p-card-zoom {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%) scale(0.8);
    width: 56px;
    height: 56px;
    border-radius: 50%;
    background: rgba(139, 92, 246, 0.9);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #fff;
    font-size: 1.2rem;
    opacity: 0;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    z-index: 5;
    pointer-events: none;
}

.p-card:hover .p-card-zoom {
    opacity: 1;
    transform: translate(-50%, -50%) scale(1);
}

.p-card {
    cursor: pointer;
}
</style>
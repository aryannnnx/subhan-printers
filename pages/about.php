<?php
// ============================================
// PAGES: About Us - Subhan Printers
// ============================================

// Set page variables
$pageTitle = 'About Us | Subhan Printers – Lahore';
$currentPage = 'about';
$pageStyles = 'about.css';
$pageScripts = 'about.js';

// Include header
require_once __DIR__ . '/../templates/header.php';

// Load models
require_once __DIR__ . '/../models/User.php';

// ============================================
// TEAM MEMBERS - 3 MEMBERS ONLY
// ============================================
// ============================================
// TEAM MEMBERS - WITH EXPERIENCE
// ============================================
$teamMembers = [
    [
        'name' => 'Mr. Yasir Ali',
        'role' => 'Founder & CEO',
        'desc' => '25+ years in printing. Started the business from scratch and built it into Lahore\'s most trusted print house.',
        'avatar' => '/SP/images/TEAM1.png',
        'fallback' => 'https://randomuser.me/api/portraits/men/44.jpg',
        'experience' => '25+',
        'tags' => ['Founder', 'Offset Expert'],
        'social' => ['linkedin' => '#', 'instagram' => '#']
    ],
    [
        'name' => 'Aryan Yasir',
        'role' => 'Lead Graphic Designer',
        'desc' => '10+ years of brand identity and packaging design. Specialises in luxury wedding card and premium packaging aesthetics.',
        'avatar' => '/SP/images/TEAM2.png',
        'fallback' => 'https://randomuser.me/api/portraits/men/52.jpg',
        'experience' => '10+',
        'tags' => ['Branding', 'Packaging'],
        'social' => ['linkedin' => '#', 'whatsapp' => '#']
    ],
    [
        'name' => 'Naveed Hussain',
        'role' => 'Operations Director',
        'desc' => 'Manages day-to-day production, quality control, and ensures every order is delivered on time.',
        'avatar' => '/SP/images/team3.png',
        'fallback' => 'https://randomuser.me/api/portraits/men/36.jpg',
        'experience' => '15+',
        'tags' => ['Operations', 'QC'],
        'social' => ['instagram' => '#', 'behance' => '#']
    ],
];

// Stats data
$stats = [
    ['icon' => 'fa-users', 'num' => '5000', 'suffix' => '+', 'label' => 'Happy Clients', 'color' => 'var(--clr-primary)'],
    ['icon' => 'fa-print', 'num' => '10000', 'suffix' => '+', 'label' => 'Projects Completed', 'color' => 'var(--clr-accent)'],
    ['icon' => 'fa-award', 'num' => '25', 'suffix' => '+', 'label' => 'Years in Business', 'color' => 'var(--clr-green)'],
    ['icon' => 'fa-star', 'num' => '4.9', 'suffix' => '', 'label' => 'Average Star Rating', 'color' => '#3b82f6']
];

// Timeline data
$timeline = [
    ['year' => '1999 — The Beginning', 'title' => 'Subhan Printers Founded', 'desc' => 'Started with a single offset press in Gawalmandi, printing wedding cards and visiting cards for the local community.', 'tag' => 'Founded', 'tagIcon' => 'fa-flag', 'dotColor' => 'linear-gradient(135deg,#8b5cf6,#6d28d9)', 'dotText' => '99'],
    ['year' => '2003 — First Expansion', 'title' => 'New Machines & First Staff', 'desc' => 'Purchased a second press and hired our first two dedicated press operators. Capacity doubled, serving 200+ monthly clients.', 'tag' => 'Expansion', 'tagIcon' => 'fa-expand', 'dotColor' => 'linear-gradient(135deg,#f59e0b,#d97706)', 'dotText' => '03'],
    ['year' => '2008 — Packaging Division', 'title' => 'Launched Custom Box Packaging', 'desc' => 'Added die-cutting and folding machinery. Began producing custom boxes for cosmetics, food, and retail brands across Punjab.', 'tag' => 'New Service', 'tagIcon' => 'fa-box', 'dotColor' => 'linear-gradient(135deg,#22c55e,#15803d)', 'dotText' => '08'],
    ['year' => '2014 — Digital Printing', 'title' => 'Invested in Digital & Large Format', 'desc' => 'Installed our first digital press and large-format printer. Launched flex banner, standee, and brochure printing at scale.', 'tag' => 'Technology', 'tagIcon' => 'fa-print', 'dotColor' => 'linear-gradient(135deg,#3b82f6,#1d4ed8)', 'dotText' => '14'],
    ['year' => '2018 — In-House Design Studio', 'title' => 'Full Graphic Design Department', 'desc' => 'Established a dedicated design studio with 8 professional designers. Complete brand identity, packaging design, and digital work.', 'tag' => 'Milestone', 'tagIcon' => 'fa-palette', 'dotColor' => 'linear-gradient(135deg,#ec4899,#be185d)', 'dotText' => '18'],
    ['year' => '2024 — Today', 'title' => "Pakistan's Trusted Print Partner", 'desc' => 'Serving 5,000+ clients across Pakistan with 30+ team members, state-of-the-art equipment, and same-day printing available in Lahore.', 'tag' => 'Present', 'tagIcon' => 'fa-trophy', 'dotColor' => 'linear-gradient(135deg,#f59e0b,#8b5cf6)', 'dotText' => '24']
];

// Values data
$values = [
    ['icon' => 'fa-gem', 'title' => 'Uncompromising Quality', 'desc' => 'We use only premium paper stocks, inks, and finishes. Every print is inspected before it leaves our facility. If it\'s not perfect, we reprint it — no questions asked.', 'color' => 'linear-gradient(135deg,#8b5cf6,#6d28d9)'],
    ['icon' => 'fa-handshake', 'title' => 'Trust & Transparency', 'desc' => 'No hidden costs, no surprises. We give you an honest quote upfront. If something goes wrong on our end, we fix it free of charge — every time.', 'color' => 'linear-gradient(135deg,#f59e0b,#b45309)'],
    ['icon' => 'fa-bolt', 'title' => 'Speed & Reliability', 'desc' => 'Deadlines are sacred. We\'ve never missed a committed delivery date in 25 years. Same-day and 24-hour rush printing available without compromising quality.', 'color' => 'linear-gradient(135deg,#22c55e,#15803d)'],
    ['icon' => 'fa-lightbulb', 'title' => 'Creative Innovation', 'desc' => 'We constantly invest in new technology and train our designers. From holographic stickers to embossed packaging — we bring cutting-edge finishes to Lahore.', 'color' => 'linear-gradient(135deg,#3b82f6,#1d4ed8)'],
    ['icon' => 'fa-heart', 'title' => 'Client-First Culture', 'desc' => 'Your satisfaction isn\'t just a target — it\'s a non-negotiable. We listen, we advise, and we adapt to your needs. You\'re not a number here; you\'re family.', 'color' => 'linear-gradient(135deg,#ec4899,#be185d)'],
    ['icon' => 'fa-leaf', 'title' => 'Responsible Business', 'desc' => 'We use food-safe inks where required, source FSC-certified paper when available, and minimise waste in our production process every day.', 'color' => 'linear-gradient(135deg,#f59e0b,#8b5cf6)']
];

// Awards data
$awards = [
    ['icon' => '🏆', 'title' => 'Best Print Shop Lahore', 'desc' => 'Recognised by the Lahore Business Directory for outstanding print quality and client satisfaction.', 'year' => '2023'],
    ['icon' => '⭐', 'title' => '5-Star Client Rating', 'desc' => 'Consistently rated 4.9/5 across Google Reviews, Facebook, and direct client surveys.', 'year' => '2020–2024'],
    ['icon' => '🌿', 'title' => 'Eco-Responsible Printing', 'desc' => 'Committed to food-safe inks, FSC-certified paper sourcing, and waste reduction in production.', 'year' => '2022'],
    ['icon' => '🤝', 'title' => '25 Years of Business', 'desc' => 'A quarter century of serving Lahore and Pakistan with integrity, quality and passion.', 'year' => '1999–2024']
];

// Reviews data
$reviews = [
    [
        'name' => 'Ayesha Khan',
        'role' => 'Bride & loyal client since 2019 – Lahore',
        'avatar' => 'https://randomuser.me/api/portraits/women/68.jpg',
        'text' => 'Subhan Printers did our entire wedding stationery — cards, envelopes, ribbons, and thank-you cards. Everything was flawless and delivered 2 days early. I\'ve recommended them to every friend getting married since!',
        'badge' => '💍 Wedding Stationery'
    ],
    [
        'name' => 'Bilal Ahmed',
        'role' => 'Brand Manager',
        'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
        'text' => 'Our perfume packaging turned out absolutely stunning. The gold foil and soft-touch lamination exceeded every expectation. Will reorder 1000 more next month.',
        'badge' => '📦 Box Packaging'
    ],
    [
        'name' => 'Fatima Zafar',
        'role' => 'E-commerce Owner',
        'avatar' => 'https://randomuser.me/api/portraits/women/45.jpg',
        'text' => 'I\'ve been using Subhan Printers for all my boutique packaging for 3 years. Consistent quality, honest pricing, and they always come through on tight deadlines.',
        'badge' => '🎀 Boutique Packaging'
    ],
    [
        'name' => 'Usman Chaudhry',
        'role' => 'Restaurant Owner',
        'avatar' => 'https://randomuser.me/api/portraits/men/75.jpg',
        'text' => 'Our menus, flyers and outdoor flex banners all came from Subhan Printers. The colours are vivid, the quality is premium, and they delivered in 3 days.',
        'badge' => '🍴 Restaurant Branding'
    ]
];

// Why choose us data
$whyUs = [
    ['icon' => 'fa-home', 'title' => 'Everything In-House', 'desc' => 'No outsourcing. Design to delivery in our own Lahore facility. Faster, cheaper, better.', 'color' => 'linear-gradient(135deg,#8b5cf6,#6d28d9)'],
    ['icon' => 'fa-clock', 'title' => '24hr Rush Available', 'desc' => 'Same-day and 24-hour printing for urgent jobs. No extra charge on weekdays.', 'color' => 'linear-gradient(135deg,#f59e0b,#b45309)'],
    ['icon' => 'fa-shield-alt', 'title' => 'Quality Guaranteed', 'desc' => 'If your print doesn\'t match the approved proof, we reprint free of charge. Always.', 'color' => 'linear-gradient(135deg,#22c55e,#15803d)'],
    ['icon' => 'fa-truck', 'title' => 'Nationwide Delivery', 'desc' => 'TCS and Leopard delivery to all cities. Lahore same-day bike delivery available.', 'color' => 'linear-gradient(135deg,#3b82f6,#1d4ed8)'],
    ['icon' => 'fa-comments', 'title' => 'WhatsApp-First Service', 'desc' => 'Place orders, share files, get proofs, track delivery — all on WhatsApp. No friction.', 'color' => 'linear-gradient(135deg,#ec4899,#be185d)'],
    ['icon' => 'fa-coins', 'title' => 'Factory-Direct Pricing', 'desc' => 'No middlemen. You pay factory price with premium quality. Bulk discounts available.', 'color' => 'linear-gradient(135deg,#f59e0b,#8b5cf6)']
];
?>

<main>

    <!-- ═══ HERO ═══ -->
    <section id="about-hero" aria-label="About hero">
        <div class="hero-mesh" aria-hidden="true"></div>
        <div class="hero-lines" aria-hidden="true"></div>
        <div class="orb orb1" aria-hidden="true"></div>
        <div class="orb orb2" aria-hidden="true"></div>
        <div class="container hero-inner">

            <div class="reveal">
                <div class="hero-eyebrow">Our Story</div>
                <h1 class="hero-title">
                    Crafting <span class="highlight">Premium Prints</span><br>Since 1999
                </h1>
                <p class="hero-desc">
                    Subhan Printers is a family-run design and printing studio in the heart of Gawalmandi,
                    Lahore. For over 25 years we've combined traditional craftsmanship with cutting-edge
                    technology to deliver prints that make lasting impressions.
                </p>
                <div class="hero-badges">
                    <span class="hero-badge"><i class="fas fa-map-marker-alt" style="color:var(--clr-accent)"></i> Gawalmandi, Lahore</span>
                    <span class="hero-badge"><i class="fas fa-calendar" style="color:var(--clr-primary)"></i> Est. 1999</span>
                    <span class="hero-badge"><i class="fas fa-users" style="color:var(--clr-green)"></i> Family Business</span>
                    <span class="hero-badge"><i class="fas fa-truck" style="color:#3b82f6"></i> Nationwide Delivery</span>
                </div>
                <div style="display:flex;gap:14px;flex-wrap:wrap">
                    <a href="#our-story" class="btn btn-primary btn-lg">
                        Our Story <i class="fas fa-arrow-down"></i>
                    </a>
                    <a href="<?php echo base_url('contact'); ?>" class="btn btn-outline btn-lg">
                        Work With Us <i class="fas fa-arrow-right"></i>
                    </a>
                </div>
            </div>

            <div class="hero-img-wrap reveal reveal-d2">
                <img class="hero-main-img"
                     src="images/office1.png"
                     alt="Subhan Printers creative office and production facility in Gawalmandi Lahore"
                     onerror="this.src='https://placehold.co/680x520/1a1a2e/8b5cf6?text=Our+Studio'"/>
                <div class="hero-img-overlay"></div>
                <div class="hero-float-card">
                    <div class="hfc-num">25+</div>
                    <div class="hfc-label">Years of Excellence</div>
                </div>
                <div class="hero-float-badge">
                    ⭐ 4.9 / 5.0<br>Client Rating
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ ANIMATED STATS ═══ -->
    <section id="about-stats" aria-label="Key statistics">
        <div class="stats-grid">
            <?php foreach ($stats as $index => $stat): ?>
            <div class="stat-block reveal <?php echo $index > 0 ? 'reveal-d' . ($index + 1) : ''; ?>">
                <div class="stat-icon" style="background:linear-gradient(135deg,<?php echo $stat['color']; ?>,<?php echo $stat['color']; ?>80)"><i class="fas <?php echo $stat['icon']; ?>"></i></div>
                <div class="stat-num">
                    <?php if ($stat['num'] === '4.9'): ?>
                        4.<span class="counter" data-target="9">0</span>
                    <?php else: ?>
                        <span class="counter" data-target="<?php echo str_replace(',', '', $stat['num']); ?>">0</span><span class="stat-suffix"><?php echo $stat['suffix']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="stat-label"><?php echo $stat['label']; ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- ═══ OUR STORY ═══ -->
    <section id="our-story" class="section" aria-label="Our story">
    <div class="container story-grid">

        <div class="story-img-col reveal">
            <!-- Image 1 - Hover Effect -->
            <div class="story-image-wrapper">
                <img class="story-main-img story-img-default"
                     src="images/office2.png"
                     alt="Subhan Printers founder working on designs"
                     onerror="this.src='https://placehold.co/580x500/1a1a2e/8b5cf6?text=Our+Story'"/>
                <img class="story-main-img story-img-hover"
                     src="images/office3.png"
                     alt="Subhan Printers modern facility"
                     onerror="this.src='https://placehold.co/580x500/1a1a2e/f59e0b?text=Modern+Facility'"/>
            </div>
            
            <!-- Image 2 - Hover Effect -->
            <div class="story-image-accent-wrapper">
                <img class="story-img-accent story-img-accent-default"
                     src="images/portfolio1.png"
                     alt="Premium box packaging work"
                     onerror="this.src='https://placehold.co/200x200/1a1a2e/f59e0b?text=Our+Work'"/>
                <img class="story-img-accent story-img-accent-hover"
                     src="images/portfolio1-hover.jpg"
                     alt="Premium packaging collection"
                     onerror="this.src='https://placehold.co/200x200/1a1a2e/8b5cf6?text=Packaging+Work'"/>
            </div>
            
            <div class="story-year-badge">
                <div class="syb-num">1999</div>
                <div class="syb-label">Founded</div>
            </div>
        </div>

        <div class="story-content reveal reveal-d2">
            <div class="tag" style="margin-bottom:16px">Our Story</div>
            <h2 class="section-title">From a Small Shop<br>to <span class="highlight">Lahore's Trusted</span> Printer</h2>
            <p class="story-lead">It started with a single offset press, a dream, and an unwavering commitment to quality in the lanes of Gawalmandi.</p>
            <p class="story-para">Founded in 1999 by the Subhan family, we began with wedding card printing for local families in the Gawalmandi neighbourhood of Lahore. Word spread fast — not because of advertising, but because our quality spoke for itself.</p>
            <p class="story-para">Over the decades we expanded from a single room to a full manufacturing facility. Today we run in-house design, offset printing, digital printing, lamination, die-cutting, embossing, and packaging — all under one roof.</p>
            <p class="story-para">We've grown from printing 50 wedding cards a month to handling 10,000+ print jobs a year for businesses, brands, and families across Pakistan — but the family values that started it all remain unchanged.</p>
            <div class="story-highlights">
                <div class="sh-item">
                    <div class="sh-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)"><i class="fas fa-home"></i></div>
                    <div><div class="sh-title">In-House Manufacturing</div><div class="sh-desc">Design to delivery — everything done on-site in our Lahore facility</div></div>
                </div>
                <div class="sh-item">
                    <div class="sh-icon" style="background:linear-gradient(135deg,#f59e0b,#b45309)"><i class="fas fa-heart"></i></div>
                    <div><div class="sh-title">Family Values</div><div class="sh-desc">Every client is treated like a member of our family — with care and respect</div></div>
                </div>
                <div class="sh-item">
                    <div class="sh-icon" style="background:linear-gradient(135deg,#22c55e,#15803d)"><i class="fas fa-leaf"></i></div>
                    <div><div class="sh-title">Quality First, Always</div><div class="sh-desc">We never compromise on materials, inks, or craftsmanship — ever</div></div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- ═══ TIMELINE ═══ -->
    <section id="our-journey" class="section" aria-label="Our journey timeline">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Our Journey</div>
                <h2 class="section-title">25 Years of <span class="highlight">Growth</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">Key milestones that shaped who we are today</p>
            </div>
            <div class="timeline">
                <?php foreach ($timeline as $index => $item): ?>
                <div class="tl-item reveal <?php echo $index > 0 ? 'reveal-d' . ($index % 3 + 1) : ''; ?>">
                    <div class="tl-dot" style="background:<?php echo $item['dotColor']; ?>"><?php echo $item['dotText']; ?></div>
                    <div class="tl-year"><?php echo $item['year']; ?></div>
                    <div class="tl-title"><?php echo $item['title']; ?></div>
                    <div class="tl-desc"><?php echo $item['desc']; ?></div>
                    <span class="tl-tag"><i class="fas <?php echo $item['tagIcon']; ?>"></i> <?php echo $item['tag']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══ CORE VALUES ═══ -->
    <section id="our-values" class="section" aria-label="Our core values">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">What We Stand For</div>
                <h2 class="section-title">Our Core <span class="highlight">Values</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">The principles that guide every decision, every print, every client relationship</p>
            </div>
            <div class="values-grid">
                <?php foreach ($values as $index => $value): ?>
                <div class="value-card reveal <?php echo $index > 0 ? 'reveal-d' . ($index % 3 + 1) : ''; ?>">
                    <div class="vc-icon" style="background:<?php echo $value['color']; ?>"><i class="fas <?php echo $value['icon']; ?>"></i></div>
                    <div class="vc-title"><?php echo $value['title']; ?></div>
                    <div class="vc-desc"><?php echo $value['desc']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══ TEAM - 3 MEMBERS (Centered) ═══ -->
    <!-- ═══ TEAM - IMPROVED 3 MEMBERS ═══ -->
<section id="our-team" class="section" aria-label="Our team">
    <div class="container">
        <div class="text-center reveal">
            <div class="tag">
                <i class="fas fa-users" style="margin-right:6px;"></i> The People Behind the Prints
            </div>
            <h2 class="section-title">Meet Our <span class="highlight">Leadership Team</span></h2>
            <div class="divider-center"></div>
            <p class="section-desc" style="max-width: 640px;">
                Meet the dedicated professionals who bring your printing projects to life with passion and precision
            </p>
        </div>
        
        <div class="team-grid team-grid-3">
            <?php foreach ($teamMembers as $index => $member): ?>
            <div class="team-card reveal <?php echo $index > 0 ? 'reveal-d' . ($index % 3 + 1) : ''; ?>">
                <div class="team-img-wrap">
                    <img class="team-img" 
                         src="<?php echo $member['avatar']; ?>" 
                         alt="<?php echo $member['name']; ?>" 
                         loading="lazy"
                         onerror="this.src='<?php echo $member['fallback']; ?>'"/>
                    <div class="team-img-overlay"></div>
                    <div class="team-social">
                        <?php foreach ($member['social'] as $platform => $url): ?>
                        <a href="<?php echo $url; ?>" aria-label="<?php echo ucfirst($platform); ?>" class="team-social-link">
                            <i class="fab fa-<?php echo $platform; ?>"></i>
                        </a>
                        <?php endforeach; ?>
                    </div>
                    <div class="team-experience-badge">
                        <span>⭐ <?php echo $member['experience'] ?? '10+'; ?> Years</span>
                    </div>
                </div>
                <div class="team-body">
                    <div class="team-name"><?php echo $member['name']; ?></div>
                    <div class="team-role"><?php echo $member['role']; ?></div>
                    <div class="team-desc"><?php echo $member['desc']; ?></div>
                    <div class="team-tags">
                        <?php foreach ($member['tags'] as $tag): ?>
                        <span class="team-tag"><?php echo $tag; ?></span>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

    <!-- ═══ AWARDS ═══ -->
    <section id="awards" class="section" aria-label="Awards and certifications">
        <div class="container">
            <div class="text-center reveal">
                <div class="tag">Recognition</div>
                <h2 class="section-title">Awards &amp; <span class="highlight">Achievements</span></h2>
                <div class="divider-center"></div>
                <p class="section-desc">Recognition of our commitment to quality and service over 25+ years</p>
            </div>
            <div class="awards-grid">
                <?php foreach ($awards as $index => $award): ?>
                <div class="award-card reveal <?php echo $index > 0 ? 'reveal-d' . ($index % 3 + 1) : ''; ?>">
                    <span class="award-icon"><?php echo $award['icon']; ?></span>
                    <div class="award-title"><?php echo $award['title']; ?></div>
                    <div class="award-desc"><?php echo $award['desc']; ?></div>
                    <span class="award-year"><?php echo $award['year']; ?></span>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══ CLIENTS MARQUEE ═══ -->
   <section id="clients" class="section" aria-label="Our Clients">
    <div class="container">
        <div class="text-center reveal">
            <div class="tag">Trusted Partners</div>
            <h2 class="section-title">Our <span class="highlight">Clients</span></h2>
            <p class="section-desc">We are proud to serve these amazing brands and businesses</p>
        </div>
    </div>

    <!-- Row 1 - 14 Logos -->
    <div class="clients-row">
        <div class="clients-marquee-wrapper">
            <div class="clients-marquee-track">
                <?php 
                // Row 1 - 14 Logos with manual image paths
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
                
                // Loop 3 times for continuous scroll
                for ($loop = 0; $loop < 3; $loop++): 
                    foreach ($clientLogos as $logo): 
                ?>
                <div class="client-logo-item">
                    <img src="<?php echo $logo['image']; ?>" 
                         alt="<?php echo htmlspecialchars($logo['name']); ?>" 
                         loading="lazy"
                         onerror="this.style.display='none';this.parentElement.querySelector('.client-logo-text').style.display='block';" />
                    <span class="client-logo-text" style="display:none;"><?php echo htmlspecialchars($logo['name']); ?></span>
                </div>
                <?php 
                    endforeach; 
                endfor; 
                ?>
            </div>
        </div>
    </div>

    <!-- Row 2 - 14 Logos -->
    <div class="clients-row" style="margin-top:20px;">
        <div class="clients-marquee-wrapper">
            <div class="clients-marquee-track rev">
                <?php 
                // Row 2 - 14 Logos with manual image paths
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
                
                // Loop 3 times for continuous scroll
                for ($loop = 0; $loop < 3; $loop++): 
                    foreach ($clientLogos2 as $logo): 
                ?>
                <div class="client-logo-item">
                    <img src="<?php echo $logo['image']; ?>" 
                         alt="<?php echo htmlspecialchars($logo['name']); ?>" 
                         loading="lazy"
                         onerror="this.style.display='none';this.parentElement.querySelector('.client-logo-text').style.display='block';" />
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

    <!-- ═══ TESTIMONIALS ═══ -->
    <section id="about-testimonials" class="section" aria-label="Client testimonials">
        <div class="t-blob tb1" aria-hidden="true"></div>
        <div class="t-blob tb2" aria-hidden="true"></div>
        <div class="container" style="position:relative;z-index:1">
            <div class="text-center reveal">
                <div class="tag" style="color:rgba(255,255,255,.7);background:rgba(255,255,255,.08);border-color:rgba(255,255,255,.15)">
                    <i class="fas fa-star" style="color:var(--clr-accent)"></i> Client Stories
                </div>
                <h2 class="section-title">What They <span class="highlight">Say About Us</span></h2>
                <div class="divider-center"></div>
            </div>
            
            <!-- Featured Review -->
            <div class="featured-review reveal">
                <div class="fr-inner">
                    <img class="fr-avatar" src="<?php echo $reviews[0]['avatar']; ?>" alt="<?php echo $reviews[0]['name']; ?>" loading="lazy"/>
                    <div>
                        <div class="fr-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                        <p class="fr-quote"><?php echo $reviews[0]['text']; ?></p>
                        <p class="fr-name"><?php echo $reviews[0]['name']; ?></p>
                        <p class="fr-role"><?php echo $reviews[0]['role']; ?></p>
                    </div>
                </div>
            </div>
            
            <!-- Reviews Grid -->
            <div class="reviews-mini-grid">
                <?php foreach (array_slice($reviews, 1) as $index => $review): ?>
                <div class="rm-card reveal <?php echo $index > 0 ? 'reveal-d' . ($index * 2 + 2) : ''; ?>">
                    <div class="rm-head">
                        <img class="rm-avatar" src="<?php echo $review['avatar']; ?>" alt="<?php echo $review['name']; ?>" loading="lazy"/>
                        <div>
                            <div class="rm-name"><?php echo $review['name']; ?></div>
                            <div class="rm-role"><?php echo $review['role']; ?></div>
                        </div>
                    </div>
                    <div class="rm-stars"><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i></div>
                    <p class="rm-text"><?php echo $review['text']; ?></p>
                    <div class="rm-badge"><?php echo $review['badge']; ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ═══ WHY CHOOSE US ═══ -->
    <section id="why-us" class="section" aria-label="Why choose us">
        <div class="container">
            <div class="why-layout">
                <div class="why-content reveal">
                    <div class="tag" style="margin-bottom:16px">Why Choose Us</div>
                    <h2 class="section-title">The <span class="highlight">Subhan Printers</span> Difference</h2>
                    <p class="section-desc" style="margin-bottom:0">We don't just print — we partner with you to make your brand look its absolute best.</p>
                    <div class="why-grid">
                        <?php foreach ($whyUs as $item): ?>
                        <div class="why-card">
                            <div class="why-icon" style="background:<?php echo $item['color']; ?>"><i class="fas <?php echo $item['icon']; ?>"></i></div>
                            <div class="why-title"><?php echo $item['title']; ?></div>
                            <div class="why-desc"><?php echo $item['desc']; ?></div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="why-img-col reveal reveal-d2">
                    <img class="why-main-img"
                         src="images/office5.png"
                         alt="Our professional team at work"
                         onerror="this.src='https://placehold.co/580x520/1a1a2e/8b5cf6?text=Our+Team'"/>
                    <div class="why-float-stats">
                        <div class="wfs-card">
                            <div class="wfs-num">98%</div>
                            <div class="wfs-label">Client Satisfaction Rate</div>
                        </div>
                        <div class="wfs-card">
                            <div class="wfs-num">30+</div>
                            <div class="wfs-label">Team Members</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ═══ CTA ═══ -->
    <section id="about-cta" aria-label="Call to action">
        <div class="cta-blob cb1" aria-hidden="true"></div>
        <div class="cta-blob cb2" aria-hidden="true"></div>
        <div class="container cta-inner">
            <div class="reveal">
                <div class="tag" style="color:rgba(255,255,255,.8);background:rgba(255,255,255,.1);border-color:rgba(255,255,255,.2);margin-bottom:20px">Ready to Print?</div>
                <div class="cta-title">Let's Create Something<br><span class="gold">Extraordinary Together</span></div>
                <p class="cta-desc">Join 5,000+ satisfied clients who trust Subhan Printers with their most important print work.</p>
                <div class="cta-btns">
                    <a href="https://wa.me/923004197033" target="_blank" rel="noopener" class="btn btn-green btn-lg">
                        <i class="fab fa-whatsapp"></i> Start on WhatsApp
                    </a>
                    <a href="<?php echo base_url('services'); ?>" class="btn btn-lg" style="background:rgba(255,255,255,.15);color:#fff;border:1.5px solid rgba(255,255,255,.3)">
                        <i class="fas fa-layer-group"></i> View Services
                    </a>
                    <a href="<?php echo base_url('portfolio'); ?>" class="btn btn-lg" style="background:rgba(255,255,255,.1);color:#fff;border:1.5px solid rgba(255,255,255,.2)">
                        <i class="fas fa-images"></i> See Our Work
                    </a>
                </div>
                <div class="cta-trust">
                    <div class="cta-trust-item"><i class="fas fa-shield-alt"></i> 100% Satisfaction Guarantee</div>
                    <div class="cta-trust-item"><i class="fas fa-redo"></i> Free Reprints on Our Error</div>
                    <div class="cta-trust-item"><i class="fas fa-truck"></i> Nationwide Delivery</div>
                    <div class="cta-trust-item"><i class="fas fa-star"></i> 4.9/5 Rating</div>
                </div>
            </div>
        </div>
    </section>

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
    .client-logo-item {
        min-width: 130px;
        height: 70px;
        padding: 10px 16px;
    }
    .client-logo-item img {
        max-height: 42px;
    }
    .clients-marquee-track {
        gap: 20px;
        animation: scrollClients 22s linear infinite;
    }
    .clients-marquee-track.rev {
        animation: scrollClientsRev 22s linear infinite;
    }
}

@media (max-width: 992px) {
    .client-logo-item {
        min-width: 110px;
        height: 60px;
        padding: 8px 12px;
    }
    .client-logo-item img {
        max-height: 35px;
    }
    .clients-marquee-track {
        gap: 16px;
        animation: scrollClients 18s linear infinite;
    }
    .clients-marquee-track.rev {
        animation: scrollClientsRev 18s linear infinite;
    }
    .clients-row::before,
    .clients-row::after {
        width: 50px;
    }
}

@media (max-width: 768px) {
    .client-logo-item {
        min-width: 90px;
        height: 50px;
        padding: 6px 10px;
    }
    .client-logo-item img {
        max-height: 30px;
    }
    .clients-marquee-track {
        gap: 12px;
        animation: scrollClients 15s linear infinite;
    }
    .clients-marquee-track.rev {
        animation: scrollClientsRev 15s linear infinite;
    }
    .clients-row::before,
    .clients-row::after {
        width: 30px;
    }
}

@media (max-width: 480px) {
    .client-logo-item {
        min-width: 70px;
        height: 40px;
        padding: 4px 8px;
    }
    .client-logo-item img {
        max-height: 24px;
    }
    .clients-marquee-track {
        gap: 8px;
        animation: scrollClients 12s linear infinite;
    }
    .clients-marquee-track.rev {
        animation: scrollClientsRev 12s linear infinite;
    }
    .client-logo-text {
        font-size: 0.6rem;
    }
    .clients-row::before,
    .clients-row::after {
        width: 20px;
    }
}
/* ============================================================
   OUR STORY - IMAGE HOVER EFFECT
   ============================================================ */

/* Main Image Container */
.story-image-wrapper {
    position: relative;
    width: 100%;
    height: 500px;
    border-radius: var(--radius-xl);
    overflow: hidden;
    cursor: pointer;
}

/* Both images positioned absolute */
.story-main-img {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: var(--radius-xl);
    transition: opacity 0.6s ease, transform 0.6s ease;
}

/* Default image visible by default */
.story-img-default {
    opacity: 1;
    z-index: 1;
}

/* Hover image hidden by default */
.story-img-hover {
    opacity: 0;
    z-index: 2;
}

/* On hover: show hover image, hide default */
.story-image-wrapper:hover .story-img-default {
    opacity: 0;
    transform: scale(1.1);
}

.story-image-wrapper:hover .story-img-hover {
    opacity: 1;
    transform: scale(1.05);
}

/* ============================================================
   ACCENT IMAGE (Small Image) - Hover Effect
   ============================================================ */

/* Accent Image Container */
.story-image-accent-wrapper {
    position: absolute;
    bottom: -20px;
    right: -20px;
    width: 200px;
    height: 200px;
    border-radius: var(--radius-lg);
    overflow: hidden;
    border: 4px solid var(--clr-bg);
    box-shadow: var(--shadow-md);
    cursor: pointer;
    z-index: 3;
}

.story-img-accent {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: opacity 0.6s ease, transform 0.6s ease;
}

.story-img-accent-default {
    opacity: 1;
    z-index: 1;
}

.story-img-accent-hover {
    opacity: 0;
    z-index: 2;
}

.story-image-accent-wrapper:hover .story-img-accent-default {
    opacity: 0;
    transform: scale(1.15);
}

.story-image-accent-wrapper:hover .story-img-accent-hover {
    opacity: 1;
    transform: scale(1.1);
}

/* ============================================================
   RESPONSIVE
   ============================================================ */
@media (max-width: 900px) {
    .story-image-wrapper {
        height: 400px;
    }
    
    .story-image-accent-wrapper {
        width: 150px;
        height: 150px;
        bottom: -15px;
        right: -15px;
    }
}

@media (max-width: 600px) {
    .story-image-wrapper {
        height: 300px;
    }
    
    .story-image-accent-wrapper {
        width: 120px;
        height: 120px;
        bottom: -10px;
        right: -10px;
    }
}
</style>
</main>

<?php
// ============================================================
// FOOTER - Include footer
// ============================================================
require_once __DIR__ . '/../templates/footer.php';
?>
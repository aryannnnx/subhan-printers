<?php 
require_once 'includes/header.php'; 

// Get statistics from database
$stats_query = "SELECT 
    (SELECT COUNT(*) FROM users WHERE role = 'user') as happy_clients,
    (SELECT COUNT(*) FROM orders WHERE status = 'completed') as projects_done
    FROM DUAL";
$stats_result = mysqli_query($conn, $stats_query);
$stats = mysqli_fetch_assoc($stats_result);

$happy_clients = number_format($stats['happy_clients'] ?? 5000);
$projects_done = number_format($stats['projects_done'] ?? 10000);
?>

<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Subhan Printers Services – Wedding cards, box packaging, flex banners, brochures, stickers, graphic design and more in Lahore, Pakistan." />
  <title>Services | Subhan Printers – Lahore</title>

  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

  <style>
    /* ============================================================
       DESIGN TOKENS — identical to index.html
       ============================================================ */
    :root {
      --clr-bg:        #0d0d14;
      --clr-surface:   #13131f;
      --clr-surface-2: #1a1a2e;
      --clr-border:    rgba(255,255,255,0.08);
      --clr-text:      #e8e8f0;
      --clr-muted:     #8888aa;
      --clr-primary:   #8b5cf6;
      --clr-primary-2: #6d28d9;
      --clr-accent:    #f59e0b;
      --clr-accent-2:  #d97706;
      --clr-green:     #22c55e;
      --clr-white:     #ffffff;
      --radius-sm:     8px;
      --radius-md:     16px;
      --radius-lg:     24px;
      --radius-xl:     32px;
      --shadow-sm:     0 2px 8px rgba(0,0,0,0.3);
      --shadow-md:     0 8px 30px rgba(0,0,0,0.4);
      --shadow-lg:     0 20px 60px rgba(0,0,0,0.5);
      --shadow-glow:   0 0 40px rgba(139,92,246,0.3);
      --font-display:  'Playfair Display', Georgia, serif;
      --font-body:     'DM Sans', system-ui, sans-serif;
      --transition:    0.3s cubic-bezier(0.4,0,0.2,1);
      --nav-h:         72px;
    }

    /* ============================================================
       RESET & BASE
       ============================================================ */
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    html { scroll-behavior: smooth; }
    body {
      font-family: var(--font-body);
      background: var(--clr-bg);
      color: var(--clr-text);
      line-height: 1.6;
      overflow-x: hidden;
    }
    img { max-width: 100%; height: auto; display: block; }
    a   { text-decoration: none; color: inherit; }
    ul  { list-style: none; }
    button { cursor: pointer; border: none; background: none; font-family: inherit; }

    /* ============================================================
       UTILITIES
       ============================================================ */
    .container    { width: 100%; max-width: 1280px; margin: 0 auto; padding: 0 24px; }
    .section      { padding: 96px 0; }
    .text-center  { text-align: center; }

    .tag {
      display: inline-flex; align-items: center; gap: 6px;
      font-size: 0.75rem; font-weight: 600; letter-spacing: 0.1em;
      text-transform: uppercase; color: var(--clr-primary);
      background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2);
      padding: 6px 14px; border-radius: 100px;
    }
    .section-title {
      font-family: var(--font-display);
      font-size: clamp(2rem, 4vw, 3rem);
      font-weight: 800; line-height: 1.15;
      color: var(--clr-white); margin: 12px 0 16px;
    }
    .section-title .highlight { color: var(--clr-primary); }
    .section-title .gold      { color: var(--clr-accent); }
    .section-desc {
      color: var(--clr-muted); font-size: 1.05rem;
      max-width: 600px; line-height: 1.75;
    }
    .text-center .section-desc { margin: 0 auto; }

    .btn {
      display: inline-flex; align-items: center; gap: 8px;
      padding: 14px 28px; border-radius: 100px;
      font-weight: 600; font-size: 0.95rem;
      transition: var(--transition); white-space: nowrap; cursor: pointer;
      font-family: var(--font-body);
    }
    .btn-primary {
      background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-2));
      color: #fff; box-shadow: 0 4px 20px rgba(139,92,246,0.4);
    }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(139,92,246,0.6); }
    .btn-outline {
      border: 2px solid rgba(255,255,255,0.2); color: var(--clr-text);
    }
    .btn-outline:hover { border-color: var(--clr-primary); color: var(--clr-primary); }
    .btn-accent {
      background: linear-gradient(135deg, var(--clr-accent), var(--clr-accent-2));
      color: #111; box-shadow: 0 4px 20px rgba(245,158,11,0.3);
    }
    .btn-accent:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(245,158,11,0.5); }
    .btn-green {
      background: linear-gradient(135deg, #22c55e, #16a34a);
      color: #fff; box-shadow: 0 4px 20px rgba(34,197,94,0.3);
    }
    .btn-green:hover { transform: translateY(-2px); box-shadow: 0 8px 30px rgba(34,197,94,0.5); }
    .btn-lg { padding: 18px 36px; font-size: 1rem; }

    .divider-center {
      width: 60px; height: 3px; margin: 16px auto;
      background: linear-gradient(90deg, var(--clr-primary), var(--clr-accent));
      border-radius: 2px;
    }

    /* reveal animation */
    .reveal {
      opacity: 0; transform: translateY(28px);
      transition: opacity 0.65s ease, transform 0.65s ease;
    }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-delay-1 { transition-delay: 0.1s; }
    .reveal-delay-2 { transition-delay: 0.2s; }
    .reveal-delay-3 { transition-delay: 0.3s; }
    .reveal-delay-4 { transition-delay: 0.4s; }

    /* Additional utility classes */
    .text-black { color: #111111; }

    /* ============================================================
       PAGE HERO — services hero banner
       ============================================================ */
    #svc-hero {
      position: relative; padding: calc(var(--nav-h) + 80px) 0 80px;
      overflow: hidden; background: var(--clr-bg);
    }
    .svc-hero-bg {
      position: absolute; inset: 0; z-index: 0;
      background:
        radial-gradient(ellipse 70% 60% at 10% 50%, rgba(139,92,246,0.18) 0%, transparent 55%),
        radial-gradient(ellipse 50% 50% at 90% 80%, rgba(245,158,11,0.1) 0%, transparent 55%);
    }
    .svc-hero-grid {
      position: absolute; inset: 0; z-index: 0; opacity: 0.025;
      background-image:
        linear-gradient(rgba(255,255,255,1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(255,255,255,1) 1px, transparent 1px);
      background-size: 48px 48px;
    }
    .svc-hero-inner {
      position: relative; z-index: 1;
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 64px; align-items: center;
    }
    .svc-hero-eyebrow {
      display: inline-flex; align-items: center; gap: 8px;
      font-size: 0.78rem; font-weight: 600; letter-spacing: 0.12em;
      text-transform: uppercase; color: var(--clr-accent); margin-bottom: 16px;
    }
    .svc-hero-eyebrow::before {
      content: ''; width: 28px; height: 2px;
      background: var(--clr-accent); border-radius: 2px;
    }
    .svc-hero-title {
      font-family: var(--font-display);
      font-size: clamp(2.2rem, 4.5vw, 3.8rem);
      font-weight: 800; line-height: 1.1;
      color: var(--clr-white); margin-bottom: 20px;
    }
    .svc-hero-title span { color: var(--clr-primary); }
    .svc-hero-desc {
      font-size: 1.05rem; color: var(--clr-muted);
      line-height: 1.8; margin-bottom: 32px; max-width: 500px;
    }
    .svc-hero-actions { display: flex; gap: 14px; flex-wrap: wrap; }

    /* Hero right — stat cards */
    .svc-hero-stats {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 16px;
    }
    .svc-stat-card {
      background: var(--clr-surface);
      border: 1px solid var(--clr-border);
      border-radius: var(--radius-lg);
      padding: 28px 24px; text-align: center;
      transition: var(--transition);
    }
    .svc-stat-card:hover {
      border-color: rgba(139,92,246,0.4);
      transform: translateY(-4px);
      box-shadow: var(--shadow-glow);
    }
    .svc-stat-card:nth-child(1) { border-top: 3px solid var(--clr-primary); }
    .svc-stat-card:nth-child(2) { border-top: 3px solid var(--clr-accent); }
    .svc-stat-card:nth-child(3) { border-top: 3px solid var(--clr-green); }
    .svc-stat-card:nth-child(4) { border-top: 3px solid #3b82f6; }
    .svc-stat-num {
      font-family: var(--font-display);
      font-size: 2.2rem; font-weight: 800; color: var(--clr-white);
      margin-bottom: 6px;
    }
    .svc-stat-label { font-size: 0.82rem; color: var(--clr-muted); }

    /* ============================================================
       CATEGORY TABS FILTER
       ============================================================ */
    #svc-tabs { background: var(--clr-surface); position: sticky; top: var(--nav-h); z-index: 100; }
    .svc-tabs-inner {
      display: flex; gap: 4px; padding: 12px 0;
      overflow-x: auto; -ms-overflow-style: none; scrollbar-width: none;
    }
    .svc-tabs-inner::-webkit-scrollbar { display: none; }
    .svc-tab {
      display: flex; align-items: center; gap: 8px;
      padding: 10px 20px; border-radius: 100px; white-space: nowrap;
      font-size: 0.85rem; font-weight: 600; cursor: pointer;
      border: 1.5px solid transparent; color: var(--clr-muted);
      transition: var(--transition); flex-shrink: 0;
    }
    .svc-tab:hover { color: var(--clr-white); border-color: rgba(139,92,246,0.3); }
    .svc-tab.active {
      background: var(--clr-primary);
      color: #fff; border-color: var(--clr-primary);
      box-shadow: 0 0 20px rgba(139,92,246,0.4);
    }
    .svc-tab i { font-size: 0.8rem; }

    /* ============================================================
       MAIN SERVICES GRID
       ============================================================ */
    #svc-main { background: var(--clr-bg); }
    .svc-section-header { margin-bottom: 48px; }

    .svc-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 24px;
    }

    /* service card */
    .svc-card {
      background: var(--clr-surface);
      border: 1px solid var(--clr-border);
      border-radius: var(--radius-xl);
      overflow: hidden; transition: var(--transition);
      display: flex; flex-direction: column;
    }
    .svc-card:hover {
      border-color: rgba(139,92,246,0.4);
      transform: translateY(-6px);
      box-shadow: var(--shadow-glow);
    }
    .svc-card-img-wrap {
      position: relative; overflow: hidden;
      height: 220px;
    }
    .svc-card-img {
      width: 100%; height: 100%; object-fit: cover;
      display: block; transition: transform 0.5s ease;
    }
    .svc-card:hover .svc-card-img { transform: scale(1.06); }
    .svc-card-badge {
      position: absolute; top: 14px; left: 14px;
      background: rgba(13,13,20,0.85); backdrop-filter: blur(8px);
      border: 1px solid var(--clr-border);
      color: var(--clr-accent); font-size: 0.72rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: 0.08em;
      padding: 5px 12px; border-radius: 100px;
    }
    .svc-card-popular {
      position: absolute; top: 14px; right: 14px;
      background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-2));
      color: #fff; font-size: 0.72rem; font-weight: 700;
      padding: 5px 12px; border-radius: 100px;
    }
    .svc-card-body {
      padding: 24px; flex: 1;
      display: flex; flex-direction: column; gap: 12px;
    }
    .svc-card-icon {
      width: 48px; height: 48px; border-radius: var(--radius-sm);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.2rem; color: #fff; flex-shrink: 0;
      margin-bottom: 4px;
    }
    .svc-card-title {
      font-weight: 700; color: var(--clr-white); font-size: 1.1rem;
    }
    .svc-card-desc {
      font-size: 0.875rem; color: var(--clr-muted); line-height: 1.7;
      flex: 1;
    }
    .svc-card-features {
      display: flex; flex-direction: column; gap: 6px;
    }
    .svc-card-feature {
      display: flex; align-items: center; gap: 8px;
      font-size: 0.82rem; color: var(--clr-muted);
    }
    .svc-card-feature i { color: var(--clr-green); font-size: 0.7rem; }
    .svc-card-footer {
      padding: 16px 24px; border-top: 1px solid var(--clr-border);
      display: flex; justify-content: space-between; align-items: center;
    }
    .svc-card-price {
      font-family: var(--font-display);
      font-size: 1rem; font-weight: 700; color: var(--clr-primary);
    }
    .svc-card-price span { font-size: 0.78rem; color: var(--clr-muted); font-family: var(--font-body); font-weight: 400; }
    .svc-card-cta {
      display: inline-flex; align-items: center; gap: 6px;
      padding: 9px 20px; border-radius: 100px;
      font-size: 0.82rem; font-weight: 700; color: #fff;
      background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-2));
      transition: var(--transition);
    }
    .svc-card-cta:hover { box-shadow: 0 6px 20px rgba(139,92,246,0.5); transform: translateY(-1px); }

    /* featured / wide card */
    .svc-card-wide {
      grid-column: span 2;
      flex-direction: row;
    }
    .svc-card-wide .svc-card-img-wrap {
      width: 360px; height: auto; flex-shrink: 0;
      border-radius: var(--radius-xl) 0 0 var(--radius-xl);
    }
    .svc-card-wide .svc-card-img { height: 100%; }
    .svc-card-wide .svc-card-body { padding: 32px; }

    /* ============================================================
       CATEGORY SECTIONS (grouped)
       ============================================================ */
    .cat-section { margin-bottom: 80px; }
    .cat-section:last-child { margin-bottom: 0; }
    .cat-section-head {
      display: flex; align-items: center; gap: 16px;
      margin-bottom: 32px; padding-bottom: 20px;
      border-bottom: 1px solid var(--clr-border);
    }
    .cat-section-icon {
      width: 52px; height: 52px; border-radius: var(--radius-md);
      display: flex; align-items: center; justify-content: center;
      font-size: 1.3rem; color: #fff; flex-shrink: 0;
    }
    .cat-section-title {
      font-family: var(--font-display);
      font-size: 1.6rem; font-weight: 800; color: var(--clr-white);
    }
    .cat-section-count {
      margin-left: auto; font-size: 0.8rem; color: var(--clr-muted);
      background: var(--clr-surface-2); border: 1px solid var(--clr-border);
      padding: 4px 12px; border-radius: 100px;
    }

    /* ============================================================
       PROCESS / HOW IT WORKS STRIP
       ============================================================ */
    #svc-process { background: var(--clr-surface); }
    .process-strip {
      display: grid; grid-template-columns: repeat(5, 1fr);
      gap: 0; position: relative;
    }
    .process-strip::before {
      content: ''; position: absolute;
      top: 32px; left: 10%; right: 10%; height: 2px;
      background: linear-gradient(90deg, var(--clr-primary), var(--clr-accent));
      z-index: 0;
    }
    .ps-step {
      text-align: center; padding: 0 16px;
      position: relative; z-index: 1;
    }
    .ps-num {
      width: 64px; height: 64px; border-radius: 50%; margin: 0 auto 16px;
      display: flex; align-items: center; justify-content: center;
      font-family: var(--font-display); font-size: 1.4rem; font-weight: 800;
      color: #fff; border: 3px solid var(--clr-bg);
      transition: var(--transition);
    }
    .ps-step:hover .ps-num { transform: scale(1.1); box-shadow: var(--shadow-glow); }
    .ps-icon { font-size: 1.4rem; margin-bottom: 12px; }
    .ps-title { font-weight: 700; color: var(--clr-white); margin-bottom: 6px; font-size: 0.95rem; }
    .ps-desc  { font-size: 0.8rem; color: var(--clr-muted); line-height: 1.6; }

    /* ============================================================
       PRICING TABLE
       ============================================================ */
    #svc-pricing { background: var(--clr-bg); }
    .pricing-grid {
      display: grid; grid-template-columns: repeat(3, 1fr);
      gap: 24px; margin-top: 48px; align-items: start;
    }
    .pricing-card {
      background: var(--clr-surface);
      border: 1px solid var(--clr-border);
      border-radius: var(--radius-xl); overflow: hidden;
      transition: var(--transition);
    }
    .pricing-card:hover { transform: translateY(-6px); box-shadow: var(--shadow-md); }
    .pricing-card.featured {
      border-color: var(--clr-primary);
      box-shadow: var(--shadow-glow);
      transform: scale(1.03);
    }
    .pricing-card.featured:hover { transform: scale(1.03) translateY(-6px); }
    .pricing-head {
      padding: 32px 28px; text-align: center;
      border-bottom: 1px solid var(--clr-border);
    }
    .pricing-card.featured .pricing-head {
      background: linear-gradient(135deg, rgba(139,92,246,0.15), rgba(109,40,217,0.1));
    }
    .pricing-badge {
      display: inline-block; padding: 4px 14px; border-radius: 100px;
      font-size: 0.72rem; font-weight: 700; text-transform: uppercase;
      letter-spacing: 0.08em; margin-bottom: 12px;
    }
    .pricing-badge.popular {
      background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-2));
      color: #fff;
    }
    .pricing-badge.basic  { background: var(--clr-surface-2); color: var(--clr-muted); border: 1px solid var(--clr-border); }
    .pricing-badge.pro    { background: rgba(245,158,11,0.15); color: var(--clr-accent); border: 1px solid rgba(245,158,11,0.3); }
    .pricing-name  { font-weight: 700; color: var(--clr-white); font-size: 1.2rem; margin-bottom: 8px; }
    .pricing-price {
      font-family: var(--font-display);
      font-size: 2.4rem; font-weight: 800; color: var(--clr-white);
      margin-bottom: 4px;
    }
    .pricing-price sup { font-size: 1rem; vertical-align: super; }
    .pricing-period { font-size: 0.82rem; color: var(--clr-muted); }
    .pricing-body { padding: 28px; }
    .pricing-feature {
      display: flex; align-items: flex-start; gap: 10px;
      font-size: 0.875rem; color: var(--clr-muted); padding: 8px 0;
      border-bottom: 1px solid var(--clr-border);
    }
    .pricing-feature:last-child { border-bottom: none; }
    .pricing-feature i { margin-top: 2px; flex-shrink: 0; }
    .pricing-feature.included i { color: var(--clr-green); }
    .pricing-feature.excluded { opacity: 0.45; }
    .pricing-feature.excluded i { color: var(--clr-muted); }
    .pricing-feature.included span { color: var(--clr-text); }
    .pricing-foot { padding: 20px 28px; border-top: 1px solid var(--clr-border); }
    .pricing-foot .btn { width: 100%; justify-content: center; }

    /* ============================================================
       MATERIALS / PAPER TYPES
       ============================================================ */
    #svc-materials { background: var(--clr-surface); }
    .materials-grid {
      display: grid; grid-template-columns: repeat(4, 1fr);
      gap: 16px; margin-top: 48px;
    }
    .mat-card {
      background: var(--clr-surface-2);
      border: 1px solid var(--clr-border);
      border-radius: var(--radius-lg); padding: 24px;
      text-align: center; transition: var(--transition); cursor: default;
    }
    .mat-card:hover { border-color: rgba(139,92,246,0.4); transform: translateY(-4px); }
    .mat-icon {
      width: 56px; height: 56px; border-radius: var(--radius-md);
      background: linear-gradient(135deg, var(--clr-primary), var(--clr-primary-2));
      display: flex; align-items: center; justify-content: center;
      font-size: 1.4rem; color: #fff; margin: 0 auto 16px;
    }
    .mat-title { font-weight: 700; color: var(--clr-white); margin-bottom: 6px; }
    .mat-desc  { font-size: 0.8rem; color: var(--clr-muted); line-height: 1.65; }
    .mat-tags  { display: flex; flex-wrap: wrap; gap: 6px; justify-content: center; margin-top: 12px; }
    .mat-tag   {
      font-size: 0.7rem; font-weight: 600;
      background: rgba(139,92,246,0.1); border: 1px solid rgba(139,92,246,0.2);
      color: var(--clr-primary); padding: 3px 10px; border-radius: 100px;
    }

    /* ============================================================
       FAQ ACCORDION
       ============================================================ */
    #svc-faq { background: var(--clr-bg); }
    .faq-grid {
      display: grid; grid-template-columns: 1fr 1fr;
      gap: 16px; margin-top: 48px;
    }
    .faq-item {
      background: var(--clr-surface);
      border: 1px solid var(--clr-border);
      border-radius: var(--radius-lg); overflow: hidden;
      transition: var(--transition);
    }
    .faq-item.open { border-color: rgba(139,92,246,0.4); }
    .faq-question {
      display: flex; justify-content: space-between; align-items: center;
      gap: 16px; padding: 20px 24px; cursor: pointer;
      font-weight: 600; color: var(--clr-white); font-size: 0.95rem;
      transition: var(--transition); width: 100%; text-align: left;
    }
    .faq-question:hover { color: var(--clr-primary); }
    .faq-icon {
      width: 28px; height: 28px; border-radius: 50%; flex-shrink: 0;
      background: rgba(139,92,246,0.15); border: 1px solid rgba(139,92,246,0.3);
      display: flex; align-items: center; justify-content: center;
      font-size: 0.75rem; color: var(--clr-primary);
      transition: transform var(--transition);
    }
    .faq-item.open .faq-icon { transform: rotate(45deg); background: var(--clr-primary); color: #fff; }
    .faq-answer {
      max-height: 0; overflow: hidden;
      transition: max-height 0.35s ease, padding 0.25s ease;
    }
    .faq-item.open .faq-answer { max-height: 300px; }
    .faq-answer p {
      padding: 0 24px 20px;
      font-size: 0.875rem; color: var(--clr-muted); line-height: 1.75;
    }

    /* ============================================================
       CTA BANNER
       ============================================================ */
    #svc-cta {
      background: linear-gradient(135deg, var(--clr-primary) 0%, #4c1d95 50%, #1e3a8a 100%);
      position: relative; overflow: hidden;
    }
    .svc-cta-blob {
      position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.2;
    }
    .svc-cta-blob-1 { width: 350px; height: 350px; top: -100px; left: -80px; background: #fff; }
    .svc-cta-blob-2 { width: 250px; height: 250px; bottom: -80px; right: -60px; background: var(--clr-accent); }
    .svc-cta-inner {
      position: relative; z-index: 1; text-align: center; padding: 96px 24px;
    }
    .svc-cta-title {
      font-family: var(--font-display);
      font-size: clamp(1.8rem, 3.5vw, 2.8rem);
      font-weight: 800; color: #fff; margin-bottom: 16px; line-height: 1.2;
    }
    .svc-cta-desc { color: rgba(255,255,255,0.8); font-size: 1.05rem; margin-bottom: 36px; }
    .svc-cta-btns { display: flex; gap: 16px; justify-content: center; flex-wrap: wrap; }
    .svc-cta-trust {
      margin-top: 32px; display: flex; gap: 24px;
      justify-content: center; flex-wrap: wrap;
    }
    .svc-cta-trust-item {
      display: flex; align-items: center; gap: 6px;
      font-size: 0.82rem; color: rgba(255,255,255,0.7);
    }
    .svc-cta-trust-item i { color: var(--clr-accent); }

    /* ============================================================
       FOOTER  (condensed — same as index.html)
       ============================================================ */
    footer {
      background: #080810; border-top: 1px solid var(--clr-border);
      padding: 72px 0 32px;
    }
    .footer-grid {
      display: grid; grid-template-columns: 2fr 1fr 1fr 1.5fr;
      gap: 48px; margin-bottom: 48px;
    }
    .footer-logo {
      display: flex; align-items: center; gap: 10px;
      font-family: var(--font-display); font-size: 1.3rem;
      color: var(--clr-white); margin-bottom: 16px;
    }
    .footer-logo span { color: var(--clr-primary); }
    .footer-logo img { width: 36px; border-radius: 6px; }
    .footer-desc { font-size: 0.88rem; color: var(--clr-muted); line-height: 1.75; margin-bottom: 24px; }
    .footer-socials { display: flex; gap: 10px; }
    .footer-social {
      width: 38px; height: 38px; border-radius: 50%;
      background: var(--clr-surface-2); border: 1px solid var(--clr-border);
      display: flex; align-items: center; justify-content: center;
      color: var(--clr-muted); font-size: 0.9rem; transition: var(--transition);
    }
    .footer-social:hover { background: var(--clr-primary); border-color: var(--clr-primary); color: #fff; }
    .footer-col-title { font-weight: 700; color: var(--clr-white); margin-bottom: 20px; font-size: 0.95rem; }
    .footer-links li + li { margin-top: 10px; }
    .footer-links a {
      font-size: 0.875rem; color: var(--clr-muted); transition: var(--transition);
      display: flex; align-items: center; gap: 6px;
    }
    .footer-links a:hover { color: var(--clr-primary); }
    .footer-contact-item {
      display: flex; gap: 10px; align-items: flex-start;
      font-size: 0.875rem; color: var(--clr-muted); margin-bottom: 14px;
    }
    .footer-contact-item i { color: var(--clr-primary); margin-top: 2px; flex-shrink: 0; }
    .footer-contact-item a:hover { color: var(--clr-primary); }
    .footer-payments {
      display: flex; align-items: center; gap: 20px;
      padding: 20px 0; margin-bottom: 24px;
      border-top: 1px solid var(--clr-border); flex-wrap: wrap;
    }
    .footer-payments-label { font-size: 0.78rem; font-weight: 600; color: var(--clr-muted); text-transform: uppercase; letter-spacing: 0.08em; white-space: nowrap; }
    .footer-payments-logos { display: flex; align-items: center; gap: 16px; flex-wrap: wrap; }
    .pay-logo {
      background: #fff; border-radius: 6px; padding: 5px 10px;
      display: flex; align-items: center; justify-content: center;
      transition: var(--transition); min-width: 56px; height: 34px;
    }
    .pay-logo:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.3); }
    .footer-bottom {
      padding-top: 28px; border-top: 1px solid var(--clr-border);
      display: flex; justify-content: space-between; align-items: center;
      flex-wrap: wrap; gap: 16px;
    }
    .footer-bottom-text { font-size: 0.82rem; color: var(--clr-muted); }
    .footer-bottom-links { display: flex; gap: 20px; }
    .footer-bottom-links a { font-size: 0.82rem; color: var(--clr-muted); transition: var(--transition); }
    .footer-bottom-links a:hover { color: var(--clr-primary); }

    /* WhatsApp float */
    .wa-float {
      position: fixed; bottom: 32px; right: 32px; z-index: 999;
      width: 58px; height: 58px; border-radius: 50%;
      background: #25d366; color: #fff;
      display: flex; align-items: center; justify-content: center;
      font-size: 1.6rem; box-shadow: 0 4px 20px rgba(37,211,102,0.4);
      transition: var(--transition);
    }
    .wa-float:hover { transform: scale(1.1); box-shadow: 0 8px 30px rgba(37,211,102,0.6); }

    /* ============================================================
       RESPONSIVE
       ============================================================ */
    @media (max-width: 1024px) {
      .svc-grid          { grid-template-columns: 1fr 1fr; }
      .svc-card-wide     { grid-column: span 2; flex-direction: column; }
      .svc-card-wide .svc-card-img-wrap { width: 100%; height: 260px; border-radius: var(--radius-xl) var(--radius-xl) 0 0; }
      .process-strip     { grid-template-columns: 1fr 1fr; }
      .process-strip::before { display: none; }
      .materials-grid    { grid-template-columns: 1fr 1fr; }
      .pricing-grid      { grid-template-columns: 1fr; max-width: 480px; margin: 48px auto 0; }
      .pricing-card.featured { transform: none; }
      .pricing-card.featured:hover { transform: translateY(-6px); }
    }
    @media (max-width: 768px) {
      .nav-links, .nav-cta .btn-login, .nav-cta .btn-signup, .nav-cta .btn:not(.btn-green) { display: none; }
      .hamburger { display: flex; }
      .section { padding: 64px 0; }
      .svc-hero-inner { grid-template-columns: 1fr; }
      .svc-hero-stats { grid-template-columns: 1fr 1fr; }
      .svc-grid    { grid-template-columns: 1fr; }
      .svc-card-wide { flex-direction: column; }
      .svc-card-wide .svc-card-img-wrap { width: 100%; height: 220px; border-radius: var(--radius-xl) var(--radius-xl) 0 0; }
      .faq-grid    { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr 1fr; gap: 32px; }
      .process-strip { grid-template-columns: 1fr 1fr; }
    }
    @media (max-width: 540px) {
      .svc-hero-stats { grid-template-columns: 1fr 1fr; }
      .materials-grid { grid-template-columns: 1fr 1fr; }
      .footer-grid { grid-template-columns: 1fr; }
      .footer-bottom { flex-direction: column; text-align: center; }
      .process-strip { grid-template-columns: 1fr; }
    }
  </style>
</head>

<body>

  <!-- ============================================================
       NAVIGATION (REMOVED - using header.php)
       ============================================================ -->

  <main>

    <!-- ============================================================
         PAGE HERO
         ============================================================ -->
    <section id="svc-hero" aria-label="Services hero">
      <div class="svc-hero-bg" aria-hidden="true"></div>
      <div class="svc-hero-grid" aria-hidden="true"></div>
      <div class="container svc-hero-inner">

        <!-- Left -->
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
            <a href="https://wa.me/923001234567" target="_blank" rel="noopener" class="btn btn-green btn-lg">
              <i class="fab fa-whatsapp" aria-hidden="true"></i> Get Free Quote
            </a>
            <a href="#svc-main" class="btn btn-outline btn-lg">
              Explore Services <i class="fas fa-arrow-down" aria-hidden="true"></i>
            </a>
          </div>
        </div>

        <!-- Right — stat cards with PHP dynamic numbers -->
        <div class="svc-hero-stats reveal reveal-delay-2">
          <div class="svc-stat-card">
            <div class="svc-stat-num" style="color:var(--clr-primary)"><?php echo $happy_clients; ?>+</div>
            <div class="svc-stat-label">Happy Clients</div>
          </div>
          <div class="svc-stat-card">
            <div class="svc-stat-num" style="color:var(--clr-accent)"><?php echo $projects_done; ?>+</div>
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
          <button class="svc-tab" data-tab="print" role="tab" aria-selected="false">
            <i class="fas fa-print" aria-hidden="true"></i> Printing
          </button>
          <button class="svc-tab" data-tab="packaging" role="tab" aria-selected="false">
            <i class="fas fa-box" aria-hidden="true"></i> Packaging
          </button>
          <button class="svc-tab" data-tab="design" role="tab" aria-selected="false">
            <i class="fas fa-palette" aria-hidden="true"></i> Graphic Design
          </button>
          <button class="svc-tab" data-tab="wedding" role="tab" aria-selected="false">
            <i class="fas fa-heart" aria-hidden="true"></i> Wedding
          </button>
          <button class="svc-tab" data-tab="large-format" role="tab" aria-selected="false">
            <i class="fas fa-expand" aria-hidden="true"></i> Large Format
          </button>
          <button class="svc-tab" data-tab="stationery" role="tab" aria-selected="false">
            <i class="fas fa-sticky-note" aria-hidden="true"></i> Stationery
          </button>
        </div>
      </div>
    </div>

    <!-- ============================================================
         ALL SERVICES — GROUPED BY CATEGORY
         ============================================================ -->
    <section id="svc-main" class="section" aria-label="All services">
      <div class="container">

        <!-- ── PRINTING SERVICES ── -->
        <div class="cat-section" data-cat="print">
          <div class="cat-section-head reveal">
            <div class="cat-section-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">
              <i class="fas fa-print" aria-hidden="true"></i>
            </div>
            <div>
              <div class="cat-section-title">Printing Services</div>
              <div style="font-size:0.82rem;color:var(--clr-muted);margin-top:2px">Offset · Digital · Large Format</div>
            </div>
            <span class="cat-section-count">6 Services</span>
          </div>

          <div class="svc-grid">

            <!-- Featured wide card -->
            <div class="svc-card svc-card-wide reveal" data-service="offset-printing" data-cat="print">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/custom boxes.webp" alt="Offset printing"
                     loading="lazy" onerror="this.src='https://placehold.co/720x440/1a1a2e/8b5cf6?text=Offset+Printing'" />
                <span class="svc-card-badge">Most Popular</span>
                <span class="svc-card-popular">⭐ Featured</span>
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">
                  <i class="fas fa-print" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Offset Printing</div>
                <div class="svc-card-desc">
                  High-quality offset printing for bulk orders. Perfect colour consistency, sharp detail,
                  and professional finish. Ideal for packaging, brochures, and wedding cards.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> CMYK + Pantone colour matching</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Minimum 500 pcs for bulk pricing</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Gloss / matte lamination available</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> UV spot, foiling &amp; embossing finishing</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 2,500 <span>/ 500 pcs</span></div>
                <a href="https://wa.me/923001234567?text=I'm interested in Offset Printing" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <!-- Digital Printing -->
            <div class="svc-card reveal reveal-delay-1" data-service="digital-printing" data-cat="print">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/f1.jpg" alt="Digital printing"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/3b82f6?text=Digital+Printing'" />
                <span class="svc-card-badge">Low MOQ</span>
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#3b82f6,#06b6d4)">
                  <i class="fas fa-desktop" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Digital Printing</div>
                <div class="svc-card-desc">
                  Fast turnaround digital printing for small runs. No printing plates needed —
                  ideal for samples, prototypes, and low-quantity jobs.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Minimum 1 piece</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Same-day delivery available</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Full colour both sides</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 150 <span>/ piece</span></div>
                <a href="https://wa.me/923001234567?text=I'm interested in Digital Printing" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <!-- Business Cards -->
            <div class="svc-card reveal reveal-delay-2" data-service="business-cards" data-cat="print stationery">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/vcards.jpg" alt="Business cards"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/22c55e?text=Business+Cards'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#22c55e,#15803d)">
                  <i class="fas fa-id-card" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Business Cards</div>
                <div class="svc-card-desc">
                  Premium business cards in standard, square, folded, and round-corner styles.
                  Matte, gloss, or soft-touch lamination.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 350gsm premium card stock</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> UV spot coating option</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 100–5000 qty range</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 1,800 <span>/ 100 pcs</span></div>
                <a href="https://wa.me/923001234567?text=I need Business Cards" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <!-- Brochures -->
            <div class="svc-card reveal" data-service="brochures" data-cat="print stationery">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/brouchers.avif" alt="Brochures"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/f59e0b?text=Brochures'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#f59e0b,#b45309)">
                  <i class="fas fa-file-alt" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Brochures &amp; Leaflets</div>
                <div class="svc-card-desc">
                  Tri-fold, bi-fold, Z-fold brochures and DL leaflets for corporate, retail,
                  restaurant, and medical businesses.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> A4 / A5 / DL sizes</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Multiple fold styles</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 130–170gsm art paper</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 3,500 <span>/ 500 pcs</span></div>
                <a href="https://wa.me/923001234567?text=I need Brochures" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <!-- Flyers -->
            <div class="svc-card reveal reveal-delay-1" data-service="flyers" data-cat="print stationery">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/food.webp" alt="Flyers"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/ec4899?text=Flyers'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#ec4899,#be185d)">
                  <i class="fas fa-paper-plane" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Flyers &amp; Posters</div>
                <div class="svc-card-desc">
                  Eye-catching flyers and posters for events, promotions, menus, and
                  announcements. Fast turnaround for urgent needs.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> A3 / A4 / A5 / custom</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 100–10,000 qty</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 48hr standard turnaround</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 2,200 <span>/ 500 pcs</span></div>
                <a href="https://wa.me/923001234567?text=I need Flyers" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

          </div><!-- /print grid -->
        </div><!-- /print cat-section -->

        <!-- ── PACKAGING SERVICES ── -->
        <div class="cat-section" data-cat="packaging">
          <div class="cat-section-head reveal">
            <div class="cat-section-icon" style="background:linear-gradient(135deg,#f59e0b,#b45309)">
              <i class="fas fa-box" aria-hidden="true"></i>
            </div>
            <div>
              <div class="cat-section-title">Packaging Services</div>
              <div style="font-size:0.82rem;color:var(--clr-muted);margin-top:2px">Custom Boxes · Labels · Bags</div>
            </div>
            <span class="cat-section-count">5 Services</span>
          </div>

          <div class="svc-grid">

            <!-- Box Packaging wide -->
            <div class="svc-card svc-card-wide reveal" data-service="box-packaging" data-cat="packaging">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/boxes.webp" alt="Custom box packaging"
                     loading="lazy" onerror="this.src='https://placehold.co/720x440/1a1a2e/f59e0b?text=Box+Packaging'" />
                <span class="svc-card-badge">Bulk Discounts</span>
                <span class="svc-card-popular">🔥 Best Seller</span>
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#f59e0b,#b45309)">
                  <i class="fas fa-box-open" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Custom Box Packaging</div>
                <div class="svc-card-desc">
                  Complete custom box manufacturing — from design to die-cutting, offset printing,
                  lamination, and pasting. All in-house in our Lahore facility.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Cosmetics, food, shoe, gift, mailer boxes</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Any size and shape — fully custom</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Rigid, folding carton, kraft, corrugated</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Hot foil, spot UV, emboss finishing</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 25 <span>/ piece (bulk)</span></div>
                <a href="https://wa.me/923001234567?text=I need Custom Box Packaging" target="_blank" rel="noopener" class="svc-card-cta">
                  Get Quote <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <!-- Corrugated Boxes -->
            <div class="svc-card reveal reveal-delay-1" data-service="corrugated-boxes" data-cat="packaging">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/corrugatedbox.jfif" alt="Corrugated boxes"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/f59e0b?text=Corrugated+Boxes'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#78716c,#44403c)">
                  <i class="fas fa-archive" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Corrugated Boxes</div>
                <div class="svc-card-desc">
                  Strong corrugated shipping and storage boxes. Single, double, and triple wall
                  options. Custom printing on brown or white kraft.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> E / B / C flute options</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Custom sizes &amp; print</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Food-safe ink available</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 35 <span>/ piece (bulk)</span></div>
                <a href="https://wa.me/923001234567?text=I need Corrugated Boxes" target="_blank" rel="noopener" class="svc-card-cta">
                  Get Quote <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <!-- Labels & Stickers -->
            <div class="svc-card reveal reveal-delay-2" data-service="labels-stickers" data-cat="packaging stationery">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/f1.jpg" alt="Labels and stickers"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/22c55e?text=Labels'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#22c55e,#15803d)">
                  <i class="fas fa-tags" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Labels &amp; Stickers</div>
                <div class="svc-card-desc">
                  Custom product labels, price tags, barcode stickers, transparent stickers,
                  and holographic labels for products and packaging.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Die-cut any shape</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Waterproof &amp; outdoor grade</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Roll or sheet format</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 5 <span>/ sticker (bulk)</span></div>
                <a href="https://wa.me/923001234567?text=I need Labels and Stickers" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <!-- Paper Bags -->
            <div class="svc-card reveal" data-service="paper-bags" data-cat="packaging">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/fabric.webp" alt="Printed paper bags"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/6366f1?text=Paper+Bags'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#6366f1,#4338ca)">
                  <i class="fas fa-shopping-bag" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Custom Paper Bags</div>
                <div class="svc-card-desc">
                  Branded shopping bags, boutique bags, and craft paper bags with cord,
                  ribbon, or flat handles. Available in all sizes.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Kraft / art paper options</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 150–400gsm thickness</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Custom ribbon / rope handle</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 40 <span>/ piece (bulk)</span></div>
                <a href="https://wa.me/923001234567?text=I need Custom Paper Bags" target="_blank" rel="noopener" class="svc-card-cta">
                  Get Quote <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <!-- Ribbons -->
            <div class="svc-card reveal reveal-delay-1" data-service="ribbons" data-cat="packaging">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/ribbons.avif" alt="Printed ribbons"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/ec4899?text=Ribbons'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#ec4899,#be185d)">
                  <i class="fas fa-ribbon" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Printed Ribbons</div>
                <div class="svc-card-desc">
                  Custom printed satin and grosgrain ribbons for gifting, boutiques,
                  and event branding. Your logo on every inch.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 6mm – 50mm widths</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Satin / grosgrain fabric</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Cut to length</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 12 <span>/ meter</span></div>
                <a href="https://wa.me/923001234567?text=I need Printed Ribbons" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

          </div><!-- /packaging grid -->
        </div><!-- /packaging cat-section -->

        <!-- ── GRAPHIC DESIGN ── -->
        <div class="cat-section" data-cat="design">
          <div class="cat-section-head reveal">
            <div class="cat-section-icon" style="background:linear-gradient(135deg,#ec4899,#be185d)">
              <i class="fas fa-palette" aria-hidden="true"></i>
            </div>
            <div>
              <div class="cat-section-title">Graphic Design</div>
              <div style="font-size:0.82rem;color:var(--clr-muted);margin-top:2px">Logo · Branding · Social Media · Packaging</div>
            </div>
            <span class="cat-section-count">4 Services</span>
          </div>

          <div class="svc-grid">
            <div class="svc-card reveal" data-service="logo-design" data-cat="design">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="https://picsum.photos/id/20/600/360" alt="Logo design"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/8b5cf6?text=Logo+Design'" />
                <span class="svc-card-badge">Free Revisions</span>
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#8b5cf6,#6d28d9)">
                  <i class="fas fa-crown" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Logo &amp; Brand Identity</div>
                <div class="svc-card-desc">
                  Professional logo design with full brand identity kit — business card,
                  letterhead, brand guidelines, and social media kit included.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 3 unique concepts</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Unlimited revisions</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> All file formats (AI, PDF, PNG, SVG)</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 2,999 <span>starting</span></div>
                <a href="https://wa.me/923001234567?text=I need a Logo Design" target="_blank" rel="noopener" class="svc-card-cta">
                  Start Project <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <div class="svc-card reveal reveal-delay-1" data-service="social-media" data-cat="design">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="https://picsum.photos/id/26/600/360" alt="Social media design"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/3b82f6?text=Social+Media'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#3b82f6,#06b6d4)">
                  <i class="fas fa-hashtag" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Social Media Design</div>
                <div class="svc-card-desc">
                  Scroll-stopping Instagram posts, stories, Facebook covers, YouTube thumbnails,
                  and full monthly content packages.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> All platform sizes</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Animated stories available</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 24hr delivery</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 1,499 <span>/ post</span></div>
                <a href="https://wa.me/923001234567?text=I need Social Media Design" target="_blank" rel="noopener" class="svc-card-cta">
                  Get Started <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <div class="svc-card reveal reveal-delay-2" data-service="packaging-design" data-cat="design packaging">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/portfolio5.png" alt="Packaging design"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/22c55e?text=Packaging+Design'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#22c55e,#15803d)">
                  <i class="fas fa-box" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Packaging Design</div>
                <div class="svc-card-desc">
                  Full dieline artwork creation and packaging design with print-ready files.
                  Includes structural design and artwork.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Dieline + artwork</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 3D mockup preview</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Print-ready files</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 4,999 <span>starting</span></div>
                <a href="https://wa.me/923001234567?text=I need Packaging Design" target="_blank" rel="noopener" class="svc-card-cta">
                  Get Started <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <div class="svc-card reveal" data-service="web-ui" data-cat="design">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="https://picsum.photos/id/1/600/360" alt="Web UI design"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/6366f1?text=Web+Design'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#6366f1,#4338ca)">
                  <i class="fas fa-laptop-code" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Web &amp; App UI Design</div>
                <div class="svc-card-desc">
                  Beautiful Figma UI/UX designs for websites, mobile apps, and dashboards.
                  Pixel-perfect and developer-ready.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Figma source files</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Mobile responsive</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Style guide included</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 7,999 <span>starting</span></div>
                <a href="https://wa.me/923001234567?text=I need UI Design" target="_blank" rel="noopener" class="svc-card-cta">
                  Start Project <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- ── WEDDING SERVICES ── -->
        <div class="cat-section" data-cat="wedding">
          <div class="cat-section-head reveal">
            <div class="cat-section-icon" style="background:linear-gradient(135deg,#ec4899,#9333ea)">
              <i class="fas fa-heart" aria-hidden="true"></i>
            </div>
            <div>
              <div class="cat-section-title">Wedding Services</div>
              <div style="font-size:0.82rem;color:var(--clr-muted);margin-top:2px">Cards · Envelopes · Thank You · Invitations</div>
            </div>
            <span class="cat-section-count">4 Services</span>
          </div>

          <div class="svc-grid">
            <div class="svc-card svc-card-wide reveal" data-service="wedding-cards" data-cat="wedding">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/portfolio.jpg" alt="Wedding cards"
                     loading="lazy" onerror="this.src='https://placehold.co/720x440/1a1a2e/ec4899?text=Wedding+Cards'" />
                <span class="svc-card-badge">Traditional &amp; Modern</span>
                <span class="svc-card-popular">💍 Wedding</span>
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#ec4899,#9333ea)">
                  <i class="fas fa-heart" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Wedding &amp; Shaadi Cards</div>
                <div class="svc-card-desc">
                  Elegant, traditional, and modern wedding invitation cards. Mehndi,
                  Barat, Walima — all ceremonies covered. Printed on premium card stock
                  with optional foiling, embossing, and ribbon finishing.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Urdu &amp; English both available</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Foiling &amp; embossing options</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Envelopes included free</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 50–5000 quantity range</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 3,500 <span>/ 100 pcs</span></div>
                <a href="https://wa.me/923001234567?text=I need Wedding Cards" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <div class="svc-card reveal reveal-delay-1" data-service="thank-you-cards" data-cat="wedding stationery">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/thankyou.jpg" alt="Thank you cards"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/ec4899?text=Thank+You+Cards'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#f472b6,#ec4899)">
                  <i class="fas fa-hand-holding-heart" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Thank You Cards</div>
                <div class="svc-card-desc">
                  Beautiful thank you cards for weddings, boutiques, and businesses.
                  Custom design with your branding or personal message.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> A6 / postcard size</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Personalised printing</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Same design as your cards</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 1,500 <span>/ 100 pcs</span></div>
                <a href="https://wa.me/923001234567?text=I need Thank You Cards" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <div class="svc-card reveal reveal-delay-2" data-service="envelopes" data-cat="wedding stationery">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/envelop.jpg" alt="Custom envelopes"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/6366f1?text=Envelopes'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#6366f1,#4338ca)">
                  <i class="fas fa-envelope" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Custom Envelopes</div>
                <div class="svc-card-desc">
                  Printed envelopes in any size. Standard, window, and pocket envelopes
                  with custom colour, logo, and return address printing.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> DL / C5 / C4 sizes</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Inside print option</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Peel &amp; seal closure</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 800 <span>/ 100 pcs</span></div>
                <a href="https://wa.me/923001234567?text=I need Custom Envelopes" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

        <!-- ── LARGE FORMAT ── -->
        <div class="cat-section" data-cat="large-format">
          <div class="cat-section-head reveal">
            <div class="cat-section-icon" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)">
              <i class="fas fa-expand" aria-hidden="true"></i>
            </div>
            <div>
              <div class="cat-section-title">Large Format Printing</div>
              <div style="font-size:0.82rem;color:var(--clr-muted);margin-top:2px">Flex · Banners · Standees · Wallpapers</div>
            </div>
            <span class="cat-section-count">3 Services</span>
          </div>

          <div class="svc-grid">
            <div class="svc-card reveal" data-service="flex-banners" data-cat="large-format">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/portfolio2.webp" alt="Flex banners"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/0ea5e9?text=Flex+Banners'" />
                <span class="svc-card-badge">Outdoor Ready</span>
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#0ea5e9,#0284c7)">
                  <i class="fas fa-image" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Flex &amp; Banners</div>
                <div class="svc-card-desc">
                  High-resolution solvent-printed flex banners for shops, events, and
                  outdoor advertising. UV-resistant inks with hem and grommets.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 440gsm / 550gsm flex media</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Any custom width &amp; length</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Grommets &amp; hemming included</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 120 <span>/ sq. ft.</span></div>
                <a href="https://wa.me/923001234567?text=I need Flex Banners" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <div class="svc-card reveal reveal-delay-1" data-service="standees" data-cat="large-format">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="images/bannerss.png" alt="Roll-up standees"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/f59e0b?text=Standees'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#f59e0b,#b45309)">
                  <i class="fas fa-arrows-alt-v" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Roll-Up Standees</div>
                <div class="svc-card-desc">
                  Professional roll-up banner stands for exhibitions, conferences, retail,
                  and office reception. Print + stand combo available.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> 85cm × 200cm standard</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Premium chrome stand</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Carry bag included</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 4,500 <span>/ unit</span></div>
                <a href="https://wa.me/923001234567?text=I need Roll-Up Standees" target="_blank" rel="noopener" class="svc-card-cta">
                  Order Now <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>

            <div class="svc-card reveal reveal-delay-2" data-service="wall-graphics" data-cat="large-format">
              <div class="svc-card-img-wrap">
                <img class="svc-card-img" src="https://picsum.photos/id/30/600/360" alt="Wall graphics"
                     loading="lazy" onerror="this.src='https://placehold.co/600x360/1a1a2e/22c55e?text=Wall+Graphics'" />
              </div>
              <div class="svc-card-body">
                <div class="svc-card-icon" style="background:linear-gradient(135deg,#22c55e,#15803d)">
                  <i class="fas fa-paint-roller" aria-hidden="true"></i>
                </div>
                <div class="svc-card-title">Wall Graphics &amp; Wallpapers</div>
                <div class="svc-card-desc">
                  Custom wall wraps, office murals, and self-adhesive wallpapers.
                  Transform your space with high-res digital printing.
                </div>
                <div class="svc-card-features">
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Self-adhesive vinyl</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Removable option</div>
                  <div class="svc-card-feature"><i class="fas fa-check-circle" aria-hidden="true"></i> Installation service</div>
                </div>
              </div>
              <div class="svc-card-footer">
                <div class="svc-card-price">₨ 180 <span>/ sq. ft.</span></div>
                <a href="https://wa.me/923001234567?text=I need Wall Graphics" target="_blank" rel="noopener" class="svc-card-cta">
                  Get Quote <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
              </div>
            </div>
          </div>
        </div>

      </div>
    </section><!-- /#svc-main -->

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
            <div class="ps-icon"><i class="fas fa-comments" style="color:var(--clr-primary)" aria-hidden="true"></i></div>
            <div class="ps-title">Share Your Idea</div>
            <div class="ps-desc">Tell us what you need via WhatsApp, call, or visit our shop in Gawalmandi.</div>
          </div>
          <div class="ps-step reveal reveal-delay-1">
            <div class="ps-num" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)">2</div>
            <div class="ps-icon"><i class="fas fa-file-invoice-dollar" style="color:#3b82f6" aria-hidden="true"></i></div>
            <div class="ps-title">Get Your Quote</div>
            <div class="ps-desc">We give you an instant price. Negotiate, finalise, and pay to confirm order.</div>
          </div>
          <div class="ps-step reveal reveal-delay-2">
            <div class="ps-num" style="background:linear-gradient(135deg,#f59e0b,#b45309)">3</div>
            <div class="ps-icon"><i class="fas fa-magic" style="color:var(--clr-accent)" aria-hidden="true"></i></div>
            <div class="ps-title">We Design &amp; Print</div>
            <div class="ps-desc">Our designers create your artwork and our machines produce the final prints.</div>
          </div>
          <div class="ps-step reveal reveal-delay-3">
            <div class="ps-num" style="background:linear-gradient(135deg,#22c55e,#15803d)">4</div>
            <div class="ps-icon"><i class="fas fa-truck" style="color:var(--clr-green)" aria-hidden="true"></i></div>
            <div class="ps-title">Pickup or Deliver</div>
            <div class="ps-desc">Collect from our shop or get your order delivered to your door across Pakistan.</div>
          </div>
          <div class="ps-step reveal reveal-delay-4">
            <div class="ps-num" style="background:linear-gradient(135deg,#ec4899,#be185d)">5</div>
            <div class="ps-icon"><i class="fas fa-star" style="color:#ec4899" aria-hidden="true"></i></div>
            <div class="ps-title">Leave a Review</div>
            <div class="ps-desc">Share your experience and get 10% off your next order. We love happy clients!</div>
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
            <div class="mat-desc">Coated for sharp images and vivid colour reproduction. Used for brochures, leaflets, and packaging.</div>
            <div class="mat-tags">
              <span class="mat-tag">130gsm</span>
              <span class="mat-tag">170gsm</span>
              <span class="mat-tag">300gsm</span>
              <span class="mat-tag">350gsm</span>
            </div>
          </div>
          <div class="mat-card reveal reveal-delay-1">
            <div class="mat-icon" style="background:linear-gradient(135deg,#f59e0b,#b45309)"><i class="fas fa-scroll" aria-hidden="true"></i></div>
            <div class="mat-title">Kraft &amp; Brown Board</div>
            <div class="mat-desc">Eco-friendly natural paper for bags, tags, and sustainable packaging. Rustic and premium feel.</div>
            <div class="mat-tags">
              <span class="mat-tag">Natural</span>
              <span class="mat-tag">White Kraft</span>
              <span class="mat-tag">Recycled</span>
            </div>
          </div>
          <div class="mat-card reveal reveal-delay-2">
            <div class="mat-icon" style="background:linear-gradient(135deg,#22c55e,#15803d)"><i class="fas fa-box" aria-hidden="true"></i></div>
            <div class="mat-title">Corrugated Flute</div>
            <div class="mat-desc">Strong and lightweight for shipping and storage boxes. Available in E, B, and C flute configurations.</div>
            <div class="mat-tags">
              <span class="mat-tag">E Flute</span>
              <span class="mat-tag">B Flute</span>
              <span class="mat-tag">C Flute</span>
              <span class="mat-tag">Double Wall</span>
            </div>
          </div>
          <div class="mat-card reveal reveal-delay-3">
            <div class="mat-icon" style="background:linear-gradient(135deg,#3b82f6,#1d4ed8)"><i class="fas fa-gem" aria-hidden="true"></i></div>
            <div class="mat-title">Finishing Options</div>
            <div class="mat-desc">Transform ordinary print into premium with our speciality finishing services.</div>
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
          <p class="section-desc">Fixed packages for graphic design. Printing priced separately based on quantity and specs.</p>
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
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>1 design concept</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>2 revisions</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>PNG &amp; PDF files</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>3–5 day delivery</span></div>
              <div class="pricing-feature excluded"><i class="fas fa-times" aria-hidden="true"></i><span>Source files (AI/PSD)</span></div>
              <div class="pricing-feature excluded"><i class="fas fa-times" aria-hidden="true"></i><span>Brand style guide</span></div>
              <div class="pricing-feature excluded"><i class="fas fa-times" aria-hidden="true"></i><span>Social media kit</span></div>
            </div>
            <div class="pricing-foot">
              <a href="https://wa.me/923001234567?text=I want the Basic Design package" target="_blank" rel="noopener"
                 class="btn btn-outline" style="width:100%;justify-content:center">
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
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>3 design concepts</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>Unlimited revisions</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>All file formats</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>48hr delivery</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>Source files (AI/PSD)</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>Brand style guide</span></div>
              <div class="pricing-feature excluded"><i class="fas fa-times" aria-hidden="true"></i><span>Social media kit</span></div>
            </div>
            <div class="pricing-foot">
              <a href="https://wa.me/923001234567?text=I want the Professional Design package" target="_blank" rel="noopener"
                 class="btn btn-primary" style="width:100%;justify-content:center">
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
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>5 design concepts</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>Unlimited revisions</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>All file formats</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>24hr rush delivery</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>Source files (AI/PSD)</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>Complete brand guide</span></div>
              <div class="pricing-feature included"><i class="fas fa-check" aria-hidden="true"></i><span>Social media kit (10 posts)</span></div>
            </div>
            <div class="pricing-foot">
              <a href="https://wa.me/923001234567?text=I want the Brand Package" target="_blank" rel="noopener"
                 class="btn btn-accent" style="width:100%;justify-content:center">
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

          <div class="faq-item reveal" data-faq>
            <button class="faq-question" aria-expanded="false">
              What is the minimum order quantity?
              <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
            </button>
            <div class="faq-answer">
              <p>For offset printing the minimum is usually 500 pieces for cost-effective pricing. For digital printing, we can print even 1 piece with no minimum. Packaging boxes start from 100 pieces. Contact us for custom quotes on any quantity.</p>
            </div>
          </div>

          <div class="faq-item reveal reveal-delay-1" data-faq>
            <button class="faq-question" aria-expanded="false">
              How long does printing take?
              <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
            </button>
            <div class="faq-answer">
              <p>Standard turnaround is 3–5 business days for most jobs. Rush orders (24–48 hours) are available at an additional cost. Large packaging orders may take 7–14 days. We will give you an exact timeline when you confirm your order.</p>
            </div>
          </div>

          <div class="faq-item reveal reveal-delay-1" data-faq>
            <button class="faq-question" aria-expanded="false">
              Do you provide delivery across Pakistan?
              <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
            </button>
            <div class="faq-answer">
              <p>Yes! We deliver to all major cities across Pakistan via TCS, Leopards, and other courier services. Delivery charges depend on weight and destination. Lahore customers can also pick up from our shop in Gawalmandi free of charge.</p>
            </div>
          </div>

          <div class="faq-item reveal reveal-delay-2" data-faq>
            <button class="faq-question" aria-expanded="false">
              What file formats do you accept?
              <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
            </button>
            <div class="faq-answer">
              <p>We accept AI, PDF, PSD, CDR, EPS, PNG (300 DPI minimum), and TIFF. For best results, provide files in CMYK colour mode at 300 DPI with 3mm bleed. If you don't have a design, our team can create one for you.</p>
            </div>
          </div>

          <div class="faq-item reveal" data-faq>
            <button class="faq-question" aria-expanded="false">
              Can I get a sample before bulk printing?
              <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
            </button>
            <div class="faq-answer">
              <p>Yes, we can print a sample proof for you before proceeding with the bulk order. Sample printing charges apply and will be deducted from your final order. This is highly recommended for packaging and wedding cards.</p>
            </div>
          </div>

          <div class="faq-item reveal reveal-delay-1" data-faq>
            <button class="faq-question" aria-expanded="false">
              What payment methods do you accept?
              <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
            </button>
            <div class="faq-answer">
              <p>We accept cash, bank transfer, JazzCash, EasyPaisa, and card payments at our shop. For online orders, 50% advance is required before production starts and the remaining 50% upon delivery or pickup.</p>
            </div>
          </div>

          <div class="faq-item reveal reveal-delay-2" data-faq>
            <button class="faq-question" aria-expanded="false">
              Do you offer design services if I don't have artwork?
              <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
            </button>
            <div class="faq-answer">
              <p>Absolutely! Our in-house design team can create everything from scratch — logos, packaging artwork, wedding card designs, brochures, and more. Design fees are charged separately. Check our pricing packages above.</p>
            </div>
          </div>

          <div class="faq-item reveal reveal-delay-3" data-faq>
            <button class="faq-question" aria-expanded="false">
              Is there a discount for bulk orders?
              <div class="faq-icon"><i class="fas fa-plus" aria-hidden="true"></i></div>
            </button>
            <div class="faq-answer">
              <p>Yes! The more you print, the lower the cost per unit. We offer competitive bulk pricing for quantities of 1,000+ pieces. Contact us on WhatsApp for a custom bulk quote tailored to your specific requirements.</p>
            </div>
          </div>

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
          <a href="https://wa.me/923001234567" target="_blank" rel="noopener"
             class="btn btn-green btn-lg">
            <i class="fab fa-whatsapp" aria-hidden="true"></i> Chat on WhatsApp
          </a>
          <a href="tel:+923001234567" class="btn btn-lg"
             style="background:rgba(255,255,255,0.15);color:#fff;border:1.5px solid rgba(255,255,255,0.3)">
            <i class="fas fa-phone" aria-hidden="true"></i> Call Now
          </a>
          <a href="contact.html" class="btn btn-lg"
             style="background:rgba(255,255,255,0.1);color:#fff;border:1.5px solid rgba(255,255,255,0.2)">
            <i class="fas fa-envelope" aria-hidden="true"></i> Send Message
          </a>
        </div>
        <div class="svc-cta-trust">
          <div class="svc-cta-trust-item"><i class="fas fa-shield-alt" aria-hidden="true"></i> 100% Quality Guarantee</div>
          <div class="svc-cta-trust-item"><i class="fas fa-redo" aria-hidden="true"></i> Free Revisions on Design</div>
          <div class="svc-cta-trust-item"><i class="fas fa-truck" aria-hidden="true"></i> Fast Nationwide Delivery</div>
          <div class="svc-cta-trust-item"><i class="fas fa-clock" aria-hidden="true"></i> 1-Hour Response Time</div>
        </div>
      </div>
    </section>

  </main>

  <!-- ============================================================
       FOOTER (REMOVED - using footer.php)
       ============================================================ -->

  <!-- WhatsApp float -->
  <a href="https://wa.me/923001234567" target="_blank" rel="noopener"
     class="wa-float" aria-label="Chat on WhatsApp">
    <i class="fab fa-whatsapp" aria-hidden="true"></i>
  </a>

  <!-- ============================================================
       JAVASCRIPT
       ============================================================ -->
  <script>
  (function () {
    'use strict';

    const $ = (s, c = document) => c.querySelector(s);
    const $$ = (s, c = document) => Array.from(c.querySelectorAll(s));

    /* ── Navbar scroll ── */
    const navbar = $('#navbar');
    const handleScroll = () => navbar.classList.toggle('scrolled', window.scrollY > 60);
    window.addEventListener('scroll', handleScroll, { passive: true });
    handleScroll();

    /* ── Hamburger / mobile menu ── */
    const hamburger  = $('#hamburger-btn');
    const mobileMenu = $('#mobile-menu');
    hamburger.addEventListener('click', () => {
      const open = mobileMenu.classList.toggle('open');
      hamburger.classList.toggle('open', open);
      hamburger.setAttribute('aria-expanded', String(open));
    });
    $$('a', mobileMenu).forEach(a => a.addEventListener('click', () => {
      mobileMenu.classList.remove('open');
      hamburger.classList.remove('open');
      hamburger.setAttribute('aria-expanded', 'false');
    }));

    /* ── Portfolio dropdown ── */
    (function () {
      const dd     = $('#portfolioDropdown');
      const toggle = $('#portfolioToggle');
      if (!dd || !toggle) return;
      toggle.addEventListener('click', e => {
        e.stopPropagation();
        const open = dd.classList.toggle('open');
        toggle.setAttribute('aria-expanded', String(open));
      });
      document.addEventListener('click', () => { 
        dd.classList.remove('open'); 
        toggle.setAttribute('aria-expanded','false'); 
      });
      document.addEventListener('keydown', e => { 
        if (e.key === 'Escape') { 
          dd.classList.remove('open'); 
          toggle.setAttribute('aria-expanded','false'); 
        } 
      });
    }());

    /* ── Reveal on scroll ── */
    const obs = new IntersectionObserver(entries => {
      entries.forEach(e => { 
        if (e.isIntersecting) { 
          e.target.classList.add('visible'); 
          obs.unobserve(e.target); 
        } 
      });
    }, { threshold: 0.08, rootMargin: '0px 0px -40px 0px' });
    $$('.reveal').forEach(el => obs.observe(el));

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

    /* ── Category tab filter (FIXED) ── */
    const tabs = $$('.svc-tab');
    const catSections = $$('.cat-section');
    const allCards = $$('.svc-card');

    function filterByTab(filterValue) {
      // First, show everything
      catSections.forEach(sec => { sec.style.display = ''; });
      allCards.forEach(card => { card.style.display = ''; });
      
      // If filter is not 'all', hide non-matching sections AND non-matching cards
      if (filterValue !== 'all') {
        // Hide sections that don't match the category
        catSections.forEach(sec => {
          const sectionCat = sec.dataset.cat;
          if (sectionCat !== filterValue) {
            sec.style.display = 'none';
          }
        });
        
        // Hide cards that don't match the category
        allCards.forEach(card => {
          const cardCats = (card.dataset.cat || '').split(' ');
          if (!cardCats.includes(filterValue)) {
            card.style.display = 'none';
          }
        });
      }
    }

    tabs.forEach(tab => {
      tab.addEventListener('click', () => {
        // Update active state
        tabs.forEach(t => { 
          t.classList.remove('active'); 
          t.setAttribute('aria-selected', 'false'); 
        });
        tab.classList.add('active');
        tab.setAttribute('aria-selected', 'true');
        
        const filterValue = tab.dataset.tab;
        filterByTab(filterValue);
      });
    });

    // Initialize with 'all' filter
    filterByTab('all');

    /* ── FAQ accordion ── */
    $$('[data-faq]').forEach(item => {
      const btn = item.querySelector('.faq-question');
      btn.addEventListener('click', () => {
        const isOpen = item.classList.contains('open');
        // close all others
        $$('[data-faq].open').forEach(o => {
          o.classList.remove('open');
          o.querySelector('.faq-question').setAttribute('aria-expanded', 'false');
        });
        if (!isOpen) {
          item.classList.add('open');
          btn.setAttribute('aria-expanded', 'true');
        } else {
          btn.setAttribute('aria-expanded', 'false');
        }
      });
    });

    /* ── Footer year ── */
    const yr = $('#footer-year');
    if (yr) yr.textContent = new Date().getFullYear();

    /* ── Fix for card heights on window resize ── */
    let resizeTimer;
    window.addEventListener('resize', function() {
      clearTimeout(resizeTimer);
      resizeTimer = setTimeout(function() {
        // Re-apply current filter to ensure proper layout
        const activeTab = $('.svc-tab.active');
        if (activeTab) {
          filterByTab(activeTab.dataset.tab);
        }
      }, 250);
    });

  }());
  </script>

<?php require_once 'includes/footer.php'; ?>
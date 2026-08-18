<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Login to Subhan Printers - Access your orders, quotations and printing projects.">
<title>Login | Subhan Printers</title>

<!-- Favicon -->
<link rel="icon" href="images/logo.png" type="image/png">

<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=DM+Sans:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
:root{
    --bg:#0d0d14;
    --surface:#13131f;
    --surface2:#1a1a2e;
    --primary:#8b5cf6;
    --primary2:#6d28d9;
    --gold:#f59e0b;
    --text:#e8e8f0;
    --muted:#8888aa;
    --success:#10b981;
    --error:#ef4444;
}

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}

html{
    scroll-behavior:smooth;
}

body{
    font-family:'DM Sans',sans-serif;
    background:var(--bg);
    min-height:100vh;
    color:white;
    overflow-x:hidden;
    position:relative;
}

/* ==========================================
   ANIMATED BACKGROUND BLOBS
   ========================================== */
.bg-blob{
    position:fixed;
    border-radius:50%;
    filter:blur(120px);
    opacity:.18;
    z-index:0;
    pointer-events:none;
    will-change:transform;
    animation:blobFloat 20s ease-in-out infinite alternate;
}

.bg-blob.purple{
    width:500px;
    height:500px;
    background:rgba(139,92,246,.4);
    top:-150px;
    left:-150px;
}

.bg-blob.gold{
    width:450px;
    height:450px;
    background:rgba(245,158,11,.25);
    bottom:-150px;
    right:-150px;
    animation-delay:-10s;
}

@keyframes blobFloat{
    0%{ transform:translate(0,0) scale(1); }
    100%{ transform:translate(30px,-30px) scale(1.1); }
}

/* ==========================================
   HEADER
   ========================================== */
.header-home{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    z-index:1000;
    padding:12px 0;
    background:rgba(13,13,20,.85);
    backdrop-filter:blur(20px);
    -webkit-backdrop-filter:blur(20px);
    border-bottom:1px solid rgba(255,255,255,.06);
    transition:all .4s cubic-bezier(.4,0,.2,1);
}

.header-home.scrolled{
    padding:8px 0;
    background:rgba(13,13,20,.95);
    box-shadow:0 10px 30px rgba(0,0,0,.4);
}

.nav-container{
    max-width:1400px;
    margin:0 auto;
    padding:0 30px;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.nav-logo{
    display:flex;
    align-items:center;
    gap:12px;
    text-decoration:none;
    color:white;
    padding:8px 16px;
    transition:all .3s ease;
}

.nav-logo:hover{
    border-color:var(--primary);
    background:rgba(139,92,246,.05);
}

.nav-logo img{
    width:40px;
    height:40px;
    object-fit:contain;
    transition:transform .3s ease;
}

.nav-logo:hover img{
    transform:rotate(10deg) scale(1.1);
}

.nav-brand{
    font-family:'Playfair Display',serif;
    font-size:1.4rem;
    font-weight:800;
}

.nav-brand .brand-first{ color:white; }
.nav-brand .brand-second{ color:var(--primary); }

.nav-links{
    display:flex;
    align-items:center;
    gap:5px;
    list-style:none;
}

.nav-links > li{
    position:relative;
}

.nav-links > li > a{
    color:var(--text);
    text-decoration:none;
    font-size:15px;
    font-weight:500;
    padding:10px 18px;
    border-radius:8px;
    position:relative;
    transition:all .3s ease;
    display:flex;
    align-items:center;
    gap:6px;
}

.nav-links > li > a::after{
    content:'';
    position:absolute;
    bottom:4px;
    left:50%;
    transform:translateX(-50%) scaleX(0);
    width:20px;
    height:2px;
    background:var(--primary);
    border-radius:2px;
    transition:transform .3s ease;
}

.nav-links > li > a:hover::after,
.nav-links > li > a.active::after{
    transform:translateX(-50%) scaleX(1);
}

.nav-links > li > a:hover{ color:white; }
.nav-links > li > a.active{ color:white; }

.dropdown-toggle .dropdown-icon{
    font-size:12px;
    transition:transform .3s ease;
}

.dropdown-toggle:hover .dropdown-icon{
    transform:rotate(180deg);
}

.dropdown-menu{
    position:absolute;
    top:calc(100% + 10px);
    left:0;
    min-width:240px;
    background:rgba(19,19,31,.98);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,.08);
    border-radius:14px;
    padding:10px;
    opacity:0;
    visibility:hidden;
    transform:translateY(10px);
    transition:all .3s cubic-bezier(.4,0,.2,1);
    box-shadow:0 20px 40px rgba(0,0,0,.4);
    list-style:none;
}

.dropdown-menu::before{
    content:'';
    position:absolute;
    top:-6px;
    left:30px;
    width:12px;
    height:12px;
    background:rgba(19,19,31,.98);
    border-left:1px solid rgba(255,255,255,.08);
    border-top:1px solid rgba(255,255,255,.08);
    transform:rotate(45deg);
}

.nav-links > li:hover .dropdown-menu{
    opacity:1;
    visibility:visible;
    transform:translateY(0);
}

.dropdown-menu li a{
    display:flex;
    align-items:center;
    gap:12px;
    padding:12px 16px;
    color:var(--text);
    text-decoration:none;
    font-size:14px;
    font-weight:500;
    border-radius:10px;
    transition:all .3s ease;
}

.dropdown-menu li a:hover{
    background:rgba(139,92,246,.1);
    color:white;
    padding-left:20px;
}

.dropdown-menu li a i{
    width:20px;
    text-align:center;
    color:var(--primary);
    font-size:14px;
}

.nav-actions{
    display:flex;
    align-items:center;
    gap:12px;
}

.btn-login{
    padding:10px 24px;
    background:#6d28d9;
    color:white;
    font-size:14px;
    font-weight:500;
    font-family:inherit;
    border-radius:50px;
    cursor:pointer;
    transition:all .3s ease;
    text-decoration:none;
}

.btn-login:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 25px rgba(124,13,222,.6);
}

.btn-order{
    padding:10px 24px;
    background:linear-gradient(135deg,var(--gold),#d97706);
    border:none;
    color:white;
    font-size:14px;
    font-weight:600;
    font-family:inherit;
    border-radius:50px;
    cursor:pointer;
    display:flex;
    align-items:center;
    gap:8px;
    transition:all .3s ease;
    text-decoration:none;
    box-shadow:0 4px 15px rgba(245,158,11,.4);
}

.btn-order:hover{
    transform:translateY(-2px);
    box-shadow:0 8px 25px rgba(245,158,11,.6);
}

.hamburger-menu{
    display:none;
    flex-direction:column;
    gap:5px;
    cursor:pointer;
    padding:8px;
    z-index:1001;
}

.hamburger-menu span{
    width:28px;
    height:3px;
    background:white;
    border-radius:3px;
    transition:all .3s ease;
    transform-origin:center;
}

.hamburger-menu.active span:nth-child(1){ transform:rotate(45deg) translate(6px,6px); }
.hamburger-menu.active span:nth-child(2){ opacity:0; transform:scaleX(0); }
.hamburger-menu.active span:nth-child(3){ transform:rotate(-45deg) translate(6px,-6px); }

.mobile-menu{
    position:fixed;
    top:0;
    right:-100%;
    width:min(320px,80vw);
    height:100vh;
    background:rgba(19,19,31,.98);
    backdrop-filter:blur(20px);
    z-index:999;
    padding:100px 30px 30px;
    transition:right .4s cubic-bezier(.4,0,.2,1);
    border-left:1px solid rgba(255,255,255,.08);
    overflow-y:auto;
}

.mobile-menu.active{ right:0; }

.mobile-menu a{
    display:flex;
    align-items:center;
    gap:12px;
    color:var(--text);
    text-decoration:none;
    padding:16px 0;
    font-size:16px;
    font-weight:500;
    border-bottom:1px solid rgba(255,255,255,.05);
    transition:color .3s ease, padding-left .3s ease;
}

.mobile-menu a:hover{
    color:var(--primary);
    padding-left:10px;
}

.mobile-overlay{
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.6);
    backdrop-filter:blur(4px);
    z-index:998;
    opacity:0;
    visibility:hidden;
    transition:all .3s ease;
}

.mobile-overlay.active{
    opacity:1;
    visibility:visible;
}

/* ==========================================
   MAIN LAYOUT
   ========================================== */
.main-wrapper{
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:120px 20px 40px;
    position:relative;
    z-index:5;
}

.login-container{
    width:1200px;
    max-width:95%;
    min-height:700px;
    display:grid;
    grid-template-columns:40% 60%;
    overflow:hidden;
    border-radius:30px;
    background:rgba(19,19,31,.85);
    backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,.08);
    box-shadow:0 20px 60px rgba(0,0,0,.5), 0 0 40px rgba(139,92,246,.25);
    position:relative;
    opacity:0;
    transform:translateY(40px) scale(.95);
    animation:containerIn .8s cubic-bezier(.4,0,.2,1) forwards;
    animation-delay:.2s;
}

@keyframes containerIn{
    to{ opacity:1; transform:translateY(0) scale(1); }
}

/* ==========================================
   LEFT PANEL — FORM
   ========================================== */
.left{
    background:rgba(19,19,31,.95);
    padding:50px 45px;
    display:flex;
    flex-direction:column;
    justify-content:center;
    overflow-y:auto;
    scrollbar-width:thin;
    scrollbar-color:var(--primary) transparent;
}

.left::-webkit-scrollbar{ width:4px; }
.left::-webkit-scrollbar-thumb{ background:var(--primary); border-radius:10px; }

.logo-wrap{
    display:flex;
    align-items:center;
    gap:15px;
    margin-bottom:25px;
    opacity:0;
    transform:translateX(-20px);
    animation:slideRight .6s ease forwards;
    animation-delay:.5s;
}

@keyframes slideRight{
    to{ opacity:1; transform:translateX(0); }
}

.logo{
    width:60px;
    height:60px;
    object-fit:contain;
    filter:drop-shadow(0 0 10px rgba(139,92,246,.5));
}

.logo-text{
    font-family:'Playfair Display',serif;
    font-size:1.8rem;
    font-weight:800;
}

.logo-text .brand-first{ color:white; }
.logo-text .brand-second{ color:var(--primary); }

.welcome{
    font-family:'Playfair Display',serif;
    font-size:2.4rem;
    margin-bottom:10px;
}

.welcome span{
    background:linear-gradient(135deg,var(--primary),var(--gold));
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    background-clip:text;
}

.subtitle{
    color:var(--muted);
    margin-bottom:30px;
    line-height:1.7;
    font-size:15px;
}

.form-group{
    margin-bottom:16px;
    position:relative;
}

.input-wrap{
    position:relative;
    width:100%;
}

.input-wrap i.fa-lock,
.input-wrap i.fa-envelope{
    position:absolute;
    left:18px;
    top:50%;
    transform:translateY(-50%);
    color:var(--muted);
    font-size:16px;
    z-index:2;
    pointer-events:none;
    transition:color .3s ease;
}

.form-group input[type="password"],
.form-group input[type="email"],
.form-group input[type="text"]{
    width:100%;
    padding:15px 50px 15px 48px;
    background:var(--surface2);
    border:1.5px solid rgba(255,255,255,.08);
    border-radius:14px;
    color:white;
    font-size:15px;
    font-family:inherit;
    outline:none;
    transition:all .3s ease;
}

.password-toggle{
    position:absolute;
    right:15px;
    top:50%;
    transform:translateY(-50%);
    color:var(--muted);
    cursor:pointer;
    font-size:16px;
    background:none;
    border:none;
    padding:5px 8px;
    z-index:3;
    display:flex;
    align-items:center;
    justify-content:center;
    transition:all .3s ease;
}

.password-toggle:hover{ color:var(--primary); }

.form-group input:focus{
    border-color:var(--primary);
    background:rgba(139,92,246,.08);
    box-shadow:0 0 0 4px rgba(139,92,246,.12);
}

.input-wrap:focus-within i.fa-lock,
.input-wrap:focus-within i.fa-envelope{ color:var(--primary); }

.input-error{
    color:var(--error);
    font-size:12px;
    margin-top:5px;
    display:none;
    align-items:center;
    gap:5px;
}

.input-error.show{
    display:flex;
    animation:shakeX .4s ease;
}

@keyframes shakeX{
    0%,100%{ transform:translateX(0); }
    25%{ transform:translateX(-5px); }
    75%{ transform:translateX(5px); }
}

.options{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin:10px 0 22px;
    font-size:14px;
}

.remember-me{
    display:flex;
    align-items:center;
    gap:8px;
    cursor:pointer;
    color:var(--muted);
}

.remember-me input[type="checkbox"]{
    appearance:none;
    -webkit-appearance:none;
    width:18px;
    height:18px;
    border:2px solid var(--muted);
    border-radius:5px;
    cursor:pointer;
    position:relative;
    transition:all .3s ease;
}

.remember-me input[type="checkbox"]:checked{
    background:var(--primary);
    border-color:var(--primary);
}

.remember-me input[type="checkbox"]:checked::after{
    content:'✓';
    position:absolute;
    top:50%;
    left:50%;
    transform:translate(-50%,-50%);
    color:white;
    font-size:12px;
    font-weight:bold;
}

.options a{
    color:var(--primary);
    text-decoration:none;
    transition:color .3s ease;
}

.options a:hover{ color:var(--gold); }

.login-btn{
    width:100%;
    padding:15px;
    border:none;
    border-radius:50px;
    background:linear-gradient(135deg,var(--primary),var(--primary2));
    color:white;
    font-weight:600;
    font-size:16px;
    font-family:inherit;
    cursor:pointer;
    transition:all .3s ease;
    box-shadow:0 8px 25px rgba(139,92,246,.3);
    margin-bottom:20px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:8px;
}

.login-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 12px 35px rgba(139,92,246,.5);
}

.login-btn:disabled,
.login-btn.loading{
    pointer-events:none;
    opacity:0.7;
}

.divider{
    text-align:center;
    margin:22px 0;
    color:var(--muted);
    position:relative;
    font-size:13px;
}

.divider::before,
.divider::after{
    content:'';
    position:absolute;
    width:calc(50% - 25px);
    height:1px;
    background:rgba(255,255,255,.08);
    top:50%;
}

.divider::before{ left:0; }
.divider::after{ right:0; }

.google-btn{
    width:100%;
    padding:14px;
    background:white;
    border:none;
    border-radius:50px;
    cursor:pointer;
    font-weight:600;
    font-size:15px;
    font-family:inherit;
    color:#222;
    transition:all .3s ease;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:10px;
}

.google-btn:hover{
    transform:translateY(-2px);
    box-shadow:0 10px 30px rgba(0,0,0,.3);
}

.google-icon{
    width:20px;
    height:20px;
}

.signup{
    text-align:center;
    margin-top:22px;
    color:var(--muted);
    font-size:14px;
}

.signup a{
    color:var(--gold);
    text-decoration:none;
    font-weight:600;
}

.signup a:hover{ color:#fbbf24; }

/* ==========================================
   RIGHT PANEL — BRAND
   ========================================== */
.right{
    position:relative;
    display:flex;
    justify-content:center;
    align-items:center;
    overflow:hidden;
}

.right-bg{
    position:absolute;
    inset:0;
    background:linear-gradient(135deg,rgba(24,13,46,0.603)), url('images/hero baneer2.png');
    background-size:cover;
    background-position:center;
    background-repeat:no-repeat;
    z-index:1;
}

.shape{
    position:absolute;
    border-radius:50%;
    z-index:2;
    opacity:.15;
    will-change:transform;
}

.shape-1{
    width:200px;
    height:200px;
    background:var(--gold);
    top:-50px;
    right:-50px;
    animation:shapeFloat1 15s ease-in-out infinite;
}

.shape-2{
    width:150px;
    height:150px;
    background:var(--primary);
    bottom:-30px;
    left:-30px;
    animation:shapeFloat2 18s ease-in-out infinite;
}

@keyframes shapeFloat1{
    0%,100%{ transform:translate(0,0) rotate(0); }
    50%{ transform:translate(-30px,40px) rotate(180deg); }
}

@keyframes shapeFloat2{
    0%,100%{ transform:translate(0,0) rotate(0); }
    50%{ transform:translate(40px,-30px) rotate(-180deg); }
}

.brand-box{
    position:relative;
    z-index:3;
    width:85%;
    max-width:500px;
    text-align:center;
    padding:40px 30px;
    background:rgba(255,255,255,.05);
    backdrop-filter:blur(4px);
    border-radius:25px;
    border:1px solid rgba(255,255,255,.1);
    opacity:0;
    transform:translateX(30px);
    animation:brandIn .8s cubic-bezier(.4,0,.2,1) forwards;
    animation-delay:.8s;
}

@keyframes brandIn{
    to{ opacity:1; transform:translateX(0); }
}

.brand-logo{
    width:110px;
    height:110px;
    object-fit:contain;
    margin:0 auto 20px;
    display:block;
    filter:drop-shadow(0 10px 30px rgba(139,92,246,.5));
    animation:logoPulse 3s ease-in-out infinite;
}

@keyframes logoPulse{
    0%,100%{ transform:scale(1); filter:drop-shadow(0 10px 30px rgba(139,92,246,.5)); }
    50%{ transform:scale(1.05); filter:drop-shadow(0 15px 40px rgba(139,92,246,.7)); }
}

.brand-title{
    font-family:'Playfair Display',serif;
    font-size:2.5rem;
    margin-bottom:15px;
}

.brand-description{
    color:#e8e8f0;
    line-height:1.8;
    font-size:15px;
}

.brand-stats{
    display:grid;
    grid-template-columns:repeat(3,1fr);
    gap:15px;
    margin-top:25px;
    padding-top:25px;
    border-top:1px solid rgba(255,255,255,.1);
}

.stat-num{
    font-family:'Playfair Display',serif;
    font-size:1.6rem;
    font-weight:800;
    background:linear-gradient(135deg,var(--gold),var(--primary));
    -webkit-background-clip:text;
    -webkit-text-fill-color:transparent;
    background-clip:text;
}

.stat-label{
    font-size:11px;
    color:var(--muted);
    text-transform:uppercase;
    letter-spacing:.5px;
    margin-top:4px;
}

/* ==========================================
   TOAST NOTIFICATION
   ========================================== */
.toast{
    position:fixed;
    top:100px;
    right:30px;
    background:var(--surface);
    border:1px solid rgba(255,255,255,.1);
    border-radius:15px;
    padding:15px 20px;
    display:flex;
    align-items:center;
    gap:12px;
    z-index:2000;
    box-shadow:0 15px 40px rgba(0,0,0,.4);
    transform:translateX(calc(100% + 40px));
    transition:transform .4s cubic-bezier(.4,0,.2,1);
    min-width:280px;
    max-width:350px;
}

.toast.show{ transform:translateX(0); }
.toast.success{ border-left:4px solid var(--success); }
.toast.error{ border-left:4px solid var(--error); }

.toast-icon{
    width:36px;
    height:36px;
    border-radius:50%;
    display:flex;
    align-items:center;
    justify-content:center;
    font-size:16px;
    flex-shrink:0;
}

.toast.success .toast-icon{ background:rgba(16,185,129,.15); color:var(--success); }
.toast.error .toast-icon{ background:rgba(239,68,68,.15); color:var(--error); }

.toast-message{
    font-size:14px;
    color:var(--text);
    line-height:1.4;
}

/* ==========================================
   RESPONSIVE
   ========================================== */
@media(max-width:1024px){
    .login-container{ grid-template-columns:1fr; max-width:550px; }
    .right{ display:none; }
    .left{ padding:40px 30px; }
}

@media(max-width:900px){
    .nav-links, .nav-actions .btn-login, .nav-actions .btn-order{ display:none; }
    .hamburger-menu{ display:flex; }
}

@media(max-width:480px){
    .left{ padding:30px 20px; }
    .welcome{ font-size:1.8rem; }
}
</style>
</head>
<body>

<div class="bg-blob purple"></div>
<div class="bg-blob gold"></div>

<!-- ==========================================
     HEADER
     ========================================== -->
<header class="header-home" id="headerHome">
    <nav class="nav-container">
        <a href="index.html" class="nav-logo">
            <img src="images/logo.png" alt="Subhan Printers Logo">
            <span class="nav-brand"><span class="brand-first">Subhan</span> <span class="brand-second">Printers</span></span>
        </a>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="services.html">Services</a></li>
            <li>
                <a href="portfolio.html" class="dropdown-toggle">
                    Portfolio <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="portfolio.html"><i class="fas fa-th-large"></i> All Projects</a></li>
                    <li><a href="portfolio.html?cat=wedding"><i class="fas fa-heart"></i> Wedding Cards</a></li>
                </ul>
            </li>
            <li><a href="about.html">About</a></li>
            <li><a href="contact.html">Contact</a></li>
        </ul>
        <div class="nav-actions">
            <a href="login.html" class="btn-login"><i class="fas fa-user"></i> Login</a>
            <a href="order.html" class="btn-order"><i class="fas fa-shopping-cart"></i> Order Now</a>
        </div>
        <div class="hamburger-menu" id="hamburgerMenu">
            <span></span><span></span><span></span>
        </div>
    </nav>
</header>

<div class="mobile-overlay" id="mobileOverlay"></div>
<div class="mobile-menu" id="mobileMenu">
    <a href="index.html"><i class="fas fa-home"></i> Home</a>
    <a href="services.html"><i class="fas fa-cogs"></i> Services</a>
    <a href="portfolio.html"><i class="fas fa-images"></i> Portfolio</a>
    <a href="about.html"><i class="fas fa-info-circle"></i> About</a>
    <a href="contact.html"><i class="fas fa-envelope"></i> Contact</a>
    <a href="login.html" style="color:var(--primary);"><i class="fas fa-user"></i> Login</a>
    <a href="order.html" style="color:var(--gold);"><i class="fas fa-shopping-cart"></i> Order Now</a>
</div>

<!-- ==========================================
     MAIN LOGIN AREA
     ========================================== -->
<main class="main-wrapper">
    <div class="login-container">

        <!-- LEFT: FORM -->
        <div class="left">
            <div class="logo-wrap">
                <img src="images/logo.png" alt="Logo" class="logo">
                <span class="logo-text">
                    <span class="brand-first">Subhan</span> <span class="brand-second">Printers</span>
                </span>
            </div>

            <h2 class="welcome">Welcome <span>Back</span></h2>
            <p class="subtitle">Access your orders, quotations and printing projects instantly.</p>

            <form id="loginForm" novalidate>
                <!-- Email -->
                <div class="form-group">
                    <div class="input-wrap">
                        <input type="email" id="emailInput" placeholder="Email Address" autocomplete="email">
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="input-error" id="emailError">
                        <i class="fas fa-exclamation-circle"></i><span>Please enter a valid email address</span>
                    </div>
                </div>

                <!-- Password -->
                <div class="form-group">
                    <div class="input-wrap">
                        <input type="password" id="passwordInput" placeholder="Password" autocomplete="current-password">
                        <i class="fas fa-lock"></i>
                        <button type="button" class="password-toggle" id="passToggle">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="input-error" id="passwordError">
                        <i class="fas fa-exclamation-circle"></i><span>Password must be at least 6 characters</span>
                    </div>
                </div>

                <!-- Options -->
                <div class="options">
                    <label class="remember-me">
                        <input type="checkbox" id="rememberMe">
                        <span>Remember Me</span>
                    </label>
                    <a href="forgetpass.html">Forgot Password?</a>
                </div>

                <!-- Submit -->
                <button type="submit" class="login-btn" id="loginBtn">
                    <i class="fas fa-sign-in-alt"></i> Login
                </button>

                <div class="divider">OR CONTINUE WITH</div>

                <!-- Google Sign In -->
                <button type="button" class="google-btn" id="googleBtn">
                    <svg class="google-icon" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue with Google
                </button>

                <div class="signup">
                    Don't have an account? <a href="register.html">Create Account</a>
                </div>
            </form>
        </div>

        <!-- RIGHT: BRAND PANEL -->
        <div class="right">
            <div class="right-bg"></div>
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="brand-box">
                <img src="images/logo.png" alt="Subhan Printers" class="brand-logo">
                <h2 class="brand-title">SUBHAN PRINTERS</h2>
                <p class="brand-description">
                    Professional graphic designing and printing services — wedding cards, packaging, flex banners &amp; more since 1998.
                </p>
                <div class="brand-stats">
                    <div class="stat-item">
                        <div class="stat-num">25+</div>
                        <div class="stat-label">Years</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">10K+</div>
                        <div class="stat-label">Orders</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-num">5★</div>
                        <div class="stat-label">Rating</div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</main>

<!-- Toast Notification -->
<div class="toast" id="toast">
    <div class="toast-icon"><i class="fas fa-check" id="toastIcon"></i></div>
    <div class="toast-message" id="toastMessage"></div>
</div>

<!-- ==========================================
     SINGLE UNIFIED FIREBASE MODULE
     (All Firebase logic in one <script type="module">)
     ========================================== -->
<script type="module">
  // ── 1. Import everything from correct Firebase modules ──
  import { initializeApp }          from "https://www.gstatic.com/firebasejs/10.12.0/firebase-app.js";
  import { getAnalytics }           from "https://www.gstatic.com/firebasejs/10.12.0/firebase-analytics.js";
  import {
    getAuth,
    signInWithPopup,
    GoogleAuthProvider,
    signInWithEmailAndPassword
  } from "https://www.gstatic.com/firebasejs/10.12.0/firebase-auth.js";

  // ── 2. Firebase config ──
  const firebaseConfig = {
    apiKey:            "AIzaSyBMxz3V0yaaWIJh-Wg3fviHKYkS5Xo4uh0",
    authDomain:        "subhanprinters.firebaseapp.com",
    projectId:         "subhanprinters",
    storageBucket:     "subhanprinters.firebasestorage.app",
    messagingSenderId: "71236166247",
    appId:             "1:71236166247:web:8f68e3281ed26016c4aac2",
    measurementId:     "G-R1MG8H1YB8"
  };

  // ── 3. Initialize app, analytics, auth ──
  const app      = initializeApp(firebaseConfig);
  const analytics = getAnalytics(app);      // optional, safe to keep
  const auth     = getAuth(app);            // pass app explicitly
  const provider = new GoogleAuthProvider();

  // ── 4. Toast helper ──
  function showToast(msg, type = 'success') {
    const toast   = document.getElementById('toast');
    const msgEl   = document.getElementById('toastMessage');
    const iconEl  = document.getElementById('toastIcon');

    msgEl.textContent = msg;
    toast.className   = `toast ${type} show`;
    iconEl.className  = type === 'success'
      ? 'fas fa-check'
      : 'fas fa-exclamation-triangle';

    setTimeout(() => toast.classList.remove('show'), 3500);
  }

  // ── 5. Email / Password login ──
  document.getElementById('loginForm').addEventListener('submit', async (e) => {
    e.preventDefault();

    const email    = document.getElementById('emailInput').value.trim();
    const password = document.getElementById('passwordInput').value;
    const emailErr = document.getElementById('emailError');
    const passErr  = document.getElementById('passwordError');
    const loginBtn = document.getElementById('loginBtn');

    // Validate
    let valid = true;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    if (!emailRegex.test(email)) {
      emailErr.classList.add('show');
      valid = false;
    } else {
      emailErr.classList.remove('show');
    }

    if (password.length < 6) {
      passErr.classList.add('show');
      valid = false;
    } else {
      passErr.classList.remove('show');
    }

    if (!valid) return;

    // Loading state
    loginBtn.classList.add('loading');
    loginBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Signing in...';

    try {
      const userCredential = await signInWithEmailAndPassword(auth, email, password);
      showToast(`Welcome back, ${userCredential.user.email}! Redirecting...`, 'success');
      setTimeout(() => { window.location.href = 'index.html'; }, 1500);
    } catch (error) {
      // Show friendly error messages
      let message = 'Login failed. Please try again.';
      if (error.code === 'auth/user-not-found')    message = 'No account found with this email.';
      if (error.code === 'auth/wrong-password')    message = 'Incorrect password. Please try again.';
      if (error.code === 'auth/invalid-email')     message = 'Invalid email address.';
      if (error.code === 'auth/too-many-requests') message = 'Too many attempts. Please try later.';
      if (error.code === 'auth/invalid-credential') message = 'Email or password is incorrect.';
      showToast(message, 'error');
    } finally {
      loginBtn.classList.remove('loading');
      loginBtn.innerHTML = '<i class="fas fa-sign-in-alt"></i> Login';
    }
  });

  // ── 6. Google Sign-In ──
  document.getElementById('googleBtn').addEventListener('click', async () => {
    const googleBtn = document.getElementById('googleBtn');
    googleBtn.disabled = true;
    googleBtn.textContent = 'Signing in...';

    try {
      const result = await signInWithPopup(auth, provider);
      const user   = result.user;
      showToast(`Welcome, ${user.displayName || user.email}! Redirecting...`, 'success');
      setTimeout(() => { window.location.href = 'index.html'; }, 1500);
    } catch (error) {
      let message = 'Google sign-in failed. Please try again.';
      if (error.code === 'auth/popup-closed-by-user') message = 'Sign-in popup was closed.';
      if (error.code === 'auth/popup-blocked')        message = 'Popup blocked. Allow popups for this site.';
      showToast(message, 'error');
    } finally {
      googleBtn.disabled = false;
      googleBtn.innerHTML = `
        <svg class="google-icon" viewBox="0 0 24 24">
          <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
          <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
          <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
          <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
        </svg>
        Continue with Google`;
    }
  });

  // ── 7. Password toggle ──
  const passToggle = document.getElementById('passToggle');
  const passInput  = document.getElementById('passwordInput');

  passToggle.addEventListener('click', () => {
    const isPassword = passInput.type === 'password';
    passInput.type   = isPassword ? 'text' : 'password';
    passToggle.innerHTML = isPassword
      ? '<i class="fas fa-eye-slash"></i>'
      : '<i class="fas fa-eye"></i>';
  });

  // ── 8. Header scroll effect ──
  const header = document.getElementById('headerHome');
  window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 50);
  });

  // ── 9. Mobile menu ──
  const hamburger    = document.getElementById('hamburgerMenu');
  const mobileMenu   = document.getElementById('mobileMenu');
  const mobileOverlay = document.getElementById('mobileOverlay');

  function toggleMobile() {
    hamburger.classList.toggle('active');
    mobileMenu.classList.toggle('active');
    mobileOverlay.classList.toggle('active');
    document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
  }

  hamburger.addEventListener('click', toggleMobile);
  mobileOverlay.addEventListener('click', toggleMobile);
  document.querySelectorAll('.mobile-menu a').forEach(link =>
    link.addEventListener('click', () => {
      if (mobileMenu.classList.contains('active')) toggleMobile();
    })
  );
</script>

</body>
</html>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Create your Subhan Printers account - Start ordering professional printing services.">
<title>Register | Subhan Printers</title>

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

*{margin:0;padding:0;box-sizing:border-box;}

html{scroll-behavior:smooth;}

body{
    font-family:'DM Sans',sans-serif;
    background:var(--bg);
    min-height:100vh;
    color:white;
    overflow-x:hidden;
    position:relative;
}

/* Background Blobs */
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
.bg-blob.purple{width:500px;height:500px;background:rgba(139,92,246,.4);top:-150px;left:-150px;}
.bg-blob.gold{width:450px;height:450px;background:rgba(245,158,11,.25);bottom:-150px;right:-150px;animation-delay:-10s;}

@keyframes blobFloat{
    0%{transform:translate(0,0) scale(1);}
    100%{transform:translate(30px,-30px) scale(1.1);}
}

/* Header */
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

/* Main Content */
.main-wrapper{min-height:100vh;display:flex;justify-content:center;align-items:center;padding:120px 20px 40px;position:relative;z-index:5;}

.register-container{
    width:1200px;max-width:95%;min-height:700px;
    display:grid;grid-template-columns:40% 60%;
    overflow:hidden;border-radius:30px;
    background:rgba(19,19,31,.85);backdrop-filter:blur(20px);-webkit-backdrop-filter:blur(20px);
    border:1px solid rgba(255,255,255,.08);
    box-shadow:0 20px 60px rgba(0,0,0,.5),0 0 40px rgba(139,92,246,.25);
    position:relative;
    opacity:0;transform:translateY(40px) scale(.95);
    animation:containerIn .8s cubic-bezier(.4,0,.2,1) forwards;animation-delay:.2s;
    will-change:transform,opacity;
}

@keyframes containerIn{to{opacity:1;transform:translateY(0) scale(1);}}

/* Left Side */
.left{background:rgba(19,19,31,.95);padding:50px 45px;display:flex;flex-direction:column;justify-content:center;overflow-y:auto;scrollbar-width:thin;scrollbar-color:var(--primary) transparent;}
.left::-webkit-scrollbar{width:4px;}
.left::-webkit-scrollbar-thumb{background:var(--primary);border-radius:10px;}

.logo-wrap{display:flex;align-items:center;gap:15px;margin-bottom:25px;opacity:0;transform:translateX(-20px);animation:slideRight .6s ease forwards;animation-delay:.5s;}
@keyframes slideRight{to{opacity:1;transform:translateX(0);}}
.logo{width:60px;height:60px;object-fit:contain;filter:drop-shadow(0 0 10px rgba(139,92,246,.5));}
.logo-text{font-family:'Playfair Display',serif;font-size:1.8rem;font-weight:800;}
.logo-text .brand-first{color:white;}
.logo-text .brand-second{color:var(--primary);}

.welcome{font-family:'Playfair Display',serif;font-size:2.4rem;margin-bottom:10px;opacity:0;transform:translateX(-20px);animation:slideRight .6s ease forwards;animation-delay:.65s;}
.welcome span{background:linear-gradient(135deg,var(--primary),var(--gold));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}

.subtitle{color:var(--muted);margin-bottom:30px;line-height:1.7;font-size:15px;opacity:0;transform:translateX(-20px);animation:slideRight .6s ease forwards;animation-delay:.8s;}

.form-group{margin-bottom:16px;position:relative;opacity:0;transform:translateY(15px);animation:slideUp .5s ease forwards;}
.form-group:nth-child(1){animation-delay:.9s;}
.form-group:nth-child(2){animation-delay:1s;}
.form-group:nth-child(3){animation-delay:1.05s;}
.form-group:nth-child(4){animation-delay:1.1s;}

@keyframes slideUp{to{opacity:1;transform:translateY(0);}}

.input-wrap{position:relative;width:100%;}
.input-wrap i.fa-lock,.input-wrap i.fa-envelope,.input-wrap i.fa-user,.input-wrap i.fa-eye{position:absolute;left:18px;top:50%;transform:translateY(-50%);color:var(--muted);font-size:16px;z-index:2;pointer-events:none;transition:color .3s ease;}
.input-wrap i.fa-eye{left:auto;right:15px;}

.form-group input[type="password"],.form-group input[type="email"],.form-group input[type="text"]{width:100%;padding:15px 50px 15px 48px;background:var(--surface2);border:1.5px solid rgba(255,255,255,.08);border-radius:14px;color:white;font-size:15px;font-family:inherit;outline:none;transition:all .3s ease;}
.form-group input:focus{border-color:var(--primary);background:rgba(139,92,246,.08);box-shadow:0 0 0 4px rgba(139,92,246,.12);}
.form-group input:focus ~ i.fa-lock,.form-group input:focus ~ i.fa-envelope,.form-group input:focus ~ i.fa-user,.input-wrap:focus-within i.fa-lock,.input-wrap:focus-within i.fa-envelope,.input-wrap:focus-within i.fa-user{color:var(--primary);}

.password-toggle{position:absolute;right:15px;top:50%;transform:translateY(-50%);color:var(--muted);cursor:pointer;font-size:16px;background:none;border:none;padding:5px 8px;z-index:3;display:flex;align-items:center;justify-content:center;transition:all .3s ease;}
.password-toggle:hover{color:var(--primary);}

.input-error{color:var(--error);font-size:12px;margin-top:5px;display:none;align-items:center;gap:5px;}
.input-error.show{display:flex;animation:shakeX .4s ease;}
@keyframes shakeX{0%,100%{transform:translateX(0);}25%{transform:translateX(-5px);}75%{transform:translateX(5px);}}

.terms-check{display:flex;align-items:flex-start;gap:10px;margin:10px 0 22px;font-size:13px;color:var(--muted);opacity:0;animation:slideUp .5s ease forwards;animation-delay:1.15s;}
.terms-check input[type="checkbox"]{appearance:none;-webkit-appearance:none;width:18px;height:18px;min-width:18px;border:2px solid var(--muted);border-radius:5px;cursor:pointer;position:relative;transition:all .3s ease;margin-top:2px;}
.terms-check input[type="checkbox"]:checked{background:var(--primary);border-color:var(--primary);}
.terms-check input[type="checkbox"]:checked::after{content:'✓';position:absolute;top:50%;left:50%;transform:translate(-50%,-50%);color:white;font-size:12px;font-weight:bold;}
.terms-check a{color:var(--primary);text-decoration:none;transition:color .3s ease;}
.terms-check a:hover{color:var(--gold);}

.register-btn{width:100%;padding:15px;border:none;border-radius:50px;background:linear-gradient(135deg,var(--primary),var(--primary2));color:white;font-weight:600;font-size:16px;font-family:inherit;cursor:pointer;position:relative;overflow:hidden;transition:all .3s ease;opacity:0;animation:slideUp .5s ease forwards;animation-delay:1.2s;box-shadow:0 8px 25px rgba(139,92,246,.3);}
.register-btn::before{content:'';position:absolute;top:0;left:-100%;width:100%;height:100%;background:linear-gradient(90deg,transparent,rgba(255,255,255,.2),transparent);transition:left .6s ease;}
.register-btn:hover{transform:translateY(-2px);box-shadow:0 12px 35px rgba(139,92,246,.5);}
.register-btn:hover::before{left:100%;}
.register-btn:active{transform:translateY(0);}
.register-btn.loading{pointer-events:none;color:transparent;}
.register-btn.loading::after{content:'';position:absolute;top:50%;left:50%;width:24px;height:24px;margin:-12px 0 0 -12px;border:3px solid rgba(255,255,255,.3);border-top-color:white;border-radius:50%;animation:spin .8s linear infinite;}
@keyframes spin{to{transform:rotate(360deg);}}

.divider{text-align:center;margin:22px 0;color:var(--muted);position:relative;font-size:13px;opacity:0;animation:slideUp .5s ease forwards;animation-delay:1.3s;}
.divider::before,.divider::after{content:'';position:absolute;width:calc(50% - 25px);height:1px;background:rgba(255,255,255,.08);top:50%;}
.divider::before{left:0;}.divider::after{right:0;}

.google-btn{width:100%;padding:14px;background:white;border:none;border-radius:50px;cursor:pointer;font-weight:600;font-size:15px;font-family:inherit;color:#222;transition:all .3s ease;display:flex;align-items:center;justify-content:center;gap:10px;opacity:0;animation:slideUp .5s ease forwards;animation-delay:1.4s;}
.google-btn:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(0,0,0,.3);}
.google-icon{width:20px;height:20px;}

.login-link{text-align:center;margin-top:22px;color:var(--muted);font-size:14px;opacity:0;animation:slideUp .5s ease forwards;animation-delay:1.5s;}
.login-link a{color:var(--gold);text-decoration:none;font-weight:600;transition:color .3s ease;}
.login-link a:hover{color:#fbbf24;}

/* Right Side */
.right{position:relative;display:flex;justify-content:center;align-items:center;overflow:hidden;}
.right-bg{position:absolute;inset:0;background:linear-gradient(135deg,rgba(24,13,46,0.603)),url('images/hero baneer2.png');background-size:cover;background-position:center;background-repeat:no-repeat;z-index:1;}
.shape{position:absolute;border-radius:50%;z-index:2;opacity:.15;will-change:transform;}
.shape-1{width:200px;height:200px;background:var(--gold);top:-50px;right:-50px;animation:shapeFloat1 15s ease-in-out infinite;}
.shape-2{width:150px;height:150px;background:var(--primary);bottom:-30px;left:-30px;animation:shapeFloat2 18s ease-in-out infinite;}
.shape-3{width:80px;height:80px;background:white;top:40%;left:10%;animation:shapeFloat3 12s ease-in-out infinite;}
@keyframes shapeFloat1{0%,100%{transform:translate(0,0) rotate(0);}50%{transform:translate(-30px,40px) rotate(180deg);}}
@keyframes shapeFloat2{0%,100%{transform:translate(0,0) rotate(0);}50%{transform:translate(40px,-30px) rotate(-180deg);}}
@keyframes shapeFloat3{0%,100%{transform:translate(0,0) scale(1);}50%{transform:translate(20px,-20px) scale(1.3);}}

.brand-box{position:relative;z-index:3;width:85%;max-width:500px;text-align:center;padding:40px 30px;background:rgba(255,255,255,.05);backdrop-filter:blur(4px);border-radius:25px;border:1px solid rgba(255,255,255,.1);opacity:0;transform:translateX(30px);animation:brandIn .8s cubic-bezier(.4,0,.2,1) forwards;animation-delay:.8s;will-change:transform,opacity;}
@keyframes brandIn{to{opacity:1;transform:translateX(0);}}
.brand-logo{width:110px;height:110px;object-fit:contain;margin:0 auto 20px;display:block;filter:drop-shadow(0 10px 30px rgba(139,92,246,.5));animation:logoPulse 3s ease-in-out infinite;}
@keyframes logoPulse{0%,100%{transform:scale(1);filter:drop-shadow(0 10px 30px rgba(139,92,246,.5));}50%{transform:scale(1.05);filter:drop-shadow(0 15px 40px rgba(139,92,246,.7));}}
.brand-title{font-family:'Playfair Display',serif;font-size:2.5rem;margin-bottom:15px;color:white;letter-spacing:1px;}
.brand-description{color:#e8e8f0;line-height:1.8;font-size:15px;}
.brand-stats{display:grid;grid-template-columns:repeat(3,1fr);gap:15px;margin-top:25px;padding-top:25px;border-top:1px solid rgba(255,255,255,.1);}
.stat-item{text-align:center;}
.stat-num{font-family:'Playfair Display',serif;font-size:1.6rem;font-weight:800;background:linear-gradient(135deg,var(--gold),var(--primary));-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;}
.stat-label{font-size:11px;color:var(--muted);text-transform:uppercase;letter-spacing:.5px;margin-top:4px;}

/* Toast */
.toast{position:fixed;top:100px;right:30px;background:var(--surface);border:1px solid rgba(255,255,255,.1);border-radius:15px;padding:15px 20px;display:flex;align-items:center;gap:12px;z-index:2000;box-shadow:0 15px 40px rgba(0,0,0,.4);transform:translateX(calc(100% + 40px));transition:transform .4s cubic-bezier(.4,0,.2,1);min-width:280px;}
.toast.show{transform:translateX(0);}
.toast.success{border-left:4px solid var(--success);}
.toast.error{border-left:4px solid var(--error);}
.toast-icon{width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:16px;}
.toast.success .toast-icon{background:rgba(16,185,129,.15);color:var(--success);}
.toast.error .toast-icon{background:rgba(239,68,68,.15);color:var(--error);}
.toast-message{font-size:14px;color:var(--text);}

/* Password Strength */
.password-strength{margin-top:8px;display:none;opacity:0;animation:slideUp .3s ease forwards;}
.password-strength.show{display:block;}
.strength-bar{height:4px;background:rgba(255,255,255,.1);border-radius:4px;overflow:hidden;margin-bottom:5px;}
.strength-fill{height:100%;width:0;border-radius:4px;transition:all .3s ease;}
.strength-text{font-size:11px;color:var(--muted);}

/* Responsive */
@media(max-width:1024px){.register-container{grid-template-columns:1fr;min-height:auto;max-width:550px;}.right{display:none;}.left{padding:40px 30px;}}
@media(max-width:900px){.nav-links{display:none;}.nav-actions .btn-login,.nav-actions .btn-order{display:none;}.hamburger-menu{display:flex;}}
@media(max-width:768px){.welcome{font-size:2rem;}.main-wrapper{padding:100px 15px 30px;}.left{padding:35px 25px;}.logo{width:50px;height:50px;}.toast{right:15px;left:15px;min-width:auto;}}
@media(max-width:400px){.terms-check{flex-direction:column;gap:8px;}}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important;}}
</style>
</head>
<body>

<div class="bg-blob purple"></div>
<div class="bg-blob gold"></div>

<!-- Header -->
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

<!-- Main Content -->
<main class="main-wrapper">
    <div class="register-container">

        <!-- Left Side -->
        <div class="left">

            <div class="logo-wrap">
                <img src="images/logo.png" alt="Logo" class="logo">
                <span class="logo-text"><span class="brand-first">Subhan</span> <span class="brand-second">Printers</span></span>
            </div>

            <h1 class="welcome">Create <span>Account</span></h1>

            <p class="subtitle">
                Join Subhan Printers and start ordering professional printing services today.
            </p>

            <form id="registerForm" novalidate>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="text" id="nameInput" placeholder="Full Name" autocomplete="name" required>
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="input-error" id="nameError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Please enter your full name</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="email" id="emailInput" placeholder="Email Address" autocomplete="email" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                    <div class="input-error" id="emailError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Please enter a valid email address</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="password" id="passwordInput" placeholder="Password" autocomplete="new-password" required>
                        <i class="fas fa-lock"></i>
                        <button type="button" class="password-toggle" id="passToggle" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="password-strength" id="passwordStrength">
                        <div class="strength-bar"><div class="strength-fill" id="strengthFill"></div></div>
                        <div class="strength-text" id="strengthText">Password strength</div>
                    </div>
                    <div class="input-error" id="passwordError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Password must be at least 6 characters</span>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrap">
                        <input type="password" id="confirmPasswordInput" placeholder="Confirm Password" autocomplete="new-password" required>
                        <i class="fas fa-lock"></i>
                        <button type="button" class="password-toggle" id="confirmPassToggle" aria-label="Show password">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="input-error" id="confirmPasswordError">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>Passwords do not match</span>
                    </div>
                </div>

                <label class="terms-check">
                    <input type="checkbox" id="termsCheck" required>
                    <span>I agree to the <a href="#">Terms of Service</a> and <a href="#">Privacy Policy</a></span>
                </label>

                <button type="submit" class="register-btn" id="registerBtn">
                    <i class="fas fa-user-plus"></i> Create Account
                </button>

                <div class="divider">OR CONTINUE WITH</div>

                <button type="button" class="google-btn" id="googleBtn">
                    <svg class="google-icon" viewBox="0 0 24 24">
                        <path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                        <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                        <path fill="#FBBC05" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                        <path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                    </svg>
                    Continue With Google
                </button>

                <div class="login-link">
                    Already have an account?
                    <a href="login.html">Sign In</a>
                </div>

            </form>

        </div>

        <!-- Right Side -->
        <div class="right">
            <div class="right-bg"></div>
            <div class="shape shape-1"></div>
            <div class="shape shape-2"></div>
            <div class="shape shape-3"></div>

            <div class="brand-box">
                <img src="images/logo.png" alt="Subhan Printers" class="brand-logo">
                <h2 class="brand-title">SUBHAN PRINTERS</h2>
                <p class="brand-description">
                    Professional graphic designing and printing services — wedding cards, packaging, flex banners & more since 1998.
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

<!-- Toast -->
<div class="toast" id="toast">
    <div class="toast-icon"><i class="fas fa-check"></i></div>
    <div class="toast-message">Account created successfully!</div>
</div>

<script>
// Header scroll
const header = document.getElementById('headerHome');
window.addEventListener('scroll', () => {
    header.classList.toggle('scrolled', window.scrollY > 50);
}, { passive: true });

// Hamburger menu
const hamburger = document.getElementById('hamburgerMenu');
const mobileMenu = document.getElementById('mobileMenu');
const mobileOverlay = document.getElementById('mobileOverlay');

function toggleMobile(){
    hamburger.classList.toggle('active');
    mobileMenu.classList.toggle('active');
    mobileOverlay.classList.toggle('active');
    document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
}

hamburger.addEventListener('click', toggleMobile);
mobileOverlay.addEventListener('click', toggleMobile);

document.querySelectorAll('.mobile-menu a:not(#mobilePortfolioToggle)').forEach(link => {
    link.addEventListener('click', () => { if(mobileMenu.classList.contains('active')) toggleMobile(); });
});

const mobilePortfolioToggle = document.getElementById('mobilePortfolioToggle');
const mobilePortfolioDropdown = document.getElementById('mobilePortfolioDropdown');
if(mobilePortfolioToggle){
    mobilePortfolioToggle.addEventListener('click', (e) => {
        e.preventDefault();
        mobilePortfolioDropdown.classList.toggle('show');
        const icon = mobilePortfolioToggle.querySelector('.fa-chevron-down');
        if(icon) icon.style.transform = mobilePortfolioDropdown.classList.contains('show') ? 'rotate(180deg)' : 'rotate(0)';
    });
}

// Password toggles
const passToggle = document.getElementById('passToggle');
const passInput = document.getElementById('passwordInput');
const confirmPassToggle = document.getElementById('confirmPassToggle');
const confirmPassInput = document.getElementById('confirmPasswordInput');

function setupPasswordToggle(toggleBtn, inputField){
    toggleBtn.addEventListener('click', () => {
        const type = inputField.type === 'password' ? 'text' : 'password';
        inputField.type = type;
        toggleBtn.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
    });
}
setupPasswordToggle(passToggle, passInput);
setupPasswordToggle(confirmPassToggle, confirmPassInput);

// Password strength meter
const strengthFill = document.getElementById('strengthFill');
const strengthText = document.getElementById('strengthText');
const passwordStrength = document.getElementById('passwordStrength');

passInput.addEventListener('input', () => {
    const val = passInput.value;
    if(val.length === 0){
        passwordStrength.classList.remove('show');
        return;
    }
    passwordStrength.classList.add('show');

    let score = 0;
    if(val.length >= 6) score++;
    if(val.length >= 10) score++;
    if(/[A-Z]/.test(val)) score++;
    if(/[0-9]/.test(val)) score++;
    if(/[^A-Za-z0-9]/.test(val)) score++;

    const levels = [
        { width: '20%', color: '#ef4444', text: 'Very Weak' },
        { width: '40%', color: '#f97316', text: 'Weak' },
        { width: '60%', color: '#eab308', text: 'Fair' },
        { width: '80%', color: '#84cc16', text: 'Strong' },
        { width: '100%', color: '#10b981', text: 'Very Strong' }
    ];

    const level = levels[Math.min(score, 4)];
    strengthFill.style.width = level.width;
    strengthFill.style.background = level.color;
    strengthText.textContent = level.text;
    strengthText.style.color = level.color;
});

// Form validation
const form = document.getElementById('registerForm');
const nameInput = document.getElementById('nameInput');
const emailInput = document.getElementById('emailInput');
const nameError = document.getElementById('nameError');
const emailError = document.getElementById('emailError');
const passwordError = document.getElementById('passwordError');
const confirmPasswordError = document.getElementById('confirmPasswordError');
const registerBtn = document.getElementById('registerBtn');

function validateEmail(email){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email); }

function showError(input, errorEl){ input.style.borderColor = 'var(--error)'; errorEl.classList.add('show'); }
function clearError(input, errorEl){ input.style.borderColor = ''; errorEl.classList.remove('show'); }

[nameInput, emailInput, passInput, confirmPassInput].forEach((input, i) => {
    const errors = [nameError, emailError, passwordError, confirmPasswordError];
    input.addEventListener('input', () => clearError(input, errors[i]));
});

form.addEventListener('submit', (e) => {
    e.preventDefault();
    let valid = true;

    if(nameInput.value.trim().length < 2){ showError(nameInput, nameError); valid = false; }
    if(!validateEmail(emailInput.value.trim())){ showError(emailInput, emailError); valid = false; }
    if(passInput.value.length < 6){ showError(passInput, passwordError); valid = false; }
    if(confirmPassInput.value !== passInput.value || confirmPassInput.value === ''){ showError(confirmPassInput, confirmPasswordError); valid = false; }
    if(!document.getElementById('termsCheck').checked){
        showToast('Please accept the Terms of Service', 'error');
        valid = false;
    }

    if(valid){
        registerBtn.classList.add('loading');
        setTimeout(() => {
            registerBtn.classList.remove('loading');
            showToast('Account created successfully! Redirecting...', 'success');
            setTimeout(() => { window.location.href = 'login.html'; }, 2000);
        }, 1500);
    }
});

document.getElementById('googleBtn').addEventListener('click', () => {
    showToast('Google Sign-Up coming soon!', 'success');
});

// Toast
function showToast(message, type='success'){
    const toast = document.getElementById('toast');
    const icon = toast.querySelector('.toast-icon i');
    toast.className = 'toast ' + type;
    toast.querySelector('.toast-message').textContent = message;
    icon.className = type === 'success' ? 'fas fa-check' : 'fas fa-times';
    toast.classList.add('show');
    setTimeout(() => toast.classList.remove('show'), 3500);
}

// Input focus effects
document.querySelectorAll('.form-group input').forEach(input => {
    input.addEventListener('focus', function(){
        this.parentElement.style.transform = 'scale(1.02)';
        this.parentElement.style.transition = 'transform .2s ease';
    });
    input.addEventListener('blur', function(){
        this.parentElement.style.transform = 'scale(1)';
    });
});
</script>

</body>
</html>

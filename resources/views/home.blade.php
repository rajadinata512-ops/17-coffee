<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>17 Coffee Kopi Enak, Pilihan Terbaik</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;1,400;1,500&family=Satisfy&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
<style>
:root {
 --espresso:#0f0603; --roast:#1c0d05; --bark:#2f1308;
 --caramel:#b86a1a; --honey:#d4893e; --gold:#e8a64d;
 --cream:#f5e2c4; --foam:#fdf6ec; --mist:#fefbf5;
 --mocha:#6b3a11; --latte:#c4a07c; --chalk:#fffdf9; --warm-grey:#8a7060;
}
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html{scroll-behavior:smooth}
body{background:var(--mist);color:var(--espresso);font-family:'DM Sans',sans-serif;overflow-x:hidden}
::-webkit-scrollbar{width:5px}
::-webkit-scrollbar-track{background:var(--foam)}
::-webkit-scrollbar-thumb{background:var(--caramel);border-radius:3px}
.script{font-family:'Satisfy',cursive}
.serif{font-family:'Playfair Display',serif}
.reveal{opacity:0;transform:translateY(30px);transition:opacity .9s ease,transform .9s ease}
.reveal.visible{opacity:1;transform:none}
.reveal-l{opacity:0;transform:translateX(-30px);transition:opacity .9s ease,transform .9s ease}
.reveal-l.visible{opacity:1;transform:none}
.reveal-r{opacity:0;transform:translateX(30px);transition:opacity .9s ease,transform .9s ease}
.reveal-r.visible{opacity:1;transform:none}

/* NAVBAR */
nav{position:fixed;top:0;left:0;width:100%;z-index:100;background:rgba(15,6,3,.94);backdrop-filter:blur(20px) saturate(180%);border-bottom:1px solid rgba(216,137,62,.14);transition:all .4s}
.nav-inner{max-width:1380px;margin:0 auto;padding:0 2.5rem;height:76px;display:flex;align-items:center;justify-content:space-between}
.brand{display:flex;align-items:center;gap:.9rem;text-decoration:none}
.brand-logo{width:44px;height:44px;background:linear-gradient(145deg,var(--caramel),var(--gold));border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.25rem;flex-shrink:0;box-shadow:0 4px 16px rgba(184,106,26,.35);overflow:hidden}
.brand-name{font-family:'Satisfy',cursive;font-size:2.1rem;color:var(--cream);line-height:1;letter-spacing:.5px}
.brand-sub{font-size:.6rem;color:rgba(196,160,124,.55);letter-spacing:2.5px;text-transform:uppercase;margin-top:3px}
.nav-links{display:flex;align-items:center;gap:2.75rem}
.nav-links a{color:rgba(245,226,196,.6);font-size:.85rem;letter-spacing:.8px;text-decoration:none;transition:color .3s;font-weight:400}
.nav-links a:hover{color:var(--honey)}
.nav-auth{display:flex;align-items:center;gap:.8rem}
.btn-login{padding:.52rem 1.5rem;border:1px solid rgba(184,106,26,.35);color:var(--cream);border-radius:50px;font-size:.82rem;font-weight:500;text-decoration:none;transition:all .3s;letter-spacing:.4px;background:transparent;cursor:pointer;font-family:'DM Sans',sans-serif}
.btn-login:hover{border-color:var(--honey);color:var(--honey)}
.btn-register{padding:.52rem 1.5rem;background:linear-gradient(135deg,var(--caramel),var(--gold));color:#fff;border-radius:50px;font-size:.82rem;font-weight:600;text-decoration:none;transition:all .3s;letter-spacing:.4px;border:none;cursor:pointer;box-shadow:0 4px 16px rgba(184,106,26,.3);font-family:'DM Sans',sans-serif}
.btn-register:hover{transform:translateY(-1px);box-shadow:0 6px 24px rgba(184,106,26,.45)}
.btn-logout{padding:.52rem 1.5rem;border:1px solid rgba(245,226,196,.2);color:rgba(245,226,196,.7);border-radius:50px;font-size:.82rem;font-weight:500;background:transparent;cursor:pointer;font-family:'DM Sans',sans-serif;transition:all .3s}
.btn-logout:hover{border-color:rgba(245,226,196,.5);color:var(--cream)}
.nav-toggle{display:none;background:none;border:1px solid rgba(184,106,26,.35);border-radius:8px;padding:.4rem .6rem;cursor:pointer;color:var(--cream)}
.nav-mobile{display:none;background:rgba(15,6,3,.97);padding:1.5rem 2.5rem;border-top:1px solid rgba(184,106,26,.1);backdrop-filter:blur(20px)}
.nav-mobile a{display:block;padding:.65rem 0;color:rgba(245,226,196,.75);font-size:.95rem;text-decoration:none;border:none;background:none;cursor:pointer;width:100%;text-align:left}
.nav-mobile a:hover{color:var(--honey)}
.nav-mobile .m-btns{display:flex;gap:.75rem;margin-top:.85rem}
.nav-mobile .m-btns a{padding:.65rem 1.25rem;text-align:center;border-radius:50px;font-weight:600}

/* HERO */
.hero{min-height:100vh;display:grid;grid-template-columns:1fr 1fr;align-items:center;padding-top:76px;background:var(--espresso);overflow:hidden;position:relative}
.hero::before{content:'';position:absolute;inset:0;background:radial-gradient(ellipse 55% 70% at 80% 55%,rgba(184,106,26,.07) 0%,transparent 65%),radial-gradient(ellipse 30% 40% at 15% 20%,rgba(184,106,26,.04) 0%,transparent 60%);pointer-events:none}
.grain-overlay{position:absolute;inset:0;opacity:.035;background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");background-size:200px;pointer-events:none}
.hero-left{padding:5.5rem 3rem 5.5rem 5rem;z-index:2;position:relative}
.hero-eyebrow{display:inline-flex;align-items:center;gap:.55rem;background:rgba(184,106,26,.1);border:1px solid rgba(184,106,26,.25);color:var(--honey);padding:.4rem 1.1rem;border-radius:50px;font-size:.72rem;letter-spacing:2.5px;text-transform:uppercase;font-weight:500;margin-bottom:2.25rem}
.hero-eyebrow-dot{width:6px;height:6px;border-radius:50%;background:var(--gold);animation:blink 2.2s ease-in-out infinite}
@keyframes blink{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.4;transform:scale(.7)}}
.hero-headline{font-family:'Satisfy',cursive;font-size:clamp(4.8rem,7.5vw,7.5rem);color:var(--cream);line-height:1.02;margin-bottom:1.75rem}
.hero-headline em{font-style:italic;color:var(--gold);font-family:'Playfair Display',serif}
.hero-sub{font-family:'Playfair Display',serif;font-size:clamp(1rem,1.6vw,1.25rem);color:rgba(196,160,124,.75);line-height:1.9;max-width:470px;margin-bottom:3rem;font-weight:300;font-style:italic}
.hero-actions{display:flex;gap:1rem;flex-wrap:wrap}
.btn-primary{padding:.95rem 2.4rem;background:linear-gradient(135deg,var(--caramel),var(--gold));color:#fff;border-radius:50px;font-weight:600;font-size:.88rem;letter-spacing:.5px;text-decoration:none;transition:all .35s;border:none;cursor:pointer;box-shadow:0 6px 24px rgba(184,106,26,.35);font-family:'DM Sans',sans-serif}
.btn-primary:hover{transform:translateY(-2px);box-shadow:0 10px 32px rgba(184,106,26,.5)}
.btn-outline{padding:.95rem 2.4rem;border:1px solid rgba(245,226,196,.22);color:var(--cream);border-radius:50px;font-weight:400;font-size:.88rem;letter-spacing:.5px;text-decoration:none;transition:all .35s;cursor:pointer;background:transparent;font-family:'DM Sans',sans-serif}
.btn-outline:hover{border-color:var(--honey);color:var(--honey)}
.hero-stats{display:flex;gap:2.75rem;margin-top:3.75rem;padding-top:2.75rem;border-top:1px solid rgba(245,226,196,.07)}
.stat-num{font-family:'Playfair Display',serif;font-size:1.9rem;color:var(--cream);font-weight:600;line-height:1}
.stat-lbl{font-size:.68rem;color:rgba(196,160,124,.55);letter-spacing:2px;text-transform:uppercase;margin-top:.3rem}
.hero-right{position:relative;display:flex;align-items:center;justify-content:center;height:100vh;min-height:600px;overflow:hidden}
.slideshow{position:relative;width:340px;height:500px}
.slide{position:absolute;inset:0;opacity:0;transition:opacity 1.2s ease;border-radius:28px;overflow:hidden;box-shadow:0 40px 80px rgba(0,0,0,.55),0 0 0 1px rgba(255,255,255,.06)}
.slide.active{opacity:1}
.slide img{width:100%;height:100%;object-fit:cover;transform:scale(1.05);transition:transform 8s ease}
.slide.active img{transform:scale(1)}
.slide-overlay{position:absolute;inset:0;background:linear-gradient(180deg,rgba(0,0,0,.0) 40%,rgba(15,6,3,.65) 100%)}
.slide-caption{position:absolute;bottom:1.5rem;left:1.5rem;right:1.5rem;font-family:'Playfair Display',serif;font-style:italic;font-size:.95rem;color:rgba(245,226,196,.85);letter-spacing:.5px}
.slide-dots{position:absolute;bottom:-40px;left:50%;transform:translateX(-50%);display:flex;gap:.6rem}
.dot{width:6px;height:6px;border-radius:50%;background:rgba(196,160,124,.3);cursor:pointer;transition:all .4s}
.dot.active{background:var(--gold);transform:scale(1.4)}
.slide-glow{position:absolute;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(184,106,26,.2),transparent 70%);top:50%;left:50%;transform:translate(-50%,-50%);animation:glow 3.5s ease-in-out infinite;pointer-events:none;z-index:-1}
@keyframes glow{0%,100%{opacity:.5;transform:translate(-50%,-50%) scale(1)}50%{opacity:1;transform:translate(-50%,-50%) scale(1.15)}}
.fb{position:absolute;background:rgba(253,246,236,.95);color:var(--espresso);padding:.5rem 1rem;border-radius:50px;font-size:.76rem;font-weight:600;display:flex;align-items:center;gap:.4rem;box-shadow:0 8px 28px rgba(0,0,0,.18);animation:floatBadge 3.5s ease-in-out infinite;white-space:nowrap;z-index:10}
.fb .fd{width:8px;height:8px;border-radius:50%;background:var(--caramel);flex-shrink:0}
.fb.b1{top:60px;left:-25px;animation-delay:0s}
.fb.b2{top:190px;right:-35px;animation-delay:.9s}
.fb.b3{bottom:110px;left:-30px;animation-delay:1.6s}
@keyframes floatBadge{0%,100%{transform:translateY(0)}50%{transform:translateY(-9px)}}
.ring{position:absolute;border:1px solid rgba(184,106,26,.12);border-radius:50%;top:50%;left:50%;transform:translate(-50%,-50%);animation:ringOut 7s linear infinite}
.ring:nth-child(1){width:220px;height:220px;animation-delay:0s}
.ring:nth-child(2){width:320px;height:320px;animation-delay:2.3s}
.ring:nth-child(3){width:420px;height:420px;animation-delay:4.6s}
@keyframes ringOut{0%{opacity:.3;transform:translate(-50%,-50%) scale(.85)}100%{opacity:0;transform:translate(-50%,-50%) scale(1.2)}}

/* MARQUEE */
.marquee-strip{background:linear-gradient(135deg,var(--caramel),var(--gold));padding:.6rem 0;overflow:hidden;white-space:nowrap}
.marquee-track{display:inline-flex;gap:2.5rem;animation:marquee 25s linear infinite}
.marquee-track span{font-size:.75rem;letter-spacing:2.5px;text-transform:uppercase;color:#fff;font-weight:500;opacity:.85;flex-shrink:0}
.marquee-track .sep{opacity:.4}
@keyframes marquee{from{transform:translateX(0)}to{transform:translateX(-50%)}}

/* ABOUT */
.about{background:var(--foam);padding:9rem 2.5rem}
.section-inner{max-width:1380px;margin:0 auto}
.section-eyebrow{font-size:.7rem;letter-spacing:3px;text-transform:uppercase;color:var(--caramel);font-weight:600;margin-bottom:1rem}
.section-title{font-family:'Playfair Display',serif;font-size:clamp(2.1rem,3.8vw,3.4rem);color:var(--espresso);line-height:1.15;font-weight:500}
.section-title em{font-style:italic;color:var(--caramel)}
.section-desc{color:var(--warm-grey);font-size:1rem;line-height:1.85;max-width:520px;margin-top:1.1rem}
.about-header{display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:end;margin-bottom:5.5rem}
.about-cards{display:grid;grid-template-columns:repeat(3,1fr);gap:2.25rem}
.about-card{background:#fff;border-radius:28px;padding:2.75rem;border:1px solid rgba(184,106,26,.1);transition:transform .45s,box-shadow .45s;position:relative;overflow:hidden}
.about-card::before{content:'';position:absolute;top:0;left:0;right:0;height:3px;background:linear-gradient(90deg,var(--caramel),var(--gold));transform:scaleX(0);transition:transform .45s;transform-origin:left}
.about-card:hover::before{transform:scaleX(1)}
.about-card:hover{transform:translateY(-8px);box-shadow:0 24px 60px rgba(184,106,26,.12)}
.card-icon{width:54px;height:54px;background:linear-gradient(135deg,rgba(184,106,26,.12),rgba(212,137,62,.06));border-radius:16px;display:flex;align-items:center;justify-content:center;font-size:1.5rem;margin-bottom:1.75rem;overflow:hidden}
.card-icon img{width:100%;height:100%;object-fit:cover;display:block}
.card-title{font-family:'Playfair Display',serif;font-size:1.4rem;color:var(--espresso);margin-bottom:.75rem;font-weight:600}
.card-desc{color:var(--warm-grey);font-size:.9rem;line-height:1.85}

/* MENU */
.menu{background:var(--roast);padding:9rem 2.5rem}
.menu .section-title{color:var(--cream)}
.menu .section-eyebrow{color:var(--honey)}
.menu .section-desc{color:var(--latte)}
.menu-header{display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:5.5rem;flex-wrap:wrap;gap:2rem}
.menu-grid{display:grid;grid-template-columns:repeat(4,1fr);gap:1.75rem}
.menu-card{background:rgba(255,255,255,.04);border:1px solid rgba(245,226,196,.07);border-radius:24px;overflow:hidden;transition:transform .4s,background .4s;cursor:pointer}
.menu-card:hover{transform:translateY(-7px);background:rgba(255,255,255,.07)}
.card-img-wrap{height:220px;position:relative;overflow:hidden}
.card-img-wrap img{width:100%;height:100%;object-fit:cover;transition:transform .6s ease}
.menu-card:hover .card-img-wrap img{transform:scale(1.08)}
.card-img-placeholder{height:220px;display:flex;align-items:center;justify-content:center;font-size:4rem;background:rgba(184,106,26,.08)}
.card-cat-badge{position:absolute;top:.85rem;left:.85rem;background:rgba(15,6,3,.7);backdrop-filter:blur(8px);color:var(--honey);font-size:.65rem;letter-spacing:2px;text-transform:uppercase;padding:.3rem .75rem;border-radius:50px;border:1px solid rgba(232,166,77,.2)}
.card-info{padding:1.35rem 1.5rem}
.card-cat{font-size:.66rem;letter-spacing:2.5px;text-transform:uppercase;color:var(--honey);margin-bottom:.4rem}
.card-name{font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--cream);font-weight:500;margin-bottom:.35rem}
.card-detail{font-size:.8rem;color:var(--latte);line-height:1.5;margin-bottom:1rem}
.card-footer{display:flex;justify-content:space-between;align-items:center}
.card-price{font-family:'Playfair Display',serif;font-size:1.25rem;color:var(--cream);font-weight:600}
.card-order{background:linear-gradient(135deg,var(--caramel),var(--gold));color:#fff;border:none;padding:.42rem 1.1rem;border-radius:50px;font-size:.76rem;font-weight:600;cursor:pointer;transition:all .3s;font-family:'DM Sans',sans-serif}
.card-order:hover{transform:scale(1.06);box-shadow:0 4px 14px rgba(184,106,26,.4)}
.empty-menu{grid-column:1/-1;text-align:center;padding:5rem 2rem;color:rgba(196,160,124,.5)}
.empty-menu-icon{font-size:4rem;margin-bottom:1.5rem}
.empty-menu h3{font-family:'Playfair Display',serif;font-size:1.6rem;color:var(--cream);margin-bottom:.75rem}

/* CTA */
.cta{background:var(--foam);padding:9rem 2.5rem}
.cta-box{max-width:1380px;margin:0 auto;background:var(--espresso);border-radius:44px;padding:6.5rem 5.5rem;position:relative;overflow:hidden;display:grid;grid-template-columns:1fr auto;align-items:center;gap:4rem;border:1px solid rgba(184,106,26,.12)}
.cta-box::before{content:'';position:absolute;top:-100px;right:-100px;width:440px;height:440px;border-radius:50%;background:radial-gradient(circle,rgba(184,106,26,.13),transparent 65%)}
.cta-box::after{content:'';position:absolute;bottom:-80px;left:10%;width:280px;height:280px;border-radius:50%;background:radial-gradient(circle,rgba(184,106,26,.06),transparent 65%)}
.cta-eyebrow{font-size:.7rem;letter-spacing:3px;text-transform:uppercase;color:var(--honey);margin-bottom:1.1rem}
.cta-title{font-family:'Satisfy',cursive;font-size:clamp(3rem,5vw,5.5rem);color:var(--cream);line-height:1.1;margin-bottom:1.5rem}
.cta-desc{color:var(--latte);font-size:1rem;line-height:1.95;max-width:490px}
.cta-actions{display:flex;flex-direction:column;gap:1rem;min-width:210px;z-index:1;position:relative}

/* FOOTER */
footer{background:var(--espresso);color:var(--latte);padding:5.5rem 2.5rem 2.5rem;border-top:1px solid rgba(184,106,26,.1)}
.footer-inner{max-width:1380px;margin:0 auto;display:grid;grid-template-columns:2fr 1fr 1fr 1.5fr;gap:4.5rem}
.footer-brand-name{font-family:'Satisfy',cursive;font-size:2.6rem;color:var(--cream);line-height:1}
.footer-desc{font-size:.87rem;line-height:1.95;color:rgba(196,160,124,.55);margin-top:1.25rem}
.footer-heading{font-family:'Playfair Display',serif;font-size:1.05rem;color:var(--cream);margin-bottom:1.6rem;font-weight:500}
.footer-links{display:flex;flex-direction:column;gap:.8rem}
.footer-links a{color:rgba(196,160,124,.55);font-size:.86rem;text-decoration:none;transition:color .3s;display:flex;align-items:center;gap:.5rem}
.footer-links a::before{content:'';opacity:.3;font-size:.72rem;transition:opacity .3s}
.footer-links a:hover{color:var(--honey)}
.footer-links a:hover::before{opacity:1}
.footer-bottom{max-width:1380px;margin:0 auto;padding-top:2.25rem;margin-top:4.5rem;border-top:1px solid rgba(184,106,26,.1);display:flex;justify-content:space-between;align-items:center;font-size:.78rem;color:rgba(196,160,124,.38)}

/* TOAST */
.toast{position:fixed;bottom:2.5rem;left:50%;transform:translateX(-50%) translateY(20px);background:var(--roast);color:var(--cream);padding:.85rem 2rem;border-radius:50px;font-size:.85rem;border:1px solid rgba(184,106,26,.25);box-shadow:0 12px 36px rgba(0,0,0,.35);opacity:0;transition:all .45s;z-index:999;pointer-events:none;white-space:nowrap}
.toast.show{opacity:1;transform:translateX(-50%) translateY(0)}

/* RESPONSIVE */
@media(max-width:1100px){
 .hero{grid-template-columns:1fr}
 .hero-right{height:480px;min-height:unset}
 .hero-left{padding:5rem 2.5rem}
 .about-header,.about-cards{grid-template-columns:1fr}
 .menu-grid{grid-template-columns:repeat(2,1fr)}
 .footer-inner{grid-template-columns:1fr 1fr;gap:3rem}
 .cta-box{grid-template-columns:1fr;text-align:center;padding:4.5rem 3rem}
 .cta-actions{flex-direction:row;justify-content:center}
}
@media(max-width:700px){
 .nav-links,.nav-auth{display:none}
 .nav-toggle{display:block}
 .hero{grid-template-columns:1fr;padding-top:76px}
 .hero-right{display:none}
 .hero-left{padding:4rem 1.75rem 4rem}
 .menu-grid{grid-template-columns:1fr}
 .footer-inner{grid-template-columns:1fr}
 .about-cards{grid-template-columns:1fr}
}
</style>
</head>
<body id="home">

<!-- NAVBAR -->
<nav id="navbar">
 <div class="nav-inner">
  <a href="/" class="brand">
   <div class="brand-logo"><img src="{{ asset('images/cappuccino.jpg') }}" alt="17 Coffee Logo" style="width:100%;height:100%;object-fit:cover;border-radius:10px;display:block;"></div>
   <div>
    <div class="brand-name">17 Coffee</div>
    <div class="brand-sub">Specialty Drinks</div>
   </div>
  </a>
  <div class="nav-links">
   <a href="#home">Beranda</a>
   <a href="#about">Tentang</a>
   <a href="#menu">Menu</a>
   <a href="#contact">Kontak</a>
  </div>
  <div class="nav-auth">
   @auth
    @if(auth()->user()->role === 'admin')
     <a href="/admin/dashboard" class="btn-login">Dashboard</a>
    @else
     <a href="{{ route('cart.index') }}" class="btn-login">Keranjang</a>
     <a href="{{ route('orders.index') }}" class="btn-login">Pesanan</a>
     <span class="btn-login" style="cursor:default">Halo, {{ auth()->user()->name }}</span>
    @endif
    <form method="POST" action="{{ route('logout') }}" style="display:inline">
     @csrf
     <button type="submit" class="btn-logout">Keluar</button>
    </form>
   @else
    <a href="{{ route('login') }}" class="btn-login">Masuk</a>
    <a href="{{ route('register') }}" class="btn-register">Daftar Gratis</a>
   @endauth
  </div>
  <button class="nav-toggle" onclick="toggleMobile()"></button>
 </div>
 <div class="nav-mobile" id="mobileNav" style="display:none">
  <a href="#home">Beranda</a>
  <a href="#about">Tentang Kami</a>
  <a href="#menu">Menu</a>
  <a href="#contact">Kontak</a>
  <div class="m-btns">
   @auth
    <form method="POST" action="{{ route('logout') }}" style="width:100%">
     @csrf
     <button type="submit" style="width:100%;padding:.65rem 1.25rem;text-align:center;border-radius:50px;font-weight:600;background:rgba(184,106,26,.15);color:var(--cream);border:1px solid rgba(184,106,26,.3);font-family:'DM Sans',sans-serif">Keluar</button>
    </form>
   @else
    <a href="{{ route('login') }}" style="background:rgba(184,106,26,.15);border:1px solid rgba(184,106,26,.3);color:var(--cream)">Masuk</a>
    <a href="{{ route('register') }}" style="background:linear-gradient(135deg,var(--caramel),var(--gold));color:#fff">Daftar</a>
   @endauth
  </div>
 </div>
</nav>

@if(session('success') || session('error') || $errors->any())
 <div style="position:fixed;top:92px;left:50%;transform:translateX(-50%);z-index:999;width:min(92%,620px)">
  @if(session('success'))
   <div style="background:#ecfdf5;color:#047857;border:1px solid #a7f3d0;border-radius:18px;padding:14px 18px;font-weight:800;margin-bottom:10px;box-shadow:0 18px 50px rgba(0,0,0,.18)"> {{ session('success') }}</div>
  @endif
  @if(session('error'))
   <div style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:18px;padding:14px 18px;font-weight:800;margin-bottom:10px;box-shadow:0 18px 50px rgba(0,0,0,.18)"> {{ session('error') }}</div>
  @endif
  @if($errors->any())
   <div style="background:#fef2f2;color:#b91c1c;border:1px solid #fecaca;border-radius:18px;padding:14px 18px;font-weight:800;box-shadow:0 18px 50px rgba(0,0,0,.18)">{{ $errors->first() }}</div>
  @endif
 </div>
@endif

<!-- HERO -->
<section class="hero">
 <div class="grain-overlay"></div>
 <div class="hero-left">
  <div class="hero-eyebrow">
   <div class="hero-eyebrow-dot"></div>
   Specialty Coffee Medan
  </div>
  <h1 class="hero-headline">
   kopi enak,<br>
   <em>pilihan</em><br>
   terbaik.
  </h1>
  <p class="hero-sub">Setiap tegukan adalah cerita. Kopi pilihan, racikan terbaik, untuk harimu yang sempurna di Medan.</p>
  <div class="hero-actions">
   <a href="#menu" class="btn-primary">Lihat Menu</a>
   <a href="#about" class="btn-outline">Tentang Kami</a>
  </div>
  <div class="hero-stats">
   <div>
    <div class="stat-num">50+</div>
    <div class="stat-lbl">Menu Pilihan</div>
   </div>
   <div>
    <div class="stat-num">100%</div>
    <div class="stat-lbl">Fresh Setiap Hari</div>
   </div>
   <div>
    <div class="stat-num"> 4.9</div>
    <div class="stat-lbl">Rating Pelanggan</div>
   </div>
  </div>
 </div>

 <div class="hero-right">
  <div class="ring"></div><div class="ring"></div><div class="ring"></div>
  <div class="slide-glow"></div>
  <div class="fb b1"><div class="fd"></div> Iced Latte Series</div>
  <div class="fb b2"><div class="fd"></div> Fresh Brewed Daily</div>
  <div class="fb b3"><div class="fd"></div> Non-Coffee Available</div>
  <div class="slideshow" id="slideshow">
   <div class="slide active">
    <img src="{{ asset('images/coffee1.jpg') }}" alt="Iced Coffee">
    <div class="slide-overlay"></div>
    <div class="slide-caption">Iced Caramel Latte Signature</div>
   </div>
   <div class="slide">
    <img src="{{ asset('images/coffee2.jpg') }}" alt="Iced Latte">
    <div class="slide-overlay"></div>
    <div class="slide-caption">Iced Oat Latte Light & Smooth</div>
   </div>
   <div class="slide">
    <img src="{{ asset('images/coffee3.jpg') }}" alt="Chocolate">
    <div class="slide-overlay"></div>
    <div class="slide-caption">Dark Chocolate Non Coffee Series</div>
   </div>
   <div class="slide-dots">
    <div class="dot active" onclick="goSlide(0)"></div>
    <div class="dot" onclick="goSlide(1)"></div>
    <div class="dot" onclick="goSlide(2)"></div>
   </div>
  </div>
 </div>
</section>

<!-- MARQUEE -->
<div class="marquee-strip">
 <div class="marquee-track">
  <span>Iced Latte</span><span class="sep"></span>
  <span>Cold Brew</span><span class="sep"></span>
  <span>Matcha Series</span><span class="sep"></span>
  <span>Dark Chocolate</span><span class="sep"></span>
  <span>Signature Drinks</span><span class="sep"></span>
  <span>Non-Coffee Available</span><span class="sep"></span>
  <span>Fresh Every Day</span><span class="sep"></span>
  <span>Medan's Finest</span><span class="sep"></span>
  <span>Iced Latte</span><span class="sep"></span>
  <span>Cold Brew</span><span class="sep"></span>
  <span>Matcha Series</span><span class="sep"></span>
  <span>Dark Chocolate</span><span class="sep"></span>
  <span>Signature Drinks</span><span class="sep"></span>
  <span>Non-Coffee Available</span><span class="sep"></span>
  <span>Fresh Every Day</span><span class="sep"></span>
  <span>Medan's Finest</span><span class="sep"></span>
 </div>
</div>

<!-- ABOUT -->
<section class="about" id="about">
 <div class="section-inner">
  <div class="about-header">
   <div class="reveal-l">
    <div class="section-eyebrow">Tentang 17 Coffee</div>
    <h2 class="section-title">Kopi bukan sekadar<br><em>minuman.</em></h2>
    <p class="section-desc">Di 17 Coffee, kami percaya setiap cangkir adalah pengalaman. Dari biji pilihan hingga racikan tangan barista terbaik kami.</p>
   </div>
   <div class="reveal-r" style="color:var(--warm-grey);font-size:.95rem;line-height:1.9">
    <p>Lahir di jantung kota Medan, 17 Coffee hadir untuk membawa cita rasa specialty coffee ke tangan siapa pun. Kami tidak hanya menyajikan kopi kami menciptakan momen.</p>
    <p style="margin-top:1rem">Setiap menu dirancang dengan cermat, mulai dari pilihan biji kopi single origin hingga kreasi non-coffee yang tak kalah istimewa.</p>
   </div>
  </div>
  <div class="about-cards">
   <div class="about-card reveal">
    <div class="card-icon"><img src="{{ asset('images/cappuccino.jpg') }}" alt="Kopi Berkualitas"></div>
    <div class="card-title">Kopi Berkualitas</div>
    <p class="card-desc">Biji kopi pilihan dari berbagai daerah terbaik Indonesia, disangrai dengan sempurna untuk menghadirkan cita rasa autentik.</p>
   </div>
   <div class="about-card reveal" style="transition-delay:.15s">
    <div class="card-icon"><img src="{{ asset('images/iced-coffee.jpg') }}" alt="Non-Coffee Series"></div>
    <div class="card-title">Non-Coffee Series</div>
    <p class="card-desc">Untuk yang tidak minum kopi, kami hadir dengan pilihan matcha, chocolate, teh, dan minuman segar lainnya.</p>
   </div>
   <div class="about-card reveal" style="transition-delay:.3s">
    <div class="card-icon"><img src="{{ asset('images/petir.svg') }}" alt="Pesan Mudah"></div>
<div class="card-title">Pesan Mudah & Cepat</div>
    <p class="card-desc">Platform pemesanan yang simpel dan intuitif. Pilih menu, atur jumlah, dan checkout dalam hitungan detik.</p>
   </div>
  </div>
 </div>
</section>

<!-- MENU -->
<section class="menu" id="menu">
 <div class="section-inner">
  <div class="menu-header">
   <div class="reveal-l">
    <div class="section-eyebrow">Menu Kami</div>
    <h2 class="section-title">Pilihan Minuman<br><em>Terbaik</em></h2>
    <p class="section-desc">Dari espresso klasik hingga kreasi signature, temukan favoritmu.</p>
   </div>
  </div>
  <div class="menu-grid">
   @forelse ($products as $product)
    <div class="menu-card reveal">
     <div class="card-img-wrap">
      @if ($product->image)
       <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
      @else
       <div class="card-img-placeholder"></div>
      @endif
      <div class="card-cat-badge">{{ $product->category->name ?? 'Coffee' }}</div>
     </div>
     <div class="card-info">
      <div class="card-name">{{ $product->name }}</div>
      <div class="card-detail">{{ Str::limit($product->description, 70) }}</div>
      <div class="card-footer">
       <div>
        <div class="card-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
        <div style="font-size:.72rem;color:var(--latte);margin-top:.2rem">Stok: {{ $product->stock }}</div>
       </div>
       @auth
        @if(auth()->user()->role === 'admin')
         <a href="{{ route('admin.products.edit', $product) }}" class="card-order" style="text-decoration:none">Edit</a>
        @else
         <form action="{{ route('cart.add', $product) }}" method="POST" class="mt-4 flex items-center gap-2">
    @csrf

    <input type="number"
           name="quantity"
           value="1"
           min="1"
           max="{{ min($product->stock, 20) }}"
           class="text-center font-black"
           style="width:52px;height:34px;border-radius:999px;border:1px solid rgba(255,255,255,.22);background:rgba(255,255,255,.10);color:#f5dfc0;text-align:center;outline:none;padding:0;">

    <button type="submit"
            style="height:34px;padding:0 18px;border-radius:999px;background:#d8922b;color:white;border:none;font-size:12px;font-weight:900;cursor:pointer;">
        Pesan
    </button>
</form>
        @endif
       @else
        <a href="{{ route('login') }}" class="card-order" style="text-decoration:none">Pesan</a>
       @endauth
      </div>
     </div>
    </div>
   @empty
    <div class="empty-menu">
     <div class="empty-menu-icon"></div>
     <h3>Menu Segera Hadir</h3>
     <p style="font-size:.9rem;margin-top:.5rem">Admin sedang menyiapkan menu terbaik untuk kamu.</p>
    </div>
   @endforelse
  </div>
 </div>
</section>

<!-- CTA -->
<section class="cta" id="cta">
 <div class="cta-box reveal">
  <div>
   <div class="cta-eyebrow">Bergabung Sekarang</div>
   <div class="cta-title">Mulai Perjalanan<br>Kopi Kamu</div>
   <p class="cta-desc">Daftar gratis dan dapatkan akses ke menu lengkap, pemesanan mudah, dan penawaran eksklusif untuk member baru.</p>
  </div>
  <div class="cta-actions">
   @auth
    <a href="#menu" class="btn-primary" style="padding:1.05rem 2.5rem;font-size:.95rem;text-align:center">Pesan Sekarang</a>
   @else
    <a href="{{ route('register') }}" class="btn-register" style="padding:1.05rem 2.5rem;font-size:.95rem;text-align:center">Daftar Gratis</a>
    <a href="{{ route('login') }}" class="btn-login" style="padding:1.05rem 2.5rem;font-size:.95rem;border-color:rgba(245,226,196,.25);color:var(--cream);text-align:center">Sudah punya akun?</a>
   @endauth
  </div>
 </div>
</section>

<!-- FOOTER -->
<footer id="contact">
 <div class="footer-inner">
  <div>
   <div class="footer-brand-name">17 Coffee</div>
   <p class="footer-desc">Platform pemesanan kopi dan non-kopi yang mudah, cepat, dan berkualitas. Hadir untuk menemani hari-hari terbaik kamu di Medan.</p>
  </div>
  <div>
   <div class="footer-heading">Navigasi</div>
   <div class="footer-links">
    <a href="#home">Beranda</a>
    <a href="#about">Tentang Kami</a>
    <a href="#menu">Menu</a>
    <a href="#cta">Bergabung</a>
   </div>
  </div>
  <div>
   <div class="footer-heading">Kategori</div>
   <div class="footer-links">
    <a href="#">Coffee Series</a>
    <a href="#">Non-Coffee</a>
    <a href="#">Signature Drinks</a>
    <a href="#">Seasonal Menu</a>
   </div>
  </div>
  <div>
   <div class="footer-heading">Kontak</div>
   <div class="footer-links">
    <a href="#">Medan, Sumatera Utara</a>
    <a href="mailto:seventeencoffeee@gmail.com">seventeencoffeee@gmail.com</a>
    <a href="#">+62 895-6320-25857</a>
    <a href="#">Instagram @17coffee.id</a>
   </div>
  </div>
 </div>
 <div class="footer-bottom">
  <span> 2026 17 Coffee. All rights reserved.</span>
  <span class="script" style="font-size:1.35rem;color:var(--honey)">kopi enak, pilihan terbaik.</span>
 </div>
</footer>

<div class="toast" id="toast"></div>

<script>
function toggleMobile(){const m=document.getElementById('mobileNav');m.style.display=m.style.display==='none'?'block':'none';}
function showToast(msg){const t=document.getElementById('toast');t.textContent=msg;t.classList.add('show');setTimeout(()=>t.classList.remove('show'),3500);}

// Slideshow
let currentSlide=0;
const slides=document.querySelectorAll('.slide');
const dots=document.querySelectorAll('.dot');
function goSlide(n){slides[currentSlide].classList.remove('active');dots[currentSlide].classList.remove('active');currentSlide=n;slides[currentSlide].classList.add('active');dots[currentSlide].classList.add('active');}
let slideTimer=setInterval(()=>goSlide((currentSlide+1)%slides.length),4500);
const ss=document.getElementById('slideshow');
if(ss){
 ss.addEventListener('mouseenter',()=>clearInterval(slideTimer));
 ss.addEventListener('mouseleave',()=>{clearInterval(slideTimer);slideTimer=setInterval(()=>goSlide((currentSlide+1)%slides.length),4500);});
}

// Scroll reveal
const obs=new IntersectionObserver((entries)=>{entries.forEach(e=>{if(e.isIntersecting)e.target.classList.add('visible');});},{threshold:.1});
document.querySelectorAll('.reveal,.reveal-l,.reveal-r').forEach(el=>obs.observe(el));

// Navbar scroll
window.addEventListener('scroll',()=>{const nav=document.getElementById('navbar');nav.style.background=window.scrollY>50?'rgba(10,4,1,.98)':'rgba(15,6,3,.94)';});
window.dispatchEvent(new Event('scroll'));
</script>





</body>
</html>
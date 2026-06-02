<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ config('app.name', '17 Coffee') }} {{ $title ?? 'Selamat Datang' }}</title>
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
html{height:100%}
body{min-height:100vh;background:var(--espresso);font-family:'DM Sans',sans-serif;color:var(--espresso);display:flex;overflow:hidden}

/* LEFT PANEL */
.auth-panel-left{
 width:46%;flex-shrink:0;position:relative;overflow:hidden;
 display:flex;flex-direction:column;justify-content:space-between;
}
.panel-bg{position:absolute;inset:0;z-index:0}
.panel-bg img{width:100%;height:100%;object-fit:cover;filter:brightness(.45) saturate(1.2)}
.panel-bg::after{
 content:'';position:absolute;inset:0;
 background:linear-gradient(180deg,rgba(15,6,3,.3) 0%,rgba(15,6,3,.55) 60%,rgba(15,6,3,.88) 100%),
       linear-gradient(90deg,transparent 60%,rgba(15,6,3,.7) 100%);
}
.panel-grain{
 position:absolute;inset:0;opacity:.04;z-index:1;
 background-image:url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
 background-size:200px;pointer-events:none;
}
.panel-content{position:relative;z-index:2;padding:3rem 3.5rem;display:flex;flex-direction:column;height:100%}
.panel-logo{display:flex;align-items:center;gap:.85rem;text-decoration:none}
.panel-logo-icon{width:44px;height:44px;background:linear-gradient(145deg,var(--caramel),var(--gold));border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:1.3rem;box-shadow:0 4px 16px rgba(184,106,26,.4)}
.panel-logo-name{font-family:'Satisfy',cursive;font-size:2rem;color:var(--cream)}
.panel-middle{flex:1;display:flex;flex-direction:column;justify-content:center;padding:2rem 0}
.panel-eyebrow{display:inline-flex;align-items:center;gap:.5rem;background:rgba(184,106,26,.15);border:1px solid rgba(184,106,26,.3);color:var(--honey);padding:.35rem 1rem;border-radius:50px;font-size:.7rem;letter-spacing:2.5px;text-transform:uppercase;font-weight:500;margin-bottom:2rem}
.panel-eyebrow-dot{width:5px;height:5px;border-radius:50%;background:var(--gold);animation:blink 2.2s ease-in-out infinite}
@keyframes blink{0%,100%{opacity:1;transform:scale(1)}50%{opacity:.3;transform:scale(.6)}}
.panel-heading{font-family:'Satisfy',cursive;font-size:clamp(3.2rem,5vw,4.8rem);color:var(--cream);line-height:1.05;margin-bottom:1.25rem}
.panel-heading em{font-style:italic;color:var(--gold);font-family:'Playfair Display',serif}
.panel-sub{font-family:'Playfair Display',serif;font-size:1.05rem;color:rgba(196,160,124,.75);line-height:1.85;font-style:italic;max-width:380px}
.panel-badges{display:flex;flex-direction:column;gap:.75rem;margin-top:2.5rem}
.panel-badge{display:inline-flex;align-items:center;gap:.6rem;color:rgba(245,226,196,.65);font-size:.82rem}
.panel-badge-dot{width:5px;height:5px;border-radius:50%;background:var(--gold);flex-shrink:0}
.panel-footer{font-size:.75rem;color:rgba(196,160,124,.35);letter-spacing:.5px}
.panel-footer span{color:rgba(196,160,124,.55)}

/* SLIDESHOW ON LEFT */
.panel-slide-ring{
 position:absolute;width:340px;height:340px;border-radius:50%;
 border:1px solid rgba(184,106,26,.1);
 top:50%;right:-60px;transform:translateY(-50%);
 animation:ringPulse 5s ease-in-out infinite;z-index:1;
}
@keyframes ringPulse{0%,100%{opacity:.3;transform:translateY(-50%) scale(1)}50%{opacity:.6;transform:translateY(-50%) scale(1.06)}}

/* RIGHT PANEL Form */
.auth-panel-right{
 flex:1;background:var(--chalk);
 display:flex;align-items:center;justify-content:center;
 padding:3rem 2rem;overflow-y:auto;
 position:relative;
}
.auth-panel-right::before{
 content:'';position:absolute;top:-200px;right:-200px;
 width:400px;height:400px;border-radius:50%;
 background:radial-gradient(circle,rgba(184,106,26,.04),transparent 65%);
 pointer-events:none;
}
.auth-form-wrap{width:100%;max-width:420px}
.auth-form-header{margin-bottom:2.5rem}
.auth-form-header h1{font-family:'Playfair Display',serif;font-size:2rem;color:var(--espresso);font-weight:600;margin-bottom:.35rem}
.auth-form-header p{font-size:.88rem;color:var(--warm-grey)}
.auth-form-header p a{color:var(--caramel);text-decoration:none;font-weight:500}
.auth-form-header p a:hover{color:var(--mocha)}

/* Form fields */
.form-group{margin-bottom:1.25rem}
.form-label{display:block;font-size:.72rem;letter-spacing:.8px;color:var(--warm-grey);margin-bottom:.5rem;font-weight:500;text-transform:uppercase}
.form-input{
 width:100%;padding:.85rem 1.15rem;
 border:1.5px solid rgba(184,106,26,.18);border-radius:14px;
 background:#fffdf8;font-family:'DM Sans',sans-serif;
 font-size:.92rem;color:var(--espresso);outline:none;transition:all .3s;
}
.form-input:focus{border-color:var(--caramel);box-shadow:0 0 0 4px rgba(184,106,26,.1)}
.form-input.error{border-color:#c0392b;background:#fff8f7}
.form-input::placeholder{color:rgba(138,112,96,.45)}
.pass-wrap{position:relative}
.pass-wrap .form-input{padding-right:3.5rem}
.pass-eye{position:absolute;right:1rem;top:50%;transform:translateY(-50%);background:none;border:none;color:var(--warm-grey);cursor:pointer;font-size:.78rem;font-weight:500;font-family:'DM Sans',sans-serif;transition:color .2s}
.pass-eye:hover{color:var(--caramel)}

.form-error{color:#c0392b;font-size:.78rem;margin-top:.35rem}
.form-row{display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;flex-wrap:wrap;gap:.5rem}
.form-check{display:flex;align-items:center;gap:.5rem;font-size:.82rem;color:var(--warm-grey);cursor:pointer}
.form-check input[type="checkbox"]{width:15px;height:15px;accent-color:var(--caramel)}
.form-link{font-size:.82rem;color:var(--caramel);text-decoration:none;font-weight:500}
.form-link:hover{color:var(--mocha)}

.btn-submit{
 width:100%;padding:1rem;
 background:linear-gradient(135deg,var(--caramel),var(--gold));
 color:#fff;border:none;border-radius:14px;font-size:.92rem;font-weight:600;
 cursor:pointer;transition:all .35s;font-family:'DM Sans',sans-serif;
 box-shadow:0 6px 22px rgba(184,106,26,.35);letter-spacing:.4px;
 margin-top:.5rem;
}
.btn-submit:hover{transform:translateY(-2px);box-shadow:0 10px 30px rgba(184,106,26,.5)}
.btn-submit:active{transform:translateY(0)}

.form-divider{text-align:center;font-size:.78rem;color:rgba(138,112,96,.45);margin:1.25rem 0;position:relative}
.form-divider::before{content:'';position:absolute;top:50%;left:0;right:0;height:1px;background:rgba(184,106,26,.12)}
.form-divider span{background:var(--chalk);padding:0 1rem;position:relative;z-index:1}

.auth-back-link{display:inline-flex;align-items:center;gap:.4rem;font-size:.8rem;color:var(--warm-grey);text-decoration:none;transition:color .2s;margin-bottom:2.5rem}
.auth-back-link:hover{color:var(--caramel)}
.auth-back-link::before{content:'';font-size:.85rem}

.auth-status{background:rgba(184,106,26,.08);border:1px solid rgba(184,106,26,.2);color:var(--mocha);padding:.75rem 1rem;border-radius:10px;font-size:.82rem;margin-bottom:1.25rem}

/* Mobile */
@media(max-width:860px){
 .auth-panel-left{display:none}
 .auth-panel-right{background:var(--espresso)}
 .auth-form-wrap{background:var(--chalk);border-radius:28px;padding:2.5rem 2rem;box-shadow:0 40px 80px rgba(0,0,0,.4)}
 .auth-form-header h1{font-size:1.75rem}
 body{overflow:auto}
 .auth-panel-right{padding:2rem 1.25rem;align-items:flex-start;padding-top:4rem}
}
</style>
</head>
<body>

<!-- Left decorative panel -->
<div class="auth-panel-left">
 <div class="panel-bg">
  <img src="{{ asset('images/coffee1.jpg') }}" alt="Coffee" id="panelBg">
 </div>
 <div class="panel-grain"></div>
 <div class="panel-slide-ring"></div>

 <div class="panel-content">
  <a href="/" class="panel-logo">
   <div class="panel-logo-icon" style="overflow:hidden;padding:0;"><img src="{{ asset('images/cappuccino.jpg') }}" alt="17 Coffee" style="width:100%;height:100%;object-fit:cover;border-radius:14px;display:block;"></div>
   <span class="panel-logo-name">17 Coffee</span>
  </a>

  <div class="panel-middle">
   <div class="panel-eyebrow">
    <div class="panel-eyebrow-dot"></div>
    Specialty Coffee Medan
   </div>
   <div class="panel-heading">
    kopi enak,<br>
    <em>pilihan</em><br>
    terbaik.
   </div>
   <p class="panel-sub">Setiap tegukan adalah cerita. Kopi pilihan, racikan terbaik, untuk harimu yang sempurna di Medan.</p>
   <div class="panel-badges">
    <div class="panel-badge"><div class="panel-badge-dot"></div> 50+ pilihan menu kopi & non-coffee</div>
    <div class="panel-badge"><div class="panel-badge-dot"></div> Biji kopi pilihan dari seluruh Indonesia</div>
    <div class="panel-badge"><div class="panel-badge-dot"></div> Fresh disajikan setiap hari</div>
   </div>
  </div>

  <div class="panel-footer">
    2026 <span>17 Coffee</span> Medan, Sumatera Utara
  </div>
 </div>
</div>

<!-- Right form panel -->
<div class="auth-panel-right">
 <div class="auth-form-wrap">
  {{ $slot }}
 </div>
</div>

<script>
// Cycle bg images on left panel
const bgImages = [
 '{{ asset("images/coffee1.jpg") }}',
 '{{ asset("images/coffee2.jpg") }}',
 '{{ asset("images/coffee3.jpg") }}'
];
let bi = 0;
setInterval(() => {
 bi = (bi + 1) % bgImages.length;
 const el = document.getElementById('panelBg');
 if(el) {
  el.style.transition = 'opacity .8s';
  el.style.opacity = '0';
  setTimeout(() => { el.src = bgImages[bi]; el.style.opacity = '1'; }, 800);
 }
}, 5000);
function togglePass(id, btn) {
 const inp = document.getElementById(id);
 if(inp.type === "password") { inp.type = "text"; btn.textContent = "Sembunyikan"; }
 else { inp.type = "password"; btn.textContent = "Lihat"; }
}
</script>





</body>
</html>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi OTP - 17 Coffee</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;600;700;800;900&family=Playfair+Display:wght@700&family=Satisfy&display=swap');
        *{box-sizing:border-box;margin:0;padding:0}
        body{min-height:100vh;font-family:'DM Sans',sans-serif;background:radial-gradient(circle at 15% 12%,rgba(216,146,43,.22),transparent 30%),radial-gradient(circle at 85% 0%,rgba(184,106,26,.18),transparent 30%),linear-gradient(135deg,#130a04,#2b160b 55%,#4b2a13);display:grid;place-items:center;padding:24px;color:#2b160b}
        .card{width:min(560px,100%);background:#fffaf2;border-radius:34px;padding:34px;box-shadow:0 30px 80px rgba(0,0,0,.30);border:1px solid rgba(255,255,255,.45)}
        .brand{display:flex;align-items:center;gap:14px;margin-bottom:26px}
        .brand img{width:54px;height:54px;object-fit:cover;border-radius:18px}
        .brand-title{font-family:'Satisfy',cursive;font-size:34px;color:#2b160b;line-height:1}
        .brand-sub{font-size:10px;letter-spacing:.28em;text-transform:uppercase;color:#b86a1a;font-weight:900}
        .kicker{color:#d8922b;text-transform:uppercase;letter-spacing:.28em;font-size:12px;font-weight:900;margin-bottom:12px}
        h1{font-family:'Playfair Display',serif;font-size:clamp(31px,7vw,46px);line-height:1.05;margin-bottom:14px}
        p{color:#745f50;line-height:1.8;margin-bottom:16px}
        .alert{padding:14px 16px;border-radius:18px;margin-bottom:14px;font-weight:800;font-size:14px}
        .success{background:#ecfdf3;color:#137a3a;border:1px solid #bbf7d0}
        .error{background:#fff1f2;color:#b42318;border:1px solid #fecdd3}
        input{width:100%;border:1.5px solid #ead9c9;border-radius:22px;padding:17px 18px;font-size:24px;font-weight:900;text-align:center;letter-spacing:.35em;outline:none;color:#2b160b;background:#fff;margin:8px 0 14px}
        input:focus{border-color:#d8922b;box-shadow:0 0 0 4px rgba(216,146,43,.15)}
        button,a.btn{width:100%;border:none;cursor:pointer;text-align:center;text-decoration:none;border-radius:999px;padding:15px 18px;font-weight:900;font-family:'DM Sans',sans-serif;font-size:15px;display:block}
        .primary{background:linear-gradient(135deg,#b86a1a,#d8922b);color:white;box-shadow:0 14px 32px rgba(216,146,43,.25)}
        .secondary{background:#2b160b;color:white}
        .muted{background:transparent;color:#8a4b18}
        .actions{display:grid;gap:12px;margin-top:18px}
        .small{font-size:13px;color:#8a7464;margin-top:10px;text-align:center}
    </style>
</head>
<body>
    <div class="card">
        <div class="brand">
            <img src="{{ asset('images/cappuccino.jpg') }}" alt="17 Coffee">
            <div>
                <div class="brand-title">17 Coffee</div>
                <div class="brand-sub">Specialty Drinks</div>
            </div>
        </div>

        <div class="kicker">Verifikasi OTP</div>
        <h1>Cek Gmail kamu.</h1>

        @if(session('success'))
            <div class="alert success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert error">{{ session('error') }}</div>
        @endif

        @if($errors->any())
            <div class="alert error">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <p>
            Kami mengirim kode OTP 6 angka ke Gmail:
            <b>{{ auth()->user()->email }}</b>.
            Masukkan kode tersebut agar bisa masuk ke akun.
        </p>

        <form method="POST" action="{{ route('verification.otp') }}">
            @csrf
            <input type="text" name="otp" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" placeholder="______" autocomplete="one-time-code" required>
            <button type="submit" class="primary">Verifikasi Sekarang</button>
        </form>

        <div class="actions">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="secondary">Kirim Ulang OTP ke Gmail</button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="muted">Logout</button>
            </form>
        </div>

        <div class="small">
            OTP berlaku 10 menit. Cek Inbox, Spam, atau Promotions.
        </div>
    </div>
</body>
</html>
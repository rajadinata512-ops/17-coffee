<x-guest-layout>
 <x-slot name="title">Masuk ke Akun</x-slot>

 <a href="/" class="auth-back-link">Kembali ke Beranda</a>

 <div class="auth-form-header">
  <h1>Selamat Datang Kembali</h1>
  <p>Belum punya akun? <a href="{{ route('register') }}">Daftar gratis di sini</a></p>
 </div>

 <!-- Session Status -->
 @if (session('status'))
  <div class="auth-status">{{ session('status') }}</div>
 @endif

 <form method="POST" action="{{ route('login') }}">
  @csrf

  <div class="form-group">
   <label class="form-label" for="email">Alamat Email</label>
   <input
    id="email" name="email" type="email"
    class="form-input {{ $errors->get('email') ? 'error' : '' }}"
    placeholder="nama@email.com"
    value="{{ old('email') }}" required autofocus autocomplete="username">
   @error('email')
    <div class="form-error">{{ $message }}</div>
   @enderror
  </div>

  <div class="form-group">
   <label class="form-label" for="password">Kata Sandi</label>
   <div class="pass-wrap">
    <input
     id="password" name="password" type="password"
     class="form-input {{ $errors->get('password') ? 'error' : '' }}"
     placeholder="Masukkan kata sandi"
     required autocomplete="current-password">
    <button type="button" class="pass-eye" onclick="togglePass('password', this)">Lihat</button>
   </div>
   @error('password')
    <div class="form-error">{{ $message }}</div>
   @enderror
  </div>

  <div class="form-row">
   <label class="form-check">
    <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
    Ingat saya
   </label>
   @if (Route::has('password.request'))
    <a href="{{ route('password.request') }}" class="form-link">Lupa kata sandi?</a>
   @endif
  </div>

  <button type="submit" class="btn-submit">Masuk Sekarang</button>

  <div class="form-divider"><span>atau</span></div>

  <a href="{{ route('register') }}" style="display:block;text-align:center;padding:.85rem;border:1.5px solid rgba(184,106,26,.2);border-radius:14px;font-size:.88rem;color:var(--warm-grey);text-decoration:none;transition:all .3s;font-family:'DM Sans',sans-serif" onmouseover="this.style.borderColor='var(--caramel)';this.style.color='var(--caramel)'" onmouseout="this.style.borderColor='rgba(184,106,26,.2)';this.style.color='var(--warm-grey)'">
   Buat Akun Baru
  </a>
 </form>
</x-guest-layout>
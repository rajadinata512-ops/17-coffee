<x-guest-layout>
 <x-slot name="title">Buat Akun Baru</x-slot>

 <a href="/" class="auth-back-link">Kembali ke Beranda</a>

 <div class="auth-form-header">
  <h1>Bergabung Sekarang</h1>
  <p>Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a></p>
 </div>

 <form method="POST" action="{{ route('register') }}">
  @csrf

  <div class="form-group">
   <label class="form-label" for="name">Nama Lengkap</label>
   <input
    id="name" name="name" type="text"
    class="form-input {{ $errors->get('name') ? 'error' : '' }}"
    placeholder="Nama kamu"
    value="{{ old('name') }}" required autofocus autocomplete="name">
   @error('name')
    <div class="form-error">{{ $message }}</div>
   @enderror
  </div>

  <div class="form-group">
   <label class="form-label" for="email">Alamat Email</label>
   <input
    id="email" name="email" type="email"
    class="form-input {{ $errors->get('email') ? 'error' : '' }}"
    placeholder="nama@email.com"
    value="{{ old('email') }}" required autocomplete="username">
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
     placeholder="Min. 8 karakter"
     required autocomplete="new-password">
    <button type="button" class="pass-eye" onclick="togglePass('password', this)">Lihat</button>
   </div>
   @error('password')
    <div class="form-error">{{ $message }}</div>
   @enderror
  </div>

  <div class="form-group">
   <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
   <div class="pass-wrap">
    <input
     id="password_confirmation" name="password_confirmation" type="password"
     class="form-input"
     placeholder="Ulangi kata sandi"
     required autocomplete="new-password">
    <button type="button" class="pass-eye" onclick="togglePass('password_confirmation', this)">Lihat</button>
   </div>
  </div>

  <button type="submit" class="btn-submit" style="margin-top:1rem">Daftar Sekarang</button>

  <div class="form-divider"><span>atau</span></div>

  <a href="{{ route('login') }}" style="display:block;text-align:center;padding:.85rem;border:1.5px solid rgba(184,106,26,.2);border-radius:14px;font-size:.88rem;color:var(--warm-grey);text-decoration:none;transition:all .3s;font-family:'DM Sans',sans-serif" onmouseover="this.style.borderColor='var(--caramel)';this.style.color='var(--caramel)'" onmouseout="this.style.borderColor='rgba(184,106,26,.2)';this.style.color='var(--warm-grey)'">
   Sudah Punya Akun? Masuk
  </a>
 </form>
</x-guest-layout>
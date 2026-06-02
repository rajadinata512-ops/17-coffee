<x-guest-layout>
 <x-slot name="title">Lupa Kata Sandi</x-slot>

 <a href="{{ route('login') }}" class="auth-back-link">Kembali ke Halaman Masuk</a>

 <div class="auth-form-header">
  <h1>Lupa Kata Sandi?</h1>
  <p>Tenang, masukkan emailmu dan kami kirimkan tautan untuk reset kata sandimu.</p>
 </div>

 @if (session('status'))
  <div class="auth-status">{{ session('status') }}</div>
 @endif

 <form method="POST" action="{{ route('password.email') }}">
  @csrf

  <div class="form-group">
   <label class="form-label" for="email">Alamat Email</label>
   <input
    id="email" name="email" type="email"
    class="form-input {{ $errors->get('email') ? 'error' : '' }}"
    placeholder="nama@email.com"
    value="{{ old('email') }}" required autofocus>
   @error('email')
    <div class="form-error">{{ $message }}</div>
   @enderror
  </div>

  <button type="submit" class="btn-submit" style="margin-top:.5rem">Kirim Tautan Reset</button>

  <div class="form-divider"><span>atau</span></div>

  <a href="{{ route('login') }}" style="display:block;text-align:center;padding:.85rem;border:1.5px solid rgba(184,106,26,.2);border-radius:14px;font-size:.88rem;color:var(--warm-grey);text-decoration:none;transition:all .3s;font-family:'DM Sans',sans-serif" onmouseover="this.style.borderColor='var(--caramel)';this.style.color='var(--caramel)'" onmouseout="this.style.borderColor='rgba(184,106,26,.2)';this.style.color='var(--warm-grey)'">
   Kembali Masuk
  </a>
 </form>
</x-guest-layout>
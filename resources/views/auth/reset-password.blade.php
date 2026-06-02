<x-guest-layout>
 <x-slot name="title">Reset Kata Sandi</x-slot>

 <a href="{{ route('login') }}" class="auth-back-link">Kembali ke Halaman Masuk</a>

 <div class="auth-form-header">
  <h1>Buat Kata Sandi Baru</h1>
  <p>Masukkan kata sandi baru yang mudah kamu ingat.</p>
 </div>

 <form method="POST" action="{{ route('password.store') }}">
  @csrf
  <input type="hidden" name="token" value="{{ $request->route('token') }}">

  <div class="form-group">
   <label class="form-label" for="email">Alamat Email</label>
   <input id="email" name="email" type="email"
    class="form-input {{ $errors->get('email') ? 'error' : '' }}"
    value="{{ old('email', $request->email) }}" required autocomplete="username">
   @error('email') <div class="form-error">{{ $message }}</div> @enderror
  </div>

  <div class="form-group">
   <label class="form-label" for="password">Kata Sandi Baru</label>
   <div class="pass-wrap">
    <input id="password" name="password" type="password"
     class="form-input {{ $errors->get('password') ? 'error' : '' }}"
     placeholder="Min. 8 karakter" required autocomplete="new-password">
    <button type="button" class="pass-eye" onclick="togglePass('password', this)">Lihat</button>
   </div>
   @error('password') <div class="form-error">{{ $message }}</div> @enderror
  </div>

  <div class="form-group">
   <label class="form-label" for="password_confirmation">Konfirmasi Kata Sandi</label>
   <div class="pass-wrap">
    <input id="password_confirmation" name="password_confirmation" type="password"
     class="form-input" placeholder="Ulangi kata sandi baru" required autocomplete="new-password">
    <button type="button" class="pass-eye" onclick="togglePass('password_confirmation', this)">Lihat</button>
   </div>
  </div>

  <button type="submit" class="btn-submit">Simpan Kata Sandi Baru</button>
 </form>
</x-guest-layout>
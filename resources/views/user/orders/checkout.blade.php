@extends('user.layouts.app')

@section('title', 'Checkout - 17 Coffee')

@section('content')
@if($cart->cartItems->isEmpty())
  <div class="card p-10 sm:p-16 text-center max-w-3xl mx-auto">
    <div class="text-7xl mb-5"></div>

    <p class="uppercase tracking-[.30em] text-[#b86a1a] text-xs font-black mb-3">
      Checkout
    </p>

    <h1 class="font-black text-3xl sm:text-5xl text-[#2b160b]">
      Keranjang Masih Kosong
    </h1>

    <p class="text-gray-500 mt-4 max-w-xl mx-auto">
      Kamu belum memilih minuman. Pilih menu kopi atau non kopi dulu, lalu tambahkan ke keranjang sebelum checkout.
    </p>

    <div class="mt-8 flex flex-col sm:flex-row justify-center gap-3">
      <a href="{{ route('user.menu') }}" class="btn-coffee px-7 py-4 inline-flex items-center justify-center gap-2">
        <i class="fa-solid fa-mug-hot"></i>
        Pilih Menu
      </a>

      <a href="{{ route('cart.index') }}" class="px-7 py-4 rounded-full bg-gray-100 text-[#2b160b] font-black inline-flex items-center justify-center gap-2">
        <i class="fa-solid fa-bag-shopping"></i>
        Lihat Keranjang
      </a>
    </div>
  </div>
@else
  <form method="POST" action="{{ route('orders.store') }}" class="grid lg:grid-cols-[1fr_390px] gap-6 items-start">
    @csrf

    <section class="card p-5 sm:p-7">
      <p class="text-[#b86a1a] uppercase tracking-[.25em] text-xs font-black">
        Checkout
      </p>

      <h1 class="font-black text-3xl sm:text-5xl text-[#2b160b] mb-2">
        Alamat Pengantaran
      </h1>

      <p class="text-gray-500 mb-7">
        Isi alamat lengkap supaya admin bisa mengantar pesanan dengan tepat.
      </p>

      @if($errors->any())
        <div class="mb-5 rounded-3xl bg-red-50 border border-red-200 text-red-700 px-5 py-4">
          <b>Data checkout belum lengkap.</b>
          <ul class="list-disc ml-5 mt-2 text-sm">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <div class="grid sm:grid-cols-2 gap-4">
        <div>
          <label class="block font-black text-sm text-[#2b160b] mb-2">Nama Penerima</label>
          <input name="recipient_name"
              value="{{ old('recipient_name', auth()->user()->name) }}"
              required
              class="w-full">
        </div>

        <div>
          <label class="block font-black text-sm text-[#2b160b] mb-2">No WhatsApp / HP</label>
          <input name="phone"
              value="{{ old('phone') }}"
              placeholder="08xxxxxxxxxx"
              required
              class="w-full">
        </div>

        <div class="sm:col-span-2">
          <label class="block font-black text-sm text-[#2b160b] mb-2">Alamat Lengkap</label>
          <textarea name="address"
               rows="4"
               placeholder="Nama jalan, nomor rumah, patokan, RT/RW"
               required
               class="w-full">{{ old('address') }}</textarea>
        </div>

        <div>
          <label class="block font-black text-sm text-[#2b160b] mb-2">Kota</label>
          <input name="city"
              value="{{ old('city', 'Medan') }}"
              required
              class="w-full">
        </div>

        <div>
          <label class="block font-black text-sm text-[#2b160b] mb-2">Provinsi</label>
          <input name="province"
              value="{{ old('province', 'Sumatera Utara') }}"
              required
              class="w-full">
        </div>

        <div>
          <label class="block font-black text-sm text-[#2b160b] mb-2">Kode Pos</label>
          <input name="postal_code"
              value="{{ old('postal_code') }}"
              placeholder="Opsional"
              class="w-full">
        </div>

        <div>
          <label class="block font-black text-sm text-[#2b160b] mb-2">Catatan Kurir</label>
          <input name="notes"
              value="{{ old('notes') }}"
              placeholder="Contoh: pagar hitam / titip satpam"
              class="w-full">
        </div>
      </div>

      <div class="mt-8">
        <h2 class="text-xl font-black mb-4 text-[#2b160b]">
          Metode Pembayaran
        </h2>

        <div class="grid sm:grid-cols-3 gap-3">
          <label class="cursor-pointer rounded-3xl border border-[#ead9c9] p-4 hover:bg-[#fff7ed]">
            <input type="radio"
                name="payment_method"
                value="cod"
                class="w-auto mr-2"
                {{ old('payment_method', 'cod') === 'cod' ? 'checked' : '' }}>
            <b>COD</b>
            <p class="text-xs text-gray-500 mt-1">Bayar saat pesanan diantar.</p>
          </label>

          <label class="cursor-pointer rounded-3xl border border-[#ead9c9] p-4 hover:bg-[#fff7ed]">
            <input type="radio"
                name="payment_method"
                value="bank_transfer"
                class="w-auto mr-2"
                {{ old('payment_method') === 'bank_transfer' ? 'checked' : '' }}>
            <b>Bank Transfer</b>
            <p class="text-xs text-gray-500 mt-1">Virtual Account Midtrans.</p>
          </label>

          <label class="cursor-pointer rounded-3xl border border-[#ead9c9] p-4 hover:bg-[#fff7ed]">
            <input type="radio"
                name="payment_method"
                value="qris"
                class="w-auto mr-2"
                {{ old('payment_method') === 'qris' ? 'checked' : '' }}>
            <b>QRIS</b>
            <p class="text-xs text-gray-500 mt-1">Bayar pakai QRIS Midtrans.</p>
          </label>
        </div>

        <p class="mt-3 text-xs text-gray-500">
          Untuk QRIS/Bank Transfer, kamu akan diarahkan ke halaman pembayaran Midtrans setelah klik Buat Pesanan.
        </p>
      </div>
    </section>

    <aside class="card p-6 sticky top-24">
      <h2 class="text-xl font-black mb-4 text-[#2b160b]">
        Pesanan Kamu
      </h2>

      <div class="space-y-4 max-h-[360px] overflow-auto pr-1">
        @foreach($cart->cartItems as $item)
          <div class="flex gap-3">
            <div class="w-16 h-16 rounded-2xl bg-[#f8efe6] overflow-hidden grid place-items-center shrink-0">
              @if($item->product->image)
                <img src="{{ $item->product?->image_url ?? asset('images/cappuccino.jpg') }}"
                   class="w-full h-full object-cover"
                   alt="{{ $item->product->name }}">
              @else
                
              @endif
            </div>

            <div class="flex-1">
              <b class="text-[#2b160b]">{{ $item->product->name }}</b>
              <p class="text-sm text-gray-500">
                {{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }}
              </p>
            </div>

            <b class="text-[#2b160b]">
              Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
            </b>
          </div>
        @endforeach
      </div>

      <div class="border-t border-[#f0e3d8] mt-5 pt-5 space-y-3">
        <div class="flex justify-between text-sm">
          <span class="text-gray-500">Subtotal</span>
          <b>Rp {{ number_format($subtotal, 0, ',', '.') }}</b>
        </div>

        <div class="flex justify-between text-sm">
          <span class="text-gray-500">Ongkir</span>
          <b>Rp {{ number_format($shippingCost, 0, ',', '.') }}</b>
        </div>

        <div class="flex justify-between text-xl">
          <span class="font-black">Total</span>
          <b>Rp {{ number_format($total, 0, ',', '.') }}</b>
        </div>
      </div>

      <button type="submit" class="btn-coffee w-full px-6 py-4 mt-6">
        Buat Pesanan
      </button>

      <a href="{{ route('cart.index') }}" class="block text-center text-sm font-bold text-[#8a4b18] mt-4">
        Kembali ke Keranjang
      </a>
    </aside>
  </form>
@endif
@endsection
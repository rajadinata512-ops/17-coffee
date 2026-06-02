@extends('user.layouts.app')

@section('title', 'Keranjang - 17 Coffee')

@section('content')
<div class="mb-8 text-white">
    <p class="uppercase tracking-[.35em] text-[#d9a45d] text-xs font-black mb-3">
        Keranjang
    </p>

    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="font-black text-4xl sm:text-5xl leading-tight">
                Keranjang Kamu
            </h1>
            <p class="text-white/65 mt-3 max-w-2xl">
                Atur jumlah minuman sebelum lanjut ke checkout.
            </p>
        </div>

        <a href="{{ route('user.menu') }}" class="btn-honey px-6 py-3 text-center inline-flex items-center justify-center gap-2">
            <i class="fa-solid fa-plus"></i>
            Tambah Menu
        </a>
    </div>
</div>

<div class="grid lg:grid-cols-[1fr_360px] gap-6 items-start">
    <section class="card p-5 sm:p-7">
        @if($cart && $cart->cartItems->count())
            <div class="space-y-4">
                @foreach($cart->cartItems as $item)
                    @php
                        $stock = $item->product->stock ?? 99;
                        $minusQty = max(1, $item->quantity - 1);
                        $plusQty = min($stock, $item->quantity + 1);
                    @endphp

                    <div class="rounded-3xl border border-[#f0e3d8] p-4 sm:p-5 flex flex-col sm:flex-row gap-4 sm:items-center">
                        <div class="w-full sm:w-24 h-40 sm:h-24 rounded-3xl bg-[#f8efe6] overflow-hidden grid place-items-center shrink-0">
                            @if($item->product && $item->product->image)
                                <img src="{{ $item->product?->image_url ?? asset('images/cappuccino.jpg') }}"
                                     alt="{{ $item->product->name }}"
                                     class="w-full h-full object-cover"
                                     onerror="this.onerror=null; this.src='{{ asset('images/cappuccino.jpg') }}';">
                            @else
                                <i class="fa-solid fa-mug-hot text-4xl text-[#b86a1a]"></i>
                            @endif
                        </div>

                        <div class="flex-1 min-w-0">
                            <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-2">
                                <div>
                                    <h2 class="font-black text-xl text-[#2b160b]">
                                        {{ $item->product->name ?? 'Produk tidak ditemukan' }}
                                    </h2>

                                    <p class="text-sm text-gray-500 mt-1">
                                        {{ $item->product->category->name ?? 'Menu' }}
                                    </p>
                                </div>

                                <div class="font-black text-lg text-[#2b160b]">
                                    Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                                </div>
                            </div>

                            <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-black text-gray-500">Jumlah</span>

                                    <div class="flex items-center rounded-full bg-[#f8efe6] border border-[#ead9c9] overflow-hidden">
                                        @if($item->quantity <= 1)
                                            <form method="POST"
                                                  action="{{ route('cart.destroy', $item) }}"
                                                  onsubmit="return confirm('Jumlah tinggal 1. Kalau dikurangi, menu ini akan dihapus dari keranjang. Lanjutkan?')">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit"
                                                        class="w-11 h-11 font-black text-xl text-[#2b160b] hover:bg-red-50 hover:text-red-600">
                                                    -
                                                </button>
                                            </form>
                                        @else
                                            <form method="POST" action="{{ route('cart.update', $item) }}">
                                                @csrf
                                                @method('PATCH')

                                                <input type="hidden" name="quantity" value="{{ $minusQty }}">

                                                <button type="submit"
                                                        class="w-11 h-11 font-black text-xl text-[#2b160b] hover:bg-[#ead9c9]">
                                                    -
                                                </button>
                                            </form>
                                        @endif

                                        <div class="w-12 h-11 grid place-items-center bg-white font-black text-[#2b160b]">
                                            {{ $item->quantity }}
                                        </div>

                                        <form method="POST" action="{{ route('cart.update', $item) }}">
                                            @csrf
                                            @method('PATCH')

                                            <input type="hidden" name="quantity" value="{{ $plusQty }}">

                                            <button type="submit"
                                                    {{ $item->quantity >= $stock ? 'disabled' : '' }}
                                                    class="w-11 h-11 font-black text-xl {{ $item->quantity >= $stock ? 'text-gray-300 cursor-not-allowed' : 'text-[#2b160b] hover:bg-[#ead9c9]' }}">
                                                +
                                            </button>
                                        </form>
                                    </div>
                                </div>

                                <div class="text-sm text-gray-500">
                                    Harga satuan:
                                    <b class="text-[#2b160b]">
                                        Rp {{ number_format($item->price, 0, ',', '.') }}
                                    </b>
                                    <span class="mx-2">-</span>
                                    Stok:
                                    <b class="text-[#2b160b]">{{ $stock }}</b>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-14 sm:py-20">
                <div class="w-24 h-24 rounded-full bg-[#f8efe6] mx-auto grid place-items-center mb-5">
                    <i class="fa-solid fa-bag-shopping text-4xl text-[#b86a1a]"></i>
                </div>

                <h2 class="font-black text-2xl sm:text-3xl text-[#2b160b]">
                    Keranjang Masih Kosong
                </h2>

                <p class="text-gray-500 mt-3 max-w-md mx-auto">
                    Pilih minuman kopi atau non kopi dulu, lalu tambahkan ke keranjang sebelum checkout.
                </p>

                <a href="{{ route('user.menu') }}" class="btn-coffee px-7 py-4 inline-flex items-center justify-center gap-2 mt-7">
                    <i class="fa-solid fa-mug-hot"></i>
                    Lihat Menu
                </a>
            </div>
        @endif
    </section>

    <aside class="card p-6 sticky top-24">
        <h2 class="font-black text-2xl text-[#2b160b] mb-5">
            Ringkasan
        </h2>

        <div class="space-y-3 text-sm">
            <div class="flex justify-between">
                <span class="text-gray-500">Subtotal</span>
                <b class="text-[#2b160b]">
                    Rp {{ number_format($subtotal ?? 0, 0, ',', '.') }}
                </b>
            </div>

            <div class="flex justify-between">
                <span class="text-gray-500">Estimasi ongkir</span>
                <b class="text-[#2b160b]">
                    Rp {{ number_format($shippingCost ?? 0, 0, ',', '.') }}
                </b>
            </div>

            <div class="border-t border-[#f0e3d8] pt-4 mt-4 flex justify-between text-xl">
                <span class="font-black text-[#2b160b]">Total</span>
                <b class="text-[#2b160b]">
                    Rp {{ number_format($total ?? 0, 0, ',', '.') }}
                </b>
            </div>
        </div>

        <a href="{{ route('checkout') }}"
           class="btn-coffee w-full px-6 py-4 mt-6 inline-flex items-center justify-center gap-2">
            <i class="fa-solid fa-credit-card"></i>
            Checkout Sekarang
        </a>

        <p class="text-xs text-gray-500 mt-4 leading-relaxed">
            Setelah checkout, pesanan akan dibuat dan kamu bisa mengirim detail pesanan ke admin lewat WhatsApp.
        </p>
    </aside>
</div>
@endsection
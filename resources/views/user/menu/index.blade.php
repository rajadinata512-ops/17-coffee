@extends('user.layouts.app')

@section('title', 'Menu Minuman - 17 Coffee')

@section('content')
@php
    $selectedCategory = request('category', $categorySlug ?? 'all');
@endphp

<div class="mb-8 text-white">
    <p class="uppercase tracking-[.35em] text-[#d9a45d] text-xs font-black mb-3">
        Menu
    </p>

    <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-4">
        <div>
            <h1 class="font-black text-4xl sm:text-5xl leading-tight">
                Pilih Menu Favoritmu
            </h1>

            <p class="text-white/65 mt-3 max-w-2xl">
                Cari kopi, non kopi, dan makanan yang tersedia. Semua menu otomatis muncul dari produk yang dibuat admin.
            </p>
        </div>

        <a href="{{ route('cart.index') }}" class="btn-honey px-6 py-3 text-center inline-flex items-center justify-center gap-2">
            <i class="fa-solid fa-bag-shopping"></i>
            Lihat Keranjang
        </a>
    </div>
</div>

<form method="GET" action="{{ route('user.menu') }}" class="mb-5">
    <input type="hidden" name="category" value="{{ $selectedCategory }}">

    <div class="bg-white rounded-3xl p-3 flex flex-col sm:flex-row gap-3">
        <div class="flex-1 relative">
            <i class="fa-solid fa-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>

            <input type="text"
                   name="q"
                   value="{{ request('q') }}"
                   class="w-full rounded-2xl border border-[#ead9c9] pl-11 pr-4 py-4 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]"
                   placeholder="Cari cappuccino, latte, matcha, makanan...">
        </div>

        <button type="submit" class="btn-coffee px-7 py-4">
            Cari
        </button>
    </div>
</form>

<div class="mb-7 flex flex-wrap gap-3">
    <a href="{{ route('user.menu', ['q' => request('q')]) }}"
       class="px-5 py-3 rounded-full text-sm font-black transition {{ $selectedCategory === 'all' || !$selectedCategory ? 'bg-white text-[#2b160b]' : 'bg-white/10 text-white hover:bg-white/20' }}">
        Semua Menu
    </a>

    @foreach($categories as $category)
        @php
            $slug = $category->slug ?? \Illuminate\Support\Str::slug($category->name);
            $isActive = $selectedCategory === $slug;
        @endphp

        <a href="{{ route('user.menu', ['category' => $slug, 'q' => request('q')]) }}"
           class="px-5 py-3 rounded-full text-sm font-black transition {{ $isActive ? 'bg-white text-[#2b160b]' : 'bg-white/10 text-white hover:bg-white/20' }}">
            {{ \Illuminate\Support\Str::title($category->name) }}
        </a>
    @endforeach
</div>

@if($products->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($products as $product)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-white/10">
                <div class="relative h-56 bg-[#f7eadc]">
                    <img src="{{ $product->image_url }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null; this.src='{{ asset('images/cappuccino.jpg') }}';">

                    <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-black bg-[#2b160b]/85 text-white">
                        {{ $product->category ? \Illuminate\Support\Str::title($product->category->name) : 'Menu' }}
                    </span>

                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-black bg-white text-[#2b160b]">
                        Stok {{ $product->stock }}
                    </span>
                </div>

                <div class="p-5">
                    <h2 class="text-xl font-black text-[#2b160b]">
                        {{ $product->name }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-2 min-h-[40px]">
                        {{ $product->description ?: 'Menu spesial 17 Coffee.' }}
                    </p>

                    <div class="mt-5 flex items-center justify-between gap-3">
                        <div>
                            <div class="font-black text-2xl text-[#2b160b]">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            <div class="rating-fixed-17coffee" style="margin-top:6px;font-size:12px;color:#d98519;font-weight:800;">
                                &#9733; {{ isset($product->ratings_avg_rating) && $product->ratings_avg_rating ? number_format($product->ratings_avg_rating, 1) : 'Baru' }}
                            </div>
                            </div>

                            <div class="text-xs text-[#d8922b] font-black mt-1">
                            </div>
                        </div>

                        <form action="{{ route('cart.add', $product) }}" method="POST" class="flex items-center gap-2">
                            @csrf

                            <input type="number"
                                   name="quantity"
                                   value="1"
                                   min="1"
                                   max="{{ min($product->stock, 20) }}"
                                   class="w-12 h-9 text-center rounded-full border border-[#ead9c9] text-sm font-black text-[#2b160b] focus:outline-none focus:ring-2 focus:ring-[#d8922b]">

                            <button type="submit" class="px-5 h-9 rounded-full bg-[#d8922b] text-white text-sm font-black hover:bg-[#b86a1a] transition">
                                Pesan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="card p-10 sm:p-16 text-center">
        <div class="text-6xl mb-4">&#9749;</div>
        <h2 class="font-black text-2xl text-[#2b160b]">Menu Belum Ada</h2>
        <p class="text-gray-500 mt-2">
            Belum ada produk aktif sesuai pencarian atau kategori ini.
        </p>

        <a href="{{ route('user.menu') }}" class="btn-coffee px-7 py-3 inline-flex mt-6">
            Reset Filter
        </a>
    </div>
@endif
@endsection
@extends('admin.layouts.app')

@section('title', 'Kelola Produk - 17 Coffee')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-[#2b160b]">Kelola Produk</h1>
        <p class="text-sm text-gray-500 mt-1">Tambah dan kelola menu minuman.</p>
    </div>

    <a href="{{ route('admin.products.create') }}" class="px-5 py-3 rounded-xl bg-[#2b160b] text-white font-black inline-flex items-center justify-center gap-2">
        <span>+</span>
        Tambah Menu
    </a>
</div>

@if(session('success'))
    <div class="mb-5 rounded-2xl bg-green-50 border border-green-200 text-green-700 px-5 py-4 font-bold">
        {{ session('success') }}
    </div>
@endif

<div class="mb-5 flex flex-wrap gap-2">
    <a href="{{ route('admin.products.index') }}"
       class="px-4 py-2 rounded-full text-sm font-bold {{ request('category') ? 'bg-white text-[#2b160b]' : 'bg-[#2b160b] text-white' }}">
        Semua
    </a>

    @foreach($categories as $category)
        <a href="{{ route('admin.products.index', ['category' => $category->id]) }}"
           class="px-4 py-2 rounded-full text-sm font-bold {{ request('category') == $category->id ? 'bg-[#2b160b] text-white' : 'bg-white text-[#2b160b]' }}">
            {{ $category->name }} ({{ $category->products_count }})
        </a>
    @endforeach
</div>

@if($products->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($products as $product)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-[#ead9c9]">
                <div class="relative h-56 bg-[#f7eadc]">
                    <img src="{{ $product->image_url }}"
                         alt="{{ $product->name }}"
                         class="w-full h-full object-cover"
                         onerror="this.onerror=null; this.src='{{ asset('images/cappuccino.jpg') }}';">

                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-black {{ $product->status === 'active' ? 'bg-green-500 text-white' : 'bg-gray-500 text-white' }}">
                        {{ $product->status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>

                    @if($product->is_featured ?? false)
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-black bg-yellow-400 text-[#2b160b]">
                            Tampil Depan
                        </span>
                    @else
                        <span class="absolute top-3 left-3 px-3 py-1 rounded-full text-xs font-black bg-white/90 text-[#2b160b]">
                            Menu Biasa
                        </span>
                    @endif
                </div>

                <div class="p-5">
                    <div class="flex items-center justify-between gap-2 mb-2">
                        <p class="text-xs uppercase tracking-[.2em] text-[#b86a1a] font-black">
                            {{ $product->category->name ?? 'Tanpa Kategori' }}
                        </p>

                        @if($product->is_featured ?? false)
                            <span class="text-[10px] px-2 py-1 rounded-full bg-yellow-100 text-[#8a4b18] font-black">
                                Pilihan Terbaik
                            </span>
                        @else
                            <span class="text-[10px] px-2 py-1 rounded-full bg-gray-100 text-gray-600 font-black">
                                Menu User
                            </span>
                        @endif
                    </div>

                    <h2 class="text-xl font-black text-[#2b160b]">
                        {{ $product->name }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-2 min-h-[40px]">
                        {{ $product->description ?: 'Belum ada deskripsi.' }}
                    </p>

                    <div class="mt-4 flex items-center justify-between">
                        <div class="text-xl font-black text-[#2b160b]">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </div>

                        <div class="text-sm font-bold text-gray-500">
                            Stok: {{ $product->stock }}
                        </div>
                    </div>

                    <div class="mt-5 grid grid-cols-[1fr_auto] gap-3">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="px-4 py-3 rounded-xl bg-yellow-100 text-[#8a4b18] font-black text-center">
                            Edit
                        </a>

                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                              onsubmit="return confirm('Hapus menu ini?')">
                            @csrf
                            @method('DELETE')

                            <button type="submit" class="px-4 py-3 rounded-xl bg-red-50 text-red-600 font-black">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@else
    <div class="bg-white rounded-3xl p-10 text-center border border-[#ead9c9]">
        <h2 class="text-2xl font-black text-[#2b160b]">Belum Ada Produk</h2>
        <p class="text-gray-500 mt-2">Tambahkan menu kopi atau non kopi terlebih dahulu.</p>
    </div>
@endif
@endsection
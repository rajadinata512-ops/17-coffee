@extends('admin.layouts.app')

@section('title', 'Kategori Menu - 17 Coffee')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-[#2b160b]">Kategori Menu</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola kategori kopi dan non kopi.</p>
    </div>

    <a href="{{ route('admin.categories.create') }}" class="px-5 py-3 rounded-xl bg-[#2b160b] text-white font-black inline-flex items-center justify-center gap-2">
        <span>+</span>
        Tambah Kategori
    </a>
</div>

@if(session('success'))
    <div class="mb-5 rounded-2xl bg-green-50 border border-green-200 text-green-700 px-5 py-4 font-bold">
        {{ session('success') }}
    </div>
@endif

@if($categories->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($categories as $category)
            <div class="bg-white rounded-3xl overflow-hidden shadow-sm border border-[#ead9c9]">
                <div class="relative h-44 bg-[#f7eadc]">
                    @if($category->image)
                        <img src="{{ $category->image_url }}"
                             alt="{{ $category->name }}"
                             class="w-full h-full object-cover"
                             onerror="this.onerror=null; this.src='{{ asset('images/cappuccino.jpg') }}';">
                    @else
                        <div class="w-full h-full grid place-items-center">
                            <i class="fa-solid fa-mug-hot text-6xl text-[#b86a1a]"></i>
                        </div>
                    @endif

                    <span class="absolute top-3 right-3 px-3 py-1 rounded-full text-xs font-black bg-white/90 text-[#2b160b]">
                        {{ $category->products_count ?? $category->products()->count() }} menu
                    </span>
                </div>

                <div class="p-5">
                    <h2 class="text-xl font-black text-[#2b160b]">
                        {{ $category->name }}
                    </h2>

                    <p class="text-sm text-gray-500 mt-2 min-h-[40px]">
                        {{ $category->description ?: 'Belum ada deskripsi kategori.' }}
                    </p>

                    <div class="mt-5 grid grid-cols-[1fr_auto] gap-3">
                        <a href="{{ route('admin.categories.edit', $category) }}"
                           class="px-4 py-3 rounded-xl bg-yellow-100 text-[#8a4b18] font-black text-center">
                            Edit
                        </a>

                        <form action="{{ route('admin.categories.destroy', $category) }}" method="POST"
                              onsubmit="return confirm('Hapus kategori ini? Produk di dalam kategori ini bisa terdampak.')">
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
        <h2 class="text-2xl font-black text-[#2b160b]">Belum Ada Kategori</h2>
        <p class="text-gray-500 mt-2">Tambahkan kategori kopi atau non kopi terlebih dahulu.</p>
    </div>
@endif
@endsection
@extends('admin.layouts.app')

@section('title', 'Edit Menu - 17 Coffee')

@section('content')
<div class="max-w-4xl">
  <div class="mb-6">
    <h1 class="text-2xl font-black text-[#2b160b]">Edit Menu</h1>
    <p class="text-sm text-gray-500 mt-1">Perbarui data menu kopi atau non kopi.</p>
  </div>

  @if($errors->any())
    <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4">
      <b>Data belum lengkap.</b>
      <ul class="list-disc ml-5 mt-2 text-sm">
        @foreach($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="bg-white rounded-3xl p-6 shadow-sm border border-[#ead9c9]">
    @csrf
    @method('PUT')

    <div class="grid md:grid-cols-2 gap-5">
      <div>
        <label class="block text-sm font-bold text-[#2b160b] mb-2">Nama Menu <span class="text-red-500">*</span></label>
        <input type="text" name="name" value="{{ old('name', $product->name) }}" required
            class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]">
      </div>

      <div>
        <label class="block text-sm font-bold text-[#2b160b] mb-2">Kategori <span class="text-red-500">*</span></label>
        <select name="category_id" required
            class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]">
          <option value="">Pilih Kategori</option>
          @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
              {{ $category->name }}
            </option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="block text-sm font-bold text-[#2b160b] mb-2">Harga (Rp) <span class="text-red-500">*</span></label>
        <input type="number" name="price" value="{{ old('price', $product->price) }}" min="0" required
            class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]">
      </div>

      <div>
        <label class="block text-sm font-bold text-[#2b160b] mb-2">Stok <span class="text-red-500">*</span></label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" min="0" required
            class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]">
      </div>

      <div>
        <label class="block text-sm font-bold text-[#2b160b] mb-2">Status <span class="text-red-500">*</span></label>
        <select name="status" required
            class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]">
          <option value="active" {{ old('status', $product->status) === 'active' ? 'selected' : '' }}>Aktif, tampil di menu</option>
          <option value="inactive" {{ old('status', $product->status) === 'inactive' ? 'selected' : '' }}>Nonaktif, sembunyikan</option>
        </select>
      </div>
            <div>
                <label class="block text-sm font-bold text-[#2b160b] mb-2">Pilihan Minuman Terbaik</label>
                <select name="is_featured"
                        class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]">
                    <option value="0" {{ old('is_featured', $product->is_featured ?? 0) == 0 ? 'selected' : '' }}>Tidak, menu biasa</option>
                    <option value="1" {{ old('is_featured', $product->is_featured ?? 0) == 1 ? 'selected' : '' }}>Ya, tampil di halaman depan</option>
                </select>
                <p class="text-xs text-gray-500 mt-2">Produk ini hanya tampil di bagian Pilihan Minuman Terbaik pada halaman depan.</p>
            </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-bold text-[#2b160b] mb-2">Deskripsi Produk</label>
        <textarea name="description" rows="4"
             class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]">{{ old('description', $product->description) }}</textarea>
      </div>

      <div class="md:col-span-2">
        <label class="block text-sm font-bold text-[#2b160b] mb-2">Foto Menu</label>

        @if($product->image)
          <div class="mb-3 w-40 h-40 rounded-2xl overflow-hidden bg-[#f7eadc] border border-[#ead9c9]">
            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
          </div>
        @endif

        <input type="file" name="image" accept="image/*,.jfif,.jpg,.jpeg,.png,.webp"
            class="w-full rounded-xl border border-dashed border-[#c28a3d] px-4 py-4 bg-[#fffaf3]">

        <p class="text-xs text-gray-500 mt-2">Kosongkan jika tidak ingin mengganti foto. Format: JPG, JPEG, PNG, WEBP, JFIF. Maksimal 4 MB.</p>
      </div>
    </div>

    <div class="mt-7 flex gap-3">
      <button type="submit" class="px-6 py-3 rounded-xl bg-[#2b160b] text-white font-black">
        Simpan Perubahan
      </button>

      <a href="{{ route('admin.products.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-[#2b160b] font-black">
        Batal
      </a>
    </div>
  </form>
</div>
@endsection
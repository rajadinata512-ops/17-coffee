@extends('admin.layouts.app')

@section('title', 'Tambah Kategori - 17 Coffee')

@section('content')
<div class="mb-6 flex items-center justify-between gap-4">
    <div>
        <h1 class="text-2xl font-black text-[#2b160b]">Tambah Kategori</h1>
        <p class="text-sm text-gray-500 mt-1">Buat kategori menu baru lengkap dengan gambar.</p>
    </div>
</div>

@if($errors->any())
    <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4">
        <b>Masih ada yang perlu diperbaiki:</b>
        <ul class="list-disc ml-5 mt-2">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white rounded-3xl border border-[#ead9c9] p-6 sm:p-8 max-w-3xl">
    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div>
            <label class="block text-sm font-bold text-[#2b160b] mb-2">
                Nama Kategori <span class="text-red-500">*</span>
            </label>
            <input type="text"
                   name="name"
                   value="{{ old('name') }}"
                   class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]"
                   placeholder="Contoh: Kopi, Non Kopi, Seasonal"
                   required>
        </div>

        <div>
            <label class="block text-sm font-bold text-[#2b160b] mb-2">
                Deskripsi
            </label>
            <textarea name="description"
                      rows="3"
                      class="w-full rounded-xl border border-[#ead9c9] px-4 py-3 focus:outline-none focus:ring-2 focus:ring-[#c28a3d]"
                      placeholder="Deskripsi singkat kategori ini...">{{ old('description') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-bold text-[#2b160b] mb-2">
                Gambar Kategori
            </label>

            <div class="rounded-2xl border-2 border-dashed border-[#d8b894] bg-[#fff8ef] p-5">
                <input type="file"
                       name="image"
                       accept=".jpg,.jpeg,.png,.webp,.jfif,image/jpeg,image/png,image/webp"
                       class="block w-full text-sm text-gray-600">

                <p class="text-xs text-gray-500 mt-3">
                    Format: JPG, JPEG, PNG, WEBP, atau JFIF. Maksimal 4 MB.
                </p>
            </div>
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="px-6 py-3 rounded-xl bg-[#2b160b] text-white font-black">
                Simpan Kategori
            </button>

            <a href="{{ route('admin.categories.index') }}" class="px-6 py-3 rounded-xl bg-gray-100 text-[#2b160b] font-black">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
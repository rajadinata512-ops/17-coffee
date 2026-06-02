@extends('user.layouts.app')

@section('title', 'Detail Pesanan - 17 Coffee')

@section('content')
@php
    $status = strtolower($order->order_status ?? $order->status ?? '');
    $isCompleted = in_array($status, ['completed', 'selesai', 'delivered', 'terkirim']);

    $address = $order->address;
    $recipientName = $address->recipient_name ?? $address->receiver_name ?? auth()->user()->name ?? '-';
    $fullAddress = $address->full_address
        ?? (($address->address ?? '-') . ', ' . ($address->city ?? '-') . ', ' . ($address->province ?? '-'));

    $orderItems = $order->relationLoaded('orderItems')
        ? $order->orderItems
        : \App\Models\OrderItem::with('product')->where('order_id', $order->id)->get();
@endphp

<style>
    .rating-stars {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
        gap: 8px;
    }

    .rating-stars input {
        display: none;
    }

    .rating-stars label {
        cursor: pointer;
        display: inline-block;
    }

    .rating-stars label img {
        width: 34px;
        height: 34px;
        object-fit: contain;
        opacity: .25;
        transition: .2s ease;
        filter: grayscale(100%);
    }

    .rating-stars label:hover img,
    .rating-stars label:hover ~ label img,
    .rating-stars input:checked ~ label img {
        opacity: 1;
        transform: scale(1.07);
        filter: grayscale(0%);
    }

    .rating-stars label span {
        display: inline-flex;
        width: 34px;
        height: 34px;
        align-items: center;
        justify-content: center;
        font-size: 32px;
        color: #d8922b;
        opacity: .25;
        transition: .2s ease;
    }

    .rating-stars label:hover span,
    .rating-stars label:hover ~ label span,
    .rating-stars input:checked ~ label span {
        opacity: 1;
        transform: scale(1.07);
    }
</style>

@if(session('success'))
    <div class="mb-5 rounded-2xl bg-green-50 border border-green-200 text-green-700 px-5 py-4 font-bold">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="mb-5 rounded-2xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 font-bold">
        {{ session('error') }}
    </div>
@endif

<div class="grid lg:grid-cols-[1fr_360px] gap-6 items-start">
    <section class="space-y-6">
        @if(($order->payment_method ?? '') !== 'cod' && ($order->payment_status ?? '') !== 'paid' && $order->payment?->redirect_url)
            <div class="card p-6 sm:p-8 border-2 border-[#d8922b]/30">
                <p class="uppercase tracking-[.35em] text-[#b86a1a] text-xs font-black mb-2">
                    Pembayaran Online
                </p>

                <h2 class="font-black text-2xl text-[#2b160b] mb-3">
                    Selesaikan Pembayaran
                </h2>

                <p class="text-gray-500 mb-5">
                    Pesanan kamu sudah dibuat. Klik tombol di bawah untuk membayar melalui Midtrans.
                </p>

                <a href="{{ $order->payment->redirect_url }}" target="_blank"
                   class="btn-coffee inline-flex items-center justify-center gap-2 px-6 py-4 w-full sm:w-auto">
                    <i class="fa-solid fa-credit-card"></i>
                    Bayar Sekarang via Midtrans
                </a>
            </div>
        @endif
        <div class="card p-6 sm:p-8">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <p class="uppercase tracking-[.35em] text-[#b86a1a] text-xs font-black mb-2">
                        Invoice
                    </p>

                    <h1 class="font-black text-3xl sm:text-4xl text-[#2b160b]">
                        #{{ $order->invoice_number }}
                    </h1>

                    <p class="text-gray-500 mt-3">
                        Dibuat {{ $order->created_at->format('d M Y H:i') }}
                    </p>
                </div>

                <span class="px-4 py-2 rounded-full text-sm font-black inline-flex items-center justify-center min-w-[110px] text-center {{ $isCompleted ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                    {{ ucfirst($order->order_status ?? $order->status ?? 'pending') }}
                </span>
            </div>
        </div>

        <div class="card p-6 sm:p-8">
            <h2 class="font-black text-2xl text-[#2b160b] mb-5">
                Pesanan
            </h2>

            @if($orderItems->count())
                <div class="space-y-4">
                    @foreach($orderItems as $item)
                        @php
                            $qty = $item->quantity ?? $item->qty ?? 1;
                            $price = $item->price ?? $item->unit_price ?? 0;
                        @endphp

                        <div class="rounded-3xl border border-[#f0e3d8] p-4 flex flex-col sm:flex-row gap-4">
                            <div class="w-full sm:w-24 h-36 sm:h-24 rounded-3xl bg-[#f8efe6] overflow-hidden grid place-items-center shrink-0">
                                @if($item->product && $item->product->image)
                                    <img src="{{ $item->product?->image_url ?? asset('images/cappuccino.jpg') }}"
                                         alt="{{ $item->product->name }}"
                                         class="w-full h-full object-cover">
                                @else
                                    <i class="fa-solid fa-mug-hot text-4xl text-[#b86a1a]"></i>
                                @endif
                            </div>

                            <div class="flex-1 flex items-center">
                                <div class="w-full flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div>
                                        <h3 class="font-black text-xl text-[#2b160b]">
                                            {{ $item->product->name ?? $item->product_name ?? $item->name ?? 'Produk' }}
                                        </h3>

                                        <p class="text-sm text-gray-500 mt-1">
                                            {{ $qty }} x Rp {{ number_format($price, 0, ',', '.') }}
                                        </p>
                                    </div>

                                    <b class="text-[#2b160b] text-lg sm:text-xl self-start sm:self-center whitespace-nowrap">
                                        Rp {{ number_format($price * $qty, 0, ',', '.') }}
                                    </b>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="rounded-3xl border border-yellow-200 bg-yellow-50 p-5 text-yellow-700 font-bold">
                    Detail item pesanan belum ditemukan. Untuk order lama, ulangi checkout atau jalankan repair order item.
                </div>
            @endif
        </div>

        @if($isCompleted)
            <div class="card p-6 sm:p-8">
                <p class="uppercase tracking-[.35em] text-[#b86a1a] text-xs font-black mb-2">
                    Rating
                </p>

                <h2 class="font-black text-2xl text-[#2b160b] mb-2">
                    Beri Rating Pesanan Kamu
                </h2>

                <p class="text-gray-500 mb-6">
                    Pesanan sudah selesai. Kalau rating sudah dikirim, pesanan ini akan masuk ke Riwayat Pesanan. Kamu tetap bisa Update Rating dari halaman detail ini.
                </p>

                @if($orderItems->count())
                    <div class="space-y-6">
                        @foreach($orderItems as $item)
                            @php
                                $existingRating = \App\Models\Rating::query()
                                    ->when(\Illuminate\Support\Facades\Schema::hasColumn('ratings', 'user_id'), fn($q) => $q->where('user_id', auth()->id()))
                                    ->when(\Illuminate\Support\Facades\Schema::hasColumn('ratings', 'order_id'), fn($q) => $q->where('order_id', $order->id))
                                    ->when(\Illuminate\Support\Facades\Schema::hasColumn('ratings', 'product_id'), fn($q) => $q->where('product_id', $item->product_id))
                                    ->first();
                            @endphp

                            <div class="rounded-3xl border border-[#f0e3d8] p-5">
                                <div class="mb-4">
                                    <h3 class="font-black text-lg text-[#2b160b]">
                                        {{ $item->product->name ?? $item->product_name ?? $item->name ?? 'Produk' }}
                                    </h3>

                                    @if($existingRating)
                                        <p class="text-sm text-green-600 font-bold mt-1">
                                            Pesanan ini sudah masuk riwayat. Rating saat ini {{ $existingRating->rating }}/5.
                                        </p>
                                    @else
                                        <p class="text-sm text-gray-500 mt-1">
                                            Pilih jumlah bintang dari 1 sampai 5.
                                        </p>
                                    @endif
                                </div>

                                <form method="POST" action="{{ route('ratings.store', $order) }}" class="space-y-4">
                                    @csrf

                                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">

                                    <div>
                                        <label class="block text-sm font-bold text-[#2b160b] mb-3">
                                            Pilih rating
                                        </label>

                                        <div class="rating-stars">
                                            @for($i = 5; $i >= 1; $i--)
                                                <input type="radio"
                                                       id="star{{ $i }}_{{ $item->id }}"
                                                       name="rating"
                                                       value="{{ $i }}"
                                                       {{ ($existingRating && $existingRating->rating == $i) ? 'checked' : '' }}
                                                       required>
                                                <label for="star{{ $i }}_{{ $item->id }}">
                                                    <img src="{{ asset('images/bintang.jpg') }}"
                                                         alt="{{ $i }} bintang"
                                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';">
                                                    <span style="display:none;">&#9733;</span>
                                                </label>
                                            @endfor
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-bold text-[#2b160b] mb-2">
                                            Ulasan
                                        </label>

                                        <textarea
                                            name="review"
                                            rows="3"
                                            class="w-full rounded-2xl border border-[#ead9c9] px-4 py-3"
                                            placeholder="Tulis ulasanmu di sini..."
                                        >{{ $existingRating->review ?? $existingRating->comment ?? '' }}</textarea>
                                    </div>

                                    <button type="submit" class="btn-coffee px-6 py-3">
                                        {{ $existingRating ? 'Update Rating' : 'Kirim Rating' }}
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">
                        Rating belum bisa diberikan karena item pesanan tidak ditemukan.
                    </p>
                @endif
            </div>
        @else
            <div class="card p-6 sm:p-8">
                <h2 class="font-black text-2xl text-[#2b160b] mb-2">
                    Rating Belum Tersedia
                </h2>

                <p class="text-gray-500">
                    Rating akan muncul setelah admin mengubah status pesanan menjadi selesai.
                </p>
            </div>
        @endif
    </section>

    <aside class="space-y-5">
        <div class="card p-6">
            <h2 class="font-black text-xl text-[#2b160b] mb-3">
                Notifikasi Admin
            </h2>

            <p class="text-sm text-gray-500 mb-5">
                Klik tombol ini jika ingin mengirim detail pesanan ke WhatsApp admin.
            </p>

            <a href="{{ $whatsappUrl ?? '#' }}" target="_blank" class="btn-honey w-full px-5 py-4 inline-flex justify-center items-center gap-2">
                <i class="fa-brands fa-whatsapp"></i>
                Kirim ke WhatsApp Admin
            </a>
        </div>

        <div class="card p-6">
            <h2 class="font-black text-xl text-[#2b160b] mb-4">
                Alamat
            </h2>

            <div class="space-y-2 text-sm text-gray-600">
                <p class="font-black text-[#2b160b]">{{ $recipientName }}</p>
                <p>{{ $address->phone ?? '-' }}</p>
                <p>{{ $fullAddress }}</p>
                <p>Kode Pos: {{ $address->postal_code ?? '-' }}</p>
                <p>Catatan: {{ $address->notes ?? '-' }}</p>
            </div>
        </div>

        <div class="card p-6">
            <h2 class="font-black text-xl text-[#2b160b] mb-4">
                Ringkasan
            </h2>

            <div class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-500">Subtotal</span>
                    <b>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</b>
                </div>

                <div class="flex justify-between">
                    <span class="text-gray-500">Ongkir</span>
                    <b>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</b>
                </div>

                <div class="pt-3 border-t border-[#f0e3d8] flex justify-between text-lg">
                    <span class="font-black">Total</span>
                    <b>Rp {{ number_format($order->total_price, 0, ',', '.') }}</b>
                </div>
            </div>
        </div>
    </aside>
</div>

@php
    $detailStatus = strtolower((string) ($order->order_status ?? $order->status ?? 'pending'));
    $detailPaymentStatus = strtolower((string) ($order->payment_status ?? 'unpaid'));
    $detailCanCancel = $detailPaymentStatus !== 'paid' && in_array($detailStatus, ['pending'], true);
@endphp

@if($detailCanCancel)
    <div style="background:#fff;border-radius:24px;padding:28px;margin:24px 0;border:1px solid rgba(255,196,125,.35);">
        <div style="letter-spacing:8px;color:#c46d10;font-size:12px;font-weight:900;text-transform:uppercase;margin-bottom:10px;">
            Batalkan Pesanan
        </div>
        <h2 style="margin:0 0 10px;color:#2a1007;">Tidak jadi beli?</h2>
        <p style="color:#667085;margin-bottom:18px;">
            Pesanan ini belum dibayar, jadi masih bisa dibatalkan. Setelah dibatalkan, pesanan akan pindah ke riwayat.
        </p>

        <form action="{{ route('orders.cancel', $order) }}" method="POST" onsubmit="return confirm('Yakin batalkan pesanan ini? Stok produk akan dikembalikan.');">
            @csrf
            <button type="submit" style="border:0;border-radius:999px;padding:13px 22px;background:#fee2e2;color:#b42318;font-weight:900;cursor:pointer;">
                Batalkan Pesanan
            </button>
        </form>
    </div>
@endif

@endsection
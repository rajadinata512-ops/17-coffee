@extends('admin.layouts.app')

@section('title', 'Detail Pesanan')
@section('page-title', 'Detail Pesanan #' . $order->invoice_number)
@section('page-subtitle', 'Informasi lengkap pesanan pelanggan')

@section('content')

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

  {{-- LEFT: Order items + summary --}}
  <div class="lg:col-span-2 space-y-5">

    {{-- Items --}}
    <div class="page-card overflow-hidden">
      <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
        <h3 class="font-bold text-[#1a0d06]">Menu yang Dipesan</h3>
        <span class="text-xs text-gray-400">{{ $order->orderItems->count() }} item</span>
      </div>
      <div class="divide-y divide-gray-50">
        @foreach($order->orderItems as $item)
          <div class="flex items-center gap-4 px-5 py-4">
            @if($item->product?->image)
              <img src="{{ $item->product?->image_url ?? asset('images/cappuccino.jpg') }}"
                 class="w-14 h-14 object-cover rounded-xl shrink-0">
            @else
              <div class="w-14 h-14 bg-[#f4ebe0] rounded-xl flex items-center justify-center text-2xl shrink-0"></div>
            @endif
            <div class="flex-1">
              <p class="font-semibold text-[#1a0d06]">{{ $item->product->name ?? 'Produk dihapus' }}</p>
              <p class="text-xs text-gray-400">{{ $item->product?->category->name }}</p>
            </div>
            <div class="text-right">
              <p class="text-xs text-gray-400">{{ $item->quantity }}x Rp {{ number_format($item->price, 0, ',', '.') }}</p>
              <p class="font-bold text-[#1a0d06]">Rp {{ number_format($item->quantity * $item->price, 0, ',', '.') }}</p>
            </div>
          </div>
        @endforeach
      </div>
      {{-- Total --}}
      <div class="bg-[#f9f4ef] px-5 py-4 space-y-2">
        <div class="flex justify-between text-sm text-gray-500">
          <span>Subtotal</span>
          <span>Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-sm text-gray-500">
          <span>Ongkos kirim</span>
          <span>Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between font-bold text-[#1a0d06] text-base border-t border-[#e8d9cc] pt-2 mt-1">
          <span>Total</span>
          <span>Rp {{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
      </div>
    </div>

    {{-- Delivery address --}}
    @if($order->address)
    <div class="page-card p-5">
      <h3 class="font-bold text-[#1a0d06] mb-3"><i class="fas fa-map-marker-alt text-[#c8a87a] mr-2"></i>Alamat Pengiriman</h3>
      <div class="bg-[#f9f4ef] rounded-xl p-4 text-sm text-gray-700 space-y-1">
        <p class="font-semibold">{{ $order->address->recipient_name }}</p>
        <p>{{ $order->address->phone }}</p>
        <p>{{ $order->address->address }}</p>
        <p>{{ $order->address->city }}, {{ $order->address->province }} {{ $order->address->postal_code }}</p>
      </div>
      @php
        $adminPhone = preg_replace('/\D+/', '', config('services.admin_whatsapp', '62835632025857'));
        $itemsText = $order->orderItems->map(fn($item) => '- '.($item->product->name ?? 'Produk'). ' x'.$item->quantity.' = Rp'.number_format($item->price * $item->quantity, 0, ',', '.'))->implode("\n");
        $waMessage = "Halo, pesanan #{$order->invoice_number} akan segera diproses.\n\n".
          "Nama: ".($order->address->recipient_name ?? '-')."\n".
          "Alamat: ".($order->address->address ?? '-').", ".($order->address->city ?? '-').", ".($order->address->province ?? '-')."\n\n".
          "Pesanan:\n{$itemsText}\n\nTotal: Rp".number_format($order->total_price, 0, ',', '.');
        $customerWa = $order->address?->phone ? 'https://wa.me/'.preg_replace('/\D+/', '', preg_replace('/^0/', '62', $order->address->phone)).'?text='.rawurlencode($waMessage) : null;
      @endphp
      @if($customerWa)
        <a href="{{ $customerWa }}" target="_blank" class="mt-4 inline-flex items-center justify-center w-full gap-2 bg-green-600 hover:bg-green-700 text-white font-bold px-4 py-3 rounded-xl transition">
          <i class="fab fa-whatsapp"></i> Chat Customer untuk Pengantaran
        </a>
      @endif

    </div>
    @endif
  </div>

  {{-- RIGHT: Status management --}}
  <div class="space-y-5">

    {{-- Customer info --}}
    <div class="page-card p-5">
      <h3 class="font-bold text-[#1a0d06] mb-3"> Pelanggan</h3>
      <div class="flex items-center gap-3">
        <div class="w-10 h-10 bg-[#f4ebe0] rounded-full flex items-center justify-center font-bold text-[#2b160b]">
          {{ strtoupper(substr($order->user->name ?? '?', 0, 1)) }}
        </div>
        <div>
          <p class="font-semibold text-sm text-[#1a0d06]">{{ $order->user->name ?? '' }}</p>
          <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
        </div>
      </div>
    </div>

    {{-- Payment info --}}
    <div class="page-card p-5">
      <h3 class="font-bold text-[#1a0d06] mb-3"> Pembayaran</h3>
      <div class="space-y-2 text-sm">
        <div class="flex justify-between">
          <span class="text-gray-400">Metode</span>
          <span class="font-semibold text-[#1a0d06]">{{ str_replace('_',' ', ucwords($order->payment_method)) }}</span>
        </div>
        <div class="flex justify-between items-center">
          <span class="text-gray-400">Status</span>
          @php
            $p = ['unpaid'=>['bg-yellow-100','text-yellow-700'],'paid'=>['bg-green-100','text-green-700'],'failed'=>['bg-red-100','text-red-700']];
            $pc = $p[$order->payment_status] ?? ['bg-gray-100','text-gray-600'];
          @endphp
          <span class="badge-status {{ $pc[0] }} {{ $pc[1] }}">{{ ucfirst($order->payment_status) }}</span>
        </div>
      </div>

      {{-- Update payment status --}}
      <form action="{{ route('admin.orders.updatePayment', $order) }}" method="POST" class="mt-4">
        @csrf @method('PATCH')
        <select name="payment_status" class="mb-3 text-sm">
          @foreach(['unpaid'=>' Belum Bayar','paid'=>' Sudah Bayar','failed'=>' Gagal'] as $v=>$l)
            <option value="{{ $v }}" {{ $order->payment_status==$v?'selected':'' }}>{{ $l }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn-primary w-full text-sm py-2.5">
          Update Pembayaran
        </button>
      </form>
    </div>

    {{-- Order status --}}
    <div class="page-card p-5">
      <h3 class="font-bold text-[#1a0d06] mb-3"> Status Pesanan</h3>
      @php
        $steps = ['pending','processing','shipping','completed'];
        $currentIdx = array_search($order->order_status, $steps);
      @endphp

      {{-- Progress --}}
      <div class="flex items-center gap-1 mb-4">
        @foreach($steps as $i => $step)
          <div class="flex-1 h-2 rounded-full {{ $i <= $currentIdx ? 'bg-[#c8a87a]' : 'bg-gray-200' }}"></div>
        @endforeach
      </div>

      <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
        @csrf @method('PATCH')
        <select name="order_status" class="mb-3 text-sm">
          @foreach(['pending'=>' Pending','processing'=>' Diproses','shipping'=>' Sedang Dikirim','completed'=>' Selesai','cancelled'=>' Dibatal'] as $v=>$l)
            <option value="{{ $v }}" {{ $order->order_status==$v?'selected':'' }}>{{ $l }}</option>
          @endforeach
        </select>
        <button type="submit" class="btn-primary w-full text-sm py-2.5">
          Update Status
        </button>
      </form>
    </div>

    {{-- Back --}}
    <a href="{{ route('admin.orders.index') }}"
      class="flex items-center gap-2 text-sm text-gray-500 hover:text-[#2b160b] transition">
      <i class="fas fa-arrow-left"></i> Kembali ke daftar pesanan
    </a>
  </div>
</div>
@endsection

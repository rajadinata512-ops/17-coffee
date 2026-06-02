@extends('admin.layouts.app')

@section('title', 'Pesanan')
@section('page-title', 'Kelola Pesanan')
@section('page-subtitle', 'Monitor dan proses semua pesanan')

@section('content')

{{-- Filter bar --}}
<div class="page-card p-4 mb-5">
  <form method="GET" action="{{ route('admin.orders.index') }}"
     class="flex flex-wrap gap-3 items-end">

    <div class="flex-1 min-w-48">
      <label class="text-xs mb-1">Cari invoice / nama</label>
      <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari">
    </div>

    <div>
      <label class="text-xs mb-1">Status Pesanan</label>
      <select name="status" class="min-w-36">
        <option value="">Semua Status</option>
        <option value="pending"  {{ request('status')=='pending'  ? 'selected':'' }}> Pending</option>
        <option value="processing" {{ request('status')=='processing' ? 'selected':'' }}> Diproses</option>
        <option value="shipping"  {{ request('status')=='shipping'  ? 'selected':'' }}> Dikirim</option>
        <option value="completed" {{ request('status')=='completed' ? 'selected':'' }}> Selesai</option>
        <option value="cancelled" {{ request('status')=='cancelled' ? 'selected':'' }}> Dibatal</option>
      </select>
    </div>

    <div>
      <label class="text-xs mb-1">Pembayaran</label>
      <select name="payment" class="min-w-36">
        <option value="">Semua</option>
        <option value="unpaid" {{ request('payment')=='unpaid' ? 'selected':'' }}> Belum Bayar</option>
        <option value="paid"  {{ request('payment')=='paid'  ? 'selected':'' }}> Sudah Bayar</option>
        <option value="failed" {{ request('payment')=='failed' ? 'selected':'' }}> Gagal</option>
      </select>
    </div>

    <div class="flex gap-2">
      <button type="submit" class="btn-primary px-4 py-2.5 text-sm">
        <i class="fas fa-search mr-1"></i> Filter
      </button>
      <a href="{{ route('admin.orders.index') }}"
        class="px-4 py-2.5 rounded-xl border-2 border-gray-200 text-gray-500 text-sm font-semibold hover:bg-gray-50 transition">
        Reset
      </a>
    </div>
  </form>
</div>

{{-- Table --}}
<div class="page-card overflow-hidden">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-[#1a0d06] text-white">
        <tr>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Invoice</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Pelanggan</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Total</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Pembayaran</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Status</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Tanggal</th>
          <th class="px-5 py-3.5 text-left text-xs font-semibold uppercase tracking-wider">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-gray-50">
        @forelse($orders as $order)
          @php
            $orderColors = [
              'pending'  => ['bg-orange-100','text-orange-700'],
              'processing' => ['bg-blue-100','text-blue-700'],
              'shipping'  => ['bg-purple-100','text-purple-700'],
              'completed' => ['bg-green-100','text-green-700'],
              'cancelled' => ['bg-red-100','text-red-700'],
            ];
            $payColors = [
              'unpaid' => ['bg-yellow-100','text-yellow-700'],
              'paid'  => ['bg-green-100','text-green-700'],
              'failed' => ['bg-red-100','text-red-700'],
            ];
            $oc = $orderColors[$order->order_status]  ?? ['bg-gray-100','text-gray-600'];
            $pc = $payColors[$order->payment_status] ?? ['bg-gray-100','text-gray-600'];
          @endphp
          <tr class="table-row">
            <td class="px-5 py-3.5">
              <span class="font-mono text-xs font-bold text-[#c8a87a]">#{{ $order->invoice_number }}</span>
            </td>
            <td class="px-5 py-3.5 font-medium text-gray-700">{{ $order->user->name ?? '' }}</td>
            <td class="px-5 py-3.5 font-bold text-[#1a0d06]">
              Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </td>
            <td class="px-5 py-3.5">
              @php
                $payIcons = [
                  'unpaid' => 'salah.jpg',
                  'paid'  => 'benar.jpg',
                  'failed' => 'tidakada.jpg',
                ];
                $payLabels = [
                  'unpaid' => 'Belum Bayar',
                  'paid'  => 'Sudah Bayar',
                  'failed' => 'Gagal',
                ];
                $pIcon = $payIcons[$order->payment_status] ?? null;
                $pLabel = $payLabels[$order->payment_status] ?? ucfirst($order->payment_status);
              @endphp
              <span class="badge-status {{ $pc[0] }} {{ $pc[1] }} inline-flex items-center gap-1.5">
                @if($pIcon)
                  <img src="{{ asset('images/'.$pIcon) }}" alt="{{ $pLabel }}" class="w-4 h-4 object-contain rounded-sm">
                @endif
                {{ $pLabel }}
              </span>
            </td>
            <td class="px-5 py-3.5">
              @php
                $orderIcons = [
                  'pending'  => 'bintang.jpg',
                  'processing' => 'mengulang.jpg',
                  'shipping'  => 'pengiriman.jpg',
                  'completed' => 'benar.jpg',
                  'cancelled' => 'salah.jpg',
                ];
                $orderLabels = [
                  'pending'  => 'Pending',
                  'processing' => 'Diproses',
                  'shipping'  => 'Dikirim',
                  'completed' => 'Selesai',
                  'cancelled' => 'Dibatal',
                ];
                $oIcon = $orderIcons[$order->order_status] ?? null;
                $oLabel = $orderLabels[$order->order_status] ?? ucfirst($order->order_status);
              @endphp
              <span class="badge-status {{ $oc[0] }} {{ $oc[1] }} inline-flex items-center gap-1.5">
                @if($oIcon)
                  <img src="{{ asset('images/'.$oIcon) }}" alt="{{ $oLabel }}" class="w-4 h-4 object-contain rounded-sm">
                @endif
                {{ $oLabel }}
              </span>
            </td>
            <td class="px-5 py-3.5 text-gray-400 text-xs">
              {{ $order->created_at->locale('id')->isoFormat('D MMM Y, HH:mm') }}
            </td>
            <td class="px-5 py-3.5">
              <a href="{{ route('admin.orders.show', $order) }}"
                class="bg-[#f4ebe0] text-[#2b160b] px-4 py-2 rounded-lg text-xs font-semibold hover:bg-[#e8d9cc] transition">
                Detail
              </a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="px-5 py-12 text-center text-gray-400">
              <div class="text-4xl mb-2"></div>
              Belum ada pesanan
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Pagination --}}
  @if($orders->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
      {{ $orders->links() }}
    </div>
  @endif
</div>
@endsection

@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Ringkasan penjualan dan aktivitas terkini')

@section('content')

{{-- STATS ROW --}}
<div class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4 mb-6">

  {{-- Total Pesanan --}}
  <div class="stat-card xl:col-span-1">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Total Pesanan</p>
        <h3 class="text-3xl font-extrabold text-[#1a0d06]">{{ $totalOrders }}</h3>
      </div>
      <div class="w-10 h-10 bg-[#fff7ed] rounded-xl flex items-center justify-center shrink-0">
        <i class="fas fa-shopping-bag text-[#c8a87a]"></i>
      </div>
    </div>
    <p class="text-xs text-gray-400 mt-2">Semua waktu</p>
  </div>

  {{-- Revenue --}}
  <div class="stat-card xl:col-span-1">
    <div class="flex items-start justify-between">
      <div class="overflow-hidden">
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Total Revenue</p>
        <h3 class="text-2xl font-extrabold text-green-600 truncate">
          Rp {{ number_format($totalRevenue, 0, ',', '.') }}
        </h3>
      </div>
      <div class="w-10 h-10 bg-green-50 rounded-xl flex items-center justify-center shrink-0">
        <i class="fas fa-wallet text-green-500"></i>
      </div>
    </div>
    <p class="text-xs text-gray-400 mt-2">Dari pesanan lunas</p>
  </div>

  {{-- Total Produk --}}
  <div class="stat-card xl:col-span-1">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Total Produk</p>
        <h3 class="text-3xl font-extrabold text-[#1a0d06]">{{ $totalProducts }}</h3>
      </div>
      <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center shrink-0">
        <i class="fas fa-mug-hot text-amber-500"></i>
      </div>
    </div>
    <p class="text-xs text-gray-400 mt-2">Menu tersedia</p>
  </div>

  {{-- Total Users --}}
  <div class="stat-card xl:col-span-1">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Total Pelanggan</p>
        <h3 class="text-3xl font-extrabold text-[#1a0d06]">{{ $totalUsers }}</h3>
      </div>
      <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center shrink-0">
        <i class="fas fa-users text-blue-400"></i>
      </div>
    </div>
    <p class="text-xs text-gray-400 mt-2">Terdaftar</p>
  </div>

  {{-- Pending Orders --}}
  <div class="stat-card xl:col-span-1 {{ $pendingOrders > 0 ? 'ring-2 ring-orange-300' : '' }}">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Pending</p>
        <h3 class="text-3xl font-extrabold {{ $pendingOrders > 0 ? 'text-orange-500' : 'text-gray-400' }}">
          {{ $pendingOrders }}
        </h3>
      </div>
      <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center shrink-0">
        <i class="fas fa-clock text-orange-400"></i>
      </div>
    </div>
    <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}"
      class="text-xs text-orange-500 hover:underline mt-2 block font-medium">
      Lihat pesanan 
    </a>
  </div>

  {{-- Low Stock --}}
  <div class="stat-card xl:col-span-1 {{ $lowStockProducts > 0 ? 'ring-2 ring-red-300' : '' }}">
    <div class="flex items-start justify-between">
      <div>
        <p class="text-xs text-gray-400 font-medium uppercase tracking-wide mb-1">Stok Rendah</p>
        <h3 class="text-3xl font-extrabold {{ $lowStockProducts > 0 ? 'text-red-500' : 'text-gray-400' }}">
          {{ $lowStockProducts }}
        </h3>
      </div>
      <div class="w-10 h-10 bg-red-50 rounded-xl flex items-center justify-center shrink-0">
        <i class="fas fa-triangle-exclamation text-red-400"></i>
      </div>
    </div>
    <p class="text-xs text-gray-400 mt-2">Produk 5 stok</p>
  </div>

</div>

{{-- CHARTS ROW --}}
<div class="grid grid-cols-1 xl:grid-cols-5 gap-5 mb-5">

  {{-- Line Chart: Trend 7 hari --}}
  <div class="xl:col-span-3 page-card p-5">
    <div class="flex items-center justify-between mb-5">
      <div>
        <h3 class="font-bold text-[#1a0d06] text-base">Tren Pesanan & Revenue</h3>
        <p class="text-xs text-gray-400 mt-0.5">7 hari terakhir</p>
      </div>
      <div class="flex gap-4 text-xs font-semibold">
        <span class="flex items-center gap-1.5 text-[#2b160b]">
          <span class="w-3 h-3 rounded-full bg-[#c8a87a] inline-block"></span>Pesanan
        </span>
        <span class="flex items-center gap-1.5 text-green-600">
          <span class="w-3 h-3 rounded-full bg-green-400 inline-block"></span>Revenue
        </span>
      </div>
    </div>
    <div class="relative h-56">
      <canvas id="trendChart"></canvas>
    </div>
  </div>

  {{-- Doughnut: Top 5 produk terlaris --}}
  <div class="xl:col-span-2 page-card p-5">
    <div class="mb-5">
      <h3 class="font-bold text-[#1a0d06] text-base">Produk Terlaris</h3>
      <p class="text-xs text-gray-400 mt-0.5">Top 5 berdasarkan qty terjual</p>
    </div>

    @if($topProducts->isEmpty())
      <div class="flex flex-col items-center justify-center h-48 text-gray-300">
        <i class="fas fa-chart-pie text-4xl mb-2"></i>
        <p class="text-sm">Belum ada data penjualan</p>
      </div>
    @else
      <div class="flex items-center gap-4">
        <div class="relative h-44 w-44 shrink-0">
          <canvas id="topProductsChart"></canvas>
        </div>
        <div class="flex-1 space-y-2 min-w-0">
          @foreach($topProducts as $i => $tp)
            @php
              $colors = ['bg-[#2b160b]','bg-[#c8a87a]','bg-amber-400','bg-orange-300','bg-yellow-200'];
              $textColors = ['text-white','text-white','text-white','text-white','text-[#1a0d06]'];
            @endphp
            <div class="flex items-center gap-2">
              <span class="w-5 h-5 {{ $colors[$i] }} {{ $textColors[$i] }} text-[9px] font-bold rounded-full flex items-center justify-center shrink-0">{{ $i+1 }}</span>
              <div class="flex-1 min-w-0">
                <p class="text-xs font-semibold text-[#1a0d06] truncate">{{ $tp->name }}</p>
                <div class="flex items-center gap-1 mt-0.5">
                  @php
                    $maxSold = $topProducts->max('total_sold');
                    $pct = $maxSold > 0 ? round(($tp->total_sold / $maxSold) * 100) : 0;
                  @endphp
                  <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full {{ $colors[$i] }} rounded-full transition-all" style="width: {{ $pct }}%"></div>
                  </div>
                  <span class="text-[10px] text-gray-400 shrink-0">{{ $tp->total_sold }}</span>
                </div>
              </div>
            </div>
          @endforeach
        </div>
      </div>
    @endif
  </div>

</div>

{{-- BOTTOM ROW --}}
<div class="grid grid-cols-1 xl:grid-cols-3 gap-5">

  {{-- Recent Orders --}}
  <div class="xl:col-span-2 page-card overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
      <h3 class="font-bold text-[#1a0d06]">Pesanan Terbaru</h3>
      <a href="{{ route('admin.orders.index') }}"
        class="text-xs text-[#c8a87a] font-semibold hover:underline">Lihat Semua </a>
    </div>
    <div class="overflow-x-auto">
      <table class="w-full text-sm">
        <thead>
          <tr class="bg-[#faf6f2]">
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Invoice</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Pelanggan</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Total</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Status</th>
            <th class="px-5 py-3 text-left text-xs font-semibold text-gray-400 uppercase tracking-wide">Aksi</th>
          </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
          @forelse($recentOrders as $order)
            @php
              $statusMap = [
                'pending'  => ['bg-orange-100 text-orange-700', ''],
                'processing' => ['bg-blue-100 text-blue-700',   ''],
                'shipping'  => ['bg-purple-100 text-purple-700', ''],
                'completed' => ['bg-green-100 text-green-700',  ''],
                'cancelled' => ['bg-red-100 text-red-700',    ''],
              ];
              $sc = $statusMap[$order->order_status] ?? ['bg-gray-100 text-gray-600', ''];
            @endphp
            <tr class="table-row">
              <td class="px-5 py-3">
                <span class="font-mono text-xs font-bold text-[#c8a87a]">#{{ $order->invoice_number }}</span>
              </td>
              <td class="px-5 py-3 text-gray-700 font-medium">{{ $order->user->name ?? '' }}</td>
              <td class="px-5 py-3 font-bold text-[#1a0d06]">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
              </td>
              <td class="px-5 py-3">
                <span class="badge-status {{ $sc[0] }}">{{ $sc[1] }} {{ ucfirst($order->order_status) }}</span>
              </td>
              <td class="px-5 py-3">
                <a href="{{ route('admin.orders.show', $order) }}"
                  class="bg-[#f4ebe0] text-[#2b160b] px-3 py-1.5 rounded-lg text-xs font-semibold hover:bg-[#e8d9cc] transition">
                  Detail
                </a>
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="5" class="px-5 py-10 text-center text-gray-400 text-sm">
                <div class="text-3xl mb-1"></div>
                Belum ada pesanan
              </td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Low Stock Alert --}}
  <div class="page-card overflow-hidden">
    <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
      <h3 class="font-bold text-[#1a0d06]"> Stok Hampir Habis</h3>
      <a href="{{ route('admin.products.index') }}"
        class="text-xs text-[#c8a87a] font-semibold hover:underline">Kelola </a>
    </div>

    @if($lowStockList->isEmpty())
      <div class="px-5 py-10 text-center">
        <div class="text-3xl mb-2"></div>
        <p class="text-sm font-semibold text-gray-500">Semua stok aman</p>
        <p class="text-xs text-gray-400 mt-1">Tidak ada produk dengan stok 5</p>
      </div>
    @else
      <div class="divide-y divide-gray-50">
        @foreach($lowStockList as $product)
          <div class="flex items-center gap-3 px-5 py-3">
            @if($product->image)
              <img src="{{ $product->image_url }}"
                 class="w-10 h-10 object-cover rounded-xl shrink-0">
            @else
              <div class="w-10 h-10 bg-[#f4ebe0] rounded-xl flex items-center justify-center text-lg shrink-0"></div>
            @endif
            <div class="flex-1 min-w-0">
              <p class="text-sm font-semibold text-[#1a0d06] truncate">{{ $product->name }}</p>
              <p class="text-xs text-gray-400">{{ $product->category->name ?? '' }}</p>
            </div>
            <div class="shrink-0 text-right">
              <span class="text-sm font-bold {{ $product->stock == 0 ? 'text-red-600' : 'text-orange-500' }}">
                {{ $product->stock }}
              </span>
              <p class="text-[10px] text-gray-400">stok</p>
            </div>
          </div>
        @endforeach
      </div>
    @endif
  </div>

</div>

@endsection


@push('scripts')
<script>
// DATA FROM PHP 
const chartDates  = @json($chartDates);
const chartOrders = @json($chartOrders);
const chartRevenue = @json($chartRevenue);
const topProductNames = @json($topProducts->pluck('name'));
const topProductSold = @json($topProducts->pluck('total_sold'));

// GLOBAL CHART DEFAULTS 
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.font.size  = 12;
Chart.defaults.color    = '#9ca3af';

// TREND CHART (Line) 
const trendCtx = document.getElementById('trendChart');
if (trendCtx) {
  new Chart(trendCtx, {
    type: 'line',
    data: {
      labels: chartDates,
      datasets: [
        {
          label: 'Pesanan',
          data: chartOrders,
          borderColor: '#2b160b',
          backgroundColor: 'rgba(43,22,11,0.06)',
          borderWidth: 2.5,
          pointBackgroundColor: '#c8a87a',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
          tension: 0.4,
          fill: true,
          yAxisID: 'y',
        },
        {
          label: 'Revenue (Rp)',
          data: chartRevenue,
          borderColor: '#22c55e',
          backgroundColor: 'rgba(34,197,94,0.06)',
          borderWidth: 2.5,
          pointBackgroundColor: '#22c55e',
          pointBorderColor: '#fff',
          pointBorderWidth: 2,
          pointRadius: 5,
          pointHoverRadius: 7,
          tension: 0.4,
          fill: true,
          yAxisID: 'y1',
        }
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      interaction: { mode: 'index', intersect: false },
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1a0d06',
          titleColor: '#f5dfc0',
          bodyColor: '#fff',
          padding: 12,
          cornerRadius: 10,
          callbacks: {
            label: ctx => {
              if (ctx.dataset.label === 'Revenue (Rp)') {
                return ' Rp ' + ctx.parsed.y.toLocaleString('id-ID');
              }
              return ' ' + ctx.parsed.y + ' pesanan';
            }
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          border: { display: false },
          ticks: { font: { size: 11 } }
        },
        y: {
          position: 'left',
          grid: { color: 'rgba(0,0,0,0.04)' },
          border: { display: false, dash: [4,4] },
          ticks: {
            font: { size: 11 },
            stepSize: 1,
            callback: v => v + ' '
          }
        },
        y1: {
          position: 'right',
          grid: { display: false },
          border: { display: false },
          ticks: {
            font: { size: 10 },
            callback: v => 'Rp ' + (v / 1000).toFixed(0) + 'k'
          }
        }
      }
    }
  });
}

// TOP PRODUCTS CHART (Doughnut) 
const donutCtx = document.getElementById('topProductsChart');
if (donutCtx && topProductNames.length > 0) {
  new Chart(donutCtx, {
    type: 'doughnut',
    data: {
      labels: topProductNames,
      datasets: [{
        data: topProductSold,
        backgroundColor: ['#2b160b','#c8a87a','#f59e0b','#fbbf24','#fde68a'],
        borderWidth: 0,
        hoverOffset: 6,
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      cutout: '65%',
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#1a0d06',
          titleColor: '#f5dfc0',
          bodyColor: '#fff',
          padding: 10,
          cornerRadius: 8,
          callbacks: {
            label: ctx => ' ' + ctx.parsed + ' terjual'
          }
        }
      }
    }
  });
}
</script>
@endpush
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('title', 'Admin') - 17coffee</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');
    body { font-family: 'Plus Jakarta Sans', sans-serif; }

    ::-webkit-scrollbar { width: 5px; }
    ::-webkit-scrollbar-track { background: #f7efe5; }
    ::-webkit-scrollbar-thumb { background: #c8a87a; border-radius: 99px; }

    .sidebar-link {
      display: flex;
      align-items: center;
      gap: 12px;
      padding: 11px 14px;
      border-radius: 12px;
      font-size: 0.875rem;
      font-weight: 500;
      color: rgba(255,255,255,0.75);
      transition: all 0.15s ease;
      cursor: pointer;
    }
    .sidebar-link:hover { background: rgba(255,255,255,0.1); color: #fff; }
    .sidebar-link.active { background: linear-gradient(135deg, #c8a87a22, #c8a87a33); color: #f5dfc0; font-weight: 600; box-shadow: inset 0 0 0 1px rgba(200,168,122,0.3); }
    .sidebar-icon { width: 20px; text-align: center; font-size: 14px; color: #c8a87a; }

    .stat-card { background: white; border-radius: 20px; padding: 24px; box-shadow: 0 2px 12px rgba(43,22,11,0.07); transition: transform 0.2s, box-shadow 0.2s; }
    .stat-card:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(43,22,11,0.12); }

    .badge-status { padding: 4px 12px; border-radius: 99px; font-size: 0.75rem; font-weight: 600; }

    #sidebar { transition: transform 0.3s cubic-bezier(.4,0,.2,1); }
    @media (max-width: 768px) {
      #sidebar { transform: translateX(-100%); }
      #sidebar.open { transform: translateX(0); }
    }
    .main-content { transition: margin 0.3s; }

    input, select, textarea {
      border: 1.5px solid #e5d5c8;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 0.9rem;
      width: 100%;
      transition: border-color 0.2s, box-shadow 0.2s;
      background: #fff;
      color: #1a0d06;
    }
    input:focus, select:focus, textarea:focus {
      outline: none;
      border-color: #c8a87a;
      box-shadow: 0 0 0 3px rgba(200,168,122,0.2);
    }

    .btn-primary {
      background: linear-gradient(135deg, #2b160b, #3d2010);
      color: white;
      padding: 11px 24px;
      border-radius: 12px;
      font-weight: 600;
      font-size: 0.9rem;
      transition: all 0.2s;
      border: none;
      cursor: pointer;
    }
    .btn-primary:hover { background: linear-gradient(135deg, #3d2010, #4e2e18); transform: translateY(-1px); box-shadow: 0 4px 12px rgba(43,22,11,0.3); }

    .btn-danger { background: #fee2e2; color: #dc2626; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.8rem; transition: all 0.15s; }
    .btn-danger:hover { background: #fecaca; }

    .btn-edit { background: #fef9c3; color: #854d0e; padding: 8px 16px; border-radius: 10px; font-weight: 600; font-size: 0.8rem; transition: all 0.15s; }
    .btn-edit:hover { background: #fef08a; }

    .table-row:hover { background: #fff7ed; }

    .page-card { background: white; border-radius: 20px; box-shadow: 0 2px 12px rgba(43,22,11,0.07); overflow: hidden; }

    label { display: block; font-size: 0.875rem; font-weight: 600; color: #2b160b; margin-bottom: 6px; }
  </style>
  @stack('styles')
</head>
<body class="bg-[#f4ebe0]">

<!-- Mobile Overlay -->
<div id="overlay" onclick="closeSidebar()"
   class="fixed inset-0 bg-black/40 z-30 hidden md:hidden backdrop-blur-sm"></div>

<!-- SIDEBAR -->
<aside id="sidebar"
    class="fixed top-0 left-0 h-screen w-64 bg-[#130a04] text-white z-40 flex flex-col overflow-hidden">

  <!-- Logo -->
  <div class="px-5 py-5 border-b border-white/10 flex items-center gap-3">
    <div class="w-10 h-10 bg-gradient-to-br from-[#c8a87a] to-[#a07840] rounded-xl flex items-center justify-center shadow overflow-hidden">
        <img src="{{ asset('images/cappuccino.jpg') }}" alt="17coffee" class="w-full h-full object-cover">
      </div>
    <div>
      <h1 class="text-base font-bold tracking-tight">17coffee</h1>
      <p class="text-[10px] text-white/40 font-medium uppercase tracking-widest">Admin Panel</p>
    </div>
  </div>

  <!-- Nav -->
  <nav class="flex-1 px-3 py-4 overflow-y-auto space-y-0.5">
    <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold px-3 mb-2">Manajemen</p>

    <a href="{{ route('admin.dashboard') }}"
      class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
      <span class="sidebar-icon"><i class="fas fa-chart-pie"></i></span>Dashboard
    </a>

    <a href="{{ route('admin.categories.index') }}"
      class="sidebar-link {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
      <span class="sidebar-icon"><i class="fas fa-tags"></i></span>Kategori Menu
    </a>

    <a href="{{ route('admin.products.index') }}"
      class="sidebar-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
      <span class="sidebar-icon"><i class="fas fa-mug-hot"></i></span>Kelola Produk
    </a>

    <div class="border-t border-white/10 my-3"></div>
    <p class="text-[10px] uppercase tracking-widest text-white/30 font-semibold px-3 mb-2">Transaksi</p>

    <a href="{{ route('admin.orders.index') }}"
      class="sidebar-link {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
      <span class="sidebar-icon"><i class="fas fa-shopping-bag"></i></span>
      Pesanan
      @php $pendingCount = \App\Models\Order::where('order_status','pending')->count(); @endphp
      @if($pendingCount > 0)
        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $pendingCount }}</span>
      @endif
    </a>

    <a href="{{ route('admin.ratings.index') }}"
      class="sidebar-link {{ request()->routeIs('admin.ratings*') ? 'active' : '' }}">
      <span class="sidebar-icon"><i class="fas fa-star"></i></span>Rating & Ulasan
    </a>
  </nav>

  <!-- User + Logout -->
  <div class="px-3 py-4 border-t border-white/10">
    <div class="flex items-center gap-3 px-3 mb-3">
      <div class="w-9 h-9 bg-gradient-to-br from-[#c8a87a] to-[#a07840] rounded-full flex items-center justify-center text-sm font-bold text-[#130a04] shrink-0">
        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
      </div>
      <div class="overflow-hidden">
        <p class="text-sm font-semibold truncate leading-tight">{{ auth()->user()->name }}</p>
        <p class="text-[11px] text-white/40 truncate">Administrator</p>
      </div>
    </div>
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="sidebar-link w-full text-red-400 hover:bg-red-950/40">
        <span class="sidebar-icon text-red-400"><i class="fas fa-arrow-right-from-bracket"></i></span>
        Keluar
      </button>
    </form>
  </div>
</aside>

<!-- MAIN -->
<div class="main-content md:ml-64 min-h-screen flex flex-col">

  <!-- Top bar -->
  <header class="sticky top-0 z-20 bg-white/80 backdrop-blur-md border-b border-[#e8d9cc] px-6 py-3.5 flex items-center justify-between">
    <div class="flex items-center gap-4">
      <button onclick="toggleSidebar()" class="md:hidden w-9 h-9 flex items-center justify-center rounded-lg bg-[#f4ebe0] text-[#2b160b]">
        <i class="fas fa-bars"></i>
      </button>
      <div>
        <h2 class="text-lg font-bold text-[#1a0d06] leading-tight">@yield('page-title', 'Dashboard')</h2>
        <p class="text-xs text-gray-400">@yield('page-subtitle', '17coffee Admin Panel')</p>
      </div>
    </div>
    <div class="hidden sm:flex items-center gap-2 text-sm text-gray-400 bg-[#f4ebe0] px-4 py-2 rounded-xl">
      <i class="fas fa-calendar-alt text-[#c8a87a]"></i>
      {{ \Carbon\Carbon::now('Asia/Jakarta')->locale('id')->translatedFormat('l, j F Y') }}
    </div>
  </header>

  <!-- Alert -->
  <div class="px-6 pt-4 space-y-2">
    @if(session('success'))
      <div class="bg-green-50 border border-green-200 text-green-700 px-5 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
        <i class="fas fa-check-circle text-green-500"></i> {{ session('success') }}
      </div>
    @endif
    @if(session('error'))
      <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl flex items-center gap-2 text-sm font-medium">
        <i class="fas fa-exclamation-circle text-red-500"></i> {{ session('error') }}
      </div>
    @endif
    @if($errors->any())
      <div class="bg-red-50 border border-red-200 text-red-700 px-5 py-3 rounded-xl text-sm">
        <p class="font-semibold mb-1"><i class="fas fa-times-circle text-red-500"></i> Terjadi kesalahan:</p>
        <ul class="list-disc ml-5 space-y-0.5">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif
  </div>

  <!-- Content -->
  <main class="flex-1 px-6 py-6">
    @yield('content')
  </main>

  <footer class="text-center text-xs text-gray-400/70 py-4 border-t border-[#e8d9cc]">Copyright {{ date('Y') }} 17coffee - Admin Panel</footer>
</div>

<script>
function toggleSidebar() {
  document.getElementById('sidebar').classList.toggle('open');
  document.getElementById('overlay').classList.toggle('hidden');
}
function closeSidebar() {
  document.getElementById('sidebar').classList.remove('open');
  document.getElementById('overlay').classList.add('hidden');
}
</script>
@stack('scripts')
</body>
</html>
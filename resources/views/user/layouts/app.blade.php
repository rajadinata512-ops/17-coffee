<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', '17 Coffee')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <style>
        @import url('https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700;800;900&family=Playfair+Display:wght@600;700;800&display=swap');

        body {
            font-family: 'DM Sans', sans-serif;
            background:
                radial-gradient(circle at 12% 8%, rgba(216,146,43,.20), transparent 28%),
                radial-gradient(circle at 90% 0%, rgba(184,106,26,.18), transparent 28%),
                linear-gradient(135deg, #130a04, #2b160b 58%, #4b2a13);
            min-height: 100vh;
            color: #2b160b;
        }

        .user-nav {
            position: sticky;
            top: 0;
            z-index: 50;
            background: rgba(19, 10, 4, .92);
            border-bottom: 1px solid rgba(255,255,255,.08);
            backdrop-filter: blur(18px);
        }

        .user-nav-link {
            color: rgba(255,255,255,.72);
            padding: 10px 18px;
            border-radius: 999px;
            font-size: 14px;
            font-weight: 900;
            text-decoration: none;
            transition: .2s ease;
            border: 1px solid transparent;
        }

        .user-nav-link:hover {
            color: #fff;
            background: rgba(255,255,255,.08);
            border-color: rgba(216,146,43,.25);
        }

        .user-nav-link.active {
            color: #fff;
            background: linear-gradient(135deg, #b86a1a, #d8922b);
            border-color: #d8922b;
            box-shadow: 0 10px 25px rgba(216,146,43,.24);
        }

        .btn-coffee {
            background: linear-gradient(135deg, #2b160b, #6f3d18);
            color: #fff;
            border-radius: 999px;
            font-weight: 900;
            transition: .2s;
            box-shadow: 0 12px 28px rgba(43,22,11,.25);
        }

        .btn-coffee:hover {
            transform: translateY(-1px);
            box-shadow: 0 18px 34px rgba(43,22,11,.32);
        }

        .btn-honey {
            background: linear-gradient(135deg, #b86a1a, #d8922b);
            color: #fff;
            border-radius: 999px;
            font-weight: 900;
            transition: .2s;
        }

        .btn-honey:hover {
            transform: translateY(-1px);
        }

        .card {
            background: #fff;
            border: 1px solid #f0e3d8;
            border-radius: 28px;
            box-shadow: 0 16px 40px rgba(43,22,11,.08);
        }

        input, select, textarea {
            border: 1.5px solid #ead9c9;
            border-radius: 18px;
            background: #fff;
            color: #241108;
            outline: none;
            transition: .2s;
        }

        input:focus, select:focus, textarea:focus {
            border-color: #c8a87a;
            box-shadow: 0 0 0 4px rgba(200,168,122,.18);
        }

        .qty-input {
            text-align: center !important;
            padding-left: 0 !important;
            padding-right: 0 !important;
            appearance: textfield;
            -moz-appearance: textfield;
        }

        .qty-input::-webkit-outer-spin-button,
        .qty-input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        @media (max-width: 640px) {
            .user-nav-link {
                padding: 9px 12px;
                font-size: 12px;
            }
        }
    </style>

    @stack('styles')
</head>

<body>
    <nav class="user-nav">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 py-3 flex items-center justify-between gap-4">
            <a href="{{ route('user.menu') }}" class="flex items-center gap-3 text-white no-underline">
                <div class="w-11 h-11 rounded-2xl bg-[#d8922b] overflow-hidden grid place-items-center shadow-lg">
                    <img src="{{ asset('images/cappuccino.jpg') }}"
                         alt="17 Coffee"
                         class="w-full h-full object-cover"
                         onerror="this.style.display='none'">
                </div>

                <div>
                    <div class="font-black leading-tight text-white">17 Coffee</div>
                    <div class="text-[10px] uppercase tracking-[.24em] text-[#c8a87a]">User Order</div>
                </div>
            </a>

            <div class="hidden md:flex items-center gap-2">
                <a href="{{ route('user.menu') }}"
                   class="user-nav-link {{ request()->routeIs('user.menu') ? 'active' : '' }}">
                    Menu
                </a>

                <a href="{{ route('cart.index') }}"
                   class="user-nav-link {{ request()->routeIs('cart.*') ? 'active' : '' }}">
                    Keranjang
                </a>

                <a href="{{ route('orders.index') }}"
                   class="user-nav-link {{ request()->routeIs('orders.*') || request()->routeIs('ratings.*') ? 'active' : '' }}">
                    Pesanan
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="user-nav-link">
                        Keluar
                    </button>
                </form>
            </div>

            <div class="md:hidden flex items-center gap-2">
                <a href="{{ route('user.menu') }}"
                   class="w-10 h-10 rounded-full {{ request()->routeIs('user.menu') ? 'bg-[#d8922b]' : 'bg-white/10' }} text-white grid place-items-center">
                    <i class="fa-solid fa-mug-hot"></i>
                </a>

                <a href="{{ route('cart.index') }}"
                   class="w-10 h-10 rounded-full {{ request()->routeIs('cart.*') ? 'bg-[#d8922b]' : 'bg-white/10' }} text-white grid place-items-center">
                    <i class="fa-solid fa-bag-shopping"></i>
                </a>

                <a href="{{ route('orders.index') }}"
                   class="w-10 h-10 rounded-full {{ request()->routeIs('orders.*') ? 'bg-[#d8922b]' : 'bg-white/10' }} text-white grid place-items-center">
                    <i class="fa-solid fa-receipt"></i>
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-10 h-10 rounded-full bg-white/10 text-white grid place-items-center">
                        <i class="fa-solid fa-right-from-bracket"></i>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 py-8 sm:py-12">
        @if(session('success'))
            <div class="mb-5 rounded-3xl bg-green-50 border border-green-200 text-green-700 px-5 py-4 font-bold flex gap-3">
                <i class="fa-solid fa-circle-check mt-1"></i>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-5 rounded-3xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 font-bold flex gap-3">
                <i class="fa-solid fa-triangle-exclamation mt-1"></i>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if($errors->any())
            <div class="mb-5 rounded-3xl bg-red-50 border border-red-200 text-red-700 px-5 py-4 text-sm">
                <b>Periksa lagi inputnya:</b>
                <ul class="list-disc ml-5 mt-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
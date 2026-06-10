<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="theme-color" content="#1e1b9b">

        <title>KosKora — Platform Manajemen Kos Modern</title>
        <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

        <!-- Fonts & Icons -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        
        <!-- Design System & Logic -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/dashboard.css'])
        <script src="https://cdn.tailwindcss.com"></script>
        
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'brand': '#1e1b9b',
                            'brand-dark': '#14126d',
                            'brand-light': '#f0f1ff',
                        },
                        fontFamily: {
                            sans: ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui'],
                        },
                        borderRadius: {
                            'premium': '16px',
                        }
                    }
                }
            }
        </script>
    </head>
    <body class="antialiased selection:bg-brand/10 selection:text-brand">
        <div class="dashboard-container">
            <!-- Sidebar Overlay (mobile) -->
            @if(auth()->user()->role !== 'user')
            <div class="sidebar-overlay" id="sidebarOverlay" onclick="closeSidebar()"></div>
            @endif

            <!-- Sidebar Navigation -->
            @if(auth()->user()->role === 'user')
                <div class="hidden lg:block">
                    <x-sidebar />
                </div>
            @else
                <x-sidebar />
            @endif

            <!-- Main Panel -->
            <div class="main-content">
                {{-- Navbar (Premium & Sticky) --}}
                <nav class="navbar">
                    {{-- Mobile Hamburger --}}
                    @if(auth()->user()->role !== 'user')
                    <button class="lg:hidden w-10 h-10 flex items-center justify-center text-slate-400 hover:text-brand transition-colors mr-4" onclick="toggleSidebar()">
                        <i class="fas fa-bars-staggered"></i>
                    </button>
                    @endif

                    {{-- Page Context --}}
                    <div class="flex-1">
                        @if(auth()->user()->role === 'user')
                            {{-- On mobile, show logo; on desktop, show title text --}}
                            <div class="block lg:hidden">
                                <img src="{{ asset('koskora.png') }}" alt="KosKora" class="h-8 w-auto">
                            </div>
                            <div class="hidden lg:block">
                                <h1 class="navbar-title">@yield('header_title', 'Dashboard')</h1>
                            </div>
                        @else
                            <h1 class="navbar-title">@yield('header_title', 'Dashboard')</h1>
                        @endif
                    </div>

                    {{-- Top Actions --}}
                    <div class="flex items-center gap-2 sm:gap-4">
                        {{-- Notifications --}}
                        <button class="w-10 h-10 rounded-xl flex items-center justify-center text-slate-400 hover:bg-slate-50 hover:text-brand transition-all relative group">
                            <i class="far fa-bell text-lg transition-transform group-hover:rotate-12"></i>
                            <span class="absolute top-2.5 right-2.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
                        </button>

                        {{-- Mobile Logout for User --}}
                        @if(auth()->user()->role === 'user')
                        <form method="POST" action="{{ route('logout') }}" class="block lg:hidden m-0 p-0">
                            @csrf
                            <button type="submit" onclick="return confirm('Keluar dari aplikasi?')" class="w-10 h-10 rounded-xl flex items-center justify-center text-red-400 hover:bg-red-50 hover:text-red-500 transition-all">
                                <i class="fas fa-sign-out-alt text-lg"></i>
                            </button>
                        </form>
                        @endif

                        {{-- User Quick Access --}}
                        <div class="hidden sm:flex items-center gap-3 pl-4 border-l border-slate-100">
                            <div class="text-right">
                                <div class="text-[12px] font-bold text-slate-800 leading-none capitalize">{{ auth()->user()->name }}</div>
                                <div class="text-[10px] font-semibold text-slate-400 uppercase tracking-widest mt-1 opacity-80">{{ auth()->user()->role }}</div>
                            </div>
                            <div class="w-10 h-10 rounded-xl bg-brand/10 text-brand flex items-center justify-center font-bold text-xs border border-brand/20 shadow-sm">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                        </div>
                    </div>
                </nav>

                <!-- Page Content Ecosystem -->
                <main class="page-content {{ auth()->user()->role === 'user' ? 'has-bottom-nav' : '' }}">
                    {{ $slot }}
                </main>
            </div>
        </div>

        {{-- ===== GLOBAL BOTTOM NAV (User Role Only) ===== --}}
        @if(auth()->user()->role === 'user')
        <nav class="ud-bottom-nav lg:hidden">
            <a href="{{ route('dashboard') }}" class="ud-bnav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-home-alt"></i>
                <span>Home</span>
            </a>
            <a href="{{ route('rent-payments.my-payments') }}" class="ud-bnav-item {{ request()->routeIs('rent-payments.my-payments') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i>
                <span>Tagihan</span>
            </a>
            <a href="{{ route('user.laundry.index') }}" class="ud-bnav-item ud-bnav-center">
                <div class="ud-bnav-center-btn">
                    <i class="fas fa-concierge-bell"></i>
                </div>
                <span>Layanan</span>
            </a>
            <a href="{{ route('user.announcements.index') }}" class="ud-bnav-item {{ request()->routeIs('user.announcements.*') ? 'active' : '' }}">
                <i class="fas fa-bullhorn"></i>
                <span>Info</span>
            </a>
            <a href="{{ route('profile.edit') }}" class="ud-bnav-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
                <i class="fas fa-user"></i>
                <span>Profil</span>
            </a>
        </nav>
        @endif

        <!-- Layout Interaction Scripts -->
        <script>
            function toggleSidebar() {
                const sb = document.querySelector('.sidebar');
                const ov = document.getElementById('sidebarOverlay');
                if (sb) sb.classList.toggle('open');
                if (ov) ov.classList.toggle('active');
            }
            function closeSidebar() {
                const sb = document.querySelector('.sidebar');
                const ov = document.getElementById('sidebarOverlay');
                if (sb) sb.classList.remove('open');
                if (ov) ov.classList.remove('active');
            }
        </script>
        @stack('scripts')
    </body>
</html>


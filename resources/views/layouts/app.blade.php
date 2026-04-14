<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Outfit:wght@700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/dashboard.css'])
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
            tailwind.config = {
                theme: {
                    extend: {
                        colors: {
                            'brand-blue': '#1e1b9b',
                            'brand-red': '#eb0008',
                        }
                    }
                }
            }
        </script>
    </head>
    <body class="font-sans antialiased text-slate-900 bg-slate-50">
        <div class="dashboard-container">
            <!-- Sidebar -->
            <x-sidebar />

            <!-- Main Content -->
            <div class="main-content">
                <!-- Navbar -->
                <nav class="navbar border-b bg-white border-slate-200">
                    <div class="text-xl font-bold text-slate-800">
                        @yield('header_title', 'Dashboard')
                    </div>
                    <div class="flex items-center space-x-6">
                        <div class="flex flex-col items-end">
                            <span class="text-sm font-semibold text-slate-700">{{ auth()->user()->name }}</span>
                            <span class="text-xs font-medium text-slate-400 uppercase tracking-widest">{{ auth()->user()->role }}</span>
                        </div>
                        
                        <div class="h-8 w-px bg-slate-100 hidden sm:block"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="flex items-center text-slate-400 hover:text-rose-600 transition-all group px-3 py-2 rounded-xl hover:bg-rose-50">
                                <span class="text-xs font-bold mr-2 hidden md:block">Logout</span>
                                <svg class="w-5 h-5 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                            </button>
                        </form>
                    </div>
                </nav>

                <!-- Page Content -->
                <main class="page-content">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>

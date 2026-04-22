<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>KosKora | Premium Living Redefined</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Google Fonts: System & Modern Weights -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS + Custom Config -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            navy: '#1e1b9b',
                            red: '#d42e2e',
                        }
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    borderRadius: {
                        'xl': '1rem',
                        '2xl': '1.25rem',
                        '3xl': '1.5rem',
                        '4xl': '2rem',
                    }
                }
            }
        }
    </script>
    <style>
        /* custom overrides & brand behavior with refined rounding */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; background: #ffffff; -webkit-font-smoothing: antialiased; }

        /* Modern rounded utilities */
        .rounded-soft { border-radius: 1.25rem; }
        .rounded-soft-lg { border-radius: 1.5rem; }
        .rounded-soft-xl { border-radius: 2rem; }
        .rounded-card { border-radius: 1.5rem; }
        .rounded-button { border-radius: 0.875rem; }
        
        /* Brand-centric gradients & shadows */
        .brand-gradient-bg { background: linear-gradient(135deg, #1e1b9b 0%, #11106e 100%); }
        .brand-shadow { box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.02); }
        .brand-shadow-soft { box-shadow: 0 15px 30px -15px rgba(30, 27, 155, 0.1); }
        .brand-card-hover { transition: all 0.35s cubic-bezier(0.2, 0, 0, 0.2); }
        .brand-card-hover:hover { transform: translateY(-6px); box-shadow: 0 30px 50px -20px rgba(30, 27, 155, 0.25); border-color: rgba(30, 27, 155, 0.2); }
        
        .input-modern { transition: all 0.2s ease; background-color: #fcfdfe; border: 2px solid #eef2f6; border-radius: 1rem; }
        .input-modern:focus { background-color: #ffffff; border-color: #1e1b9b; outline: none; box-shadow: 0 0 0 4px rgba(30,27,155,0.05); }
        
        .btn-primary { transition: all 0.25s ease; background: #1e1b9b; border-radius: 0.875rem; }
        .btn-primary:hover { background: #11106e; transform: scale(0.98); }
        .btn-accent { transition: all 0.25s ease; background: #eb0008; border-radius: 0.875rem; }
        .btn-accent:hover { background: #b11f1f; transform: scale(0.98); }
        
        .glass-nav { background: rgba(255,255,255,0.96); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.03); border-radius: 0 0 2rem 2rem; }
        .section-title-highlight { position: relative; display: inline-block; }
        .section-title-highlight:after { content: ''; position: absolute; bottom: -8px; left: 0; width: 48px; height: 3px; background: #d42e2e; border-radius: 6px; }
        .badge-status { backdrop-filter: blur(4px); background: rgba(0,0,0,0.65); border-radius: 2rem; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        img { display: block; max-width: 100%; }
        
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.02); opacity: 0.95; }
        }
        .animate-pulse-slow { animation: pulse-slow 4s infinite ease-in-out; }

        /* Modern card inner rounding */
        .card-modern {
            border-radius: 1.5rem;
            transition: all 0.3s cubic-bezier(0.2, 0, 0, 1);
        }
        .service-card {
            border-radius: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .service-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 30px -15px rgba(0,0,0,0.08);
        }
    </style>
</head>
<body class="antialiased text-slate-800 overflow-x-hidden">

    <!-- ========== NAVIGATION with rounded bottom ========== -->
    <nav class="glass-nav fixed top-0 w-full z-50 py-4 md:py-5 px-4 md:px-10 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo + Branding -->
            <div class="flex items-center gap-3">
                <a href="/">
                    <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-8 w-auto md:h-10">
                </a>
                <span class="text-[10px] font-bold tracking-[0.2em] text-slate-400 hidden sm:block border-l border-slate-200 pl-3 uppercase">Premium Living</span>
            </div>
            
            <div class="hidden md:flex items-center gap-8">
                <a href="#katalog" class="text-[12px] font-semibold text-slate-500 hover:text-brand-navy transition uppercase tracking-wide">Katalog</a>
                <a href="#layanan" class="text-[12px] font-semibold text-slate-500 hover:text-brand-navy transition uppercase tracking-wide">Layanan</a>
                <a href="#tentang" class="text-[12px] font-semibold text-slate-500 hover:text-brand-navy transition uppercase tracking-wide">Tentang</a>
            </div>
            
            <div class="flex items-center gap-3">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-brand-navy text-white text-[11px] font-bold rounded-button shadow-md hover:bg-brand-red transition-all duration-200 uppercase tracking-wide">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-block text-[11px] font-bold text-slate-500 hover:text-brand-navy transition uppercase tracking-wider">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2.5 bg-brand-navy text-white text-[11px] font-bold rounded-button shadow-md hover:bg-brand-red transition-all duration-200 uppercase tracking-wide">Join Pro</a>
                        @endif
                    @endauth
                @endif

                <!-- Mobile hamburger -->
                <button onclick="document.getElementById('mobileNav').classList.toggle('hidden')" class="md:hidden p-2 rounded-xl hover:bg-slate-100 transition" aria-label="Menu">
                    <svg class="w-5 h-5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <!-- Mobile Nav Drawer -->
        <div id="mobileNav" class="hidden md:hidden mt-4 pb-2 border-t border-slate-100 pt-4">
            <div class="flex flex-col gap-3">
                <a href="#katalog" class="text-sm font-semibold text-slate-600 hover:text-brand-navy py-2">Katalog</a>
                <a href="#layanan" class="text-sm font-semibold text-slate-600 hover:text-brand-navy py-2">Layanan</a>
                <a href="#tentang" class="text-sm font-semibold text-slate-600 hover:text-brand-navy py-2">Tentang</a>
                @guest
                    <a href="{{ route('login') }}" class="text-sm font-semibold text-brand-navy py-2">Masuk</a>
                @endguest
            </div>
        </div>
    </nav>

    <!-- ========== HERO SECTION with rounded corners ========== -->
    <section class="relative pt-24 md:pt-32 pb-16 md:pb-24 px-4 md:px-6 overflow-hidden">
        <!-- Background Image with Overlay & Blur -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('hero-bg.png') }}" class="w-full h-full object-cover opacity-90 scale-105 transform">
            <div class="absolute inset-0 bg-white/40 backdrop-blur-[2px]"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-white/10 via-white/40 to-white"></div>
        </div>

        <div class="max-w-6xl mx-auto text-center relative z-10">
            <div class="inline-flex mb-8 bg-white/80 backdrop-blur-md rounded-full px-5 py-2 border border-brand-navy/10 shadow-sm animate-pulse-slow">
                <span class="text-[10px] font-black uppercase tracking-[0.25em] text-brand-navy flex items-center gap-2">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-navy opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-navy"></span>
                    </span>
                    Premium Ecosystem
                </span>
            </div>
            <h1 class="text-3xl sm:text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.05] text-brand-navy max-w-4xl mx-auto drop-shadow-[0_10px_10px_rgba(255,255,255,0.8)]">
                Hunian Berkelas.<br>
                <span class="text-brand-red bg-clip-text">Pengelolaan Tanpa Ribet.</span>
            </h1>
            <p class="text-slate-700 text-xs md:text-lg max-w-2xl mx-auto mt-5 md:mt-8 font-semibold leading-relaxed drop-shadow-sm">
                Manajemen kos profesional & teknologi cerdas. <span class="text-brand-navy/60 font-medium hidden sm:inline">Nikmati kemudahan cari kamar, laundry, dan cleaner terverifikasi dalam satu genggaman.</span>
            </p>
            
            <!-- Search Console with rounded inputs -->
            <div class="max-w-4xl mx-auto mt-6 md:mt-8 bg-white/90 backdrop-blur-sm brand-shadow-soft p-2 md:p-3 rounded-2xl border border-slate-100">
                <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="flex-[2] relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari area, tipe..." class="w-full pl-12 pr-4 py-4 rounded-xl input-modern font-medium text-sm">
                    </div>
                    <div class="flex-1">
                        <select name="city" class="w-full px-5 py-4 rounded-xl input-modern font-medium text-sm cursor-pointer appearance-none">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="w-full md:w-auto px-8 md:px-12 py-4 bg-brand-navy text-white rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-brand-red transition-all shadow-md">Jelajahi</button>
                </form>
            </div>
            
            <!-- quick stats -->
            <div class="flex flex-wrap justify-center gap-8 mt-14">
                <div><span class="font-extrabold text-2xl text-brand-navy">150+</span><span class="text-xs font-semibold text-slate-400 block uppercase">Unit Premium</span></div>
                <div><span class="font-extrabold text-2xl text-brand-navy">12</span><span class="text-xs font-semibold text-slate-400 block uppercase">Kota Besar</span></div>
                <div><span class="font-extrabold text-2xl text-brand-navy">99%</span><span class="text-xs font-semibold text-slate-400 block uppercase">Kepuasan Tenant</span></div>
            </div>
        </div>
    </section>

    <!-- ========== CATALOG SECTION (ROOMS) rounded cards ========== -->
    <section id="katalog" class="py-16 px-6 scroll-mt-24 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap justify-between items-end gap-6 mb-12">
                <div>
                    <span class="text-[11px] font-black text-brand-red uppercase tracking-[0.3em]">Koleksi eksklusif</span>
                    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mt-3">Rekomendasi Kamar<span class="text-slate-300">.</span></h2>
                    <p class="text-slate-400 text-sm max-w-lg mt-2">Unit terbaik dengan standar kebersihan & keamanan tinggi.</p>
                </div>
                <a href="#katalog" class="text-[12px] font-bold uppercase tracking-wider text-brand-navy border-b border-brand-navy pb-1 hover:text-brand-red transition">Lihat semua →</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($groupedRooms as $property)
                    <div class="group bg-white rounded-2xl border border-slate-100 overflow-hidden brand-card-hover transition-all flex flex-col shadow-sm hover:shadow-xl">
                        <div class="relative h-64 bg-slate-50 overflow-hidden rounded-t-2xl">
                            @if($property->thumbnail)
                                <img src="{{ asset('storage/' . $property->thumbnail) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-100">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span class="px-3 py-1.5 bg-brand-navy text-white text-[10px] font-bold rounded-full shadow-md uppercase tracking-widest">
                                    {{ $property->gender }}
                                </span>
                            </div>
                            <div class="absolute bottom-4 left-4 right-4 capitalize">
                                <div class="px-4 py-2 bg-white/90 backdrop-blur-md rounded-2xl shadow-lg flex items-center justify-between border border-white/50">
                                    <div class="flex items-center gap-1.5">
                                        <i class="fas fa-star text-amber-400 text-[10px]"></i>
                                        <span class="text-[11px] font-bold text-slate-900">{{ number_format($property->avg_rating, 1) }}</span>
                                        <span class="text-[9px] text-slate-400 font-medium lowercase">({{ $property->total_reviews }} ulasan)</span>
                                    </div>
                                    <div class="h-4 w-[1px] bg-slate-200 mx-2"></div>
                                    <div class="flex gap-1">
                                        @foreach($property->room_types as $type)
                                            <span class="text-[8px] font-black text-brand-navy uppercase tracking-tighter">{{ $type }}</span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <h3 class="text-2xl font-extrabold text-slate-900 mb-1 leading-tight">{{ $property->name }}</h3>
                            <div class="flex items-center gap-1 text-slate-400 text-xs mb-4">
                                <svg class="w-4 h-4 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="text-[11px] font-medium uppercase tracking-wider">{{ $property->location }}</span>
                            </div>
                            
                            <div class="flex items-center justify-between pt-5 border-t border-slate-50 mt-auto">
                                <div>
                                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Mulai dari</p>
                                    <p class="text-xl font-extrabold text-brand-navy">Rp {{ number_format($property->min_price / 1000, 1, '.', '') }}jt<span class="text-xs text-slate-400 font-normal">/bln</span></p>
                                </div>
                                <button onclick="openPropertyModal({{ json_encode($property) }})" class="px-8 py-3 bg-brand-navy text-white text-[10px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-red transition shadow-sm active:scale-95">Lihat Detail</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center text-slate-300 font-bold uppercase tracking-[0.2em] border-2 border-dashed border-slate-100 rounded-3xl">
                        Katalog sedang diperbarui.
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-14 text-slate-400 text-[11px] font-semibold uppercase tracking-wider">✅ 100% unit terverifikasi oleh tim KosKora</div>
        </div>
    </section>

    <!-- ========== LAYANAN INTEGRASI with modern rounded cards ========== -->
    <section id="layanan" class="py-16 px-6 bg-slate-50/60 scroll-mt-24">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 mt-2">Nikmati kemudahan <span class="text-brand-navy">tanpa pindah aplikasi</span></h2>
                <p class="text-slate-500 max-w-2xl mx-auto mt-4">Laundry profesional & tenaga kebersihan terlatih — semua terintegrasi dengan akun KosKora anda.</p>
            </div>
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Laundry Partners -->
                <div class="bg-white rounded-2xl p-8 brand-shadow-soft border border-slate-100 flex flex-col service-card">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-brand-navy/10 flex items-center justify-center"><svg class="w-6 h-6 text-brand-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                        <h3 class="text-2xl font-extrabold text-slate-900">Laundry Partner</h3>
                    </div>
                    <div class="space-y-4 flex-1">
                        @forelse($laundries as $laundry)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-white transition border border-transparent hover:border-slate-200 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-xl overflow-hidden bg-slate-200">
                                        @if($laundry->image)
                                            <img src="{{ asset('storage/' . $laundry->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-brand-navy/20 font-bold text-xs">L</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 text-sm">{{ $laundry->name }}</p>
                                        <p class="text-[10px] text-slate-400 uppercase tracking-wide truncate max-w-[150px]">{{ $laundry->address }}</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-bold text-brand-navy">{{ $laundry->phone }}</span>
                            </div>
                        @empty
                            <p class="text-slate-400 font-medium text-sm">Belum ada partner terdaftar.</p>
                        @endforelse
                    </div>
                    @auth
                        <a href="{{ route('user.laundry.index') }}" class="mt-8 w-full py-3.5 text-[11px] font-bold uppercase tracking-wider border-2 border-brand-navy/20 rounded-xl text-brand-navy hover:bg-brand-navy hover:text-white transition text-center">Pesan Laundry via KosKora →</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-8 w-full py-3.5 text-[11px] font-bold uppercase tracking-wider border-2 border-brand-navy/20 rounded-xl text-brand-navy hover:bg-brand-navy hover:text-white transition text-center">Masuk untuk Pesan Laundry →</a>
                    @endauth
                </div>
                <!-- Cleaner Network -->
                <div class="bg-white rounded-2xl p-8 brand-shadow-soft border border-slate-100 flex flex-col service-card">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-brand-red/10 flex items-center justify-center"><svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A9 9 0 0112 3a9 9 0 017.879 14.804M15 10h.01M9 10h.01M12 21v-6"/></svg></div>
                        <h3 class="text-2xl font-extrabold text-slate-900">Cleaner Professional</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4 flex-1">
                        @forelse($cleaners as $cleaner)
                            <div class="bg-slate-50 rounded-xl p-4 text-center border border-transparent hover:border-brand-red transition active:scale-95 cursor-pointer">
                                <div class="w-14 h-14 mx-auto bg-slate-200 rounded-full mb-3 overflow-hidden border-2 border-white shadow-sm">
                                    @if($cleaner->photo)
                                        <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-brand-red/20 font-bold">C</div>
                                    @endif
                                </div>
                                <p class="font-bold text-sm text-brand-navy">{{ $cleaner->user->name }}</p>
                                <span class="text-[10px] font-bold bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full uppercase">Verified</span>
                            </div>
                        @empty
                            <p class="col-span-2 text-slate-400 font-medium text-sm text-center py-8">Cleaner belum tersedia.</p>
                        @endforelse
                    </div>
                    @auth
                        <a href="{{ route('user.cleaning.index') }}" class="mt-8 w-full py-3.5 text-[11px] font-bold uppercase tracking-wider border-2 border-brand-red/20 rounded-xl text-brand-red hover:bg-brand-red hover:text-white transition text-center">Booking Cleaner →</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-8 w-full py-3.5 text-[11px] font-bold uppercase tracking-wider border-2 border-brand-red/20 rounded-xl text-brand-red hover:bg-brand-red hover:text-white transition text-center">Masuk untuk Booking →</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
    
    <!-- ========== TENTANG with rounded elements ========== -->
    <section id="tentang" class="py-16 px-6 scroll-mt-24 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-20">
            <div class="flex-1">
                <span class="text-[11px] font-black text-brand-red uppercase tracking-[0.3em]">KosKora way</span>
                <h2 class="text-4xl md:text-5xl font-extrabold text-brand-navy mt-3 leading-tight">Bukan sekadar kos, <br>tapi <span class="text-brand-red">gaya hidup premium.</span></h2>
                <p class="text-slate-500 mt-8 leading-relaxed font-medium">Kami menyatukan teknologi, kebersihan standar hotel, dan akses layanan instan. Setiap properti telah melalui kurasi desain, keamanan, dan konektivitas terbaik untuk profesional muda & ekspat.</p>
                <div class="mt-10 flex flex-wrap gap-8">
                    <div><span class="font-extrabold text-3xl text-brand-navy">24/7</span><span class="block text-[10px] font-semibold uppercase text-slate-400 mt-1">SLA Support</span></div>
                    <div><span class="font-extrabold text-3xl text-brand-navy">+45</span><span class="block text-[10px] font-semibold uppercase text-slate-400 mt-1">Smart Units</span></div>
                    <div><span class="font-extrabold text-3xl text-brand-navy">4.9★</span><span class="block text-[10px] font-semibold uppercase text-slate-400 mt-1">User Rating</span></div>
                </div>
                <button class="mt-12 px-10 py-4 bg-brand-navy text-white rounded-xl text-[12px] font-bold shadow-lg hover:bg-brand-red transition-all uppercase tracking-widest">Gabung Jadi Mitra</button>
            </div>
            <div class="flex-1 relative">
                <div class="bg-slate-50 rounded-2xl p-6 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-brand-navy/5 transform -skew-x-12 translate-x-32 group-hover:translate-x-40 transition-transform duration-1000 rounded-2xl"></div>
                    <img src="https://placehold.co/800x600/1e1b9b/ffffff?text=KOSKORA+ECOSYSTEM" alt="ecosystem" class="w-full rounded-xl shadow-2xl relative z-10 border border-white">
                </div>
            </div>
        </div>
    </section>
    
    <!-- ========== MODAL SECTION (PROPERTY DETAIL) ========== -->
    <div id="propertyModal" class="fixed inset-0 z-[200] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-end sm:items-center justify-center min-h-screen px-0 sm:px-4 py-0 sm:py-12 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-md transition-opacity" onclick="closePropertyModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl w-full relative border border-slate-100 max-h-[95vh] sm:max-h-none overflow-y-auto">
                <button onclick="closePropertyModal()" class="absolute top-4 right-4 sm:top-6 sm:right-6 z-30 p-2.5 bg-white shadow-xl rounded-2xl text-brand-navy hover:bg-brand-red hover:text-white transition-all">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-5">
                    <!-- Right Side: Units & Reviews -->
                    <div class="lg:col-span-3 p-6 sm:p-12 order-2 lg:order-1 overflow-y-auto">
                        <div class="flex justify-between items-start mb-8">
                            <div>
                                <span id="propGender" class="px-3 py-1 bg-brand-navy/10 text-brand-navy text-[9px] font-black uppercase tracking-widest rounded-full mb-3 inline-block"></span>
                                <h3 id="propTitle" class="text-3xl sm:text-5xl font-extrabold text-slate-900 tracking-tight leading-none mb-4"></h3>
                                <div id="propLocation" class="flex items-center text-[10px] font-black text-slate-400 uppercase tracking-widest flex-wrap gap-2">
                                    <i class="fas fa-location-dot text-brand-red"></i>
                                    <span></span>
                                    <div class="flex items-center gap-1.5 ml-4 px-2 py-1 bg-amber-50 text-amber-600 rounded-lg">
                                        <i class="fas fa-star text-[10px]"></i>
                                        <span id="propRatingScore" class="font-bold"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Available Units -->
                        <div class="mb-12">
                            <div class="flex items-center justify-between mb-6">
                                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">Pilihan Unit Tersedia</h4>
                                <span class="h-[1px] flex-1 bg-slate-50 mx-4"></span>
                            </div>
                            <div id="propRooms" class="space-y-4">
                                <!-- Dynamic room rows -->
                            </div>
                        </div>

                        <!-- Property Reviews -->
                        <div>
                            <div class="flex items-center justify-between mb-8">
                                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">Ulasan Penghuni</h4>
                                <span class="h-[1px] flex-1 bg-slate-50 mx-4"></span>
                            </div>
                            <div id="propReviews" class="space-y-6">
                                <!-- Dynamic reviews -->
                            </div>
                        </div>
                    </div>

                    <!-- Left Side: Image & Stats (Sticky-ish) -->
                    <div class="lg:col-span-2 bg-slate-50 p-6 lg:order-2 flex flex-col h-full lg:sticky lg:top-0">
                        <div class="flex-1 rounded-3xl overflow-hidden shadow-2xl relative group mb-6">
                            <img id="propMainImg" src="" class="w-full h-full object-cover transition duration-1000 group-hover:scale-105">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                        </div>
                        
                        <!-- Facilities Highlight -->
                        <div class="bg-white rounded-3xl p-6 shadow-sm border border-slate-100">
                             <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 mb-6">Fasilitas Kos</h4>
                             <div id="propAssets" class="grid grid-cols-2 gap-y-4 gap-x-2">
                                 <!-- Dynamic assets -->
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ========== FOOTER ========== -->
    <footer class="border-t border-slate-100 py-12 px-6 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-10">
            <div class="flex flex-col items-center md:items-start gap-4">
                <img src="{{ asset('koskora.png') }}" class="h-8 w-auto" alt="Logo">
                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">© 2025 KosKora. All rights reserved.</p>
            </div>
            <div class="flex flex-wrap justify-center gap-10 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                <a href="#" class="hover:text-brand-navy transition">Privasi</a>
                <a href="#" class="hover:text-brand-navy transition">TOS</a>
                <a href="#" class="hover:text-brand-navy transition">Support</a>
                <a href="#" class="hover:text-brand-navy border-b-2 border-brand-red">IG</a>
            </div>
        </div>
        <div class="text-center text-[9px] text-slate-300 mt-12 uppercase tracking-[0.3em]">Premium Living, Simplified.</div>
    </footer>

    <script>
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const modal = document.getElementById('propertyModal');
        const mainImg = document.getElementById('propMainImg');
        const title = document.getElementById('propTitle');
        const gender = document.getElementById('propGender');
        const loc = document.getElementById('propLocation').querySelector('span');
        const ratingScore = document.getElementById('propRatingScore');
        const roomsContainer = document.getElementById('propRooms');
        const reviewsContainer = document.getElementById('propReviews');
        const assetsContainer = document.getElementById('propAssets');

        function openPropertyModal(prop) {
            title.innerText = prop.name;
            gender.innerText = 'KHUSUS ' + prop.gender.toUpperCase();
            loc.innerText = prop.location.toUpperCase();
            ratingScore.innerText = Number(prop.avg_rating).toFixed(1) + ' (' + prop.total_reviews + ' ulasan)';
            mainImg.src = prop.thumbnail ? '/storage/' + prop.thumbnail : 'https://placehold.co/800x600?text=Premium+Living';

            // Distinct Assets (Facilities)
            assetsContainer.innerHTML = '';
            let allAssets = [];
            prop.rooms.forEach(r => {
                if(r.assets) allAssets = [...allAssets, ...r.assets];
            });
            // Unique by name
            const uniqueAssets = Array.from(new Map(allAssets.map(a => [a.name, a])).values()).slice(0, 8);
            
            uniqueAssets.forEach(asset => {
                const item = document.createElement('div');
                item.className = 'flex items-center gap-2.5 group';
                item.innerHTML = `
                    <div class="w-6 h-6 rounded-lg bg-slate-50 flex items-center justify-center text-brand-navy border border-slate-100 group-hover:bg-brand-navy group-hover:text-white transition-colors">
                        <i class="${asset.icon || 'fas fa-check'} text-[10px]"></i>
                    </div>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wide group-hover:text-slate-900 transition-colors">${asset.name}</span>
                `;
                assetsContainer.appendChild(item);
            });

            // Units List
            roomsContainer.innerHTML = '';
            prop.rooms.forEach(room => {
                const row = document.createElement('div');
                row.className = "group/row p-6 bg-slate-50/50 rounded-2xl border border-slate-100 flex flex-col md:flex-row justify-between items-center gap-6 hover:border-brand-navy/30 hover:bg-white hover:shadow-xl transition-all duration-300";
                
                let assetsHtml = '';
                if(room.assets) {
                    room.assets.slice(0, 3).forEach(a => {
                        assetsHtml += `<span class="px-2 py-0.5 bg-white border border-slate-100 rounded-md text-[8px] font-bold text-slate-400 uppercase tracking-tighter mr-1 shadow-sm">${a.name}</span>`;
                    });
                    if(room.assets.length > 3) assetsHtml += `<span class="text-[8px] font-black text-slate-300 uppercase">+${room.assets.length - 3}</span>`;
                }

                row.innerHTML = `
                    <div class="flex items-center gap-5">
                        <div class="w-16 h-16 rounded-xl overflow-hidden border border-white shadow-md flex-shrink-0">
                            <img src="${room.picture ? '/storage/'+room.picture[0] : 'https://placehold.co/100x100?text=Unit'}" class="w-full h-full object-cover">
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <h5 class="text-base font-black text-slate-900 uppercase">Room #${room.room_number}</h5>
                                <span class="px-2 py-0.5 bg-brand-navy text-white text-[8px] font-black uppercase rounded shadow-sm">${room.room_type}</span>
                                <span class="text-[9px] font-bold ${room.status == 'available' ? 'text-emerald-500' : 'text-rose-500'} uppercase underline-offset-4 decoration-2">${room.status == 'available' ? 'Tersedia' : 'Penuh'}</span>
                            </div>
                            <div class="flex items-center opacity-70 group-hover/row:opacity-100 transition-opacity">
                                ${assetsHtml}
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center gap-8">
                        <div class="text-right">
                            <p class="text-sm font-black text-brand-navy">Rp ${new Intl.NumberFormat('id-ID').format(room.price)}<span class="text-[10px] text-slate-400 font-medium">/bln</span></p>
                        </div>
                        ${room.status == 'available' ? `
                            <form action="/bookings/${room.id}/rent" method="POST">
                                <input type="hidden" name="_token" value="${csrfToken}">
                                <button type="submit" class="px-8 py-3 bg-brand-navy text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-red transition shadow-lg active:scale-95">SEWA UNIT</button>
                            </form>
                        ` : `
                            <button disabled class="px-8 py-3 bg-slate-200 text-slate-400 text-[9px] font-black uppercase tracking-widest rounded-xl cursor-not-allowed">FULL UNIT</button>
                        `}
                    </div>
                `;
                roomsContainer.appendChild(row);
            });

            // Reviews List
            reviewsContainer.innerHTML = '';
            let allPropReviews = [];
            prop.rooms.forEach(r => {
                if(r.reviews) allPropReviews = [...allPropReviews, ...r.reviews];
            });

            if(allPropReviews.length > 0) {
                allPropReviews.slice(0, 5).forEach(rev => {
                    const revDiv = document.createElement('div');
                    revDiv.className = "p-6 bg-slate-50/20 border border-slate-100 rounded-3xl group/rev hover:border-brand-navy/20 transition-all";
                    const initial = rev.is_anonymous ? 'A' : (rev.user ? rev.user.name.charAt(0) : 'U');
                    const reviewer = rev.is_anonymous ? 'Penghuni Anonim' : (rev.user ? rev.user.name : 'Penghuni');
                    
                    revDiv.innerHTML = `
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-xl bg-brand-navy text-white flex items-center justify-center font-black text-xs shadow-md">
                                ${initial}
                            </div>
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1">
                                    <span class="text-xs font-black text-slate-900 uppercase tracking-tight">${reviewer}</span>
                                    <div class="flex text-[8px] text-amber-500">
                                        ${Array(rev.rating).fill('<i class="fas fa-star"></i>').join('')}
                                    </div>
                                </div>
                                <p class="text-[11px] font-medium text-slate-500 italic leading-relaxed">"${rev.comment}"</p>
                                <p class="text-[8px] font-black text-slate-300 uppercase tracking-widest mt-2">${new Date(rev.created_at).toLocaleDateString()}</p>
                            </div>
                        </div>
                    `;
                    reviewsContainer.appendChild(revDiv);
                });
            } else {
                reviewsContainer.innerHTML = '<div class="py-12 bg-slate-50/50 rounded-3xl border-2 border-dashed border-slate-100 text-center"><p class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300 lowercase">Belum ada ulasan untuk kos ini</p></div>';
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closePropertyModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>
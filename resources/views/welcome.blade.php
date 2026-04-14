<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>KosKora | Hunian Premium, Terintegrasi & Modern</title>
    <!-- Google Fonts: System & Modern Weights -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700;14..32,800&family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Tailwind CSS + Custom Config -->
    <script src="https://cdn.tailwindcss.com"></script>
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
                        manrope: ['Manrope', 'sans-serif'],
                        inter: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        /* custom overrides & key brand behavior */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif; scroll-behavior: smooth; background: #ffffff; }

        /* Brand-centric utilities */
        .brand-gradient-bg { background: linear-gradient(135deg, #1e1b9b 0%, #11106e 100%); }
        .brand-shadow { box-shadow: 0 20px 35px -12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.02); }
        .brand-card-hover { transition: all 0.35s cubic-bezier(0.2, 0, 0, 1); }
        .brand-card-hover:hover { transform: translateY(-6px); box-shadow: 0 30px 50px -20px rgba(30, 27, 155, 0.25); border-color: rgba(30, 27, 155, 0.3); }
        .input-modern { transition: all 0.2s ease; background-color: #f8fafc; border: 1px solid #eef2f6; }
        .input-modern:focus { background-color: #ffffff; border-color: #1e1b9b; outline: none; box-shadow: 0 0 0 3px rgba(30,27,155,0.1); }
        .btn-primary { transition: all 0.25s ease; background: #1e1b9b; }
        .btn-primary:hover { background: #11106e; transform: scale(0.98); box-shadow: 0 10px 20px -8px rgba(30,27,155,0.4); }
        .btn-accent { transition: all 0.25s ease; background: #d42e2e; }
        .btn-accent:hover { background: #b11f1f; transform: scale(0.98); }
        .glass-nav { background: rgba(255,255,255,0.96); backdrop-filter: blur(8px); border-bottom: 1px solid rgba(0,0,0,0.03); }
        .section-title-highlight { position: relative; display: inline-block; }
        .section-title-highlight:after { content: ''; position: absolute; bottom: -8px; left: 0; width: 48px; height: 3px; background: #d42e2e; border-radius: 4px; }
        .badge-status { backdrop-filter: blur(4px); background: rgba(0,0,0,0.65); }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        img { display: block; max-width: 100%; }
        
        @keyframes pulse-slow {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.02); opacity: 0.95; }
        }
        .animate-pulse-slow { animation: pulse-slow 4s infinite ease-in-out; }
    </style>
</head>
<body class="antialiased text-slate-800 overflow-x-hidden">

    <!-- ========== NAVIGATION ========== -->
    <nav class="glass-nav fixed top-0 w-full z-50 py-5 px-6 md:px-10 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <!-- Logo + Branding -->
            <div class="flex items-center gap-3">
                <a href="/">
                    <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-9 w-auto md:h-10">
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
                        <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-brand-navy text-white text-[11px] font-bold rounded-full shadow-md hover:bg-brand-red transition-all duration-200 uppercase tracking-wide">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="hidden sm:inline-block text-[11px] font-bold text-slate-500 hover:text-brand-navy transition uppercase tracking-wider">Masuk</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-6 py-2.5 bg-brand-navy text-white text-[11px] font-bold rounded-full shadow-md hover:bg-brand-red transition-all duration-200 uppercase tracking-wide font-manrope">Join Pro</a>
                        @endif
                    @endauth
                @endif
            </div>
        </div>
    </nav>

    <!-- ========== HERO SECTION ========== -->
    <section class="relative pt-32 pb-24 px-6 overflow-hidden">
        <!-- Background Image with Overlay & Blur -->
        <div class="absolute inset-0 z-0">
            <img src="{{ asset('hero-bg.png') }}" class="w-full h-full object-cover opacity-90 scale-105 transform">
            <!-- Subtle backdrop blur to soften architectural lines clashing with text -->
            <div class="absolute inset-0 bg-white/40 backdrop-blur-[2px]"></div>
            <!-- Dynamic gradient to keep the center clean and readable -->
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
            <h1 class="font-manrope text-5xl md:text-6xl lg:text-7xl font-extrabold tracking-tight leading-[1.05] text-brand-navy max-w-4xl mx-auto drop-shadow-[0_10px_10px_rgba(255,255,255,0.8)]">
                Hunian Berkelas.<br>
                <span class="text-brand-red bg-clip-text">Pengelolaan Tanpa Ribet.</span>
            </h1>
            <p class="text-slate-700 text-sm md:text-lg max-w-2xl mx-auto mt-8 font-semibold leading-relaxed drop-shadow-sm">
                Manajemen kos profesional & teknologi cerdas. <span class="text-brand-navy/60 font-medium">Nikmati kemudahan cari kamar, laundry, dan cleaner terverifikasi dalam satu genggaman.</span>
            </p>
            
            <!-- Search Console -->
            <div class="max-w-4xl mx-auto mt-8 bg-white/90 backdrop-blur-sm rounded-2xl brand-shadow p-2.5 border border-slate-100">
                <form action="{{ route('home') }}" method="GET" class="flex flex-col md:flex-row gap-2">
                    <div class="flex-[2] relative">
                        <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari area, tipe kamar, atau fasilitas..." class="w-full pl-12 pr-4 py-4 rounded-xl input-modern font-medium text-sm">
                    </div>
                    <div class="flex-1">
                        <select name="city" class="w-full px-5 py-4 rounded-xl input-modern font-medium text-sm cursor-pointer appearance-none">
                            <option value="">Semua Kota</option>
                            @foreach($cities as $city)
                                <option value="{{ $city }}" {{ request('city') == $city ? 'selected' : '' }}>{{ $city }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" class="md:w-auto px-10 py-4 bg-brand-navy text-white rounded-xl font-bold text-sm uppercase tracking-wider hover:bg-brand-red transition-all shadow-md">Jelajahi</button>
                </form>
            </div>
            
            <!-- quick stats -->
            <div class="flex flex-wrap justify-center gap-8 mt-14">
                <div><span class="font-black text-2xl text-brand-navy">150+</span><span class="text-xs font-semibold text-slate-400 block uppercase">Unit Premium</span></div>
                <div><span class="font-black text-2xl text-brand-navy">12</span><span class="text-xs font-semibold text-slate-400 block uppercase">Kota Besar</span></div>
                <div><span class="font-black text-2xl text-brand-navy">99%</span><span class="text-xs font-semibold text-slate-400 block uppercase">Kepuasan Tenant</span></div>
            </div>
        </div>
    </section>

    <!-- ========== CATALOG SECTION (ROOMS) ========== -->
    <section id="katalog" class="py-16 px-6 scroll-mt-24 bg-white">
        <div class="max-w-7xl mx-auto">
            <div class="flex flex-wrap justify-between items-end gap-6 mb-12">
                <div>
                    <span class="text-[11px] font-black text-brand-red uppercase tracking-[0.3em]">Koleksi eksklusif</span>
                    <h2 class="font-manrope text-4xl md:text-5xl font-bold text-[#0F0C2F] mt-3">Rekomendasi Kamar<span class="text-slate-300">.</span></h2>
                    <p class="text-slate-400 text-sm max-w-lg mt-2">Unit terbaik dengan standar kebersihan & keamanan tinggi.</p>
                </div>
                <a href="#katalog" class="text-[12px] font-bold uppercase tracking-wider text-brand-navy border-b border-brand-navy pb-1 hover:text-brand-red transition">Lihat semua →</a>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($rooms as $room)
                    <div class="group bg-white rounded-2xl border border-slate-100 overflow-hidden brand-card-hover transition-all flex flex-col">
                        <div class="relative h-64 bg-slate-50 overflow-hidden">
                            @if($room->picture && count($room->picture) > 0)
                                <img src="{{ asset('storage/' . $room->picture[0]) }}" class="w-full h-full object-cover transition duration-700 group-hover:scale-110">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-100">
                                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-4 left-4 flex gap-2">
                                <span class="bg-brand-navy text-white text-[10px] font-black px-3 py-1.5 rounded-full shadow-md uppercase">{{ $room->room_type }}</span>
                                <span class="px-3 py-1.5 {{ $room->status == 'available' ? 'bg-emerald-500' : 'bg-brand-red' }} text-white text-[10px] font-black rounded-full shadow-md uppercase">{{ $room->status == 'available' ? 'Ready' : 'Full' }}</span>
                            </div>
                        </div>
                        <div class="p-6 flex-1 flex flex-col">
                            <div class="flex justify-between items-start">
                                <h3 class="font-manrope text-2xl font-bold text-[#0F0C2F]">#{{ $room->room_number }}</h3>
                                <span class="text-xl font-black text-brand-navy">Rp {{ number_format($room->price / 1000, 1, '.', '') }}jt<span class="text-sm text-slate-400 font-normal">/bln</span></span>
                            </div>
                            <div class="flex items-center gap-1 text-slate-400 text-xs mt-2 mb-4">
                                <svg class="w-4 h-4 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                                <span class="text-[11px] font-medium">{{ $room->district }}, {{ $room->city }}</span>
                            </div>
                            <p class="text-slate-500 text-sm leading-relaxed mb-6 line-clamp-2">
                                {{ $room->description ?: 'Premium dwelling experience with curated maintenance services and professional property management.' }}
                            </p>
                            <div class="flex gap-3 pt-5 border-t border-slate-50 mt-auto">
                                <button onclick="openRoomModal({{ json_encode($room) }})" class="flex-1 py-3 text-[11px] font-bold uppercase tracking-wider border border-slate-200 rounded-xl text-brand-navy hover:border-brand-navy transition">Detail</button>
                                @if($room->status == 'available')
                                    <form action="{{ route('bookings.rent', $room->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full py-3 bg-brand-red text-white text-[11px] font-bold uppercase tracking-wider rounded-xl hover:bg-brand-navy transition shadow-sm">Sewa Sekarang</button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full py-24 text-center text-slate-200 font-black italic uppercase tracking-[0.3em] border-2 border-dashed border-slate-50 rounded-2xl">
                        Katalog sedang diperbarui.
                    </div>
                @endforelse
            </div>
            <div class="text-center mt-14 text-slate-400 text-[11px] font-semibold uppercase tracking-wider">✅ 100% unit terverifikasi oleh tim KosKora</div>
        </div>
    </section>

    <!-- ========== LAYANAN INTEGRASI ========== -->
    <section id="layanan" class="py-16 px-6 bg-slate-50/60 scroll-mt-24">
        <div class="max-w-7xl mx-auto">
            <div class="text-center mb-16">
                <span class="text-[11px] font-black text-brand-red uppercase tracking-[0.3em]">Ekosistem Pintar</span>
                <h2 class="font-manrope text-4xl md:text-5xl font-bold text-[#0F0C2F] mt-2">Nikmati kemudahan <span class="text-brand-navy">tanpa pindah aplikasi</span></h2>
                <p class="text-slate-500 max-w-2xl mx-auto mt-4">Laundry profesional & tenaga kebersihan terlatih — semua terintegrasi dengan akun KosKora anda.</p>
            </div>
            <div class="grid lg:grid-cols-2 gap-12">
                <!-- Laundry Partners -->
                <div class="bg-white rounded-2xl p-8 brand-shadow border border-slate-100 flex flex-col">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-brand-navy/10 flex items-center justify-center"><svg class="w-6 h-6 text-brand-navy" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 12h.01M12 12h.01M16 12h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                        <h3 class="font-manrope text-2xl font-bold text-[#0F0C2F]">Laundry Partner</h3>
                    </div>
                    <div class="space-y-4 flex-1">
                        @forelse($laundries as $laundry)
                            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 hover:bg-white transition border border-transparent hover:border-slate-200 group">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-lg overflow-hidden bg-slate-200">
                                        @if($laundry->image)
                                            <img src="{{ asset('storage/' . $laundry->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-brand-navy/20 font-black text-xs">L</div>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="font-bold text-[#0F0C2F] text-sm">{{ $laundry->name }}</p>
                                        <p class="text-[9px] text-slate-400 uppercase tracking-wide truncate max-w-[150px]">{{ $laundry->address }}</p>
                                    </div>
                                </div>
                                <span class="text-[11px] font-black text-brand-navy">{{ $laundry->phone }}</span>
                            </div>
                        @empty
                            <p class="text-slate-300 font-bold italic text-sm">Belum ada partner terdaftar.</p>
                        @endforelse
                    </div>
                    @auth
                        <a href="{{ route('user.laundry.index') }}" class="mt-8 w-full py-3.5 text-[11px] font-bold uppercase tracking-wider border-2 border-brand-navy/10 rounded-xl text-brand-navy hover:bg-brand-navy hover:text-white transition text-center">Pesan Laundry via KosKora →</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-8 w-full py-3.5 text-[11px] font-bold uppercase tracking-wider border-2 border-brand-navy/10 rounded-xl text-brand-navy hover:bg-brand-navy hover:text-white transition text-center">Masuk untuk Pesan Laundry →</a>
                    @endauth
                </div>
                <!-- Cleaner Network -->
                <div class="bg-white rounded-2xl p-8 brand-shadow border border-slate-100 flex flex-col">
                    <div class="flex items-center gap-4 mb-8">
                        <div class="w-12 h-12 rounded-xl bg-brand-red/10 flex items-center justify-center"><svg class="w-6 h-6 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5.121 17.804A9 9 0 0112 3a9 9 0 017.879 14.804M15 10h.01M9 10h.01M12 21v-6"/></svg></div>
                        <h3 class="font-manrope text-2xl font-bold text-[#0F0C2F]">Cleaner Professional</h3>
                    </div>
                    <div class="grid grid-cols-2 gap-4 flex-1">
                        @forelse($cleaners as $cleaner)
                            <div class="bg-slate-50 rounded-xl p-4 text-center border border-transparent hover:border-brand-red transition active:scale-95 cursor-pointer">
                                <div class="w-14 h-14 mx-auto bg-slate-200 rounded-full mb-3 overflow-hidden border-2 border-white shadow-sm">
                                    @if($cleaner->photo)
                                        <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-full h-full object-cover">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center text-brand-red/20 font-black">C</div>
                                    @endif
                                </div>
                                <p class="font-bold text-sm text-brand-navy">{{ $cleaner->user->name }}</p>
                                <span class="text-[8px] font-black bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-full uppercase tracking-tighter">Pro Verified</span>
                            </div>
                        @empty
                            <p class="col-span-2 text-slate-300 font-bold italic text-sm text-center py-8">Cleaner belum tersedia.</p>
                        @endforelse
                    </div>
                    @auth
                        <a href="{{ route('user.cleaning.index') }}" class="mt-8 w-full py-3.5 text-[11px] font-bold uppercase tracking-wider border-2 border-brand-red/10 rounded-xl text-brand-red hover:bg-brand-red hover:text-white transition text-center">Booking Cleaner →</a>
                    @else
                        <a href="{{ route('login') }}" class="mt-8 w-full py-3.5 text-[11px] font-bold uppercase tracking-wider border-2 border-brand-red/10 rounded-xl text-brand-red hover:bg-brand-red hover:text-white transition text-center">Masuk untuk Booking →</a>
                    @endauth
                </div>
            </div>
        </div>
    </section>
    
    <!-- ========== TENTANG ========== -->
    <section id="tentang" class="py-16 px-6 scroll-mt-24 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col lg:flex-row items-center gap-20">
            <div class="flex-1">
                <span class="text-[11px] font-black text-brand-red uppercase tracking-[0.3em]">KosKora way</span>
                <h2 class="font-manrope text-4xl md:text-5xl font-bold text-brand-navy mt-3 leading-tight">Bukan sekadar kos, <br>tapi <span class="text-brand-red">gaya hidup premium.</span></h2>
                <p class="text-slate-500 mt-8 leading-relaxed font-medium">Kami menyatukan teknologi, kebersihan standar hotel, dan akses layanan instan. Setiap properti telah melalui kurasi desain, keamanan, dan konektivitas terbaik untuk profesional muda & ekspat.</p>
                <div class="mt-10 flex flex-wrap gap-8">
                    <div><span class="font-black text-3xl text-brand-navy">24/7</span><span class="block text-[10px] font-bold uppercase text-slate-400 mt-1">SLA Support</span></div>
                    <div><span class="font-black text-3xl text-brand-navy">+45</span><span class="block text-[10px] font-bold uppercase text-slate-400 mt-1">Smart Units</span></div>
                    <div><span class="font-black text-3xl text-brand-navy">4.9★</span><span class="block text-[10px] font-bold uppercase text-slate-400 mt-1">User Rating</span></div>
                </div>
                <button class="mt-12 px-10 py-4 bg-brand-navy text-white rounded-full text-[12px] font-bold shadow-lg hover:bg-brand-red transition-all uppercase tracking-widest">Gabung Jadi Mitra</button>
            </div>
            <div class="flex-1 relative">
                <div class="bg-slate-50 rounded-3xl p-6 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-brand-navy/5 transform -skew-x-12 translate-x-32 group-hover:translate-x-40 transition-transform duration-1000"></div>
                    <img src="https://placehold.co/800x600/1e1b9b/ffffff?text=KOSKORA+ECOSYSTEM" alt="ecosystem" class="w-full rounded-2xl shadow-2xl relative z-10 border border-white">
                </div>
            </div>
        </div>
    </section>
    
    <!-- ========== MODAL SECTION (RESTYLED) ========== -->
    <div id="roomModal" class="fixed inset-0 z-[200] hidden overflow-y-auto" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 py-12 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" onclick="closeRoomModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full relative border border-slate-100">
                <button onclick="closeRoomModal()" class="absolute top-6 right-6 z-30 p-2.5 bg-slate-50 rounded-full text-brand-navy hover:bg-brand-red hover:text-white transition-all shadow-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="grid grid-cols-1 lg:grid-cols-5">
                    <!-- Gallery Area -->
                    <div class="lg:col-span-2 bg-slate-50 p-6 space-y-4">
                        <div class="aspect-square rounded-2xl overflow-hidden bg-white border border-slate-100 shadow-sm">
                            <img id="modalMainImg" src="" class="w-full h-full object-cover">
                        </div>
                        <div id="modalThumbnails" class="grid grid-cols-4 gap-2"></div>
                    </div>

                    <!-- Info Area -->
                    <div class="lg:col-span-3 p-10 flex flex-col justify-between">
                        <div class="space-y-8">
                            <div>
                                <span id="modalType" class="px-4 py-1.5 bg-brand-navy/10 text-brand-navy text-[9px] font-black uppercase tracking-widest rounded-full mb-4 inline-block"></span>
                                <h3 id="modalTitle" class="font-manrope text-4xl font-extrabold text-[#0F0C2F] tracking-tight"></h3>
                            </div>

                            <div class="text-4xl font-black text-brand-navy" id="modalPrice"></div>

                            <div class="space-y-3">
                                <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-300">Fasilitas & Detail</h4>
                                <p id="modalDesc" class="text-slate-500 font-medium leading-relaxed italic text-sm"></p>
                            </div>

                            <div id="modalAddress" class="pt-6 flex items-center text-[10px] font-black text-slate-400 uppercase tracking-widest border-t border-slate-50">
                                <svg class="w-4 h-4 mr-2 text-brand-red" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span></span>
                            </div>
                        </div>

                        <div class="pt-12">
                            <form id="modalForm" action="" method="POST">
                                @csrf
                                <button type="submit" id="modalSubmitBtn" class="w-full py-5 bg-brand-navy text-white rounded-2xl font-bold text-sm uppercase tracking-[0.2em] hover:bg-brand-red transition-all shadow-xl shadow-blue-50">MULAI SEWA UNIT</button>
                            </form>
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
        const modal = document.getElementById('roomModal');
        const mainImg = document.getElementById('modalMainImg');
        const thumbsContainer = document.getElementById('modalThumbnails');
        const title = document.getElementById('modalTitle');
        const type = document.getElementById('modalType');
        const price = document.getElementById('modalPrice');
        const desc = document.getElementById('modalDesc');
        const address = document.getElementById('modalAddress').querySelector('span');
        const form = document.getElementById('modalForm');
        const submitBtn = document.getElementById('modalSubmitBtn');

        function openRoomModal(room) {
            title.innerText = 'ROOM #' + room.room_number;
            type.innerText = room.room_type.toUpperCase();
            price.innerHTML = `Rp ${new Intl.NumberFormat('id-ID').format(room.price)} <span class="text-lg font-bold text-slate-200">/bln</span>`;
            desc.innerText = room.description || 'Premium architectural experience with curated maintenance services and professional property management.';
            address.innerText = `${room.district}, ${room.city}`.toUpperCase();
            
            if (room.status === 'available') {
                form.action = `/rooms/${room.id}/rent`;
                submitBtn.classList.remove('hidden');
            } else {
                submitBtn.classList.add('hidden');
            }

            thumbsContainer.innerHTML = '';
            const pics = room.picture || [];
            if (pics.length > 0) {
                mainImg.src = '/storage/' + pics[0];
                pics.forEach((p, idx) => {
                    const thumb = document.createElement('div');
                    thumb.className = `aspect-square rounded-xl overflow-hidden border-2 cursor-pointer transition-all ${idx === 0 ? 'border-brand-navy' : 'border-transparent'}`;
                    thumb.innerHTML = `<img src="/storage/${p}" class="w-full h-full object-cover">`;
                    thumb.onclick = () => {
                        mainImg.src = '/storage/' + p;
                        document.querySelectorAll('#modalThumbnails div').forEach(d => d.classList.replace('border-brand-navy', 'border-transparent'));
                        thumb.classList.replace('border-transparent', 'border-brand-navy');
                    };
                    thumbsContainer.appendChild(thumb);
                });
            } else {
                mainImg.src = 'https://placehold.co/600x600/1e1b9b/ffffff?text=KOSKORA+PREMIUM';
            }

            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeRoomModal() {
            modal.classList.add('hidden');
            document.body.style.overflow = 'auto';
        }
    </script>
</body>
</html>

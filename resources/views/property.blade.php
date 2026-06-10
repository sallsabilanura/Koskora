<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>{{ $property->name }} | KosKora</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Detail properti {{ $property->name }} - {{ $property->location }}. Temukan kamar kos premium di KosKora.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: { navy: '#1e1b9b', red: '#d42e2e' }
                    },
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                }
            }
        }
    </script>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; scroll-behavior: smooth; background: #f8fafc; -webkit-font-smoothing: antialiased; }
        .glass-nav { background: rgba(255,255,255,0.96); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.03); }
        img { display: block; max-width: 100%; }
        .brand-card-hover { transition: all 0.35s cubic-bezier(0.2, 0, 0, 0.2); }
        .brand-card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(30, 27, 155, 0.2); }
    </style>
</head>
<body class="antialiased text-slate-800">

    {{-- NAVIGATION --}}
    <nav class="glass-nav fixed top-0 w-full z-50 py-4 md:py-5 px-4 md:px-10 transition-all duration-300">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="/"><img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-8 w-auto md:h-10"></a>
                <span class="text-[10px] font-bold tracking-[0.2em] text-slate-400 hidden sm:block border-l border-slate-200 pl-3 uppercase">Premium Living</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('home') }}" class="px-5 py-2 bg-white text-slate-600 text-[11px] font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all uppercase tracking-wide flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-brand-navy text-white text-[11px] font-bold rounded-xl shadow-md hover:bg-brand-red transition-all duration-200 uppercase tracking-wide">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-brand-navy text-white text-[11px] font-bold rounded-xl shadow-md hover:bg-brand-red transition-all duration-200 uppercase tracking-wide">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- HERO BANNER --}}
    <section class="relative pt-20 md:pt-24">
        <div class="relative h-[320px] md:h-[420px] overflow-hidden">
            @if($property->thumbnail)
                <img src="{{ asset('storage/' . $property->thumbnail) }}" class="w-full h-full object-cover">
            @else
                <div class="w-full h-full bg-gradient-to-br from-brand-navy to-brand-navy/80"></div>
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-slate-900/30 to-transparent"></div>
            <div class="absolute bottom-0 left-0 right-0 p-6 md:p-12">
                <div class="max-w-7xl mx-auto">
                    <span class="px-3 py-1.5 bg-brand-navy text-white text-[10px] font-bold rounded-full shadow-md uppercase tracking-widest mb-3 inline-block">
                        Khusus {{ ucfirst($property->gender) }}
                    </span>
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tight leading-none mb-3 drop-shadow-lg">{{ $property->name }}</h1>
                    <div class="flex items-center gap-4 flex-wrap">
                        <div class="flex items-center gap-1.5 text-white/80">
                            <i class="fas fa-location-dot text-brand-red"></i>
                            <span class="text-[11px] font-bold uppercase tracking-wider">{{ $property->location }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 px-3 py-1 bg-white/20 backdrop-blur-md rounded-full">
                            <i class="fas fa-star text-amber-400 text-[10px]"></i>
                            <span class="text-[11px] font-bold text-white">{{ number_format($property->avg_rating, 1) }}</span>
                            <span class="text-[9px] text-white/60 font-medium">({{ $property->total_reviews }} ulasan)</span>
                        </div>
                        <div class="px-3 py-1 bg-white/20 backdrop-blur-md rounded-full">
                            <span class="text-[11px] font-bold text-white">{{ $property->rooms->count() }} Unit</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <section class="max-w-7xl mx-auto px-4 md:px-6 py-10 md:py-16">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">

            {{-- LEFT: Room Listings --}}
            <div class="lg:col-span-2 space-y-8">
                <div>
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400">Pilihan Unit Tersedia</h2>
                        <span class="h-[1px] flex-1 bg-slate-200 mx-4"></span>
                        <span class="text-[10px] font-bold text-slate-400">{{ $property->rooms->where('status', 'available')->count() }} tersedia</span>
                    </div>

                    <div class="space-y-4">
                        @foreach($property->rooms as $room)
                            <div class="group bg-white rounded-2xl border border-slate-100 p-5 md:p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-5 hover:border-brand-navy/20 hover:shadow-xl transition-all duration-300">
                                <div class="flex items-center gap-5 flex-1 min-w-0">
                                    {{-- Room Thumbnail --}}
                                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-xl overflow-hidden border border-slate-100 shadow-md flex-shrink-0">
                                        @if($room->picture && count($room->picture) > 0)
                                            <img src="{{ asset('storage/' . $room->picture[0]) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                                        @else
                                            <div class="w-full h-full bg-slate-50 flex items-center justify-center text-slate-200">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            </div>
                                        @endif
                                    </div>

                                    <div class="min-w-0">
                                        <div class="flex items-center gap-2 mb-2 flex-wrap">
                                            <h3 class="text-lg font-black text-slate-900 uppercase">Room #{{ $room->room_number }}</h3>
                                            <span class="px-2.5 py-0.5 bg-brand-navy text-white text-[8px] font-black uppercase rounded-md shadow-sm">{{ $room->room_type }}</span>
                                            @if($room->status == 'available')
                                                <span class="text-[9px] font-bold text-emerald-500 uppercase">Tersedia</span>
                                            @else
                                                <span class="text-[9px] font-bold text-rose-500 uppercase">Penuh</span>
                                            @endif
                                        </div>
                                        {{-- Assets preview --}}
                                        <div class="flex items-center gap-1 flex-wrap">
                                            @foreach($room->assets->take(4) as $asset)
                                                <span class="px-2 py-0.5 bg-slate-50 border border-slate-100 rounded-md text-[8px] font-bold text-slate-400 uppercase tracking-tighter">{{ $asset->name }}</span>
                                            @endforeach
                                            @if($room->assets->count() > 4)
                                                <span class="text-[8px] font-black text-slate-300">+{{ $room->assets->count() - 4 }}</span>
                                            @endif
                                        </div>
                                        {{-- Photo count --}}
                                        @if($room->picture && count($room->picture) > 1)
                                            <div class="flex items-center gap-1 mt-1.5">
                                                <i class="fas fa-images text-[9px] text-slate-300"></i>
                                                <span class="text-[9px] font-bold text-slate-400">{{ count($room->picture) }} foto</span>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex items-center gap-4 md:gap-6 w-full md:w-auto justify-between md:justify-end">
                                    <div class="text-right">
                                        @if($room->hasDiscount())
                                            <span class="text-[10px] text-slate-400 line-through font-bold block">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                                            <p class="text-lg font-black text-brand-red">Rp {{ number_format($room->discounted_price, 0, ',', '.') }}<span class="text-[10px] text-slate-400 font-medium">/bln</span></p>
                                            @if($room->discount_label)
                                                <div class="flex justify-end gap-2 mt-1">
                                                    <span class="px-2 py-1 bg-brand-red text-white text-[8px] font-black rounded-md shadow-sm uppercase tracking-widest animate-bounce">
                                                        {{ $room->discount_label }}
                                                    </span>
                                                </div>
                                            @endif
                                            @if($room->discount_end)
                                                <div class="text-[8px] font-bold text-slate-400 flex items-center gap-1 justify-end mt-1 uppercase tracking-tighter">
                                                    <i class="fas fa-clock text-[7px]"></i>
                                                    Hingga {{ $room->discount_end->format('d M') }}
                                                </div>
                                            @endif
                                        @else
                                            <p class="text-lg font-black text-brand-navy">Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-[10px] text-slate-400 font-medium">/bln</span></p>
                                        @endif
                                    </div>
                                    <a href="{{ route('room.detail', $room->id) }}" class="px-6 py-3 bg-brand-navy text-white text-[9px] font-black uppercase tracking-widest rounded-xl hover:bg-brand-red transition shadow-md active:scale-95 whitespace-nowrap">
                                        Lihat Detail
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- REVIEWS --}}
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
                    <div class="px-6 md:px-8 py-6 border-b border-slate-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-black text-slate-800 tracking-tight">Ulasan Penghuni</h3>
                            <p class="text-sm text-slate-400 font-medium mt-0.5">{{ $allReviews->count() }} ulasan</p>
                        </div>
                        @if($allReviews->count() > 0)
                            <div class="flex items-center gap-3 bg-amber-50 border border-amber-100 rounded-2xl px-4 py-2.5">
                                <div class="text-2xl font-black text-amber-600">{{ number_format($property->avg_rating, 1) }}</div>
                                <div class="flex items-center gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= round($property->avg_rating) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse($allReviews->take(10) as $review)
                            <div class="px-6 md:px-8 py-5 hover:bg-slate-50/50 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-xl bg-brand-navy text-white flex items-center justify-center font-black text-xs shadow-md flex-shrink-0">
                                        {{ $review->is_anonymous ? '?' : strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-1 flex-wrap gap-2">
                                            <span class="text-xs font-black text-slate-900 uppercase tracking-tight">
                                                {{ $review->is_anonymous ? 'Penghuni Anonim' : ($review->user->name ?? 'Penghuni') }}
                                            </span>
                                            <div class="flex text-amber-400 gap-0.5">
                                                @for($i = 1; $i <= $review->rating; $i++)
                                                    <i class="fas fa-star text-[10px]"></i>
                                                @endfor
                                            </div>
                                        </div>
                                        @if($review->comment)
                                            <p class="text-[12px] font-medium text-slate-500 italic leading-relaxed">"{{ $review->comment }}"</p>
                                        @endif
                                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mt-2">{{ $review->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-8 py-16 text-center">
                                <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <p class="text-slate-400 font-bold text-sm">Belum ada ulasan untuk kos ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDEBAR: Info & Facilities --}}
            <div class="space-y-6 lg:sticky lg:top-24 self-start">
                {{-- Price Summary --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Mulai dari</p>
                    @if($property->has_discount)
                        <span class="text-[10px] text-slate-400 line-through font-bold">Rp {{ number_format($property->min_price, 0, ',', '.') }}</span>
                        <p class="text-3xl font-extrabold text-brand-red mb-1">Rp {{ number_format($property->min_discounted_price, 0, ',', '.') }}<span class="text-sm text-slate-400 font-normal">/bln</span></p>
                    @else
                        <p class="text-3xl font-extrabold text-brand-navy mb-1">Rp {{ number_format($property->min_price, 0, ',', '.') }}<span class="text-sm text-slate-400 font-normal">/bln</span></p>
                    @endif
                    <p class="text-[10px] text-slate-400 font-medium">{{ $property->rooms->where('status', 'available')->count() }} dari {{ $property->rooms->count() }} unit tersedia</p>
                </div>

                {{-- Facilities --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-5">Fasilitas Kos</h4>
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($allAssets as $asset)
                            <div class="flex items-center gap-2.5 group">
                                <div class="w-7 h-7 rounded-lg bg-slate-50 flex items-center justify-center text-brand-navy border border-slate-100 group-hover:bg-brand-navy group-hover:text-white transition-colors flex-shrink-0">
                                    <i class="{{ $asset->icon ?? 'fas fa-check' }} text-[10px]"></i>
                                </div>
                                <span class="text-[11px] font-bold text-slate-600 group-hover:text-slate-900 transition-colors">{{ $asset->name }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- Location --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-4">Lokasi</h4>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-brand-red/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-map-marker-alt text-brand-red text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 capitalize">{{ $property->location }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $property->address }}</p>
                        </div>
                    </div>
                </div>

                {{-- Room Types --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h4 class="text-[10px] font-black uppercase tracking-[0.3em] text-slate-400 mb-4">Tipe Kamar</h4>
                    <div class="flex flex-wrap gap-2">
                        @foreach($property->room_types as $type)
                            <span class="px-3 py-1.5 bg-brand-navy/5 text-brand-navy text-[10px] font-black uppercase tracking-wider rounded-lg border border-brand-navy/10">{{ $type }}</span>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-slate-200 py-10 px-6 bg-white mt-10">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('koskora.png') }}" class="h-7 w-auto" alt="Logo">
                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">© 2025 KosKora</p>
            </div>
            <div class="flex gap-8 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                <a href="{{ route('home') }}" class="hover:text-brand-navy transition">Beranda</a>
                <a href="#" class="hover:text-brand-navy transition">Privasi</a>
                <a href="#" class="hover:text-brand-navy transition">Support</a>
            </div>
        </div>
    </footer>

</body>
</html>

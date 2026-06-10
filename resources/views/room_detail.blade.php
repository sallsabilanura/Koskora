<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>Room #{{ $room->room_number }} — {{ $room->property_name ?? 'KosKora' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="Detail kamar {{ $room->room_number }} di {{ $room->property_name }}. Lihat foto, fasilitas, dan harga sewa.">
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
        .thumbnail-active { border-color: #1e1b9b !important; }
        .brand-card-hover { transition: all 0.35s cubic-bezier(0.2, 0, 0, 0.2); }
        .brand-card-hover:hover { transform: translateY(-4px); box-shadow: 0 20px 40px -15px rgba(30, 27, 155, 0.2); }

        /* Lightbox */
        .lightbox { display: none; position: fixed; inset: 0; z-index: 200; background: rgba(0,0,0,0.92); backdrop-filter: blur(8px); }
        .lightbox.active { display: flex; align-items: center; justify-content: center; }
        .lightbox img { max-width: 90vw; max-height: 85vh; border-radius: 1rem; object-fit: contain; }
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
                @if($room->property_name)
                    <a href="{{ route('property.show', urlencode($room->property_name)) }}" class="px-5 py-2 bg-white text-slate-600 text-[11px] font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        {{ $room->property_name }}
                    </a>
                @else
                    <a href="{{ route('home') }}" class="px-5 py-2 bg-white text-slate-600 text-[11px] font-bold rounded-xl border border-slate-200 hover:bg-slate-50 transition-all uppercase tracking-wide flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        Kembali
                    </a>
                @endif
                @auth
                    <a href="{{ url('/dashboard') }}" class="px-6 py-2.5 bg-brand-navy text-white text-[11px] font-bold rounded-xl shadow-md hover:bg-brand-red transition-all duration-200 uppercase tracking-wide">Dashboard</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <section class="max-w-7xl mx-auto px-4 md:px-6 pt-24 md:pt-28 pb-12">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-[11px] font-bold text-slate-400 uppercase tracking-wider mb-8">
            <a href="{{ route('home') }}" class="hover:text-brand-navy transition">Beranda</a>
            <span>›</span>
            @if($room->property_name)
                <a href="{{ route('property.show', urlencode($room->property_name)) }}" class="hover:text-brand-navy transition">{{ $room->property_name }}</a>
                <span>›</span>
            @endif
            <span class="text-slate-600">Room #{{ $room->room_number }}</span>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-10">

            {{-- LEFT: Gallery --}}
            <div class="lg:col-span-3 space-y-4">
                @if($room->picture && count($room->picture) > 0)
                    {{-- Main Image --}}
                    <div class="aspect-[16/10] rounded-2xl overflow-hidden border border-slate-100 shadow-2xl relative group cursor-pointer" onclick="openLightbox(document.getElementById('main-picture').src)">
                        <img id="main-picture" src="{{ asset('storage/' . $room->picture[0]) }}" class="w-full h-full object-cover transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent pointer-events-none"></div>
                        <div class="absolute top-4 right-4 px-3 py-1.5 bg-white/90 backdrop-blur-md rounded-full shadow-md opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="fas fa-expand text-slate-600 text-xs"></i>
                        </div>
                    </div>

                    {{-- Thumbnails Grid --}}
                    @if(count($room->picture) > 1)
                        <div class="grid grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-3">
                            @foreach($room->picture as $index => $img)
                                <div class="aspect-square rounded-xl overflow-hidden border-2 {{ $index == 0 ? 'thumbnail-active' : 'border-transparent' }} cursor-pointer hover:border-brand-navy/50 transition-all shadow-sm" onclick="changeMainImage('{{ asset('storage/' . $img) }}', this)">
                                    <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                                </div>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="aspect-[16/10] rounded-2xl bg-slate-50 flex flex-col items-center justify-center border-4 border-dashed border-slate-100 italic text-slate-400 space-y-3">
                        <svg class="w-16 h-16 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        <span class="text-sm font-bold">Belum ada foto untuk kamar ini.</span>
                    </div>
                @endif

                {{-- Description --}}
                @if($room->description)
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 mt-6">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3">Tentang Kamar</h3>
                        <p class="text-sm text-slate-600 leading-relaxed font-medium whitespace-pre-line">{{ $room->description }}</p>
                    </div>
                @endif

                {{-- Reviews Section --}}
                @php
                    $reviews = $room->reviews->sortByDesc('created_at');
                    $avgRating = $reviews->avg('rating') ?? 0;
                @endphp
                <div class="bg-white rounded-2xl border border-slate-100 overflow-hidden mt-6">
                    <div class="px-6 py-5 border-b border-slate-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight">Ulasan Kamar</h3>
                            <p class="text-xs text-slate-400 font-medium mt-0.5">{{ $reviews->count() }} ulasan</p>
                        </div>
                        @if($reviews->count() > 0)
                            <div class="flex items-center gap-2 bg-amber-50 border border-amber-100 rounded-xl px-3 py-2">
                                <span class="text-xl font-black text-amber-600">{{ number_format($avgRating, 1) }}</span>
                                <div class="flex gap-0.5">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3 h-3 {{ $i <= round($avgRating) ? 'text-amber-400' : 'text-slate-200' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    @endfor
                                </div>
                            </div>
                        @endif
                    </div>
                    <div class="divide-y divide-slate-50">
                        @forelse($reviews as $review)
                            <div class="px-6 py-5 hover:bg-slate-50/50 transition-colors">
                                <div class="flex items-start gap-4">
                                    <div class="w-9 h-9 rounded-xl bg-brand-navy text-white flex items-center justify-center font-black text-xs shadow-md flex-shrink-0">
                                        {{ $review->is_anonymous ? '?' : strtoupper(substr($review->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="flex-1">
                                        <div class="flex items-center justify-between mb-1 flex-wrap gap-2">
                                            <span class="text-xs font-black text-slate-900">{{ $review->is_anonymous ? 'Anonim' : ($review->user->name ?? 'Penghuni') }}</span>
                                            <div class="flex text-amber-400 gap-0.5">
                                                @for($i = 1; $i <= $review->rating; $i++)
                                                    <i class="fas fa-star text-[9px]"></i>
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
                            <div class="px-6 py-14 text-center">
                                <p class="text-slate-400 font-bold text-sm">Belum ada ulasan untuk kamar ini.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- RIGHT SIDEBAR --}}
            <div class="lg:col-span-2 space-y-6 lg:sticky lg:top-24 self-start">
                {{-- Room Info Card --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    @if($room->property_name)
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">{{ $room->property_name }}</p>
                    @endif
                    <h1 class="text-3xl font-extrabold text-slate-900 tracking-tight mb-3">Room #{{ $room->room_number }}</h1>

                    <div class="flex items-center gap-2 mb-5 flex-wrap">
                        <span class="px-3 py-1 bg-brand-navy/10 text-brand-navy text-[9px] font-black uppercase tracking-widest rounded-full">{{ $room->room_type }}</span>
                        @if($room->gender == 'putra')
                            <span class="px-3 py-1 bg-blue-50 text-blue-700 text-[9px] font-black rounded-full uppercase tracking-widest border border-blue-100">♂ Putra</span>
                        @elseif($room->gender == 'putri')
                            <span class="px-3 py-1 bg-pink-50 text-pink-700 text-[9px] font-black rounded-full uppercase tracking-widest border border-pink-100">♀ Putri</span>
                        @else
                            <span class="px-3 py-1 bg-purple-50 text-purple-700 text-[9px] font-black rounded-full uppercase tracking-widest border border-purple-100">⚤ Gabungan</span>
                        @endif
                        @php
                            $statusStyle = [
                                'available' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                'occupied' => 'bg-rose-50 text-rose-700 border-rose-100',
                                'maintenance' => 'bg-amber-50 text-amber-700 border-amber-100',
                            ];
                        @endphp
                        <span class="px-3 py-1 {{ $statusStyle[$room->status] ?? 'bg-slate-50' }} text-[9px] font-black rounded-full uppercase tracking-widest border">
                            {{ $room->status == 'available' ? 'Tersedia' : ($room->status == 'occupied' ? 'Penuh' : 'Maintenance') }}
                        </span>
                    </div>

                    {{-- Price --}}
                    <div class="pt-4 border-t border-slate-50 mb-5">
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest mb-1">Harga Sewa</p>
                        @if($room->hasDiscount())
                            <div class="flex flex-col">
                                <span class="text-xs text-slate-400 line-through font-bold">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                                <div class="flex items-center gap-3">
                                    <p class="text-3xl font-extrabold text-brand-red tracking-tight">Rp {{ number_format($room->discounted_price, 0, ',', '.') }}<span class="text-sm text-slate-400 font-normal">/bln</span></p>
                                    @if($room->discount_label)
                                        <span class="px-2 py-1 bg-brand-red text-white text-[8px] font-black rounded-md shadow-sm uppercase tracking-widest animate-bounce">
                                            {{ $room->discount_label }}
                                        </span>
                                    @endif
                                    @if($room->discount_end)
                                        <span class="px-2 py-1 bg-brand-navy text-white text-[8px] font-black rounded-md shadow-sm uppercase tracking-widest flex items-center gap-1">
                                            <i class="fas fa-calendar-alt text-[7px]"></i>
                                            Hingga {{ $room->discount_end->format('d M Y') }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @else
                            <p class="text-3xl font-extrabold text-brand-navy tracking-tight">Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-sm text-slate-400 font-normal">/bln</span></p>
                        @endif
                    </div>

                    {{-- CTA --}}
                    @if($room->status == 'available')
                        @auth
                            <form action="{{ route('bookings.rent', $room->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full py-4 bg-brand-navy text-white rounded-xl font-black text-sm uppercase tracking-widest hover:bg-brand-red transition-all shadow-lg active:scale-[0.98] flex items-center justify-center gap-2">
                                    <i class="fas fa-key"></i>
                                    Sewa Sekarang
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="w-full py-4 bg-brand-navy text-white rounded-xl font-black text-sm uppercase tracking-widest hover:bg-brand-red transition-all shadow-lg text-center block">
                                Masuk untuk Sewa
                            </a>
                        @endauth
                    @else
                        <button disabled class="w-full py-4 bg-slate-200 text-slate-400 rounded-xl font-black text-sm uppercase tracking-widest cursor-not-allowed">
                            Unit Tidak Tersedia
                        </button>
                    @endif
                </div>

                {{-- Assets --}}
                @if($room->assets->count() > 0)
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-5">Fasilitas & Aset</h3>
                        <div class="grid grid-cols-2 gap-3">
                            @foreach($room->assets as $asset)
                                <div class="flex items-center gap-2.5 p-2.5 bg-slate-50 rounded-xl border border-slate-100 group hover:bg-brand-navy/5 transition-colors">
                                    <div class="w-7 h-7 rounded-lg bg-white flex items-center justify-center text-brand-navy shadow-sm flex-shrink-0 border border-slate-100">
                                        <i class="{{ $asset->icon ?? 'fas fa-check' }} text-[10px]"></i>
                                    </div>
                                    <span class="text-[11px] font-bold text-slate-600">{{ $asset->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                {{-- Location --}}
                <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                    <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-4">Lokasi</h3>
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-brand-red/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                            <i class="fas fa-map-marker-alt text-brand-red text-sm"></i>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800 capitalize">{{ $room->district }}, {{ $room->city }}</p>
                            <p class="text-xs text-slate-400 mt-1">{{ $room->address }}</p>
                        </div>
                    </div>
                </div>

                {{-- Sibling Rooms --}}
                @if($siblingRooms->count() > 0)
                    <div class="bg-white rounded-2xl p-6 border border-slate-100 shadow-sm">
                        <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-5">Unit Lain di {{ $room->property_name }}</h3>
                        <div class="space-y-3">
                            @foreach($siblingRooms as $sibling)
                                <a href="{{ route('room.detail', $sibling->id) }}" class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 hover:bg-white hover:shadow-md border border-transparent hover:border-slate-200 transition-all group">
                                    <div class="w-12 h-12 rounded-lg overflow-hidden bg-slate-200 flex-shrink-0">
                                        @if($sibling->picture && count($sibling->picture) > 0)
                                            <img src="{{ asset('storage/' . $sibling->picture[0]) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-slate-300 text-xs font-bold">#{{ $sibling->room_number }}</div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-black text-slate-800 uppercase">Room #{{ $sibling->room_number }}</p>
                                        @if($sibling->hasDiscount())
                                            <p class="text-[10px] text-brand-red font-bold">Rp {{ number_format($sibling->discounted_price, 0, ',', '.') }}/bln</p>
                                        @else
                                            <p class="text-[10px] text-slate-400 font-medium">Rp {{ number_format($sibling->price, 0, ',', '.') }}/bln</p>
                                        @endif
                                    </div>
                                    <span class="text-[8px] font-black {{ $sibling->status == 'available' ? 'text-emerald-500' : 'text-rose-500' }} uppercase">
                                        {{ $sibling->status == 'available' ? 'Open' : 'Full' }}
                                    </span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    {{-- LIGHTBOX --}}
    <div id="lightbox" class="lightbox" onclick="closeLightbox()">
        <button onclick="closeLightbox()" class="absolute top-6 right-6 p-3 bg-white/10 hover:bg-white/20 rounded-2xl transition text-white z-10">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
        <img id="lightbox-img" src="" class="pointer-events-none">
    </div>

    {{-- FOOTER --}}
    <footer class="border-t border-slate-200 py-10 px-6 bg-white">
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center gap-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('koskora.png') }}" class="h-7 w-auto" alt="Logo">
                <p class="text-[10px] font-bold text-slate-400 tracking-widest uppercase">© 2025 KosKora</p>
            </div>
            <div class="flex gap-8 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                <a href="{{ route('home') }}" class="hover:text-brand-navy transition">Beranda</a>
                <a href="#" class="hover:text-brand-navy transition">Privasi</a>
            </div>
        </div>
    </footer>

    <script>
        function changeMainImage(url, thumb) {
            document.getElementById('main-picture').src = url;
            document.querySelectorAll('.thumbnail-active').forEach(el => el.classList.remove('thumbnail-active'));
            thumb.classList.add('thumbnail-active');
        }

        function openLightbox(src) {
            document.getElementById('lightbox-img').src = src;
            document.getElementById('lightbox').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLightbox() {
            document.getElementById('lightbox').classList.remove('active');
            document.body.style.overflow = 'auto';
        }

        document.addEventListener('keydown', e => {
            if (e.key === 'Escape') closeLightbox();
        });
    </script>
</body>
</html>

<x-app-layout>
    @section('header_title', 'Room Details')

    <div class="max-w-6xl mx-auto space-y-8 animate-fade-in">
        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="flex items-center gap-5">
                <a href="{{ route('rooms.index') }}" class="w-11 h-11 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand transition-all shadow-sm">
                    <i class="fas fa-arrow-left text-xs"></i>
                </a>
                <div>
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Unit #{{ $room->room_number }}</h2>
                        <span class="badge {{ $room->status === 'available' ? 'badge-green' : 'badge-red' }}">{{ strtoupper($room->status) }}</span>
                    </div>
                    <p class="text-slate-500 font-medium text-sm">{{ $room->property_name ?: 'KosKora Main' }} • {{ $room->room_type }}</p>
                </div>
            </div>
            @if(auth()->user()->isAdmin())
                <div class="flex items-center gap-3">
                    <a href="{{ route('rooms.edit', $room->id) }}" class="btn btn-primary">
                        <i class="fas fa-edit text-xs"></i>
                        Edit Unit Kamar
                    </a>
                </div>
            @endif
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- ===== LEFT: GALLERY & DESCRIPTION ===== --}}
            <div class="lg:col-span-8 space-y-8">
                {{-- Gallery --}}
                <div class="stat-card !p-4">
                    @if($room->picture && count($room->picture) > 0)
                        <div class="aspect-[16/9] rounded-2xl overflow-hidden mb-4 shadow-inner bg-slate-100">
                            <img id="main-picture" src="{{ asset('storage/' . $room->picture[0]) }}" class="w-full h-full object-cover">
                        </div>
                        <div class="grid grid-cols-5 gap-3">
                            @foreach($room->picture as $index => $img)
                                <div class="aspect-square rounded-xl overflow-hidden border-2 {{ $index == 0 ? 'border-brand shadow-md' : 'border-slate-100' }} cursor-pointer hover:border-brand/50 transition-all thumb-item group" 
                                     onclick="changeMainImage('{{ asset('storage/' . $img) }}', this)">
                                    <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform">
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="aspect-[16/9] rounded-2xl bg-slate-50 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 text-slate-300">
                            <i class="fas fa-images text-5xl mb-4 opacity-20"></i>
                            <span class="text-sm font-black uppercase tracking-widest">Belum ada foto</span>
                        </div>
                    @endif
                </div>

                {{-- Description Card --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                            <i class="fas fa-align-left text-brand text-sm"></i>
                            Deskripsi Unit
                        </h3>
                    </div>
                    <div class="text-slate-600 font-medium leading-relaxed whitespace-pre-line text-sm">
                        {{ $room->description ?: 'Tidak ada deskripsi tambahan untuk unit ini.' }}
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8 pt-8 border-t border-slate-100">
                        <div class="space-y-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat Lengkap</span>
                            <p class="text-sm font-bold text-slate-700 leading-snug">{{ $room->address ?: '—' }}</p>
                        </div>
                        <div class="space-y-1">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Wilayah</span>
                            <p class="text-sm font-bold text-slate-700 leading-snug">{{ $room->district }}, {{ $room->city }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ===== RIGHT: PRICING & FEATURES ===== --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Price Card --}}
                <div class="stat-card bg-brand border-brand-dark shadow-xl shadow-brand/20 relative overflow-hidden group">
                    <div class="absolute top-0 right-0 -mr-16 -mt-16 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:bg-white/20 transition-all"></div>
                    
                    <div class="relative z-10">
                        <span class="text-[10px] font-black text-white/60 uppercase tracking-widest">Harga Sewa Bulanan</span>
                        @if($room->hasDiscount())
                            <div class="flex items-baseline gap-3 mt-2">
                                <div class="text-3xl font-black text-white tracking-tight">Rp {{ number_format($room->discounted_price, 0, ',', '.') }}</div>
                                <div class="px-2 py-1 bg-red-500 text-white text-[10px] font-black rounded-lg shadow-sm">{{ $room->discount_percentage }}% OFF</div>
                            </div>
                            <div class="text-sm text-white/40 font-bold line-through mt-1">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                        @else
                            <div class="text-3xl font-black text-white tracking-tight mt-2">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                        @endif
                    </div>
                </div>

                {{-- Features Card --}}
                <div class="stat-card">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-sm font-black text-slate-400 uppercase tracking-widest">Fasilitas Unit</h3>
                        <i class="fas fa-bolt text-brand text-xs"></i>
                    </div>
                    @if($room->assets->count() > 0)
                        <div class="grid grid-cols-1 gap-3">
                            @foreach($room->assets as $asset)
                                <div class="flex items-center gap-4 p-3 rounded-2xl bg-slate-50 border border-slate-100 group hover:border-brand transition-all">
                                    <div class="w-10 h-10 rounded-xl bg-white flex items-center justify-center text-brand shadow-sm transition-transform group-hover:scale-110">
                                        <i class="{{ $asset->icon ?: 'fas fa-check' }} text-sm"></i>
                                    </div>
                                    <span class="text-[11px] font-extrabold text-slate-600 uppercase tracking-wider group-hover:text-brand transition-colors">{{ $asset->name }}</span>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-6">
                            <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Fasilitas Kosong</p>
                        </div>
                    @endif
                </div>

                {{-- Quick Actions --}}
                @if($room->status == 'available' && !auth()->user()->isAdmin())
                    <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="w-full py-5 bg-brand text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-dark transition-all transform active:scale-[0.98] shadow-xl shadow-brand/20 flex items-center justify-center gap-3">
                        <i class="fas fa-key"></i>
                        Booking Unit Sekarang
                    </a>
                @endif
            </div>
        </div>

        {{-- ===== REVIEWS ===== --}}
        <div class="stat-card">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-lg font-extrabold text-slate-900 flex items-center gap-2">
                    <i class="fas fa-star text-amber-400 text-sm"></i>
                    Ulasan Penghuni
                </h3>
                @php
                    $reviews = $room->reviews()->with('user')->latest()->get();
                    $avg = $reviews->avg('rating') ?: 0;
                @endphp
                <div class="px-4 py-2 bg-slate-50 border border-slate-100 rounded-xl flex items-center gap-3">
                    <span class="text-lg font-black text-slate-900">{{ number_format($avg, 1) }}</span>
                    <div class="flex gap-1">
                        @for($i=1; $i<=5; $i++)
                            <i class="fas fa-star text-[10px] {{ $i <= round($avg) ? 'text-amber-400' : 'text-slate-200' }}"></i>
                        @endfor
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @forelse($reviews as $rev)
                    <div class="p-6 rounded-2xl bg-slate-50/50 border border-slate-100 group hover:border-brand/30 transition-all">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-white text-brand border border-slate-100 flex items-center justify-center font-black text-sm shadow-sm">
                                    {{ strtoupper(substr($rev->user->name ?? '?', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-[13px] font-extrabold text-slate-900">{{ $rev->is_anonymous ? 'Anonim' : ($rev->user->name ?? 'User') }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $rev->created_at->translatedFormat('d M Y') }}</div>
                                </div>
                            </div>
                            <div class="flex gap-0.5">
                                @for($i=1; $i<=5; $i++)
                                    <i class="fas fa-star text-[9px] {{ $i <= $rev->rating ? 'text-amber-400' : 'text-slate-200' }}"></i>
                                @endfor
                            </div>
                        </div>
                        <p class="text-sm font-medium text-slate-600 leading-relaxed italic">"{{ $rev->comment ?: 'Tidak ada komentar.' }}"</p>
                    </div>
                @empty
                    <div class="md:col-span-2 text-center py-12">
                        <i class="fas fa-comment-slash text-4xl text-slate-200 mb-4 opacity-30"></i>
                        <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Belum ada ulasan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(url, thumb) {
            document.getElementById('main-picture').src = url;
            document.querySelectorAll('.thumb-item').forEach(c => {
                c.classList.remove('border-brand', 'shadow-md');
                c.classList.add('border-slate-100');
            });
            thumb.classList.remove('border-slate-100');
            thumb.classList.add('border-brand', 'shadow-md');
        }
    </script>
</x-app-layout>

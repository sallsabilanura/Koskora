<x-app-layout>
    @section('header_title', 'Room Details')

    <div class="max-w-6xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Kamar {{ $room->room_number }}</h2>
            <div class="flex items-center space-x-4">
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('rooms.edit', $room->id) }}" class="px-6 py-2.5 bg-slate-800 text-white rounded-xl font-bold text-sm hover:bg-slate-900 transition-all shadow-lg shadow-slate-200">
                        Edit Data Kamar
                    </a>
                @endif
                <a href="{{ route('rooms.index') }}" class="px-6 py-2.5 bg-white text-slate-600 border border-slate-200 rounded-xl font-bold text-sm hover:bg-slate-50 transition-all">
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
            <!-- Gallery Grid -->
            <div class="space-y-4">
                @if($room->picture && count($room->picture) > 0)
                    <!-- Main Featured Image -->
                    <div class="aspect-[16/10] rounded-[2.5rem] overflow-hidden border border-slate-100 shadow-2xl relative group">
                        <img id="main-picture" src="{{ asset('storage/' . $room->picture[0]) }}" class="w-full h-full object-cover transition-all duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent pointer-none"></div>
                    </div>
                    
                    <!-- Thumbnails -->
                    <div class="grid grid-cols-4 md:grid-cols-5 gap-3">
                        @foreach($room->picture as $index => $img)
                            <div class="aspect-square rounded-2xl overflow-hidden border-2 {{ $index == 0 ? 'border-blue-500' : 'border-transparent' }} cursor-pointer hover:border-blue-300 transition-all thumbnail-card" onclick="changeMainImage('{{ asset('storage/' . $img) }}', this)">
                                <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="aspect-[16/10] rounded-[2.5rem] bg-slate-50 flex items-center justify-center border-4 border-dashed border-slate-100 italic text-slate-400">
                        Belum ada gallery foto untuk kamar ini.
                    </div>
                @endif
            </div>

            <!-- Details Info -->
            <div class="bg-white rounded-[2.5rem] p-10 shadow-xl shadow-slate-100 border border-slate-200 h-fit">
                <div class="flex items-center justify-between mb-8">
                    <span class="px-4 py-1.5 bg-blue-50 text-blue-600 text-xs font-black uppercase tracking-widest rounded-full">
                        {{ $room->room_type }}
                    </span>
                    @php
                        $statusClasses = [
                            'available' => 'bg-emerald-100 text-emerald-700',
                            'occupied' => 'bg-rose-100 text-rose-700',
                            'maintenance' => 'bg-amber-100 text-amber-700',
                        ];
                    @endphp
                    <span class="px-4 py-1.5 {{ $statusClasses[$room->status] ?? 'bg-slate-100' }} text-[10px] font-black rounded-full uppercase tracking-widest">
                        {{ $room->status }}
                    </span>
                </div>

                <div class="space-y-6">
                    <div>
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Harga Sewa</div>
                        <div class="text-4xl font-black text-slate-800 tracking-tight">
                            Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-lg font-normal text-slate-400">/bulan</span>
                        </div>
                    </div>

                    <div class="pt-6 border-t border-slate-50">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Deskripsi & Fasilitas</div>
                        <div class="text-slate-600 leading-relaxed font-medium whitespace-pre-line">
                            {{ $room->description ?: 'Tidak ada deskripsi tersedia.' }}
                        </div>
                    </div>

                    <div class="pt-8 border-t border-slate-50">
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-4 bg-slate-50 rounded-2xl flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-blue-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Lantai</div>
                                    <div class="text-sm font-bold text-slate-700">Lantai 1</div>
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-2xl flex items-center space-x-3">
                                <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-emerald-500">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <div>
                                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tersedia</div>
                                    <div class="text-sm font-bold text-slate-700">Ready</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if($room->status == 'available' && !auth()->user()->isAdmin())
                        <div class="pt-6">
                            <a href="{{ route('bookings.create', ['room_id' => $room->id]) }}" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-lg hover:bg-blue-700 shadow-2xl shadow-blue-100 transition-all flex items-center justify-center">
                                SEWA SEKARANG
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function changeMainImage(url, thumb) {
            document.getElementById('main-picture').src = url;
            
            // Highlight thumbnail
            const cards = document.querySelectorAll('.thumbnail-card');
            cards.forEach(c => c.classList.replace('border-blue-500', 'border-transparent'));
            thumb.classList.replace('border-transparent', 'border-blue-500');
        }
    </script>
</x-app-layout>

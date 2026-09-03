<x-app-layout>
    @section('header_title', 'Rooms Management')

    <div class="space-y-8 animate-fade-in">
        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen Kamar</h2>
                <p class="text-slate-500 font-medium text-sm">Kelola unit properti, harga, dan ketersediaan unit kos Anda.</p>
            </div>
            <a href="{{ route('rooms.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle text-sm"></i>
                Tambah Unit Kamar
            </a>
        </div>

        {{-- ===== FILTER SECTION ===== --}}
        <div class="filter-bar">
            <form action="{{ route('rooms.index') }}" method="GET">
                <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                    {{-- Search --}}
                    <div style="position:relative; flex:1; min-width:180px;">
                        <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nomor, properti, atau daerah..."
                               style="padding-left:2.5rem; width:100%; margin:0;">
                    </div>
                    {{-- District --}}
                    <select name="district" onchange="this.form.submit()" style="width:160px; flex-shrink:0; margin:0;">
                        <option value="">Semua Daerah</option>
                        @foreach($districts as $d)
                            <option value="{{ $d->district }}" {{ request('district') == $d->district ? 'selected' : '' }}>
                                {{ $d->district }} ({{ $d->count }})
                            </option>
                        @endforeach
                    </select>
                    {{-- Status --}}
                    <select name="status" onchange="this.form.submit()" style="width:140px; flex-shrink:0; margin:0;">
                        <option value="">Semua Status</option>
                        <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Available</option>
                        <option value="occupied" {{ request('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                        <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                    </select>
                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                        <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'district']))
                        <a href="{{ route('rooms.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                            <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ===== TABLE SECTION ===== --}}
        <div class="table-wrap table-wrap-visible">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Info Kamar</th>
                        <th>Kategori</th>
                        <th>Harga Sewa</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rooms as $room)
                        <tr class="group">
                            <td data-label="Kamar">
                                <div class="flex items-center gap-3">
                                    <div class="relative">
                                        @if($room->image)
                                            <img src="{{ asset('storage/' . $room->image) }}" class="w-11 h-11 rounded-xl object-cover border border-slate-200 shadow-sm">
                                        @else
                                            <div class="w-11 h-11 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center text-slate-300">
                                                <i class="fas fa-image"></i>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-sm">#{{ $room->room_number }}</div>
                                        <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">{{ $room->property_name ?: 'KosKora Main' }}</div>
                                        <div class="text-[10px] font-black text-brand">{{ $room->district }}, {{ $room->city }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Kategori">
                                <div class="text-sm font-bold text-slate-700">{{ $room->room_type }}</div>
                                @if($room->gender_target)
                                    <span class="badge {{ $room->gender_target == 'putri' ? 'badge-red' : ($room->gender_target == 'putra' ? 'badge-blue' : 'badge-gray') }} mt-1">
                                        {{ ucfirst($room->gender_target) }}
                                    </span>
                                @endif
                            </td>
                            <td data-label="Harga">
                                @if($room->hasDiscount())
                                    <div class="text-[10px] font-bold text-slate-400 line-through">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                                    <div class="flex items-center gap-2">
                                        <div class="font-extrabold text-red-500">Rp {{ number_format($room->discounted_price, 0, ',', '.') }}</div>
                                        <span class="px-1.5 py-0.5 bg-red-50 text-red-500 text-[8px] font-black rounded-md">SALE</span>
                                    </div>
                                @else
                                    <div class="font-extrabold text-slate-900">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                                @endif
                            </td>
                            <td class="text-center" data-label="Status">
                                @php
                                    $badgeCls = [
                                        'available' => 'badge-green',
                                        'occupied' => 'badge-red',
                                        'maintenance' => 'badge-amber',
                                    ][$room->status] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $badgeCls }}">{{ strtoupper($room->status) }}</span>
                            </td>
                            <td class="text-right" data-label="Aksi">
                                <div class="flex items-center justify-end">
                                    <div class="relative inline-block text-left" x-data="{ open: false }" @click.outside="open = false">
                                        <button @click="open = !open" type="button" class="w-8 h-8 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-slate-700 hover:border-slate-300 transition-all focus:outline-none">
                                            <i class="fas fa-ellipsis-v text-xs"></i>
                                        </button>
                                        
                                        <div x-show="open" 
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="transform opacity-0 scale-95"
                                             x-transition:enter-end="transform opacity-100 scale-100"
                                             x-transition:leave="transition ease-in duration-75"
                                             x-transition:leave-start="transform opacity-100 scale-100"
                                             x-transition:leave-end="transform opacity-0 scale-95"
                                             class="absolute right-0 mt-1.5 w-36 rounded-2xl bg-white border border-slate-200 shadow-xl z-50 py-1.5 text-left focus:outline-none"
                                             style="display: none;">
                                             
                                             <a href="{{ route('rooms.show', $room->id) }}" class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">
                                                 <i class="fas fa-eye w-4 text-center"></i> Detail
                                             </a>
                                             
                                             <a href="{{ route('rooms.edit', $room->id) }}" class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">
                                                 <i class="fas fa-edit w-4 text-center"></i> Ubah
                                             </a>
                                             
                                             <div class="h-px bg-slate-100 my-1"></div>
                                             
                                             <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="block w-full">
                                                 @csrf @method('DELETE')
                                                 <button type="submit" onclick="return confirm('Hapus unit ini?')" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors">
                                                     <i class="fas fa-trash-alt w-4 text-center"></i> Hapus
                                                 </button>
                                             </form>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5">
                                <div class="empty-state">
                                    <i class="fas fa-door-closed"></i>
                                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest mt-2">Belum ada unit kamar</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="flex justify-center pt-4">
            {{ $rooms->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>
<x-app-layout>
    @section('header_title', 'Rooms Management')

    <div class="space-y-4 md:space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Daftar Kamar</h2>
            <a href="{{ route('rooms.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 transition btn-touch">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Kamar
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-3 md:p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center shadow-sm text-sm">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <!-- Desktop Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm desktop-table">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Gambar</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No Kamar</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Tipe</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Harga</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($rooms as $room)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-600 font-medium">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                @if($room->picture && is_array($room->picture) && count($room->picture) > 0)
                                    <img src="{{ asset('storage/' . $room->picture[0]) }}" class="w-16 h-12 object-cover rounded-lg border border-slate-200">
                                @else
                                    <div class="w-16 h-12 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-slate-800 font-bold">{{ $room->room_number }}</div>
                                <div class="mt-1">
                                    @if($room->gender == 'putra')
                                        <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 rounded-lg">Putra</span>
                                    @elseif($room->gender == 'putri')
                                        <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest bg-pink-100 text-pink-700 rounded-lg">Putri</span>
                                    @else
                                        <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest bg-purple-100 text-purple-700 rounded-lg">Gabungan</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ $room->room_type }}</td>
                            <td class="px-6 py-4 text-slate-800 font-semibold">Rp {{ number_format($room->price, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 text-xs font-bold rounded-full {{ $room->status == 'available' ? 'bg-emerald-100 text-emerald-700' : ($room->status == 'occupied' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <a href="{{ route('rooms.show', $room->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors" title="View Details">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                    <a href="{{ route('rooms.edit', $room->id) }}" class="p-2 text-slate-400 hover:text-amber-600 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" onclick="return confirm('Yakin hapus data ini?')" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="mobile-cards space-y-3">
            @foreach ($rooms as $room)
                <div class="mobile-card">
                    <div class="flex items-start gap-3 mb-3">
                        @if($room->picture && is_array($room->picture) && count($room->picture) > 0)
                            <img src="{{ asset('storage/' . $room->picture[0]) }}" class="w-16 h-16 object-cover rounded-xl border border-slate-200 flex-shrink-0">
                        @else
                            <div class="w-16 h-16 bg-slate-100 rounded-xl flex items-center justify-center text-slate-300 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <span class="font-black text-slate-800 text-lg">Room #{{ $room->room_number }}</span>
                                <span class="px-2.5 py-0.5 text-[10px] font-bold rounded-full {{ $room->status == 'available' ? 'bg-emerald-100 text-emerald-700' : ($room->status == 'occupied' ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700') }}">
                                    {{ ucfirst($room->status) }}
                                </span>
                            </div>
                            <div class="text-xs text-slate-500 mb-2">{{ $room->room_type }}</div>
                            <div class="flex flex-wrap gap-1 mb-2">
                                @if($room->gender == 'putra')
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest bg-blue-100 text-blue-700 rounded-lg">Khusus Putra</span>
                                @elseif($room->gender == 'putri')
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest bg-pink-100 text-pink-700 rounded-lg">Khusus Putri</span>
                                @else
                                    <span class="px-2 py-0.5 text-[8px] font-black uppercase tracking-widest bg-purple-100 text-purple-700 rounded-lg">Campur</span>
                                @endif
                            </div>
                            <div class="text-sm font-bold text-brand-blue mt-1">Rp {{ number_format($room->price, 0, ',', '.') }}/bln</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-1 pt-3 border-t border-slate-100">
                        <a href="{{ route('rooms.show', $room->id) }}" class="px-3 py-2 text-xs font-bold text-blue-600 bg-blue-50 rounded-lg transition btn-touch">Detail</a>
                        <a href="{{ route('rooms.edit', $room->id) }}" class="px-3 py-2 text-xs font-bold text-amber-600 bg-amber-50 rounded-lg transition btn-touch">Edit</a>
                        <form action="{{ route('rooms.destroy', $room->id) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-2 text-xs font-bold text-rose-600 bg-rose-50 rounded-lg transition btn-touch" onclick="return confirm('Yakin hapus data ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

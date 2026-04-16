<x-app-layout>
    @section('header_title', 'Rentals Management')

    <div class="space-y-4 md:space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Daftar Sewa Kamar</h2>
            <a href="{{ route('rentals.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 transition btn-touch">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                Tambah Data Sewa
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-3 md:p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center shadow-sm text-sm">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        @if ($error = Session::get('error'))
            <div class="p-3 md:p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl flex items-center shadow-sm text-sm">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $error }}
            </div>
        @endif

        <!-- Desktop Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm desktop-table">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">No</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Penyewa & Kamar</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Durasi Sewa</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Total Harga</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @foreach ($rentals as $rental)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-600 font-medium text-center">{{ $loop->iteration }}</td>
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800">{{ $rental->tenant->user->name ?? 'Unknown Tenant' }}</div>
                                <div class="text-xs font-semibold text-blue-600 italic">Kamar {{ $rental->room->room_number ?? '-' }} ({{ $rental->room->room_type ?? '-' }})</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-slate-700">
                                    {{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y') }}
                                </div>
                                <div class="text-xs text-slate-400 font-medium tracking-tight">s/d {{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}</div>
                            </td>
                            <td class="px-6 py-4 text-right font-bold text-slate-800 italic">
                                Rp {{ number_format($rental->total_price, 0, ',', '.') }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $statusClasses = [
                                        'active' => 'bg-emerald-100 text-emerald-700',
                                        'pending' => 'bg-amber-100 text-amber-700 border border-amber-200',
                                        'finished' => 'bg-slate-100 text-slate-600'
                                    ];
                                @endphp
                                <span class="px-3 py-1 text-xs font-black rounded-full uppercase tracking-tighter {{ $statusClasses[$rental->status] ?? 'bg-slate-100' }}">
                                    {{ $rental->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    @if($rental->status == 'pending')
                                        <form action="{{ route('rentals.approve', $rental->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-emerald-600 text-white text-xs font-bold rounded-lg hover:bg-emerald-700 transition-colors shadow-lg shadow-emerald-100">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                ACC
                                            </button>
                                        </form>
                                    @endif
                                    <a href="{{ route('rentals.show', $rental->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                    <a href="{{ route('rentals.edit', $rental->id) }}" class="p-2 text-slate-400 hover:text-amber-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></a>
                                    <form action="{{ route('rentals.destroy', $rental->id) }}" method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors" onclick="return confirm('Yakin hapus data ini?')"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
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
            @foreach ($rentals as $rental)
                <div class="mobile-card">
                    <div class="flex items-center justify-between mb-3">
                        <div class="min-w-0 flex-1">
                            <div class="font-bold text-slate-800 truncate">{{ $rental->tenant->user->name ?? 'Unknown' }}</div>
                            <div class="text-xs font-semibold text-blue-600 italic">Kamar {{ $rental->room->room_number ?? '-' }} · {{ $rental->room->room_type ?? '-' }}</div>
                        </div>
                        @php
                            $mobileStatusClasses = [
                                'active' => 'bg-emerald-100 text-emerald-700',
                                'pending' => 'bg-amber-100 text-amber-700',
                                'finished' => 'bg-slate-100 text-slate-600'
                            ];
                        @endphp
                        <span class="px-2.5 py-0.5 text-[10px] font-black rounded-full uppercase {{ $mobileStatusClasses[$rental->status] ?? 'bg-slate-100' }} flex-shrink-0 ml-2">
                            {{ $rental->status }}
                        </span>
                    </div>
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div class="bg-slate-50 rounded-lg p-2.5">
                            <div class="text-[9px] font-bold text-slate-400 uppercase">Periode</div>
                            <div class="text-xs font-bold text-slate-700">{{ \Carbon\Carbon::parse($rental->start_date)->format('d/m/Y') }}</div>
                            <div class="text-[10px] text-slate-400">s/d {{ \Carbon\Carbon::parse($rental->end_date)->format('d/m/Y') }}</div>
                        </div>
                        <div class="bg-slate-50 rounded-lg p-2.5">
                            <div class="text-[9px] font-bold text-slate-400 uppercase">Total Harga</div>
                            <div class="text-sm font-black text-brand-blue">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-1 pt-3 border-t border-slate-100">
                        @if($rental->status == 'pending')
                            <form action="{{ route('rentals.approve', $rental->id) }}" method="POST" class="inline-block">
                                @csrf
                                <button type="submit" class="px-3 py-2 text-xs font-bold text-white bg-emerald-600 rounded-lg btn-touch">ACC</button>
                            </form>
                        @endif
                        <a href="{{ route('rentals.show', $rental->id) }}" class="px-3 py-2 text-xs font-bold text-blue-600 bg-blue-50 rounded-lg btn-touch">Detail</a>
                        <a href="{{ route('rentals.edit', $rental->id) }}" class="px-3 py-2 text-xs font-bold text-amber-600 bg-amber-50 rounded-lg btn-touch">Edit</a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

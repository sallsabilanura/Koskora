<x-app-layout>
    @section('header_title', 'Rentals Management')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Kontrak Sewa</h2>
                <p class="text-slate-500 font-medium text-sm">Monitoring status hunian, durasi kontrak, dan penempatan unit.</p>
            </div>
            <a href="{{ route('rentals.create') }}" class="btn btn-primary">
                <i class="fas fa-file-signature text-sm"></i>
                Buat Kontrak Baru
            </a>
        </div>

        {{-- ===== FILTER SECTION ===== --}}
        <div class="filter-bar">
            <form action="{{ route('rentals.index') }}" method="GET">
                <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                    <div style="position:relative; flex:1; min-width:180px;">
                        <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama penyewa atau nomor kamar..."
                               style="padding-left:2.5rem; width:100%; margin:0;">
                    </div>
                    <select name="status" onchange="this.form.submit()" style="width:150px; flex-shrink:0; margin:0;">
                        <option value="">Semua Status</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="finished" {{ request('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                    </select>
                    <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                        <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status']))
                        <a href="{{ route('rentals.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                            <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ===== TABLE SECTION ===== --}}
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th class="w-16 text-center">#</th>
                        <th>Penyewa & Unit</th>
                        <th>Periode Kontrak</th>
                        <th class="text-right">Total Nilai</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($rentals as $rental)
                        <tr class="group">
                            <td class="text-center text-slate-400 font-bold text-xs" data-label="#">{{ $loop->iteration + ($rentals->currentPage() - 1) * $rentals->perPage() }}</td>
                            <td data-label="Penyewa">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-brand-light text-brand flex items-center justify-center text-sm shadow-sm">
                                        <i class="fas fa-file-contract"></i>
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $rental->tenant->user->name ?? 'Unknown' }}</div>
                                        <div class="text-[11px] font-black text-brand uppercase tracking-widest mt-0.5">UNIT #{{ $rental->room->room_number ?? '-' }} &bull; {{ $rental->duration_type === 'yearly' ? 'TAHUNAN' : 'BULANAN' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Periode">
                                <div class="flex items-center gap-2 text-sm">
                                    <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y') }}</span>
                                    <i class="fas fa-arrow-right text-[10px] text-slate-300"></i>
                                    <span class="font-bold text-slate-700">{{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}</span>
                                </div>
                            </td>
                            <td class="text-right" data-label="Total">
                                <div class="text-sm font-extrabold text-slate-900">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="text-center" data-label="Status">
                                @php
                                    $badgeCls = [
                                        'active' => 'badge-green',
                                        'pending' => 'badge-amber',
                                        'finished' => 'badge-gray'
                                    ][$rental->status] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $badgeCls }}">{{ strtoupper($rental->status) }}</span>
                            </td>
                            <td class="text-right" data-label="Aksi">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                    @if($rental->status == 'pending')
                                        <form action="{{ route('rentals.approve', $rental->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary !h-9 !px-4 !text-[10px] !rounded-xl">APPROVE</button>
                                        </form>
                                    @endif
                                    <a href="{{ route('rentals.show', $rental->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand transition-all" title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('rentals.edit', $rental->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand transition-all" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-file-invoice"></i>
                                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest mt-2">Belum ada kontrak sewa</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="flex justify-center pt-4">
            {{ $rentals->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>

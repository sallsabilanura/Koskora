<x-app-layout>
    @section('header_title', 'Tenants Management')

    <div class="space-y-8 animate-fade-in">
        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen Penyewa</h2>
                <p class="text-slate-500 font-medium text-sm">Kelola data penghuni, informasi kontak, dan status hunian.</p>
            </div>
            <a href="{{ route('tenants.create') }}" class="btn btn-primary">
                <i class="fas fa-user-plus text-sm"></i>
                Tambah Penyewa
            </a>
        </div>

        <div class="filter-bar">
            <form action="{{ route('tenants.index') }}" method="GET">
                <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                    <div style="position:relative; flex:1; min-width:180px;">
                        <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari nama, email, NIK, atau pekerjaan..."
                               style="padding-left:2.5rem; width:100%; margin:0;">
                    </div>
                    <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                        <i class="fas fa-search" style="font-size:0.75rem;"></i> Cari Data
                    </button>
                    @if(request('search'))
                        <a href="{{ route('tenants.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset">
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
                        <th>Identitas Penyewa</th>
                        <th>Kontak & Alamat</th>
                        <th>Pekerjaan</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($tenants as $tenant)
                        <tr class="group">
                            <td class="text-center text-slate-400 font-bold text-xs" data-label="#">{{ $loop->iteration + ($tenants->currentPage() - 1) * $tenants->perPage() }}</td>
                            <td data-label="Penyewa">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center font-black text-sm shadow-sm">
                                        {{ strtoupper(substr($tenant->user->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $tenant->user->name ?? 'Unknown' }}</div>
                                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-0.5">NIK: {{ $tenant->nik }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Kontak">
                                <div class="text-sm font-bold text-slate-700">{{ $tenant->user->email ?? '-' }}</div>
                                <div class="text-[11px] font-medium text-slate-400 truncate max-w-[200px] mt-0.5">{{ $tenant->address }}</div>
                            </td>
                            <td data-label="Pekerjaan">
                                <span class="text-xs font-bold text-slate-600 uppercase tracking-wide">{{ $tenant->occupation }}</span>
                            </td>
                            <td class="text-center" data-label="Status">
                                <span class="badge {{ $tenant->status == 'active' ? 'badge-green' : 'badge-red' }}">
                                    {{ strtoupper($tenant->status) }}
                                </span>
                            </td>
                            <td class="text-right" data-label="Aksi">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                    <a href="{{ route('tenants.show', $tenant->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand transition-all" title="Detail">
                                        <i class="fas fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('tenants.edit', $tenant->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand transition-all" title="Edit">
                                        <i class="fas fa-edit text-xs"></i>
                                    </a>
                                    <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" onclick="return confirm('Hapus data penyewa ini?')" 
                                                class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-red-500 hover:border-red-500 transition-all">
                                            <i class="fas fa-trash-alt text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-user-slash"></i>
                                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest mt-2">Data penyewa belum tersedia</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="flex justify-center pt-4">
            {{ $tenants->appends(request()->query())->links() }}
        </div>
    </div>
</x-app-layout>

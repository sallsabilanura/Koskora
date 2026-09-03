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
        <div class="table-wrap table-wrap-visible">
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
                                             
                                             <a href="{{ route('tenants.show', $tenant->id) }}" class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">
                                                 <i class="fas fa-eye w-4 text-center"></i> Detail
                                             </a>
                                             
                                             <a href="{{ route('tenants.edit', $tenant->id) }}" class="flex items-center gap-2 px-4 py-2 text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-brand transition-colors">
                                                 <i class="fas fa-edit w-4 text-center"></i> Ubah
                                             </a>
                                             
                                             <div class="h-px bg-slate-100 my-1"></div>
                                             
                                             <form action="{{ route('tenants.destroy', $tenant->id) }}" method="POST" class="block w-full">
                                                 @csrf @method('DELETE')
                                                 <button type="submit" onclick="return confirm('Hapus data penyewa ini?')" class="w-full flex items-center gap-2 px-4 py-2 text-xs font-bold text-red-600 hover:bg-red-50 transition-colors">
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

<x-app-layout>
    @section('header_title', 'Manajemen Pengumuman')

    <div class="space-y-4 md:space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Daftar Pengumuman</h2>
            <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 transition btn-touch">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Buat Pengumuman
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
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Judul & Konten</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Tipe</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($announcements as $item)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="font-bold text-slate-800 text-base mb-1">{{ $item->title }}</div>
                                <div class="text-slate-500 text-xs line-clamp-1 mb-2">{{ Str::limit($item->content, 100) }}</div>
                                <div class="flex flex-wrap gap-2 items-center">
                                    <span class="text-[10px] font-bold text-slate-400 uppercase bg-slate-100 px-2 py-0.5 rounded-lg">
                                        {{ $item->created_at->diffForHumans() }}
                                    </span>
                                    <span class="text-[10px] font-bold text-indigo-500 uppercase bg-indigo-50 px-2 py-0.5 rounded-lg border border-indigo-100">
                                        Target: {{ ucfirst($item->target_role) }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @php
                                    $colors = [
                                        'info' => 'bg-blue-100 text-blue-700',
                                        'update' => 'bg-emerald-100 text-emerald-700',
                                        'warning' => 'bg-amber-100 text-amber-700',
                                        'danger' => 'bg-rose-100 text-rose-700',
                                    ];
                                @endphp
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase {{ $colors[$item->type] ?? $colors['info'] }}">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                @if($item->is_active)
                                    <span class="inline-flex items-center text-emerald-600 font-bold text-[10px] uppercase">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mr-1.5 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-slate-400 font-bold text-[10px] uppercase">
                                        <span class="w-1.5 h-1.5 bg-slate-300 rounded-full mr-1.5"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end space-x-1">
                                    <a href="{{ route('admin.announcements.edit', $item->id) }}" class="p-2 text-slate-400 hover:text-blue-600 transition-colors">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-2 text-slate-400 hover:text-rose-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada pengumuman.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($announcements->hasPages())
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-200">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>

        <!-- Mobile Cards -->
        <div class="mobile-cards space-y-3">
            @foreach ($announcements as $item)
                <div class="mobile-card">
                    <div class="flex items-start justify-between mb-2">
                        <div class="flex-1 min-w-0">
                            <div class="font-bold text-slate-800 text-base truncate">{{ $item->title }}</div>
                            <div class="text-[10px] text-slate-400 mt-0.5">{{ $item->created_at->format('d M Y H:i') }}</div>
                        </div>
                        @php
                            $mobileColors = [
                                'info' => 'bg-blue-100 text-blue-700',
                                'update' => 'bg-emerald-100 text-emerald-700',
                                'warning' => 'bg-amber-100 text-amber-700',
                                'danger' => 'bg-rose-100 text-rose-700',
                            ];
                        @endphp
                        <span class="px-2 py-0.5 rounded-full text-[9px] font-bold uppercase {{ $mobileColors[$item->type] ?? $mobileColors['info'] }} flex-shrink-0 ml-2">
                            {{ $item->type }}
                        </span>
                    </div>
                    <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $item->content }}</p>
                    <div class="flex items-center justify-between pt-3 border-t border-slate-100">
                        <div class="flex gap-2">
                            @if($item->is_active)
                                <span class="inline-flex items-center text-emerald-600 font-bold text-[9px] uppercase">
                                    <span class="w-1 h-1 bg-emerald-500 rounded-full mr-1"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center text-slate-400 font-bold text-[9px] uppercase">
                                    <span class="w-1 h-1 bg-slate-300 rounded-full mr-1"></span>
                                    Draft
                                </span>
                            @endif
                            <span class="px-2 py-0.5 text-[9px] font-bold text-indigo-500 bg-indigo-50 rounded-lg">To: {{ ucfirst($item->target_role) }}</span>
                        </div>
                        <div class="flex gap-1">
                            <a href="{{ route('admin.announcements.edit', $item->id) }}" class="px-3 py-2 text-xs font-bold text-amber-600 bg-amber-50 rounded-lg btn-touch">Edit</a>
                            <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-3 py-2 text-xs font-bold text-rose-600 bg-rose-50 rounded-lg btn-touch">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>

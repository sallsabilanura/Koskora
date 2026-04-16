<x-app-layout>
    @section('header_title', 'Manajemen Pengumuman')

    <div class="space-y-8">
        <div class="flex items-center justify-between bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
            <div>
                <h2 class="text-2xl font-black text-slate-800 italic tracking-tight">Daftar Pengumuman</h2>
                <p class="text-slate-400 text-sm font-bold uppercase tracking-widest mt-1">Kelola informasi untuk seluruh penyewa</p>
            </div>
            <a href="{{ route('admin.announcements.create') }}" class="inline-flex items-center px-6 py-3 bg-brand-blue text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-100 transform hover:-translate-y-1 active:scale-95">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                BUAT PENGUMUMAN
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Judul & Konten</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Tipe</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Status</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($announcements as $item)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-black text-slate-800 text-lg mb-1 leading-tight">{{ $item->title }}</div>
                                <div class="text-slate-400 text-xs line-clamp-1 font-medium mb-2">{{ Str::limit($item->content, 100) }}</div>
                                <div class="flex flex-wrap gap-2 items-center">
                                    <div class="flex items-center text-[10px] font-black text-slate-300 uppercase tracking-widest bg-slate-50 px-2 py-1 rounded-lg">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        {{ $item->created_at->diffForHumans() }}
                                    </div>
                                    <div class="flex items-center text-[10px] font-black text-indigo-400 uppercase tracking-widest bg-indigo-50 px-2 py-1 rounded-lg border border-indigo-100">
                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                        Target: {{ ucfirst($item->target_role) }}
                                    </div>
                                    @if($item->expires_at)
                                        <div class="flex items-center text-[10px] font-black {{ $item->expires_at->isPast() ? 'text-rose-400 bg-rose-50' : 'text-amber-400 bg-amber-50' }} uppercase tracking-widest px-2 py-1 rounded-lg border border-current opacity-70">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $item->expires_at->isPast() ? 'Expired' : 'Sampai: ' . $item->expires_at->format('d M H:i') }}
                                        </div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @php
                                    $colors = [
                                        'info' => 'bg-blue-100 text-blue-700 border-blue-200',
                                        'update' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                                        'warning' => 'bg-amber-100 text-amber-700 border-amber-200',
                                        'danger' => 'bg-rose-100 text-rose-700 border-rose-200',
                                    ];
                                @endphp
                                <span class="px-4 py-1.5 rounded-full text-[10px] font-black uppercase tracking-widest border {{ $colors[$item->type] ?? $colors['info'] }}">
                                    {{ $item->type }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                @if($item->is_active)
                                    <span class="inline-flex items-center text-emerald-600 font-black text-[10px] uppercase tracking-widest">
                                        <span class="w-2 h-2 bg-emerald-500 rounded-full mr-2 animate-pulse"></span>
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center text-slate-400 font-black text-[10px] uppercase tracking-widest">
                                        <span class="w-2 h-2 bg-slate-300 rounded-full mr-2"></span>
                                        Draft
                                    </span>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex items-center justify-center space-x-3">
                                    <a href="{{ route('admin.announcements.edit', $item->id) }}" class="p-3 bg-slate-100 text-slate-600 rounded-xl hover:bg-brand-blue hover:text-white transition-all shadow-sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('admin.announcements.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Hapus pengumuman ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-3 bg-rose-50 text-rose-600 rounded-xl hover:bg-brand-red hover:text-white transition-all shadow-sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <div class="p-6 bg-slate-50 rounded-[2.5rem] mb-4 text-slate-200">
                                        <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </div>
                                    <h4 class="text-xl font-black text-slate-800 italic">Belum Ada Pengumuman</h4>
                                    <p class="text-slate-400 font-medium text-sm mt-1">Silakan klik tombol di atas untuk membuat pengumuman pertama Anda.</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($announcements->hasPages())
                <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
                    {{ $announcements->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

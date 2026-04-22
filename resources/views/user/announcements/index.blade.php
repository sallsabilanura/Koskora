<x-app-layout>
    @section('header_title', 'Pusat Informasi')

    <div class="space-y-6 md:space-y-8">
        <div class="p-6 md:p-10 bg-white border border-slate-200 rounded-3xl shadow-sm relative overflow-hidden group">
            <div class="relative z-10 space-y-2 md:space-y-3">
                <div class="inline-flex items-center space-x-2 bg-brand-blue/10 px-3 py-1 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-blue opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-blue"></span>
                    </span>
                    <span class="text-[10px] font-black text-brand-blue uppercase tracking-[0.2em]">Pusat Informasi</span>
                </div>
                <h2 class="text-2xl md:text-4xl font-extrabold text-slate-800 tracking-tight">Pengumuman & Berita</h2>
                <p class="text-slate-500 text-sm md:text-base font-medium max-w-2xl leading-relaxed">
                    Informasi terbaru mengenai fasilitas, peraturan, dan kegiatan di lingkungan KosKora. 
                    Pastikan Anda selalu memperbarui informasi agar tidak tertinggal berita penting.
                </p>
            </div>
            <!-- Decorative accent -->
            <div class="absolute top-0 right-0 w-1 h-full bg-brand-blue"></div>
            <div class="absolute -right-16 -bottom-16 w-32 h-32 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
            @forelse($announcements as $item)
                @php
                    $styles = [
                        'info' => [
                            'bg' => 'bg-white',
                            'border' => 'border-blue-100',
                            'icon_bg' => 'bg-blue-50',
                            'icon_color' => 'text-blue-600',
                            'badge' => 'bg-blue-50 text-blue-600 border-blue-100',
                        ],
                        'update' => [
                            'bg' => 'bg-white',
                            'border' => 'border-emerald-100',
                            'icon_bg' => 'bg-emerald-50',
                            'icon_color' => 'text-emerald-600',
                            'badge' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                        ],
                        'warning' => [
                            'bg' => 'bg-white',
                            'border' => 'border-amber-100',
                            'icon_bg' => 'bg-amber-50',
                            'icon_color' => 'text-amber-600',
                            'badge' => 'bg-amber-50 text-amber-600 border-amber-100',
                        ],
                        'danger' => [
                            'bg' => 'bg-white',
                            'border' => 'border-rose-100',
                            'icon_bg' => 'bg-rose-50',
                            'icon_color' => 'text-rose-600',
                            'badge' => 'bg-rose-50 text-rose-600 border-rose-100',
                        ],
                    ];
                    $style = $styles[$item->type] ?? $styles['info'];
                @endphp

                <div class="{{ $style['bg'] }} border border-slate-200 rounded-3xl p-6 md:p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all group flex flex-col justify-between h-full min-h-[300px]">
                    <div>
                        <div class="flex items-center justify-between mb-6">
                            <div class="{{ $style['icon_bg'] }} {{ $style['icon_color'] }} p-3 rounded-xl group-hover:scale-110 transition-transform">
                                @if($item->type == 'info')
                                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($item->type == 'update')
                                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                @else
                                    <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                @endif
                            </div>
                            <span class="px-3 py-1 {{ $style['badge'] }} text-[9px] font-black uppercase tracking-widest rounded-full border">
                                {{ $item->type }}
                            </span>
                        </div>

                        <h3 class="text-xl md:text-2xl font-extrabold text-slate-800 leading-tight mb-3 group-hover:text-brand-blue transition-colors">{{ $item->title }}</h3>
                        <p class="text-slate-500 font-medium leading-relaxed line-clamp-4 text-sm">
                            {{ $item->content }}
                        </p>
                    </div>

                    <div class="mt-6 pt-6 border-t border-slate-100 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 md:w-10 md:h-10 bg-slate-100 rounded-xl flex items-center justify-center text-[10px] md:text-xs font-black text-slate-400">
                                {{ strtoupper(substr($item->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest leading-none mb-1">Penerbit</div>
                                <div class="text-[10px] md:text-xs font-bold text-slate-600">{{ $item->user->name }}</div>
                            </div>
                        </div>
                        <div class="text-[9px] md:text-[10px] font-bold text-slate-300">
                            {{ $item->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center bg-white border border-slate-200 border-dashed rounded-3xl">
                    <svg class="w-16 h-16 text-slate-200 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    <h4 class="text-xl font-extrabold text-slate-800">Belum Ada Pengumuman Baru</h4>
                    <p class="text-slate-400 font-medium mt-1">Semua informasi penting akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        @if($announcements->hasPages())
            <div class="pt-8">
                {{ $announcements->links() }}
            </div>
        @endif
    </div>
</x-app-layout>

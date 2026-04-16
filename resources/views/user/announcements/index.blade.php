<x-app-layout>
    @section('header_title', 'Pusat Informasi')

    <div class="space-y-8">
        <div class="p-10 bg-white rounded-[3rem] border border-slate-100 shadow-2xl relative overflow-hidden">
             <!-- Decorative background -->
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-blue-50 rounded-full opacity-50"></div>
            <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-50 rounded-full opacity-50"></div>

            <div class="relative z-10">
                <h2 class="text-4xl font-black text-slate-800 italic tracking-tight mb-4">Pengumuman & Berita</h2>
                <p class="text-slate-400 text-lg font-medium max-w-2xl leading-relaxed">
                    Informasi terbaru mengenai fasilitas, peraturan, dan kegiatan di lingkungan KosKora. 
                    Pastikan Anda selalu memperbarui informasi agar tidak tertinggal berita penting.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($announcements as $item)
                @php
                    $styles = [
                        'info' => [
                            'bg' => 'bg-white',
                            'border' => 'border-blue-100',
                            'icon_bg' => 'bg-blue-100',
                            'icon_color' => 'text-blue-600',
                            'badge' => 'bg-blue-50 text-blue-600 border-blue-100',
                            'shadow' => 'shadow-blue-50',
                        ],
                        'update' => [
                            'bg' => 'bg-white',
                            'border' => 'border-emerald-100',
                            'icon_bg' => 'bg-emerald-100',
                            'icon_color' => 'text-emerald-600',
                            'badge' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            'shadow' => 'shadow-emerald-50',
                        ],
                        'warning' => [
                            'bg' => 'bg-amber-50/30',
                            'border' => 'border-amber-100',
                            'icon_bg' => 'bg-amber-100',
                            'icon_color' => 'text-amber-600',
                            'badge' => 'bg-amber-50 text-amber-600 border-amber-100',
                            'shadow' => 'shadow-amber-50',
                        ],
                        'danger' => [
                            'bg' => 'bg-rose-50/30',
                            'border' => 'border-rose-100',
                            'icon_bg' => 'bg-rose-100',
                            'icon_color' => 'text-rose-600',
                            'badge' => 'bg-rose-50 text-rose-600 border-rose-100',
                            'shadow' => 'shadow-rose-50',
                        ],
                    ];
                    $style = $styles[$item->type] ?? $styles['info'];
                @endphp

                <div class="{{ $style['bg'] }} border {{ $style['border'] }} rounded-[2.5rem] p-8 shadow-xl {{ $style['shadow'] }} hover:-translate-y-2 transition-all group flex flex-col justify-between h-full min-h-[350px]">
                    <div>
                        <div class="flex items-center justify-between mb-8">
                            <div class="{{ $style['icon_bg'] }} {{ $style['icon_color'] }} p-4 rounded-2xl group-hover:scale-110 transition-transform">
                                @if($item->type == 'info')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @elseif($item->type == 'update')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                @endif
                            </div>
                            <span class="px-4 py-1.5 {{ $style['badge'] }} text-[10px] font-black uppercase tracking-widest rounded-full border">
                                {{ $item->type }}
                            </span>
                        </div>

                        <h3 class="text-2xl font-black text-slate-800 leading-tight mb-4">{{ $item->title }}</h3>
                        <p class="text-slate-500 font-medium leading-relaxed line-clamp-4">
                            {{ $item->content }}
                        </p>
                    </div>

                    <div class="mt-8 pt-6 border-t border-slate-50 flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 bg-slate-100 rounded-full flex items-center justify-center text-[10px] font-black text-slate-400">
                                {{ substr($item->user->name, 0, 1) }}
                            </div>
                            <div>
                                <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Diterbitkan Oleh</div>
                                <div class="text-[11px] font-bold text-slate-600">{{ $item->user->name }}</div>
                            </div>
                        </div>
                        <div class="text-[10px] font-bold text-slate-300">
                            {{ $item->created_at->format('d M Y') }}
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center">
                    <div class="p-8 bg-white border-2 border-dashed border-slate-100 rounded-[3rem] inline-block mb-6">
                        <svg class="w-16 h-16 text-slate-200 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                    </div>
                    <h4 class="text-2xl font-black text-slate-800 italic">Belum Ada Pengumuman Baru</h4>
                    <p class="text-slate-400 font-medium mt-2">Semua informasi penting akan muncul di sini.</p>
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

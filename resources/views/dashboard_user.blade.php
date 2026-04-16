<x-app-layout>
    @section('header_title', 'Dashboard')

    <div class="space-y-6 md:space-y-8">
        @if ($message = Session::get('success'))
            <div class="p-3 md:p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm text-sm">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="p-6 md:p-10 bg-gradient-to-br from-blue-700 to-indigo-800 rounded-2xl md:rounded-[2.5rem] text-white shadow-2xl shadow-blue-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 md:gap-10 overflow-hidden relative">
            <div class="relative z-10 space-y-3 md:space-y-4">
                <h3 class="text-xl md:text-3xl font-black italic tracking-tight">Selamat Datang, {{ auth()->user()->name }}!</h3>
                <p class="text-blue-100 text-sm md:text-lg max-w-md leading-relaxed opacity-90">
                    @if($activeRental)
                        Senang melihat Anda kembali di unit <strong>No. {{ $activeRental->room->room_number }}</strong>. Semoga hari Anda menyenangkan.
                    @else
                        Anda belum memiliki data sewa aktif. Silakan cari kamar impian Anda bersama KosKora.
                    @endif
                </p>
            </div>
            @if(!$activeRental)
                <a href="{{ url('/') }}" class="relative z-10 px-6 md:px-10 py-3 md:py-4 bg-white text-blue-600 rounded-xl md:rounded-2xl font-black text-sm md:text-lg hover:bg-rose-50 hover:text-rose-600 transition-all shadow-xl hover:-translate-y-1 active:scale-95 w-full md:w-auto text-center btn-touch">
                    Cari Kamar
                </a>
            @endif
            <!-- Decorative Circles -->
            <div class="absolute -right-10 md:-right-20 -bottom-10 md:-bottom-20 w-40 md:w-80 h-40 md:h-80 bg-blue-500 rounded-full opacity-20"></div>
            <div class="absolute -right-5 md:-right-10 -bottom-5 md:-bottom-10 w-20 md:w-40 h-20 md:h-40 bg-rose-500 rounded-full opacity-10"></div>
        </div>

        <!-- Announcements -->
        <div class="space-y-4 md:space-y-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3 text-slate-400">
                    <div class="w-1.5 h-6 bg-brand-blue rounded-full"></div>
                    <h3 class="text-lg md:text-xl font-black text-slate-800 tracking-tight">Pengumuman Terbaru</h3>
                </div>
                <a href="{{ route('user.announcements.index') }}" class="text-[10px] md:text-xs font-black text-brand-blue uppercase tracking-widest hover:text-blue-700">Semua</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
                @forelse($announcements as $announce)
                    <div class="bg-white border border-slate-100 rounded-2xl md:rounded-[2rem] p-4 md:p-6 shadow-xl shadow-slate-100/50 hover:-translate-y-1 transition-all group">
                        <div class="flex items-center justify-between mb-3 md:mb-4">
                            @php
                                $typeColors = [
                                    'info' => 'bg-blue-50 text-blue-600',
                                    'update' => 'bg-emerald-50 text-emerald-600',
                                    'warning' => 'bg-amber-50 text-amber-600',
                                    'danger' => 'bg-rose-50 text-rose-600',
                                ];
                            @endphp
                            <span class="px-2.5 py-1 {{ $typeColors[$announce->type] ?? $typeColors['info'] }} text-[8px] font-black uppercase tracking-widest rounded-full">
                                {{ $announce->type }}
                            </span>
                            <span class="text-[8px] font-bold text-slate-300">{{ $announce->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="font-black text-slate-800 leading-tight mb-2 group-hover:text-brand-blue transition-colors text-sm md:text-base">{{ Str::limit($announce->title, 40) }}</h4>
                        <p class="text-[11px] text-slate-500 line-clamp-2 font-medium leading-relaxed">{{ Str::limit($announce->content, 80) }}</p>
                    </div>
                @empty
                    <div class="col-span-full bg-slate-50 border-2 border-dashed border-slate-100 rounded-2xl md:rounded-[2rem] p-8 md:p-10 text-center">
                        <p class="text-slate-400 font-bold text-sm italic">Belum ada pengumuman terbaru.</p>
                    </div>
                @endforelse
            </div>
        </div>

        @if($activeRental)
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                <!-- Notifications / Alerts -->
                <div class="lg:col-span-2 space-y-6 md:space-y-8">
                    <div class="flex items-center space-x-3 text-slate-400">
                        <div class="w-1.5 h-6 bg-slate-300 rounded-full"></div>
                        <h3 class="text-lg md:text-xl font-bold text-slate-800 tracking-tight">Status Sewa & Pembayaran</h3>
                    </div>

                    @if($currentPaymentStatus !== 'paid')
                        <div class="{{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-50 border-amber-200' : 'bg-blue-50 border-blue-200' }} border-2 rounded-2xl md:rounded-3xl p-5 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-6">
                            <div class="flex items-center space-x-4 md:space-x-6">
                                <div class="{{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }} p-3 md:p-4 rounded-xl md:rounded-2xl flex-shrink-0">
                                    <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-base md:text-lg font-black {{ $currentPaymentStatus == 'unpaid' ? 'text-amber-800' : 'text-blue-800' }}">
                                        {{ $currentPaymentStatus == 'unpaid' ? 'Tagihan Menanti' : 'Sedang Diverifikasi' }}
                                    </h4>
                                    <p class="text-xs md:text-sm font-medium {{ $currentPaymentStatus == 'unpaid' ? 'text-amber-600' : 'text-blue-600' }}">
                                        {{ $currentPaymentStatus == 'unpaid' ? 'Silakan lakukan pembayaran untuk bulan ini.' : 'Admin sedang meninjau bukti pembayaran Anda.' }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('rent-payments.my-payments') }}" class="px-5 md:px-6 py-2.5 {{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-600 hover:bg-amber-700 shadow-amber-100' : 'bg-blue-600 hover:bg-blue-700 shadow-blue-100' }} text-white rounded-xl font-bold text-xs shadow-lg transition-all w-full md:w-auto text-center btn-touch">
                                Lihat Detail
                            </a>
                        </div>
                    @else
                        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-2xl md:rounded-3xl p-5 md:p-8 flex items-center space-x-4 md:space-x-6">
                            <div class="bg-emerald-100 text-emerald-600 p-3 md:p-4 rounded-xl md:rounded-2xl flex-shrink-0">
                                <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base md:text-lg font-black text-emerald-800">Semua Beres!</h4>
                                <p class="text-xs md:text-sm font-medium text-emerald-600">Terima kasih telah melakukan pembayaran tepat waktu.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Active Unit Summary Card -->
                    <div class="bg-white rounded-2xl md:rounded-3xl border border-slate-200 p-5 md:p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-6 md:mb-8">
                            <div class="flex items-center space-x-3 md:space-x-4">
                                <div class="w-11 h-11 md:w-14 md:h-14 bg-blue-100 text-blue-600 rounded-xl md:rounded-2xl flex items-center justify-center text-lg md:text-xl font-black flex-shrink-0">
                                    {{ $activeRental->room->room_number }}
                                </div>
                                <div class="min-w-0">
                                    <div class="text-lg md:text-xl font-black text-slate-800 truncate">{{ $activeRental->room->room_type }}</div>
                                    <div class="text-xs md:text-sm font-bold text-slate-400 italic">Mulai Sewa: {{ \Carbon\Carbon::parse($activeRental->start_date)->format('d M Y') }}</div>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 text-[9px] md:text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-200 flex-shrink-0">
                                Aktif
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-3 md:gap-4">
                            <div class="p-3 md:p-4 bg-slate-50 rounded-xl md:rounded-2xl">
                                <div class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Durasi Sewa</div>
                                <div class="text-sm font-black text-slate-700">{{ $activeRental->duration }} Bulan</div>
                            </div>
                            <div class="p-3 md:p-4 bg-slate-50 rounded-xl md:rounded-2xl">
                                <div class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Biaya</div>
                                <div class="text-sm font-black text-slate-700">Rp {{ number_format($activeRental->total_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Sidebar / Quick Links -->
                <div class="space-y-6 md:space-y-8">
                    <div class="flex items-center space-x-3 text-slate-400">
                        <div class="w-1.5 h-6 bg-slate-300 rounded-full"></div>
                        <h3 class="text-lg md:text-xl font-bold text-slate-800 tracking-tight">Pusat Bantuan</h3>
                    </div>
                    
                    <div class="bg-slate-900 rounded-2xl md:rounded-[2.5rem] p-6 md:p-8 text-white shadow-xl">
                        <h4 class="text-base md:text-lg font-black mb-4 italic tracking-tight">Kontak Pengelola</h4>
                        <div class="space-y-4">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 2.01c-5.52 0-9.99 4.47-9.99 9.99 0 2.01.59 3.87 1.61 5.44L2.04 22l4.74-1.55c1.5.87 3.24 1.38 5.11 1.38 5.52 0 10.03-4.51 10.03-10.03 0-5.53-4.51-9.99-10.03-9.99z"/></svg>
                                </div>
                                <div class="text-sm font-bold">+62 812-3456-7890</div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="text-sm font-bold">cs@koskora.com</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>

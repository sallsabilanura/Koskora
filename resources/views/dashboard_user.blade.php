<x-app-layout>
    @section('header_title', 'Dashboard')

    <div class="space-y-6 md:space-y-8">
        @if ($message = Session::get('success'))
            <div class="p-3 md:p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center shadow-sm text-sm">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <!-- Welcome Banner -->
        <div class="p-6 md:p-10 bg-white border border-slate-200 rounded-3xl shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-6 md:gap-10 overflow-hidden relative group">
            <div class="relative z-10 space-y-2 md:space-y-3">
                <div class="inline-flex items-center space-x-2 bg-brand-blue/10 px-3 py-1 rounded-full">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand-blue opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-brand-blue"></span>
                    </span>
                    <span class="text-[10px] font-black text-brand-blue uppercase tracking-[0.2em]">Member Ecosystem</span>
                </div>
                <h3 class="text-2xl md:text-4xl font-extrabold text-slate-800 tracking-tight">Selamat Datang, <span class="text-brand-blue">{{ auth()->user()->name }}</span>!</h3>
                <p class="text-slate-500 text-sm md:text-base max-w-md leading-relaxed font-medium">
                    @if($activeRental)
                        Senang melihat Anda kembali di unit <span class="font-bold text-slate-700">No. {{ $activeRental->room->room_number }}</span>. Beristirahatlah dengan nyaman.
                    @else
                        Anda belum memiliki data sewa aktif. Silakan cari kamar impian Anda bersama KosKora.
                    @endif
                </p>
            </div>
            @if(!$activeRental)
                <a href="{{ url('/') }}" class="relative z-10 px-6 md:px-10 py-3 md:py-4 bg-brand-navy text-white rounded-xl md:rounded-2xl font-black text-sm md:text-base hover:bg-brand-red transition-all shadow-xl hover:-translate-y-1 active:scale-95 w-full md:w-auto text-center btn-touch">
                    Mulai Jelajahi Kamar
                </a>
            @endif
            <!-- Decorative Accent -->
            <div class="absolute top-0 right-0 w-1 h-full bg-brand-blue"></div>
            <div class="absolute -right-16 -bottom-16 w-32 h-32 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-700"></div>
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
                    <div class="bg-white border border-slate-100 rounded-3xl p-4 md:p-6 shadow-xl shadow-slate-100/50 hover:-translate-y-1 transition-all group">
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
                        <h4 class="font-bold text-slate-800 leading-tight mb-2 group-hover:text-brand-blue transition-colors text-sm md:text-base">{{ Str::limit($announce->title, 40) }}</h4>
                        <p class="text-[11px] text-slate-500 line-clamp-2 font-medium leading-relaxed opacity-80">{{ Str::limit($announce->content, 80) }}</p>
                    </div>
                @empty
                    <div class="col-span-full bg-slate-50 border-2 border-dashed border-slate-100 rounded-3xl p-8 md:p-10 text-center">
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
                        <div class="{{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-50 border-amber-200' : 'bg-blue-50 border-blue-200' }} border-2 rounded-3xl p-5 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 md:gap-6">
                            <div class="flex items-center space-x-4 md:space-x-6">
                                <div class="{{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }} p-3 md:p-4 rounded-xl flex-shrink-0">
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
                        <div class="bg-emerald-50 border-2 border-emerald-200 rounded-3xl p-5 md:p-8 flex items-center space-x-4 md:space-x-6">
                            <div class="bg-emerald-100 text-emerald-600 p-3 md:p-4 rounded-xl flex-shrink-0">
                                <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-base md:text-lg font-black text-emerald-800">Semua Beres!</h4>
                                <p class="text-xs md:text-sm font-medium text-emerald-600">Terima kasih telah melakukan pembayaran tepat waktu.</p>
                            </div>
                        </div>
                    @endif

                    <!-- Active Unit Summary Card -->
                    <div class="bg-white rounded-3xl border border-slate-200 p-5 md:p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-6 md:mb-8">
                            <div class="flex items-center space-x-3 md:space-x-4">
                                <div class="w-11 h-11 md:w-14 md:h-14 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-lg md:text-xl font-black flex-shrink-0">
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
                            <div class="p-3 md:p-4 bg-slate-50 rounded-xl">
                                <div class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Status Masa Sewa</div>
                                <div class="text-sm font-black {{ \Carbon\Carbon::parse($activeRental->end_date)->isFuture() ? 'text-amber-600' : 'text-slate-700' }}">
                                    Berakhir {{ \Carbon\Carbon::parse($activeRental->end_date)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="p-3 md:p-4 bg-slate-50 rounded-xl flex flex-col justify-center items-center text-center group cursor-pointer hover:bg-brand-blue/5 transition-colors" onclick="toggleMoveOutModal()">
                                <div class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Rencana Keluar?</div>
                                <div class="text-[10px] font-black text-brand-blue uppercase tracking-widest group-hover:scale-105 transition-transform">Atur Tanggal</div>
                            </div>
                        </div>

                        {{-- Review CTA --}}
                        <div class="mt-4">
                            <button onclick="toggleReviewModal()" class="w-full py-3 bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200 text-amber-700 rounded-2xl font-black text-xs uppercase tracking-widest hover:from-amber-100 hover:to-orange-100 transition-all flex items-center justify-center gap-2 group">
                                <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                Beri Ulasan Kamar
                            </button>
                        </div>

                        <!-- Termination Info Badge if scheduled -->
                        @if(\Carbon\Carbon::parse($activeRental->end_date)->diffInDays(now()) < 60)
                             <div class="mt-4 p-3 bg-amber-50 rounded-xl border border-amber-100 flex items-center space-x-3">
                                <svg class="w-4 h-4 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Masa sewa dijadwalkan berakhir pada {{ \Carbon\Carbon::parse($activeRental->end_date)->translatedFormat('d F Y') }}</span>
                             </div>
                        @endif
                    </div>
                </div>

                <!-- Right Sidebar / Quick Links -->
                <div class="space-y-6 md:space-y-8">
                    <div class="flex items-center space-x-3 text-slate-400">
                        <div class="w-1.5 h-6 bg-slate-300 rounded-full"></div>
                        <h3 class="text-lg md:text-xl font-bold text-slate-800 tracking-tight">Pusat Bantuan</h3>
                    </div>
                    
                    <div class="bg-slate-900 rounded-3xl p-6 md:p-8 text-white shadow-xl relative overflow-hidden group/help">
                        <h4 class="text-base md:text-lg font-black mb-4 tracking-tight">Kontak Pengelola</h4>
                        <div class="space-y-4 relative z-10">
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover/help:bg-emerald-500/20 transition-colors">
                                    <svg class="w-4 h-4 text-emerald-400" fill="currentColor" viewBox="0 0 24 24"><path d="M12.01 2.01c-5.52 0-9.99 4.47-9.99 9.99 0 2.01.59 3.87 1.61 5.44L2.04 22l4.74-1.55c1.5.87 3.24 1.38 5.11 1.38 5.52 0 10.03-4.51 10.03-10.03 0-5.53-4.51-9.99-10.03-9.99z"/></svg>
                                </div>
                                <div class="text-sm font-bold">+62 812-3456-7890</div>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-8 h-8 bg-white/10 rounded-lg flex items-center justify-center flex-shrink-0 group-hover/help:bg-blue-500/20 transition-colors">
                                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                </div>
                                <div class="text-sm font-bold">cs@koskora.com</div>
                            </div>
                        </div>
                        <!-- Decorative glow -->
                        <div class="absolute -top-10 -right-10 w-24 h-24 bg-brand-blue opacity-20 blur-3xl"></div>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Move Out Request Modal -->
    <div id="moveOutModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl animate-modal-up">
            <div class="p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-amber-500 rounded-full"></div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase">Rencana Keluar</h3>
                    </div>
                    <button onclick="toggleMoveOutModal()" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                    <p class="text-xs font-bold text-amber-700 leading-relaxed uppercase tracking-wider">
                        Apakah Anda berencana pindah? Silakan tentukan tanggal berakhir sewa Anda di bawah ini untuk memberi tahu pengelola.
                    </p>
                </div>

                @if($activeRental)
                <form action="{{ route('rentals.request-termination', $activeRental->id) }}" method="POST" class="space-y-6">
                    @csrf
                    <div class="space-y-2">
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Tanggal Keluar</label>
                        <input type="date" name="end_date" value="{{ $activeRental->end_date }}"
                            min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-brand-blue focus:border-brand-blue transition-all"
                            required>
                    </div>

                    <button type="submit" class="w-full py-4 bg-brand-navy text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-brand-blue transition-all shadow-lg active:scale-95">
                        Simpan Rencana Keluar
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>

    <!-- Room Review Modal -->
    @if($activeRental)
    <div id="reviewModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all duration-300">
        <div class="bg-white rounded-[2.5rem] w-full max-w-md overflow-hidden shadow-2xl animate-modal-up">
            <div class="p-8 space-y-6">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-amber-400 rounded-full"></div>
                        <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase">Ulasan Kamar</h3>
                    </div>
                    <button onclick="toggleReviewModal()" class="p-2 hover:bg-slate-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <div class="flex items-center gap-3 p-4 bg-amber-50 border border-amber-100 rounded-2xl">
                    <svg class="w-5 h-5 text-amber-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                    <p class="text-xs font-bold text-amber-700">Kamar {{ $activeRental->room->room_number }} — {{ $activeRental->room->property_name ?? $activeRental->room->room_type }}</p>
                </div>

                <form action="{{ route('room-reviews.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $activeRental->room->id }}">

                    <!-- Star Rating -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Rating Bintang</label>
                        <div class="flex items-center gap-2" id="modal-star-group">
                            @for($i = 1; $i <= 5; $i++)
                            <label class="cursor-pointer modal-star-label">
                                <input type="radio" name="rating" value="{{ $i }}" class="hidden" required>
                                <svg class="w-9 h-9 transition-all duration-150 text-slate-200 hover:text-amber-400 modal-star-svg" fill="currentColor" viewBox="0 0 20 20" data-val="{{ $i }}"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                            </label>
                            @endfor
                        </div>
                    </div>

                    <!-- Comment -->
                    <div>
                        <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-3">Komentar (Opsional)</label>
                        <textarea name="comment" rows="3" class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-medium text-slate-700 py-3 px-4 focus:ring-brand-blue focus:border-brand-blue transition-all resize-none" placeholder="Ceritakan pengalaman menghuni kamar ini..."></textarea>
                    </div>

                    <!-- Anonymous Toggle -->
                    <div class="flex items-center gap-3">
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="is_anonymous" value="1" class="sr-only peer">
                            <div class="w-10 h-6 bg-slate-200 rounded-full peer peer-checked:after:translate-x-full after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-brand-blue"></div>
                        </label>
                        <span class="text-sm font-bold text-slate-600">Kirim sebagai anonim</span>
                    </div>

                    <button type="submit" class="w-full py-4 bg-amber-500 hover:bg-amber-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest transition-all shadow-lg shadow-amber-100 active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                        Kirim Ulasan
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endif

    <script>
        function toggleMoveOutModal() {
            const modal = document.getElementById('moveOutModal');
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        function toggleReviewModal() {
            const modal = document.getElementById('reviewModal');
            if (!modal) return;
            if (modal.classList.contains('hidden')) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            } else {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Interactive stars in review modal
        const modalStars = document.querySelectorAll('.modal-star-svg');
        const modalRadios = document.querySelectorAll('#reviewModal input[name="rating"]');
        modalStars.forEach((star, idx) => {
            star.addEventListener('click', () => {
                modalRadios[idx].checked = true;
                modalStars.forEach((s, sIdx) => {
                    s.classList.toggle('text-amber-400', sIdx <= idx);
                    s.classList.toggle('text-slate-200', sIdx > idx);
                });
            });
            star.addEventListener('mouseenter', () => {
                modalStars.forEach((s, sIdx) => {
                    s.classList.toggle('text-amber-300', sIdx <= idx);
                });
            });
            star.addEventListener('mouseleave', () => {
                const checked = Array.from(modalRadios).findIndex(r => r.checked);
                modalStars.forEach((s, sIdx) => {
                    s.classList.remove('text-amber-300');
                    s.classList.toggle('text-amber-400', checked >= 0 && sIdx <= checked);
                    s.classList.toggle('text-slate-200', checked < 0 || sIdx > checked);
                });
            });
        });
    </script>

    <style>
        @keyframes modal-up {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-modal-up {
            animation: modal-up 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }
    </style>
</x-app-layout>

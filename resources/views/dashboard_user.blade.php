<x-app-layout>
    @section('header_title', 'Dashboard')

    <div class="user-dash animate-fade-in">
        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm text-sm mb-6">
                <i class="fas fa-check-circle mr-3 text-lg"></i>
                <span class="font-semibold">{{ $message }}</span>
            </div>
        @endif

        {{-- ===== MOBILE HEADER (hidden on desktop) ===== --}}
        <div class="ud-mobile-header lg:hidden">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">
                        {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('l, d F Y') }}
                    </p>
                    <h2 class="text-2xl font-extrabold text-slate-900 mt-1">
                        Hi, {{ explode(' ', auth()->user()->name)[0] }}! 👋
                    </h2>
                </div>
                <div class="flex items-center gap-2">
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" onclick="return confirm('Keluar dari aplikasi?')" class="w-10 h-10 rounded-2xl bg-red-50 text-red-500 flex items-center justify-center font-bold text-sm shadow-sm border border-red-100 hover:bg-red-100 transition-colors" title="Keluar">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                    <a href="{{ route('profile.edit') }}" class="w-12 h-12 rounded-2xl bg-brand text-white flex items-center justify-center font-bold text-sm shadow-lg shadow-brand/20">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </a>
                </div>
            </div>
        </div>

        {{-- ===== DESKTOP WELCOME (hidden on mobile) ===== --}}
        <div class="hidden lg:block mb-8">
            <div class="p-8 md:p-10 bg-white border border-slate-200 rounded-[28px] shadow-sm flex items-center justify-between gap-8 overflow-hidden relative group">
                <div class="relative z-10 space-y-3">
                    <div class="inline-flex items-center space-x-2 bg-brand/10 px-4 py-1.5 rounded-full">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-brand"></span>
                        </span>
                        <span class="text-[10px] font-bold text-brand uppercase tracking-widest">Premium Member</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-extrabold text-slate-800 tracking-tight leading-tight">
                        Selamat Datang, <span class="text-brand">{{ auth()->user()->name }}</span>!
                    </h3>
                    <p class="text-slate-500 text-sm max-w-xl leading-relaxed font-medium">
                        @if($activeRental)
                            Anda menghuni unit <span class="font-bold text-slate-700">No. {{ $activeRental->room->room_number }}</span>. Nikmati waktu istirahat Anda bersama KosKora.
                        @else
                            Anda belum memiliki data sewa aktif. Mari temukan hunian ternyaman untuk produktivitas Anda.
                        @endif
                    </p>
                </div>
                @if(!$activeRental)
                    <a href="{{ url('/') }}" class="relative z-10 btn btn-primary !px-8 !py-4 !text-sm shrink-0">
                        <i class="fas fa-search"></i> Cari Kamar
                    </a>
                @endif
                <div class="absolute -right-20 -bottom-20 w-56 h-56 bg-slate-50 rounded-full group-hover:scale-110 transition-transform duration-1000"></div>
            </div>
        </div>

        {{-- ===== MOBILE WELCOME CARD ===== --}}
        <div class="lg:hidden mb-6">
            <div class="ud-welcome-card p-5 rounded-[20px] relative overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center gap-2 mb-2">
                        <span class="relative flex h-2 w-2">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-white"></span>
                        </span>
                        <span class="text-[9px] font-bold text-white/80 uppercase tracking-widest">Premium Member</span>
                    </div>
                    <p class="text-white text-sm font-semibold leading-relaxed opacity-95">
                        @if($activeRental)
                            Anda menghuni unit <span class="font-bold">No. {{ $activeRental->room->room_number }}</span>. Semoga nyaman! 🏠
                        @else
                            Belum ada sewa aktif. Yuk cari hunian terbaik!
                        @endif
                    </p>
                    @if(!$activeRental)
                        <a href="{{ url('/') }}" class="mt-3 inline-flex items-center gap-2 px-5 py-2.5 bg-white text-brand rounded-xl text-xs font-bold shadow-lg">
                            <i class="fas fa-search"></i> Cari Kamar
                        </a>
                    @endif
                </div>
                <div class="absolute -right-6 -bottom-6 w-28 h-28 bg-white/10 rounded-full"></div>
                <div class="absolute right-10 -top-4 w-16 h-16 bg-white/5 rounded-full"></div>
            </div>
        </div>

        {{-- ===== QUICK ACTIONS (Mobile Grid) ===== --}}
        <div class="lg:hidden mb-6">
            <div class="grid grid-cols-4 gap-3">
                <a href="{{ route('rent-payments.my-payments') }}" class="ud-quick-action">
                    <div class="ud-qa-icon bg-rose-50 text-rose-500"><i class="fas fa-credit-card"></i></div>
                    <span class="ud-qa-label">Tagihan</span>
                </a>
                <a href="{{ route('user.laundry.index') }}" class="ud-quick-action">
                    <div class="ud-qa-icon bg-blue-50 text-blue-500"><i class="fas fa-soap"></i></div>
                    <span class="ud-qa-label">Laundry</span>
                </a>
                <a href="{{ route('user.cleaning.index') }}" class="ud-quick-action">
                    <div class="ud-qa-icon bg-emerald-50 text-emerald-500"><i class="fas fa-broom"></i></div>
                    <span class="ud-qa-label">Cleaning</span>
                </a>
                <a href="{{ route('user.announcements.index') }}" class="ud-quick-action">
                    <div class="ud-qa-icon bg-amber-50 text-amber-500"><i class="fas fa-bullhorn"></i></div>
                    <span class="ud-qa-label">Info</span>
                </a>
            </div>
        </div>

        @if($activeRental)
            {{-- ===== PAYMENT STATUS ALERT ===== --}}
            @if($currentPaymentStatus !== 'paid')
                <div class="ud-alert-card mb-6 p-4 md:p-6 rounded-[20px] md:rounded-[28px] border-2 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 {{ $currentPaymentStatus == 'unpaid' ? 'bg-rose-50 border-rose-100' : 'bg-brand-light border-brand/10' }}">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl flex items-center justify-center text-xl {{ $currentPaymentStatus == 'unpaid' ? 'bg-rose-100 text-rose-600' : 'bg-brand/10 text-brand' }}">
                            <i class="fas {{ $currentPaymentStatus == 'unpaid' ? 'fa-exclamation-triangle' : 'fa-clock-rotate-left' }}"></i>
                        </div>
                        <div>
                            <h4 class="text-sm md:text-base font-extrabold {{ $currentPaymentStatus == 'unpaid' ? 'text-rose-900' : 'text-slate-900' }}">
                                {{ $currentPaymentStatus == 'unpaid' ? 'Tagihan Menanti' : 'Verifikasi Pembayaran' }}
                            </h4>
                            <p class="text-xs font-medium {{ $currentPaymentStatus == 'unpaid' ? 'text-rose-600' : 'text-slate-500' }} hidden sm:block">
                                {{ $currentPaymentStatus == 'unpaid' ? 'Segera lakukan pembayaran.' : 'Bukti pembayaran sedang diproses.' }}
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('rent-payments.my-payments') }}" class="px-5 py-2.5 rounded-xl font-bold text-xs shadow-md transition-all w-full sm:w-auto text-center {{ $currentPaymentStatus == 'unpaid' ? 'bg-rose-600 text-white' : 'bg-brand text-white' }}">
                        Detail Tagihan
                    </a>
                </div>
            @else
                <div class="mb-6 p-4 md:p-6 bg-emerald-50 border-2 border-emerald-100 rounded-[20px] md:rounded-[28px] flex items-center gap-4">
                    <div class="w-12 h-12 bg-emerald-100 text-emerald-600 rounded-2xl flex items-center justify-center text-xl">
                        <i class="fas fa-check-double"></i>
                    </div>
                    <div>
                        <h4 class="text-sm font-extrabold text-emerald-900">Pembayaran Lunas</h4>
                        <p class="text-xs font-medium text-emerald-600">Terima kasih! Nikmati fasilitas KosKora.</p>
                    </div>
                </div>
            @endif

            {{-- ===== ACTIVE UNIT CARD ===== --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-[20px] md:rounded-[28px] border border-slate-200 p-5 md:p-8 shadow-sm">
                        <div class="flex items-center justify-between mb-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 md:w-14 md:h-14 bg-brand/10 text-brand rounded-2xl flex items-center justify-center text-lg md:text-xl font-extrabold">
                                    {{ $activeRental->room->room_number }}
                                </div>
                                <div>
                                    <div class="text-base md:text-lg font-extrabold text-slate-800 leading-tight">{{ $activeRental->room->room_type }}</div>
                                    <div class="text-[10px] font-bold text-slate-400 mt-0.5 uppercase tracking-widest">Sejak {{ \Carbon\Carbon::parse($activeRental->start_date)->format('d M Y') }}</div>
                                </div>
                            </div>
                            <span class="badge badge-success px-3 py-1">Aktif</span>
                        </div>

                        <div class="grid grid-cols-2 gap-3 md:gap-4">
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Masa Sewa</div>
                                <div class="text-xs md:text-sm font-bold text-slate-700">
                                    s.d. {{ \Carbon\Carbon::parse($activeRental->end_date)->format('d M Y') }}
                                </div>
                            </div>
                            <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 cursor-pointer hover:bg-brand/5 transition-colors" onclick="toggleMoveOutModal()">
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Perubahan?</div>
                                <div class="text-xs font-bold text-brand uppercase tracking-wider">Atur Keluar</div>
                            </div>
                        </div>

                        <button onclick="toggleReviewModal()" class="w-full mt-4 py-3.5 bg-amber-50 border border-amber-100 text-amber-700 rounded-2xl font-bold text-[11px] uppercase tracking-widest hover:bg-amber-100 transition-all flex items-center justify-center gap-2 group">
                            <i class="fas fa-star text-amber-400 group-hover:scale-110 transition-transform"></i>
                            Beri Ulasan Kamar
                        </button>
                    </div>
                </div>

                {{-- SUPPORT SIDEBAR (Desktop) --}}
                <div class="hidden lg:block space-y-6">
                    <div class="bg-slate-900 rounded-[28px] p-8 text-white shadow-xl relative overflow-hidden group">
                        <div class="relative z-10">
                            <h4 class="text-lg font-extrabold mb-5 tracking-tight">Kontak Pengelola</h4>
                            <div class="space-y-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center">
                                        <i class="fab fa-whatsapp text-emerald-400"></i>
                                    </div>
                                    <span class="text-sm font-bold opacity-90">+62 812-3456-7890</span>
                                </div>
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 bg-white/10 rounded-xl flex items-center justify-center">
                                        <i class="far fa-envelope text-blue-400"></i>
                                    </div>
                                    <span class="text-sm font-bold opacity-90">help@koskora.com</span>
                                </div>
                            </div>
                        </div>
                        <div class="absolute -right-12 -top-12 w-40 h-40 bg-brand opacity-10 blur-[60px]"></div>
                    </div>
                </div>
            </div>
        @endif

        {{-- ===== ANNOUNCEMENTS ===== --}}
        <div class="mb-6">
            <div class="flex items-center justify-between mb-4 px-1">
                <div class="flex items-center gap-2">
                    <div class="w-1 h-5 bg-brand rounded-full"></div>
                    <h3 class="text-base md:text-lg font-extrabold text-slate-800 tracking-tight">Broadcast Info</h3>
                </div>
                <a href="{{ route('user.announcements.index') }}" class="text-[10px] font-bold text-brand hover:underline uppercase tracking-wider">Semua →</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @forelse($announcements as $announce)
                    <div class="stat-card group cursor-pointer hover:border-brand/30 !p-4 md:!p-5">
                        <div class="flex items-center justify-between mb-3">
                            @php
                                $badgeClass = match($announce->type) {
                                    'update' => 'badge-success',
                                    'warning' => 'badge-warning',
                                    'danger' => 'badge-danger',
                                    default => 'badge-info',
                                };
                            @endphp
                            <span class="badge {{ $badgeClass }} !text-[9px]">{{ $announce->type }}</span>
                            <span class="text-[9px] font-bold text-slate-300">{{ $announce->created_at->diffForHumans() }}</span>
                        </div>
                        <h4 class="font-bold text-sm text-slate-800 leading-snug mb-2 group-hover:text-brand transition-colors">{{ Str::limit($announce->title, 45) }}</h4>
                        <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">{{ Str::limit($announce->content, 80) }}</p>
                    </div>
                @empty
                    <div class="col-span-full py-10 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[20px] text-center">
                        <i class="fas fa-bullhorn text-2xl text-slate-200 mb-2"></i>
                        <div class="text-slate-400 font-bold text-xs">Tidak ada pengumuman hari ini.</div>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- ===== MOBILE SUPPORT CARD (visible only on mobile) ===== --}}
        @if($activeRental)
        <div class="lg:hidden mb-24">
            <div class="bg-slate-900 rounded-[20px] p-5 text-white relative overflow-hidden">
                <div class="relative z-10">
                    <h4 class="text-sm font-extrabold mb-3">Kontak Pengelola</h4>
                    <div class="flex gap-3">
                        <a href="https://wa.me/6281234567890" class="flex-1 flex items-center justify-center gap-2 py-3 bg-white/10 rounded-xl text-xs font-bold hover:bg-white/20 transition">
                            <i class="fab fa-whatsapp text-emerald-400"></i> WhatsApp
                        </a>
                        <a href="mailto:help@koskora.com" class="flex-1 flex items-center justify-center gap-2 py-3 bg-white/10 rounded-xl text-xs font-bold hover:bg-white/20 transition">
                            <i class="far fa-envelope text-blue-400"></i> Email
                        </a>
                    </div>
                </div>
                <div class="absolute -right-8 -top-8 w-28 h-28 bg-brand opacity-15 blur-[40px]"></div>
            </div>
        </div>
        @else
        <div class="mb-24 lg:mb-0"></div>
        @endif
    </div>

    {{-- ===== MODALS ===== --}}

    <div id="moveOutModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
        <div class="bg-white rounded-[28px] w-full max-w-md overflow-hidden shadow-2xl animate-fade-in p-6 md:p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Rencana Keluar</h3>
                <button onclick="toggleMoveOutModal()" class="w-9 h-9 hover:bg-slate-100 rounded-full transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-slate-400"></i>
                </button>
            </div>
            @if($activeRental)
            <form action="{{ route('rentals.request-termination', $activeRental->id) }}" method="POST" class="space-y-5">
                @csrf
                <div class="space-y-2">
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilih Tanggal</label>
                    <input type="date" name="end_date" value="{{ $activeRental->end_date }}"
                        min="{{ date('Y-m-d', strtotime('+1 day')) }}" required
                        class="w-full rounded-xl border-slate-200 focus:border-brand focus:ring-brand">
                </div>
                <button type="submit" class="w-full py-3.5 bg-slate-900 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-slate-800 transition-all shadow-xl">
                    Simpan Perubahan
                </button>
            </form>
            @endif
        </div>
    </div>

    @if($activeRental)
    <div id="reviewModal" class="fixed inset-0 z-[100] hidden items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm transition-all">
        <div class="bg-white rounded-[28px] w-full max-w-md overflow-hidden shadow-2xl animate-fade-in p-6 md:p-8 space-y-6">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-extrabold text-slate-800 tracking-tight">Ulasan Kamar</h3>
                <button onclick="toggleReviewModal()" class="w-9 h-9 hover:bg-slate-100 rounded-full transition-colors flex items-center justify-center">
                    <i class="fas fa-times text-slate-400"></i>
                </button>
            </div>
            <form action="{{ route('room-reviews.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="room_id" value="{{ $activeRental->room->id }}">
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-3">Rating</label>
                    <div class="flex items-center gap-2" id="modal-star-group">
                        @for($i = 1; $i <= 5; $i++)
                        <label class="cursor-pointer modal-star-label">
                            <input type="radio" name="rating" value="{{ $i }}" class="hidden" required>
                            <i class="fas fa-star text-2xl text-slate-200 hover:text-amber-400 modal-star-icon transition-colors" data-val="{{ $i }}"></i>
                        </label>
                        @endfor
                    </div>
                </div>
                <div>
                    <label class="text-[10px] font-bold text-slate-400 uppercase tracking-widest block mb-2">Pesan Anda</label>
                    <textarea name="comment" rows="3" placeholder="Tuliskan ulasan Anda..." class="w-full rounded-xl border-slate-200 focus:border-brand focus:ring-brand text-sm"></textarea>
                </div>
                <button type="submit" class="w-full py-3.5 bg-amber-500 text-white rounded-2xl font-bold text-xs uppercase tracking-widest hover:bg-amber-600 transition-all shadow-xl shadow-amber-100">
                    Kirim Ulasan
                </button>
            </form>
        </div>
    </div>
    @endif

    <script>
        function toggleMoveOutModal() {
            const m = document.getElementById('moveOutModal');
            m.classList.toggle('hidden');
            m.classList.toggle('flex');
        }
        function toggleReviewModal() {
            const m = document.getElementById('reviewModal');
            if (m) {
                m.classList.toggle('hidden');
                m.classList.toggle('flex');
            }
        }
        const stars = document.querySelectorAll('.modal-star-icon');
        const radios = document.querySelectorAll('#reviewModal input[name="rating"]');
        stars.forEach((star, idx) => {
            star.addEventListener('click', () => {
                radios[idx].checked = true;
                stars.forEach((s, sIdx) => {
                    s.classList.toggle('text-amber-400', sIdx <= idx);
                    s.classList.toggle('text-slate-200', sIdx > idx);
                });
            });
        });
    </script>
</x-app-layout>
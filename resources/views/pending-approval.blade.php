<x-guest-layout>
    <div class="text-center space-y-8">
        {{-- Animated Pending Icon --}}
        <div class="flex justify-center">
            <div class="relative">
                <div class="w-28 h-28 rounded-3xl bg-amber-50 border-2 border-amber-200 flex items-center justify-center shadow-xl animate-pulse">
                    <i class="fas fa-clock text-5xl text-amber-500"></i>
                </div>
                <div class="absolute -top-2 -right-2 w-8 h-8 bg-amber-400 rounded-full flex items-center justify-center shadow-lg">
                    <i class="fas fa-hourglass-half text-white text-xs"></i>
                </div>
            </div>
        </div>

        {{-- Title --}}
        <div class="space-y-3">
            <h2 class="text-2xl font-extrabold text-brand-blue">
                Menunggu <span class="text-brand-red">Persetujuan</span>
            </h2>
            <p class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                Akun KosKora Anda
            </p>
        </div>

        {{-- Role Badge --}}
        <div class="flex justify-center">
            @if(auth()->user()->role === 'user')
                <div class="flex items-center gap-2 bg-blue-50 border border-blue-200 rounded-full px-5 py-2">
                    <i class="fas fa-search-location text-brand-blue text-sm"></i>
                    <span class="text-xs font-black text-brand-blue uppercase tracking-wider">Pencari Kos</span>
                </div>
            @else
                <div class="flex items-center gap-2 bg-purple-50 border border-purple-200 rounded-full px-5 py-2">
                    <i class="fas fa-hotel text-purple-600 text-sm"></i>
                    <span class="text-xs font-black text-purple-700 uppercase tracking-wider">Admin Kos</span>
                </div>
            @endif
        </div>

        {{-- Info Box --}}
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 text-left space-y-3">
            <div class="flex items-start gap-3">
                <i class="fas fa-info-circle text-brand-blue mt-0.5 text-sm"></i>
                <div class="space-y-2">
                    <p class="text-xs font-bold text-slate-700">Registrasi Anda berhasil dikirim!</p>
                    <p class="text-[11px] text-slate-500 leading-relaxed">
                        Akun Anda sedang dalam proses verifikasi oleh tim KosKora. 
                        Super Admin akan meninjau dan menyetujui akun Anda dalam waktu <strong>1×24 jam</strong>.
                    </p>
                </div>
            </div>
        </div>

        {{-- Steps --}}
        <div class="text-left space-y-3">
            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Proses Verifikasi</p>
            <div class="space-y-2.5">
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-emerald-100 border border-emerald-300 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-[10px] text-emerald-600"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-600">Formulir pendaftaran terisi</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-amber-100 border border-amber-300 flex items-center justify-center flex-shrink-0 animate-pulse">
                        <i class="fas fa-hourglass-half text-[10px] text-amber-600"></i>
                    </div>
                    <span class="text-xs font-bold text-amber-700">Menunggu review Super Admin</span>
                </div>
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-unlock text-[10px] text-slate-400"></i>
                    </div>
                    <span class="text-xs font-semibold text-slate-400">Akun aktif & siap digunakan</span>
                </div>
            </div>
        </div>

        {{-- User Info --}}
        <div class="border-t border-slate-100 pt-6 space-y-1">
            <p class="text-[11px] font-bold text-slate-500">Akun terdaftar atas nama:</p>
            <p class="text-sm font-extrabold text-slate-800">{{ auth()->user()->name }}</p>
            <p class="text-[11px] text-slate-400 font-medium">{{ auth()->user()->email }}</p>
        </div>

        {{-- Logout Button --}}
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full py-4 border-2 border-slate-200 text-slate-500 rounded-[0.875rem] font-black text-xs uppercase tracking-widest hover:border-brand-red hover:text-brand-red transition-all">
                <i class="fas fa-sign-out-alt mr-2"></i>Keluar dari Akun
            </button>
        </form>
    </div>
</x-guest-layout>

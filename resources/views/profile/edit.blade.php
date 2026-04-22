<x-app-layout>
    @section('header_title', 'My Profile')

    <div class="profile-page">
        {{-- Profile Header Card --}}
        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm relative group">
            <div class="h-32 bg-slate-50 relative overflow-hidden">
                <div class="absolute inset-0 bg-brand-blue/5"></div>
                <!-- Decorative pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#1e1b9b 1px, transparent 1px); background-size: 20px 20px;"></div>
            </div>
            <div class="flex flex-col items-center px-6 pb-8 -mt-16 relative z-10">
                <div class="relative mb-4 group/avatar">
                    <div class="w-32 h-32 rounded-3xl bg-white p-2 shadow-xl">
                        <div class="w-full h-full rounded-2xl bg-brand-navy flex items-center justify-center text-white text-3xl font-extrabold shadow-inner border-4 border-white">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-10 h-10 bg-emerald-500 border-4 border-white rounded-2xl flex items-center justify-center text-white shadow-lg">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </div>
                
                <div class="text-center space-y-1">
                    <h1 class="text-2xl font-extrabold text-slate-800 tracking-tight">{{ auth()->user()->name }}</h1>
                    <p class="text-sm font-bold text-slate-400 uppercase tracking-widest">{{ auth()->user()->email }}</p>
                    <div class="pt-3">
                        <span class="inline-flex items-center px-3 py-1 rounded-full bg-brand-blue/10 text-brand-blue text-[10px] font-black uppercase tracking-widest border border-brand-blue/20">
                            {{ auth()->user()->role }} Account
                        </span>
                    </div>
                </div>
            </div>

            {{-- Quick Stats --}}
            @if(auth()->user()->role === 'user' && auth()->user()->tenant)
                @php
                    $tenant = auth()->user()->tenant;
                    $activeRental = $tenant->rentals()->where('status', 'active')->with('room')->first();
                @endphp
                <div class="grid grid-cols-3 border-t border-slate-100 bg-slate-50/30">
                    <div class="p-4 text-center border-r border-slate-100">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Nomor Kamar</div>
                        <div class="text-lg font-extrabold text-slate-800 tracking-tight leading-none">{{ $activeRental ? $activeRental->room->room_number : '-' }}</div>
                    </div>
                    <div class="p-4 text-center border-r border-slate-100">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Mulai Sewa</div>
                        <div class="text-lg font-extrabold text-slate-800 tracking-tight leading-none">{{ $activeRental ? \Carbon\Carbon::parse($activeRental->start_date)->format('d/m/y') : '-' }}</div>
                    </div>
                    <div class="p-4 text-center">
                        <div class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1 leading-none">Status</div>
                        <div class="text-lg font-extrabold {{ $activeRental ? 'text-emerald-600' : 'text-slate-400' }} tracking-tight leading-none flex items-center justify-center gap-1.5">
                            <span class="w-2 h-2 rounded-full {{ $activeRental ? 'bg-emerald-500' : 'bg-slate-300' }}"></span>
                            <span>{{ $activeRental ? 'Aktif' : 'Nonaktif' }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Success Toasts --}}
        @if (session('status') === 'profile-updated')
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-bold uppercase tracking-widest">Informasi Profil Berhasil Diperbarui</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm" x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 3000)">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-bold uppercase tracking-widest">Kata Sandi Berhasil Diperbarui</span>
            </div>
        @endif

        {{-- Profile Information Section --}}
        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
            <div class="p-6 md:p-8 bg-slate-50/50 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-brand-blue shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight leading-none mb-1 uppercase">Informasi Profil</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Kelola Identitas Akun Anda</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('patch')

                <div class="space-y-2">
                    <label for="name" class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Nama Lengkap</label>
                    <div class="relative flex items-center">
                        <svg class="absolute left-4 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-brand-blue transition-all" required autocomplete="name">
                    </div>
                    @error('name')
                        <p class="text-[10px] font-black text-rose-500 uppercase mt-1 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="space-y-2">
                    <label for="email" class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Alamat Email</label>
                    <div class="relative flex items-center">
                        <svg class="absolute left-4 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-brand-blue transition-all" required autocomplete="username">
                    </div>
                    @error('email')
                        <p class="text-[10px] font-black text-rose-500 uppercase mt-1 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="pt-2">
                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-brand-navy text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-brand-blue transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Password Section --}}
        <div class="bg-white border border-slate-200 rounded-3xl overflow-hidden shadow-sm">
            <div class="p-6 md:p-8 bg-slate-50/50 border-b border-slate-100 flex items-center gap-4">
                <div class="w-12 h-12 bg-white border border-slate-200 rounded-2xl flex items-center justify-center text-amber-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-slate-800 tracking-tight leading-none mb-1 uppercase">Keamanan Akun</h2>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none">Ubah Kata Sandi Berkala</p>
                </div>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="p-6 md:p-8 space-y-6">
                @csrf
                @method('put')

                <div class="space-y-2">
                    <label for="current_password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Kata Sandi Saat Ini</label>
                    <div class="relative flex items-center">
                        <svg class="absolute left-4 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        <input type="password" id="current_password" name="current_password" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-brand-blue transition-all" placeholder="••••••••">
                    </div>
                    @error('current_password', 'updatePassword')
                        <p class="text-[10px] font-black text-rose-500 uppercase mt-1 pl-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label for="password" class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Kata Sandi Baru</label>
                        <div class="relative flex items-center">
                            <svg class="absolute left-4 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <input type="password" id="password" name="password" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-brand-blue transition-all" placeholder="••••••••">
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label for="password_confirmation" class="text-[10px] font-black text-slate-400 uppercase tracking-widest pl-1">Konfirmasi Kata Sandi</label>
                        <div class="relative flex items-center">
                            <svg class="absolute left-4 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <input type="password" id="password_confirmation" name="password_confirmation" class="w-full pl-12 pr-4 py-3 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-brand-blue transition-all" placeholder="••••••••">
                        </div>
                    </div>
                </div>
                @error('password', 'updatePassword')
                    <p class="text-[10px] font-black text-rose-500 uppercase mt-1 pl-1">{{ $message }}</p>
                @enderror

                <div class="pt-2">
                    <button type="submit" class="w-full md:w-auto px-10 py-4 bg-[#d97706] text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-amber-600 transition-all active:scale-95 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Update Kata Sandi
                    </button>
                </div>
            </form>
        </div>

        {{-- Danger Zone --}}
        <div class="bg-white border border-rose-100 rounded-3xl overflow-hidden shadow-sm">
            <div class="p-6 md:p-8 bg-rose-50/30 border-b border-rose-50 flex items-center gap-4">
                <div class="w-12 h-12 bg-white border border-rose-100 rounded-2xl flex items-center justify-center text-rose-500 shadow-sm">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h2 class="text-lg font-extrabold text-rose-800 tracking-tight leading-none mb-1 uppercase">Hapus Akun</h2>
                    <p class="text-[10px] font-black text-rose-400 uppercase tracking-widest leading-none">Tindakan Tidak Dapat Dibatalkan</p>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <p class="text-xs font-bold text-slate-500 leading-relaxed mb-6">
                    Setelah akun dihapus, semua data dan informasi akan dihapus secara permanen dari server kami. Pastikan Anda sudah menyimpan data penting sebelum melanjutkan.
                </p>
                <button type="button" class="w-full md:w-auto px-10 py-4 bg-rose-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-lg hover:bg-rose-700 transition-all active:scale-95 flex items-center justify-center gap-2" onclick="document.getElementById('deleteModal').classList.add('active'); document.getElementById('deleteModal').classList.remove('hidden')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Akun Sekarang
                </button>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div id="deleteModal" class="fixed inset-0 z-[60] hidden items-center justify-center p-4">
            <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="this.parentElement.classList.remove('active'); this.parentElement.classList.add('hidden')"></div>
            <div class="bg-white rounded-3xl w-full max-w-md p-8 md:p-10 relative z-10 shadow-2xl border border-slate-200">
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="w-20 h-20 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mb-2">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    </div>
                    <h3 class="text-2xl font-extrabold text-slate-800 tracking-tight leading-none">Hapus Akun?</h3>
                    <p class="text-xs font-bold text-slate-400 uppercase tracking-widest leading-loose">Masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun permanen.</p>
                </div>
                
                <form method="post" action="{{ route('profile.destroy') }}" class="mt-8 space-y-6">
                    @csrf
                    @method('delete')

                    <div class="space-y-2">
                        <div class="relative flex items-center">
                            <svg class="absolute left-4 w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <input type="password" name="password" class="w-full pl-12 pr-4 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl font-extrabold text-slate-700 focus:ring-0 focus:border-rose-500 transition-all placeholder-slate-300" placeholder="Kata Sandi Akun" required>
                        </div>
                        @if($errors->userDeletion->has('password'))
                            <p class="text-[10px] font-black text-rose-500 uppercase mt-1 pl-1">{{ $errors->userDeletion->first('password') }}</p>
                        @endif
                    </div>

                    <div class="flex gap-4">
                        <button type="button" class="flex-1 py-4 bg-slate-50 text-slate-500 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-100 transition-all" onclick="this.closest('#deleteModal').classList.remove('active'); this.closest('#deleteModal').classList.add('hidden')">
                            Batal
                        </button>
                        <button type="submit" class="flex-1 py-4 bg-rose-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl transition-all hover:bg-rose-700 active:scale-95">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->isNotEmpty())
        <script>document.getElementById('deleteModal').classList.add('active'); document.getElementById('deleteModal').classList.remove('hidden');</script>
    @endif
</x-app-layout>

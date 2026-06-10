<x-app-layout>
    @section('header_title', 'My Profile')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        {{-- ===== PROFILE HEADER ===== --}}
        <div class="stat-card !p-0 overflow-hidden">
            <div class="h-24 bg-brand relative">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
            </div>
            <div class="px-8 pb-8 flex flex-col md:flex-row items-center md:items-end gap-6 -mt-10 relative">
                <div class="w-24 h-24 rounded-2xl bg-white p-1.5 shadow-xl">
                    <div class="w-full h-full rounded-xl bg-brand-light text-brand flex items-center justify-center text-3xl font-bold border border-brand/10">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                </div>
                <div class="flex-1 text-center md:text-left mb-2">
                    <h2 class="text-xl font-bold text-slate-900 tracking-tight">{{ auth()->user()->name }}</h2>
                    <p class="text-[13px] text-slate-500 font-medium">{{ auth()->user()->email }} • {{ ucfirst(auth()->user()->role) }} Account</p>
                </div>
                <div class="mb-2 flex items-center gap-2">
                    <span class="badge badge-purple uppercase tracking-widest">{{ auth()->user()->role }}</span>
                    <form method="POST" action="{{ route('logout') }}" class="m-0 p-0">
                        @csrf
                        <button type="submit" class="badge bg-red-50 text-red-500 uppercase tracking-widest border border-red-200 cursor-pointer hover:bg-red-100 transition-colors">
                            <i class="fas fa-sign-out-alt mr-1"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Success Messages --}}
        @if (session('status') === 'profile-updated' || session('status') === 'password-updated')
            <div class="badge badge-green w-full justify-start p-3 rounded-xl border border-emerald-100">
                <i class="fas fa-check-circle mr-2"></i>
                Pengaturan akun Anda berhasil diperbarui.
            </div>
        @endif

        {{-- ===== PROFILE INFO ===== --}}
        <div class="stat-card">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1 h-4 bg-brand rounded-full"></span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Informasi Profil</h3>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                @csrf
                @method('patch')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label for="name" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required>
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div class="space-y-1.5">
                        <label for="email" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required>
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                </div>

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn btn-primary px-8">Simpan Perubahan</button>
                </div>
            </form>
        </div>

        {{-- ===== SECURITY SECTION ===== --}}
        <div class="stat-card">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1 h-4 bg-brand rounded-full"></span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Keamanan Akun</h3>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div class="space-y-1.5">
                    <label for="current_password" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kata Sandi Saat Ini</label>
                    <input type="password" id="current_password" name="current_password" placeholder="••••••••">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1.5">
                        <label for="password" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kata Sandi Baru</label>
                        <input type="password" id="password" name="password" placeholder="••••••••">
                    </div>
                    <div class="space-y-1.5">
                        <label for="password_confirmation" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Konfirmasi Kata Sandi</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" placeholder="••••••••">
                    </div>
                </div>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />

                <div class="flex justify-end pt-2">
                    <button type="submit" class="btn btn-primary px-8">Update Password</button>
                </div>
            </form>
        </div>

        {{-- ===== DANGER ZONE ===== --}}
        <div class="stat-card border-red-100 bg-red-50/10">
            <div class="flex items-center gap-2 mb-4">
                <span class="w-1 h-4 bg-red-500 rounded-full"></span>
                <h3 class="text-sm font-bold text-red-800 uppercase tracking-wider">Hapus Akun</h3>
            </div>
            
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                <p class="text-[13px] text-slate-500 max-w-xl">
                    Tindakan ini permanen. Semua data akun Anda akan dihapus selamanya. Pastikan Anda sudah memikirkannya.
                </p>
                <button type="button" class="btn !bg-red-500 !text-white hover:!bg-red-600 px-6" 
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'confirm-user-deletion' }))">
                    Hapus Akun
                </button>
            </div>
        </div>

        {{-- ===== DELETE CONFIRMATION MODAL ===== --}}
        <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
            <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
                @csrf
                @method('delete')

                <h2 class="text-lg font-bold text-slate-900">Apakah Anda yakin?</h2>
                <p class="mt-1 text-sm text-slate-500">Silakan masukkan kata sandi Anda untuk mengonfirmasi penghapusan akun permanen.</p>

                <div class="mt-6 space-y-2">
                    <label for="delete-password" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kata Sandi</label>
                    <input id="delete-password" name="password" type="password" placeholder="••••••••" required>
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" class="btn btn-ghost" x-on:click="$dispatch('close')">Batal</button>
                    <button type="submit" class="btn !bg-red-500 !text-white hover:!bg-red-600">Ya, Hapus Akun</button>
                </div>
            </form>
        </x-modal>
    </div>
</x-app-layout>

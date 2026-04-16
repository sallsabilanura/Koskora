<x-app-layout>
    @section('header_title', 'My Profile')

    <div class="profile-page">
        {{-- Profile Header Card --}}
        <div class="profile-header-card">
            <div class="profile-header-bg"></div>
            <div class="profile-header-content">
                <div class="profile-avatar-wrapper">
                    <div class="profile-avatar" id="profileAvatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                    </div>
                    <div class="profile-avatar-badge">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    </div>
                </div>
                <div class="profile-info">
                    <h1 class="profile-name">{{ auth()->user()->name }}</h1>
                    <p class="profile-email">{{ auth()->user()->email }}</p>
                    <span class="profile-role-badge profile-role-{{ auth()->user()->role }}">
                        {{ ucfirst(auth()->user()->role) }}
                    </span>
                </div>
            </div>

            {{-- Quick Stats --}}
            @if(auth()->user()->role === 'user' && auth()->user()->tenant)
                @php
                    $tenant = auth()->user()->tenant;
                    $activeRental = $tenant->rentals()->where('status', 'active')->with('room')->first();
                @endphp
                <div class="profile-stats">
                    <div class="profile-stat-item">
                        <div class="profile-stat-value">{{ $activeRental ? $activeRental->room->room_number : '-' }}</div>
                        <div class="profile-stat-label">Kamar</div>
                    </div>
                    <div class="profile-stat-divider"></div>
                    <div class="profile-stat-item">
                        <div class="profile-stat-value">{{ $activeRental ? \Carbon\Carbon::parse($activeRental->start_date)->format('d/m/Y') : '-' }}</div>
                        <div class="profile-stat-label">Sejak</div>
                    </div>
                    <div class="profile-stat-divider"></div>
                    <div class="profile-stat-item">
                        <div class="profile-stat-value">
                            <span class="inline-flex items-center gap-1">
                                <span class="w-2 h-2 rounded-full {{ $activeRental ? 'bg-emerald-400' : 'bg-slate-300' }}"></span>
                                {{ $activeRental ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                        <div class="profile-stat-label">Status</div>
                    </div>
                </div>
            @endif
        </div>

        {{-- Success Toast --}}
        @if (session('status') === 'profile-updated')
            <div class="profile-toast success" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-semibold text-emerald-700">Profile berhasil diperbarui!</span>
            </div>
        @endif

        @if (session('status') === 'password-updated')
            <div class="profile-toast success" x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)">
                <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span class="text-sm font-semibold text-emerald-700">Password berhasil diperbarui!</span>
            </div>
        @endif

        {{-- Profile Information Section --}}
        <div class="profile-section">
            <div class="profile-section-header">
                <div class="profile-section-icon blue">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <div>
                    <h2 class="profile-section-title">Informasi Profil</h2>
                    <p class="profile-section-desc">Perbarui nama dan alamat email Anda</p>
                </div>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="profile-form">
                @csrf
                @method('patch')

                <div class="profile-form-group">
                    <label for="name" class="profile-label">Nama Lengkap</label>
                    <div class="profile-input-wrapper">
                        <svg class="profile-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" class="profile-input" required autocomplete="name" placeholder="Masukkan nama lengkap">
                    </div>
                    @error('name')
                        <p class="profile-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="email" class="profile-label">Alamat Email</label>
                    <div class="profile-input-wrapper">
                        <svg class="profile-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" class="profile-input" required autocomplete="username" placeholder="Masukkan email">
                    </div>
                    @error('email')
                        <p class="profile-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-form-actions">
                    <button type="submit" class="profile-btn primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Password Section --}}
        <div class="profile-section">
            <div class="profile-section-header">
                <div class="profile-section-icon amber">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                </div>
                <div>
                    <h2 class="profile-section-title">Ubah Password</h2>
                    <p class="profile-section-desc">Pastikan menggunakan password yang kuat</p>
                </div>
            </div>

            <form method="post" action="{{ route('password.update') }}" class="profile-form">
                @csrf
                @method('put')

                <div class="profile-form-group">
                    <label for="current_password" class="profile-label">Password Saat Ini</label>
                    <div class="profile-input-wrapper">
                        <svg class="profile-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                        <input type="password" id="current_password" name="current_password" class="profile-input" autocomplete="current-password" placeholder="••••••••">
                    </div>
                    @error('current_password', 'updatePassword')
                        <p class="profile-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="password" class="profile-label">Password Baru</label>
                    <div class="profile-input-wrapper">
                        <svg class="profile-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        <input type="password" id="password" name="password" class="profile-input" autocomplete="new-password" placeholder="••••••••">
                    </div>
                    @error('password', 'updatePassword')
                        <p class="profile-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-form-group">
                    <label for="password_confirmation" class="profile-label">Konfirmasi Password</label>
                    <div class="profile-input-wrapper">
                        <svg class="profile-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="profile-input" autocomplete="new-password" placeholder="••••••••">
                    </div>
                    @error('password_confirmation', 'updatePassword')
                        <p class="profile-error">{{ $message }}</p>
                    @enderror
                </div>

                <div class="profile-form-actions">
                    <button type="submit" class="profile-btn primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        {{-- Danger Zone --}}
        <div class="profile-section danger-zone">
            <div class="profile-section-header">
                <div class="profile-section-icon red">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h2 class="profile-section-title text-rose-700">Hapus Akun</h2>
                    <p class="profile-section-desc">Tindakan ini tidak bisa dibatalkan</p>
                </div>
            </div>

            <div class="danger-zone-content">
                <p class="text-sm text-slate-500 leading-relaxed mb-4">
                    Setelah akun dihapus, semua data dan informasi akan dihapus secara permanen. Pastikan Anda sudah menyimpan data yang diperlukan.
                </p>
                <button type="button" class="profile-btn danger" onclick="document.getElementById('deleteModal').classList.add('active')">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    Hapus Akun Saya
                </button>
            </div>
        </div>

        {{-- Delete Confirmation Modal --}}
        <div class="profile-modal-overlay" id="deleteModal">
            <div class="profile-modal">
                <div class="profile-modal-icon">
                    <svg class="w-8 h-8 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Hapus Akun?</h3>
                <p class="text-sm text-slate-500 mb-5">Masukkan password untuk konfirmasi penghapusan akun.</p>
                
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="profile-form-group" style="margin-bottom: 1.25rem;">
                        <div class="profile-input-wrapper">
                            <svg class="profile-input-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <input type="password" name="password" class="profile-input" placeholder="Masukkan password" required>
                        </div>
                        @if($errors->userDeletion->has('password'))
                            <p class="profile-error">{{ $errors->userDeletion->first('password') }}</p>
                        @endif
                    </div>

                    <div class="flex gap-3">
                        <button type="button" class="profile-btn secondary flex-1" onclick="document.getElementById('deleteModal').classList.remove('active')">
                            Batal
                        </button>
                        <button type="submit" class="profile-btn danger flex-1">
                            Ya, Hapus
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @if($errors->userDeletion->isNotEmpty())
        <script>document.getElementById('deleteModal').classList.add('active');</script>
    @endif
</x-app-layout>

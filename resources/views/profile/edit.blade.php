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
            <div class="badge badge-green w-full justify-start p-3 rounded-xl border border-emerald-100 mb-6">
                <i class="fas fa-check-circle mr-2"></i>
                Pengaturan akun Anda berhasil diperbarui.
            </div>
        @endif

        {{-- Booking Flow Warning Banner --}}
        @if (session('info'))
            <div class="badge badge-amber w-full justify-start p-3 rounded-xl border border-amber-100 flex items-center gap-2 mb-6">
                <i class="fas fa-exclamation-circle text-amber-600 mr-2"></i>
                <span class="text-amber-800 font-bold text-xs">{{ session('info') }}</span>
            </div>
        @endif

        {{-- ===== PROFILE INFO ===== --}}
        <div class="stat-card">
            <div class="flex items-center gap-2 mb-6">
                <span class="w-1 h-4 bg-brand rounded-full"></span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Informasi Profil</h3>
            </div>

            <form method="post" action="{{ route('profile.update') }}" class="space-y-6" enctype="multipart/form-data">
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
                @if ($user->isAnyAdmin())
                    <input type="hidden" name="province_id" id="province_id_input" value="{{ old('province_id', $user->province_id) }}">
                    <input type="hidden" name="province" id="province_name_input" value="{{ old('province', $user->province) }}">
                    <input type="hidden" name="city_id" id="city_id_input" value="{{ old('city_id', $user->city_id) }}">
                    <input type="hidden" name="city" id="city_name_input" value="{{ old('city', $user->city) }}">
                    <input type="hidden" name="district_id" id="district_id_input" value="{{ old('district_id', $user->district_id) }}">
                    <input type="hidden" name="district" id="district_name_input" value="{{ old('district', $user->district) }}">
                    <input type="hidden" name="village_id" id="village_id_input" value="{{ old('village_id', $user->village_id) }}">
                    <input type="hidden" name="village" id="village_name_input" value="{{ old('village', $user->village) }}">

                    <div class="mt-6 border-t border-slate-100 pt-6 space-y-6">
                        <div class="flex items-center gap-2">
                            <span class="w-1 h-4 bg-brand rounded-full"></span>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Alamat Cabang / Wilayah</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                                <select id="province-select">
                                    <option value="">Pilih Provinsi</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kota / Kabupaten</label>
                                <select id="city-select" disabled>
                                    <option value="">Pilih Kota/Kabupaten</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                                <select id="district-select" disabled>
                                    <option value="">Pilih Kecamatan</option>
                                </select>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kelurahan / Desa</label>
                                <select id="village-select" disabled>
                                    <option value="">Pilih Kelurahan/Desa</option>
                                </select>
                            </div>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="address" rows="2" placeholder="Jl. Merdeka No. 123...">{{ old('address', $user->address) }}</textarea>
                        </div>
                    </div>
                @endif

                @if ($user->isUser())
                    <div class="mt-8 border-t border-slate-100 pt-8 space-y-8">
                        <div class="flex items-center gap-2">
                            <span class="w-1 h-4 bg-brand rounded-full"></span>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Dokumen Identitas (KTP & Foto)</h4>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Foto KTP -->
                            <div class="space-y-3">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Foto KTP <span class="text-red-500">*</span></label>
                                <div class="relative group">
                                    <div id="ktp-preview" class="aspect-[3/2] rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-brand group-hover:bg-brand-light/20">
                                        @if($user->tenant?->foto_ktp)
                                            <img src="{{ asset('storage/' . $user->tenant->foto_ktp) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-id-card text-3xl text-slate-300 mb-2 transition-transform group-hover:scale-110"></i>
                                            <span class="text-[10px] font-bold text-slate-400">Klik untuk upload KTP</span>
                                        @endif
                                    </div>
                                    <input type="file" name="foto_ktp" onchange="previewImage(this, 'ktp-preview')" class="absolute inset-0 opacity-0 cursor-pointer text-[0px]" accept="image/*">
                                </div>
                                <x-input-error :messages="$errors->get('foto_ktp')" class="mt-2" />
                            </div>

                            <!-- Foto Diri -->
                            <div class="space-y-3">
                                <label class="block text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Foto Diri Bebas <span class="text-red-500">*</span></label>
                                <div class="relative group">
                                    <div id="self-preview" class="aspect-[3/2] rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-brand group-hover:bg-brand-light/20">
                                        @if($user->tenant?->foto_diri)
                                            <img src="{{ asset('storage/' . $user->tenant->foto_diri) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="fas fa-user-circle text-3xl text-slate-300 mb-2 transition-transform group-hover:scale-110"></i>
                                            <span class="text-[10px] font-bold text-slate-400">Klik untuk upload foto diri</span>
                                        @endif
                                    </div>
                                    <input type="file" name="foto_diri" onchange="previewImage(this, 'self-preview')" class="absolute inset-0 opacity-0 cursor-pointer text-[0px]" accept="image/*">
                                </div>
                                <x-input-error :messages="$errors->get('foto_diri')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-4">
                            <span class="w-1 h-4 bg-brand rounded-full"></span>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Informasi Pribadi Penyewa</h4>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1.5">
                                <label for="nama_lengkap" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap (Sesuai KTP)</label>
                                <input type="text" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap', $user->tenant?->nama_lengkap ?? $user->name) }}">
                                <x-input-error :messages="$errors->get('nama_lengkap')" class="mt-2" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="nama_panggilan" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Panggilan</label>
                                <input type="text" id="nama_panggilan" name="nama_panggilan" value="{{ old('nama_panggilan', $user->tenant?->nama_panggilan) }}" placeholder="Contoh: Budi">
                                <x-input-error :messages="$errors->get('nama_panggilan')" class="mt-2" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="nik" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">NIK KTP (16 Digit)</label>
                                <input type="text" id="nik" name="nik" maxlength="16" value="{{ old('nik', $user->tenant?->nik) }}" placeholder="32xxxxxxxxxxxxxx">
                                <x-input-error :messages="$errors->get('nik')" class="mt-2" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="jenis_kelamin" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Jenis Kelamin</label>
                                <select id="jenis_kelamin" name="jenis_kelamin">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin', $user->tenant?->jenis_kelamin) == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin', $user->tenant?->jenis_kelamin) == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                <x-input-error :messages="$errors->get('jenis_kelamin')" class="mt-2" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="nomor_whatsapp" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nomor WhatsApp</label>
                                <input type="text" id="nomor_whatsapp" name="nomor_whatsapp" value="{{ old('nomor_whatsapp', $user->tenant?->nomor_whatsapp) }}" placeholder="08xxxxxxxxxx">
                                <x-input-error :messages="$errors->get('nomor_whatsapp')" class="mt-2" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="occupation" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pekerjaan</label>
                                <input type="text" id="occupation" name="occupation" value="{{ old('occupation', $user->tenant?->occupation) }}" placeholder="Contoh: Programmer / Mahasiswa">
                                <x-input-error :messages="$errors->get('occupation')" class="mt-2" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="tempat_lahir" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tempat Lahir</label>
                                <input type="text" id="tempat_lahir" name="tempat_lahir" value="{{ old('tempat_lahir', $user->tenant?->tempat_lahir) }}" placeholder="Contoh: Bandung">
                                <x-input-error :messages="$errors->get('tempat_lahir')" class="mt-2" />
                            </div>

                            <div class="space-y-1.5">
                                <label for="tanggal_lahir" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tanggal Lahir</label>
                                <input type="date" id="tanggal_lahir" name="tanggal_lahir" value="{{ old('tanggal_lahir', $user->tenant?->tanggal_lahir) }}">
                                <x-input-error :messages="$errors->get('tanggal_lahir')" class="mt-2" />
                            </div>

                            <div class="space-y-1.5 md:col-span-2">
                                <label for="emergency_contact" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kontak Darurat (No HP Orang Tua / Wali)</label>
                                <input type="text" id="emergency_contact" name="emergency_contact" value="{{ old('emergency_contact', $user->tenant?->emergency_contact) }}" placeholder="Contoh: 08xxxxxxxxxx">
                                <x-input-error :messages="$errors->get('emergency_contact')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center gap-2 pt-4">
                            <span class="w-1 h-4 bg-brand rounded-full"></span>
                            <h4 class="text-xs font-bold text-slate-800 uppercase tracking-wider">Alamat Lengkap Penyewa</h4>
                        </div>

                        <div class="space-y-6">
                            <div class="space-y-1.5">
                                <label for="alamat_ktp" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap (Sesuai KTP)</label>
                                <textarea id="alamat_ktp" name="alamat_ktp" rows="2" placeholder="Jl. Merdeka No. 1...">{{ old('alamat_ktp', $user->tenant?->alamat_ktp) }}</textarea>
                                <x-input-error :messages="$errors->get('alamat_ktp')" class="mt-2" />
                            </div>

                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="space-y-1.5">
                                    <label for="rt" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">RT</label>
                                    <input type="text" id="rt" name="rt" value="{{ old('rt', $user->tenant?->rt) }}" placeholder="001">
                                    <x-input-error :messages="$errors->get('rt')" class="mt-2" />
                                </div>
                                <div class="space-y-1.5">
                                    <label for="rw" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">RW</label>
                                    <input type="text" id="rw" name="rw" value="{{ old('rw', $user->tenant?->rw) }}" placeholder="002">
                                    <x-input-error :messages="$errors->get('rw')" class="mt-2" />
                                </div>
                                <div class="space-y-1.5 md:col-span-2">
                                    <label for="tenant-province" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                                    <select id="tenant-province" name="province">
                                        <option value="">Pilih Provinsi</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div class="space-y-1.5">
                                    <label for="tenant-city" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kota / Kabupaten</label>
                                    <select id="tenant-city" name="city" disabled>
                                        <option value="">Pilih Kota</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                                </div>
                                <div class="space-y-1.5">
                                    <label for="tenant-district" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                                    <select id="tenant-district" name="district" disabled>
                                        <option value="">Pilih Kecamatan</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('district')" class="mt-2" />
                                </div>
                                <div class="space-y-1.5">
                                    <label for="tenant-village" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kelurahan</label>
                                    <select id="tenant-village" name="village" disabled>
                                        <option value="">Pilih Kelurahan</option>
                                    </select>
                                    <x-input-error :messages="$errors->get('village')" class="mt-2" />
                                </div>
                            </div>

                            <div class="space-y-1.5">
                                <label for="tenant-address" class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Domisili Sekarang (Jika beda dengan KTP)</label>
                                <textarea id="tenant-address" name="address" rows="2" placeholder="Masukkan alamat lengkap saat ini...">{{ old('address', $user->tenant?->address) }}</textarea>
                                <x-input-error :messages="$errors->get('address')" class="mt-2" />
                            </div>
                        </div>
                    </div>
                @endif

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

        @if ($user->isAnyAdmin())
            <script>
                const baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';
                const pSelect = document.getElementById('province-select');
                const cSelect = document.getElementById('city-select');
                const dSelect = document.getElementById('district-select');
                const vSelect = document.getElementById('village-select');

                const pInputId = document.getElementById('province_id_input');
                const pInputName = document.getElementById('province_name_input');
                const cInputId = document.getElementById('city_id_input');
                const cInputName = document.getElementById('city_name_input');
                const dInputId = document.getElementById('district_id_input');
                const dInputName = document.getElementById('district_name_input');
                const vInputId = document.getElementById('village_id_input');
                const vInputName = document.getElementById('village_name_input');

                async function initAddress() {
                    // Load provinces
                    const res = await fetch(`${baseUrl}/provinces.json`);
                    const provinces = await res.json();
                    provinces.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.textContent = p.name;
                        if (p.id === pInputId.value) opt.selected = true;
                        pSelect.appendChild(opt);
                    });

                    if (pInputId.value) {
                        await loadCities(pInputId.value, cInputId.value);
                    }
                    if (cInputId.value) {
                        await loadDistricts(cInputId.value, dInputId.value);
                    }
                    if (dInputId.value) {
                        await loadVillages(dInputId.value, vInputId.value);
                    }
                }

                async function loadCities(provinceId, selectedId = null) {
                    resetSelect(cSelect, 'Kota/Kabupaten');
                    resetSelect(dSelect, 'Kecamatan');
                    resetSelect(vSelect, 'Kelurahan/Desa');
                    const res = await fetch(`${baseUrl}/regencies/${provinceId}.json`);
                    const cities = await res.json();
                    cSelect.disabled = false;
                    cities.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.name;
                        if (item.id === selectedId) opt.selected = true;
                        cSelect.appendChild(opt);
                    });
                }

                async function loadDistricts(cityId, selectedId = null) {
                    resetSelect(dSelect, 'Kecamatan');
                    resetSelect(vSelect, 'Kelurahan/Desa');
                    const res = await fetch(`${baseUrl}/districts/${cityId}.json`);
                    const districts = await res.json();
                    dSelect.disabled = false;
                    districts.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.name;
                        if (item.id === selectedId) opt.selected = true;
                        dSelect.appendChild(opt);
                    });
                }

                async function loadVillages(districtId, selectedId = null) {
                    resetSelect(vSelect, 'Kelurahan/Desa');
                    const res = await fetch(`${baseUrl}/villages/${districtId}.json`);
                    const villages = await res.json();
                    vSelect.disabled = false;
                    villages.forEach(item => {
                        const opt = document.createElement('option');
                        opt.value = item.id;
                        opt.textContent = item.name;
                        if (item.id === selectedId) opt.selected = true;
                        vSelect.appendChild(opt);
                    });
                }

                function resetSelect(el, label) { el.innerHTML = `<option value="">Pilih ${label}</option>`; el.disabled = true; }

                pSelect.addEventListener('change', async function() {
                    pInputId.value = this.value;
                    pInputName.value = this.value ? this.options[this.selectedIndex].text : '';
                    cInputId.value = ''; cInputName.value = '';
                    dInputId.value = ''; dInputName.value = '';
                    vInputId.value = ''; vInputName.value = '';
                    if (this.value) {
                        await loadCities(this.value);
                    } else {
                        resetSelect(cSelect, 'Kota/Kabupaten'); resetSelect(dSelect, 'Kecamatan'); resetSelect(vSelect, 'Kelurahan/Desa');
                    }
                });

                cSelect.addEventListener('change', async function() {
                    cInputId.value = this.value;
                    cInputName.value = this.value ? this.options[this.selectedIndex].text : '';
                    dInputId.value = ''; dInputName.value = '';
                    vInputId.value = ''; vInputName.value = '';
                    if (this.value) {
                        await loadDistricts(this.value);
                    } else {
                        resetSelect(dSelect, 'Kecamatan'); resetSelect(vSelect, 'Kelurahan/Desa');
                    }
                });

                dSelect.addEventListener('change', async function() {
                    dInputId.value = this.value;
                    dInputName.value = this.value ? this.options[this.selectedIndex].text : '';
                    vInputId.value = ''; vInputName.value = '';
                    if (this.value) {
                        await loadVillages(this.value);
                    } else {
                        resetSelect(vSelect, 'Kelurahan/Desa');
                    }
                });

                vSelect.addEventListener('change', function() {
                    vInputId.value = this.value;
                    vInputName.value = this.value ? this.options[this.selectedIndex].text : '';
                });

                document.addEventListener('DOMContentLoaded', () => {
                    if (pSelect) {
                        initAddress();
                    }
                });
            </script>
        @endif

        @if ($user->isUser())
            <script>
                // Image Preview Logic
                function previewImage(input, previewId) {
                    const preview = document.getElementById(previewId);
                    if (input.files && input.files[0]) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            preview.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                            preview.classList.remove('border-dashed');
                            preview.classList.add('border-solid', 'border-blue-500');
                        }
                        reader.readAsDataURL(input.files[0]);
                    }
                }

                // IndoRegion API Logic (Using emsifa API) for Tenant Profile
                const tenantBaseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

                async function fetchTenantData(url) {
                    try {
                        const response = await fetch(url);
                        return await response.json();
                    } catch (error) {
                        console.error('Error fetching region data:', error);
                        return [];
                    }
                }

                const tpSelect = document.getElementById('tenant-province');
                const tcSelect = document.getElementById('tenant-city');
                const tdSelect = document.getElementById('tenant-district');
                const tvSelect = document.getElementById('tenant-village');

                async function initTenantAddress() {
                    const savedProvince = "{{ $user->tenant?->province ?? '' }}";
                    const savedCity = "{{ $user->tenant?->city ?? '' }}";
                    const savedDistrict = "{{ $user->tenant?->district ?? '' }}";
                    const savedVillage = "{{ $user->tenant?->village ?? '' }}";

                    // 1. Fetch Provinces
                    const provinces = await fetchTenantData(`${tenantBaseUrl}/provinces.json`);
                    let selectedProvinceId = null;
                    provinces.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.name;
                        opt.dataset.id = p.id;
                        opt.textContent = p.name;
                        if (savedProvince && p.name.toUpperCase() === savedProvince.toUpperCase()) {
                            opt.selected = true;
                            selectedProvinceId = p.id;
                        }
                        tpSelect.appendChild(opt);
                    });

                    if (selectedProvinceId) {
                        // 2. Fetch Cities
                        const cities = await fetchTenantData(`${tenantBaseUrl}/regencies/${selectedProvinceId}.json`);
                        let selectedCityId = null;
                        tcSelect.disabled = false;
                        cities.forEach(c => {
                            const opt = document.createElement('option');
                            opt.value = c.name;
                            opt.dataset.id = c.id;
                            opt.textContent = c.name;
                            if (savedCity && c.name.toUpperCase() === savedCity.toUpperCase()) {
                                opt.selected = true;
                                selectedCityId = c.id;
                            }
                            tcSelect.appendChild(opt);
                        });

                        if (selectedCityId) {
                            // 3. Fetch Districts
                            const districts = await fetchTenantData(`${tenantBaseUrl}/districts/${selectedCityId}.json`);
                            let selectedDistrictId = null;
                            tdSelect.disabled = false;
                            districts.forEach(d => {
                                const opt = document.createElement('option');
                                opt.value = d.name;
                                opt.dataset.id = d.id;
                                opt.textContent = d.name;
                                if (savedDistrict && d.name.toUpperCase() === savedDistrict.toUpperCase()) {
                                    opt.selected = true;
                                    selectedDistrictId = d.id;
                                }
                                tdSelect.appendChild(opt);
                            });

                            if (selectedDistrictId) {
                                // 4. Fetch Villages
                                const villages = await fetchTenantData(`${tenantBaseUrl}/villages/${selectedDistrictId}.json`);
                                tvSelect.disabled = false;
                                villages.forEach(v => {
                                    const opt = document.createElement('option');
                                    opt.value = v.name;
                                    opt.textContent = v.name;
                                    if (savedVillage && v.name.toUpperCase() === savedVillage.toUpperCase()) {
                                        opt.selected = true;
                                    }
                                    tvSelect.appendChild(opt);
                                });
                            }
                        }
                    }
                }

                if (tpSelect) {
                    tpSelect.addEventListener('change', async function() {
                        const provinceId = this.options[this.selectedIndex].dataset.id;
                        tcSelect.innerHTML = '<option value="">Pilih Kota</option>'; tcSelect.disabled = true;
                        tdSelect.innerHTML = '<option value="">Pilih Kecamatan</option>'; tdSelect.disabled = true;
                        tvSelect.innerHTML = '<option value="">Pilih Kelurahan</option>'; tvSelect.disabled = true;

                        if (provinceId) {
                            const cities = await fetchTenantData(`${tenantBaseUrl}/regencies/${provinceId}.json`);
                            cities.forEach(c => {
                                const opt = document.createElement('option');
                                opt.value = c.name; opt.dataset.id = c.id; opt.textContent = c.name;
                                tcSelect.appendChild(opt);
                            });
                            tcSelect.disabled = false;
                        }
                    });

                    tcSelect.addEventListener('change', async function() {
                        const cityId = this.options[this.selectedIndex].dataset.id;
                        tdSelect.innerHTML = '<option value="">Pilih Kecamatan</option>'; tdSelect.disabled = true;
                        tvSelect.innerHTML = '<option value="">Pilih Kelurahan</option>'; tvSelect.disabled = true;

                        if (cityId) {
                            const districts = await fetchTenantData(`${tenantBaseUrl}/districts/${cityId}.json`);
                            districts.forEach(d => {
                                const opt = document.createElement('option');
                                opt.value = d.name; opt.dataset.id = d.id; opt.textContent = d.name;
                                tdSelect.appendChild(opt);
                            });
                            tdSelect.disabled = false;
                        }
                    });

                    tdSelect.addEventListener('change', async function() {
                        const districtId = this.options[this.selectedIndex].dataset.id;
                        tvSelect.innerHTML = '<option value="">Pilih Kelurahan</option>'; tvSelect.disabled = true;

                        if (districtId) {
                            const villages = await fetchTenantData(`${tenantBaseUrl}/villages/${districtId}.json`);
                            villages.forEach(v => {
                                const opt = document.createElement('option');
                                opt.value = v.name; opt.textContent = v.name;
                                tvSelect.appendChild(opt);
                            });
                            tvSelect.disabled = false;
                        }
                    });
                }

                document.addEventListener('DOMContentLoaded', () => {
                    initTenantAddress();
                });
            </script>
        @endif
    </div>
</x-app-layout>

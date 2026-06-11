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
    </div>
</x-app-layout>

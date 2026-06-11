<x-app-layout>
    @section('header_title', 'Add New Room')

    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Tambah Kamar Baru</h2>
                <p class="text-slate-500 font-medium text-sm">Lengkapi detail unit untuk dipublikasikan ke calon penyewa.</p>
            </div>
            <a href="{{ route('rooms.index') }}" class="btn btn-ghost !h-11">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-red-50 border border-red-100 rounded-2xl">
                <ul class="list-disc list-inside text-sm font-bold text-red-500">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data" id="room-form" class="space-y-8">
            @csrf
            
            {{-- SECTION 1: BASIC INFO --}}
            <div class="stat-card !p-0 overflow-hidden">
                <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Informasi Dasar</h3>
                    <div class="w-8 h-8 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs">
                        <i class="fas fa-info"></i>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Properti / Kos</label>
                        <input type="text" name="property_name" id="property_name_input" list="property-name-list"
                               placeholder="Pilih atau ketik nama kos baru..." value="{{ old('property_name') }}" autocomplete="off">
                        <datalist id="property-name-list">
                            @foreach($propertyNames as $pn)
                                <option value="{{ $pn }}">{{ $pn }}</option>
                            @endforeach
                        </datalist>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Kamar</label>
                        <input type="text" name="room_number" placeholder="Contoh: A01" value="{{ old('room_number') }}">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Kamar</label>
                        <input type="text" name="room_type" placeholder="Contoh: Deluxe" value="{{ old('room_type') }}">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga Sewa (Bulan)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-extrabold text-sm">Rp</span>
                            <input type="number" name="price" placeholder="0" value="{{ old('price') }}">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Gender</label>
                        <select name="gender">
                            <option value="gabungan" {{ old('gender') == 'gabungan' ? 'selected' : '' }}>Gabungan</option>
                            <option value="putra" {{ old('gender') == 'putra' ? 'selected' : '' }}>Putra</option>
                            <option value="putri" {{ old('gender') == 'putri' ? 'selected' : '' }}>Putri</option>
                        </select>
                    </div>
                </div>

                {{-- PRICING PROMO --}}
                <div class="px-8 py-8 bg-slate-50/50 border-t border-slate-100">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-2 h-2 rounded-full bg-red-400"></div>
                        <h4 class="text-xs font-black text-slate-900 uppercase tracking-widest">Pricing & Promo (Opsional)</h4>
                    </div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        <div class="col-span-2 md:col-span-1 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Diskon (%)</label>
                            <input type="number" name="discount_percentage" placeholder="0" value="{{ old('discount_percentage', 0) }}">
                        </div>
                        <div class="col-span-2 md:col-span-1 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Label Promo</label>
                            <input type="text" name="discount_label" placeholder="Promo" value="{{ old('discount_label') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl Mulai</label>
                            <input type="date" name="discount_start" value="{{ old('discount_start') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl Selesai</label>
                            <input type="date" name="discount_end" value="{{ old('discount_end') }}">
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: ASSETS & FACILITIES --}}
            <div class="stat-card !p-0 overflow-hidden">
                <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Aset & Fasilitas</h3>
                    <div class="w-8 h-8 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs">
                        <i class="fas fa-couch"></i>
                    </div>
                </div>
                <div class="p-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                    @foreach($assets as $asset)
                        <label class="relative group cursor-pointer">
                            <input type="checkbox" name="assets[]" value="{{ $asset->id }}" class="peer hidden" 
                                   {{ is_array(old('assets')) && in_array($asset->id, old('assets')) ? 'checked' : '' }}>
                            <div class="p-5 rounded-2xl border-2 border-slate-100 bg-white peer-checked:border-brand peer-checked:bg-brand-light/30 transition-all flex flex-col items-center text-center gap-3">
                                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 peer-checked:text-brand peer-checked:bg-white peer-checked:shadow-sm transition-all">
                                    <i class="{{ $asset->icon ?: 'fas fa-check' }} text-lg"></i>
                                </div>
                                <span class="text-[10px] font-black uppercase text-slate-500 tracking-widest group-hover:text-brand transition-colors">{{ $asset->name }}</span>
                            </div>
                            <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                <div class="w-5 h-5 bg-brand text-white rounded-full flex items-center justify-center shadow-md">
                                    <i class="fas fa-check text-[8px]"></i>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
            </div>

            {{-- SECTION 3: LOCATION --}}
            <div class="stat-card !p-0 overflow-hidden">
                <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Lokasi Properti</h3>
                    <div class="w-8 h-8 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs">
                        <i class="fas fa-map-marker-alt"></i>
                    </div>
                </div>
                <div class="p-8 space-y-8">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Provinsi</label>
                            <select id="province-select" name="province_id">
                                <option value="">Pilih Provinsi</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota / Kabupaten</label>
                            <select id="city-select" name="city_id" disabled>
                                <option value="">Pilih Kota/Kabupaten</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                            <select id="district-select" name="district_id" disabled>
                                <option value="">Pilih Kecamatan</option>
                            </select>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelurahan / Desa</label>
                            <select id="village-select" name="village_id" disabled>
                                <option value="">Pilih Kelurahan/Desa</option>
                            </select>
                        </div>
                    </div>
                    <div class="space-y-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="address" rows="2" placeholder="Jl. Merdeka No. 123...">{{ old('address', auth()->user()->address) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi Tambahan</label>
                            <textarea name="description" rows="4" placeholder="Detail spesifik kamar...">{{ old('description') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 4: GALLERY --}}
            <div class="stat-card !p-0 overflow-hidden">
                <div class="px-8 py-5 bg-slate-50/50 border-b border-slate-100 flex items-center justify-between">
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Foto Unit Kamar</h3>
                    <div class="w-8 h-8 rounded-lg bg-brand-light text-brand flex items-center justify-center text-xs">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="p-8">
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4" id="image-preview-container">
                        <div id="dropzone" class="aspect-square rounded-2xl border-2 border-dashed border-slate-200 bg-white flex flex-col items-center justify-center cursor-pointer hover:border-brand hover:bg-brand-light/10 transition-all group relative overflow-hidden">
                            <input id="file-upload" name="picture[]" type="file" class="absolute inset-0 opacity-0 cursor-pointer" multiple onchange="handleFileSelect(event)">
                            <div class="w-10 h-10 rounded-full bg-slate-50 flex items-center justify-center text-slate-400 group-hover:bg-white group-hover:text-brand transition-all mb-2 shadow-sm">
                                <i class="fas fa-plus text-sm"></i>
                            </div>
                            <span class="text-[10px] font-black text-slate-400 group-hover:text-brand uppercase tracking-widest">Tambah Foto</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SUBMIT BUTTON --}}
            <div class="flex items-center justify-end gap-4 pb-12">
                <a href="{{ route('rooms.index') }}" class="px-8 py-4 text-sm font-bold text-slate-500 hover:text-slate-700 transition-colors">Batal</a>
                <button type="submit" class="px-10 py-5 bg-brand text-white rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-brand-dark transition-all transform active:scale-[0.98] shadow-xl shadow-brand/20">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Unit Sekarang
                </button>
            </div>

            <input type="hidden" id="default_province_id" value="{{ old('province_id', auth()->user()->province_id) }}">
            <input type="hidden" id="default_province" value="{{ old('province', auth()->user()->province) }}">
            <input type="hidden" id="default_city_id" value="{{ old('city_id', auth()->user()->city_id) }}">
            <input type="hidden" id="default_city" value="{{ old('city', auth()->user()->city) }}">
            <input type="hidden" id="default_district_id" value="{{ old('district_id', auth()->user()->district_id) }}">
            <input type="hidden" id="default_district" value="{{ old('district', auth()->user()->district) }}">
            <input type="hidden" id="default_village_id" value="{{ old('village_id', auth()->user()->village_id) }}">
            <input type="hidden" id="default_village" value="{{ old('village', auth()->user()->village) }}">
        </form>
    </div>

    <script>
        let fileQueue = new DataTransfer();
        const fileInput = document.getElementById('file-upload');
        const container = document.getElementById('image-preview-container');
        const dropzone = document.getElementById('dropzone');

        const baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';
        const pSelect = document.getElementById('province-select');
        const cSelect = document.getElementById('city-select');
        const dSelect = document.getElementById('district-select');
        const vSelect = document.getElementById('village-select');

        // Append hidden inputs for names if they don't exist
        const names = ['province', 'city', 'district', 'village'];
        names.forEach(name => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name;
            input.id = name + '-name-input';
            document.getElementById('room-form').appendChild(input);
        });

        const pInputName = document.getElementById('province-name-input');
        const cInputName = document.getElementById('city-name-input');
        const dInputName = document.getElementById('district-name-input');
        const vInputName = document.getElementById('village-name-input');

        const defProvinceId = document.getElementById('default_province_id').value;
        const defProvinceName = document.getElementById('default_province').value;
        const defCityId = document.getElementById('default_city_id').value;
        const defCityName = document.getElementById('default_city').value;
        const defDistrictId = document.getElementById('default_district_id').value;
        const defDistrictName = document.getElementById('default_district').value;
        const defVillageId = document.getElementById('default_village_id').value;
        const defVillageName = document.getElementById('default_village').value;

        async function initAddress() {
            // Load provinces
            const res = await fetch(`${baseUrl}/provinces.json`);
            const provinces = await res.json();
            provinces.forEach(p => {
                const opt = document.createElement('option');
                opt.value = p.id;
                opt.textContent = p.name;
                if (p.id === defProvinceId) opt.selected = true;
                pSelect.appendChild(opt);
            });

            if (defProvinceId) {
                pInputName.value = defProvinceName;
                await loadCities(defProvinceId, defCityId);
            }
            if (defCityId) {
                cInputName.value = defCityName;
                await loadDistricts(defCityId, defDistrictId);
            }
            if (defDistrictId) {
                dInputName.value = defDistrictName;
                await loadVillages(defDistrictId, defVillageId);
            }
            if (defVillageId) {
                vInputName.value = defVillageName;
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
            pInputName.value = this.value ? this.options[this.selectedIndex].text : '';
            cInputName.value = '';
            dInputName.value = '';
            vInputName.value = '';
            if (this.value) {
                await loadCities(this.value);
            } else {
                resetSelect(cSelect, 'Kota/Kabupaten'); resetSelect(dSelect, 'Kecamatan'); resetSelect(vSelect, 'Kelurahan/Desa');
            }
        });

        cSelect.addEventListener('change', async function() {
            cInputName.value = this.value ? this.options[this.selectedIndex].text : '';
            dInputName.value = '';
            vInputName.value = '';
            if (this.value) {
                await loadDistricts(this.value);
            } else {
                resetSelect(dSelect, 'Kecamatan'); resetSelect(vSelect, 'Kelurahan/Desa');
            }
        });

        dSelect.addEventListener('change', async function() {
            dInputName.value = this.value ? this.options[this.selectedIndex].text : '';
            vInputName.value = '';
            if (this.value) {
                await loadVillages(this.value);
            } else {
                resetSelect(vSelect, 'Kelurahan/Desa');
            }
        });

        vSelect.addEventListener('change', function() {
            vInputName.value = this.value ? this.options[this.selectedIndex].text : '';
        });

        document.addEventListener('DOMContentLoaded', () => {
            initAddress();
        });

        function handleFileSelect(event) {
            const files = event.target.files;
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                fileQueue.items.add(file);
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'aspect-square rounded-2xl border border-slate-100 bg-white overflow-hidden relative group shadow-sm';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button type="button" onclick="removeFromFileQueue('${file.name}', ${file.size}, this)" class="w-8 h-8 bg-red-500 text-white rounded-lg shadow-lg"><i class="fas fa-times text-[10px]"></i></button>
                        </div>`;
                    container.insertBefore(div, dropzone);
                }
                reader.readAsDataURL(file);
            }
            fileInput.files = fileQueue.files;
        }

        function removeFromFileQueue(name, size, btn) {
            const newQueue = new DataTransfer();
            const currentFiles = fileQueue.files;
            let removed = false;
            for (let i = 0; i < currentFiles.length; i++) {
                if (!removed && currentFiles[i].name === name && currentFiles[i].size === size) { removed = true; continue; }
                newQueue.items.add(currentFiles[i]);
            }
            fileQueue = newQueue; fileInput.files = fileQueue.files;
            btn.closest('div.aspect-square').remove();
        }
    </script>
</x-app-layout>

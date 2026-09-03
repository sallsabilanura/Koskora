<x-app-layout>
    @section('header_title', 'Edit Room')

    <div class="max-w-4xl mx-auto space-y-8 animate-fade-in">
        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Edit Kamar #{{ $room->room_number }}</h2>
                <p class="text-slate-500 font-medium text-sm">Ubah detail unit untuk menyesuaikan informasi terbaru.</p>
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

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl text-sm font-bold text-emerald-600 flex items-center">
                <i class="fas fa-check-circle mr-2"></i> {{ $message }}
            </div>
        @endif

        <form action="{{ route('rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" id="room-form" class="space-y-8">
            @csrf
            @method('PUT')
            
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
                        <input type="text" name="property_name" value="{{ old('property_name', $room->property_name) }}" placeholder="Contoh: Kos Kalibata City">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nomor Kamar</label>
                        <input type="text" name="room_number" placeholder="Contoh: A01" value="{{ old('room_number', $room->room_number) }}">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tipe Kamar</label>
                        <input type="text" name="room_type" placeholder="Contoh: Deluxe" value="{{ old('room_type', $room->room_type) }}">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Harga Sewa (Bulan)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-extrabold text-sm">Rp</span>
                            <input type="number" name="price" placeholder="0" value="{{ old('price', $room->price) }}">
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Deposit (Opsional)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-extrabold text-sm">Rp</span>
                            <input type="number" name="deposit" placeholder="0" value="{{ old('deposit', $room->deposit) }}">
                        </div>
                        <p class="text-[10px] text-slate-400 ml-1">Kosongkan jika tidak ada deposit.</p>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kategori Gender</label>
                        <select name="gender">
                            <option value="gabungan" {{ old('gender', $room->gender) == 'gabungan' ? 'selected' : '' }}>Gabungan</option>
                            <option value="putra" {{ old('gender', $room->gender) == 'putra' ? 'selected' : '' }}>Putra</option>
                            <option value="putri" {{ old('gender', $room->gender) == 'putri' ? 'selected' : '' }}>Putri</option>
                        </select>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Kamar</label>
                        <select name="status">
                            <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
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
                            <input type="number" name="discount_percentage" placeholder="0" value="{{ old('discount_percentage', $room->discount_percentage) }}">
                        </div>
                        <div class="col-span-2 md:col-span-1 space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Label Promo</label>
                            <input type="text" name="discount_label" placeholder="Promo" value="{{ old('discount_label', $room->discount_label) }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl Mulai</label>
                            <input type="date" name="discount_start" value="{{ old('discount_start', $room->discount_start ? $room->discount_start->format('Y-m-d') : '') }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl Selesai</label>
                            <input type="date" name="discount_end" value="{{ old('discount_end', $room->discount_end ? $room->discount_end->format('Y-m-d') : '') }}">
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
                <div class="p-8">
                    @php
                        $roomAssetIds = $room->assets->pluck('id')->toArray();
                        $groupedAssets = collect($assets)->groupBy('category');
                        $categoryTitles = [
                            'fasilitas_kamar' => 'Fasilitas Kamar',
                            'fasilitas_kamar_mandi' => 'Fasilitas Kamar Mandi',
                            'fasilitas_umum' => 'Fasilitas Umum',
                            'fasilitas_parkir' => 'Fasilitas Parkir',
                            'peraturan' => 'Peraturan Kos/Kamar',
                        ];
                    @endphp

                    <div class="space-y-8">
                        @foreach($groupedAssets as $category => $categoryAssets)
                            <div>
                                <h3 class="text-xs font-black text-slate-400 uppercase tracking-widest mb-4">{{ $categoryTitles[$category] ?? ucfirst(str_replace('_', ' ', $category)) }}</h3>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    @foreach($categoryAssets as $asset)
                                        <label class="relative group cursor-pointer">
                                            <input type="checkbox" name="assets[]" value="{{ $asset->id }}" class="peer hidden" 
                                                   {{ in_array($asset->id, old('assets', $roomAssetIds)) ? 'checked' : '' }}>
                                            <div class="p-5 rounded-2xl border-2 border-slate-100 bg-white peer-checked:border-brand peer-checked:bg-brand-light/30 transition-all flex flex-col items-center text-center gap-3 hover:border-slate-200">
                                                <div class="w-12 h-12 rounded-xl bg-slate-50 flex items-center justify-center text-slate-400 peer-checked:text-brand peer-checked:bg-white peer-checked:shadow-sm transition-all group-hover:scale-110">
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
                        @endforeach
                    </div>
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
                                <option value="">{{ $room->province ?: 'Pilih Provinsi' }}</option>
                            </select>
                            <input type="hidden" name="province" id="province-name" value="{{ $room->province }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kota / Kabupaten</label>
                            <select id="city-select" name="city_id">
                                <option value="">{{ $room->city ?: 'Pilih Kota/Kabupaten' }}</option>
                            </select>
                            <input type="hidden" name="city" id="city-name" value="{{ $room->city }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kecamatan</label>
                            <select id="district-select" name="district_id">
                                <option value="">{{ $room->district ?: 'Pilih Kecamatan' }}</option>
                            </select>
                            <input type="hidden" name="district" id="district-name" value="{{ $room->district }}">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kelurahan / Desa</label>
                            <select id="village-select" name="village_id">
                                <option value="">{{ $room->village ?: 'Pilih Kelurahan/Desa' }}</option>
                            </select>
                            <input type="hidden" name="village" id="village-name" value="{{ $room->village }}">
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                            <textarea name="address" rows="2" placeholder="Jl. Merdeka No. 123...">{{ old('address', $room->address) }}</textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Deskripsi Tentang Kos/Kamar</label>
                            <textarea name="description" rows="2" placeholder="Detail spesifik kamar...">{{ old('description', $room->description) }}</textarea>
                        </div>
                        <div class="md:col-span-2 space-y-2">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Peraturan Khusus & Biaya Tambahan</label>
                            <textarea name="additional_rules" rows="3" placeholder="Contoh: Tamu menginap Rp50.000, Tambah elektronik Rp50.000/item">{{ old('additional_rules', $room->additional_rules) }}</textarea>
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
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4" id="gallery-container">
                        <!-- Existing Photos -->
                        @if($room->picture)
                            @foreach($room->picture as $img)
                                <div class="relative aspect-square rounded-2xl border border-slate-100 bg-white shadow-sm overflow-hidden group">
                                    <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" onclick="confirmDeleteImage('{{ $img }}')" class="w-8 h-8 bg-red-500 text-white rounded-lg shadow-lg">
                                            <i class="fas fa-times text-[10px]"></i>
                                        </button>
                                    </div>
                                    <span class="absolute top-2 left-2 bg-white/90 backdrop-blur px-2 py-0.5 rounded-lg text-[8px] font-black uppercase text-slate-500">Tersimpan</span>
                                </div>
                            @endforeach
                        @endif

                        <!-- Dropzone -->
                        <div id="dropzone" class="aspect-square rounded-2xl border-2 border-dashed border-slate-200 bg-white flex flex-col items-center justify-center cursor-pointer hover:border-brand hover:bg-brand-light/10 transition-all group relative overflow-hidden">
                            <input id="file-upload" name="picture[]" type="file" class="absolute inset-0 opacity-0 cursor-pointer" multiple onchange="handleQueueFiles(event)">
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
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
    </div>

    <!-- Hidden Delete Form -->
    <form id="delete-image-form" action="{{ route('rooms.image.destroy', $room->id) }}" method="POST" class="hidden">
        @csrf
        <input type="hidden" name="image_path" id="delete-image-path">
    </form>

    <script>
        // Global variables for file handling
        let fileQueue = new DataTransfer();
        const fileInput = document.getElementById('file-upload');
        const container = document.getElementById('gallery-container');
        const dropzone = document.getElementById('dropzone');

        function confirmDeleteImage(path) {
            if (confirm('Hapus foto ini dari galeri secara permanen?')) {
                document.getElementById('delete-image-path').value = path;
                document.getElementById('delete-image-form').submit();
            }
        }

        // --- Location API Integration ---
        const baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';
        const pSelect = document.getElementById('province-select');
        const cSelect = document.getElementById('city-select');
        const dSelect = document.getElementById('district-select');
        const vSelect = document.getElementById('village-select');
        
        const pName = document.getElementById('province-name');
        const cName = document.getElementById('city-name');
        const dName = document.getElementById('district-name');
        const vName = document.getElementById('village-name');

        // Fetch Provinces
        fetch(`${baseUrl}/provinces.json`)
            .then(res => res.json())
            .then(data => {
                data.forEach(p => {
                    const opt = document.createElement('option');
                    opt.value = p.id;
                    opt.textContent = p.name;
                    pSelect.appendChild(opt);
                });
            });

        pSelect.addEventListener('change', function() {
            resetSelect(cSelect, 'Kota/Kabupaten');
            resetSelect(dSelect, 'Kecamatan');
            resetSelect(vSelect, 'Kelurahan/Desa');
            
            if (this.value) {
                pName.value = this.options[this.selectedIndex].text;
                fetch(`${baseUrl}/regencies/${this.value}.json`)
                    .then(res => res.json())
                    .then(data => {
                        cSelect.disabled = false;
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.name;
                            cSelect.appendChild(opt);
                        });
                    });
            } else {
                pName.value = '';
            }
        });

        cSelect.addEventListener('change', function() {
            resetSelect(dSelect, 'Kecamatan');
            resetSelect(vSelect, 'Kelurahan/Desa');
            
            if (this.value) {
                cName.value = this.options[this.selectedIndex].text;
                fetch(`${baseUrl}/districts/${this.value}.json`)
                    .then(res => res.json())
                    .then(data => {
                        dSelect.disabled = false;
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.name;
                            dSelect.appendChild(opt);
                        });
                    });
            } else {
                cName.value = '';
            }
        });

        dSelect.addEventListener('change', function() {
            resetSelect(vSelect, 'Kelurahan/Desa');
            
            if (this.value) {
                dName.value = this.options[this.selectedIndex].text;
                fetch(`${baseUrl}/villages/${this.value}.json`)
                    .then(res => res.json())
                    .then(data => {
                        vSelect.disabled = false;
                        data.forEach(item => {
                            const opt = document.createElement('option');
                            opt.value = item.id;
                            opt.textContent = item.name;
                            vSelect.appendChild(opt);
                        });
                    });
            } else {
                dName.value = '';
            }
        });

        vSelect.addEventListener('change', function() {
            if (this.value) {
                vName.value = this.options[this.selectedIndex].text;
            } else {
                vName.value = '';
            }
        });

        function resetSelect(el, label) {
            el.innerHTML = `<option value="">Pilih ${label}</option>`;
            el.disabled = true;
        }

        // --- File Queue Logic ---
        function handleQueueFiles(event) {
            const files = event.target.files;

            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                fileQueue.items.add(file);

                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'new-queue-preview aspect-square rounded-3xl border-2 border-blue-200 bg-blue-50 shadow-sm overflow-hidden relative group';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-blue-600 bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                             <button type="button" onclick="removeFromEditQueue('${file.name}', ${file.size}, this)" class="p-2 bg-rose-500 text-white rounded-xl hover:bg-rose-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                             </button>
                        </div>
                        <span class="absolute top-2 left-2 bg-blue-600 text-white px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-widest">New</span>
                    `;
                    container.insertBefore(div, dropzone);
                }
                reader.readAsDataURL(file);
            }
            fileInput.files = fileQueue.files;
        }

        function removeFromEditQueue(name, size, btn) {
            const newQueue = new DataTransfer();
            const currentFiles = fileQueue.files;
            let removed = false;

            for (let i = 0; i < currentFiles.length; i++) {
                if (!removed && currentFiles[i].name === name && currentFiles[i].size === size) {
                    removed = true;
                    continue;
                }
                newQueue.items.add(currentFiles[i]);
            }

            fileQueue = newQueue;
            fileInput.files = fileQueue.files;
            btn.closest('.new-queue-preview').remove();
        }
    </script>
</x-app-layout>

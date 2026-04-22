<x-app-layout>
    @section('header_title', 'Add New Room')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800">Tambah Kamar Baru</h2>
            <a href="{{ route('rooms.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-xl font-semibold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-200 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm">
                <ul class="list-disc list-inside text-sm font-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 md:p-12">
            <form action="{{ route('rooms.store') }}" method="POST" enctype="multipart/form-data" class="space-y-10" id="room-form">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Property Name (Smart Combo-box) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Properti / Kos</label>
                        <div class="relative">
                            <input
                                type="text"
                                name="property_name"
                                id="property_name_input"
                                list="property-name-list"
                                class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold pr-10"
                                placeholder="Pilih yang ada atau ketik nama baru..."
                                value="{{ old('property_name') }}"
                                autocomplete="off"
                            >
                            <datalist id="property-name-list">
                                @foreach($propertyNames as $pn)
                                    <option value="{{ $pn }}">{{ $pn }}</option>
                                @endforeach
                            </datalist>
                            <div class="absolute right-4 top-1/2 -translate-y-1/2 pointer-events-none text-slate-300">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                        <p class="text-xs text-slate-400 mt-1.5">
                            <span class="font-bold text-blue-500">Pilih</span> nama kos yang sudah ada, atau <span class="font-bold text-emerald-500">ketik baru</span> untuk membuat properti baru.
                            Kamar dengan nama yang sama akan otomatis dikelompokkan.
                        </p>
                    </div>

                    <!-- Room Number -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">No Kamar</label>
                        <input type="text" name="room_number" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold" placeholder="Contoh: A01" value="{{ old('room_number') }}">
                    </div>

                    <!-- Room Type -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tipe Kamar</label>
                        <input type="text" name="room_type" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold" placeholder="Contoh: Deluxe" value="{{ old('room_type') }}">
                    </div>

                    <!-- Price -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga Sewa (Rp / Bulan)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="price" class="w-full pl-12 rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-black text-slate-700" placeholder="0" value="{{ old('price') }}">
                        </div>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Kamar</label>
                        <select name="status" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="available" {{ old('status') == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status') == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ old('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>

                    <!-- Gender Category -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Gender</label>
                        <select name="gender" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="gabungan" {{ old('gender') == 'gabungan' ? 'selected' : '' }}>Gabungan (Putra/Putri)</option>
                            <option value="putra" {{ old('gender') == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                            <option value="putri" {{ old('gender') == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                        </select>
                    </div>
                </div>

                <!-- Room Assets -->
                <div class="pt-8 border-t border-slate-100">
                    <label class="block text-sm font-bold text-slate-700 mb-4">Aset & Fasilitas Kamar</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($assets as $asset)
                            <label class="relative group cursor-pointer">
                                <input type="checkbox" name="assets[]" value="{{ $asset->id }}" class="peer hidden" {{ is_array(old('assets')) && in_array($asset->id, old('assets')) ? 'checked' : '' }}>
                                <div class="p-4 rounded-2xl border-2 border-slate-100 bg-slate-50 peer-checked:border-blue-500 peer-checked:bg-blue-50 peer-checked:text-blue-700 transition-all hover:border-slate-200 flex flex-col items-center text-center space-y-2">
                                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center group-hover:scale-110 transition-transform">
                                        @if($asset->icon)
                                            <i class="{{ $asset->icon }} text-lg"></i>
                                        @else
                                            <svg class="w-6 h-6 text-slate-400 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                                        @endif
                                    </div>
                                    <span class="text-xs font-bold uppercase tracking-tight">{{ $asset->name }}</span>
                                </div>
                                <div class="absolute top-2 right-2 opacity-0 peer-checked:opacity-100 transition-opacity">
                                    <svg class="w-5 h-5 text-blue-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                </div>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Location Selector -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 border-t border-slate-100">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Provinsi</label>
                        <select id="province-select" name="province" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="">Pilih Provinsi</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kota / Kabupaten</label>
                        <select id="city-select" name="city" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600" disabled>
                            <option value="">Pilih Kota/Kabupaten</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kecamatan</label>
                        <select id="district-select" name="district" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600" disabled>
                            <option value="">Pilih Kecamatan</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kelurahan / Desa</label>
                        <select id="village-select" name="village" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600" disabled>
                            <option value="">Pilih Kelurahan/Desa</option>
                        </select>
                    </div>
                </div>

                <!-- Address -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap Kamar (Jalan, No Rumah, dll)</label>
                    <textarea name="address" rows="3" class="w-full rounded-3xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-medium text-slate-600 placeholder:text-slate-300" placeholder="Contoh: Jl. Merdeka No. 123...">{{ old('address') }}</textarea>
                </div>

                <!-- Description -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi & Fasilitas</label>
                    <textarea name="description" rows="5" class="w-full rounded-3xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all placeholder:text-slate-300" placeholder="Detail fasilitas kamar (AC, Kamar Mandi Dalam, Kasur, dll)...">{{ old('description') }}</textarea>
                </div>

                <!-- Picture / Gallery -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-bold text-slate-700">Gallery Foto Kamar</label>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-full">Bisa pilih berkali-kali</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4" id="image-preview-container">
                        <!-- Dropzone -->
                        <div id="dropzone" class="aspect-square rounded-3xl border-4 border-dashed border-slate-100 bg-slate-50 flex flex-col items-center justify-center cursor-pointer hover:bg-white hover:border-blue-400 transition-all group overflow-hidden relative">
                            <input id="file-upload" name="picture[]" type="file" class="absolute inset-0 opacity-0 cursor-pointer" multiple onchange="handleFileSelect(event)">
                            <div class="text-center group-hover:scale-110 transition-transform pointer-events-none">
                                <svg class="w-8 h-8 mx-auto text-slate-300 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-[10px] font-black text-slate-400 mt-2 block group-hover:text-blue-600">Tambah Foto</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 italic">Klik berkali-kali untuk menambah lebih banyak foto (kamar mandi, dapur, dll).</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-8 border-t border-slate-50">
                    <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-2xl font-black text-lg hover:bg-blue-700 shadow-2xl shadow-blue-100 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                        <span>SIMPAN DATA KAMAR</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
    <script>
        // Global variables for file handling
        let fileQueue = new DataTransfer();
        const fileInput = document.getElementById('file-upload');
        const container = document.getElementById('image-preview-container');
        const dropzone = document.getElementById('dropzone');

        // --- Location API Integration ---
        const baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';
        const pSelect = document.getElementById('province-select');
        const cSelect = document.getElementById('city-select');
        const dSelect = document.getElementById('district-select');
        const vSelect = document.getElementById('village-select');

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
                document.getElementById('province-name-input').value = this.options[this.selectedIndex].text;
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
                document.getElementById('province-name-input').value = '';
            }
        });

        cSelect.addEventListener('change', function() {
            resetSelect(dSelect, 'Kecamatan');
            resetSelect(vSelect, 'Kelurahan/Desa');
            
            if (this.value) {
                document.getElementById('city-name-input').value = this.options[this.selectedIndex].text;
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
                document.getElementById('city-name-input').value = '';
            }
        });

        dSelect.addEventListener('change', function() {
            resetSelect(vSelect, 'Kelurahan/Desa');
            
            if (this.value) {
                document.getElementById('district-name-input').value = this.options[this.selectedIndex].text;
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
                document.getElementById('district-name-input').value = '';
            }
        });

        vSelect.addEventListener('change', function() {
            if (this.value) {
                document.getElementById('village-name-input').value = this.options[this.selectedIndex].text;
            } else {
                document.getElementById('village-name-input').value = '';
            }
        });

        function resetSelect(el, label) {
            el.innerHTML = `<option value="">Pilih ${label}</option>`;
            el.disabled = true;
        }

        // --- Hidden Inputs for Names ---
        const names = ['province', 'city', 'district', 'village'];
        names.forEach(name => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = name; // Controller expects string
            input.id = name + '-name-input';
            document.getElementById('room-form').appendChild(input);
        });

        // Update select names so they don't conflict with string names
        pSelect.name = 'province_id';
        cSelect.name = 'city_id';
        dSelect.name = 'district_id';
        vSelect.name = 'village_id';

        // --- File Upload Logic ---
        function handleFileSelect(event) {
            const files = event.target.files;
            
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                
                // Add to DataTransfer
                fileQueue.items.add(file);
                
                // Create Preview
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'preview-card aspect-square rounded-3xl border border-slate-100 bg-white shadow-sm overflow-hidden relative group';
                    div.dataset.name = file.name;
                    div.dataset.size = file.size;
                    
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <button type="button" onclick="removeFromFileQueue('${file.name}', ${file.size}, this)" class="p-2 bg-rose-500 text-white rounded-xl hover:bg-rose-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>
                    `;
                    container.insertBefore(div, dropzone);
                }
                reader.readAsDataURL(file);
            }
            
            // Sync input files with our queue
            fileInput.files = fileQueue.files;
        }

        function removeFromFileQueue(name, size, btn) {
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
            
            // Remove the preview element
            btn.closest('.preview-card').remove();
        }
    </script>
</x-app-layout>

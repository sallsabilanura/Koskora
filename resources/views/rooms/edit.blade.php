<x-app-layout>
    @section('header_title', 'Edit Room')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800">Edit Kamar: {{ $room->room_number }}</h2>
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

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-sm p-8 md:p-12">
            <form action="{{ route('rooms.update', $room->id) }}" method="POST" enctype="multipart/form-data" class="space-y-10" id="room-form">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Property Name -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Nama Properti / Kos</label>
                        <input type="text" name="property_name" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold" placeholder="Contoh: Kos Kalibata City, Kos Tebet Indah..." value="{{ old('property_name', $room->property_name) }}">
                        <p class="text-xs text-slate-400 mt-1.5">Nama properti digunakan untuk mengelompokkan kamar di halaman utama.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">No Kamar</label>
                        <input type="text" name="room_number" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold" value="{{ old('room_number', $room->room_number) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tipe Kamar</label>
                        <input type="text" name="room_type" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold" value="{{ old('room_type', $room->room_type) }}">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Harga Sewa (Rp / Bulan)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold">Rp</span>
                            <input type="number" name="price" class="w-full pl-12 rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-black text-slate-700" value="{{ old('price', $room->price) }}">
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Kamar</label>
                        <select name="status" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="available" {{ old('status', $room->status) == 'available' ? 'selected' : '' }}>Available</option>
                            <option value="occupied" {{ old('status', $room->status) == 'occupied' ? 'selected' : '' }}>Occupied</option>
                            <option value="maintenance" {{ old('status', $room->status) == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>

                    <!-- Gender Category -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kategori Gender</label>
                        <select name="gender" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="gabungan" {{ old('gender', $room->gender) == 'gabungan' ? 'selected' : '' }}>Gabungan (Putra/Putri)</option>
                            <option value="putra" {{ old('gender', $room->gender) == 'putra' ? 'selected' : '' }}>Khusus Putra</option>
                            <option value="putri" {{ old('gender', $room->gender) == 'putri' ? 'selected' : '' }}>Khusus Putri</option>
                        </select>
                    </div>
                </div>

                <!-- Room Assets -->
                <div class="pt-8 border-t border-slate-100">
                    <label class="block text-sm font-bold text-slate-700 mb-4">Aset & Fasilitas Kamar</label>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @php
                            $roomAssetIds = $room->assets->pluck('id')->toArray();
                        @endphp
                        @foreach($assets as $asset)
                            <label class="relative group cursor-pointer">
                                <input type="checkbox" name="assets[]" value="{{ $asset->id }}" class="peer hidden" {{ in_array($asset->id, old('assets', $roomAssetIds)) ? 'checked' : '' }}>
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
                        <select id="province-select" name="province_id" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="">{{ $room->province ?: 'Pilih Provinsi' }}</option>
                        </select>
                        <input type="hidden" name="province" id="province-name" value="{{ $room->province }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kota / Kabupaten</label>
                        <select id="city-select" name="city_id" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="">{{ $room->city ?: 'Pilih Kota/Kabupaten' }}</option>
                        </select>
                        <input type="hidden" name="city" id="city-name" value="{{ $room->city }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kecamatan</label>
                        <select id="district-select" name="district_id" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="">{{ $room->district ?: 'Pilih Kecamatan' }}</option>
                        </select>
                        <input type="hidden" name="district" id="district-name" value="{{ $room->district }}">
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kelurahan / Desa</label>
                        <select id="village-select" name="village_id" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-bold text-slate-600">
                            <option value="">{{ $room->village ?: 'Pilih Kelurahan/Desa' }}</option>
                        </select>
                        <input type="hidden" name="village" id="village-name" value="{{ $room->village }}">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap Kamar (Jalan, No Rumah, dll)</label>
                    <textarea name="address" rows="3" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all font-medium text-slate-700">{{ old('address', $room->address) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Deskripsi & Fasilitas</label>
                    <textarea name="description" rows="5" class="w-full rounded-3xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all">{{ old('description', $room->description) }}</textarea>
                </div>

                <!-- Picture / Gallery -->
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <label class="block text-sm font-bold text-slate-700">Gallery Kamar</label>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-100 px-3 py-1 rounded-full">Tambah atau Hapus Foto</span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-4" id="gallery-container">
                        <!-- Existing Photos -->
                        @if($room->picture)
                            @foreach($room->picture as $img)
                                <div class="relative aspect-square rounded-3xl border border-slate-100 bg-white shadow-sm overflow-hidden group">
                                    <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover">
                                    <div class="absolute inset-0 bg-black bg-opacity-40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <button type="button" onclick="confirmDeleteImage('{{ $img }}')" class="p-2 bg-rose-500 text-white rounded-xl hover:bg-rose-600 transition-colors shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                    <span class="absolute top-2 left-2 bg-white/80 backdrop-blur px-2 py-0.5 rounded-lg text-[8px] font-black uppercase text-slate-500">Stored</span>
                                </div>
                            @endforeach
                        @endif

                        <!-- Dropzone for New Photos -->
                        <div id="dropzone" class="aspect-square rounded-3xl border-4 border-dashed border-slate-100 bg-slate-50 flex flex-col items-center justify-center cursor-pointer hover:bg-white hover:border-blue-400 transition-all group overflow-hidden relative">
                            <input id="file-upload" name="picture[]" type="file" class="absolute inset-0 opacity-0 cursor-pointer" multiple onchange="handleQueueFiles(event)">
                            <div class="text-center group-hover:scale-110 transition-transform pointer-events-none">
                                <svg class="w-8 h-8 mx-auto text-slate-300 group-hover:text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span class="text-[10px] font-black text-slate-400 mt-2 block group-hover:text-blue-600 uppercase tracking-tighter">Tambah Foto</span>
                            </div>
                        </div>
                    </div>
                    <p class="text-xs text-slate-400 italic">Klik berkali-kali untuk menumpuk foto baru. Klik (X) pada foto baru jika ingin membatalkan sebelum Update.</p>
                </div>

                <div class="pt-8 border-t border-slate-50 flex items-center justify-between gap-4">
                    <button type="submit" class="flex-1 py-5 bg-blue-600 text-white rounded-2xl font-black text-lg hover:bg-blue-700 shadow-2xl shadow-blue-100 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>UPDATE DATA KAMAR</span>
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

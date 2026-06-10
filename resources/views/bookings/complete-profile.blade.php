<x-app-layout>
    @section('header_title', 'Lengkapi Profil Penghuni')

    <div class="min-h-screen bg-slate-50/50 py-12 px-4 sm:px-6">
        <div class="max-w-5xl mx-auto">
            <div class="text-center mb-10">
                <h2 class="text-3xl font-black text-slate-900 tracking-tight">Data Penghuni</h2>
                <p class="text-slate-500 mt-2 font-medium">Mohon lengkapi data diri Anda untuk menyewa <span class="text-blue-600 font-bold">Kamar {{ $room->room_number }}</span></p>
            </div>

            @if ($errors->any())
                <div class="mb-8 p-5 bg-rose-50 border border-rose-100 text-rose-700 rounded-3xl shadow-sm flex items-start gap-4">
                    <div class="w-10 h-10 rounded-2xl bg-rose-100 flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-exclamation-circle text-rose-600"></i>
                    </div>
                    <div>
                        <p class="font-bold text-sm mb-1">Ada kesalahan pengisian:</p>
                        <ul class="list-disc list-inside text-xs space-y-0.5 opacity-90">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <form action="{{ route('bookings.store-profile') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    
                    <!-- Sidebar: Photo Uploads -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white p-6 rounded-[2.5rem] border border-slate-200 shadow-sm">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-6">Upload Dokumen</h3>
                            
                            <!-- Foto KTP -->
                            <div class="space-y-4 mb-8">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Foto KTP <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div id="ktp-preview" class="aspect-[3/2] rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-blue-400 group-hover:bg-blue-50/30">
                                        <i class="fas fa-id-card text-3xl text-slate-300 mb-2 transition-transform group-hover:scale-110"></i>
                                        <span class="text-[10px] font-bold text-slate-400">Klik untuk upload KTP</span>
                                    </div>
                                    <input type="file" name="foto_ktp" onchange="previewImage(this, 'ktp-preview')" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" required>
                                </div>
                            </div>

                            <!-- Foto Diri -->
                            <div class="space-y-4">
                                <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider">Foto Diri Bebas <span class="text-rose-500">*</span></label>
                                <div class="relative group">
                                    <div id="self-preview" class="aspect-square rounded-3xl border-2 border-dashed border-slate-200 bg-slate-50 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-blue-400 group-hover:bg-blue-50/30">
                                        <i class="fas fa-user-circle text-3xl text-slate-300 mb-2 transition-transform group-hover:scale-110"></i>
                                        <span class="text-[10px] font-bold text-slate-400">Klik untuk upload foto diri</span>
                                    </div>
                                    <input type="file" name="foto_diri" onchange="previewImage(this, 'self-preview')" class="absolute inset-0 opacity-0 cursor-pointer" accept="image/*" required>
                                </div>
                            </div>
                        </div>

                        <!-- Room Card Mini -->
                        <div class="bg-blue-600 p-6 rounded-[2.5rem] text-white shadow-xl shadow-blue-200 overflow-hidden relative">
                            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/10 rounded-full blur-2xl"></div>
                            <p class="text-[9px] font-black uppercase tracking-[0.3em] opacity-60 mb-1">Unit Pilihan</p>
                            <h4 class="text-2xl font-black mb-1">Kamar {{ $room->room_number }}</h4>
                            <p class="text-xs font-bold opacity-80">{{ $room->property_name }}</p>
                            <div class="mt-6 flex items-center justify-between">
                                <span class="text-sm font-medium opacity-80">Sewa bulanan</span>
                                <span class="text-lg font-black italic">Rp {{ number_format($room->price, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Main Form -->
                    <div class="lg:col-span-2 space-y-6">
                        
                        <!-- Personal Info -->
                        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Informasi Pribadi</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="md:col-span-2">
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Lengkap (Sesuai KTP)</label>
                                    <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->name) }}" placeholder="Masukkan nama lengkap" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nama Panggilan</label>
                                    <input type="text" name="nama_panggilan" value="{{ old('nama_panggilan') }}" placeholder="Contoh: Budi" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">NIK (16 Digit)</label>
                                    <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" placeholder="32xxxxxxxxxxxxxx" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" required>
                                        <option value="">Pilih</option>
                                        <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                        <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Nomor WhatsApp</label>
                                    <input type="text" name="nomor_whatsapp" value="{{ old('nomor_whatsapp') }}" placeholder="08xxxxxxxxxx" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Tempat Lahir</label>
                                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota Lahir" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Tanggal Lahir</label>
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Pekerjaan</label>
                                    <input type="text" name="occupation" value="{{ old('occupation') }}" placeholder="Contoh: Programmer" required>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Kontak Darurat</label>
                                    <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="No HP Orang Tua/Wali" required>
                                </div>
                            </div>
                        </div>

                        <!-- Address Info -->
                        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-200 shadow-sm">
                            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-8">Informasi Alamat</h3>
                            
                            <div class="space-y-6">
                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Lengkap (Sesuai KTP)</label>
                                    <textarea name="alamat_ktp" rows="2" placeholder="Jl. Merdeka No. 1..." required>{{ old('alamat_ktp') }}</textarea>
                                </div>

                                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                    <div class="md:col-span-1">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">RT</label>
                                        <input type="text" name="rt" value="{{ old('rt') }}" placeholder="001" required>
                                    </div>
                                    <div class="md:col-span-1">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">RW</label>
                                        <input type="text" name="rw" value="{{ old('rw') }}" placeholder="002" required>
                                    </div>
                                    <div class="md:col-span-2">
                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Provinsi</label>
                                        <select id="province" name="province" required>
                                            <option value="">Pilih Provinsi</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Kota / Kabupaten</label>
                                        <select id="city" name="city" required disabled>
                                            <option value="">Pilih Kota</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Kecamatan</label>
                                        <select id="district" name="district" required disabled>
                                            <option value="">Pilih Kecamatan</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-[10px] font-black text-slate-400 uppercase mb-2">Kelurahan</label>
                                        <select id="village" name="village" required disabled>
                                            <option value="">Pilih Kelurahan</option>
                                        </select>
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-xs font-bold text-slate-600 uppercase tracking-wider mb-2">Alamat Domisili Sekarang (Jika beda dengan KTP)</label>
                                    <textarea name="address" rows="2" placeholder="Masukkan alamat lengkap saat ini..." required>{{ old('address', old('alamat_ktp')) }}</textarea>
                                </div>
                            </div>
                        </div>

                        <div class="pt-4">
                            <button type="submit" class="w-full h-16 bg-blue-600 text-white rounded-[2rem] font-black text-lg hover:bg-blue-700 shadow-2xl shadow-blue-200 transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                                <span>Simpan & Lanjutkan Reservasi</span>
                                <i class="fas fa-arrow-right text-sm"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

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

        // IndoRegion API Logic (Using emsifa API)
        const baseUrl = 'https://www.emsifa.com/api-wilayah-indonesia/api';

        async function fetchData(url) {
            try {
                const response = await fetch(url);
                return await response.json();
            } catch (error) {
                console.error('Error fetching region data:', error);
                return [];
            }
        }

        async function loadProvinces() {
            const provinces = await fetchData(`${baseUrl}/provinces.json`);
            const provinceSelect = document.getElementById('province');
            provinces.forEach(p => {
                const option = document.createElement('option');
                option.value = p.name;
                option.dataset.id = p.id;
                option.textContent = p.name;
                provinceSelect.appendChild(option);
            });
        }

        document.getElementById('province').addEventListener('change', async function() {
            const provinceId = this.options[this.selectedIndex].dataset.id;
            const citySelect = document.getElementById('city');
            const districtSelect = document.getElementById('district');
            const villageSelect = document.getElementById('village');

            citySelect.innerHTML = '<option value="">Pilih Kota</option>';
            citySelect.disabled = true;
            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            districtSelect.disabled = true;
            villageSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            villageSelect.disabled = true;

            if (provinceId) {
                const cities = await fetchData(`${baseUrl}/regencies/${provinceId}.json`);
                cities.forEach(c => {
                    const option = document.createElement('option');
                    option.value = c.name;
                    option.dataset.id = c.id;
                    option.textContent = c.name;
                    citySelect.appendChild(option);
                });
                citySelect.disabled = false;
            }
        });

        document.getElementById('city').addEventListener('change', async function() {
            const cityId = this.options[this.selectedIndex].dataset.id;
            const districtSelect = document.getElementById('district');
            const villageSelect = document.getElementById('village');

            districtSelect.innerHTML = '<option value="">Pilih Kecamatan</option>';
            districtSelect.disabled = true;
            villageSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            villageSelect.disabled = true;

            if (cityId) {
                const districts = await fetchData(`${baseUrl}/districts/${cityId}.json`);
                districts.forEach(d => {
                    const option = document.createElement('option');
                    option.value = d.name;
                    option.dataset.id = d.id;
                    option.textContent = d.name;
                    districtSelect.appendChild(option);
                });
                districtSelect.disabled = false;
            }
        });

        document.getElementById('district').addEventListener('change', async function() {
            const districtId = this.options[this.selectedIndex].dataset.id;
            const villageSelect = document.getElementById('village');

            villageSelect.innerHTML = '<option value="">Pilih Kelurahan</option>';
            villageSelect.disabled = true;

            if (districtId) {
                const villages = await fetchData(`${baseUrl}/villages/${districtId}.json`);
                villages.forEach(v => {
                    const option = document.createElement('option');
                    option.value = v.name;
                    option.textContent = v.name;
                    villageSelect.appendChild(option);
                });
                villageSelect.disabled = false;
            }
        });

        loadProvinces();
    </script>
</x-app-layout>

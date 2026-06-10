<x-app-layout>
    @section('header_title', 'Edit Partner Laundry')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Edit Partner: {{ $laundry->name }}</h2>
                <p class="text-slate-500 text-[13px] mt-0.5">Perbarui informasi toko, kontak, dan branding mitra laundry.</p>
            </div>
            <a href="{{ route('admin.laundries.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left text-[10px]"></i>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-start gap-3 shadow-sm">
                <i class="fas fa-exclamation-circle mt-1"></i>
                <ul class="list-disc list-inside text-[13px] font-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="stat-card">
            <form action="{{ route('admin.laundries.update', $laundry->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Owner Info --}}
                    <div class="space-y-5">
                        <div class="flex items-center gap-2">
                            <span class="w-1 h-3 bg-brand rounded-full"></span>
                            <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-widest">Informasi Pemilik</h4>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Pemilik</label>
                            <input type="text" name="partner_name" value="{{ old('partner_name', $laundry->user->name) }}" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Email Akun</label>
                            <input type="email" name="email" value="{{ old('email', $laundry->user->email) }}" required>
                        </div>
                    </div>

                    {{-- Laundry Branding --}}
                    <div class="space-y-5">
                        <div class="flex items-center gap-2">
                            <span class="w-1 h-3 bg-brand rounded-full"></span>
                            <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-widest">Branding Laundry</h4>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Toko Laundry</label>
                            <input type="text" name="laundry_name" value="{{ old('laundry_name', $laundry->name) }}" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">No. WhatsApp</label>
                            <input type="text" name="phone" value="{{ old('phone', $laundry->phone) }}" placeholder="0812...">
                        </div>
                    </div>

                    {{-- Logo Section --}}
                    <div class="md:col-span-2 space-y-4">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Logo / Banner Laundry</label>
                        <div class="flex items-center gap-6 p-4 bg-slate-50/50 border border-slate-100 rounded-[2rem]">
                            <div class="w-24 h-24 rounded-[1.5rem] bg-white border border-slate-200 overflow-hidden shadow-sm shrink-0">
                                @if($laundry->image)
                                    <img src="{{ asset('storage/' . $laundry->image) }}" class="w-full h-full object-cover" id="banner-preview">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-200">
                                        <i class="fas fa-image text-2xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="file" name="image" id="image-input" 
                                    class="block w-full text-[11px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-brand-light file:text-brand cursor-pointer">
                                <p class="text-[11px] text-slate-400 font-medium">Opsional. Pilih gambar baru (JPG/PNG, max 2MB) untuk memperbarui logo.</p>
                            </div>
                        </div>
                    </div>

                    {{-- Bank Info --}}
                    <div class="md:col-span-2 space-y-5 pt-4 border-t border-slate-50">
                        <div class="flex items-center gap-2">
                            <span class="w-1 h-3 bg-brand rounded-full"></span>
                            <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-widest">Informasi Rekening</h4>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ old('bank_name', $laundry->bank_name) }}" placeholder="Contoh: BCA">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">No. Rekening</label>
                                <input type="text" name="account_number" value="{{ old('account_number', $laundry->account_number) }}" placeholder="0001...">
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Atas Nama</label>
                                <input type="text" name="account_name" value="{{ old('account_name', $laundry->account_name) }}" placeholder="Nama Pemilik">
                            </div>
                        </div>
                    </div>

                    {{-- Address --}}
                    <div class="md:col-span-2 space-y-1.5 pt-4 border-t border-slate-50">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Toko</label>
                        <textarea name="address" rows="3" placeholder="Alamat lengkap mitra...">{{ old('address', $laundry->address) }}</textarea>
                    </div>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t border-slate-50">
                    <button type="submit" class="btn btn-primary px-10 shadow-lg shadow-brand/20">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('image-input').onchange = function (evt) {
            const [file] = this.files;
            if (file) {
                const preview = document.getElementById('banner-preview');
                if (preview) {
                    preview.src = URL.createObjectURL(file);
                }
            }
        }
    </script>
</x-app-layout>

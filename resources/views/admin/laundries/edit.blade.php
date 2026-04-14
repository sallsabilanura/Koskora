<x-app-layout>
    @section('header_title', 'Edit Partner Laundry')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Edit Partner: {{ $laundry->name }}</h2>
                <p class="text-slate-500 font-medium">Perbarui informasi toko dan branding laundry.</p>
            </div>
            <a href="{{ route('admin.laundries.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-xl font-semibold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-200 transition ease-in-out duration-150">
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

        <div class="bg-white rounded-[2.5rem] border border-slate-200 shadow-sm p-8 md:p-12 overflow-hidden">
            <form action="{{ route('admin.laundries.update', $laundry->id) }}" method="POST" enctype="multipart/form-data" class="space-y-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Owner Name -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Pemilik</label>
                        <input type="text" name="partner_name" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" value="{{ old('partner_name', $laundry->user->name) }}" required>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Email Akun</label>
                        <input type="email" name="email" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" value="{{ old('email', $laundry->user->email) }}" required>
                    </div>

                    <!-- Laundry Name -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Toko Laundry</label>
                        <input type="text" name="laundry_name" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700 text-lg" value="{{ old('laundry_name', $laundry->name) }}" required>
                    </div>

                    <!-- Branding Banner -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-4">Logo / Banner Laundry</label>
                        <div class="flex items-start space-x-6">
                            <div class="w-32 h-32 rounded-3xl bg-slate-50 border-2 border-dashed border-slate-200 overflow-hidden flex items-center justify-center shrink-0">
                                @if($laundry->image)
                                    <img src="{{ asset('storage/' . $laundry->image) }}" class="w-full h-full object-cover" id="banner-preview">
                                @else
                                    <svg class="w-12 h-12 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1 space-y-4">
                                <input type="file" name="image" id="image-input" class="w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-6 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                                <p class="text-xs text-slate-400 italic font-medium leading-relaxed">Pilih gambar baru jika ingin mengganti banner/logo saat ini. Ukuran maksimal 2MB (JPEG, PNG).</p>
                            </div>
                        </div>
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nomor WhatsApp</label>
                        <input type="text" name="phone" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" value="{{ old('phone', $laundry->phone) }}" placeholder="0812...">
                    </div>

                    <!-- Bank Info -->
                    <div class="md:col-span-2 grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-50">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Bank</label>
                            <input type="text" name="bank_name" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" value="{{ old('bank_name', $laundry->bank_name) }}" placeholder="Contoh: BCA">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">No. Rekening</label>
                            <input type="text" name="account_number" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" value="{{ old('account_number', $laundry->account_number) }}" placeholder="0001...">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Atas Nama</label>
                            <input type="text" name="account_name" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" value="{{ old('account_name', $laundry->account_name) }}" placeholder="Nama Pemilik">
                        </div>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Toko</label>
                        <textarea name="address" rows="3" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600">{{ old('address', $laundry->address) }}</textarea>
                    </div>
                </div>

                <div class="pt-8 border-t border-slate-100">
                    <button type="submit" class="w-full py-5 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-rose-600 transition-all shadow-2xl shadow-blue-100 uppercase tracking-[0.2em]">
                        SIMPAN PERUBAHAN PARTNER
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

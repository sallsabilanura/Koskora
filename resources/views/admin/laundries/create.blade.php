<x-app-layout>
    @section('header_title', 'Tambah Partner Laundry')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in pb-12">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.laundries.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="badge badge-red w-full justify-start p-4 rounded-xl border border-red-100 mb-4 flex-col items-start gap-2 shadow-sm">
                <div class="flex items-center font-bold text-red-700 text-sm">
                    <i class="fas fa-exclamation-circle mr-2 text-lg"></i>
                    Terdapat Kesalahan Pengisian Data:
                </div>
                <ul class="list-disc list-inside text-xs text-red-600 ml-6 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-[20px] shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-8 border-b border-slate-50 bg-slate-50/50">
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Pendaftaran Partner Laundry Baru</h3>
                <p class="text-slate-500 text-[13px] mt-1">Silakan lengkapi semua informasi terkait partner dan data login di bawah ini.</p>
            </div>

            <form action="{{ route('admin.laundries.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Owner Info --}}
                    <div class="space-y-5">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-brand/10 text-brand flex items-center justify-center">
                                <i class="fas fa-user-tie text-sm"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Informasi Akun Pemilik</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Pemilik <span class="text-red-500">*</span></label>
                            <input type="text" name="partner_name" value="{{ old('partner_name') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Email Login <span class="text-red-500">*</span></label>
                            <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="email@contoh.com" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Password <span class="text-red-500">*</span></label>
                            <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Minimal 8 karakter" required>
                        </div>
                    </div>

                    {{-- Laundry Info --}}
                    <div class="space-y-5">
                        <div class="flex items-center gap-2 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-brand/10 text-brand flex items-center justify-center">
                                <i class="fas fa-store text-sm"></i>
                            </div>
                            <h4 class="text-sm font-bold text-slate-800 uppercase tracking-widest">Informasi Outlet Laundry</h4>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Outlet Laundry <span class="text-red-500">*</span></label>
                            <input type="text" name="laundry_name" value="{{ old('laundry_name') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Contoh: Bersih Kilat Laundry" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">No. WhatsApp <span class="text-red-500">*</span></label>
                            <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="08123456789" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Logo / Foto Outlet</label>
                            <input type="file" name="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition-all border border-slate-200 rounded-xl bg-slate-50">
                            <p class="text-[10px] text-slate-400 ml-1 mt-1">Format: JPG, PNG. Maksimal 2MB.</p>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Alamat Lengkap Outlet <span class="text-red-500">*</span></label>
                        <textarea name="address" rows="3" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Tuliskan alamat lengkap beserta rincian jalan, nomor bangunan..." required>{{ old('address') }}</textarea>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-100 mt-8">
                    <a href="{{ route('admin.laundries.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors text-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-brand text-white font-bold text-sm shadow-brand/30 shadow-lg hover:bg-brand-dark transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Partner Baru
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

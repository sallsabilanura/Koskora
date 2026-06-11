<x-app-layout>
    @section('header_title', 'Daftarkan Petugas Kebersihan')

    <div class="max-w-3xl mx-auto space-y-6 animate-fade-in pb-12">
        <div class="flex items-center justify-between">
            <a href="{{ route('admin.cleaning.index') }}" class="btn btn-ghost">
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
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Petugas Kebersihan Baru</h3>
                <p class="text-slate-500 text-[13px] mt-1">Berikan akses sistem kepada petugas kebersihan (cleaner) yang baru.</p>
            </div>

            <form action="{{ route('admin.cleaning.cleaners.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Lengkap Petugas <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Nama lengkap sesuai KTP" required>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Email Login <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="email@contoh.com" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Password Akun <span class="text-red-500">*</span></label>
                    <input type="password" name="password" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Minimal 8 karakter" required>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Foto Profil Petugas</label>
                    <input type="file" name="photo" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition-all border border-slate-200 rounded-xl bg-slate-50">
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Bio / Keahlian Singkat</label>
                    <textarea name="bio" rows="3" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Contoh: Ahli pembersihan kamar mandi dan jendela...">{{ old('bio') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-100 mt-8">
                    <a href="{{ route('admin.cleaning.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors text-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-brand text-white font-bold text-sm shadow-brand/30 shadow-lg hover:bg-brand-dark transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fas fa-check"></i>
                        Daftarkan Petugas
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

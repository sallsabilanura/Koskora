<x-app-layout>
    @section('header_title', 'Tambah Paket Layanan')

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
                    Terdapat Kesalahan:
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
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Tambah Paket Cleaning Baru</h3>
                <p class="text-slate-500 text-[13px] mt-1">Tentukan nama, rincian, dan harga paket layanan kebersihan baru untuk sistem.</p>
            </div>

            <form action="{{ route('admin.cleaning.packages.store') }}" method="POST" class="p-8 space-y-6">
                @csrf
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Nama Paket <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Contoh: Bebersih Kamar Tidur & Kamar Mandi" required>
                </div>
                
                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Harga Layanan (Rp) <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <span class="text-slate-400 font-bold text-sm">Rp</span>
                        </div>
                        <input type="number" name="price" value="{{ old('price') }}" class="w-full pl-12 pr-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="50000" min="0" required>
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[11px] font-bold text-slate-500 uppercase tracking-widest ml-1">Deskripsi Layanan <span class="text-red-500">*</span></label>
                    <textarea name="description" rows="4" class="w-full px-4 py-3 rounded-xl bg-slate-50 border-slate-200 text-sm focus:ring-brand focus:border-brand" placeholder="Rincian bagian mana saja yang dibersihkan, durasi, alat yang digunakan..." required>{{ old('description') }}</textarea>
                </div>

                <div class="flex items-center justify-end gap-4 pt-8 border-t border-slate-100 mt-8">
                    <a href="{{ route('admin.cleaning.index') }}" class="px-6 py-3 rounded-xl font-bold text-slate-500 hover:bg-slate-100 transition-colors text-sm">
                        Batal
                    </a>
                    <button type="submit" class="px-8 py-3 rounded-xl bg-brand text-white font-bold text-sm shadow-brand/30 shadow-lg hover:bg-brand-dark transition-all transform hover:-translate-y-0.5 flex items-center gap-2">
                        <i class="fas fa-save"></i>
                        Simpan Paket
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

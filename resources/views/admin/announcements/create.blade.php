<x-app-layout>
    @section('header_title', 'Buat Pengumuman Baru')

    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black text-slate-800 italic tracking-tight">Tulis Informasi</h2>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Siarkan berita terbaru ke seluruh penghuni kos</p>
            </div>
            <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl p-8 md:p-12 overflow-hidden relative">
            <!-- Decorative Gradient -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-blue-50 to-indigo-50 rounded-full -mr-32 -mt-32 opacity-50"></div>

            <form action="{{ route('admin.announcements.store') }}" method="POST" class="relative z-10 space-y-10">
                @csrf
                
                <div class="space-y-8">
                    <!-- Title -->
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Judul Pengumuman</label>
                        <input type="text" name="title" value="{{ old('title') }}" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-black text-slate-800 text-xl placeholder-slate-300" placeholder="Contoh: Perbaikan Pipa Air di Lantai 2">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Type -->
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Kategori / Tipe</label>
                            <select name="type" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-bold text-slate-700">
                                <option value="info" {{ old('type') == 'info' ? 'selected' : '' }}>🔵 Info Biasa (Info)</option>
                                <option value="update" {{ old('type') == 'update' ? 'selected' : '' }}>🟢 Pembaruan (Update)</option>
                                <option value="warning" {{ old('type') == 'warning' ? 'selected' : '' }}>🟡 Peringatan (Warning)</option>
                                <option value="danger" {{ old('type') == 'danger' ? 'selected' : '' }}>🔴 Penting/Darurat (Danger)</option>
                            </select>
                        </div>

                        <!-- Target Role -->
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Target Penerima</label>
                            <select name="target_role" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-bold text-slate-700">
                                <option value="all" {{ old('target_role') == 'all' ? 'selected' : '' }}>🌍 Semua (All Roles)</option>
                                <option value="user" {{ old('target_role') == 'user' ? 'selected' : '' }}>🏠 Penyewa Saja (Tenants)</option>
                                <option value="laundry" {{ old('target_role') == 'laundry' ? 'selected' : '' }}>🧺 Partner Laundry</option>
                                <option value="cleaner" {{ old('target_role') == 'cleaner' ? 'selected' : '' }}>🧹 Partner Cleaner</option>
                            </select>
                        </div>

                        <!-- Expiry Date -->
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Berlaku Sampai (Opsional)</label>
                            <input type="datetime-local" name="expires_at" value="{{ old('expires_at') }}" class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-bold text-slate-700">
                            <p class="mt-2 text-[9px] text-slate-400 italic">Kosongkan jika ingin terus ditayangkan</p>
                        </div>

                        <!-- Status Toggle -->
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Status Publikasi</label>
                            <div class="flex items-center space-x-3 p-5 bg-slate-50 rounded-2xl border border-transparent hover:border-slate-100 transition-all">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" class="sr-only peer" checked>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                                <span class="text-sm font-bold text-slate-600">Langsung Terbitkan</span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Isi Pesan Lengkap</label>
                        <textarea name="content" rows="8" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-[2rem] focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-medium text-slate-700 placeholder-slate-300 text-lg leading-relaxed" placeholder="Tuliskan detail pengumuman di sini...">{{ old('content') }}</textarea>
                    </div>
                </div>

                <div class="pt-10 border-t border-slate-50">
                    <button type="submit" class="w-full py-6 bg-brand-blue text-white rounded-3xl font-black text-xl hover:bg-blue-700 shadow-2xl shadow-blue-100 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6 rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        <span>TERBITKAN PENGUMUMAN SEKARANG</span>
                    </button>
                    <p class="text-center text-[10px] font-black text-slate-300 uppercase tracking-widest mt-6">Penyewa akan langsung melihat pengumuman ini di dashboard mereka</p>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    @section('header_title', 'Edit Pengumuman')

    <div class="max-w-4xl mx-auto space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-3xl font-black text-slate-800 italic tracking-tight">Perbarui Informasi</h2>
                <p class="text-slate-400 text-[10px] font-black uppercase tracking-[0.2em] mt-1">Sesuaikan isi pengumuman yang sudah ada</p>
            </div>
            <a href="{{ route('admin.announcements.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl p-8 md:p-12 overflow-hidden relative">
            <!-- Decorative Gradient -->
            <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-amber-50 to-orange-50 rounded-full -mr-32 -mt-32 opacity-50"></div>

            <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST" class="relative z-10 space-y-10">
                @csrf
                @method('PUT')
                
                <div class="space-y-8">
                    <!-- Title -->
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Judul Pengumuman</label>
                        <input type="text" name="title" value="{{ old('title', $announcement->title) }}" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-black text-slate-800 text-xl placeholder-slate-300">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Type -->
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Kategori / Tipe</label>
                            <select name="type" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-bold text-slate-700">
                                <option value="info" {{ old('type', $announcement->type) == 'info' ? 'selected' : '' }}>🔵 Info Biasa (Info)</option>
                                <option value="update" {{ old('type', $announcement->type) == 'update' ? 'selected' : '' }}>🟢 Pembaruan (Update)</option>
                                <option value="warning" {{ old('type', $announcement->type) == 'warning' ? 'selected' : '' }}>🟡 Peringatan (Warning)</option>
                                <option value="danger" {{ old('type', $announcement->type) == 'danger' ? 'selected' : '' }}>🔴 Penting/Darurat (Danger)</option>
                            </select>
                        </div>

                        <!-- Target Role -->
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Target Penerima</label>
                            <select name="target_role" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-bold text-slate-700">
                                <option value="all" {{ old('target_role', $announcement->target_role) == 'all' ? 'selected' : '' }}>🌍 Semua (All Roles)</option>
                                <option value="user" {{ old('target_role', $announcement->target_role) == 'user' ? 'selected' : '' }}>🏠 Penyewa Saja (Tenants)</option>
                                <option value="laundry" {{ old('target_role', $announcement->target_role) == 'laundry' ? 'selected' : '' }}>🧺 Partner Laundry</option>
                                <option value="cleaner" {{ old('target_role', $announcement->target_role) == 'cleaner' ? 'selected' : '' }}>🧹 Partner Cleaner</option>
                            </select>
                        </div>

                        <!-- Expiry Date -->
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Berlaku Sampai (Opsional)</label>
                            <input type="datetime-local" name="expires_at" value="{{ old('expires_at', $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}" class="w-full px-6 py-5 bg-slate-50 border-none rounded-2xl focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-bold text-slate-700">
                            <p class="mt-2 text-[9px] text-slate-400 italic">Kosongkan jika ingin terus ditayangkan</p>
                        </div>

                        <!-- Status Toggle -->
                        <div class="group">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Status Publikasi</label>
                            <div class="flex items-center space-x-3 p-5 bg-slate-50 rounded-2xl border border-transparent hover:border-slate-100 transition-all">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" name="is_active" class="sr-only peer" {{ $announcement->is_active ? 'checked' : '' }}>
                                    <div class="w-11 h-6 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-emerald-500"></div>
                                </label>
                                <span class="text-sm font-bold text-slate-600">Publikasikan Pengumuman</span>
                            </div>
                        </div>
                    </div>

                    <!-- Content -->
                    <div class="group">
                        <label class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.3em] mb-3 group-focus-within:text-brand-blue ml-1 transition-colors">Isi Pesan Lengkap</label>
                        <textarea name="content" rows="8" required class="w-full px-6 py-5 bg-slate-50 border-none rounded-[2rem] focus:ring-4 focus:ring-blue-100 focus:bg-white transition-all font-medium text-slate-700 placeholder-slate-300 text-lg leading-relaxed">{{ old('content', $announcement->content) }}</textarea>
                    </div>
                </div>

                <div class="pt-10 border-t border-slate-50">
                    <button type="submit" class="w-full py-6 bg-brand-blue text-white rounded-3xl font-black text-xl hover:bg-blue-700 shadow-2xl shadow-blue-100 transition-all transform hover:-translate-y-1 active:scale-95 flex items-center justify-center space-x-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        <span>SIMPAN PERUBAHAN</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

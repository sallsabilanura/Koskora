<x-app-layout>
    @section('header_title', 'Incident Report')

    <div class="max-w-2xl mx-auto py-4 space-y-6 animate-fade-in">
        {{-- ===== REPORT FORM ===== --}}
        <div class="stat-card !p-0 overflow-hidden">
            <div class="p-8 border-b border-slate-50 space-y-2">
                <div class="flex items-center gap-3">
                    <div class="w-1.5 h-6 bg-red-500 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight uppercase">Laporan Insiden Keamanan</h3>
                </div>
                <p class="text-slate-500 text-[13px]">Laporkan anomali, kerusakan, atau kejadian penting di area penugasan.</p>
            </div>

            <form action="{{ route('security.report.store') }}" method="POST" enctype="multipart/form-data" class="p-8 space-y-6">
                @csrf
                <input type="hidden" name="location" value="{{ $todayShift->location }}">

                <div class="space-y-6">
                    {{-- Active Location Banner --}}
                    <div class="bg-red-50 border border-red-100 rounded-2xl p-5 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-red-500 text-white rounded-2xl flex items-center justify-center shadow-lg shadow-red-100">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-red-400 uppercase tracking-widest">Lokasi Kejadian (Area Tugas)</p>
                                <p class="text-lg font-black text-slate-800 tracking-tight uppercase">{{ $todayShift->location }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Waktu Kejadian</label>
                        <input type="datetime-local" name="incident_date" value="{{ date('Y-m-d\TH:i') }}" required
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Judul Laporan</label>
                        <input type="text" name="title" placeholder="Contoh: Kerusakan Lampu Area Parkir" required
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-red-500 focus:border-red-500 outline-none transition-all">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Deskripsi & Kronologi</label>
                        <textarea name="description" rows="5" placeholder="Ceritakan detail kejadian secara lengkap dan jelas..." required
                            class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-4 px-5 focus:ring-red-500 focus:border-red-500 outline-none transition-all"></textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Lampiran Foto (Opsional)</label>
                        <input type="file" name="attachment" 
                            class="block w-full text-[11px] text-slate-500 file:mr-4 file:py-3 file:px-6 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:bg-slate-900 file:text-white file:uppercase file:tracking-widest hover:file:bg-brand transition-all">
                    </div>
                </div>

                <div class="flex items-center justify-between gap-4 pt-8 border-t border-slate-50">
                    <a href="{{ route('security.dashboard') }}" class="py-3 px-6 text-[11px] font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">Batal</a>
                    <button type="submit" class="bg-red-600 text-white flex-1 py-5 rounded-2xl font-black text-xs uppercase tracking-widest shadow-xl shadow-red-100 hover:bg-red-700 transition-all transform active:scale-95">
                        Kirim Laporan Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

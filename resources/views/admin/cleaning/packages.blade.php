<x-app-layout>
    @section('header_title', 'Master Data Paket Kebersihan')

    <div class="space-y-8">
        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Table List -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-1.5 h-6 bg-rose-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight uppercase italic">Paket Layanan</h3>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Paket</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Keterangan</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Harga</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($packages as $package)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <span class="px-3 py-1 {{ strtolower($package->name) == 'max' ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }} text-[10px] font-black rounded-full uppercase tracking-widest">{{ $package->name }}</span>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-xs text-slate-500 font-medium">{{ $package->description }}</p>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="text-sm font-black text-slate-800 tracking-tighter">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center text-slate-300 font-bold italic">Belum ada paket terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Form -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Tambah Paket</h3>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm">
                    <form action="{{ route('admin.cleaning.packages.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Paket</label>
                            <input type="text" name="name" required class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600" placeholder="Misal: MIN, MAX, MEDIUM">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Deskripsi Layanan</label>
                            <textarea name="description" rows="4" required class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600 text-sm" placeholder="Apa saja yang dibersihkan? Mirip paket laundry..."></textarea>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Harga (Rupiah)</label>
                            <input type="number" name="price" required class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600 text-sm" placeholder="35000">
                        </div>
                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-rose-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl transition-all">
                            Simpan Paket
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

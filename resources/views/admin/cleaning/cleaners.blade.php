<x-app-layout>
    @section('header_title', 'Master Data Petugas Kebersihan')

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
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Daftar Petugas Aktif</h3>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Foto</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama / Email</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Biodata</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($cleaners as $cleaner)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="w-12 h-12 rounded-xl border border-slate-200 overflow-hidden bg-slate-100">
                                            @if($cleaner->photo)
                                                <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"></path></svg>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-slate-800">{{ $cleaner->user->name }}</div>
                                        <div class="text-[10px] font-bold text-slate-400">{{ $cleaner->user->email }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <p class="text-xs text-slate-500 line-clamp-2 max-w-xs italic">"{{ $cleaner->bio ?: '-' }}"</p>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-20 text-center text-slate-300 font-bold italic">Belum ada petugas terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Add Form -->
            <div class="space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-1.5 h-6 bg-rose-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Tambah Petugas</h3>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm">
                    <form action="{{ route('admin.cleaning.cleaners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</label>
                            <input type="text" name="name" required class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600" placeholder="Contoh: Budi Santoso">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Email (Untuk Login)</label>
                            <input type="email" name="email" required class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600" placeholder="budi@example.com">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Password Default</label>
                            <input type="password" name="password" required class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600" placeholder="Minimal 8 karakter">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Foto Wajah</label>
                            <input type="file" name="photo" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-[10px] file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-slate-400 uppercase tracking-widest">Bio Singkat</label>
                            <textarea name="bio" rows="3" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600" placeholder="Misal: Ahli kebersihan kamar premium."></textarea>
                        </div>
                        <button type="submit" class="w-full py-4 bg-slate-900 hover:bg-rose-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-xl transition-all">
                            Daftarkan Petugas
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

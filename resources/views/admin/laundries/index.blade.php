<x-app-layout>
    @section('header_title', 'Partner Laundry')

    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Manajemen Partner Laundry</h2>
                <p class="text-slate-500 font-medium">Daftarkan dan kelola penyedia jasa laundry sekitar kos.</p>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Registration Form -->
            <div class="lg:col-span-1">
                <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 italic">Daftar Partner Baru</h3>
                    <form action="{{ route('admin.laundries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Pemilik</label>
                            <input type="text" name="partner_name" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" placeholder="Nama Lengkap..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Email Akun</label>
                            <input type="email" name="email" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" placeholder="email@laundry.com" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Password</label>
                            <input type="password" name="password" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" required>
                        </div>
                        <hr class="my-6 border-slate-100">
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Toko Laundry</label>
                            <input type="text" name="laundry_name" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" placeholder="Contoh: Amanah Laundry" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Logo / Banner Laundry</label>
                            <input type="file" name="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-all cursor-pointer">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-medium text-slate-600" placeholder="Alamat toko..."></textarea>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nomor WhatsApp</label>
                            <input type="text" name="phone" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" placeholder="0812...">
                        </div>
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                            REGISTER PARTNER
                        </button>
                    </form>
                </div>
            </div>

            <!-- Partners List -->
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Daftar Partner Aktif</h3>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 border-b border-slate-100">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Toko / Pemilik</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontak</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Alamat</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($laundries as $laundry)
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="flex items-center space-x-4">
                                            <div class="w-12 h-12 rounded-xl bg-slate-100 overflow-hidden border border-slate-200 shrink-0">
                                                @if($laundry->image)
                                                    <img src="{{ asset('storage/' . $laundry->image) }}" class="w-full h-full object-cover">
                                                @else
                                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="font-black text-slate-800">{{ $laundry->name }}</div>
                                                <div class="text-xs font-bold text-slate-400 italic">Owner: {{ $laundry->user->name }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-bold text-slate-700">{{ $laundry->user->email }}</div>
                                        <div class="text-xs font-bold text-blue-500">{{ $laundry->phone }}</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-xs font-medium text-slate-500 max-w-[200px] line-clamp-2">{{ $laundry->address }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end space-x-2">
                                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[10px] font-black rounded-full uppercase">Aktif</span>
                                            <a href="{{ route('admin.laundries.edit', $laundry->id) }}" class="p-2 bg-blue-50 text-blue-600 rounded-xl hover:bg-blue-600 hover:text-white transition-all">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-8 py-20 text-center text-slate-300 font-bold italic">Belum ada partner laundry.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

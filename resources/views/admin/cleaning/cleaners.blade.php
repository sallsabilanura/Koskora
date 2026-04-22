<x-app-layout>
    @section('header_title', 'Master Data Petugas Kebersihan')

    <div class="space-y-4 md:space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Petugas Kebersihan</h2>
            <button @click="$dispatch('open-modal', 'add-cleaner')" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 transition btn-touch">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Petugas
            </button>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-3 md:p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl flex items-center shadow-sm text-sm">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <!-- Desktop Table -->
        <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm desktop-table">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-200">
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Foto & Nama</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Bio</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($cleaners as $cleaner)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if($cleaner->photo)
                                            <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-10 h-10 object-cover rounded-lg border border-slate-200">
                                        @else
                                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 border border-slate-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                            </div>
                                        @endif
                                        <div class="font-bold text-slate-800">{{ $cleaner->user->name }}</div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $cleaner->user->email }}</td>
                                <td class="px-6 py-4 text-xs text-slate-500 line-clamp-1 max-w-xs italic">
                                    {{ $cleaner->bio ?: 'Berkomitmen memberikan standar kebersihan tertinggi.' }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-lg uppercase tracking-wider">Verified</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada petugas.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="mobile-cards space-y-3">
            @foreach ($cleaners as $cleaner)
                <div class="mobile-card">
                    <div class="flex items-start gap-3 mb-3">
                        @if($cleaner->photo)
                            <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-14 h-14 object-cover rounded-xl border border-slate-200">
                        @else
                            <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-300 border border-slate-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <div class="font-bold text-slate-800 text-base truncate">{{ $cleaner->user->name }}</div>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-full uppercase">Verified</span>
                            </div>
                            <div class="text-[10px] font-bold text-brand-blue mb-1">{{ $cleaner->user->email }}</div>
                            <div class="text-[11px] text-slate-500 line-clamp-2 italic leading-relaxed">"{{ $cleaner->bio ?: 'Berkomitmen memberikan standar kebersihan tertinggi.' }}"</div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Premium Add Cleaner Modal -->
        <x-modal name="add-cleaner" focusable maxWidth="3xl">
            <div class="relative">
                <button type="button" @click="$dispatch('close')" class="absolute top-6 right-6 p-2 text-slate-400 hover:text-rose-600 transition-colors z-20">
                    <svg class="w-5 h-5 font-black" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="p-8 md:p-10">
                    <div class="mb-8 text-center">
                        <h3 class="text-2xl font-bold text-slate-800">Tambah Petugas Baru</h3>
                        <p class="text-slate-500 text-sm">Daftarkan petugas kebersihan profesional baru.</p>
                    </div>

                    <form action="{{ route('admin.cleaning.cleaners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Nama Lengkap</label>
                                <input type="text" name="name" required class="w-full h-12 px-4 rounded-xl border-slate-200 focus:ring-blue-600" placeholder="Ahmad Subarjo">
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Email</label>
                                <input type="email" name="email" required class="w-full h-12 px-4 rounded-xl border-slate-200 focus:ring-blue-600" placeholder="ahmad@koskora.com">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Password</label>
                            <input type="password" name="password" required class="w-full h-12 px-4 rounded-xl border-slate-200 focus:ring-blue-600" placeholder="••••••••">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Foto Profil</label>
                            <input type="file" name="photo" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider pl-1">Bio Singkat</label>
                            <textarea name="bio" rows="3" class="w-full px-4 py-3 rounded-xl border-slate-200 focus:ring-blue-600 italic text-sm" placeholder="Tuliskan pengalaman..."></textarea>
                        </div>

                        <div class="flex items-center gap-4 pt-4 border-t border-slate-100">
                            <x-secondary-button x-on:click="$dispatch('close')" class="flex-1 h-12 rounded-xl justify-center">Batal</x-secondary-button>
                            <button type="submit" class="flex-[2] h-12 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-blue-700 transition-all active:scale-95">Daftarkan Petugas</button>
                        </div>
                    </form>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>

<x-app-layout>
    @section('header_title', 'Partner Laundry')

    <div class="space-y-4 md:space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <h2 class="text-xl md:text-2xl font-bold text-slate-800">Partner Laundry</h2>
            <button @click="$dispatch('open-modal', 'register-partner')" class="inline-flex items-center justify-center px-4 py-2.5 bg-blue-600 border border-transparent rounded-xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 transition btn-touch">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Register Partner
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
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Partner & Owner</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Kontak</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Lokasi</th>
                            <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($laundries as $laundry)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center space-x-3">
                                        @if($laundry->image)
                                            <img src="{{ asset('storage/' . $laundry->image) }}" class="w-10 h-10 object-cover rounded-lg border border-slate-200">
                                        @else
                                            <div class="w-10 h-10 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 border border-slate-200">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-bold text-slate-800">{{ $laundry->name }}</div>
                                            <div class="text-[10px] font-medium text-slate-500">{{ $laundry->user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs font-bold text-slate-700">{{ $laundry->user->email }}</div>
                                    <div class="text-[10px] text-slate-400">{{ $laundry->phone }}</div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-xs text-slate-500 line-clamp-1 max-w-xs">{{ $laundry->address }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex items-center justify-end space-x-2">
                                        <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-lg uppercase tracking-wider">Active</span>
                                        <a href="{{ route('admin.laundries.edit', $laundry->id) }}" class="p-2 text-slate-400 hover:text-amber-600 transition-colors">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-8 text-center text-slate-400 text-sm">Belum ada partner laundry.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile Cards -->
        <div class="mobile-cards space-y-3">
            @foreach ($laundries as $laundry)
                <div class="mobile-card">
                    <div class="flex items-start gap-3 mb-3">
                        @if($laundry->image)
                            <img src="{{ asset('storage/' . $laundry->image) }}" class="w-14 h-14 object-cover rounded-xl border border-slate-200">
                        @else
                            <div class="w-14 h-14 bg-slate-100 rounded-xl flex items-center justify-center text-slate-300 border border-slate-200">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-0.5">
                                <div class="font-bold text-slate-800 text-base truncate">{{ $laundry->name }}</div>
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-[9px] font-bold rounded-full uppercase">Active</span>
                            </div>
                            <div class="text-[10px] font-bold text-brand-blue mb-1">Owner: {{ $laundry->user->name }}</div>
                            <div class="text-[11px] text-slate-500 line-clamp-1 truncate">{{ $laundry->address }}</div>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2 mb-3 bg-slate-50 rounded-lg p-2">
                        <div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase">WhatsApp</div>
                            <div class="text-[10px] font-bold text-slate-700">{{ $laundry->phone }}</div>
                        </div>
                        <div>
                            <div class="text-[9px] font-bold text-slate-400 uppercase">Email</div>
                            <div class="text-[10px] font-bold text-slate-700 truncate">{{ $laundry->user->email }}</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-1 pt-3 border-t border-slate-100">
                        <a href="{{ route('admin.laundries.edit', $laundry->id) }}" class="px-3 py-2 text-xs font-bold text-amber-600 bg-amber-50 rounded-lg btn-touch">Edit Partner</a>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Premium Registration Modal -->
        <x-modal name="register-partner" focusable maxWidth="4xl">
            <div class="relative">
                <button type="button" @click="$dispatch('close')" class="absolute top-6 right-6 p-2 text-slate-400 hover:text-rose-600 transition-colors z-20">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="p-8 md:p-10">
                    <div class="mb-8">
                        <h3 class="text-2xl font-bold text-slate-800">Daftar Partner Laundry Baru</h3>
                        <p class="text-slate-500 text-sm">Lengkapi informasi untuk mendaftarkan partner laundry.</p>
                    </div>

                    <form action="{{ route('admin.laundries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-blue-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <h4 class="text-xs font-bold uppercase tracking-wider">Info Pemilik</h4>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700">Nama Pemilik</label>
                                    <input type="text" name="partner_name" class="w-full rounded-xl border-slate-200 focus:ring-blue-600" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700">Email Login</label>
                                    <input type="email" name="email" class="w-full rounded-xl border-slate-200 focus:ring-blue-600" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700">Password</label>
                                    <input type="password" name="password" class="w-full rounded-xl border-slate-200 focus:ring-blue-600" required>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center gap-2 text-rose-600">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <h4 class="text-xs font-bold uppercase tracking-wider">Info Laundry</h4>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700">Nama Laundry</label>
                                    <input type="text" name="laundry_name" class="w-full rounded-xl border-slate-200 focus:ring-blue-600" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700">WhatsApp</label>
                                    <input type="text" name="phone" class="w-full rounded-xl border-slate-200 focus:ring-blue-600" required>
                                </div>
                                <div class="space-y-2">
                                    <label class="block text-xs font-bold text-slate-700">Logo Laundry</label>
                                    <input type="file" name="image" class="w-full text-xs text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                </div>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="block text-xs font-bold text-slate-700">Alamat Lengkap</label>
                            <textarea name="address" rows="2" class="w-full rounded-xl border-slate-200 focus:ring-blue-600"></textarea>
                        </div>

                        <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-100">
                            <x-secondary-button x-on:click="$dispatch('close')" class="rounded-xl">Batalkan</x-secondary-button>
                            <button type="submit" class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-xs uppercase tracking-widest hover:bg-blue-700 transition-all">Daftarkan Partner</button>
                        </div>
                    </form>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>

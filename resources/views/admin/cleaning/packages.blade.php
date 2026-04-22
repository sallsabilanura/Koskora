<x-app-layout>
    @section('header_title', 'Master Data Paket Kebersihan')

    <div class="space-y-12 pb-20" x-data="{ showModal: false }">
        <!-- Premium Header -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="relative pl-6">
                <div class="absolute left-0 top-0 bottom-0 w-2 bg-gradient-to-b from-brand-navy to-brand-red rounded-full"></div>
                <h2 class="text-4xl font-extrabold text-slate-800 tracking-tight italic uppercase">Paket Layanan<span class="text-brand-navy">.</span></h2>
                <p class="text-slate-500 font-semibold text-sm mt-1">Konfigurasi paket kebersihan gedung KosKora.</p>
            </div>
            <button @click="$dispatch('open-modal', 'add-package')" class="group flex items-center gap-4 px-10 py-4.5 bg-brand-navy text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] hover:bg-brand-red transition-all shadow-2xl shadow-brand-navy/10 active:scale-95">
                <span class="p-1 bg-white/10 rounded-lg group-hover:bg-white/20 transition-all">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                </span>
                Buat Paket Baru
            </button>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-5 bg-white border border-slate-100 shadow-xl shadow-slate-50 flex items-center justify-between rounded-2xl group animate-in zoom-in duration-300">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-brand-navy text-white rounded-xl flex items-center justify-center mr-4 group-hover:rotate-12 transition-transform shadow-lg shadow-brand-navy/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.3em] leading-none mb-1">Update Status</p>
                        <p class="text-slate-700 font-bold font-sm tracking-tight italic">{{ $message }}</p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Packages List - Modern Table -->
        <div class="space-y-8">
            <div class="flex items-center space-x-3">
                <div class="w-2 h-8 bg-brand-red rounded-full"></div>
                <h3 class="text-xl font-extrabold text-slate-800 tracking-tight uppercase italic underline decoration-brand-red/20 decoration-4 underline-offset-8">Daftar Katalog Paket</h3>
            </div>

            <div class="bg-white rounded-3xl border border-slate-100 overflow-hidden shadow-2xl shadow-slate-100/50">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50 border-b border-slate-100">
                                <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Package Tier</th>
                                <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Service Scope</th>
                                <th class="px-8 py-6 text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Investment</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($packages as $package)
                                <tr class="hover:bg-slate-50/40 transition-all group">
                                    <td class="px-8 py-8">
                                        <div class="flex flex-col gap-2">
                                            <span class="inline-flex items-center px-4 py-1.5 {{ strtolower($package->name) == 'max' ? 'bg-rose-50 text-rose-600 border-rose-100 shadow-rose-100' : 'bg-brand-navy/5 text-brand-navy border-brand-navy/10 shadow-brand-navy/5' }} text-[11px] font-black rounded-lg border shadow-sm uppercase tracking-widest w-fit group-hover:scale-105 transition-transform">
                                                {{ $package->name }}
                                                @if(strtolower($package->name) == 'max')
                                                    <svg class="w-3 h-3 ml-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                @endif
                                            </span>
                                            <span class="text-[9px] font-bold text-slate-300 uppercase tracking-widest pl-1 mt-1">Tier Code: #PKG{{ $package->id }}</span>
                                        </div>
                                    </td>
                                    <td class="px-8 py-8">
                                        <p class="text-sm text-slate-600 font-medium max-w-xl leading-relaxed italic group-hover:text-slate-900 transition-colors">"{{ $package->description }}"</p>
                                    </td>
                                    <td class="px-8 py-8 text-right">
                                        <div class="flex flex-col items-end gap-1">
                                            <div class="text-2xl font-black text-brand-navy tracking-tighter italic">Rp {{ number_format($package->price, 0, ',', '.') }}</div>
                                            <div class="text-[10px] font-bold text-slate-300 uppercase tracking-widest">Nett Price</div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-8 py-32 text-center bg-white">
                                        <div class="w-20 h-20 bg-slate-50 rounded-2xl flex items-center justify-center mx-auto mb-6 border-2 border-dashed border-slate-100 rotate-3 transition-transform hover:rotate-0">
                                            <svg class="w-10 h-10 text-slate-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                        </div>
                                        <h4 class="text-slate-400 font-black uppercase tracking-[0.4em] text-xs">Tiering Belum Dikonfigurasi</h4>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Premium Add Package Modal -->
        <x-modal name="add-package" focusable maxWidth="3xl">
            <div class="relative">
                <button type="button" @click="$dispatch('close')" class="absolute top-10 right-10 p-2.5 bg-slate-50 text-slate-400 hover:text-brand-red rounded-xl transition-all z-20 shadow-sm border border-slate-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>

                <div class="p-14">
                    <div class="mb-12">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-1.5 bg-brand-navy rounded-full"></div>
                            <span class="text-[11px] font-black text-brand-navy uppercase tracking-[0.4em]">Master Configuration</span>
                        </div>
                        <h3 class="text-4xl font-extrabold text-slate-800 tracking-tighter italic uppercase">Tambah Paket Layanan</h3>
                        <p class="text-slate-400 font-medium text-sm mt-3 leading-relaxed">Pastikan deskripsi layanan mendetail untuk transparansi harga kepada tenant.</p>
                    </div>

                    <form action="{{ route('admin.cleaning.packages.store') }}" method="POST" class="space-y-10">
                        @csrf
                        
                        <div class="space-y-10">
                            <!-- Basic Config -->
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Nama Paket / Tier</label>
                                <input type="text" name="name" required class="w-full h-14 px-8 rounded-lg border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-brand-navy transition-all font-black text-slate-800 italic text-xl uppercase placeholder:lowercase" placeholder="e.g. ULTRA CLEAN">
                            </div>

                            <!-- Description -->
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Service Scope & Description</label>
                                <textarea name="description" rows="5" required class="w-full px-8 py-6 rounded-lg border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-brand-navy transition-all font-medium text-slate-600 text-base leading-relaxed italic" placeholder="Jelaskan cakupan pekerjaan... (Sapu, Pel, Lap, dll)"></textarea>
                            </div>

                            <!-- Pricing -->
                            <div class="space-y-3">
                                <label class="block text-[11px] font-black text-slate-400 uppercase tracking-widest pl-1">Investment (Price IDR)</label>
                                <div class="relative group">
                                    <div class="absolute left-6 top-1/2 -translate-y-1/2 flex items-center pointer-events-none">
                                        <span class="text-lg font-black text-slate-300 group-hover:text-brand-navy transition-colors">Rp</span>
                                        <div class="w-px h-6 bg-slate-100 mx-4"></div>
                                    </div>
                                    <input type="number" name="price" required class="w-full h-16 pl-20 pr-8 rounded-lg border-slate-100 bg-slate-50/50 focus:bg-white focus:ring-brand-navy focus:border-brand-navy transition-all font-black text-slate-800 text-2xl tracking-tighter" placeholder="50.000">
                                </div>
                                <p class="text-[10px] text-slate-400 font-bold italic mt-2 pl-1">Input harga tanpa titik atau koma.</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-6 pt-6">
                            <x-secondary-button x-on:click="$dispatch('close')" class="flex-1 h-16 rounded-xl font-black text-xs uppercase tracking-[0.2em] justify-center hover:bg-slate-50 transition-all border-2 border-slate-100">
                                Abort
                            </x-secondary-button>
                            <button type="submit" class="flex-[3] h-16 bg-brand-navy text-white rounded-xl font-black text-xs uppercase tracking-[0.2em] hover:bg-brand-red transition-all shadow-2xl shadow-brand-navy/10 active:scale-95 group">
                                <span class="flex items-center justify-center gap-3">
                                    Simpan Paket Premium
                                    <svg class="w-4 h-4 group-hover:translate-x-2 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>

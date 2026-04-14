<x-app-layout>
    @section('header_title', 'Harga Layanan Laundry')

    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Manajemen Harga & Layanan</h2>
                <p class="text-slate-500 font-medium">Turunkan harga untuk menarik pelanggan atau tambahkan jenis pakaian baru.</p>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Add New Service -->
            <div class="md:col-span-1 space-y-8">
                <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 italic text-brand-blue">Informasi Rekening</h3>
                    <p class="text-xs text-slate-500 mb-6">Digunakan pelanggan untuk melakukan pembayaran deposit atau pelunasan.</p>
                    <form action="{{ route('laundry.bank-info.update') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nama Bank</label>
                            <input type="text" name="bank_name" value="{{ $laundry->bank_name }}" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" placeholder="Contoh: BCA / Mandiri..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Nomor Rekening</label>
                            <input type="text" name="account_number" value="{{ $laundry->account_number }}" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" placeholder="0001122..." required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Atas Nama</label>
                            <input type="text" name="account_name" value="{{ $laundry->account_name }}" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" placeholder="Nama Pemilik Rekening..." required>
                        </div>
                        <button type="submit" class="w-full py-4 bg-brand-navy text-white rounded-2xl font-black text-sm hover:bg-opacity-90 transition-all">
                            UPDATE REKENING
                        </button>
                    </form>
                </div>

                <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm">
                    <h3 class="text-lg font-bold text-slate-800 mb-6 italic">Tambah Layanan Satuan</h3>
                    <form action="{{ route('laundry.services.store') }}" method="POST" class="space-y-6">
                        @csrf
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Jenis Pakaian / Item</label>
                            <input type="text" name="item_name" class="w-full rounded-2xl border-slate-200 focus:ring-blue-500 font-bold text-slate-700" placeholder="Contoh: Baju Satuan" required>
                        </div>
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2">Harga (Rp)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-black">Rp</span>
                                <input type="number" name="price" class="w-full pl-12 rounded-2xl border-slate-200 focus:ring-blue-500 font-black text-slate-700" placeholder="0" required>
                            </div>
                        </div>
                        <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black text-sm hover:bg-blue-700 transition-all">
                            TAMBAH LAYANAN
                        </button>
                    </form>
                </div>
            </div>

            <!-- Services List -->
            <div class="md:col-span-2 space-y-6">
                <div class="flex items-center space-x-3">
                    <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                    <h3 class="text-xl font-bold text-slate-800 tracking-tight">Daftar Harga Saya</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($services as $service)
                        <div class="bg-white rounded-3xl border border-slate-200 p-6 flex items-center justify-between group hover:border-blue-300 transition-all">
                            <div>
                                <div class="text-sm font-black text-slate-800">{{ $service->item_name }}</div>
                                <div class="text-lg font-black text-blue-600 italic">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                            </div>
                            <form action="{{ route('laundry.services.destroy', $service->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-3 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-2xl transition-all opacity-0 group-hover:opacity-100">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                    @empty
                        <div class="col-span-2 py-20 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[2.5rem] flex flex-col items-center justify-center text-slate-400">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            <div class="font-bold">Belum ada daftar layanan.</div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

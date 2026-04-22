<x-app-layout>
    @section('header_title', 'Daftar Tugas Bebersih')

    <div class="space-y-10 pb-20">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Left Side: Stats & Bank Info -->
            <div class="lg:col-span-1 space-y-8">
                <!-- Bank Info Management -->
                <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 shadow-xl shadow-slate-200/50 relative overflow-hidden group">
                    <div class="relative z-10 space-y-6">
                        <div class="flex items-center space-x-3">
                            <div class="w-1.5 h-6 bg-brand-blue rounded-full"></div>
                            <h3 class="text-lg font-black text-slate-800 tracking-tight uppercase">Informasi Rekening</h3>
                        </div>
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-relaxed">
                            Pastikan data rekening Anda benar agar penghuni bisa melakukan transfer pembayaran.
                        </p>
                        
                        <form action="{{ route('cleaner.bank-info.update') }}" method="POST" class="space-y-5">
                            @csrf
                            @php $cleaner = auth()->user()->cleaner; @endphp
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Nama Bank</label>
                                <input type="text" name="bank_name" value="{{ $cleaner->bank_name }}" 
                                    class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-3 px-4 focus:ring-brand-blue focus:border-brand-blue transition-all" 
                                    placeholder="Contoh: BCA / Mandiri..." required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">No. Rekening</label>
                                <input type="text" name="account_number" value="{{ $cleaner->account_number }}" 
                                    class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-3 px-4 focus:ring-brand-blue focus:border-brand-blue transition-all" 
                                    placeholder="0001122..." required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-slate-500 uppercase tracking-[0.2em] ml-1">Atas Nama</label>
                                <input type="text" name="account_name" value="{{ $cleaner->account_name }}" 
                                    class="w-full bg-slate-50 border-slate-100 rounded-2xl text-xs font-bold py-3 px-4 focus:ring-brand-blue focus:border-brand-blue transition-all" 
                                    placeholder="Nama Sesuai Buku Tabungan..." required>
                            </div>
                            <button type="submit" class="w-full py-4 bg-brand-navy text-white rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-brand-blue transition-all shadow-lg active:scale-95">
                                Update Data Rekening
                            </button>
                        </form>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-slate-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
                </div>

                <!-- Simple Stats -->
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden">
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest relative z-10">Mendatang</div>
                        <div class="text-3xl font-black text-slate-800 tracking-tighter relative z-10">{{ $orders->where('status', 'pending')->count() }}</div>
                        <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-blue-50 rounded-full opacity-30"></div>
                    </div>
                    <div class="p-6 bg-white rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between h-32 relative overflow-hidden">
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest relative z-10">Dikerjakan</div>
                        <div class="text-3xl font-black text-slate-800 tracking-tighter relative z-10">{{ $orders->where('status', 'working')->count() }}</div>
                        <div class="absolute -right-4 -bottom-4 w-12 h-12 bg-rose-50 rounded-full opacity-30"></div>
                    </div>
                </div>
            </div>

            <!-- Right Side: Order List -->
            <div class="lg:col-span-2 space-y-8">
                @if ($message = Session::get('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm animate-fade-in">
                        <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <span class="text-sm font-bold">{{ $message }}</span>
                    </div>
                @endif

                <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
                    <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                        <h3 class="text-xl font-black text-slate-800 tracking-tight italic uppercase">Monitoring Tugas</h3>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Update status & Verifikasi Pembayaran</span>
                    </div>
                    
                    <div class="overflow-x-auto">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-50">
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kamar & Penghuni</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tagihan</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status Kerja</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-50">
                                @forelse($orders as $order)
                                    <tr class="hover:bg-slate-50/50 transition-all group">
                                        <td class="px-8 py-6">
                                            <div class="flex items-center space-x-4">
                                                <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-lg group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                                                    @php $rental = optional($order->user->tenant)->rentals ? $order->user->tenant->rentals->first() : null; @endphp
                                                    {{ $rental && $rental->room ? $rental->room->room_number : '?' }}
                                                </div>
                                                <div>
                                                    <div class="text-sm font-black text-slate-800 tracking-tight italic">{{ $order->user->name }}</div>
                                                    <div class="text-[9px] font-bold text-slate-400 uppercase mt-0.5 tracking-widest">{{ $order->package->name }} Package</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="space-y-1.5">
                                                <div class="text-sm font-black text-brand-blue tracking-tighter">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                                
                                                <div class="flex items-center flex-wrap gap-2">
                                                    @php 
                                                        $pStatus = $order->payment_status ?? 'unpaid';
                                                        $pLabel = $pStatus == 'unpaid' ? 'UNPAID' : ($pStatus == 'pending' ? 'MENUNGGU VERIFIKASI' : 'PAID (LUNAS)');
                                                        $pColor = $pStatus == 'unpaid' ? 'bg-rose-50 text-rose-600 border-rose-100' : ($pStatus == 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse' : 'bg-emerald-50 text-emerald-600 border-emerald-100');
                                                    @endphp
                                                    <span class="px-2 py-0.5 {{ $pColor }} text-[8px] font-black rounded uppercase tracking-widest border shrink-0">{{ $pLabel }}</span>
                                                    
                                                    @if($pStatus == 'pending' && $order->payment_proof)
                                                        <div class="flex items-center gap-2">
                                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="p-1.5 bg-slate-100 text-slate-400 rounded-lg hover:bg-brand-blue hover:text-white transition-all shadow-sm shrink-0" title="Lihat Bukti">
                                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                            </a>
                                                            <form action="{{ route('cleaner.verify-payment', $order->id) }}" method="POST" class="shrink-0">
                                                                @csrf
                                                                <button type="submit" class="px-3 py-1 bg-emerald-600 text-white text-[8px] font-black uppercase tracking-widest rounded-lg hover:bg-emerald-700 transition-all shadow-lg active:scale-95 flex items-center gap-1">
                                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                                    Verifikasi
                                                                </button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-8 py-6">
                                            <div class="flex flex-col items-center gap-2">
                                                <form action="{{ route('cleaner.orders.status', $order->id) }}" method="POST">
                                                    @csrf
                                                    <select name="status" onchange="this.form.submit()" class="w-full bg-slate-100 border-none rounded-xl text-[9px] font-black uppercase tracking-widest py-2 px-6 focus:ring-brand-blue cursor-pointer appearance-none text-center shadow-inner hover:bg-slate-200 transition-colors">
                                                        <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                        <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                        <option value="working" {{ $order->status == 'working' ? 'selected' : '' }}>Working</option>
                                                        <option value="done" {{ $order->status == 'done' ? 'selected' : '' }}>Done</option>
                                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                    </select>
                                                </form>
                                                <span class="text-[8px] font-bold text-slate-400 uppercase tracking-widest">{{ $order->scheduled_at->format('d M, H:i') }}</span>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-8 py-24 text-center">
                                            <div class="flex flex-col items-center opacity-20">
                                                <svg class="w-16 h-16 text-slate-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                                <span class="text-sm font-black uppercase tracking-widest italic">Belum ada tugas pembersihan</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

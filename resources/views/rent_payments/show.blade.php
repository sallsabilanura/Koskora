<x-app-layout>
    @section('header_title', 'Invoice Detail')

    <div class="max-w-2xl mx-auto space-y-8 animate-fade-in pb-20">
        {{-- ===== HEADER ACTIONS ===== --}}
        <div class="flex items-center justify-between px-2">
            <a href="{{ route('rent-payments.my-payments') }}" class="w-10 h-10 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 hover:text-brand transition-all shadow-sm active:scale-90">
                <i class="fas fa-arrow-left text-xs"></i>
            </a>
            <div class="flex items-center gap-2">
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">KosKora</h3>
                <div class="w-2 h-2 bg-brand rounded-full animate-pulse"></div>
            </div>
            <button class="w-10 h-10 bg-white border border-slate-100 rounded-2xl flex items-center justify-center text-slate-400 shadow-sm">
                <i class="far fa-bell text-xs"></i>
            </button>
        </div>

        {{-- ===== INVOICE CARD ===== --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-2xl shadow-slate-200/50 overflow-hidden">
            <div class="p-8 md:p-10 space-y-10">
                
                {{-- Status & ID --}}
                <div class="flex items-center justify-between">
                    <div class="space-y-1">
                        <div class="text-[9px] font-black text-slate-300 uppercase tracking-widest leading-none">Invoice #{{ strtoupper(substr($rentPayment->id, 0, 8)) }}</div>
                        <h2 class="text-xl font-black text-slate-800 tracking-tight leading-none">{{ $rentPayment->month }} Rent</h2>
                    </div>
                    @php
                        $statusBadge = [
                            'paid' => 'bg-emerald-50 text-emerald-500 border-emerald-100',
                            'pending' => 'bg-amber-50 text-amber-500 border-amber-100',
                            'unpaid' => 'bg-rose-50 text-rose-500 border-rose-100'
                        ][$rentPayment->status] ?? 'bg-slate-50 text-slate-400 border-slate-100';
                    @endphp
                    <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $statusBadge }}">
                        {{ $rentPayment->status }}
                    </span>
                </div>

                {{-- Due Date --}}
                <div class="flex items-center gap-3 p-5 bg-slate-50 rounded-3xl">
                    <div class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-brand shadow-sm">
                        <i class="far fa-calendar-alt text-sm"></i>
                    </div>
                    <div>
                        <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Waktu Pembayaran</div>
                        <div class="text-[11px] font-black text-slate-700">{{ \Carbon\Carbon::parse($rentPayment->payment_date)->format('M d, Y') }}</div>
                    </div>
                </div>

                {{-- Amount --}}
                <div class="flex items-center justify-between py-2 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Tagihan</span>
                    <span class="text-2xl font-black text-brand tracking-tighter italic">Rp {{ number_format($rentPayment->amount, 0, ',', '.') }}</span>
                </div>

                {{-- Billing Breakdown --}}
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Billing Breakdown</h4>
                    <div class="space-y-3">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-xs font-black text-slate-800">Sewa Bulanan</div>
                                <div class="text-[9px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">Unit {{ $rentPayment->room->room_number }} — {{ $rentPayment->room->room_type }}</div>
                            </div>
                            <span class="text-xs font-black text-slate-700 tracking-tight">Rp {{ number_format($rentPayment->amount, 0, ',', '.') }}</span>
                        </div>
                        
                        <div class="flex items-start justify-between border-t border-slate-50 pt-3">
                            <div>
                                <div class="text-xs font-black text-slate-800">Service Fee</div>
                                <div class="text-[9px] font-bold text-slate-400 mt-0.5 uppercase tracking-wider">Keamanan, Kebersihan, Air</div>
                            </div>
                            <span class="text-xs font-black text-slate-700 tracking-tight">Included</span>
                        </div>
                    </div>
                </div>

                {{-- Payment Information --}}
                <div class="space-y-4">
                    <h4 class="text-[10px] font-black text-slate-300 uppercase tracking-[0.3em]">Payment Information</h4>
                    <div class="p-6 bg-slate-50 rounded-[2rem] border border-slate-100 flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center text-brand shadow-sm">
                                <i class="fas fa-university text-lg"></i>
                            </div>
                            <div>
                                <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Metode Bayar</div>
                                <div class="text-xs font-black text-slate-800 uppercase tracking-tighter">{{ $rentPayment->method ?: 'TRANSFER MANUAL' }}</div>
                            </div>
                        </div>
                        <i class="fas fa-chevron-right text-slate-200 text-xs"></i>
                    </div>
                    @if($rentPayment->payment_date)
                        <div class="flex items-center gap-2 px-6 text-slate-400">
                            <i class="far fa-clock text-[10px]"></i>
                            <span class="text-[9px] font-black uppercase tracking-widest">Paid on {{ \Carbon\Carbon::parse($rentPayment->payment_date)->format('M d, Y') }}</span>
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="space-y-3 pt-6 border-t border-slate-50">
                    <button onclick="window.print()" class="w-full py-5 bg-brand text-white rounded-3xl font-black text-[10px] uppercase tracking-widest shadow-xl shadow-brand/20 active:scale-95 transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-download"></i>
                        Download Receipt
                    </button>
                    @if($rentPayment->payment_proof)
                        <a href="{{ asset('storage/' . $rentPayment->payment_proof) }}" target="_blank" class="w-full py-5 bg-slate-100 text-slate-600 rounded-3xl font-black text-[10px] uppercase tracking-widest active:scale-95 transition-all flex items-center justify-center gap-2 hover:bg-slate-200">
                            <i class="fas fa-image"></i>
                            Lihat Bukti Bayar
                        </a>
                    @endif
                    <button class="w-full py-5 bg-white border border-slate-100 text-slate-400 rounded-3xl font-black text-[10px] uppercase tracking-widest hover:text-brand transition-all flex items-center justify-center gap-2">
                        <i class="fas fa-share-alt"></i>
                        Share Invoice
                    </button>
                </div>
            </div>

            {{-- Support Footer --}}
            <div class="bg-blue-50/50 p-8 text-center border-t border-blue-50/50">
                <p class="text-[10px] font-bold text-blue-400 uppercase tracking-widest mb-1">Ada kendala dengan pembayaran ini?</p>
                <a href="#" class="text-[11px] font-black text-blue-600 underline underline-offset-4">Hubungi Property Manager <i class="fas fa-external-link-alt ml-1"></i></a>
            </div>
        </div>
    </div>
</x-app-layout>

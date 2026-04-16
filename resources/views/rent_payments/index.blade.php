<x-app-layout>
    @section('header_title', 'Rent Payments')

    <div class="space-y-6 md:space-y-10" x-data="{ 
        rejectModal: false, 
        rejectId: null, 
        rejectAction: '',
        openReject(id, action) {
            this.rejectId = id;
            this.rejectAction = action;
            this.rejectModal = true;
        }
    }">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-1">
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Manajemen Pembayaran</h2>
                <p class="text-sm font-medium text-slate-400">Kelola dan verifikasi tagihan bulanan penyewa KosKora.</p>
            </div>
            <a href="{{ route('rent-payments.create') }}" class="inline-flex items-center justify-center px-6 py-3 bg-slate-900 border border-transparent rounded-2xl font-black text-xs text-white uppercase tracking-widest hover:bg-slate-800 active:scale-95 transition-all shadow-xl shadow-slate-200 btn-touch">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Catat Pembayaran
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm text-sm font-bold animate-fade-in">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/></svg>
                {{ $message }}
            </div>
        @endif

        @if ($error = Session::get('error'))
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-center shadow-sm text-sm font-bold animate-fade-in">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/></svg>
                {{ $error }}
            </div>
        @endif

        <!-- Desktop Table (Hidden on small screens) -->
        <div class="hidden lg:block bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-2xl shadow-slate-200/50">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-50">
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Penyewa & Unit</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Periode Tagihan</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Bukti Bayar</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                        <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Manajemen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-6">
                            <div class="flex items-center space-x-4">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs uppercase group-hover:bg-blue-600 group-hover:text-white transition-all">
                                    {{ substr($payment->tenants->user->name ?? '?', 0, 2) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-800 leading-none">{{ $payment->tenants->user->name ?? 'User '.$payment->tenants->nik }}</div>
                                    <div class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mt-1">Room {{ $payment->room->room_number ?? '-' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm font-black text-slate-700 leading-none">{{ $payment->month }}</div>
                            <div class="text-[10px] text-slate-400 font-bold mt-1">Pay date: {{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="text-sm font-black text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @if($payment->payment_proof)
                                <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="inline-flex p-1 bg-white border border-slate-200 rounded-xl shadow-sm hover:scale-110 transition-transform">
                                    <img src="{{ asset('storage/' . $payment->payment_proof) }}" class="w-8 h-8 object-cover rounded-lg">
                                </a>
                            @else
                                <span class="text-[9px] font-black text-slate-200 uppercase tracking-widest">No Proof</span>
                            @endif
                        </td>
                        <td class="px-8 py-6 text-center">
                            @php
                                $statusStyles = [
                                    'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100 animate-pulse',
                                    'unpaid' => 'bg-rose-50 text-rose-600 border-rose-100'
                                ];
                            @endphp
                            <span class="px-3 py-1 text-[9px] font-black rounded-full uppercase tracking-widest border {{ $statusStyles[$payment->status] ?? 'bg-slate-50' }}">
                                {{ $payment->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end space-x-2">
                                @if($payment->status == 'pending')
                                    <form action="{{ route('rent-payments.verify', $payment->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-[9px] font-black rounded-xl hover:bg-blue-700 transition-all shadow-lg shadow-blue-100">
                                            VERIFY
                                        </button>
                                    </form>
                                    <button @click="openReject({{ $payment->id }}, '{{ route('rent-payments.reject', $payment->id) }}')" class="inline-flex items-center px-4 py-2 bg-rose-600 text-white text-[9px] font-black rounded-xl hover:bg-rose-700 transition-all shadow-lg shadow-rose-100 uppercase tracking-widest">
                                        REJECT
                                    </button>
                                @endif
                                <a href="{{ route('rent-payments.show', $payment->id) }}" class="p-2 text-slate-300 hover:text-slate-600 transition-colors"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></a>
                                <a href="{{ route('rent-payments.edit', $payment->id) }}" class="p-2 text-slate-300 hover:text-amber-500 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg></a>
                                <form action="{{ route('rent-payments.destroy', $payment->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus permanen data ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-slate-300 hover:text-rose-500 transition-colors"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center text-slate-300 font-bold italic">Belum ada data pembayaran.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Cards (Shown only on small screens) -->
        <div class="lg:hidden space-y-4 pb-20">
            @foreach ($payments as $payment)
                <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-50 space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="w-8 h-8 rounded-full bg-slate-900 flex items-center justify-center text-white font-black text-[10px] uppercase">
                                {{ substr($payment->tenants->user->name ?? '?', 0, 2) }}
                            </div>
                            <div class="min-w-0">
                                <div class="font-black text-slate-800 text-sm truncate uppercase tracking-tight">{{ $payment->tenants->user->name ?? 'User '.$payment->tenants->nik }}</div>
                                <div class="text-[9px] font-black text-blue-600 uppercase tracking-widest">Room {{ $payment->room->room_number ?? '-' }}</div>
                            </div>
                        </div>
                        <span class="px-2.5 py-0.5 text-[8px] font-black rounded-full uppercase tracking-widest border {{ $statusStyles[$payment->status] ?? 'bg-slate-50' }}">
                            {{ $payment->status }}
                        </span>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-slate-50 p-4 rounded-2xl">
                        <div>
                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Periode</div>
                            <div class="text-xs font-black text-slate-700 uppercase tracking-tight">{{ $payment->month }}</div>
                        </div>
                        <div class="text-right">
                            <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-1">Nominal</div>
                            <div class="text-xs font-black text-slate-900 tracking-tight italic">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        @if($payment->payment_proof)
                            <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" class="text-[9px] font-black text-blue-600 underline uppercase tracking-widest">Lihat Bukti</a>
                        @else
                            <span class="text-[9px] font-black text-slate-200 uppercase tracking-widest italic">No proof</span>
                        @endif
                        
                        <div class="flex items-center space-x-2">
                            @if($payment->status == 'pending')
                                <form action="{{ route('rent-payments.verify', $payment->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-[9px] font-black rounded-lg btn-touch shadow-lg shadow-blue-100 uppercase tracking-widest">Verify</button>
                                </form>
                                <button @click="openReject({{ $payment->id }}, '{{ route('rent-payments.reject', $payment->id) }}')" class="px-4 py-2 bg-rose-600 text-white text-[9px] font-black rounded-lg btn-touch shadow-lg shadow-rose-100 uppercase tracking-widest">Reject</button>
                            @else
                                <a href="{{ route('rent-payments.show', $payment->id) }}" class="px-4 py-2 bg-slate-900 text-white text-[9px] font-black rounded-lg btn-touch uppercase tracking-widest">Detail</a>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {!! $payments->links() !!}

        <!-- Rejection Modal -->
        <div x-show="rejectModal" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm"
             style="display: none;">
            
            <div @click.away="rejectModal = false" class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-slide-up">
                <div class="p-8 pb-0">
                    <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-6">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight">Tolak Pembayaran?</h3>
                    <p class="text-sm font-medium text-slate-400 mt-2">Dana yang telah dikirim akan dikembalikan (manual) atau diminta kirim ulang bukti yang valid. Tuliskan alasannya:</p>
                </div>

                <form :action="rejectAction" method="POST" class="p-8">
                    @csrf
                    <textarea name="rejection_reason" required
                              class="w-full h-32 bg-slate-50 border-none rounded-2xl text-sm font-bold placeholder:text-slate-300 focus:ring-2 focus:ring-rose-500 transition-all p-4"
                              placeholder="Contoh: Bukti transfer tidak jelas / nominal tidak sesuai..."></textarea>
                    
                    <div class="mt-6 flex gap-3">
                        <button type="button" @click="rejectModal = false" class="flex-1 px-6 py-4 bg-slate-100 text-slate-600 rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all">
                            Batal
                        </div>
                        <button type="submit" class="flex-1 px-6 py-4 bg-rose-600 text-white rounded-2xl font-black text-[10px] shadow-xl shadow-rose-100 hover:bg-rose-700 transition-all uppercase tracking-widest">
                            Ya, Tolak Pembayaran
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.5s ease-out; }
        .animate-slide-up { animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</x-app-layout>

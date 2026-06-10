<x-app-layout>
    @section('header_title', 'Keuangan')

    <div class="space-y-8 animate-fade-in pb-10">
        {{-- ===== BALANCE OVERVIEW ===== --}}
        <div class="bg-slate-900 rounded-[3rem] p-10 text-white shadow-[0_30px_60px_-15px_rgba(15,23,42,0.3)] relative overflow-hidden group">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-10">
                <div class="space-y-4 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 bg-white/5 px-3 py-1 rounded-full border border-white/5">
                        <span class="w-1.5 h-1.5 bg-brand rounded-full animate-pulse"></span>
                        <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Saldo Tersedia</span>
                    </div>
                    <h2 class="text-4xl md:text-6xl font-black tracking-tighter italic">Rp {{ number_format($balance, 0, ',', '.') }}</h2>
                    <p class="text-slate-500 text-[10px] font-black uppercase tracking-[0.2em] max-w-sm">Dana siap cair ke rekening utama Anda.</p>
                </div>
                <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'withdraw-modal' }))" 
                    class="px-10 py-5 bg-brand text-white rounded-2xl font-black text-[10px] uppercase tracking-widest shadow-2xl shadow-brand/20 hover:scale-105 transition-all active:scale-95 disabled:opacity-20 disabled:pointer-events-none"
                    {{ $balance < 10000 ? 'disabled' : '' }}>
                    Tarik Dana Sekarang
                </button>
            </div>
            {{-- Decorative accents --}}
            <div class="absolute -right-12 -bottom-12 w-64 h-64 bg-brand opacity-10 rounded-full blur-3xl transition-transform duration-700 group-hover:scale-110"></div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm text-xs font-bold">
                <i class="fas fa-check-circle mr-3"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== WITHDRAWAL HISTORY ===== --}}
        <div class="space-y-6">
            <div class="flex items-center gap-3 px-2">
                <div class="w-1.5 h-6 bg-slate-900 rounded-full"></div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Riwayat Penarikan</h3>
            </div>

            <div class="bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50/50 border-b border-slate-50">
                            <tr>
                                <th class="px-8 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Waktu</th>
                                <th class="px-8 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                                <th class="px-8 py-5 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                <th class="px-8 py-5 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Informasi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($withdrawals as $w)
                                <tr class="group hover:bg-slate-50/30 transition-colors">
                                    <td class="px-8 py-6">
                                        <div class="text-[11px] font-black text-slate-800 uppercase leading-none mb-1">{{ $w->created_at->format('d M Y') }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $w->created_at->format('H:i') }} WIB</div>
                                    </td>
                                    <td class="px-8 py-6">
                                        <div class="text-sm font-black text-slate-900 italic tracking-tighter">Rp {{ number_format($w->amount, 0, ',', '.') }}</div>
                                    </td>
                                    <td class="px-8 py-6 text-center">
                                        @php
                                            $sClass = [
                                                'pending' => 'badge-amber',
                                                'approved' => 'badge-green',
                                                'rejected' => 'badge-red',
                                            ][$w->status] ?? 'badge-gray';
                                        @endphp
                                        <span class="badge {{ $sClass }} !text-[9px] uppercase font-black">{{ $w->status }}</span>
                                    </td>
                                    <td class="px-8 py-6 text-right">
                                        @if($w->payment_proof)
                                            <a href="{{ asset('storage/' . $w->payment_proof) }}" target="_blank" 
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-brand hover:text-white transition-all">
                                                <i class="fas fa-file-invoice-dollar"></i> Bukti Bayar
                                            </a>
                                        @elseif($w->status == 'rejected')
                                            <span class="text-[9px] font-black text-rose-400 uppercase tracking-widest italic">Ditolak: {{ $w->notes }}</span>
                                        @else
                                            <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest italic">Proses Verifikasi</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-20 text-center">
                                        <i class="fas fa-history text-4xl mb-4 text-slate-100"></i>
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Belum ada aktivitas penarikan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- ===== WITHDRAWAL MODAL ===== --}}
    <x-modal name="withdraw-modal" focusable maxWidth="md">
        <div class="p-10 space-y-8">
            <div class="text-center">
                <div class="w-16 h-16 bg-brand/10 text-brand rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-xl shadow-brand/5">
                    <i class="fas fa-wallet text-2xl"></i>
                </div>
                <h3 class="text-xl font-black text-slate-800 tracking-tighter uppercase leading-none">Cairkan Dana</h3>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-3">Transfer ke rekening utama</p>
            </div>

            <form action="{{ route('laundry.withdrawals.store') }}" method="POST" class="space-y-8">
                @csrf
                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Nominal (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-6 top-1/2 -translate-y-1/2 text-brand font-black text-sm italic">Rp</span>
                        <input type="number" name="amount" min="10000" max="{{ $balance }}" step="1000" 
                               class="w-full h-16 bg-slate-50 border-transparent rounded-2xl !pl-14 text-xl font-black italic tracking-tighter focus:ring-brand focus:border-brand transition-all outline-none" placeholder="0" required>
                    </div>
                    <div class="flex items-center justify-between px-1">
                        <span class="text-[8px] font-black text-slate-300 uppercase tracking-widest">Min. Rp 10.000</span>
                        <span class="text-[8px] font-black text-brand uppercase tracking-widest">Max. {{ number_format($balance, 0, ',', '.') }}</span>
                    </div>
                </div>

                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Pesan (Opsional)</label>
                    <textarea name="notes" rows="2" class="w-full bg-slate-50 border-transparent rounded-2xl p-6 text-xs font-bold text-slate-600 focus:ring-brand focus:border-brand transition-all outline-none resize-none" placeholder="Tulis instruksi tambahan..."></textarea>
                </div>

                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" class="w-full py-5 bg-slate-900 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl active:scale-95 transition-all">Konfirmasi</button>
                    <button type="button" @click="$dispatch('close')" class="text-center py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-brand transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>

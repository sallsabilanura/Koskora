<x-app-layout>
    @section('header_title', 'Keuangan Kebersihan')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== BALANCE OVERVIEW ===== --}}
        <div class="stat-card !bg-brand !border-none text-white relative overflow-hidden group">
            <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8 p-4">
                <div class="space-y-2 text-center md:text-left">
                    <div class="inline-flex items-center gap-2 bg-white/10 px-3 py-1 rounded-full backdrop-blur-sm">
                        <span class="w-1.5 h-1.5 bg-brand-light rounded-full"></span>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-brand-light">Saldo Pendapatan</span>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-bold tracking-tight">Rp {{ number_format($balance, 0, ',', '.') }}</h2>
                    <p class="text-brand-light/70 text-xs font-medium max-w-sm">Dapatkan komisi bersih Anda secara instan setelah tugas diselesaikan.</p>
                </div>
                <button onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'withdraw-modal' }))" 
                    class="btn !bg-white !text-brand hover:!bg-brand-light px-10 !h-14 !text-[13px] shadow-xl shadow-brand/20"
                    {{ $balance < 10000 ? 'disabled' : '' }}>
                    Cairkan Dana Sekarang
                </button>
            </div>
            {{-- Decorative accents --}}
            <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-white/10 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700"></div>
        </div>

        @if ($message = Session::get('success'))
            <div class="badge badge-green w-full justify-start p-3 rounded-xl border border-emerald-100">
                <i class="fas fa-check-circle mr-2"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== WITHDRAWAL HISTORY ===== --}}
        <div class="space-y-4 pt-4">
            <div class="flex items-center gap-2">
                <span class="w-1 h-4 bg-brand rounded-full"></span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Riwayat Pencairan</h3>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Tanggal & Waktu</th>
                            <th>Nominal Pencairan</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Keterangan / Bukti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($withdrawals as $w)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-900">{{ $w->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">{{ $w->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td>
                                    <div class="text-[13px] font-bold text-slate-800">Rp {{ number_format($w->amount, 0, ',', '.') }}</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusBadge = [
                                            'pending' => 'badge-amber',
                                            'approved' => 'badge-green',
                                            'rejected' => 'badge-red',
                                        ][$w->status] ?? 'badge-gray';
                                    @endphp
                                    <span class="badge {{ $statusBadge }}">{{ ucfirst($w->status) }}</span>
                                </td>
                                <td class="text-right">
                                    @if($w->payment_proof)
                                        <a href="{{ asset('storage/' . $w->payment_proof) }}" target="_blank" 
                                           class="text-[11px] font-bold text-brand hover:underline inline-flex items-center gap-1.5">
                                            <i class="fas fa-file-invoice"></i> Lihat Bukti Bayar
                                        </a>
                                    @elseif($w->status == 'rejected')
                                        <span class="text-[10px] font-medium text-red-400 italic">"{{ $w->notes }}"</span>
                                    @else
                                        <span class="text-[10px] text-slate-300 italic font-medium">Sedang diproses admin...</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-history"></i></div>
                                        <p>Belum ada riwayat pencairan dana.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== WITHDRAWAL MODAL ===== --}}
    <x-modal name="withdraw-modal" focusable maxWidth="md">
        <div class="p-8">
            <div class="text-center mb-8">
                <div class="w-12 h-12 bg-brand-light text-brand rounded-xl flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-hand-holding-usd text-xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Cairkan Pendapatan</h3>
                <p class="text-slate-500 text-[13px] mt-0.5">Saldo akan ditransfer ke rekening operasional Anda.</p>
            </div>

            <form action="{{ route('cleaner.withdrawals.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Nominal (Rp)</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-brand font-bold text-sm">Rp</span>
                        <input type="number" name="amount" min="10000" max="{{ $balance }}" step="1000" 
                               class="!pl-11 !text-lg !font-bold" placeholder="0" required>
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Catatan</label>
                    <textarea name="notes" rows="2" placeholder="Catatan opsional..."></textarea>
                </div>

                <div class="flex flex-col gap-2 pt-4">
                    <button type="submit" class="btn btn-primary w-full !h-12">Konfirmasi Pencairan</button>
                    <button type="button" @click="$dispatch('close')" class="btn btn-ghost w-full">Batalkan</button>
                </div>
            </form>
        </div>
    </x-modal>
</x-app-layout>

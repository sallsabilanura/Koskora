<x-app-layout>
    @section('header_title', 'Finance Admin')

    <div class="space-y-8 animate-fade-in pb-10 px-2">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 px-2">
            <div>
                <h2 class="text-2xl font-black text-slate-800 tracking-tighter uppercase leading-none">Manajemen Penarikan</h2>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-2">Kelola dana keluar mitra KosKora</p>
            </div>
            <div class="inline-flex items-center gap-3 bg-amber-500/10 border border-amber-500/20 px-4 py-2 rounded-2xl">
                <span class="w-2 h-2 bg-amber-500 rounded-full animate-pulse"></span>
                <span class="text-[10px] font-black text-amber-600 uppercase tracking-widest">{{ $withdrawals->where('status', 'pending')->count() }} Permintaan Baru</span>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center shadow-sm text-xs font-bold animate-fade-in">
                <i class="fas fa-check-circle mr-3"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== TABLE SECTION ===== --}}
        <div class="bg-white border border-slate-100 rounded-[2.5rem] shadow-2xl shadow-slate-200/50 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-slate-50/50 border-b border-slate-50">
                        <tr>
                            <th class="px-8 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Partner</th>
                            <th class="px-8 py-5 text-left text-[9px] font-black text-slate-400 uppercase tracking-widest">Nominal & Waktu</th>
                            <th class="px-8 py-5 text-center text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            <th class="px-8 py-5 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($withdrawals as $w)
                            <tr class="group hover:bg-slate-50/30 transition-colors">
                                <td class="px-8 py-6">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center font-black text-xs shadow-lg shadow-slate-900/10">
                                            {{ substr($w->user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-[11px] font-black text-slate-800 uppercase leading-none mb-1">{{ $w->user->name }}</div>
                                            <div class="text-[9px] font-black text-brand uppercase tracking-widest italic">{{ $w->user->role }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-black text-slate-900 italic tracking-tighter mb-1">Rp {{ number_format($w->amount, 0, ',', '.') }}</div>
                                    <div class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $w->created_at->format('d M Y, H:i') }}</div>
                                </td>
                                <td class="px-8 py-6 text-center">
                                    @php
                                        $bCls = [
                                            'pending' => 'badge-amber',
                                            'approved' => 'badge-green',
                                            'rejected' => 'badge-red',
                                        ][$w->status] ?? 'badge-gray';
                                    @endphp
                                    <span class="badge {{ $bCls }} !text-[9px] uppercase font-black">{{ $w->status }}</span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        @if($w->status == 'pending')
                                            <button onclick="openApproveModal({{ $w->id }}, {{ $w->amount }})" 
                                                    class="w-9 h-9 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center hover:bg-emerald-500 hover:text-white transition-all shadow-sm active:scale-90" title="Setujui">
                                                <i class="fas fa-check text-[10px]"></i>
                                            </button>
                                            <button onclick="openRejectModal({{ $w->id }})" 
                                                    class="w-9 h-9 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm active:scale-90" title="Tolak">
                                                <i class="fas fa-times text-[10px]"></i>
                                            </button>
                                        @elseif($w->payment_proof)
                                            <a href="{{ asset('storage/' . $w->payment_proof) }}" target="_blank" 
                                               class="inline-flex items-center gap-2 px-4 py-2 bg-slate-50 text-slate-400 rounded-lg text-[9px] font-black uppercase tracking-widest hover:bg-brand hover:text-white transition-all shadow-sm">
                                                <i class="fas fa-receipt"></i> Bukti Bayar
                                            </a>
                                        @else
                                            <span class="text-[9px] font-black text-slate-200 uppercase tracking-widest italic">Archived</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-20 text-center">
                                    <i class="fas fa-wallet text-4xl mb-4 text-slate-100"></i>
                                    <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest italic">Tidak ada permintaan pencairan dana</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- ===== APPROVE MODAL ===== --}}
    <div id="approveModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-modal-up">
            <form id="approveForm" method="POST" enctype="multipart/form-data" class="p-10 space-y-8">
                @csrf
                <div class="text-center">
                    <div class="w-16 h-16 bg-emerald-50 text-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-check-double text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tighter uppercase leading-none">Verifikasi Bayar</h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-3">Nominal: <span id="approveAmountLabel" class="text-emerald-500"></span></p>
                </div>

                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Upload Bukti Transfer</label>
                    <div class="relative group">
                        <input type="file" name="payment_proof" class="block w-full text-[10px] text-slate-500 file:mr-4 file:py-4 file:px-6 file:rounded-2xl file:border-0 file:text-[10px] file:font-black file:bg-slate-900 file:text-white hover:file:bg-brand transition-all cursor-pointer" required>
                    </div>
                </div>

                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" class="w-full py-5 bg-emerald-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-emerald-500/20 active:scale-95 transition-all">Konfirmasi Pembayaran</button>
                    <button type="button" onclick="closeApproveModal()" class="text-center py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-brand transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ===== REJECT MODAL ===== --}}
    <div id="rejectModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-sm" style="display: none;">
        <div class="bg-white w-full max-w-md rounded-[2.5rem] shadow-2xl overflow-hidden animate-modal-up">
            <form id="rejectForm" method="POST" class="p-10 space-y-8">
                @csrf
                <div class="text-center">
                    <div class="w-16 h-16 bg-rose-50 text-rose-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-times-circle text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tighter uppercase leading-none">Tolak Permintaan</h3>
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mt-3">Berikan alasan penolakan yang jelas</p>
                </div>

                <div class="space-y-3">
                    <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest ml-1">Alasan Penolakan</label>
                    <textarea name="notes" rows="3" class="w-full bg-slate-50 border-transparent rounded-2xl p-6 text-xs font-bold text-slate-600 focus:ring-rose-500 focus:border-rose-500 transition-all outline-none resize-none" placeholder="Tulis alasan penolakan..." required></textarea>
                </div>

                <div class="flex flex-col gap-3 pt-4">
                    <button type="submit" class="w-full py-5 bg-rose-500 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-xl shadow-rose-500/20 active:scale-95 transition-all">Tolak Sekarang</button>
                    <button type="button" onclick="closeRejectModal()" class="text-center py-2 text-[10px] font-black text-slate-400 uppercase tracking-widest hover:text-brand transition-colors">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openApproveModal(id, amount) {
            document.getElementById('approveForm').action = `/admin/withdrawals/${id}/approve`;
            document.getElementById('approveAmountLabel').innerText = 'Rp ' + new Intl.NumberFormat('id-ID').format(amount);
            document.getElementById('approveModal').classList.remove('hidden');
            document.getElementById('approveModal').style.display = 'flex';
        }
        function closeApproveModal() {
            document.getElementById('approveModal').classList.add('hidden');
            document.getElementById('approveModal').style.display = 'none';
        }
        function openRejectModal(id) {
            document.getElementById('rejectForm').action = `/admin/withdrawals/${id}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').style.display = 'flex';
        }
        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').style.display = 'none';
        }
    </script>

    <style>
        @keyframes modal-up {
            from { opacity: 0; transform: translateY(40px) scale(0.95); }
            to { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-modal-up { animation: modal-up 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
    </style>
</x-app-layout>

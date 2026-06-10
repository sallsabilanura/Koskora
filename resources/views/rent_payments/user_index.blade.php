<x-app-layout>
    @section('header_title', 'Billing & Pembayaran')

    <div class="space-y-8 animate-fade-in pb-10">
        {{-- ===== TOP OVERVIEW ===== --}}
        @if($activeRental)
            <div class="container-card !p-8 relative overflow-hidden group">
                <div class="flex flex-col md:flex-row items-start md:items-center justify-between gap-8 relative z-10">
                    <div class="flex items-center gap-6">
                        <div class="w-16 h-16 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $currentPaymentStatus == 'paid' ? 'bg-emerald-50 text-emerald-500' : 'bg-amber-50 text-amber-500' }}">
                            <i class="fas {{ $currentPaymentStatus == 'paid' ? 'fa-check-double' : 'fa-file-invoice-dollar' }} text-2xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-bold text-slate-900 tracking-tight">
                                {{ $currentPaymentStatus == 'paid' ? 'Tagihan Terverifikasi' : 'Pembayaran Menanti' }}
                            </h4>
                            <p class="text-slate-500 text-sm mt-1">
                                {{ $currentPaymentStatus == 'paid' 
                                    ? 'Pembayaran periode ini sudah lunas. Terima kasih!' 
                                    : 'Selesaikan pembayaran unit Anda untuk periode ' . date('F Y') }}
                            </p>
                        </div>
                    </div>
                    @if($currentPaymentStatus == 'unpaid')
                        <button id="pay-button" class="btn btn-primary px-10 shadow-lg shadow-brand/20">
                            Bayar Sekarang
                        </button>
                    @elseif($currentPaymentStatus == 'pending')
                        <span class="badge badge-amber px-6 py-2">Sedang Diverifikasi</span>
                    @else
                        <div class="flex items-center gap-2 text-emerald-500 font-bold text-sm">
                            <i class="fas fa-check-circle"></i>
                            LUNAS
                        </div>
                    @endif
                </div>
                
                @if($currentPaymentStatus == 'unpaid' && $currentPayment && $currentPayment->rejection_reason)
                    <div class="mt-6 p-4 bg-rose-50 border border-rose-100 rounded-xl flex items-start gap-3">
                        <i class="fas fa-exclamation-triangle text-rose-500 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-bold text-rose-800 uppercase tracking-widest">Pembayaran Ditolak</p>
                            <p class="text-xs text-rose-600 mt-1 italic">"{{ $currentPayment->rejection_reason }}"</p>
                        </div>
                    </div>
                @endif

                <div class="absolute -right-8 -top-8 w-32 h-32 bg-slate-50 rounded-full blur-3xl opacity-50"></div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- HISTORY TABLE --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="flex items-center justify-between px-2">
                    <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Riwayat Transaksi</h3>
                    <div class="text-[11px] font-medium text-slate-400">{{ count($myPayments) }} Rekaman ditemukan</div>
                </div>

                <div class="table-wrap">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Periode</th>
                                <th>Waktu Bayar</th>
                                <th class="text-right">Nominal</th>
                                <th class="text-center">Status</th>
                                <th class="text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($myPayments as $payment)
                                <tr>
                                    <td data-label="Periode"><span class="font-bold text-slate-900 uppercase text-xs">{{ $payment->month }}</span></td>
                                    <td data-label="Waktu Bayar"><span class="text-slate-500 text-xs">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</span></td>
                                    <td class="text-right" data-label="Nominal"><span class="font-bold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span></td>
                                    <td class="text-center" data-label="Status">
                                        @php 
                                            $badge = [
                                                'paid' => 'badge-green', 
                                                'pending' => 'badge-amber', 
                                                'unpaid' => 'badge-red'
                                            ][$payment->status] ?? 'badge-gray'; 
                                        @endphp
                                        <span class="badge {{ $badge }}">{{ strtoupper($payment->status) }}</span>
                                    </td>
                                    <td class="text-right" data-label="Aksi">
                                        <a href="{{ route('rent-payments.show', $payment->id) }}" class="btn btn-outline !h-9 !px-4 !text-[11px] hover:!bg-brand hover:!text-white hover:!border-brand transition-all">
                                            Invoice
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="empty-state">
                                            <div class="empty-state-icon"><i class="fas fa-receipt"></i></div>
                                            <p class="text-slate-500">Belum ada riwayat pembayaran yang tercatat.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- SIDEBAR INFO --}}
            <div class="space-y-8">
                {{-- UNIT INFO --}}
                @if($activeRental)
                    <div class="container-card">
                        <h4 class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-6">Informasi Unit</h4>
                        <div class="flex items-center gap-4 mb-6">
                            <div class="w-12 h-12 bg-slate-900 text-white rounded-xl flex items-center justify-center font-bold text-lg">
                                {{ $activeRental->room->room_number }}
                            </div>
                            <div>
                                <p class="text-sm font-bold text-slate-900">{{ $activeRental->room->room_type }}</p>
                                <p class="text-[11px] text-slate-400 font-medium">Aktif sejak {{ \Carbon\Carbon::parse($activeRental->start_date)->format('M Y') }}</p>
                            </div>
                        </div>
                        <div class="space-y-4 pt-4 border-t border-slate-50">
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-400">Harga Sewa</span>
                                <span class="font-bold text-slate-900">Rp {{ number_format($activeRental->room->price, 0, ',', '.') }}</span>
                            </div>
                            <div class="flex justify-between items-center text-xs">
                                <span class="text-slate-400">Jatuh Tempo</span>
                                <span class="font-bold text-brand">Tgl 10 / Bulan</span>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- PAYMENT GUIDE --}}
                <div class="container-card bg-slate-50 border-none">
                    <h4 class="text-xs font-bold text-slate-900 mb-4 flex items-center gap-2">
                        <i class="fas fa-info-circle text-brand"></i>
                        Panduan Billing
                    </h4>
                    <ul class="space-y-4">
                        <li class="flex gap-3 items-start">
                            <div class="w-1.5 h-1.5 bg-brand rounded-full mt-1.5 flex-shrink-0"></div>
                            <p class="text-xs text-slate-500 leading-relaxed">Gunakan metode pembayaran yang tersedia di sistem untuk verifikasi otomatis.</p>
                        </li>
                        <li class="flex gap-3 items-start">
                            <div class="w-1.5 h-1.5 bg-brand rounded-full mt-1.5 flex-shrink-0"></div>
                            <p class="text-xs text-slate-500 leading-relaxed">Simpan bukti transfer jika Anda menggunakan metode manual.</p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    @if($activeRental && $currentPaymentStatus == 'unpaid')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        if (payButton) {
            payButton.addEventListener('click', function () {
                payButton.disabled = true;
                payButton.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mohon Tunggu...';

                fetch('{{ route('rent-payments.midtrans-token') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        rental_id: '{{ $activeRental->id }}'
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.snap_token) {
                        window.snap.pay(data.snap_token, {
                            onSuccess: function(result) { window.location.reload(); },
                            onPending: function(result) { window.location.reload(); },
                            onError: function(result) { alert("Pembayaran gagal!"); payButton.disabled = false; payButton.innerHTML = 'Bayar Sekarang'; },
                            onClose: function() { payButton.disabled = false; payButton.innerHTML = 'Bayar Sekarang'; }
                        });
                    } else {
                        alert('Error: ' + (data.error || 'Gagal generate token'));
                        payButton.disabled = false;
                        payButton.innerHTML = 'Bayar Sekarang';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Kesalahan koneksi. Silakan coba lagi.');
                    payButton.disabled = false;
                    payButton.innerHTML = 'Bayar Sekarang';
                });
            });
        }
    </script>
    @endif
</x-app-layout>

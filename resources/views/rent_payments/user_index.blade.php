<x-app-layout>
    @section('header_title', 'Billing & Payments')

    <div class="space-y-8 md:space-y-12">
        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 md:gap-12">
            <div class="lg:col-span-2 space-y-8 md:space-y-12">
                
                <!-- Active Billing Card -->
                @if($activeRental)
                    <div class="relative overflow-hidden">
                        @if($currentPaymentStatus !== 'paid')
                            <div class="group relative bg-white border border-slate-200 rounded-3xl p-6 md:p-10 shadow-sm overflow-hidden transition-all hover:shadow-md">
                                <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 md:gap-10">
                                    <div class="flex items-center space-x-5 md:space-x-8">
                                        <div class="w-16 h-16 md:w-20 md:h-20 {{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-50 text-amber-500' : 'bg-blue-50 text-blue-500' }} rounded-2xl flex items-center justify-center flex-shrink-0 transition-transform group-hover:scale-110 duration-500">
                                            @if($currentPaymentStatus == 'unpaid')
                                                <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            @else
                                                <svg class="w-8 h-8 md:w-10 md:h-10 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <h4 class="text-xl md:text-2xl font-extrabold text-slate-800 tracking-tight leading-none">
                                                {{ $currentPaymentStatus == 'unpaid' ? 'Tagihan Menanti' : 'Verifikasi Berlangsung' }}
                                            </h4>
                                            <p class="text-sm font-medium text-slate-500 mt-2 max-w-xs leading-relaxed">
                                                {{ $currentPaymentStatus == 'unpaid' ? "Selesaikan pembayaran unit Anda untuk periode " . date('M Y') : "Bukti pembayaran sedang dalam antrean verifikasi Admin KosKora." }}
                                            </p>
                                        </div>
                                    </div>
    
                                    @if($currentPaymentStatus == 'unpaid')
                                        <div class="flex flex-col md:flex-row gap-3 w-full md:w-auto">
                                            <button id="pay-button" class="px-8 py-3.5 bg-brand-navy text-white rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-brand-red transition-all hover:-translate-y-1 active:scale-95 text-center btn-touch">
                                                Bayar Online
                                            </button>
                                            <a href="{{ route('rent-payments.user-create', ['rental_id' => $activeRental->id]) }}" class="px-8 py-3.5 bg-slate-100 text-slate-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-slate-200 transition-all text-center btn-touch">
                                                Upload Bukti
                                            </a>
                                        </div>
                                    @endif
                                </div>
    
                                <!-- Decorative -->
                                <div class="absolute -right-10 -bottom-10 w-40 h-40 {{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-50' : 'bg-blue-50' }} rounded-full opacity-30"></div>
                            </div>

                            <!-- Rejection Reason Notification -->
                            @if($currentPaymentStatus == 'unpaid' && $currentPayment && $currentPayment->rejection_reason)
                                <div class="mt-4 p-5 bg-rose-50 border border-rose-100 rounded-3xl flex items-start space-x-4 animate-fade-in shadow-lg shadow-rose-100/50">
                                    <div class="w-10 h-10 bg-rose-100 text-rose-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                    </div>
                                    <div class="min-w-0">
                                        <h5 class="text-xs font-black text-rose-800 uppercase tracking-widest leading-none mb-1">Pembayaran Sebelumnya Ditolak</h5>
                                        <p class="text-sm font-bold text-rose-600 italic">"{{ $currentPayment->rejection_reason }}"</p>
                                        <p class="text-[10px] text-rose-400 font-medium mt-1">Silakan upload ulang bukti bayar yang benar dan valid.</p>
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="bg-emerald-600 rounded-3xl p-6 md:p-10 text-white shadow-lg flex flex-col md:flex-row items-center justify-between gap-8 overflow-hidden relative group">
                                <div class="relative z-10 flex items-center space-x-6 md:space-x-8">
                                    <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center flex-shrink-0 shadow-xl group-hover:rotate-12 transition-transform duration-500">
                                        <svg class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-xl md:text-2xl font-extrabold tracking-tight">Lunas & Beres!</h4>
                                        <p class="text-emerald-50 text-xs md:text-sm font-medium opacity-90 leading-relaxed mt-1">Terima kasih telah membayar tepat waktu.<br>Kamu penyewa teladan!</p>
                                    </div>
                                </div>
                                <a href="{{ route('rent-payments.ticket', $currentPayment->id) }}" target="_blank" class="relative z-10 px-8 py-3.5 bg-white text-emerald-600 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-emerald-50 shadow-sm transition-all hover:-translate-y-1 active:scale-95 text-center btn-touch">
                                    Akses Tiket Masuk
                                </a>
                                <!-- Decorative -->
                                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white opacity-5 rounded-full"></div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Payment History Area -->
                <div class="space-y-6 md:space-y-8">
                    <div class="flex items-center justify-between mb-2">
                        <div class="flex items-center space-x-4">
                            <div class="w-1.5 h-6 bg-brand-blue rounded-full"></div>
                            <h3 class="text-lg md:text-xl font-extrabold text-slate-800 tracking-tight">Riwayat Transaksi</h3>
                        </div>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ count($myPayments) }} Records</span>
                    </div>
                    
                    <!-- Desktop Layout -->
                    <div class="hidden md:block bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50/50 border-b border-slate-100">
                                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Periode</th>
                                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Waktu Bayar</th>
                                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Nominal</th>
                                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                    <th class="px-6 py-4 text-[9px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($myPayments as $payment)
                                    <tr class="hover:bg-slate-50/50 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-extrabold text-slate-800 leading-none uppercase">{{ $payment->month }}</div>
                                            <div class="text-[9px] font-bold text-slate-400 mt-1 uppercase overflow-hidden truncate max-w-[120px]">
                                                @if($payment->rejection_reason && $payment->status == 'unpaid')
                                                    <span class="text-rose-500 italic">Ditolak: {{ Str::limit($payment->rejection_reason, 20) }}</span>
                                                @else
                                                    Paid via {{ $payment->method }}
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="text-xs font-bold text-slate-600 leading-none">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-right font-extrabold text-slate-800">
                                            Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @php 
                                                $statusClasses = [
                                                    'paid' => 'bg-emerald-50 text-emerald-600 border-emerald-100', 
                                                    'pending' => 'bg-amber-50 text-amber-600 border-amber-100',
                                                    'unpaid' => 'bg-rose-50 text-rose-600 border-rose-100'
                                                ]; 
                                            @endphp
                                            <span class="px-2.5 py-1 text-[8px] font-black rounded-full uppercase tracking-widest border {{ $statusClasses[$payment->status] ?? 'bg-slate-50' }}">
                                                {{ $payment->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($payment->status == 'paid')
                                                <a href="{{ route('rent-payments.ticket', $payment->id) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 bg-slate-900 text-white text-[8px] font-black rounded-lg hover:bg-slate-800 transition-all uppercase tracking-widest active:scale-95">
                                                    Tiket
                                                </a>
                                            @elseif($payment->method == 'Midtrans' && $payment->status != 'paid')
                                                <button onclick="checkStatus({{ $payment->id }})" class="inline-flex items-center px-3 py-2 bg-blue-600 text-white text-[8px] font-black rounded-lg hover:bg-blue-700 transition-all uppercase tracking-widest active:scale-95">
                                                    Check
                                                </button>
                                            @else
                                                <span class="text-slate-300 text-[8px] font-black uppercase tracking-widest">Locked</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-20 text-center">
                                            <div class="flex flex-col items-center opacity-20">
                                                <svg class="w-12 h-12 text-slate-900 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                                                <div class="text-[10px] font-black uppercase tracking-widest">No history yet</div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Mobile Layout -->
                    <div class="md:hidden space-y-4 pb-12">
                        @forelse($myPayments as $payment)
                            <div class="bg-white rounded-3xl p-6 shadow-xl shadow-slate-200/50 border border-slate-50 space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="space-y-1">
                                        <span class="text-sm font-black text-slate-800 uppercase tracking-tight">{{ $payment->month }}</span>
                                        <div class="text-[9px] font-bold text-slate-400">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</div>
                                    </div>
                                    <span class="px-2.5 py-0.5 text-[8px] font-black rounded-full uppercase tracking-widest border {{ $statusClasses[$payment->status] ?? 'bg-slate-50' }}">
                                        {{ $payment->status }}
                                    </span>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl flex items-center justify-between">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Bayar</span>
                                    <span class="text-sm font-black text-brand-blue italic tracking-tighter">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                </div>
                                @if($payment->rejection_reason && $payment->status == 'unpaid')
                                    <div class="p-3 bg-rose-50 rounded-xl text-[10px] font-bold text-rose-600 italic border border-rose-100">
                                        "{{ $payment->rejection_reason }}"
                                    </div>
                                @endif
                                @if($payment->status == 'paid')
                                    <a href="{{ route('rent-payments.ticket', $payment->id) }}" target="_blank" class="block w-full py-3 text-center text-[10px] font-black text-white bg-slate-900 rounded-2xl shadow-xl shadow-slate-200 transition-all uppercase tracking-widest btn-touch active:scale-95">
                                        Lihat Tiket Masuk
                                    </a>
                                @elseif($payment->method == 'Midtrans' && $payment->status != 'paid')
                                    <button onclick="checkStatus({{ $payment->id }})" class="block w-full py-3 text-center text-[10px] font-black text-white bg-blue-600 rounded-2xl shadow-xl shadow-blue-200 transition-all uppercase tracking-widest btn-touch active:scale-95">
                                        Cek Status Pembayaran
                                    </button>
                                @else
                                    <div class="text-center py-2 text-[9px] font-black text-slate-300 uppercase tracking-widest italic tracking-tighter">Dalam Proses Verifikasi</div>
                                @endif
                            </div>
                        @empty
                            <div class="text-center py-12 opacity-30">
                                <svg class="w-10 h-10 mx-auto text-slate-900 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                                <div class="text-[10px] font-black uppercase tracking-widest">Nothing here</div>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Side Information Column -->
            <div class="space-y-8 md:space-y-12">
                <!-- Info Section -->
                <div class="bg-slate-900 rounded-3xl p-8 text-white shadow-lg relative overflow-hidden group">
                    <div class="relative z-10 space-y-6">
                        <div class="w-12 h-12 bg-white/10 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 transition-all duration-700">
                            <svg class="w-6 h-6 text-blue-400 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h4 class="text-lg font-extrabold tracking-tight">Pusat Pembayaran</h4>
                        <ul class="space-y-4">
                            <li class="flex items-start space-x-3">
                                <span class="w-1.5 h-1.5 bg-blue-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                <p class="text-[11px] font-medium text-slate-400 leading-relaxed uppercase tracking-widest">Bayar sebelum tanggal 10 setiap bulannya untuk menghindari denda.</p>
                            </li>
                            <li class="flex items-start space-x-3">
                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                <p class="text-[11px] font-medium text-slate-400 leading-relaxed uppercase tracking-widest">Simpan bukti transfer m-banking atau struk pembayaran Anda.</p>
                            </li>
                            <li class="flex items-start space-x-3">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full mt-1.5 flex-shrink-0"></span>
                                <p class="text-[11px] font-medium text-slate-400 leading-relaxed uppercase tracking-widest">Tiket masuk akan aktif otomatis setelah verifikasi Admin selesai.</p>
                            </li>
                        </ul>
                    </div>
                    <!-- Glow -->
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-600 rounded-full opacity-10 blur-3xl"></div>
                </div>

                <!-- Active Room Summary -->
                @if($activeRental)
                    <div class="bg-white rounded-3xl border border-slate-200 p-6 md:p-8 shadow-sm">
                        <h4 class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-6">Informasi Unit</h4>
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-slate-900 text-white rounded-xl flex items-center justify-center font-black text-lg shadow-md">
                                {{ $activeRental->room->room_number }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-sm font-extrabold text-slate-800 truncate uppercase tracking-tight">{{ $activeRental->room->room_type }}</div>
                                <div class="text-[10px] font-bold text-brand-blue uppercase tracking-widest">Rp {{ number_format($activeRental->room->price, 0, ',', '.') }}/bln</div>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Sewa Sejak</span>
                                <span class="text-[10px] font-bold text-slate-700 uppercase">{{ \Carbon\Carbon::parse($activeRental->start_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</span>
                                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-600 text-[8px] font-black rounded uppercase tracking-widest border border-emerald-100">Aktif</span>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    @if($activeRental && $currentPaymentStatus == 'unpaid')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
    <script type="text/javascript">
        const payButton = document.getElementById('pay-button');
        if (payButton) {
            payButton.addEventListener('click', function () {
                payButton.disabled = true;
                payButton.innerHTML = 'Generating Token...';

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
                            onSuccess: function(result) {
                                window.location.reload();
                            },
                            onPending: function(result) {
                                window.location.reload();
                            },
                            onError: function(result) {
                                alert("Payment failed!");
                                payButton.disabled = false;
                                payButton.innerHTML = 'Bayar Online (QRIS/VA)';
                            },
                            onClose: function() {
                                payButton.disabled = false;
                                payButton.innerHTML = 'Bayar Online (QRIS/VA)';
                            }
                        });
                    } else {
                        alert('Error: ' + (data.error || 'Failed to generate payment token'));
                        payButton.disabled = false;
                        payButton.innerHTML = 'Bayar Online (QRIS/VA)';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Connection error. Please try again.');
                    payButton.disabled = false;
                    payButton.innerHTML = 'Bayar Online (QRIS/VA)';
                });
            });
        }

        function checkStatus(paymentId) {
            const btn = event.currentTarget;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = 'Checking...';

            fetch(`/my-payments/${paymentId}/check-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                if (data.status === 'paid' || data.status === 'unpaid') {
                    window.location.reload();
                } else {
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal mengecek status.');
                btn.disabled = false;
                btn.innerHTML = originalText;
            });
        }
    </script>
    @endif
</x-app-layout>

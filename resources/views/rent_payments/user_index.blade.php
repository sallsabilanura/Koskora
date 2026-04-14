<x-app-layout>
    @section('header_title', 'Pembayaran Sewa')

    <div class="space-y-8">
        <!-- Billing Alert Section (Copied and refined for this page) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <div class="lg:col-span-2 space-y-8">
                @if($activeRental && $currentPaymentStatus !== 'paid')
                    <div class="{{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-50 border-amber-200 shadow-amber-100' : 'bg-blue-50 border-blue-200 shadow-blue-100' }} border-2 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg animate-pulse-subtle">
                        <div class="flex items-center space-x-6">
                            <div class="{{ $currentPaymentStatus == 'unpaid' ? 'bg-amber-100 text-amber-600' : 'bg-blue-100 text-blue-600' }} p-4 rounded-2xl">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-black {{ $currentPaymentStatus == 'unpaid' ? 'text-amber-800' : 'text-blue-800' }} tracking-tight">
                                    {{ $currentPaymentStatus == 'unpaid' ? 'Tagihan Bulan Ini' : 'Menunggu Verifikasi' }}
                                </h4>
                                <p class="{{ $currentPaymentStatus == 'unpaid' ? 'text-amber-600' : 'text-blue-600' }} font-medium">
                                    {{ $currentPaymentStatus == 'unpaid' ? "Pembayaran untuk " . date('F Y') . " belum tercatat." : "Bukti bayar " . date('F Y') . " sudah terkirim. Mohon tunggu Admin." }}
                                </p>
                            </div>
                        </div>
                        @if($currentPaymentStatus == 'unpaid')
                            <a href="{{ route('rent-payments.user-create', ['rental_id' => $activeRental->id]) }}" class="px-8 py-3 bg-amber-600 text-white rounded-xl font-black text-sm hover:bg-amber-700 shadow-lg shadow-amber-200 transition-all transform hover:scale-105">
                                Bayar Sekarang
                            </a>
                        @else
                            <div class="px-6 py-2 bg-blue-100 text-blue-700 rounded-xl font-black text-xs uppercase tracking-widest italic">
                                Pending
                            </div>
                        @endif
                    </div>
                @elseif($activeRental && $currentPaymentStatus == 'paid')
                    <div class="bg-emerald-50 border-2 border-emerald-200 rounded-3xl p-8 flex flex-col md:flex-row items-center justify-between gap-6 shadow-lg shadow-emerald-100">
                        <div class="flex items-center space-x-6">
                            <div class="bg-emerald-100 text-emerald-600 p-4 rounded-2xl">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <h4 class="text-xl font-black text-emerald-800 tracking-tight">Pembayaran Lunas</h4>
                                <p class="text-emerald-600 font-medium">Tagihan untuk <strong>{{ date('F Y') }}</strong> sudah terbayar. Terima kasih!</p>
                            </div>
                        </div>
                        <a href="{{ route('rent-payments.ticket', $currentPayment->id) }}" target="_blank" class="px-8 py-3 bg-emerald-600 text-white rounded-xl font-black text-sm hover:bg-emerald-700 shadow-lg shadow-emerald-200 transition-all transform hover:scale-105">
                            Lihat Tiket Masuk
                        </a>
                    </div>
                @endif

                <div class="space-y-6">
                    <div class="flex items-center space-x-3">
                        <div class="w-1.5 h-6 bg-blue-600 rounded-full"></div>
                        <h3 class="text-xl font-bold text-slate-800 tracking-tight">Riwayat Pembayaran Saya</h3>
                    </div>
                    
                    <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
                        <table class="w-full text-left">
                            <thead>
                                <tr class="bg-slate-50 border-b border-slate-100">
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Bulan</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tgl Bayar</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nominal</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                                    <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($myPayments as $payment)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="px-8 py-6">
                                            <span class="text-sm font-black text-slate-800">{{ $payment->month }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="text-xs font-bold text-slate-500">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            <span class="text-sm font-black text-slate-800">Rp {{ number_format($payment->amount, 0, ',', '.') }}</span>
                                        </td>
                                        <td class="px-8 py-6">
                                            @php
                                                $statusClasses = [
                                                    'paid' => 'bg-emerald-100 text-emerald-700',
                                                    'pending' => 'bg-amber-100 text-amber-700',
                                                ];
                                            @endphp
                                            <span class="px-3 py-1 text-[10px] font-black rounded-full uppercase tracking-tighter {{ $statusClasses[$payment->status] ?? 'bg-slate-100 text-slate-600' }}">
                                                {{ $payment->status }}
                                            </span>
                                        </td>
                                        <td class="px-8 py-6 text-right">
                                            @if($payment->status == 'paid')
                                                <a href="{{ route('rent-payments.ticket', $payment->id) }}" target="_blank" class="text-blue-600 hover:text-blue-800 font-black text-[10px] uppercase tracking-widest border-b-2 border-blue-100 hover:border-blue-600 transition-all">
                                                    Lihat Tiket
                                                </a>
                                            @else
                                                <span class="text-slate-400 text-[10px] font-bold italic">Menunggu Verifikasi</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-8 py-20 text-center">
                                            <div class="text-slate-300 mb-2">
                                                <svg class="w-12 h-12 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div class="text-sm font-bold text-slate-400">Belum ada data pembayaran.</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Sidebar Info (Rules or Info) -->
            <div class="space-y-8">
                <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white shadow-2xl relative overflow-hidden">
                    <div class="relative z-10 space-y-4">
                        <h4 class="text-lg font-black tracking-tight italic">Informasi Pembayaran</h4>
                        <ul class="text-sm space-y-3 text-slate-300 font-medium">
                            <li class="flex items-start">
                                <span class="text-blue-400 mr-2">•</span>
                                Pembayaran dilakukan setiap bulan paling lambat tanggal 10.
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-400 mr-2">•</span>
                                Harap upload bukti transfer yang jelas agar admin bisa segera memverifikasi.
                            </li>
                            <li class="flex items-start">
                                <span class="text-blue-400 mr-2">•</span>
                                Tiket masuk otomatis akan muncul di daftar riwayat setelah diverifikasi.
                            </li>
                        </ul>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-blue-500 rounded-full opacity-10"></div>
                </div>

                @if($activeRental)
                    <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 shadow-sm">
                        <h4 class="text-sm font-black text-slate-400 uppercase tracking-widest mb-6">Unit Aktif Saya</h4>
                        <div class="flex items-center space-x-4 mb-6">
                            <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-2xl flex items-center justify-center font-black">
                                {{ $activeRental->room->room_number }}
                            </div>
                            <div>
                                <div class="text-sm font-black text-slate-800">{{ $activeRental->room->room_type }}</div>
                                <div class="text-xs font-bold text-slate-400 italic">Rp {{ number_format($activeRental->room->price, 0, ',', '.') }}/bln</div>
                            </div>
                        </div>
                        <div class="p-4 bg-slate-50 rounded-2xl">
                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Mulai Sewa</div>
                            <div class="text-xs font-black text-slate-700">{{ \Carbon\Carbon::parse($activeRental->start_date)->format('d F Y') }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        @keyframes pulse-subtle {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.95; transform: scale(1.005); }
        }
        .animate-pulse-subtle {
            animation: pulse-subtle 4s infinite ease-in-out;
        }
    </style>
</x-app-layout>

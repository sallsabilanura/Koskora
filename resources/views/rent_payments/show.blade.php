<x-app-layout>
    @section('header_title', 'Payment Receipt')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800">Detail Pembayaran Sewa</h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('rent-payments.edit', $rentPayment->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-xl font-semibold text-xs text-blue-600 uppercase tracking-widest hover:bg-blue-100 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                <a href="{{ route('rent-payments.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-xl font-semibold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-200 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden relative">
            <!-- Receipt Header Decoration -->
            <div class="h-3 bg-blue-600 w-full"></div>
            
            <div class="p-10 space-y-10">
                <!-- Top Section: Status and Amount -->
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Status Pembayaran</div>
                        <span class="px-5 py-2 text-sm font-black rounded-full {{ $rentPayment->status == 'paid' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ strtoupper($rentPayment->status) }}
                        </span>
                    </div>
                    <div class="md:text-right">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-widest mb-1">Total Dibayar</div>
                        <div class="text-4xl font-black text-slate-900 tracking-tight italic">Rp {{ number_format($rentPayment->amount, 0, ',', '.') }}</div>
                    </div>
                </div>

                <!-- Middle Section: Details Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-y-8 gap-x-12 pt-10 border-t border-dashed border-slate-200">
                    <!-- Tenant -->
                    <div class="space-y-1">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">Penyewa</div>
                        <div class="text-lg font-bold text-slate-800">{{ $rentPayment->tenants->user->name ?? 'Unknown' }}</div>
                        <div class="text-sm text-slate-500 font-mono italic">{{ $rentPayment->tenants->nik }}</div>
                    </div>

                    <!-- Room -->
                    <div class="space-y-1">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">Unit Kamar</div>
                        <div class="text-lg font-bold text-blue-600">Kamar {{ $rentPayment->room->room_number }}</div>
                        <div class="text-sm text-slate-500 italic">{{ $rentPayment->room->room_type }}</div>
                    </div>

                    <!-- Period -->
                    <div class="space-y-1">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">Periode / Bulan</div>
                        <div class="text-lg font-bold text-slate-800">{{ $rentPayment->month }}</div>
                    </div>

                    <!-- Payment Date -->
                    <div class="space-y-1">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">Tanggal Transaksi</div>
                        <div class="text-lg font-bold text-slate-800">{{ \Carbon\Carbon::parse($rentPayment->payment_date)->format('d F Y') }}</div>
                    </div>

                    <!-- Method -->
                    <div class="space-y-1">
                        <div class="text-slate-400 text-xs font-bold uppercase tracking-widest">Metode Pembayaran</div>
                        <div class="text-lg font-bold text-slate-800">{{ $rentPayment->method ?: 'N/A' }}</div>
                    </div>
                </div>

                <!-- Footer Section -->
                <div class="pt-10 border-t border-slate-100 flex flex-col items-center justify-center space-y-4">
                    <div class="text-slate-300 text-xs italic">Bukti pembayaran digital KosKora</div>
                    <button onclick="window.print()" class="inline-flex items-center text-blue-600 hover:text-blue-800 font-bold text-sm transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2 2h2m2 4h10a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2-2v4a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                        Cetak Kuitansi
                    </button>
                </div>
            </div>
            
            <!-- Decorative Punch Holes -->
            <div class="absolute top-1/2 -left-3 w-6 h-6 bg-slate-100 rounded-full border border-slate-200"></div>
            <div class="absolute top-1/2 -right-3 w-6 h-6 bg-slate-100 rounded-full border border-slate-200"></div>
        </div>
    </div>
</x-app-layout>

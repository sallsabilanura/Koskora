<x-app-layout>
    @section('header_title', 'Rental Details')

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800">Detail Sewa Kamar #{{ $rental->id }}</h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('rentals.edit', $rental->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-xl font-semibold text-xs text-blue-600 uppercase tracking-widest hover:bg-blue-100 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                <a href="{{ route('rentals.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-xl font-semibold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-200 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Rental Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Summary Card -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800">Ringkasan Penyewaan</h3>
                        <span class="px-4 py-1.5 text-sm font-bold rounded-full {{ $rental->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">
                            {{ ucfirst($rental->status) }}
                        </span>
                    </div>
                    <div class="p-8 space-y-8">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <!-- Tenant Info -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    <span class="text-xs font-bold uppercase tracking-wider">Informasi Penyewa</span>
                                </div>
                                <div class="bg-slate-50 rounded-xl p-5 border border-slate-100">
                                    <div class="font-bold text-slate-800 text-lg mb-1">{{ $rental->tenant->user->name ?? 'Unknown' }}</div>
                                    <div class="text-sm text-slate-500 mb-3">NIK: {{ $rental->tenant->nik ?? '-' }}</div>
                                    <div class="text-sm text-slate-600 leading-relaxed italic border-t border-slate-200 pt-3">
                                        {{ $rental->tenant->address ?? '-' }}
                                    </div>
                                </div>
                            </div>

                            <!-- Room Info -->
                            <div class="space-y-4">
                                <div class="flex items-center space-x-3 text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                    <span class="text-xs font-bold uppercase tracking-wider">Informasi Kamar</span>
                                </div>
                                <div class="bg-blue-50 rounded-xl p-5 border border-blue-100">
                                    <div class="font-bold text-blue-900 text-lg mb-1">Kamar {{ $rental->room->room_number ?? '-' }}</div>
                                    <div class="text-sm text-blue-700 font-medium mb-3">Tipe: {{ $rental->room->room_type ?? '-' }}</div>
                                    <div class="text-sm text-blue-600 bg-white/50 rounded-lg p-2 border border-blue-100 inline-block font-bold">
                                        Rp {{ number_format($rental->room->price ?? 0, 0, ',', '.') }} / bln
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Date Info -->
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-slate-100">
                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Mulai Sewa</div>
                                <div class="text-slate-800 font-semibold italic">{{ \Carbon\Carbon::parse($rental->start_date)->format('d F Y') }}</div>
                            </div>
                            <div>
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Selesai Sewa</div>
                                <div class="text-slate-800 font-semibold italic">{{ \Carbon\Carbon::parse($rental->end_date)->format('d F Y') }}</div>
                            </div>
                            <div class="md:text-right">
                                <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Total Pembayaran</div>
                                <div class="text-2xl font-black text-blue-600 tracking-tight">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action/Next Card -->
            <div class="space-y-6">
                <div class="bg-emerald-600 rounded-2xl p-8 text-white shadow-xl shadow-emerald-100 relative overflow-hidden">
                    <svg class="absolute -right-4 -bottom-4 w-32 h-32 text-emerald-500 opacity-30" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <h3 class="text-xl font-bold mb-4 italic">Status Aktif</h3>
                    <p class="text-emerald-100 text-sm mb-8 leading-relaxed">Penyewaan ini sedang berjalan. Pastikan tagihan bulanan dikelola dengan tepat waktu.</p>
                    
                    <a href="{{ route('rent-payments.index', ['rental_id' => $rental->id]) }}" class="inline-flex items-center w-full justify-center px-6 py-3 bg-white text-emerald-700 rounded-xl font-bold text-sm hover:bg-emerald-50 transition-colors">
                        Lihat Riwayat Bayar
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

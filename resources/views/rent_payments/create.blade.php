<x-app-layout>
    @section('header_title', 'New Payment')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800">Catat Pembayaran Baru</h2>
            <a href="{{ route('rent-payments.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-xl font-semibold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-200 transition ease-in-out duration-150">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-xl shadow-sm">
                <div class="font-bold mb-2 flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Maaf! Ada masalah dengan input Anda.
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8">
            <form action="{{ route('rent-payments.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Rental Selection -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Sewa Aktif</label>
                        <select name="rental_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <option value="">-- Pilih Sewa --</option>
                            @foreach ($rentals as $rental)
                                <option value="{{ $rental->id }}" {{ old('rental_id') == $rental->id ? 'selected' : '' }}>
                                    {{ $rental->tenant->user->name ?? $rental->tenant->nik }} - Kamar {{ $rental->room->room_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Bulan Pembayaran</label>
                        <input type="text" name="month" value="{{ old('month') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Contoh: Januari 2024">
                    </div>

                    <!-- Amount -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Jumlah Bayar (Rp)</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="0">
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    </div>

                    <!-- Method -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Metode Pembayaran</label>
                        <input type="text" name="method" value="{{ old('method') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Contoh: Transfer, Cash">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-base text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150 shadow-lg shadow-blue-200">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

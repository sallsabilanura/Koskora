<x-app-layout>
    @section('header_title', 'New Payment')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Catat Pembayaran Baru</h2>
                <p class="text-slate-500 text-[13px] mt-0.5">Dokumentasikan transaksi sewa secara manual untuk pencatatan keuangan.</p>
            </div>
            <a href="{{ route('rent-payments.index') }}" class="btn btn-ghost">
                <i class="fas fa-arrow-left text-[10px]"></i>
                Kembali
            </a>
        </div>

        @if ($errors->any())
            <div class="p-4 bg-rose-50 border border-rose-100 text-rose-700 rounded-2xl flex items-start gap-3 shadow-sm">
                <i class="fas fa-exclamation-circle mt-1"></i>
                <ul class="list-disc list-inside text-[13px] font-bold">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="stat-card">
            <form action="{{ route('rent-payments.store') }}" method="POST" class="space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Rental Selection -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilih Sewa Aktif</label>
                        <select name="rental_id" required>
                            <option value="">-- Pilih Sewa --</option>
                            @foreach ($rentals as $rental)
                                <option value="{{ $rental->id }}" {{ old('rental_id') == $rental->id ? 'selected' : '' }}>
                                    {{ $rental->tenant->user->name ?? $rental->tenant->nik }} - Kamar {{ $rental->room->room_number }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Month -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Bulan Pembayaran</label>
                        <input type="text" name="month" value="{{ old('month') }}" placeholder="Contoh: Januari 2024">
                    </div>

                    <!-- Amount -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Jumlah Bayar (Rp)</label>
                        <input type="number" step="0.01" name="amount" value="{{ old('amount') }}" placeholder="0">
                    </div>

                    <!-- Date -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tanggal Pembayaran</label>
                        <input type="date" name="payment_date" value="{{ old('payment_date', now()->format('Y-m-d')) }}">
                    </div>

                    <!-- Method -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Metode Pembayaran</label>
                        <input type="text" name="method" value="{{ old('method') }}" placeholder="Contoh: Transfer, Cash">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Status</label>
                        <select name="status">
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-4 border-t border-slate-50">
                    <button type="submit" class="btn btn-primary px-10 shadow-lg shadow-brand/20">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

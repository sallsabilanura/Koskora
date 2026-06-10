<x-app-layout>
    @section('header_title', 'New Rental Data')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Tambah Data Sewa Baru</h2>
                <p class="text-slate-500 text-[13px] mt-0.5">Atur penempatan penyewa ke unit kamar yang tersedia.</p>
            </div>
            <a href="{{ route('rentals.index') }}" class="btn btn-ghost">
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
            <form action="{{ route('rentals.store') }}" method="POST" class="space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Tenant Selection -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilih Penyewa</label>
                        <select name="tenant_id" required>
                            <option value="">-- Pilih Penyewa --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ old('tenant_id') == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->user->name ?? 'User '.$tenant->nik }} (NIK: {{ $tenant->nik }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Room Selection -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilih Kamar</label>
                        <select name="room_id" id="room_id" required onchange="calculateRental()">
                            <option value="" data-price="0">-- Pilih Kamar Tersedia --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-price="{{ $room->price }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->room_number }} - {{ $room->room_type }} (Rp {{ number_format($room->price, 0, ',', '.') }}/bln)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" required onchange="calculateRental()">
                    </div>

                    <!-- Duration -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Durasi Sewa (Bulan)</label>
                        <input type="number" name="duration_months" id="duration_months" value="{{ old('duration_months') }}" min="1" required oninput="calculateRental()">
                    </div>

                    <!-- End Date (Read-only) -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Tanggal Selesai (Otomatis)</label>
                        <input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" readonly class="!bg-slate-100 !cursor-not-allowed">
                    </div>

                    <!-- Total Price (Read-only) -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Total Harga</label>
                        <input type="text" id="total_price_display" readonly class="!bg-slate-100 !cursor-not-allowed !font-bold !text-brand">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Status Sewa</label>
                        <select name="status">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="finished" {{ old('status') == 'finished' ? 'selected' : '' }}>Finished</option>
                        </select>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-4 border-t border-slate-50">
                    <button type="submit" class="btn btn-primary px-10 shadow-lg shadow-brand/20">
                        Simpan Data Sewa
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function calculateRental() {
            const roomSelect = document.getElementById('room_id');
            const startDateInput = document.getElementById('start_date');
            const durationInput = document.getElementById('duration_months');
            const endDateInput = document.getElementById('end_date');
            const totalPriceDisplay = document.getElementById('total_price_display');

            if (roomSelect.value && startDateInput.value && durationInput.value) {
                const price = parseFloat(roomSelect.options[roomSelect.selectedIndex].getAttribute('data-price'));
                const duration = parseInt(durationInput.value);

                const total = price * duration;
                totalPriceDisplay.value = 'Rp ' + total.toLocaleString('id-ID');

                const startDate = new Date(startDateInput.value);
                // Set end date to start date + N months
                startDate.setMonth(startDate.getMonth() + duration);
                
                const yyyy = startDate.getFullYear();
                const mm = String(startDate.getMonth() + 1).padStart(2, '0');
                const dd = String(startDate.getDate()).padStart(2, '0');
                endDateInput.value = `${yyyy}-${mm}-${dd}`;
            } else {
                endDateInput.value = '';
                totalPriceDisplay.value = '';
            }
        }

        // Initialize on load
        document.addEventListener('DOMContentLoaded', calculateRental);
    </script>
</x-app-layout>

<x-app-layout>
    @section('header_title', 'Edit Rental Data')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800">Edit Data Sewa</h2>
            <a href="{{ route('rentals.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-xl font-semibold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-200 transition ease-in-out duration-150">
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
            <form action="{{ route('rentals.update', $rental->id) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Tenant Selection -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Penyewa</label>
                        <select name="tenant_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" required>
                            <option value="">-- Pilih Penyewa --</option>
                            @foreach($tenants as $tenant)
                                <option value="{{ $tenant->id }}" {{ old('tenant_id', $rental->tenant_id) == $tenant->id ? 'selected' : '' }}>
                                    {{ $tenant->user->name ?? 'User '.$tenant->nik }} (NIK: {{ $tenant->nik }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Room Selection -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Kamar</label>
                        <select name="room_id" id="room_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" required>
                            <option value="">-- Pilih Kamar --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id', $rental->room_id) == $room->id ? 'selected' : '' }}>
                                    {{ $room->room_number }} - {{ $room->room_type }} (Rp {{ number_format($room->price, 0, ',', '.') }}/bln)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="start_date" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" value="{{ old('start_date', $rental->start_date) }}" required>
                    </div>

                    <!-- End Date (Editable for Terminations) -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Tanggal Berakhir (Rencana Keluar)</label>
                        <input type="date" name="end_date" id="end_date" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors bg-amber-50" value="{{ old('end_date', $rental->end_date) }}" required>
                        <p class="mt-1 text-[10px] text-amber-600 font-bold uppercase tracking-wider">Sesuaikan tanggal ini jika penghuni akan keluar lebih awal atau lewat masa sewa.</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status Sewa</label>
                        <select name="status" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <option value="active" {{ old('status', $rental->status) == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="finished" {{ old('status', $rental->status) == 'finished' ? 'selected' : '' }}>Finished</option>
                        </select>
                    </div>

                    <!-- Monthly Price -->
                    <div>
                        <label class="block text-sm font-bold text-blue-700 mb-2">Harga Sewa Bulanan (Rp)</label>
                        <input type="number" name="monthly_price" class="w-full rounded-xl border-blue-200 focus:border-blue-500 focus:ring-blue-500 transition-colors font-bold text-blue-700 bg-blue-50" value="{{ old('monthly_price', $rental->monthly_price) }}" required>
                        <p class="mt-1 text-[10px] text-blue-600 font-bold uppercase tracking-wider">Ubah nominal ini untuk menerapkan kenaikan harga sewa bagi penyewa ini.</p>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4 border-t border-slate-100 flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center px-6 py-3 bg-amber-600 border border-transparent rounded-xl font-bold text-base text-white uppercase tracking-widest hover:bg-amber-700 active:bg-amber-900 focus:outline-none focus:border-amber-900 focus:ring ring-amber-300 transition ease-in-out duration-150 shadow-lg shadow-amber-200">
                        Update Data Sewa & Terminasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

<x-app-layout>
    @section('header_title', 'Rental Details')

    <div class="max-w-6xl mx-auto space-y-8 animate-fade-in">
        {{-- Header Actions --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-xl bg-brand/10 text-brand flex items-center justify-center text-xl shadow-sm">
                    <i class="fas fa-file-contract"></i>
                </div>
                <div>
                    <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Detail Sewa #{{ $rental->id }}</h2>
                    <p class="text-slate-500 font-medium text-sm">Dokumentasi lengkap kontrak dan data penyewa.</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('rentals.edit', $rental->id) }}" class="btn btn-ghost !px-4">
                    <i class="fas fa-edit text-xs"></i>
                    Edit Kontrak
                </a>
                <a href="{{ route('rentals.index') }}" class="btn btn-ghost !px-4">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-8 space-y-8">
                
                {{-- SECTION 1: PERSONAL DATA --}}
                <div class="stat-card">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                            <i class="fas fa-user"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800 uppercase tracking-wider text-xs">Informasi Pribadi Penyewa</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-y-6 gap-x-8">
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Lengkap</p>
                            <p class="font-bold text-slate-900">{{ $rental->tenant->nama_lengkap ?? ($rental->tenant->user->name ?? '-') }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nama Panggilan</p>
                            <p class="font-bold text-slate-900">{{ $rental->tenant->nama_panggilan ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nomor NIK (KTP)</p>
                            <p class="font-bold text-brand tracking-wider">{{ $rental->tenant->nik ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Nomor WhatsApp</p>
                            <p class="font-bold text-emerald-600">
                                @if($rental->tenant->nomor_whatsapp)
                                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $rental->tenant->nomor_whatsapp) }}" target="_blank" class="hover:underline">
                                        <i class="fab fa-whatsapp mr-1"></i> {{ $rental->tenant->nomor_whatsapp }}
                                    </a>
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Jenis Kelamin</p>
                            <p class="font-bold text-slate-900">{{ $rental->tenant->jenis_kelamin ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Tempat, Tanggal Lahir</p>
                            <p class="font-bold text-slate-900">
                                {{ $rental->tenant->tempat_lahir ?? '-' }}, 
                                {{ $rental->tenant->tanggal_lahir ? \Carbon\Carbon::parse($rental->tenant->tanggal_lahir)->format('d F Y') : '-' }}
                            </p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pekerjaan</p>
                            <p class="font-bold text-slate-900">{{ $rental->tenant->occupation ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kontak Darurat</p>
                            <p class="font-bold text-rose-600">{{ $rental->tenant->emergency_contact ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 2: ADDRESS DETAILS --}}
                <div class="stat-card">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-slate-100">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800 uppercase tracking-wider text-xs">Detail Alamat & Domisili</h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2 space-y-1 bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Alamat Sesuai KTP</p>
                            <p class="font-medium text-slate-700 leading-relaxed">{{ $rental->tenant->alamat_ktp ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Provinsi</p>
                            <p class="font-bold text-slate-900">{{ $rental->tenant->province ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kota / Kabupaten</p>
                            <p class="font-bold text-slate-900">{{ $rental->tenant->city ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kecamatan</p>
                            <p class="font-bold text-slate-900">{{ $rental->tenant->district ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Kelurahan / Desa</p>
                            <p class="font-bold text-slate-900">{{ $rental->tenant->village ?? '-' }}</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">RT / RW</p>
                            <p class="font-bold text-slate-900">RT {{ $rental->tenant->rt ?? '-' }} / RW {{ $rental->tenant->rw ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- SECTION 3: DOCUMENTS (PHOTOS) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Photo KTP --}}
                    <div class="stat-card">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                                <i class="fas fa-id-card"></i>
                            </div>
                            <h3 class="font-extrabold text-slate-800 uppercase tracking-wider text-xs">Foto KTP</h3>
                        </div>
                        <div class="aspect-[3/2] rounded-xl bg-slate-100 overflow-hidden border border-slate-200 group relative">
                            @if($rental->tenant->foto_ktp)
                                <img src="{{ asset('storage/' . $rental->tenant->foto_ktp) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                <a href="{{ asset('storage/' . $rental->tenant->foto_ktp) }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold text-xs uppercase tracking-widest">
                                    <i class="fas fa-search-plus mr-2"></i> Lihat Fullsize
                                </a>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Tidak ada foto</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Photo Diri --}}
                    <div class="stat-card">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                                <i class="fas fa-camera"></i>
                            </div>
                            <h3 class="font-extrabold text-slate-800 uppercase tracking-wider text-xs">Foto Diri</h3>
                        </div>
                        <div class="aspect-[3/2] rounded-xl bg-slate-100 overflow-hidden border border-slate-200 group relative">
                            @if($rental->tenant->foto_diri)
                                <img src="{{ asset('storage/' . $rental->tenant->foto_diri) }}" class="w-full h-full object-cover transition-transform group-hover:scale-105">
                                <a href="{{ asset('storage/' . $rental->tenant->foto_diri) }}" target="_blank" class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white font-bold text-xs uppercase tracking-widest">
                                    <i class="fas fa-search-plus mr-2"></i> Lihat Fullsize
                                </a>
                            @else
                                <div class="w-full h-full flex flex-col items-center justify-center text-slate-400">
                                    <i class="fas fa-image text-3xl mb-2"></i>
                                    <span class="text-[10px] font-black uppercase tracking-widest">Tidak ada foto</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar Content --}}
            <div class="lg:col-span-4 space-y-8">
                {{-- Rental Status --}}
                <div class="stat-card !bg-brand !border-none text-white overflow-hidden">
                    <div class="absolute -right-4 -bottom-4 w-32 h-32 text-white/10">
                        <i class="fas fa-check-circle text-[120px]"></i>
                    </div>
                    <div class="relative z-10">
                        <div class="text-[10px] font-black uppercase tracking-[0.3em] text-white/60 mb-2">Status Kontrak</div>
                        <div class="text-3xl font-black mb-4 uppercase">{{ $rental->status }}</div>
                        <div class="text-sm text-white/80 leading-relaxed mb-6">
                            @if($rental->status == 'active')
                                Kontrak sedang berjalan dan aktif. Seluruh fasilitas dapat digunakan oleh penyewa.
                            @elseif($rental->status == 'pending')
                                Menunggu persetujuan admin untuk memulai masa sewa.
                            @else
                                Kontrak telah selesai atau tidak aktif.
                            @endif
                        </div>
                        <a href="{{ route('rent-payments.index', ['rental_id' => $rental->id]) }}" class="btn !bg-white !text-brand w-full">
                            <i class="fas fa-credit-card"></i>
                            Riwayat Pembayaran
                        </a>
                    </div>
                </div>

                {{-- Room Details --}}
                <div class="stat-card">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">
                            <i class="fas fa-door-open"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800 uppercase tracking-wider text-xs">Unit Properti</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="p-4 rounded-xl bg-slate-50 border border-slate-100">
                            <div class="text-2xl font-black text-slate-900 mb-1">UNIT #{{ $rental->room->room_number ?? '-' }}</div>
                            <div class="text-[10px] font-black text-brand uppercase tracking-widest">{{ $rental->room->room_type ?? 'Standard' }} Room</div>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div class="p-3 rounded-xl border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Harga / Bln</p>
                                <p class="font-bold text-slate-900">Rp {{ number_format($rental->room->price ?? 0, 0, ',', '.') }}</p>
                            </div>
                            <div class="p-3 rounded-xl border border-slate-100">
                                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Total Nilai</p>
                                <p class="font-bold text-brand">Rp {{ number_format($rental->total_price, 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Period --}}
                <div class="stat-card">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <h3 class="font-extrabold text-slate-800 uppercase tracking-wider text-xs">Periode Sewa</h3>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-0.5 h-12 bg-slate-100 relative mt-2">
                                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-2 h-2 rounded-full bg-emerald-500"></div>
                                <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-2 h-2 rounded-full bg-rose-500"></div>
                            </div>
                            <div class="flex-1 space-y-4">
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Check-in</p>
                                    <p class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($rental->start_date)->format('d M Y') }}</p>
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Check-out</p>
                                    <p class="font-bold text-slate-900">{{ \Carbon\Carbon::parse($rental->end_date)->format('d M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>


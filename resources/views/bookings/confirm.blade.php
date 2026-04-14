<x-app-layout>
    @section('header_title', 'Rental Confirmation')

    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="text-center mb-12">
            <span class="text-blue-600 font-black uppercase tracking-widest text-xs">Langkah Terakhir</span>
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight mt-2">Konfirmasi Penyewaan</h2>
            <p class="text-slate-500 mt-2">Silakan tinjau kembali data penyewaan Anda sebelum menekan tombol konfirmasi.</p>
        </div>

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            <div class="p-10 space-y-10">
                <!-- Summary Grid -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
                    <!-- Unit Info -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Unit Kamar</span>
                        </div>
                        <div class="bg-blue-50 rounded-2xl p-6 border border-blue-100 flex items-center justify-between">
                            <div>
                                <div class="text-xl font-black text-blue-900">Kamar {{ $room->room_number }}</div>
                                <div class="text-sm text-blue-700 font-medium">{{ $room->room_type }}</div>
                            </div>
                            <div class="text-right">
                                <div class="text-blue-900 font-black">Rp {{ number_format($room->price, 0, ',', '.') }}</div>
                                <div class="text-xs text-blue-600 font-medium">per bulan</div>
                            </div>
                        </div>
                    </div>

                    <!-- Period Info -->
                    <div class="space-y-4">
                        <div class="flex items-center space-x-3 text-slate-400">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            <span class="text-xs font-bold uppercase tracking-wider">Periode Awal</span>
                        </div>
                        <div class="bg-slate-50 rounded-2xl p-6 border border-slate-200">
                            <div class="text-lg font-bold text-slate-800">{{ now()->format('d M Y') }}</div>
                            <div class="text-sm text-slate-500">Estimasi sampai {{ now()->addMonth()->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>

                <!-- Profile Review -->
                <div class="px-8 py-6 rounded-2xl bg-slate-50/50 border border-slate-100 space-y-4">
                    <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">Profil Penyewa</div>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                        <div>
                            <div class="text-xs text-slate-400 mb-1">Nama Lengkap</div>
                            <div class="text-sm font-bold text-slate-700">{{ $user->name }}</div>
                        </div>
                        <div>
                            <div class="text-xs text-slate-400 mb-1">NIK</div>
                            <div class="text-sm font-bold text-slate-800 font-mono italic">{{ $user->tenant->nik }}</div>
                        </div>
                        <div class="md:col-span-2">
                            <div class="text-xs text-slate-400 mb-1">Pekerjaan</div>
                            <div class="text-sm font-bold text-slate-700">{{ $user->tenant->occupation }}</div>
                        </div>
                    </div>
                </div>

                <!-- Confirmation Button -->
                <div class="pt-10 border-t border-slate-100">
                    <form action="{{ route('bookings.store', $room->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-5 bg-emerald-600 text-white rounded-2xl font-black text-lg hover:bg-emerald-700 shadow-2xl shadow-emerald-100 transition-all flex items-center justify-center space-x-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            <span>Konfirmasi & Sewa Sekarang</span>
                        </button>
                    </form>
                    <p class="text-center text-xs text-slate-400 mt-6 leading-relaxed">
                        Dengan mengklik tombol di atas, Anda menyetujui syarat dan ketentuan<br>penyewaan yang berlaku di KosKora Premium.
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

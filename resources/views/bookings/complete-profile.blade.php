<x-app-layout>
    @section('header_title', 'Complete Your Profile')

    <div class="max-w-4xl mx-auto py-12 px-6">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-slate-800 tracking-tight">Lengkapi Profil Anda</h2>
            <p class="text-slate-500 mt-2">Untuk melanjutkan penyewaan <strong>Kamar {{ $room->room_number }}</strong>, mohon lengkapi data diri Anda.</p>
        </div>

        @if ($errors->any())
            <div class="mb-8 p-4 bg-rose-50 border border-rose-200 text-rose-700 rounded-2xl shadow-sm">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-200 shadow-xl overflow-hidden">
            <div class="flex flex-col md:flex-row">
                <!-- Room Info -->
                <div class="md:w-1/3 bg-slate-50 p-8 border-b md:border-b-0 md:border-r border-slate-200">
                    <div class="space-y-6">
                        <div class="aspect-video rounded-2xl overflow-hidden border border-slate-200 bg-white shadow-sm">
                            @if($room->picture && is_array($room->picture) && count($room->picture) > 0)
                                <img src="{{ asset('storage/' . $room->picture[0]) }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-slate-300 font-bold italic">No Photo</div>
                            @endif
                        </div>
                        <div>
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Pilihan Kamar</div>
                            <div class="text-xl font-black text-slate-800 tracking-tight">Kamar {{ $room->room_number }}</div>
                            <div class="text-sm text-blue-600 font-bold italic">{{ $room->room_type }}</div>
                        </div>
                        <div class="pt-6 border-t border-slate-200">
                            <div class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Harga</div>
                            <div class="text-2xl font-black text-slate-900">Rp {{ number_format($room->price, 0, ',', '.') }}<span class="text-xs text-slate-400 font-normal">/bln</span></div>
                        </div>
                    </div>
                </div>

                <!-- Form -->
                <div class="md:w-2/3 p-10">
                    <form action="{{ route('bookings.store-profile') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">NIK (Sesuai KTP)</label>
                                <input type="text" name="nik" value="{{ old('nik') }}" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all" placeholder="16 digit NIK">
                            </div>
                            <div>
                                <label class="block text-sm font-bold text-slate-700 mb-2">Pekerjaan</label>
                                <input type="text" name="occupation" value="{{ old('occupation') }}" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all" placeholder="Contoh: Karyawan Swasta">
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Kontak Darurat (No. HP)</label>
                            <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all" placeholder="Nomor keluarga atau kerabat">
                        </div>

                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                            <textarea name="address" rows="3" class="w-full rounded-2xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-all" placeholder="Masukkan alamat lengkap sesuai KTP...">{{ old('address') }}</textarea>
                        </div>

                        <div class="pt-6">
                            <button type="submit" class="w-full py-4 bg-blue-600 text-white rounded-2xl font-black hover:bg-blue-700 shadow-xl shadow-blue-200 transition-all">
                                Simpan Profil & Lanjutkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

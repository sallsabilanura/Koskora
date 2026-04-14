<x-app-layout>
    @section('header_title', 'Add New Tenant')

    <div class="max-w-4xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800">Tambah Penyewa Baru</h2>
            <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-xl font-semibold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-200 transition ease-in-out duration-150">
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
            <form action="{{ route('tenants.store') }}" method="POST" class="space-y-6">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- User (Account) -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pilih Akun User</label>
                        <select name="user_id" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- NIK -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">NIK (Sesuai KTP)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Masukkan 16 digit NIK">
                    </div>

                    <!-- Occupation -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Pekerjaan</label>
                        <input type="text" name="occupation" value="{{ old('occupation') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Contoh: Karyawan Swasta">
                    </div>

                    <!-- Emergency Contact -->
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2">Kontak Darurat</label>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="No. HP Keluarga / Kerabat">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Status</label>
                        <select name="status" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-bold text-slate-700 mb-2">Alamat Lengkap</label>
                        <textarea name="address" rows="4" class="w-full rounded-xl border-slate-200 focus:border-blue-500 focus:ring-blue-500 transition-colors" placeholder="Alamat lengkap sesuai KTP...">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Submit -->
                <div class="pt-4 border-t border-slate-100">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3 bg-blue-600 border border-transparent rounded-xl font-bold text-base text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 transition ease-in-out duration-150 shadow-lg shadow-blue-200">
                        Simpan Data Penyewa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

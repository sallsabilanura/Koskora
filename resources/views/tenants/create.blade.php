<x-app-layout>
    @section('header_title', 'Add New Tenant')

    <div class="max-w-4xl mx-auto space-y-6 animate-fade-in">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-bold text-slate-900 tracking-tight">Tambah Penyewa Baru</h2>
                <p class="text-slate-500 text-[13px] mt-0.5">Daftarkan akun user sebagai penyewa resmi.</p>
            </div>
            <a href="{{ route('tenants.index') }}" class="btn btn-ghost">
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
            <form action="{{ route('tenants.store') }}" method="POST" class="space-y-8">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- User (Account) -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pilih Akun User</label>
                        <select name="user_id">
                            <option value="">-- Pilih User --</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- NIK -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">NIK (Sesuai KTP)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK">
                    </div>

                    <!-- Occupation -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Pekerjaan</label>
                        <input type="text" name="occupation" value="{{ old('occupation') }}" placeholder="Contoh: Karyawan Swasta">
                    </div>

                    <!-- Emergency Contact -->
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Kontak Darurat</label>
                        <input type="text" name="emergency_contact" value="{{ old('emergency_contact') }}" placeholder="No. HP Keluarga / Kerabat">
                    </div>

                    <!-- Status -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Status</label>
                        <select name="status">
                            <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                            <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tidak Aktif</option>
                        </select>
                    </div>

                    <!-- Address -->
                    <div class="md:col-span-2 space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                        <textarea name="address" rows="3" placeholder="Alamat lengkap sesuai KTP...">{{ old('address') }}</textarea>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex justify-end pt-4 border-t border-slate-50">
                    <button type="submit" class="btn btn-primary px-10 shadow-lg shadow-brand/20">
                        Simpan Data Penyewa
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>

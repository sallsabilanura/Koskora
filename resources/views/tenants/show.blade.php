<x-app-layout>
    @section('header_title', 'Tenant Details')

    <div class="max-w-5xl mx-auto space-y-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-slate-800">Detail Penyewa: {{ $tenant->user->name ?? 'Unknown' }}</h2>
            <div class="flex items-center space-x-3">
                <a href="{{ route('tenants.edit', $tenant->id) }}" class="inline-flex items-center px-4 py-2 bg-blue-50 border border-blue-200 rounded-xl font-semibold text-xs text-blue-600 uppercase tracking-widest hover:bg-blue-100 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                    Edit
                </a>
                <a href="{{ route('tenants.index') }}" class="inline-flex items-center px-4 py-2 bg-slate-100 border border-transparent rounded-xl font-semibold text-xs text-slate-600 uppercase tracking-widest hover:bg-slate-200 transition ease-in-out duration-150">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Main Content -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Personal Information -->
                <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                    <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50 flex items-center justify-between">
                        <h3 class="text-lg font-bold text-slate-800">Informasi Pribadi</h3>
                        <span class="px-4 py-1.5 text-sm font-bold rounded-full {{ $tenant->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }}">
                            {{ ucfirst($tenant->status) }}
                        </span>
                    </div>
                    <div class="p-8 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <div class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">NIK</div>
                                <div class="text-lg font-mono font-semibold text-slate-800">{{ $tenant->nik }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Pekerjaan</div>
                                <div class="text-lg font-semibold text-slate-800">{{ $tenant->occupation }}</div>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Kontak Darurat</div>
                                <div class="text-lg font-semibold text-slate-800">{{ $tenant->emergency_contact }}</div>
                            </div>
                             <div>
                                <div class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-1">Email (Akun)</div>
                                <div class="text-lg font-semibold text-slate-800">{{ $tenant->user->email ?? 'N/A' }}</div>
                            </div>
                        </div>

                        <div>
                            <div class="text-sm font-bold text-slate-400 uppercase tracking-wider mb-2">Alamat Lengkap</div>
                            <div class="bg-slate-50 rounded-xl p-4 text-slate-600 border border-slate-100 leading-relaxed">
                                {{ $tenant->address ?: 'Tidak ada alamat tersedia.' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stats/Extra Card -->
            <div class="space-y-6">
                <div class="bg-indigo-600 rounded-2xl p-8 text-white shadow-xl shadow-indigo-100">
                    <div class="flex items-center justify-center bg-indigo-500 w-16 h-16 rounded-2xl mb-6">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold mb-1">{{ $tenant->user->name ?? 'Unknown Tenant' }}</h3>
                    <p class="text-indigo-200 text-sm mb-6">Terdaftar sejak {{ $tenant->created_at->format('M Y') }}</p>
                    
                    <div class="pt-6 border-t border-indigo-500">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-indigo-200">Total Rental</span>
                            <span class="font-bold">1 Active</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

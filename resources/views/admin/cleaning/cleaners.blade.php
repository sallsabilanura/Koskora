<x-app-layout>
    @section('header_title', 'Master Data Cleaning')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Petugas Kebersihan</h2>
                <p class="text-slate-500 text-[13px] mt-0.5">Kelola personil kebersihan profesional untuk unit KosKora.</p>
            </div>
            <button @click="$dispatch('open-modal', 'add-cleaner')" class="btn btn-primary">
                <i class="fas fa-plus-circle text-[10px]"></i>
                Tambah Petugas
            </button>
        </div>

        @if ($message = Session::get('success'))
            <div class="badge badge-green w-full justify-start p-3 rounded-xl border border-emerald-100">
                <i class="fas fa-check-circle mr-2"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== TABLE SECTION ===== --}}
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Foto & Nama</th>
                        <th>Email Akun</th>
                        <th>Bio & Pengalaman</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($cleaners as $cleaner)
                        <tr>
                            <td>
                                <div class="flex items-center gap-3">
                                    @if($cleaner->photo)
                                        <img src="{{ asset('storage/' . $cleaner->photo) }}" class="w-9 h-9 object-cover rounded-lg border border-slate-100">
                                    @else
                                        <div class="w-9 h-9 bg-slate-100 text-slate-400 rounded-lg flex items-center justify-center font-bold text-xs border border-slate-50">
                                            <i class="fas fa-user-sparkles"></i>
                                        </div>
                                    @endif
                                    <div class="font-semibold text-slate-900">{{ $cleaner->user->name }}</div>
                                </div>
                            </td>
                            <td class="text-slate-500 text-[12px] font-medium">{{ $cleaner->user->email }}</td>
                            <td>
                                <div class="text-[11px] text-slate-400 italic line-clamp-1 max-w-[300px]">
                                    "{{ $cleaner->bio ?: 'Berkomitmen memberikan standar kebersihan tertinggi.' }}"
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-green">Verified</span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-broom"></i></div>
                                    <p>Belum ada petugas kebersihan yang terdaftar.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== ADD CLEANER MODAL ===== --}}
        <x-modal name="add-cleaner" focusable maxWidth="2xl">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Petugas Baru</h3>
                        <p class="text-slate-500 text-[13px] mt-0.5">Daftarkan petugas kebersihan profesional baru.</p>
                    </div>
                    <button @click="$dispatch('close')" class="nav-icon-btn">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <form action="{{ route('admin.cleaning.cleaners.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Nama Lengkap</label>
                            <input type="text" name="name" placeholder="Ahmad Subarjo" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Email</label>
                            <input type="email" name="email" placeholder="ahmad@koskora.com" required>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Foto Profil</label>
                        <input type="file" name="photo" class="block w-full text-[11px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-slate-600">
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Bio & Pengalaman</label>
                        <textarea name="bio" rows="3" placeholder="Tuliskan pengalaman atau moto kerja..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button" @click="$dispatch('close')" class="btn btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-primary px-8">Daftarkan Petugas</button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</x-app-layout>

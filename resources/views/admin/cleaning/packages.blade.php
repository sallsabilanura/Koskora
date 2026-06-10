<x-app-layout>
    @section('header_title', 'Cleaning Package Catalog')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div>
                <h2 class="text-xl font-semibold text-slate-900">Katalog Paket Kebersihan</h2>
                <p class="text-slate-500 text-[13px] mt-0.5">Konfigurasi paket layanan kebersihan untuk penghuni KosKora.</p>
            </div>
            <button @click="$dispatch('open-modal', 'add-package')" class="btn btn-primary">
                <i class="fas fa-plus-circle text-[10px]"></i>
                Buat Paket Baru
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
                        <th>Package Tier</th>
                        <th>Cakupan Layanan</th>
                        <th class="text-right">Harga (Nett)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($packages as $package)
                        <tr>
                            <td>
                                <div class="flex flex-col gap-1.5">
                                    <span class="badge badge-purple w-fit">{{ $package->name }}</span>
                                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest pl-0.5">#PKG{{ $package->id }}</span>
                                </div>
                            </td>
                            <td>
                                <div class="text-[12px] text-slate-600 font-medium leading-relaxed italic max-w-lg">
                                    "{{ $package->description }}"
                                </div>
                            </td>
                            <td class="text-right">
                                <div class="text-[15px] font-bold text-slate-900 tracking-tight">
                                    Rp {{ number_format($package->price, 0, ',', '.') }}
                                </div>
                                <div class="text-[10px] text-slate-400 font-medium uppercase tracking-tighter">Per Service</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3">
                                <div class="empty-state">
                                    <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
                                    <p>Belum ada paket layanan yang dibuat.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== ADD PACKAGE MODAL ===== --}}
        <x-modal name="add-package" focusable maxWidth="2xl">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight">Buat Paket Baru</h3>
                        <p class="text-slate-500 text-[13px] mt-0.5">Definisikan paket layanan kebersihan baru untuk katalog.</p>
                    </div>
                    <button @click="$dispatch('close')" class="nav-icon-btn">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <form action="{{ route('admin.cleaning.packages.store') }}" method="POST" class="space-y-6">
                    @csrf
                    
                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Nama Paket / Tier</label>
                        <input type="text" name="name" placeholder="Contoh: Paket Standar" required>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Deskripsi Layanan</label>
                        <textarea name="description" rows="4" placeholder="Jelaskan apa saja yang didapatkan (Sapu, Pel, Kamar Mandi, dll)..." required></textarea>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-xs font-semibold text-slate-500 uppercase tracking-wider ml-1">Harga Layanan (Rp)</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 font-bold text-xs">Rp</span>
                            <input type="number" name="price" class="!pl-11" placeholder="50000" required>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button" @click="$dispatch('close')" class="btn btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-primary px-8">Simpan Paket</button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>
</x-app-layout>

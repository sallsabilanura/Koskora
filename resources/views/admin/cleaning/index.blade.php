<x-app-layout>
    @section('header_title', 'Cleaning Hub')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== TAB NAVIGATION ===== --}}
        <div class="flex overflow-x-auto gap-2 pb-1 no-scrollbar">
            <button onclick="switchTab('pesanan')" id="tab-btn-pesanan"
                class="tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200 bg-white border border-slate-100 text-slate-500 hover:text-brand shadow-sm">
                <i class="fas fa-clipboard-check text-[10px]"></i>
                Daftar Pesanan
            </button>
            <button onclick="switchTab('petugas')" id="tab-btn-petugas"
                class="tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200 bg-white border border-slate-100 text-slate-500 hover:text-brand shadow-sm">
                <i class="fas fa-user-shield text-[10px]"></i>
                Daftar Petugas
            </button>
            <button onclick="switchTab('paket')" id="tab-btn-paket"
                class="tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200 bg-white border border-slate-100 text-slate-500 hover:text-brand shadow-sm">
                <i class="fas fa-box text-[10px]"></i>
                Paket Layanan
            </button>
        </div>

        {{-- ===== TAB: PESANAN ===== --}}
        <div id="tab-pesanan" class="tab-panel space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total
                                Pendapatan</p>
                            <h3 class="text-2xl font-bold text-slate-800">Rp
                                {{ number_format($orders->where('payment_status', 'paid')->sum('total_price'), 0, ',', '.') }}
                            </h3>
                        </div>
                        <div
                            class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Booking Aktif
                            </p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $orders->whereNotIn('status', ['done', 'cancelled'])->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-broom"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                <div class="p-4 border-b border-slate-50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Arus Pesanan Bebersih</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Penghuni & Unit</th>
                            <th>Paket Layanan</th>
                            <th>Petugas</th>
                            <th>Jadwal</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>
                                    <div class="font-semibold text-slate-900">{{ $order->user->name }}</div>
                                    @php $rental = $order->user->tenant ? $order->user->tenant->rentals->first() : null; @endphp
                                    <div class="text-[10px] font-bold text-brand uppercase">Room
                                        {{ $rental && $rental->room ? $rental->room->room_number : '?' }}</div>
                                </td>
                                <td>
                                    <div class="text-[12px] font-bold text-slate-700">{{ $order->package->name }}</div>
                                    <div class="text-[10px] text-brand font-bold">Rp
                                        {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                </td>
                                <td>
                                    <div class="text-[12px] font-semibold text-slate-600">{{ $order->cleaner->user->name }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-[11px] font-bold text-slate-700">
                                        {{ $order->scheduled_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">
                                        {{ $order->scheduled_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $sBadge = [
                                            'done' => 'badge-green',
                                            'working' => 'badge-purple',
                                            'pending' => 'badge-amber',
                                            'cancelled' => 'badge-red',
                                        ][$order->status] ?? 'badge-gray';
                                    @endphp
                                    <span class="badge {{ $sBadge }}">{{ ucfirst($order->status) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-calendar-check"></i></div>
                                        <p>Belum ada transaksi cleaning yang tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== TAB: PETUGAS ===== --}}
        <div id="tab-petugas" class="tab-panel hidden space-y-6">
            <div class="flex justify-end">
                <button @click="$dispatch('open-modal', 'register-cleaner')" class="btn btn-primary">
                    <i class="fas fa-plus text-[10px]"></i>
                    Daftarkan Petugas
                </button>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Petugas</th>
                            <th>Email</th>
                            <th>Bio Singkat</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($cleaners as $cleaner)
                            <tr>
                                <td>
                                    <div class="flex items-center gap-3">
                                        @if($cleaner->photo)
                                            <img src="{{ asset('storage/' . $cleaner->photo) }}"
                                                class="w-9 h-9 object-cover rounded-lg border border-slate-100">
                                        @else
                                            <div
                                                class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400">
                                                <i class="fas fa-user text-xs"></i>
                                            </div>
                                        @endif
                                        <div class="font-semibold text-slate-900">{{ $cleaner->user->name }}</div>
                                    </div>
                                </td>
                                <td><span class="text-[12px] text-slate-600 font-medium">{{ $cleaner->user->email }}</span>
                                </td>
                                <td>
                                    <p class="text-[11px] text-slate-500 line-clamp-1 max-w-xs">{{ $cleaner->bio }}</p>
                                </td>
                                <td class="text-center"><span class="badge badge-green">Aktif</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-user-slash"></i></div>
                                        <p>Belum ada petugas yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== TAB: PAKET ===== --}}
        <div id="tab-paket" class="tab-panel hidden space-y-6">
            <div class="flex justify-end">
                <button @click="$dispatch('open-modal', 'add-package')" class="btn btn-primary">
                    <i class="fas fa-plus text-[10px]"></i>
                    Tambah Paket
                </button>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama Paket</th>
                            <th>Deskripsi</th>
                            <th class="text-right">Harga Layanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($packages as $package)
                            <tr>
                                <td>
                                    <div class="font-bold text-slate-800">{{ $package->name }}</div>
                                </td>
                                <td>
                                    <p class="text-[11px] text-slate-500 max-w-sm">{{ $package->description }}</p>
                                </td>
                                <td class="text-right">
                                    <div class="font-bold text-brand">Rp {{ number_format($package->price, 0, ',', '.') }}
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-box-open"></i></div>
                                        <p>Belum ada paket layanan yang ditambahkan.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== MODALS ===== --}}
        {{-- Register Cleaner Modal --}}
        <x-modal name="register-cleaner" focusable maxWidth="md">
            <div class="p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">Daftarkan Petugas Baru</h3>
                    <p class="text-slate-500 text-[13px] mt-0.5">Berikan akses sistem kepada petugas kebersihan baru.
                    </p>
                </div>
                <form action="{{ route('admin.cleaning.cleaners.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-5">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Lengkap</label>
                        <input type="text" name="name" placeholder="Nama Petugas" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Email Login</label>
                        <input type="email" name="email" placeholder="email@contoh.com" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Foto Profil</label>
                        <input type="file" name="photo"
                            class="block w-full text-[11px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-slate-600">
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Bio/Keahlian Singkat</label>
                        <textarea name="bio" rows="2" placeholder="Contoh: Ahli pembersihan kamar & kamar mandi..."></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button" @click="$dispatch('close')" class="btn btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-primary px-8">Daftarkan Petugas</button>
                    </div>
                </form>
            </div>
        </x-modal>

        {{-- Add Package Modal --}}
        <x-modal name="add-package" focusable maxWidth="md">
            <div class="p-8">
                <div class="mb-8">
                    <h3 class="text-xl font-bold text-slate-900 tracking-tight">Tambah Paket Layanan</h3>
                    <p class="text-slate-500 text-[13px] mt-0.5">Tentukan nama, rincian, dan harga paket baru.</p>
                </div>
                <form action="{{ route('admin.cleaning.packages.store') }}" method="POST" class="space-y-5">
                    @csrf
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Paket</label>
                        <input type="text" name="name" placeholder="Contoh: Bebersih Kamar Mandi" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Harga (Rp)</label>
                        <input type="number" name="price" placeholder="50000" required>
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Deskripsi Layanan</label>
                        <textarea name="description" rows="3" placeholder="Rincian apa saja yang dibersihkan..." required></textarea>
                    </div>
                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button" @click="$dispatch('close')" class="btn btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-primary px-8">Simpan Paket</button>
                    </div>
                </form>
            </div>
        </x-modal>
    </div>

    <script>
        const ACTIVE_TAB_CLASSES = ['!bg-brand', '!text-white', '!border-brand', 'shadow-brand/20'];
        const INACTIVE_TAB_CLASSES = ['bg-white', 'text-slate-500', 'border-slate-100'];

        function switchTab(tabId) {
            document.querySelectorAll('.tab-panel').forEach(el => el.classList.add('hidden'));
            document.querySelectorAll('.tab-btn').forEach(btn => {
                btn.classList.remove(...ACTIVE_TAB_CLASSES);
                btn.classList.add(...INACTIVE_TAB_CLASSES);
            });

            document.getElementById('tab-' + tabId).classList.remove('hidden');
            const activeBtn = document.getElementById('tab-btn-' + tabId);
            activeBtn.classList.remove(...INACTIVE_TAB_CLASSES);
            activeBtn.classList.add(...ACTIVE_TAB_CLASSES);
            localStorage.setItem('cleaningAdminTab', tabId);
        }

        const savedTab = localStorage.getItem('cleaningAdminTab') || 'pesanan';
        switchTab(savedTab);
    </script>
</x-app-layout>
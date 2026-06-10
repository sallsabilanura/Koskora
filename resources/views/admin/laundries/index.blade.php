<x-app-layout>
    @section('header_title', 'Laundry Hub')

    <div class="space-y-6 animate-fade-in">
        {{-- ===== TAB NAVIGATION ===== --}}
        <div class="flex overflow-x-auto gap-2 pb-1 no-scrollbar">
            <button onclick="switchTab('pesanan')" id="tab-btn-pesanan"
                class="tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200 bg-white border border-slate-100 text-slate-500 hover:text-brand shadow-sm">
                <i class="fas fa-clipboard-check text-[10px]"></i>
                Daftar Pesanan
            </button>
            <button onclick="switchTab('partner')" id="tab-btn-partner"
                class="tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200 bg-white border border-slate-100 text-slate-500 hover:text-brand shadow-sm">
                <i class="fas fa-store text-[10px]"></i>
                Daftar Partner
            </button>
            <button onclick="switchTab('layanan')" id="tab-btn-layanan"
                class="tab-btn flex-shrink-0 flex items-center gap-2 px-5 py-2.5 rounded-xl text-[11px] font-bold uppercase tracking-widest transition-all duration-200 bg-white border border-slate-100 text-slate-500 hover:text-brand shadow-sm">
                <i class="fas fa-tshirt text-[10px]"></i>
                Daftar Layanan
            </button>
        </div>

        @if ($message = Session::get('success'))
            <div class="badge badge-green w-full justify-start p-3 rounded-xl border border-emerald-100">
                <i class="fas fa-check-circle mr-2"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== TAB: PESANAN ===== --}}
        <div id="tab-pesanan" class="tab-panel space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Total Komisi</p>
                            <h3 class="text-2xl font-bold text-slate-800">Rp
                                {{ number_format($orders->where('payment_status', 'paid')->sum('commission_amount'), 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="w-10 h-10 bg-emerald-50 text-emerald-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-coins"></i>
                        </div>
                    </div>
                </div>
                <div class="stat-card">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1">Pesanan Aktif</p>
                            <h3 class="text-2xl font-bold text-slate-800">
                                {{ $orders->whereNotIn('status', ['done', 'cancelled'])->count() }}</h3>
                        </div>
                        <div class="w-10 h-10 bg-blue-50 text-blue-500 rounded-xl flex items-center justify-center">
                            <i class="fas fa-sync fa-spin"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="table-wrap">
                <div class="p-4 border-b border-slate-50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Arus Pesanan Laundry</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Penghuni & Unit</th>
                            <th>Partner Laundry</th>
                            <th>Total Bayar</th>
                            <th>Jadwal / Waktu</th>
                            <th class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td data-label="Penghuni">
                                    <div class="font-semibold text-slate-900">{{ $order->user->name }}</div>
                                    @php $rental = $order->user->tenant ? $order->user->tenant->rentals->first() : null; @endphp
                                    <div class="text-[10px] font-bold text-brand uppercase">Room
                                        {{ $rental && $rental->room ? $rental->room->room_number : '?' }}</div>
                                </td>
                                <td data-label="Partner">
                                    <div class="text-[12px] font-bold text-slate-700">{{ $order->laundry->name }}</div>
                                </td>
                                <td data-label="Total">
                                    <div class="text-[12px] font-bold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                    <div class="text-[10px] text-brand font-bold">Komisi: Rp {{ number_format($order->commission_amount, 0, ',', '.') }}</div>
                                </td>
                                <td data-label="Waktu">
                                    <div class="text-[11px] font-bold text-slate-700">{{ $order->created_at->format('d M Y') }}</div>
                                    <div class="text-[10px] text-slate-400 font-medium">{{ $order->created_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="text-center" data-label="Status">
                                    @php
                                        $sBadge = [
                                            'done' => 'badge-green',
                                            'in_progress' => 'badge-purple',
                                            'ready' => 'badge-blue',
                                            'picked_up' => 'badge-amber',
                                            'pending' => 'badge-gray',
                                            'cancelled' => 'badge-red',
                                        ][$order->status] ?? 'badge-gray';
                                    @endphp
                                    <span class="badge {{ $sBadge }}">{{ ucfirst(str_replace('_', ' ', $order->status)) }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-receipt"></i></div>
                                        <p>Belum ada transaksi laundry yang tercatat.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== TAB: PARTNER ===== --}}
        <div id="tab-partner" class="tab-panel hidden space-y-6">
            <div class="flex justify-end">
                <button @click="$dispatch('open-modal', 'register-partner')" class="btn btn-primary">
                    <i class="fas fa-plus text-[10px]"></i>
                    Daftarkan Partner
                </button>
            </div>

            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Partner & Owner</th>
                            <th>Kontak</th>
                            <th>Alamat</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($laundries as $laundry)
                            <tr>
                                <td data-label="Partner">
                                    <div class="flex items-center gap-3">
                                        @if($laundry->image)
                                            <img src="{{ asset('storage/' . $laundry->image) }}"
                                                class="w-9 h-9 object-cover rounded-lg border border-slate-100">
                                        @else
                                            <div class="w-9 h-9 bg-slate-100 rounded-lg flex items-center justify-center text-slate-400 border border-slate-50">
                                                <i class="fas fa-tshirt text-xs"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="font-semibold text-slate-900">{{ $laundry->name }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $laundry->user->name }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td data-label="Kontak">
                                    <div class="text-[11px] font-semibold text-slate-700">{{ $laundry->user->email }}</div>
                                    <div class="text-[10px] text-brand font-bold">{{ $laundry->phone }}</div>
                                </td>
                                <td data-label="Alamat">
                                    <div class="text-[11px] text-slate-500 line-clamp-1 max-w-[200px]">{{ $laundry->address }}</div>
                                </td>
                                <td class="text-center" data-label="Status">
                                    <span class="badge badge-green">Aktif</span>
                                </td>
                                <td class="text-right" data-label="Aksi">
                                    <div class="flex items-center justify-end gap-1">
                                        <a href="{{ route('admin.laundries.edit', $laundry->id) }}" class="nav-icon-btn" title="Edit">
                                            <i class="fas fa-edit text-[11px]"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-store-slash"></i></div>
                                        <p>Belum ada partner laundry yang terdaftar.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== TAB: LAYANAN ===== --}}
        <div id="tab-layanan" class="tab-panel hidden space-y-6">
            <div class="table-wrap">
                <div class="p-4 border-b border-slate-50">
                    <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Katalog Layanan Partner</h3>
                </div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Partner Laundry</th>
                            <th>Nama Layanan/Item</th>
                            <th class="text-right">Harga Layanan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($services as $service)
                            <tr>
                                <td data-label="Partner">
                                    <div class="font-bold text-slate-800">{{ $service->laundry->name }}</div>
                                </td>
                                <td data-label="Layanan">
                                    <div class="text-[12px] font-semibold text-slate-700">{{ $service->item_name }}</div>
                                </td>
                                <td class="text-right" data-label="Harga">
                                    <div class="font-bold text-brand">Rp {{ number_format($service->price, 0, ',', '.') }}</div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-tshirt"></i></div>
                                        <p>Belum ada layanan yang ditawarkan oleh partner.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- ===== MODALS ===== --}}
        <x-modal name="register-partner" focusable maxWidth="3xl">
            <div class="p-8">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-xl font-bold text-slate-900 tracking-tight">Daftar Partner Baru</h3>
                        <p class="text-slate-500 text-[13px] mt-0.5">Silakan lengkapi informasi partner laundry di bawah ini.</p>
                    </div>
                    <button @click="$dispatch('close')" class="nav-icon-btn">
                        <i class="fas fa-times text-xs"></i>
                    </button>
                </div>

                <form action="{{ route('admin.laundries.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        {{-- Owner Info --}}
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-1 h-3 bg-brand rounded-full"></span>
                                <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-widest">Informasi Pemilik</h4>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Pemilik</label>
                                <input type="text" name="partner_name" placeholder="Nama Lengkap" required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Email Login</label>
                                <input type="email" name="email" placeholder="email@contoh.com" required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Password</label>
                                <input type="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        {{-- Laundry Info --}}
                        <div class="space-y-4">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="w-1 h-3 bg-brand rounded-full"></span>
                                <h4 class="text-[11px] font-bold text-slate-800 uppercase tracking-widest">Informasi Laundry</h4>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Nama Laundry</label>
                                <input type="text" name="laundry_name" placeholder="Nama Toko Laundry" required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">No. WhatsApp</label>
                                <input type="text" name="phone" placeholder="0812..." required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Logo Laundry</label>
                                <input type="file" name="image"
                                    class="block w-full text-[11px] text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-[10px] file:font-bold file:bg-slate-100 file:text-slate-600">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1.5">
                        <label class="text-[11px] font-bold text-slate-400 uppercase tracking-widest ml-1">Alamat Lengkap</label>
                        <textarea name="address" rows="2" placeholder="Jl. Raya Utama No. 123..."></textarea>
                    </div>

                    <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-50">
                        <button type="button" @click="$dispatch('close')" class="btn btn-ghost">Batal</button>
                        <button type="submit" class="btn btn-primary px-8">Daftarkan Partner</button>
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
            localStorage.setItem('laundryAdminTab', tabId);
        }

        const savedTab = localStorage.getItem('laundryAdminTab') || 'pesanan';
        switchTab(savedTab);
    </script>
</x-app-layout>
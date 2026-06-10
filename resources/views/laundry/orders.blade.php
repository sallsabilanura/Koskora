<x-app-layout>
    @section('header_title', 'Laundry Operations')

    <div class="space-y-8 animate-fade-in pb-10">
        {{-- ===== HEADER ===== --}}
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 px-2">
            <div>
                <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Manajemen Pesanan</h2>
                <p class="text-slate-500 text-sm mt-1">Pantau dan update status cucian penghuni.</p>
            </div>
            @if($orders->count() > 0)
                <div class="flex items-center gap-2 px-4 py-2 bg-brand/10 text-brand rounded-xl border border-brand/20">
                    <span class="w-2 h-2 bg-brand rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold uppercase tracking-widest">{{ $orders->count() }} Pesanan Aktif</span>
                </div>
            @endif
        </div>

        {{-- ===== STATS GRID ===== --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="md:col-span-2 container-card !bg-slate-900 !text-white !border-none relative overflow-hidden group">
                <div class="relative z-10 flex flex-col md:flex-row justify-between items-center gap-8">
                    <div>
                        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Pendapatan Tersedia</p>
                        <h3 class="text-3xl font-black tracking-tight">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                    </div>
                    <a href="{{ route('laundry.withdrawals.index') }}" class="btn !bg-brand !text-white !border-none px-10 shadow-lg shadow-brand/20 hover:!bg-brand-dark transition-all">
                        Tarik Saldo
                    </a>
                </div>
                <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-brand/10 rounded-full blur-3xl"></div>
            </div>
            <div class="container-card flex flex-col justify-center text-center">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Status Kemitraan</p>
                <div class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-50 text-emerald-600 rounded-xl border border-emerald-100 mx-auto">
                    <i class="fas fa-certificate text-xs"></i>
                    <span class="text-xs font-black uppercase tracking-tighter">PRO PARTNER</span>
                </div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-center gap-3 text-xs font-bold animate-fade-in">
                <i class="fas fa-check-circle"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== ORDERS TABLE ===== --}}
        <div class="space-y-4">
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest px-2">Daftar Pekerjaan</h3>
            <div class="table-wrap">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Penghuni</th>
                            <th>Rincian Item</th>
                            <th class="text-right">Tagihan</th>
                            <th class="text-center">Pembayaran</th>
                            <th class="text-center">Status</th>
                            <th class="text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr class="group">
                                <td>
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs group-hover:bg-brand/10 group-hover:text-brand transition-all">
                                            {{ strtoupper(substr($order->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <div class="font-bold text-slate-900 text-xs">{{ $order->user->name }}</div>
                                            @php $rental = $order->user->tenant ? $order->user->tenant->rentals->first() : null; @endphp
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">
                                                Unit #{{ $rental && $rental->room ? $rental->room->room_number : '—' }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="flex flex-wrap gap-1.5 max-w-[250px]">
                                        @foreach($order->items as $item)
                                            <span class="px-2 py-0.5 bg-slate-50 text-slate-600 text-[10px] font-bold rounded-md border border-slate-100">
                                                {{ $item['qty'] }}x {{ $item['item'] }}
                                            </span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="text-right">
                                    <div class="text-xs font-bold text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                </td>
                                <td class="text-center">
                                    @php
                                        $payBadge = [
                                            'paid' => 'badge-green',
                                            'pending' => 'badge-amber',
                                            'unpaid' => 'badge-red',
                                        ][$order->payment_status] ?? 'badge-gray';
                                    @endphp
                                    <span class="badge {{ $payBadge }}">{{ strtoupper($order->payment_status) }}</span>
                                </td>
                                <td class="text-center">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['cls' => 'badge-amber', 'label' => 'Pickup'],
                                            'picked_up' => ['cls' => 'badge-blue', 'label' => 'Dijemput'],
                                            'in_progress' => ['cls' => 'badge-blue', 'label' => 'Proses'],
                                            'ready' => ['cls' => 'badge-green', 'label' => 'Siap'],
                                            'delivered' => ['cls' => 'badge-gray', 'label' => 'Diantar'],
                                            'done' => ['cls' => 'badge-gray', 'label' => 'Selesai'],
                                        ];
                                        $currStatus = $statusConfig[$order->status] ?? ['cls' => 'badge-gray', 'label' => $order->status];
                                    @endphp
                                    <span class="badge {{ $currStatus['cls'] }}">{{ strtoupper($currStatus['label']) }}</span>
                                </td>
                                <td class="text-right">
                                    <form action="{{ route('laundry.orders.status', $order->id) }}" method="POST">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" 
                                                class="!text-[11px] !h-9 !py-0 !px-3 !w-32 !bg-slate-50 border-slate-200 !rounded-lg focus:ring-brand cursor-pointer font-bold uppercase tracking-wider text-slate-600">
                                            @foreach($statusConfig as $val => $cfg)
                                                <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>{{ $cfg['label'] }}</option>
                                            @endforeach
                                        </select>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">
                                    <div class="empty-state">
                                        <div class="empty-state-icon"><i class="fas fa-tshirt"></i></div>
                                        <p class="text-slate-500">Belum ada pesanan cucian yang masuk.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>

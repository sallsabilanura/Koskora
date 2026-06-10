<x-app-layout>
    @section('header_title', 'Cleaning Tasks')

    <div class="space-y-8 animate-fade-in pb-10">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- ===== LEFT SIDE: BANK & STATS ===== --}}
            <div class="lg:col-span-1 space-y-8">
                {{-- Financial Overview --}}
                <div class="container-card !bg-slate-900 !text-white !border-none relative overflow-hidden group">
                    <div class="relative z-10 space-y-6">
                        <div>
                            <p class="text-[10px] font-bold uppercase tracking-widest text-slate-500 mb-1">Pendapatan Tersedia</p>
                            <h3 class="text-3xl font-black tracking-tight">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                        </div>
                        <a href="{{ route('cleaner.withdrawals.index') }}" class="btn !bg-brand !text-white !border-none w-full shadow-lg shadow-brand/20 hover:!bg-brand-dark transition-all">
                            Cairkan Dana
                        </a>
                    </div>
                    <div class="absolute -right-8 -bottom-8 w-32 h-32 bg-brand/10 rounded-full blur-3xl"></div>
                </div>

                {{-- Bank Info Management --}}
                <div class="container-card space-y-6">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-widest flex items-center gap-2">
                        <i class="fas fa-university text-brand"></i>
                        Info Rekening
                    </h4>
                    
                    <form action="{{ route('cleaner.bank-info.update') }}" method="POST" class="space-y-4">
                        @csrf
                        @php $cleaner = auth()->user()->cleaner; @endphp
                        <div class="form-group">
                            <label class="form-label">Nama Bank</label>
                            <input type="text" name="bank_name" value="{{ $cleaner->bank_name }}" placeholder="Contoh: BCA, Mandiri" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">No. Rekening</label>
                            <input type="text" name="account_number" value="{{ $cleaner->account_number }}" placeholder="0001234567" required>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Atas Nama</label>
                            <input type="text" name="account_name" value="{{ $cleaner->account_name }}" placeholder="Sesuai Buku Tabungan" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-full !h-12 mt-2">Update Data</button>
                    </form>
                </div>
            </div>

            {{-- ===== RIGHT SIDE: TASK MONITORING ===== --}}
            <div class="lg:col-span-2 space-y-6">
                @if ($message = Session::get('success'))
                    <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-xl flex items-center gap-3 text-xs font-bold animate-fade-in">
                        <i class="fas fa-check-circle"></i>
                        {{ $message }}
                    </div>
                @endif

                <div class="space-y-4">
                    <div class="flex items-center justify-between px-2">
                        <h3 class="text-xs font-bold text-slate-400 uppercase tracking-widest">Monitoring Tugas</h3>
                        <div class="flex gap-4">
                            <span class="text-[11px] font-bold text-slate-400"><span class="text-brand">{{ $orders->where('status', 'pending')->count() }}</span> Pending</span>
                            <span class="text-[11px] font-bold text-slate-400"><span class="text-emerald-500">{{ $orders->where('status', 'working')->count() }}</span> Aktif</span>
                        </div>
                    </div>

                    <div class="table-wrap">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Unit & Penghuni</th>
                                    <th>Paket Layanan</th>
                                    <th class="text-center">Status Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    <tr class="group">
                                        <td>
                                            <div class="flex items-center gap-4">
                                                <div class="w-12 h-12 rounded-xl bg-slate-900 text-white flex items-center justify-center font-bold text-lg group-hover:scale-105 transition-transform">
                                                    @php $rental = optional($order->user->tenant)->rentals ? $order->user->tenant->rentals->first() : null; @endphp
                                                    {{ $rental && $rental->room ? $rental->room->room_number : '?' }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-slate-900 text-xs">{{ $order->user->name }}</div>
                                                    <div class="text-[10px] font-bold text-brand uppercase tracking-wider">Jadwal: {{ $order->scheduled_at->format('d M, H:i') }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="font-bold text-slate-900 text-xs">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                                            <div class="flex items-center gap-2 mt-1.5">
                                                <span class="badge badge-blue !text-[9px]">{{ strtoupper($order->package->name) }}</span>
                                                @php 
                                                    $pStatus = $order->payment_status ?? 'unpaid';
                                                    $pBadge = $pStatus == 'paid' ? 'badge-green' : 'badge-amber';
                                                @endphp
                                                <span class="badge {{ $pBadge }} !text-[9px]">{{ strtoupper($pStatus) }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <form action="{{ route('cleaner.orders.status', $order->id) }}" method="POST">
                                                @csrf
                                                <select name="status" onchange="this.form.submit()" 
                                                        class="!text-[11px] !h-9 !py-0 !px-3 !w-32 !bg-slate-50 border-slate-200 !rounded-lg focus:ring-brand cursor-pointer font-bold uppercase tracking-wider text-slate-600">
                                                    <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="approved" {{ $order->status == 'approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="working" {{ $order->status == 'working' ? 'selected' : '' }}>Working</option>
                                                    <option value="done" {{ $order->status == 'done' ? 'selected' : '' }}>Done</option>
                                                    <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                </select>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3">
                                            <div class="empty-state">
                                                <div class="empty-state-icon"><i class="fas fa-broom"></i></div>
                                                <p class="text-slate-500">Belum ada tugas pembersihan hari ini.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

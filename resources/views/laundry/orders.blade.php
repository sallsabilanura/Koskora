<x-app-layout>
    @section('header_title', 'Laundry Partner')

    <div class="animate-fade-in pb-28 space-y-5">

        {{-- ===== TOP HEADER ===== --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Laundry Partner</h2>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
            @if($orders->count() > 0)
                <div class="flex items-center gap-2 px-3 py-1.5 bg-brand/10 text-brand rounded-xl border border-brand/20">
                    <span class="w-2 h-2 bg-brand rounded-full animate-pulse"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $orders->count() }} Aktif</span>
                </div>
            @endif
        </div>

        {{-- ===== BALANCE CARD ===== --}}
        <div class="relative rounded-[24px] overflow-hidden bg-slate-900 p-6 shadow-xl">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Saldo Tersedia</p>
                <h3 class="text-3xl font-black text-white tracking-tight">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                <a href="{{ route('laundry.withdrawals.index') }}"
                   class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-brand text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-brand/30 active:scale-95 transition-all">
                    <i class="fas fa-wallet"></i> Tarik Saldo
                </a>
            </div>
            {{-- Decorative --}}
            <div class="absolute top-0 right-0 w-40 h-40 bg-brand/10 rounded-full blur-3xl -translate-y-10 translate-x-10"></div>
            <div class="absolute bottom-0 right-12 w-20 h-20 bg-blue-500/5 rounded-full blur-2xl"></div>
            {{-- PRO Badge --}}
            <div class="absolute top-5 right-5 flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/20 border border-emerald-500/30 rounded-full">
                <i class="fas fa-certificate text-emerald-400 text-[8px]"></i>
                <span class="text-[8px] font-black text-emerald-400 uppercase tracking-widest">Pro Partner</span>
            </div>
        </div>

        {{-- ===== SUCCESS FLASH ===== --}}
        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 text-xs font-bold">
                <i class="fas fa-check-circle text-lg"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== ORDER LIST ===== --}}
        <div class="space-y-3">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Daftar Pekerjaan</h3>

            @forelse($orders as $order)
                @php
                    $statusConfig = [
                        'pending'     => ['icon' => 'fa-clock',          'color' => 'bg-amber-50 text-amber-600 border-amber-100',  'dot' => 'bg-amber-400',  'label' => 'Menunggu Pickup'],
                        'picked_up'   => ['icon' => 'fa-truck-pickup',   'color' => 'bg-blue-50 text-blue-600 border-blue-100',     'dot' => 'bg-blue-400',   'label' => 'Sudah Dijemput'],
                        'in_progress' => ['icon' => 'fa-soap',           'color' => 'bg-indigo-50 text-indigo-600 border-indigo-100','dot' => 'bg-indigo-400', 'label' => 'Sedang Diproses'],
                        'ready'       => ['icon' => 'fa-box-check',      'color' => 'bg-teal-50 text-teal-600 border-teal-100',     'dot' => 'bg-teal-400',   'label' => 'Siap Diantar'],
                        'delivered'   => ['icon' => 'fa-check',          'color' => 'bg-emerald-50 text-emerald-600 border-emerald-100','dot' => 'bg-emerald-400','label' => 'Diantar'],
                        'done'        => ['icon' => 'fa-check-double',   'color' => 'bg-slate-50 text-slate-400 border-slate-100',  'dot' => 'bg-slate-300',  'label' => 'Selesai'],
                    ];
                    $curr = $statusConfig[$order->status] ?? ['icon' => 'fa-question', 'color' => 'bg-slate-50 text-slate-400 border-slate-100', 'dot' => 'bg-slate-300', 'label' => $order->status];
                    $payBadge = $order->payment_status === 'paid'
                        ? 'bg-emerald-50 text-emerald-600 border-emerald-100'
                        : ($order->payment_status === 'pending' ? 'bg-amber-50 text-amber-600 border-amber-100' : 'bg-rose-50 text-rose-600 border-rose-100');
                    $payLabel = $order->payment_status === 'paid' ? 'Lunas' : ($order->payment_status === 'pending' ? 'Verifikasi' : 'Belum Bayar');
                    $rental = optional($order->user->tenant)->rentals ? $order->user->tenant->rentals->first() : null;
                @endphp

                <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm overflow-hidden">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-5 pt-5 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-sm">
                                {{ strtoupper(substr($order->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900 leading-tight">{{ $order->user->name }}</div>
                                <div class="text-[10px] font-bold text-brand uppercase tracking-widest">
                                    Unit #{{ $rental && $rental->room ? $rental->room->room_number : '—' }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-base font-black text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            <div class="text-[9px] text-slate-400 font-bold">{{ $order->created_at->format('d M, H:i') }}</div>
                        </div>
                    </div>

                    {{-- Items --}}
                    <div class="px-5 pb-3">
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($order->items as $item)
                                <span class="px-2.5 py-1 bg-slate-50 text-slate-600 text-[10px] font-bold rounded-lg border border-slate-100">
                                    {{ $item['qty'] }}× {{ $item['item'] }}
                                </span>
                            @endforeach
                        </div>
                    </div>

                    {{-- Status Row --}}
                    <div class="flex items-center gap-2 px-5 pb-3 flex-wrap">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-black rounded-full border {{ $curr['color'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $curr['dot'] }}"></span>
                            {{ $curr['label'] }}
                        </span>
                        <span class="px-3 py-1 text-[10px] font-black rounded-full border {{ $payBadge }}">
                            {{ $payLabel }}
                        </span>
                    </div>

                    {{-- Action: Status Dropdown --}}
                    <div class="border-t border-slate-50 px-4 py-3 bg-slate-50/50">
                        <form action="{{ route('laundry.orders.status', $order->id) }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Update Status</label>
                            <select name="status" onchange="this.form.submit()"
                                    class="flex-1 !text-[11px] !h-10 !py-0 !px-3 !bg-white border-slate-200 !rounded-xl focus:ring-brand cursor-pointer font-bold text-slate-700 uppercase tracking-wider">
                                @foreach($statusConfig as $val => $cfg)
                                    <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>{{ $cfg['label'] }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-slate-50/50 rounded-[20px] border-2 border-dashed border-slate-100">
                    <div class="w-16 h-16 bg-slate-100 rounded-3xl flex items-center justify-center text-2xl text-slate-300 mx-auto mb-4">
                        <i class="fas fa-tshirt"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-400">Belum ada pesanan cucian masuk.</p>
                    <p class="text-[10px] text-slate-300 mt-1">Pesanan baru akan muncul di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

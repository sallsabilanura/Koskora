<x-app-layout>
    @section('header_title', 'Cleaning Partner')

    <div class="animate-fade-in pb-28 space-y-5">

        {{-- ===== TOP HEADER ===== --}}
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Cleaning Partner</h2>
                <p class="text-[11px] text-slate-400 font-medium mt-0.5">{{ now()->locale('id')->translatedFormat('l, d F Y') }}</p>
            </div>
            <div class="flex items-center gap-1.5 px-3 py-1.5 bg-slate-100 rounded-xl">
                <span class="text-[10px] font-black text-brand">{{ $orders->where('status', 'pending')->count() }}</span>
                <span class="text-[10px] font-bold text-slate-400">Pending</span>
                <span class="w-px h-3 bg-slate-200 mx-0.5"></span>
                <span class="text-[10px] font-black text-emerald-600">{{ $orders->where('status', 'working')->count() }}</span>
                <span class="text-[10px] font-bold text-slate-400">Aktif</span>
            </div>
        </div>

        {{-- ===== BALANCE CARD ===== --}}
        <div class="relative rounded-[24px] overflow-hidden text-white p-6 shadow-xl" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <div class="relative z-10">
                <p class="text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1">Pendapatan Tersedia</p>
                <h3 class="text-3xl font-black tracking-tight">Rp {{ number_format($balance, 0, ',', '.') }}</h3>
                <a href="{{ route('cleaner.withdrawals.index') }}"
                   class="mt-4 inline-flex items-center gap-2 px-5 py-2.5 bg-brand text-white text-[11px] font-black uppercase tracking-widest rounded-xl shadow-lg shadow-brand/30 active:scale-95 transition-all">
                    <i class="fas fa-wallet"></i> Cairkan Dana
                </a>
            </div>
            <div class="absolute top-0 right-0 w-40 h-40 bg-emerald-500/10 rounded-full blur-3xl -translate-y-10 translate-x-10"></div>
            <div class="absolute bottom-0 right-12 w-20 h-20 bg-brand/10 rounded-full blur-2xl"></div>
            <div class="absolute top-5 right-5 flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/20 border border-emerald-500/30 rounded-full">
                <i class="fas fa-broom text-emerald-400 text-[8px]"></i>
                <span class="text-[8px] font-black text-emerald-400 uppercase tracking-widest">Pro Cleaner</span>
            </div>
        </div>

        {{-- ===== BANK INFO CARD ===== --}}
        <details class="bg-white rounded-[20px] border border-slate-100 shadow-sm overflow-hidden">
            <summary class="flex items-center justify-between px-5 py-4 cursor-pointer select-none list-none">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 bg-slate-100 rounded-xl flex items-center justify-center text-brand">
                        <i class="fas fa-university text-sm"></i>
                    </div>
                    <div>
                        <div class="text-sm font-extrabold text-slate-900 leading-tight">Info Rekening</div>
                        @php $cleaner = auth()->user()->cleaner; @endphp
                        <div class="text-[10px] text-slate-400 font-bold">
                            {{ $cleaner->bank_name ?: 'Belum diisi' }} {{ $cleaner->account_number ? '• '.$cleaner->account_number : '' }}
                        </div>
                    </div>
                </div>
                <i class="fas fa-chevron-down text-slate-300 text-xs transition-transform duration-200"></i>
            </summary>
            <div class="px-5 pb-5 border-t border-slate-50">
                <form action="{{ route('cleaner.bank-info.update') }}" method="POST" class="space-y-3 pt-4">
                    @csrf
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
                    <button type="submit" class="btn btn-primary w-full !h-12">Simpan Data</button>
                </form>
            </div>
        </details>

        {{-- ===== SUCCESS FLASH ===== --}}
        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-2xl flex items-center gap-3 text-xs font-bold">
                <i class="fas fa-check-circle text-lg"></i>
                {{ $message }}
            </div>
        @endif

        {{-- ===== TASK LIST ===== --}}
        <div class="space-y-3">
            <h3 class="text-[10px] font-black text-slate-400 uppercase tracking-widest px-1">Daftar Tugas</h3>

            @forelse($orders as $order)
                @php
                    $rental = optional($order->user->tenant)->rentals ? $order->user->tenant->rentals->first() : null;
                    $statusMap = [
                        'pending'   => ['icon' => 'fa-clock',       'color' => 'bg-amber-50 text-amber-600 border-amber-100',    'dot' => 'bg-amber-400',  'label' => 'Menunggu'],
                        'approved'  => ['icon' => 'fa-thumbs-up',   'color' => 'bg-blue-50 text-blue-600 border-blue-100',       'dot' => 'bg-blue-400',   'label' => 'Diterima'],
                        'working'   => ['icon' => 'fa-broom',       'color' => 'bg-indigo-50 text-indigo-600 border-indigo-100', 'dot' => 'bg-indigo-400', 'label' => 'Dikerjakan'],
                        'done'      => ['icon' => 'fa-check-double','color' => 'bg-emerald-50 text-emerald-600 border-emerald-100','dot'=>'bg-emerald-400', 'label' => 'Selesai'],
                        'cancelled' => ['icon' => 'fa-times',       'color' => 'bg-rose-50 text-rose-500 border-rose-100',       'dot' => 'bg-rose-300',   'label' => 'Dibatalkan'],
                    ];
                    $curr = $statusMap[$order->status] ?? ['icon' => 'fa-question', 'color' => 'bg-slate-50 text-slate-400 border-slate-100', 'dot' => 'bg-slate-300', 'label' => $order->status];
                    $pStatus = $order->payment_status ?? 'unpaid';
                    $pBadge = $pStatus === 'paid' ? 'bg-emerald-50 text-emerald-600 border-emerald-100' : 'bg-amber-50 text-amber-600 border-amber-100';
                    $pLabel = $pStatus === 'paid' ? 'Lunas' : 'Belum Bayar';
                @endphp

                <div class="bg-white rounded-[20px] border border-slate-100 shadow-sm overflow-hidden">
                    {{-- Header --}}
                    <div class="flex items-center justify-between px-5 pt-5 pb-3">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-lg leading-none">
                                {{ $rental && $rental->room ? $rental->room->room_number : '?' }}
                            </div>
                            <div>
                                <div class="text-sm font-extrabold text-slate-900 leading-tight">{{ $order->user->name }}</div>
                                <div class="text-[10px] font-bold text-brand uppercase tracking-widest">
                                    <i class="fas fa-calendar-clock mr-1 opacity-60"></i>
                                    {{ $order->scheduled_at->format('d M Y, H:i') }}
                                </div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-base font-black text-slate-900">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            <span class="inline-flex px-2.5 py-0.5 text-[9px] font-black rounded-full border {{ $pBadge }}">{{ $pLabel }}</span>
                        </div>
                    </div>

                    {{-- Package + Status --}}
                    <div class="flex items-center gap-2 px-5 pb-3 flex-wrap">
                        <span class="px-3 py-1 bg-slate-900 text-white text-[10px] font-black rounded-full uppercase tracking-wider">
                            {{ $order->package->name }}
                        </span>
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 text-[10px] font-black rounded-full border {{ $curr['color'] }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $curr['dot'] }}"></span>
                            {{ $curr['label'] }}
                        </span>
                    </div>

                    {{-- Action: Status Dropdown --}}
                    <div class="border-t border-slate-50 px-4 py-3 bg-slate-50/50">
                        <form action="{{ route('cleaner.orders.status', $order->id) }}" method="POST" class="flex items-center gap-3">
                            @csrf
                            <label class="text-[9px] font-black text-slate-400 uppercase tracking-widest whitespace-nowrap">Update</label>
                            <select name="status" onchange="this.form.submit()"
                                    class="flex-1 !text-[11px] !h-10 !py-0 !px-3 !bg-white border-slate-200 !rounded-xl focus:ring-brand cursor-pointer font-bold text-slate-700 uppercase tracking-wider">
                                @foreach($statusMap as $val => $cfg)
                                    <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>{{ $cfg['label'] }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>
                </div>
            @empty
                <div class="py-20 text-center bg-slate-50/50 rounded-[20px] border-2 border-dashed border-slate-100">
                    <div class="w-16 h-16 bg-slate-100 rounded-3xl flex items-center justify-center text-2xl text-slate-300 mx-auto mb-4">
                        <i class="fas fa-broom"></i>
                    </div>
                    <p class="text-xs font-bold text-slate-400">Belum ada tugas pembersihan.</p>
                    <p class="text-[10px] text-slate-300 mt-1">Tugas baru akan tampil di sini.</p>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>

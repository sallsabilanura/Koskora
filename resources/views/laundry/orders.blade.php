<x-app-layout>
    @section('header_title', 'Laundry Terminal')

    <div class="space-y-8 md:space-y-12">
        <!-- Dashboard Header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="space-y-2">
                <h2 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight italic">Cucian Masuk</h2>
                <p class="text-sm font-medium text-slate-400">Pusat kendali operasional laundry partner KosKora.</p>
            </div>
            @if($orders->count() > 0)
                <div class="px-5 py-2.5 bg-blue-50 text-blue-600 rounded-2xl border border-blue-100 flex items-center space-x-3">
                    <span class="w-2 h-2 bg-blue-500 rounded-full animate-ping"></span>
                    <span class="text-[10px] font-black uppercase tracking-widest">{{ $orders->count() }} Total Pesanan Aktif</span>
                </div>
            @endif
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 text-emerald-700 rounded-[1.5rem] flex items-center shadow-lg shadow-emerald-100/50 animate-fade-in">
                <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24"><path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/></svg>
                <span class="text-sm font-bold">{{ $message }}</span>
            </div>
        @endif

        <!-- List Content -->
        <div class="space-y-6">
            <!-- Desktop Layout -->
            <div class="hidden lg:block bg-white rounded-[2.5rem] border border-slate-100 overflow-hidden shadow-2xl shadow-slate-200/40">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Penghuni & Unit</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Rincian Cucian</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Total Tagihan</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Pembayaran</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-center">Update</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-8 py-7">
                                <div class="flex items-center space-x-4">
                                    <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 font-black text-xs uppercase group-hover:bg-blue-600 group-hover:text-white transition-all">
                                        {{ substr($order->user->name, 0, 2) }}
                                    </div>
                                    <div>
                                        <div class="font-black text-slate-800 leading-none">{{ $order->user->name }}</div>
                                        @php $rental = $order->user->tenant ? $order->user->tenant->rentals->first() : null; @endphp
                                        <div class="text-[10px] font-bold text-blue-600 tracking-widest mt-1 uppercase">Kamar {{ $rental && $rental->room ? $rental->room->room_number : 'Umum' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-8 py-7">
                                <div class="space-y-1.5">
                                    @foreach($order->items as $item)
                                        <div class="flex items-center text-xs font-semibold text-slate-600">
                                            <span class="w-5 h-5 bg-slate-100 rounded-md flex items-center justify-center text-[10px] font-black text-slate-400 mr-2">{{ $item['qty'] }}</span>
                                            {{ $item['item'] }}
                                        </div>
                                    @endforeach
                                </div>
                                @if($order->notes)
                                    <div class="mt-3 text-[9px] font-black text-amber-600 bg-amber-50 px-2 py-1 rounded-lg inline-flex items-center">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                                        {{ $order->notes }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-7">
                                <div class="text-sm font-black text-slate-800 italic">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-8 py-7">
                                <div class="flex flex-col space-y-1.5">
                                    @if($order->payment_status == 'unpaid')
                                        <div class="px-3 py-1 bg-rose-50 text-rose-600 text-[8px] font-black rounded-full uppercase tracking-[0.2em] border border-rose-100 w-fit">UNPAID</div>
                                    @elseif($order->payment_status == 'pending')
                                        <div class="px-3 py-1 bg-amber-50 text-amber-600 text-[8px] font-black rounded-full uppercase tracking-[0.2em] border border-amber-100 w-fit animate-pulse">PENDING</div>
                                        @if($order->payment_proof)
                                            <div class="flex items-center space-x-2">
                                                <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-[9px] font-black text-blue-600 underline uppercase tracking-widest">Bukti Transfer</a>
                                                <form action="{{ route('laundry.orders.verify-payment', $order->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="p-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition-all shadow-md">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        @endif
                                    @else
                                        <div class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[8px] font-black rounded-full uppercase tracking-[0.2em] border border-emerald-100 w-fit">PAID</div>
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-7">
                                @php
                                    $statusConfig = [
                                        'pending' => ['bg' => 'bg-amber-50', 'text' => 'text-amber-600', 'label' => 'Pickup'],
                                        'picked_up' => ['bg' => 'bg-blue-50', 'text' => 'text-blue-600', 'label' => 'Dijemput'],
                                        'in_progress' => ['bg' => 'bg-indigo-50', 'text' => 'text-indigo-600', 'label' => 'Proses'],
                                        'ready' => ['bg' => 'bg-emerald-50', 'text' => 'text-emerald-600', 'label' => 'Siap'],
                                        'delivered' => ['bg' => 'bg-slate-50', 'text' => 'text-slate-500', 'label' => 'Diantar'],
                                        'done' => ['bg' => 'bg-slate-100', 'text' => 'text-slate-400', 'label' => 'Selesai'],
                                    ];
                                    $current = $statusConfig[$order->status] ?? $statusConfig['pending'];
                                @endphp
                                <span class="px-3 py-1 {{ $current['bg'] }} {{ $current['text'] }} text-[9px] font-black rounded-full uppercase tracking-widest italic border border-current opacity-80">
                                    {{ $current['label'] }}
                                </span>
                            </td>
                            <td class="px-8 py-7">
                                <form action="{{ route('laundry.orders.status', $order->id) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="w-full bg-slate-50 border-none rounded-xl text-[10px] font-black uppercase tracking-widest py-2 px-3 focus:ring-2 focus:ring-blue-600 cursor-pointer shadow-inner">
                                        @foreach($statusConfig as $val => $cfg)
                                            <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>{{ $cfg['label'] }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="px-8 py-24 text-center">
                                <div class="flex flex-col items-center opacity-20">
                                    <svg class="w-16 h-16 text-slate-800 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                    <span class="text-sm font-black uppercase tracking-widest italic">Belum ada cucian masuk</span>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Mobile Layout -->
            <div class="lg:hidden space-y-6 pb-20">
                @foreach($orders as $order)
                    <div class="bg-white rounded-[2rem] p-6 shadow-xl shadow-slate-200/50 border border-slate-50 space-y-5 animate-slide-up">
                        <!-- User Header -->
                        <div class="flex items-center justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="w-10 h-10 rounded-2xl bg-slate-900 text-white flex items-center justify-center font-black text-xs uppercase shadow-lg shadow-slate-200">
                                    {{ substr($order->user->name, 0, 2) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="font-black text-slate-800 text-sm truncate uppercase tracking-tight">{{ $order->user->name }}</div>
                                    @php $rental = $order->user->tenant ? $order->user->tenant->rentals->first() : null; @endphp
                                    <div class="text-[9px] font-black text-blue-600 uppercase tracking-widest tracking-tighter">Room {{ $rental && $rental->room ? $rental->room->room_number : 'Umum' }}</div>
                                </div>
                            </div>
                            @php $current = $statusConfig[$order->status] ?? $statusConfig['pending']; @endphp
                            <span class="px-2.5 py-0.5 {{ $current['bg'] }} {{ $current['text'] }} text-[8px] font-black rounded-full uppercase tracking-widest italic border border-current">
                                {{ $current['label'] }}
                            </span>
                        </div>

                        <!-- Items Grid -->
                        <div class="bg-slate-50 p-4 rounded-2xl grid grid-cols-2 gap-3">
                            <div class="col-span-2 flex flex-wrap gap-2 mb-1">
                                @foreach($order->items as $item)
                                    <span class="px-2 py-1 bg-white rounded-lg text-[10px] font-bold text-slate-600 border border-slate-100">
                                        <b class="text-slate-900">{{ $item['qty'] }}x</b> {{ $item['item'] }}
                                    </span>
                                @endforeach
                            </div>
                            <div class="pt-2 border-t border-slate-200/50">
                                <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Total Harga</div>
                                <div class="text-xs font-black text-slate-900 italic tracking-tighter">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            </div>
                            <div class="pt-2 border-t border-slate-200/50 text-right">
                                <div class="text-[8px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Payment</div>
                                <div class="text-[9px] font-black {{ $order->payment_status == 'paid' ? 'text-emerald-500' : 'text-rose-500' }} uppercase tracking-widest">{{ $order->payment_status }}</div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-between pt-1">
                            <div>
                                @if($order->payment_status == 'pending' && $order->payment_proof)
                                    <form action="{{ route('laundry.orders.verify-payment', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white text-[9px] font-black rounded-xl shadow-lg shadow-blue-100 uppercase tracking-widest active:scale-95 btn-touch">Verifikasi Bayar</button>
                                    </form>
                                @endif
                            </div>
                            <form action="{{ route('laundry.orders.status', $order->id) }}" method="POST" class="flex-1 max-w-[120px] ml-4 text-right">
                                @csrf
                                <select name="status" onchange="this.form.submit()" class="w-full bg-slate-900 text-white border-none rounded-xl text-[9px] font-black uppercase tracking-widest py-2 px-3 shadow-xl shadow-slate-200 transition-all active:scale-95 cursor-pointer">
                                    @foreach($statusConfig as $val => $cfg)
                                        <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>Ke {{ $cfg['label'] }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <style>
        .animate-fade-in { animation: fadeIn 0.8s ease-out; }
        .animate-slide-up { animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    </style>
</x-app-layout>

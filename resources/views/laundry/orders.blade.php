<x-app-layout>
    @section('header_title', 'Daftar Pesanan Laundry')

    <div class="space-y-8">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Cucian Masuk</h2>
                <p class="text-slate-500 font-medium">Pantau dan perbarui status cucian para penghuni kos.</p>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] border border-slate-200 overflow-hidden shadow-sm">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi Penjemputan</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Cucian</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Harga</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Bayar</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Cucian</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($orders as $order)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-8 py-6">
                                <div class="font-black text-slate-800">{{ $order->user->name }}</div>
                                <div class="text-xs font-bold text-blue-600 italic">
                                    @php $rental = $order->user->tenant ? $order->user->tenant->rentals->first() : null; @endphp
                                    @if($rental && $rental->room)
                                        Kamar {{ $rental->room->room_number }}
                                    @else
                                        Umum
                                    @endif
                                </div>
                                <div class="text-[10px] text-slate-400 mt-1">{{ $order->created_at->format('d M Y, H:i') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="space-y-1">
                                    @foreach($order->items as $item)
                                        <div class="text-xs font-medium text-slate-600">
                                            <span class="font-black text-slate-800">{{ $item['qty'] }}x</span> {{ $item['item'] }}
                                        </div>
                                    @endforeach
                                </div>
                                @if($order->notes)
                                    <div class="mt-2 text-[10px] font-bold text-amber-600 py-1 px-2 bg-amber-50 rounded-lg inline-block">
                                        Note: {{ $order->notes }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-8 py-6">
                                <div class="text-sm font-black text-slate-800 italic">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-8 py-6">
                                <div class="flex flex-col space-y-2">
                                    @if($order->payment_status == 'unpaid')
                                        <span class="px-3 py-1 bg-rose-100 text-rose-700 text-[9px] font-black rounded-full uppercase tracking-widest text-center">Belum Bayar</span>
                                    @elseif($order->payment_status == 'pending')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 text-[9px] font-black rounded-full uppercase tracking-widest text-center">Menunggu Verifikasi</span>
                                        @if($order->payment_proof)
                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-[9px] font-black text-blue-600 text-center underline italic hover:text-blue-800 transition-all">
                                                Lihat Bukti →
                                            </a>
                                            <form action="{{ route('laundry.orders.verify-payment', $order->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="w-full py-1.5 bg-blue-600 text-white text-[9px] font-black rounded-lg hover:bg-blue-700 transition-all shadow-md shadow-blue-100">
                                                    Verifikasi Sekarang
                                                </button>
                                            </form>
                                        @endif
                                    @else
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 text-[9px] font-black rounded-full uppercase tracking-widest text-center">Sudah Bayar</span>
                                        @if($order->payment_proof)
                                            <a href="{{ asset('storage/' . $order->payment_proof) }}" target="_blank" class="text-[9px] font-black text-slate-400 text-center underline italic">
                                                Lihat Bukti
                                            </a>
                                        @endif
                                    @endif
                                </div>
                            </td>
                            <td class="px-8 py-6">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-amber-100 text-amber-700',
                                        'picked_up' => 'bg-blue-100 text-blue-700',
                                        'in_progress' => 'bg-indigo-100 text-indigo-700',
                                        'ready' => 'bg-emerald-100 text-emerald-700',
                                        'delivered' => 'bg-slate-100 text-slate-700',
                                        'done' => 'bg-slate-200 text-slate-400 line-through',
                                    ];
                                    $statusLabels = [
                                        'pending' => 'Penjemputan',
                                        'picked_up' => 'Dijemput',
                                        'in_progress' => 'Dicuci',
                                        'ready' => 'Siap',
                                        'delivered' => 'Diantar',
                                        'done' => 'Selesai',
                                    ];
                                @endphp
                                <span class="px-3 py-1 {{ $statusClasses[$order->status] }} text-[10px] font-black rounded-full uppercase tracking-widest italic">
                                    {{ $statusLabels[$order->status] }}
                                </span>
                            </td>
                            <td class="px-8 py-6 text-center">
                                <form action="{{ route('laundry.orders.status', $order->id) }}" method="POST">
                                    @csrf
                                    <select name="status" onchange="this.form.submit()" class="text-[10px] font-black uppercase tracking-widest border-none bg-slate-100 rounded-xl focus:ring-blue-500 py-1.5 pl-3 pr-8 cursor-pointer hover:bg-slate-200 transition-all">
                                        @foreach($statusLabels as $val => $label)
                                            <option value="{{ $val }}" {{ $order->status == $val ? 'selected' : '' }}>
                                                {{ $label }}
                                            </option>
                                        @endforeach
                                    </select>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center text-slate-300 font-bold italic">Belum ada pesanan masuk.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>

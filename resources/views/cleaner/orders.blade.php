<x-app-layout>
    @section('header_title', 'Daftar Tugas Bebersih')

    <div class="space-y-10 pb-20">
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-8 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center space-x-6 overflow-hidden relative group">
                <div class="w-16 h-16 bg-blue-50 text-blue-600 rounded-3xl flex items-center justify-center flex-shrink-0 group-hover:bg-blue-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 2m6-2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Tugas Mendatang</div>
                    <div class="text-3xl font-black text-slate-800 tracking-tighter">{{ $orders->where('status', 'pending')->count() }}</div>
                </div>
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-blue-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            </div>

            <div class="p-8 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center space-x-6 overflow-hidden relative group">
                <div class="w-16 h-16 bg-rose-50 text-rose-600 rounded-3xl flex items-center justify-center flex-shrink-0 group-hover:bg-rose-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Sedang Dikerjakan</div>
                    <div class="text-3xl font-black text-slate-800 tracking-tighter">{{ $orders->where('status', 'working')->count() }}</div>
                </div>
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-rose-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            </div>

            <div class="p-8 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm flex items-center space-x-6 overflow-hidden relative group">
                <div class="w-16 h-16 bg-emerald-50 text-emerald-600 rounded-3xl flex items-center justify-center flex-shrink-0 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-500">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
                <div>
                    <div class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Tugas Selesai</div>
                    <div class="text-3xl font-black text-slate-800 tracking-tighter">{{ $orders->where('status', 'done')->count() }}</div>
                </div>
                <div class="absolute -right-4 -top-4 w-20 h-20 bg-emerald-50 rounded-full opacity-50 group-hover:scale-150 transition-transform duration-700"></div>
            </div>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                {{ $message }}
            </div>
        @endif

        <!-- Order List Table -->
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-xl shadow-slate-200/50 overflow-hidden">
            <div class="p-8 border-b border-slate-100 flex items-center justify-between">
                <h3 class="text-xl font-black text-slate-800 tracking-tight italic">Tugas Pembersihan Anda</h3>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Update status Tugas secara berkala</span>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">No. Kamar / Penghuni</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Paket</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jadwal Datang</th>
                            <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Update Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($orders as $order)
                            <tr class="hover:bg-slate-50/50 transition-all group">
                                <td class="px-8 py-6">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center font-black text-lg group-hover:bg-blue-600 group-hover:text-white transition-all">
                                            {{ optional($order->user->tenant->rentals->first())->room->room_number ?? '?' }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-black text-slate-800">{{ $order->user->name }}</div>
                                            <div class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Penghuni Kos</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-6">
                                    <span class="px-3 py-1 {{ strtolower($order->package->name) == 'max' ? 'bg-rose-100 text-rose-600' : 'bg-blue-100 text-blue-600' }} text-[10px] font-black rounded-full uppercase tracking-widest">{{ $order->package->name }}</span>
                                    <p class="text-[10px] text-slate-400 italic mt-2 max-w-[200px] line-clamp-1">"{{ $order->notes ?: 'Tdk ada catatan' }}"</p>
                                </td>
                                <td class="px-8 py-6">
                                    <div class="text-sm font-black text-slate-800">{{ $order->scheduled_at->format('d M, Y') }}</div>
                                    <div class="text-[10px] font-bold text-rose-500">{{ $order->scheduled_at->format('H:i') }} WIB</div>
                                </td>
                                <td class="px-8 py-6">
                                    <form action="{{ route('cleaner.orders.status', $order->id) }}" method="POST" class="flex items-center justify-center space-x-2">
                                        @csrf
                                        <select name="status" onchange="this.form.submit()" class="px-4 py-2 bg-slate-100 border-none rounded-xl text-[10px] font-black uppercase tracking-widest focus:ring-blue-500 appearance-none pr-8 cursor-pointer">
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
                                <td colspan="4" class="px-8 py-24 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-16 h-16 text-slate-100 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path></svg>
                                        <span class="text-slate-300 font-bold italic tracking-tight">Belum ada tugas yang masuk untuk Anda.</span>
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

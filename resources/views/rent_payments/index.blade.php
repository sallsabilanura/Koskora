<x-app-layout>
    @section('header_title', 'Rent Payments')

    <div class="space-y-8 animate-fade-in" x-data="{ 
        rejectModal: false, 
        rejectId: null, 
        rejectAction: '',
        openReject(id, action) {
            this.rejectId = id;
            this.rejectAction = action;
            this.rejectModal = true;
        }
    }">
        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div class="space-y-1">
                <h2 class="text-xl font-extrabold text-slate-900 tracking-tight">Manajemen Pembayaran</h2>
                <p class="text-slate-500 font-medium text-sm">Verifikasi bukti bayar dan pantau arus kas masuk Anda.</p>
            </div>
            <a href="{{ route('rent-payments.create') }}" class="btn btn-primary">
                <i class="fas fa-plus-circle text-sm"></i>
                Catat Pembayaran
            </a>
        </div>

        @if ($message = Session::get('success'))
            <div class="p-4 bg-emerald-50 border border-emerald-100 rounded-2xl flex items-center gap-3 text-emerald-600 font-bold text-sm">
                <i class="fas fa-check-circle"></i>
                {{ $message }}
            </div>
        @endif

        <div class="filter-bar">
            <form action="{{ route('rent-payments.index') }}" method="GET">
                <div style="display:flex; gap:0.625rem; align-items:center; flex-wrap:wrap;">
                    {{-- Search --}}
                    <div style="position:relative; flex:1; min-width:180px;">
                        <i class="fas fa-search" style="position:absolute; left:0.875rem; top:50%; transform:translateY(-50%); color:#94a3b8; font-size:0.8rem; pointer-events:none;"></i>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari kamar atau metode..."
                               style="padding-left:2.5rem; width:100%; margin:0;">
                    </div>
                    {{-- Tenant --}}
                    <select name="tenant_id" onchange="this.form.submit()" style="width:160px; flex-shrink:0; margin:0;">
                        <option value="">Semua Penyewa</option>
                        @foreach($tenants as $t)
                            <option value="{{ $t->id }}" {{ request('tenant_id') == $t->id ? 'selected' : '' }}>
                                {{ $t->user->name ?? 'Unknown' }}
                            </option>
                        @endforeach
                    </select>
                    {{-- Status --}}
                    <select name="status" onchange="this.form.submit()" style="width:130px; flex-shrink:0; margin:0;">
                        <option value="">Semua Status</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="unpaid" {{ request('status') == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
                    </select>
                    {{-- Submit --}}
                    <button type="submit" class="btn btn-primary" style="flex-shrink:0; white-space:nowrap;">
                        <i class="fas fa-search" style="font-size:0.75rem;"></i> Filter
                    </button>
                    @if(request()->anyFilled(['search', 'status', 'tenant_id']))
                        <a href="{{ route('rent-payments.index') }}" class="btn btn-ghost" style="flex-shrink:0;" title="Reset filter">
                            <i class="fas fa-undo-alt" style="font-size:0.75rem;"></i>
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- ===== TABLE SECTION ===== --}}
        <div class="table-wrap">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Penyewa & Unit</th>
                        <th>Periode</th>
                        <th class="text-right">Nominal</th>
                        <th class="text-center">Bukti</th>
                        <th class="text-center">Status</th>
                        <th class="text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr class="group">
                            <td data-label="Penyewa">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-brand text-white flex items-center justify-center font-black text-sm shadow-sm">
                                        {{ strtoupper(substr($payment->tenants->user->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="font-extrabold text-slate-900 text-sm">{{ $payment->tenants->user->name ?? 'Unknown' }}</div>
                                        <div class="text-[11px] font-black text-brand uppercase tracking-widest mt-0.5">KAMAR #{{ $payment->room->room_number ?? '-' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td data-label="Periode">
                                <div class="text-sm font-bold text-slate-700">{{ $payment->month }}</div>
                                <div class="text-[11px] font-medium text-slate-400 mt-0.5">{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</div>
                            </td>
                            <td class="text-right" data-label="Nominal">
                                <div class="text-sm font-extrabold text-slate-900">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $payment->method }}</div>
                            </td>
                            <td class="text-center" data-label="Bukti">
                                @if($payment->payment_proof)
                                    <a href="{{ asset('storage/' . $payment->payment_proof) }}" target="_blank" 
                                       class="inline-block p-1 bg-white border border-slate-200 rounded-xl hover:scale-105 transition-transform shadow-sm">
                                        <img src="{{ asset('storage/' . $payment->payment_proof) }}" class="w-10 h-10 object-cover rounded-lg">
                                    </a>
                                @else
                                    <span class="text-[9px] font-black text-slate-300 uppercase tracking-widest">No Proof</span>
                                @endif
                            </td>
                            <td class="text-center" data-label="Status">
                                @php
                                    $badgeCls = [
                                        'paid' => 'badge-green',
                                        'pending' => 'badge-amber',
                                        'unpaid' => 'badge-red'
                                    ][$payment->status] ?? 'badge-gray';
                                @endphp
                                <span class="badge {{ $badgeCls }}">{{ strtoupper($payment->status) }}</span>
                            </td>
                            <td class="text-right" data-label="Aksi">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all">
                                    @if($payment->status == 'pending')
                                        <form action="{{ route('rent-payments.verify', $payment->id) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary !h-9 !px-4 !text-[10px] !rounded-xl !bg-brand">VERIFY</button>
                                        </form>
                                        <button @click="openReject({{ $payment->id }}, '{{ route('rent-payments.reject', $payment->id) }}')" 
                                                class="w-9 h-9 rounded-xl bg-white border border-red-200 flex items-center justify-center text-red-500 hover:bg-red-50 transition-all" title="Reject">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    @endif

                                    <div class="relative" x-data="{ open: false }">
                                        <button @click="open = !open" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand transition-all" title="Force Status">
                                            <i class="fas fa-tools text-xs"></i>
                                        </button>
                                        <div x-show="open" @click.away="open = false" 
                                             class="absolute right-0 mt-2 w-36 bg-white rounded-2xl shadow-xl border border-slate-100 z-50 py-2 overflow-hidden"
                                             style="display: none;"
                                             x-transition:enter="transition ease-out duration-100"
                                             x-transition:enter-start="opacity-0 scale-95"
                                             x-transition:enter-end="opacity-100 scale-100">
                                            <form action="{{ route('rent-payments.force-status', $payment->id) }}" method="POST">
                                                @csrf
                                                <button type="submit" name="status" value="paid" class="w-full text-left px-4 py-2 text-[11px] font-bold text-slate-600 hover:bg-emerald-50 hover:text-emerald-600">SET PAID</button>
                                                <button type="submit" name="status" value="pending" class="w-full text-left px-4 py-2 text-[11px] font-bold text-slate-600 hover:bg-amber-50 hover:text-amber-600">SET PENDING</button>
                                                <button type="submit" name="status" value="unpaid" class="w-full text-left px-4 py-2 text-[11px] font-bold text-slate-600 hover:bg-red-50 hover:text-red-600">SET UNPAID</button>
                                            </form>
                                        </div>
                                    </div>

                                    <a href="{{ route('rent-payments.show', $payment->id) }}" class="w-9 h-9 rounded-xl bg-white border border-slate-200 flex items-center justify-center text-slate-400 hover:text-brand hover:border-brand transition-all" title="Detail">
                                        <i class="fas fa-file-invoice text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <i class="fas fa-receipt"></i>
                                    <p class="text-sm font-black text-slate-400 uppercase tracking-widest mt-2">Belum ada riwayat pembayaran</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ===== PAGINATION ===== --}}
        <div class="flex justify-center pt-4">
            {{ $payments->appends(request()->query())->links() }}
        </div>

        {{-- ===== REJECTION MODAL ===== --}}
        <div x-show="rejectModal" 
             class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-slate-900/40 backdrop-blur-sm"
             style="display: none;"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            
            <div @click.away="rejectModal = false" class="bg-white w-full max-w-md rounded-[2rem] shadow-2xl overflow-hidden">
                <div class="p-8">
                    <div class="w-14 h-14 bg-red-50 text-red-500 rounded-2xl flex items-center justify-center mb-6">
                        <i class="fas fa-exclamation-triangle text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-extrabold text-slate-900 tracking-tight">Tolak Pembayaran?</h3>
                    <p class="text-sm font-medium text-slate-500 mt-2 leading-relaxed">Berikan alasan penolakan agar penyewa dapat segera memperbaikinya.</p>
                    
                    <form :action="rejectAction" method="POST" class="mt-8 space-y-6">
                        @csrf
                        <div class="space-y-2">
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">Alasan Penolakan</label>
                            <textarea name="rejection_reason" required rows="3"
                                      placeholder="Contoh: Bukti transfer tidak valid..."></textarea>
                        </div>
                        
                        <div class="flex gap-3">
                            <button type="button" @click="rejectModal = false" class="btn btn-ghost flex-1 !h-14">BATAL</button>
                            <button type="submit" class="btn btn-primary flex-1 !h-14 !bg-red-500 hover:!bg-red-600 shadow-red-200">KONFIRMASI TOLAK</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

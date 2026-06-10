<x-app-layout>
    @section('header_title', 'Security Scanner')

    <div class="max-w-xl mx-auto py-4 space-y-6 animate-fade-in">
        {{-- ===== SCANNER CONTROL ===== --}}
        <div class="stat-card !p-8 text-center space-y-6">
            <div class="space-y-2">
                <div class="w-16 h-16 bg-brand-light text-brand rounded-2xl flex items-center justify-center mx-auto shadow-inner mb-4">
                    <i class="fas fa-qrcode text-2xl"></i>
                </div>
                <h3 class="text-xl font-bold text-slate-900 tracking-tight">Verifikasi Akses</h3>
                <p class="text-slate-500 text-[13px]">Scan atau masukkan Receipt ID untuk validasi tiket penghuni.</p>
            </div>

            <form action="{{ route('security.scan') }}" method="GET" class="space-y-4" id="scan-form">
                <div class="relative">
                    <input type="text" name="ticket_id" id="ticket_id_input" value="{{ request('ticket_id') }}" 
                        class="w-full !h-14 !text-center !text-2xl !font-bold !tracking-[0.4em] !text-slate-800 !bg-slate-50 focus:!bg-white border-2 border-slate-100 rounded-2xl" 
                        placeholder="00000" autofocus>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <button type="button" onclick="startScanner()" class="w-full py-4 bg-white border border-slate-200 text-slate-700 rounded-2xl font-black text-[11px] uppercase tracking-widest hover:border-brand hover:text-brand transition-all flex items-center justify-center gap-3">
                        <i class="fas fa-camera"></i> Buka Kamera
                    </button>
                    <button type="submit" class="w-full py-4 bg-slate-900 text-white rounded-2xl font-black text-[11px] uppercase tracking-widest hover:bg-brand transition-all flex items-center justify-center gap-3 shadow-xl shadow-slate-200">
                        <i class="fas fa-search"></i> Cek Manual
                    </button>
                </div>
            </form>

            {{-- Scanner Viewport --}}
            <div id="reader" class="hidden rounded-2xl overflow-hidden border-2 border-dashed border-slate-200 bg-slate-50 p-4"></div>
        </div>

        @if(request('ticket_id') && $payment)
            @php
                $isPaid = ($currentMonthStatus === 'paid');
                $statusColor = $isPaid ? ($isExpiredTicket ? 'amber' : 'emerald') : 'rose';
            @endphp

            {{-- RESULT CARD --}}
            <div class="stat-card !border-{{ $statusColor }}-200 !bg-{{ $statusColor }}-50/30 overflow-hidden animate-modal-up border-2">
                <div class="flex flex-col items-center text-center space-y-6">
                    {{-- Status Icon --}}
                    <div class="w-20 h-20 bg-{{ $statusColor }}-500 text-white rounded-full flex items-center justify-center shadow-xl shadow-{{ $statusColor }}-200 relative">
                        <i class="fas {{ $isPaid ? 'fa-check' : 'fa-times' }} text-3xl"></i>
                        @if($isExpiredTicket && $isPaid)
                            <div class="absolute -top-1 -right-1 w-8 h-8 bg-white text-amber-500 rounded-full flex items-center justify-center border-4 border-amber-500 shadow-sm">
                                <i class="fas fa-clock text-[10px]"></i>
                            </div>
                        @endif
                    </div>
                    
                    <div class="space-y-1">
                        <h4 class="text-2xl font-black text-slate-900 tracking-tight">{{ $payment->tenants->user->name }}</h4>
                        <p class="text-sm font-black text-brand uppercase tracking-tighter">Room {{ $payment->room->room_number }}</p>
                    </div>

                    {{-- Logic Messaging --}}
                    <div class="w-full bg-white/60 rounded-2xl p-6 space-y-4 shadow-sm border border-{{ $statusColor }}-100">
                        <div class="flex items-center justify-between">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status Verifikasi</span>
                            @if($isPaid)
                                <span class="px-3 py-1 bg-emerald-500 text-white text-[9px] font-black rounded-lg uppercase tracking-widest">Akses Diizinkan</span>
                            @else
                                <span class="px-3 py-1 bg-rose-500 text-white text-[9px] font-black rounded-lg uppercase tracking-widest">Akses Ditolak</span>
                            @endif
                        </div>

                        <div class="space-y-3 pt-4 border-t border-slate-100">
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="font-bold text-slate-500">Tiket di-scan:</span>
                                <span class="font-black {{ $isExpiredTicket ? 'text-amber-600' : 'text-slate-800' }}">
                                    {{ $payment->month }} ({{ $payment->status == 'paid' ? 'LUNAS' : 'PENDING' }})
                                </span>
                            </div>
                            <div class="flex justify-between items-center text-[11px]">
                                <span class="font-bold text-slate-500">Bulan Berjalan ({{ now()->format('M Y') }}):</span>
                                <span class="font-black {{ $isPaid ? 'text-emerald-600' : 'text-rose-600' }}">
                                    {{ $isPaid ? 'SUDAH LUNAS' : 'BELUM BAYAR' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    @if($isPaid)
                        <div class="w-full py-5 bg-emerald-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-emerald-200">
                            Welcome, {{ explode(' ', $payment->tenants->user->name)[0] }}!
                        </div>
                    @else
                        <div class="w-full py-5 bg-rose-600 text-white rounded-2xl font-black text-xs uppercase tracking-[0.2em] shadow-lg shadow-rose-200">
                            Mohon Selesaikan Pembayaran
                        </div>
                        <p class="text-[10px] font-bold text-rose-500 uppercase italic">Penyewa belum membayar sewa untuk periode {{ now()->format('F Y') }}</p>
                    @endif
                </div>
            </div>
        @elseif(request('ticket_id'))
             <div class="stat-card !border-rose-200 !bg-rose-50/30 overflow-hidden animate-modal-up text-center py-10">
                <i class="fas fa-search text-rose-300 text-4xl mb-4"></i>
                <h4 class="text-xl font-black text-slate-900 uppercase">Data Tidak Ditemukan</h4>
                <p class="text-xs font-bold text-slate-400 mt-2">Pastikan ID Tiket #{{ request('ticket_id') }} benar.</p>
             </div>
        @endif
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        function onScanSuccess(decodedText) {
            let ticketId = decodedText;
            // Handle full URL if scanned from QR directly
            if (decodedText.includes('ticket_id=')) {
                const urlParams = new URLSearchParams(decodedText.split('?')[1]);
                ticketId = urlParams.get('ticket_id');
            } else if (decodedText.includes('/')) {
                const parts = decodedText.split('/');
                ticketId = parts[parts.length - 1];
            }

            document.getElementById('ticket_id_input').value = ticketId;
            document.getElementById('scan-form').submit();
            if (html5QrcodeScanner) {
                html5QrcodeScanner.clear();
            }
        }

        let html5QrcodeScanner = null;
        function startScanner() {
            const reader = document.getElementById('reader');
            reader.classList.remove('hidden');
            if (!html5QrcodeScanner) {
                html5QrcodeScanner = new Html5QrcodeScanner("reader", { 
                    fps: 10, 
                    qrbox: {width: 250, height: 250},
                    aspectRatio: 1.0
                });
            }
            html5QrcodeScanner.render(onScanSuccess);
        }
    </script>
</x-app-layout>

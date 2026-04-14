<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tiket Masuk Digital - KosKora</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .ticket-card {
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            box-shadow: 0 50px 100px -20px rgba(0, 0, 0, 0.25), 0 30px 60px -30px rgba(0, 0, 0, 0.3);
        }
        .ticket-border {
            border-style: dashed;
            border-width: 2px;
            border-color: rgba(255, 255, 255, 0.1);
        }
        .glass {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        .punch-hole {
            width: 30px;
            height: 30px;
            background: #f8fafc; /* Match body bg */
            border-radius: 50%;
            position: absolute;
            z-index: 20;
        }
    </style>
</head>
<body class="bg-slate-50 min-h-screen flex items-center justify-center p-6 sm:p-12">

    <div class="relative w-full max-w-md">
        <!-- Ticket Container -->
        <div class="ticket-card rounded-[2.5rem] overflow-hidden relative text-white">
            
            <!-- Punch Holes (Decorative) -->
            <div class="punch-hole -left-[15px] top-1/2 -translate-y-1/2 shadow-inner"></div>
            <div class="punch-hole -right-[15px] top-1/2 -translate-y-1/2 shadow-inner"></div>

            <!-- Header -->
            <div class="p-8 pt-10 text-center border-b-4 border-rose-600 bg-black/20">
                <div class="flex justify-center mb-6">
                    <img src="{{ asset('koskora.png') }}" alt="Logo" class="h-10 w-auto">
                </div>
                <h1 class="text-xs font-black tracking-[0.3em] uppercase opacity-60">PASS DIGITAL MASUK</h1>
                <div class="mt-2 text-2xl font-black italic tracking-tighter">PREMIUM TENANT</div>
            </div>

            <!-- Body / QR Code -->
            <div class="p-8 pb-10 flex flex-col items-center">
                <div class="p-4 bg-white rounded-3xl mb-8 shadow-2xl relative">
                    <div class="absolute -top-2 -right-2 w-6 h-6 bg-rose-500 rounded-full border-4 border-[#0f172a]"></div>
                    @php
                        $qrData = route('rent-payments.ticket', $rentPayment->id);
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=" . urlencode($qrData);
                    @endphp
                    <img src="{{ $qrUrl }}" alt="QR Verification" class="w-48 h-48">
                </div>
                
                <div class="space-y-1 text-center">
                    <p class="text-[10px] font-black uppercase tracking-widest opacity-40">Status Pembayaran</p>
                    <div class="px-4 py-1 bg-emerald-500/20 text-emerald-400 text-xs font-black uppercase tracking-widest rounded-full border border-emerald-500/30">
                        VERIFIED / PAID
                    </div>
                </div>
            </div>

            <!-- Footer Details -->
            <div class="p-8 pt-0 grid grid-cols-2 gap-x-8 gap-y-6">
                <div class="space-y-1 border-l-2 border-rose-500 pl-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-40 text-rose-400">Penghuni</p>
                    <p class="text-sm font-black truncate">{{ $rentPayment->tenants->user->name }}</p>
                </div>
                <div class="space-y-1 border-l-2 border-blue-500 pl-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-40 text-blue-400">Nomor Kamar</p>
                    <p class="text-lg font-black leading-none">{{ $rentPayment->room->room_number }}</p>
                </div>
                <div class="space-y-1 border-l-2 border-blue-500 pl-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-40 text-blue-400">Periode</p>
                    <p class="text-sm font-black">{{ $rentPayment->month }}</p>
                </div>
                <div class="space-y-1 border-l-2 border-rose-500 pl-4">
                    <p class="text-[10px] font-bold uppercase tracking-widest opacity-40 text-rose-400">ID Tiket</p>
                    <p class="text-[10px] font-mono opacity-60">#{{ str_pad($rentPayment->id, 8, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>

            <!-- Disclaimer -->
            <div class="p-8 py-6 bg-black/40 text-center">
                <p class="text-[8px] font-medium text-slate-400 uppercase tracking-widest leading-relaxed">
                    Tiket ini berlaku selama periode sewa bulan bersangkutan.<br>
                    Tunjukkan tiket ini kepada petugas keamanan atau scan pada akses pintu digital.
                </p>
            </div>
        </div>

        <!-- Print Action -->
        <div class="mt-8 flex justify-center no-print">
            <button onclick="window.print()" class="px-8 py-3 bg-white text-slate-800 rounded-2xl font-black text-xs shadow-lg hover:shadow-xl transition-all flex items-center space-x-2 border border-slate-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Cetak / Simpan PDF</span>
            </button>
        </div>
    </div>

</body>
</html>

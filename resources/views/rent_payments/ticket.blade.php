<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Digital Receipt & Pass - KosKora</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #f8fafc;
        }
        .receipt-card {
            background: #ffffff;
            position: relative;
            filter: drop-shadow(0 20px 50px rgba(0, 0, 0, 0.05));
        }
        .receipt-card::after {
            content: "";
            position: absolute;
            bottom: -10px;
            left: 0;
            right: 0;
            height: 20px;
            background-image: radial-gradient(circle at 10px -5px, transparent 12px, white 13px);
            background-size: 20px 20px;
        }
        .qr-container {
            background: linear-gradient(135deg, #f1f5f9 0%, #ffffff 100%);
            border: 1px solid #e2e8f0;
        }
        @media print {
            .no-print { display: none; }
            body { background: white; padding: 0; }
            .receipt-card { filter: none; border: 1px solid #e2e8f0; }
        }
        .status-badge {
            letter-spacing: 0.1em;
            text-transform: uppercase;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 sm:p-8">

    <div class="w-full max-w-lg">
        <!-- Main Receipt Card -->
        <div class="receipt-card rounded-t-3xl overflow-hidden">
            
            <!-- Strategic Header -->
            <div class="p-8 pb-4 flex items-center justify-between border-b border-slate-50">
                <div>
                    <img src="{{ asset('koskora.png') }}" alt="KosKora Logo" class="h-8 w-auto mb-2">
                    <h1 class="text-[10px] font-black tracking-widest text-slate-400 uppercase">Official Digital Receipt</h1>
                </div>
                <div class="text-right">
                    <span class="px-3 py-1 bg-emerald-50 text-emerald-600 text-[10px] font-black rounded-full border border-emerald-100 status-badge">
                        Success / Verified
                    </span>
                </div>
            </div>

            <!-- Receipt Content -->
            <div class="p-8">
                <!-- High Priority Info -->
                <div class="flex flex-col items-center mb-8">
                    @php
                        $qrData = route('rent-payments.ticket', $rentPayment->id);
                        $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($qrData);
                    @endphp
                    <div class="qr-container p-6 rounded-[2rem] shadow-inner mb-6 relative">
                        <div class="absolute -top-1 -right-1 w-6 h-6 bg-brand-blue rounded-full border-4 border-white"></div>
                        <img src="{{ $qrUrl }}" alt="QR Code" class="w-40 h-40 opacity-90">
                    </div>
                    <h2 class="text-2xl font-black text-slate-800 tracking-tighter">Premium Entry Pass</h2>
                    <p class="text-xs font-medium text-slate-400 mt-1">Scan at digital lobby or show to staff</p>
                </div>

                <!-- Transaction Details -->
                <div class="space-y-6 pt-6 border-t border-slate-100">
                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Tenant Name</p>
                            <p class="text-sm font-bold text-slate-800">{{ $rentPayment->tenants->user->name }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Unit Number</p>
                            <span class="inline-flex items-center px-2 py-1 bg-slate-100 text-slate-700 text-xs font-black rounded-lg">
                                Room {{ $rentPayment->room->room_number }}
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Billing Period</p>
                            <p class="text-sm font-bold text-slate-800">{{ $rentPayment->month }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Amount Paid</p>
                            <p class="text-sm font-black text-brand-blue">Rp {{ number_format($rentPayment->amount, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-8">
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Transaction ID</p>
                            <p class="text-[10px] font-mono font-bold text-slate-500 italic">#{{ str_pad($rentPayment->id, 10, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1.5">Payment Date</p>
                            <p class="text-sm font-bold text-slate-800">{{ \Carbon\Carbon::parse($rentPayment->payment_date)->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Footer -->
            <div class="p-8 py-6 bg-slate-50 border-t border-slate-100 text-center">
                <div class="flex items-center justify-center space-x-2 mb-3">
                    <svg class="w-3 h-3 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M2.166 4.9L10 .303l7.834 4.597v10.2L10 19.697l-7.834-4.597V4.9zm13.668 1.95L10 3.12 4.166 6.53v8.324L10 17.182l5.834-2.328V6.85zM6.666 9.666L5 11.333 8.333 14.666l6.667-6.666-1.667-1.667L8.333 11.332 6.666 9.666z" clip-rule="evenodd"></path></svg>
                    <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest">Digitally Signed & Verified</span>
                </div>
                <p class="text-[8px] font-medium text-slate-400 leading-relaxed uppercase tracking-tighter">
                    This receipt is system-generated and does not require a physical signature.<br>
                    Authenticity can be verified by scanning the QR code above.
                </p>
            </div>
        </div>

        <!-- Print/Download Actions -->
        <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4 no-print">
            <button onclick="window.print()" class="w-full sm:w-auto px-8 py-3 bg-white text-slate-800 rounded-2xl font-black text-[10px] shadow-sm hover:shadow-md transition-all flex items-center justify-center space-x-2 border border-slate-200 uppercase tracking-widest">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                <span>Print Receipt</span>
            </button>
            <a href="{{ route('dashboard') }}" class="w-full sm:w-auto px-10 py-3 bg-slate-900 text-white rounded-2xl font-black text-[10px] shadow-xl hover:bg-slate-800 transition-all text-center uppercase tracking-widest">
                Back to Dashboard
            </a>
        </div>
    </div>

</body>
</html>

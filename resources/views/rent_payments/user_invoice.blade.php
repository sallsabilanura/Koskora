<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #KSK-{{ str_pad($rentPayment->id, 5, '0', STR_PAD_LEFT) }} — KosKora</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --brand: #4f46e5;
            --brand-light: #eef2ff;
            --brand-dark: #4338ca;
            --paid: #10b981;
            --pending: #f59e0b;
            --unpaid: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f1f5f9;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 2rem 1rem;
            color: #1e293b;
        }

        /* ===== TOP ACTION BAR ===== */
        .top-bar {
            width: 100%;
            max-width: 640px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
        }
        .top-bar a, .top-bar button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.6rem 1rem;
            border-radius: 0.75rem;
            font-size: 0.75rem;
            font-weight: 700;
            text-decoration: none;
            border: 1.5px solid #e2e8f0;
            background: #fff;
            color: #64748b;
            cursor: pointer;
            transition: all 0.15s;
        }
        .top-bar a:hover, .top-bar button:hover { border-color: var(--brand); color: var(--brand); }
        .top-bar .actions { display: flex; gap: 0.5rem; }
        .top-bar .btn-primary {
            background: var(--brand);
            color: #fff;
            border-color: var(--brand);
        }
        .top-bar .btn-primary:hover { background: var(--brand-dark); border-color: var(--brand-dark); color: #fff; }

        /* ===== INVOICE CARD ===== */
        .invoice-card {
            width: 100%;
            max-width: 640px;
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 20px 60px rgba(0,0,0,0.08), 0 1px 2px rgba(0,0,0,0.04);
            overflow: hidden;
        }

        /* Header gradient */
        .invoice-header {
            background: linear-gradient(135deg, #1e1b4b 0%, #312e81 40%, var(--brand) 100%);
            padding: 2.5rem;
            position: relative;
            overflow: hidden;
        }
        .invoice-header::before {
            content: '';
            position: absolute;
            top: -40px; right: -40px;
            width: 200px; height: 200px;
            border-radius: 50%;
            background: rgba(255,255,255,0.05);
        }
        .invoice-header::after {
            content: '';
            position: absolute;
            bottom: -60px; left: -20px;
            width: 160px; height: 160px;
            border-radius: 50%;
            background: rgba(255,255,255,0.04);
        }
        .invoice-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 2rem;
        }
        .invoice-brand .logo {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.15);
            border-radius: 0.75rem;
            display: flex; align-items: center; justify-content: center;
            font-weight: 900; color: #fff; font-size: 1rem;
        }
        .invoice-brand span {
            font-size: 1.1rem;
            font-weight: 800;
            color: #fff;
            letter-spacing: -0.02em;
        }
        .invoice-brand .dot { width: 6px; height: 6px; background: var(--brand); border-radius: 50%; }
        .invoice-meta {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            position: relative; z-index: 1;
        }
        .invoice-no {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: rgba(255,255,255,0.5);
            margin-bottom: 0.4rem;
        }
        .invoice-title {
            font-size: 1.6rem;
            font-weight: 900;
            color: #fff;
            letter-spacing: -0.04em;
            line-height: 1;
        }
        .invoice-subtitle {
            font-size: 0.72rem;
            font-weight: 500;
            color: rgba(255,255,255,0.55);
            margin-top: 0.3rem;
        }

        /* Status badge on header */
        .status-chip {
            padding: 0.5rem 1rem;
            border-radius: 999px;
            font-size: 0.65rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.12em;
        }
        .status-paid   { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .status-unpaid  { background: #fee2e2; color: #991b1b; }

        /* ===== BODY ===== */
        .invoice-body { padding: 2rem 2.5rem; }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 2rem;
            padding-bottom: 2rem;
            border-bottom: 1.5px dashed #e2e8f0;
        }
        .info-block .label {
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #94a3b8;
            margin-bottom: 0.3rem;
        }
        .info-block .value {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1e293b;
        }
        .info-block .value-sm {
            font-size: 0.72rem;
            font-weight: 600;
            color: #475569;
        }

        /* Line items */
        .line-items { margin-bottom: 1.5rem; }
        .line-items-header {
            display: flex;
            justify-content: space-between;
            font-size: 0.6rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: #94a3b8;
            padding: 0 0 0.75rem;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 0.75rem;
        }
        .line-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.9rem 0;
            border-bottom: 1px solid #f8fafc;
        }
        .line-item:last-child { border-bottom: none; }
        .line-item .item-name { font-size: 0.82rem; font-weight: 700; color: #1e293b; }
        .line-item .item-desc { font-size: 0.65rem; font-weight: 500; color: #94a3b8; margin-top: 0.15rem; }
        .line-item .item-amount { font-size: 0.82rem; font-weight: 800; color: #1e293b; }
        .line-item .item-included {
            font-size: 0.65rem; font-weight: 700;
            color: #10b981; background: #d1fae5;
            padding: 0.15rem 0.5rem; border-radius: 999px;
        }

        /* Total section */
        .total-section {
            background: linear-gradient(135deg, #f8faff, #eef2ff);
            border-radius: 1rem;
            padding: 1.5rem;
            margin-bottom: 2rem;
            border: 1.5px solid #e0e7ff;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .total-label { font-size: 0.7rem; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.1em; }
        .total-amount { font-size: 1.8rem; font-weight: 900; color: var(--brand); letter-spacing: -0.04em; }
        .total-note { font-size: 0.65rem; color: #94a3b8; font-weight: 500; margin-top: 0.3rem; }

        /* Payment method */
        .payment-method {
            display: flex;
            align-items: center;
            gap: 1rem;
            background: #f8fafc;
            border: 1.5px solid #f1f5f9;
            border-radius: 0.875rem;
            padding: 1rem 1.25rem;
            margin-bottom: 2rem;
        }
        .payment-icon {
            width: 42px; height: 42px;
            border-radius: 0.75rem;
            background: var(--brand-light);
            color: var(--brand);
            display: flex; align-items: center; justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }
        .payment-info .pm-label { font-size: 0.6rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.12em; color: #94a3b8; }
        .payment-info .pm-value { font-size: 0.82rem; font-weight: 700; color: #1e293b; }
        .payment-date { margin-left: auto; text-align: right; }
        .payment-date .pd-label { font-size: 0.6rem; font-weight: 700; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.1em; }
        .payment-date .pd-value { font-size: 0.72rem; font-weight: 700; color: #475569; }

        /* Action buttons */
        .action-buttons { display: flex; gap: 0.75rem; flex-wrap: wrap; }
        .action-btn {
            flex: 1;
            min-width: 130px;
            padding: 0.9rem 1rem;
            border-radius: 0.875rem;
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            cursor: pointer;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            transition: all 0.15s;
            text-decoration: none;
        }
        .action-btn-primary { background: var(--brand); color: #fff; box-shadow: 0 4px 15px rgba(79,70,229,0.3); }
        .action-btn-primary:hover { background: var(--brand-dark); transform: translateY(-1px); }
        .action-btn-ghost { background: #f1f5f9; color: #475569; }
        .action-btn-ghost:hover { background: #e2e8f0; }

        /* Footer */
        .invoice-footer {
            background: #f8fafc;
            border-top: 1.5px solid #f1f5f9;
            padding: 1.5rem 2.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .footer-brand { font-size: 0.65rem; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.12em; }
        .footer-note { font-size: 0.6rem; color: #cbd5e1; font-weight: 500; }
        .footer-barcode {
            display: flex; gap: 2px;
        }
        .footer-barcode span {
            display: block;
            width: 2px;
            background: #cbd5e1;
            border-radius: 1px;
        }

        /* ===== SHARE MODAL ===== */
        .modal-overlay {
            position: fixed; inset: 0;
            background: rgba(0,0,0,0.4);
            backdrop-filter: blur(4px);
            z-index: 100;
            display: flex; align-items: center; justify-content: center;
            padding: 1rem;
            opacity: 0; pointer-events: none;
            transition: opacity 0.2s;
        }
        .modal-overlay.open { opacity: 1; pointer-events: all; }
        .modal-box {
            background: #fff;
            border-radius: 1.25rem;
            width: 100%;
            max-width: 380px;
            padding: 2rem;
            box-shadow: 0 25px 50px rgba(0,0,0,0.15);
            transform: scale(0.95);
            transition: transform 0.2s;
        }
        .modal-overlay.open .modal-box { transform: scale(1); }
        .modal-title { font-size: 1rem; font-weight: 800; color: #1e293b; margin-bottom: 0.25rem; }
        .modal-sub { font-size: 0.72rem; color: #94a3b8; font-weight: 500; margin-bottom: 1.5rem; }
        .share-options { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1.25rem; }
        .share-option {
            display: flex; flex-direction: column;
            align-items: center; gap: 0.5rem;
            padding: 1rem;
            border-radius: 0.875rem;
            border: 1.5px solid #f1f5f9;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.15s;
        }
        .share-option:hover { border-color: var(--brand); background: var(--brand-light); }
        .share-option .share-icon {
            width: 40px; height: 40px;
            border-radius: 0.75rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
        }
        .share-option .share-label { font-size: 0.65rem; font-weight: 700; color: #475569; text-transform: uppercase; letter-spacing: 0.1em; }
        .share-whatsapp .share-icon { background: #d1fae5; color: #059669; }
        .share-copy .share-icon { background: var(--brand-light); color: var(--brand); }
        .share-email .share-icon { background: #fef3c7; color: #d97706; }
        .share-print .share-icon { background: #f1f5f9; color: #475569; }
        .copy-url-box {
            display: flex; gap: 0.5rem;
            background: #f8fafc;
            border: 1.5px solid #f1f5f9;
            border-radius: 0.75rem;
            padding: 0.75rem 1rem;
            margin-bottom: 1rem;
        }
        .copy-url-box input {
            flex: 1;
            border: none; background: transparent;
            font-size: 0.7rem; font-weight: 600; color: #475569;
            outline: none;
        }
        .copy-url-box button {
            font-size: 0.65rem; font-weight: 800; color: var(--brand);
            border: none; background: transparent; cursor: pointer;
            text-transform: uppercase; letter-spacing: 0.1em;
        }
        .modal-close {
            width: 100%; padding: 0.8rem;
            background: #f1f5f9; border: none; border-radius: 0.75rem;
            font-size: 0.7rem; font-weight: 700; color: #64748b;
            cursor: pointer; transition: background 0.15s;
        }
        .modal-close:hover { background: #e2e8f0; }

        /* ===== PRINT STYLES ===== */
        @media print {
            * { -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
            body { background: #fff; padding: 0; }
            .top-bar, .action-buttons, .modal-overlay { display: none !important; }
            .invoice-card { box-shadow: none; border-radius: 0; max-width: 100%; }
            .invoice-header { -webkit-print-color-adjust: exact; }
        }

        @media (max-width: 640px) {
            .invoice-header { padding: 1.75rem; }
            .invoice-body { padding: 1.5rem; }
            .info-grid { grid-template-columns: 1fr; gap: 1rem; }
            .invoice-footer { flex-direction: column; gap: 1rem; text-align: center; }
            .action-buttons { flex-direction: column; }
        }
    </style>
</head>
<body>

    {{-- TOP ACTION BAR --}}
    <div class="top-bar no-print">
        <a href="{{ route('rent-payments.my-payments') }}">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>
        <div class="actions">
            <button onclick="document.getElementById('shareModal').classList.add('open')">
                <i class="fas fa-share-nodes"></i>
                Bagikan
            </button>
            <button class="btn-primary" onclick="window.print()">
                <i class="fas fa-print"></i>
                Cetak
            </button>
        </div>
    </div>

    {{-- INVOICE CARD --}}
    <div class="invoice-card" id="invoice-content">

        {{-- HEADER --}}
        <div class="invoice-header">
            <div class="invoice-brand">
                <div class="logo">K</div>
                <span>KosKora</span>
                <div class="dot"></div>
            </div>
            <div class="invoice-meta">
                <div>
                    <div class="invoice-no">Invoice #KSK-{{ str_pad($rentPayment->id, 5, '0', STR_PAD_LEFT) }}</div>
                    <div class="invoice-title">{{ strtoupper($rentPayment->month) }}</div>
                    <div class="invoice-subtitle">Bukti Pembayaran Sewa — Diterbitkan {{ \Carbon\Carbon::parse($rentPayment->payment_date)->format('d M Y') }}</div>
                </div>
                @php
                    $statusClass = ['paid' => 'status-paid', 'pending' => 'status-pending', 'unpaid' => 'status-unpaid'][$rentPayment->status] ?? 'status-unpaid';
                    $statusLabel = ['paid' => '✓ Lunas', 'pending' => '⏳ Menunggu', 'unpaid' => '✗ Belum Bayar'][$rentPayment->status] ?? 'Unpaid';
                @endphp
                <span class="status-chip {{ $statusClass }}">{{ $statusLabel }}</span>
            </div>
        </div>

        {{-- BODY --}}
        <div class="invoice-body">

            {{-- Info Grid --}}
            <div class="info-grid">
                <div class="info-block">
                    <div class="label">Kepada</div>
                    <div class="value">{{ $rentPayment->tenants->user->name ?? 'Penyewa' }}</div>
                    <div class="value-sm">{{ $rentPayment->tenants->user->email ?? '' }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Properti</div>
                    <div class="value">Unit {{ $rentPayment->room->room_number }}</div>
                    <div class="value-sm">{{ $rentPayment->room->room_type }} — {{ $rentPayment->room->property_name ?? 'KosKora' }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Tanggal Tagihan</div>
                    <div class="value">{{ \Carbon\Carbon::parse($rentPayment->payment_date)->format('d F Y') }}</div>
                </div>
                <div class="info-block">
                    <div class="label">Jatuh Tempo</div>
                    <div class="value">Tanggal 10 / Bulan</div>
                    <div class="value-sm">{{ $rentPayment->month }}</div>
                </div>
            </div>

            {{-- Line Items --}}
            <div class="line-items">
                <div class="line-items-header">
                    <span>Deskripsi</span>
                    <span>Jumlah</span>
                </div>
                <div class="line-item">
                    <div>
                        <div class="item-name">{{ ($rentPayment->rental && $rentPayment->rental->duration_type === 'yearly') ? 'Sewa Tahunan' : 'Sewa Bulanan' }} — Unit {{ $rentPayment->room->room_number }}</div>
                        <div class="item-desc">{{ $rentPayment->room->room_type }} &bull; Periode {{ $rentPayment->month }} {{ ($rentPayment->rental && $rentPayment->rental->duration_type === 'yearly') ? '(Kontrak Tahunan — Hemat 10%)' : '' }}</div>
                    </div>
                    <div class="item-amount">Rp {{ number_format($rentPayment->amount, 0, ',', '.') }}</div>
                </div>
                <div class="line-item">
                    <div>
                        <div class="item-name">Fasilitas & Layanan</div>
                        <div class="item-desc">Keamanan, Kebersihan, Air & Listrik</div>
                    </div>
                    <div><span class="item-included">Termasuk</span></div>
                </div>
            </div>

            {{-- Total --}}
            <div class="total-section">
                <div class="total-row">
                    <div>
                        <div class="total-label">Total Tagihan</div>
                        <div class="total-note">Sudah termasuk semua fasilitas</div>
                    </div>
                    <div class="total-amount">Rp {{ number_format($rentPayment->amount, 0, ',', '.') }}</div>
                </div>
            </div>

            {{-- Payment Method --}}
            <div class="payment-method">
                <div class="payment-icon">
                    @if(strtolower($rentPayment->method ?? '') === 'midtrans')
                        <i class="fas fa-credit-card"></i>
                    @else
                        <i class="fas fa-building-columns"></i>
                    @endif
                </div>
                <div class="payment-info">
                    <div class="pm-label">Metode Pembayaran</div>
                    <div class="pm-value">{{ strtoupper($rentPayment->method ?: 'Transfer Manual') }}</div>
                </div>
                <div class="payment-date">
                    <div class="pd-label">Tanggal Bayar</div>
                    <div class="pd-value">{{ \Carbon\Carbon::parse($rentPayment->payment_date)->format('d M Y') }}</div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="action-buttons no-print">
                <button class="action-btn action-btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i>
                    Cetak Invoice
                </button>
                <button class="action-btn action-btn-ghost" onclick="document.getElementById('shareModal').classList.add('open')">
                    <i class="fas fa-share-nodes"></i>
                    Bagikan
                </button>
                @if($rentPayment->payment_proof)
                    <a href="{{ asset('storage/' . $rentPayment->payment_proof) }}" target="_blank" class="action-btn action-btn-ghost">
                        <i class="fas fa-image"></i>
                        Bukti Bayar
                    </a>
                @endif
            </div>
        </div>

        {{-- FOOTER --}}
        <div class="invoice-footer">
            <div>
                <div class="footer-brand">KosKora Platform</div>
                <div class="footer-note">Dokumen ini digenerate secara otomatis oleh sistem.</div>
            </div>
            <div class="footer-barcode">
                @foreach(str_split('10110101011101001') as $bit)
                    <span style="height: {{ $bit === '1' ? '24px' : '14px' }}"></span>
                @endforeach
            </div>
        </div>
    </div>

    {{-- SHARE MODAL --}}
    <div class="modal-overlay" id="shareModal" onclick="if(event.target===this) this.classList.remove('open')">
        <div class="modal-box">
            <div class="modal-title">Bagikan Invoice</div>
            <div class="modal-sub">Pilih cara berbagi invoice pembayaran ini</div>

            <div class="share-options">
                <a class="share-option share-whatsapp"
                   href="https://wa.me/?text={{ urlencode('Halo! Ini invoice pembayaran sewa saya untuk periode ' . $rentPayment->month . ' — Rp ' . number_format($rentPayment->amount, 0, ',', '.') . '. Lihat di: ' . url()->current()) }}"
                   target="_blank">
                    <div class="share-icon"><i class="fab fa-whatsapp"></i></div>
                    <span class="share-label">WhatsApp</span>
                </a>
                <button class="share-option share-copy" onclick="copyLink()">
                    <div class="share-icon"><i class="fas fa-link"></i></div>
                    <span class="share-label">Salin Link</span>
                </button>
                <a class="share-option share-email"
                   href="mailto:?subject={{ urlencode('Invoice Sewa ' . $rentPayment->month . ' — KosKora') }}&body={{ urlencode('Halo, terlampir adalah invoice pembayaran sewa Unit ' . $rentPayment->room->room_number . ' untuk periode ' . $rentPayment->month . '.' . "\n\n" . 'Total: Rp ' . number_format($rentPayment->amount, 0, ',', '.') . "\n" . 'Status: ' . strtoupper($rentPayment->status) . "\n\n" . 'Link: ' . url()->current()) }}">
                    <div class="share-icon"><i class="fas fa-envelope"></i></div>
                    <span class="share-label">Email</span>
                </a>
                <button class="share-option share-print" onclick="window.print()">
                    <div class="share-icon"><i class="fas fa-print"></i></div>
                    <span class="share-label">Cetak PDF</span>
                </button>
            </div>

            <div class="copy-url-box">
                <input type="text" id="invoiceUrl" value="{{ url()->current() }}" readonly>
                <button onclick="copyLink()">Salin</button>
            </div>

            <button class="modal-close" onclick="document.getElementById('shareModal').classList.remove('open')">
                Tutup
            </button>
        </div>
    </div>

    <script>
        function copyLink() {
            const input = document.getElementById('invoiceUrl');
            navigator.clipboard.writeText(input.value).then(() => {
                const btn = document.querySelector('.copy-url-box button');
                const orig = btn.innerText;
                btn.innerText = '✓ Disalin!';
                btn.style.color = '#10b981';
                setTimeout(() => { btn.innerText = orig; btn.style.color = ''; }, 2000);
            }).catch(() => {
                input.select();
                document.execCommand('copy');
            });
        }
    </script>
</body>
</html>

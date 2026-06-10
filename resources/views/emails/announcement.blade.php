<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            line-height: 1.6;
            color: #334155;
            margin: 0;
            padding: 0;
            background-color: #f8fafc;
        }
        .container {
            max-width: 600px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            border: 1px solid #e2e8f0;
        }
        .header {
            background-color: #1e1b9b;
            padding: 40px 20px;
            text-align: center;
        }
        .header img {
            height: 40px;
            margin-bottom: 20px;
        }
        .content {
            padding: 40px;
        }
        .greeting {
            font-size: 20px;
            font-weight: 800;
            color: #1e1b9b;
            margin-bottom: 16px;
        }
        .message-box {
            background-color: #f1f5f9;
            padding: 24px;
            border-radius: 16px;
            border-left: 4px solid #d42e2e;
            margin-top: 24px;
        }
        .footer {
            padding: 20px;
            text-align: center;
            font-size: 12px;
            color: #94a3b8;
            background-color: #fcfdfe;
            border-top: 1px solid #f1f5f9;
        }
        .btn {
            display: inline-block;
            padding: 14px 28px;
            background-color: #1e1b9b;
            color: #ffffff;
            text-decoration: none;
            border-radius: 12px;
            font-weight: 700;
            font-size: 14px;
            margin-top: 32px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 style="color: white; margin: 0; font-size: 24px; letter-spacing: 2px;">KOSKORA</h1>
            <p style="color: rgba(255,255,255,0.7); margin-top: 5px; font-size: 10px; font-weight: bold; text-transform: uppercase; letter-spacing: 3px;">Premium Living</p>
        </div>
        <div class="content">
            <div class="greeting">Halo, {{ $recipientName }}!</div>
            <p>Ada pengumuman penting untuk kamu di <strong>KosKora</strong>. Berikut adalah detailnya:</p>
            
            <div class="message-box">
                <h3 style="margin-top: 0; color: #0f172a;">{{ $announcement->title }}</h3>
                <p style="margin-bottom: 0;">{{ $announcement->content }}</p>
            </div>

            <center>
                <a href="{{ url('/dashboard') }}" class="btn">Buka Dashboard</a>
            </center>
        </div>
        <div class="footer">
            &copy; 2025 KosKora. Premium Living, Simplified.<br>
            Jl. Raya KosKora No. 123, Jakarta Selatan.
        </div>
    </div>
</body>
</html>

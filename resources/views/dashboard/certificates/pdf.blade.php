<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Sertifikat - {{ $certificate->certificate_number }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        @page {
            margin: 0;
            size: A4 landscape;
        }
        
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #1a1a2e;
            padding: 20px;
        }
        
        .certificate {
            background: #ffffff;
            width: 100%;
            height: 100%;
            position: relative;
            border: 4px solid #c9a227;
            padding: 40px 60px;
        }
        
        .corner-decoration {
            position: absolute;
            width: 60px;
            height: 60px;
            border: 3px solid #c9a227;
        }
        
        .corner-tl { top: 15px; left: 15px; border-right: none; border-bottom: none; }
        .corner-tr { top: 15px; right: 15px; border-left: none; border-bottom: none; }
        .corner-bl { bottom: 15px; left: 15px; border-right: none; border-top: none; }
        .corner-br { bottom: 15px; right: 15px; border-left: none; border-top: none; }
        
        .header {
            text-align: center;
            margin-bottom: 25px;
        }
        
        .logo-circle {
            width: 70px;
            height: 70px;
            background: linear-gradient(135deg, #c9a227, #f4d03f);
            border-radius: 50%;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logo-text {
            font-size: 28px;
            font-weight: bold;
            color: #1a1a2e;
        }
        
        .title {
            font-size: 42px;
            font-weight: bold;
            color: #1a1a2e;
            letter-spacing: 12px;
            margin: 0;
        }
        
        .subtitle {
            font-size: 14px;
            color: #6c757d;
            letter-spacing: 6px;
            margin-top: 5px;
        }
        
        .body {
            text-align: center;
            margin: 30px 0;
        }
        
        .text {
            font-size: 14px;
            color: #6c757d;
            margin: 0;
        }
        
        .name {
            font-size: 32px;
            font-weight: bold;
            color: #1a1a2e;
            margin: 15px 0;
            padding-bottom: 10px;
            border-bottom: 2px solid #c9a227;
            display: inline-block;
        }
        
        .course {
            font-size: 20px;
            font-weight: 600;
            color: #16213e;
            margin: 15px 0 5px;
        }
        
        .vendor {
            font-size: 13px;
            color: #6c757d;
            font-style: italic;
        }
        
        .footer {
            position: absolute;
            bottom: 50px;
            left: 60px;
            right: 60px;
            display: table;
            width: calc(100% - 120px);
            border-top: 1px solid #e9ecef;
            padding-top: 20px;
        }
        
        .footer-item {
            display: table-cell;
            vertical-align: bottom;
            width: 33.33%;
        }
        
        .footer-item.center {
            text-align: center;
        }
        
        .footer-item.right {
            text-align: right;
        }
        
        .label {
            font-size: 10px;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin: 0;
        }
        
        .value {
            font-size: 12px;
            font-weight: 600;
            color: #1a1a2e;
            margin: 5px 0 0;
        }
        
        .signature-line {
            width: 150px;
            border-bottom: 2px solid #1a1a2e;
            margin: 0 auto;
            padding-bottom: 5px;
            text-align: center;
        }
        
        .signature-initial {
            font-family: 'DejaVu Sans', cursive;
            font-size: 36px;
            font-weight: bold;
            color: #1a1a2e;
        }
        
        .signature-name {
            font-size: 11px;
            font-weight: 600;
            color: #1a1a2e;
            margin-top: 8px;
        }
        
        .seal {
            position: absolute;
            bottom: 80px;
            right: 80px;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #c9a227, #f4d03f);
            border-radius: 50%;
            text-align: center;
            line-height: 80px;
        }
        
        .seal-text {
            font-size: 10px;
            font-weight: bold;
            color: #1a1a2e;
            letter-spacing: 1px;
        }
        
        .verification {
            position: absolute;
            bottom: 20px;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #999;
        }
    </style>
</head>
<body>
    <div class="certificate">
        <!-- Corner Decorations -->
        <div class="corner-decoration corner-tl"></div>
        <div class="corner-decoration corner-tr"></div>
        <div class="corner-decoration corner-bl"></div>
        <div class="corner-decoration corner-br"></div>
        
        <!-- Header -->
        <div class="header">
            <div class="logo-circle">
                <span class="logo-text">S</span>
            </div>
            <h1 class="title">SERTIFIKAT</h1>
            <p class="subtitle">TANDA KELULUSAN</p>
        </div>
        
        <!-- Body -->
        <div class="body">
            <p class="text">Diberikan kepada:</p>
            <h2 class="name">{{ $certificate->user->name }}</h2>
            <p class="text" style="margin-top: 25px;">Atas keberhasilannya menyelesaikan kelas:</p>
            <h3 class="course">{{ $certificate->kelas->judul }}</h3>
            <p class="vendor">Diselenggarakan oleh {{ $certificate->kelas->vendor->nama ?? 'SISKAE' }}</p>
        </div>
        
        <!-- Footer -->
        <div class="footer">
            <div class="footer-item">
                <p class="label">Tanggal Penerbitan</p>
                <p class="value">{{ $certificate->formatted_issue_date }}</p>
            </div>
            <div class="footer-item center">
                <div class="signature-line">
                    <span class="signature-initial">H</span>
                </div>
                <p class="signature-name">Direktur SISKAE</p>
            </div>
            <div class="footer-item right">
                <p class="label">No. Sertifikat</p>
                <p class="value">{{ $certificate->certificate_number }}</p>
            </div>
        </div>
        
        <!-- Seal -->
        <div class="seal">
            <span class="seal-text">VERIFIED</span>
        </div>
        
        <!-- Verification URL -->
        <div class="verification">
            Verifikasi: {{ $certificate->verification_url }}
        </div>
    </div>
</body>
</html>

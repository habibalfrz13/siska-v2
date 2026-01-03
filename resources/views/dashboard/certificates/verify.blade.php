<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verifikasi Sertifikat - SISKAE</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --primary: #4154f1;
            --success: #2eca6a;
            --danger: #ea5455;
        }
        
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        
        .verify-card {
            background: #fff;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            max-width: 500px;
            width: 100%;
            overflow: hidden;
        }
        
        .verify-header {
            padding: 2rem;
            text-align: center;
        }
        
        .verify-header.valid {
            background: linear-gradient(135deg, #2eca6a 0%, #28a745 100%);
        }
        
        .verify-header.invalid {
            background: linear-gradient(135deg, #ea5455 0%, #dc3545 100%);
        }
        
        .verify-icon {
            width: 80px;
            height: 80px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
        }
        
        .verify-icon i {
            font-size: 2.5rem;
            color: #fff;
        }
        
        .verify-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #fff;
            margin: 0;
        }
        
        .verify-subtitle {
            color: rgba(255,255,255,0.9);
            margin: 0.5rem 0 0;
        }
        
        .verify-body {
            padding: 2rem;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 0.75rem 0;
            border-bottom: 1px solid #f0f0f0;
        }
        
        .info-row:last-child {
            border-bottom: none;
        }
        
        .info-label {
            color: #6c757d;
            font-size: 0.9rem;
        }
        
        .info-value {
            font-weight: 600;
            color: #1e293b;
            text-align: right;
        }
        
        .verify-footer {
            padding: 1.5rem 2rem;
            background: #f8f9fa;
            text-align: center;
        }
        
        .cert-number-display {
            font-family: 'Courier New', monospace;
            font-size: 1.1rem;
            letter-spacing: 2px;
            color: var(--primary);
            background: rgba(65, 84, 241, 0.1);
            padding: 0.5rem 1rem;
            border-radius: 8px;
            display: inline-block;
            margin-bottom: 1rem;
        }
        
        .brand-link {
            color: #6c757d;
            text-decoration: none;
        }
        
        .brand-link:hover {
            color: var(--primary);
        }
    </style>
</head>
<body>
    <div class="verify-card">
        @if($isValid && $certificate)
        <!-- Valid Certificate -->
        <div class="verify-header valid">
            <div class="verify-icon">
                <i class="bi bi-patch-check-fill"></i>
            </div>
            <h1 class="verify-title">Sertifikat Valid</h1>
            <p class="verify-subtitle">Sertifikat ini terverifikasi dan sah</p>
        </div>
        <div class="verify-body">
            <div class="text-center mb-4">
                <div class="cert-number-display">{{ $certificate->certificate_number }}</div>
            </div>
            
            <div class="info-row">
                <span class="info-label">Nama Penerima</span>
                <span class="info-value">{{ $certificate->user->name }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nama Kelas</span>
                <span class="info-value">{{ $certificate->kelas->judul }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Tanggal Terbit</span>
                <span class="info-value">{{ $certificate->formatted_issue_date }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value text-success">
                    <i class="bi bi-check-circle me-1"></i>Valid
                </span>
            </div>
        </div>
        @else
        <!-- Invalid Certificate -->
        <div class="verify-header invalid">
            <div class="verify-icon">
                <i class="bi bi-x-circle-fill"></i>
            </div>
            <h1 class="verify-title">Sertifikat Tidak Valid</h1>
            <p class="verify-subtitle">Sertifikat tidak ditemukan atau tidak sah</p>
        </div>
        <div class="verify-body">
            <div class="text-center mb-4">
                <div class="cert-number-display">{{ $certificateNumber }}</div>
            </div>
            
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Nomor sertifikat yang Anda masukkan tidak terdaftar dalam sistem kami. 
                Pastikan nomor sertifikat yang Anda masukkan sudah benar.
            </div>
        </div>
        @endif
        
        <div class="verify-footer">
            <small class="text-muted">
                Diverifikasi oleh <a href="/" class="brand-link fw-semibold">SISKAE</a>
            </small>
        </div>
    </div>
</body>
</html>

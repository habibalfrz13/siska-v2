<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Data Peserta - SISKAE</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            color: #1e293b;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        .report-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        /* Header */
        .report-header {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            color: #fff;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 1.5rem;
        }

        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-logo {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .brand-text h1 {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.25rem;
        }

        .brand-text p {
            font-size: 0.75rem;
            opacity: 0.8;
        }

        .report-meta {
            text-align: right;
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .report-meta p {
            margin-bottom: 0.25rem;
        }

        .report-title {
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .report-title h2 {
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 700;
            color: #4154f1;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.8rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card.highlight {
            background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
        }

        .stat-card.highlight .stat-value,
        .stat-card.highlight .stat-label {
            color: #fff;
        }

        /* Data Table */
        .data-section {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
        }

        .section-header {
            background: #f8f9fa;
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e9ecef;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .section-title {
            font-weight: 600;
            font-size: 0.9rem;
            color: #1e293b;
        }

        .section-badge {
            background: #4154f1;
            color: #fff;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
        }

        .data-table thead th {
            background: #1e293b;
            color: #fff;
            padding: 1rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            text-align: left;
        }

        .data-table tbody tr {
            border-bottom: 1px solid #f0f0f0;
        }

        .data-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .data-table tbody tr:hover {
            background: rgba(65, 84, 241, 0.03);
        }

        .data-table tbody td {
            padding: 1rem;
            font-size: 0.85rem;
        }

        .row-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
            color: #fff;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .peserta-name {
            font-weight: 600;
            color: #1e293b;
        }

        .class-badge {
            display: inline-block;
            background: rgba(65, 84, 241, 0.1);
            color: #4154f1;
            padding: 0.35rem 0.75rem;
            border-radius: 6px;
            font-size: 0.8rem;
            font-weight: 500;
        }

        /* Footer */
        .report-footer {
            margin-top: 1.5rem;
            padding: 1rem;
            text-align: center;
            font-size: 0.75rem;
            color: #6c757d;
        }

        .signature-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
            padding-right: 2rem;
        }

        .signature-box {
            text-align: center;
            width: 200px;
        }

        .signature-date {
            font-size: 0.8rem;
            color: #6c757d;
            margin-bottom: 4rem;
        }

        .signature-line {
            border-top: 2px solid #1e293b;
            padding-top: 0.5rem;
        }

        .signature-name {
            font-weight: 600;
            color: #1e293b;
        }

        .signature-title {
            font-size: 0.75rem;
            color: #6c757d;
        }

        /* Print Styles */
        @media print {
            body {
                background: #fff;
            }
            
            .report-container {
                padding: 0;
                max-width: 100%;
            }

            .report-header,
            .stat-card,
            .data-section {
                box-shadow: none;
                border: 1px solid #e9ecef;
            }

            .no-print {
                display: none;
            }
        }

        .print-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
            color: #fff;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(65, 84, 241, 0.4);
        }

        .print-btn:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="report-container">
        <!-- Header -->
        <div class="report-header">
            <div class="header-top">
                <div class="brand">
                    <div class="brand-logo">S</div>
                    <div class="brand-text">
                        <h1>SISKAE</h1>
                        <p>Sistem Informasi Sertifikasi dan Kompetensi</p>
                    </div>
                </div>
                <div class="report-meta">
                    <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    <p><strong>Waktu:</strong> {{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                </div>
            </div>
            <div class="report-title">
                <h2>Laporan Data Peserta</h2>
            </div>
        </div>

        <!-- Stats -->
        @php
            $totalPeserta = $myclass->count();
            $uniqueKelas = $myclass->unique('judul')->count();
        @endphp
        <div class="stats-grid">
            <div class="stat-card highlight">
                <div class="stat-value">{{ $totalPeserta }}</div>
                <div class="stat-label">Total Peserta</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $uniqueKelas }}</div>
                <div class="stat-label">Total Kelas</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">{{ $totalPeserta > 0 ? round($totalPeserta / max($uniqueKelas, 1), 1) : 0 }}</div>
                <div class="stat-label">Rata-rata/Kelas</div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-section">
            <div class="section-header">
                <span class="section-title">Detail Data Peserta</span>
                <span class="section-badge">{{ $totalPeserta }} Records</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Peserta</th>
                        <th>Kelas</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($myclass as $kelas)
                    <tr>
                        <td><span class="row-number">{{ $loop->iteration }}</span></td>
                        <td><span class="peserta-name">{{ $kelas->nama_peserta }}</span></td>
                        <td><span class="class-badge">{{ $kelas->judul }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 2rem;">Tidak ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Signature -->
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-date">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                <div class="signature-line">
                    <p class="signature-name">Administrator</p>
                    <p class="signature-title">SISKAE</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="report-footer">
            <p>Dokumen ini digenerate secara otomatis oleh sistem SISKAE</p>
            <p>© {{ date('Y') }} SISKAE - Sistem Informasi Sertifikasi dan Kompetensi Keahlian</p>
        </div>
    </div>

    <button class="print-btn no-print" onclick="window.print()">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
            <path d="M2.5 8a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
            <path d="M5 1a2 2 0 0 0-2 2v2H2a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h1v1a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2v-1h1a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2h-1V3a2 2 0 0 0-2-2H5zM4 3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1v2H4V3zm1 5a2 2 0 0 0-2 2v1H2a1 1 0 0 1-1-1V7a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1v-1a2 2 0 0 0-2-2H5zm7 2v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h6a1 1 0 0 1 1 1z"/>
        </svg>
        Cetak Laporan
    </button>
</body>
</html>

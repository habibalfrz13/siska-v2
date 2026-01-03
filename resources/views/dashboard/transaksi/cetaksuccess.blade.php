<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Transaksi Berhasil - SISKAE</title>
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
            background: linear-gradient(135deg, #0d6535 0%, #198754 100%);
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
            background: rgba(255,255,255,0.2);
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
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .report-title h2 {
            font-size: 1.25rem;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .report-title .badge {
            display: inline-block;
            background: rgba(255,255,255,0.2);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }

        /* Stats Grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 1.25rem;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
            position: relative;
            overflow: hidden;
        }

        .stat-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
        }

        .stat-card.green::before { background: linear-gradient(90deg, #198754, #20c997); }
        .stat-card.blue::before { background: linear-gradient(90deg, #4154f1, #6c5ce7); }
        .stat-card.purple::before { background: linear-gradient(90deg, #764ba2, #667eea); }
        .stat-card.gold::before { background: linear-gradient(90deg, #f59e0b, #fbbf24); }

        .stat-value {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 0.25rem;
        }

        .stat-label {
            font-size: 0.75rem;
            color: #6c757d;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-card.revenue .stat-value {
            font-size: 1.25rem;
            color: #198754;
        }

        /* Data Table */
        .data-section {
            background: #fff;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.06);
            margin-bottom: 1.5rem;
        }

        .section-header {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
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
            background: #198754;
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

        .data-table tbody td {
            padding: 1rem;
            font-size: 0.85rem;
        }

        .row-number {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: #fff;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .user-name {
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

        .amount {
            font-weight: 700;
            color: #198754;
        }

        /* Total Row */
        .total-row {
            background: linear-gradient(135deg, #1e293b 0%, #334155 100%) !important;
        }

        .total-row td {
            color: #fff;
            font-weight: 600;
            padding: 1.25rem 1rem;
        }

        .total-label {
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .total-amount {
            font-size: 1.25rem;
            font-weight: 700;
            color: #20c997;
        }

        /* Signature */
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

        /* Footer */
        .report-footer {
            margin-top: 1.5rem;
            padding: 1rem;
            text-align: center;
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

            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }

            .no-print {
                display: none;
            }
        }

        @media (max-width: 768px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        .print-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, #198754 0%, #20c997 100%);
            color: #fff;
            border: none;
            padding: 1rem 1.5rem;
            border-radius: 50px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(25, 135, 84, 0.4);
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
                <h2>Laporan Transaksi Berhasil</h2>
                <span class="badge">Status: Settlement</span>
            </div>
        </div>

        <!-- Stats -->
        @php
            $totalTransaksi = $transaksis->count();
            $avgTransaction = $totalTransaksi > 0 ? $totalPembayaran / $totalTransaksi : 0;
        @endphp
        <div class="stats-grid">
            <div class="stat-card green">
                <div class="stat-value">{{ $totalTransaksi }}</div>
                <div class="stat-label">Total Transaksi</div>
            </div>
            <div class="stat-card blue">
                <div class="stat-value">{{ $transaksis->unique('kelas_id')->count() }}</div>
                <div class="stat-label">Kelas Terbeli</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-value">Rp {{ number_format($avgTransaction/1000, 0) }}K</div>
                <div class="stat-label">Rata-rata</div>
            </div>
            <div class="stat-card revenue gold">
                <div class="stat-value">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>

        <!-- Data Table -->
        <div class="data-section">
            <div class="section-header">
                <span class="section-title">Detail Transaksi Berhasil</span>
                <span class="section-badge">{{ $totalTransaksi }} Transaksi</span>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th width="60">No</th>
                        <th>Nama Pengguna</th>
                        <th>Kelas</th>
                        <th width="150" style="text-align: right;">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $trx)
                    <tr>
                        <td><span class="row-number">{{ $loop->iteration }}</span></td>
                        <td><span class="user-name">{{ optional($trx->user->biodata)->username ?? 'N/A' }}</span></td>
                        <td><span class="class-badge">{{ Str::limit($trx->kelas->judul, 35) }}</span></td>
                        <td style="text-align: right;"><span class="amount">Rp {{ number_format($trx->jumlah_pembayaran, 0, ',', '.') }}</span></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" style="text-align: center; padding: 2rem;">Tidak ada transaksi berhasil</td>
                    </tr>
                    @endforelse
                    <tr class="total-row">
                        <td colspan="3"><span class="total-label">Total Pendapatan</span></td>
                        <td style="text-align: right;"><span class="total-amount">Rp {{ number_format($totalPembayaran, 0, ',', '.') }}</span></td>
                    </tr>
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

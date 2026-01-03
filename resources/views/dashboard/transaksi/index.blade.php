@extends('dashboard.main')

@section('title', 'Manajemen Transaksi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Transaksi</h4>
            <p class="text-muted mb-0">Pantau dan kelola semua transaksi pembayaran</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('transaksi.cetaksuccess') }}" class="btn btn-soft-warning">
                <i class="bi bi-printer me-2"></i>Cetak Laporan
            </a>
            <a href="{{ route('transaksi.create') }}" class="btn btn-gradient-primary">
                <i class="bi bi-plus-lg me-2"></i>Buat Transaksi
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    @php
        $totalTransaksi = $transaksis->count();
        $successTransaksi = $transaksis->where('status_pembayaran', 'Berhasil')->count();
        $pendingTransaksi = $transaksis->where('status_pembayaran', 'Pending')->count();
        $failedTransaksi = $transaksis->whereIn('status_pembayaran', ['expire', 'Gagal', 'failure'])->count();
        $totalPendapatan = $transaksis->where('status_pembayaran', 'Berhasil')->sum('jumlah_pembayaran');
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="analytics-card">
                <div class="analytics-icon blue">
                    <i class="bi bi-receipt"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">{{ $totalTransaksi }}</span>
                    <span class="analytics-label">Total Transaksi</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="analytics-card">
                <div class="analytics-icon green">
                    <i class="bi bi-check-circle"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">{{ $successTransaksi }}</span>
                    <span class="analytics-label">Berhasil</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="analytics-card">
                <div class="analytics-icon yellow">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">{{ $pendingTransaksi }}</span>
                    <span class="analytics-label">Pending</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="analytics-card revenue">
                <div class="analytics-icon purple">
                    <i class="bi bi-currency-dollar"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">Rp {{ number_format($totalPendapatan/1000, 0) }}K</span>
                    <span class="analytics-label">Total Pendapatan</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Transaction Table -->
    <div class="modern-table-card">
        <div class="table-header">
            <h6 class="mb-0 fw-semibold">
                <i class="bi bi-list-ul me-2"></i>Daftar Transaksi
            </h6>
            <div class="table-filter">
                <select class="form-select form-select-sm" id="statusFilter" onchange="filterTable()">
                    <option value="">Semua Status</option>
                    <option value="Berhasil">Berhasil</option>
                    <option value="Pending">Pending</option>
                    <option value="Gagal">Gagal</option>
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table modern-table" id="transactionTable">
                <thead>
                    <tr>
                        <th width="50">No</th>
                        <th>Pengguna</th>
                        <th>Kelas</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($transaksis as $transaksi)
                    <tr data-status="{{ $transaksi->status_pembayaran }}">
                        <td><span class="row-number">{{ $loop->iteration }}</span></td>
                        <td>
                            <div class="user-info">
                                <div class="user-avatar">
                                    {{ strtoupper(substr($transaksi->user->biodata->username ?? 'U', 0, 1)) }}
                                </div>
                                <div class="user-details">
                                    <span class="user-name">{{ $transaksi->user->biodata->username ?? 'N/A' }}</span>
                                    <span class="user-email">{{ $transaksi->user->email ?? '' }}</span>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class="class-name">{{ Str::limit($transaksi->kelas->judul, 35) }}</span>
                        </td>
                        <td>
                            <span class="amount">Rp {{ number_format($transaksi->jumlah_pembayaran, 0, ',', '.') }}</span>
                        </td>
                        <td>
                            @php
                                $statusClass = match($transaksi->status_pembayaran) {
                                    'Berhasil' => 'success',
                                    'Pending' => 'warning',
                                    'Gagal' => 'danger',
                                    default => 'secondary'
                                };
                                $statusLabel = match($transaksi->status_pembayaran) {
                                    'Berhasil' => 'Berhasil',
                                    'Pending' => 'Pending',
                                    'Gagal' => 'Gagal',
                                    default => ucfirst($transaksi->status_pembayaran)
                                };
                            @endphp
                            <span class="status-badge {{ $statusClass }}">
                                <i class="bi {{ $statusClass == 'success' ? 'bi-check-circle' : ($statusClass == 'warning' ? 'bi-clock' : 'bi-x-circle') }}"></i>
                                {{ $statusLabel }}
                            </span>
                        </td>
                        <td>
                            <span class="date-text">{{ $transaksi->created_at->format('d M Y') }}</span>
                            <span class="time-text">{{ $transaksi->created_at->format('H:i') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-5">
                            <i class="bi bi-inbox display-4 text-muted d-block mb-2"></i>
                            <p class="text-muted mb-0">Belum ada transaksi</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
/* Analytics Card */
.analytics-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: var(--bg-white, #fff);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

.analytics-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
}

.analytics-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.analytics-icon.blue { background: rgba(65, 84, 241, 0.1); color: #4154f1; }
.analytics-icon.green { background: rgba(46, 202, 106, 0.1); color: #2eca6a; }
.analytics-icon.yellow { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
.analytics-icon.red { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
.analytics-icon.purple { background: rgba(118, 75, 162, 0.1); color: #764ba2; }

.analytics-content {
    display: flex;
    flex-direction: column;
}

.analytics-value {
    font-size: 1.35rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    line-height: 1.2;
}

.analytics-label {
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
}

/* Modern Table */
.modern-table-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.table-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color, #e9ecef);
}

.table-filter select {
    min-width: 150px;
}

.modern-table {
    margin-bottom: 0;
}

.modern-table thead th {
    background: var(--bg-light, #f8f9fa);
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted, #6c757d);
    border: none;
    padding: 1rem;
}

.modern-table tbody td {
    padding: 1rem;
    vertical-align: middle;
    border-color: var(--border-color, #f0f0f0);
}

.row-number {
    width: 28px;
    height: 28px;
    background: var(--bg-light, #f0f0f0);
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
}

.user-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 0.9rem;
}

.user-details {
    display: flex;
    flex-direction: column;
}

.user-name {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    font-size: 0.9rem;
}

.user-email {
    font-size: 0.75rem;
    color: var(--text-muted, #6c757d);
}

.class-name {
    font-weight: 500;
    color: var(--text-primary, #1e293b);
}

.amount {
    font-weight: 700;
    color: var(--primary, #4154f1);
}

.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
}

.status-badge.success { background: rgba(46, 202, 106, 0.1); color: #2eca6a; }
.status-badge.warning { background: rgba(255, 193, 7, 0.1); color: #e6a800; }
.status-badge.danger { background: rgba(220, 53, 69, 0.1); color: #dc3545; }
.status-badge.secondary { background: rgba(108, 117, 125, 0.1); color: #6c757d; }

.date-text {
    display: block;
    font-weight: 500;
    color: var(--text-primary, #1e293b);
    font-size: 0.85rem;
}

.time-text {
    font-size: 0.75rem;
    color: var(--text-muted, #6c757d);
}

/* Dark Mode */
[data-theme="dark"] .analytics-card,
[data-theme="dark"] .modern-table-card {
    background: var(--bg-white);
}
</style>

@push('scripts')
<script>
function filterTable() {
    const filter = document.getElementById('statusFilter').value;
    const rows = document.querySelectorAll('#transactionTable tbody tr');
    
    rows.forEach(row => {
        if (!filter || row.dataset.status === filter) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}
</script>
@endpush
@endsection

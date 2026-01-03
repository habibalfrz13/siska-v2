@extends('dashboard.main')

@section('title', 'Manajemen Peserta')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Manajemen Peserta</h4>
            <p class="text-muted mb-0">Kelola data peserta kelas dan pelatihan</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('peserta.cetak') }}" class="btn btn-soft-warning">
                <i class="bi bi-printer me-2"></i>Cetak Laporan
            </a>
            <a href="{{ route('peserta.create') }}" class="btn btn-gradient-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Peserta
            </a>
        </div>
    </div>

    <!-- Stats Summary -->
    @php
        $totalPeserta = $pesertas->count();
        $uniqueKelas = $pesertas->unique('judul')->count();
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="analytics-card">
                <div class="analytics-icon blue">
                    <i class="bi bi-people"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">{{ $totalPeserta }}</span>
                    <span class="analytics-label">Total Peserta</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="analytics-card">
                <div class="analytics-icon green">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">{{ $uniqueKelas }}</span>
                    <span class="analytics-label">Kelas Aktif</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="analytics-card">
                <div class="analytics-icon purple">
                    <i class="bi bi-graph-up-arrow"></i>
                </div>
                <div class="analytics-content">
                    <span class="analytics-value">{{ $totalPeserta > 0 ? round($totalPeserta / max($uniqueKelas, 1), 1) : 0 }}</span>
                    <span class="analytics-label">Rata-rata/Kelas</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Peserta Grid -->
    @if($pesertas->isEmpty())
        <div class="empty-state">
            <i class="bi bi-people display-1 text-muted mb-3 d-block"></i>
            <h5>Belum Ada Peserta</h5>
            <p class="text-muted">Mulai dengan menambahkan peserta baru.</p>
            <a href="{{ route('peserta.create') }}" class="btn btn-gradient-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Peserta
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($pesertas as $peserta)
            <div class="col-md-6 col-lg-4">
                <div class="peserta-card">
                    <div class="peserta-header">
                        <div class="peserta-avatar">
                            {{ strtoupper(substr($peserta->nama_peserta, 0, 2)) }}
                        </div>
                        <div class="peserta-info">
                            <h6 class="peserta-name">{{ $peserta->nama_peserta }}</h6>
                            <span class="peserta-id">ID: #{{ str_pad($peserta->id, 4, '0', STR_PAD_LEFT) }}</span>
                        </div>
                    </div>
                    <div class="peserta-body">
                        <div class="peserta-class">
                            <i class="bi bi-journal-bookmark"></i>
                            <span>{{ Str::limit($peserta->judul, 40) }}</span>
                        </div>
                    </div>
                    <div class="peserta-actions">
                        <a href="{{ route('peserta.show', $peserta->id) }}" class="action-btn info" title="Lihat Detail">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('peserta.edit', $peserta->id) }}" class="action-btn warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <button type="button" class="action-btn danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $peserta->id }}" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal{{ $peserta->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered modal-sm">
                    <div class="modal-content">
                        <div class="modal-body text-center py-4">
                            <i class="bi bi-exclamation-triangle text-danger display-4 mb-3 d-block"></i>
                            <p class="mb-1">Hapus peserta:</p>
                            <p class="fw-bold mb-3">{{ $peserta->nama_peserta }}?</p>
                            <div class="d-flex gap-2 justify-content-center">
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                                <form action="{{ route('peserta.destroy', $peserta->id) }}" method="POST" class="d-inline">
                                    @method('DELETE')
                                    @csrf
                                    <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
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
.analytics-icon.purple { background: rgba(118, 75, 162, 0.1); color: #764ba2; }

.analytics-content {
    display: flex;
    flex-direction: column;
}

.analytics-value {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
}

.analytics-label {
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
}

/* Peserta Card */
.peserta-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
}

.peserta-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.peserta-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.peserta-avatar {
    width: 50px;
    height: 50px;
    border-radius: 12px;
    background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1rem;
}

.peserta-info {
    flex-grow: 1;
}

.peserta-name {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    margin: 0;
    font-size: 1rem;
}

.peserta-id {
    font-size: 0.75rem;
    color: var(--text-muted, #6c757d);
}

.peserta-body {
    padding: 1rem 1.25rem;
}

.peserta-class {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-secondary, #5a6169);
}

.peserta-class i {
    color: var(--primary, #4154f1);
}

.peserta-actions {
    display: flex;
    border-top: 1px solid var(--border-color, #e9ecef);
}

.action-btn {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0.75rem;
    text-decoration: none;
    transition: all 0.2s;
    border: none;
    background: none;
    cursor: pointer;
}

.action-btn i {
    font-size: 1rem;
}

.action-btn.info { color: #17a2b8; }
.action-btn.info:hover { background: rgba(23, 162, 184, 0.1); }
.action-btn.warning { color: #ffc107; }
.action-btn.warning:hover { background: rgba(255, 193, 7, 0.1); }
.action-btn.danger { color: #dc3545; }
.action-btn.danger:hover { background: rgba(220, 53, 69, 0.1); }

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--bg-white, #fff);
    border-radius: 16px;
}

/* Dark Mode */
[data-theme="dark"] .analytics-card,
[data-theme="dark"] .peserta-card,
[data-theme="dark"] .empty-state {
    background: var(--bg-white);
}

[data-theme="dark"] .peserta-header {
    background: rgba(255,255,255,0.03);
}
</style>
@endsection

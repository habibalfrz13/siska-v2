@extends('dashboard.main')

@section('title', 'Kelola Kelas')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelola Kelas</h4>
            <p class="text-muted mb-0">Manajemen kelas dan materi pembelajaran</p>
        </div>
        @if (Auth::user()->id_role == 1)
        <a href="{{ route('kelas.create') }}" class="btn btn-gradient-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kelas Baru
        </a>
        @endif
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        @php
            $totalKelas = $kelas->count();
            $kelasAktif = $kelas->where('status', 'Aktif')->count();
            $kelasNonaktif = $kelas->where('status', 'Tidak Aktif')->count();
        @endphp
        <div class="col-md-4">
            <div class="stats-card stats-total">
                <div class="stats-icon"><i class="bi bi-journal-bookmark"></i></div>
                <div class="stats-info">
                    <span class="stats-number">{{ $totalKelas }}</span>
                    <span class="stats-label">Total Kelas</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card stats-active">
                <div class="stats-icon"><i class="bi bi-check-circle"></i></div>
                <div class="stats-info">
                    <span class="stats-number">{{ $kelasAktif }}</span>
                    <span class="stats-label">Kelas Aktif</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stats-card stats-inactive">
                <div class="stats-icon"><i class="bi bi-pause-circle"></i></div>
                <div class="stats-info">
                    <span class="stats-number">{{ $kelasNonaktif }}</span>
                    <span class="stats-label">Kelas Nonaktif</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Classes Grid -->
    @if($kelas->isEmpty())
        <div class="empty-state">
            <i class="bi bi-inbox display-1 text-muted mb-3 d-block"></i>
            <h5>Belum Ada Kelas</h5>
            <p class="text-muted">Mulai dengan menambahkan kelas baru.</p>
            <a href="{{ route('kelas.create') }}" class="btn btn-gradient-primary">
                <i class="bi bi-plus-lg me-2"></i>Tambah Kelas
            </a>
        </div>
    @else
        <div class="row g-4">
            @foreach ($kelas as $item)
            <div class="col-md-6 col-xl-4">
                <div class="class-admin-card">
                    <!-- Card Image -->
                    <div class="card-image">
                        <img src="{{ url('images/galerikelas/'.$item->foto) }}" alt="{{ $item->judul }}">
                        <div class="card-status {{ $item->status == 'Aktif' ? 'active' : 'inactive' }}">
                            <i class="bi {{ $item->status == 'Aktif' ? 'bi-check-circle' : 'bi-pause-circle' }}"></i>
                            {{ $item->status }}
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="card-body-content">
                        <div class="card-category">
                            <i class="bi bi-folder me-1"></i>{{ $item->kategori->nama_kategori ?? 'Uncategorized' }}
                        </div>
                        <h5 class="card-title-text">{{ Str::limit($item->judul, 45) }}</h5>
                        
                        <div class="card-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar3"></i>
                                <span>{{ \Carbon\Carbon::parse($item->pelaksanaan)->format('d M Y') }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-people"></i>
                                <span>{{ $item->kuota }} Kuota</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-building"></i>
                                <span>{{ $item->vendor->nama ?? 'SISKAE' }}</span>
                            </div>
                        </div>

                        <div class="card-price">
                            <span class="price-label">Harga</span>
                            <span class="price-value">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                        </div>

                        <!-- Module Info -->
                        @php
                            $moduleCount = $item->modules()->count();
                            $materialCount = 0;
                            foreach($item->modules as $mod) {
                                $materialCount += $mod->materials()->count();
                            }
                        @endphp
                        <div class="card-modules">
                            <div class="module-stat">
                                <i class="bi bi-collection"></i>
                                <span>{{ $moduleCount }} Modul</span>
                            </div>
                            <div class="module-stat">
                                <i class="bi bi-file-earmark-play"></i>
                                <span>{{ $materialCount }} Materi</span>
                            </div>
                        </div>
                    </div>

                    <!-- Card Footer -->
                    @if (Auth::user()->id_role == 1)
                    <div class="card-footer-actions">
                        <a href="{{ route('admin.modules.index', $item->id) }}" class="action-btn primary" title="Kelola Modul">
                            <i class="bi bi-collection"></i>
                            <span>Modul</span>
                        </a>
                        <a href="{{ route('kelas.show', $item->id) }}" class="action-btn info" title="Detail">
                            <i class="bi bi-eye"></i>
                            <span>Detail</span>
                        </a>
                        <a href="{{ route('kelas.edit', $item->id) }}" class="action-btn warning" title="Edit">
                            <i class="bi bi-pencil"></i>
                            <span>Edit</span>
                        </a>
                        <button type="button" class="action-btn danger" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}" title="Hapus">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header border-0">
                            <h5 class="modal-title">Konfirmasi Hapus</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body text-center py-4">
                            <i class="bi bi-exclamation-triangle text-danger display-4 mb-3 d-block"></i>
                            <p class="mb-1">Apakah Anda yakin ingin menghapus kelas:</p>
                            <p class="fw-bold">{{ $item->judul }}?</p>
                            <p class="text-muted small">Tindakan ini tidak dapat dibatalkan.</p>
                        </div>
                        <div class="modal-footer border-0 justify-content-center">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <form action="{{ route('kelas.destroy', $item->id) }}" method="POST" class="d-inline">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    <i class="bi bi-trash me-1"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<style>
/* Stats Cards */
.stats-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    border-radius: 12px;
    background: var(--bg-white, #fff);
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.stats-card .stats-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stats-total .stats-icon {
    background: rgba(65, 84, 241, 0.1);
    color: #4154f1;
}

.stats-active .stats-icon {
    background: rgba(46, 202, 106, 0.1);
    color: #2eca6a;
}

.stats-inactive .stats-icon {
    background: rgba(108, 117, 125, 0.1);
    color: #6c757d;
}

.stats-info {
    display: flex;
    flex-direction: column;
}

.stats-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    line-height: 1.2;
}

.stats-label {
    font-size: 0.85rem;
    color: var(--text-muted, #6c757d);
}

/* Class Admin Card */
.class-admin-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.class-admin-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.card-image {
    position: relative;
    height: 160px;
    overflow: hidden;
}

.card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.class-admin-card:hover .card-image img {
    transform: scale(1.05);
}

.card-status {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    gap: 0.35rem;
}

.card-status.active {
    background: rgba(46, 202, 106, 0.9);
    color: #fff;
}

.card-status.inactive {
    background: rgba(108, 117, 125, 0.9);
    color: #fff;
}

.card-body-content {
    padding: 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.card-category {
    font-size: 0.75rem;
    color: var(--primary, #4154f1);
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.5rem;
}

.card-title-text {
    font-weight: 600;
    font-size: 1rem;
    color: var(--text-primary, #1e293b);
    margin-bottom: 0.75rem;
    line-height: 1.4;
}

.card-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 0.75rem;
    margin-bottom: 1rem;
}

.card-meta .meta-item {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
}

.card-meta .meta-item i {
    font-size: 0.85rem;
}

.card-price {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem;
    background: var(--bg-light, #f8f9fa);
    border-radius: 8px;
    margin-bottom: 0.75rem;
}

.price-label {
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
}

.price-value {
    font-weight: 700;
    color: var(--primary, #4154f1);
}

.card-modules {
    display: flex;
    gap: 1rem;
    margin-top: auto;
}

.module-stat {
    display: flex;
    align-items: center;
    gap: 0.4rem;
    font-size: 0.8rem;
    color: var(--text-secondary, #5a6169);
}

.module-stat i {
    color: var(--primary, #4154f1);
}

.card-footer-actions {
    display: flex;
    border-top: 1px solid var(--border-color, #e9ecef);
}

.action-btn {
    flex: 1;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 0.25rem;
    padding: 0.75rem 0.5rem;
    text-decoration: none;
    font-size: 0.7rem;
    font-weight: 600;
    transition: all 0.2s;
    border: none;
    background: none;
    cursor: pointer;
}

.action-btn i {
    font-size: 1rem;
}

.action-btn span {
    display: none;
}

@media (min-width: 992px) {
    .action-btn span {
        display: block;
    }
}

.action-btn.primary {
    color: #4154f1;
}

.action-btn.primary:hover {
    background: rgba(65, 84, 241, 0.1);
}

.action-btn.info {
    color: #17a2b8;
}

.action-btn.info:hover {
    background: rgba(23, 162, 184, 0.1);
}

.action-btn.warning {
    color: #ffc107;
}

.action-btn.warning:hover {
    background: rgba(255, 193, 7, 0.1);
}

.action-btn.danger {
    color: #dc3545;
}

.action-btn.danger:hover {
    background: rgba(220, 53, 69, 0.1);
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
    background: var(--bg-white, #fff);
    border-radius: 16px;
}

/* Dark Mode */
[data-theme="dark"] .stats-card,
[data-theme="dark"] .class-admin-card,
[data-theme="dark"] .empty-state {
    background: var(--bg-white);
}

[data-theme="dark"] .card-price {
    background: rgba(255,255,255,0.03);
}
</style>
@endsection

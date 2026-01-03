@extends('dashboard.main')

@section('title', 'Kelas Saya')

@section('content')
<div class="learning-dashboard">
    <!-- Header -->
    <div class="learning-header mb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
            <div>
                <h4 class="fw-bold mb-1">Kelas Saya</h4>
                <p class="text-muted mb-0">Lanjutkan pembelajaran Anda</p>
            </div>
            <a href="{{ route('kelas.userIndex') }}" class="btn btn-soft-primary">
                <i class="bi bi-plus-lg me-2"></i>Jelajahi Kelas Lainnya
            </a>
        </div>
    </div>

    @if($classesWithProgress->isEmpty())
        <!-- Empty State -->
        <div class="empty-state text-center py-5">
            <div class="empty-icon mb-4">
                <i class="bi bi-journal-bookmark display-1 text-muted"></i>
            </div>
            <h5 class="fw-semibold mb-2">Belum Ada Kelas Aktif</h5>
            <p class="text-muted mb-4">Anda belum memiliki kelas aktif. Daftar ke kelas untuk mulai belajar.</p>
            <a href="{{ route('kelas.userIndex') }}" class="btn btn-gradient-primary">
                <i class="bi bi-search me-2"></i>Cari Kelas
            </a>
        </div>
    @else
        <!-- Stats Row -->
        <div class="row g-3 mb-4">
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card-mini">
                    <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                        <i class="bi bi-journal-check"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">{{ $classesWithProgress->count() }}</span>
                        <span class="stat-label">Kelas Aktif</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card-mini">
                    <div class="stat-icon bg-success bg-opacity-10 text-success">
                        <i class="bi bi-check-circle"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">{{ $classesWithProgress->sum('completed_materials') }}</span>
                        <span class="stat-label">Materi Selesai</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card-mini">
                    <div class="stat-icon bg-warning bg-opacity-10 text-warning">
                        <i class="bi bi-collection-play"></i>
                    </div>
                    <div class="stat-info">
                        <span class="stat-number">{{ $classesWithProgress->sum('total_materials') }}</span>
                        <span class="stat-label">Total Materi</span>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-3">
                <div class="stat-card-mini">
                    <div class="stat-icon bg-info bg-opacity-10 text-info">
                        <i class="bi bi-graph-up-arrow"></i>
                    </div>
                    <div class="stat-info">
                        @php
                            $avgProgress = $classesWithProgress->count() > 0 
                                ? round($classesWithProgress->avg('progress_percent')) 
                                : 0;
                        @endphp
                        <span class="stat-number">{{ $avgProgress }}%</span>
                        <span class="stat-label">Rata-rata Progress</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Classes Grid -->
        <div class="row g-4">
            @foreach($classesWithProgress as $item)
            <div class="col-md-6 col-lg-4">
                <div class="learning-card">
                    <div class="card-image">
                        <img src="{{ url('images/galerikelas/'.$item['kelas']->foto) }}" alt="{{ $item['kelas']->judul }}">
                        <div class="card-overlay">
                            <span class="badge-category">{{ $item['kelas']->kategori->nama ?? 'Umum' }}</span>
                        </div>
                    </div>
                    <div class="card-content">
                        <h5 class="card-title">{{ Str::limit($item['kelas']->judul, 50) }}</h5>
                        
                        <div class="card-meta">
                            <span><i class="bi bi-collection me-1"></i>{{ $item['total_materials'] }} Materi</span>
                            <span><i class="bi bi-check2-circle me-1"></i>{{ $item['completed_materials'] }} Selesai</span>
                        </div>
                        
                        <!-- Progress Bar -->
                        <div class="progress-wrapper">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="progress-label">Progress</span>
                                <span class="progress-percent">{{ $item['progress_percent'] }}%</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar {{ $item['progress_percent'] == 100 ? 'bg-success' : 'bg-primary' }}" 
                                     style="width: {{ $item['progress_percent'] }}%"></div>
                            </div>
                        </div>
                        
                        <a href="{{ route('learn.course', $item['kelas']->id) }}" class="btn btn-gradient-primary w-100 mt-3">
                            @if($item['progress_percent'] == 0)
                                <i class="bi bi-play-fill me-2"></i>Mulai Belajar
                            @elseif($item['progress_percent'] == 100)
                                <i class="bi bi-arrow-repeat me-2"></i>Review Materi
                            @else
                                <i class="bi bi-play-fill me-2"></i>Lanjutkan
                            @endif
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.learning-dashboard {
    padding-bottom: 2rem;
}

.stat-card-mini {
    background: var(--bg-white, #fff);
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.stat-card-mini .stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-card-mini .stat-info {
    display: flex;
    flex-direction: column;
}

.stat-card-mini .stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    line-height: 1.2;
}

.stat-card-mini .stat-label {
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
}

.learning-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.learning-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 30px rgba(0,0,0,0.12);
}

.learning-card .card-image {
    position: relative;
    height: 160px;
    overflow: hidden;
}

.learning-card .card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.learning-card:hover .card-image img {
    transform: scale(1.05);
}

.learning-card .card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    padding: 12px;
}

.learning-card .badge-category {
    background: rgba(255,255,255,0.95);
    color: var(--primary, #4154f1);
    padding: 0.35rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.learning-card .card-content {
    padding: 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.learning-card .card-title {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    margin-bottom: 0.75rem;
    font-size: 1rem;
    line-height: 1.4;
}

.learning-card .card-meta {
    display: flex;
    gap: 1rem;
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
    margin-bottom: 1rem;
}

.learning-card .progress-wrapper {
    margin-top: auto;
}

.learning-card .progress-label {
    font-size: 0.75rem;
    color: var(--text-muted, #6c757d);
}

.learning-card .progress-percent {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--primary, #4154f1);
}

.empty-state .empty-icon i {
    color: var(--text-muted, #ccc);
}

/* Dark mode */
[data-theme="dark"] .learning-card,
[data-theme="dark"] .stat-card-mini {
    background: var(--bg-white);
}
</style>
@endsection

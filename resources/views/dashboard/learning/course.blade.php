@extends('dashboard.main')

@section('title', $kelas->judul)

@section('content')
<div class="course-page">
    <!-- Course Header -->
    <div class="course-header mb-4">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-2">
                        <li class="breadcrumb-item"><a href="{{ route('learn.index') }}">Kelas Saya</a></li>
                        <li class="breadcrumb-item active">{{ Str::limit($kelas->judul, 30) }}</li>
                    </ol>
                </nav>
                <h4 class="fw-bold mb-2">{{ $kelas->judul }}</h4>
                <div class="course-meta d-flex flex-wrap gap-3 text-muted">
                    <span><i class="bi bi-folder me-1"></i>{{ $kelas->kategori->nama ?? 'Umum' }}</span>
                    <span><i class="bi bi-collection me-1"></i>{{ $totalMaterials }} Materi</span>
                    <span><i class="bi bi-building me-1"></i>{{ $kelas->vendor->nama ?? 'SISKAE' }}</span>
                </div>
            </div>
            <div class="col-lg-4 mt-3 mt-lg-0">
                <div class="progress-card">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="fw-semibold">Progress Anda</span>
                        <span class="text-primary fw-bold">{{ $progressPercent }}%</span>
                    </div>
                    <div class="progress" style="height: 8px;">
                        <div class="progress-bar {{ $progressPercent == 100 ? 'bg-success' : 'bg-primary' }}" 
                             style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <div class="text-muted small mt-2">
                        {{ $completedMaterials }} dari {{ $totalMaterials }} materi selesai
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Sidebar - Modules -->
        <div class="col-lg-4 col-xl-3 order-lg-2">
            <div class="modules-sidebar">
                <div class="sidebar-header">
                    <h6 class="fw-bold mb-0">
                        <i class="bi bi-list-ul me-2"></i>Daftar Materi
                    </h6>
                </div>
                <div class="modules-list">
                    @forelse($modulesWithProgress as $index => $moduleData)
                    <div class="module-item {{ $moduleData['is_complete'] ? 'completed' : '' }}">
                        <div class="module-header" data-bs-toggle="collapse" data-bs-target="#module-{{ $index }}">
                            <div class="module-info">
                                <span class="module-number">{{ $index + 1 }}</span>
                                <div class="module-text">
                                    <span class="module-title">{{ $moduleData['module']->title }}</span>
                                    <span class="module-progress">{{ $moduleData['completed'] }}/{{ $moduleData['total'] }}</span>
                                </div>
                            </div>
                            <i class="bi bi-chevron-down module-toggle"></i>
                        </div>
                        <div class="collapse {{ $index === 0 ? 'show' : '' }}" id="module-{{ $index }}">
                            <div class="materials-list">
                                @foreach($moduleData['materials'] as $material)
                                <a href="{{ route('learn.material', $material->id) }}" 
                                   class="material-item {{ $material->isCompletedByUser(auth()->id()) ? 'completed' : '' }}">
                                    <i class="bi {{ $material->type_icon }}"></i>
                                    <span class="material-title">{{ Str::limit($material->title, 35) }}</span>
                                    @if($material->isCompletedByUser(auth()->id()))
                                        <i class="bi bi-check-circle-fill text-success ms-auto"></i>
                                    @elseif($material->duration)
                                        <span class="material-duration">{{ $material->duration }}m</span>
                                    @endif
                                </a>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4 text-muted">
                        <i class="bi bi-inbox display-4 d-block mb-2"></i>
                        <p class="mb-0">Belum ada materi</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-8 col-xl-9 order-lg-1">
            <div class="course-content-card">
                <div class="course-image mb-4">
                    <img src="{{ url('images/galerikelas/'.$kelas->foto) }}" alt="{{ $kelas->judul }}">
                </div>
                
                <h5 class="fw-bold mb-3">Tentang Kelas Ini</h5>
                <div class="course-description mb-4">
                    {!! nl2br(e($kelas->deskripsi)) !!}
                </div>

                @if($firstMaterial)
                <div class="start-learning-cta">
                    <div class="cta-content">
                        <h6 class="fw-bold mb-1">Siap untuk belajar?</h6>
                        <p class="text-muted mb-0">Mulai dari materi pertama</p>
                    </div>
                    <a href="{{ route('learn.material', $firstMaterial->id) }}" class="btn btn-gradient-primary">
                        <i class="bi bi-play-fill me-2"></i>Mulai Belajar
                    </a>
                </div>
                @endif

                {{-- Certificate CTA - Show when 100% complete --}}
                @if($progressPercent == 100)
                    @php
                        $existingCert = \App\Models\Certificate::getFor(auth()->id(), $kelas->id);
                    @endphp
                    <div class="certificate-cta mt-4">
                        <div class="cta-icon">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <div class="cta-content">
                            <h6 class="fw-bold mb-1">
                                @if($existingCert)
                                    Selamat! Anda sudah mendapatkan sertifikat
                                @else
                                    Selamat! Anda telah menyelesaikan kelas ini
                                @endif
                            </h6>
                            <p class="text-muted mb-0">
                                @if($existingCert)
                                    Sertifikat No: {{ $existingCert->certificate_number }}
                                @else
                                    Klik tombol di bawah untuk mendapatkan sertifikat kelulusan Anda
                                @endif
                            </p>
                        </div>
                        <div class="cta-action">
                            @if($existingCert)
                                <a href="{{ route('certificates.show', $existingCert->id) }}" class="btn btn-success">
                                    <i class="bi bi-eye me-2"></i>Lihat Sertifikat
                                </a>
                                <a href="{{ route('certificates.download', $existingCert->id) }}" class="btn btn-soft-primary">
                                    <i class="bi bi-download me-2"></i>Download
                                </a>
                            @else
                                <form action="{{ route('certificates.generate', $kelas->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-warning">
                                        <i class="bi bi-trophy me-2"></i>Dapatkan Sertifikat
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.course-page {
    padding-bottom: 2rem;
}

.progress-card {
    background: var(--bg-white, #fff);
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.modules-sidebar {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
    position: sticky;
    top: 90px;
}

.sidebar-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color, #e9ecef);
}

.modules-list {
    max-height: 70vh;
    overflow-y: auto;
}

.module-item {
    border-bottom: 1px solid var(--border-color, #f0f0f0);
}

.module-item:last-child {
    border-bottom: none;
}

.module-header {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 1rem 1.25rem;
    cursor: pointer;
    transition: background 0.2s;
}

.module-header:hover {
    background: var(--bg-light, #f8f9fa);
}

.module-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.module-number {
    width: 28px;
    height: 28px;
    border-radius: 8px;
    background: var(--primary, #4154f1);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.8rem;
    font-weight: 600;
}

.module-item.completed .module-number {
    background: #2eca6a;
}

.module-text {
    display: flex;
    flex-direction: column;
}

.module-title {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-primary, #1e293b);
}

.module-progress {
    font-size: 0.75rem;
    color: var(--text-muted, #6c757d);
}

.module-toggle {
    transition: transform 0.3s;
}

.module-header[aria-expanded="true"] .module-toggle {
    transform: rotate(180deg);
}

.materials-list {
    padding: 0.5rem 0;
    background: var(--bg-light, #f8f9fa);
}

.material-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.75rem 1.25rem 0.75rem 3.5rem;
    text-decoration: none;
    color: var(--text-secondary, #5a6169);
    font-size: 0.85rem;
    transition: all 0.2s;
}

.material-item:hover {
    background: rgba(65, 84, 241, 0.08);
    color: var(--primary, #4154f1);
}

.material-item.completed {
    color: var(--text-muted, #6c757d);
}

.material-item i:first-child {
    font-size: 1rem;
    width: 20px;
    text-align: center;
}

.material-title {
    flex-grow: 1;
}

.material-duration {
    font-size: 0.75rem;
    color: var(--text-muted, #999);
}

.course-content-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.course-image {
    border-radius: 12px;
    overflow: hidden;
    max-height: 300px;
}

.course-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.course-description {
    color: var(--text-secondary, #5a6169);
    line-height: 1.8;
}

.start-learning-cta {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: linear-gradient(135deg, rgba(65, 84, 241, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
    border-radius: 12px;
    padding: 1.25rem;
    gap: 1rem;
    flex-wrap: wrap;
}

.cta-content p {
    font-size: 0.875rem;
}

.certificate-cta {
    display: flex;
    align-items: center;
    gap: 1rem;
    background: linear-gradient(135deg, #fff9e6 0%, #fff3cc 100%);
    border: 2px solid #ffd700;
    border-radius: 12px;
    padding: 1.25rem;
    flex-wrap: wrap;
}

.certificate-cta .cta-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #ffd700 0%, #ffb700 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #1a1a2e;
    flex-shrink: 0;
}

.certificate-cta .cta-content {
    flex-grow: 1;
}

.certificate-cta .cta-content h6 {
    color: #1a1a2e;
}

.certificate-cta .cta-action {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
}

/* Dark mode */
[data-theme="dark"] .modules-sidebar,
[data-theme="dark"] .course-content-card,
[data-theme="dark"] .progress-card {
    background: var(--bg-white);
}

[data-theme="dark"] .materials-list {
    background: rgba(255,255,255,0.03);
}

[data-theme="dark"] .certificate-cta {
    background: rgba(255, 215, 0, 0.1);
}
</style>
@endsection

@extends('dashboard.main')

@section('title', 'Sertifikat Saya')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Sertifikat Saya</h4>
            <p class="text-muted mb-0">Koleksi sertifikat kelulusan Anda</p>
        </div>
        <a href="{{ route('learn.index') }}" class="btn btn-soft-primary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Kelas
        </a>
    </div>

    @if($certificates->isEmpty())
        <!-- Empty State -->
        <div class="empty-state text-center py-5">
            <div class="mb-4">
                <i class="bi bi-award display-1 text-muted"></i>
            </div>
            <h5 class="fw-semibold mb-2">Belum Ada Sertifikat</h5>
            <p class="text-muted mb-4">Selesaikan 100% materi kelas untuk mendapatkan sertifikat.</p>
            <a href="{{ route('learn.index') }}" class="btn btn-gradient-primary">
                <i class="bi bi-play-circle me-2"></i>Mulai Belajar
            </a>
        </div>
    @else
        <!-- Certificates Grid -->
        <div class="row g-4">
            @foreach($certificates as $cert)
            <div class="col-md-6 col-lg-4">
                <div class="certificate-card">
                    <div class="certificate-header">
                        <div class="certificate-badge">
                            <i class="bi bi-award-fill"></i>
                        </div>
                        <span class="certificate-number">{{ $cert->certificate_number }}</span>
                    </div>
                    <div class="certificate-body">
                        <h5 class="certificate-title">{{ $cert->kelas->judul }}</h5>
                        <div class="certificate-meta">
                            <div class="meta-item">
                                <i class="bi bi-calendar3"></i>
                                <span>{{ $cert->formatted_issue_date }}</span>
                            </div>
                            <div class="meta-item">
                                <i class="bi bi-patch-check"></i>
                                <span class="text-success">Valid</span>
                            </div>
                        </div>
                    </div>
                    <div class="certificate-footer">
                        <a href="{{ route('certificates.show', $cert->id) }}" class="btn btn-soft-primary flex-grow-1">
                            <i class="bi bi-eye me-1"></i>Lihat
                        </a>
                        <a href="{{ route('certificates.download', $cert->id) }}" class="btn btn-gradient-primary flex-grow-1">
                            <i class="bi bi-download me-1"></i>Download
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<style>
.certificate-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    border: 2px solid transparent;
}

.certificate-card:hover {
    transform: translateY(-4px);
    border-color: #ffd700;
    box-shadow: 0 12px 30px rgba(255, 215, 0, 0.2);
}

.certificate-header {
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
    padding: 1.25rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.certificate-badge {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, #ffd700 0%, #ffb700 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #1a1a2e;
}

.certificate-number {
    font-family: 'Courier New', monospace;
    font-size: 0.8rem;
    color: rgba(255,255,255,0.8);
    letter-spacing: 1px;
}

.certificate-body {
    padding: 1.25rem;
}

.certificate-title {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    margin-bottom: 1rem;
    line-height: 1.4;
}

.certificate-meta {
    display: flex;
    gap: 1rem;
}

.certificate-meta .meta-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.85rem;
    color: var(--text-muted, #6c757d);
}

.certificate-footer {
    padding: 1rem 1.25rem;
    border-top: 1px solid var(--border-color, #e9ecef);
    display: flex;
    gap: 0.75rem;
}

.empty-state i {
    opacity: 0.3;
}
</style>
@endsection

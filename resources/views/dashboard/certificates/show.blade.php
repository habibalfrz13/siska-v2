@extends('dashboard.main')

@section('title', 'Sertifikat - ' . $certificate->kelas->judul)

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">
        <div>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-1">
                    <li class="breadcrumb-item"><a href="{{ route('certificates.index') }}">Sertifikat</a></li>
                    <li class="breadcrumb-item active">{{ $certificate->certificate_number }}</li>
                </ol>
            </nav>
            <h4 class="fw-bold mb-0">Preview Sertifikat</h4>
        </div>
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-soft-secondary" onclick="copyVerificationLink()">
                <i class="bi bi-link-45deg me-1"></i>Salin Link
            </button>
            <a href="{{ route('certificates.download', $certificate->id) }}" class="btn btn-gradient-primary">
                <i class="bi bi-download me-2"></i>Download PDF
            </a>
        </div>
    </div>

    <!-- Certificate Preview -->
    <div class="certificate-preview-wrapper">
        <div class="certificate-preview">
            <!-- Border Frame -->
            <div class="cert-border">
                <div class="cert-inner">
                    <!-- Header -->
                    <div class="cert-header">
                        <div class="cert-logo">
                            <i class="bi bi-mortarboard-fill"></i>
                        </div>
                        <h1 class="cert-title">SERTIFIKAT</h1>
                        <p class="cert-subtitle">TANDA KELULUSAN</p>
                    </div>

                    <!-- Body -->
                    <div class="cert-body">
                        <p class="cert-text">Diberikan kepada:</p>
                        <h2 class="cert-name">{{ $certificate->user->name }}</h2>
                        <p class="cert-text mt-4">Atas keberhasilannya menyelesaikan kelas:</p>
                        <h3 class="cert-course">{{ $certificate->kelas->judul }}</h3>
                        <p class="cert-vendor">Diselenggarakan oleh {{ $certificate->kelas->vendor->nama ?? 'SISKAE' }}</p>
                    </div>

                    <!-- Footer -->
                    <div class="cert-footer">
                        <div class="cert-date">
                            <p class="label">Tanggal Penerbitan</p>
                            <p class="value">{{ $certificate->formatted_issue_date }}</p>
                        </div>
                        <div class="cert-signature">
                            <div class="signature-line">
                                <span class="signature-initial">H</span>
                            </div>
                            <p class="signature-name">Direktur SISKAE</p>
                        </div>
                        <div class="cert-number">
                            <p class="label">No. Sertifikat</p>
                            <p class="value">{{ $certificate->certificate_number }}</p>
                        </div>
                    </div>

                    <!-- Seal -->
                    <div class="cert-seal">
                        <i class="bi bi-patch-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Verification Info -->
    <div class="verification-info mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="info-card">
                    <div class="info-icon">
                        <i class="bi bi-shield-check"></i>
                    </div>
                    <div class="info-content">
                        <h6 class="fw-semibold mb-1">Verifikasi Sertifikat</h6>
                        <p class="text-muted small mb-2">Link ini dapat dibagikan untuk memverifikasi keaslian sertifikat:</p>
                        <div class="verification-link">
                            <code id="verification-url">{{ $certificate->verification_url }}</code>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.certificate-preview-wrapper {
    display: flex;
    justify-content: center;
    padding: 2rem;
    background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
    border-radius: 16px;
}

.certificate-preview {
    background: #fff;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15);
    width: 100%;
    max-width: 900px;
    aspect-ratio: 1.414;
}

.cert-border {
    height: 100%;
    padding: 20px;
    background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
}

.cert-inner {
    height: 100%;
    background: #fff;
    border: 3px solid #c9a227;
    padding: 40px;
    position: relative;
    display: flex;
    flex-direction: column;
}

.cert-header {
    text-align: center;
    margin-bottom: 30px;
}

.cert-logo {
    width: 70px;
    height: 70px;
    margin: 0 auto 15px;
    background: linear-gradient(135deg, #c9a227 0%, #f4d03f 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2rem;
    color: #1a1a2e;
}

.cert-title {
    font-size: 2.5rem;
    font-weight: 700;
    color: #1a1a2e;
    letter-spacing: 8px;
    margin: 0;
}

.cert-subtitle {
    font-size: 1rem;
    color: #6c757d;
    letter-spacing: 4px;
    margin: 5px 0 0;
}

.cert-body {
    text-align: center;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.cert-text {
    color: #6c757d;
    margin: 0;
    font-size: 1rem;
}

.cert-name {
    font-size: 2rem;
    font-weight: 700;
    color: #1a1a2e;
    margin: 10px 0;
    border-bottom: 2px solid #c9a227;
    padding-bottom: 10px;
    display: inline-block;
}

.cert-course {
    font-size: 1.3rem;
    font-weight: 600;
    color: #16213e;
    margin: 10px 0 5px;
}

.cert-vendor {
    color: #6c757d;
    font-size: 0.9rem;
    font-style: italic;
}

.cert-footer {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    margin-top: 30px;
    padding-top: 20px;
    border-top: 1px solid #e9ecef;
}

.cert-footer .label {
    font-size: 0.75rem;
    color: #6c757d;
    margin: 0;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.cert-footer .value {
    font-size: 0.9rem;
    font-weight: 600;
    color: #1a1a2e;
    margin: 5px 0 0;
}

.cert-signature {
    text-align: center;
}

.signature-line {
    width: 150px;
    height: 60px;
    border-bottom: 2px solid #1a1a2e;
    margin: 0 auto;
    display: flex;
    align-items: flex-end;
    justify-content: center;
    padding-bottom: 5px;
}

.signature-initial {
    font-family: 'Brush Script MT', cursive;
    font-size: 3rem;
    color: #1a1a2e;
    line-height: 1;
}

.signature-name {
    font-size: 0.8rem;
    font-weight: 600;
    color: #1a1a2e;
    margin: 8px 0 0;
}

.cert-seal {
    position: absolute;
    bottom: 60px;
    right: 60px;
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #c9a227 0%, #f4d03f 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    color: #1a1a2e;
    box-shadow: 0 4px 15px rgba(201, 162, 39, 0.4);
}

.info-card {
    background: var(--bg-white, #fff);
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    gap: 1rem;
    align-items: flex-start;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
}

.info-icon {
    width: 48px;
    height: 48px;
    background: rgba(46, 202, 106, 0.1);
    color: #2eca6a;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.verification-link {
    background: var(--bg-light, #f8f9fa);
    padding: 0.5rem 1rem;
    border-radius: 8px;
    overflow-x: auto;
}

.verification-link code {
    font-size: 0.85rem;
    color: var(--primary, #4154f1);
}

@media (max-width: 768px) {
    .certificate-preview {
        aspect-ratio: auto;
        min-height: 500px;
    }
    
    .cert-title {
        font-size: 1.5rem;
        letter-spacing: 3px;
    }
    
    .cert-name {
        font-size: 1.3rem;
    }
    
    .cert-footer {
        flex-direction: column;
        gap: 1.5rem;
        align-items: center;
    }
}
</style>
@endsection

@push('scripts')
<script>
function copyVerificationLink() {
    const url = document.getElementById('verification-url').textContent;
    navigator.clipboard.writeText(url).then(() => {
        alert('Link verifikasi berhasil disalin!');
    });
}
</script>
@endpush

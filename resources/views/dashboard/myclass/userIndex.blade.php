@extends('dashboard.main')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Kelas Saya</h4>
            <p class="text-muted mb-0">Kelola dan pantau progres kelas yang Anda ikuti</p>
        </div>
        <a href="{{ route('kelas.userIndex') }}" class="btn btn-gradient-primary">
            <i class="bi bi-plus-lg me-2"></i>Tambah Kelas
        </a>
    </div>

    @if ($myclass->isEmpty())
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-mortarboard"></i>
        </div>
        <h5>Belum Ada Kelas yang Diikuti</h5>
        <p>Anda belum terdaftar di kelas manapun. Mulai tingkatkan skill Anda sekarang!</p>
        <a href="{{ route('kelas.userIndex') }}" class="btn btn-gradient-primary">
            <i class="bi bi-search me-2"></i>Jelajahi Kelas
        </a>
    </div>
    @else
    <!-- Class Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        @foreach ($myclass as $class)
        @php
            $data = App\Models\Kelas::where('id', $class->kelas_id)->first();
        @endphp
        <div class="col">
            <div class="myclass-card">
                <div class="myclass-image">
                    <img src="{{ url('images/galerikelas/'.$class->kelas->foto) }}" alt="{{ $class->kelas->judul }}">
                    <span class="myclass-status {{ $class->status == 'Aktif' ? 'active' : ($class->status == 'Pending' ? 'pending' : 'inactive') }}">
                        @if($class->status == 'Aktif')
                            <i class="bi bi-check-circle me-1"></i>
                        @elseif($class->status == 'Pending')
                            <i class="bi bi-clock me-1"></i>
                        @else
                            <i class="bi bi-exclamation-circle me-1"></i>
                        @endif
                        {{ $class->status }}
                    </span>
                </div>
                <div class="myclass-body">
                    <h5 class="myclass-title">{{ $class->kelas->judul }}</h5>
                    
                    <div class="myclass-info">
                        <div class="info-row">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ \Carbon\Carbon::parse($class->kelas->pelaksanaan)->format('d M Y') }}</span>
                        </div>
                        <div class="info-row">
                            <i class="bi bi-building"></i>
                            <span>{{ $class->kelas->vendor->nama ?? 'SISKAE' }}</span>
                        </div>
                    </div>
                    
                    <div class="myclass-actions">
                        @if($class->status == 'Aktif')
                            <a href="{{ route('learn.course', $class->kelas_id) }}" class="btn btn-soft-success flex-grow-1">
                                <i class="bi bi-play-circle me-2"></i>Masuk Kelas
                            </a>
                            <button type="button" class="btn btn-soft-primary" 
                                    data-bs-toggle="modal" data-bs-target="#detailModal{{ $class->id }}">
                                <i class="bi bi-info-circle"></i>
                            </button>
                        @elseif($class->status == 'Tidak Aktif')
                            <a href="{{ route('transaksi.userIndex', $class->id) }}" class="btn btn-soft-warning flex-grow-1">
                                <i class="bi bi-credit-card me-2"></i>Bayar Sekarang
                            </a>
                        @elseif($class->status == 'Pending')
                            <button type="button" class="btn btn-soft-primary flex-grow-1" 
                                    data-bs-toggle="modal" data-bs-target="#detailModal{{ $class->id }}">
                                <i class="bi bi-hourglass-split me-2"></i>Menunggu Pembayaran
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Detail Modal -->
        <div class="modal fade" id="detailModal{{ $class->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">
                                <i class="bi bi-info-circle me-2"></i>Detail Kelas
                            </h5>
                            <small class="opacity-75">{{ $class->kelas->judul }}</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="registration-summary">
                            <div class="summary-item">
                                <span class="text-muted">Judul Kelas</span>
                                <span class="fw-semibold">{{ $class->kelas->judul }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="text-muted">Kategori</span>
                                <span>{{ $class->kelas->kategori->nama ?? '-' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="text-muted">Vendor</span>
                                <span>{{ $class->kelas->vendor->nama ?? 'SISKAE' }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="text-muted">Kuota</span>
                                <span>{{ $class->kelas->kuota }} Peserta</span>
                            </div>
                            <div class="summary-item">
                                <span class="text-muted">Pelaksanaan</span>
                                <span>{{ \Carbon\Carbon::parse($class->kelas->pelaksanaan)->format('d M Y') }}</span>
                            </div>
                            <div class="summary-item">
                                <span class="text-muted">Status Anda</span>
                                <span class="status-badge {{ $class->status == 'Aktif' ? 'success' : ($class->status == 'Pending' ? 'warning' : 'danger') }}">
                                    {{ $class->status }}
                                </span>
                            </div>
                        </div>
                        
                        @if($class->kelas->deskripsi)
                        <div class="mt-3">
                            <h6 class="fw-semibold mb-2">Deskripsi</h6>
                            <p class="text-muted small mb-0">{{ $class->kelas->deskripsi }}</p>
                        </div>
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                            <i class="bi bi-x-lg me-1"></i>Tutup
                        </button>
                        @if($class->status == 'Aktif')
                        <a href="{{ route('learn.course', $class->kelas_id) }}" class="btn btn-gradient-primary">
                            <i class="bi bi-play-circle me-1"></i>Masuk Kelas
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection


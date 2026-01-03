@extends('dashboard.main')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="fw-bold mb-1">Daftar Kelas Tersedia</h4>
            <p class="text-muted mb-0">Pilih kelas yang ingin Anda ikuti</p>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
            <i class="bi bi-journal-bookmark me-1"></i> {{ count($kelas) }} Kelas
        </span>
    </div>

    @if(count($kelas) == 0)
    <!-- Empty State -->
    <div class="empty-state">
        <div class="empty-icon">
            <i class="bi bi-journal-x"></i>
        </div>
        <h5>Tidak Ada Kelas Tersedia</h5>
        <p>Saat ini belum ada kelas yang tersedia untuk Anda ikuti.</p>
        <a href="{{ route('home') }}" class="btn btn-soft-primary">
            <i class="bi bi-arrow-left me-2"></i>Kembali ke Dashboard
        </a>
    </div>
    @else
    <!-- Class Grid -->
    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 row-cols-xl-4 g-4">
        @foreach ($kelas as $item)
        <div class="col">
            <div class="class-card">
                <div class="class-image">
                    <img src="{{ url('images/galerikelas/'.$item->foto) }}" alt="{{ $item->judul }}">
                    <span class="class-badge">
                        <i class="bi bi-bookmark-star me-1"></i>{{ $item->kategori->nama ?? 'Kelas' }}
                    </span>
                    <span class="class-price">Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                </div>
                <div class="class-body">
                    <h5 class="class-title">{{ $item->judul }}</h5>
                    <p class="class-desc">{{ Str::limit(strip_tags($item->deskripsi), 80, '...') }}</p>
                    
                    <div class="class-meta">
                        <div class="meta-item">
                            <i class="bi bi-people"></i>
                            <span>{{ $item->kuota }} Peserta</span>
                        </div>
                        <div class="meta-item">
                            <i class="bi bi-calendar3"></i>
                            <span>{{ \Carbon\Carbon::parse($item->pelaksanaan)->format('d M Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="class-actions">
                        <button type="button" class="btn btn-gradient-primary flex-grow-1" 
                                data-bs-toggle="modal" data-bs-target="#registerModal{{ $item->id }}">
                            <i class="bi bi-person-plus me-2"></i>Daftar
                        </button>
                        <a href="{{ route('kelas.show', $item->id) }}" class="btn btn-soft-primary">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Registration Modal -->
        <div class="modal fade" id="registerModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div>
                            <h5 class="modal-title">
                                <i class="bi bi-person-plus me-2"></i>Pendaftaran Kelas
                            </h5>
                            <small class="opacity-75">Konfirmasi data pendaftaran Anda</small>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('myclass.store') }}" enctype="multipart/form-data">
                        @csrf
                        @method('POST')
                        <div class="modal-body">
                            <!-- Class Summary -->
                            <div class="registration-summary mb-4">
                                <div class="summary-item">
                                    <span class="text-muted">Nama Peserta</span>
                                    <span class="fw-semibold">{{ $biodata->username ?? Auth::user()->name }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="text-muted">Kelas</span>
                                    <span class="fw-semibold">{{ $item->judul }}</span>
                                </div>
                                <div class="summary-item">
                                    <span class="text-muted">Pelaksanaan</span>
                                    <span>{{ \Carbon\Carbon::parse($item->pelaksanaan)->format('d M Y') }}</span>
                                </div>
                                <div class="summary-item">
                                    <span>Total Pembayaran</span>
                                    <span>Rp {{ number_format($item->harga, 0, ',', '.') }}</span>
                                </div>
                            </div>

                            <input type="hidden" name="nama_peserta" value="{{ $biodata->username ?? Auth::user()->name }}">
                            <input type="hidden" name="judul" value="{{ $item->judul }}">
                            <input type="hidden" name="harga" value="{{ $item->harga }}">
                            <input type="hidden" name="pelaksanaan" value="{{ $item->pelaksanaan }}">
                            <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                            <input type="hidden" name="kelas_id" value="{{ $item->id }}">
                            
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle me-2"></i>
                                <div>Dengan mendaftar, Anda setuju untuk mengikuti kelas ini sesuai jadwal yang telah ditentukan.</div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                <i class="bi bi-x-lg me-1"></i>Batal
                            </button>
                            <button type="submit" class="btn btn-gradient-primary">
                                <i class="bi bi-check-lg me-1"></i>Konfirmasi Pendaftaran
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @endif
</div>
@endsection


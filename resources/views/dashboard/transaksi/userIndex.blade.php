@extends('dashboard.main')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6 col-md-8">
            <!-- Payment Card -->
            <div class="payment-card">
                <div class="payment-header">
                    <div class="mb-2">
                        <i class="bi bi-credit-card fs-2"></i>
                    </div>
                    <h4>Konfirmasi Pembayaran</h4>
                    <p>Selesaikan pembayaran untuk mengaktifkan kelas</p>
                </div>
                
                <div class="payment-body">
                    <!-- Amount Display -->
                    <div class="payment-amount">
                        <div class="label">Total Pembayaran</div>
                        <div class="amount">Rp {{ number_format($transaksi->jumlah_pembayaran, 0, ',', '.') }}</div>
                    </div>
                    
                    <!-- Payment Details -->
                    <div class="payment-details">
                        <div class="detail-row">
                            <span class="detail-label">Nama Kelas</span>
                            <span class="detail-value">{{ $transaksi->kelas->judul }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Kategori</span>
                            <span class="detail-value">{{ $transaksi->kelas->kategori->nama ?? 'Umum' }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Pelaksanaan</span>
                            <span class="detail-value">{{ \Carbon\Carbon::parse($transaksi->kelas->pelaksanaan)->format('d M Y') }}</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Status</span>
                            <span class="status-badge {{ $transaksi->status_pembayaran == 'Berhasil' ? 'success' : ($transaksi->status_pembayaran == 'Pending' ? 'warning' : 'danger') }}">
                                @if($transaksi->status_pembayaran == 'Pending')
                                    <i class="bi bi-clock me-1"></i>
                                @elseif($transaksi->status_pembayaran == 'Berhasil')
                                    <i class="bi bi-check-circle me-1"></i>
                                @else
                                    <i class="bi bi-x-circle me-1"></i>
                                @endif
                                {{ $transaksi->status_pembayaran }}
                            </span>
                        </div>
                    </div>
                    
                    @if ($transaksi->status_pembayaran != 'Gagal')
                    <!-- Payment Buttons -->
                    <div class="d-grid gap-3">
                        <button class="btn-payment" id="pay-button">
                            <i class="bi bi-shield-check me-2"></i>Bayar Sekarang
                        </button>
                        <a href="{{ route('transaksi.batalkan', $transaksi->id) }}" class="btn btn-soft-danger text-center py-3">
                            <i class="bi bi-x-circle me-2"></i>Batalkan Transaksi
                        </a>
                    </div>
                    @else
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <div>Transaksi ini telah dibatalkan atau gagal.</div>
                    </div>
                    <a href="{{ route('myclass.userIndex') }}" class="btn btn-soft-primary w-100">
                        <i class="bi bi-arrow-left me-2"></i>Kembali ke Kelas Saya
                    </a>
                    @endif
                    
                    <!-- Security Badge -->
                    <div class="text-center mt-4">
                        <div class="d-flex align-items-center justify-content-center gap-2 text-muted small">
                            <i class="bi bi-lock"></i>
                            <span>Pembayaran aman diproses oleh Midtrans</span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Back Link -->
            <div class="text-center mt-3">
                <a href="{{ route('myclass.userIndex') }}" class="text-muted text-decoration-none">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Kelas Saya
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        const payButton = document.getElementById('pay-button');
        const snapToken = '{{ $transaksi->snap_token ?? '' }}';
        
        if (payButton) {
            payButton.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Validate snap token exists
                if (!snapToken || snapToken === '') {
                    alert('Token pembayaran tidak tersedia. Silakan refresh halaman atau hubungi admin.');
                    console.error('Snap token is empty or null');
                    return;
                }
                
                // Check if snap object exists
                if (typeof snap === 'undefined') {
                    alert('Sistem pembayaran sedang tidak tersedia. Silakan coba beberapa saat lagi.');
                    console.error('Midtrans Snap.js not loaded');
                    return;
                }
                
                // Process payment
                snap.pay(snapToken, {
                    onSuccess: function(result) {
                        console.log('Payment success:', result);
                        window.location.href = '{{ route('transaksi.sukses', $transaksi->id) }}';
                    },
                    onPending: function(result) {
                        console.log('Payment pending:', result);
                        alert('Pembayaran pending. Silakan selesaikan pembayaran Anda.');
                    },
                    onError: function(result) {
                        console.error('Payment error:', result);
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        console.log('Payment popup closed');
                    }
                });
            });
        }
    });
</script>
@endpush


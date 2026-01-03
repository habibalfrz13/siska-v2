@extends('dashboard.main')

@section('content')

<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center">
                <div>
                    <h4 class="fw-bold mb-1">Selamat Datang, {{ Auth::user()->name }}! 👋</h4>
                    <p class="text-muted mb-0">
                        @if(Auth::user()->id_role == 1)
                            Berikut adalah ringkasan data sistem SISKAE hari ini.
                        @else
                            Siap untuk meningkatkan skill Anda hari ini?
                        @endif
                    </p>
                </div>
                <div class="mt-3 mt-md-0">
                    <span class="badge bg-primary bg-opacity-10 text-primary px-3 py-2">
                        <i class="bi bi-calendar3 me-1"></i>
                        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->id_role == 1)
<!-- Admin Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card gradient-primary animate-fade-in-up">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label mb-2">Total Kelas</p>
                    <h2 class="stat-value">{{ $total }}</h2>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="stat-trend">
                    <i class="bi bi-check-circle me-1"></i> {{ $aktif }} Aktif
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card gradient-success animate-fade-in-up" style="animation-delay: 0.1s;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label mb-2">Transaksi Berhasil</p>
                    <h2 class="stat-value">{{ $successfulSales }}</h2>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-check2-circle"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="stat-trend up">
                    <i class="bi bi-graph-up-arrow me-1"></i> Aktif
                </span>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card gradient-warning animate-fade-in-up" style="animation-delay: 0.2s;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label mb-2">Total Pendapatan</p>
                    <h2 class="stat-value" style="font-size: 1.5rem;">{{ 'Rp ' . number_format($totalRevenue, 0, ',', '.') }}</h2>
                </div>
                <div class="stat-icon">
                    <i class="bi bi-wallet2"></i>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('transaksi.cetaksuccess') }}" class="stat-trend text-decoration-none">
                    <i class="bi bi-printer me-1"></i> Cetak Laporan
                </a>
            </div>
        </div>
    </div>
    
    <div class="col-sm-6 col-xl-3">
        <div class="stat-card gradient-info animate-fade-in-up" style="animation-delay: 0.3s;">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <p class="stat-label mb-2">Cuaca Hari Ini</p>
                    <h2 class="stat-value" id="weather-temp">--°C</h2>
                </div>
                <div class="stat-icon" id="weather-icon-box">
                    <i class="bi bi-cloud-sun"></i>
                </div>
            </div>
            <div class="mt-3">
                <span class="stat-trend" id="weather-desc">Memuat data...</span>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row mb-4">
    <div class="col-12">
        <h5 class="fw-bold mb-3">Aksi Cepat</h5>
        <div class="row g-3">
            <div class="col-6 col-md-3">
                <a href="{{ route('kelas.create') }}" class="quick-action">
                    <div class="action-icon bg-primary">
                        <i class="bi bi-plus-lg"></i>
                    </div>
                    <div>
                        <span class="fw-semibold">Tambah Kelas</span>
                        <small class="d-block text-muted">Buat kelas baru</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('transaksi.index') }}" class="quick-action">
                    <div class="action-icon bg-success">
                        <i class="bi bi-credit-card"></i>
                    </div>
                    <div>
                        <span class="fw-semibold">Transaksi</span>
                        <small class="d-block text-muted">Kelola pembayaran</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('peserta.index') }}" class="quick-action">
                    <div class="action-icon bg-warning">
                        <i class="bi bi-people"></i>
                    </div>
                    <div>
                        <span class="fw-semibold">Peserta</span>
                        <small class="d-block text-muted">Lihat data peserta</small>
                    </div>
                </a>
            </div>
            <div class="col-6 col-md-3">
                <a href="{{ route('user.index') }}" class="quick-action">
                    <div class="action-icon bg-danger">
                        <i class="bi bi-person-gear"></i>
                    </div>
                    <div>
                        <span class="fw-semibold">Pengguna</span>
                        <small class="d-block text-muted">Kelola akun</small>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Charts Row -->
<div class="row g-4 mb-4">
    <!-- Sertifikasi Chart -->
    <div class="col-lg-4">
        <div class="chart-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="chart-title mb-0">Status Sertifikasi</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary">{{ $total }} Total</span>
            </div>
            <div class="d-flex justify-content-center">
                <div style="width: 200px; height: 200px;">
                    <canvas id="certificationChart"></canvas>
                </div>
            </div>
            <div class="row text-center mt-3 pt-3 border-top">
                <div class="col-6">
                    <span class="d-block text-success fw-bold">{{ $aktif }}</span>
                    <small class="text-muted">Aktif</small>
                </div>
                <div class="col-6">
                    <span class="d-block text-danger fw-bold">{{ $noaktif }}</span>
                    <small class="text-muted">Tidak Aktif</small>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Sales Chart -->
    <div class="col-lg-4">
        <div class="chart-card h-100">
            <h6 class="chart-title">Penjualan Kelas</h6>
            <div style="height: 220px;">
                <canvas id="salesChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Revenue Chart -->
    <div class="col-lg-4">
        <div class="chart-card h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="chart-title mb-0">Pendapatan</h6>
                <a href="{{ route('transaksi.cetaksuccess') }}" class="btn btn-sm btn-soft-primary">
                    <i class="bi bi-printer me-1"></i> Cetak
                </a>
            </div>
            <div style="height: 220px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
</div>
@endif

<!-- Daftar Sertifikasi Table -->
<div class="row">
    <div class="col-12">
        <div class="modern-card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-semibold">
                    <i class="bi bi-list-check me-2 text-primary"></i>
                    Daftar Sertifikasi
                </h5>
                @if(auth()->user()->id_role == 1)
                <a href="{{ route('kelas.create') }}" class="btn btn-sm btn-gradient-primary">
                    <i class="bi bi-plus-lg me-1"></i> Tambah Baru
                </a>
                @endif
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover modern-table" id="example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Sertifikasi</th>
                                <th>Pelaksanaan</th>
                                <th>Status</th>
                                @if(auth()->user()->id_role == 1)
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($certifications as $index => $certification)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td class="fw-semibold">{{ $certification->judul }}</td>
                                    <td>{{ \Carbon\Carbon::parse($certification->pelaksanaan)->format('d M Y') }}</td>
                                    <td>
                                        @if($certification->status == 'Aktif')
                                            <span class="status-badge success">
                                                <i class="bi bi-check-circle me-1"></i>{{ $certification->status }}
                                            </span>
                                        @else
                                            <span class="status-badge danger">
                                                <i class="bi bi-x-circle me-1"></i>{{ $certification->status }}
                                            </span>
                                        @endif
                                    </td>
                                    @if(auth()->user()->id_role == 1)
                                    <td>
                                        <a href="{{ route('kelas.show', $certification->id) }}" class="btn btn-sm btn-soft-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Chart defaults
    Chart.defaults.font.family = "'Nunito', sans-serif";
    Chart.defaults.color = '#6c757d';

    // Certification Chart
    const certificationCtx = document.getElementById('certificationChart').getContext('2d');
    new Chart(certificationCtx, {
        type: 'doughnut',
        data: {
            labels: ['Aktif', 'Tidak Aktif'],
            datasets: [{
                data: [{{ $aktif }}, {{ $noaktif }}],
                backgroundColor: [
                    'rgba(46, 202, 106, 0.85)',
                    'rgba(234, 84, 85, 0.85)'
                ],
                borderWidth: 0,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            cutout: '70%',
            plugins: {
                legend: { display: false }
            }
        }
    });

    @if(auth()->user()->id_role == 1)
    // Sales Chart
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    new Chart(salesCtx, {
        type: 'bar',
        data: {
            labels: ['Penjualan Berhasil'],
            datasets: [{
                label: 'Total',
                data: [{{ $successfulSales }}],
                backgroundColor: 'rgba(65, 84, 241, 0.85)',
                borderRadius: 8,
                barThickness: 60
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' }
                },
                x: { grid: { display: false } }
            }
        }
    });

    // Revenue Chart
    const revenueCtx = document.getElementById('revenueChart').getContext('2d');
    new Chart(revenueCtx, {
        type: 'bar',
        data: {
            labels: ['Total Pendapatan'],
            datasets: [{
                label: 'Pendapatan',
                data: [{{ $totalRevenue }}],
                backgroundColor: 'rgba(255, 159, 67, 0.85)',
                borderRadius: 8,
                barThickness: 60
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { 
                    beginAtZero: true,
                    grid: { color: 'rgba(0,0,0,0.05)' },
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                },
                x: { grid: { display: false } }
            }
        }
    });
    @endif

    // Weather API
    const apiKey = '2bb008a2dee92d080615c7975ccf5bfa';
    const city = 'Padang';
    const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=id`;

    fetch(apiUrl)
    .then(response => response.json())
    .then(data => {
        document.getElementById('weather-temp').textContent = Math.round(data.main.temp) + '°C';
        const desc = data.weather[0].description;
        document.getElementById('weather-desc').textContent = desc.charAt(0).toUpperCase() + desc.slice(1);
        
        // Update icon
        const iconCode = data.weather[0].icon;
        const iconUrl = `https://openweathermap.org/img/wn/${iconCode}.png`;
        document.getElementById('weather-icon-box').innerHTML = `<img src="${iconUrl}" alt="Weather" style="width: 40px;">`;
    })
    .catch(error => {
        console.log('Error fetching weather:', error);
        document.getElementById('weather-desc').textContent = 'Data tidak tersedia';
    });
</script>
@endpush

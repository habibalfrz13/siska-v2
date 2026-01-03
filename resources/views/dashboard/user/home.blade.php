@extends('dashboard.main')

@section('title', 'Dashboard')

@section('content')
<div class="user-dashboard">
    <!-- Hero Welcome Section -->
    <div class="welcome-hero mb-4">
        <div class="welcome-content">
            <div class="welcome-text">
                <span class="welcome-greeting">Selamat {{ $greeting }}, {{ Auth::user()->name }}! 👋</span>
                <h2 class="welcome-headline">Apa yang ingin Anda pelajari hari ini?</h2>
                <p class="welcome-desc">Tingkatkan kompetensi Anda dengan sertifikasi terbaik</p>
            </div>
            <div class="welcome-actions">
                <a href="{{ route('kelas.userIndex') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-search me-2"></i>Jelajahi Kelas
                </a>
            </div>
        </div>
        <div class="welcome-illustration">
            <img src="{{ asset('template/dashboard/images/logos/education.svg') }}" alt="Education" onerror="this.style.display='none'">
        </div>
    </div>

    <!-- User Stats -->
    <div class="row g-4 mb-4">
        <div class="col-sm-6 col-lg-3">
            <div class="user-stat-card">
                <div class="stat-icon-wrapper gradient-primary">
                    <i class="bi bi-mortarboard"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $enrolledClasses }}</span>
                    <span class="stat-label">Kelas Diikuti</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="user-stat-card">
                <div class="stat-icon-wrapper gradient-success">
                    <i class="bi bi-patch-check"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $activeClasses }}</span>
                    <span class="stat-label">Kelas Aktif</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="user-stat-card">
                <div class="stat-icon-wrapper gradient-warning">
                    <i class="bi bi-hourglass-split"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $pendingPayments }}</span>
                    <span class="stat-label">Menunggu Bayar</span>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-3">
            <div class="user-stat-card">
                <div class="stat-icon-wrapper gradient-info">
                    <i class="bi bi-award"></i>
                </div>
                <div class="stat-content">
                    <span class="stat-number">{{ $completedClasses }}</span>
                    <span class="stat-label">Sertifikat</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- My Classes Section -->
        <div class="col-lg-8">
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-journal-bookmark me-2 text-primary"></i>Kelas Saya
                    </h5>
                    <a href="{{ route('myclass.userIndex') }}" class="btn btn-sm btn-soft-primary">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body p-0">
                    @if($myClasses->count() > 0)
                        <div class="my-classes-list">
                            @foreach($myClasses->take(4) as $class)
                            <div class="my-class-item">
                                <div class="class-thumb">
                                    <img src="{{ url('images/galerikelas/'.$class->kelas->foto) }}" alt="{{ $class->kelas->judul }}">
                                </div>
                                <div class="class-info">
                                    <h6 class="class-name">{{ $class->kelas->judul }}</h6>
                                    <div class="class-meta-row">
                                        <span class="meta-item">
                                            <i class="bi bi-calendar3"></i>
                                            {{ \Carbon\Carbon::parse($class->kelas->pelaksanaan)->format('d M Y') }}
                                        </span>
                                        <span class="status-pill {{ $class->status == 'Aktif' ? 'active' : ($class->status == 'Pending' ? 'pending' : 'inactive') }}">
                                            {{ $class->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="class-action">
                                    @if($class->status == 'Aktif')
                                        <a href="{{ route('learn.course', $class->kelas_id) }}" class="btn btn-sm btn-gradient-primary">
                                            <i class="bi bi-play-circle"></i>
                                        </a>
                                    @elseif($class->status == 'Tidak Aktif')
                                        <a href="{{ route('transaksi.userIndex', $class->id) }}" class="btn btn-sm btn-soft-warning">
                                            <i class="bi bi-credit-card"></i>
                                        </a>
                                    @else
                                        <span class="btn btn-sm btn-soft-secondary disabled">
                                            <i class="bi bi-clock"></i>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @else
                        <div class="empty-state-mini">
                            <div class="empty-icon">
                                <i class="bi bi-journal-x"></i>
                            </div>
                            <p>Anda belum mengikuti kelas apapun</p>
                            <a href="{{ route('kelas.userIndex') }}" class="btn btn-gradient-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i>Daftar Kelas
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions & Info -->
        <div class="col-lg-4">
            <!-- Quick Actions -->
            <div class="dashboard-card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning me-2 text-warning"></i>Aksi Cepat
                    </h5>
                </div>
                <div class="card-body">
                    <div class="quick-action-grid">
                        <a href="{{ route('kelas.userIndex') }}" class="quick-action-item">
                            <div class="action-icon bg-primary bg-opacity-10 text-primary">
                                <i class="bi bi-search"></i>
                            </div>
                            <span>Cari Kelas</span>
                        </a>
                        <a href="{{ route('myclass.userIndex') }}" class="quick-action-item">
                            <div class="action-icon bg-success bg-opacity-10 text-success">
                                <i class="bi bi-journal-check"></i>
                            </div>
                            <span>Kelas Saya</span>
                        </a>
                        <a href="{{ route('user.profile') }}" class="quick-action-item">
                            <div class="action-icon bg-info bg-opacity-10 text-info">
                                <i class="bi bi-person"></i>
                            </div>
                            <span>Profil</span>
                        </a>
                        @if($pendingPayments > 0)
                        <a href="{{ route('myclass.userIndex') }}" class="quick-action-item">
                            <div class="action-icon bg-warning bg-opacity-10 text-warning position-relative">
                                <i class="bi bi-credit-card"></i>
                                <span class="notification-dot"></span>
                            </div>
                            <span>Pembayaran</span>
                        </a>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Weather Widget -->
            <div class="dashboard-card weather-widget">
                <div class="weather-content" id="weather-widget">
                    <div class="weather-icon">
                        <i class="bi bi-cloud-sun" id="weather-icon"></i>
                    </div>
                    <div class="weather-info">
                        <span class="weather-temp" id="weather-temp">--°C</span>
                        <span class="weather-desc" id="weather-desc">Memuat...</span>
                        <span class="weather-location">
                            <i class="bi bi-geo-alt me-1"></i>Padang
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Classes -->
    @if($recommendedClasses->count() > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="dashboard-card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-stars me-2 text-warning"></i>Rekomendasi Untuk Anda
                    </h5>
                    <a href="{{ route('kelas.userIndex') }}" class="btn btn-sm btn-soft-primary">
                        Lihat Semua <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @foreach($recommendedClasses->take(4) as $kelas)
                        <div class="col-md-6 col-lg-3">
                            <div class="recommended-card">
                                <div class="recommended-image">
                                    <img src="{{ url('images/galerikelas/'.$kelas->foto) }}" alt="{{ $kelas->judul }}">
                                    <span class="recommended-badge">
                                        <i class="bi bi-star-fill me-1"></i>Populer
                                    </span>
                                </div>
                                <div class="recommended-body">
                                    <h6 class="recommended-title">{{ Str::limit($kelas->judul, 40) }}</h6>
                                    <div class="recommended-meta">
                                        <span><i class="bi bi-people"></i> {{ $kelas->kuota }} slot</span>
                                        <span class="recommended-price">Rp {{ number_format($kelas->harga, 0, ',', '.') }}</span>
                                    </div>
                                    <a href="{{ route('kelas.show', $kelas->id) }}" class="btn btn-sm btn-gradient-primary w-100 mt-2">
                                        <i class="bi bi-eye me-1"></i>Detail
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
/* User Dashboard Styles */
.user-dashboard {
    padding-bottom: 2rem;
}

/* Welcome Hero */
.welcome-hero {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    padding: 2.5rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
    overflow: hidden;
    position: relative;
}

.welcome-hero::before {
    content: '';
    position: absolute;
    top: -50%;
    right: -10%;
    width: 400px;
    height: 400px;
    background: rgba(255,255,255,0.1);
    border-radius: 50%;
}

.welcome-content {
    position: relative;
    z-index: 1;
}

.welcome-greeting {
    color: rgba(255,255,255,0.9);
    font-size: 0.95rem;
    display: block;
    margin-bottom: 0.5rem;
}

.welcome-headline {
    color: #fff;
    font-size: 1.75rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
}

.welcome-desc {
    color: rgba(255,255,255,0.85);
    margin-bottom: 1.5rem;
}

.welcome-actions .btn {
    font-weight: 600;
    padding: 0.75rem 1.5rem;
    border-radius: 12px;
}

.welcome-illustration {
    position: relative;
    z-index: 1;
}

.welcome-illustration img {
    max-height: 180px;
    filter: drop-shadow(0 10px 30px rgba(0,0,0,0.2));
}

/* User Stat Cards */
.user-stat-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    padding: 1.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.user-stat-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.stat-icon-wrapper {
    width: 56px;
    height: 56px;
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    color: #fff;
    flex-shrink: 0;
}

.stat-icon-wrapper.gradient-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
.stat-icon-wrapper.gradient-success { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
.stat-icon-wrapper.gradient-warning { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
.stat-icon-wrapper.gradient-info { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

.stat-content {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 1.75rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    line-height: 1.2;
}

.stat-label {
    font-size: 0.85rem;
    color: var(--text-muted, #6c757d);
}

/* Dashboard Cards */
.dashboard-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    overflow: hidden;
}

.dashboard-card .card-header {
    padding: 1.25rem 1.5rem;
    background: transparent;
    border-bottom: 1px solid var(--border-color, #e9ecef);
}

.dashboard-card .card-header h5 {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
}

.dashboard-card .card-body {
    padding: 1.5rem;
}

/* My Classes List */
.my-classes-list {
    padding: 0.5rem 0;
}

.my-class-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.5rem;
    border-bottom: 1px solid var(--border-color, #f0f0f0);
    transition: background 0.2s;
}

.my-class-item:last-child {
    border-bottom: none;
}

.my-class-item:hover {
    background: var(--bg-light, #f8f9fa);
}

.class-thumb {
    width: 60px;
    height: 60px;
    border-radius: 12px;
    overflow: hidden;
    flex-shrink: 0;
}

.class-thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.class-info {
    flex-grow: 1;
    min-width: 0;
}

.class-name {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    margin-bottom: 0.25rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.class-meta-row {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-size: 0.8rem;
}

.class-meta-row .meta-item {
    color: var(--text-muted, #6c757d);
}

.class-meta-row .meta-item i {
    margin-right: 4px;
}

.status-pill {
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-pill.active {
    background: rgba(46, 202, 106, 0.15);
    color: #2eca6a;
}

.status-pill.pending {
    background: rgba(255, 159, 67, 0.15);
    color: #ff9f43;
}

.status-pill.inactive {
    background: rgba(234, 84, 85, 0.15);
    color: #ea5455;
}

/* Quick Action Grid */
.quick-action-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 1rem;
}

.quick-action-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.5rem;
    padding: 1rem;
    border-radius: 12px;
    background: var(--bg-light, #f8f9fa);
    text-decoration: none;
    transition: all 0.2s;
}

.quick-action-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.quick-action-item .action-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.quick-action-item span {
    font-size: 0.85rem;
    font-weight: 500;
    color: var(--text-primary, #1e293b);
}

.notification-dot {
    position: absolute;
    top: -2px;
    right: -2px;
    width: 10px;
    height: 10px;
    background: #ea5455;
    border-radius: 50%;
    border: 2px solid #fff;
}

/* Weather Widget */
.weather-widget {
    background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    color: #fff;
}

.weather-content {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.5rem;
}

.weather-icon i {
    font-size: 3rem;
}

.weather-info {
    display: flex;
    flex-direction: column;
}

.weather-temp {
    font-size: 2rem;
    font-weight: 700;
}

.weather-desc {
    font-size: 0.9rem;
    opacity: 0.9;
    text-transform: capitalize;
}

.weather-location {
    font-size: 0.8rem;
    opacity: 0.8;
    margin-top: 0.25rem;
}

/* Recommended Cards */
.recommended-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.05);
    transition: all 0.3s ease;
    height: 100%;
}

.recommended-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
}

.recommended-image {
    position: relative;
    height: 140px;
    overflow: hidden;
}

.recommended-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s ease;
}

.recommended-card:hover .recommended-image img {
    transform: scale(1.05);
}

.recommended-badge {
    position: absolute;
    top: 10px;
    left: 10px;
    background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    color: #fff;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 600;
}

.recommended-body {
    padding: 1rem;
}

.recommended-title {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    margin-bottom: 0.5rem;
    font-size: 0.9rem;
    line-height: 1.4;
    height: 2.8em;
    overflow: hidden;
}

.recommended-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
}

.recommended-price {
    font-weight: 600;
    color: var(--primary, #4154f1);
}

/* Empty State Mini */
.empty-state-mini {
    text-align: center;
    padding: 2.5rem;
}

.empty-state-mini .empty-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: var(--bg-light, #f8f9fa);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 1.5rem;
    color: var(--text-muted, #6c757d);
}

.empty-state-mini p {
    color: var(--text-muted, #6c757d);
    margin-bottom: 1rem;
}

/* Responsive */
@media (max-width: 768px) {
    .welcome-hero {
        padding: 1.5rem;
        flex-direction: column;
        text-align: center;
    }
    
    .welcome-headline {
        font-size: 1.25rem;
    }
    
    .welcome-illustration {
        display: none;
    }
    
    .stat-number {
        font-size: 1.5rem;
    }
}

/* Dark mode */
[data-theme="dark"] .user-stat-card,
[data-theme="dark"] .dashboard-card,
[data-theme="dark"] .recommended-card {
    background: var(--bg-white);
}

[data-theme="dark"] .quick-action-item {
    background: rgba(255,255,255,0.05);
}
</style>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Weather API
    const apiKey = '2bb008a2dee92d080615c7975ccf5bfa';
    const city = 'Padang';
    
    fetch(`https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=id`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('weather-temp').textContent = Math.round(data.main.temp) + '°C';
            const desc = data.weather[0].description;
            document.getElementById('weather-desc').textContent = desc.charAt(0).toUpperCase() + desc.slice(1);
            
            // Update icon based on weather
            const iconMapping = {
                '01d': 'bi-sun', '01n': 'bi-moon-stars',
                '02d': 'bi-cloud-sun', '02n': 'bi-cloud-moon',
                '03d': 'bi-cloud', '03n': 'bi-cloud',
                '04d': 'bi-clouds', '04n': 'bi-clouds',
                '09d': 'bi-cloud-drizzle', '09n': 'bi-cloud-drizzle',
                '10d': 'bi-cloud-rain', '10n': 'bi-cloud-rain',
                '11d': 'bi-cloud-lightning', '11n': 'bi-cloud-lightning',
                '13d': 'bi-snow', '13n': 'bi-snow',
                '50d': 'bi-cloud-haze', '50n': 'bi-cloud-haze'
            };
            const iconCode = data.weather[0].icon;
            const iconClass = iconMapping[iconCode] || 'bi-cloud-sun';
            document.getElementById('weather-icon').className = 'bi ' + iconClass;
        })
        .catch(error => {
            console.log('Weather error:', error);
            document.getElementById('weather-desc').textContent = 'Data tidak tersedia';
        });
});
</script>
@endpush

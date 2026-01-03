@extends('dashboard.main')

@section('title', 'Profil Saya')

@section('content')
<div class="container-fluid profile-page">
    <div class="profile-header">
        <div class="profile-cover"></div>
        <div class="profile-info-wrapper">
            <div class="profile-avatar-section">
                {{-- PERUBAHAN: Class diganti menjadi page-profile-avatar agar tidak bentrok dengan navbar --}}
                <div class="page-profile-avatar">
                    @if($biodata?->foto)
                        <img src="{{ url('images/profile/'.$biodata->foto) }}" alt="{{ auth()->user()->name }}">
                    @else
                        <div class="page-avatar-placeholder">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <button class="avatar-edit-btn" title="Ubah Foto">
                        <i class="bi bi-camera"></i>
                    </button>
                </div>
                <div class="profile-name-section">
                    <h3 class="profile-name">{{ auth()->user()->name }}</h3>
                    <p class="profile-role">
                        <i class="bi bi-shield-check me-1"></i>
                        {{ auth()->user()->id_role == 1 ? 'Administrator' : 'Member' }}
                    </p>
                </div>
            </div>
            <div class="profile-actions">
                @if($biodata)
                <a href="{{ route('biodata.edit', $biodata->id) }}" class="btn btn-gradient-primary">
                    <i class="bi bi-pencil-square me-2"></i>Edit Profil
                </a>
                @else
                <a href="{{ route('biodata.create') }}" class="btn btn-gradient-primary">
                    <i class="bi bi-plus-circle me-2"></i>Lengkapi Profil
                </a>
                @endif
            </div>
        </div>
    </div>

    @php
        $kelasCount = \App\Models\Myclass::where('user_id', auth()->id())->count();
        $activeKelas = \App\Models\Myclass::where('user_id', auth()->id())->where('status', 'Aktif')->count();
        $certCount = \App\Models\Certificate::where('user_id', auth()->id())->count();
    @endphp
    <div class="row g-3 mb-4">
        <div class="col-6 col-lg-3">
            <div class="profile-stat-card">
                <div class="stat-icon blue">
                    <i class="bi bi-journal-bookmark"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ $kelasCount }}</span>
                    <span class="stat-label">Kelas Terdaftar</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="profile-stat-card">
                <div class="stat-icon green">
                    <i class="bi bi-play-circle"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ $activeKelas }}</span>
                    <span class="stat-label">Kelas Aktif</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="profile-stat-card">
                <div class="stat-icon gold">
                    <i class="bi bi-award"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ $certCount }}</span>
                    <span class="stat-label">Sertifikat</span>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="profile-stat-card">
                <div class="stat-icon purple">
                    <i class="bi bi-calendar3"></i>
                </div>
                <div class="stat-info">
                    <span class="stat-number">{{ auth()->user()->created_at->diffInDays(now()) }}</span>
                    <span class="stat-label">Hari Bergabung</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="info-card mb-4">
                <div class="info-card-header">
                    <h6 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Tentang Saya</h6>
                </div>
                <div class="info-card-body">
                    <p class="bio-text">{{ $biodata->bio ?: 'Belum ada bio. Klik Edit Profil untuk menambahkan.' }}</p>
                </div>
            </div>

            <div class="info-card">
                <div class="info-card-header">
                    <h6 class="mb-0"><i class="bi bi-lightning-charge me-2"></i>Menu Cepat</h6>
                </div>
                <div class="info-card-body p-0">
                    <a href="{{ route('learn.index') }}" class="quick-link">
                        <div class="quick-link-icon blue"><i class="bi bi-play-circle"></i></div>
                        <span>Mulai Belajar</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                    <a href="{{ route('certificates.index') }}" class="quick-link">
                        <div class="quick-link-icon gold"><i class="bi bi-award"></i></div>
                        <span>Sertifikat Saya</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                    <a href="{{ route('myclass.userIndex') }}" class="quick-link">
                        <div class="quick-link-icon green"><i class="bi bi-mortarboard"></i></div>
                        <span>Kelas Saya</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                    <a href="{{ route('biodata.edit', $biodata->id) }}" class="quick-link">
                        <div class="quick-link-icon purple"><i class="bi bi-gear"></i></div>
                        <span>Pengaturan Profil</span>
                        <i class="bi bi-chevron-right ms-auto"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="info-card">
                <div class="info-card-header d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="bi bi-person-vcard me-2"></i>Informasi Pribadi</h6>
                    <a href="{{ route('biodata.edit', $biodata->id) }}" class="btn btn-sm btn-soft-primary">
                        <i class="bi bi-pencil me-1"></i>Edit
                    </a>
                </div>
                <div class="info-card-body">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon"><i class="bi bi-person"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">Username</span>
                                    <span class="detail-value">{{ $user->name }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon"><i class="bi bi-person-badge"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">Nama Lengkap</span>
                                    <span class="detail-value">{{ $biodata->username ?: 'Belum diisi' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon"><i class="bi bi-envelope"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">Email</span>
                                    <span class="detail-value">{{ $user->email }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon"><i class="bi bi-phone"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">Nomor Telepon</span>
                                    <span class="detail-value">{{ $biodata->nomor_telepon ?: 'Belum diisi' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon"><i class="bi bi-gender-ambiguous"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">Jenis Kelamin</span>
                                    <span class="detail-value">{{ $biodata->jenis_kelamin ?: 'Belum diisi' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <div class="detail-icon"><i class="bi bi-calendar-event"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">Bergabung Sejak</span>
                                    <span class="detail-value">{{ auth()->user()->created_at->translatedFormat('d F Y') }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="detail-item">
                                <div class="detail-icon"><i class="bi bi-geo-alt"></i></div>
                                <div class="detail-content">
                                    <span class="detail-label">Alamat</span>
                                    <span class="detail-value">{{ $biodata->alamat ?: 'Belum diisi' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if($activeKelas > 0)
            <div class="info-card mt-4">
                <div class="info-card-header">
                    <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Kelas Aktif Terbaru</h6>
                </div>
                <div class="info-card-body p-0">
                    @php
                        $recentClasses = \App\Models\Myclass::with('kelas')
                            ->where('user_id', auth()->id())
                            ->where('status', 'Aktif')
                            ->latest()
                            ->take(3)
                            ->get();
                    @endphp
                    @foreach($recentClasses as $myClass)
                    <a href="{{ route('learn.course', $myClass->kelas_id) }}" class="activity-item">
                        <div class="activity-image">
                            <img src="{{ url('images/galerikelas/'.$myClass->kelas->foto) }}" alt="">
                        </div>
                        <div class="activity-content">
                            <h6 class="activity-title">{{ Str::limit($myClass->kelas->judul, 40) }}</h6>
                            <span class="activity-meta">
                                <i class="bi bi-calendar3 me-1"></i>
                                {{ \Carbon\Carbon::parse($myClass->kelas->pelaksanaan)->format('d M Y') }}
                            </span>
                        </div>
                        <div class="activity-action">
                            <span class="btn btn-sm btn-soft-primary">Lanjutkan</span>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

<style>
.profile-page {
    padding-bottom: 2rem;
}

/* Profile Header */
.profile-header {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 20px rgba(0,0,0,0.06);
    margin-bottom: 1.5rem;
}

.profile-cover {
    height: 120px;
    background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
}

.profile-info-wrapper {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
    padding: 0 1.5rem 1.5rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.profile-avatar-section {
    display: flex;
    align-items: flex-end;
    gap: 1.25rem;
    margin-top: -50px;
}

/* PERUBAHAN CSS: Class diganti agar tidak menimpa style navbar */
.page-profile-avatar {
    position: relative;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    border: 4px solid var(--bg-white, #fff);
    background: var(--bg-light, #f8f9fa);
    overflow: hidden;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

.page-profile-avatar img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.page-avatar-placeholder {
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, #4154f1 0%, #764ba2 100%);
    color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 2.5rem;
    font-weight: 700;
}

.avatar-edit-btn {
    position: absolute;
    bottom: 5px;
    right: 5px;
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: var(--bg-white, #fff);
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    transition: all 0.2s;
    z-index: 5;
}

.avatar-edit-btn:hover {
    background: var(--primary, #4154f1);
    color: #fff;
}

.profile-name-section {
    padding-bottom: 0.5rem;
}

.profile-name {
    font-weight: 700;
    font-size: 1.5rem;
    color: var(--text-primary, #1e293b);
    margin: 0;
}

.profile-role {
    color: var(--primary, #4154f1);
    font-size: 0.9rem;
    font-weight: 500;
    margin: 0.25rem 0 0;
}

.profile-actions {
    padding-bottom: 0.5rem;
}

/* Stat Cards */
.profile-stat-card {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1.25rem;
    background: var(--bg-white, #fff);
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    transition: all 0.3s ease;
}

.profile-stat-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.08);
}

.stat-icon {
    width: 48px;
    height: 48px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.25rem;
}

.stat-icon.blue { background: rgba(65, 84, 241, 0.1); color: #4154f1; }
.stat-icon.green { background: rgba(46, 202, 106, 0.1); color: #2eca6a; }
.stat-icon.gold { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
.stat-icon.purple { background: rgba(118, 75, 162, 0.1); color: #764ba2; }

.stat-info {
    display: flex;
    flex-direction: column;
}

.stat-number {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--text-primary, #1e293b);
    line-height: 1.2;
}

.stat-label {
    font-size: 0.8rem;
    color: var(--text-muted, #6c757d);
}

/* Info Card */
.info-card {
    background: var(--bg-white, #fff);
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 2px 12px rgba(0,0,0,0.06);
}

.info-card-header {
    padding: 1rem 1.25rem;
    border-bottom: 1px solid var(--border-color, #e9ecef);
}

.info-card-header h6 {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
}

.info-card-body {
    padding: 1.25rem;
}

.bio-text {
    color: var(--text-secondary, #5a6169);
    line-height: 1.7;
    margin: 0;
}

/* Quick Links */
.quick-link {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    text-decoration: none;
    color: var(--text-primary, #1e293b);
    border-bottom: 1px solid var(--border-color, #f0f0f0);
    transition: all 0.2s;
}

.quick-link:last-child {
    border-bottom: none;
}

.quick-link:hover {
    background: var(--bg-light, #f8f9fa);
    color: var(--primary, #4154f1);
}

.quick-link-icon {
    width: 40px;
    height: 40px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1rem;
}

.quick-link-icon.blue { background: rgba(65, 84, 241, 0.1); color: #4154f1; }
.quick-link-icon.green { background: rgba(46, 202, 106, 0.1); color: #2eca6a; }
.quick-link-icon.gold { background: rgba(255, 193, 7, 0.1); color: #ffc107; }
.quick-link-icon.purple { background: rgba(118, 75, 162, 0.1); color: #764ba2; }

/* Detail Item */
.detail-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    background: var(--bg-light, #f8f9fa);
    border-radius: 12px;
    transition: all 0.2s;
}

.detail-item:hover {
    background: rgba(65, 84, 241, 0.05);
}

.detail-icon {
    width: 40px;
    height: 40px;
    background: var(--bg-white, #fff);
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--primary, #4154f1);
    font-size: 1rem;
    flex-shrink: 0;
}

.detail-content {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.75rem;
    color: var(--text-muted, #6c757d);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.detail-value {
    font-weight: 600;
    color: var(--text-primary, #1e293b);
    margin-top: 0.25rem;
}

/* Activity Item */
.activity-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem 1.25rem;
    text-decoration: none;
    border-bottom: 1px solid var(--border-color, #f0f0f0);
    transition: all 0.2s;
}

.activity-item:last-child {
    border-bottom: none;
}

.activity-item:hover {
    background: var(--bg-light, #f8f9fa);
}

.activity-image {
    width: 60px;
    height: 50px;
    border-radius: 8px;
    overflow: hidden;
    flex-shrink: 0;
}

.activity-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.activity-content {
    flex-grow: 1;
}

.activity-title {
    font-weight: 600;
    font-size: 0.9rem;
    color: var(--text-primary, #1e293b);
    margin: 0 0 0.25rem;
}

.activity-meta {
    font-size: 0.75rem;
    color: var(--text-muted, #6c757d);
}

/* Dark Mode */
[data-theme="dark"] .profile-header,
[data-theme="dark"] .profile-stat-card,
[data-theme="dark"] .info-card {
    background: var(--bg-white);
}

[data-theme="dark"] .detail-item {
    background: rgba(255,255,255,0.03);
}

[data-theme="dark"] .detail-icon {
    background: rgba(255,255,255,0.05);
}
</style>
@endsection
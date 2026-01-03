<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
<head>
    {{-- Meta SEO --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="SISKAE - Sistem Informasi Sertifikasi dan Kompetensi Digital">
    <meta name="author" content="SISKAE">
    <meta name="robots" content="noindex, nofollow">
    
    {{-- Title --}}
    <title>@yield('title', 'Dashboard') - SISKAE</title>
    
    {{-- Favicon --}}
    <link rel="shortcut icon" type="image/svg+xml" href="{{ asset('template/dashboard/images/logos/4.svg') }}">
    
    {{-- Core CSS --}}
    <link rel="stylesheet" href="{{ asset('template/dashboard/css/styles.min.css') }}">
    <link rel="stylesheet" href="{{ asset('template/dashboard/css/dashboard-theme.css') }}">
    
    {{-- Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    {{-- Plugin CSS --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    
    {{-- Midtrans Snap.js --}}
    @php
        $midtransUrl = config('midtrans.isProduction') 
            ? 'https://app.midtrans.com/snap/snap.js' 
            : 'https://app.sandbox.midtrans.com/snap/snap.js';
    @endphp
    <script src="{{ $midtransUrl }}" data-client-key="{{ config('midtrans.clientKey') }}"></script>
    
    {{-- Custom Page CSS --}}
    @stack('css')
    
    <style>
        /* Header & Layout Fixes */
        .app-header {
            background: var(--bg-white);
            box-shadow: 0 1px 3px rgba(0,0,0,0.08);
            z-index: 50;
        }
        
        .app-header .navbar {
            min-height: 70px;
            padding: 0 24px;
        }
        
        /* Search */
        .header-search {
            width: 260px;
        }
        
        .header-search .form-control {
            border: 1px solid var(--border-color, #e9ecef);
            background: var(--bg-light, #f8f9fa);
            padding: 8px 16px 8px 40px;
            font-size: 0.875rem;
            transition: all 0.2s;
        }
        
        .header-search .form-control:focus {
            background: #fff;
            border-color: var(--primary, #4154f1);
            box-shadow: 0 0 0 3px rgba(65, 84, 241, 0.1);
        }
        
        /* Header Buttons */
        .header-btn {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--bg-light, #f8f9fa);
            border: none;
            color: var(--text-secondary, #5a6169);
            transition: all 0.2s;
        }
        
        .header-btn:hover {
            background: rgba(65, 84, 241, 0.1);
            color: var(--primary, #4154f1);
        }
        
        /* Profile Dropdown */
        .profile-dropdown .dropdown-toggle {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 4px 12px 4px 4px;
            border-radius: 50px;
            background: transparent;
            border: 1px solid transparent;
            text-decoration: none;
            transition: all 0.2s;
        }
        
        .profile-dropdown .dropdown-toggle:hover,
        .profile-dropdown .dropdown-toggle[aria-expanded="true"] {
            background: var(--bg-light, #f8f9fa);
            border-color: var(--border-color, #e9ecef);
        }
        
        .profile-dropdown .dropdown-toggle::after {
            display: none;
        }
        
        .profile-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 600;
            font-size: 0.9rem;
        }
        
        .profile-info {
            line-height: 1.2;
            text-align: left;
        }
        
        .profile-info .name {
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-primary, #1e293b);
            margin-bottom: 2px;
        }
        
        .profile-info .role {
            font-size: 0.75rem;
            color: var(--text-muted, #6c757d);
            display: block;
        }
        
        /* Dropdown Menu */
        .dropdown-menu-modern {
            border: none;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.12);
            padding: 8px;
            min-width: 220px;
        }
        
        .dropdown-menu-modern .dropdown-item {
            border-radius: 8px;
            padding: 10px 14px;
            font-size: 0.875rem;
            color: var(--text-secondary, #5a6169);
            transition: all 0.15s;
        }
        
        .dropdown-menu-modern .dropdown-item:hover {
            background: var(--bg-light, #f8f9fa);
            color: var(--primary, #4154f1);
        }
        
        .dropdown-menu-modern .dropdown-item i {
            width: 20px;
            font-size: 1rem;
        }
        
        /* Notification Badge */
        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            min-width: 18px;
            height: 18px;
            font-size: 0.65rem;
            font-weight: 600;
        }
        
        /* Dark Mode */
        [data-theme="dark"] .app-header {
            background: var(--bg-white);
            border-bottom: 1px solid var(--border-color);
        }
        
        [data-theme="dark"] .header-btn,
        [data-theme="dark"] .profile-dropdown .dropdown-toggle {
            background: rgba(255,255,255,0.05);
        }
        
        [data-theme="dark"] .dropdown-menu-modern {
            background: var(--bg-white);
            border: 1px solid var(--border-color);
        }
        
        /* Content Area */
        .body-wrapper > .container-fluid {
            padding: 24px;
            min-height: calc(100vh - 70px);
        }
        
        /* Footer */
        .dashboard-footer {
            padding: 20px 0;
            margin-top: 40px;
            border-top: 1px solid var(--border-color, #e9ecef);
            text-align: center;
        }
        
        .dashboard-footer p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--text-muted, #6c757d);
        }
    </style>
</head>

<body>
    <div class="page-wrapper" id="main-wrapper" data-layout="vertical" data-navbarbg="skin6" 
         data-sidebartype="full" data-sidebar-position="fixed" data-header-position="fixed">
        
        {{-- Sidebar --}}
        <aside class="left-sidebar">
            <div>
                <div class="brand-logo d-flex align-items-center justify-content-between">
                    <a href="{{ route('home') }}" class="text-nowrap logo-img d-flex align-items-center text-decoration-none">
                        <img src="{{ asset('template/dashboard/images/logos/4.svg') }}" width="40" alt="SISKAE Logo">
                        <span class="ms-2 fs-5 fw-bold text-primary">SISKAE</span>
                    </a>
                    <button class="close-btn d-xl-none btn border-0 p-0" id="sidebarCollapse" aria-label="Close sidebar">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>
                
                <nav class="sidebar-nav scroll-sidebar" data-simplebar>
                    @include('dashboard.sidebar')
                </nav>
            </div>
        </aside>
        
        {{-- Main Content --}}
        <div class="body-wrapper">
            
            {{-- Header --}}
            <header class="app-header">
                <nav class="navbar navbar-expand-lg navbar-light">
                    {{-- Mobile Toggle --}}
                    <ul class="navbar-nav">
                        <li class="nav-item d-block d-xl-none">
                            <button class="nav-link border-0 bg-transparent sidebartoggler" id="headerCollapse" aria-label="Toggle sidebar">
                                <i class="bi bi-list fs-4"></i>
                            </button>
                        </li>
                    </ul>
                    
                    {{-- Search --}}
                    <div class="header-search d-none d-md-block position-relative ms-3">
                        <i class="bi bi-search position-absolute top-50 start-0 translate-middle-y ms-3 text-muted"></i>
                        <input type="text" class="form-control rounded-pill" placeholder="Cari..." id="global-search" aria-label="Search">
                    </div>
                    
                    {{-- Right Side --}}
                    <div class="navbar-collapse justify-content-end" id="navbarNav">
                        <ul class="navbar-nav flex-row align-items-center gap-2">
                            
                            {{-- Theme Toggle --}}
                            <li class="nav-item">
                                <button class="header-btn" id="theme-toggle" title="Ubah tema" aria-label="Toggle theme">
                                    <i class="bi bi-moon-stars" id="theme-icon"></i>
                                </button>
                            </li>
                            
                            {{-- Notifications --}}
                            <li class="nav-item dropdown">
                                <a class="header-btn position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false" aria-label="Notifications">
                                    <i class="bi bi-bell"></i>
                                    @php
                                        $pendingCount = 0;
                                        try {
                                            $pendingCount = \App\Models\Transaksi::where('status_pembayaran', 'pending')->count();
                                        } catch (\Exception $e) {}
                                    @endphp
                                    @if($pendingCount > 0)
                                        <span class="notification-badge badge rounded-pill bg-danger">
                                            {{ $pendingCount > 9 ? '9+' : $pendingCount }}
                                        </span>
                                    @endif
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-modern" style="width: 320px;">
                                    <div class="px-3 py-2 border-bottom d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0 fw-semibold">Notifikasi</h6>
                                        @if($pendingCount > 0)
                                            <span class="badge bg-danger">{{ $pendingCount }} baru</span>
                                        @endif
                                    </div>
                                    <div style="max-height: 280px; overflow-y: auto;">
                                        @if($pendingCount > 0)
                                            <a href="{{ route('transaksi.index') }}" class="dropdown-item d-flex align-items-start gap-3 py-3">
                                                <span class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                                                    <i class="bi bi-clock-history"></i>
                                                </span>
                                                <div>
                                                    <p class="mb-1 fw-semibold text-dark">{{ $pendingCount }} Transaksi Pending</p>
                                                    <small class="text-muted">Menunggu konfirmasi pembayaran</small>
                                                </div>
                                            </a>
                                        @else
                                            <div class="text-center py-4">
                                                <i class="bi bi-bell-slash text-muted fs-1 mb-2 d-block"></i>
                                                <small class="text-muted">Tidak ada notifikasi</small>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            
                            {{-- Profile --}}
                            <li class="nav-item dropdown profile-dropdown">
                                <a class="dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <div class="profile-avatar">
                                        {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                                    </div>
                                    <div class="profile-info d-none d-md-block">
                                        <span class="name d-block">{{ Auth::user()->name ?? 'User' }}</span>
                                        <span class="role">{{ Auth::user()->id_role == 1 ? 'Administrator' : 'Member' }}</span>
                                    </div>
                                    <i class="bi bi-chevron-down text-muted d-none d-md-block"></i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-modern">
                                    <div class="px-3 py-2 border-bottom mb-2">
                                        <p class="fw-semibold mb-0">{{ Auth::user()->name ?? 'User' }}</p>
                                        <small class="text-muted">{{ Auth::user()->email ?? '' }}</small>
                                    </div>
                                    <a href="{{ route('user.profile') }}" class="dropdown-item d-flex align-items-center gap-2">
                                        <i class="bi bi-person"></i>
                                        <span>Profil Saya</span>
                                    </a>
                                    @if(Auth::user()->id_role == 1)
                                    <a href="{{ route('transaksi.index') }}" class="dropdown-item d-flex align-items-center gap-2">
                                        <i class="bi bi-receipt"></i>
                                        <span>Transaksi</span>
                                    </a>
                                    @endif
                                    <hr class="my-2">
                                    <form action="{{ route('logout') }}" method="POST">
                                        @csrf
                                        <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                            <i class="bi bi-box-arrow-right"></i>
                                            <span>Keluar</span>
                                        </button>
                                    </form>
                                </div>
                            </li>
                            
                        </ul>
                    </div>
                </nav>
            </header>
            
            {{-- Content --}}
            <div class="container-fluid">
                @include('dashboard.notifikasi')
                
                @yield('content')
                
                {{-- Footer --}}
                <footer class="dashboard-footer">
                    <p>&copy; {{ date('Y') }} <strong>SISKAE</strong>. Developed by <a href="https://github.com/habibalfrz13" target="_blank" class="text-primary text-decoration-none">Alfrz</a></p>
                </footer>
            </div>
            
        </div>
    </div>
    
    {{-- Core JS --}}
    <script src="{{ asset('template/dashboard/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('template/dashboard/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/dashboard/js/sidebarmenu.js') }}"></script>
    <script src="{{ asset('template/dashboard/js/app.min.js') }}"></script>
    <script src="{{ asset('template/dashboard/libs/simplebar/dist/simplebar.js') }}"></script>
    
    {{-- Plugin JS --}}
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    
    {{-- Global Scripts --}}
    <script>
        'use strict';
        
        document.addEventListener('DOMContentLoaded', function() {
            // DataTables Init
            if ($.fn.DataTable) {
                $('#example').DataTable({
                    language: {
                        search: "Cari:",
                        lengthMenu: "Tampilkan _MENU_ data",
                        info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
                        paginate: { first: "«", last: "»", next: "›", previous: "‹" },
                        emptyTable: "Tidak ada data",
                        zeroRecords: "Data tidak ditemukan"
                    },
                    pageLength: 10,
                    responsive: true
                });
            }
            
            // Select2 Init
            if ($.fn.select2) {
                $('.select2').select2({
                    theme: 'bootstrap-5',
                    placeholder: 'Pilih opsi',
                    allowClear: true,
                    width: '100%'
                });
            }
            
            // Theme Toggle
            const themeToggle = document.getElementById('theme-toggle');
            const themeIcon = document.getElementById('theme-icon');
            const html = document.documentElement;
            
            // Load saved theme
            const savedTheme = localStorage.getItem('siskae-theme') || 'light';
            html.setAttribute('data-theme', savedTheme);
            updateThemeIcon(savedTheme);
            
            if (themeToggle) {
                themeToggle.addEventListener('click', function() {
                    const currentTheme = html.getAttribute('data-theme');
                    const newTheme = currentTheme === 'light' ? 'dark' : 'light';
                    
                    html.setAttribute('data-theme', newTheme);
                    localStorage.setItem('siskae-theme', newTheme);
                    updateThemeIcon(newTheme);
                });
            }
            
            function updateThemeIcon(theme) {
                if (!themeIcon) return;
                themeIcon.className = theme === 'dark' ? 'bi bi-sun' : 'bi bi-moon-stars';
            }
        });
    </script>
    
    {{-- Page Scripts --}}
    @stack('scripts')
    
</body>
</html>
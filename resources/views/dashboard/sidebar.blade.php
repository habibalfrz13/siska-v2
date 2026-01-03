<nav class="sidebar-nav scroll-sidebar" data-simplebar="">
    <ul id="sidebarnav">
        
        {{-- Admin Sidebar --}}
        @if (Auth::user()->id_role == '1')
        
        <!-- Dashboard Section -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Menu Utama</span>
        </li>
        
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>

        <!-- Konten Section -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Manajemen</span>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('kategori.*') ? 'active' : '' }}" href="{{ route('kategori.index') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-bookmark-star"></i>
                </span>
                <span class="hide-menu">Kategori</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('vendor.*') ? 'active' : '' }}" href="{{ route('vendor.index') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-building-check"></i>
                </span>
                <span class="hide-menu">Vendor</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('kelas.*') ? 'active' : '' }}" href="{{ route('kelas.index') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </span>
                <span class="hide-menu">Kelas</span>
                @php
                    $kelasAktif = \App\Models\Kelas::where('status', 'Aktif')->count();
                @endphp
                @if($kelasAktif > 0)
                <span class="sidebar-badge bg-success">{{ $kelasAktif }}</span>
                @endif
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('transaksi.*') ? 'active' : '' }}" href="{{ route('transaksi.index') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-credit-card-2-front"></i>
                </span>
                <span class="hide-menu">Transaksi</span>
                @php
                    $pendingTrans = \App\Models\Transaksi::where('status_pembayaran', 'pending')->count();
                @endphp
                @if($pendingTrans > 0)
                <span class="sidebar-badge bg-warning">{{ $pendingTrans }}</span>
                @endif
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('peserta.*') ? 'active' : '' }}" href="{{ route('peserta.index') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-people"></i>
                </span>
                <span class="hide-menu">Peserta</span>
            </a>
        </li>

        <!-- User Management -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Pengguna</span>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('user.*') && !request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.index') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-person-gear"></i>
                </span>
                <span class="hide-menu">Kelola Pengguna</span>
            </a>
        </li>

        <!-- Account Section (Admin) -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Akun</span>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-person-circle"></i>
                </span>
                <span class="hide-menu">Profil Saya</span>
            </a>
        </li>

        <!-- Reports Section -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Laporan</span>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('peserta.cetak') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-printer"></i>
                </span>
                <span class="hide-menu">Cetak Peserta</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link" href="{{ route('transaksi.cetaksuccess') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-file-earmark-text"></i>
                </span>
                <span class="hide-menu">Laporan Transaksi</span>
            </a>
        </li>

        {{-- User Sidebar --}}
        @else
        
        <!-- Dashboard Section -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Menu Utama</span>
        </li>
        
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="ti ti-layout-dashboard"></i>
                </span>
                <span class="hide-menu">Dashboard</span>
            </a>
        </li>

        <!-- Kelas Section -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Pembelajaran</span>
        </li>
        
        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('kelas.userIndex') ? 'active' : '' }}" href="{{ route('kelas.userIndex') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-journal-bookmark"></i>
                </span>
                <span class="hide-menu">Daftar Kelas</span>
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('myclass.userIndex') ? 'active' : '' }}" href="{{ route('myclass.userIndex') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-mortarboard"></i>
                </span>
                <span class="hide-menu">Kelas Saya</span>
                @php
                    $myClassCount = \App\Models\Myclass::where('user_id', Auth::id())->count();
                @endphp
                @if($myClassCount > 0)
                <span class="sidebar-badge bg-primary">{{ $myClassCount }}</span>
                @endif
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('learn.*') ? 'active' : '' }}" href="{{ route('learn.index') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-play-circle"></i>
                </span>
                <span class="hide-menu">Mulai Belajar</span>
                @php
                    $activeClassCount = \App\Models\Myclass::where('user_id', Auth::id())->where('status', 'Aktif')->count();
                @endphp
                @if($activeClassCount > 0)
                <span class="sidebar-badge bg-success">{{ $activeClassCount }}</span>
                @endif
            </a>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('certificates.*') ? 'active' : '' }}" href="{{ route('certificates.index') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-award"></i>
                </span>
                <span class="hide-menu">Sertifikat Saya</span>
                @php
                    $certCount = \App\Models\Certificate::where('user_id', Auth::id())->count();
                @endphp
                @if($certCount > 0)
                <span class="sidebar-badge bg-warning">{{ $certCount }}</span>
                @endif
            </a>
        </li>

        <!-- Profile Section -->
        <li class="nav-small-cap">
            <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
            <span class="hide-menu">Akun</span>
        </li>

        <li class="sidebar-item">
            <a class="sidebar-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}" aria-expanded="false">
                <span class="sidebar-icon">
                    <i class="bi bi-person-circle"></i>
                </span>
                <span class="hide-menu">Profil Saya</span>
            </a>
        </li>
        
        @endif
        
    </ul>
</nav>
<!-- End Sidebar navigation -->
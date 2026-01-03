<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SISKAE') }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700&family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        :root {
            --primary-color: #4154f1;
            --secondary-color: #012970;
            --bg-light: #f6f9ff;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: var(--bg-light);
            color: #444444;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navbar Styling */
        .navbar {
            background: #fff;
            box-shadow: 0px 2px 20px rgba(1, 41, 112, 0.1);
            padding: 15px 0;
        }

        .navbar-brand {
            font-family: 'Poppins', sans-serif;
            font-weight: 700;
            color: var(--secondary-color) !important;
            font-size: 24px;
            display: flex;
            align-items: center;
        }

        .nav-link {
            color: #013289;
            font-weight: 600;
            margin-left: 15px;
            transition: 0.3s;
        }

        .nav-link:hover, .nav-link.active {
            color: var(--primary-color);
        }

        /* Dropdown Menu */
        .dropdown-menu {
            border: none;
            box-shadow: 0 5px 30px 0 rgba(82, 63, 105, 0.2);
            border-radius: 10px;
        }
        
        .dropdown-item {
            padding: 10px 20px;
            font-weight: 500;
        }

        .dropdown-item:hover {
            background-color: #f6f9ff;
            color: var(--primary-color);
        }

        .btn-auth {
            background: var(--primary-color);
            color: white;
            border-radius: 50px;
            padding: 8px 25px;
            font-size: 14px;
        }
        
        .btn-auth:hover {
            background: #2a3bcd;
            color: white;
        }

        main {
            flex: 1; /* Mendorong footer ke bawah */
        }

        /* Footer Simple */
        footer {
            background: #fff;
            padding: 20px 0;
            border-top: 1px solid #eee;
            margin-top: auto;
            text-align: center;
            font-size: 14px;
            color: #6c757d;
        }
    </style>

    {{-- @vite(['resources/sass/app.scss', 'resources/js/app.js']) --}}
</head>
<body>
    <div id="app" class="d-flex flex-column min-vh-100">
        
        <nav class="navbar navbar-expand-md navbar-light sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    <img src="{{ asset('template/frontend/img/logo.png') }}" alt="" width="35" height="35" class="d-inline-block align-text-top me-2">
                    SISKAE
                </a>
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto">
                        @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ url('/home') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Kelas Saya</a>
                        </li>
                        @endauth
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                                </li>
                            @endif

                            @if (Route::has('register'))
                                <li class="nav-item ms-2">
                                    <a class="btn btn-auth" href="{{ route('register') }}">{{ __('Daftar') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center me-2" style="width: 35px; height: 35px;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="fw-bold">{{ Auth::user()->name }}</span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-end animate slideIn" aria-labelledby="navbarDropdown">
                                    <h6 class="dropdown-header text-muted">Akun Saya</h6>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-person me-2"></i> Profil
                                    </a>
                                    <a class="dropdown-item" href="#">
                                        <i class="bi bi-gear me-2"></i> Pengaturan
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        <i class="bi bi-box-arrow-right me-2"></i> {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            <div class="container">
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                @yield('content')
            </div>
        </main>

        <footer>
            <div class="container">
                &copy; {{ date('Y') }} <strong><span>SISKAE</span></strong>. All Rights Reserved.
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    
    @yield('scripts')
</body>
</html>
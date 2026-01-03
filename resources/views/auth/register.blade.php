@extends('layouts.app')

@section('content')
<style>
    /* Menggunakan base style yang sama dengan Login agar konsisten */
    body {
        background-color: #ffffff;
    }

    .register-container {
        min-height: calc(100vh - 80px); /* Mengurangi tinggi navbar */
        display: flex;
    }

    /* Bagian Kiri (Visual) */
    .register-banner {
        background: url('https://images.unsplash.com/photo-1523240795612-9a054b0db644?q=80&w=1470&auto=format&fit=crop') no-repeat center center;
        background-size: cover;
        position: relative;
        width: 100%;
        height: 100%;
        min-height: 100vh; /* Full height */
    }

    .register-banner::before {
        content: "";
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        /* Gradient sedikit berbeda (lebih cerah) untuk nuansa "Awal Baru" */
        background: linear-gradient(135deg, rgba(1, 41, 112, 0.85), rgba(65, 84, 241, 0.8));
    }

    .banner-content {
        position: absolute;
        bottom: 10%;
        left: 10%;
        right: 10%;
        color: white;
        z-index: 2;
    }

    /* Bagian Kanan (Form) */
    .register-form-section {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px;
        width: 100%;
    }

    .form-wrapper {
        width: 100%;
        max-width: 500px; /* Sedikit lebih lebar dari login karena field lebih banyak */
    }

    .section-title {
        font-weight: 800;
        color: #012970;
        font-size: 28px;
        margin-bottom: 10px;
    }

    /* Input Styling */
    .input-group-text {
        background-color: #f8f9fa;
        border-right: none;
        color: #4154f1;
    }

    .form-control {
        border-left: none;
        background-color: #f8f9fa;
        padding: 12px;
        font-size: 14px;
        border-color: #dee2e6;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: #86b7fe;
        box-shadow: none; /* Hilangkan shadow default bootstrap agar lebih clean */
        border-left: 1px solid #86b7fe; /* Kembalikan border saat fokus */
    }
    
    /* Fix border radius untuk input group */
    .input-group .input-group-text:first-child {
        border-radius: 10px 0 0 10px;
        border: 1px solid #dee2e6;
        border-right: none;
    }
    .input-group .form-control:not(:last-child) {
        border-radius: 0; /* Middle elements */
    }
    .input-group .form-control:last-child {
        border-radius: 0 10px 10px 0;
        border-left: none;
        border: 1px solid #dee2e6;
        border-left: none;
    }
    
    /* Khusus Password Toggle di kanan */
    .password-toggle {
        cursor: pointer;
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        border-left: none;
        border-radius: 0 10px 10px 0 !important;
        color: #6c757d;
    }

    .btn-register {
        background: #4154f1;
        border: none;
        border-radius: 10px;
        padding: 12px;
        font-weight: 600;
        width: 100%;
        transition: 0.3s;
    }

    .btn-register:hover {
        background: #2a3bcd;
        box-shadow: 0 5px 15px rgba(65, 84, 241, 0.3);
    }

    @media (max-width: 991.98px) {
        .register-banner { display: none; }
        .register-form-section { padding: 20px; }
    }
</style>

<div class="container-fluid g-0">
    <div class="row g-0">
        
        <div class="col-lg-5 d-none d-lg-block position-relative">
            <div class="register-banner">
                <div class="banner-content" data-aos="fade-up">
                    <h2 class="fw-bold display-6">Bergabunglah dengan Komunitas Profesional</h2>
                    <p class="mt-3 opacity-75">Akses ratusan materi eksklusif, mentor berpengalaman, dan sertifikasi yang diakui industri. Mulai langkah pertamamu hari ini.</p>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="register-form-section">
                <div class="form-wrapper">
                    
                    <div class="text-center mb-5">
                        <a href="{{ url('/') }}" class="d-inline-flex align-items-center text-decoration-none mb-3">
                            <img src="{{ asset('template/frontend/img/logo.png') }}" alt="Logo" width="45">
                            <span class="ms-2 fs-3 fw-bold text-dark" style="font-family: 'Poppins', sans-serif;">SISKAE</span>
                        </a>
                        <h1 class="section-title">Buat Akun Baru</h1>
                        <p class="text-muted">Lengkapi data diri Anda untuk memulai.</p>
                    </div>

                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">NAMA LENGKAP</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Contoh: Budi Santoso">
                            </div>
                            @error('name')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold small text-muted">ALAMAT EMAIL</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <small class="text-danger mt-1 d-block">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small text-muted">PASSWORD</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                    <input id="password" type="password" class="form-control border-end-0 @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter">
                                    <span class="input-group-text password-toggle" onclick="togglePassword('password', 'icon-pass')">
                                        <i class="bi bi-eye-slash" id="icon-pass"></i>
                                    </span>
                                </div>
                                @error('password')
                                    <small class="text-danger mt-1 d-block">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-4">
                                <label class="form-label fw-bold small text-muted">ULANGI PASSWORD</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                    <input id="password-confirm" type="password" class="form-control border-end-0" name="password_confirmation" required autocomplete="new-password" placeholder="Ketik ulang password">
                                    <span class="input-group-text password-toggle" onclick="togglePassword('password-confirm', 'icon-confirm')">
                                        <i class="bi bi-eye-slash" id="icon-confirm"></i>
                                    </span>
                                </div>
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input class="form-check-input" type="checkbox" value="" id="flexCheckDefault" required>
                            <label class="form-check-label small text-muted" for="flexCheckDefault">
                                Saya menyetujui <a href="#" class="text-decoration-none">Syarat & Ketentuan</a> serta <a href="#" class="text-decoration-none">Kebijakan Privasi</a>.
                            </label>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-register text-white">
                                DAFTAR SEKARANG
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="text-muted">Sudah punya akun? <a href="{{ route('login') }}" class="fw-bold text-primary text-decoration-none">Masuk di sini</a></p>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePassword(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        
        if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("bi-eye-slash");
            icon.classList.add("bi-eye");
        } else {
            input.type = "password";
            icon.classList.remove("bi-eye");
            icon.classList.add("bi-eye-slash");
        }
    }
</script>
@endsection
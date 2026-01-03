@extends('layouts.app')

@section('content')
<style>
    /* Custom CSS khusus untuk Halaman Login */
    body {
        font-family: 'Poppins', sans-serif;
        background-color: #fdfdfd;
        overflow-x: hidden;
    }

    .login-section {
        min-height: 100vh;
        width: 100%;
        background: #fff;
    }

    /* Bagian Kiri (Gambar & Branding) */
    .login-banner {
        background: url('https://images.unsplash.com/photo-1522202176988-66273c2fd55f?q=80&w=1471&auto=format&fit=crop') no-repeat center center;
        background-size: cover;
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* Overlay Gradient agar teks terbaca */
    .login-banner::before {
        content: "";
        position: absolute;
        top: 0; 
        left: 0;
        width: 100%; 
        height: 100%;
        background: linear-gradient(135deg, rgba(65, 84, 241, 0.9), rgba(1, 41, 112, 0.8));
        z-index: 1;
    }

    .banner-content {
        position: relative;
        z-index: 2;
        color: white;
        padding: 40px;
        text-align: center;
    }

    /* Bagian Kanan (Form) */
    .login-form-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 40px;
        background-color: #ffffff;
    }

    .login-wrapper {
        width: 100%;
        max-width: 450px;
    }

    .brand-logo {
        font-weight: 800;
        color: #012970;
        font-size: 24px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 2rem;
    }

    .form-title {
        font-size: 2rem;
        font-weight: 700;
        color: #012970;
        margin-bottom: 0.5rem;
    }

    .form-subtitle {
        color: #6c757d;
        margin-bottom: 2rem;
    }

    /* Styling Input Form Modern */
    .form-control {
        padding: 12px 20px;
        border-radius: 10px;
        border: 1px solid #dee2e6;
        background-color: #f8f9fa;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        background-color: #fff;
        border-color: #4154f1;
        box-shadow: 0 0 0 4px rgba(65, 84, 241, 0.1);
    }

    .input-group-text {
        background: transparent;
        border-left: none;
        border-radius: 0 10px 10px 0;
        cursor: pointer;
        color: #6c757d;
    }
    
    /* Tombol Utama */
    .btn-login {
        background-color: #4154f1;
        color: white;
        padding: 12px;
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: 0.5px;
        transition: all 0.3s ease;
        border: none;
        width: 100%;
    }

    .btn-login:hover {
        background-color: #2a3bcd;
        transform: translateY(-2px);
        box-shadow: 0 5px 15px rgba(65, 84, 241, 0.3);
        color: white;
    }

    /* Link & Checkbox */
    .form-check-input:checked {
        background-color: #4154f1;
        border-color: #4154f1;
    }

    .forgot-link {
        color: #4154f1;
        font-weight: 500;
        text-decoration: none;
        font-size: 14px;
    }

    .forgot-link:hover {
        text-decoration: underline;
    }

    /* Mobile Responsiveness */
    @media (max-width: 991.98px) {
        .login-banner {
            display: none; /* Sembunyikan gambar di layar kecil agar ringan */
        }
        .login-form-container {
            padding: 20px;
        }
    }
</style>

<div class="container-fluid g-0">
    <div class="row g-0">
        
        <div class="col-lg-6 d-none d-lg-block">
            <div class="login-banner">
                <div class="banner-content" data-aos="fade-up">
                    <h1 class="display-4 fw-bold mb-3">Selamat Datang di SISKAE</h1>
                    <p class="lead mb-4">Platform sertifikasi & kompetensi keahlian terdepan.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <span class="badge bg-white text-primary bg-opacity-75 p-2">Edukasi</span>
                        <span class="badge bg-white text-primary bg-opacity-75 p-2">Sertifikasi</span>
                        <span class="badge bg-white text-primary bg-opacity-75 p-2">Karir</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="login-form-container">
                <div class="login-wrapper">
                    
                    <a href="{{ url('/') }}" class="brand-logo text-decoration-none">
                        <img src="{{ asset('template/frontend/img/logo.png') }}" alt="Logo" width="40">
                        <span>SISKAE</span>
                    </a>

                    <h2 class="form-title">Login Member</h2>
                    <p class="form-subtitle">Masuk untuk mengakses kelas dan sertifikasi Anda.</p>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="email" class="form-label fw-bold small text-muted">ALAMAT EMAIL</label>
                            <input type="email" id="email" name="email" 
                                class="form-control @error('email') is-invalid @enderror" 
                                placeholder="nama@email.com" 
                                value="{{ old('email') }}" required autocomplete="email" autofocus>
                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <label for="password" class="form-label fw-bold small text-muted">PASSWORD</label>
                            </div>
                            <div class="input-group">
                                <input type="password" id="password" name="password" 
                                    class="form-control border-end-0 @error('password') is-invalid @enderror" 
                                    placeholder="Masukkan password Anda" required autocomplete="current-password">
                                <span class="input-group-text bg-white border-start-0 border-default" id="togglePassword">
                                    <i class="bi bi-eye-slash" id="eyeIcon"></i>
                                </span>
                            </div>
                            @error('password')
                                <span class="invalid-feedback d-block" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label small text-secondary" for="remember">
                                    Ingat Saya
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="forgot-link">Lupa Password?</a>
                            @endif
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-login">
                                MASUK SEKARANG
                            </button>
                        </div>

                        <div class="text-center mt-4">
                            <p class="small text-muted">Belum punya akun? 
                                <a href="{{ route('register') }}" class="fw-bold text-primary text-decoration-none">Daftar Gratis</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            // Toggle type attribute
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle icon
            if(type === 'text') {
                eyeIcon.classList.remove('bi-eye-slash');
                eyeIcon.classList.add('bi-eye');
            } else {
                eyeIcon.classList.remove('bi-eye');
                eyeIcon.classList.add('bi-eye-slash');
            }
        });
    });
</script>
@endsection
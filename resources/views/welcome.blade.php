<!DOCTYPE html>
<html lang="id" class="scroll-smooth">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  
  <title>SISKAE - Platform Sertifikasi & Kompetensi Digital Profesional</title>
  
  <!-- SEO Meta Tags -->
  <meta name="description" content="Tingkatkan karir Anda dengan sertifikasi ahli dan kursus kompetensi digital di SISKAE. Platform edukasi terdepan untuk masa depan cerah.">
  <meta name="keywords" content="sertifikasi, kursus online, kompetensi, digital skills, pelatihan kerja, siskae, lms, e-learning">
  <meta name="author" content="SISKAE Team">
  <meta name="robots" content="index, follow">
  
  <!-- Open Graph / Facebook -->
  <meta property="og:type" content="website">
  <meta property="og:url" content="{{ url('/') }}">
  <meta property="og:title" content="SISKAE - Platform Sertifikasi & Kompetensi Digital">
  <meta property="og:description" content="Platform pengembangan skill terbaik untuk masa depan karir Anda.">
  <meta property="og:image" content="{{ asset('template/frontend/img/hero-img.png') }}">

  <!-- Twitter -->
  <meta property="twitter:card" content="summary_large_image">
  <meta property="twitter:url" content="{{ url('/') }}">
  <meta property="twitter:title" content="SISKAE - Platform Sertifikasi & Kompetensi Digital">
  <meta property="twitter:description" content="Platform pengembangan skill terbaik untuk masa depan karir Anda.">
  <meta property="twitter:image" content="{{ asset('template/frontend/img/hero-img.png') }}">

  <!-- Favicons -->
  <link href="{{ asset('template/frontend/img/favicon.png') }}" rel="icon">
  <link href="{{ asset('template/frontend/img/apple-touch-icon.png') }}" rel="apple-touch-icon">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Space+Grotesk:wght@500;700&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

  <style>
    :root {
      /* Modern SaaS Palette */
      --primary: #2563EB; /* Royal Blue */
      --primary-dark: #1E40AF;
      --primary-light: #EFF6FF;
      --secondary: #0F172A; /* Slate 900 */
      --accent: #38BDF8; /* Sky 400 */
      --text-main: #334155; /* Slate 700 */
      --text-light: #64748B; /* Slate 500 */
      --background: #FFFFFF;
      --surface: #F8FAFC; /* Slate 50 */
      --border: #E2E8F0;
      
      --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
      --shadow-md: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1);
      --shadow-lg: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
      --shadow-xl: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
    }

    body {
      font-family: 'Inter', sans-serif;
      color: var(--text-main);
      background-color: var(--background);
      -webkit-font-smoothing: antialiased;
      line-height: 1.6;
      overflow-x: hidden;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: 'Space Grotesk', sans-serif;
      color: var(--secondary);
      font-weight: 700;
      letter-spacing: -0.02em;
    }

    a {
      text-decoration: none;
      color: inherit;
      transition: all 0.2s ease;
    }

    /* Navbar */
    .navbar-custom {
      padding: 1.25rem 0;
      background: rgba(255, 255, 255, 0.85);
      backdrop-filter: blur(12px);
      -webkit-backdrop-filter: blur(12px);
      border-bottom: 1px solid rgba(226, 232, 240, 0.6);
      transition: all 0.3s ease;
      z-index: 1000;
    }
    
    .navbar-custom.scrolled {
      padding: 0.75rem 0;
      background: rgba(255, 255, 255, 0.95);
      box-shadow: var(--shadow-sm);
    }

    .brand-logo {
      font-family: 'Space Grotesk', sans-serif;
      font-weight: 800;
      font-size: 1.5rem;
      color: var(--primary);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .nav-link {
      font-weight: 500;
      color: var(--text-main) !important;
      padding: 0.5rem 1rem !important;
      border-radius: 0.5rem;
    }

    .nav-link:hover, .nav-link.active {
      color: var(--primary) !important;
      background: var(--primary-light);
    }

    .btn-login {
      background: var(--secondary);
      color: white !important;
      padding: 0.6rem 1.5rem;
      border-radius: 9999px;
      font-weight: 600;
      transition: transform 0.2s, box-shadow 0.2s;
    }

    .btn-login:hover {
      transform: translateY(-1px);
      box-shadow: var(--shadow-md);
      background: #1e293b;
    }

    /* Hero Section */
    .hero-section {
      padding-top: 140px;
      padding-bottom: 80px;
      position: relative;
      background: radial-gradient(50% 50% at 50% 50%, rgba(37, 99, 235, 0.05) 0%, rgba(255, 255, 255, 0) 100%);
    }

    .hero-title {
      font-size: 3.5rem;
      line-height: 1.1;
      margin-bottom: 1.5rem;
      background: linear-gradient(135deg, var(--secondary) 0%, var(--primary) 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }
    
    @media (max-width: 768px) {
        .hero-title { font-size: 2.5rem; }
    }

    .hero-subtitle {
      font-size: 1.25rem;
      color: var(--text-light);
      margin-bottom: 2.5rem;
      max-width: 540px;
    }

    .btn-primary-lg {
      background: var(--primary);
      color: white;
      padding: 1rem 2rem;
      border-radius: 9999px;
      font-weight: 600;
      font-size: 1.1rem;
      border: none;
      box-shadow: 0 10px 25px -5px rgba(37, 99, 235, 0.4);
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
    }

    .btn-primary-lg:hover {
      background: var(--primary-dark);
      transform: translateY(-2px);
      box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.3);
      color: white;
    }

    .hero-image-wrapper {
      position: relative;
      z-index: 10;
    }

    .hero-image {
      border-radius: 24px;
      box-shadow: var(--shadow-xl);
      transform: perspective(1000px) rotateY(-5deg) rotateX(2deg);
      transition: transform 0.5s ease;
      width: 100%;
    }

    .hero-image:hover {
      transform: perspective(1000px) rotateY(0deg) rotateX(0deg);
    }

    /* Features Section */
    .features-section {
      padding: 100px 0;
      background: white;
    }
    
    .section-title {
      text-align: center;
      max-width: 700px;
      margin: 0 auto 60px;
    }
    
    .section-title h2 {
      font-size: 2.5rem;
      margin-bottom: 1rem;
    }
    
    .feature-card {
      padding: 2rem;
      border-radius: 1.5rem;
      background: white;
      border: 1px solid var(--border);
      height: 100%;
      transition: all 0.3s ease;
    }
    
    .feature-card:hover {
      border-color: var(--primary);
      box-shadow: var(--shadow-lg);
      transform: translateY(-5px);
    }
    
    .feature-icon-box {
      width: 56px;
      height: 56px;
      border-radius: 1rem;
      background: var(--primary-light);
      color: var(--primary);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.5rem;
      margin-bottom: 1.5rem;
    }

    /* Course Card */
    .courses-section {
      padding: 100px 0;
      background: var(--surface);
    }
    
    .course-card {
      background: white;
      border-radius: 1.25rem;
      overflow: hidden;
      border: 1px solid var(--border);
      transition: all 0.3s ease;
      height: 100%;
      display: flex;
      flex-direction: column;
    }
    
    .course-card:hover {
      transform: translateY(-8px);
      box-shadow: var(--shadow-xl);
      border-color: rgba(37, 99, 235, 0.2);
    }
    
    .course-thumb {
      position: relative;
      height: 220px;
      overflow: hidden;
    }
    
    .course-thumb img {
      width: 100%;
      height: 100%;
      object-fit: cover;
      transition: transform 0.5s ease;
    }
    
    .course-card:hover .course-thumb img {
      transform: scale(1.05);
    }
    
    .badge-online {
      position: absolute;
      top: 1rem;
      right: 1rem;
      background: rgba(255, 255, 255, 0.95);
      color: var(--primary);
      padding: 0.35rem 0.75rem;
      border-radius: 99px;
      font-size: 0.75rem;
      font-weight: 700;
      box-shadow: var(--shadow-sm);
    }

    .course-content {
      padding: 1.5rem;
      flex-grow: 1;
      display: flex;
      flex-direction: column;
    }
    
    .course-cat {
      font-size: 0.75rem;
      text-transform: uppercase;
      letter-spacing: 0.05em;
      color: var(--accent);
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    
    .course-title {
      font-size: 1.25rem;
      font-weight: 700;
      margin-bottom: 0.75rem;
      line-height: 1.4;
    }
    
    .course-desc {
      font-size: 0.9rem;
      color: var(--text-light);
      margin-bottom: 1.5rem;
      flex-grow: 1;
    }
    
    .course-meta {
      padding-top: 1rem;
      border-top: 1px solid var(--border);
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .course-price {
      font-weight: 700;
      color: var(--secondary);
      font-size: 1.1rem;
    }

    /* Footer */
    .footer-section {
      background: var(--surface);
      padding: 80px 0 30px;
      border-top: 1px solid var(--border);
    }
    
    .footer-brand span {
      font-family: 'Space Grotesk', sans-serif;
      font-weight: 700;
      font-size: 1.5rem;
      color: var(--secondary);
    }
    
    .social-icon {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: white;
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      color: var(--text-main);
      transition: all 0.2s;
    }
    
    .social-icon:hover {
      background: var(--primary);
      color: white;
      border-color: var(--primary);
    }
    
    .footer-title {
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: var(--secondary);
    }
    
    .footer-links li {
      margin-bottom: 0.75rem;
    }
    
    .footer-links a {
      color: var(--text-light);
    }
    
    .footer-links a:hover {
      color: var(--primary);
    }

    /* Weather Widget */
    .weather-pill {
      background: white;
      border: 1px solid var(--border);
      border-radius: 100px;
      padding: 0.5rem 1rem;
      display: inline-flex;
      align-items: center;
      gap: 0.75rem;
      box-shadow: var(--shadow-md);
      margin-top: 2rem;
      font-weight: 500;
      font-size: 0.9rem;
      color: var(--text-main);
    }
    
    .weather-icon img {
      width: 32px;
      height: 32px;
    }

  </style>
</head>

<body>

  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg fixed-top navbar-custom" id="mainNav">
    <div class="container xl:max-w-7xl">
      <a class="navbar-brand brand-logo" href="{{ url('/') }}">
        <img src="{{ asset('template/frontend/img/logo.png') }}" alt="SISKAE" height="40">
        <span>SISKAE</span>
      </a>
      <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent">
        <span class="navbar-toggler-icon"></span>
      </button>
      
      <div class="collapse navbar-collapse" id="navbarContent">
        <ul class="navbar-nav mx-auto mb-2 mb-lg-0 gap-lg-3">
          <li class="nav-item"><a class="nav-link active" href="#hero">Beranda</a></li>
          <li class="nav-item"><a class="nav-link" href="#features">Keunggulan</a></li>
          <li class="nav-item"><a class="nav-link" href="#courses">Kelas</a></li>
          <li class="nav-item"><a class="nav-link" href="#about">Tentang</a></li>
        </ul>
        <div class="d-flex gap-2">
            <a href="{{ route('login') }}" class="btn btn-login shadow-sm">Masuk / Daftar</a>
        </div>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section id="hero" class="hero-section d-flex align-items-center">
    <div class="container">
      <div class="row align-items-center gy-5">
        <div class="col-lg-6" data-aos="fade-right" data-aos-duration="1000">
          <h1 class="hero-title">Bangun Karir Masa Depan dengan Skill Digital</h1>
          <p class="hero-subtitle">Platform pembelajaran #1 untuk sertifikasi profesi dan kompetensi teknis. Belajar dari praktisi, praktik langsung, dan dapatkan sertifikat yang diakui industri.</p>
          
          <div class="d-flex flex-wrap gap-3">
            <a href="#courses" class="btn btn-primary-lg">
              Mulai Belajar <i class="bi bi-arrow-right"></i>
            </a>
            <a href="https://www.youtube.com/watch?v=LXb3EKWsInQ" class="glightbox btn btn-outline-dark rounded-pill px-4 py-3 fw-bold d-flex align-items-center gap-2">
              <i class="bi bi-play-circle-fill"></i> Tonton Demo
            </a>
          </div>

          <!-- Weather Widget Integration -->
          <div id="weather-box" class="weather-pill d-none" data-aos="fade-up" data-aos-delay="400">
             <div id="weather-icon" class="weather-icon"></div>
             <div>
                <span id="temperature" class="fw-bold">--°C</span>
                <span class="text-muted mx-1">•</span>
                <span id="description">Memuat cuaca...</span>
             </div>
             <span class="badge bg-light text-dark ms-2">Padang</span>
          </div>

        </div>
        <div class="col-lg-6" data-aos="fade-left" data-aos-duration="1000" data-aos-delay="200">
          <div class="hero-image-wrapper">
            <img src="{{ asset('template/frontend/img/hero-img.png') }}" alt="Learning Platform" class="hero-image">
            
            <!-- Floating Cards Decoration -->
            <div class="position-absolute bottom-0 start-0 bg-white p-3 rounded-4 shadow-lg mb-4 ms-n4 d-none d-md-block" data-aos="zoom-in" data-aos-delay="600">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-success bg-opacity-10 p-2 rounded-circle text-success">
                        <i class="bi bi-patch-check-fill fs-4"></i>
                    </div>
                    <div>
                        <div class="small text-muted">Sertifikasi</div>
                        <div class="fw-bold">Terakreditasi BNSP</div>
                    </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Features Section -->
  <section id="features" class="features-section">
    <div class="container">
      <div class="section-title" data-aos="fade-up">
        <h2>Kenapa Memilih SISKAE?</h2>
        <p class="text-muted">Kami menggabungkan kurikulum berstandar industri dengan teknologi pembelajaran modern untuk hasil terbaik.</p>
      </div>

      <div class="row g-4">
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="100">
          <div class="feature-card">
            <div class="feature-icon-box">
              <i class="bi bi-award"></i>
            </div>
            <h4>Sertifikat Resmi</h4>
            <p class="text-muted mb-0">Dapatkan sertifikat kompetensi yang valid dan dapat diverifikasi secara online untuk menunjang portofolio karir Anda.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="200">
          <div class="feature-card">
            <div class="feature-icon-box">
              <i class="bi bi-people"></i>
            </div>
            <h4>Mentor Expert</h4>
            <p class="text-muted mb-0">Belajar langsung dari para praktisi senior yang berpengalaman menangani project-project besar di industri.</p>
          </div>
        </div>
        <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="300">
          <div class="feature-card">
            <div class="feature-icon-box">
              <i class="bi bi-infinity"></i>
            </div>
            <h4>Akses Seumur Hidup</h4>
            <p class="text-muted mb-0">Cukup sekali bayar untuk akses materi selamanya, termasuk update materi di masa depan tanpa biaya tambahan.</p>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Courses Section -->
  <section id="courses" class="courses-section">
    <div class="container">
      <div class="section-title" data-aos="fade-up">
        <h2>Kelas Populer Minggu Ini</h2>
        <p class="text-muted">Jelajahi berbagai pilihan kelas yang paling banyak diminati oleh komunitas kami.</p>
      </div>

      <div class="row g-4">
        @if(isset($kelas) && count($kelas) > 0)
          @foreach($kelas as $class)
          <div class="col-lg-4 col-md-6" data-aos="fade-up" data-aos-delay="{{ $loop->iteration * 100 }}">
            <div class="course-card">
              <div class="course-thumb">
                <img src="{{ url('images/galerikelas/' . $class->foto) }}" alt="{{ $class->judul }}">
                <span class="badge-online">Online Course</span>
              </div>
              <div class="course-content">
                <div class="course-cat">{{ $class->kategori ?? 'Umum' }}</div>
                <h3 class="course-title">
                  <a href="{{ route('login') }}" class="stretched-link">{{ $class->judul }}</a>
                </h3>
                <p class="course-desc">
                  {{ Str::limit(strip_tags($class->deskripsi), 85) }}
                </p>
                <div class="course-meta">
                  <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-person-circle text-muted"></i>
                    <span class="text-muted small">Expert Mentor</span>
                  </div>
                  <div class="course-price">
                    @if($class->harga == 0)
                      <span class="text-success">Gratis</span>
                    @else
                      Rp {{ number_format($class->harga, 0, ',', '.') }}
                    @endif
                  </div>
                </div>
              </div>
            </div>
          </div>
          @endforeach
        @else
          <div class="col-12 text-center py-5" data-aos="fade-in">
            <div class="bg-white p-5 rounded-4 border">
              <i class="bi bi-clipboard-x display-4 text-muted mb-3 d-block"></i>
              <h5 class="text-muted">Belum ada kelas yang tersedia.</h5>
            </div>
          </div>
        @endif
      </div>
      
      <div class="text-center mt-5">
        <a href="{{ route('login') }}" class="btn btn-outline-primary rounded-pill px-5 py-2 fw-bold">Lihat Semua Katalog</a>
      </div>
    </div>
  </section>

  <!-- CTA Section -->
  <section class="py-5">
    <div class="container" data-aos="zoom-in">
      <div class="bg-dark rounded-5 p-5 position-relative overflow-hidden text-center text-white">
        <div class="position-absolute top-0 start-0 w-100 h-100" style="background: linear-gradient(45deg, #0F172A 0%, #1E3A8A 100%); opacity: 0.9; z-index: 1;"></div>
        <div class="position-relative" style="z-index: 2;">
          <h2 class="text-white mb-3 display-5 fw-bold">Siap Mengubah Karir Anda?</h2>
          <p class="text-white-50 mb-4 fs-5">Bergabung dengan 10,000+ member lainnya hari ini.</p>
          <a href="{{ route('login') }}" class="btn btn-light rounded-pill px-5 py-3 fw-bold text-primary">Daftar Sekarang Gratis</a>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer-section">
    <div class="container">
      <div class="row g-4 justify-content-between">
        <div class="col-lg-4 col-md-12">
          <a href="{{ url('/') }}" class="footer-brand d-block mb-4 text-decoration-none">
            <img src="{{ asset('template/frontend/img/logo.png') }}" alt="" width="32" class="me-2">
            <span>SISKAE</span>
          </a>
          <p class="text-muted mb-4">
            Mencetak talenta digital Indonesia yang kompeten dan siap kerja melalui pendidikan vokasi yang inklusif dan berkualitas.
          </p>
          <div class="d-flex gap-2">
            <a href="#" class="social-icon"><i class="bi bi-twitter"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-facebook"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-instagram"></i></a>
            <a href="#" class="social-icon"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>
        
        <div class="col-lg-2 col-md-6">
          <h5 class="footer-title">Platform</h5>
          <ul class="list-unstyled footer-links">
            <li><a href="#hero">Beranda</a></li>
            <li><a href="#courses">Katalog Kelas</a></li>
            <li><a href="#features">Fitur</a></li>
            <li><a href="#">Untuk Perusahaan</a></li>
          </ul>
        </div>
        
        <div class="col-lg-2 col-md-6">
          <h5 class="footer-title">Perusahaan</h5>
          <ul class="list-unstyled footer-links">
            <li><a href="#about">Tentang Kami</a></li>
            <li><a href="#">Karir</a></li>
            <li><a href="#">Blog</a></li>
            <li><a href="#">Kontak</a></li>
          </ul>
        </div>
        
        <div class="col-lg-3 col-md-6">
          <h5 class="footer-title">Hubungi Kami</h5>
          <ul class="list-unstyled footer-links">
            <li class="d-flex gap-2 mb-3">
              <i class="bi bi-geo-alt text-primary mt-1"></i>
              <span class="text-muted">Jl. Khatib Sulaiman No. 1<br>Padang, Sumatera Barat</span>
            </li>
            <li class="d-flex gap-2 mb-3">
              <i class="bi bi-envelope text-primary mt-1"></i>
              <span class="text-muted">support@siskae.com</span>
            </li>
          </ul>
        </div>
      </div>
      
      <div class="border-top pt-4 mt-5 text-center text-muted small">
        <p class="mb-0">&copy; {{ date('Y') }} <strong>SISKAE</strong>. All Rights Reserved.</p>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
  <script src="{{ asset('template/frontend/vendor/glightbox/js/glightbox.min.js') }}"></script>
  
  <script>
    document.addEventListener('DOMContentLoaded', () => {
      // Initialize AOS
      AOS.init({ duration: 800, once: true });

      // Navbar Scroll Effect
      const navbar = document.getElementById('mainNav');
      window.addEventListener('scroll', () => {
        if (window.scrollY > 10) {
          navbar.classList.add('scrolled');
        } else {
          navbar.classList.remove('scrolled');
        }
      });

      // Simple Weather Widget
      const apiKey = '2bb008a2dee92d080615c7975ccf5bfa'; 
      const city = 'Padang';
      const apiUrl = `https://api.openweathermap.org/data/2.5/weather?q=${city}&appid=${apiKey}&units=metric&lang=id`;

      fetch(apiUrl)
        .then(res => res.json())
        .then(data => {
            if(data.cod === 200) {
                document.getElementById('weather-box').classList.remove('d-none');
                document.getElementById('temperature').innerText = Math.round(data.main.temp) + '°C';
                
                const desc = data.weather[0].description;
                document.getElementById('description').innerText = desc.charAt(0).toUpperCase() + desc.slice(1);
                
                const iconCode = data.weather[0].icon;
                const iconUrl = `http://openweathermap.org/img/wn/${iconCode}.png`;
                document.getElementById('weather-icon').innerHTML = `<img src="${iconUrl}" alt="weather">`;
            }
        })
        .catch(console.error);
    });
  </script>
</body>
</html>
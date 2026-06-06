<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.hospital_name', 'SIMRS Core') }} - Pelayanan Kesehatan Unggul</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --simrs-primary: #0D9488;
            --simrs-primary-dark: #0F766E;
            --simrs-secondary: #0F172A;
            --simrs-accent: #F59E0B;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #FFFFFF;
            color: #334155;
            overflow-x: hidden;
        }

        /* Hero Section */
        .hero-section {
            min-height: 100vh;
            background: linear-gradient(135deg, #F8FAFC 0%, #F0FDFA 100%);
            display: flex;
            align-items: center;
            position: relative;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 40%;
            height: 100%;
            background: radial-gradient(circle at 70% 30%, rgba(13, 148, 136, 0.08) 0%, transparent 70%);
            z-index: 0;
        }

        .navbar-brand-custom {
            font-weight: 800;
            font-size: 1.5rem;
            letter-spacing: -0.04em;
            color: var(--simrs-secondary);
        }

        .nav-link-custom {
            font-weight: 600;
            color: #64748B;
            transition: all 0.3s ease;
        }

        .nav-link-custom:hover {
            color: var(--simrs-primary);
        }

        .hero-title {
            font-size: 4rem;
            font-weight: 800;
            letter-spacing: -0.04em;
            line-height: 1.1;
            color: var(--simrs-secondary);
        }

        .btn-premium {
            padding: 1rem 2rem;
            border-radius: 14px;
            font-weight: 700;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
        }

        .btn-premium-primary {
            background: var(--simrs-primary);
            color: white;
            border: none;
            box-shadow: 0 10px 30px rgba(13, 148, 136, 0.3);
        }

        .btn-premium-primary:hover {
            background: var(--simrs-primary-dark);
            transform: translateY(-4px);
            box-shadow: 0 15px 35px rgba(13, 148, 136, 0.4);
            color: white;
        }

        .btn-premium-outline {
            background: white;
            border: 2px solid #E2E8F0;
            color: #64748B;
        }

        .btn-premium-outline:hover {
            border-color: var(--simrs-primary);
            color: var(--simrs-primary);
            transform: translateY(-4px);
        }

        /* Features */
        .feature-card {
            padding: 2.5rem;
            border-radius: 24px;
            background: white;
            border: 1px solid #F1F5F9;
            transition: all 0.4s ease;
            height: 100%;
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 30px 60px rgba(0,0,0,0.05);
            border-color: var(--simrs-primary);
        }

        .feature-icon {
            width: 60px;
            height: 60px;
            border-radius: 16px;
            background: #F0FDFA;
            color: var(--simrs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-bottom: 1.5rem;
        }

        @media (max-width: 991px) {
            .hero-title { font-size: 2.75rem; }
        }
    </style>
</head>
<body>

<!-- Navigation -->
<nav class="navbar navbar-expand-lg py-4 position-absolute w-100 z-3">
    <div class="container">
        <a class="navbar-brand navbar-brand-custom" href="#">
            <i class="fa-solid fa-house-chimney-medical text-primary me-2"></i>
            SIMRS <span class="text-primary">CORE</span>
        </a>
        <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav mx-auto">
                <li class="nav-item"><a class="nav-link nav-link-custom px-3" href="#">Tentang Kami</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom px-3" href="#">Layanan Medis</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom px-3" href="#">Jadwal Dokter</a></li>
                <li class="nav-item"><a class="nav-link nav-link-custom px-3" href="{{ route('public.queue.display') }}">Monitor Antrean</a></li>
            </ul>
            <div class="d-flex gap-2">
                @if (Route::has('login'))
                    @auth
                        <a href="{{ url('/dashboard') }}" class="btn btn-premium btn-premium-primary">
                            <span>BUKA DASHBOARD</span>
                            <i class="fa-solid fa-arrow-right"></i>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-premium btn-premium-primary">
                            <span>LOGIN STAFF</span>
                            <i class="fa-solid fa-lock"></i>
                        </a>
                    @endauth
                @endif
            </div>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-7 z-1">
                <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill fw-800 mb-4 animate-fadeIn">
                    <i class="fa-solid fa-shield-heart me-1"></i> AKREDITASI PARIPURNA KARS
                </div>
                <h1 class="hero-title mb-4">Masa Depan<br><span class="text-primary">Pelayanan Medis</span> Presisi</h1>
                <p class="fs-5 text-muted mb-5" style="max-width: 580px; line-height: 1.6;">
                    Kami mengintegrasikan teknologi informasi terkini untuk memberikan pengalaman kesehatan yang lebih cepat, aman, dan transparan bagi setiap pasien.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="#" class="btn btn-premium btn-premium-primary">
                        <span>REGISTRASI ONLINE</span>
                        <i class="fa-solid fa-calendar-plus"></i>
                    </a>
                    <a href="{{ route('public.queue.display') }}" class="btn btn-premium btn-premium-outline">
                        <span>CEK ANTREAN LIVE</span>
                        <i class="fa-solid fa-tv"></i>
                    </a>
                </div>
                
                <div class="mt-5 d-flex align-items-center gap-4">
                    <div class="d-flex -space-x-2">
                        <img src="https://ui-avatars.com/api/?name=DR&background=0D9488&color=fff" class="rounded-circle border border-2 border-white" style="width: 48px;">
                        <img src="https://ui-avatars.com/api/?name=RS&background=0F172A&color=fff" class="rounded-circle border border-2 border-white" style="width: 48px; margin-left: -12px;">
                        <img src="https://ui-avatars.com/api/?name=HIS&background=3B82F6&color=fff" class="rounded-circle border border-2 border-white" style="width: 48px; margin-left: -12px;">
                    </div>
                    <div>
                        <div class="fw-800 text-dark">50+ Dokter Spesialis</div>
                        <div class="small text-muted">Siap melayani Anda 24/7</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="position-relative">
                    <img src="https://images.unsplash.com/photo-1519494026892-80bbd2d6fd0d?auto=format&fit=crop&q=80&w=800" class="img-fluid rounded-circle shadow-lg border border-5 border-white" style="width: 500px; height: 500px; object-fit: cover;">
                    <div class="position-absolute bottom-0 start-0 bg-white p-4 rounded-4 shadow-xl border mb-5 ms-n5 animate-bounce">
                        <div class="d-flex align-items-center gap-3">
                            <div class="bg-success bg-opacity-10 text-success rounded-circle p-2">
                                <i class="fa-solid fa-check-double"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-bold">Waktu Tunggu</div>
                                <div class="fw-800 h5 mb-0 text-dark">Rata-rata < 15 Menit</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <h2 class="fw-800 text-slate mb-2">Layanan Unggulan Kami</h2>
            <p class="text-muted">Inovasi teknologi untuk kenyamanan pelayanan kesehatan keluarga Anda.</p>
        </div>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-id-card-clip"></i></div>
                    <h5 class="fw-800 mb-3">Registrasi Mandiri</h5>
                    <p class="text-muted small mb-0">Lakukan pendaftaran kunjungan dari mana saja melalui smartphone Anda tanpa perlu mengantre lama di loket.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-file-medical"></i></div>
                    <h5 class="fw-800 mb-3">Rekam Medis Digital</h5>
                    <p class="text-muted small mb-0">Akses riwayat kesehatan, hasil lab, dan radiologi secara terintegrasi dengan protokol keamanan data tinggi.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="feature-card">
                    <div class="feature-icon"><i class="fa-solid fa-truck-medical"></i></div>
                    <h5 class="fw-800 mb-3">UGD 24 Jam</h5>
                    <p class="text-muted small mb-0">Tim medis darurat yang sigap didukung dengan ambulans berfasilitas lengkap untuk penanganan cepat.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="py-5 bg-slate text-white" style="background: var(--simrs-secondary);">
    <div class="container py-4">
        <div class="row g-5">
            <div class="col-lg-4">
                <h4 class="fw-800 mb-4">SIMRS <span class="text-primary">CORE</span></h4>
                <p class="text-white-50 small lh-lg">
                    Sistem Informasi Manajemen Rumah Sakit terintegrasi yang dirancang untuk efisiensi, akurasi, dan kepuasan pasien. Memberikan standar baru dalam digitalisasi kesehatan Indonesia.
                </p>
                <div class="d-flex gap-3 mt-4">
                    <a href="#" class="text-white-50 hover-white"><i class="fa-brands fa-facebook fs-5"></i></a>
                    <a href="#" class="text-white-50 hover-white"><i class="fa-brands fa-instagram fs-5"></i></a>
                    <a href="#" class="text-white-50 hover-white"><i class="fa-brands fa-linkedin fs-5"></i></a>
                </div>
            </div>
            <div class="col-lg-2">
                <h6 class="fw-800 text-uppercase mb-4 tracking-wider">Layanan</h6>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-2"><a href="#" class="text-decoration-none text-white-50">Rawat Jalan</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-white-50">Rawat Inap</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-white-50">Laboratorium</a></li>
                    <li class="mb-2"><a href="#" class="text-decoration-none text-white-50">Radiologi</a></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-800 text-uppercase mb-4 tracking-wider">Kontak</h6>
                <ul class="list-unstyled small text-white-50">
                    <li class="mb-3 d-flex gap-3"><i class="fa-solid fa-location-dot mt-1 text-primary"></i><span>Jl. Kesehatan No. 123, Jakarta Selatan, Indonesia</span></li>
                    <li class="mb-3 d-flex gap-3"><i class="fa-solid fa-phone text-primary"></i><span>(021) 1234-5678</span></li>
                    <li class="mb-3 d-flex gap-3"><i class="fa-solid fa-envelope text-primary"></i><span>info@rs-core.com</span></li>
                </ul>
            </div>
            <div class="col-lg-3">
                <h6 class="fw-800 text-uppercase mb-4 tracking-wider">Jam Operasional</h6>
                <div class="p-3 rounded-4 bg-white bg-opacity-5">
                    <div class="d-flex justify-content-between small mb-2">
                        <span>Senin - Sabtu</span>
                        <span class="text-primary fw-bold">24 Jam</span>
                    </div>
                    <div class="d-flex justify-content-between small">
                        <span>Minggu / Libur</span>
                        <span class="text-primary fw-bold">IGD Saja</span>
                    </div>
                </div>
            </div>
        </div>
        <hr class="my-5 opacity-10">
        <div class="d-flex justify-content-between align-items-center small text-white-50">
            <div>&copy; {{ now()->year }} {{ config('app.hospital_name') }}. Hak Cipta Dilindungi.</div>
            <div class="d-flex gap-4">
                <a href="#" class="text-decoration-none text-white-50">Kebijakan Privasi</a>
                <a href="#" class="text-decoration-none text-white-50">Syarat & Ketentuan</a>
            </div>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

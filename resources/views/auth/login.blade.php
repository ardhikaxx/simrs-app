@extends('layouts.auth')

@section('title', 'Login Staff')

@section('content')
<div class="auth-shell">
    <section class="auth-panel position-relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="position-absolute top-0 inset-s-0 w-100 h-100 opacity-10" style="background-image: radial-gradient(#14919B 1px, transparent 1px); background-size: 24px 24px;"></div>
        
        <div class="position-relative z-1 d-flex flex-column h-100">
            <div class="mb-5">
                <div class="brand-mark mb-4">
                    <i class="fa-solid fa-microscope text-white"></i>
                </div>
                <h1 class="display-5 fw-800 text-white mb-3">SIMRS <span class="text-simrs-primary-light">Core</span></h1>
                <div class="h5 fw-600 text-white-50 mb-4">Clinical Precision Interface v1.0</div>
                <p class="lead text-white-50 mb-0" style="max-width:480px; line-height: 1.6;">
                    Solusi manajemen rumah sakit terpadu yang dirancang untuk efisiensi operasional medis, integrasi satu sehat, dan pelayanan pasien yang unggul.
                </p>
            </div>

            <div class="mt-auto">
                <div class="d-flex gap-4 mb-4">
                    <div class="text-center">
                        <div class="h4 fw-800 text-white mb-0">100%</div>
                        <div class="small text-white-50 text-uppercase tracking-wider">Terintegrasi</div>
                    </div>
                    <div class="text-center border-start border-white-10 ps-4">
                        <div class="h4 fw-800 text-white mb-0">AES-256</div>
                        <div class="small text-white-50 text-uppercase tracking-wider">Enkripsi Data</div>
                    </div>
                </div>
                <div class="small text-white-50 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-shield-halved text-simrs-primary-light"></i>
                    <span class="text-mono">{{ config('app.hospital_name') }} Security Protocol Active</span>
                </div>
            </div>
        </div>
    </section>

    <section class="d-flex align-items-center justify-content-center p-4 bg-white">
        <div class="auth-card-container w-100" style="max-width: 400px;">
            <div class="text-center mb-5 d-lg-none">
                <div class="brand-mark mx-auto mb-3">
                    <i class="fa-solid fa-microscope text-white"></i>
                </div>
                <h2 class="fw-800 h3 mb-1 text-simrs-gray-900">SIMRS Core</h2>
            </div>

            <form action="{{ route('login.store') }}" method="POST" class="auth-form">
                @csrf
                <div class="mb-4">
                    <h2 class="h4 fw-800 text-simrs-gray-900 mb-2">Selamat Datang</h2>
                    <p class="text-muted small">Silakan masuk ke akun Anda untuk memulai sesi kerja.</p>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Alamat Email</label>
                    <div class="input-group-simrs">
                        <i class="fa-solid fa-envelope icon"></i>
                        <input type="email" name="email" value="{{ old('email', 'superadmin@simrs.test') }}" class="form-control" placeholder="nama@rs-sehat.com" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Kata Sandi</label>
                    <div class="input-group-simrs">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="password" name="password" class="form-control" value="password" placeholder="Masukkan kata sandi" required>
                    </div>
                </div>

                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div class="form-check custom-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                        <label class="form-check-label small text-simrs-gray-600" for="rememberMe">Ingat saya</label>
                    </div>
                    <a href="#" class="small text-simrs-primary fw-700 text-decoration-none">Lupa password?</a>
                </div>

                <button class="btn btn-simrs-primary-lg w-100 mb-4">
                    <span>Masuk ke Sistem</span>
                    <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                </button>

                <div class="p-3 rounded-3 bg-light border text-center">
                    <div class="small text-muted mb-1">Butuh bantuan akses?</div>
                    <div class="small fw-700 text-simrs-gray-800">Hubungi Unit IT (Ext. 410)</div>
                </div>
            </form>

            <div class="mt-5 text-center">
                <p class="small text-muted">© {{ now()->year }} {{ config('app.hospital_name') }}. All rights reserved.</p>
            </div>
        </div>
    </section>
</div>

<style>
    .auth-shell { grid-template-columns: 1fr 1fr !important; }
    .fw-800 { font-weight: 800; }
    .fw-700 { font-weight: 700; }
    .fw-600 { font-weight: 600; }
    .text-simrs-primary-light { color: #14919B; }
    .text-simrs-gray-900 { color: #0F172A; }
    .text-simrs-gray-800 { color: #1E293B; }
    .text-simrs-gray-600 { color: #475569; }
    .text-simrs-primary { color: #0B6477; }
    .border-white-10 { border-color: rgba(255,255,255,0.1) !important; }
    .tracking-wider { letter-spacing: 0.05em; }
    
    .auth-panel { padding: 4rem !important; }
    
    .input-group-simrs {
        position: relative;
        display: flex;
        align-items: center;
    }
    
    .input-group-simrs .icon {
        position: absolute;
        left: 1rem;
        color: #94A3B8;
        z-index: 10;
        font-size: 0.9rem;
    }
    
    .input-group-simrs .form-control {
        padding-left: 2.75rem;
        height: 50px;
        background-color: #F8FAFC;
    }
    
    .btn-simrs-primary-lg {
        background: #0B6477;
        color: white;
        border: none;
        height: 50px;
        border-radius: 10px;
        font-weight: 700;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 4px 12px rgba(11, 100, 119, 0.2);
    }
    
    .btn-simrs-primary-lg:hover {
        background: #094E5C;
        transform: translateY(-1px);
        box-shadow: 0 6px 15px rgba(11, 100, 119, 0.3);
        color: white;
    }
    
    .custom-check .form-check-input:checked {
        background-color: #0B6477;
        border-color: #0B6477;
    }
    
    .auth-form .form-label-custom {
        font-size: 0.82rem;
        font-weight: 700;
        color: #475569;
        margin-bottom: 0.5rem;
        display: block;
    }

    @keyframes fadeInRight {
        from { opacity: 0; transform: translateX(20px); }
        to { opacity: 1; transform: translateX(0); }
    }

    .auth-card-container {
        animation: fadeInRight 0.6s ease-out;
    }

    @media(max-width:991.98px){
        .auth-shell { grid-template-columns: 1fr !important; }
    }
</style>
@endsection

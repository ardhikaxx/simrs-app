@extends('layouts.auth')

@section('title', 'Staff Authentication')

@section('content')
<div class="auth-shell">
    <div class="auth-panel">
        <div class="position-absolute top-0 start-0 p-5">
            <div class="brand-logo-container border border-white border-opacity-10">
                <i class="fa-solid fa-house-chimney-medical"></i>
            </div>
        </div>

        <div class="position-relative">
            <h1 class="display-4 fw-800 text-white mb-4">The Standard for<br><span class="text-primary-light">Healthcare Efficiency</span></h1>
            <p class="text-white-50 fs-5 mb-5 fw-medium" style="max-width: 500px; line-height: 1.6;">
                SIMRS Core provides clinical precision through integrated management, data security, and seamless hospital operations.
            </p>

            <div class="d-flex gap-5">
                <div>
                    <div class="h3 fw-800 mb-1 text-white">v1.2</div>
                    <div class="small text-uppercase fw-bold text-white-50 tracking-wider">Clinical OS</div>
                </div>
                <div class="vr opacity-25"></div>
                <div>
                    <div class="h3 fw-800 mb-1 text-white">256-bit</div>
                    <div class="small text-uppercase fw-bold text-white-50 tracking-wider">Encryption</div>
                </div>
            </div>
        </div>

        <div class="position-absolute bottom-0 start-0 p-5">
            <div class="small text-white-50 fw-medium">
                <i class="fa-solid fa-shield-halved me-2 text-primary-light"></i>
                Authorized Access Protocol Active
            </div>
        </div>
    </div>

    <div class="auth-form-side">
        <div class="w-100" style="max-width: 380px;">
            <div class="text-center mb-5 d-lg-none">
                <div class="brand-logo-container mx-auto">
                    <i class="fa-solid fa-house-chimney-medical"></i>
                </div>
                <h2 class="fw-800 text-gray-900 mb-1">SIMRS Core</h2>
                <p class="text-muted small">Hospital Information System</p>
            </div>

            <div class="mb-5">
                <h2 class="fw-800 text-gray-900 mb-2">Login Staff</h2>
                <p class="text-muted fw-medium">Silakan masuk menggunakan kredensial Anda untuk memulai sesi kerja.</p>
            </div>

            <form action="{{ route('login.store') }}" method="POST" class="needs-validation">
                @csrf
                <div class="mb-4">
                    <label class="form-label fw-800 text-gray-700 small text-uppercase tracking-wider mb-2">Alamat Email</label>
                    <div class="input-group-premium">
                        <i class="fa-solid fa-envelope icon"></i>
                        <input type="email" name="email" value="{{ old('email', 'superadmin@simrs.test') }}" class="form-control-premium" placeholder="name@hospital.com" required autofocus>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <label class="form-label fw-800 text-gray-700 small text-uppercase tracking-wider mb-0">Kata Sandi</label>
                        <a href="#" class="small text-primary fw-700 text-decoration-none">Lupa Password?</a>
                    </div>
                    <div class="input-group-premium">
                        <i class="fa-solid fa-lock icon"></i>
                        <input type="password" name="password" value="password" class="form-control-premium" placeholder="Masukkan kata sandi" required>
                    </div>
                </div>

                <div class="mb-4">
                    <div class="form-check custom-check-premium">
                        <input type="checkbox" name="remember" class="form-check-input" id="rememberMe">
                        <label class="form-check-label small fw-bold text-gray-600" for="rememberMe">Tetap masuk selama 30 hari</label>
                    </div>
                </div>

                <button type="submit" class="btn btn-login-premium w-100 mb-4">
                    <span>Masuk ke Dashboard</span>
                    <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                </button>

                <div class="p-3 rounded-4 bg-light text-center border-0">
                    <p class="small text-muted mb-0 fw-bold">Punya kendala akses? <span class="text-dark">Hubungi IT Support</span></p>
                </div>
            </form>

            <footer class="mt-5 pt-4 text-center">
                <p class="small text-muted fw-medium">&copy; {{ now()->year }} {{ config('app.hospital_name') }} &bull; HealthIT v1.2</p>
            </footer>
        </div>
    </div>
</div>

<style>
    .fw-800 { font-weight: 800; }
    .tracking-wider { letter-spacing: 0.1em; }
    .text-primary-light { color: var(--simrs-primary-light); }
    .text-gray-900 { color: var(--simrs-gray-900); }
    .text-gray-700 { color: #475569; }

    .input-group-premium {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-group-premium .icon {
        position: absolute;
        left: 1.25rem;
        color: #94A3B8;
        font-size: 0.95rem;
        z-index: 10;
    }

    .form-control-premium {
        width: 100%;
        height: 54px;
        padding: 0 1.25rem 0 3.25rem;
        background: #F1F5F9;
        border: 2px solid transparent;
        border-radius: 14px;
        font-weight: 600;
        font-size: 0.95rem;
        color: var(--simrs-gray-900);
        transition: all 0.3s ease;
    }

    .form-control-premium:focus {
        background: white;
        border-color: var(--simrs-primary);
        box-shadow: 0 10px 20px -5px rgba(13, 148, 136, 0.1);
        outline: none;
    }

    .btn-login-premium {
        height: 54px;
        background: linear-gradient(135deg, var(--simrs-primary), var(--simrs-primary-dark));
        color: white;
        border: none;
        border-radius: 14px;
        font-weight: 800;
        font-size: 1rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 10px 25px -5px rgba(13, 148, 136, 0.4);
    }

    .btn-login-premium:hover {
        transform: translateY(-2px);
        box-shadow: 0 15px 30px -5px rgba(13, 148, 136, 0.5);
        color: white;
    }

    .custom-check-premium .form-check-input {
        width: 1.15rem;
        height: 1.15rem;
        margin-top: 0.15rem;
        border-radius: 6px;
        border: 2px solid #CBD5E1;
    }

    .custom-check-premium .form-check-input:checked {
        background-color: var(--simrs-primary);
        border-color: var(--simrs-primary);
    }

    @keyframes slideUpFade {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .auth-form-side > div {
        animation: slideUpFade 0.6s ease-out forwards;
    }
</style>
@endsection

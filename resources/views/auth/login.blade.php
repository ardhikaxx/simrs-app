@extends('layouts.auth')

@section('title', 'Login Staff')

@section('content')
<div class="auth-shell">
    <section class="auth-panel">
        <div>
            <div class="brand-mark mb-4"><i class="fa-solid fa-hospital-user"></i></div>
            <h1 class="fw-bold mb-3">{{ config('app.name') }}</h1>
            <p class="text-white-50 mb-0" style="max-width:520px">Sistem Informasi Manajemen Rumah Sakit Terintegrasi untuk pendaftaran, rekam medis, penunjang, billing, BPJS, dan laporan.</p>
        </div>
        <div class="small text-white-50">
            <div class="text-mono">{{ config('app.hospital_name') }}</div>
            <div>Clinical Precision Interface</div>
        </div>
    </section>
    <section class="d-flex align-items-center justify-content-center p-3">
        <form action="{{ route('login.store') }}" method="POST" class="auth-card">
            @csrf
            <div class="mb-4">
                <div class="section-label mb-2">Akses Staff</div>
                <h2 class="h4 fw-bold mb-1">Login SIMRS</h2>
                <div class="text-muted small">Gunakan akun yang dibuat oleh administrator.</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" name="email" value="{{ old('email', 'superadmin@simrs.test') }}" class="form-control" required autofocus>
            </div>
            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <input type="password" name="password" class="form-control" required value="password">
            </div>
            <div class="d-flex align-items-center justify-content-between mb-4">
                <label class="form-check small">
                    <input type="checkbox" name="remember" class="form-check-input">
                    <span class="form-check-label">Ingat sesi</span>
                </label>
                <span class="small text-muted text-mono">Demo: password</span>
            </div>
            <button class="btn btn-simrs w-100"><i class="fa-solid fa-right-to-bracket me-2"></i>Masuk</button>
        </form>
    </section>
</div>
@endsection

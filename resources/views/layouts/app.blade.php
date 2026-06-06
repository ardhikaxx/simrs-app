<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMRS') - {{ config('app.hospital_name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
        :root{--simrs-primary:#0B6477;--simrs-primary-dark:#094E5C;--simrs-primary-light:#14919B;--simrs-primary-pale:#E6F4F7;--simrs-secondary:#2C3E7A;--simrs-secondary-pale:#EEF0F8;--simrs-accent:#E07B1F;--simrs-success:#1A8754;--simrs-success-pale:#E8F5EE;--simrs-danger:#C5372C;--simrs-danger-pale:#FDECEA;--simrs-warning:#C78A12;--simrs-warning-pale:#FEF7E6;--simrs-info:#1678B4;--simrs-info-pale:#E6F2FB;--simrs-critical:#8B0000;--simrs-critical-bg:#FFE4E4;--simrs-white:#fff;--simrs-gray-50:#F8FAFC;--simrs-gray-100:#F1F5F9;--simrs-gray-200:#E2E8F0;--simrs-gray-300:#CBD5E1;--simrs-gray-400:#94A3B8;--simrs-gray-500:#64748B;--simrs-gray-600:#475569;--simrs-gray-700:#334155;--simrs-gray-800:#1E293B;--simrs-gray-900:#0F172A;--sidebar-bg:#0B1F2E;--sidebar-hover:#132D41;--sidebar-active:#0B6477;--sidebar-text:#94A3B8;--sidebar-width:260px;--topbar-height:64px;--shadow-card:0 2px 8px rgba(30,41,59,.07),0 0 1px rgba(30,41,59,.08)}
        body{font-family:'Plus Jakarta Sans',sans-serif;font-size:.875rem;color:var(--simrs-gray-700);background:var(--simrs-gray-50);line-height:1.6}
        .text-mono{font-family:'JetBrains Mono',monospace;font-size:.82em}.page-title{font-size:1.25rem;font-weight:800;color:var(--simrs-gray-900)}.section-label{font-size:.68rem;font-weight:800;letter-spacing:.09em;text-transform:uppercase;color:var(--simrs-gray-400)}
        .simrs-sidebar{position:fixed;inset:0 auto 0 0;width:var(--sidebar-width);height:100vh;background:var(--sidebar-bg);display:flex;flex-direction:column;z-index:1040;box-shadow:4px 0 20px rgba(0,0,0,.15)}
        .sidebar-brand{display:flex;align-items:center;gap:.75rem;padding:1.25rem 1rem;min-height:72px;color:white;text-decoration:none;border-bottom:1px solid rgba(255,255,255,.06)}
        .brand-icon{width:38px;height:38px;border-radius:8px;background:linear-gradient(135deg,var(--simrs-primary-light),var(--simrs-primary));display:flex;align-items:center;justify-content:center}.brand-name{font-weight:800;font-size:1.05rem;display:block}.brand-sub{font-size:.7rem;color:var(--sidebar-text);display:block;white-space:nowrap;max-width:170px;overflow:hidden;text-overflow:ellipsis}
        .sidebar-menu{flex:1;overflow-y:auto;padding:.5rem 0}.menu-section-label{font-size:.63rem;font-weight:800;letter-spacing:.1em;text-transform:uppercase;color:#64748b;padding:1rem 1.1rem .35rem}.menu-item{display:flex;align-items:center;gap:.75rem;color:var(--sidebar-text);text-decoration:none;font-weight:600;font-size:.84rem;padding:.62rem 1rem;margin:.1rem .5rem;border-radius:8px;white-space:nowrap}.menu-item i{width:18px;text-align:center}.menu-item:hover{background:var(--sidebar-hover);color:white}.menu-item.active{background:var(--sidebar-active);color:white;box-shadow:0 2px 8px rgba(11,100,119,.35)}
        .sidebar-footer{padding:.85rem 1rem;border-top:1px solid rgba(255,255,255,.06);color:#cbd5e1}.user-avatar-sm{width:34px;height:34px;border-radius:8px;background:linear-gradient(135deg,var(--simrs-primary-light),var(--simrs-secondary));display:flex;align-items:center;justify-content:center;color:white;font-weight:800;font-size:.72rem}
        .main-wrapper{margin-left:var(--sidebar-width);min-height:100vh;display:flex;flex-direction:column}.simrs-topbar{position:sticky;top:0;height:var(--topbar-height);background:white;border-bottom:1px solid var(--simrs-gray-200);display:flex;align-items:center;justify-content:space-between;padding:0 1.25rem;z-index:1030}.simrs-content{flex:1;padding:1.5rem;max-width:1600px;width:100%}.simrs-footer{padding:.75rem 1.5rem;background:white;border-top:1px solid var(--simrs-gray-200);font-size:.75rem;color:var(--simrs-gray-500);display:flex;justify-content:space-between}
        .simrs-card{background:white;border:1px solid var(--simrs-gray-200);border-radius:8px;box-shadow:var(--shadow-card);margin-bottom:1rem}.simrs-card-header{display:flex;align-items:center;justify-content:space-between;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid var(--simrs-gray-200)}.simrs-card-title{font-weight:800;color:var(--simrs-gray-800);display:flex;align-items:center;gap:.5rem}.simrs-card-body{padding:1.25rem}
        .kpi-card{background:white;border:1px solid var(--simrs-gray-200);border-radius:8px;padding:1rem;box-shadow:var(--shadow-card)}.kpi-icon{width:42px;height:42px;border-radius:8px;display:flex;align-items:center;justify-content:center}.kpi-value{font-size:1.45rem;font-weight:800;color:var(--simrs-gray-900);line-height:1.2}.kpi-label{font-size:.72rem;font-weight:800;letter-spacing:.05em;text-transform:uppercase;color:var(--simrs-gray-500)}
        .btn-simrs-primary{background:var(--simrs-primary);border-color:var(--simrs-primary);color:white;border-radius:8px;font-weight:700}.btn-simrs-primary:hover{background:var(--simrs-primary-dark);border-color:var(--simrs-primary-dark);color:white}.btn-simrs-outline{border:1px solid var(--simrs-gray-300);background:white;color:var(--simrs-gray-700);border-radius:8px;font-weight:700}.btn-simrs-outline:hover{border-color:var(--simrs-primary);color:var(--simrs-primary);background:var(--simrs-primary-pale)}
        .form-control,.form-select{font-size:.85rem;border:1.5px solid var(--simrs-gray-200);border-radius:8px}.form-control:focus,.form-select:focus{border-color:var(--simrs-primary);box-shadow:0 0 0 3px rgba(11,100,119,.1)}.form-label-custom{font-size:.76rem;font-weight:700;color:var(--simrs-gray-600);margin-bottom:.35rem}
        .table{font-size:.84rem}.table thead th{font-size:.68rem;text-transform:uppercase;letter-spacing:.06em;color:var(--simrs-gray-500);background:var(--simrs-gray-50);border-bottom:1px solid var(--simrs-gray-200)}.table td{vertical-align:middle}.badge-status{font-size:.68rem;font-weight:800;border-radius:99px;padding:.22rem .55rem;background:var(--simrs-gray-100);color:var(--simrs-gray-600)}.status-selesai,.status-lunas,.status-aktif{background:var(--simrs-success-pale);color:var(--simrs-success)}.status-baru,.status-terdaftar,.status-draft,.status-diperiksa{background:var(--simrs-info-pale);color:var(--simrs-info)}.status-menunggu,.status-order,.status-parsial,.status-pemeriksaan-dokter,.status-dalam-perawatan,.status-menunggu-kasir,.status-menunggu-farmasi,.status-menunggu-lab,.status-menunggu-rad{background:var(--simrs-warning-pale);color:var(--simrs-warning)}.status-kritis,.status-nonaktif{background:var(--simrs-critical-bg);color:var(--simrs-critical)}.status-peringatan{background:var(--simrs-warning-pale);color:var(--simrs-warning)}.status-aman{background:var(--simrs-success-pale);color:var(--simrs-success)}
        .alert-medical{display:flex;gap:.9rem;padding:.9rem 1rem;border-radius:8px;border-left:4px solid;margin-bottom:.75rem}.alert-medical-critical{background:var(--simrs-critical-bg);border-left-color:var(--simrs-critical)}.alert-medical-warning{background:var(--simrs-warning-pale);border-left-color:var(--simrs-warning)}.alert-medical-info{background:var(--simrs-info-pale);border-left-color:var(--simrs-info)}
        .page-header-bar{display:flex;align-items:flex-start;justify-content:space-between;gap:1rem;margin-bottom:1.25rem}.patient-info-chip{display:inline-flex;align-items:center;gap:.45rem;background:var(--simrs-primary-pale);color:var(--simrs-primary-dark);border-radius:99px;padding:.35rem .75rem;font-size:.78rem;font-weight:700}
        @media(max-width:991.98px){.simrs-sidebar{transform:translateX(-100%)}.main-wrapper{margin-left:0}.simrs-content{padding:1rem}.page-header-bar{flex-direction:column}.simrs-footer{display:none}}
        @media print{.simrs-sidebar,.simrs-topbar,.simrs-footer,.page-header-actions,.btn{display:none!important}.main-wrapper{margin-left:0}.simrs-card{box-shadow:none}.simrs-content{padding:0}}
    </style>
    @yield('styles')
</head>
<body>
@php($staff = auth('staff')->user())
<nav class="simrs-sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-hospital-user"></i></div>
        <div><span class="brand-name">SIMRS</span><span class="brand-sub">{{ config('app.hospital_name') }}</span></div>
    </a>
    <div class="sidebar-menu">
        <div class="menu-section-label">Utama</div>
        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fa-solid fa-gauge-high"></i><span>Dashboard</span></a>
        <div class="menu-section-label">Pelayanan</div>
        <a href="{{ route('pendaftaran.pasien.index') }}" class="menu-item {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}"><i class="fa-solid fa-clipboard-list"></i><span>Pendaftaran</span></a>
        <a href="{{ route('keperawatan.antrian') }}" class="menu-item {{ request()->routeIs('keperawatan.*') ? 'active' : '' }}"><i class="fa-solid fa-heart-pulse"></i><span>Keperawatan</span></a>
        <a href="{{ route('rekam-medis.antrian') }}" class="menu-item {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}"><i class="fa-solid fa-notes-medical"></i><span>Rekam Medis</span></a>
        <div class="menu-section-label">Penunjang</div>
        <a href="{{ route('farmasi.antrian-resep') }}" class="menu-item {{ request()->routeIs('farmasi.*') ? 'active' : '' }}"><i class="fa-solid fa-pills"></i><span>Farmasi</span></a>
        <a href="{{ route('lab.antrian') }}" class="menu-item {{ request()->routeIs('lab.*') ? 'active' : '' }}"><i class="fa-solid fa-flask-vial"></i><span>Laboratorium</span></a>
        <a href="{{ route('rad.antrian') }}" class="menu-item {{ request()->routeIs('rad.*') ? 'active' : '' }}"><i class="fa-solid fa-x-ray"></i><span>Radiologi</span></a>
        <div class="menu-section-label">Keuangan</div>
        <a href="{{ route('keuangan.antrian-kasir') }}" class="menu-item {{ request()->routeIs('keuangan.*') ? 'active' : '' }}"><i class="fa-solid fa-cash-register"></i><span>Kasir & Billing</span></a>
        <a href="{{ route('casemix.index') }}" class="menu-item {{ request()->routeIs('casemix.*') ? 'active' : '' }}"><i class="fa-solid fa-file-invoice-dollar"></i><span>Casemix</span></a>
        <a href="{{ route('bpjs.index') }}" class="menu-item {{ request()->routeIs('bpjs.*') ? 'active' : '' }}"><i class="fa-solid fa-id-card-clip"></i><span>BPJS</span></a>
        <div class="menu-section-label">Analitik</div>
        <a href="{{ route('laporan.kunjungan') }}" class="menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}"><i class="fa-solid fa-chart-line"></i><span>Laporan</span></a>
        <div class="menu-section-label">Sistem</div>
        <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"><i class="fa-solid fa-users-gear"></i><span>Manajemen User</span></a>
        <a href="{{ route('admin.audit.index') }}" class="menu-item {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}"><i class="fa-solid fa-shield-halved"></i><span>Audit Trail</span></a>
    </div>
    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-2">
            <div class="user-avatar-sm">{{ strtoupper(substr($staff?->display_name ?? 'RS', 0, 2)) }}</div>
            <div class="min-w-0">
                <div class="fw-bold text-truncate" style="max-width:180px">{{ $staff?->display_name }}</div>
                <div class="small text-secondary">{{ $staff?->roles->first()?->nama_peran ?? 'Staf' }}</div>
            </div>
        </div>
    </div>
</nav>
<main class="main-wrapper">
    <header class="simrs-topbar">
        <div>
            <div class="page-title">@yield('page-title', 'SIMRS')</div>
            <div class="small text-muted">@yield('page-subtitle', config('app.hospital_name'))</div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="text-mono small text-muted d-none d-md-inline" id="clock">{{ now()->format('d/m/Y H:i') }}</span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="btn btn-sm btn-simrs-outline"><i class="fa-solid fa-right-from-bracket me-1"></i>Logout</button>
            </form>
        </div>
    </header>
    <section class="simrs-content">
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm">
                <strong>Validasi gagal.</strong> {{ $errors->first() }}
            </div>
        @endif
        @yield('content')
    </section>
    <footer class="simrs-footer">
        <span>SIMRS Terintegrasi</span>
        <span>{{ config('app.hospital_name') }} - {{ now()->year }}</span>
    </footer>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
@include('partials.swal')
<script>
    setInterval(() => {
        const el = document.getElementById('clock');
        if (el) el.textContent = new Intl.DateTimeFormat('id-ID', {dateStyle:'short', timeStyle:'short'}).format(new Date());
    }, 1000);
</script>
@yield('scripts')
</body>
</html>

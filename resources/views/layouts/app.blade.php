<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMRS') - {{ config('app.hospital_name') }}</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    
    <!-- CSS Frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    
    <style>
        :root {
            --simrs-primary: #0B6477;
            --simrs-primary-dark: #074755;
            --simrs-primary-light: #14919B;
            --simrs-primary-pale: #F0F9FA;
            --simrs-secondary: #2C3E7A;
            --simrs-secondary-pale: #EEF0F8;
            --simrs-accent: #E07B1F;
            --simrs-success: #10B981;
            --simrs-success-pale: #ECFDF5;
            --simrs-danger: #EF4444;
            --simrs-danger-pale: #FEF2F2;
            --simrs-warning: #F59E0B;
            --simrs-warning-pale: #FFFBEB;
            --simrs-info: #3B82F6;
            --simrs-info-pale: #EFF6FF;
            --simrs-gray-50: #F8FAFC;
            --simrs-gray-100: #F1F5F9;
            --simrs-gray-200: #E2E8F0;
            --simrs-gray-300: #CBD5E1;
            --simrs-gray-400: #94A3B8;
            --simrs-gray-500: #64748B;
            --simrs-gray-600: #475569;
            --simrs-gray-700: #334155;
            --simrs-gray-800: #1E293B;
            --simrs-gray-900: #0F172A;
            
            --sidebar-bg: #ffffff;
            --sidebar-hover: #F8FAFC;
            --sidebar-active: #E6F4F7;
            --sidebar-text: #475569;
            --sidebar-width: 280px;
            --topbar-height: 76px;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            color: var(--simrs-gray-700);
            background: #F4F7F9;
            line-height: 1.6;
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Sidebar Styling */
        .simrs-sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--sidebar-bg);
            display: flex;
            flex-direction: column;
            z-index: 1040;
            transition: var(--transition-smooth);
            border-right: 1px solid var(--simrs-gray-200);
            box-shadow: 4px 0 24px rgba(0,0,0,0.02);
        }

        .sidebar-brand {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1.75rem 1.5rem;
            text-decoration: none;
            position: relative;
        }
        
        .sidebar-brand::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 1.5rem;
            right: 1.5rem;
            height: 1px;
            background: linear-gradient(90deg, var(--simrs-gray-200), transparent);
        }

        .brand-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary));
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
            box-shadow: 0 8px 16px rgba(11, 100, 119, 0.25);
            border: 2px solid white;
        }

        .brand-name { font-weight: 800; font-size: 1.25rem; letter-spacing: -0.03em; color: var(--simrs-gray-900); }
        .brand-sub { font-size: 0.75rem; color: var(--simrs-primary); font-weight: 700; text-transform: uppercase; letter-spacing: 0.05em; }

        .sidebar-menu {
            flex: 1;
            overflow-y: auto;
            padding: 1.5rem 0;
            scrollbar-width: thin;
            scrollbar-color: var(--simrs-gray-300) transparent;
        }

        .menu-section-label {
            font-size: 0.65rem;
            font-weight: 800;
            letter-spacing: 0.15em;
            text-transform: uppercase;
            color: var(--simrs-gray-500);
            padding: 1.5rem 1.75rem 0.75rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .menu-section-label::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--simrs-gray-200);
        }

        .menu-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.9rem;
            padding: 0.8rem 1.5rem;
            margin: 0.2rem 1rem;
            border-radius: 10px;
            transition: var(--transition-smooth);
            position: relative;
        }

        .menu-item i { 
            width: 24px; 
            text-align: center; 
            font-size: 1.1rem; 
            color: var(--simrs-gray-400); 
            transition: var(--transition-smooth);
        }
        
        .menu-item:hover { 
            background: var(--sidebar-hover); 
            color: var(--simrs-gray-900); 
            transform: translateX(4px);
        }
        
        .menu-item:hover i { color: var(--simrs-primary); }
        
        .menu-item.active { 
            background: var(--sidebar-active); 
            color: var(--simrs-primary-dark); 
            font-weight: 700;
        }
        
        .menu-item.active i { color: var(--simrs-primary); }
        
        .menu-item.active::before {
            content: '';
            position: absolute;
            left: -1rem;
            top: 50%;
            transform: translateY(-50%);
            height: 60%;
            width: 4px;
            background: var(--simrs-primary);
            border-radius: 0 4px 4px 0;
        }

        .sidebar-footer {
            padding: 1.5rem;
            background: white;
            border-top: 1px solid var(--simrs-gray-200);
        }

        /* Main Wrapper */
        .main-wrapper {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            transition: var(--transition-smooth);
        }

        .simrs-topbar {
            position: sticky;
            top: 0;
            height: var(--topbar-height);
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(226, 232, 240, 0.8);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2rem;
            z-index: 1030;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .page-title { 
            font-size: 1.4rem; 
            font-weight: 800; 
            color: var(--simrs-gray-900); 
            letter-spacing: -0.02em; 
            margin-bottom: 0.1rem;
        }
        
        .simrs-content {
            flex: 1;
            padding: 2.5rem;
            max-width: 1600px;
            width: 100%;
            margin: 0 auto;
        }

        /* Component Enhancements */
        .simrs-card {
            background: white;
            border: 1px solid rgba(226, 232, 240, 0.8);
            border-radius: 16px;
            box-shadow: var(--shadow-sm);
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: var(--transition-smooth);
        }
        
        .simrs-card:hover {
            box-shadow: var(--shadow-md);
        }

        .simrs-card-header {
            padding: 1.5rem;
            background: white;
            border-bottom: 1px solid var(--simrs-gray-100);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .simrs-card-title { 
            font-weight: 800; 
            font-size: 1.1rem; 
            color: var(--simrs-gray-800); 
            display: flex; 
            align-items: center; 
            gap: 0.75rem; 
        }
        
        .simrs-card-title i { color: var(--simrs-primary); }

        .btn-simrs-primary {
            background: linear-gradient(to right, var(--simrs-primary), var(--simrs-primary-light));
            border: none;
            color: white;
            border-radius: 10px;
            font-weight: 700;
            padding: 0.6rem 1.5rem;
            transition: var(--transition-smooth);
            box-shadow: 0 4px 12px rgba(11, 100, 119, 0.25);
        }

        .btn-simrs-primary:hover, .btn-simrs-primary:focus {
            background: linear-gradient(to right, var(--simrs-primary-dark), var(--simrs-primary));
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(11, 100, 119, 0.35);
            color: white;
        }
        
        .btn-simrs-outline {
            background: white;
            border: 1px solid var(--simrs-gray-300);
            color: var(--simrs-gray-700);
            border-radius: 10px;
            font-weight: 600;
            padding: 0.6rem 1.5rem;
            transition: var(--transition-smooth);
        }
        
        .btn-simrs-outline:hover {
            background: var(--simrs-gray-50);
            border-color: var(--simrs-primary);
            color: var(--simrs-primary);
        }

        .badge-status {
            font-size: 0.7rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.4rem 1rem;
            border-radius: 6px;
        }
        
        .form-control, .form-select {
            border-radius: 10px;
            border-color: var(--simrs-gray-300);
            padding: 0.6rem 1rem;
            font-size: 0.9rem;
            transition: var(--transition-smooth);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--simrs-primary-light);
            box-shadow: 0 0 0 4px var(--simrs-primary-pale);
        }
        
        .form-label-custom {
            font-weight: 700;
            color: var(--simrs-gray-700);
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideInUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        .simrs-content { animation: slideInUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) forwards; }

        @media(max-width: 991.98px) {
            .simrs-sidebar { transform: translateX(-100%); }
            .simrs-sidebar.show { transform: translateX(0); }
            .main-wrapper { margin-left: 0; }
            .simrs-content { padding: 1.5rem; }
            .page-title { font-size: 1.25rem; }
        }
        
        /* Premium Scrollbar */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: var(--simrs-gray-300); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: var(--simrs-gray-400); }
    </style>
    @yield('styles')
</head>
<body>

@php($staff = auth('staff')->user())

<nav class="simrs-sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon">
            <i class="fa-solid fa-microscope text-white"></i>
        </div>
        <div>
            <div class="brand-name">SIMRS Core</div>
            <div class="brand-sub">Clinical Precision</div>
        </div>
    </a>

    <div class="sidebar-menu">
        <div class="menu-section-label">Main Dashboard</div>
        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-grid-2-vertical"></i><span>Dashboard</span>
        </a>

        <div class="menu-section-label">Front Office & Inap</div>
        <a href="{{ route('pendaftaran.pasien.index') }}" class="menu-item {{ request()->routeIs('pendaftaran.pasien.*') ? 'active' : '' }}">
            <i class="fa-solid fa-hospital-user"></i><span>Master Pasien</span>
        </a>
        <a href="{{ route('pendaftaran.antrian') }}" class="menu-item {{ request()->routeIs('pendaftaran.antrian') ? 'active' : '' }}">
            <i class="fa-solid fa-list-ol"></i><span>Antrean Unit</span>
        </a>
        <a href="{{ route('pendaftaran.beds.index') }}" class="menu-item {{ request()->routeIs('pendaftaran.beds.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bed-pulse"></i><span>Bed Management</span>
        </a>

        <div class="menu-section-label">Clinical Service</div>
        <a href="{{ route('keperawatan.antrian') }}" class="menu-item {{ request()->routeIs('keperawatan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-nurse"></i><span>Asuhan Keperawatan</span>
        </a>
        <a href="{{ route('rekam-medis.antrian') }}" class="menu-item {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}">
            <i class="fa-solid fa-stethoscope"></i><span>Rekam Medis (RME)</span>
        </a>

        <div class="menu-section-label">Ancillary Service</div>
        <a href="{{ route('farmasi.antrian-resep') }}" class="menu-item {{ request()->routeIs('farmasi.antrian-resep') ? 'active' : '' }}">
            <i class="fa-solid fa-receipt"></i><span>Resep Farmasi</span>
        </a>
        <a href="{{ route('farmasi.inventory.index') }}" class="menu-item {{ request()->routeIs('farmasi.inventory.*') ? 'active' : '' }}">
            <i class="fa-solid fa-pills"></i><span>Stok & Katalog</span>
        </a>
        <a href="{{ route('farmasi.procurement.index') }}" class="menu-item {{ request()->routeIs('farmasi.procurement.*') ? 'active' : '' }}">
            <i class="fa-solid fa-truck-ramp-box"></i><span>Pengadaan (PO)</span>
        </a>
        <a href="{{ route('lab.antrian') }}" class="menu-item {{ request()->routeIs('lab.*') ? 'active' : '' }}">
            <i class="fa-solid fa-flask-vial"></i><span>Laboratorium</span>
        </a>
        <a href="{{ route('rad.antrian') }}" class="menu-item {{ request()->routeIs('rad.*') ? 'active' : '' }}">
            <i class="fa-solid fa-x-ray"></i><span>Radiologi</span>
        </a>

        <div class="menu-section-label">Finance & JKN</div>
        <a href="{{ route('public.queue.display') }}" target="_blank" class="menu-item">
            <i class="fa-solid fa-tv"></i><span>Layar Antrean TV</span>
        </a>
        <a href="{{ route('keuangan.antrian-kasir') }}" class="menu-item {{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-cash-register"></i><span>Kasir & Billing</span>
        </a>
        <a href="{{ route('casemix.index') }}" class="menu-item {{ request()->routeIs('casemix.*') ? 'active' : '' }}">
            <i class="fa-solid fa-calculator"></i><span>Casemix Monitoring</span>
        </a>
        <a href="{{ route('bpjs.index') }}" class="menu-item {{ request()->routeIs('bpjs.*') ? 'active' : '' }}">
            <i class="fa-solid fa-id-card-clip"></i><span>BPJS Bridging</span>
        </a>

        <div class="menu-section-label">Laporan & Analitik</div>
        <a href="{{ route('laporan.bi.dashboard') }}" class="menu-item {{ request()->routeIs('laporan.bi.*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i><span>Analitik Eksekutif</span>
        </a>
        <a href="{{ route('laporan.kunjungan') }}" class="menu-item {{ request()->routeIs('laporan.kunjungan') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice"></i><span>Lap. Kunjungan</span>
        </a>
        <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear"></i><span>User Management</span>
        </a>
        <a href="{{ route('admin.audit.index') }}" class="menu-item {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
            <i class="fa-solid fa-shield-halved"></i><span>System Audit</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="d-flex align-items-center gap-3">
            <div class="user-avatar-sm" style="width: 38px; height: 38px; background: var(--simrs-primary-light); color: white; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-weight: 800;">
                {{ strtoupper(substr($staff?->display_name ?? 'RS', 0, 1)) }}
            </div>
            <div class="min-w-0">
                <div class="fw-bold text-white text-truncate small" style="max-width: 140px;">{{ $staff?->display_name }}</div>
                <div class="small text-muted" style="font-size: 0.65rem; text-transform: uppercase; font-weight: 700;">{{ $staff?->roles->first()?->nama_peran ?? 'STAFF' }}</div>
            </div>
        </div>
    </div>
</nav>

<main class="main-wrapper">
    <header class="simrs-topbar">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-sm btn-light d-lg-none border shadow-sm" id="sidebarToggle">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <div>
                <div class="page-title">@yield('page-title', 'Dashboard')</div>
                <div class="small text-muted fw-600 d-none d-md-block" style="font-size: 0.72rem;">
                    <i class="fa-solid fa-hospital me-1 opacity-50"></i> {{ config('app.hospital_name') }}
                </div>
            </div>
        </div>
        
        <div class="d-flex align-items-center gap-4">
            <div class="text-end d-none d-lg-block">
                <div class="fw-800 text-simrs-gray-900 small" id="clock">{{ now()->format('H:i') }}</div>
                <div class="small text-muted fw-600" style="font-size: 0.65rem; text-transform: uppercase;">{{ now()->translatedFormat('l, d F Y') }}</div>
            </div>
            
            <div class="vr mx-2 opacity-10 d-none d-lg-block"></div>
            
            <div class="dropdown">
                <button class="btn btn-sm btn-simrs-outline px-3 border-0 shadow-none d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-circle-user fs-5 text-simrs-primary"></i>
                    <i class="fa-solid fa-chevron-down small opacity-50" style="font-size: 0.6rem;"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2 p-2">
                    <li><a class="dropdown-item py-2 rounded-2 small fw-600" href="#"><i class="fa-solid fa-user-gear me-2 opacity-50"></i>Pengaturan Akun</a></li>
                    <li><hr class="dropdown-divider opacity-5"></li>
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button class="dropdown-item py-2 rounded-2 small fw-700 text-danger">
                                <i class="fa-solid fa-power-off me-2 opacity-75"></i>Keluar Sistem
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <section class="simrs-content">
        @if($errors->any())
            <div class="alert alert-danger border-0 shadow-sm rounded-3 d-flex align-items-center gap-3 p-3 mb-4">
                <i class="fa-solid fa-circle-exclamation fs-4"></i>
                <div>
                    <div class="fw-800">Terjadi Kesalahan Validasi</div>
                    <div class="small opacity-75">{{ $errors->first() }}</div>
                </div>
            </div>
        @endif

        @yield('content')
    </section>

    <footer class="simrs-footer px-4 py-3 bg-white border-top d-flex justify-content-between align-items-center small">
        <div class="text-muted fw-600">
            <span class="text-simrs-primary fw-800">SIMRS Core</span> v1.0.0 &bull; <span class="text-mono">{{ now()->year }}</span>
        </div>
        <div class="text-muted fw-600 d-none d-md-block">
            Built with <i class="fa-solid fa-heart text-danger mx-1"></i> for Healthcare Excellence
        </div>
    </footer>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@include('partials.swal')

<script>
    // Real-time Clock
    function updateClock() {
        const now = new Date();
        const el = document.getElementById('clock');
        if (el) {
            el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Sidebar Toggle
    document.getElementById('sidebarToggle')?.addEventListener('click', function() {
        document.getElementById('sidebar').classList.toggle('show');
    });

    // Initialize Select2 if exists
    $(document).ready(function() {
        if ($('.select2-init').length) {
            $('.select2-init').select2({
                theme: 'bootstrap-5',
                width: '100%'
            });
        }
    });
</script>

@yield('scripts')
</body>
</html>

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
            --simrs-primary: #0D9488; /* Teal 600 */
            --simrs-primary-dark: #0F766E;
            --simrs-primary-light: #2DD4BF;
            --simrs-primary-pale: #F0FDFA;
            --simrs-secondary: #1E293B; /* Slate 800 */
            --simrs-accent: #F59E0B; /* Amber 500 */
            
            --sidebar-bg: #0F172A; /* Slate 900 */
            --sidebar-text: #94A3B8;
            --sidebar-text-active: #FFFFFF;
            --sidebar-width: 280px;
            
            --topbar-height: 80px;
            --shadow-premium: 0 10px 30px -5px rgba(0, 0, 0, 0.04), 0 4px 10px -5px rgba(0, 0, 0, 0.02);
            --transition-bounce: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #F8FAFC;
            color: #334155;
            -webkit-font-smoothing: antialiased;
            letter-spacing: -0.01em;
        }

        /* Sidebar Modernization */
        .simrs-sidebar {
            position: fixed;
            left: 0;
            top: 0;
            bottom: 0;
            width: var(--sidebar-width);
            background: var(--sidebar-bg);
            z-index: 1050;
            transition: var(--transition-bounce);
            display: flex;
            flex-direction: column;
            box-shadow: 10px 0 40px rgba(0,0,0,0.1);
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .brand-box {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 8px 20px rgba(13, 148, 136, 0.3);
            color: white;
            font-size: 1.2rem;
        }

        .brand-text-main {
            font-weight: 800;
            font-size: 1.15rem;
            color: white;
            line-height: 1.2;
            letter-spacing: -0.02em;
        }

        .brand-text-sub {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: var(--simrs-primary-light);
            font-weight: 700;
        }

        .sidebar-content {
            flex: 1;
            overflow-y: auto;
            padding: 1rem 0.75rem;
            scrollbar-width: none;
        }
        .sidebar-content::-webkit-scrollbar { display: none; }

        .nav-category {
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: #475569;
            font-weight: 800;
            margin: 1.5rem 1.25rem 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link-custom {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.85rem 1.25rem;
            color: var(--sidebar-text);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.875rem;
            border-radius: 12px;
            transition: all 0.2s ease;
            margin-bottom: 0.25rem;
        }

        .nav-link-custom i {
            font-size: 1.1rem;
            width: 24px;
            text-align: center;
            transition: all 0.2s ease;
        }

        .nav-link-custom:hover {
            background: rgba(255,255,255,0.05);
            color: white;
            transform: translateX(4px);
        }

        .nav-link-custom.active {
            background: linear-gradient(to right, rgba(13, 148, 136, 0.15), transparent);
            color: var(--simrs-primary-light);
            position: relative;
        }

        .nav-link-custom.active::after {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            bottom: 20%;
            width: 3px;
            background: var(--simrs-primary);
            border-radius: 0 4px 4px 0;
        }

        .sidebar-user-card {
            margin: 1.5rem 1rem;
            padding: 1.25rem;
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.05);
            border-radius: 16px;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            background: var(--simrs-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 800;
            font-size: 1.1rem;
        }

        /* Topbar Sophistication */
        .simrs-main {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: var(--transition-bounce);
        }

        .simrs-header {
            height: var(--topbar-height);
            background: rgba(255,255,255,0.8);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            position: sticky;
            top: 0;
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 2.5rem;
            border-bottom: 1px solid #F1F5F9;
        }

        .header-search {
            background: #F1F5F9;
            border-radius: 14px;
            padding: 0.6rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            max-width: 400px;
            border: 1px solid transparent;
            transition: all 0.3s ease;
        }

        .header-search:focus-within {
            background: white;
            border-color: var(--simrs-primary-light);
            box-shadow: 0 0 0 4px var(--simrs-primary-pale);
        }

        .header-search input {
            border: none;
            background: transparent;
            outline: none;
            width: 100%;
            font-size: 0.875rem;
            font-weight: 500;
        }

        /* Content Area */
        .content-body {
            padding: 2.5rem;
            max-width: 1600px;
            margin: 0 auto;
        }

        .page-header-title {
            font-size: 1.75rem;
            font-weight: 800;
            color: #0F172A;
            letter-spacing: -0.03em;
            margin-bottom: 0.25rem;
        }

        .page-header-desc {
            font-size: 0.875rem;
            color: #64748B;
            font-weight: 500;
        }

        /* Premium Components */
        .card-premium {
            background: white;
            border-radius: 20px;
            border: 1px solid #F1F5F9;
            box-shadow: var(--shadow-premium);
            transition: all 0.3s ease;
        }

        .btn-premium {
            padding: 0.75rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-premium-primary {
            background: linear-gradient(135deg, var(--simrs-primary), var(--simrs-primary-dark));
            color: white;
            border: none;
            box-shadow: 0 10px 20px -5px rgba(13, 148, 136, 0.4);
        }

        .btn-premium-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 25px -5px rgba(13, 148, 136, 0.5);
            color: white;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .simrs-sidebar { transform: translateX(-100%); }
            .simrs-sidebar.open { transform: translateX(0); }
            .simrs-main { margin-left: 0; }
            .simrs-header { padding: 0 1.5rem; }
            .content-body { padding: 1.5rem; }
        }
    </style>
    @yield('styles')
</head>
<body>

@php($staff = auth('staff')->user())

<aside class="simrs-sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="brand-box">
            <i class="fa-solid fa-house-chimney-medical"></i>
        </div>
        <div>
            <div class="brand-text-main">SIMRS <span class="text-primary-light">Core</span></div>
            <div class="brand-text-sub">Next-Gen Clinical</div>
        </div>
    </div>

    <div class="sidebar-content">
        <div class="nav-category">Dashboard & Stats</div>
        <a href="{{ route('dashboard') }}" class="nav-link-custom {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-pie"></i><span>Overview Hub</span>
        </a>

        <div class="nav-category">Front Desk & Inpatient</div>
        <a href="{{ route('pendaftaran.pasien.index') }}" class="nav-link-custom {{ request()->routeIs('pendaftaran.pasien.*') ? 'active' : '' }}">
            <i class="fa-solid fa-address-book"></i><span>Master Pasien</span>
        </a>
        <a href="{{ route('pendaftaran.antrian') }}" class="nav-link-custom {{ request()->routeIs('pendaftaran.antrian') ? 'active' : '' }}">
            <i class="fa-solid fa-users-viewfinder"></i><span>Antrean Terpadu</span>
        </a>
        <a href="{{ route('pendaftaran.beds.index') }}" class="nav-link-custom {{ request()->routeIs('pendaftaran.beds.*') ? 'active' : '' }}">
            <i class="fa-solid fa-bed-pulse"></i><span>Bed Monitoring</span>
        </a>

        <div class="nav-category">Clinical Workflow</div>
        <a href="{{ route('keperawatan.antrian') }}" class="nav-link-custom {{ request()->routeIs('keperawatan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-nurse"></i><span>Asuhan Keperawatan</span>
        </a>
        <a href="{{ route('rekam-medis.antrian') }}" class="nav-link-custom {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}">
            <i class="fa-solid fa-notes-medical"></i><span>Rekam Medis (RME)</span>
        </a>

        <div class="nav-category">Ancillary & Support</div>
        <a href="{{ route('farmasi.antrian-resep') }}" class="nav-link-custom {{ request()->routeIs('farmasi.antrian-resep') ? 'active' : '' }}">
            <i class="fa-solid fa-prescription-bottle-medical"></i><span>E-Prescription</span>
        </a>
        <a href="{{ route('lab.antrian') }}" class="nav-link-custom {{ request()->routeIs('lab.*') ? 'active' : '' }}">
            <i class="fa-solid fa-vials"></i><span>Laboratorium</span>
        </a>
        <a href="{{ route('rad.antrian') }}" class="nav-link-custom {{ request()->routeIs('rad.*') ? 'active' : '' }}">
            <i class="fa-solid fa-x-ray"></i><span>Radiologi</span>
        </a>

        <div class="nav-category">Finance & JKN</div>
        <a href="{{ route('keuangan.antrian-kasir') }}" class="nav-link-custom {{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-wallet"></i><span>Billing & Kasir</span>
        </a>
        <a href="{{ route('casemix.index') }}" class="nav-link-custom {{ request()->routeIs('casemix.*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar"></i><span>Casemix / JKN</span>
        </a>
        
        <div class="nav-category">System Admin</div>
        <a href="{{ route('admin.users.index') }}" class="nav-link-custom {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="fa-solid fa-user-shield"></i><span>User Management</span>
        </a>
    </div>

    <div class="sidebar-user-card">
        <div class="user-avatar">
            {{ strtoupper(substr($staff?->display_name ?? 'RS', 0, 1)) }}
        </div>
        <div class="min-w-0 overflow-hidden">
            <div class="fw-bold text-white small text-truncate">{{ $staff?->display_name }}</div>
            <div class="small text-muted text-uppercase fw-bold" style="font-size: 0.6rem;">{{ $staff?->roles->first()?->nama_peran ?? 'STAFF' }}</div>
        </div>
        <form action="{{ route('logout') }}" method="POST" class="ms-auto">
            @csrf
            <button class="btn btn-link p-0 text-muted hover-white transition-all border-0 shadow-none">
                <i class="fa-solid fa-power-off"></i>
            </button>
        </form>
    </div>
</aside>

<main class="simrs-main">
    <header class="simrs-header">
        <div class="d-flex align-items-center gap-4">
            <button class="btn d-lg-none p-0 fs-4 text-slate" id="menuToggle">
                <i class="fa-solid fa-bars-staggered"></i>
            </button>
            <div class="d-none d-md-block">
                <div class="page-header-title">@yield('page-title', 'Dashboard')</div>
                <div class="page-header-desc">@yield('page-subtitle', 'Monitoring real-time aktivitas klinis rumah sakit')</div>
            </div>
        </div>

        <div class="d-flex align-items-center gap-4">
            <div class="header-search d-none d-xl-flex">
                <i class="fa-solid fa-magnifying-glass text-slate opacity-40"></i>
                <input type="text" placeholder="Cari data pasien, order, atau rekam medis...">
                <span class="badge bg-white text-slate border small shadow-sm">⌘K</span>
            </div>

            <div class="vr mx-2 opacity-10 d-none d-lg-block"></div>

            <div class="d-flex align-items-center gap-3">
                <div class="text-end d-none d-sm-block">
                    <div class="fw-800 text-slate mb-0" id="digitalClock" style="font-size: 1.1rem; line-height: 1;">00:00</div>
                    <div class="small text-muted fw-bold" style="font-size: 0.7rem;">{{ now()->translatedFormat('d M Y') }}</div>
                </div>
                <div class="dropdown">
                    <button class="btn p-1 border-0 shadow-none" data-bs-toggle="dropdown">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($staff?->display_name) }}&background=0D9488&color=fff&bold=true" class="rounded-circle border" style="width: 42px;">
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 mt-3 p-2" style="min-width: 220px;">
                        <li class="p-3 border-bottom mb-2">
                            <div class="fw-800 text-dark">{{ $staff?->display_name }}</div>
                            <div class="small text-muted">{{ $staff?->username }}</div>
                        </li>
                        <li><a class="dropdown-item rounded-3 py-2 fw-bold small" href="#"><i class="fa-solid fa-id-card-clip me-2 opacity-50"></i>Profil Saya</a></li>
                        <li><a class="dropdown-item rounded-3 py-2 fw-bold small" href="#"><i class="fa-solid fa-gears me-2 opacity-50"></i>Pengaturan</a></li>
                        <li><hr class="dropdown-divider opacity-5"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button class="dropdown-item rounded-3 py-2 fw-800 small text-danger">
                                    <i class="fa-solid fa-door-open me-2"></i>Keluar Sistem
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </header>

    <div class="content-body">
        @if(session('swal_success') || session('swal_error') || session('swal_warning'))
            <!-- Feedback section handled by partials.swal -->
        @endif
        
        @yield('content')
    </div>

    <footer class="mt-auto py-4 px-5 bg-white border-top d-flex justify-content-between align-items-center">
        <div class="small text-muted fw-bold">
            <span class="text-primary fw-800">SIMRS Core</span> &bull; Clinical OS v1.2 &copy; {{ now()->year }}
        </div>
        <div class="small text-muted fw-bold d-none d-md-block">
            Made with <i class="fa-solid fa-heart text-danger mx-1"></i> for Patient Safety
        </div>
    </footer>
</main>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

@include('partials.swal')

<script>
    // Digital Clock
    function updateClock() {
        const now = new Date();
        const clockEl = document.getElementById('digitalClock');
        if (clockEl) {
            clockEl.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', hour12: false });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Sidebar Toggle
    const menuToggle = document.getElementById('menuToggle');
    const sidebar = document.getElementById('sidebar');
    if (menuToggle && sidebar) {
        menuToggle.addEventListener('click', () => {
            sidebar.classList.toggle('open');
        });
    }

    // Auto-initialize Select2
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

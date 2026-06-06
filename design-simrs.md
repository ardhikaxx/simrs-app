# design-simrs.md
# Panduan Desain UI/UX — SIMRS Terintegrasi
# Visual Identity: "Clinical Precision" — Desain Antarmuka Medis Modern

---

## 1. KONSEP DESAIN & IDENTITAS VISUAL

### 1.1 Filosofi Desain

**"Clinical Precision"** — Antarmuka yang mencerminkan ketepatan klinis: bersih, hierarkis, fungsional, dan tepercaya. Setiap elemen dirancang untuk meminimalkan beban kognitif tenaga kesehatan yang bekerja di bawah tekanan tinggi.

**Prinsip Utama:**
- **Clarity** — Informasi paling kritis selalu terlihat pertama
- **Speed** — Aksi yang sering dilakukan mudah dijangkau (≤ 2 klik)
- **Trust** — Palet warna medis teal-biru yang menenangkan dan profesional
- **Density** — Data padat namun tetap terbaca dengan tipografi terstruktur

### 1.2 Target Pengguna & Konteks Penggunaan
- Digunakan di dalam ruangan (cahaya buatan, layar 1920×1080 atau tablet 1024px)
- Pengguna multitasking (dokter sambil berbicara dengan pasien)
- Operasi 24/7 oleh berbagai shift staf
- Campuran tingkat melek teknologi tinggi (dokter, IT) hingga rendah (petugas pendaftaran senior)

---

## 2. PALET WARNA

### 2.1 CSS Custom Properties

```css
:root {
    /* PRIMARY — Medical Teal */
    --simrs-primary:        #0B6477;
    --simrs-primary-dark:   #094E5C;
    --simrs-primary-light:  #14919B;
    --simrs-primary-pale:   #E6F4F7;

    /* SECONDARY — Warm Indigo */
    --simrs-secondary:      #2C3E7A;
    --simrs-secondary-dark: #1E2D5F;
    --simrs-secondary-light:#4A5FA8;
    --simrs-secondary-pale: #EEF0F8;

    /* ACCENT — Alert Amber */
    --simrs-accent:         #E07B1F;
    --simrs-accent-dark:    #B8611A;
    --simrs-accent-pale:    #FDF3E3;

    /* STATUS SEMANTIK */
    --simrs-success:        #1A8754;
    --simrs-success-pale:   #E8F5EE;
    --simrs-danger:         #C5372C;
    --simrs-danger-pale:    #FDECEA;
    --simrs-warning:        #C78A12;
    --simrs-warning-pale:   #FEF7E6;
    --simrs-info:           #1678B4;
    --simrs-info-pale:      #E6F2FB;

    /* WARNA KRITIS MEDIS */
    --simrs-critical:       #8B0000;
    --simrs-critical-bg:    #FFE4E4;

    /* NEUTRAL */
    --simrs-white:          #FFFFFF;
    --simrs-gray-50:        #F8FAFC;
    --simrs-gray-100:       #F1F5F9;
    --simrs-gray-200:       #E2E8F0;
    --simrs-gray-300:       #CBD5E1;
    --simrs-gray-400:       #94A3B8;
    --simrs-gray-500:       #64748B;
    --simrs-gray-600:       #475569;
    --simrs-gray-700:       #334155;
    --simrs-gray-800:       #1E293B;
    --simrs-gray-900:       #0F172A;

    /* SIDEBAR */
    --sidebar-bg:           #0B1F2E;
    --sidebar-hover:        #132D41;
    --sidebar-active:       #0B6477;
    --sidebar-text:         #94A3B8;
    --sidebar-text-active:  #FFFFFF;
    --sidebar-width:        260px;
    --sidebar-collapsed:    68px;

    /* TOPBAR */
    --topbar-bg:            #FFFFFF;
    --topbar-border:        #E2E8F0;
    --topbar-height:        64px;

    /* TYPOGRAPHY */
    --font-primary:         'Plus Jakarta Sans', sans-serif;
    --font-mono:            'JetBrains Mono', monospace;

    /* SPACING */
    --content-padding:      1.5rem;
    --card-padding:         1.25rem;
    --border-radius:        0.5rem;
    --border-radius-lg:     0.75rem;
    --border-radius-xl:     1rem;
    --border-radius-pill:   100px;

    /* SHADOWS */
    --shadow-sm:    0 1px 2px rgba(0,0,0,0.05);
    --shadow-md:    0 4px 12px rgba(11,100,119,0.08), 0 1px 3px rgba(0,0,0,0.06);
    --shadow-lg:    0 10px 30px rgba(11,100,119,0.12), 0 2px 8px rgba(0,0,0,0.08);
    --shadow-card:  0 2px 8px rgba(30,41,59,0.07), 0 0 1px rgba(30,41,59,0.08);

    /* TRANSITIONS */
    --transition-fast:  0.15s ease;
    --transition-base:  0.25s ease;
    --transition-slow:  0.4s ease;
}
```

---

## 3. TIPOGRAFI

```html
<!-- Google Fonts di <head> -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
```

```css
body {
    font-family: var(--font-primary);
    font-size: 0.875rem;
    color: var(--simrs-gray-700);
    line-height: 1.6;
    background-color: var(--simrs-gray-50);
    -webkit-font-smoothing: antialiased;
}

.page-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--simrs-gray-900);
    letter-spacing: -0.02em;
}

.section-label {
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.09em;
    text-transform: uppercase;
    color: var(--simrs-gray-400);
}

.text-mono {
    font-family: var(--font-mono);
    font-size: 0.82em;
    letter-spacing: 0.03em;
}
```

---

## 4. LAYOUT UTAMA (app.blade.php)

```html
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIMRS') — RS {{ config('app.hospital_name') }}</title>

    <!-- CDN Dependencies -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

    <style>
    /* ── Paste semua CSS Variables dari Bagian 2 di sini ── */

    /* LAYOUT STRUCTURE */
    .simrs-sidebar {
        position: fixed; top: 0; left: 0;
        height: 100vh; width: var(--sidebar-width);
        background: var(--sidebar-bg);
        display: flex; flex-direction: column;
        overflow: hidden;
        transition: width var(--transition-base);
        z-index: 1040;
        box-shadow: 4px 0 20px rgba(0,0,0,0.15);
    }
    .simrs-sidebar.collapsed { width: var(--sidebar-collapsed); }
    .simrs-sidebar.collapsed .brand-text,
    .simrs-sidebar.collapsed .menu-item span:not(.menu-badge),
    .simrs-sidebar.collapsed .menu-section-label,
    .simrs-sidebar.collapsed .sidebar-search,
    .simrs-sidebar.collapsed .sidebar-footer .user-details { display: none; }

    .main-wrapper {
        margin-left: var(--sidebar-width);
        transition: margin-left var(--transition-base);
        min-height: 100vh;
        display: flex; flex-direction: column;
    }
    .main-wrapper.sidebar-collapsed { margin-left: var(--sidebar-collapsed); }

    .simrs-topbar {
        position: sticky; top: 0; z-index: 1030;
        height: var(--topbar-height);
        background: var(--topbar-bg);
        border-bottom: 1px solid var(--topbar-border);
        display: flex; align-items: center;
        justify-content: space-between;
        padding: 0 1.25rem;
        box-shadow: var(--shadow-sm);
    }

    .simrs-content {
        flex: 1;
        padding: var(--content-padding);
        max-width: 1600px;
    }

    .simrs-footer {
        padding: 0.75rem 1.5rem;
        background: var(--simrs-white);
        border-top: 1px solid var(--simrs-gray-200);
        display: flex; justify-content: space-between;
        font-size: 0.75rem; color: var(--simrs-gray-500);
    }

    /* SIDEBAR BRAND */
    .sidebar-brand {
        display: flex; align-items: center;
        padding: 1.25rem 1rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
        gap: 0.75rem; min-height: 72px;
        text-decoration: none;
    }
    .brand-icon {
        width: 38px; height: 38px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary));
        border-radius: var(--border-radius);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 1.1rem;
        box-shadow: 0 2px 8px rgba(11,100,119,0.4);
    }
    .brand-name {
        display: block; font-size: 1.1rem; font-weight: 800;
        color: var(--simrs-white); letter-spacing: 0.02em;
        line-height: 1.2;
    }
    .brand-sub {
        display: block; font-size: 0.7rem;
        color: var(--sidebar-text); line-height: 1;
        white-space: nowrap; overflow: hidden;
        text-overflow: ellipsis; max-width: 160px;
    }

    /* SIDEBAR SEARCH */
    .sidebar-search {
        padding: 0.75rem 1rem;
        display: flex; align-items: center; gap: 0.5rem;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-search i { color: var(--simrs-gray-500); font-size: 0.8rem; }
    .sidebar-search input {
        background: transparent; border: none; outline: none;
        color: var(--simrs-gray-300); font-size: 0.8rem;
        width: 100%; font-family: var(--font-primary);
    }
    .sidebar-search input::placeholder { color: var(--simrs-gray-600); }

    /* SIDEBAR MENU */
    .sidebar-menu {
        flex: 1; overflow-y: auto; overflow-x: hidden;
        padding: 0.5rem 0;
    }
    .sidebar-menu::-webkit-scrollbar { width: 3px; }
    .sidebar-menu::-webkit-scrollbar-track { background: transparent; }
    .sidebar-menu::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

    .menu-section-label {
        font-size: 0.65rem; font-weight: 700;
        letter-spacing: 0.1em; text-transform: uppercase;
        color: var(--simrs-gray-600);
        padding: 1rem 1.1rem 0.35rem;
    }

    .menu-item {
        display: flex; align-items: center;
        padding: 0.6rem 1rem; gap: 0.75rem;
        color: var(--sidebar-text);
        text-decoration: none;
        font-size: 0.85rem; font-weight: 500;
        border-radius: 0.375rem;
        margin: 0.1rem 0.5rem;
        transition: all var(--transition-fast);
        position: relative; white-space: nowrap;
    }
    .menu-item i { font-size: 0.95rem; width: 18px; text-align: center; flex-shrink: 0; }
    .menu-item:hover {
        background: var(--sidebar-hover);
        color: var(--simrs-white);
    }
    .menu-item.active {
        background: var(--sidebar-active);
        color: var(--simrs-white);
        box-shadow: 0 2px 8px rgba(11,100,119,0.35);
    }
    .menu-item.active i { color: rgba(255,255,255,0.9); }
    .menu-badge {
        margin-left: auto; flex-shrink: 0;
        background: var(--simrs-danger);
        color: white; font-size: 0.65rem; font-weight: 700;
        padding: 0.15rem 0.45rem;
        border-radius: var(--border-radius-pill);
        min-width: 20px; text-align: center;
        animation: pulse-badge 2s infinite;
    }
    @keyframes pulse-badge {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.1); }
    }

    /* SIDEBAR FOOTER */
    .sidebar-footer {
        padding: 0.75rem 1rem;
        border-top: 1px solid rgba(255,255,255,0.06);
    }
    .sidebar-user-info { display: flex; align-items: center; gap: 0.65rem; }
    .user-avatar-sm {
        width: 34px; height: 34px; flex-shrink: 0;
        background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-secondary));
        border-radius: var(--border-radius);
        display: flex; align-items: center; justify-content: center;
        color: white; font-size: 0.75rem; font-weight: 700;
    }
    .user-name-small { font-size: 0.8rem; font-weight: 600; color: var(--simrs-gray-200); line-height: 1.2; }
    .user-role-small { font-size: 0.7rem; color: var(--sidebar-text); }

    /* TOPBAR ELEMENTS */
    .topbar-left, .topbar-right { display: flex; align-items: center; gap: 0.25rem; }

    .sidebar-toggle {
        width: 36px; height: 36px;
        background: transparent; border: none;
        color: var(--simrs-gray-500);
        border-radius: var(--border-radius);
        cursor: pointer;
        transition: all var(--transition-fast);
        display: flex; align-items: center; justify-content: center;
    }
    .sidebar-toggle:hover { background: var(--simrs-gray-100); color: var(--simrs-gray-800); }

    .topbar-clock {
        font-size: 0.8rem; color: var(--simrs-gray-600);
        align-items: center; font-family: var(--font-mono);
    }

    .topbar-icon-btn {
        width: 36px; height: 36px;
        background: transparent; border: none;
        color: var(--simrs-gray-500);
        border-radius: var(--border-radius);
        cursor: pointer; transition: all var(--transition-fast);
        display: flex; align-items: center; justify-content: center;
    }
    .topbar-icon-btn:hover { background: var(--simrs-gray-100); color: var(--simrs-gray-800); }

    .notif-badge {
        position: absolute; top: 4px; right: 4px;
        background: var(--simrs-danger);
        color: white; font-size: 0.6rem;
        width: 16px; height: 16px;
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        font-weight: 700;
    }

    .topbar-user-btn {
        display: flex; align-items: center; gap: 0.5rem;
        background: transparent; border: 1px solid var(--simrs-gray-200);
        border-radius: var(--border-radius);
        padding: 0.35rem 0.65rem; cursor: pointer;
        transition: all var(--transition-fast);
    }
    .topbar-user-btn:hover { background: var(--simrs-gray-100); }

    .user-avatar {
        width: 30px; height: 30px;
        background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary));
        border-radius: 6px;
        color: white; font-size: 0.7rem; font-weight: 700;
        display: flex; align-items: center; justify-content: center;
    }
    .user-name { font-size: 0.8rem; font-weight: 600; color: var(--simrs-gray-800); line-height: 1.2; }
    .user-role { font-size: 0.68rem; color: var(--simrs-gray-500); }

    /* BREADCRUMB */
    .breadcrumb-item + .breadcrumb-item::before { color: var(--simrs-gray-400); }
    .breadcrumb-item a { color: var(--simrs-primary); text-decoration: none; font-size: 0.8rem; }
    .breadcrumb-item.active { color: var(--simrs-gray-600); font-size: 0.8rem; }

    /* PAGE HEADER BAR */
    .page-header-bar {
        display: flex; align-items: flex-start;
        justify-content: space-between;
        margin-bottom: 1.5rem;
        gap: 1rem;
    }
    .page-subtitle { font-size: 0.82rem; }

    /* NOTIFICATION DROPDOWN */
    .notif-dropdown { width: 340px; padding: 0; border: 1px solid var(--simrs-gray-200); box-shadow: var(--shadow-lg); }
    .notif-header {
        display: flex; justify-content: space-between;
        align-items: center; padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--simrs-gray-200);
        font-size: 0.85rem;
    }
    .notif-body { max-height: 360px; overflow-y: auto; }
    .notif-item {
        display: flex; gap: 0.75rem;
        padding: 0.75rem 1rem;
        border-bottom: 1px solid var(--simrs-gray-100);
        transition: background var(--transition-fast);
        cursor: pointer;
    }
    .notif-item:hover { background: var(--simrs-gray-50); }
    .notif-item.unread { background: var(--simrs-primary-pale); }
    .notif-icon {
        width: 32px; height: 32px; border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; flex-shrink: 0;
    }
    .notif-text { font-size: 0.8rem; color: var(--simrs-gray-700); line-height: 1.4; }
    .notif-time { font-size: 0.7rem; color: var(--simrs-gray-400); margin-top: 0.2rem; }
    </style>

    @yield('styles')
</head>
<body>

<!-- SIDEBAR -->
<nav class="simrs-sidebar" id="sidebar">
    <a href="{{ route('dashboard') }}" class="sidebar-brand">
        <div class="brand-icon"><i class="fa-solid fa-hospital-user"></i></div>
        <div class="brand-text">
            <span class="brand-name">SIMRS</span>
            <span class="brand-sub">{{ config('app.hospital_name', 'Rumah Sakit') }}</span>
        </div>
    </a>

    <div class="sidebar-search">
        <i class="fa-solid fa-magnifying-glass"></i>
        <input type="text" placeholder="Cari menu..." id="sidebarSearch">
    </div>

    <div class="sidebar-menu" id="sidebarMenu">
        <div class="menu-section-label">UTAMA</div>
        <a href="{{ route('dashboard') }}" class="menu-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="fa-solid fa-gauge-high"></i><span>Dashboard</span>
        </a>

        <div class="menu-section-label">PELAYANAN</div>
        <a href="{{ route('pendaftaran.kunjungan.create') }}" class="menu-item {{ request()->routeIs('pendaftaran.*') ? 'active' : '' }}">
            <i class="fa-solid fa-clipboard-list"></i><span>Pendaftaran</span>
        </a>
        <a href="{{ route('keperawatan.antrian') }}" class="menu-item {{ request()->routeIs('keperawatan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-heart-pulse"></i><span>Asuhan Keperawatan</span>
        </a>
        <a href="{{ route('rekam-medis.antrian') }}" class="menu-item {{ request()->routeIs('rekam-medis.*') ? 'active' : '' }}">
            <i class="fa-solid fa-notes-medical"></i><span>Rekam Medis</span>
        </a>

        <div class="menu-section-label">PENUNJANG</div>
        <a href="{{ route('farmasi.antrian-resep') }}" class="menu-item {{ request()->routeIs('farmasi.*') ? 'active' : '' }}">
            <i class="fa-solid fa-pills"></i><span>Farmasi</span>
        </a>
        <a href="{{ route('lab.antrian') }}" class="menu-item {{ request()->routeIs('lab.*') ? 'active' : '' }}">
            <i class="fa-solid fa-flask-vial"></i><span>Laboratorium</span>
        </a>
        <a href="{{ route('rad.antrian') }}" class="menu-item {{ request()->routeIs('rad.*') ? 'active' : '' }}">
            <i class="fa-solid fa-x-ray"></i><span>Radiologi</span>
        </a>

        <div class="menu-section-label">KEUANGAN</div>
        <a href="{{ route('keuangan.antrian-kasir') }}" class="menu-item {{ request()->routeIs('keuangan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-cash-register"></i><span>Kasir & Billing</span>
        </a>
        <a href="{{ route('casemix.index') }}" class="menu-item {{ request()->routeIs('casemix.*') ? 'active' : '' }}">
            <i class="fa-solid fa-file-invoice-dollar"></i><span>Casemix & BPJS</span>
        </a>

        <div class="menu-section-label">ANALITIK</div>
        <a href="{{ route('laporan.kunjungan') }}" class="menu-item {{ request()->routeIs('laporan.*') ? 'active' : '' }}">
            <i class="fa-solid fa-chart-line"></i><span>Laporan</span>
        </a>

        <div class="menu-section-label">SISTEM</div>
        <a href="{{ route('admin.users.index') }}" class="menu-item {{ request()->routeIs('admin.*') ? 'active' : '' }}">
            <i class="fa-solid fa-users-gear"></i><span>Manajemen User</span>
        </a>
        <a href="{{ route('admin.audit.index') }}" class="menu-item {{ request()->routeIs('admin.audit.*') ? 'active' : '' }}">
            <i class="fa-solid fa-shield-halved"></i><span>Audit Trail</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-user-info">
            <div class="user-avatar-sm">{{ strtoupper(substr(auth('staff')->user()->nama_lengkap, 0, 2)) }}</div>
            <div class="user-details">
                <div class="user-name-small">{{ Str::limit(auth('staff')->user()->nama_lengkap, 22) }}</div>
                <div class="user-role-small">{{ auth('staff')->user()->roles->first()?->nama_peran }}</div>
            </div>
        </div>
    </div>
</nav>

<!-- MAIN WRAPPER -->
<div class="main-wrapper" id="mainWrapper">

    <!-- TOPBAR -->
    <header class="simrs-topbar">
        <div class="topbar-left">
            <button class="sidebar-toggle" id="sidebarToggle"><i class="fa-solid fa-bars"></i></button>
            <nav aria-label="breadcrumb" class="ms-2">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="fa-solid fa-house fa-xs"></i></a></li>
                    @yield('breadcrumb')
                </ol>
            </nav>
        </div>
        <div class="topbar-right">
            <div class="topbar-clock d-none d-md-flex">
                <i class="fa-regular fa-clock me-1"></i>
                <span id="realtimeClock">--:--:--</span>
            </div>
            <div class="dropdown ms-2">
                <button class="topbar-icon-btn position-relative" data-bs-toggle="dropdown">
                    <i class="fa-solid fa-bell"></i>
                    <span class="notif-badge">3</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end notif-dropdown">
                    <div class="notif-header">
                        <span class="fw-semibold">Notifikasi</span>
                        <a href="#" class="small" style="color:var(--simrs-primary)">Tandai semua dibaca</a>
                    </div>
                    <div class="notif-body">
                        <div class="notif-item unread">
                            <div class="notif-icon" style="background:var(--simrs-danger-pale);color:var(--simrs-danger)"><i class="fa-solid fa-flask-vial"></i></div>
                            <div>
                                <div class="notif-text"><strong>Nilai Kritis Lab</strong> — Pasien Budi (RM-00012345): Hemoglobin 5.2 g/dL</div>
                                <div class="notif-time">2 menit lalu</div>
                            </div>
                        </div>
                        <div class="notif-item unread">
                            <div class="notif-icon" style="background:var(--simrs-warning-pale);color:var(--simrs-warning)"><i class="fa-solid fa-pills"></i></div>
                            <div>
                                <div class="notif-text"><strong>Stok Rendah</strong> — Amoxicillin 500mg tersisa 8 strip</div>
                                <div class="notif-time">15 menit lalu</div>
                            </div>
                        </div>
                        <div class="notif-item">
                            <div class="notif-icon" style="background:var(--simrs-info-pale);color:var(--simrs-info)"><i class="fa-solid fa-file-invoice-dollar"></i></div>
                            <div>
                                <div class="notif-text"><strong>Klaim Disetujui</strong> — INV-20250601-00028 diverifikasi BPJS</div>
                                <div class="notif-time">1 jam lalu</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="dropdown ms-2">
                <button class="topbar-user-btn" data-bs-toggle="dropdown">
                    <div class="user-avatar">{{ strtoupper(substr(auth('staff')->user()->nama_lengkap, 0, 2)) }}</div>
                    <div class="user-info d-none d-md-block">
                        <div class="user-name">{{ Str::limit(auth('staff')->user()->nama_lengkap, 18) }}</div>
                        <div class="user-role">{{ auth('staff')->user()->roles->first()?->nama_peran }}</div>
                    </div>
                    <i class="fa-solid fa-chevron-down ms-1 small text-muted"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end" style="min-width:220px">
                    <li>
                        <div class="px-3 py-2">
                            <div class="fw-semibold small">{{ auth('staff')->user()->nama_lengkap }}</div>
                            <div class="text-muted" style="font-size:0.75rem">NIP: {{ auth('staff')->user()->nip }}</div>
                        </div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item small" href="#"><i class="fa-regular fa-user me-2 text-muted"></i>Profil Saya</a></li>
                    <li><a class="dropdown-item small" href="#"><i class="fa-solid fa-key me-2 text-muted"></i>Ubah Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" id="logoutForm">@csrf
                            <button type="button" class="dropdown-item small text-danger" onclick="confirmLogout()">
                                <i class="fa-solid fa-right-from-bracket me-2"></i>Keluar
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </header>

    <!-- CONTENT -->
    <main class="simrs-content">
        <div class="page-header-bar">
            <div>
                <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                <p class="text-muted mb-0" style="font-size:0.82rem">@yield('page-subtitle', '')</p>
            </div>
            <div class="d-flex align-items-center gap-2">@yield('page-actions')</div>
        </div>

        @yield('content')
    </main>

    <footer class="simrs-footer">
        <span>© {{ date('Y') }} SIMRS Terintegrasi</span>
        <span class="text-muted">Laravel 12 | v1.0.0</span>
    </footer>
</div>

<!-- CDN Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

<script>
const SimrsAlert = {
    success: (msg, title = 'Berhasil!') => Swal.fire({
        icon: 'success', title, text: msg,
        confirmButtonColor: '#0B6477',
        timer: 3500, timerProgressBar: true,
        customClass: { confirmButton: 'btn btn-sm px-4' }
    }),
    error: (msg, title = 'Terjadi Kesalahan') => Swal.fire({
        icon: 'error', title, html: msg,
        confirmButtonColor: '#C5372C',
        confirmButtonText: 'Tutup'
    }),
    warning: (msg, title = 'Perhatian') => Swal.fire({
        icon: 'warning', title, text: msg,
        confirmButtonColor: '#C78A12'
    }),
    confirmDelete: (url, itemName = 'data ini') => Swal.fire({
        title: 'Hapus Data?',
        html: `Anda akan menghapus <strong>${itemName}</strong>.<br>Tindakan ini <u>tidak dapat dibatalkan</u>.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#C5372C', cancelButtonColor: '#94A3B8',
        confirmButtonText: '<i class="fa-solid fa-trash me-1"></i> Ya, Hapus!',
        cancelButtonText: 'Batal', reverseButtons: true, focusCancel: true,
    }).then(r => {
        if (r.isConfirmed) {
            const f = document.createElement('form');
            f.method = 'POST'; f.action = url;
            f.innerHTML = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="DELETE">`;
            document.body.appendChild(f); f.submit();
        }
    }),
    confirmAction: (url, text, method = 'POST', extra = {}) => Swal.fire({
        title: 'Konfirmasi', text, icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0B6477', cancelButtonColor: '#94A3B8',
        confirmButtonText: 'Ya, Lanjutkan', cancelButtonText: 'Batal', reverseButtons: true,
    }).then(r => {
        if (r.isConfirmed) {
            const f = document.createElement('form');
            f.method = 'POST'; f.action = url;
            let html = `<input type="hidden" name="_token" value="{{ csrf_token() }}"><input type="hidden" name="_method" value="${method}">`;
            Object.entries(extra).forEach(([k,v]) => html += `<input type="hidden" name="${k}" value="${v}">`);
            f.innerHTML = html; document.body.appendChild(f); f.submit();
        }
    }),
    inaCBGWarning: (data) => {
        const color = data.persen >= 95 ? '#C5372C' : '#C78A12';
        Swal.fire({
            title: data.persen >= 95 ? '🚨 KRITIS: Ceiling INA-CBG' : '⚡ Peringatan INA-CBG',
            html: `<div class="text-start small">
                <p>${data.pesan}</p><hr>
                <div class="row g-2 text-center mb-2">
                    <div class="col-4"><div class="text-muted">Biaya Riil</div><div class="fw-bold">Rp ${Number(data.total_riil).toLocaleString('id-ID')}</div></div>
                    <div class="col-4"><div class="text-muted">Ceiling</div><div class="fw-bold">Rp ${Number(data.tarif_ina_cbg).toLocaleString('id-ID')}</div></div>
                    <div class="col-4"><div class="text-muted">Utilisasi</div><div class="fw-bold" style="color:${color}">${data.persen}%</div></div>
                </div>
                <div class="progress" style="height:8px"><div class="progress-bar" style="width:${Math.min(data.persen,100)}%;background:${color}"></div></div>
            </div>`,
            icon: data.persen >= 95 ? 'error' : 'warning',
            confirmButtonColor: '#0B6477', confirmButtonText: 'Perbarui Rencana',
            showCancelButton: true, cancelButtonText: 'Abaikan',
        });
    },
};

@if(session('swal_success')) SimrsAlert.success('{{ session("swal_success") }}'); @endif
@if(session('swal_error'))   SimrsAlert.error('{{ session("swal_error") }}');     @endif
@if(session('swal_warning')) SimrsAlert.warning('{{ session("swal_warning") }}'); @endif

function updateClock() {
    const n = new Date();
    const el = document.getElementById('realtimeClock');
    if (el) el.textContent = n.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
}
setInterval(updateClock, 1000); updateClock();

document.getElementById('sidebarToggle').addEventListener('click', () => {
    document.getElementById('sidebar').classList.toggle('collapsed');
    document.getElementById('mainWrapper').classList.toggle('sidebar-collapsed');
});

function confirmLogout() {
    Swal.fire({
        title: 'Keluar dari Sistem?', text: 'Sesi Anda akan diakhiri.', icon: 'question',
        showCancelButton: true, confirmButtonColor: '#C5372C', cancelButtonColor: '#94A3B8',
        confirmButtonText: '<i class="fa-solid fa-right-from-bracket me-1"></i> Ya, Keluar',
        cancelButtonText: 'Batal', reverseButtons: true,
    }).then(r => { if (r.isConfirmed) document.getElementById('logoutForm').submit(); });
}
</script>

@yield('scripts')
</body>
</html>
```

---

## 5. HALAMAN LOGIN

```html
<!-- resources/views/auth/login.blade.php -->
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — SIMRS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    <style>
    /* CSS Variables (paste dari Bagian 2) */

    body {
        font-family: 'Plus Jakarta Sans', sans-serif;
        background: var(--simrs-gray-100);
        min-height: 100vh;
        display: flex; align-items: stretch;
    }

    .login-left {
        width: 45%;
        background: linear-gradient(135deg, #0B1F2E 0%, #0B6477 60%, #14919B 100%);
        display: flex; flex-direction: column;
        justify-content: center; align-items: center;
        padding: 3rem; position: relative; overflow: hidden;
    }

    /* Dekoratif lingkaran di belakang */
    .login-left::before {
        content: '';
        position: absolute; width: 500px; height: 500px;
        border-radius: 50%; opacity: 0.07;
        background: white;
        top: -150px; right: -100px;
    }
    .login-left::after {
        content: '';
        position: absolute; width: 300px; height: 300px;
        border-radius: 50%; opacity: 0.05;
        background: white;
        bottom: -100px; left: -80px;
    }

    .login-brand { text-align: center; position: relative; z-index: 1; }
    .login-logo {
        width: 80px; height: 80px;
        background: rgba(255,255,255,0.15);
        border-radius: 20px;
        display: flex; align-items: center; justify-content: center;
        font-size: 2.5rem; color: white;
        margin: 0 auto 1.5rem;
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255,255,255,0.2);
    }
    .login-brand h1 {
        font-size: 2.5rem; font-weight: 800;
        color: white; letter-spacing: -0.02em; margin-bottom: 0.25rem;
    }
    .login-brand p { color: rgba(255,255,255,0.7); font-size: 0.9rem; }

    .login-features { margin-top: 3rem; position: relative; z-index: 1; }
    .login-feature-item {
        display: flex; align-items: center; gap: 0.75rem;
        color: rgba(255,255,255,0.8); font-size: 0.85rem;
        margin-bottom: 0.75rem;
    }
    .login-feature-icon {
        width: 32px; height: 32px;
        background: rgba(255,255,255,0.12);
        border-radius: 8px;
        display: flex; align-items: center; justify-content: center;
        font-size: 0.85rem; flex-shrink: 0;
    }

    .login-right {
        flex: 1;
        display: flex; align-items: center; justify-content: center;
        padding: 2rem;
        background: var(--simrs-gray-50);
    }

    .login-card {
        background: white;
        border-radius: 16px;
        padding: 2.5rem;
        width: 100%; max-width: 420px;
        box-shadow: 0 8px 40px rgba(11,100,119,0.1), 0 1px 3px rgba(0,0,0,0.05);
    }

    .login-card h2 {
        font-size: 1.5rem; font-weight: 700;
        color: var(--simrs-gray-900); margin-bottom: 0.35rem;
    }
    .login-card p { font-size: 0.85rem; color: var(--simrs-gray-500); }

    .form-label-custom {
        font-size: 0.8rem; font-weight: 600;
        color: var(--simrs-gray-600); margin-bottom: 0.4rem;
    }

    .input-group-custom {
        position: relative;
    }
    .input-group-custom .input-icon {
        position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%);
        color: var(--simrs-gray-400); z-index: 2; font-size: 0.85rem;
    }
    .input-group-custom .form-control {
        padding-left: 2.5rem;
        border: 1.5px solid var(--simrs-gray-200);
        border-radius: var(--border-radius);
        font-size: 0.875rem;
        transition: all var(--transition-fast);
        height: 44px;
    }
    .input-group-custom .form-control:focus {
        border-color: var(--simrs-primary);
        box-shadow: 0 0 0 3px rgba(11,100,119,0.1);
    }
    .input-group-custom .toggle-password {
        position: absolute; right: 0.9rem; top: 50%; transform: translateY(-50%);
        background: none; border: none; cursor: pointer;
        color: var(--simrs-gray-400); z-index: 2;
    }

    .btn-login {
        height: 46px; font-size: 0.9rem; font-weight: 600;
        background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary));
        border: none; border-radius: var(--border-radius);
        color: white; width: 100%;
        transition: all var(--transition-base);
        box-shadow: 0 4px 14px rgba(11,100,119,0.3);
    }
    .btn-login:hover {
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(11,100,119,0.4);
        background: linear-gradient(135deg, #16a2ad, var(--simrs-primary-light));
    }
    .btn-login:active { transform: translateY(0); }

    .hospital-badge {
        display: inline-flex; align-items: center; gap: 0.5rem;
        background: var(--simrs-primary-pale);
        color: var(--simrs-primary-dark);
        font-size: 0.75rem; font-weight: 600;
        padding: 0.3rem 0.8rem; border-radius: var(--border-radius-pill);
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .login-left { display: none; }
        .login-right { padding: 1.5rem; }
    }
    </style>
</head>
<body>
    <div class="login-left">
        <div class="login-brand">
            <div class="login-logo"><i class="fa-solid fa-hospital-user"></i></div>
            <h1>SIMRS</h1>
            <p>Sistem Informasi Manajemen<br>Rumah Sakit Terintegrasi</p>
        </div>
        <div class="login-features">
            <div class="login-feature-item">
                <div class="login-feature-icon"><i class="fa-solid fa-shield-halved"></i></div>
                <span>Keamanan Data Berlapis & Audit Trail</span>
            </div>
            <div class="login-feature-item">
                <div class="login-feature-icon"><i class="fa-solid fa-network-wired"></i></div>
                <span>Integrasi BPJS VClaim & SATUSEHAT</span>
            </div>
            <div class="login-feature-item">
                <div class="login-feature-icon"><i class="fa-solid fa-chart-line"></i></div>
                <span>Analitik Real-time & Laporan INA-CBG</span>
            </div>
            <div class="login-feature-item">
                <div class="login-feature-icon"><i class="fa-solid fa-users-gear"></i></div>
                <span>RBAC Multi-tingkat Sesuai PMK 82/2013</span>
            </div>
        </div>
    </div>

    <div class="login-right">
        <div class="login-card">
            <div class="hospital-badge">
                <i class="fa-solid fa-hospital"></i>
                {{ config('app.hospital_name', 'Rumah Sakit Umum') }}
            </div>
            <h2>Selamat Datang</h2>
            <p class="mb-4">Masuk ke akun staf Anda untuk melanjutkan</p>

            @if($errors->any())
            <div class="alert alert-danger d-flex align-items-center gap-2 py-2 mb-3" style="font-size:0.82rem">
                <i class="fa-solid fa-circle-xmark"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            <form method="POST" action="{{ route('login.post') }}">
                @csrf
                <div class="mb-3">
                    <label class="form-label-custom">NIP / Email</label>
                    <div class="input-group-custom">
                        <i class="input-icon fa-solid fa-id-badge"></i>
                        <input type="text" name="login" class="form-control"
                               placeholder="Masukkan NIP atau email"
                               value="{{ old('login') }}" required autofocus>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-custom">Kata Sandi</label>
                    <div class="input-group-custom">
                        <i class="input-icon fa-solid fa-lock"></i>
                        <input type="password" name="password" id="passwordInput"
                               class="form-control" placeholder="Masukkan kata sandi" required>
                        <button type="button" class="toggle-password" onclick="togglePassword()">
                            <i class="fa-regular fa-eye" id="eyeIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="remember" id="rememberMe">
                        <label class="form-check-label" style="font-size:0.8rem" for="rememberMe">Ingat saya</label>
                    </div>
                    <a href="#" style="font-size:0.8rem; color:var(--simrs-primary); text-decoration:none">Lupa kata sandi?</a>
                </div>

                <button type="submit" class="btn-login">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>Masuk ke Sistem
                </button>
            </form>

            <div class="mt-4 pt-3 border-top text-center" style="font-size:0.75rem; color:var(--simrs-gray-400)">
                <i class="fa-solid fa-shield-halved me-1"></i>
                Sesi dilindungi enkripsi SSL. Semua aktivitas dicatat.
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    function togglePassword() {
        const input = document.getElementById('passwordInput');
        const icon = document.getElementById('eyeIcon');
        if (input.type === 'password') {
            input.type = 'text'; icon.className = 'fa-regular fa-eye-slash';
        } else {
            input.type = 'password'; icon.className = 'fa-regular fa-eye';
        }
    }

    @if(session('swal_warning'))
    Swal.fire({ icon: 'warning', title: 'Perhatian', text: '{{ session("swal_warning") }}', confirmButtonColor: '#0B6477' });
    @endif
    </script>
</body>
</html>
```

---

## 6. KOMPONEN KARTU KPI DASHBOARD

```html
<!-- Contoh penggunaan di halaman dashboard -->

<!-- KPI Card — Kunjungan Hari Ini -->
<div class="col-xl-3 col-md-6">
    <div class="kpi-card kpi-primary">
        <div class="kpi-icon"><i class="fa-solid fa-clipboard-list"></i></div>
        <div class="kpi-body">
            <div class="kpi-label">Kunjungan Hari Ini</div>
            <div class="kpi-value">248</div>
            <div class="kpi-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span>+12% vs kemarin</span>
            </div>
        </div>
    </div>
</div>

<!-- KPI Card — Antrian Aktif (dengan pulse) -->
<div class="col-xl-3 col-md-6">
    <div class="kpi-card kpi-warning">
        <div class="kpi-icon"><i class="fa-solid fa-hourglass-half"></i></div>
        <div class="kpi-body">
            <div class="kpi-label">Antrian Menunggu</div>
            <div class="kpi-value">
                34
                <span class="live-indicator"><span class="live-dot"></span>LIVE</span>
            </div>
            <div class="kpi-trend">
                <span class="text-muted small">Terlama: 47 mnt · Rata-rata: 22 mnt</span>
            </div>
        </div>
    </div>
</div>

<!-- KPI Card — Pendapatan Hari Ini -->
<div class="col-xl-3 col-md-6">
    <div class="kpi-card kpi-success">
        <div class="kpi-icon"><i class="fa-solid fa-sack-dollar"></i></div>
        <div class="kpi-body">
            <div class="kpi-label">Pendapatan Hari Ini</div>
            <div class="kpi-value">Rp 47,2 Jt</div>
            <div class="kpi-trend trend-up">
                <i class="fa-solid fa-arrow-trend-up"></i>
                <span>+8,3% vs kemarin</span>
            </div>
        </div>
    </div>
</div>

<!-- KPI Card — Stok Kritis -->
<div class="col-xl-3 col-md-6">
    <div class="kpi-card kpi-danger">
        <div class="kpi-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div class="kpi-body">
            <div class="kpi-label">Obat Stok Kritis</div>
            <div class="kpi-value">7</div>
            <div class="kpi-trend trend-down">
                <i class="fa-solid fa-arrow-trend-down"></i>
                <span>Perlu restock segera</span>
            </div>
        </div>
    </div>
</div>
```

```css
/* KPI CARDS */
.kpi-card {
    background: white;
    border-radius: var(--border-radius-lg);
    padding: 1.25rem;
    display: flex; align-items: flex-start; gap: 1rem;
    box-shadow: var(--shadow-card);
    border: 1px solid var(--simrs-gray-100);
    transition: all var(--transition-base);
    height: 100%;
}
.kpi-card:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-md);
}

.kpi-icon {
    width: 48px; height: 48px; flex-shrink: 0;
    border-radius: var(--border-radius);
    display: flex; align-items: center; justify-content: center;
    font-size: 1.25rem;
}
.kpi-primary .kpi-icon { background: var(--simrs-primary-pale); color: var(--simrs-primary); }
.kpi-success .kpi-icon { background: var(--simrs-success-pale); color: var(--simrs-success); }
.kpi-warning .kpi-icon { background: var(--simrs-warning-pale); color: var(--simrs-warning); }
.kpi-danger  .kpi-icon { background: var(--simrs-danger-pale);  color: var(--simrs-danger);  }
.kpi-info    .kpi-icon { background: var(--simrs-info-pale);    color: var(--simrs-info);    }

.kpi-label { font-size: 0.75rem; font-weight: 600; color: var(--simrs-gray-500); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.3rem; }
.kpi-value { font-size: 1.6rem; font-weight: 800; color: var(--simrs-gray-900); line-height: 1.1; margin-bottom: 0.4rem; display: flex; align-items: center; gap: 0.5rem; }
.kpi-trend { font-size: 0.75rem; font-weight: 500; display: flex; align-items: center; gap: 0.3rem; }
.trend-up   { color: var(--simrs-success); }
.trend-down { color: var(--simrs-danger); }

/* Live indicator */
.live-indicator {
    font-size: 0.6rem; font-weight: 700; letter-spacing: 0.05em;
    background: var(--simrs-danger-pale); color: var(--simrs-danger);
    padding: 0.15rem 0.4rem; border-radius: var(--border-radius-pill);
    display: flex; align-items: center; gap: 0.3rem;
}
.live-dot {
    width: 6px; height: 6px; border-radius: 50%;
    background: var(--simrs-danger);
    animation: live-pulse 1.5s infinite;
}
@keyframes live-pulse {
    0%, 100% { opacity: 1; transform: scale(1); }
    50% { opacity: 0.4; transform: scale(0.7); }
}
```

---

## 7. SIMRS CARD COMPONENT

```css
/* BASE CARD */
.simrs-card {
    background: var(--simrs-white);
    border-radius: var(--border-radius-lg);
    box-shadow: var(--shadow-card);
    border: 1px solid var(--simrs-gray-100);
    overflow: hidden;
    transition: box-shadow var(--transition-fast);
}
.simrs-card:hover { box-shadow: var(--shadow-md); }

.simrs-card-header {
    padding: 0.9rem 1.25rem;
    border-bottom: 1px solid var(--simrs-gray-100);
    display: flex; align-items: center;
    justify-content: space-between;
    background: var(--simrs-white);
}
.simrs-card-title {
    font-size: 0.875rem; font-weight: 700;
    color: var(--simrs-gray-800);
    display: flex; align-items: center; gap: 0.5rem;
}
.simrs-card-title i { color: var(--simrs-primary); }
.simrs-card-body { padding: var(--card-padding); }

/* CARD ACCENT BORDER (garis warna atas) */
.simrs-card.border-top-primary { border-top: 3px solid var(--simrs-primary); }
.simrs-card.border-top-success { border-top: 3px solid var(--simrs-success); }
.simrs-card.border-top-danger  { border-top: 3px solid var(--simrs-danger);  }
.simrs-card.border-top-warning { border-top: 3px solid var(--simrs-warning); }
```

---

## 8. TOMBOL & AKSI

```css
/* TOMBOL PRIMARY */
.btn-simrs-primary {
    background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary));
    border: none; color: white;
    font-size: 0.83rem; font-weight: 600;
    padding: 0.5rem 1.1rem;
    border-radius: var(--border-radius);
    transition: all var(--transition-base);
    box-shadow: 0 2px 8px rgba(11,100,119,0.25);
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-simrs-primary:hover {
    background: linear-gradient(135deg, #16a2ad, var(--simrs-primary-light));
    transform: translateY(-1px);
    box-shadow: 0 4px 14px rgba(11,100,119,0.35);
    color: white;
}
.btn-simrs-primary:active { transform: translateY(0); }

/* OUTLINE */
.btn-simrs-outline {
    background: transparent;
    border: 1.5px solid var(--simrs-primary);
    color: var(--simrs-primary);
    font-size: 0.83rem; font-weight: 600;
    padding: 0.5rem 1.1rem;
    border-radius: var(--border-radius);
    transition: all var(--transition-fast);
    display: inline-flex; align-items: center; gap: 0.4rem;
}
.btn-simrs-outline:hover {
    background: var(--simrs-primary-pale);
    color: var(--simrs-primary-dark);
}

/* TOMBOL BAHAYA */
.btn-simrs-danger {
    background: var(--simrs-danger);
    border: none; color: white;
    font-size: 0.83rem; font-weight: 600;
    padding: 0.5rem 1.1rem;
    border-radius: var(--border-radius);
    transition: all var(--transition-base);
}
.btn-simrs-danger:hover { background: var(--simrs-danger-dark); color: white; }

/* TOMBOL AKSI TABEL (icon only) */
.btn-action {
    width: 30px; height: 30px;
    border: none; border-radius: 6px;
    display: inline-flex; align-items: center; justify-content: center;
    font-size: 0.8rem; cursor: pointer;
    transition: all var(--transition-fast);
    text-decoration: none;
}
.btn-action-view    { background: var(--simrs-info-pale); color: var(--simrs-info); }
.btn-action-edit    { background: var(--simrs-warning-pale); color: var(--simrs-warning); }
.btn-action-delete  { background: var(--simrs-danger-pale); color: var(--simrs-danger); }
.btn-action-print   { background: var(--simrs-gray-100); color: var(--simrs-gray-600); }
.btn-action-approve { background: var(--simrs-success-pale); color: var(--simrs-success); }

.btn-action:hover { filter: brightness(0.9); transform: scale(1.08); }
```

---

## 9. BADGE & STATUS

```css
/* BADGE STATUS KUNJUNGAN */
.badge-status {
    font-size: 0.7rem; font-weight: 600;
    padding: 0.25rem 0.6rem;
    border-radius: var(--border-radius-pill);
    display: inline-flex; align-items: center; gap: 0.3rem;
    letter-spacing: 0.02em;
}
.badge-status::before {
    content: ''; width: 6px; height: 6px;
    border-radius: 50%; flex-shrink: 0;
}

.status-menunggu   { background: #FEF7E6; color: #9A6E00; }
.status-menunggu::before { background: #C78A12; }
.status-proses     { background: var(--simrs-info-pale); color: var(--simrs-info-dark); }
.status-proses::before { background: var(--simrs-info); animation: live-pulse 1.5s infinite; }
.status-selesai    { background: var(--simrs-success-pale); color: var(--simrs-success-dark); }
.status-selesai::before { background: var(--simrs-success); }
.status-batal      { background: var(--simrs-gray-100); color: var(--simrs-gray-500); }
.status-batal::before { background: var(--simrs-gray-400); }
.status-kritis     { background: var(--simrs-critical-bg); color: var(--simrs-critical); }
.status-kritis::before { background: var(--simrs-critical); animation: live-pulse 0.8s infinite; }

/* BADGE RISK LEVEL (untuk BPJS utilisasi) */
.risk-aman    { background: var(--simrs-success-pale); color: var(--simrs-success-dark); font-size: 0.7rem; padding: 0.2rem 0.55rem; border-radius: var(--border-radius-pill); font-weight: 700; }
.risk-waspada { background: var(--simrs-warning-pale); color: var(--simrs-warning-dark); font-size: 0.7rem; padding: 0.2rem 0.55rem; border-radius: var(--border-radius-pill); font-weight: 700; }
.risk-kritis  { background: var(--simrs-danger-pale); color: var(--simrs-danger-dark); font-size: 0.7rem; padding: 0.2rem 0.55rem; border-radius: var(--border-radius-pill); font-weight: 700; }

/* BADGE LAB FLAG */
.lab-normal   { color: var(--simrs-success); font-weight: 600; }
.lab-tinggi   { color: var(--simrs-warning); font-weight: 600; }
.lab-rendah   { color: var(--simrs-info); font-weight: 600; }
.lab-kritis   { color: var(--simrs-critical); font-weight: 700; animation: text-blink 1s infinite; }

@keyframes text-blink {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.3; }
}
```

---

## 10. TABEL DATA

```html
<!-- Struktur tabel SIMRS standar -->
<div class="simrs-card">
    <div class="simrs-card-header">
        <div class="simrs-card-title">
            <i class="fa-solid fa-table-list"></i>
            Daftar Kunjungan Hari Ini
        </div>
        <div class="d-flex align-items-center gap-2">
            <div class="search-input-wrapper">
                <i class="fa-solid fa-magnifying-glass search-icon"></i>
                <input type="text" class="form-control form-control-sm search-input" placeholder="Cari pasien...">
            </div>
            <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn-simrs-primary">
                <i class="fa-solid fa-plus"></i> Daftar Baru
            </a>
        </div>
    </div>
    <div class="simrs-card-body p-0">
        <div class="table-responsive">
            <table class="table simrs-table mb-0">
                <thead>
                    <tr>
                        <th>No. Registrasi</th>
                        <th>Pasien</th>
                        <th>Poli Tujuan</th>
                        <th>Dokter DPJP</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Jam Daftar</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($encounters as $enc)
                    <tr>
                        <td><span class="text-mono fw-semibold" style="color:var(--simrs-primary)">{{ $enc->no_registrasi }}</span></td>
                        <td>
                            <div class="patient-cell">
                                <div class="patient-avatar">{{ strtoupper(substr($enc->patient->nama_pasien, 0, 2)) }}</div>
                                <div>
                                    <div class="fw-semibold" style="font-size:0.83rem">{{ $enc->patient->nama_pasien }}</div>
                                    <div class="text-muted text-mono" style="font-size:0.72rem">{{ $enc->patient->no_rkm_medis }}</div>
                                </div>
                            </div>
                        </td>
                        <td><span style="font-size:0.82rem">{{ $enc->department->nama_depart }}</span></td>
                        <td><span style="font-size:0.82rem">dr. {{ $enc->doctor->user->nama_lengkap }}</span></td>
                        <td>
                            @if($enc->jenis_pembayaran === 'bpjs')
                                <span class="badge-payment bpjs">BPJS</span>
                            @elseif($enc->jenis_pembayaran === 'umum')
                                <span class="badge-payment umum">Umum</span>
                            @else
                                <span class="badge-payment asuransi">Asuransi</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge-status status-{{ str_replace('_','-',$enc->status_encounter) }}">
                                {{ ucwords(str_replace('_', ' ', $enc->status_encounter)) }}
                            </span>
                        </td>
                        <td><span class="text-mono" style="font-size:0.8rem">{{ $enc->waktu_masuk->format('H:i') }}</span></td>
                        <td>
                            <div class="d-flex justify-content-center gap-1">
                                <a href="{{ route('rekam-medis.show', $enc->id) }}" class="btn-action btn-action-view" title="Lihat Detail"><i class="fa-solid fa-eye"></i></a>
                                <a href="{{ route('pendaftaran.kunjungan.edit', $enc->id) }}" class="btn-action btn-action-edit" title="Edit"><i class="fa-solid fa-pen"></i></a>
                                <a href="{{ route('keuangan.invoice.show', $enc->id) }}" class="btn-action btn-action-print" title="Invoice"><i class="fa-solid fa-file-invoice"></i></a>
                                <button onclick="SimrsAlert.confirmDelete('{{ route('pendaftaran.kunjungan.destroy', $enc->id) }}', '{{ $enc->no_registrasi }}')"
                                    class="btn-action btn-action-delete" title="Hapus">
                                    <i class="fa-solid fa-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($encounters->hasPages())
        <div class="px-4 py-3 border-top" style="border-color:var(--simrs-gray-100)!important">
            {{ $encounters->links('vendor.pagination.bootstrap-5') }}
        </div>
        @endif
    </div>
</div>
```

```css
/* TABEL SIMRS */
.simrs-table { border-collapse: separate; border-spacing: 0; width: 100%; }
.simrs-table thead tr { background: var(--simrs-gray-50); }
.simrs-table thead th {
    font-size: 0.72rem; font-weight: 700;
    text-transform: uppercase; letter-spacing: 0.05em;
    color: var(--simrs-gray-500);
    padding: 0.7rem 1rem; white-space: nowrap;
    border-bottom: 2px solid var(--simrs-gray-200);
}
.simrs-table tbody tr { transition: background var(--transition-fast); }
.simrs-table tbody tr:hover { background: var(--simrs-gray-50); }
.simrs-table tbody td {
    padding: 0.7rem 1rem;
    border-bottom: 1px solid var(--simrs-gray-100);
    vertical-align: middle;
}

/* Patient cell */
.patient-cell { display: flex; align-items: center; gap: 0.65rem; }
.patient-avatar {
    width: 32px; height: 32px; border-radius: 8px;
    background: linear-gradient(135deg, var(--simrs-primary-pale), var(--simrs-secondary-pale));
    color: var(--simrs-primary-dark);
    display: flex; align-items: center; justify-content: center;
    font-size: 0.7rem; font-weight: 700; flex-shrink: 0;
    border: 1px solid var(--simrs-gray-200);
}

/* Pembayaran badges */
.badge-payment {
    font-size: 0.68rem; font-weight: 700;
    padding: 0.2rem 0.55rem;
    border-radius: var(--border-radius-pill);
}
.badge-payment.bpjs     { background: #E6F0FB; color: #1060A8; }
.badge-payment.umum     { background: var(--simrs-gray-100); color: var(--simrs-gray-600); }
.badge-payment.asuransi { background: var(--simrs-secondary-pale); color: var(--simrs-secondary); }

/* Search input */
.search-input-wrapper { position: relative; }
.search-icon { position: absolute; left: 0.65rem; top: 50%; transform: translateY(-50%); color: var(--simrs-gray-400); font-size: 0.75rem; pointer-events: none; }
.search-input { padding-left: 2rem; font-size: 0.82rem; border: 1.5px solid var(--simrs-gray-200); border-radius: var(--border-radius); width: 200px; }
.search-input:focus { border-color: var(--simrs-primary); box-shadow: 0 0 0 3px rgba(11,100,119,0.1); outline: none; }
```

---

## 11. FORM REKAM MEDIS (Multi-tab)

```html
<!-- Form RME Dokter — contoh struktur tab -->
<div class="simrs-card">
    <div class="simrs-card-header">
        <div class="simrs-card-title"><i class="fa-solid fa-notes-medical"></i> Rekam Medis Elektronik</div>
        <div class="d-flex align-items-center gap-2">
            <!-- Info pasien singkat di header -->
            <div class="patient-info-chip">
                <i class="fa-solid fa-user-injured"></i>
                <strong>{{ $encounter->patient->nama_pasien }}</strong>
                <span class="text-mono text-muted">{{ $encounter->patient->no_rkm_medis }}</span>
                <span class="badge-status status-proses ms-1">Dalam Pemeriksaan</span>
            </div>
        </div>
    </div>

    <!-- Tab Navigation -->
    <div class="rme-tabs">
        <button class="rme-tab active" data-tab="anamnesis">
            <i class="fa-solid fa-comment-medical"></i> Anamnesis
        </button>
        <button class="rme-tab" data-tab="pemeriksaan">
            <i class="fa-solid fa-stethoscope"></i> Pemeriksaan Fisik
        </button>
        <button class="rme-tab" data-tab="diagnosis">
            <i class="fa-solid fa-microscope"></i> Diagnosis & Rencana
        </button>
        <button class="rme-tab" data-tab="resep">
            <i class="fa-solid fa-prescription-bottle-medical"></i> Resep Elektronik
        </button>
        <button class="rme-tab" data-tab="penunjang">
            <i class="fa-solid fa-flask-vial"></i> Order Penunjang
        </button>
    </div>

    <!-- Tab Content -->
    <div class="rme-tab-content" id="tab-anamnesis">
        <div class="row g-3">
            <div class="col-12">
                <label class="form-label-custom">Keluhan Utama <span class="text-danger">*</span></label>
                <textarea name="keluhan_utama" class="form-control simrs-textarea" rows="3"
                    placeholder="Deskripsikan keluhan utama pasien...">{{ old('keluhan_utama', $record->keluhan_utama ?? '') }}</textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Riwayat Penyakit Sekarang</label>
                <textarea name="riwayat_penyakit_sekarang" class="form-control simrs-textarea" rows="4"
                    placeholder="Onset, lokasi, kualitas, kuantitas, faktor yang memperberat/meringankan..."></textarea>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Riwayat Penyakit Dahulu</label>
                <textarea name="riwayat_penyakit_dahulu" class="form-control simrs-textarea" rows="4"
                    placeholder="Riwayat penyakit sebelumnya, operasi, alergi obat..."></textarea>
            </div>
        </div>
    </div>

    <div class="rme-tab-content d-none" id="tab-pemeriksaan">
        <!-- Tanda Vital -->
        <div class="vital-signs-grid">
            <div class="vital-item">
                <div class="vital-icon" style="background:var(--simrs-danger-pale);color:var(--simrs-danger)"><i class="fa-solid fa-heart"></i></div>
                <div>
                    <div class="vital-label">Tekanan Darah</div>
                    <div class="d-flex align-items-center gap-1">
                        <input type="number" name="td_sistolik" class="form-control vital-input" placeholder="120">
                        <span class="text-muted">/</span>
                        <input type="number" name="td_diastolik" class="form-control vital-input" placeholder="80">
                        <span class="vital-unit">mmHg</span>
                    </div>
                </div>
            </div>
            <div class="vital-item">
                <div class="vital-icon" style="background:var(--simrs-warning-pale);color:var(--simrs-warning)"><i class="fa-solid fa-temperature-half"></i></div>
                <div>
                    <div class="vital-label">Suhu Tubuh</div>
                    <div class="d-flex align-items-center gap-1">
                        <input type="number" step="0.1" name="suhu" class="form-control vital-input" placeholder="36.8">
                        <span class="vital-unit">°C</span>
                    </div>
                </div>
            </div>
            <div class="vital-item">
                <div class="vital-icon" style="background:var(--simrs-info-pale);color:var(--simrs-info)"><i class="fa-solid fa-lungs"></i></div>
                <div>
                    <div class="vital-label">SpO₂</div>
                    <div class="d-flex align-items-center gap-1">
                        <input type="number" name="spo2" class="form-control vital-input" placeholder="98">
                        <span class="vital-unit">%</span>
                    </div>
                </div>
            </div>
            <div class="vital-item">
                <div class="vital-icon" style="background:var(--simrs-success-pale);color:var(--simrs-success)"><i class="fa-solid fa-person-walking"></i></div>
                <div>
                    <div class="vital-label">Nadi</div>
                    <div class="d-flex align-items-center gap-1">
                        <input type="number" name="nadi" class="form-control vital-input" placeholder="80">
                        <span class="vital-unit">bpm</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

```css
/* RME TABS */
.rme-tabs {
    display: flex; gap: 0; overflow-x: auto;
    border-bottom: 2px solid var(--simrs-gray-200);
    padding: 0 1.25rem;
    scrollbar-width: none;
}
.rme-tab {
    background: transparent; border: none;
    padding: 0.75rem 1rem;
    font-size: 0.8rem; font-weight: 600;
    color: var(--simrs-gray-500);
    cursor: pointer; white-space: nowrap;
    border-bottom: 2px solid transparent;
    margin-bottom: -2px;
    transition: all var(--transition-fast);
    display: flex; align-items: center; gap: 0.4rem;
}
.rme-tab:hover { color: var(--simrs-primary); }
.rme-tab.active {
    color: var(--simrs-primary);
    border-bottom-color: var(--simrs-primary);
}

.rme-tab-content { padding: 1.25rem; }

/* Vital Signs Grid */
.vital-signs-grid {
    display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 1rem; margin-bottom: 1.5rem;
}
.vital-item {
    background: var(--simrs-gray-50);
    border: 1px solid var(--simrs-gray-200);
    border-radius: var(--border-radius);
    padding: 0.9rem;
    display: flex; align-items: flex-start; gap: 0.75rem;
    transition: border-color var(--transition-fast);
}
.vital-item:focus-within { border-color: var(--simrs-primary); background: var(--simrs-white); }
.vital-icon { width: 36px; height: 36px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 0.9rem; flex-shrink: 0; }
.vital-label { font-size: 0.7rem; font-weight: 700; color: var(--simrs-gray-500); text-transform: uppercase; letter-spacing: 0.04em; margin-bottom: 0.4rem; }
.vital-input { height: 32px; width: 75px; font-size: 0.9rem; font-weight: 600; font-family: var(--font-mono); text-align: center; padding: 0.2rem 0.4rem; border: 1.5px solid var(--simrs-gray-200); border-radius: 6px; }
.vital-input:focus { border-color: var(--simrs-primary); outline: none; box-shadow: 0 0 0 3px rgba(11,100,119,0.1); }
.vital-unit { font-size: 0.72rem; color: var(--simrs-gray-500); }

/* Patient Info Chip di header */
.patient-info-chip {
    display: flex; align-items: center; gap: 0.5rem;
    background: var(--simrs-primary-pale);
    padding: 0.35rem 0.75rem; border-radius: var(--border-radius-pill);
    font-size: 0.8rem; color: var(--simrs-primary-dark);
}

/* Form Controls Global */
.form-label-custom {
    font-size: 0.78rem; font-weight: 600;
    color: var(--simrs-gray-600); margin-bottom: 0.35rem;
    display: block;
}
.simrs-textarea {
    font-size: 0.85rem; border: 1.5px solid var(--simrs-gray-200);
    border-radius: var(--border-radius); resize: vertical; min-height: 80px;
    transition: border-color var(--transition-fast);
}
.simrs-textarea:focus {
    border-color: var(--simrs-primary);
    box-shadow: 0 0 0 3px rgba(11,100,119,0.1);
    outline: none;
}
.form-control:focus {
    border-color: var(--simrs-primary);
    box-shadow: 0 0 0 3px rgba(11,100,119,0.1);
}
```

---

## 12. PERINGATAN & ALERT MEDIS

```html
<!-- Alert KRITIS (nilai lab kritis, INA-CBG warning) -->
<div class="alert-medical alert-medical-critical">
    <div class="alert-medical-icon"><i class="fa-solid fa-circle-radiation fa-lg"></i></div>
    <div class="alert-medical-body">
        <div class="alert-medical-title">Nilai Kritis Laboratorium</div>
        <div class="alert-medical-text">Hemoglobin: <strong>5.2 g/dL</strong> (nilai kritis rendah, normal: 12.0–16.0). Konfirmasi dengan dokter DPJP segera.</div>
    </div>
    <button class="btn-action btn-action-approve ms-auto" title="Tandai sudah dikonfirmasi"><i class="fa-solid fa-check"></i></button>
</div>

<!-- Alert WARNING -->
<div class="alert-medical alert-medical-warning">
    <div class="alert-medical-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
    <div class="alert-medical-body">
        <div class="alert-medical-title">Peringatan INA-CBG</div>
        <div class="alert-medical-text">Utilisasi biaya mencapai 87% dari ceiling. Pertimbangkan efisiensi tindakan selanjutnya.</div>
    </div>
    <a href="#" class="btn-simrs-outline btn-sm ms-auto text-nowrap">Lihat Detail</a>
</div>

<!-- Alert INFO -->
<div class="alert-medical alert-medical-info">
    <div class="alert-medical-icon"><i class="fa-solid fa-circle-info"></i></div>
    <div class="alert-medical-body">
        <div class="alert-medical-title">Alergi Obat Tercatat</div>
        <div class="alert-medical-text">Pasien memiliki riwayat alergi terhadap <strong>Penisilin</strong> (reaksi: urtikaria) dan <strong>Aspirin</strong> (reaksi: sesak napas).</div>
    </div>
</div>
```

```css
/* MEDICAL ALERTS */
.alert-medical {
    display: flex; align-items: flex-start; gap: 0.9rem;
    padding: 0.9rem 1rem;
    border-radius: var(--border-radius);
    border-left: 4px solid;
    margin-bottom: 0.75rem;
    animation: slide-in 0.3s ease;
}
@keyframes slide-in {
    from { opacity: 0; transform: translateX(-8px); }
    to { opacity: 1; transform: translateX(0); }
}

.alert-medical-critical {
    background: var(--simrs-critical-bg);
    border-left-color: var(--simrs-critical);
}
.alert-medical-critical .alert-medical-icon { color: var(--simrs-critical); animation: pulse-icon 1s infinite; }
@keyframes pulse-icon {
    0%, 100% { transform: scale(1); }
    50% { transform: scale(1.15); }
}

.alert-medical-warning {
    background: var(--simrs-warning-pale);
    border-left-color: var(--simrs-warning);
}
.alert-medical-warning .alert-medical-icon { color: var(--simrs-warning); }

.alert-medical-info {
    background: var(--simrs-info-pale);
    border-left-color: var(--simrs-info);
}
.alert-medical-info .alert-medical-icon { color: var(--simrs-info); }

.alert-medical-icon { padding-top: 2px; flex-shrink: 0; }
.alert-medical-title { font-size: 0.82rem; font-weight: 700; color: var(--simrs-gray-800); margin-bottom: 0.2rem; }
.alert-medical-text { font-size: 0.8rem; color: var(--simrs-gray-600); line-height: 1.5; }
```

---

## 13. CHART.JS DEFAULTS

```javascript
// Konfigurasi global Chart.js untuk SIMRS
Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
Chart.defaults.font.size = 12;
Chart.defaults.color = '#64748B';

const SIMRS_CHART_COLORS = {
    primary:    'rgba(11, 100, 119, 1)',
    primaryFill:'rgba(11, 100, 119, 0.1)',
    success:    'rgba(26, 135, 84, 1)',
    successFill:'rgba(26, 135, 84, 0.1)',
    danger:     'rgba(197, 55, 44, 1)',
    dangerFill: 'rgba(197, 55, 44, 0.1)',
    warning:    'rgba(199, 138, 18, 1)',
    warningFill:'rgba(199, 138, 18, 0.1)',
    info:       'rgba(22, 120, 180, 1)',
    gray:       'rgba(100, 116, 139, 1)',
};

// Contoh: Line chart kunjungan harian
const visitChart = new Chart(document.getElementById('visitChart'), {
    type: 'line',
    data: {
        labels: ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'],
        datasets: [{
            label: 'Rawat Jalan',
            data: [120, 145, 132, 158, 144, 89, 67],
            borderColor: SIMRS_CHART_COLORS.primary,
            backgroundColor: SIMRS_CHART_COLORS.primaryFill,
            borderWidth: 2, fill: true,
            tension: 0.4, pointRadius: 4, pointBackgroundColor: SIMRS_CHART_COLORS.primary,
        }, {
            label: 'IGD',
            data: [18, 22, 19, 24, 21, 30, 28],
            borderColor: SIMRS_CHART_COLORS.danger,
            backgroundColor: SIMRS_CHART_COLORS.dangerFill,
            borderWidth: 2, fill: true, tension: 0.4,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position: 'top', align: 'end' },
            tooltip: {
                backgroundColor: 'rgba(15, 23, 42, 0.9)',
                titleColor: '#E2E8F0', bodyColor: '#94A3B8',
                borderColor: 'rgba(255,255,255,0.1)', borderWidth: 1,
                padding: 10, cornerRadius: 8,
            }
        },
        scales: {
            x: { grid: { color: 'rgba(226,232,240,0.5)' }, ticks: { font: { size: 11 } } },
            y: { grid: { color: 'rgba(226,232,240,0.5)' }, ticks: { font: { size: 11 } }, beginAtZero: true }
        }
    }
});
```

---

## 14. UTILITAS RESPONSIF

```css
/* RESPONSIVE BREAKPOINTS */
@media (max-width: 1199.98px) {
    :root { --sidebar-width: 240px; }
}

@media (max-width: 991.98px) {
    .simrs-sidebar {
        transform: translateX(-100%);
        box-shadow: none;
    }
    .simrs-sidebar.mobile-open {
        transform: translateX(0);
        box-shadow: 8px 0 30px rgba(0,0,0,0.2);
    }
    .main-wrapper { margin-left: 0 !important; }

    /* Overlay backdrop */
    .sidebar-overlay {
        position: fixed; inset: 0; z-index: 1039;
        background: rgba(0,0,0,0.4); backdrop-filter: blur(2px);
        display: none;
    }
    .sidebar-overlay.show { display: block; }
}

@media (max-width: 575.98px) {
    .simrs-content { padding: 1rem; }
    .page-header-bar { flex-direction: column; align-items: flex-start; gap: 0.75rem; }
    .kpi-card { padding: 1rem; }
    .kpi-value { font-size: 1.3rem; }
    .vital-signs-grid { grid-template-columns: repeat(2, 1fr); }
}

/* PRINT STYLES */
@media print {
    .simrs-sidebar, .simrs-topbar, .simrs-footer,
    .page-header-actions, .btn-action, .btn-simrs-primary { display: none !important; }
    .main-wrapper { margin-left: 0; }
    .simrs-content { padding: 0; }
    .simrs-card { box-shadow: none; border: 1px solid #ddd; }
    body { font-size: 11pt; }
}
```

---

## 15. PANDUAN SWEETALERT2 CUSTOM THEME

```css
/* Override SweetAlert2 agar sesuai SIMRS */
.swal2-popup {
    font-family: 'Plus Jakarta Sans', sans-serif !important;
    border-radius: 12px !important;
    box-shadow: 0 20px 60px rgba(0,0,0,0.15) !important;
}
.swal2-title { font-size: 1.15rem !important; font-weight: 700 !important; color: #0F172A !important; }
.swal2-html-container { font-size: 0.875rem !important; color: #475569 !important; }
.swal2-confirm { border-radius: 8px !important; font-weight: 600 !important; font-size: 0.85rem !important; padding: 0.55rem 1.25rem !important; }
.swal2-cancel  { border-radius: 8px !important; font-weight: 600 !important; font-size: 0.85rem !important; padding: 0.55rem 1.25rem !important; }
.swal2-timer-progress-bar { background: var(--simrs-primary) !important; }

/* Critical popup special style */
.swal-critical .swal2-popup {
    border-top: 4px solid var(--simrs-critical) !important;
}
```

---

## 16. RINGKASAN CDN LENGKAP

```html
<!-- ═══════════════════════════════════════════════
     SEMUA CDN DEPENDENCIES SIMRS — Urutan Import
     ═══════════════════════════════════════════════ -->
<!-- HEAD: CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">

<!-- BODY BOTTOM: JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<!-- Opsional: DataTables untuk tabel panjang -->
<link href="https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap5.min.js"></script>
<!-- Opsional: Select2 untuk dropdown pencarian obat/ICD-10 -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
```

---

*design-simrs.md — SIMRS Laravel 12 | Visual Identity: Clinical Precision | Bootstrap 5 + SweetAlert2 + Font Awesome 6 | Versi 1.0*
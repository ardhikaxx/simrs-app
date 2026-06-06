<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean - {{ config('app.hospital_name') }}</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&family=JetBrains+Mono:wght@800&display=swap" rel="stylesheet">
    
    <!-- Frameworks -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-body: #0F172A; /* Deep Slate */
            --bg-card: rgba(30, 41, 59, 0.7);
            --primary-accent: #0D9488; /* Teal */
            --secondary-accent: #3B82F6; /* Blue */
            --text-call: #F59E0B; /* Amber/Gold */
            --glass-border: rgba(255, 255, 255, 0.08);
        }

        body {
            background-color: var(--bg-body);
            background-image: 
                radial-gradient(circle at 0% 0%, rgba(13, 148, 136, 0.15) 0%, transparent 40%),
                radial-gradient(circle at 100% 100%, rgba(59, 130, 246, 0.1) 0%, transparent 40%);
            color: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
            height: 100vh;
            margin: 0;
        }

        /* Header Sophistication */
        .header-monitor {
            height: 100px;
            padding: 0 4rem;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--glass-border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 100;
        }

        .brand-logo {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, var(--primary-accent), #0F766E);
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 25px rgba(13, 148, 136, 0.4);
            font-size: 1.5rem;
        }

        .brand-title {
            font-weight: 800;
            font-size: 1.8rem;
            letter-spacing: -0.04em;
            line-height: 1;
        }

        .monitor-clock {
            font-family: 'JetBrains Mono', monospace;
            font-size: 2.8rem;
            color: var(--primary-accent);
            line-height: 1;
            text-shadow: 0 0 20px rgba(13, 148, 136, 0.3);
        }

        /* Main Call Area */
        .main-stage {
            height: calc(100vh - 180px);
            padding: 2.5rem;
        }

        .call-card {
            background: var(--bg-card);
            border-radius: 32px;
            border: 1px solid var(--glass-border);
            backdrop-filter: blur(10px);
            box-shadow: 0 40px 100px rgba(0,0,0,0.4);
            height: 100%;
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .call-label {
            font-weight: 800;
            letter-spacing: 0.2em;
            color: var(--primary-accent);
            text-transform: uppercase;
            font-size: 1rem;
            margin-top: 3rem;
        }

        .call-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11rem;
            font-weight: 800;
            color: var(--text-call);
            line-height: 0.9;
            margin: 1.5rem 0;
            text-shadow: 0 0 50px rgba(245, 158, 11, 0.4);
        }

        .call-destination {
            font-size: 3.5rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: -0.02em;
            color: white;
        }

        .call-doctor {
            font-size: 1.8rem;
            color: rgba(255,255,255,0.6);
            font-weight: 600;
        }

        /* Sidebar Lists */
        .sidebar-title {
            font-size: 0.85rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.15em;
            color: rgba(255,255,255,0.4);
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--glass-border);
            margin-bottom: 1.5rem;
        }

        .queue-item {
            background: rgba(255,255,255,0.03);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 1.25rem 1.5rem;
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }

        .queue-item:hover {
            background: rgba(255,255,255,0.06);
            border-color: var(--primary-accent);
        }

        .item-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 2.2rem;
            font-weight: 800;
            color: var(--text-call);
            line-height: 1;
        }

        .item-unit {
            font-size: 1rem;
            font-weight: 800;
            text-transform: uppercase;
            color: white;
            opacity: 0.9;
        }

        .item-status {
            font-size: 0.65rem;
            font-weight: 800;
            padding: 0.5rem 1rem;
            border-radius: 10px;
            background: var(--primary-accent);
            color: white;
        }

        /* Information Bar */
        .info-bar {
            height: 80px;
            background: var(--primary-accent);
            position: fixed;
            bottom: 0;
            width: 100%;
            display: flex;
            align-items: center;
            box-shadow: 0 -10px 40px rgba(0,0,0,0.3);
            z-index: 1000;
        }

        .info-label {
            background: #0F766E;
            height: 100%;
            padding: 0 2rem;
            display: flex;
            align-items: center;
            font-weight: 800;
            font-size: 1.1rem;
            gap: 0.75rem;
            box-shadow: 10px 0 20px rgba(0,0,0,0.1);
            z-index: 10;
        }

        .ticker-wrap {
            flex: 1;
            overflow: hidden;
            padding-left: 2rem;
        }

        .ticker-text {
            display: inline-block;
            white-space: nowrap;
            font-weight: 700;
            font-size: 1.4rem;
            animation: ticker 40s linear infinite;
        }

        @keyframes ticker {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        @keyframes pulse-call {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
            100% { transform: scale(1); opacity: 1; }
        }

        .calling-active {
            animation: pulse-call 1.5s ease-in-out infinite;
        }

        .video-container {
            flex: 1;
            margin: 2rem;
            border-radius: 20px;
            overflow: hidden;
            background: black;
            box-shadow: 0 15px 35px rgba(0,0,0,0.3);
            border: 1px solid var(--glass-border);
        }
    </style>
</head>
<body>

<header class="header-monitor">
    <div class="d-flex align-items-center gap-4">
        <div class="brand-logo">
            <i class="fa-solid fa-hospital-user text-white"></i>
        </div>
        <div>
            <div class="brand-title">SIMRS <span style="color: var(--primary-accent)">CORE</span></div>
            <div class="small fw-bold text-white-50 text-uppercase tracking-wider">Clinical OS Intelligence</div>
        </div>
    </div>
    
    <div class="text-center d-none d-xl-block">
        <div class="h5 mb-0 fw-800 text-white-50">SISTEM MONITOR ANTREAN TERPADU</div>
        <div class="small fw-bold text-primary opacity-75">{{ config('app.hospital_name') }}</div>
    </div>

    <div class="text-end">
        <div class="monitor-clock" id="digitalClock">00:00:00</div>
        <div class="small fw-800 text-white-50 text-uppercase mt-1">{{ now()->translatedFormat('l, d F Y') }}</div>
    </div>
</header>

<main class="container-fluid main-stage">
    <div class="row h-100 g-5">
        <!-- Center Stage: Current Call -->
        <div class="col-lg-8 h-100">
            <div class="call-card">
                <div class="flex-grow-1 d-flex flex-column align-items-center text-center">
                    <div class="call-label">PANGGILAN ANTREAN SEKARANG</div>
                    <div class="call-number {{ $activeQueues->first() ? 'calling-active' : '' }}">
                        {{ $activeQueues->first()?->no_antrian ?? '---' }}
                    </div>
                    <div class="call-destination mb-1">
                        {{ $activeQueues->first()?->department->nama_depart ?? 'SISTEM SIAP' }}
                    </div>
                    <div class="call-doctor">
                        <i class="fa-solid fa-user-doctor me-2 opacity-50"></i>
                        {{ $activeQueues->first()?->doctor?->display_name ?? 'Mohon Menunggu Antrean Berikutnya' }}
                    </div>
                </div>

                <div class="video-container">
                    <video width="100%" height="100%" autoplay loop muted style="object-fit: cover;">
                        <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>

        <!-- Right Side: Unit Queues -->
        <div class="col-lg-4 h-100">
            <div class="sidebar-title d-flex justify-content-between">
                <span>Daftar Antrean Unit</span>
                <span>STATUS</span>
            </div>
            
            <div class="pe-2" style="height: calc(100% - 40px); overflow-y: auto;">
                @forelse($activeQueues->skip(1) as $q)
                    <div class="queue-item">
                        <div>
                            <div class="item-number">{{ $q->no_antrian }}</div>
                            <div class="item-unit">{{ $q->department->nama_depart }}</div>
                        </div>
                        <div class="text-end">
                            <div class="item-status">MENUNGGU</div>
                            <div class="small fw-bold text-white-50 mt-1" style="font-size: 0.6rem;">UNIT AKTIF</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 opacity-20">
                        <i class="fa-solid fa-layer-group display-1 mb-4 d-block"></i>
                        <h4 class="fw-800">BELUM ADA ANTREAN</h4>
                        <p class="small fw-bold">Sistem memonitor pendaftaran baru...</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</main>

<footer class="info-bar">
    <div class="info-label">
        <i class="fa-solid fa-circle-info"></i>
        <span>INFORMASI</span>
    </div>
    <div class="ticker-wrap">
        <div class="ticker-text">
            Selamat Datang di {{ config('app.hospital_name') }} • Utamakan Keselamatan Pasien • Harap Menyiapkan Kartu Identitas (KTP) dan Kartu BPJS untuk Kelancaran Administrasi • Jadwal Dokter Hari Ini: dr. Bima Santoso (Spesialis Penyakit Dalam), dr. Maya Lestari (Spesialis Anak) • Terima Kasih Atas Kepercayaan Anda Kepada Kami.
        </div>
    </div>
</footer>

<script>
    function updateClock() {
        const now = new Date();
        const el = document.getElementById('digitalClock');
        if (el) {
            el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: false });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Auto Refresh every 30 seconds to fetch new data
    setTimeout(function() {
        window.location.reload();
    }, 30000);
</script>

</body>
</html>

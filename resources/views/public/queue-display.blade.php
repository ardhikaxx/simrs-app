<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Monitor Antrean - {{ config('app.hospital_name') }}</title>
    
    <!-- Fonts & Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@700;800&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bs-primary: #3b82f6;
            --bs-info: #06b6d4;
            --bs-warning: #f59e0b;
        }
        body {
            background-color: #f8f9fa;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
            height: 100vh;
            display: flex;
            flex-direction: column;
            margin: 0;
        }

        .header-brand {
            background: #ffffff;
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
            z-index: 10;
        }

        .clock-display {
            font-family: 'JetBrains Mono', monospace;
            color: var(--bs-primary);
            font-weight: 800;
            letter-spacing: -1px;
        }

        .main-content {
            flex: 1;
            padding: 2rem;
            overflow: hidden;
        }

        .card-active-call {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.05);
            border: 1px solid rgba(0,0,0,0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow: hidden;
        }

        .active-number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11rem;
            font-weight: 800;
            line-height: 1;
            color: #1e293b;
            letter-spacing: -4px;
            text-shadow: 2px 4px 10px rgba(0,0,0,0.05);
        }

        .pulse-highlight {
            animation: pulse-border 2.5s infinite;
        }

        @keyframes pulse-border {
            0% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
            70% { box-shadow: 0 0 0 30px rgba(59, 130, 246, 0); }
            100% { box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
        }

        .queue-list-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
            border: 1px solid rgba(0,0,0,0.03);
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .queue-item {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.04);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .queue-item:last-child {
            border-bottom: none;
        }
        
        .queue-item .number {
            font-family: 'JetBrains Mono', monospace;
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--bs-primary);
            line-height: 1;
        }

        .info-ticker {
            background: #1e293b;
            color: #ffffff;
            height: 70px;
            display: flex;
            align-items: center;
            font-size: 1.25rem;
            font-weight: 600;
            z-index: 1000;
        }
        
        .ticker-label {
            background: var(--bs-primary);
            height: 100%;
            padding: 0 2.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
            z-index: 2;
            box-shadow: 5px 0 15px rgba(0,0,0,0.2);
        }

        .ticker-content {
            flex: 1;
            overflow: hidden;
            white-space: nowrap;
            position: relative;
        }

        .ticker-text {
            display: inline-block;
            animation: ticker 40s linear infinite;
        }

        @keyframes ticker {
            0% { transform: translateX(100%); }
            100% { transform: translateX(-100%); }
        }

        .video-wrapper {
            flex: 1;
            border-radius: 16px;
            overflow: hidden;
            margin: 0 2rem 2rem 2rem;
            background: #000;
            box-shadow: 0 10px 25px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<header class="header-brand py-3 px-4 px-xl-5 d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-4">
        <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 70px; height: 70px;">
            <i class="fa-solid fa-hospital-user fs-2"></i>
        </div>
        <div>
            <h2 class="fw-bold text-dark mb-0 lh-1">SIMRS <span class="text-primary">CORE</span></h2>
            <div class="fw-semibold text-muted text-uppercase mt-2" style="font-size: 0.9rem; letter-spacing: 1px;">Sistem Monitor Antrean Terpadu</div>
        </div>
    </div>
    <div class="text-end">
        <div class="clock-display fs-1 lh-1" id="digitalClock">00:00:00</div>
        <div class="fw-bold text-muted text-uppercase mt-2" style="font-size: 0.9rem; letter-spacing: 1px;">{{ now()->translatedFormat('l, d F Y') }}</div>
    </div>
</header>

<main class="main-content">
    <div class="row h-100 g-4">
        <!-- Main Stage -->
        <div class="col-lg-7 col-xl-8 h-100">
            <div class="card-active-call {{ $activeQueues->first() ? 'pulse-highlight' : '' }}">
                <div class="text-center pt-5 pb-4 px-4 flex-shrink-0">
                    <div class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-4 py-2 fw-bold text-uppercase mb-4" style="font-size: 1.25rem; letter-spacing: 2px;">
                        <i class="fa-solid fa-bell me-2"></i>Panggilan Saat Ini
                    </div>
                    <div class="active-number text-primary mb-3">
                        {{ $activeQueues->first()?->no_antrian ?? '---' }}
                    </div>
                    <h1 class="fw-bold text-dark display-4 mb-2 text-uppercase" style="letter-spacing: -1px;">
                        {{ $activeQueues->first()?->department->nama_depart ?? 'Sistem Siap' }}
                    </h1>
                    <div class="fs-3 text-muted fw-semibold">
                        <i class="fa-solid fa-user-doctor me-2 opacity-50"></i>
                        {{ $activeQueues->first()?->doctor?->display_name ?? 'Menunggu Antrean...' }}
                    </div>
                </div>
                <div class="video-wrapper">
                    <video width="100%" height="100%" autoplay loop muted style="object-fit: cover;">
                        <!-- Menggunakan video placeholder publik -->
                        <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                    </video>
                </div>
            </div>
        </div>

        <!-- Next Queues -->
        <div class="col-lg-5 col-xl-4 h-100">
            <div class="queue-list-card">
                <div class="p-4 border-bottom border-light bg-light rounded-top-4 d-flex justify-content-between align-items-center">
                    <h4 class="fw-bold text-dark mb-0"><i class="fa-solid fa-list-ol text-primary me-2"></i>Antrean Berikutnya</h4>
                </div>
                <div class="overflow-auto d-flex flex-column h-100">
                    @forelse($activeQueues->skip(1)->take(6) as $q)
                        <div class="queue-item">
                            <div>
                                <div class="number">{{ $q->no_antrian }}</div>
                                <div class="fs-5 fw-bold text-dark mt-1">{{ $q->department->nama_depart }}</div>
                            </div>
                            <div class="text-end">
                                <span class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill fw-bold" style="font-size: 0.9rem; letter-spacing: 1px;">MENUNGGU</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center my-auto p-5">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 100px; height: 100px;">
                                <i class="fa-solid fa-mug-hot fs-1 text-muted opacity-50"></i>
                            </div>
                            <h4 class="fw-bold text-dark mb-2">Belum Ada Antrean</h4>
                            <p class="text-muted fs-5">Sistem memantau pendaftaran baru...</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="info-ticker">
    <div class="ticker-label">
        <i class="fa-solid fa-circle-info fs-4"></i>
        <span class="text-uppercase" style="letter-spacing: 1px;">Informasi</span>
    </div>
    <div class="ticker-content">
        <div class="ticker-text">
            Selamat Datang di {{ config('app.hospital_name') }} &bull; Utamakan Keselamatan Pasien &bull; Harap Menyiapkan Kartu Identitas (KTP) dan Kartu BPJS untuk Kelancaran Administrasi &bull; Terima Kasih Atas Kepercayaan Anda Kepada Kami.
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

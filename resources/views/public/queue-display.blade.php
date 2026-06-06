<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Display Antrean - {{ config('app.hospital_name') }}</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;700;800&family=JetBrains+Mono:wght@800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --bg-display: #04121b;
            --card-display: #0b1f2e;
            --primary-accent: #0B6477;
            --text-gold: #ffc107;
        }

        body {
            background-color: var(--bg-display);
            color: white;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow: hidden;
            height: 100vh;
        }

        .header-display {
            background: linear-gradient(to right, #0b1f2e, var(--primary-accent));
            padding: 1.5rem 3rem;
            border-bottom: 4px solid var(--text-gold);
        }

        .brand-text { font-weight: 800; font-size: 2rem; letter-spacing: -1px; }
        .clock-display { font-family: 'JetBrains Mono', monospace; font-size: 2.5rem; color: var(--text-gold); }

        .main-call-area {
            background: var(--card-display);
            border-radius: 20px;
            border: 2px solid rgba(255,255,255,0.1);
            padding: 2rem;
            box-shadow: 0 20px 40px rgba(0,0,0,0.5);
            height: 100%;
        }

        .queue-number-large {
            font-family: 'JetBrains Mono', monospace;
            font-size: 8rem;
            color: var(--text-gold);
            line-height: 1;
            margin-bottom: 1rem;
            text-shadow: 0 0 30px rgba(255, 193, 7, 0.3);
        }

        .destination-text { font-size: 3rem; font-weight: 800; text-transform: uppercase; }

        .sidebar-queue {
            height: calc(100vh - 130px);
            overflow-y: auto;
            padding-right: 1rem;
        }

        .small-queue-card {
            background: rgba(255,255,255,0.03);
            border-radius: 12px;
            margin-bottom: 1rem;
            padding: 1rem 1.5rem;
            border-left: 5px solid var(--primary-accent);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .small-queue-number { font-family: 'JetBrains Mono', monospace; font-size: 2rem; font-weight: 800; }

        .running-text {
            background: var(--primary-accent);
            padding: 0.8rem;
            position: fixed;
            bottom: 0;
            width: 100%;
            font-weight: 700;
            font-size: 1.2rem;
        }

        @keyframes blink { 0% { opacity: 1; } 50% { opacity: 0.3; } 100% { opacity: 1; } }
        .blinking { animation: blink 1s infinite; }
    </style>
</head>
<body>

<header class="header-display d-flex justify-content-between align-items-center">
    <div class="d-flex align-items-center gap-3">
        <i class="fa-solid fa-hospital-user fs-1 text-white"></i>
        <div>
            <div class="brand-text">SIMRS CORE</div>
            <div class="small text-white-50 text-uppercase fw-bold">Antrean Pelayanan {{ config('app.hospital_name') }}</div>
        </div>
    </div>
    <div class="text-end">
        <div class="clock-display" id="clock">12:45:00</div>
        <div class="fw-bold text-white-50">{{ now()->translatedFormat('l, d F Y') }}</div>
    </div>
</header>

<div class="container-fluid p-4">
    <div class="row g-4">
        <!-- Area Panggilan Utama -->
        <div class="col-lg-8">
            <div class="main-call-area text-center d-flex flex-column justify-content-center">
                <div class="mb-2 text-white-50 h4 fw-bold">PANGGILAN ANTREAN</div>
                <div class="queue-number-large blinking">
                    {{ $activeQueues->first()?->no_antrian ?? '---' }}
                </div>
                <div class="destination-text mb-2">
                    {{ $activeQueues->first()?->department->nama_depart ?? 'Mohon Tunggu' }}
                </div>
                <div class="h3 fw-bold text-white-50">
                    <i class="fa-solid fa-user-doctor me-2"></i>
                    {{ $activeQueues->first()?->doctor?->display_name ?? '-' }}
                </div>
                
                <div class="mt-5 pt-5 border-top border-secondary opacity-50">
                    <video width="100%" height="auto" autoplay loop muted style="border-radius: 15px;">
                        <source src="https://www.w3schools.com/html/mov_bbb.mp4" type="video/mp4">
                        Video Informasi Kesehatan
                    </video>
                </div>
            </div>
        </div>

        <!-- Daftar Antrean Bangsal/Unit -->
        <div class="col-lg-4">
            <div class="sidebar-queue">
                <h4 class="fw-800 mb-4 text-white-50 border-bottom pb-2">DAFTAR TUNGGU UNIT</h4>
                @forelse($activeQueues->skip(1) as $q)
                    <div class="small-queue-card">
                        <div>
                            <div class="small-queue-number text-gold">{{ $q->no_antrian }}</div>
                            <div class="small fw-bold text-white-50 text-uppercase">{{ $q->department->nama_depart }}</div>
                        </div>
                        <div class="text-end">
                            <div class="badge bg-primary px-3 py-2" style="font-size: 0.7rem;">SILAKAN MENUNGGU</div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-5 opacity-25">
                        <i class="fa-solid fa-clock-rotate-left display-1 mb-3"></i>
                        <h5>Belum Ada Antrean Baru</h5>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<footer class="running-text">
    <marquee behavior="scroll" direction="left">
        Selamat Datang di {{ config('app.hospital_name') }} - Utamakan Keselamatan Pasien - Jadwal Dokter Hari ini: dr. Bima Santoso (POL-UM) 08:00 - 12:00, dr. Maya Lestari (POL-PD) 09:00 - 14:00 - Harap Menyiapkan Kartu BPJS dan KTP Saat Melakukan Registrasi - Tetap Patuhi Protokol Kesehatan Selama Berada di Area Rumah Sakit.
    </marquee>
</footer>

<script>
    function updateClock() {
        const now = new Date();
        const el = document.getElementById('clock');
        if (el) {
            el.textContent = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Auto Refresh setiap 30 detik untuk update antrean
    setTimeout(function() {
        window.location.reload();
    }, 30000);
</script>

</body>
</html>

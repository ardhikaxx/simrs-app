@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', 'Operational Intelligence')
@section('page-subtitle', 'Real-time clinical insights and hospital throughput monitoring')

@section('content')
<!-- KPI Cards Row -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 position-relative overflow-hidden kpi-card">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-gradient-primary text-white shadow-sm">
                    <i class="fa-solid fa-user-injured"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Total Pasien</div>
                    <h3 class="fw-900 mb-0 text-slate">{{ number_format($metrics['patients']) }}</h3>
                </div>
            </div>
            <div class="mt-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-success bg-opacity-10 text-success small fw-bold px-2 py-1 rounded-pill"><i class="fa-solid fa-arrow-trend-up me-1"></i>+2.4%</span>
                    <span class="small text-muted fw-medium" style="font-size: 0.75rem;">vs bulan lalu</span>
                </div>
            </div>
            <div class="kpi-bg-icon">
                <i class="fa-solid fa-hospital-user"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 position-relative overflow-hidden kpi-card">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-gradient-info text-white shadow-sm">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Kunjungan Hari Ini</div>
                    <h3 class="fw-900 mb-0 text-slate">{{ number_format($metrics['visits_today']) }}</h3>
                </div>
            </div>
            <div class="mt-4 d-flex align-items-center justify-content-between">
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-info bg-opacity-10 text-info small fw-bold px-2 py-1 rounded-pill"><i class="fa-solid fa-satellite-dish animate-pulse me-1"></i>Live</span>
                    <span class="small text-muted fw-medium" style="font-size: 0.75rem;">Antrean aktif</span>
                </div>
            </div>
            <div class="kpi-bg-icon">
                <i class="fa-solid fa-stethoscope"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 position-relative overflow-hidden kpi-card">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-gradient-warning text-white shadow-sm">
                    <i class="fa-solid fa-bed-pulse"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Pelayanan Aktif</div>
                    <h3 class="fw-900 mb-0 text-slate">{{ number_format($metrics['active_encounters']) }}</h3>
                </div>
            </div>
            <div class="mt-4 d-flex align-items-center gap-3">
                <div class="progress flex-grow-1" style="height: 8px; background-color: rgba(245, 158, 11, 0.2);">
                    <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: 78%"></div>
                </div>
                <span class="small fw-bold text-warning" style="font-size: 0.75rem;">78%</span>
            </div>
            <div class="kpi-bg-icon">
                <i class="fa-solid fa-heart-pulse"></i>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 position-relative overflow-hidden kpi-card">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-gradient-danger text-white shadow-sm">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Penerimaan Kasir</div>
                    <h3 class="fw-900 mb-0 text-slate">Rp {{ number_format($metrics['revenue_today'] / 1000000, 1) }}M</h3>
                </div>
            </div>
            <div class="mt-4 d-flex align-items-center gap-3">
                <div class="progress flex-grow-1" style="height: 8px; background-color: rgba(239, 68, 68, 0.2);">
                    <div class="progress-bar bg-danger rounded-pill" role="progressbar" style="width: 92%"></div>
                </div>
                <span class="small fw-bold text-danger" style="font-size: 0.75rem;">92%</span>
            </div>
            <div class="kpi-bg-icon">
                <i class="fa-solid fa-cash-register"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-xl-8 d-flex flex-column gap-4">
        <div class="card-premium border-0 p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <div>
                    <h5 class="fw-900 text-slate mb-1 d-flex align-items-center gap-2">
                        <i class="fa-solid fa-chart-area text-primary"></i> Tren Volume Kunjungan
                    </h5>
                    <p class="small text-muted mb-0">Statistik harian pasien rawat jalan & inap</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm fw-bold border px-3 rounded-pill shadow-sm text-muted d-flex align-items-center gap-2" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-calendar-day text-primary"></i> 7 Hari Terakhir <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.7rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4 mt-2">
                        <li><a class="dropdown-item small fw-bold py-2" href="#">7 Hari Terakhir</a></li>
                        <li><a class="dropdown-item small fw-bold py-2" href="#">30 Hari Terakhir</a></li>
                        <li><a class="dropdown-item small fw-bold py-2" href="#">Tahun Ini</a></li>
                    </ul>
                </div>
            </div>
            <div style="height: 320px; width: 100%;">
                <canvas id="visitChart"></canvas>
            </div>
        </div>

        <div class="card-premium border-0 overflow-hidden flex-grow-1">
            <div class="p-4 border-bottom d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <h5 class="fw-900 text-slate mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-users-viewfinder text-primary"></i> Antrean Pelayanan Aktif
                </h5>
                <a href="{{ route('pendaftaran.antrian') }}" class="btn btn-primary btn-sm px-4 fw-800 rounded-pill shadow-sm bg-gradient-primary border-0 transition-hover">
                    VIEW MONITOR <i class="fa-solid fa-arrow-right ms-2 small"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0 custom-table">
                    <thead>
                        <tr class="bg-light bg-opacity-75 text-muted small fw-800 text-uppercase tracking-wider">
                            <th class="ps-4 border-0 py-3 rounded-start">Registrasi</th>
                            <th class="border-0 py-3">Informasi Pasien</th>
                            <th class="border-0 py-3">Unit Pelayanan</th>
                            <th class="border-0 py-3 text-center rounded-end">Alur Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($queue as $encounter)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <span class="font-monospace fw-bold text-primary bg-primary bg-opacity-10 px-2 py-1 rounded-3 small border border-primary border-opacity-10">
                                    {{ $encounter->no_registrasi }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="fw-800 text-slate mb-0">{{ $encounter->patient->nama_pasien }}</div>
                                <div class="small text-muted fw-medium font-monospace opacity-75 d-flex align-items-center gap-1 mt-1">
                                    <i class="fa-regular fa-id-card"></i> RM: {{ $encounter->patient->no_rkm_medis }}
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-slate mb-0 d-flex align-items-center gap-2">
                                    <div class="d-inline-block p-1 rounded bg-light"><i class="fa-solid fa-building text-muted" style="font-size: 0.7rem;"></i></div>
                                    {{ $encounter->department->nama_depart }}
                                </div>
                                <div class="small text-muted fw-medium mt-1 d-flex align-items-center gap-1">
                                    <i class="fa-solid fa-user-doctor opacity-50"></i> {{ $encounter->doctor?->display_name ?? 'Dokter Jaga' }}
                                </div>
                            </td>
                            <td class="text-center py-3">
                                @php
                                    $statusConfig = match($encounter->status_antrian) {
                                        'terdaftar' => ['bg' => 'bg-secondary', 'text' => 'REGISTERED', 'icon' => 'fa-clipboard-list'],
                                        'asesmen_perawat' => ['bg' => 'bg-warning', 'text' => 'NURSING', 'icon' => 'fa-user-nurse'],
                                        'pemeriksaan_dokter' => ['bg' => 'bg-info', 'text' => 'CLINICAL', 'icon' => 'fa-stethoscope'],
                                        'menunggu_kasir', 'menunggu_farmasi', 'menunggu_lab', 'menunggu_rad' => ['bg' => 'bg-primary', 'text' => 'WAITING', 'icon' => 'fa-hourglass-half'],
                                        'selesai' => ['bg' => 'bg-success', 'text' => 'DONE', 'icon' => 'fa-check-circle'],
                                        default => ['bg' => 'bg-dark', 'text' => strtoupper($encounter->status_antrian), 'icon' => 'fa-circle-dot']
                                    };
                                @endphp
                                <div class="badge {{ $statusConfig['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusConfig['bg']) }} border border-{{ str_replace('bg-', '', $statusConfig['bg']) }} border-opacity-25 rounded-pill px-3 py-2 fw-800 d-inline-flex align-items-center gap-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    <i class="fa-solid {{ $statusConfig['icon'] }}"></i> {{ $statusConfig['text'] }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                    <i class="fa-solid fa-inbox fs-1 text-muted opacity-25"></i>
                                </div>
                                <h5 class="fw-900 text-slate">Antrean Kosong</h5>
                                <p class="text-muted small">Saat ini tidak ada pasien dalam antrean aktif.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Sidebar Content -->
    <div class="col-xl-4 d-flex flex-column gap-4">
        <!-- Critical Alerts -->
        @if($criticalLabs->count())
            <div class="card-premium border-0 bg-danger bg-opacity-10 border-start border-danger border-4 p-4 position-relative overflow-hidden alert-card">
                <div class="alert-bg-icon"><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div class="d-flex gap-3 align-items-start position-relative z-1">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        <i class="fa-solid fa-triangle-exclamation animate-pulse"></i>
                    </div>
                    <div>
                        <h6 class="fw-900 text-danger mb-1 text-uppercase tracking-wider" style="font-size: 0.75rem;">Lab Panic Results</h6>
                        <p class="text-danger opacity-75 small fw-bold mb-3 lh-sm">{{ $criticalLabs->count() }} hasil kritis memerlukan intervensi klinis segera.</p>
                        <a href="{{ route('lab.antrian') }}" class="btn btn-danger btn-sm fw-800 rounded-pill px-4 shadow-sm">FOLLOW UP NOW</a>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-premium border-0">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-900 text-slate mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-prescription-bottle-medical text-primary"></i> Dispensing Farmasi
                </h6>
                <span class="badge bg-primary text-white rounded-pill px-3 py-1 shadow-sm">{{ $pendingPrescriptions->count() }}</span>
            </div>
            <div class="p-0">
                <div class="list-group list-group-flush rounded-bottom-4">
                    @forelse($pendingPrescriptions->take(5) as $rx)
                        <div class="list-group-item p-4 border-light transition-hover bg-transparent">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="fw-800 text-slate">{{ $rx->encounter->patient->nama_pasien }}</div>
                                <span class="badge {{ $rx->status === 'baru' ? 'bg-warning text-warning' : 'bg-primary text-primary' }} bg-opacity-10 border border-{{ $rx->status === 'baru' ? 'warning' : 'primary' }} border-opacity-25 rounded-pill px-2 py-1 small fw-bold" style="font-size: 0.6rem;">{{ strtoupper($rx->status) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small">
                                <span class="text-muted fw-bold font-monospace bg-light px-2 py-1 rounded d-flex align-items-center gap-1">
                                    <i class="fa-solid fa-receipt opacity-50"></i> {{ $rx->no_resep }}
                                </span>
                                <span class="text-muted fw-medium d-flex align-items-center gap-1">
                                    <i class="fa-regular fa-clock"></i> {{ $rx->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="d-inline-flex bg-success bg-opacity-10 p-3 rounded-circle mb-3">
                                <i class="fa-solid fa-check-circle fs-3 text-success"></i>
                            </div>
                            <h6 class="fw-800 text-slate mb-1">Semua Selesai</h6>
                            <p class="text-muted small mb-0 fw-medium">Tidak ada resep tertunda saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 bg-light bg-opacity-50 text-center border-top rounded-bottom-4">
                <a href="{{ route('farmasi.antrian-resep') }}" class="text-decoration-none fw-800 small text-primary d-flex align-items-center justify-content-center gap-2">
                    LIHAT SEMUA RESEP <i class="fa-solid fa-arrow-right"></i>
                </a>
            </div>
        </div>

        <div class="card-premium border-0">
            <div class="p-4 border-bottom">
                <h6 class="fw-900 text-slate mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-boxes-stacked text-warning"></i> Peringatan Stok Obat
                </h6>
            </div>
            <div class="p-0">
                <div class="list-group list-group-flush rounded-bottom-4">
                    @forelse($lowStock->take(5) as $medicine)
                        <div class="list-group-item p-4 border-light d-flex justify-content-between align-items-center transition-hover bg-transparent">
                            <div>
                                <div class="fw-800 text-slate mb-1">{{ $medicine->nama_obat }}</div>
                                <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                                    <i class="fa-solid fa-tag opacity-50"></i> {{ $medicine->kategori }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="h4 mb-0 fw-900 text-danger">{{ $medicine->stok }}</div>
                                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">{{ $medicine->satuan }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="d-inline-flex bg-success bg-opacity-10 p-3 rounded-circle mb-3">
                                <i class="fa-solid fa-box-open fs-3 text-success"></i>
                            </div>
                            <h6 class="fw-800 text-slate mb-1">Stok Aman</h6>
                            <p class="text-muted small mb-0 fw-medium">Stok perbekalan medis dalam kondisi baik.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 bg-light bg-opacity-50 text-center border-top rounded-bottom-4">
                <a href="{{ route('farmasi.procurement.create') }}" class="text-decoration-none fw-800 small text-primary d-flex align-items-center justify-content-center gap-2">
                    PENGADAAN SEKARANG <i class="fa-solid fa-cart-plus"></i>
                </a>
            </div>
        </div>
    </div>
</div>

<style>
    /* KPI Cards Enhancements */
    .kpi-card {
        transition: var(--transition-bounce);
    }
    .kpi-card:hover {
        transform: translateY(-8px);
    }
    .kpi-icon {
        width: 56px; height: 56px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
        z-index: 2;
        position: relative;
    }
    .kpi-bg-icon {
        position: absolute;
        top: 50%;
        right: -10px;
        transform: translateY(-50%);
        font-size: 6rem;
        color: var(--simrs-gray-200);
        opacity: 0.3;
        z-index: 1;
        transition: all 0.5s ease;
    }
    .kpi-card:hover .kpi-bg-icon {
        transform: translateY(-50%) scale(1.1);
        right: 0;
    }

    /* Gradients */
    .bg-gradient-primary { background: linear-gradient(135deg, var(--simrs-primary-light), var(--simrs-primary)); }
    .bg-gradient-info { background: linear-gradient(135deg, #38bdf8, #2563eb); }
    .bg-gradient-warning { background: linear-gradient(135deg, #fcd34d, #f59e0b); }
    .bg-gradient-danger { background: linear-gradient(135deg, #fca5a5, #ef4444); }

    /* Text Colors */
    .text-slate { color: var(--simrs-secondary); }
    
    /* Animations */
    .animate-pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: .5; }
    }

    .alert-card {
        animation: pulse-border 2s infinite;
    }
    @keyframes pulse-border {
        0% { border-color: rgba(239, 68, 68, 1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.4); }
        70% { border-color: rgba(239, 68, 68, 0); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { border-color: rgba(239, 68, 68, 1); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .alert-bg-icon {
        position: absolute;
        top: -10px;
        right: -10px;
        font-size: 6rem;
        color: #ef4444;
        opacity: 0.1;
        transform: rotate(15deg);
    }

    /* Table Enhancements */
    .custom-table th {
        font-size: 0.75rem;
        color: #64748b;
    }
    .custom-table td {
        padding: 1rem 0.5rem;
    }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { background-color: var(--simrs-gray-50) !important; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chart Initialization
        const ctx = document.getElementById('visitChart');
        if (ctx) {
            // Updated to match the new Cyan primary color scheme (#0891b2)
            const primaryColor = '#0891b2'; 
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 350);
            gradient.addColorStop(0, 'rgba(8, 145, 178, 0.25)'); // Cyan with opacity
            gradient.addColorStop(1, 'rgba(8, 145, 178, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($visitLabels),
                    datasets: [{
                        label: 'Kunjungan Pasien',
                        data: @json($visitSeries),
                        borderColor: primaryColor,
                        backgroundColor: gradient,
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: primaryColor,
                        pointBorderWidth: 3,
                        pointRadius: 5,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { family: 'Plus Jakarta Sans', size: 14, weight: '800' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 14, weight: '700' },
                            padding: 15,
                            cornerRadius: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Pasien';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false, borderDash: [5, 5] },
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '700' }, color: '#64748b', padding: 10 }
                        },
                        x: { 
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '700' }, color: '#64748b', padding: 10 }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        }
    });
</script>
@endsection
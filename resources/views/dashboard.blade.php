@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', 'Operational Intelligence')
@section('page-subtitle', 'Real-time clinical insights and hospital throughput monitoring')

@section('content')
<!-- KPI Cards Row -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 kpi-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Total Pasien</div>
                    <div class="icon-box bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">{{ number_format($metrics['patients']) }}</h3>
                <div class="d-flex align-items-center gap-2 small">
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-2"><i class="fa-solid fa-arrow-up me-1"></i>2.4%</span>
                    <span class="text-muted">vs bulan lalu</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 kpi-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Kunjungan Hari Ini</div>
                    <div class="icon-box bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-calendar-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">{{ number_format($metrics['visits_today']) }}</h3>
                <div class="d-flex align-items-center gap-2 small">
                    <span class="badge bg-info bg-opacity-10 text-info rounded-pill px-2"><i class="fa-solid fa-satellite-dish me-1"></i>Live</span>
                    <span class="text-muted">Antrean aktif</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 kpi-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Pelayanan Aktif</div>
                    <div class="icon-box bg-warning bg-opacity-10 text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-bed-pulse fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">{{ number_format($metrics['active_encounters']) }}</h3>
                <div class="d-flex align-items-center gap-3">
                    <div class="progress flex-grow-1" style="height: 6px; background-color: rgba(245, 158, 11, 0.15);">
                        <div class="progress-bar bg-warning rounded-pill" role="progressbar" style="width: 78%"></div>
                    </div>
                    <span class="small fw-semibold text-warning" style="font-size: 0.75rem;">78%</span>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 kpi-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Penerimaan Kasir</div>
                    <div class="icon-box bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-money-bill-trend-up fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">Rp {{ number_format($metrics['revenue_today'] / 1000000, 1) }}M</h3>
                <div class="d-flex align-items-center gap-3">
                    <div class="progress flex-grow-1" style="height: 6px; background-color: rgba(239, 68, 68, 0.15);">
                        <div class="progress-bar bg-danger rounded-pill" role="progressbar" style="width: 92%"></div>
                    </div>
                    <span class="small fw-semibold text-danger" style="font-size: 0.75rem;">92%</span>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-xl-8 d-flex flex-column gap-4">
        <!-- Chart Section -->
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 mb-4">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Tren Volume Kunjungan</h5>
                    <p class="small text-muted mb-0">Statistik harian pasien rawat jalan & inap</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm fw-semibold border px-3 rounded-pill text-muted d-flex align-items-center gap-2 shadow-none" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-calendar-day text-primary opacity-75"></i> 7 Hari Terakhir <i class="fa-solid fa-chevron-down ms-1" style="font-size: 0.7rem;"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end border-0 shadow rounded-3 mt-2">
                        <li><a class="dropdown-item small fw-medium py-2" href="#">7 Hari Terakhir</a></li>
                        <li><a class="dropdown-item small fw-medium py-2" href="#">30 Hari Terakhir</a></li>
                        <li><a class="dropdown-item small fw-medium py-2" href="#">Tahun Ini</a></li>
                    </ul>
                </div>
            </div>
            <div style="height: 300px; width: 100%;">
                <canvas id="visitChart"></canvas>
            </div>
        </div>

        <!-- Table Section -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden flex-grow-1">
            <div class="p-4 border-bottom border-light d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3 bg-white">
                <div>
                    <h5 class="fw-bold text-dark mb-1">Antrean Pelayanan Aktif</h5>
                    <p class="small text-muted mb-0">Pasien yang sedang dalam proses pelayanan</p>
                </div>
                <a href="{{ route('pendaftaran.antrian') }}" class="btn btn-primary btn-sm px-4 fw-semibold rounded-pill shadow-none">
                    Lihat Semua Antrean <i class="fa-solid fa-arrow-right ms-1 small"></i>
                </a>
            </div>
            <div class="table-responsive bg-white">
                <table class="table table-hover table-borderless align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                            <th class="ps-4 py-3 rounded-start">Registrasi</th>
                            <th class="py-3">Informasi Pasien</th>
                            <th class="py-3">Unit Pelayanan</th>
                            <th class="py-3 text-center rounded-end">Status</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-light">
                    @forelse($queue as $encounter)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 font-monospace px-2 py-1 rounded-2">
                                    {{ $encounter->no_registrasi }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-dark mb-0">{{ $encounter->patient->nama_pasien }}</div>
                                <div class="small text-muted mt-1 font-monospace opacity-75">
                                    RM: {{ $encounter->patient->no_rkm_medis }}
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-medium text-dark mb-0 d-flex align-items-center gap-2">
                                    {{ $encounter->department->nama_depart }}
                                </div>
                                <div class="small text-muted mt-1 d-flex align-items-center gap-1">
                                    <i class="fa-solid fa-user-doctor opacity-50"></i> {{ $encounter->doctor?->display_name ?? 'Dokter Jaga' }}
                                </div>
                            </td>
                            <td class="text-center py-3">
                                @php
                                    $statusConfig = match($encounter->status_antrian) {
                                        'terdaftar' => ['bg' => 'bg-secondary', 'text' => 'Registered'],
                                        'asesmen_perawat' => ['bg' => 'bg-warning', 'text' => 'Nursing'],
                                        'pemeriksaan_dokter' => ['bg' => 'bg-info', 'text' => 'Clinical'],
                                        'menunggu_kasir', 'menunggu_farmasi', 'menunggu_lab', 'menunggu_rad' => ['bg' => 'bg-primary', 'text' => 'Waiting'],
                                        'selesai' => ['bg' => 'bg-success', 'text' => 'Done'],
                                        default => ['bg' => 'bg-dark', 'text' => ucfirst($encounter->status_antrian)]
                                    };
                                @endphp
                                <span class="badge {{ $statusConfig['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusConfig['bg']) }} rounded-pill px-3 py-1 fw-semibold d-inline-block">
                                    {{ $statusConfig['text'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 64px; height: 64px;">
                                    <i class="fa-solid fa-inbox fs-4 text-muted opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Antrean Kosong</h6>
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
            <div class="card border-0 shadow-sm rounded-4 bg-danger bg-opacity-10 border-start border-danger border-4">
                <div class="card-body p-4">
                    <div class="d-flex gap-3 align-items-start">
                        <div class="bg-danger text-white rounded-3 d-flex align-items-center justify-content-center shrink-0" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-triangle-exclamation"></i>
                        </div>
                        <div>
                            <h6 class="fw-bold text-danger mb-1">Peringatan Lab Kritis</h6>
                            <p class="text-danger opacity-75 small fw-medium mb-3 lh-sm">{{ $criticalLabs->count() }} hasil kritis memerlukan intervensi klinis segera.</p>
                            <a href="{{ route('lab.antrian') }}" class="btn btn-sm btn-danger fw-semibold px-3 rounded-pill shadow-none">Tindak Lanjut</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pharmacy Dispensing -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="p-4 border-bottom border-light d-flex justify-content-between align-items-center bg-white rounded-top-4">
                <h6 class="fw-bold text-dark mb-0">Dispensing Farmasi</h6>
                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-2 py-1">{{ $pendingPrescriptions->count() }}</span>
            </div>
            <div class="p-0 bg-white">
                <div class="list-group list-group-flush">
                    @forelse($pendingPrescriptions->take(5) as $rx)
                        <div class="list-group-item p-4 border-light transition-hover">
                            <div class="d-flex justify-content-between align-items-start mb-1">
                                <div class="fw-semibold text-dark">{{ $rx->encounter->patient->nama_pasien }}</div>
                                <span class="badge {{ $rx->status === 'baru' ? 'bg-warning text-warning' : 'bg-primary text-primary' }} bg-opacity-10 rounded-pill px-2 py-1 small fw-medium" style="font-size: 0.65rem;">{{ ucfirst($rx->status) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small">
                                <span class="text-muted font-monospace bg-light px-2 py-1 rounded d-inline-block">
                                    {{ $rx->no_resep }}
                                </span>
                                <span class="text-muted fw-medium">
                                    {{ $rx->created_at->diffForHumans() }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="d-inline-flex bg-light p-3 rounded-circle mb-3">
                                <i class="fa-solid fa-check fs-4 text-success opacity-75"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Semua Selesai</h6>
                            <p class="text-muted small mb-0 fw-medium">Tidak ada resep tertunda saat ini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 bg-light bg-opacity-50 text-center border-top border-light rounded-bottom-4">
                <a href="{{ route('farmasi.antrian-resep') }}" class="text-decoration-none fw-semibold small text-primary">Lihat Semua Resep <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
        </div>

        <!-- Low Stock Warning -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="p-4 border-bottom border-light bg-white rounded-top-4">
                <h6 class="fw-bold text-dark mb-0">Peringatan Stok Obat</h6>
            </div>
            <div class="p-0 bg-white">
                <div class="list-group list-group-flush">
                    @forelse($lowStock->take(5) as $medicine)
                        <div class="list-group-item p-4 border-light d-flex justify-content-between align-items-center transition-hover">
                            <div>
                                <div class="fw-semibold text-dark mb-1">{{ $medicine->nama_obat }}</div>
                                <div class="small text-muted fw-medium">
                                    {{ $medicine->kategori }}
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="h5 mb-0 fw-bold text-danger">{{ $medicine->stok }}</div>
                                <div class="small text-muted fw-semibold text-uppercase" style="font-size: 0.65rem;">{{ $medicine->satuan }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <div class="d-inline-flex bg-light p-3 rounded-circle mb-3">
                                <i class="fa-solid fa-box-open fs-4 text-success opacity-75"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Stok Aman</h6>
                            <p class="text-muted small mb-0 fw-medium">Persediaan medis dalam kondisi baik.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 bg-light bg-opacity-50 text-center border-top border-light rounded-bottom-4">
                <a href="{{ route('farmasi.procurement.create') }}" class="text-decoration-none fw-semibold small text-primary">Pengadaan Baru <i class="fa-solid fa-plus ms-1"></i></a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Clean Minimalist Styling */
    body { background-color: #f8f9fa; }
    
    .kpi-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .kpi-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important;
    }

    .icon-box {
        transition: all 0.3s ease;
    }
    .kpi-card:hover .icon-box {
        transform: scale(1.1) rotate(-5deg);
    }

    .custom-table th {
        font-size: 0.75rem;
        color: #6c757d;
        font-weight: 600;
    }
    .custom-table td {
        padding: 1rem 0.5rem;
        color: #495057;
    }

    .transition-hover { transition: background-color 0.2s ease; }
    .transition-hover:hover { background-color: #f8f9fa !important; }
    
    .card {
        border-color: rgba(0,0,0,0.03) !important;
    }
    .badge {
        font-weight: 500;
        letter-spacing: 0.3px;
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Soft SaaS Chart Theme
        const ctx = document.getElementById('visitChart');
        if (ctx) {
            const primaryColor = '#3b82f6'; // Clean soft blue
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
            gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($visitLabels),
                    datasets: [{
                        label: 'Kunjungan Pasien',
                        data: @json($visitSeries),
                        borderColor: primaryColor,
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: primaryColor,
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: '600' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 13, weight: '400' },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Kunjungan';
                                }
                            }
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(0, 0, 0, 0.04)', drawBorder: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '500' }, color: '#64748b', padding: 10 }
                        },
                        x: { 
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '500' }, color: '#64748b', padding: 10 }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        }
    });
</script>
@endsection
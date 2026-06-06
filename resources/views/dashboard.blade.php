@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', 'Operational Intelligence')
@section('page-subtitle', 'Real-time clinical insights and hospital throughput monitoring')

@section('content')
<!-- KPI Cards Row -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-teal-soft text-primary">
                    <i class="fa-solid fa-user-injured"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Total Pasien</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ number_format($metrics['patients']) }}</h3>
                </div>
            </div>
            <div class="mt-3 d-flex align-items-center gap-2">
                <span class="badge bg-success bg-opacity-10 text-success small fw-bold">+2.4%</span>
                <span class="small text-muted fw-medium">vs bulan lalu</span>
            </div>
            <i class="fa-solid fa-hospital-user position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-blue-soft text-info">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Kunjungan Hari Ini</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ number_format($metrics['visits_today']) }}</h3>
                </div>
            </div>
            <div class="mt-3 d-flex align-items-center gap-2">
                <span class="badge bg-info bg-opacity-10 text-info small fw-bold">Live</span>
                <span class="small text-muted fw-medium">Antrean aktif saat ini</span>
            </div>
            <i class="fa-solid fa-stethoscope position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-amber-soft text-warning">
                    <i class="fa-solid fa-bed-pulse"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Pelayanan Aktif</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ number_format($metrics['active_encounters']) }}</h3>
                </div>
            </div>
            <div class="mt-3 d-flex align-items-center gap-2">
                <span class="badge bg-warning bg-opacity-10 text-warning small fw-bold">78%</span>
                <span class="small text-muted fw-medium">Okupansi Bed</span>
            </div>
            <i class="fa-solid fa-heart-pulse position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-rose-soft text-danger">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Penerimaan Kasir</div>
                    <h3 class="fw-800 mb-0 text-slate">Rp {{ number_format($metrics['revenue_today'] / 1000000, 1) }}M</h3>
                </div>
            </div>
            <div class="mt-3 d-flex align-items-center gap-2">
                <span class="badge bg-danger bg-opacity-10 text-danger small fw-bold">Target</span>
                <span class="small text-muted fw-medium">92% dari harian</span>
            </div>
            <i class="fa-solid fa-cash-register position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Main Content -->
    <div class="col-xl-8">
        <div class="card-premium border-0 bg-white p-4 mb-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h5 class="fw-800 text-slate mb-1">Tren Volume Kunjungan</h5>
                    <p class="small text-muted mb-0">Statistik harian pasien rawat jalan & inap</p>
                </div>
                <div class="dropdown">
                    <button class="btn btn-light btn-sm fw-bold border-0 px-3" data-bs-toggle="dropdown">
                        <i class="fa-solid fa-calendar-day me-2 opacity-50"></i>7 Hari Terakhir
                    </button>
                    <ul class="dropdown-menu border-0 shadow-lg rounded-3">
                        <li><a class="dropdown-item small fw-bold" href="#">30 Hari Terakhir</a></li>
                        <li><a class="dropdown-item small fw-bold" href="#">Tahun Ini</a></li>
                    </ul>
                </div>
            </div>
            <div style="height: 350px;">
                <canvas id="visitChart"></canvas>
            </div>
        </div>

        <div class="card-premium border-0 bg-white overflow-hidden">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h5 class="fw-800 text-slate mb-0">Antrean Pelayanan Aktif</h5>
                <a href="{{ route('pendaftaran.antrian') }}" class="btn btn-primary btn-sm px-4 fw-800 rounded-pill shadow-sm">
                    VIEW MONITOR <i class="fa-solid fa-arrow-right ms-2 small"></i>
                </a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                            <th class="ps-4 border-0 py-3">Registrasi</th>
                            <th class="border-0 py-3">Informasi Pasien</th>
                            <th class="border-0 py-3">Unit Pelayanan</th>
                            <th class="border-0 py-3 text-center">Alur Status</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($queue as $encounter)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <span class="font-monospace fw-800 text-primary bg-primary bg-opacity-10 px-2 py-1 rounded small border border-primary border-opacity-10">
                                    {{ $encounter->no_registrasi }}
                                </span>
                            </td>
                            <td class="py-3">
                                <div class="fw-800 text-slate mb-0">{{ $encounter->patient->nama_pasien }}</div>
                                <div class="small text-muted fw-medium font-monospace opacity-75">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                            </td>
                            <td class="py-3">
                                <div class="fw-bold text-slate mb-0">{{ $encounter->department->nama_depart }}</div>
                                <div class="small text-muted fw-medium"><i class="fa-solid fa-user-doctor me-1 opacity-50"></i>{{ $encounter->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                            </td>
                            <td class="text-center py-3">
                                @php
                                    $statusConfig = match($encounter->status_antrian) {
                                        'terdaftar' => ['bg' => 'bg-slate', 'text' => 'REGISTERED'],
                                        'asesmen_perawat' => ['bg' => 'bg-amber', 'text' => 'NURSING'],
                                        'pemeriksaan_dokter' => ['bg' => 'bg-teal', 'text' => 'CLINICAL'],
                                        'menunggu_kasir', 'menunggu_farmasi', 'menunggu_lab', 'menunggu_rad' => ['bg' => 'bg-blue', 'text' => 'WAITING'],
                                        'selesai' => ['bg' => 'bg-success', 'text' => 'DONE'],
                                        default => ['bg' => 'bg-dark', 'text' => strtoupper($encounter->status_antrian)]
                                    };
                                @endphp
                                <div class="badge {{ $statusConfig['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusConfig['bg']) }} rounded-pill px-3 py-2 fw-800" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    {{ $statusConfig['text'] }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                                    <i class="fa-solid fa-inbox fs-2 text-muted opacity-25"></i>
                                </div>
                                <h6 class="fw-800 text-slate">Antrean Kosong</h6>
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
    <div class="col-xl-4">
        <!-- Critical Alerts -->
        @if($criticalLabs->count())
            <div class="card-premium border-0 bg-danger bg-opacity-10 border-start border-danger border-4 p-4 mb-4 position-relative overflow-hidden">
                <div class="d-flex gap-3 align-items-start position-relative">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        <i class="fa-solid fa-triangle-exclamation animate-pulse"></i>
                    </div>
                    <div>
                        <h6 class="fw-800 text-danger mb-1 text-uppercase tracking-wider" style="font-size: 0.75rem;">Lab Panic Results</h6>
                        <p class="text-danger opacity-75 small fw-bold mb-3">{{ $criticalLabs->count() }} hasil kritis memerlukan intervensi klinis segera.</p>
                        <a href="{{ route('lab.antrian') }}" class="btn btn-danger btn-sm fw-800 rounded-pill px-4 shadow-sm">FOLLOW UP NOW</a>
                    </div>
                </div>
            </div>
        @endif

        <div class="card-premium border-0 bg-white mb-4">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center">
                <h6 class="fw-800 text-slate mb-0">Dispensing Farmasi</h6>
                <span class="badge bg-teal text-white rounded-pill px-3">{{ $pendingPrescriptions->count() }}</span>
            </div>
            <div class="p-0">
                <div class="list-group list-group-flush">
                    @forelse($pendingPrescriptions->take(5) as $rx)
                        <div class="list-group-item p-4 border-light transition-hover">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <div class="fw-800 text-slate">{{ $rx->encounter->patient->nama_pasien }}</div>
                                <span class="badge {{ $rx->status === 'baru' ? 'bg-amber text-amber' : 'bg-teal text-teal' }} bg-opacity-10 small fw-bold" style="font-size: 0.6rem;">{{ strtoupper($rx->status) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center small">
                                <span class="text-muted fw-bold font-monospace"><i class="fa-solid fa-receipt me-1 opacity-50"></i>{{ $rx->no_resep }}</span>
                                <span class="text-muted fw-medium">{{ $rx->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fa-solid fa-check-circle fs-1 text-success opacity-10 mb-2 d-block"></i>
                            <p class="text-muted small mb-0 fw-bold">Tidak ada resep tertunda</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 bg-light bg-opacity-50 text-center">
                <a href="{{ route('farmasi.antrian-resep') }}" class="text-decoration-none fw-800 small text-primary">LIHAT SEMUA RESEP</a>
            </div>
        </div>

        <div class="card-premium border-0 bg-white">
            <div class="p-4 border-bottom">
                <h6 class="fw-800 text-slate mb-0">Peringatan Stok Obat</h6>
            </div>
            <div class="p-0">
                <div class="list-group list-group-flush">
                    @forelse($lowStock->take(5) as $medicine)
                        <div class="list-group-item p-4 border-light d-flex justify-content-between align-items-center transition-hover">
                            <div>
                                <div class="fw-800 text-slate mb-1">{{ $medicine->nama_obat }}</div>
                                <div class="small text-muted fw-medium"><i class="fa-solid fa-tag me-1 opacity-50"></i>{{ $medicine->kategori }}</div>
                            </div>
                            <div class="text-end">
                                <div class="h5 mb-0 fw-900 text-danger">{{ $medicine->stok }}</div>
                                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">{{ $medicine->satuan }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fa-solid fa-box-open fs-1 text-success opacity-10 mb-2 d-block"></i>
                            <p class="text-muted small mb-0 fw-bold">Stok perbekalan aman</p>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="p-3 bg-light bg-opacity-50 text-center rounded-bottom-4">
                <a href="{{ route('farmasi.procurement.create') }}" class="text-decoration-none fw-800 small text-primary">PENGADAAN SEKARANG</a>
            </div>
        </div>
    </div>
</div>

<style>
    .kpi-icon {
        width: 56px; height: 56px;
        border-radius: 14px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.5rem;
    }
    
    .bg-teal-soft { background: #F0FDFA; }
    .bg-blue-soft { background: #EFF6FF; }
    .bg-amber-soft { background: #FFFBEB; }
    .bg-rose-soft { background: #FFF1F2; }
    
    .text-slate { color: #1E293B; }
    .text-teal { color: #0D9488; }
    .text-blue { color: #3B82F6; }
    .text-amber { color: #F59E0B; }
    
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: translateY(-8px); }
    
    .transition-hover:hover { background-color: #F8FAFC !important; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('visitChart');
        if (ctx) {
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 350);
            gradient.addColorStop(0, 'rgba(13, 148, 136, 0.2)');
            gradient.addColorStop(1, 'rgba(13, 148, 136, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($visitLabels),
                    datasets: [{
                        label: 'Kunjungan Pasien',
                        data: @json($visitSeries),
                        borderColor: '#0D9488',
                        backgroundColor: gradient,
                        borderWidth: 4,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0D9488',
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
                            backgroundColor: '#0F172A',
                            titleFont: { family: 'Plus Jakarta Sans', size: 14, weight: '800' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 14, weight: '700' },
                            padding: 15,
                            cornerRadius: 12,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(226, 232, 240, 0.5)', drawBorder: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '700' }, color: '#94A3B8', padding: 10 }
                        },
                        x: { 
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '700' }, color: '#94A3B8', padding: 10 }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        }
    });
</script>
@endsection

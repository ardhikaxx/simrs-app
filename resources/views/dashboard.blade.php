@extends('layouts.app')

@section('title', 'Dashboard Utama')
@section('page-title', 'Dashboard Operasional SIMRS')
@section('page-subtitle', 'Pusat kendali dan ringkasan real-time pelayanan rumah sakit')

@section('content')
<!-- KPI Cards Row -->
<div class="row g-4 mb-5">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative bg-white dashboard-kpi-card">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="kpi-icon-wrapper bg-primary bg-gradient text-white shadow-sm">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <div>
                    <div class="kpi-title text-muted">Total Pasien</div>
                    <div class="kpi-value text-dark">{{ number_format($metrics['patients']) }}</div>
                </div>
            </div>
            <div class="kpi-accent bg-primary"></div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative bg-white dashboard-kpi-card">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="kpi-icon-wrapper bg-info bg-gradient text-white shadow-sm">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <div class="kpi-title text-muted">Kunjungan Hari Ini</div>
                    <div class="kpi-value text-dark">{{ number_format($metrics['visits_today']) }}</div>
                </div>
            </div>
            <div class="kpi-accent bg-info"></div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative bg-white dashboard-kpi-card">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="kpi-icon-wrapper bg-warning bg-gradient text-white shadow-sm">
                    <i class="fa-solid fa-bed-pulse"></i>
                </div>
                <div>
                    <div class="kpi-title text-muted">Pelayanan Aktif</div>
                    <div class="kpi-value text-dark">{{ number_format($metrics['active_encounters']) }}</div>
                </div>
            </div>
            <div class="kpi-accent bg-warning"></div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative bg-white dashboard-kpi-card">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="kpi-icon-wrapper bg-success bg-gradient text-white shadow-sm">
                    <i class="fa-solid fa-money-bill-trend-up"></i>
                </div>
                <div>
                    <div class="kpi-title text-muted">Penerimaan Kasir</div>
                    <div class="kpi-value text-dark fs-4">Rp {{ number_format($metrics['revenue_today'] / 1000000, 1) }}M</div>
                </div>
            </div>
            <div class="kpi-accent bg-success"></div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Utama Kiri -->
    <div class="col-xl-8 d-flex flex-column gap-4">
        <!-- Grafik Kunjungan -->
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-chart-area small"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Tren Kunjungan (7 Hari Terakhir)</h6>
                </div>
                <div class="dropdown">
                    <button class="btn btn-sm btn-light border-light-subtle text-muted px-2" data-bs-toggle="dropdown"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3">
                        <li><a class="dropdown-item small" href="#"><i class="fa-solid fa-download me-2"></i>Unduh Laporan</a></li>
                    </ul>
                </div>
            </div>
            <div class="card-body p-4">
                <div style="height: 320px; position: relative;">
                    <canvas id="visitChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Tabel Antrean Aktif -->
        <div class="card border-0 shadow-sm rounded-4 flex-grow-1">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-info bg-opacity-10 text-info rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-people-arrows small"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Antrean Pelayanan Aktif</h6>
                </div>
                <a href="{{ route('pendaftaran.antrian') }}" class="btn btn-sm btn-primary bg-gradient shadow-sm rounded-pill px-3 fw-semibold transition-hover">Lihat Monitor <i class="fa-solid fa-arrow-right ms-1"></i></a>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light bg-opacity-75">
                        <tr class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                            <th class="border-0 px-4 py-3">Registrasi</th>
                            <th class="border-0 py-3">Pasien</th>
                            <th class="border-0 py-3">Poliklinik/Unit</th>
                            <th class="border-0 py-3">DPJP</th>
                            <th class="border-0 px-4 py-3 text-center">Status Alur</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                    @forelse($queue as $encounter)
                        <tr>
                            <td class="px-4 py-3">
                                <span class="text-primary font-monospace fw-bold bg-primary bg-opacity-10 px-2 py-1 rounded border border-primary border-opacity-25">{{ $encounter->no_registrasi }}</span>
                            </td>
                            <td>
                                <div class="fw-bold text-dark">{{ $encounter->patient->nama_pasien }}</div>
                                <div class="small text-muted font-monospace"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>{{ $encounter->patient->no_rkm_medis }}</div>
                            </td>
                            <td>
                                <div class="fw-semibold text-dark">{{ $encounter->department->nama_depart }}</div>
                            </td>
                            <td>
                                <div class="d-flex align-items-center gap-2">
                                    <i class="fa-solid fa-user-doctor text-muted opacity-50"></i>
                                    <span class="fw-medium">{{ $encounter->doctor?->display_name ?? 'Belum Ditentukan' }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                @php
                                    $badgeStatusClass = match($encounter->status_antrian) {
                                        'terdaftar' => 'bg-info',
                                        'asesmen_perawat' => 'bg-warning',
                                        'pemeriksaan_dokter' => 'bg-primary',
                                        'menunggu_kasir', 'menunggu_farmasi', 'menunggu_lab', 'menunggu_rad' => 'bg-secondary',
                                        'selesai' => 'bg-success',
                                        default => 'bg-dark'
                                    };
                                @endphp
                                <span class="badge {{ $badgeStatusClass }} bg-opacity-10 text-{{ str_replace('bg-', '', $badgeStatusClass) }} rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    {{ str_replace('_', ' ', strtoupper($encounter->status_antrian)) }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 60px; height: 60px;">
                                    <i class="fa-solid fa-mug-hot fs-2 text-muted opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark">Belum ada pasien</h6>
                                <p class="text-muted small mb-0">Antrean pelayanan saat ini kosong.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kolom Samping Kanan (Alerts & Monitoring) -->
    <div class="col-xl-4 d-flex flex-column gap-4">
        <!-- Critical Lab Alert -->
        @if($criticalLabs->count())
            <div class="card border-0 shadow-sm rounded-4 bg-danger bg-opacity-10 border-start border-danger border-4">
                <div class="card-body p-4 d-flex gap-3 align-items-start">
                    <div class="bg-danger text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
                        <i class="fa-solid fa-triangle-exclamation pulse-animation"></i>
                    </div>
                    <div>
                        <h6 class="fw-bold text-danger mb-1 text-uppercase" style="letter-spacing: 0.5px;">Nilai Kritis Laboratorium</h6>
                        <p class="text-danger opacity-75 small fw-semibold mb-2">{{ $criticalLabs->count() }} hasil tes membutuhkan verifikasi dan intervensi klinis segera.</p>
                        <a href="{{ route('lab.antrian') }}" class="btn btn-sm btn-danger fw-bold rounded-pill px-3 shadow-sm">Tindak Lanjuti <i class="fa-solid fa-arrow-right ms-1"></i></a>
                    </div>
                </div>
            </div>
        @endif

        <!-- Pending Prescriptions -->
        <div class="card border-0 shadow-sm rounded-4 flex-grow-1">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-prescription-bottle-medical small"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Dispensing Farmasi</h6>
                </div>
                <span class="badge bg-success rounded-pill">{{ $pendingPrescriptions->count() }}</span>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($pendingPrescriptions as $rx)
                        <div class="list-group-item px-4 py-3 border-light">
                            <div class="d-flex justify-content-between align-items-center mb-1">
                                <h6 class="fw-bold text-dark mb-0">{{ $rx->encounter->patient->nama_pasien }}</h6>
                                <span class="badge {{ $rx->status === 'baru' ? 'bg-warning text-dark' : 'bg-primary' }} rounded-pill" style="font-size: 0.65rem;">{{ strtoupper($rx->status) }}</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-mono small text-muted"><i class="fa-solid fa-receipt me-1 opacity-50"></i>{{ $rx->no_resep }}</div>
                                <div class="small text-muted fw-semibold">{{ $rx->created_at->diffForHumans() }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fa-solid fa-check-double fs-1 text-success opacity-25 mb-2 d-block"></i>
                            <div class="text-muted small fw-semibold">Tidak ada resep tertunda.</div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer bg-light border-0 text-center py-3">
                <a href="{{ route('farmasi.antrian-resep') }}" class="text-decoration-none fw-bold small text-primary transition-hover">Buka Antrean Farmasi</a>
            </div>
        </div>

        <!-- Low Stock Alert -->
        <div class="card border-0 shadow-sm rounded-4 flex-grow-1">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-boxes-stacked small"></i>
                    </div>
                    <h6 class="fw-bold mb-0 text-dark">Peringatan Stok Obat</h6>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @forelse($lowStock as $medicine)
                        <div class="list-group-item px-4 py-3 border-light d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="fw-bold text-dark mb-1">{{ $medicine->nama_obat }}</h6>
                                <div class="small text-muted"><i class="fa-solid fa-tag me-1 opacity-50"></i>{{ $medicine->kategori }}</div>
                            </div>
                            <div class="text-end">
                                <div class="h5 mb-0 fw-bolder text-danger">{{ $medicine->stok }}</div>
                                <div class="small text-muted text-uppercase" style="font-size: 0.65rem;">{{ $medicine->satuan }}</div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-5">
                            <i class="fa-solid fa-box-open fs-1 text-success opacity-25 mb-2 d-block"></i>
                            <div class="text-muted small fw-semibold">Ketersediaan stok perbekalan aman.</div>
                        </div>
                    @endforelse
                </div>
            </div>
            <div class="card-footer bg-light border-0 text-center py-3">
                <a href="{{ route('farmasi.procurement.create') }}" class="text-decoration-none fw-bold small text-primary transition-hover"><i class="fa-solid fa-cart-plus me-1"></i> Buat PO Pengadaan</a>
            </div>
        </div>
    </div>
</div>

<style>
    /* Premium Dashboard Specific Styles */
    .dashboard-kpi-card { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .dashboard-kpi-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.05) !important; }
    
    .kpi-icon-wrapper {
        width: 64px; height: 64px;
        border-radius: 16px;
        display: flex; align-items: center; justify-content: center;
        font-size: 1.75rem;
    }
    
    .kpi-title { font-size: 0.75rem; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 0.25rem; }
    .kpi-value { font-size: 1.75rem; font-weight: 900; line-height: 1; letter-spacing: -1px; }
    
    .kpi-accent {
        position: absolute; bottom: 0; left: 0; right: 0; height: 4px;
        opacity: 0.8;
    }

    .transition-hover { transition: all 0.2s ease; display: inline-block; }
    .transition-hover:hover { transform: translateX(3px); }

    @keyframes pulse-ring {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(239, 68, 68, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(239, 68, 68, 0); }
    }
    .pulse-animation { animation: pulse-ring 2s infinite; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('visitChart');
        if (ctx) {
            // Create a gradient for the chart
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 320);
            gradient.addColorStop(0, 'rgba(11, 100, 119, 0.4)'); // Primary color with opacity
            gradient.addColorStop(1, 'rgba(11, 100, 119, 0.0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($visitLabels),
                    datasets: [{
                        label: 'Volume Pasien',
                        data: @json($visitSeries),
                        borderColor: '#0B6477', // var(--simrs-primary)
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: '#0B6477',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        fill: true,
                        tension: 0.4 // Smooth curves
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 23, 42, 0.9)',
                            titleFont: { family: 'Plus Jakarta Sans', size: 13, weight: 'bold' },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 14, weight: 'bold' },
                            padding: 12,
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: 'rgba(226, 232, 240, 0.6)', drawBorder: false },
                            border: { dash: [4, 4] },
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '600' }, color: '#94A3B8', padding: 10 }
                        },
                        x: { 
                            grid: { display: false, drawBorder: false },
                            ticks: { font: { family: 'Plus Jakarta Sans', weight: '600' }, color: '#94A3B8', padding: 10 }
                        }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        }
    });
</script>
@endsection

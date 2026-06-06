@extends('layouts.app')

@section('title', 'Business Intelligence')
@section('page-title', 'Hospital Analytics Dashboard')
@section('page-subtitle', 'Visualisasi data performa dan indikator pelayanan rumah sakit')

@section('content')
<!-- KPI Row -->
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Master Pasien</div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-users fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">{{ number_format($stats['total_patients']) }}</h3>
                <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                    <i class="fa-solid fa-database text-primary opacity-50"></i> Terintegrasi Sistem
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Kunjungan Hari Ini</div>
                    <div class="bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-calendar-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">{{ number_format($stats['today_visits']) }}</h3>
                <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                    <i class="fa-solid fa-hospital-user text-info opacity-50"></i> Unit Terdaftar
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Pendapatan Bulanan</div>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-money-bill-trend-up fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">Rp {{ number_format($stats['monthly_revenue'] / 1000000, 1) }}M</h3>
                <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                    <i class="fa-solid fa-cash-register text-success opacity-50"></i> Realisasi Kasir
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Okupansi (BOR)</div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-bed-pulse fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-2">{{ number_format($stats['bor'], 1) }}%</h3>
                <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                    <i class="fa-solid fa-bed text-warning opacity-50"></i> Rawat Inap Efektif
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Revenue Trend Chart -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="p-4 border-bottom border-light bg-white rounded-top-4">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-line text-primary"></i> Tren Pendapatan Harian (7 Hari Terakhir)
                </h6>
            </div>
            <div class="card-body p-4">
                <div style="height: 320px; width: 100%;">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Visits by Unit -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <div class="p-4 border-bottom border-light bg-white rounded-top-4">
                <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
                    <i class="fa-solid fa-chart-pie text-info"></i> Volume Pasien per Unit
                </h6>
            </div>
            <div class="card-body p-4">
                <div style="height: 320px; width: 100%;">
                    <canvas id="unitVisitsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-4px); box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05) !important; }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Theme Colors
        const primaryColor = '#3b82f6';
        const infoColor = '#06b6d4';
        const textMuted = '#64748b';
        
        // Revenue Trend Chart
        const ctxRev = document.getElementById('revenueTrendChart');
        if(ctxRev) {
            const gradRev = ctxRev.getContext('2d').createLinearGradient(0, 0, 0, 320);
            gradRev.addColorStop(0, 'rgba(59, 130, 246, 0.2)');
            gradRev.addColorStop(1, 'rgba(59, 130, 246, 0)');
            
            new Chart(ctxRev, {
                type: 'line',
                data: {
                    labels: {!! json_encode($revenueTrend->pluck('date')) !!},
                    datasets: [{
                        label: 'Pendapatan (Rp)',
                        data: {!! json_encode($revenueTrend->pluck('total')) !!},
                        borderColor: primaryColor,
                        backgroundColor: gradRev,
                        borderWidth: 3,
                        pointBackgroundColor: '#ffffff',
                        pointBorderColor: primaryColor,
                        pointBorderWidth: 2,
                        pointRadius: 4,
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
                            titleFont: { family: 'Plus Jakarta Sans', size: 13 },
                            bodyFont: { family: 'Plus Jakarta Sans', size: 13 },
                            padding: 12, cornerRadius: 8, displayColors: false
                        }
                    },
                    scales: { 
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false }, ticks: { color: textMuted, font: { family: 'Plus Jakarta Sans' } } },
                        x: { grid: { display: false, drawBorder: false }, ticks: { color: textMuted, font: { family: 'Plus Jakarta Sans' } } }
                    }
                }
            });
        }

        // Unit Visits Chart
        const ctxUnit = document.getElementById('unitVisitsChart');
        if(ctxUnit) {
            new Chart(ctxUnit, {
                type: 'doughnut',
                data: {
                    labels: {!! json_encode($unitStats->pluck('department.nama_depart')) !!},
                    datasets: [{
                        data: {!! json_encode($unitStats->pluck('total')) !!},
                        backgroundColor: [primaryColor, infoColor, '#f59e0b', '#10b981', '#8b5cf6'],
                        borderWidth: 0,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { 
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20, color: textMuted, font: { family: 'Plus Jakarta Sans' } } },
                        tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8 }
                    }
                }
            });
        }
    });
</script>
@endsection

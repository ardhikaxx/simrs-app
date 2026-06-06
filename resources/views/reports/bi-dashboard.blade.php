@extends('layouts.app')

@section('title', 'Business Intelligence')
@section('page-title', 'Hospital Analytics Dashboard')
@section('page-subtitle', 'Visualisasi data performa dan indikator pelayanan rumah sakit')

@section('content')
<!-- KPI Row -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="simrs-card bg-primary text-white border-0 shadow-lg">
            <div class="simrs-card-body">
                <div class="small opacity-75 fw-bold text-uppercase mb-1">Total Database Pasien</div>
                <div class="h2 fw-800 mb-0">{{ number_format($stats['total_patients']) }}</div>
                <div class="small mt-2"><i class="fa-solid fa-user-check me-1"></i>Master Pasien Terintegrasi</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="simrs-card bg-info text-white border-0 shadow-lg">
            <div class="simrs-card-body">
                <div class="small opacity-75 fw-bold text-uppercase mb-1">Kunjungan Hari Ini</div>
                <div class="h2 fw-800 mb-0">{{ number_format($stats['today_visits']) }}</div>
                <div class="small mt-2"><i class="fa-solid fa-calendar-check me-1"></i>Kunjungan Unit Terdaftar</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="simrs-card bg-success text-white border-0 shadow-lg">
            <div class="simrs-card-body">
                <div class="small opacity-75 fw-bold text-uppercase mb-1">Pendapatan Bulan Ini</div>
                <div class="h2 fw-800 mb-0">Rp {{ number_format($stats['monthly_revenue'] / 1000000, 1) }}M</div>
                <div class="small mt-2"><i class="fa-solid fa-money-bill-trend-up me-1"></i>Realisasi Pembayaran Kasir</div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="simrs-card bg-secondary text-white border-0 shadow-lg">
            <div class="simrs-card-body">
                <div class="small opacity-75 fw-bold text-uppercase mb-1">BOR (Keterisian Bed)</div>
                <div class="h2 fw-800 mb-0">{{ number_format($stats['bor'], 1) }}%</div>
                <div class="small mt-2"><i class="fa-solid fa-bed me-1"></i>Rasio Rawat Inap Efektif</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Revenue Trend Chart -->
    <div class="col-lg-8">
        <div class="simrs-card h-100">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title text-simrs-primary"><i class="fa-solid fa-chart-line"></i>Tren Pendapatan Harian (7 Hari Terakhir)</div>
            </div>
            <div class="simrs-card-body">
                <div style="height: 300px;">
                    <canvas id="revenueTrendChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- Visits by Unit -->
    <div class="col-lg-4">
        <div class="simrs-card h-100">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title text-simrs-primary"><i class="fa-solid fa-chart-pie"></i>Volume Pasien per Unit</div>
            </div>
            <div class="simrs-card-body">
                <div style="height: 300px;">
                    <canvas id="unitVisitsChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Trend Chart
        new Chart(document.getElementById('revenueTrendChart'), {
            type: 'line',
            data: {
                labels: {!! json_encode($revenueTrend->pluck('date')) !!},
                datasets: [{
                    label: 'Pendapatan (IDR)',
                    data: {!! json_encode($revenueTrend->pluck('total')) !!},
                    borderColor: '#0B6477',
                    backgroundColor: 'rgba(11, 100, 119, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });

        // Unit Visits Chart
        new Chart(document.getElementById('unitVisitsChart'), {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($unitStats->pluck('department.nama_depart')) !!},
                datasets: [{
                    data: {!! json_encode($unitStats->pluck('total')) !!},
                    backgroundColor: ['#0B6477', '#14919B', '#E07B1F', '#2C3E7A', '#1A8754']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });
    });
</script>
@endsection

@extends('layouts.app')

@section('title', 'Manajemen Tempat Tidur')
@section('page-title', 'Bed Command Center')
@section('page-subtitle', 'Monitoring real-time kapasitas, okupansi, dan ketersediaan tempat tidur rawat inap')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Total Kapasitas</div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-hospital fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $stats['total'] }} <span class="fs-6 fw-medium text-muted">Bed</span></h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Bed Kosong</div>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-door-open fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-success mb-0">{{ $stats['available'] }} <span class="fs-6 fw-medium text-muted">Bed</span></h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Terisi Pasien</div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-injured fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-danger mb-0">{{ $stats['occupied'] }} <span class="fs-6 fw-medium text-muted">Bed</span></h3>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        @php($bor = $stats['total'] > 0 ? ($stats['occupied'] / $stats['total']) * 100 : 0)
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover bg-primary bg-gradient text-white position-relative overflow-hidden">
            <div class="card-body p-4 position-relative z-1">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-white-50 fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">BOR (Keterisian)</div>
                    <div class="rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-chart-line fs-3 text-white opacity-50"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-white mb-2">{{ number_format($bor, 1) }}%</h3>
                <div class="progress bg-white bg-opacity-25" style="height: 6px;">
                    <div class="progress-bar bg-white" style="width: {{ $bor }}%"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($departments as $dept)
<div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-building fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-1">{{ $dept->nama_depart }}</h5>
                <p class="small text-muted mb-0 fw-medium">Kapasitas Operasional: <span class="fw-bold">{{ $dept->beds->count() }}</span> Bed Terdaftar</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-2 rounded-pill fw-semibold small">
                {{ $dept->beds->where('status', 'available')->count() }} KOSONG
            </span>
            <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-2 rounded-pill fw-semibold small">
                {{ $dept->beds->where('status', 'occupied')->count() }} TERISI
            </span>
        </div>
    </div>
    <div class="p-4 bg-light bg-opacity-50">
        <div class="row g-3">
            @foreach($dept->beds as $bed)
                @php
                    $bedBg = match($bed->status) {
                        'occupied' => 'bg-danger bg-opacity-10 border-danger',
                        'available' => 'bg-success bg-opacity-10 border-success',
                        default => 'bg-secondary bg-opacity-10 border-secondary'
                    };
                    $bedText = match($bed->status) {
                        'occupied' => 'text-danger',
                        'available' => 'text-success',
                        default => 'text-secondary'
                    };
                @endphp
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="card border border-opacity-25 {{ $bedBg }} shadow-none rounded-4 h-100 text-center p-3 bed-card transition-hover">
                        <div class="small fw-bold mb-2 text-uppercase text-truncate text-muted" style="font-size: 0.7rem; letter-spacing: 0.5px;">{{ $bed->room_name }}</div>
                        <div class="h4 fw-bold mb-2 {{ $bedText }}">
                            <i class="fa-solid fa-bed me-1"></i>{{ $bed->bed_number }}
                        </div>
                        <div class="mb-3">
                            <span class="badge bg-white text-dark border px-2 py-1 rounded-pill shadow-sm" style="font-size: 0.65rem;">{{ strtoupper($bed->class) }}</span>
                        </div>
                        <div class="pt-2 mt-auto border-top border-dark border-opacity-10">
                            <span class="fw-bold small text-uppercase {{ $bedText }}" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                {{ $bed->status === 'occupied' ? 'TERISI' : ($bed->status === 'available' ? 'KOSONG' : 'OUT-OF-ORDER') }}
                            </span>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endforeach

<style>
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important; }
    .bed-card:hover { transform: translateY(-4px) scale(1.02); }
</style>
@endsection

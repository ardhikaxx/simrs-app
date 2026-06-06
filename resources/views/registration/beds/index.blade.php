@extends('layouts.app')

@section('title', 'Manajemen Tempat Tidur')
@section('page-title', 'Bed Command Center')
@section('page-subtitle', 'Monitoring real-time kapasitas, okupansi, dan ketersediaan tempat tidur rawat inap')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-teal-soft text-primary">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Total Kapasitas</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ $stats['total'] }} <small class="fs-6 opacity-50">BED</small></h3>
                </div>
            </div>
            <i class="fa-solid fa-hospital position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-emerald-soft text-success">
                    <i class="fa-solid fa-door-open"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Bed Kosong</div>
                    <h3 class="fw-800 mb-0 text-success">{{ $stats['available'] }} <small class="fs-6 opacity-50 text-dark">BED</small></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-rose-soft text-danger">
                    <i class="fa-solid fa-user-injured"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Terisi Pasien</div>
                    <h3 class="fw-800 mb-0 text-danger">{{ $stats['occupied'] }} <small class="fs-6 opacity-50 text-dark">BED</small></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 col-xl-3">
        @php($bor = $stats['total'] > 0 ? ($stats['occupied'] / $stats['total']) * 100 : 0)
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-primary bg-gradient text-white">
            <div class="d-flex align-items-center justify-content-between">
                <div>
                    <div class="text-white-50 fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">BOR (Keterisian)</div>
                    <h3 class="fw-800 mb-0">{{ number_format($bor, 1) }}%</h3>
                </div>
                <div class="h2 mb-0 opacity-25"><i class="fa-solid fa-chart-line"></i></div>
            </div>
            <div class="mt-3">
                <div class="progress bg-white bg-opacity-20" style="height: 6px; border-radius: 10px;">
                    <div class="progress-bar bg-white" style="width: {{ $bor }}%; border-radius: 10px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@foreach($departments as $dept)
<div class="card-premium border-0 bg-white overflow-hidden mb-5">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-hospital-user fs-5"></i>
            </div>
            <div>
                <h5 class="fw-800 text-slate mb-0">{{ $dept->nama_depart }}</h5>
                <p class="small text-muted mb-0 fw-medium">Kapasitas Operasional: {{ $dept->beds->count() }} Bed Terdaftar</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <span class="badge bg-emerald-soft text-success px-3 py-2 rounded-pill fw-800" style="font-size: 0.65rem;">
                {{ $dept->beds->where('status', 'available')->count() }} KOSONG
            </span>
            <span class="badge bg-rose-soft text-danger px-3 py-2 rounded-pill fw-800" style="font-size: 0.65rem;">
                {{ $dept->beds->where('status', 'occupied')->count() }} TERISI
            </span>
        </div>
    </div>
    <div class="p-4">
        <div class="row g-4">
            @foreach($dept->beds as $bed)
                <div class="col-6 col-md-4 col-lg-3 col-xl-2">
                    <div class="bed-card p-4 rounded-4 text-center border transition-bounce-hover {{ $bed->status === 'occupied' ? 'bg-rose-soft border-danger border-opacity-10' : ($bed->status === 'available' ? 'bg-emerald-soft border-success border-opacity-10' : 'bg-light border-slate border-opacity-10') }}">
                        <div class="small fw-800 mb-2 text-uppercase text-truncate text-slate opacity-75" style="font-size: 0.65rem;">{{ $bed->room_name }}</div>
                        <div class="h3 fw-900 mb-2 {{ $bed->status === 'occupied' ? 'text-danger' : ($bed->status === 'available' ? 'text-success' : 'text-slate') }}">
                            <i class="fa-solid fa-bed"></i> {{ $bed->bed_number }}
                        </div>
                        <div class="badge bg-white text-slate border fw-800 px-3 py-1 rounded-pill shadow-sm mb-3" style="font-size: 0.6rem;">{{ strtoupper($bed->class) }}</div>
                        
                        <div class="pt-2 border-top border-dark border-opacity-5">
                            <span class="fw-800 small text-uppercase {{ $bed->status === 'occupied' ? 'text-danger' : ($bed->status === 'available' ? 'text-success' : 'text-muted') }}" style="font-size: 0.65rem; letter-spacing: 0.5px;">
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
    .kpi-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bg-teal-soft { background: #F0FDFA; }
    .bg-rose-soft { background: #FFF1F2; }
    .bg-blue-soft { background: #EFF6FF; }
    .bg-emerald-soft { background: #ECFDF5; }
    
    .text-slate { color: #1E293B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: translateY(-5px); box-shadow: 0 15px 30px rgba(0,0,0,0.05); }
    
    .bed-card { 
        position: relative;
        cursor: pointer;
    }
</style>
@endsection

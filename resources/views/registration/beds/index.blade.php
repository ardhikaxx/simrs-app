@extends('layouts.app')

@section('title', 'Manajemen Tempat Tidur')
@section('page-title', 'Bed Management (Kamar & Bangsal)')
@section('page-subtitle', 'Monitoring real-time ketersediaan tempat tidur rawat inap')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="simrs-card border-0 shadow-sm bg-white overflow-hidden">
            <div class="simrs-card-body d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-primary text-white" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-bed"></i>
                </div>
                <div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider" style="font-size: 0.6rem;">Total Kapasitas</div>
                    <div class="h5 fw-800 text-simrs-gray-900 mb-0">{{ $stats['total'] }} Bed</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="simrs-card border-0 shadow-sm bg-white overflow-hidden border-start border-success border-4">
            <div class="simrs-card-body d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-success-subtle text-success" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-door-open"></i>
                </div>
                <div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider" style="font-size: 0.6rem;">Bed Kosong</div>
                    <div class="h5 fw-800 text-success mb-0">{{ $stats['available'] }} Bed</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="simrs-card border-0 shadow-sm bg-white overflow-hidden border-start border-danger border-4">
            <div class="simrs-card-body d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-danger-subtle text-danger" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-user-injured"></i>
                </div>
                <div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider" style="font-size: 0.6rem;">Terisi Pasien</div>
                    <div class="h5 fw-800 text-danger mb-0">{{ $stats['occupied'] }} Bed</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        @php($bor = $stats['total'] > 0 ? ($stats['occupied'] / $stats['total']) * 100 : 0)
        <div class="simrs-card border-0 shadow-sm bg-primary text-white overflow-hidden">
            <div class="simrs-card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="small fw-700 text-white-50 text-uppercase tracking-wider" style="font-size: 0.6rem;">BOR (Keterisian)</div>
                    <div class="h5 fw-800 mb-0">{{ number_format($bor, 1) }}%</div>
                </div>
                <div class="h2 mb-0 opacity-25"><i class="fa-solid fa-chart-pie"></i></div>
            </div>
        </div>
    </div>
</div>

@foreach($departments as $dept)
<div class="simrs-card mb-4">
    <div class="simrs-card-header bg-light">
        <div class="simrs-card-title">
            <i class="fa-solid fa-hospital text-simrs-primary"></i>
            <span>Unit: {{ $dept->nama_depart }} ({{ $dept->beds->count() }} Bed)</span>
        </div>
    </div>
    <div class="simrs-card-body">
        <div class="row g-3">
            @foreach($dept->beds as $bed)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="bed-item p-3 border rounded-3 text-center position-relative shadow-sm {{ $bed->status === 'occupied' ? 'bg-danger-subtle border-danger' : ($bed->status === 'available' ? 'bg-success-subtle border-success' : 'bg-light border-secondary') }}">
                        <div class="small fw-800 mb-1 text-uppercase text-truncate">{{ $bed->room_name }}</div>
                        <div class="h4 fw-800 mb-1 {{ $bed->status === 'occupied' ? 'text-danger' : ($bed->status === 'available' ? 'text-success' : 'text-muted') }}">
                            <i class="fa-solid fa-bed"></i> {{ $bed->bed_number }}
                        </div>
                        <div class="small fw-700 opacity-75" style="font-size: 0.65rem;">{{ $bed->class }}</div>
                        <div class="mt-2">
                            <span class="badge {{ $bed->status === 'occupied' ? 'bg-danger' : ($bed->status === 'available' ? 'bg-success' : 'bg-secondary') }} py-1 px-2" style="font-size: 0.55rem;">
                                {{ strtoupper($bed->status) }}
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
    .bed-item { transition: all 0.2s ease; cursor: pointer; }
    .bed-item:hover { transform: translateY(-3px); box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Laporan Morbiditas')
@section('page-title', 'Analisis Morbiditas Pasien')
@section('page-subtitle', 'Distribusi frekuensi diagnosis utama berdasarkan standarisasi ICD-10')

@section('content')
<div class="simrs-card mb-4">
    <div class="simrs-card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label-custom">Mulai Tanggal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-day text-muted small"></i></span>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Sampai Tanggal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-week text-muted small"></i></span>
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                </div>
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button class="btn btn-simrs-primary shadow-sm px-4">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Tampilkan Laporan
                </button>
                <a href="{{ route('laporan.export', 'morbiditas') }}" class="btn btn-simrs-outline shadow-sm px-4">
                    <i class="fa-solid fa-file-export me-2"></i>Ekspor CSV
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach($rows->take(3) as $row)
        <div class="col-md-4">
            <div class="simrs-card h-100 border-0 shadow-sm bg-white overflow-hidden">
                <div class="simrs-card-body d-flex align-items-center gap-3">
                    <div class="brand-icon shadow-none bg-{{ $loop->first ? 'primary' : 'info' }}-subtle text-{{ $loop->first ? 'primary' : 'info' }}" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-ranking-star"></i>
                    </div>
                    <div>
                        <div class="small fw-700 text-muted text-uppercase tracking-wider">Top #{{ $loop->iteration }} Diagnosis</div>
                        <div class="fw-800 text-simrs-gray-900 h6 mb-0">{{ $row->icd10_primer }}</div>
                        <div class="small text-muted text-truncate" style="max-width: 200px;">{{ $row->diagnosis_kerja }}</div>
                    </div>
                    <div class="ms-auto text-end">
                        <div class="h4 fw-800 text-simrs-primary mb-0">{{ number_format($row->total) }}</div>
                        <div class="small text-muted">Kasus</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="simrs-card shadow-sm border-0">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-chart-bar"></i>
            <span>Daftar Distribusi Penyakit (Morbiditas)</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4" style="width: 80px;">Rangking</th>
                    <th style="width: 120px;">Kode ICD-10</th>
                    <th>Deskripsi Diagnosa (Medis)</th>
                    <th class="pe-4 text-end">Total Frekuensi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($rows as $row)
                <tr>
                    <td class="ps-4">
                        <div class="user-avatar-sm" style="width: 28px; height: 28px; font-size: 0.7rem; background: var(--simrs-primary-pale); color: var(--simrs-primary);">
                            #{{ $loop->iteration + (($rows->currentPage() - 1) * $rows->perPage()) }}
                        </div>
                    </td>
                    <td>
                        <span class="text-mono fw-800 text-simrs-primary bg-light px-2 py-1 rounded border">{{ $row->icd10_primer }}</span>
                    </td>
                    <td>
                        <div class="fw-600 text-simrs-gray-800">{{ $row->diagnosis_kerja }}</div>
                        <div class="small text-muted">Sesuai standar input Rekam Medis Elektronik</div>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="fw-800 text-simrs-gray-900 h6 mb-0">{{ number_format($row->total) }}</div>
                        <div class="progress mt-1 ms-auto" style="height: 4px; width: 100px;">
                            <div class="progress-bar bg-primary" style="width: {{ min(($row->total / ($rows->first()->total ?: 1)) * 100, 100) }}%"></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <i class="fa-solid fa-chart-pie fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Data morbiditas tidak ditemukan untuk periode ini.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($rows->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $rows->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

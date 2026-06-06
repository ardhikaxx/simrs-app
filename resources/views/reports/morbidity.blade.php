@extends('layouts.app')

@section('title', 'Laporan Morbiditas')
@section('page-title', 'Analisis Morbiditas Pasien')
@section('page-subtitle', 'Distribusi frekuensi diagnosis utama berdasarkan standarisasi ICD-10')

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4 col-lg-3">
                <label class="form-label text-muted fw-semibold small mb-1">Mulai Tanggal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-day text-muted"></i></span>
                    <input type="date" name="from" value="{{ $from }}" class="form-control border-start-0 ps-0 shadow-none focus-ring-0">
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <label class="form-label text-muted fw-semibold small mb-1">Sampai Tanggal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-week text-muted"></i></span>
                    <input type="date" name="to" value="{{ $to }}" class="form-control border-start-0 ps-0 shadow-none focus-ring-0">
                </div>
            </div>
            <div class="col-md-4 col-lg-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-medium px-4 shadow-sm rounded-3">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Filter
                </button>
                <a href="{{ route('laporan.export', 'morbiditas') }}" class="btn btn-light border fw-medium px-4 shadow-sm rounded-3 text-muted hover-bg-gray">
                    <i class="fa-solid fa-file-export me-2"></i>Ekspor CSV
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    @foreach($rows->take(3) as $row)
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 transition-hover">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="bg-{{ $loop->first ? 'primary' : 'info' }} bg-opacity-10 text-{{ $loop->first ? 'primary' : 'info' }} rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-ranking-star fs-5"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="small fw-semibold text-muted text-uppercase mb-1" style="letter-spacing: 0.5px;">Top #{{ $loop->iteration }} Diagnosis</div>
                        <div class="fw-bold text-dark h5 mb-1 text-truncate">{{ $row->icd10_primer }}</div>
                        <div class="small text-muted text-truncate">{{ $row->diagnosis_kerja }}</div>
                    </div>
                    <div class="text-end ps-2">
                        <div class="h3 fw-bold text-{{ $loop->first ? 'primary' : 'info' }} mb-0">{{ number_format($row->total) }}</div>
                        <div class="small text-muted fw-medium">Kasus</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="p-4 border-bottom border-light bg-white">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-chart-bar text-primary"></i>
            Daftar Distribusi Penyakit (Morbiditas)
        </h6>
    </div>
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start" style="width: 100px;">Rank</th>
                    <th class="py-3" style="width: 150px;">Kode ICD-10</th>
                    <th class="py-3">Deskripsi Diagnosa (Medis)</th>
                    <th class="pe-4 py-3 text-end rounded-end" style="width: 250px;">Total Frekuensi</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($rows as $row)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center justify-content-center bg-light text-muted fw-bold rounded-circle" style="width: 32px; height: 32px; font-size: 0.8rem;">
                            {{ $loop->iteration + (($rows->currentPage() - 1) * $rows->perPage()) }}
                        </div>
                    </td>
                    <td class="py-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 font-monospace px-2 py-1 rounded-2">
                            {{ $row->icd10_primer }}
                        </span>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $row->diagnosis_kerja }}</div>
                        <div class="small text-muted">Sesuai standar input RME</div>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <div class="fw-bold text-dark mb-1">{{ number_format($row->total) }} Kasus</div>
                        <div class="progress ms-auto bg-light" style="height: 6px; width: 120px;">
                            <div class="progress-bar bg-primary rounded-pill" style="width: {{ min(($row->total / ($rows->first()->total ?: 1)) * 100, 100) }}%"></div>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-5">
                        <div class="d-inline-flex bg-light p-3 rounded-circle mb-3">
                            <i class="fa-solid fa-chart-pie fs-3 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Data Kosong</h6>
                        <div class="text-muted small">Data morbiditas tidak ditemukan untuk periode ini.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($rows->hasPages())
        <div class="p-3 border-top border-light bg-white rounded-bottom-4">
            {{ $rows->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa; }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { background-color: #f8f9fa !important; transform: translateY(-3px); }
    table .transition-hover:hover { transform: none; }
</style>
@endsection

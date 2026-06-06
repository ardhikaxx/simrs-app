@extends('layouts.app')

@section('title', 'Laporan Kunjungan')
@section('page-title', 'Analisis Kunjungan Pasien')
@section('page-subtitle', 'Rekapitulasi kunjungan berdasarkan unit pelayanan, jenis layanan, dan penjamin')

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4 col-lg-3">
                <label class="form-label text-muted fw-semibold small mb-1">Periode Awal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-day text-muted"></i></span>
                    <input type="date" name="from" value="{{ $from }}" class="form-control border-start-0 ps-0 shadow-none focus-ring-0">
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <label class="form-label text-muted fw-semibold small mb-1">Periode Akhir</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-week text-muted"></i></span>
                    <input type="date" name="to" value="{{ $to }}" class="form-control border-start-0 ps-0 shadow-none focus-ring-0">
                </div>
            </div>
            <div class="col-md-4 col-lg-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-medium px-4 shadow-sm rounded-3">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Filter
                </button>
                <a href="{{ route('laporan.export', 'kunjungan') }}" class="btn btn-light border fw-medium px-4 shadow-sm rounded-3 text-muted hover-bg-gray">
                    <i class="fa-solid fa-file-export me-2"></i>Ekspor CSV
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    @foreach($summary as $item)
        <div class="col-md-3">
            <div class="card h-100 border-0 shadow-sm rounded-4 transition-hover">
                <div class="card-body p-4 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-hospital-user fs-5"></i>
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="small fw-semibold text-muted text-uppercase text-truncate mb-1" style="letter-spacing: 0.5px;">{{ str_replace('_',' ', $item->jenis_kunjungan) }}</div>
                        <div class="h4 fw-bold text-dark mb-0">{{ number_format($item->total) }}</div>
                        <div class="small text-muted fw-medium mt-1" style="font-size: 0.7rem;">
                            <span class="badge bg-light text-secondary border px-2 py-1">{{ strtoupper($item->cara_bayar) }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="p-4 border-bottom border-light bg-white">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-list-check text-primary"></i>
            Daftar Riwayat Kunjungan Pasien
        </h6>
    </div>
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">Waktu Masuk</th>
                    <th class="py-3">No. Registrasi</th>
                    <th class="py-3">Informasi Pasien</th>
                    <th class="py-3">Unit & Dokter DPJP</th>
                    <th class="py-3">Jenis / Penjamin</th>
                    <th class="pe-4 py-3 text-center rounded-end">Status Akhir</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($rows as $encounter)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $encounter->waktu_masuk?->format('d/m/Y') }}</div>
                        <div class="small text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                    </td>
                    <td class="py-3">
                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 font-monospace px-2 py-1 rounded-2">
                            {{ $encounter->no_registrasi }}
                        </span>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-medium text-dark mb-1">{{ $encounter->department?->nama_depart }}</div>
                        <div class="small text-muted"><i class="fa-solid fa-user-doctor opacity-50 me-1"></i>{{ $encounter->doctor?->display_name ?? '-' }}</div>
                    </td>
                    <td class="py-3">
                        <div class="small fw-semibold text-dark mb-1">{{ str_replace('_',' ', ucfirst($encounter->jenis_kunjungan)) }}</div>
                        <span class="badge bg-light text-secondary border fw-medium px-2 py-1">
                            {{ strtoupper($encounter->cara_bayar) }}
                        </span>
                    </td>
                    <td class="pe-4 py-3 text-center">
                        @php
                            $status = strtolower($encounter->status_encounter);
                            $color = match($status) {
                                'selesai' => 'success',
                                'batal' => 'danger',
                                default => 'info'
                            };
                        @endphp
                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-1 fw-semibold">
                            {{ str_replace('_',' ',strtoupper($encounter->status_encounter)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="d-inline-flex bg-light p-3 rounded-circle mb-3">
                            <i class="fa-solid fa-calendar-xmark fs-3 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Data Kosong</h6>
                        <div class="text-muted small">Data kunjungan tidak ditemukan untuk periode ini.</div>
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

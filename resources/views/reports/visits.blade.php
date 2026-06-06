@extends('layouts.app')

@section('title', 'Laporan Kunjungan')
@section('page-title', 'Analisis Kunjungan Pasien')
@section('page-subtitle', 'Rekapitulasi kunjungan berdasarkan unit pelayanan, jenis layanan, dan penjamin')

@section('content')
<div class="simrs-card mb-4">
    <div class="simrs-card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label-custom">Periode Awal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-day text-muted small"></i></span>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Periode Akhir</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-week text-muted small"></i></span>
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                </div>
            </div>
            <div class="col-md-6 d-flex gap-2">
                <button class="btn btn-simrs-primary shadow-sm px-4">
                    <i class="fa-solid fa-magnifying-glass me-2"></i>Tampilkan Laporan
                </button>
                <a href="{{ route('laporan.export', 'kunjungan') }}" class="btn btn-simrs-outline shadow-sm px-4">
                    <i class="fa-solid fa-file-export me-2"></i>Ekspor CSV
                </a>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach($summary as $item)
        <div class="col-md-3">
            <div class="simrs-card h-100 border-0 shadow-sm bg-white overflow-hidden">
                <div class="simrs-card-body d-flex align-items-center gap-3">
                    <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-hospital-user"></i>
                    </div>
                    <div>
                        <div class="small fw-700 text-muted text-uppercase tracking-wider" style="font-size: 0.6rem;">{{ str_replace('_',' ', $item->jenis_kunjungan) }}</div>
                        <div class="h5 fw-800 text-simrs-gray-900 mb-0">{{ number_format($item->total) }}</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">{{ strtoupper($item->cara_bayar) }}</div>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="simrs-card shadow-sm border-0">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-list-check"></i>
            <span>Daftar Riwayat Kunjungan Pasien</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Waktu Masuk</th>
                    <th>No. Registrasi</th>
                    <th>Informasi Pasien</th>
                    <th>Unit & Dokter DPJP</th>
                    <th>Jenis / Penjamin</th>
                    <th class="pe-4 text-center">Status Akhir</th>
                </tr>
            </thead>
            <tbody>
            @forelse($rows as $encounter)
                <tr>
                    <td class="ps-4">
                        <div class="fw-600 text-simrs-gray-800">{{ $encounter->waktu_masuk?->format('d/m/Y') }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                    </td>
                    <td>
                        <span class="text-mono fw-800 text-simrs-primary small bg-light px-2 py-1 rounded border">{{ $encounter->no_registrasi }}</span>
                    </td>
                    <td>
                        <div class="fw-600 text-simrs-gray-900">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.7rem;">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td>
                        <div class="small fw-700 text-simrs-gray-800">{{ $encounter->department?->nama_depart }}</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">{{ $encounter->doctor?->display_name ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="small fw-600 mb-1">{{ str_replace('_',' ', ucfirst($encounter->jenis_kunjungan)) }}</div>
                        <span class="badge bg-light text-simrs-secondary border border-simrs-gray-200 px-2 py-0" style="font-size: 0.65rem;">
                            {{ strtoupper($encounter->cara_bayar) }}
                        </span>
                    </td>
                    <td class="pe-4 text-center">
                        <span class="badge-status status-{{ str_replace('_','-',$encounter->status_encounter) }} shadow-none py-1 px-3" style="font-size: 0.7rem;">
                            {{ str_replace('_',' ',strtoupper($encounter->status_encounter)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fa-solid fa-calendar-xmark fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Data kunjungan tidak ditemukan untuk periode ini.</div>
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

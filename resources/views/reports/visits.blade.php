@extends('layouts.app')

@section('title', 'Laporan Kunjungan')
@section('page-title', 'Laporan Kunjungan')
@section('page-subtitle', 'Rekap kunjungan berdasarkan periode, jenis layanan, dan penjamin')

@section('content')
<form method="GET" class="simrs-card">
    <div class="simrs-card-body d-flex flex-wrap gap-2 align-items-end">
        <div><label class="form-label-custom">Dari</label><input type="date" name="from" value="{{ $from }}" class="form-control"></div>
        <div><label class="form-label-custom">Sampai</label><input type="date" name="to" value="{{ $to }}" class="form-control"></div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="{{ route('laporan.export', 'kunjungan') }}" class="btn btn-simrs-outline"><i class="fa-solid fa-file-csv me-1"></i>Export CSV</a>
    </div>
</form>
<div class="row g-3 mb-3">
    @foreach($summary as $item)
        <div class="col-md-3">
            <div class="kpi-card">
                <div class="kpi-label">{{ str_replace('_',' ', $item->jenis_kunjungan) }} - {{ strtoupper($item->cara_bayar) }}</div>
                <div class="kpi-value">{{ number_format($item->total) }}</div>
            </div>
        </div>
    @endforeach
</div>
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Tanggal</th><th>No Registrasi</th><th>Pasien</th><th>Unit</th><th>Dokter</th><th>Jenis</th><th>Penjamin</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($rows as $encounter)
                <tr>
                    <td>{{ $encounter->waktu_masuk?->format('d/m/Y H:i') }}</td>
                    <td class="text-mono">{{ $encounter->no_registrasi }}</td>
                    <td>{{ $encounter->patient->nama_pasien }}</td>
                    <td>{{ $encounter->department?->nama_depart }}</td>
                    <td>{{ $encounter->doctor?->display_name ?? '-' }}</td>
                    <td>{{ str_replace('_',' ', ucfirst($encounter->jenis_kunjungan)) }}</td>
                    <td>{{ strtoupper($encounter->cara_bayar) }}</td>
                    <td><span class="badge-status status-{{ str_replace('_','-',$encounter->status_encounter) }}">{{ str_replace('_',' ',ucfirst($encounter->status_encounter)) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada kunjungan dalam periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $rows->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Antrian Pendaftaran')
@section('page-title', 'Antrian Pelayanan')
@section('page-subtitle', 'Kunjungan aktif dari pendaftaran sampai kasir')

@section('content')
<div class="page-header-bar">
    <div class="section-label">Total aktif: {{ $encounters->total() }}</div>
    <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn btn-simrs-primary"><i class="fa-solid fa-calendar-plus me-1"></i>Kunjungan Baru</a>
</div>
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Antrian</th><th>Pasien</th><th>Unit</th><th>Dokter</th><th>Masuk</th><th>Penjamin</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr>
                    <td><span class="text-mono fw-bold">{{ $encounter->no_antrian }}</span><div class="small text-muted text-mono">{{ $encounter->no_registrasi }}</div></td>
                    <td><strong>{{ $encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $encounter->patient->no_rkm_medis }}</div></td>
                    <td>{{ $encounter->department->nama_depart }}</td>
                    <td>{{ $encounter->doctor?->display_name ?? '-' }}</td>
                    <td>{{ $encounter->waktu_masuk?->format('d/m/Y H:i') }}</td>
                    <td>{{ strtoupper($encounter->cara_bayar) }}</td>
                    <td><span class="badge-status status-{{ str_replace('_','-',$encounter->status_antrian) }}">{{ str_replace('_',' ',ucfirst($encounter->status_antrian)) }}</span></td>
                    <td class="text-nowrap">
                        <a href="{{ route('keperawatan.asesmen.edit', $encounter) }}" class="btn btn-sm btn-simrs-outline">Asesmen</a>
                        <a href="{{ route('rekam-medis.edit', $encounter) }}" class="btn btn-sm btn-simrs-outline">RM</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada antrean aktif.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $encounters->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

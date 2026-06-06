@extends('layouts.app')

@section('title', 'Antrian Keperawatan')
@section('page-title', 'Antrian Asesmen Keperawatan')
@section('page-subtitle', 'Triase, tanda vital, dan asesmen awal pasien')

@section('content')
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Antrian</th><th>Pasien</th><th>Unit</th><th>DPJP</th><th>Tanda Vital</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr>
                    <td><span class="text-mono fw-bold">{{ $encounter->no_antrian }}</span><div class="small text-muted">{{ $encounter->waktu_masuk?->format('d/m H:i') }}</div></td>
                    <td><strong>{{ $encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $encounter->patient->no_rkm_medis }}</div></td>
                    <td>{{ $encounter->department->nama_depart }}</td>
                    <td>{{ $encounter->doctor?->display_name ?? '-' }}</td>
                    <td>
                        @if($encounter->nursingAssessment)
                            <span class="text-mono">{{ $encounter->nursingAssessment->tekanan_darah_sistolik }}/{{ $encounter->nursingAssessment->tekanan_darah_diastolik }}</span>
                            <div class="small text-muted">Triase {{ $encounter->nursingAssessment->triase }}</div>
                        @else
                            <span class="text-muted">Belum asesmen</span>
                        @endif
                    </td>
                    <td><span class="badge-status status-{{ str_replace('_','-',$encounter->status_antrian) }}">{{ str_replace('_',' ',ucfirst($encounter->status_antrian)) }}</span></td>
                    <td><a href="{{ route('keperawatan.asesmen.edit', $encounter) }}" class="btn btn-sm btn-simrs-primary"><i class="fa-solid fa-clipboard-check me-1"></i>Asesmen</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pasien dalam antrean keperawatan.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $encounters->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Antrian Rekam Medis')
@section('page-title', 'Antrian Pemeriksaan Dokter')
@section('page-subtitle', 'Input rekam medis elektronik, resep, lab, dan radiologi')

@section('content')
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Registrasi</th><th>Pasien</th><th>Unit</th><th>Asesmen</th><th>Rekam Medis</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr>
                    <td><span class="text-mono">{{ $encounter->no_registrasi }}</span><div class="small text-muted">{{ $encounter->waktu_masuk?->diffForHumans() }}</div></td>
                    <td><strong>{{ $encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $encounter->patient->age }} tahun - {{ $encounter->patient->no_rkm_medis }}</div></td>
                    <td>{{ $encounter->department->nama_depart }}</td>
                    <td>
                        @if($encounter->nursingAssessment)
                            <span class="badge-status status-selesai">Lengkap</span>
                        @else
                            <span class="badge-status status-menunggu">Belum</span>
                        @endif
                    </td>
                    <td>
                        @if($encounter->medicalRecord)
                            <span class="badge-status status-selesai">{{ $encounter->medicalRecord->icd10_primer }}</span>
                        @else
                            <span class="badge-status status-draft">Draft</span>
                        @endif
                    </td>
                    <td><span class="badge-status status-{{ str_replace('_','-',$encounter->status_antrian) }}">{{ str_replace('_',' ',ucfirst($encounter->status_antrian)) }}</span></td>
                    <td><a href="{{ route('rekam-medis.edit', $encounter) }}" class="btn btn-sm btn-simrs-primary"><i class="fa-solid fa-notes-medical me-1"></i>Periksa</a></td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Tidak ada pasien dalam antrean dokter.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $encounters->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

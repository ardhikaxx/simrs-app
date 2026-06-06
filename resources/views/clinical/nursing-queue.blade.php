@extends('layouts.app')

@section('title', 'Antrian Keperawatan')
@section('page-title', 'Antrian Asesmen Keperawatan')
@section('page-subtitle', 'Triase, tanda vital, dan asesmen awal pasien oleh perawat')

@section('content')
<div class="page-header-bar mb-3">
    <form class="d-flex gap-2 flex-grow-1" method="GET">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari No. Antrian, nama pasien, atau No. RM...">
        </div>
        <select name="unit" class="form-select shadow-sm" style="max-width: 200px;">
            <option value="">Semua Unit</option>
            <option value="IGD">IGD</option>
            <option value="POL">Poliklinik</option>
        </select>
        <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
    </form>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-user-nurse"></i>
            <span>Daftar Tunggu Pemeriksaan Perawat</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. Antrian</th>
                    <th>Informasi Pasien</th>
                    <th>Unit Pelayanan</th>
                    <th>Dokter DPJP</th>
                    <th class="text-center">Tanda Vital</th>
                    <th>Status Antrean</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-800 text-simrs-primary h5 mb-0">{{ $encounter->no_antrian }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                    </td>
                    <td>
                        <div class="fw-bold text-simrs-gray-900">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.75rem;">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td>
                        <div class="small fw-600 text-simrs-gray-800">{{ $encounter->department->nama_depart }}</div>
                    </td>
                    <td>
                        <div class="small fw-600 text-simrs-secondary">{{ $encounter->doctor?->display_name ?? '-' }}</div>
                    </td>
                    <td class="text-center">
                        @if($encounter->nursingAssessment)
                            <div class="small fw-800 text-simrs-primary mb-1">{{ $encounter->nursingAssessment->tekanan_darah_sistolik }}/{{ $encounter->nursingAssessment->tekanan_darah_diastolik }}</div>
                            <span class="badge-status status-{{ $encounter->nursingAssessment->triase }} px-2 py-0" style="font-size: 0.65rem;">
                                TRIASE {{ strtoupper($encounter->nursingAssessment->triase) }}
                            </span>
                        @else
                            <span class="text-muted opacity-50 small italic">Belum Asesmen</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status status-{{ str_replace('_','-',$encounter->status_antrian) }} shadow-none">
                            {{ str_replace('_',' ',ucfirst($encounter->status_antrian)) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('keperawatan.asesmen.edit', $encounter) }}" class="btn btn-sm btn-simrs-primary shadow-sm px-3">
                            <i class="fa-solid fa-clipboard-check me-1"></i>Asesmen
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fa-solid fa-notes-medical fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Tidak ada pasien dalam daftar tunggu keperawatan saat ini.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($encounters->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $encounters->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

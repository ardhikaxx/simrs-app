@extends('layouts.app')

@section('title', 'Antrian Keperawatan')
@section('page-title', 'Antrean Asesmen Keperawatan')
@section('page-subtitle', 'Triase, tanda vital, dan asesmen awal pasien oleh perawat')

@section('content')
<!-- Filter & Action Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <form class="row g-3 align-items-center" method="GET">
            <div class="col-md-5">
                <div class="input-group bg-light rounded-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0 py-2" placeholder="Cari No. Antrean, nama pasien, atau No. RM...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="unit" class="form-select bg-light border-light shadow-none focus-ring-0 fw-medium py-2 rounded-3 text-muted">
                    <option value="">Semua Unit</option>
                    <option value="IGD" @selected(request('unit') === 'IGD')>IGD</option>
                    <option value="POL" @selected(request('unit') === 'POL')>Poliklinik</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-3 px-4 py-2 w-100 transition-hover">
                    <i class="fa-solid fa-filter me-2"></i>Filter Data
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-user-nurse fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Daftar Tunggu Pemeriksaan Perawat</h5>
                <p class="text-muted small mb-0 fw-medium">Total {{ $encounters->total() }} pasien menunggu</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Antrean</th>
                    <th class="py-3">Informasi Pasien</th>
                    <th class="py-3">Unit Pelayanan</th>
                    <th class="py-3">Dokter DPJP</th>
                    <th class="py-3 text-center">Tanda Vital</th>
                    <th class="py-3 text-center">Status Antrean</th>
                    <th class="pe-4 py-3 text-end rounded-end">Aksi Medis</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($encounters as $encounter)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="text-primary font-monospace fw-bold bg-primary bg-opacity-10 px-2 py-1 rounded-2 border border-primary border-opacity-10 d-inline-block mb-1 fs-6">{{ $encounter->no_antrian }}</div>
                        <div class="small text-muted fw-medium d-flex align-items-center gap-1" style="font-size: 0.7rem;"><i class="fa-regular fa-clock opacity-50"></i>{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-dark mb-1">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark">{{ $encounter->department->nama_depart }}</div>
                    </td>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user-doctor text-muted opacity-50"></i>
                            <span class="fw-medium text-dark">{{ $encounter->doctor?->display_name ?? 'Belum Ditentukan' }}</span>
                        </div>
                    </td>
                    <td class="text-center py-3">
                        @if($encounter->nursingAssessment)
                            <div class="fw-bold text-primary font-monospace mb-1">{{ $encounter->nursingAssessment->tekanan_darah_sistolik }}/{{ $encounter->nursingAssessment->tekanan_darah_diastolik }}</div>
                            @php
                                $triaseBg = match($encounter->nursingAssessment->triase) {
                                    'merah' => 'bg-danger text-danger border-danger border-opacity-25',
                                    'kuning' => 'bg-warning text-dark border-warning border-opacity-25',
                                    'hijau' => 'bg-success text-success border-success border-opacity-25',
                                    'hitam' => 'bg-dark text-dark border-dark border-opacity-25',
                                    default => 'bg-secondary text-secondary'
                                };
                            @endphp
                            <span class="badge {{ $triaseBg }} bg-opacity-10 border px-2 py-1 rounded-pill" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                TRIASE {{ strtoupper($encounter->nursingAssessment->triase) }}
                            </span>
                        @else
                            <span class="text-muted opacity-50 fst-italic small">Belum Asesmen</span>
                        @endif
                    </td>
                    <td class="text-center py-3">
                        @php
                            $badgeStatusClass = match($encounter->status_antrian) {
                                'menunggu' => 'bg-warning text-dark border-warning border-opacity-25',
                                'asesmen_perawat' => 'bg-info text-info border-info border-opacity-25',
                                'pemeriksaan_dokter' => 'bg-primary text-primary border-primary border-opacity-25',
                                'diperiksa', 'selesai' => 'bg-success text-success border-success border-opacity-25',
                                default => 'bg-secondary text-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeStatusClass }} bg-opacity-10 border rounded-pill px-3 py-1 fw-bold shadow-sm" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ str_replace('_',' ',strtoupper($encounter->status_antrian)) }}
                        </span>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('keperawatan.asesmen.edit', $encounter) }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-4 py-2 fw-semibold transition-hover">
                            <i class="fa-solid fa-clipboard-check me-1"></i> Asesmen
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-notes-medical fs-2 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Antrean Kosong</h6>
                        <p class="text-muted small mb-0">Tidak ada pasien dalam daftar tunggu keperawatan saat ini.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($encounters->hasPages())
        <div class="p-4 border-top border-light bg-white rounded-bottom-4 d-flex justify-content-center">
            {{ $encounters->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
</style>
@endsection
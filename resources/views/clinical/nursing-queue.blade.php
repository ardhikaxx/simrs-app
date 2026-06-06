@extends('layouts.app')

@section('title', 'Antrian Keperawatan')
@section('page-title', 'Antrian Asesmen Keperawatan')
@section('page-subtitle', 'Triase, tanda vital, dan asesmen awal pasien oleh perawat')

@section('content')
<!-- Filter & Action Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-3">
        <form class="row g-3 align-items-center" method="GET">
            <div class="col-md-5">
                <div class="input-group input-group-lg shadow-none">
                    <span class="input-group-text bg-light border-end-0 text-muted px-4 rounded-start-pill"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-light border-start-0 ps-0 shadow-none fs-6" placeholder="Cari No. Antrian, nama pasien, atau No. RM...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="unit" class="form-select form-select-lg bg-light shadow-none fs-6 rounded-pill border-light-subtle">
                    <option value="">Semua Unit</option>
                    <option value="IGD">IGD</option>
                    <option value="POL">Poliklinik</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-lg fw-bold shadow-sm rounded-pill px-4 fs-6 w-100">
                    <i class="fa-solid fa-filter me-2"></i>Filter Data
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-user-nurse fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Daftar Tunggu Pemeriksaan Perawat</h5>
                <p class="text-muted small mb-0">Total {{ $encounters->total() }} pasien menunggu</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
            <thead class="bg-light bg-opacity-75">
                <tr class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    <th class="border-0 px-4 py-3">No. Antrian</th>
                    <th class="border-0 py-3">Informasi Pasien</th>
                    <th class="border-0 py-3">Unit Pelayanan</th>
                    <th class="border-0 py-3">Dokter DPJP</th>
                    <th class="border-0 py-3 text-center">Tanda Vital</th>
                    <th class="border-0 py-3 text-center">Status Antrean</th>
                    <th class="border-0 px-4 py-3 text-end">Aksi Medis</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
            @forelse($encounters as $encounter)
                <tr>
                    <td class="px-4 py-3">
                        <div class="text-primary font-monospace fw-bold bg-primary bg-opacity-10 px-2 py-1 rounded border border-primary border-opacity-25 d-inline-block mb-1 fs-5">{{ $encounter->no_antrian }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;"><i class="fa-regular fa-clock me-1 opacity-50"></i>{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark mb-1">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark">{{ $encounter->department->nama_depart }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-user-doctor text-muted opacity-50"></i>
                            <span class="fw-medium">{{ $encounter->doctor?->display_name ?? 'Belum Ditentukan' }}</span>
                        </div>
                    </td>
                    <td class="text-center">
                        @if($encounter->nursingAssessment)
                            <div class="fw-bold text-primary font-monospace mb-1">{{ $encounter->nursingAssessment->tekanan_darah_sistolik }}/{{ $encounter->nursingAssessment->tekanan_darah_diastolik }}</div>
                            @php
                                $triaseBg = match($encounter->nursingAssessment->triase) {
                                    'merah' => 'bg-danger text-white',
                                    'kuning' => 'bg-warning text-dark',
                                    'hijau' => 'bg-success text-white',
                                    'hitam' => 'bg-dark text-white',
                                    default => 'bg-secondary text-white'
                                };
                            @endphp
                            <span class="badge {{ $triaseBg }} px-2 py-1 rounded-pill" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                TRIASE {{ strtoupper($encounter->nursingAssessment->triase) }}
                            </span>
                        @else
                            <span class="text-muted opacity-50 fst-italic small">Belum Asesmen</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @php
                            $badgeStatusClass = match($encounter->status_antrian) {
                                'menunggu' => 'bg-warning text-dark',
                                'asesmen_perawat' => 'bg-info text-white',
                                'pemeriksaan_dokter' => 'bg-primary text-white',
                                'diperiksa', 'selesai' => 'bg-success text-white',
                                default => 'bg-secondary text-white'
                            };
                        @endphp
                        <span class="badge {{ $badgeStatusClass }} rounded-pill px-3 py-2 fw-bold shadow-sm" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ str_replace('_',' ',strtoupper($encounter->status_antrian)) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-end">
                        <a href="{{ route('keperawatan.asesmen.edit', $encounter) }}" class="btn btn-sm btn-primary bg-gradient shadow-sm rounded-3 px-3 py-2 fw-semibold transition-hover">
                            <i class="fa-solid fa-clipboard-check me-1"></i> Asesmen
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-notes-medical fs-1 text-muted opacity-50"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Antrean Kosong</h5>
                        <p class="text-muted small">Tidak ada pasien dalam daftar tunggu keperawatan saat ini.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($encounters->hasPages())
        <div class="card-footer bg-white border-top p-4 d-flex justify-content-center">
            {{ $encounters->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important; }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Database Pasien')
@section('page-title', 'Master Data Pasien')
@section('page-subtitle', 'Manajemen identitas pusat, riwayat nomor RM, dan validasi data kependudukan')

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <div class="row g-4 align-items-center">
            <div class="col-lg-6">
                <form method="GET">
                    <div class="input-group bg-light rounded-3 px-3 py-1">
                        <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0" placeholder="Cari berdasarkan Nama, No. RM, NIK, atau BPJS Pasien...">
                    </div>
                </form>
            </div>
            <div class="col-lg-6 text-lg-end">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn btn-light border bg-white px-4 py-2 fw-medium shadow-sm rounded-3 text-muted hover-bg-gray transition-hover">
                        <i class="fa-solid fa-calendar-plus me-2"></i>Registrasi Kunjungan
                    </a>
                    <a href="{{ route('pendaftaran.pasien.create') }}" class="btn btn-primary px-4 py-2 fw-medium shadow-sm rounded-3 transition-hover">
                        <i class="fa-solid fa-user-plus me-2"></i>Tambah Pasien Baru
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="p-4 border-bottom border-light bg-white d-flex align-items-center gap-3">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
            <i class="fa-solid fa-users-viewfinder fs-5"></i>
        </div>
        <div>
            <h6 class="fw-bold text-dark mb-0">Indeks Master Pasien</h6>
            <p class="small text-muted mb-0 fw-medium">Total Terverifikasi: {{ number_format($patients->total()) }} Record</p>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Rekam Medis</th>
                    <th class="py-3">Informasi Demografi</th>
                    <th class="py-3">Legalitas (NIK/BPJS)</th>
                    <th class="py-3 text-center">Profil Klinis</th>
                    <th class="py-3">Kontak & Domisili</th>
                    <th class="pe-4 py-3 text-end rounded-end">Manajemen</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($patients as $patient)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-primary h6 mb-1">{{ $patient->no_rkm_medis }}</div>
                        <div class="small text-muted fw-semibold" style="font-size: 0.65rem;">REG: {{ $patient->created_at?->format('d/m/Y') }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $patient->nama_pasien }}</div>
                        <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                            <i class="fa-solid fa-cake-candles opacity-50" style="font-size: 0.7rem;"></i>
                            {{ $patient->tempat_lahir }}, {{ $patient->tgl_lahir?->format('d M Y') }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="small fw-semibold text-dark mb-1 font-monospace">NIK: {{ $patient->nik }}</div>
                        <div class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 fw-semibold" style="font-size: 0.65rem;">
                            <i class="fa-solid fa-shield-halved me-1"></i>{{ $patient->no_bpjs ?: 'NON-PENJAMIN' }}
                        </div>
                    </td>
                    <td class="text-center py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $patient->age }} Th</div>
                        <div class="badge bg-light text-secondary border fw-medium" style="font-size: 0.65rem;">
                            {{ $patient->jenis_kelamin === 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="small fw-medium text-dark mb-1"><i class="fa-solid fa-phone me-2 opacity-50"></i>{{ $patient->no_telp_pasien ?: '-' }}</div>
                        <div class="small text-muted fw-medium text-truncate" style="max-width: 150px;" title="{{ $patient->kota }}">
                            <i class="fa-solid fa-location-dot me-2 opacity-50"></i>{{ $patient->kota }}
                        </div>
                    </td>
                    <td class="pe-4 text-end py-3">
                        <a href="{{ route('pendaftaran.pasien.show', $patient) }}" class="btn btn-light border btn-sm px-3 fw-medium rounded-pill shadow-sm text-primary transition-hover">
                            Detail <i class="fa-solid fa-arrow-right ms-1 small"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-user-slash fs-2 text-muted opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Data Tidak Ditemukan</h6>
                            <p class="small fw-medium mb-0">Pasien tidak terdaftar dalam kriteria pencarian.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($patients->hasPages())
        <div class="p-4 border-top border-light bg-white rounded-bottom-4">
            {{ $patients->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa; }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { background-color: #f8f9fa !important; transform: translateY(-2px); }
    table .transition-hover:hover { transform: none; }
</style>
@endsection

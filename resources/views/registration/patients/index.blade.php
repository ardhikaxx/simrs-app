@extends('layouts.app')

@section('title', 'Database Pasien')
@section('page-title', 'Master Data Pasien')
@section('page-subtitle', 'Manajemen identitas pusat, riwayat nomor RM, dan validasi data kependudukan')

@section('content')
<div class="card-premium border-0 bg-white p-4 mb-5">
    <div class="row g-4 align-items-center">
        <div class="col-lg-6">
            <form method="GET">
                <div class="header-search w-100 max-w-none bg-light border-0 px-3">
                    <i class="fa-solid fa-magnifying-glass opacity-40"></i>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari berdasarkan Nama, No. RM, NIK, atau BPJS Pasien...">
                </div>
            </form>
        </div>
        <div class="col-lg-6 text-lg-end">
            <div class="d-flex gap-3 justify-content-lg-end">
                <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn-premium btn-light border bg-white px-4">
                    <i class="fa-solid fa-calendar-plus opacity-50"></i>REGISTRASI KUNJUNGAN
                </a>
                <a href="{{ route('pendaftaran.pasien.create') }}" class="btn-premium btn-primary px-4">
                    <i class="fa-solid fa-user-plus"></i>TAMBAH PASIEN BARU
                </a>
            </div>
        </div>
    </div>
</div>

<div class="card-premium border-0 bg-white overflow-hidden">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-users-viewfinder fs-5"></i>
            </div>
            <div>
                <h5 class="fw-800 text-slate mb-0">Indeks Master Pasien</h5>
                <p class="small text-muted mb-0 fw-medium">Total Terverifikasi: {{ number_format($patients->total()) }} Record</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                    <th class="ps-4 border-0 py-3">No. Rekam Medis</th>
                    <th class="border-0 py-3">Informasi Demografi</th>
                    <th class="border-0 py-3">Legalitas (NIK/BPJS)</th>
                    <th class="border-0 py-3 text-center">Profil Klinis</th>
                    <th class="border-0 py-3">Kontak & Domisili</th>
                    <th class="pe-4 border-0 py-3 text-end">Manajemen</th>
                </tr>
            </thead>
            <tbody>
            @forelse($patients as $patient)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="text-mono fw-800 text-primary h6 mb-1">{{ $patient->no_rkm_medis }}</div>
                        <div class="small text-muted fw-bold" style="font-size: 0.65rem;">REG: {{ $patient->created_at?->format('d/m/Y') }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-800 text-slate mb-1">{{ $patient->nama_pasien }}</div>
                        <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                            <i class="fa-solid fa-cake-candles opacity-50" style="font-size: 0.7rem;"></i>
                            {{ $patient->tempat_lahir }}, {{ $patient->tgl_lahir?->format('d M Y') }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="small fw-800 text-slate mb-1 font-monospace">NIK: {{ $patient->nik }}</div>
                        <div class="badge bg-blue bg-opacity-10 text-blue border border-blue border-opacity-10 fw-800" style="font-size: 0.6rem;">
                            <i class="fa-solid fa-shield-halved me-1"></i>{{ $patient->no_bpjs ?: 'NON-PENJAMIN' }}
                        </div>
                    </td>
                    <td class="text-center py-3">
                        <div class="fw-800 text-slate mb-1">{{ $patient->age }} Th</div>
                        <div class="badge bg-light text-slate border fw-800" style="font-size: 0.6rem;">
                            {{ $patient->jenis_kelamin === 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="small fw-800 text-slate mb-1"><i class="fa-solid fa-phone me-1 opacity-50"></i>{{ $patient->no_telp_pasien ?: '-' }}</div>
                        <div class="small text-muted fw-medium text-truncate" style="max-width: 150px;" title="{{ $patient->kota }}">
                            <i class="fa-solid fa-location-dot me-1 opacity-50"></i>{{ $patient->kota }}
                        </div>
                    </td>
                    <td class="pe-4 text-end py-3">
                        <a href="{{ route('pendaftaran.pasien.show', $patient) }}" class="btn btn-white border btn-sm px-3 fw-800 rounded-3 shadow-sm text-primary transition-bounce-hover">
                            <i class="fa-solid fa-folder-open me-1"></i>DETAIL
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-user-slash fs-1 opacity-25"></i>
                            </div>
                            <h6 class="fw-800 text-slate">Data Tidak Ditemukan</h6>
                            <p class="small fw-medium">Pasien tidak terdaftar dalam kriteria pencarian.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($patients->hasPages())
        <div class="p-4 border-top bg-white">
            {{ $patients->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .text-slate { color: #1E293B; }
    .text-blue { color: #3B82F6; }
    .header-search { background: #F1F5F9; border-radius: 12px; padding: 0.6rem 1rem; display: flex; align-items: center; gap: 0.75rem; }
    .header-search input { border: none; background: transparent; outline: none; width: 100%; font-size: 0.9rem; font-weight: 600; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.05); }
    .transition-hover:hover { background-color: #F8FAFC !important; }
</style>
@endsection

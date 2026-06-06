@extends('layouts.app')

@section('title', 'Master Pasien')
@section('page-title', 'Database Pasien')
@section('page-subtitle', 'Manajemen identitas, nomor RM, dan riwayat rekam medis pasien')

@section('content')
<div class="page-header-bar mb-3">
    <form class="d-flex gap-2 flex-grow-1" method="GET">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari berdasarkan nama, No. RM, NIK, atau nomor BPJS...">
        </div>
        <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
    </form>
    <div class="page-header-actions d-flex gap-2">
        <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn btn-simrs-outline shadow-sm"><i class="fa-solid fa-calendar-plus me-2"></i>Registrasi Kunjungan</a>
        <a href="{{ route('pendaftaran.pasien.create') }}" class="btn btn-simrs-primary shadow-sm"><i class="fa-solid fa-user-plus me-2"></i>Pasien Baru</a>
    </div>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white border-bottom-0">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-users-viewfinder"></i>
            <span>Daftar Master Pasien Terdaftar</span>
        </div>
        <div class="small text-muted fw-normal">Total: {{ $patients->total() }} Record Pasien</div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. Rekam Medis</th>
                    <th>Nama & Identitas Klinis</th>
                    <th>NIK / No. BPJS</th>
                    <th>Usia / JK</th>
                    <th>Kontak & Domisili</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-800 text-simrs-primary h6 mb-0">{{ $patient->no_rkm_medis }}</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">Terdaftar: {{ $patient->created_at?->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-simrs-gray-900">{{ $patient->nama_pasien }}</div>
                        <div class="small text-muted">
                            <i class="fa-solid fa-cake-candles me-1 opacity-50"></i>{{ $patient->tempat_lahir }}, {{ $patient->tgl_lahir?->format('d/m/Y') }}
                        </div>
                    </td>
                    <td>
                        <div class="text-mono small fw-600 mb-1">
                            <i class="fa-solid fa-id-card me-1 opacity-50"></i>{{ $patient->nik }}
                        </div>
                        <span class="badge bg-light text-simrs-secondary border border-simrs-gray-200 px-2 py-0" style="font-size: 0.65rem;">
                            <i class="fa-solid fa-shield-halved me-1 opacity-50"></i>{{ $patient->no_bpjs ?: 'NON-BPJS' }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-700 text-simrs-gray-800">{{ $patient->age }} Th</div>
                        <div class="small text-muted">{{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</div>
                    </td>
                    <td>
                        <div class="small fw-600 mb-1"><i class="fa-solid fa-phone me-1 opacity-50"></i>{{ $patient->no_telp_pasien ?: '-' }}</div>
                        <div class="small text-muted text-truncate" style="max-width: 150px;" title="{{ $patient->kota }}"><i class="fa-solid fa-location-dot me-1 opacity-50"></i>{{ $patient->kota }}</div>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('pendaftaran.pasien.show', $patient) }}" class="btn btn-sm btn-simrs-outline shadow-sm px-3 py-1">
                            <i class="fa-solid fa-folder-open me-1"></i>Detail Profil
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fa-solid fa-user-slash fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Data pasien tidak ditemukan dengan kriteria pencarian tersebut.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($patients->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $patients->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

@extends('layouts.app')

@section('title', 'Antrian Pendaftaran')
@section('page-title', 'Dashboard Antrean Pelayanan')
@section('page-subtitle', 'Monitoring real-time status kunjungan pasien dari pendaftaran hingga kasir')

@section('content')
<div class="page-header-bar mb-3">
    <div class="d-flex align-items-center gap-3">
        <div class="kpi-card py-2 px-3 shadow-none border bg-white d-flex align-items-center gap-3">
            <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 32px; height: 32px; font-size: 0.8rem;">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <div class="small text-muted fw-700 text-uppercase" style="font-size: 0.6rem;">Kunjungan Aktif</div>
                <div class="fw-800 text-simrs-gray-900">{{ $encounters->total() }}</div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('pendaftaran.pasien.index') }}" class="btn btn-simrs-outline shadow-sm">
            <i class="fa-solid fa-users-viewfinder me-2"></i>Master Pasien
        </a>
        <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn btn-simrs-primary shadow-sm">
            <i class="fa-solid fa-calendar-plus me-2"></i>Registrasi Baru
        </a>
    </div>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white border-bottom-0">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-list-ol"></i>
            <span>Daftar Antrean Pasien Berjalan</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. Antrean / Reg</th>
                    <th>Informasi Pasien</th>
                    <th>Unit Pelayanan</th>
                    <th>Dokter DPJP</th>
                    <th>Waktu Masuk</th>
                    <th>Penjamin</th>
                    <th class="text-center">Status Alur</th>
                    <th class="pe-4 text-end">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-800 text-simrs-primary h5 mb-0">{{ $encounter->no_antrian }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.65rem;">{{ $encounter->no_registrasi }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-simrs-gray-900">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.75rem;">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td>
                        <div class="small fw-700 text-simrs-gray-800">{{ $encounter->department->nama_depart }}</div>
                        <div class="small text-muted text-uppercase" style="font-size: 0.65rem;">{{ str_replace('_', ' ', $encounter->jenis_kunjungan) }}</div>
                    </td>
                    <td>
                        <div class="small fw-600 text-simrs-secondary">{{ $encounter->doctor?->display_name ?? '-' }}</div>
                    </td>
                    <td>
                        <div class="small fw-600">{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $encounter->waktu_masuk?->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-simrs-secondary border border-simrs-gray-200 px-2 py-0" style="font-size: 0.65rem;">
                            {{ strtoupper($encounter->cara_bayar) }}
                        </span>
                    </td>
                    <td class="text-center">
                        <span class="badge-status status-{{ str_replace('_','-',$encounter->status_antrian) }} shadow-none py-1 px-3" style="font-size: 0.7rem;">
                            {{ str_replace('_',' ',strtoupper($encounter->status_antrian)) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="dropdown">
                            <button class="btn btn-sm btn-simrs-outline shadow-none border-0 p-1" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical fs-5"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                <li><a class="dropdown-item py-2 small fw-600" href="{{ route('keperawatan.asesmen.edit', $encounter) }}"><i class="fa-solid fa-user-nurse me-2 text-muted"></i>Input Asesmen</a></li>
                                <li><a class="dropdown-item py-2 small fw-600" href="{{ route('rekam-medis.edit', $encounter) }}"><i class="fa-solid fa-stethoscope me-2 text-muted"></i>Input RME</a></li>
                                <li><hr class="dropdown-divider opacity-5"></li>
                                <li><a class="dropdown-item py-2 small fw-600" href="{{ route('pendaftaran.pasien.show', $encounter->patient) }}"><i class="fa-solid fa-folder-open me-2 text-muted"></i>Lihat Profil Pasien</a></li>
                                <li><a class="dropdown-item py-2 small fw-600 text-danger" href="#"><i class="fa-solid fa-calendar-xmark me-2"></i>Batalkan Kunjungan</a></li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="fa-solid fa-clipboard-list fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Tidak ada antrean pelayanan yang aktif saat ini.</div>
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

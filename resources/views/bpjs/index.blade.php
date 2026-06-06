@extends('layouts.app')

@section('title', 'BPJS Kesehatan')
@section('page-title', 'Integrasi BPJS VClaim')
@section('page-subtitle', 'Manajemen Surat Eligibilitas Peserta (SEP) dan Pengajuan e-Claim')

@section('content')
<div class="row g-4 mb-4">
    <!-- Cek Kepesertaan -->
    <div class="col-lg-4">
        <div class="simrs-card h-100">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title text-simrs-primary">
                    <i class="fa-solid fa-id-card-clip"></i>
                    <span>Verifikasi Kepesertaan</span>
                </div>
            </div>
            <div class="simrs-card-body">
                <form method="GET">
                    <label class="form-label-custom">Nomor Kartu JKN / NIK</label>
                    <div class="input-group shadow-sm">
                        <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-address-card text-muted"></i></span>
                        <input name="no_kartu" value="{{ request('no_kartu') }}" class="form-control border-start-0 text-mono fw-bold" placeholder="0001961000..." style="letter-spacing: 1px;">
                        <button class="btn btn-simrs-primary px-3"><i class="fa-solid fa-magnifying-glass"></i></button>
                    </div>
                </form>

                @if($participant)
                    <div class="mt-4 p-3 rounded-3 border bg-light">
                        <div class="d-flex align-items-center gap-3 mb-3">
                            <div class="brand-icon shadow-none bg-success-subtle text-success" style="width: 40px; height: 40px;">
                                <i class="fa-solid fa-user-check"></i>
                            </div>
                            <div>
                                <div class="small text-muted fw-bold text-uppercase tracking-wider">Status Peserta</div>
                                <div class="fw-800 h5 mb-0 text-success">{{ $participant['response']['statusPeserta'] }}</div>
                            </div>
                        </div>
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="p-2 rounded bg-white border">
                                    <div class="small text-muted mb-1">Kelas Hak</div>
                                    <div class="small fw-800 text-dark">{{ $participant['response']['hakKelas'] }}</div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="p-2 rounded bg-white border">
                                    <div class="small text-muted mb-1">Jenis Peserta</div>
                                    <div class="small fw-800 text-dark">{{ $participant['response']['jenisPeserta'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 text-center py-3 opacity-50">
                        <i class="fa-solid fa-fingerprint fs-1 mb-2 d-block text-muted"></i>
                        <div class="small text-muted">Masukkan nomor kartu untuk verifikasi bridging VClaim.</div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi Mode & Statistik -->
    <div class="col-lg-8">
        <div class="row g-3 h-100">
            <div class="col-md-12">
                <div class="alert-medical alert-medical-info shadow-sm mb-0 h-100">
                    <div>
                        <div class="brand-icon shadow-none bg-info-subtle text-info mb-3" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-plug-circle-bolt"></i>
                        </div>
                    </div>
                    <div>
                        <h5 class="fw-800 mb-1">Bridging VClaim Aktif (Mode Simulasi)</h5>
                        <p class="text-muted small mb-0">Sistem saat ini terhubung ke simulator VClaim lokal sesuai spesifikasi integrasi Trust Mark BPJS. Semua data SEP yang diterbitkan akan tersimpan dalam basis data internal rumah sakit untuk kebutuhan bridging Satu Sehat.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Kunjungan BPJS -->
<div class="page-header-bar mb-3 mt-4">
    <form class="d-flex gap-2 flex-grow-1" method="GET">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari No. Registrasi, nama pasien, atau No. RM...">
        </div>
        <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
    </form>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-list-check"></i>
            <span>Antrean Pembuatan SEP Pasien BPJS</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. Registrasi</th>
                    <th>Informasi Pasien</th>
                    <th>No. Kartu JKN</th>
                    <th>Unit / DPJP</th>
                    <th>Diagnosis (ICD-10)</th>
                    <th>Status SEP</th>
                    <th class="pe-4 text-center">Aksi Bridging</th>
                </tr>
            </thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-simrs-primary small">{{ $encounter->no_registrasi }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $encounter->waktu_masuk?->format('d/m H:i') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-simrs-gray-900">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.75rem;">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td>
                        <div class="text-mono small fw-bold text-simrs-secondary">
                            <i class="fa-solid fa-id-card me-1 opacity-50"></i>{{ $encounter->patient->no_bpjs ?: '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="small fw-600">{{ $encounter->department?->nama_depart }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ $encounter->doctor?->display_name ?? '-' }}</div>
                    </td>
                    <td>
                        @if($encounter->medicalRecord?->icd10_primer)
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1" style="font-size: 0.72rem; font-weight: 700;">
                                <i class="fa-solid fa-stethoscope me-1 opacity-50"></i>{{ $encounter->medicalRecord->icd10_primer }}
                            </span>
                        @else
                            <span class="text-muted opacity-50 italic small">- Belum diinput dokter -</span>
                        @endif
                    </td>
                    <td>
                        @if($encounter->sepDocument)
                            <div class="text-success fw-bold small mb-1">
                                <i class="fa-solid fa-check-circle me-1"></i>{{ $encounter->sepDocument->no_sep }}
                            </div>
                            <div class="small text-muted" style="font-size: 0.7rem;">Issued: {{ $encounter->sepDocument->issued_at?->format('d/m/Y') }}</div>
                        @else
                            <span class="badge-status status-menunggu">Belum Terbit</span>
                        @endif
                    </td>
                    <td class="pe-4 text-center">
                        <div class="d-flex justify-content-center gap-2">
                            @if(!$encounter->sepDocument)
                                <form action="{{ route('bpjs.sep.store', $encounter) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="diagnosis_awal" value="{{ $encounter->medicalRecord?->icd10_primer ?: 'Z00.0' }}">
                                    <button class="btn btn-sm btn-simrs-outline shadow-sm py-1 px-3">
                                        <i class="fa-solid fa-file-circle-plus me-1"></i>Terbit SEP
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-light border shadow-none py-1 px-3" disabled>
                                    <i class="fa-solid fa-print me-1"></i>Cetak SEP
                                </button>
                            @endif
                            <form action="{{ route('bpjs.eclaim.simulate', $encounter) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-simrs-primary shadow-sm py-1 px-3">
                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i>e-Claim
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fa-solid fa-hospital-user fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Tidak ada kunjungan pasien BPJS hari ini yang membutuhkan SEP.</div>
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

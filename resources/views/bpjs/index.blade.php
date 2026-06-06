@extends('layouts.app')

@section('title', 'BPJS Kesehatan')
@section('page-title', 'Integrasi BPJS VClaim')
@section('page-subtitle', 'Manajemen Surat Eligibilitas Peserta (SEP) dan Pengajuan e-Claim')

@section('content')
<div class="row g-4 mb-4">
    <!-- Cek Kepesertaan -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-2 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-primary text-white rounded-2 d-flex align-items-center justify-content-center shadow-sm" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-id-card-clip small"></i>
                    </span>
                    <h6 class="fw-bold mb-0 text-dark">Verifikasi Kepesertaan</h6>
                </div>
            </div>
            <div class="card-body p-4">
                <form method="GET">
                    <label class="form-label text-muted fw-bold small text-uppercase mb-2">Nomor Kartu JKN / NIK</label>
                    <div class="input-group shadow-none mb-3">
                        <span class="input-group-text bg-light border-end-0 text-muted px-3 rounded-start-pill"><i class="fa-solid fa-address-card"></i></span>
                        <input name="no_kartu" value="{{ request('no_kartu') }}" class="form-control border-start-0 text-primary font-monospace fw-bold bg-light shadow-none fs-6" placeholder="0001961000..." style="letter-spacing: 1px;">
                        <button class="btn btn-dark fw-semibold px-4 rounded-end-pill">Cek</button>
                    </div>
                </form>

                @if($participant)
                    <!-- Kartu Status BPJS Premium -->
                    <div class="mt-4 p-4 rounded-4 position-relative overflow-hidden" style="background: linear-gradient(135deg, #10B981, #059669); color: white;">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="fa-brands fa-envira" style="font-size: 6rem; transform: rotate(15deg);"></i>
                        </div>
                        <div class="position-relative z-1">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="small fw-bold text-uppercase tracking-wider opacity-75">Status Peserta</div>
                                <i class="fa-solid fa-circle-check fs-4"></i>
                            </div>
                            <div class="h3 fw-bolder mb-4">{{ $participant['response']['statusPeserta'] }}</div>
                            
                            <div class="row g-3 bg-white bg-opacity-10 rounded-3 p-2">
                                <div class="col-6 border-end border-light border-opacity-25">
                                    <div class="small opacity-75 fw-semibold mb-1" style="font-size: 0.65rem; text-transform: uppercase;">Kelas Hak</div>
                                    <div class="fw-bold">{{ $participant['response']['hakKelas'] }}</div>
                                </div>
                                <div class="col-6">
                                    <div class="small opacity-75 fw-semibold mb-1" style="font-size: 0.65rem; text-transform: uppercase;">Jenis Peserta</div>
                                    <div class="fw-bold text-truncate">{{ $participant['response']['jenisPeserta'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 text-center py-5 bg-light rounded-4 border border-dashed">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mb-3" style="width: 60px; height: 60px;">
                            <i class="fa-solid fa-fingerprint fs-3 text-muted opacity-50"></i>
                        </div>
                        <p class="small text-muted fw-medium mb-0 px-3">Masukkan nomor kartu JKN untuk verifikasi bridging ke server BPJS.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi Mode & Statistik -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <div class="d-flex align-items-start gap-4">
                    <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-circle shadow-sm flex-shrink-0" style="width: 64px; height: 64px; font-size: 1.5rem;">
                        <i class="fa-solid fa-plug-circle-bolt pulse-animation"></i>
                    </div>
                    <div>
                        <h4 class="fw-bolder text-dark mb-2">Bridging VClaim Aktif <span class="badge bg-primary fs-6 ms-2 align-middle">Mode Simulasi</span></h4>
                        <p class="text-muted fw-medium lh-lg mb-0" style="font-size: 0.95rem;">
                            Sistem saat ini terhubung ke simulator VClaim lokal sesuai spesifikasi integrasi Trust Mark BPJS. Semua data SEP yang diterbitkan akan tersimpan dalam basis data internal rumah sakit untuk kebutuhan rekonsiliasi dan pengajuan e-Claim.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Kunjungan BPJS -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-4 pb-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-list-check fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Antrean Pembuatan SEP JKN</h5>
                <p class="text-muted small mb-0">Total {{ $encounters->total() }} pasien BPJS terdaftar hari ini</p>
            </div>
        </div>
        <form class="d-flex" method="GET" style="min-width: 320px;">
            <div class="input-group shadow-none">
                <span class="input-group-text bg-light border-end-0 text-muted px-3 rounded-start-pill"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="Cari No. Registrasi atau Nama...">
                <button type="submit" class="btn btn-dark fw-semibold px-4 rounded-end-pill">Cari</button>
            </div>
        </form>
    </div>

    <div class="table-responsive mt-3">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
            <thead class="bg-light bg-opacity-75">
                <tr class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    <th class="border-0 px-4 py-3">No. Registrasi</th>
                    <th class="border-0 py-3">Informasi Pasien</th>
                    <th class="border-0 py-3">Unit / DPJP</th>
                    <th class="border-0 py-3">Diagnosis Klinis</th>
                    <th class="border-0 py-3 text-center">Status SEP</th>
                    <th class="border-0 px-4 py-3 text-end">Aksi Bridging</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
            @forelse($encounters as $encounter)
                <tr>
                    <td class="px-4 py-3">
                        <div class="text-primary font-monospace fw-bold mb-1">{{ $encounter->no_registrasi }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;"><i class="fa-regular fa-clock me-1 opacity-50"></i>{{ $encounter->waktu_masuk?->format('d/m H:i') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark mb-1">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>RM: {{ $encounter->patient->no_rkm_medis }} <span class="mx-1 text-black-50">&bull;</span> JKN: <span class="text-primary fw-semibold">{{ $encounter->patient->no_bpjs ?: '-' }}</span></div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark mb-1">{{ $encounter->department?->nama_depart }}</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-user-doctor me-1 opacity-50"></i>{{ $encounter->doctor?->display_name ?? 'Belum Ditentukan' }}
                        </div>
                    </td>
                    <td>
                        @if($encounter->medicalRecord?->icd10_primer)
                            <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-25 px-2 py-1 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-stethoscope me-1 text-primary opacity-75"></i>{{ $encounter->medicalRecord->icd10_primer }}
                            </span>
                        @else
                            <span class="text-muted opacity-50 fst-italic small">Belum diinput RME</span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($encounter->sepDocument)
                            <div class="text-success font-monospace fw-bolder mb-1" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-check-circle me-1"></i>{{ $encounter->sepDocument->no_sep }}
                            </div>
                            <div class="small text-muted" style="font-size: 0.65rem; text-transform: uppercase;">Issued: {{ $encounter->sepDocument->issued_at?->format('d/m/Y') }}</div>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem;">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>BELUM TERBIT
                            </span>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            @if(!$encounter->sepDocument)
                                <form action="{{ route('bpjs.sep.store', $encounter) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="diagnosis_awal" value="{{ $encounter->medicalRecord?->icd10_primer ?: 'Z00.0' }}">
                                    <button class="btn btn-sm btn-primary bg-gradient shadow-sm rounded-3 px-3 fw-semibold transition-hover">
                                        <i class="fa-solid fa-file-circle-plus me-1"></i>Terbitkan SEP
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-light border-light-subtle text-muted shadow-sm rounded-3 px-3 fw-semibold" disabled>
                                    <i class="fa-solid fa-print me-1"></i>Cetak SEP
                                </button>
                            @endif
                            <form action="{{ route('bpjs.eclaim.simulate', $encounter) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-info text-white bg-gradient shadow-sm rounded-3 px-3 fw-semibold transition-hover">
                                    <i class="fa-solid fa-cloud-arrow-up me-1"></i>e-Claim
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-id-card-clip fs-1 text-muted opacity-50"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Tidak Ada Antrean BPJS</h5>
                        <p class="text-muted small mb-0">Semua administrasi SEP pasien telah diselesaikan.</p>
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
    .tracking-wider { letter-spacing: 0.1em; }
    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: var(--simrs-gray-300) !important; }
    
    @keyframes pulse-ring {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.7); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    .pulse-animation { animation: pulse-ring 2s infinite; border-radius: 50%; }
</style>
@endsection

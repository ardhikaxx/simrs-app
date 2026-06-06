@extends('layouts.app')

@section('title', 'BPJS Kesehatan')
@section('page-title', 'Integrasi BPJS VClaim')
@section('page-subtitle', 'Manajemen Surat Eligibilitas Peserta (SEP) dan Pengajuan e-Claim')

@section('content')
<div class="row g-4 mb-4">
    <!-- Cek Kepesertaan -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white">
            <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-id-card-clip fs-5"></i>
                </div>
                <h6 class="fw-bold mb-0 text-dark">Verifikasi Kepesertaan</h6>
            </div>
            <div class="card-body p-4">
                <form method="GET">
                    <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Nomor Kartu JKN / NIK</label>
                    <div class="input-group bg-light rounded-3 px-3 py-1 mb-4 border border-light">
                        <span class="input-group-text bg-transparent border-0 text-muted"><i class="fa-solid fa-address-card"></i></span>
                        <input name="no_kartu" value="{{ request('no_kartu') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0 text-primary font-monospace fw-bold fs-6" placeholder="0001961000..." style="letter-spacing: 1px;">
                    </div>
                    <button class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm transition-hover">
                        <i class="fa-solid fa-magnifying-glass me-2"></i>CEK STATUS BRIDGING
                    </button>
                </form>

                @if($participant)
                    <!-- Kartu Status BPJS Premium (SaaS Style) -->
                    <div class="mt-4 p-4 rounded-4 position-relative overflow-hidden bg-success bg-opacity-10 border border-success border-opacity-10">
                        <div class="position-relative z-1">
                            <div class="d-flex align-items-center justify-content-between mb-3">
                                <div class="small fw-bold text-success text-uppercase" style="letter-spacing: 1px;">Status Peserta</div>
                                <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 24px; height: 24px; font-size: 0.75rem;">
                                    <i class="fa-solid fa-check"></i>
                                </div>
                            </div>
                            <div class="h3 fw-bold text-dark mb-4">{{ $participant['response']['statusPeserta'] }}</div>
                            
                            <div class="row g-2">
                                <div class="col-6">
                                    <div class="p-2 rounded-3 bg-white border border-light shadow-xs">
                                        <div class="small text-muted fw-semibold mb-1" style="font-size: 0.65rem; text-transform: uppercase;">Kelas Hak</div>
                                        <div class="fw-bold text-dark small text-truncate">{{ $participant['response']['hakKelas'] }}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="p-2 rounded-3 bg-white border border-light shadow-xs">
                                        <div class="small text-muted fw-semibold mb-1" style="font-size: 0.65rem; text-transform: uppercase;">Jenis Peserta</div>
                                        <div class="fw-bold text-dark small text-truncate">{{ $participant['response']['jenisPeserta'] }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="mt-4 text-center py-5 bg-light rounded-4 border border-dashed border-light">
                        <div class="d-inline-flex align-items-center justify-content-center bg-white rounded-circle shadow-sm mb-3" style="width: 60px; height: 60px;">
                            <i class="fa-solid fa-fingerprint fs-3 text-muted opacity-50"></i>
                        </div>
                        <p class="small text-muted fw-medium mb-0 px-3 lh-base">Masukkan nomor kartu JKN untuk verifikasi bridging ke server BPJS Kesehatan.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Informasi Mode & Statistik -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 h-100 bg-white overflow-hidden transition-hover">
            <div class="card-body p-4 d-flex flex-column justify-content-center">
                <div class="d-flex align-items-start gap-4">
                    <div class="d-flex align-items-center justify-content-center bg-info bg-opacity-10 text-info rounded-circle shadow-sm flex-shrink-0" style="width: 64px; height: 64px; font-size: 1.5rem;">
                        <i class="fa-solid fa-plug-circle-bolt pulse-animation"></i>
                    </div>
                    <div>
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <h4 class="fw-bold text-dark mb-0">Bridging VClaim Aktif</h4>
                            <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 px-3 py-2 rounded-pill fw-bold" style="font-size: 0.7rem;">MODE SIMULASI</span>
                        </div>
                        <p class="text-muted fw-medium lh-lg mb-0" style="font-size: 0.95rem;">
                            Sistem saat ini terhubung ke simulator VClaim lokal sesuai spesifikasi integrasi Trust Mark BPJS. Semua data SEP yang diterbitkan akan tersimpan dalam basis data internal rumah sakit untuk kebutuhan rekonsiliasi dan pengajuan e-Claim. Seluruh proses pengiriman data dipantau secara real-time.
                        </p>
                    </div>
                </div>
            </div>
            <div class="card-footer bg-light bg-opacity-50 border-top border-light p-4">
                <div class="row g-4 text-center">
                    <div class="col-4 border-end border-light">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.65rem;">Klaim Diajukan</div>
                        <div class="h4 fw-bold text-dark mb-0">124</div>
                    </div>
                    <div class="col-4 border-end border-light">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.65rem;">SEP Terbit</div>
                        <div class="h4 fw-bold text-dark mb-0">{{ $encounters->whereNotNull('sepDocument')->count() }}</div>
                    </div>
                    <div class="col-4">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.65rem;">SLA Bridging</div>
                        <div class="h4 fw-bold text-success mb-0">99.8%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Daftar Kunjungan BPJS -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4 bg-white">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-list-check fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Antrean Pembuatan SEP JKN</h5>
                <p class="text-muted small mb-0 fw-medium">Total {{ $encounters->total() }} pasien BPJS terdaftar hari ini</p>
            </div>
        </div>
        <form class="d-flex gap-2" method="GET" style="min-width: 350px;">
            <div class="input-group bg-light rounded-pill px-3 py-1 border border-light">
                <span class="input-group-text bg-transparent border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0 py-2 small" placeholder="Cari No. Registrasi atau Nama...">
            </div>
            <button type="submit" class="btn btn-primary px-4 fw-bold rounded-pill shadow-sm transition-hover">Cari</button>
        </form>
    </div>

    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Registrasi</th>
                    <th class="py-3">Informasi Pasien</th>
                    <th class="py-3">Unit / DPJP Pengirim</th>
                    <th class="py-3">Diagnosis (RME)</th>
                    <th class="py-3 text-center">Status SEP</th>
                    <th class="pe-4 py-3 text-end rounded-end">Aksi Bridging</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($encounters as $encounter)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="text-primary font-monospace fw-bold mb-1">{{ $encounter->no_registrasi }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;"><i class="fa-regular fa-clock me-1 opacity-50"></i>{{ $encounter->waktu_masuk?->format('d/m H:i') }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-dark mb-1">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace d-flex align-items-center gap-2">
                            <span class="badge bg-light text-secondary border border-light-subtle px-2 py-0" style="font-size: 0.65rem;">{{ $encounter->patient->no_rkm_medis }}</span>
                            <span class="text-primary fw-bold" style="font-size: 0.7rem;">JKN: {{ $encounter->patient->no_bpjs ?: '---' }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $encounter->department?->nama_depart }}</div>
                        <div class="text-muted small fw-medium" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-user-doctor me-1 opacity-50"></i>{{ $encounter->doctor?->display_name ?? 'Belum Ditentukan' }}
                        </div>
                    </td>
                    <td class="py-3">
                        @if($encounter->medicalRecord?->icd10_primer)
                            <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-25 px-2 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-stethoscope me-1 text-primary opacity-75"></i>{{ $encounter->medicalRecord->icd10_primer }}
                            </span>
                        @else
                            <span class="text-muted opacity-50 fst-italic small fw-medium">Belum diinput RME</span>
                        @endif
                    </td>
                    <td class="text-center py-3">
                        @if($encounter->sepDocument)
                            <div class="text-success font-monospace fw-bold mb-1" style="font-size: 0.85rem;">
                                <i class="fa-solid fa-circle-check me-1"></i>{{ $encounter->sepDocument->no_sep }}
                            </div>
                            <div class="small text-muted fw-semibold text-uppercase" style="font-size: 0.6rem;">Issued: {{ $encounter->sepDocument->issued_at?->format('d/m/Y') }}</div>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-circle-exclamation me-1"></i>BELUM TERBIT
                            </span>
                        @endif
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            @if(!$encounter->sepDocument)
                                <form action="{{ route('bpjs.sep.store', $encounter) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="diagnosis_awal" value="{{ $encounter->medicalRecord?->icd10_primer ?: 'Z00.0' }}">
                                    <button class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 fw-bold transition-hover">
                                        <i class="fa-solid fa-file-circle-plus me-1 small"></i>Terbitkan SEP
                                    </button>
                                </form>
                            @else
                                <button class="btn btn-sm btn-light border border-light-subtle text-muted shadow-sm rounded-pill px-3 fw-bold" disabled>
                                    <i class="fa-solid fa-print me-1 small"></i>Cetak SEP
                                </button>
                            @endif
                            <form action="{{ route('bpjs.eclaim.simulate', $encounter) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-info text-white shadow-sm rounded-pill px-3 fw-bold transition-hover">
                                    <i class="fa-solid fa-cloud-arrow-up me-1 small"></i>e-Claim
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-id-card-clip fs-1 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1 text-uppercase" style="letter-spacing: 1px;">Tidak Ada Antrean BPJS</h6>
                        <p class="text-muted small mb-0 fw-medium">Semua administrasi SEP pasien telah diselesaikan.</p>
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
    .border-dashed { border-style: dashed !important; border-width: 2px !important; border-color: #e2e8f0 !important; }
    .shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    
    @keyframes pulse-ring {
        0% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4); }
        70% { transform: scale(1); box-shadow: 0 0 0 10px rgba(59, 130, 246, 0); }
        100% { transform: scale(0.9); box-shadow: 0 0 0 0 rgba(59, 130, 246, 0); }
    }
    .pulse-animation { animation: pulse-ring 2s infinite; border-radius: 50%; }
</style>
@endsection


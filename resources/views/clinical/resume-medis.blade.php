@extends('layouts.app')

@section('title', 'Resume Medis Pasien')
@section('page-title', 'Discharge Summary (Resume Medis)')
@section('page-subtitle', 'Ringkasan pelayanan medis terintegrasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden bg-white" id="printableResume">
            <!-- Header Resume -->
            <div class="card-header bg-white border-bottom p-5 pt-5 pb-4">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary text-white rounded-4 d-flex justify-content-center align-items-center shadow-sm" style="width: 60px; height: 60px; font-size: 2rem;">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <div>
                            <h2 class="fw-bolder mb-0 text-dark" style="letter-spacing: -0.5px;">{{ config('app.hospital_name') }}</h2>
                            <div class="text-muted fw-semibold" style="font-size: 0.85rem;">Jl. Kesehatan No. 123, Bandung | Telp: (022) 1234567</div>
                        </div>
                    </div>
                    <div class="text-end border-start border-3 border-primary ps-4">
                        <div class="h3 fw-bolder text-primary mb-0" style="letter-spacing: 1px;">RESUME MEDIS</div>
                        <div class="text-muted fw-bold text-uppercase" style="font-size: 0.7rem; letter-spacing: 1.5px;">Electronic Discharge Summary</div>
                    </div>
                </div>

                <div class="row g-0 rounded-4 bg-light border border-light-subtle overflow-hidden">
                    <div class="col-md-6 border-end border-light-subtle p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="fa-solid fa-user-injured" style="font-size: 4rem;"></i>
                        </div>
                        <div class="small text-muted fw-bold text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">Identitas Pasien</div>
                        <h4 class="fw-bolder text-dark mb-1">{{ $encounter->patient->nama_pasien }}</h4>
                        <div class="font-monospace fw-bold h5 text-primary mb-3">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                        <div class="d-flex gap-3 small text-muted fw-semibold">
                            <span><i class="fa-solid fa-venus-mars me-1 opacity-50"></i>{{ $encounter->patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            <span><i class="fa-solid fa-cake-candles me-1 opacity-50"></i>{{ $encounter->patient->age }} Th</span>
                            <span><i class="fa-solid fa-id-card me-1 opacity-50"></i>{{ $encounter->patient->nik }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="fa-solid fa-hospital-user" style="font-size: 4rem;"></i>
                        </div>
                        <div class="small text-muted fw-bold text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">Informasi Kunjungan</div>
                        <div class="row g-2 small" style="font-size: 0.85rem;">
                            <div class="col-5 fw-bold text-muted">No. Registrasi</div>
                            <div class="col-7 fw-bolder text-dark font-monospace">{{ $encounter->no_registrasi }}</div>
                            <div class="col-5 fw-bold text-muted">Unit Pelayanan</div>
                            <div class="col-7 fw-bold text-dark">{{ $encounter->department->nama_depart }}</div>
                            <div class="col-5 fw-bold text-muted">Waktu Masuk</div>
                            <div class="col-7 fw-bold text-dark">{{ $encounter->waktu_masuk->format('d/m/Y H:i') }} WIB</div>
                            <div class="col-5 fw-bold text-muted">Dokter DPJP</div>
                            <div class="col-7 fw-bold text-primary">{{ $encounter->doctor->display_name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body Resume -->
            <div class="card-body p-5 bg-white">
                <div class="mb-5">
                    <div class="d-flex align-items-center gap-2 border-bottom border-primary border-2 pb-2 mb-4">
                        <i class="fa-solid fa-stethoscope text-primary fs-5"></i>
                        <h5 class="fw-bolder text-primary mb-0" style="letter-spacing: 0.5px;">RINGKASAN KLINIS (SOAP)</h5>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">Keluhan Utama (Subjective)</label>
                                <div class="p-3 bg-light rounded-3 border border-light-subtle text-dark fw-medium lh-base">{{ $encounter->medicalRecord?->keluhan_utama ?: '-' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">Diagnosis Kerja (Assessment)</label>
                                <div class="p-3 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 rounded-3 fw-bold lh-base">{{ $encounter->medicalRecord?->diagnosis_kerja ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="small fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">Pemeriksaan Fisik (Objective)</label>
                                <div class="p-3 bg-light rounded-3 border border-light-subtle text-dark fw-medium lh-base" style="min-height: 100px;">{{ $encounter->medicalRecord?->pemeriksaan_fisik ?: '-' }}</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="small fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">ICD-10 Primer</label>
                                    <div class="p-3 bg-white border border-light-subtle rounded-3 text-center fw-bolder fs-5 text-dark shadow-sm">{{ $encounter->medicalRecord?->icd10_primer ?: '???' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="small fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">ICD-9 Prosedur</label>
                                    <div class="p-3 bg-white border border-light-subtle rounded-3 text-center fw-bolder fs-5 text-secondary shadow-sm">{{ $encounter->medicalRecord?->icd9_prosedur ?: '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 border-bottom border-success border-2 pb-2 mb-4">
                            <i class="fa-solid fa-pills text-success fs-5"></i>
                            <h5 class="fw-bolder text-success mb-0" style="letter-spacing: 0.5px;">TERAPI OBAT (PULANG)</h5>
                        </div>
                        <ul class="list-group list-group-flush small">
                            @forelse($encounter->prescriptions->pluck('details')->flatten() as $detail)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-light-subtle">
                                    <div>
                                        <div class="fw-bold text-dark" style="font-size: 0.9rem;">{{ $detail->nama_obat }}</div>
                                        <div class="text-muted fst-italic mt-1"><i class="fa-solid fa-caret-right text-success me-1 small"></i>{{ $detail->aturan_pakai }}</div>
                                    </div>
                                    <span class="badge bg-light text-dark border border-secondary border-opacity-25 px-3 py-2 rounded-pill font-monospace" style="font-size: 0.85rem;">{{ $detail->jumlah }} {{ $detail->satuan }}</span>
                                </li>
                            @empty
                                <li class="text-muted fst-italic py-3 text-center bg-light rounded-3">Tidak ada resep obat terdaftar.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 border-bottom border-info border-2 pb-2 mb-4">
                            <i class="fa-solid fa-flask-vial text-info fs-5"></i>
                            <h5 class="fw-bolder text-info mb-0" style="letter-spacing: 0.5px;">HASIL PENUNJANG</h5>
                        </div>
                        <div class="small">
                            <div class="fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">Laboratorium</div>
                            <div class="mb-4 d-flex flex-wrap gap-2">
                                @forelse($encounter->labOrders->pluck('results')->flatten() as $res)
                                    <span class="badge bg-light text-dark border border-light-subtle px-3 py-2 shadow-sm">
                                        <span class="opacity-75">{{ $res->parameter }}:</span> <span class="fw-bolder font-monospace ms-1" style="font-size: 0.8rem;">{{ $res->nilai }} {{ $res->satuan }}</span>
                                    </span>
                                @empty
                                    <div class="text-muted fst-italic p-2 bg-light w-100 rounded-3 text-center">Belum ada hasil lab.</div>
                                @endforelse
                            </div>
                            
                            <div class="fw-bold text-muted text-uppercase tracking-wider mb-2" style="font-size: 0.7rem;">Radiologi (Kesan)</div>
                            <div class="p-3 bg-light border border-light-subtle rounded-3 fst-italic text-dark fw-medium lh-sm">
                                "{{ $encounter->radiologyOrders->first()?->result?->kesan ?: 'Belum ada hasil radiologi yang diverifikasi.' }}"
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-5 row">
                    <div class="col-4 text-center">
                        <div class="small text-muted mb-5 pb-4 fw-medium text-uppercase tracking-wider" style="font-size: 0.65rem;">Petugas Rekam Medis</div>
                        <div class="fw-bolder border-top border-dark pt-2">{{ auth('staff')->user()->display_name }}</div>
                        <div class="small text-muted font-monospace">NIK. {{ auth('staff')->user()->nip }}</div>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4 text-center">
                        <div class="small text-muted mb-5 pb-4 fw-medium text-uppercase tracking-wider" style="font-size: 0.65rem;">Dokter Penanggung Jawab</div>
                        <div class="fw-bolder border-top border-primary pt-2 text-primary text-uppercase">{{ $encounter->doctor->display_name }}</div>
                        <div class="small text-muted font-monospace">SIP. {{ $encounter->doctor->nip }}</div>
                    </div>
                </div>
            </div>

            <!-- Footer Resume -->
            <div class="card-footer bg-light border-top p-4 text-center border-0">
                <div class="small text-muted fw-bold">Dokumen ini diterbitkan secara elektronik oleh Sistem Informasi Manajemen RS Core v1.0</div>
                <div class="small text-muted opacity-50 mt-1 font-monospace" style="font-size: 0.75rem;">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | ID-SUM: {{ $encounter->id }}</div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mb-5 action-buttons">
            <button class="btn btn-light border-light-subtle fw-bold text-muted px-4 py-2 shadow-sm rounded-pill transition-hover" onclick="window.history.back()">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali
            </button>
            <button class="btn btn-primary bg-gradient px-5 py-2 fw-bold shadow-sm rounded-pill transition-hover" onclick="window.print()">
                <i class="fa-solid fa-print me-2"></i>Cetak Dokumen Resume
            </button>
        </div>
    </div>
</div>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important; }
    .tracking-wider { letter-spacing: 0.1em; }
    
    @media print {
        body { background: white !important; }
        .main-wrapper { margin-left: 0 !important; padding: 0 !important; }
        .simrs-topbar, .simrs-sidebar, .simrs-footer, .action-buttons { display: none !important; }
        .simrs-content { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
        .card { box-shadow: none !important; border: none !important; margin: 0 !important; }
        .border-light-subtle { border-color: #dee2e6 !important; }
        .rounded-4, .rounded-3, .rounded-top-4, .rounded-bottom-4 { border-radius: 0 !important; }
        .bg-light { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .bg-primary { background-color: #0B6477 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; color: white !important;}
        .text-primary { color: #0B6477 !important; }
        .bg-primary-subtle { background-color: #cce5ff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endsection

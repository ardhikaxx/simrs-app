@extends('layouts.app')

@section('title', 'Resume Medis Pasien')
@section('page-title', 'Discharge Summary (Resume Medis)')
@section('page-subtitle', 'Ringkasan pelayanan medis terintegrasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-10">
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden bg-white" id="printableResume">
            <!-- Header Resume -->
            <div class="card-header bg-white border-bottom border-light p-5 pt-5 pb-4">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex justify-content-center align-items-center flex-shrink-0" style="width: 60px; height: 60px; font-size: 2rem;">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <div>
                            <h2 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">{{ config('app.hospital_name') }}</h2>
                            <div class="text-muted fw-medium mt-1" style="font-size: 0.85rem;">Jl. Kesehatan No. 123, Bandung | Telp: (022) 1234567</div>
                        </div>
                    </div>
                    <div class="text-end border-start border-3 border-primary ps-4">
                        <div class="h3 fw-bold text-primary mb-0" style="letter-spacing: 1px;">RESUME MEDIS</div>
                        <div class="text-muted fw-semibold text-uppercase mt-1" style="font-size: 0.7rem; letter-spacing: 1.5px;">Electronic Discharge Summary</div>
                    </div>
                </div>

                <div class="row g-0 rounded-4 bg-light border border-light overflow-hidden">
                    <div class="col-md-6 border-end border-light p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="fa-solid fa-user-injured text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <div class="small text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Identitas Pasien</div>
                        <h4 class="fw-bold text-dark mb-1">{{ $encounter->patient->nama_pasien }}</h4>
                        <div class="font-monospace fw-bold h5 text-primary mb-3">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                        <div class="d-flex gap-3 small text-muted fw-medium">
                            <span><i class="fa-solid fa-venus-mars me-1 opacity-50"></i>{{ $encounter->patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                            <span><i class="fa-solid fa-cake-candles me-1 opacity-50"></i>{{ $encounter->patient->age }} Th</span>
                            <span><i class="fa-solid fa-id-card me-1 opacity-50"></i>{{ $encounter->patient->nik }}</span>
                        </div>
                    </div>
                    <div class="col-md-6 p-4 position-relative">
                        <div class="position-absolute top-0 end-0 p-3 opacity-10">
                            <i class="fa-solid fa-hospital-user text-primary" style="font-size: 4rem;"></i>
                        </div>
                        <div class="small text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Informasi Kunjungan</div>
                        <div class="row g-2 small" style="font-size: 0.85rem;">
                            <div class="col-5 fw-medium text-muted">No. Registrasi</div>
                            <div class="col-7 fw-bold text-dark font-monospace">{{ $encounter->no_registrasi }}</div>
                            <div class="col-5 fw-medium text-muted">Unit Pelayanan</div>
                            <div class="col-7 fw-bold text-dark">{{ $encounter->department->nama_depart }}</div>
                            <div class="col-5 fw-medium text-muted">Waktu Masuk</div>
                            <div class="col-7 fw-bold text-dark">{{ $encounter->waktu_masuk->format('d/m/Y H:i') }} WIB</div>
                            <div class="col-5 fw-medium text-muted">Dokter DPJP</div>
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
                        <h5 class="fw-bold text-primary mb-0" style="letter-spacing: 0.5px;">RINGKASAN KLINIS (SOAP)</h5>
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="small fw-semibold text-muted text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Keluhan Utama (Subjective)</label>
                                <div class="p-3 bg-light rounded-3 border border-light text-dark fw-medium lh-base">{{ $encounter->medicalRecord?->keluhan_utama ?: '-' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-semibold text-muted text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Diagnosis Kerja (Assessment)</label>
                                <div class="p-3 bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-3 fw-bold lh-base">{{ $encounter->medicalRecord?->diagnosis_kerja ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <label class="small fw-semibold text-muted text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Pemeriksaan Fisik (Objective)</label>
                                <div class="p-3 bg-light rounded-3 border border-light text-dark fw-medium lh-base" style="min-height: 100px;">{{ $encounter->medicalRecord?->pemeriksaan_fisik ?: '-' }}</div>
                            </div>
                            <div class="row g-3">
                                <div class="col-6">
                                    <label class="small fw-semibold text-muted text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">ICD-10 Primer</label>
                                    <div class="p-3 bg-white border border-light rounded-3 text-center fw-bold fs-5 text-dark shadow-sm">{{ $encounter->medicalRecord?->icd10_primer ?: '???' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="small fw-semibold text-muted text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">ICD-9 Prosedur</label>
                                    <div class="p-3 bg-white border border-light rounded-3 text-center fw-bold fs-5 text-secondary shadow-sm">{{ $encounter->medicalRecord?->icd9_prosedur ?: '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 mb-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-2 border-bottom border-success border-2 pb-2 mb-4">
                            <i class="fa-solid fa-pills text-success fs-5"></i>
                            <h5 class="fw-bold text-success mb-0" style="letter-spacing: 0.5px;">TERAPI OBAT (PULANG)</h5>
                        </div>
                        <ul class="list-group list-group-flush small">
                            @forelse($encounter->prescriptions->pluck('details')->flatten() as $detail)
                                <li class="list-group-item d-flex justify-content-between align-items-center px-0 py-2 border-light">
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
                            <h5 class="fw-bold text-info mb-0" style="letter-spacing: 0.5px;">HASIL PENUNJANG</h5>
                        </div>
                        <div class="small">
                            <div class="fw-semibold text-muted text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Laboratorium</div>
                            <div class="mb-4 d-flex flex-wrap gap-2">
                                @forelse($encounter->labOrders->pluck('results')->flatten() as $res)
                                    <span class="badge bg-light text-dark border border-light px-3 py-2 shadow-sm">
                                        <span class="opacity-75">{{ $res->parameter }}:</span> <span class="fw-bold font-monospace ms-1" style="font-size: 0.8rem;">{{ $res->nilai }} {{ $res->satuan }}</span>
                                    </span>
                                @empty
                                    <div class="text-muted fst-italic p-2 bg-light w-100 rounded-3 text-center">Belum ada hasil lab.</div>
                                @endforelse
                            </div>
                            
                            <div class="fw-semibold text-muted text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Radiologi (Kesan)</div>
                            <div class="p-3 bg-light border border-light rounded-3 fst-italic text-dark fw-medium lh-sm">
                                "{{ $encounter->radiologyOrders->first()?->result?->kesan ?: 'Belum ada hasil radiologi yang diverifikasi.' }}"
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-5 row">
                    <div class="col-4 text-center">
                        <div class="small text-muted mb-5 pb-4 fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Petugas Rekam Medis</div>
                        <div class="fw-bold border-top border-light border-2 pt-3">{{ auth('staff')->user()->display_name }}</div>
                        <div class="small text-muted font-monospace mt-1">NIK. {{ auth('staff')->user()->nip }}</div>
                    </div>
                    <div class="col-4"></div>
                    <div class="col-4 text-center">
                        <div class="small text-muted mb-5 pb-4 fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Dokter Penanggung Jawab</div>
                        <div class="fw-bold border-top border-primary border-2 pt-3 text-primary text-uppercase">{{ $encounter->doctor->display_name }}</div>
                        <div class="small text-muted font-monospace mt-1">SIP. {{ $encounter->doctor->nip }}</div>
                    </div>
                </div>
            </div>

            <!-- Footer Resume -->
            <div class="card-footer bg-light border-top border-light p-4 text-center border-0 rounded-bottom-4">
                <div class="small text-muted fw-semibold">Dokumen ini diterbitkan secara elektronik oleh Sistem Informasi Manajemen RS Core v1.2</div>
                <div class="small text-muted opacity-75 mt-1 font-monospace" style="font-size: 0.75rem;">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | ID-SUM: {{ $encounter->id }}</div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mb-5 action-buttons">
            <button class="btn btn-light border-light fw-bold text-muted px-4 py-2 shadow-sm rounded-pill transition-hover hover-bg-gray" onclick="window.history.back()">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali
            </button>
            <button class="btn btn-primary px-5 py-2 fw-bold shadow-sm rounded-pill transition-hover" onclick="window.print()">
                <i class="fa-solid fa-print me-2"></i>Cetak Dokumen Resume
            </button>
        </div>
    </div>
</div>

<style>
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    
    @media print {
        body { background: white !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .sidebar, .topbar, .sidebar-overlay, footer, .action-buttons { display: none !important; }
        .content-wrapper { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
        .card { box-shadow: none !important; border: none !important; margin: 0 !important; }
        .rounded-4, .rounded-3, .rounded-top-4, .rounded-bottom-4, .rounded-circle, .rounded-pill { border-radius: 0 !important; }
        
        .bg-light { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .bg-primary { background-color: #3b82f6 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; color: white !important;}
        .bg-primary.bg-opacity-10 { background-color: #e0e7ff !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
        .text-primary { color: #3b82f6 !important; }
    }
</style>
@endsection

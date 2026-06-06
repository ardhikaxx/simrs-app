@extends('layouts.app')

@section('title', 'Resume Medis Pasien')
@section('page-title', 'Discharge Summary (Resume Medis)')
@section('page-subtitle', 'Ringkasan pelayanan medis terintegrasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-xl-9">
        <div class="simrs-card shadow-lg border-0 mb-4" id="printableResume">
            <!-- Header Resume -->
            <div class="p-5 border-bottom bg-white rounded-top-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="d-flex align-items-center gap-3">
                        <i class="fa-solid fa-hospital-user fs-1 text-simrs-primary"></i>
                        <div>
                            <h2 class="fw-800 mb-0 text-simrs-gray-900">{{ config('app.hospital_name') }}</h2>
                            <div class="small text-muted fw-600">Jl. Kesehatan No. 123, Bandung | Telp: (022) 1234567</div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="h4 fw-800 text-simrs-primary mb-0">RESUME MEDIS</div>
                        <div class="small text-muted fw-bold">Electronic Discharge Summary</div>
                    </div>
                </div>

                <div class="row g-4 p-4 rounded-3 bg-light border">
                    <div class="col-md-6 border-end">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-2">Identitas Pasien</div>
                        <h4 class="fw-800 text-simrs-gray-900 mb-1">{{ $encounter->patient->nama_pasien }}</h4>
                        <div class="text-mono fw-700 h5 text-simrs-primary mb-2">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                        <div class="small text-muted">JK: {{ $encounter->patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} | Usia: {{ $encounter->patient->age }} Th</div>
                        <div class="small text-muted">NIK: {{ $encounter->patient->nik }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-2">Informasi Kunjungan</div>
                        <div class="row g-2 small">
                            <div class="col-5 fw-bold text-muted">No. Registrasi:</div><div class="col-7 fw-800 text-dark">{{ $encounter->no_registrasi }}</div>
                            <div class="col-5 fw-bold text-muted">Unit Pelayanan:</div><div class="col-7 fw-800 text-dark">{{ $encounter->department->nama_depart }}</div>
                            <div class="col-5 fw-bold text-muted">Waktu Masuk:</div><div class="col-7 fw-800 text-dark">{{ $encounter->waktu_masuk->format('d/m/Y H:i') }}</div>
                            <div class="col-5 fw-bold text-muted">DPJP:</div><div class="col-7 fw-800 text-simrs-primary">{{ $encounter->doctor->display_name }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Body Resume -->
            <div class="p-5 bg-white">
                <div class="mb-5">
                    <h5 class="fw-800 text-simrs-primary border-bottom pb-2 mb-3"><i class="fa-solid fa-stethoscope me-2"></i>RINGKASAN KLINIS (SOAP)</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small fw-800 text-muted text-uppercase mb-1">Keluhan Utama (Subjective):</label>
                                <div class="p-3 bg-light rounded border lh-base">{{ $encounter->medicalRecord->keluhan_utama ?: '-' }}</div>
                            </div>
                            <div class="mb-3">
                                <label class="small fw-800 text-muted text-uppercase mb-1">Diagnosis Kerja (Assessment):</label>
                                <div class="p-3 bg-light rounded border border-primary-subtle lh-base fw-700">{{ $encounter->medicalRecord->diagnosis_kerja ?: '-' }}</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="small fw-800 text-muted text-uppercase mb-1">Pemeriksaan Fisik (Objective):</label>
                                <div class="p-3 bg-light rounded border lh-base">{{ $encounter->medicalRecord->pemeriksaan_fisik ?: '-' }}</div>
                            </div>
                            <div class="row g-2">
                                <div class="col-6">
                                    <label class="small fw-800 text-muted text-uppercase mb-1">ICD-10 Primer:</label>
                                    <div class="p-2 bg-info-subtle rounded text-center border border-info-subtle fw-800 text-info">{{ $encounter->medicalRecord->icd10_primer ?: '???' }}</div>
                                </div>
                                <div class="col-6">
                                    <label class="small fw-800 text-muted text-uppercase mb-1">ICD-9 Prosedur:</label>
                                    <div class="p-2 bg-secondary-subtle rounded text-center border border-secondary-subtle fw-800 text-secondary">{{ $encounter->medicalRecord->icd9_prosedur ?: '-' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row g-5 mb-5">
                    <div class="col-md-6">
                        <h5 class="fw-800 text-simrs-primary border-bottom pb-2 mb-3"><i class="fa-solid fa-pills me-2"></i>TERAPI OBAT (PULANG)</h5>
                        <ul class="list-group list-group-flush small">
                            @forelse($encounter->prescriptions->pluck('details')->flatten() as $detail)
                                <li class="list-group-item d-flex justify-content-between align-items-start px-0 border-light">
                                    <div class="ms-2 me-auto">
                                        <div class="fw-800 text-dark">{{ $detail->nama_obat }}</div>
                                        <div class="text-muted italic">{{ $detail->aturan_pakai }}</div>
                                    </div>
                                    <span class="badge bg-light text-dark border px-2 py-1">{{ $detail->jumlah }} {{ $detail->satuan }}</span>
                                </li>
                            @empty
                                <li class="text-muted italic py-3">Tidak ada resep obat terdaftar.</li>
                            @endforelse
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5 class="fw-800 text-simrs-primary border-bottom pb-2 mb-3"><i class="fa-solid fa-flask-vial me-2"></i>HASIL PENUNJANG</h5>
                        <div class="small">
                            <div class="fw-bold text-muted mb-1">Laboratorium:</div>
                            <div class="mb-3">
                                @forelse($encounter->labOrders->pluck('results')->flatten() as $res)
                                    <span class="badge bg-light text-dark border px-2 py-1 mb-1 me-1">{{ $res->parameter }}: {{ $res->nilai }} {{ $res->satuan }}</span>
                                @empty
                                    <span class="text-muted italic small">Belum ada hasil lab.</span>
                                @endforelse
                            </div>
                            <div class="fw-bold text-muted mb-1">Radiologi (Kesan):</div>
                            <div class="p-2 bg-light border rounded italic opacity-75">
                                {{ $encounter->radiologyOrders->first()?->result?->kesan ?: 'Belum ada hasil radiologi.' }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-5 d-flex justify-content-between align-items-end">
                    <div class="text-center" style="width: 250px;">
                        <div class="small text-muted mb-5 pb-3">Petugas Rekam Medis,</div>
                        <div class="fw-800 border-top pt-2">{{ auth('staff')->user()->display_name }}</div>
                        <div class="small text-muted">NIK. {{ auth('staff')->user()->nip }}</div>
                    </div>
                    <div class="text-center" style="width: 250px;">
                        <div class="small text-muted mb-5 pb-3">Dokter Penanggung Jawab,</div>
                        <div class="fw-800 border-top pt-2 text-simrs-primary text-uppercase">{{ $encounter->doctor->display_name }}</div>
                        <div class="small text-muted">SIP. {{ $encounter->doctor->nip }}</div>
                    </div>
                </div>
            </div>

            <!-- Footer Resume -->
            <div class="p-4 bg-light rounded-bottom-4 border-top text-center">
                <div class="small text-muted">Dokumen ini diterbitkan secara elektronik oleh Sistem Informasi Manajemen RS Core v1.0</div>
                <div class="small text-muted opacity-50 mt-1">Dicetak pada: {{ now()->format('d/m/Y H:i:s') }} | ID-SUM: {{ $encounter->id }}</div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mb-5">
            <button class="btn btn-simrs-outline px-4 shadow-sm" onclick="window.history.back()">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali
            </button>
            <button class="btn btn-simrs-primary px-5 shadow-sm" onclick="window.print()">
                <i class="fa-solid fa-print me-2"></i>Cetak Resume Medis
            </button>
        </div>
    </div>
</div>

<style>
    @media print {
        body { background: white !important; }
        .main-wrapper { margin-left: 0 !important; padding: 0 !important; }
        .simrs-topbar, .simrs-sidebar, .simrs-footer, .btn, .page-header-bar { display: none !important; }
        .simrs-content { padding: 0 !important; margin: 0 !important; }
        .simrs-card { box-shadow: none !important; border: 1px solid #eee !important; margin: 0 !important; }
        .rounded-top-4, .rounded-bottom-4 { border-radius: 0 !important; }
    }
</style>
@endsection

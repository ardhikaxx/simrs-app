@extends('layouts.app')

@section('title', 'Ekspertise Radiologi')
@section('page-title', 'Hasil Pemeriksaan Imaging')
@section('page-subtitle', 'Dokumentasi temuan klinis dan kesan radiologis')

@section('content')
@php($result = $radiologyOrder->result)
<div class="row g-4 mb-4">
    <!-- Ringkasan Pasien & Order -->
    <div class="col-lg-8">
        <div class="simrs-card h-100 mb-0 border-0 shadow-sm overflow-hidden bg-white">
            <div class="simrs-card-body p-0">
                <div class="d-flex align-items-center bg-primary text-white p-3 gap-3">
                    <div class="brand-icon shadow-none bg-white text-primary" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-x-ray"></i>
                    </div>
                    <div>
                        <div class="small fw-700 text-uppercase tracking-wider opacity-75">Order Radiologi</div>
                        <h6 class="fw-800 mb-0">{{ $radiologyOrder->jenis_pemeriksaan }} <span class="badge bg-white text-primary ms-2">{{ $radiologyOrder->no_order }}</span></h6>
                    </div>
                    <div class="ms-auto text-end">
                        <span class="badge {{ $radiologyOrder->prioritas === 'cito' ? 'bg-danger' : 'bg-info' }} px-3">
                            {{ strtoupper($radiologyOrder->prioritas) }}
                        </span>
                    </div>
                </div>
                <div class="p-3">
                    <div class="row g-3">
                        <div class="col-md-6 border-end">
                            <div class="small text-muted fw-700 text-uppercase mb-1">Identitas Pasien</div>
                            <div class="fw-800 text-simrs-gray-900">{{ $radiologyOrder->encounter->patient->nama_pasien }}</div>
                            <div class="small text-muted text-mono">{{ $radiologyOrder->encounter->patient->no_rkm_medis }} | {{ $radiologyOrder->encounter->patient->age }} Th ({{ $radiologyOrder->encounter->patient->jenis_kelamin }})</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted fw-700 text-uppercase mb-1">Pengirim</div>
                            <div class="fw-800 text-simrs-gray-900">{{ $radiologyOrder->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                            <div class="small text-muted">{{ $radiologyOrder->encounter->department->nama_depart }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Klinis -->
    <div class="col-lg-4">
        <div class="simrs-card h-100 mb-0 border-0 shadow-sm bg-white">
            <div class="simrs-card-header border-0 bg-transparent pb-0">
                <div class="simrs-card-title text-simrs-primary small">
                    <i class="fa-solid fa-notes-medical"></i>
                    <span>Indikasi Klinis</span>
                </div>
            </div>
            <div class="simrs-card-body">
                <div class="p-3 rounded-3 bg-light border italic small">
                    "{{ $radiologyOrder->catatan_klinis ?: 'Tidak ada informasi klinis tambahan dari dokter pengirim.' }}"
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('rad.hasil.update', $radiologyOrder) }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="simrs-card shadow-sm border-0">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary">
                        <i class="fa-solid fa-file-waveform"></i>
                        <span>Laporan Ekspertise Radiologi</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="mb-4">
                        <label class="form-label-custom fs-6">Temuan (Findings)</label>
                        <textarea name="temuan" class="form-control" rows="10" placeholder="Deskripsikan temuan anatomi, kelainan, atau observasi pada citra..." required>{{ old('temuan', $result?->temuan) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label-custom fs-6">Kesan (Conclusion / Impression)</label>
                        <textarea name="kesan" class="form-control fw-600" rows="4" placeholder="Ringkasan diagnosis radiologis..." required>{{ old('kesan', $result?->kesan) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label-custom">Integrasi PACS / Path Citra</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="fa-solid fa-link text-muted small"></i></span>
                                <input name="image_path" value="{{ old('image_path', $result?->image_path) }}" class="form-control text-mono small" placeholder="Contoh: radiology/2026/06/IMG-001.jpg">
                            </div>
                            <div class="form-text small italic">Inputkan path file citra atau URL integrasi PACS pihak ketiga.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="simrs-card sticky-top" style="top: 80px; z-index: 100;">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary small">
                        <i class="fa-solid fa-shield-check"></i>
                        <span>Verifikasi Medis</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle mb-4">
                        <div class="small fw-700 text-simrs-primary mb-1 text-uppercase">Instruksi:</div>
                        <p class="small text-simrs-primary-dark mb-0 lh-sm">Pastikan temuan dan kesan telah sesuai dengan identitas pasien di atas sebelum menyimpan hasil ekspertise.</p>
                    </div>
                    
                    <button class="btn btn-simrs-primary w-100 py-3 fw-800 shadow-sm border-0 mb-3">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>SIMPAN & VALIDASI
                    </button>
                    
                    <a href="{{ route('rad.antrian') }}" class="btn btn-simrs-outline w-100 fw-bold border-0 text-muted">
                        <i class="fa-solid fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>
                <div class="simrs-card-body bg-light border-top p-3 text-center">
                    <div class="small text-muted">© Digital Imaging System</div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

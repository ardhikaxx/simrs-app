@extends('layouts.app')

@section('title', 'Ekspertise Radiologi')
@section('page-title', 'Pemeriksaan Imaging')
@section('page-subtitle', 'Dokumentasi temuan klinis, interpretasi citra, dan kesimpulan radiologis')

@section('content')
@php($result = $radiologyOrder->result)

<div class="row g-4 mb-4">
    <!-- Ringkasan Pasien & Order -->
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
            <div class="card-body p-0">
                <div class="d-flex align-items-stretch bg-primary bg-gradient text-white">
                    <div class="p-4 d-flex align-items-center justify-content-center bg-white bg-opacity-10 border-end border-white border-opacity-10 flex-shrink-0" style="width: 100px;">
                        <i class="fa-solid fa-x-ray fs-2"></i>
                    </div>
                    <div class="p-4 flex-grow-1 min-w-0">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="badge bg-white text-primary fw-bold">{{ $radiologyOrder->no_order }}</span>
                            <h5 class="fw-bold mb-0 text-truncate">{{ $radiologyOrder->jenis_pemeriksaan }}</h5>
                        </div>
                        <div class="d-flex flex-wrap align-items-center gap-4 small fw-medium text-white-50">
                            <span><i class="fa-regular fa-calendar-check me-2"></i>{{ $radiologyOrder->ordered_at?->format('d/m/Y H:i') }}</span>
                            <span><i class="fa-solid fa-user-doctor me-2"></i>{{ $radiologyOrder->doctor?->display_name ?? 'Dokter Jaga' }}</span>
                        </div>
                    </div>
                    <div class="p-4 text-end d-flex flex-column justify-content-center flex-shrink-0">
                        <div class="small fw-semibold text-uppercase opacity-75 mb-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">Prioritas</div>
                        @if($radiologyOrder->prioritas === 'cito')
                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm fw-bold animate-pulse" style="letter-spacing: 0.5px;">
                                <i class="fa-solid fa-bolt-lightning me-1"></i>CITO
                            </span>
                        @else
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-bold" style="letter-spacing: 0.5px;">
                                RUTIN
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="small text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Identitas Pasien</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    {{ strtoupper(substr($radiologyOrder->encounter->patient->nama_pasien, 0, 1)) }}
                                </div>
                                <div class="min-w-0">
                                    <div class="fw-bold text-dark fs-6 text-truncate mb-1">{{ $radiologyOrder->encounter->patient->nama_pasien }}</div>
                                    <div class="small text-muted fw-medium text-truncate">
                                        <span class="font-monospace text-primary opacity-75">{{ $radiologyOrder->encounter->patient->no_rkm_medis }}</span> <span class="mx-1 opacity-25">|</span> {{ $radiologyOrder->encounter->patient->age }} Tahun <span class="mx-1 opacity-25">|</span> {{ $radiologyOrder->encounter->patient->jenis_kelamin }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 border-start-md border-light ps-md-4">
                            <div class="small text-muted fw-semibold text-uppercase mb-2" style="font-size: 0.7rem; letter-spacing: 0.5px;">Unit / Departemen Asal</div>
                            <div class="fw-bold text-dark mb-1">{{ $radiologyOrder->encounter->department->nama_depart }}</div>
                            <div class="small text-muted fw-medium d-flex align-items-center gap-2">
                                <i class="fa-solid fa-hospital-user text-primary opacity-50"></i>
                                {{ $radiologyOrder->encounter->jenis_kunjungan === 'rawat_inap' ? 'Rawat Inap' : 'Rawat Jalan' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Klinis -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white h-100 overflow-hidden">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <div class="d-flex align-items-center gap-2 text-primary">
                    <i class="fa-solid fa-clipboard-question"></i>
                    <span class="fw-semibold small text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Indikasi & Catatan Klinis</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="p-3 rounded-4 bg-light border-0 small text-muted lh-base position-relative h-100" style="min-height: 100px;">
                    <i class="fa-solid fa-quote-left position-absolute top-0 start-0 m-3 opacity-10 fs-2"></i>
                    <span class="position-relative fst-italic ms-3">{{ $radiologyOrder->catatan_klinis ?: 'Tidak ada catatan klinis tambahan dari dokter pengirim.' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('rad.hasil.update', $radiologyOrder) }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom border-light p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-file-waveform fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Laporan Ekspertise Radiologi</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small text-uppercase mb-2" style="letter-spacing: 0.5px;">Temuan (Findings)</label>
                        <textarea name="temuan" class="form-control bg-light border-light shadow-none focus-ring-0 py-3 px-4 rounded-3" rows="10" placeholder="Deskripsikan temuan anatomi, kelainan, atau observasi pada citra..." required>{{ old('temuan', $result?->temuan) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold text-muted small text-uppercase mb-2" style="letter-spacing: 0.5px;">Kesan (Conclusion / Impression)</label>
                        <textarea name="kesan" class="form-control bg-primary bg-opacity-10 border-0 fw-medium shadow-none focus-ring-0 py-3 px-4 text-dark rounded-3" rows="4" placeholder="Ringkasan diagnosis radiologis..." required>{{ old('kesan', $result?->kesan) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label fw-semibold text-muted small text-uppercase mb-2" style="letter-spacing: 0.5px;">Integrasi PACS / Path Citra</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light text-muted"><i class="fa-solid fa-link"></i></span>
                                <input name="image_path" value="{{ old('image_path', $result?->image_path) }}" class="form-control bg-light border-light shadow-none focus-ring-0 font-monospace" placeholder="Contoh: radiology/2026/06/IMG-001.jpg">
                            </div>
                            <div class="form-text small mt-2 opacity-75">Inputkan path file citra atau URL integrasi PACS pihak ketiga untuk akses visual.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 90px; z-index: 100;">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-user-check fs-6"></i>
                    </div>
                    <h6 class="fw-bold text-dark mb-0">Verifikasi Medis</h6>
                </div>

                <div class="alert alert-info bg-info bg-opacity-10 border-0 rounded-4 p-3 mb-4">
                    <div class="d-flex gap-3">
                        <i class="fa-solid fa-circle-info text-info fs-5 mt-1 flex-shrink-0"></i>
                        <p class="small text-dark fw-medium mb-0 lh-sm">
                            Pastikan interpretasi citra telah divalidasi oleh Dokter Spesialis Radiologi sebelum menekan tombol simpan.
                        </p>
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm transition-hover mb-3">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i>SIMPAN & VALIDASI
                </button>
                
                <a href="{{ route('rad.antrian') }}" class="btn btn-light border border-light w-100 fw-bold py-3 rounded-pill text-muted hover-bg-gray transition-hover">
                    KEMBALI KE DAFTAR
                </a>

                <div class="mt-4 pt-4 border-top border-light text-center">
                    <img src="https://ui-avatars.com/api/?name=RAD&background=F1F5F9&color=64748b&bold=true" class="rounded-circle mb-2" style="width: 40px; height: 40px;" alt="Avatar">
                    <div class="small text-muted fw-bold">Radiology Information System</div>
                    <div class="small text-muted fw-medium" style="font-size: 0.7rem;">v1.2.0 &bull; {{ now()->year }}</div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .7; } }
</style>
@endsection

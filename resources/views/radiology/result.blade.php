@extends('layouts.app')

@section('title', 'Ekspertise Radiologi')
@section('page-title', 'Pemeriksaan Imaging')
@section('page-subtitle', 'Dokumentasi temuan klinis, interpretasi citra, dan kesimpulan radiologis')

@section('content')
@php($result = $radiologyOrder->result)

<div class="row g-4 mb-4">
    <!-- Ringkasan Pasien & Order -->
    <div class="col-lg-8">
        <div class="card-premium border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
            <div class="card-body p-0">
                <div class="d-flex align-items-stretch bg-primary bg-gradient text-white">
                    <div class="p-4 d-flex align-items-center justify-content-center bg-white bg-opacity-10 border-end border-white border-opacity-10" style="width: 100px;">
                        <i class="fa-solid fa-x-ray fs-1"></i>
                    </div>
                    <div class="p-4 grow">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <span class="badge bg-white text-primary fw-800">{{ $radiologyOrder->no_order }}</span>
                            <h5 class="fw-800 mb-0">{{ $radiologyOrder->jenis_pemeriksaan }}</h5>
                        </div>
                        <div class="d-flex align-items-center gap-4 small opacity-75 fw-bold">
                            <span><i class="fa-regular fa-calendar-check me-1"></i>{{ $radiologyOrder->ordered_at?->format('d/m/Y H:i') }}</span>
                            <span><i class="fa-solid fa-user-doctor me-1"></i>{{ $radiologyOrder->doctor?->display_name ?? 'Dokter Jaga' }}</span>
                        </div>
                    </div>
                    <div class="p-4 text-end d-flex flex-column justify-content-center">
                        <div class="small fw-bold text-uppercase tracking-wider opacity-75 mb-1" style="font-size: 0.6rem;">Prioritas</div>
                        @if($radiologyOrder->prioritas === 'cito')
                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm fw-800 animate-pulse">
                                <i class="fa-solid fa-bolt-lightning me-1"></i>CITO
                            </span>
                        @else
                            <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-800">
                                RUTIN
                            </span>
                        @endif
                    </div>
                </div>
                <div class="p-4">
                    <div class="row g-4">
                        <div class="col-md-7">
                            <div class="small text-muted fw-bold text-uppercase tracking-wider mb-2" style="font-size: 0.65rem;">Identitas Pasien</div>
                            <div class="d-flex align-items-center gap-3">
                                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-800" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                    {{ strtoupper(substr($radiologyOrder->encounter->patient->nama_pasien, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="fw-800 text-slate fs-5">{{ $radiologyOrder->encounter->patient->nama_pasien }}</div>
                                    <div class="small text-muted fw-medium">
                                        {{ $radiologyOrder->encounter->patient->no_rkm_medis }} <span class="mx-2 opacity-25">|</span> {{ $radiologyOrder->encounter->patient->age }} Tahun <span class="mx-2 opacity-25">|</span> {{ $radiologyOrder->encounter->patient->jenis_kelamin }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-5 border-start ps-md-4">
                            <div class="small text-muted fw-bold text-uppercase tracking-wider mb-2" style="font-size: 0.65rem;">Unit / Departemen Asal</div>
                            <div class="fw-800 text-slate mb-1">{{ $radiologyOrder->encounter->department->nama_depart }}</div>
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
        <div class="card-premium border-0 bg-white h-100 overflow-hidden">
            <div class="card-header bg-white border-0 p-4 pb-0">
                <div class="d-flex align-items-center gap-2 text-primary">
                    <i class="fa-solid fa-clipboard-question"></i>
                    <span class="fw-800 small text-uppercase tracking-wider" style="font-size: 0.7rem;">Indikasi & Catatan Klinis</span>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="p-3 rounded-4 bg-light border-0 italic small text-muted lh-base position-relative" style="min-height: 100px;">
                    <i class="fa-solid fa-quote-left position-absolute top-0 start-0 m-2 opacity-10 fs-2"></i>
                    <span class="position-relative">{{ $radiologyOrder->catatan_klinis ?: 'Tidak ada catatan klinis tambahan dari dokter pengirim.' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('rad.hasil.update', $radiologyOrder) }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="card-premium border-0 bg-white overflow-hidden mb-4">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-file-waveform fs-6"></i>
                        </div>
                        <h5 class="fw-800 text-slate mb-0">Laporan Ekspertise Radiologi</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="mb-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider mb-2">Temuan (Findings)</label>
                        <textarea name="temuan" class="form-control bg-light border-0 py-3" rows="12" placeholder="Deskripsikan temuan anatomi, kelainan, atau observasi pada citra..." required>{{ old('temuan', $result?->temuan) }}</textarea>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider mb-2">Kesan (Conclusion / Impression)</label>
                        <textarea name="kesan" class="form-control bg-teal-soft border-0 fw-bold py-3 text-primary" rows="4" placeholder="Ringkasan diagnosis radiologis..." required>{{ old('kesan', $result?->kesan) }}</textarea>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label class="form-label fw-800 text-slate small text-uppercase tracking-wider mb-2">Integrasi PACS / Path Citra</label>
                            <div class="header-search bg-light border-0 w-100 max-w-none px-3">
                                <i class="fa-solid fa-link opacity-40"></i>
                                <input name="image_path" value="{{ old('image_path', $result?->image_path) }}" class="font-monospace" placeholder="Contoh: radiology/2026/06/IMG-001.jpg">
                            </div>
                            <div class="form-text small italic mt-2">Inputkan path file citra atau URL integrasi PACS pihak ketiga untuk akses visual.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card-premium border-0 bg-white p-4 sticky-top" style="top: 100px; z-index: 100;">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-user-check fs-6"></i>
                    </div>
                    <h6 class="fw-800 text-slate mb-0">Verifikasi Medis</h6>
                </div>

                <div class="alert alert-info bg-blue-soft border-0 rounded-4 p-3 mb-4">
                    <div class="d-flex gap-3">
                        <i class="fa-solid fa-circle-info text-blue fs-4"></i>
                        <p class="small text-dark fw-medium mb-0 lh-sm">
                            Pastikan interpretasi citra telah divalidasi oleh Dokter Spesialis Radiologi sebelum menekan tombol simpan.
                        </p>
                    </div>
                </div>
                
                <button class="btn btn-primary w-100 py-3 fw-800 rounded-pill shadow-sm transition-bounce-hover mb-3">
                    <i class="fa-solid fa-cloud-arrow-up me-2"></i>SIMPAN & VALIDASI
                </button>
                
                <a href="{{ route('rad.antrian') }}" class="btn btn-light border w-100 fw-800 py-3 rounded-pill">
                    KEMBALI KE DAFTAR
                </a>

                <div class="mt-4 pt-4 border-top text-center">
                    <img src="https://ui-avatars.com/api/?name=RAD&background=F1F5F9&color=94A3B8&bold=true" class="rounded-circle mb-2" style="width: 40px;">
                    <div class="small text-muted fw-bold">Radiology Information System</div>
                    <div class="small text-muted fw-medium" style="font-size: 0.65rem;">v1.2.0 • {{ now()->year }}</div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .bg-teal-soft { background: #F0FDFA; }
    .bg-blue-soft { background: #EFF6FF; }
    .text-blue { color: #3B82F6; }
    .text-slate { color: #1E293B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.02); }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .7; } }
</style>
@endsection

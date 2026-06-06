@extends('layouts.app')

@section('title', 'Casemix Monitoring')
@section('page-title', 'Monitoring Casemix & INA-CBG')
@section('page-subtitle', 'Analisis utilisasi biaya riil terhadap plafon tarif klaim JKN')

@section('content')
<!-- Filter & Action Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-3">
        <form class="row g-3 align-items-center" method="GET">
            <div class="col-md-5">
                <div class="input-group input-group-lg shadow-none">
                    <span class="input-group-text bg-light border-end-0 text-muted px-4 rounded-start-pill"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-light border-start-0 ps-0 shadow-none fs-6" placeholder="Cari nama pasien, No. RM, atau kode ICD-10...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="utilisasi" class="form-select form-select-lg bg-light shadow-none fs-6 rounded-pill border-light-subtle">
                    <option value="">Semua Status Utilisasi</option>
                    <option value="aman" @selected(request('utilisasi') === 'aman')>Aman (< 80%)</option>
                    <option value="peringatan" @selected(request('utilisasi') === 'peringatan')>Peringatan (80-100%)</option>
                    <option value="kritis" @selected(request('utilisasi') === 'kritis')>Kritis (> 100%)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-dark btn-lg fw-bold shadow-sm rounded-pill px-4 fs-6 w-100">
                    <i class="fa-solid fa-filter me-2"></i>Filter Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm rounded-4">
    <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Daftar Kunjungan BPJS & Estimasi Klaim</h5>
                <p class="text-muted small mb-0">Total {{ $invoices->total() }} record untuk dimonitoring</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
            <thead class="bg-light bg-opacity-75">
                <tr class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    <th class="border-0 px-4 py-3">Data Pasien</th>
                    <th class="border-0 py-3">Diagnosis (ICD-10)</th>
                    <th class="border-0 py-3 text-end">Biaya Riil RS</th>
                    <th class="border-0 py-3 text-end">Tarif INA-CBG</th>
                    <th class="border-0 py-3 text-center">Tingkat Utilisasi</th>
                    <th class="border-0 py-3 text-center">Status</th>
                    <th class="border-0 py-3">No. SEP</th>
                    <th class="border-0 px-4 py-3 text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
            @forelse($invoices as $invoice)
                @php
                    $percent = $invoice->tarif_ina_cbg > 0 ? ($invoice->total_tagihan / $invoice->tarif_ina_cbg) * 100 : 0;
                    $statusProps = match(true) {
                        $percent > 100 => ['color' => 'danger', 'icon' => 'fa-arrow-trend-up', 'bg' => 'bg-danger'],
                        $percent > 85 => ['color' => 'warning', 'icon' => 'fa-circle-exclamation', 'bg' => 'bg-warning'],
                        default => ['color' => 'success', 'icon' => 'fa-check', 'bg' => 'bg-success']
                    };
                @endphp
                <tr>
                    <td class="px-4 py-3">
                        <div class="fw-bold text-dark mb-1">{{ $invoice->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>RM: {{ $invoice->encounter->patient->no_rkm_medis }} <span class="mx-1 text-black-50">&bull;</span> {{ $invoice->encounter->no_registrasi }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-25 px-2 py-1 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-stethoscope me-1 text-primary opacity-75"></i>{{ $invoice->encounter->medicalRecord?->icd10_primer ?: '???' }}
                            </span>
                        </div>
                        <div class="small text-truncate text-muted fw-medium" style="max-width: 180px;" title="{{ $invoice->encounter->medicalRecord?->diagnosis_kerja }}">
                            {{ $invoice->encounter->medicalRecord?->diagnosis_kerja ?: 'Belum diinput' }}
                        </div>
                    </td>
                    <td class="text-end">
                        <div class="fw-bold text-dark font-monospace">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-end">
                        <div class="fw-bolder text-primary font-monospace">Rp {{ number_format($invoice->tarif_ina_cbg, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-center" style="width: 150px;">
                        <div class="d-flex flex-column align-items-center">
                            <div class="fw-bolder text-{{ $statusProps['color'] }} mb-1" style="font-size: 0.85rem;">
                                {{ number_format($percent, 1, ',', '.') }}%
                            </div>
                            <div class="progress w-100 bg-light rounded-pill shadow-inner" style="height: 6px;">
                                <div class="progress-bar {{ $statusProps['bg'] }} rounded-pill" role="progressbar" style="width: {{ min($percent, 100) }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $statusProps['bg'] }} bg-opacity-10 text-{{ $statusProps['color'] }} rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fa-solid {{ $statusProps['icon'] }} me-1"></i>{{ strtoupper($invoice->status_utilisasi ?: 'DRAFT') }}
                        </span>
                    </td>
                    <td>
                        <div class="text-primary font-monospace fw-bold small bg-primary bg-opacity-10 px-2 py-1 rounded border border-primary border-opacity-25 d-inline-block">{{ $invoice->encounter->sepDocument?->no_sep ?: 'BELUM TERBIT' }}</div>
                    </td>
                    <td class="px-4 py-3 text-end">
                        <a href="{{ route('casemix.simulate', $invoice->encounter) }}" class="btn btn-sm btn-primary bg-gradient shadow-sm rounded-3 px-3 py-2 fw-semibold transition-hover">
                            <i class="fa-solid fa-chart-pie me-1"></i> Simulasi
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-calculator fs-1 text-muted opacity-50"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Data Kosong</h5>
                        <p class="text-muted small">Tidak ada data kunjungan BPJS untuk dianalisis utilitasnya.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($invoices->hasPages())
        <div class="card-footer bg-white border-top p-4 d-flex justify-content-center">
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 6px 12px rgba(11, 100, 119, 0.2) !important; }
    .shadow-inner { box-shadow: inset 0 1px 2px rgba(0,0,0,0.075); }
</style>
@endsection

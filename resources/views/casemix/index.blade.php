@extends('layouts.app')

@section('title', 'Casemix Monitoring')
@section('page-title', 'Monitoring Casemix & INA-CBG')
@section('page-subtitle', 'Analisis utilisasi biaya riil terhadap plafon tarif klaim JKN')

@section('content')
<!-- Filter & Action Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <form class="row g-3 align-items-center" method="GET">
            <div class="col-md-5">
                <div class="input-group bg-light rounded-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0 py-2" placeholder="Cari nama pasien, No. RM, atau kode ICD-10...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="utilisasi" class="form-select bg-light border-light shadow-none focus-ring-0 fw-medium py-2 rounded-3 text-muted">
                    <option value="">Semua Status Utilisasi</option>
                    <option value="aman" @selected(request('utilisasi') === 'aman')>Aman (< 80%)</option>
                    <option value="peringatan" @selected(request('utilisasi') === 'peringatan')>Peringatan (80-100%)</option>
                    <option value="kritis" @selected(request('utilisasi') === 'kritis')>Kritis (> 100%)</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-3 px-4 py-2 w-100 transition-hover">
                    <i class="fa-solid fa-filter me-2"></i>Filter Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-file-invoice-dollar fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Daftar Kunjungan BPJS & Estimasi Klaim</h5>
                <p class="text-muted small mb-0 fw-medium">Total {{ $invoices->total() }} record untuk dimonitoring</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">Data Pasien</th>
                    <th class="py-3">Diagnosis (ICD-10)</th>
                    <th class="py-3 text-end">Biaya Riil RS</th>
                    <th class="py-3 text-end">Tarif INA-CBG</th>
                    <th class="py-3 text-center">Tingkat Utilisasi</th>
                    <th class="py-3 text-center">Status</th>
                    <th class="py-3">No. SEP</th>
                    <th class="pe-4 py-3 text-end rounded-end">Tindakan</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($invoices as $invoice)
                @php
                    $percent = $invoice->tarif_ina_cbg > 0 ? ($invoice->total_tagihan / $invoice->tarif_ina_cbg) * 100 : 0;
                    $statusProps = match(true) {
                        $percent > 100 => ['color' => 'danger', 'icon' => 'fa-arrow-trend-up', 'bg' => 'bg-danger'],
                        $percent > 85 => ['color' => 'warning', 'icon' => 'fa-circle-exclamation', 'bg' => 'bg-warning'],
                        default => ['color' => 'success', 'icon' => 'fa-check', 'bg' => 'bg-success']
                    };
                @endphp
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-dark mb-1">{{ $invoice->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>RM: {{ $invoice->encounter->patient->no_rkm_medis }} <span class="mx-1 text-black-50">&bull;</span> {{ $invoice->encounter->no_registrasi }}</div>
                    </td>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <span class="badge bg-secondary bg-opacity-10 text-dark border border-secondary border-opacity-25 px-2 py-1 fw-bold" style="font-size: 0.75rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-stethoscope me-1 text-primary opacity-75"></i>{{ $invoice->encounter->medicalRecord?->icd10_primer ?: '???' }}
                            </span>
                        </div>
                        <div class="small text-truncate text-muted fw-medium" style="max-width: 180px;" title="{{ $invoice->encounter->medicalRecord?->diagnosis_kerja }}">
                            {{ $invoice->encounter->medicalRecord?->diagnosis_kerja ?: 'Belum diinput' }}
                        </div>
                    </td>
                    <td class="text-end py-3">
                        <div class="fw-semibold text-dark font-monospace">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-end py-3">
                        <div class="fw-bold text-primary font-monospace">Rp {{ number_format($invoice->tarif_ina_cbg, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-center py-3" style="width: 150px;">
                        <div class="d-flex flex-column align-items-center">
                            <div class="fw-bold text-{{ $statusProps['color'] }} mb-1" style="font-size: 0.85rem;">
                                {{ number_format($percent, 1, ',', '.') }}%
                            </div>
                            <div class="progress w-100 bg-light rounded-pill border border-light" style="height: 6px;">
                                <div class="progress-bar {{ $statusProps['bg'] }} rounded-pill" role="progressbar" style="width: {{ min($percent, 100) }}%" aria-valuenow="{{ $percent }}" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </td>
                    <td class="text-center py-3">
                        <span class="badge {{ $statusProps['bg'] }} bg-opacity-10 text-{{ $statusProps['color'] }} rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fa-solid {{ $statusProps['icon'] }} me-1"></i>{{ strtoupper($invoice->status_utilisasi ?: 'DRAFT') }}
                        </span>
                    </td>
                    <td class="py-3">
                        <div class="text-primary font-monospace fw-semibold small bg-primary bg-opacity-10 px-2 py-1 rounded border border-primary border-opacity-10 d-inline-block">{{ $invoice->encounter->sepDocument?->no_sep ?: 'BELUM TERBIT' }}</div>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('casemix.simulate', $invoice->encounter) }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 py-2 fw-semibold transition-hover">
                            <i class="fa-solid fa-chart-pie me-1"></i> Simulasi
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-calculator fs-2 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Data Kosong</h6>
                        <p class="text-muted small mb-0">Tidak ada data kunjungan BPJS untuk dianalisis utilitasnya.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($invoices->hasPages())
        <div class="p-4 border-top border-light bg-white rounded-bottom-4 d-flex justify-content-center">
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
</style>
@endsection

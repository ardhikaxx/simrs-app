@extends('layouts.app')

@section('title', 'Casemix Monitoring')
@section('page-title', 'Monitoring Casemix & INA-CBG')
@section('page-subtitle', 'Analisis utilisasi biaya riil terhadap plafon tarif klaim JKN')

@section('content')
<div class="page-header-bar mb-3">
    <form class="d-flex gap-2 flex-grow-1" method="GET">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari nama pasien, No. RM, atau kode ICD-10...">
        </div>
        <select name="utilisasi" class="form-select shadow-sm" style="max-width: 200px;">
            <option value="">Semua Utilisasi</option>
            <option value="aman" @selected(request('utilisasi') === 'aman')>Aman (< 80%)</option>
            <option value="peringatan" @selected(request('utilisasi') === 'peringatan')>Peringatan (80-100%)</option>
            <option value="kritis" @selected(request('utilisasi') === 'kritis')>Kritis (> 100%)</option>
        </select>
        <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
    </form>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white border-bottom-0">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <span>Daftar Kunjungan BPJS & Estimasi Klaim</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Data Pasien</th>
                    <th>Diagnosis & ICD-10</th>
                    <th class="text-end">Biaya Riil RS</th>
                    <th class="text-end">Tarif INA-CBG</th>
                    <th class="text-center">Utilisasi (%)</th>
                    <th class="text-center">Status</th>
                    <th>No. SEP</th>
                    <th class="pe-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                @php
                    $percent = $invoice->tarif_ina_cbg > 0 ? ($invoice->total_tagihan / $invoice->tarif_ina_cbg) * 100 : 0;
                    $statusClass = $percent > 100 ? 'status-kritis' : ($percent > 85 ? 'status-peringatan' : 'status-aman');
                @endphp
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold text-simrs-gray-900">{{ $invoice->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.75rem;">RM: {{ $invoice->encounter->patient->no_rkm_medis }} | {{ $invoice->encounter->no_registrasi }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1" style="font-size: 0.7rem; font-weight: 700;">
                                {{ $invoice->encounter->medicalRecord?->icd10_primer ?: '???' }}
                            </span>
                            <div class="small text-truncate text-muted" style="max-width: 150px;">{{ $invoice->encounter->medicalRecord?->diagnosis_kerja ?: 'Belum diinput' }}</div>
                        </div>
                    </td>
                    <td class="text-end fw-bold text-simrs-gray-800">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-end fw-bold text-simrs-primary">Rp {{ number_format($invoice->tarif_ina_cbg, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <div class="fw-800 {{ $percent > 100 ? 'text-danger' : ($percent > 85 ? 'text-warning' : 'text-success') }}">
                            {{ number_format($percent, 1, ',', '.') }}%
                        </div>
                        <div class="progress mt-1" style="height: 4px; width: 60px; margin: 0 auto;">
                            <div class="progress-bar bg-{{ $percent > 100 ? 'danger' : ($percent > 85 ? 'warning' : 'success') }}" style="width: {{ min($percent, 100) }}%"></div>
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge-status {{ $statusClass }} shadow-none small">
                            {{ $invoice->status_utilisasi ?: 'Draft' }}
                        </span>
                    </td>
                    <td>
                        <div class="text-mono small fw-bold">{{ $invoice->encounter->sepDocument?->no_sep ?: '-' }}</div>
                    </td>
                    <td class="pe-4 text-center">
                        <a href="{{ route('casemix.simulate', $invoice->encounter) }}" class="btn btn-sm btn-simrs-outline shadow-sm px-3">
                            <i class="fa-solid fa-chart-pie me-1"></i>Simulasi
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="fa-solid fa-calculator fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Tidak ada data kunjungan BPJS untuk monitoring casemix.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

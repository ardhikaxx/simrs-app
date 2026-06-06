@extends('layouts.app')

@section('title', 'Casemix')
@section('page-title', 'Monitoring Casemix')
@section('page-subtitle', 'Utilisasi biaya riil terhadap ceiling INA-CBG')

@section('content')
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Invoice</th><th>Pasien</th><th>Diagnosis</th><th>Total Riil</th><th>Tarif INA-CBG</th><th>Status</th><th>SEP</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td class="text-mono">{{ $invoice->no_invoice }}</td>
                    <td><strong>{{ $invoice->encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $invoice->encounter->no_registrasi }}</div></td>
                    <td>{{ $invoice->encounter->medicalRecord?->icd10_primer ?: '-' }}<div class="small text-muted">{{ $invoice->encounter->medicalRecord?->diagnosis_kerja }}</div></td>
                    <td>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($invoice->tarif_ina_cbg, 0, ',', '.') }}</td>
                    <td><span class="badge-status status-{{ $invoice->status_utilisasi ?: 'draft' }}">{{ $invoice->status_utilisasi ?: 'Belum' }}</span></td>
                    <td>{{ $invoice->encounter->sepDocument?->no_sep ?: '-' }}</td>
                    <td><a href="{{ route('casemix.simulate', $invoice->encounter) }}" class="btn btn-sm btn-simrs-primary">Simulasi</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada invoice BPJS.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $invoices->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

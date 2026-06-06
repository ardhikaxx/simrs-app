@extends('layouts.app')

@section('title', 'Billing')
@section('page-title', 'Antrian Kasir & Billing')
@section('page-subtitle', 'Invoice, status pembayaran, dan tagihan pasien')

@section('content')
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Invoice</th><th>Pasien</th><th>Unit</th><th>Penjamin</th><th>Total</th><th>Dibayar</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td class="text-mono">{{ $invoice->no_invoice }}<div class="small text-muted">{{ $invoice->issued_at?->format('d/m/Y H:i') }}</div></td>
                    <td><strong>{{ $invoice->encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $invoice->encounter->patient->no_rkm_medis }}</div></td>
                    <td>{{ $invoice->encounter->department?->nama_depart }}</td>
                    <td>{{ strtoupper($invoice->metode_penjamin) }}</td>
                    <td>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</td>
                    <td><span class="badge-status status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span></td>
                    <td><a href="{{ route('keuangan.invoice.show', $invoice) }}" class="btn btn-sm btn-simrs-primary"><i class="fa-solid fa-file-invoice-dollar me-1"></i>Invoice</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada invoice.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $invoices->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

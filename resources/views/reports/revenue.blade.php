@extends('layouts.app')

@section('title', 'Laporan Pendapatan')
@section('page-title', 'Laporan Pendapatan')
@section('page-subtitle', 'Rekap invoice, pembayaran, dan piutang per penjamin')

@section('content')
<form method="GET" class="simrs-card">
    <div class="simrs-card-body d-flex flex-wrap gap-2 align-items-end">
        <div><label class="form-label-custom">Dari</label><input type="date" name="from" value="{{ $from }}" class="form-control"></div>
        <div><label class="form-label-custom">Sampai</label><input type="date" name="to" value="{{ $to }}" class="form-control"></div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-filter me-1"></i>Filter</button>
    </div>
</form>
<div class="row g-3 mb-3">
    @foreach($summary as $item)
        <div class="col-md-4">
            <div class="kpi-card">
                <div class="kpi-label">{{ strtoupper($item->metode_penjamin) }} - {{ ucfirst($item->status) }}</div>
                <div class="kpi-value">Rp {{ number_format($item->total_dibayar, 0, ',', '.') }}</div>
                <div class="small text-muted">{{ $item->total_invoice }} invoice dari Rp {{ number_format($item->total_tagihan, 0, ',', '.') }}</div>
            </div>
        </div>
    @endforeach
</div>
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Invoice</th><th>Pasien</th><th>Unit</th><th>Penjamin</th><th>Total</th><th>Dibayar</th><th>Piutang</th><th>Status</th></tr></thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td class="text-mono">{{ $invoice->no_invoice }}</td>
                    <td>{{ $invoice->encounter->patient->nama_pasien }}</td>
                    <td>{{ $invoice->encounter->department?->nama_depart }}</td>
                    <td>{{ strtoupper($invoice->metode_penjamin) }}</td>
                    <td>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</td>
                    <td>Rp {{ number_format($invoice->outstanding, 0, ',', '.') }}</td>
                    <td><span class="badge-status status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Belum ada invoice dalam periode ini.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $invoices->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

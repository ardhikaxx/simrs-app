@extends('layouts.app')

@section('title', 'Laporan Pendapatan')
@section('page-title', 'Analisis Keuangan & Pendapatan')
@section('page-subtitle', 'Rekapitulasi invoice, realisasi pembayaran, dan pemantauan piutang')

@section('content')
<div class="simrs-card mb-4">
    <div class="simrs-card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label-custom">Periode Awal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-day text-muted small"></i></span>
                    <input type="date" name="from" value="{{ $from }}" class="form-control">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Periode Akhir</label>
                <div class="input-group">
                    <span class="input-group-text bg-light"><i class="fa-solid fa-calendar-week text-muted small"></i></span>
                    <input type="date" name="to" value="{{ $to }}" class="form-control">
                </div>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-simrs-primary shadow-sm px-4">
                    <i class="fa-solid fa-filter me-2"></i>Filter Data
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-3 mb-4">
    @foreach($summary as $item)
        <div class="col-md-4">
            <div class="simrs-card h-100 border-0 shadow-sm bg-white overflow-hidden">
                <div class="simrs-card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-wallet"></i>
                        </div>
                        <span class="badge-status status-{{ $item->status }} px-2 py-0" style="font-size: 0.65rem;">
                            {{ strtoupper($item->status) }}
                        </span>
                    </div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider mb-1">{{ $item->metode_penjamin }}</div>
                    <div class="h4 fw-800 text-simrs-gray-900 mb-2">Rp {{ number_format($item->total_dibayar, 0, ',', '.') }}</div>
                    <div class="small text-muted border-top pt-2">
                        <span class="fw-bold">{{ $item->total_invoice }}</span> Invoice Terbit
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="simrs-card shadow-sm border-0">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-file-invoice-dollar"></i>
            <span>Rincian Transaksi Invoice Penjamin</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. Invoice</th>
                    <th>Informasi Pasien</th>
                    <th>Penjamin</th>
                    <th class="text-end">Total Tagihan</th>
                    <th class="text-end">Telah Dibayar</th>
                    <th class="text-end">Sisa (Piutang)</th>
                    <th class="pe-4 text-center">Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-800 text-simrs-primary small">{{ $invoice->no_invoice }}</div>
                        <div class="small text-muted" style="font-size: 0.65rem;">{{ $invoice->issued_at?->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div class="fw-600 text-simrs-gray-800">{{ $invoice->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $invoice->encounter->department?->nama_depart }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-simrs-secondary border border-simrs-gray-200 px-2 py-0" style="font-size: 0.65rem;">
                            {{ strtoupper($invoice->metode_penjamin) }}
                        </span>
                    </td>
                    <td class="text-end fw-bold">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-end text-success fw-bold">Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</td>
                    <td class="text-end text-danger fw-bold">Rp {{ number_format($invoice->outstanding, 0, ',', '.') }}</td>
                    <td class="pe-4 text-center">
                        <span class="badge-status status-{{ $invoice->status }} shadow-none py-1 px-3" style="font-size: 0.7rem;">
                            {{ strtoupper($invoice->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fa-solid fa-receipt fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Data pendapatan tidak ditemukan untuk periode ini.</div>
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

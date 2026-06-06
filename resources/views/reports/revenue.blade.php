@extends('layouts.app')

@section('title', 'Laporan Pendapatan')
@section('page-title', 'Analisis Keuangan & Pendapatan')
@section('page-subtitle', 'Rekapitulasi invoice, realisasi pembayaran, dan pemantauan piutang')

@section('content')
<div class="card border-0 shadow-sm rounded-4 mb-4">
    <div class="card-body p-4">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-4 col-lg-3">
                <label class="form-label text-muted fw-semibold small mb-1">Periode Awal</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-day text-muted"></i></span>
                    <input type="date" name="from" value="{{ $from }}" class="form-control border-start-0 ps-0 shadow-none focus-ring-0">
                </div>
            </div>
            <div class="col-md-4 col-lg-3">
                <label class="form-label text-muted fw-semibold small mb-1">Periode Akhir</label>
                <div class="input-group">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-week text-muted"></i></span>
                    <input type="date" name="to" value="{{ $to }}" class="form-control border-start-0 ps-0 shadow-none focus-ring-0">
                </div>
            </div>
            <div class="col-md-4 col-lg-6 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-medium px-4 shadow-sm rounded-3">
                    <i class="fa-solid fa-filter me-2"></i>Filter Data
                </button>
            </div>
        </form>
    </div>
</div>

<div class="row g-4 mb-4">
    @foreach($summary as $item)
        @php
            $statusColors = [
                'lunas' => 'success',
                'sebagian' => 'warning',
                'belum_bayar' => 'danger'
            ];
            $color = $statusColors[$item->status] ?? 'secondary';
        @endphp
        <div class="col-md-4">
            <div class="card h-100 border-0 shadow-sm rounded-4 transition-hover">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-wallet fs-5"></i>
                        </div>
                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-2 py-1 fw-semibold small" style="letter-spacing: 0.5px;">
                            {{ strtoupper(str_replace('_', ' ', $item->status)) }}
                        </span>
                    </div>
                    <div class="small fw-semibold text-muted text-uppercase tracking-wider mb-1" style="letter-spacing: 0.5px;">{{ str_replace('_', ' ', $item->metode_penjamin) }}</div>
                    <div class="h3 fw-bold text-dark mb-3">Rp {{ number_format($item->total_dibayar, 0, ',', '.') }}</div>
                    <div class="small text-muted border-top border-light pt-3 d-flex align-items-center gap-2">
                        <span class="badge bg-light text-dark border">{{ $item->total_invoice }}</span> Invoice Diterbitkan
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="p-4 border-bottom border-light bg-white">
        <h6 class="fw-bold text-dark mb-0 d-flex align-items-center gap-2">
            <i class="fa-solid fa-file-invoice-dollar text-primary"></i>
            Rincian Transaksi Invoice Penjamin
        </h6>
    </div>
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Invoice</th>
                    <th class="py-3">Informasi Pasien</th>
                    <th class="py-3">Penjamin</th>
                    <th class="py-3 text-end">Total Tagihan</th>
                    <th class="py-3 text-end">Telah Dibayar</th>
                    <th class="py-3 text-end">Sisa (Piutang)</th>
                    <th class="pe-4 py-3 text-center rounded-end">Status</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($invoices as $invoice)
                @php
                    $statusColors = [
                        'lunas' => 'success',
                        'sebagian' => 'warning',
                        'belum_bayar' => 'danger'
                    ];
                    $color = $statusColors[$invoice->status] ?? 'secondary';
                @endphp
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-primary font-monospace small mb-1">{{ $invoice->no_invoice }}</div>
                        <div class="small text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $invoice->issued_at?->format('d/m/Y') ?? '-' }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $invoice->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted">{{ $invoice->encounter->department?->nama_depart }}</div>
                    </td>
                    <td class="py-3">
                        <span class="badge bg-light text-secondary border fw-medium px-2 py-1">
                            {{ strtoupper(str_replace('_', ' ', $invoice->metode_penjamin)) }}
                        </span>
                    </td>
                    <td class="text-end py-3 fw-semibold text-dark">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-end py-3 fw-bold text-success">Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</td>
                    <td class="text-end py-3 fw-bold text-danger">Rp {{ number_format($invoice->outstanding, 0, ',', '.') }}</td>
                    <td class="pe-4 py-3 text-center">
                        <span class="badge bg-{{ $color }} bg-opacity-10 text-{{ $color }} rounded-pill px-3 py-1 fw-semibold">
                            {{ strtoupper(str_replace('_', ' ', $invoice->status)) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-inline-flex bg-light p-3 rounded-circle mb-3">
                            <i class="fa-solid fa-receipt fs-3 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Data Kosong</h6>
                        <div class="text-muted small">Data pendapatan tidak ditemukan untuk periode ini.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($invoices->hasPages())
        <div class="p-3 border-top border-light bg-white rounded-bottom-4">
            {{ $invoices->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { background-color: #f8f9fa !important; transform: translateY(-3px); }
    table .transition-hover:hover { transform: none; }
</style>
@endsection

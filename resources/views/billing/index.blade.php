@extends('layouts.app')

@section('title', 'Billing & Kasir')
@section('page-title', 'Antrian Billing & Kasir')
@section('page-subtitle', 'Monitoring tagihan, pelunasan invoice, dan status penjaminan pasien')

@section('content')
<div class="page-header-bar mb-3">
    <form class="d-flex gap-2 flex-grow-1" method="GET">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari nomor invoice, nama pasien, atau No. RM...">
        </div>
        <select name="status" class="form-select shadow-sm" style="max-width: 180px;">
            <option value="">Semua Status</option>
            <option value="draft" @selected(request('status') === 'draft')>Draft</option>
            <option value="parsial" @selected(request('status') === 'parsial')>Parsial</option>
            <option value="lunas" @selected(request('status') === 'lunas')>Lunas</option>
        </select>
        <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
    </form>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-cash-register"></i>
            <span>Daftar Tagihan Berjalan</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="ps-4">No. Invoice</th>
                    <th>Informasi Pasien</th>
                    <th>Unit Pelayanan</th>
                    <th>Penjamin</th>
                    <th class="text-end">Total Tagihan</th>
                    <th class="text-end">Telah Dibayar</th>
                    <th class="text-center">Status</th>
                    <th class="pe-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($invoices as $invoice)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-simrs-primary">{{ $invoice->no_invoice }}</div>
                        <div class="small text-muted">{{ $invoice->issued_at?->format('d/m/Y H:i') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-simrs-gray-900">{{ $invoice->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono">{{ $invoice->encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td>
                        <div class="small fw-600">{{ $invoice->encounter->department?->nama_depart }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ $invoice->encounter->doctor?->display_name ?? '-' }}</div>
                    </td>
                    <td>
                        <span class="badge bg-light text-simrs-secondary border border-simrs-gray-200 px-2 py-1" style="font-size: 0.72rem; font-weight: 700;">
                            <i class="fa-solid fa-shield-halved me-1 opacity-50"></i>{{ strtoupper($invoice->metode_penjamin) }}
                        </span>
                    </td>
                    <td class="text-end fw-bold">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
                    <td class="text-end text-success fw-bold">Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge-status status-{{ $invoice->status }} shadow-none">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </td>
                    <td class="pe-4 text-center">
                        <a href="{{ route('keuangan.invoice.show', $invoice) }}" class="btn btn-sm btn-simrs-primary shadow-sm px-3">
                            <i class="fa-solid fa-money-bill-transfer me-1"></i>Detail & Bayar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <i class="fa-solid fa-file-invoice fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Tidak ada data tagihan yang sesuai dengan kriteria.</div>
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

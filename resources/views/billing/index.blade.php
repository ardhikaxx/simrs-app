@extends('layouts.app')

@section('title', 'Billing & Kasir')
@section('page-title', 'Manajemen Billing & Kasir')
@section('page-subtitle', 'Monitoring tagihan, pelunasan invoice, dan status penjaminan pasien')

@section('content')
<!-- Filter & Action Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-3">
        <form class="row g-3 align-items-center" method="GET">
            <div class="col-md-5">
                <div class="input-group input-group-lg shadow-none">
                    <span class="input-group-text bg-light border-end-0 text-muted px-4 rounded-start-pill"><i class="fa-solid fa-magnifying-glass"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-light border-start-0 ps-0 shadow-none fs-6" placeholder="Cari nomor invoice, nama pasien, atau No. RM...">
                </div>
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select form-select-lg bg-light shadow-none fs-6 rounded-pill border-light-subtle">
                    <option value="">Semua Status Tagihan</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft (Belum Fix)</option>
                    <option value="parsial" @selected(request('status') === 'parsial')>Parsial (Belum Lunas)</option>
                    <option value="lunas" @selected(request('status') === 'lunas')>Lunas Selesai</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2">
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
                <i class="fa-solid fa-cash-register fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Daftar Tagihan Berjalan</h5>
                <p class="text-muted small mb-0">Total {{ $invoices->total() }} invoice terdata</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
            <thead class="bg-light bg-opacity-75">
                <tr class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                    <th class="border-0 px-4 py-3">No. Invoice & Waktu</th>
                    <th class="border-0 py-3">Pasien / RM</th>
                    <th class="border-0 py-3">Unit Pelayanan</th>
                    <th class="border-0 py-3 text-center">Penjamin</th>
                    <th class="border-0 py-3 text-end">Total Tagihan</th>
                    <th class="border-0 py-3 text-end">Terbayar</th>
                    <th class="border-0 py-3 text-center">Status</th>
                    <th class="border-0 px-4 py-3 text-end">Tindakan</th>
                </tr>
            </thead>
            <tbody class="border-top-0">
            @forelse($invoices as $invoice)
                @php
                    $statusProps = match($invoice->status) {
                        'lunas' => ['bg' => 'bg-success', 'text' => 'text-success', 'icon' => 'fa-circle-check'],
                        'parsial' => ['bg' => 'bg-warning', 'text' => 'text-warning', 'icon' => 'fa-clock-rotate-left'],
                        'draft' => ['bg' => 'bg-secondary', 'text' => 'text-secondary', 'icon' => 'fa-file-signature'],
                        default => ['bg' => 'bg-dark', 'text' => 'text-dark', 'icon' => 'fa-circle-dot']
                    };
                @endphp
                <tr>
                    <td class="px-4 py-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-file-invoice text-muted opacity-50"></i>
                            <span class="text-primary font-monospace fw-bold">{{ $invoice->no_invoice }}</span>
                        </div>
                        <div class="small text-muted" style="font-size: 0.75rem;">
                            <i class="fa-regular fa-clock me-1 opacity-50"></i>{{ $invoice->issued_at?->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td>
                        <div class="fw-bold text-dark mb-1">{{ $invoice->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>{{ $invoice->encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td>
                        <div class="fw-semibold text-dark mb-1">{{ $invoice->encounter->department?->nama_depart }}</div>
                        <div class="text-muted small" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-user-doctor me-1 opacity-50"></i>{{ $invoice->encounter->doctor?->display_name ?? 'Dokter Umum' }}
                        </div>
                    </td>
                    <td class="text-center">
                        <span class="badge bg-light text-dark border border-secondary border-opacity-25 px-2 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-shield-halved me-1 text-primary opacity-75"></i>{{ strtoupper($invoice->metode_penjamin) }}
                        </span>
                    </td>
                    <td class="text-end">
                        <div class="fw-bolder text-dark font-monospace">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-end">
                        <div class="fw-bolder text-success font-monospace">Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $statusProps['bg'] }} bg-opacity-10 {{ $statusProps['text'] }} rounded-pill px-3 py-2 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fa-solid {{ $statusProps['icon'] }} me-1"></i>{{ strtoupper($invoice->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-end">
                        <a href="{{ route('keuangan.invoice.show', $invoice) }}" class="btn btn-sm btn-primary bg-gradient shadow-sm rounded-3 px-3 py-2 fw-semibold transition-hover">
                            <i class="fa-solid fa-cash-register me-1"></i> Detail & Bayar
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-file-invoice-dollar fs-1 text-muted opacity-50"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-1">Tagihan Kosong</h5>
                        <p class="text-muted small">Tidak ada data tagihan yang sesuai dengan kriteria pencarian.</p>
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
</style>
@endsection
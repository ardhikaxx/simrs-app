@extends('layouts.app')

@section('title', 'Billing & Kasir')
@section('page-title', 'Manajemen Billing & Kasir')
@section('page-subtitle', 'Monitoring tagihan, pelunasan invoice, dan status penjaminan pasien')

@section('content')
<!-- Filter & Action Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <form class="row g-3 align-items-center" method="GET">
            <div class="col-md-5">
                <div class="input-group bg-light rounded-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0 py-2" placeholder="Cari nomor invoice, nama pasien, atau No. RM...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select bg-light border-light shadow-none focus-ring-0 fw-medium py-2 rounded-3 text-muted">
                    <option value="">Semua Status Tagihan</option>
                    <option value="draft" @selected(request('status') === 'draft')>Draft (Belum Fix)</option>
                    <option value="parsial" @selected(request('status') === 'parsial')>Parsial (Belum Lunas)</option>
                    <option value="lunas" @selected(request('status') === 'lunas')>Lunas Selesai</option>
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
                <i class="fa-solid fa-cash-register fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Daftar Tagihan Berjalan</h5>
                <p class="text-muted small mb-0 fw-medium">Total {{ $invoices->total() }} invoice terdata</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Invoice & Waktu</th>
                    <th class="py-3">Pasien / RM</th>
                    <th class="py-3">Unit Pelayanan</th>
                    <th class="py-3 text-center">Penjamin</th>
                    <th class="py-3 text-end">Total Tagihan</th>
                    <th class="py-3 text-end">Terbayar</th>
                    <th class="py-3 text-center">Status</th>
                    <th class="pe-4 py-3 text-end rounded-end">Tindakan</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($invoices as $invoice)
                @php
                    $statusProps = match($invoice->status) {
                        'lunas' => ['bg' => 'success', 'text' => 'success', 'icon' => 'fa-circle-check'],
                        'parsial' => ['bg' => 'warning', 'text' => 'warning', 'icon' => 'fa-clock-rotate-left'],
                        'draft' => ['bg' => 'secondary', 'text' => 'secondary', 'icon' => 'fa-file-signature'],
                        default => ['bg' => 'dark', 'text' => 'dark', 'icon' => 'fa-circle-dot']
                    };
                @endphp
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <i class="fa-solid fa-file-invoice text-primary opacity-50"></i>
                            <span class="text-primary font-monospace fw-bold">{{ $invoice->no_invoice }}</span>
                        </div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;">
                            <i class="fa-regular fa-clock me-1 opacity-50"></i>{{ $invoice->issued_at?->format('d/m/Y H:i') }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-dark mb-1">{{ $invoice->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace fw-medium"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>RM: {{ $invoice->encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $invoice->encounter->department?->nama_depart }}</div>
                        <div class="text-muted small fw-medium" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-user-doctor me-1 opacity-50"></i>{{ $invoice->encounter->doctor?->display_name ?? 'Dokter Umum' }}
                        </div>
                    </td>
                    <td class="text-center py-3">
                        <span class="badge bg-light text-secondary border border-light-subtle px-3 py-1 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fa-solid fa-shield-halved me-1 text-primary opacity-75"></i>{{ strtoupper($invoice->metode_penjamin) }}
                        </span>
                    </td>
                    <td class="text-end py-3">
                        <div class="fw-bold text-dark font-monospace">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-end py-3">
                        <div class="fw-bold text-success font-monospace">Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</div>
                    </td>
                    <td class="text-center py-3">
                        <span class="badge bg-{{ $statusProps['bg'] }} bg-opacity-10 text-{{ $statusProps['text'] }} rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            <i class="fa-solid {{ $statusProps['icon'] }} me-1"></i>{{ strtoupper($invoice->status) }}
                        </span>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('keuangan.invoice.show', $invoice) }}" class="btn btn-sm btn-light border btn-sm px-3 fw-medium rounded-pill shadow-sm text-primary transition-hover">
                            Detail <i class="fa-solid fa-arrow-right ms-1 small"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-file-invoice-dollar fs-2 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Tagihan Kosong</h6>
                        <p class="text-muted small mb-0 fw-medium">Tidak ada data tagihan yang sesuai dengan kriteria pencarian.</p>
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
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
</style>
@endsection
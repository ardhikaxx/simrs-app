@extends('layouts.app')

@section('title', 'Daftar Pengadaan')
@section('page-title', 'Supply Chain & Procurement')
@section('page-subtitle', 'Manajemen pemesanan perbekalan farmasi ke distributor')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-teal-soft text-primary">
                    <i class="fa-solid fa-truck-ramp-box"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Total Purchase Order</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ $orders->total() }} PO</h3>
                </div>
            </div>
            <i class="fa-solid fa-cart-flatbed-suitcases position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
    <div class="col-md-8 text-md-end d-flex align-items-center justify-content-md-end gap-3">
        <a href="{{ route('farmasi.inventory.index') }}" class="btn-premium btn-light border bg-white px-4">
            <i class="fa-solid fa-boxes-stacked opacity-50"></i>KATALOG STOK
        </a>
        <a href="{{ route('farmasi.procurement.create') }}" class="btn-premium btn-primary px-4">
            <i class="fa-solid fa-plus-circle"></i>BUAT PO BARU
        </a>
    </div>
</div>

<div class="card-premium border-0 bg-white overflow-hidden">
    <div class="p-4 border-bottom d-flex align-items-center gap-3 bg-white">
        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
            <i class="fa-solid fa-list-check fs-5"></i>
        </div>
        <div>
            <h5 class="fw-800 text-slate mb-0">Daftar Purchase Order (PO)</h5>
            <p class="small text-muted mb-0 fw-medium">Histori pengadaan barang medis dan non-medis</p>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                    <th class="ps-4 border-0 py-3">No. PO / Tanggal</th>
                    <th class="border-0 py-3">Supplier / Distributor</th>
                    <th class="border-0 py-3">Petugas Pembuat</th>
                    <th class="border-0 py-3 text-end">Total Nominal</th>
                    <th class="border-0 py-3 text-center">Status</th>
                    <th class="pe-4 border-0 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $po)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="text-mono fw-800 text-primary h6 mb-1">{{ $po->no_po }}</div>
                        <div class="small text-muted fw-bold">{{ $po->order_date->format('d M Y') }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-800 text-slate mb-1">{{ $po->supplier_name }}</div>
                        @if($po->received_at)
                            <div class="small text-success fw-bold" style="font-size: 0.65rem;">
                                <i class="fa-solid fa-check-double me-1"></i>Diterima: {{ $po->received_at->format('d/m/Y') }}
                            </div>
                        @else
                            <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Menunggu Pengiriman</div>
                        @endif
                    </td>
                    <td class="py-3">
                        <div class="small fw-800 text-slate">{{ $po->user->display_name }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Pharmacy Officer</div>
                    </td>
                    <td class="text-end py-3">
                        <div class="fw-900 text-slate">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Gross Total</div>
                    </td>
                    <td class="text-center py-3">
                        @php
                            $poStatus = match($po->status) {
                                'ordered' => ['bg' => 'bg-blue', 'text' => 'ORDERED'],
                                'received' => ['bg' => 'bg-success', 'text' => 'RECEIVED'],
                                'cancelled' => ['bg' => 'bg-danger', 'text' => 'CANCELLED'],
                                default => ['bg' => 'bg-slate', 'text' => strtoupper($po->status)]
                            };
                        @endphp
                        <div class="badge {{ $poStatus['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $poStatus['bg']) }} rounded-pill px-3 py-2 fw-800" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                            {{ $poStatus['text'] }}
                        </div>
                    </td>
                    <td class="pe-4 text-center py-3">
                        @if($po->status === 'ordered')
                            <form action="{{ route('farmasi.procurement.receive', $po) }}" method="POST" class="receive-form">
                                @csrf
                                <button class="btn btn-primary btn-sm px-4 fw-800 rounded-pill shadow-sm transition-bounce-hover">
                                    <i class="fa-solid fa-box-open me-1"></i>Terima Barang
                                </button>
                            </form>
                        @elseif($po->status === 'received')
                            <div class="d-inline-flex align-items-center gap-2 text-success fw-900" style="font-size: 0.7rem;">
                                <i class="fa-solid fa-circle-check fs-6"></i> STOCK UPDATED
                            </div>
                        @else
                            <span class="text-muted small italic fw-bold">NO ACTION</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-truck-medical fs-1 text-muted opacity-25"></i>
                            </div>
                            <h6 class="fw-800 text-slate">Belum Ada Transaksi PO</h6>
                            <p class="text-muted small">Mulai pengadaan barang dengan menekan tombol "Buat PO Baru".</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
        <div class="p-4 border-top bg-white">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .kpi-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bg-teal-soft { background: #F0FDFA; }
    .text-slate { color: #1E293B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.05); }
    .transition-hover:hover { background-color: #F8FAFC !important; }
</style>

@section('scripts')
<script>
    document.querySelectorAll('.receive-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Penerimaan',
                text: "Apakah Anda yakin barang telah diterima sesuai pesanan? Stok akan bertambah secara otomatis.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0D9488',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'YA, TERIMA BARANG',
                cancelButtonText: 'BATAL',
                customClass: { popup: 'rounded-4 border-0 shadow-lg', title: 'fw-800' }
            }).then((result) => {
                if (result.isConfirmed) { this.submit(); }
            });
        });
    });
</script>
@endsection
@endsection

@extends('layouts.app')

@section('title', 'Daftar Pengadaan')
@section('page-title', 'Pengadaan Farmasi (PO)')
@section('page-subtitle', 'Monitoring pemesanan obat ke supplier dan penerimaan barang')

@section('content')
<div class="page-header-bar mb-3">
    <div class="d-flex align-items-center gap-3">
        <div class="kpi-card py-2 px-3 shadow-none border bg-white d-flex align-items-center gap-3">
            <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 32px; height: 32px; font-size: 0.8rem;">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <div>
                <div class="small text-muted fw-700 text-uppercase" style="font-size: 0.6rem;">Total Order</div>
                <div class="fw-800 text-simrs-gray-900">{{ $orders->total() }} PO</div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('farmasi.inventory.index') }}" class="btn btn-simrs-outline shadow-sm">
            <i class="fa-solid fa-boxes-stacked me-2"></i>Katalog Stok
        </a>
        <a href="{{ route('farmasi.procurement.create') }}" class="btn btn-simrs-primary shadow-sm">
            <i class="fa-solid fa-cart-plus me-2"></i>Buat Purchase Order
        </a>
    </div>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white border-bottom-0">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-list-check"></i>
            <span>Daftar Purchase Order (PO)</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. PO / Tgl</th>
                    <th>Supplier / Distributor</th>
                    <th>Pembuat</th>
                    <th class="text-end">Total Nominal</th>
                    <th class="text-center">Status PO</th>
                    <th class="pe-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $po)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-800 text-simrs-primary h6 mb-0">{{ $po->no_po }}</div>
                        <div class="small text-muted">{{ $po->order_date->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div class="fw-700 text-simrs-gray-900">{{ $po->supplier_name }}</div>
                        @if($po->received_at)
                            <div class="small text-success" style="font-size: 0.65rem;"><i class="fa-solid fa-check-double me-1"></i>Diterima: {{ $po->received_at->format('d/m/Y') }}</div>
                        @endif
                    </td>
                    <td>
                        <div class="small fw-600 text-simrs-secondary">{{ $po->user->display_name }}</div>
                    </td>
                    <td class="text-end fw-800">
                        Rp {{ number_format($po->total_amount, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <span class="badge-status status-{{ $po->status }} shadow-none py-1 px-3" style="font-size: 0.7rem;">
                            {{ strtoupper($po->status) }}
                        </span>
                    </td>
                    <td class="pe-4 text-center">
                        @if($po->status === 'ordered')
                            <form action="{{ route('farmasi.procurement.receive', $po) }}" method="POST" class="receive-form">
                                @csrf
                                <button class="btn btn-sm btn-simrs-primary shadow-sm px-3 py-1">
                                    <i class="fa-solid fa-box-open me-1"></i>Terima Barang
                                </button>
                            </form>
                        @elseif($po->status === 'received')
                            <div class="small text-success fw-800"><i class="fa-solid fa-circle-check me-1"></i>STOCK UPDATED</div>
                        @else
                            <span class="text-muted small italic">No action</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fa-solid fa-truck-medical fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Belum ada data pengadaan (PO) yang diterbitkan.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

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
                confirmButtonColor: '#0B6477',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, Terima Barang!',
                cancelButtonText: 'Batal',
                customClass: { popup: 'rounded-4 border-0 shadow-lg', title: 'fw-800' }
            }).then((result) => {
                if (result.isConfirmed) { this.submit(); }
            });
        });
    });
</script>
@endsection
@endsection

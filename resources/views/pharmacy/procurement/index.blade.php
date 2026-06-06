@extends('layouts.app')

@section('title', 'Daftar Pengadaan')
@section('page-title', 'Supply Chain & Procurement')
@section('page-subtitle', 'Manajemen pemesanan perbekalan farmasi ke distributor')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-truck-ramp-box fs-4"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Total Purchase Order</div>
                    <h3 class="fw-bold text-dark mb-0">{{ $orders->total() }} <span class="fs-6 fw-medium text-muted">PO</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-8 d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
        <a href="{{ route('farmasi.inventory.index') }}" class="btn btn-light border bg-white px-4 py-2 fw-medium shadow-sm rounded-3 text-muted hover-bg-gray transition-hover">
            <i class="fa-solid fa-boxes-stacked me-2"></i>Katalog Stok
        </a>
        <a href="{{ route('farmasi.procurement.create') }}" class="btn btn-primary px-4 py-2 fw-medium shadow-sm rounded-3 transition-hover">
            <i class="fa-solid fa-plus-circle me-2"></i>Buat PO Baru
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-list-check fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Daftar Purchase Order (PO)</h5>
                <p class="small text-muted mb-0 fw-medium">Histori pengadaan barang medis dan non-medis</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. PO / Tanggal</th>
                    <th class="py-3">Supplier / Distributor</th>
                    <th class="py-3">Petugas Pembuat</th>
                    <th class="py-3 text-end">Total Nominal</th>
                    <th class="py-3 text-center">Status</th>
                    <th class="pe-4 py-3 text-center rounded-end">Aksi</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($orders as $po)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-primary h6 mb-1 font-monospace">{{ $po->no_po }}</div>
                        <div class="small text-muted fw-medium">{{ $po->order_date->format('d M Y') }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $po->supplier_name }}</div>
                        @if($po->received_at)
                            <div class="small text-success fw-semibold" style="font-size: 0.7rem;">
                                <i class="fa-solid fa-check-double me-1"></i>Diterima: {{ $po->received_at->format('d/m/Y') }}
                            </div>
                        @else
                            <div class="small text-muted fw-medium" style="font-size: 0.7rem;">Menunggu Pengiriman</div>
                        @endif
                    </td>
                    <td class="py-3">
                        <div class="small fw-semibold text-dark mb-1">{{ $po->user->display_name }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;">Pharmacy Officer</div>
                    </td>
                    <td class="text-end py-3">
                        <div class="fw-bold text-dark mb-1">Rp {{ number_format($po->total_amount, 0, ',', '.') }}</div>
                        <div class="small text-muted fw-semibold text-uppercase" style="font-size: 0.65rem;">Gross Total</div>
                    </td>
                    <td class="text-center py-3">
                        @php
                            $poStatus = match($po->status) {
                                'ordered' => ['bg' => 'info', 'text' => 'ORDERED'],
                                'received' => ['bg' => 'success', 'text' => 'RECEIVED'],
                                'cancelled' => ['bg' => 'danger', 'text' => 'CANCELLED'],
                                default => ['bg' => 'secondary', 'text' => strtoupper($po->status)]
                            };
                        @endphp
                        <div class="badge bg-{{ $poStatus['bg'] }} bg-opacity-10 text-{{ $poStatus['bg'] }} rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ $poStatus['text'] }}
                        </div>
                    </td>
                    <td class="pe-4 text-center py-3">
                        @if($po->status === 'ordered')
                            <form action="{{ route('farmasi.procurement.receive', $po) }}" method="POST" class="receive-form">
                                @csrf
                                <button class="btn btn-primary btn-sm px-4 fw-medium rounded-pill shadow-sm transition-hover">
                                    <i class="fa-solid fa-box-open me-2"></i>Terima Barang
                                </button>
                            </form>
                        @elseif($po->status === 'received')
                            <div class="d-inline-flex align-items-center gap-2 text-success fw-bold" style="font-size: 0.75rem;">
                                <i class="fa-solid fa-circle-check"></i> Stock Updated
                            </div>
                        @else
                            <span class="text-muted small fst-italic fw-medium">N/A</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-truck-medical fs-2 text-muted opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Belum Ada Transaksi PO</h6>
                            <p class="text-muted small mb-0">Mulai pengadaan barang dengan menekan tombol "Buat PO Baru".</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
        <div class="p-4 border-top border-light bg-white rounded-bottom-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
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
                confirmButtonColor: '#3b82f6',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, TERIMA BARANG',
                cancelButtonText: 'BATAL',
                customClass: { popup: 'rounded-4 border-0 shadow-sm', title: 'fw-bold' }
            }).then((result) => {
                if (result.isConfirmed) { this.submit(); }
            });
        });
    });
</script>
@endsection
@endsection

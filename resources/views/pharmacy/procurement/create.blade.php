@extends('layouts.app')

@section('title', 'Buat Purchase Order')
@section('page-title', 'Penerbitan Purchase Order')
@section('page-subtitle', 'Pemesanan item farmasi kepada supplier')

@section('content')
<form action="{{ route('farmasi.procurement.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="simrs-card">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary">
                        <i class="fa-solid fa-cart-shopping"></i>
                        <span>Informasi Order & Detail Item</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="row g-3 mb-4">
                        <div class="col-md-8">
                            <label class="form-label-custom">Nama Supplier / Distributor</label>
                            <input name="supplier_name" class="form-control fw-bold" placeholder="Contoh: PT. Kimia Farma Trading" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-custom">Tanggal Pemesanan</label>
                            <input type="date" name="order_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-sm table-borderless align-middle">
                            <thead class="bg-light">
                                <tr class="small text-muted text-uppercase fw-bold">
                                    <th style="width: 50%;" class="ps-3">Item Obat</th>
                                    <th style="width: 15%;">Jumlah</th>
                                    <th style="width: 35%;">Harga Satuan (HPP)</th>
                                </tr>
                            </thead>
                            <tbody id="poRows">
                                @for($i = 0; $i < 5; $i++)
                                    <tr>
                                        <td class="pb-3 ps-3">
                                            <select name="medicine_id[]" class="form-select select2-init">
                                                <option value="">Pilih Item...</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}">{{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }} {{ $medicine->satuan }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="pb-3">
                                            <input type="number" name="qty[]" class="form-control text-center fw-bold" value="0">
                                        </td>
                                        <td class="pb-3">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light small">Rp</span>
                                                <input type="number" name="price[]" class="form-control fw-bold" value="0">
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>
                    <button type="button" class="btn btn-sm btn-simrs-outline border-0 shadow-none text-muted fw-bold" id="addPoRow">
                        <i class="fa-solid fa-plus-circle me-1"></i>Tambah Baris Item
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="simrs-card sticky-top" style="top: 80px;">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary small">
                        <i class="fa-solid fa-shield-check"></i>
                        <span>Konfirmasi PO</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="alert alert-info border-0 shadow-sm small mb-4">
                        <i class="fa-solid fa-circle-info me-2"></i>Status PO akan langsung menjadi <b>ORDERED</b>. Stok akan bertambah saat petugas melakukan konfirmasi "Terima Barang".
                    </div>
                    <button class="btn btn-simrs-primary w-100 py-3 fw-800 shadow-sm border-0 mb-3">
                        <i class="fa-solid fa-file-invoice me-2"></i>TERBITKAN PO SEKARANG
                    </button>
                    <a href="{{ route('farmasi.procurement.index') }}" class="btn btn-simrs-outline w-100 fw-bold border-0 text-muted">
                        <i class="fa-solid fa-xmark me-2"></i>Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@section('scripts')
<script>
    document.getElementById('addPoRow')?.addEventListener('click', () => {
        const tbody = document.getElementById('poRows');
        const firstRow = tbody.querySelector('tr');
        const newRow = firstRow.cloneNode(true);
        newRow.querySelectorAll('input').forEach(input => input.value = 0);
        tbody.appendChild(newRow);
        $('.select2-init').select2({ theme: 'bootstrap-5', width: '100%' });
    });
</script>
@endsection
@endsection

@extends('layouts.app')

@section('title', 'Buat Purchase Order')
@section('page-title', 'Penerbitan PO Baru')
@section('page-subtitle', 'Proses pengadaan stok perbekalan farmasi')

@section('content')
<form action="{{ route('farmasi.procurement.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card border-0 shadow-sm rounded-4 bg-white mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-cart-shopping fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Informasi Order & Detail Item</h5>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-md-8">
                            <label class="form-label fw-semibold text-muted small text-uppercase mb-2" style="letter-spacing: 0.5px;">Nama Supplier / Distributor</label>
                            <input name="supplier_name" class="form-control bg-light border-light fw-medium py-2 shadow-none focus-ring-0" placeholder="Contoh: PT. Kimia Farma Trading" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold text-muted small text-uppercase mb-2" style="letter-spacing: 0.5px;">Tanggal Pemesanan</label>
                            <input type="date" name="order_date" class="form-control bg-light border-light py-2 shadow-none focus-ring-0 fw-medium" value="{{ date('Y-m-d') }}" required>
                        </div>
                    </div>

                    <div class="table-responsive bg-white">
                        <table class="table table-borderless align-middle mb-0 custom-table">
                            <thead class="bg-light">
                                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                                    <th style="width: 50%;" class="ps-4 py-3 rounded-start">Item Perbekalan</th>
                                    <th style="width: 15%;" class="text-center py-3">Jumlah</th>
                                    <th style="width: 35%;" class="text-end py-3 pe-4 rounded-end">Harga Satuan (HPP)</th>
                                </tr>
                            </thead>
                            <tbody id="poRows" class="border-top border-light">
                                @for($i = 0; $i < 5; $i++)
                                    <tr class="border-bottom border-light">
                                        <td class="py-3 ps-4">
                                            <select name="medicine_id[]" class="form-select border-light bg-light py-2 select2-init shadow-none">
                                                <option value="">Pilih Item...</option>
                                                @foreach($medicines as $medicine)
                                                    <option value="{{ $medicine->id }}">{{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }} {{ $medicine->satuan }})</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td class="py-3">
                                            <input type="number" name="qty[]" class="form-control border-light bg-primary bg-opacity-10 text-primary text-center fw-bold py-2 shadow-none focus-ring-0" value="0">
                                        </td>
                                        <td class="py-3 pe-4">
                                            <div class="input-group">
                                                <span class="input-group-text bg-light border-light text-muted small"><i class="fa-solid fa-rupiah-sign"></i></span>
                                                <input type="number" name="price[]" class="form-control border-light bg-light py-2 fw-semibold shadow-none focus-ring-0" value="0">
                                            </div>
                                        </td>
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <button type="button" class="btn btn-light border text-primary fw-semibold rounded-pill px-4 mt-4 transition-hover hover-bg-gray" id="addPoRow">
                        <i class="fa-solid fa-plus-circle me-2"></i>Tambah Baris Item
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white sticky-top" style="top: 90px; z-index: 100;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-shield-check fs-6"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Konfirmasi & Validasi</h6>
                    </div>

                    <div class="alert alert-info bg-info bg-opacity-10 border-0 rounded-4 p-3 mb-4 d-flex gap-3">
                        <i class="fa-solid fa-circle-info text-info fs-5 mt-1 flex-shrink-0"></i>
                        <p class="small text-dark fw-medium mb-0 lh-sm">
                            Status PO akan langsung menjadi <span class="badge bg-primary bg-opacity-10 text-primary">ORDERED</span>. Stok inventori akan bertambah otomatis saat barang dikonfirmasi telah diterima.
                        </p>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm transition-hover mb-3">
                        <i class="fa-solid fa-file-invoice me-2"></i>TERBITKAN P.O.
                    </button>
                    
                    <a href="{{ route('farmasi.procurement.index') }}" class="btn btn-light border border-light w-100 fw-bold py-3 rounded-pill text-muted hover-bg-gray transition-hover">
                        BATALKAN PROSES
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); }
</style>

@section('scripts')
<script>
    document.getElementById('addPoRow')?.addEventListener('click', () => {
        const tbody = document.getElementById('poRows');
        const rows = tbody.querySelectorAll('tr');
        const firstRow = rows[0];
        const newRow = firstRow.cloneNode(true);
        
        // Reset values
        newRow.querySelectorAll('input').forEach(input => input.value = 0);
        
        // Handle select2 cloning
        const selectContainer = newRow.querySelector('td:first-child');
        const oldSelect = selectContainer.querySelector('select');
        const selectName = oldSelect.name;
        
        // Remove old select2 artifacts
        selectContainer.innerHTML = '';
        const newSelect = document.createElement('select');
        newSelect.name = selectName;
        newSelect.className = 'form-select border-light bg-light py-2 select2-init shadow-none';
        newSelect.innerHTML = oldSelect.innerHTML;
        selectContainer.appendChild(newSelect);
        
        tbody.appendChild(newRow);
        
        // Re-init select2 for the whole page
        $('.select2-init').select2({ theme: 'bootstrap-5', width: '100%', dropdownCssClass: 'shadow-sm border-0 rounded-3 mt-1' });
    });
</script>
@endsection
@endsection

@extends('layouts.app')

@section('title', 'Buat Purchase Order')
@section('page-title', 'Penerbitan PO Baru')
@section('page-subtitle', 'Proses pengadaan stok perbekalan farmasi')

@section('content')
<form action="{{ route('farmasi.procurement.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card-premium border-0 bg-white p-4 mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-cart-shopping fs-6"></i>
                    </div>
                    <h5 class="fw-800 text-slate mb-0">Informasi Order & Detail Item</h5>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-8">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Nama Supplier / Distributor</label>
                        <input name="supplier_name" class="form-control bg-light border-0 fw-bold py-3" placeholder="Contoh: PT. Kimia Farma Trading" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Tanggal Pemesanan</label>
                        <input type="date" name="order_date" class="form-control bg-light border-0 py-3" value="{{ date('Y-m-d') }}" required>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless align-middle">
                        <thead>
                            <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                                <th style="width: 50%;" class="ps-3 py-3 rounded-start-3">Item Perbekalan</th>
                                <th style="width: 15%; text-center py-3">Jumlah</th>
                                <th style="width: 35%; text-end py-3 rounded-end-3">Harga Satuan (HPP)</th>
                            </tr>
                        </thead>
                        <tbody id="poRows">
                            @for($i = 0; $i < 5; $i++)
                                <tr class="border-bottom border-light">
                                    <td class="py-3 ps-3">
                                        <select name="medicine_id[]" class="form-select border-0 bg-light py-2 select2-init">
                                            <option value="">Pilih Item...</option>
                                            @foreach($medicines as $medicine)
                                                <option value="{{ $medicine->id }}">{{ $medicine->nama_obat }} (Stok: {{ $medicine->stok }} {{ $medicine->satuan }})</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td class="py-3">
                                        <input type="number" name="qty[]" class="form-control border-0 bg-teal-soft text-primary text-center fw-800 py-2" value="0">
                                    </td>
                                    <td class="py-3">
                                        <div class="input-group">
                                            <span class="input-group-text bg-light border-0 text-muted small">Rp</span>
                                            <input type="number" name="price[]" class="form-control border-0 bg-light py-2 fw-bold" value="0">
                                        </div>
                                    </td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>

                <button type="button" class="btn btn-link text-primary fw-800 text-decoration-none p-0 mt-3" id="addPoRow">
                    <i class="fa-solid fa-plus-circle me-1"></i>TAMBAH BARIS ITEM
                </button>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card-premium border-0 bg-white p-4 sticky-top" style="top: 100px;">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-shield-check fs-6"></i>
                    </div>
                    <h6 class="fw-800 text-slate mb-0">Konfirmasi & Validasi</h6>
                </div>

                <div class="alert alert-info bg-blue-soft border-0 rounded-4 p-3 mb-4">
                    <div class="d-flex gap-3">
                        <i class="fa-solid fa-circle-info text-blue fs-4"></i>
                        <p class="small text-dark fw-medium mb-0">
                            Status PO akan langsung menjadi <b>ORDERED</b>. Stok inventori akan bertambah otomatis saat barang dikonfirmasi telah diterima.
                        </p>
                    </div>
                </div>

                <button class="btn btn-primary w-100 py-3 fw-800 rounded-pill shadow-sm transition-bounce-hover mb-3">
                    <i class="fa-solid fa-file-invoice me-2"></i>TERBITKAN PURCHASE ORDER
                </button>
                
                <a href="{{ route('farmasi.procurement.index') }}" class="btn btn-light border w-100 fw-800 py-3 rounded-pill">
                    BATALKAN PROSES
                </a>
            </div>
        </div>
    </div>
</form>

<style>
    .bg-teal-soft { background: #F0FDFA; }
    .bg-blue-soft { background: #EFF6FF; }
    .text-blue { color: #3B82F6; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.02); }
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
        newSelect.className = 'form-select border-0 bg-light py-2 select2-init';
        newSelect.innerHTML = oldSelect.innerHTML;
        selectContainer.appendChild(newSelect);
        
        tbody.appendChild(newRow);
        
        // Re-init select2 for the whole page
        $('.select2-init').select2({ theme: 'bootstrap-5', width: '100%' });
    });
</script>
@endsection
@endsection

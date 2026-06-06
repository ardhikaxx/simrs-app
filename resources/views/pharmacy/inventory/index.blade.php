@extends('layouts.app')

@section('title', 'Inventory Farmasi')
@section('page-title', 'Manajemen Perbekalan Farmasi')
@section('page-subtitle', 'Monitoring stok obat, alat kesehatan, dan masa kedaluwarsa')

@section('content')
<div class="row g-4">
    <!-- Form Tambah Obat -->
    <div class="col-xl-4">
        <div class="simrs-card sticky-top" style="top: 80px; z-index: 100;">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title text-simrs-primary">
                    <i class="fa-solid fa-capsules"></i>
                    <span>Registrasi Item Farmasi</span>
                </div>
            </div>
            <div class="simrs-card-body">
                <form action="{{ route('farmasi.inventory.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Kode Item</label>
                            <input name="kode_obat" class="form-control text-mono fw-bold" placeholder="OBT-XXXX" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Satuan</label>
                            <select name="satuan" class="form-select">
                                <option value="tablet">Tablet</option>
                                <option value="kapsul">Kapsul</option>
                                <option value="sirup">Sirup / Botol</option>
                                <option value="injeksi">Vial / Ampul</option>
                                <option value="sachet">Sachet</option>
                                <option value="pcs">Pcs</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Nama Obat & Kekuatan Sediaan</label>
                            <input name="nama_obat" class="form-control" placeholder="Contoh: Paracetamol 500mg" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Kategori / Golongan</label>
                            <input name="kategori" class="form-control" placeholder="Contoh: Analgesik Antipiretik" required>
                        </div>
                        <div class="col-md-6 border-top pt-2">
                            <label class="form-label-custom">Stok Awal</label>
                            <div class="input-group">
                                <input type="number" name="stok" class="form-control fw-bold" value="0" required>
                                <span class="input-group-text bg-light border-start-0"><i class="fa-solid fa-box-open text-muted small"></i></span>
                            </div>
                        </div>
                        <div class="col-md-6 border-top pt-2">
                            <label class="form-label-custom">Stok Minimum</label>
                            <input type="number" name="stok_minimum" class="form-control" value="10" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Beli</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light small">Rp</span>
                                <input type="number" name="harga_beli" class="form-control" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Harga Jual</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light small">Rp</span>
                                <input type="number" name="harga_jual" class="form-control fw-bold text-simrs-primary" value="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Tgl Kedaluwarsa</label>
                            <input type="date" name="expired_at" class="form-control">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Produsen / Pabrik</label>
                            <input name="manufacturer" class="form-control" placeholder="Pabrikan">
                        </div>
                    </div>
                    <button class="btn btn-simrs-primary w-100 mt-4 py-2 fw-800 shadow-sm border-0">
                        <i class="fa-solid fa-plus-circle me-2"></i>Simpan Data Obat
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar Obat -->
    <div class="col-xl-8">
        <div class="page-header-bar mb-3">
            <form class="d-flex gap-2 grow" method="GET">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari obat berdasarkan nama, kode, atau kategori...">
                </div>
                <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
            </form>
        </div>

        <div class="simrs-card">
            <div class="simrs-card-header bg-white border-bottom-0">
                <div class="simrs-card-title text-simrs-primary">
                    <i class="fa-solid fa-boxes-stacked"></i>
                    <span>Katalog Stok Perbekalan</span>
                </div>
                <div class="small text-muted fw-normal">Total: {{ $medicines->total() }} Item</div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Identitas Obat</th>
                            <th class="text-center">Stok Saat Ini</th>
                            <th class="text-end">Harga Jual (IDR)</th>
                            <th class="text-center">ED (Expiry)</th>
                            <th class="text-center">Status Stok</th>
                            <th class="pe-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($medicines as $medicine)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 36px; height: 36px; font-size: 0.8rem;">
                                        <i class="fa-solid fa-pills"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-simrs-gray-900">{{ $medicine->nama_obat }}</div>
                                        <div class="text-mono small text-muted">{{ $medicine->kode_obat }} | {{ $medicine->kategori }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center">
                                <div class="fw-800 text-simrs-gray-800 h6 mb-0">{{ $medicine->stok }}</div>
                                <div class="small text-muted text-uppercase" style="font-size: 0.65rem;">{{ $medicine->satuan }}</div>
                            </td>
                            <td class="text-end fw-bold text-simrs-primary">
                                Rp {{ number_format($medicine->harga_jual, 0, ',', '.') }}
                            </td>
                            <td class="text-center">
                                @if($medicine->expired_at)
                                    @php($isExpired = $medicine->expired_at->isPast())
                                    <div class="{{ $isExpired ? 'text-danger fw-bold' : 'text-muted small' }}">
                                        {{ $medicine->expired_at->format('d/m/Y') }}
                                    </div>
                                    @if($isExpired) <div class="small text-danger fw-800" style="font-size: 0.6rem;">EXPIRED</div> @endif
                                @else
                                    <span class="text-muted opacity-50">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($medicine->is_low_stock)
                                    <span class="badge-status status-kritis shadow-none">
                                        <i class="fa-solid fa-triangle-exclamation me-1"></i>Low Stock
                                    </span>
                                @else
                                    <span class="badge-status status-aman shadow-none">
                                        <i class="fa-solid fa-check-circle me-1 text-success"></i>Tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="pe-4 text-center">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-simrs-outline shadow-none border-0 p-1" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 p-2">
                                        <li>
                                            <button class="dropdown-item py-2 rounded-2 small fw-600" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditObat" 
                                                data-item='@json($medicine)'>
                                                <i class="fa-solid fa-pen-to-square me-2 text-muted"></i>Edit Data
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 rounded-2 small fw-600" href="{{ route('farmasi.inventory.show', $medicine) }}">
                                                <i class="fa-solid fa-clock-rotate-left me-2 text-muted"></i>Kartu Stok
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider opacity-5"></li>
                                        <li>
                                            <form action="{{ route('farmasi.inventory.destroy', $medicine) }}" method="POST" class="d-inline delete-form">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 rounded-2 small fw-600 text-danger">
                                                    <i class="fa-solid fa-trash-can me-2"></i>Hapus Item
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-solid fa-box-open fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <div class="text-muted">Data stok obat tidak ditemukan di basis data.</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($medicines->hasPages())
                <div class="p-3 border-top bg-light">
                    {{ $medicines->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edit Obat -->
<div class="modal fade" id="modalEditObat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditObat" method="POST" class="modal-content border-0 shadow-lg">
            @csrf @method('PATCH')
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-800"><i class="fa-solid fa-pen-to-square me-2"></i>Update Data Obat</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">Kode Item</label>
                        <input name="kode_obat" id="edit_kode" class="form-control text-mono fw-bold" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Satuan</label>
                        <select name="satuan" id="edit_satuan" class="form-select">
                            <option value="tablet">Tablet</option>
                            <option value="kapsul">Kapsul</option>
                            <option value="sirup">Sirup / Botol</option>
                            <option value="injeksi">Vial / Ampul</option>
                            <option value="sachet">Sachet</option>
                            <option value="pcs">Pcs</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-custom">Nama Obat</label>
                        <input name="nama_obat" id="edit_nama" class="form-control fw-bold" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label-custom">Kategori</label>
                        <input name="kategori" id="edit_kategori" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Stok Minimum</label>
                        <input type="number" name="stok_minimum" id="edit_min" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Harga Jual (IDR)</label>
                        <input type="number" name="harga_jual" id="edit_harga" class="form-control fw-800 text-simrs-primary" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label-custom">Produsen</label>
                        <input name="manufacturer" id="edit_produsen" class="form-control">
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-simrs-outline px-4 fw-bold border-0" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-simrs-primary px-4 fw-800">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    // Handle Edit Modal Data
    const modalEditObat = document.getElementById('modalEditObat');
    modalEditObat.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const medicine = JSON.parse(button.getAttribute('data-item'));
        const form = document.getElementById('formEditObat');
        
        form.action = `/farmasi/inventory/${medicine.id}`;
        document.getElementById('edit_kode').value = medicine.kode_obat;
        document.getElementById('edit_nama').value = medicine.nama_obat;
        document.getElementById('edit_kategori').value = medicine.kategori;
        document.getElementById('edit_satuan').value = medicine.satuan;
        document.getElementById('edit_min').value = medicine.stok_minimum;
        document.getElementById('edit_harga').value = medicine.harga_jual;
        document.getElementById('edit_produsen').value = medicine.manufacturer || '';
    });

    // Handle Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi Hapus',
                text: "Apakah Anda yakin ingin menghapus item obat ini? Tindakan ini tidak dapat dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#C5372C',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4 border-0 shadow-lg',
                    title: 'fw-800',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection
@endsection

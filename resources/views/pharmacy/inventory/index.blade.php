@extends('layouts.app')

@section('title', 'Katalog Farmasi')
@section('page-title', 'Inventory & Perbekalan')
@section('page-subtitle', 'Manajemen stok obat, alkes, dan pemantauan masa kedaluwarsa')

@section('content')
<div class="row g-4">
    <!-- Form Registrasi Item -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4 sticky-top" style="top: 90px; z-index: 100;">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-capsules fs-5"></i>
                </div>
                <h5 class="fw-bold text-dark mb-0">Registrasi Item Baru</h5>
            </div>

            <form action="{{ route('farmasi.inventory.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Kode Item</label>
                        <input name="kode_obat" class="form-control bg-light border-light fw-bold font-monospace shadow-none focus-ring-0 py-2" placeholder="OBT-XXXX" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Satuan</label>
                        <select name="satuan" class="form-select bg-light border-light fw-medium shadow-none focus-ring-0 py-2">
                            <option value="tablet">Tablet</option>
                            <option value="kapsul">Kapsul</option>
                            <option value="sirup">Sirup / Botol</option>
                            <option value="injeksi">Vial / Ampul</option>
                            <option value="sachet">Sachet</option>
                            <option value="pcs">Pcs</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Nama & Kekuatan Sediaan</label>
                        <input name="nama_obat" class="form-control bg-light border-light fw-semibold shadow-none focus-ring-0 py-2" placeholder="Contoh: Paracetamol 500mg" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Kategori / Golongan</label>
                        <input name="kategori" class="form-control bg-light border-light shadow-none focus-ring-0 py-2" placeholder="Contoh: Analgesik Antipiretik" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Stok Awal</label>
                        <input type="number" name="stok" class="form-control bg-light border-light fw-bold text-primary shadow-none focus-ring-0 py-2" value="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Stok Minimum</label>
                        <input type="number" name="stok_minimum" class="form-control bg-light border-light shadow-none focus-ring-0 py-2" value="10" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Harga Beli</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-muted small"><i class="fa-solid fa-rupiah-sign"></i></span>
                            <input type="number" name="harga_beli" class="form-control bg-light border-light shadow-none focus-ring-0 py-2" value="0" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Harga Jual</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-muted small"><i class="fa-solid fa-rupiah-sign"></i></span>
                            <input type="number" name="harga_jual" class="form-control bg-light border-light fw-bold text-primary shadow-none focus-ring-0 py-2" value="0" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Produsen / Pabrik</label>
                        <input name="manufacturer" class="form-control bg-light border-light shadow-none focus-ring-0 py-2" placeholder="Pabrikan">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Tanggal Kedaluwarsa</label>
                        <input type="date" name="expired_at" class="form-control bg-light border-light shadow-none focus-ring-0 py-2">
                    </div>
                </div>
                
                <button class="btn btn-primary w-100 mt-4 py-3 fw-bold rounded-pill shadow-sm transition-hover">
                    <i class="fa-solid fa-plus-circle me-2"></i>DAFTARKAN ITEM
                </button>
            </form>
        </div>
    </div>

    <!-- Katalog Stok -->
    <div class="col-xl-8 d-flex flex-column gap-4">
        <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
            <form class="row g-3" method="GET">
                <div class="col-md-9">
                    <div class="input-group bg-light rounded-3">
                        <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0 py-2" placeholder="Cari berdasarkan nama, kode, atau kategori obat...">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100 h-100 fw-bold rounded-3 shadow-sm transition-hover">
                        <i class="fa-solid fa-filter me-2"></i>FILTER
                    </button>
                </div>
            </form>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white flex-grow-1">
            <div class="p-4 border-bottom border-light d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-boxes-stacked fs-5"></i>
                    </div>
                    <h5 class="fw-bold text-dark mb-0">Katalog Perbekalan Farmasi</h5>
                </div>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm">Total: {{ $medicines->total() }} Item</span>
            </div>

            <div class="table-responsive bg-white">
                <table class="table table-hover table-borderless align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                            <th class="ps-4 py-3 rounded-start">Informasi Item</th>
                            <th class="py-3 text-center">Stok</th>
                            <th class="py-3 text-end">Harga Jual</th>
                            <th class="py-3 text-center">Kedaluwarsa</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="pe-4 py-3 text-end rounded-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-light">
                    @forelse($medicines as $medicine)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                                        <i class="fa-solid fa-pills"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="fw-bold text-dark mb-1 text-truncate">{{ $medicine->nama_obat }}</div>
                                        <div class="small text-muted fw-medium font-monospace opacity-75 text-truncate">
                                            {{ $medicine->kode_obat }} <span class="mx-1">&bull;</span> {{ $medicine->kategori }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center py-3">
                                <div class="fw-bold text-dark h6 mb-1">{{ $medicine->stok }}</div>
                                <div class="small text-muted fw-semibold text-uppercase" style="font-size: 0.65rem;">{{ $medicine->satuan }}</div>
                            </td>
                            <td class="text-end py-3">
                                <div class="fw-bold text-primary mb-1">Rp {{ number_format($medicine->harga_jual, 0, ',', '.') }}</div>
                                <div class="small text-muted fw-semibold text-uppercase" style="font-size: 0.65rem;">Retail Price</div>
                            </td>
                            <td class="text-center py-3">
                                @if($medicine->expired_at)
                                    @php($isExpired = $medicine->expired_at->isPast())
                                    <div class="badge {{ $isExpired ? 'bg-danger' : 'bg-light text-secondary border' }} px-3 py-1 fw-semibold" style="font-size: 0.65rem;">
                                        {{ $medicine->expired_at->format('d/m/Y') }}
                                    </div>
                                @else
                                    <span class="text-muted opacity-25 fw-medium">-</span>
                                @endif
                            </td>
                            <td class="text-center py-3">
                                @if($medicine->is_low_stock)
                                    <div class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-1 fw-bold animate-pulse" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                        LOW STOCK
                                    </div>
                                @else
                                    <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                        AVAILABLE
                                    </div>
                                @endif
                            </td>
                            <td class="pe-4 text-end py-3">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle border-0 shadow-none d-flex align-items-center justify-content-center mx-auto me-md-0 ms-md-auto" style="width: 32px; height: 32px;" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-2">
                                        <li>
                                            <button class="dropdown-item py-2 rounded-3 small fw-medium" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditObat" 
                                                data-item='@json($medicine)'>
                                                <i class="fa-solid fa-pen-to-square me-2 opacity-50"></i>Edit Item
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 rounded-3 small fw-medium" href="{{ route('farmasi.inventory.show', $medicine) }}">
                                                <i class="fa-solid fa-clock-rotate-left me-2 opacity-50"></i>Kartu Stok
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider opacity-10"></li>
                                        <li>
                                            <form action="{{ route('farmasi.inventory.destroy', $medicine) }}" method="POST" class="delete-form">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 rounded-3 small fw-semibold text-danger">
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
                                <div class="py-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                        <i class="fa-solid fa-box-open fs-2 text-muted opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Data</h6>
                                    <p class="text-muted small mb-0">Katalog stok farmasi saat ini kosong.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($medicines->hasPages())
                <div class="p-4 border-top border-light bg-white rounded-bottom-4">
                    {{ $medicines->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edit Obat -->
<div class="modal fade" id="modalEditObat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditObat" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf @method('PATCH')
            <div class="modal-header bg-light border-0 p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-pen-to-square fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold text-dark">Update Data Item</h5>
                </div>
                <button type="button" class="btn-close shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-white border-top border-bottom border-light">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Kode Item</label>
                        <input name="kode_obat" id="edit_kode" class="form-control bg-light border-light fw-bold font-monospace shadow-none focus-ring-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Satuan</label>
                        <select name="satuan" id="edit_satuan" class="form-select bg-light border-light fw-medium shadow-none focus-ring-0">
                            <option value="tablet">Tablet</option>
                            <option value="kapsul">Kapsul</option>
                            <option value="sirup">Sirup / Botol</option>
                            <option value="injeksi">Vial / Ampul</option>
                            <option value="sachet">Sachet</option>
                            <option value="pcs">Pcs</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Nama Obat</label>
                        <input name="nama_obat" id="edit_nama" class="form-control bg-light border-light fw-semibold shadow-none focus-ring-0" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Kategori</label>
                        <input name="kategori" id="edit_kategori" class="form-control bg-light border-light shadow-none focus-ring-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Stok Minimum</label>
                        <input type="number" name="stok_minimum" id="edit_min" class="form-control bg-light border-light shadow-none focus-ring-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Harga Jual</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-light text-muted small"><i class="fa-solid fa-rupiah-sign"></i></span>
                            <input type="number" name="harga_jual" id="edit_harga" class="form-control bg-light border-light fw-bold text-primary shadow-none focus-ring-0" required>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Produsen</label>
                        <input name="manufacturer" id="edit_produsen" class="form-control bg-light border-light shadow-none focus-ring-0">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 bg-white">
                <button type="button" class="btn btn-light border border-light text-muted fw-semibold rounded-pill px-4 transition-hover hover-bg-gray" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary fw-bold rounded-pill px-4 shadow-sm transition-hover">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); }
    .card.transition-hover:hover { box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
</style>

@section('scripts')
<script>
    // Handle Edit Modal Data
    const modalEditObat = document.getElementById('modalEditObat');
    modalEditObat?.addEventListener('show.bs.modal', function (event) {
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
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, HAPUS ITEM',
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

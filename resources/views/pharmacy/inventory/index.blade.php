@extends('layouts.app')

@section('title', 'Katalog Farmasi')
@section('page-title', 'Inventory & Perbekalan')
@section('page-subtitle', 'Manajemen stok obat, alkes, dan pemantauan masa kedaluwarsa')

@section('content')
<div class="row g-4">
    <!-- Form Registrasi Item -->
    <div class="col-xl-4">
        <div class="card-premium border-0 bg-white p-4 sticky-top" style="top: 100px;">
            <div class="d-flex align-items-center gap-3 mb-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-capsules fs-6"></i>
                </div>
                <h5 class="fw-800 text-slate mb-0">Registrasi Item Baru</h5>
            </div>

            <form action="{{ route('farmasi.inventory.store') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Kode Item</label>
                        <input name="kode_obat" class="form-control bg-light border-0 fw-bold font-monospace" placeholder="OBT-XXXX" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Satuan</label>
                        <select name="satuan" class="form-select bg-light border-0 fw-bold">
                            <option value="tablet">Tablet</option>
                            <option value="kapsul">Kapsul</option>
                            <option value="sirup">Sirup / Botol</option>
                            <option value="injeksi">Vial / Ampul</option>
                            <option value="sachet">Sachet</option>
                            <option value="pcs">Pcs</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Nama & Kekuatan Sediaan</label>
                        <input name="nama_obat" class="form-control bg-light border-0 fw-bold" placeholder="Contoh: Paracetamol 500mg" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Kategori / Golongan</label>
                        <input name="kategori" class="form-control bg-light border-0" placeholder="Contoh: Analgesik Antipiretik" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Stok Awal</label>
                        <input type="number" name="stok" class="form-control bg-light border-0 fw-800 text-primary" value="0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Stok Minimum</label>
                        <input type="number" name="stok_minimum" class="form-control bg-light border-0" value="10" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Harga Beli</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted small">Rp</span>
                            <input type="number" name="harga_beli" class="form-control bg-light border-0" value="0" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Harga Jual</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted small">Rp</span>
                            <input type="number" name="harga_jual" class="form-control bg-light border-0 fw-800 text-primary" value="0" required>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Produsen / Pabrik</label>
                        <input name="manufacturer" class="form-control bg-light border-0" placeholder="Pabrikan">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Tanggal Kedaluwarsa</label>
                        <input type="date" name="expired_at" class="form-control bg-light border-0">
                    </div>
                </div>
                
                <button class="btn btn-primary w-100 mt-4 py-3 fw-800 rounded-pill shadow-sm transition-bounce-hover">
                    <i class="fa-solid fa-plus-circle me-2"></i>DAFTARKAN ITEM
                </button>
            </form>
        </div>
    </div>

    <!-- Katalog Stok -->
    <div class="col-xl-8">
        <div class="card-premium border-0 bg-white p-4 mb-4">
            <form class="row g-3" method="GET">
                <div class="col-md-9">
                    <div class="header-search w-100 max-w-none bg-light border-0">
                        <i class="fa-solid fa-magnifying-glass opacity-40"></i>
                        <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari berdasarkan nama, kode, atau kategori obat...">
                    </div>
                </div>
                <div class="col-md-3">
                    <button class="btn btn-primary w-100 h-100 fw-800 rounded-3">
                        <i class="fa-solid fa-filter me-2"></i>FILTER
                    </button>
                </div>
            </form>
        </div>

        <div class="card-premium border-0 bg-white overflow-hidden">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="fa-solid fa-boxes-stacked fs-5"></i>
                    </div>
                    <h5 class="fw-800 text-slate mb-0">Katalog Perbekalan Farmasi</h5>
                </div>
                <span class="badge bg-light text-slate border fw-800 px-3 py-2 rounded-pill">TOTAL: {{ $medicines->total() }} ITEM</span>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                            <th class="ps-4 border-0 py-3">Informasi Item</th>
                            <th class="border-0 py-3 text-center">Stok</th>
                            <th class="border-0 py-3 text-end">Harga Jual</th>
                            <th class="border-0 py-3 text-center">Kedaluwarsa</th>
                            <th class="border-0 py-3 text-center">Status</th>
                            <th class="pe-4 border-0 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($medicines as $medicine)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-teal-soft text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                        <i class="fa-solid fa-pills"></i>
                                    </div>
                                    <div>
                                        <div class="fw-800 text-slate mb-0">{{ $medicine->nama_obat }}</div>
                                        <div class="small text-muted fw-bold font-monospace opacity-75">
                                            {{ $medicine->kode_obat }} <span class="mx-1">•</span> {{ $medicine->kategori }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="text-center py-3">
                                <div class="fw-900 text-slate h6 mb-0">{{ $medicine->stok }}</div>
                                <div class="small text-muted fw-800 text-uppercase" style="font-size: 0.6rem;">{{ $medicine->satuan }}</div>
                            </td>
                            <td class="text-end py-3">
                                <div class="fw-800 text-primary">Rp {{ number_format($medicine->harga_jual, 0, ',', '.') }}</div>
                                <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Retail Price</div>
                            </td>
                            <td class="text-center py-3">
                                @if($medicine->expired_at)
                                    @php($isExpired = $medicine->expired_at->isPast())
                                    <div class="badge {{ $isExpired ? 'bg-danger' : 'bg-light text-slate border' }} px-3 py-2 fw-800" style="font-size: 0.65rem;">
                                        {{ $medicine->expired_at->format('d/m/Y') }}
                                    </div>
                                @else
                                    <span class="text-muted opacity-25 fw-bold">-</span>
                                @endif
                            </td>
                            <td class="text-center py-3">
                                @if($medicine->is_low_stock)
                                    <div class="badge bg-amber bg-opacity-10 text-amber rounded-pill px-3 py-2 fw-800 animate-pulse" style="font-size: 0.65rem;">
                                        LOW STOCK
                                    </div>
                                @else
                                    <div class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 fw-800" style="font-size: 0.65rem;">
                                        AVAILABLE
                                    </div>
                                @endif
                            </td>
                            <td class="pe-4 text-end py-3">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle border-0 shadow-none p-0" style="width: 32px; height: 32px;" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2">
                                        <li>
                                            <button class="dropdown-item py-2 rounded-3 small fw-bold" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#modalEditObat" 
                                                data-item='@json($medicine)'>
                                                <i class="fa-solid fa-pen-to-square me-2 opacity-50"></i>Edit Item
                                            </button>
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2 rounded-3 small fw-bold" href="{{ route('farmasi.inventory.show', $medicine) }}">
                                                <i class="fa-solid fa-clock-rotate-left me-2 opacity-50"></i>Kartu Stok
                                            </a>
                                        </li>
                                        <li><hr class="dropdown-divider opacity-5"></li>
                                        <li>
                                            <form action="{{ route('farmasi.inventory.destroy', $medicine) }}" method="POST" class="delete-form">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 rounded-3 small fw-800 text-danger">
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
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                    <i class="fa-solid fa-box-open fs-1 text-muted opacity-25"></i>
                                </div>
                                <h6 class="fw-800 text-slate">Belum Ada Data</h6>
                                <p class="text-muted small">Katalog stok farmasi saat ini kosong.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($medicines->hasPages())
                <div class="p-4 border-top bg-white">
                    {{ $medicines->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edit Obat (Redesigned) -->
<div class="modal fade" id="modalEditObat" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditObat" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf @method('PATCH')
            <div class="modal-header bg-slate text-white border-0 p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-pen-to-square fs-6"></i>
                    </div>
                    <h5 class="modal-title fw-800">Update Data Item</h5>
                </div>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-light bg-opacity-50">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase">Kode Item</label>
                        <input name="kode_obat" id="edit_kode" class="form-control border-0 fw-bold font-monospace" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase">Satuan</label>
                        <select name="satuan" id="edit_satuan" class="form-select border-0 fw-bold">
                            <option value="tablet">Tablet</option>
                            <option value="kapsul">Kapsul</option>
                            <option value="sirup">Sirup / Botol</option>
                            <option value="injeksi">Vial / Ampul</option>
                            <option value="sachet">Sachet</option>
                            <option value="pcs">Pcs</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-800 text-slate small text-uppercase">Nama Obat</label>
                        <input name="nama_obat" id="edit_nama" class="form-control border-0 fw-800" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-800 text-slate small text-uppercase">Kategori</label>
                        <input name="kategori" id="edit_kategori" class="form-control border-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase">Stok Minimum</label>
                        <input type="number" name="stok_minimum" id="edit_min" class="form-control border-0" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase">Harga Jual</label>
                        <input type="number" name="harga_jual" id="edit_harga" class="form-control border-0 fw-800 text-primary" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-800 text-slate small text-uppercase">Produsen</label>
                        <input name="manufacturer" id="edit_produsen" class="form-control border-0">
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 pt-0 bg-light bg-opacity-50">
                <button type="button" class="btn btn-link text-muted fw-800 text-decoration-none" data-bs-dismiss="modal">BATAL</button>
                <button class="btn btn-primary px-5 fw-800 rounded-pill shadow-sm">SIMPAN PERUBAHAN</button>
            </div>
        </form>
    </div>
</div>

<style>
    .bg-teal-soft { background: #F0FDFA; }
    .bg-slate { background: #0F172A; }
    .text-slate { color: #1E293B; }
    .text-amber { color: #F59E0B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.02); }
    .transition-hover:hover { background-color: #F8FAFC !important; }
    .header-search { background: #F1F5F9; border-radius: 12px; padding: 0.6rem 1rem; display: flex; align-items: center; gap: 0.75rem; }
    .header-search input { border: none; background: transparent; outline: none; width: 100%; font-size: 0.9rem; font-weight: 600; }
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
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'YA, HAPUS ITEM',
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

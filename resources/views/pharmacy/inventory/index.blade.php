@extends('layouts.app')

@section('title', 'Inventory Farmasi')
@section('page-title', 'Inventory Obat')
@section('page-subtitle', 'Stok, harga, expiry, dan minimum stock alert')

@section('content')
<div class="row g-3">
    <div class="col-xl-4">
        <form action="{{ route('farmasi.inventory.store') }}" method="POST" class="simrs-card">
            @csrf
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-capsules"></i>Obat Baru</div></div>
            <div class="simrs-card-body">
                <div class="row g-3">
                    <div class="col-6"><label class="form-label-custom">Kode</label><input name="kode_obat" class="form-control" required></div>
                    <div class="col-6"><label class="form-label-custom">Satuan</label><input name="satuan" class="form-control" value="tablet" required></div>
                    <div class="col-12"><label class="form-label-custom">Nama Obat</label><input name="nama_obat" class="form-control" required></div>
                    <div class="col-12"><label class="form-label-custom">Kategori</label><input name="kategori" class="form-control" required></div>
                    <div class="col-6"><label class="form-label-custom">Stok</label><input type="number" name="stok" class="form-control" value="0" required></div>
                    <div class="col-6"><label class="form-label-custom">Stok Minimum</label><input type="number" name="stok_minimum" class="form-control" value="10" required></div>
                    <div class="col-6"><label class="form-label-custom">Harga Beli</label><input type="number" name="harga_beli" class="form-control" value="0" required></div>
                    <div class="col-6"><label class="form-label-custom">Harga Jual</label><input type="number" name="harga_jual" class="form-control" value="0" required></div>
                    <div class="col-6"><label class="form-label-custom">Expired</label><input type="date" name="expired_at" class="form-control"></div>
                    <div class="col-6"><label class="form-label-custom">Pabrikan</label><input name="manufacturer" class="form-control"></div>
                </div>
                <button class="btn btn-simrs-primary w-100 mt-3"><i class="fa-solid fa-plus me-1"></i>Tambah Obat</button>
            </div>
        </form>
    </div>
    <div class="col-xl-8">
        <div class="simrs-card">
            <div class="simrs-card-header">
                <div class="simrs-card-title"><i class="fa-solid fa-boxes-stacked"></i>Daftar Obat</div>
                <form method="GET" class="d-flex gap-2">
                    <input name="q" value="{{ request('q') }}" class="form-control form-control-sm" placeholder="Cari obat">
                    <button class="btn btn-sm btn-simrs-outline"><i class="fa-solid fa-magnifying-glass"></i></button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Kode</th><th>Obat</th><th>Stok</th><th>Harga Jual</th><th>Expired</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($medicines as $medicine)
                        <tr>
                            <td class="text-mono">{{ $medicine->kode_obat }}</td>
                            <td><strong>{{ $medicine->nama_obat }}</strong><div class="small text-muted">{{ $medicine->kategori }} - {{ $medicine->manufacturer }}</div></td>
                            <td><span class="text-mono fw-bold">{{ $medicine->stok }}</span> {{ $medicine->satuan }}<div class="small text-muted">Min {{ $medicine->stok_minimum }}</div></td>
                            <td>Rp {{ number_format($medicine->harga_jual, 0, ',', '.') }}</td>
                            <td>{{ $medicine->expired_at?->format('d/m/Y') ?: '-' }}</td>
                            <td>
                                @if($medicine->is_low_stock)
                                    <span class="badge-status status-kritis">Stok rendah</span>
                                @else
                                    <span class="badge-status status-aman">Aman</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Data obat belum tersedia.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $medicines->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
</div>
@endsection

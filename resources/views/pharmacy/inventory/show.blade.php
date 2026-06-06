@extends('layouts.app')

@section('title', 'Kartu Stok Obat')
@section('page-title', 'Kartu Stok & Histori Mutasi')
@section('page-subtitle', $medicine->nama_obat . ' [' . $medicine->kode_obat . ']')

@section('content')
<div class="row g-4">
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white mb-4">
            <div class="bg-dark p-4 text-center position-relative">
                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 72px; height: 72px;">
                    <i class="fa-solid fa-pills text-primary fs-2"></i>
                </div>
                <h5 class="fw-bold text-white mb-1">{{ $medicine->nama_obat }}</h5>
                <div class="small text-info fw-semibold font-monospace" style="letter-spacing: 1px;">{{ $medicine->kode_obat }}</div>
                <i class="fa-solid fa-prescription-bottle-medical position-absolute top-50 start-50 translate-middle opacity-10" style="font-size: 8rem; z-index: 0;"></i>
            </div>
            <div class="card-body p-4 position-relative z-1">
                <div class="row g-4">
                    <div class="col-6 border-end border-light">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Stok Fisik</div>
                        <div class="h3 fw-bold text-primary mb-0">{{ $medicine->stok }}</div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">{{ $medicine->satuan }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.65rem; letter-spacing: 0.5px;">Safety Stock</div>
                        <div class="h3 fw-bold text-dark mb-0">{{ $medicine->stok_minimum }}</div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">{{ $medicine->satuan }}</div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-4 bg-light border border-light">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted fw-semibold">Kategori</span>
                                <span class="small fw-bold text-dark">{{ $medicine->kategori }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted fw-semibold">Manufaktur</span>
                                <span class="small fw-bold text-dark">{{ $medicine->manufacturer ?: 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small text-muted fw-semibold">Masa Berlaku</span>
                                <span class="small fw-bold {{ $medicine->expired_at?->isPast() ? 'text-danger' : 'text-dark' }}">
                                    {{ $medicine->expired_at?->format('d/m/Y') ?: '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('farmasi.inventory.index') }}" class="btn btn-light border border-light text-muted w-100 fw-bold py-3 rounded-pill transition-hover hover-bg-gray">
            <i class="fa-solid fa-arrow-left me-2"></i>KEMBALI KE KATALOG
        </a>
    </div>

    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="p-4 border-bottom border-light d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-bold text-dark mb-0">Log Mutasi Transaksi</h5>
                    <p class="small text-muted mb-0 fw-medium">Histori lengkap perubahan stok item ini</p>
                </div>
            </div>

            <div class="table-responsive bg-white">
                <table class="table table-hover table-borderless align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                            <th class="ps-4 py-3 rounded-start">Waktu Transaksi</th>
                            <th class="py-3">Petugas</th>
                            <th class="py-3 text-center">Tipe</th>
                            <th class="py-3 text-end">Awal</th>
                            <th class="py-3 text-center">Qty</th>
                            <th class="py-3 text-end">Akhir</th>
                            <th class="pe-4 py-3 rounded-end">Referensi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-light">
                    @forelse($medicine->transactions as $trx)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <div class="fw-semibold text-dark mb-1">{{ $trx->created_at->format('d M Y') }}</div>
                                <div class="small text-muted fw-medium"><i class="fa-regular fa-clock me-1"></i>{{ $trx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-3">
                                <div class="small fw-semibold text-dark mb-1">{{ $trx->user?->display_name ?: 'SYSTEM' }}</div>
                                <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Operator</div>
                            </td>
                            <td class="text-center py-3">
                                @php
                                    $typeCfg = match($trx->jenis_transaksi) {
                                        'masuk' => ['bg' => 'success', 'icon' => 'fa-arrow-down-long'],
                                        'keluar' => ['bg' => 'danger', 'icon' => 'fa-arrow-up-long'],
                                        default => ['bg' => 'info', 'icon' => 'fa-rotate']
                                    };
                                @endphp
                                <div class="badge bg-{{ $typeCfg['bg'] }} bg-opacity-10 text-{{ $typeCfg['bg'] }} rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    <i class="fa-solid {{ $typeCfg['icon'] }} me-1"></i>{{ strtoupper($trx->jenis_transaksi) }}
                                </div>
                            </td>
                            <td class="text-end py-3 font-monospace fw-semibold text-muted">{{ $trx->stok_sebelum }}</td>
                            <td class="text-center py-3">
                                <div class="fw-bold {{ $trx->jenis_transaksi === 'masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $trx->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ $trx->qty }}
                                </div>
                            </td>
                            <td class="text-end py-3 font-monospace fw-bold text-primary">{{ $trx->stok_sesudah }}</td>
                            <td class="pe-4 py-3">
                                <div class="small fw-semibold text-dark mb-1">{{ $trx->referensi }}</div>
                                <div class="small text-muted fw-medium fst-italic" style="font-size: 0.7rem;">{{ $trx->catatan }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="py-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                        <i class="fa-solid fa-folder-open fs-2 text-muted opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark">Belum Ada Mutasi</h6>
                                    <p class="text-muted small mb-0">Histori mutasi stok belum tersedia.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
</style>
@endsection

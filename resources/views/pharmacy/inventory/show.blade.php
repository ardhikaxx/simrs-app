@extends('layouts.app')

@section('title', 'Kartu Stok Obat')
@section('page-title', 'Kartu Stok & Histori Mutasi')
@section('page-subtitle', $medicine->nama_obat . ' [' . $medicine->kode_obat . ']')

@section('content')
<div class="row g-4">
    <div class="col-xl-4">
        <div class="card-premium border-0 overflow-hidden bg-white mb-4">
            <div class="bg-slate p-4 text-center position-relative">
                <div class="bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center mx-auto mb-3 shadow-sm" style="width: 64px; height: 64px;">
                    <i class="fa-solid fa-pills text-white fs-3"></i>
                </div>
                <h5 class="fw-800 text-white mb-1">{{ $medicine->nama_obat }}</h5>
                <div class="small text-primary-light fw-bold font-monospace tracking-widest">{{ $medicine->kode_obat }}</div>
                <i class="fa-solid fa-prescription-bottle-medical position-absolute top-50 inset-s-0 translate-middle opacity-5 fs-huge"></i>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-6 border-end">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Stok Fisik</div>
                        <div class="h3 fw-900 text-primary mb-0">{{ $medicine->stok }}</div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">{{ $medicine->satuan }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Safety Stock</div>
                        <div class="h3 fw-900 text-slate mb-0">{{ $medicine->stok_minimum }}</div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">{{ $medicine->satuan }}</div>
                    </div>
                    <div class="col-12">
                        <div class="p-3 rounded-4 bg-light bg-opacity-50 border">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted fw-bold">Kategori</span>
                                <span class="small fw-800 text-slate">{{ $medicine->kategori }}</span>
                            </div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="small text-muted fw-bold">Manufaktur</span>
                                <span class="small fw-800 text-slate">{{ $medicine->manufacturer ?: 'N/A' }}</span>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="small text-muted fw-bold">Masa Berlaku</span>
                                <span class="small fw-800 {{ $medicine->expired_at?->isPast() ? 'text-danger' : 'text-slate' }}">
                                    {{ $medicine->expired_at?->format('d/m/Y') ?: '-' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <a href="{{ route('farmasi.inventory.index') }}" class="btn btn-light border w-100 fw-800 py-3 rounded-pill transition-bounce-hover">
            <i class="fa-solid fa-arrow-left me-2"></i>KEMBALI KE KATALOG
        </a>
    </div>

    <div class="col-xl-8">
        <div class="card-premium border-0 bg-white overflow-hidden">
            <div class="p-4 border-bottom d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                </div>
                <div>
                    <h5 class="fw-800 text-slate mb-0">Log Mutasi Transaksi</h5>
                    <p class="small text-muted mb-0 fw-medium">Histori lengkap perubahan stok item ini</p>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                            <th class="ps-4 border-0 py-3">Waktu Transaksi</th>
                            <th class="border-0 py-3">Petugas</th>
                            <th class="border-0 py-3 text-center">Tipe</th>
                            <th class="border-0 py-3 text-end">Awal</th>
                            <th class="border-0 py-3 text-center">Qty</th>
                            <th class="border-0 py-3 text-end">Akhir</th>
                            <th class="pe-4 border-0 py-3">Referensi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($medicine->transactions as $trx)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <div class="fw-800 text-slate mb-0">{{ $trx->created_at->format('d M Y') }}</div>
                                <div class="small text-muted fw-bold opacity-75">{{ $trx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-3">
                                <div class="small fw-800 text-slate">{{ $trx->user?->display_name ?: 'SYSTEM' }}</div>
                                <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Operator</div>
                            </td>
                            <td class="text-center py-3">
                                @php
                                    $typeCfg = match($trx->jenis_transaksi) {
                                        'masuk' => ['bg' => 'bg-success', 'icon' => 'fa-arrow-down-long'],
                                        'keluar' => ['bg' => 'bg-danger', 'icon' => 'fa-arrow-up-long'],
                                        default => ['bg' => 'bg-blue', 'icon' => 'fa-rotate']
                                    };
                                @endphp
                                <div class="badge {{ $typeCfg['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $typeCfg['bg']) }} rounded-pill px-3 py-2 fw-800" style="font-size: 0.6rem;">
                                    <i class="fa-solid {{ $typeCfg['icon'] }} me-1"></i>{{ strtoupper($trx->jenis_transaksi) }}
                                </div>
                            </td>
                            <td class="text-end py-3 text-mono fw-bold text-muted">{{ $trx->stok_sebelum }}</td>
                            <td class="text-center py-3">
                                <div class="fw-900 {{ $trx->jenis_transaksi === 'masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $trx->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ $trx->qty }}
                                </div>
                            </td>
                            <td class="text-end py-3 text-mono fw-900 text-primary">{{ $trx->stok_sesudah }}</td>
                            <td class="pe-4 py-3">
                                <div class="small fw-800 text-slate mb-0">{{ $trx->referensi }}</div>
                                <div class="small text-muted fw-medium italic" style="font-size: 0.65rem;">{{ $trx->catatan }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                    <i class="fa-solid fa-folder-open fs-1 text-muted opacity-25"></i>
                                </div>
                                <h6 class="fw-800 text-slate">Belum Ada Mutasi</h6>
                                <p class="text-muted small">Histori mutasi stok belum tersedia.</p>
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
    .bg-slate { background: #0F172A; }
    .text-primary-light { color: #2DD4BF; }
    .fs-huge { font-size: 5rem; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: translateY(-3px); }
    .transition-hover:hover { background-color: #F8FAFC !important; }
</style>
@endsection

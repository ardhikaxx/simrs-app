@extends('layouts.app')

@section('title', 'Kartu Stok Obat')
@section('page-title', 'Kartu Stok / Mutasi Obat')
@section('page-subtitle', $medicine->nama_obat . ' [' . $medicine->kode_obat . ']')

@section('content')
<div class="row g-4">
    <div class="col-xl-4">
        <div class="simrs-card mb-4 border-0 shadow-sm overflow-hidden bg-white">
            <div class="simrs-card-body p-0">
                <div class="bg-primary text-white p-4 text-center">
                    <div class="brand-icon shadow-none bg-white text-primary mx-auto mb-3" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fa-solid fa-pills"></i>
                    </div>
                    <h5 class="fw-800 mb-1">{{ $medicine->nama_obat }}</h5>
                    <div class="text-mono small opacity-75">{{ $medicine->kode_obat }}</div>
                </div>
                <div class="p-4">
                    <div class="row g-3">
                        <div class="col-6 border-end">
                            <div class="small text-muted fw-700 text-uppercase mb-1">Stok Saat Ini</div>
                            <div class="h4 fw-800 text-simrs-primary mb-0">{{ $medicine->stok }}</div>
                            <div class="small text-muted">{{ strtoupper($medicine->satuan) }}</div>
                        </div>
                        <div class="col-6">
                            <div class="small text-muted fw-700 text-uppercase mb-1">Stok Minimum</div>
                            <div class="h4 fw-800 text-simrs-gray-900 mb-0">{{ $medicine->stok_minimum }}</div>
                            <div class="small text-muted">{{ strtoupper($medicine->satuan) }}</div>
                        </div>
                        <div class="col-12 border-top pt-3">
                            <div class="small text-muted fw-700 text-uppercase mb-1">Informasi Lainnya</div>
                            <ul class="list-unstyled mb-0 small lh-lg">
                                <li><span class="text-muted">Kategori:</span> <span class="fw-bold">{{ $medicine->kategori }}</span></li>
                                <li><span class="text-muted">Pabrikan:</span> <span class="fw-bold">{{ $medicine->manufacturer ?: '-' }}</span></li>
                                <li><span class="text-muted">Kedaluwarsa:</span> <span class="fw-bold {{ $medicine->expired_at?->isPast() ? 'text-danger' : '' }}">{{ $medicine->expired_at?->format('d/m/Y') ?: '-' }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a href="{{ route('farmasi.inventory.index') }}" class="btn btn-simrs-outline w-100 fw-bold border-0 shadow-none text-muted">
            <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Katalog
        </a>
    </div>

    <div class="col-xl-8">
        <div class="simrs-card">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title text-simrs-primary">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Log Mutasi Transaksi Stok</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Waktu</th>
                            <th>Petugas</th>
                            <th>Aksi</th>
                            <th class="text-end">Stok Awal</th>
                            <th class="text-center">Perubahan</th>
                            <th class="text-end">Stok Akhir</th>
                            <th class="pe-4">Referensi / Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($medicine->transactions as $trx)
                        <tr>
                            <td class="ps-4">
                                <div class="fw-bold small">{{ $trx->created_at->format('d/m/Y') }}</div>
                                <div class="text-muted" style="font-size: 0.7rem;">{{ $trx->created_at->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="small fw-600">{{ $trx->user?->display_name ?: 'SYSTEM' }}</div>
                            </td>
                            <td>
                                @php
                                    $badge = $trx->jenis_transaksi === 'masuk' ? 'status-aman' : ($trx->jenis_transaksi === 'keluar' ? 'status-kritis' : 'status-baru');
                                @endphp
                                <span class="badge-status {{ $badge }} py-1 px-3">
                                    {{ strtoupper($trx->jenis_transaksi) }}
                                </span>
                            </td>
                            <td class="text-end text-mono small">{{ $trx->stok_sebelum }}</td>
                            <td class="text-center">
                                <div class="fw-800 {{ $trx->jenis_transaksi === 'masuk' ? 'text-success' : 'text-danger' }}">
                                    {{ $trx->jenis_transaksi === 'masuk' ? '+' : '-' }}{{ $trx->qty }}
                                </div>
                            </td>
                            <td class="text-end text-mono fw-800 text-simrs-primary">{{ $trx->stok_sesudah }}</td>
                            <td class="pe-4">
                                <div class="small fw-600 mb-0">{{ $trx->referensi }}</div>
                                <div class="small text-muted italic" style="font-size: 0.7rem;">{{ $trx->catatan }}</div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center py-5 text-muted">Belum ada mutasi stok.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

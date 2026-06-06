@extends('layouts.app')

@section('title', 'Simulasi INA-CBG')
@section('page-title', 'Analisis Simulasi Casemix')
@section('page-subtitle', $encounter->patient->nama_pasien . ' - ' . ($result['icd10'] ?? 'Diagnosis belum tersedia'))

@section('content')
<div class="row g-4">
    <!-- Ringkasan Metrik Simulasi -->
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-secondary bg-opacity-10 text-secondary rounded-circle d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <div>
                    <div class="small fw-bold text-muted text-uppercase tracking-wider mb-1" style="font-size: 0.75rem;">Total Biaya Riil RS</div>
                    <div class="h3 fw-bolder text-dark mb-0 font-monospace">Rp {{ number_format($result['total_riil'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden bg-white">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-primary bg-gradient text-white rounded-circle shadow-sm d-flex align-items-center justify-content-center" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <div class="small fw-bold text-muted text-uppercase tracking-wider mb-1" style="font-size: 0.75rem;">Plafon INA-CBG</div>
                    <div class="h3 fw-bolder text-primary mb-0 font-monospace">Rp {{ number_format($result['tarif_ina_cbg'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @php $p = $result['persen'] ?? 0; @endphp
        @php
            $bgClass = $p > 100 ? 'bg-danger' : ($p > 85 ? 'bg-warning' : 'bg-success');
            $iconClass = $p > 100 ? 'fa-arrow-trend-up' : ($p > 85 ? 'fa-circle-exclamation' : 'fa-check-double');
        @endphp
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden {{ $bgClass }} text-white position-relative">
            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                <i class="fa-solid fa-chart-pie" style="font-size: 5rem;"></i>
            </div>
            <div class="card-body p-4 d-flex align-items-center justify-content-between position-relative z-1">
                <div>
                    <div class="small fw-bold text-white-50 text-uppercase tracking-wider mb-1" style="font-size: 0.75rem;">Persentase Utilisasi</div>
                    <div class="h1 fw-bolder mb-0 font-monospace">{{ number_format($p, 1, ',', '.') }}%</div>
                </div>
                <div class="bg-white bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 1.25rem;">
                    <i class="fa-solid {{ $iconClass }}"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Status Utilisasi -->
    <div class="col-12">
        @php
            $alertClass = ($result['status'] ?? '') === 'kritis' ? 'danger' : (($result['status'] ?? '') === 'peringatan' ? 'warning' : 'info');
        @endphp
        <div class="card border-0 shadow-sm rounded-4 bg-{{ $alertClass }} bg-opacity-10 border-start border-{{ $alertClass }} border-4">
            <div class="card-body p-4 d-flex gap-3 align-items-center">
                <div class="bg-{{ $alertClass }} text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="flex-grow-1">
                    <div class="d-flex align-items-center gap-3 mb-1">
                        <span class="h5 fw-bolder text-{{ $alertClass }} mb-0">{{ $result['kode_inacbg'] ?? 'TARIF BELUM TERSEDIA' }}</span>
                        <span class="badge bg-{{ $alertClass }} px-3 py-1 rounded-pill" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ strtoupper($result['status'] ?? 'UNKNOWN') }}
                        </span>
                    </div>
                    <div class="text-{{ $alertClass }} opacity-75 fw-semibold small">{{ $result['pesan'] ?? $result['message'] ?? 'Lengkapi rincian billing dan diagnosis klinis untuk melakukan simulasi tarif INA-CBG.' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Rincian Biaya Riil -->
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 bg-white">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-list-ul fs-6"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold mb-0 text-dark">Komponen Biaya Transaksi Pasien</h5>
                        <p class="text-muted small mb-0">Total: {{ count($encounter->billingInvoice?->billingDetails ?? []) }} Item Layanan</p>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light bg-opacity-75">
                        <tr class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                            <th class="border-0 px-4 py-3">Kategori Layanan</th>
                            <th class="border-0 py-3">Deskripsi Tindakan/Obat</th>
                            <th class="border-0 py-3 text-center">Jumlah (Qty)</th>
                            <th class="border-0 py-3 text-end">Harga Satuan</th>
                            <th class="border-0 px-4 py-3 text-end">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                    @forelse($encounter->billingInvoice?->billingDetails ?? [] as $detail)
                        <tr>
                            <td class="px-4 py-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 fw-bold" style="font-size: 0.65rem;">
                                    {{ strtoupper($detail->kategori) }}
                                </span>
                            </td>
                            <td><div class="fw-semibold text-dark">{{ $detail->deskripsi }}</div></td>
                            <td class="text-center font-monospace fw-medium">{{ rtrim(rtrim(number_format($detail->qty, 2, ',', '.'), '0'), ',') }}</td>
                            <td class="text-end font-monospace text-muted">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="px-4 py-3 text-end font-monospace fw-bold text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                                    <i class="fa-solid fa-file-invoice fs-1 text-muted opacity-50"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">Rincian Kosong</h5>
                                <p class="text-muted small">Rincian billing belum tersedia untuk kunjungan ini.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    @if(count($encounter->billingInvoice?->billingDetails ?? []) > 0)
                    <tfoot class="bg-light bg-opacity-50">
                        <tr>
                            <td colspan="4" class="text-end py-4 fw-bold text-muted text-uppercase" style="font-size: 0.85rem; letter-spacing: 1px;">Grand Total Biaya Riil</td>
                            <td class="px-4 text-end py-4 h4 mb-0 fw-bolder text-primary font-monospace">Rp {{ number_format($result['total_riil'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex flex-column flex-sm-row justify-content-between gap-3">
    <a href="{{ route('casemix.index') }}" class="btn btn-light border-light-subtle text-muted fw-bold px-4 py-2 shadow-sm rounded-pill transition-hover">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Monitoring
    </a>
    <button class="btn btn-primary bg-gradient shadow-sm px-4 py-2 fw-bold rounded-pill transition-hover" onclick="window.print()">
        <i class="fa-solid fa-print me-2"></i>Cetak Hasil Simulasi
    </button>
</div>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.15) !important; }
    .tracking-wider { letter-spacing: 0.1em; }
</style>
@endsection

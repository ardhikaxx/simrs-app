@extends('layouts.app')

@section('title', 'Simulasi INA-CBG')
@section('page-title', 'Analisis Simulasi Casemix')
@section('page-subtitle', $encounter->patient->nama_pasien . ' - ' . ($result['icd10'] ?? 'Diagnosis belum tersedia'))

@section('content')
<div class="row g-4">
    <!-- Ringkasan Metrik Simulasi -->
    <div class="col-md-4">
        <div class="simrs-card border-0 shadow-sm overflow-hidden bg-white">
            <div class="simrs-card-body d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-secondary-subtle text-secondary" style="width: 52px; height: 52px;">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider">Total Biaya Riil RS</div>
                    <div class="h4 fw-800 mb-0">Rp {{ number_format($result['total_riil'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="simrs-card border-0 shadow-sm overflow-hidden bg-white">
            <div class="simrs-card-body d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 52px; height: 52px;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider">Plafon INA-CBG</div>
                    <div class="h4 fw-800 mb-0">Rp {{ number_format($result['tarif_ina_cbg'] ?? 0, 0, ',', '.') }}</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        @php $p = $result['persen'] ?? 0; @endphp
        <div class="simrs-card border-0 shadow-sm overflow-hidden bg-{{ $p > 100 ? 'danger' : ($p > 85 ? 'warning' : 'success') }} text-white">
            <div class="simrs-card-body d-flex align-items-center justify-content-between">
                <div>
                    <div class="small fw-700 opacity-75 text-uppercase tracking-wider">Persentase Utilisasi</div>
                    <div class="h4 fw-800 mb-0">{{ number_format($p, 1, ',', '.') }}%</div>
                </div>
                <div class="h1 mb-0 opacity-25">
                    <i class="fa-solid fa-chart-line"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Alert Status Utilisasi -->
    <div class="col-12">
        <div class="alert-medical alert-medical-{{ ($result['status'] ?? '') === 'kritis' ? 'critical' : (($result['status'] ?? '') === 'peringatan' ? 'warning' : 'info') }} shadow-sm">
            <div class="brand-icon shadow-none bg-white text-{{ ($result['status'] ?? '') === 'kritis' ? 'danger' : (($result['status'] ?? '') === 'peringatan' ? 'warning' : 'info') }}" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-circle-info"></i>
            </div>
            <div>
                <div class="d-flex align-items-center gap-2 mb-1">
                    <span class="h6 fw-800 mb-0">{{ $result['kode_inacbg'] ?? 'TARIF BELUM TERSEDIA' }}</span>
                    <span class="badge-status {{ ($result['status'] ?? '') === 'kritis' ? 'status-kritis' : (($result['status'] ?? '') === 'peringatan' ? 'status-peringatan' : 'status-aman') }} py-1">
                        {{ strtoupper($result['status'] ?? 'UNKNOWN') }}
                    </span>
                </div>
                <div class="small opacity-75 fw-600">{{ $result['pesan'] ?? $result['message'] ?? 'Lengkapi rincian billing dan diagnosis klinis untuk melakukan simulasi tarif INA-CBG.' }}</div>
            </div>
        </div>
    </div>

    <!-- Rincian Biaya Riil -->
    <div class="col-12">
        <div class="simrs-card">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title text-simrs-primary">
                    <i class="fa-solid fa-list-ul"></i>
                    <span>Komponen Biaya Transaksi Pasien</span>
                </div>
                <div class="small text-muted">Total: {{ count($encounter->billingInvoice?->billingDetails ?? []) }} Item</div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Kategori Layanan</th>
                            <th>Deskripsi Tindakan/Obat</th>
                            <th class="text-end">Jumlah (Qty)</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="pe-4 text-end">Total Biaya</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($encounter->billingInvoice?->billingDetails ?? [] as $detail)
                        <tr>
                            <td class="ps-4">
                                <span class="small fw-700 text-muted text-uppercase" style="font-size: 0.65rem;">{{ $detail->kategori }}</span>
                            </td>
                            <td><div class="fw-600 text-simrs-gray-800">{{ $detail->deskripsi }}</div></td>
                            <td class="text-end text-mono">{{ number_format($detail->qty, 0, ',', '.') }}</td>
                            <td class="text-end text-mono">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="pe-4 text-end text-mono fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <i class="fa-solid fa-file-invoice fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <div class="text-muted">Rincian billing belum tersedia untuk kunjungan ini.</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                    @if(count($encounter->billingInvoice?->billingDetails ?? []) > 0)
                    <tfoot class="bg-light fw-800">
                        <tr>
                            <td colspan="4" class="text-end py-3">GRAND TOTAL BIAYA RIIL</td>
                            <td class="pe-4 text-end py-3 h5 mb-0 fw-800 text-simrs-primary">Rp {{ number_format($result['total_riil'] ?? 0, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

<div class="mt-4 d-flex justify-content-between">
    <a href="{{ route('casemix.index') }}" class="btn btn-simrs-outline shadow-sm px-4">
        <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Monitoring
    </a>
    <button class="btn btn-simrs-primary shadow-sm px-4" onclick="window.print()">
        <i class="fa-solid fa-print me-2"></i>Cetak Hasil Simulasi
    </button>
</div>
@endsection

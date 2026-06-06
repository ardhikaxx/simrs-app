@extends('layouts.app')

@section('title', 'Detail Invoice')
@section('page-title', 'Billing Invoice')
@section('page-subtitle', $invoice->no_invoice)

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <!-- Informasi Pasien Ringkas -->
        <div class="simrs-card mb-4">
            <div class="simrs-card-body">
                <div class="d-flex align-items-center gap-4">
                    <div class="user-avatar-sm" style="width: 56px; height: 56px; font-size: 1.25rem;">
                        {{ strtoupper(substr($invoice->encounter->patient->nama_pasien, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <h3 class="h5 fw-800 mb-1">{{ $invoice->encounter->patient->nama_pasien }}</h3>
                                <div class="text-mono small text-muted">No. RM: {{ $invoice->encounter->patient->no_rkm_medis }} | Reg: {{ $invoice->encounter->no_registrasi }}</div>
                            </div>
                            <span class="badge-status status-{{ $invoice->status }} fs-6 px-3">
                                {{ strtoupper($invoice->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light px-4 py-2 border-top d-flex gap-4 small">
                <div class="text-muted">Unit: <span class="fw-bold text-dark">{{ $invoice->encounter->department?->nama_depart }}</span></div>
                <div class="text-muted">DPJP: <span class="fw-bold text-dark">{{ $invoice->encounter->doctor?->display_name ?? '-' }}</span></div>
                <div class="text-muted">Penjamin: <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-0">{{ strtoupper($invoice->metode_penjamin) }}</span></div>
            </div>
        </div>

        <!-- Rincian Item Tagihan -->
        <div class="simrs-card">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title">
                    <i class="fa-solid fa-receipt text-simrs-primary"></i>
                    <span>Rincian Layanan & Tindakan</span>
                </div>
                <form action="{{ route('keuangan.invoice.generate', $invoice->encounter) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-simrs-outline shadow-sm">
                        <i class="fa-solid fa-rotate me-1"></i>Sinkronisasi Billing
                    </button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Kategori</th>
                            <th>Deskripsi Item</th>
                            <th class="text-center">Qty</th>
                            <th class="text-end">Harga Satuan</th>
                            <th class="pe-4 text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($invoice->billingDetails as $detail)
                        <tr>
                            <td class="ps-4">
                                <span class="small fw-700 text-uppercase text-muted" style="font-size: 0.65rem;">{{ $detail->kategori }}</span>
                            </td>
                            <td><div class="fw-600 text-simrs-gray-800">{{ $detail->deskripsi }}</div></td>
                            <td class="text-center text-mono">{{ rtrim(rtrim(number_format($detail->qty, 2, ',', '.'), '0'), ',') }}</td>
                            <td class="text-end text-mono">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="pe-4 text-end text-mono fw-bold">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
            <div class="simrs-card-body bg-light border-top">
                <div class="row justify-content-end">
                    <div class="col-md-5">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Subtotal:</span>
                            <span class="fw-bold">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-muted">Potongan/Diskon:</span>
                            <span class="text-danger fw-bold">- Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between pt-2 border-top border-2">
                            <span class="h6 fw-800 mb-0">Total Tagihan:</span>
                            <span class="h6 fw-800 mb-0 text-simrs-primary">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="simrs-card mt-4">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title text-success">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Riwayat Transaksi Masuk</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">No. Kuitansi</th>
                            <th>Waktu Bayar</th>
                            <th>Metode</th>
                            <th>Referensi</th>
                            <th class="pe-4 text-end">Jumlah</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($invoice->payments as $payment)
                        <tr>
                            <td class="ps-4 text-mono small fw-bold">{{ $payment->no_payment }}</td>
                            <td>{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1">{{ strtoupper($payment->metode_bayar) }}</span></td>
                            <td><span class="small text-muted">{{ $payment->referensi ?: '-' }}</span></td>
                            <td class="pe-4 text-end fw-bold text-success">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada transaksi pembayaran untuk invoice ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Ringkasan Outstanding -->
        <div class="simrs-card mb-4 bg-primary text-white border-0 shadow-lg overflow-hidden position-relative">
            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                <i class="fa-solid fa-wallet fs-1" style="transform: rotate(15deg);"></i>
            </div>
            <div class="simrs-card-body position-relative z-1">
                <div class="small opacity-75 fw-bold text-uppercase tracking-wider mb-2">Sisa Tagihan (Outstanding)</div>
                <div class="h2 fw-800 mb-2">Rp {{ number_format($invoice->outstanding, 0, ',', '.') }}</div>
                <div class="d-flex align-items-center gap-2">
                    <i class="fa-solid fa-circle-info small"></i>
                    <span class="small fw-600">Terbayar: Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>

        <!-- Perbandingan INA-CBG -->
        @if($invoice->tarif_ina_cbg)
            <div class="simrs-card mb-4 border-{{ $invoice->status_utilisasi === 'kritis' ? 'danger' : ($invoice->status_utilisasi === 'peringatan' ? 'warning' : 'success') }}">
                <div class="simrs-card-body">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="brand-icon shadow-none bg-{{ $invoice->status_utilisasi === 'kritis' ? 'danger' : ($invoice->status_utilisasi === 'peringatan' ? 'warning' : 'success') }}-subtle text-{{ $invoice->status_utilisasi === 'kritis' ? 'danger' : ($invoice->status_utilisasi === 'peringatan' ? 'warning' : 'success') }}" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <div class="small fw-700 text-muted text-uppercase tracking-wider">Estimasi INA-CBG</div>
                            <div class="fw-800 h5 mb-0 text-{{ $invoice->status_utilisasi === 'kritis' ? 'danger' : ($invoice->status_utilisasi === 'peringatan' ? 'warning' : 'success') }}">Rp {{ number_format($invoice->tarif_ina_cbg, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="p-2 rounded bg-light border small">
                        Utilisasi: <span class="fw-bold">{{ $invoice->status_utilisasi ?: 'Dalam Toleransi' }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulir Pembayaran -->
        @if($invoice->outstanding > 0)
            <div class="simrs-card shadow-sm">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title">
                        <i class="fa-solid fa-cash-register text-simrs-primary"></i>
                        <span>Proses Pembayaran</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <form action="{{ route('keuangan.payment.store', $invoice) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label-custom">Metode Pembayaran</label>
                            <select name="metode_bayar" class="form-select form-select-lg fw-bold text-simrs-primary" required>
                                @foreach(['tunai','debit','kredit','transfer','qris','bpjs','asuransi'] as $method)
                                    <option value="{{ $method }}" @selected($invoice->metode_penjamin === $method)>{{ strtoupper($method) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label-custom">Jumlah Nominal (IDR)</label>
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-light fw-bold">Rp</span>
                                <input type="number" name="jumlah_bayar" class="form-control fw-800 text-simrs-primary" value="{{ (int) $invoice->outstanding }}" required>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label-custom">Referensi / No. Kartu</label>
                            <input name="referensi" class="form-control" placeholder="No. kartu, kode transfer, dll">
                        </div>
                        <button class="btn btn-simrs-primary w-100 py-3 fw-800 shadow-sm border-0">
                            <i class="fa-solid fa-check-circle me-2"></i>Konfirmasi Pelunasan
                        </button>
                    </form>
                </div>
            </div>
        @else
            <div class="simrs-card bg-success-subtle border-success-subtle text-center py-4 px-3">
                <i class="fa-solid fa-circle-check text-success fs-1 mb-3"></i>
                <h5 class="fw-800 text-success mb-1">Tagihan Lunas</h5>
                <p class="small text-success opacity-75 mb-0">Invoice ini telah dibayar penuh dan status kunjungan telah ditutup.</p>
                <div class="mt-4">
                    <button class="btn btn-sm btn-success px-4" onclick="window.print()"><i class="fa-solid fa-print me-2"></i>Cetak Kuitansi</button>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

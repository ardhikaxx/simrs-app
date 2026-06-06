@extends('layouts.app')

@section('title', 'Invoice')
@section('page-title', 'Invoice ' . $invoice->no_invoice)
@section('page-subtitle', $invoice->encounter->patient->nama_pasien . ' - ' . $invoice->encounter->no_registrasi)

@section('content')
<div class="row g-3">
    <div class="col-xl-8">
        <div class="simrs-card">
            <div class="simrs-card-header">
                <div>
                    <div class="simrs-card-title"><i class="fa-solid fa-file-invoice-dollar"></i>Rincian Tagihan</div>
                    <div class="small text-muted">{{ $invoice->encounter->department?->nama_depart }} - {{ strtoupper($invoice->metode_penjamin) }}</div>
                </div>
                <form action="{{ route('keuangan.invoice.generate', $invoice->encounter) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-simrs-outline"><i class="fa-solid fa-rotate me-1"></i>Hitung Ulang</button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Kategori</th><th>Deskripsi</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr></thead>
                    <tbody>
                    @foreach($invoice->billingDetails as $detail)
                        <tr>
                            <td>{{ $detail->kategori }}</td>
                            <td>{{ $detail->deskripsi }}</td>
                            <td class="text-mono">{{ rtrim(rtrim(number_format($detail->qty, 2, ',', '.'), '0'), ',') }}</td>
                            <td>Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                        <tr><th colspan="4" class="text-end">Subtotal</th><th>Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</th></tr>
                        <tr><th colspan="4" class="text-end">Diskon</th><th>Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</th></tr>
                        <tr><th colspan="4" class="text-end">Total Tagihan</th><th>Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</th></tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-receipt"></i>Riwayat Pembayaran</div></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>No Payment</th><th>Tanggal</th><th>Metode</th><th>Referensi</th><th>Jumlah</th></tr></thead>
                    <tbody>
                    @forelse($invoice->payments as $payment)
                        <tr>
                            <td class="text-mono">{{ $payment->no_payment }}</td>
                            <td>{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                            <td>{{ strtoupper($payment->metode_bayar) }}</td>
                            <td>{{ $payment->referensi ?: '-' }}</td>
                            <td>Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Belum ada pembayaran.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        <div class="kpi-card mb-3">
            <div class="kpi-label">Outstanding</div>
            <div class="kpi-value">Rp {{ number_format($invoice->outstanding, 0, ',', '.') }}</div>
            <span class="badge-status status-{{ $invoice->status }}">{{ ucfirst($invoice->status) }}</span>
        </div>
        @if($invoice->tarif_ina_cbg)
            <div class="alert-medical alert-medical-{{ $invoice->status_utilisasi === 'kritis' ? 'critical' : ($invoice->status_utilisasi === 'peringatan' ? 'warning' : 'info') }}">
                <div><i class="fa-solid fa-scale-balanced"></i></div>
                <div><strong>INA-CBG</strong><div class="small">Tarif Rp {{ number_format($invoice->tarif_ina_cbg, 0, ',', '.') }} - utilisasi {{ $invoice->status_utilisasi ?: 'belum dihitung' }}</div></div>
            </div>
        @endif
        @if($invoice->outstanding > 0)
            <form action="{{ route('keuangan.payment.store', $invoice) }}" method="POST" class="simrs-card">
                @csrf
                <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-cash-register"></i>Pembayaran</div></div>
                <div class="simrs-card-body">
                    <div class="mb-3">
                        <label class="form-label-custom">Metode</label>
                        <select name="metode_bayar" class="form-select" required>
                            @foreach(['tunai','debit','kredit','transfer','qris','bpjs','asuransi'] as $method)
                                <option value="{{ $method }}" @selected($invoice->metode_penjamin === $method)>{{ strtoupper($method) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Jumlah Bayar</label>
                        <input type="number" name="jumlah_bayar" class="form-control" value="{{ (int) $invoice->outstanding }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-custom">Referensi</label>
                        <input name="referensi" class="form-control" placeholder="No kartu, no transfer, atau catatan">
                    </div>
                    <button class="btn btn-simrs-primary w-100"><i class="fa-solid fa-check me-1"></i>Proses Bayar</button>
                </div>
            </form>
        @endif
    </div>
</div>
@endsection

@extends('layouts.app')

@section('title', 'Detail Invoice Kasir')
@section('page-title', 'Penyelesaian Transaksi (Kasir)')
@section('page-subtitle', 'Rincian invoice No. ' . $invoice->no_invoice)

@section('content')
<div class="row g-4">
    <div class="col-xl-8">
        <!-- Informasi Pasien Ringkas -->
        <div class="card border-0 shadow-sm rounded-4 mb-4 overflow-hidden bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle shadow-sm" style="width: 70px; height: 70px; font-size: 1.75rem; border: 2px solid var(--simrs-primary-pale);">
                        {{ strtoupper(substr($invoice->encounter->patient->nama_pasien, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div>
                                <h4 class="fw-bold mb-1 text-dark">{{ $invoice->encounter->patient->nama_pasien }}</h4>
                                <div class="text-muted font-monospace small"><i class="fa-solid fa-id-badge me-1"></i>RM: {{ $invoice->encounter->patient->no_rkm_medis }} <span class="mx-2 text-black-50">&bull;</span> Reg: {{ $invoice->encounter->no_registrasi }}</div>
                            </div>
                            @php
                                $statusProps = match($invoice->status) {
                                    'lunas' => ['bg' => 'bg-success', 'text' => 'text-success'],
                                    'parsial' => ['bg' => 'bg-warning', 'text' => 'text-warning'],
                                    'draft' => ['bg' => 'bg-secondary', 'text' => 'text-secondary'],
                                    default => ['bg' => 'bg-dark', 'text' => 'text-dark']
                                };
                            @endphp
                            <span class="badge {{ $statusProps['bg'] }} bg-opacity-10 {{ $statusProps['text'] }} px-4 py-2 rounded-pill fs-6 fw-bold border border-{{ str_replace('bg-', '', $statusProps['bg']) }} border-opacity-25">
                                {{ strtoupper($invoice->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light px-4 py-3 border-top d-flex flex-wrap gap-4 small">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white p-2 rounded shadow-sm text-muted"><i class="fa-solid fa-house-medical"></i></div>
                    <div>
                        <div class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Unit Pelayanan</div>
                        <div class="fw-bold text-dark">{{ $invoice->encounter->department?->nama_depart }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white p-2 rounded shadow-sm text-muted"><i class="fa-solid fa-user-doctor"></i></div>
                    <div>
                        <div class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Dokter DPJP</div>
                        <div class="fw-bold text-dark">{{ $invoice->encounter->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white p-2 rounded shadow-sm text-muted"><i class="fa-solid fa-shield-halved"></i></div>
                    <div>
                        <div class="text-muted fw-bold text-uppercase" style="font-size: 0.65rem;">Penjamin</div>
                        <div class="fw-bold text-primary">{{ strtoupper($invoice->metode_penjamin) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rincian Item Tagihan -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
            <div class="card-header bg-white border-bottom p-4 d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-receipt fs-6"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Rincian Layanan & Tindakan</h5>
                </div>
                <form action="{{ route('keuangan.invoice.generate', $invoice->encounter) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-light border-light-subtle text-primary fw-semibold px-3 rounded-pill shadow-sm transition-hover">
                        <i class="fa-solid fa-rotate me-1"></i>Sinkronisasi Billing
                    </button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-75">
                        <tr class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                            <th class="border-0 px-4 py-3">Kategori</th>
                            <th class="border-0 py-3">Deskripsi Item</th>
                            <th class="border-0 py-3 text-center">Qty</th>
                            <th class="border-0 py-3 text-end">Harga Satuan</th>
                            <th class="border-0 px-4 py-3 text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                    @forelse($invoice->billingDetails as $detail)
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
                            <td colspan="5" class="text-center py-4 text-muted small">Rincian tagihan belum digenerate. Silakan klik tombol sinkronisasi.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-body bg-light bg-opacity-50 border-top p-4">
                <div class="row justify-content-end">
                    <div class="col-md-6 col-lg-5">
                        <div class="d-flex justify-content-between mb-2 small">
                            <span class="text-muted fw-semibold">Subtotal Tagihan:</span>
                            <span class="fw-bold text-dark font-monospace">Rp {{ number_format($invoice->subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 small">
                            <span class="text-muted fw-semibold">Potongan/Diskon:</span>
                            <span class="text-danger fw-bold font-monospace">- Rp {{ number_format($invoice->diskon, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between pt-3 border-top border-dark border-opacity-10">
                            <span class="h5 fw-bold mb-0 text-dark">Total Tagihan:</span>
                            <span class="h5 fw-bolder mb-0 text-primary font-monospace">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="card border-0 shadow-sm rounded-4">
            <div class="card-header bg-white border-bottom p-4 d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-clock-rotate-left fs-6"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Riwayat Transaksi Masuk</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light bg-opacity-75">
                        <tr class="text-muted fw-bold text-uppercase" style="letter-spacing: 0.5px; font-size: 0.75rem;">
                            <th class="border-0 px-4 py-3">No. Kuitansi</th>
                            <th class="border-0 py-3">Waktu Transaksi</th>
                            <th class="border-0 py-3 text-center">Metode</th>
                            <th class="border-0 py-3">Referensi</th>
                            <th class="border-0 px-4 py-3 text-end">Nominal Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                    @forelse($invoice->payments as $payment)
                        <tr>
                            <td class="px-4 py-3 font-monospace fw-bold text-dark small">{{ $payment->no_payment }}</td>
                            <td class="text-muted small fw-medium">{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-center">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-bold" style="font-size: 0.65rem;">
                                    {{ strtoupper($payment->metode_bayar) }}
                                </span>
                            </td>
                            <td><span class="small text-muted">{{ $payment->referensi ?: '-' }}</span></td>
                            <td class="px-4 py-3 text-end fw-bolder text-success font-monospace">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5 small">Belum ada transaksi pembayaran untuk invoice ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <!-- Ringkasan Outstanding (Premium Card) -->
        <div class="card border-0 shadow-lg rounded-4 mb-4 overflow-hidden position-relative bg-primary bg-gradient text-white">
            <div class="position-absolute top-0 end-0 p-4 opacity-10">
                <i class="fa-solid fa-wallet" style="font-size: 6rem; transform: rotate(15deg);"></i>
            </div>
            <div class="card-body p-4 position-relative z-1">
                <div class="small text-white-50 fw-bold text-uppercase tracking-wider mb-2">Sisa Tagihan (Outstanding)</div>
                <div class="fw-bolder mb-3 font-monospace" style="font-size: 2.5rem; line-height: 1;">Rp {{ number_format($invoice->outstanding, 0, ',', '.') }}</div>
                
                <div class="bg-white bg-opacity-10 rounded-3 p-3 mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <span class="small text-white-50 fw-semibold">Total Tagihan</span>
                        <span class="small text-white fw-bold font-monospace">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="small text-white-50 fw-semibold">Telah Dibayar</span>
                        <span class="small text-white fw-bold font-monospace">Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perbandingan INA-CBG -->
        @if($invoice->tarif_ina_cbg)
            @php
                $inaClass = $invoice->status_utilisasi === 'kritis' ? 'danger' : ($invoice->status_utilisasi === 'peringatan' ? 'warning' : 'success');
            @endphp
            <div class="card border-0 shadow-sm rounded-4 mb-4 border-start border-{{ $inaClass }} border-4 bg-white">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="d-flex align-items-center justify-content-center bg-{{ $inaClass }} bg-opacity-10 text-{{ $inaClass }} rounded-circle shadow-sm" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <div class="small fw-bold text-muted text-uppercase tracking-wider">Estimasi Plafon INA-CBG</div>
                            <div class="fw-bolder h4 mb-0 text-{{ $inaClass }} font-monospace">Rp {{ number_format($invoice->tarif_ina_cbg, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light border border-light-subtle small fw-semibold text-muted">
                        <span>Status Utilisasi:</span>
                        <span class="badge bg-{{ $inaClass }} bg-opacity-10 text-{{ $inaClass }} px-2 py-1">{{ strtoupper($invoice->status_utilisasi ?: 'AMAN') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulir Pembayaran -->
        @if($invoice->outstanding > 0)
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-cash-register fs-6"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Proses Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('keuangan.payment.store', $invoice) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="form-floating mb-3">
                            <select name="metode_bayar" class="form-select fw-bold text-primary shadow-none border-light-subtle bg-light" id="floatingMetode" required>
                                @foreach(['tunai','debit','kredit','transfer','qris','bpjs','asuransi'] as $method)
                                    <option value="{{ $method }}" @selected($invoice->metode_penjamin === $method)>{{ strtoupper($method) }}</option>
                                @endforeach
                            </select>
                            <label for="floatingMetode" class="text-muted fw-semibold">Metode Transaksi</label>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Jumlah Nominal (IDR)</label>
                            <div class="input-group input-group-lg shadow-none">
                                <span class="input-group-text bg-light border-end-0 text-muted fw-bold fs-6">Rp</span>
                                <input type="number" name="jumlah_bayar" class="form-control border-start-0 bg-light fw-bolder text-primary font-monospace shadow-none" value="{{ (int) $invoice->outstanding }}" required>
                            </div>
                        </div>
                        
                        <div class="form-floating mb-4">
                            <input name="referensi" class="form-control shadow-none border-light-subtle bg-light" id="floatingRef" placeholder="Referensi">
                            <label for="floatingRef" class="text-muted fw-semibold">No. Referensi / EDC / Catatan</label>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-3 transition-hover" style="background: linear-gradient(135deg, var(--simrs-primary), var(--simrs-primary-light)); border: none;">
                            <i class="fa-solid fa-check-double me-2"></i>Konfirmasi Pelunasan
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- Panel Lunas -->
            <div class="card border-0 shadow-sm rounded-4 bg-success bg-opacity-10 border border-success border-opacity-25 text-center py-5 px-4">
                <div class="d-flex justify-content-center mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-check-to-slot" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bolder text-success mb-2">Tagihan Telah Lunas</h4>
                <p class="small text-success opacity-75 fw-medium mb-4">Semua kewajiban administrasi untuk invoice ini telah diselesaikan. Kunjungan dinyatakan ditutup secara finansial.</p>
                
                <button class="btn btn-success fw-bold rounded-pill shadow-sm px-4 py-2 transition-hover" onclick="window.print()">
                    <i class="fa-solid fa-print me-2"></i>Cetak Kuitansi Resmi
                </button>
            </div>
        @endif
    </div>
</div>

<style>
    .transition-hover { transition: all 0.3s ease; }
    .transition-hover:hover { transform: translateY(-3px); box-shadow: 0 8px 15px rgba(11, 100, 119, 0.25) !important; }
    .tracking-wider { letter-spacing: 0.1em; }
    .form-floating > label { font-size: 0.85rem; padding-left: 1.25rem; }
    .form-control:focus, .form-select:focus { border-color: var(--simrs-primary); box-shadow: 0 0 0 0.25rem rgba(11, 100, 119, 0.1) !important; }
    
    @media print {
        body { background: white !important; }
        .main-wrapper { margin: 0 !important; padding: 0 !important; }
        .simrs-sidebar, .simrs-topbar, .simrs-footer, form, .btn { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #ccc !important; }
    }
</style>
@endsection
@extends('layouts.app')

@section('title', 'Detail Invoice Kasir')
@section('page-title', 'Penyelesaian Transaksi (Kasir)')
@section('page-subtitle', 'Rincian invoice No. ' . $invoice->no_invoice)

@section('content')
<div class="row g-4">
    <div class="col-xl-8 d-flex flex-column gap-4">
        <!-- Informasi Pasien Ringkas -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-4">
                    <div class="d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary rounded-circle shadow-sm" style="width: 70px; height: 70px; font-size: 1.75rem;">
                        {{ strtoupper(substr($invoice->encounter->patient->nama_pasien, 0, 1)) }}
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-start mb-2 gap-3">
                            <div>
                                <h4 class="fw-bold mb-1 text-dark">{{ $invoice->encounter->patient->nama_pasien }}</h4>
                                <div class="text-muted font-monospace small fw-medium">
                                    <i class="fa-solid fa-id-badge me-1"></i>RM: {{ $invoice->encounter->patient->no_rkm_medis }} 
                                    <span class="mx-2 text-black-50">&bull;</span> Reg: {{ $invoice->encounter->no_registrasi }}
                                </div>
                            </div>
                            @php
                                $statusProps = match($invoice->status) {
                                    'lunas' => ['bg' => 'success', 'text' => 'success'],
                                    'parsial' => ['bg' => 'warning', 'text' => 'warning'],
                                    'draft' => ['bg' => 'secondary', 'text' => 'secondary'],
                                    default => ['bg' => 'dark', 'text' => 'dark']
                                };
                            @endphp
                            <span class="badge bg-{{ $statusProps['bg'] }} bg-opacity-10 text-{{ $statusProps['text'] }} px-4 py-2 rounded-pill fs-6 fw-bold border border-{{ $statusProps['bg'] }} border-opacity-25 shadow-none">
                                {{ strtoupper($invoice->status) }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-light bg-opacity-50 px-4 py-3 border-top border-light d-flex flex-wrap gap-4 small">
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white p-2 rounded shadow-sm text-muted"><i class="fa-solid fa-house-medical"></i></div>
                    <div>
                        <div class="text-muted fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Unit Pelayanan</div>
                        <div class="fw-bold text-dark">{{ $invoice->encounter->department?->nama_depart }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white p-2 rounded shadow-sm text-muted"><i class="fa-solid fa-user-doctor"></i></div>
                    <div>
                        <div class="text-muted fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Dokter DPJP</div>
                        <div class="fw-bold text-dark">{{ $invoice->encounter->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-white p-2 rounded shadow-sm text-muted"><i class="fa-solid fa-shield-halved"></i></div>
                    <div>
                        <div class="text-muted fw-semibold text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Penjamin</div>
                        <div class="fw-bold text-primary">{{ strtoupper($invoice->metode_penjamin) }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rincian Item Tagihan -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-bottom border-light p-4 d-flex flex-column flex-sm-row justify-content-between align-items-sm-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-receipt fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Rincian Layanan & Tindakan</h5>
                </div>
                <form action="{{ route('keuangan.invoice.generate', $invoice->encounter) }}" method="POST">
                    @csrf
                    <button class="btn btn-sm btn-light border border-light-subtle text-primary fw-bold px-3 rounded-pill shadow-sm transition-hover hover-bg-gray">
                        <i class="fa-solid fa-rotate me-1"></i>Sinkronisasi Billing
                    </button>
                </form>
            </div>
            <div class="table-responsive bg-white">
                <table class="table table-hover table-borderless align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                            <th class="ps-4 py-3 rounded-start">Kategori</th>
                            <th class="py-3">Deskripsi Item</th>
                            <th class="py-3 text-center">Qty</th>
                            <th class="py-3 text-end">Harga Satuan</th>
                            <th class="pe-4 py-3 text-end rounded-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-light">
                    @forelse($invoice->billingDetails as $detail)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 fw-semibold" style="font-size: 0.65rem;">
                                    {{ strtoupper($detail->kategori) }}
                                </span>
                            </td>
                            <td class="py-3"><div class="fw-semibold text-dark">{{ $detail->deskripsi }}</div></td>
                            <td class="text-center py-3 font-monospace fw-medium">{{ rtrim(rtrim(number_format($detail->qty, 2, ',', '.'), '0'), ',') }}</td>
                            <td class="text-end py-3 font-monospace text-muted">Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}</td>
                            <td class="pe-4 py-3 text-end font-monospace fw-bold text-dark">Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted small">
                                <div class="py-4">
                                    <i class="fa-solid fa-file-invoice fs-1 opacity-10 mb-3 d-block"></i>
                                    Rincian tagihan belum tersedia. Silakan sinkronisasi data.
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="card-body bg-light bg-opacity-50 border-top border-light p-4">
                <div class="row justify-content-end">
                    <div class="col-md-7 col-lg-6">
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
                            <span class="h5 fw-bold mb-0 text-primary font-monospace">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Riwayat Pembayaran -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Riwayat Transaksi Masuk</h5>
            </div>
            <div class="table-responsive bg-white">
                <table class="table table-hover table-borderless align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                            <th class="ps-4 py-3 rounded-start">No. Kuitansi</th>
                            <th class="py-3">Waktu Transaksi</th>
                            <th class="py-3 text-center">Metode</th>
                            <th class="py-3">Referensi</th>
                            <th class="pe-4 py-3 text-end rounded-end">Nominal Masuk</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-light">
                    @forelse($invoice->payments as $payment)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3 font-monospace fw-bold text-dark small">{{ $payment->no_payment }}</td>
                            <td class="py-3 text-muted small fw-medium">{{ $payment->paid_at?->format('d/m/Y H:i') }}</td>
                            <td class="text-center py-3">
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.65rem;">
                                    {{ strtoupper($payment->metode_bayar) }}
                                </span>
                            </td>
                            <td class="py-3"><span class="small text-muted fw-medium">{{ $payment->referensi ?: '-' }}</span></td>
                            <td class="pe-4 py-3 text-end fw-bold text-success font-monospace">Rp {{ number_format($payment->jumlah_bayar, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-5 small fw-medium">Belum ada transaksi pembayaran untuk invoice ini.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-xl-4 d-flex flex-column gap-4">
        <!-- Ringkasan Outstanding (Clean SaaS Card) -->
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-wallet fs-4"></i>
                    </div>
                    @if($invoice->outstanding > 0)
                        <span class="badge bg-danger bg-opacity-10 text-danger border border-danger border-opacity-25 px-3 py-1 rounded-pill fw-bold" style="font-size: 0.65rem;">OUTSTANDING</span>
                    @else
                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-3 py-1 rounded-pill fw-bold" style="font-size: 0.65rem;">SETTLED</span>
                    @endif
                </div>
                <div class="small text-muted fw-semibold text-uppercase mb-1" style="letter-spacing: 1px; font-size: 0.7rem;">Sisa Tagihan</div>
                <div class="h2 fw-bold text-dark mb-4 font-monospace">Rp {{ number_format($invoice->outstanding, 0, ',', '.') }}</div>
                
                <div class="p-3 rounded-4 bg-light border border-light">
                    <div class="d-flex justify-content-between align-items-center mb-2 small">
                        <span class="text-muted fw-medium">Total Tagihan</span>
                        <span class="text-dark fw-bold font-monospace">Rp {{ number_format($invoice->total_tagihan, 0, ',', '.') }}</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center small">
                        <span class="text-muted fw-medium">Telah Dibayar</span>
                        <span class="text-success fw-bold font-monospace">Rp {{ number_format($invoice->total_dibayar, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Perbandingan INA-CBG -->
        @if($invoice->tarif_ina_cbg)
            @php
                $inaClass = $invoice->status_utilisasi === 'kritis' ? 'danger' : ($invoice->status_utilisasi === 'peringatan' ? 'warning' : 'success');
            @endphp
            <div class="card border-0 shadow-sm rounded-4 border-start border-{{ $inaClass }} border-4 bg-white transition-hover">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="d-flex align-items-center justify-content-center bg-{{ $inaClass }} bg-opacity-10 text-{{ $inaClass }} rounded-3 shadow-sm" style="width: 48px; height: 48px; font-size: 1.25rem;">
                            <i class="fa-solid fa-scale-balanced"></i>
                        </div>
                        <div>
                            <div class="small fw-semibold text-muted text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">Plafon INA-CBG</div>
                            <div class="fw-bold h4 mb-0 text-{{ $inaClass }} font-monospace">Rp {{ number_format($invoice->tarif_ina_cbg, 0, ',', '.') }}</div>
                        </div>
                    </div>
                    <div class="d-flex align-items-center justify-content-between p-2 rounded-3 bg-light border border-light small fw-bold text-muted">
                        <span>Status Utilisasi:</span>
                        <span class="badge bg-{{ $inaClass }} bg-opacity-10 text-{{ $inaClass }} px-3 py-1 rounded-pill">{{ strtoupper($invoice->status_utilisasi ?: 'AMAN') }}</span>
                    </div>
                </div>
            </div>
        @endif

        <!-- Formulir Pembayaran -->
        @if($invoice->outstanding > 0)
            <div class="card border-0 shadow-sm rounded-4 bg-white">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-cash-register fs-6"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Proses Pembayaran</h5>
                </div>
                <div class="card-body p-4">
                    <form action="{{ route('keuangan.payment.store', $invoice) }}" method="POST" class="needs-validation" novalidate>
                        @csrf
                        <div class="mb-4">
                            <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Metode Transaksi</label>
                            <select name="metode_bayar" class="form-select fw-bold text-primary shadow-none border-light bg-light py-2 px-3 rounded-3" required>
                                @foreach(['tunai','debit','kredit','transfer','qris','bpjs','asuransi'] as $method)
                                    <option value="{{ $method }}" @selected($invoice->metode_penjamin === $method)>{{ strtoupper($method) }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Nominal Bayar (IDR)</label>
                            <div class="input-group input-group-lg bg-light rounded-3 overflow-hidden border border-light">
                                <span class="input-group-text bg-transparent border-0 text-muted fw-bold fs-6">Rp</span>
                                <input type="number" name="jumlah_bayar" class="form-control border-0 bg-transparent fw-bold text-primary font-monospace shadow-none focus-ring-0" value="{{ (int) $invoice->outstanding }}" required>
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Referensi / No. Kartu</label>
                            <input name="referensi" class="form-control bg-light border-light shadow-none focus-ring-0 py-2 px-3 rounded-3" placeholder="No. EDC / Ref Transfer / Catatan">
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm transition-hover">
                            <i class="fa-solid fa-check-double me-2"></i>SIMPAN TRANSAKSI
                        </button>
                    </form>
                </div>
            </div>
        @else
            <!-- Panel Lunas -->
            <div class="card border-0 shadow-sm rounded-4 bg-success bg-opacity-10 border border-success border-opacity-10 text-center py-5 px-4 transition-hover">
                <div class="d-flex justify-content-center mb-3">
                    <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 80px; height: 80px;">
                        <i class="fa-solid fa-check-to-slot" style="font-size: 2.5rem;"></i>
                    </div>
                </div>
                <h4 class="fw-bold text-success mb-2">Tagihan Telah Lunas</h4>
                <p class="small text-success opacity-75 fw-medium mb-4 lh-sm">Semua kewajiban administrasi telah diselesaikan. Kunjungan dinyatakan ditutup secara finansial.</p>
                
                <button class="btn btn-success fw-bold rounded-pill shadow-sm px-5 py-3 transition-hover w-100" onclick="window.print()">
                    <i class="fa-solid fa-print me-2"></i>CETAK KUITANSI
                </button>
            </div>
        @endif
    </div>
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
    .border-start-md { border-left: 1px solid #e2e8f0; }

    @media print {
        body { background: white !important; }
        .main-content { margin-left: 0 !important; padding: 0 !important; }
        .sidebar, .topbar, .sidebar-overlay, footer, .action-buttons, form, .btn { display: none !important; }
        .content-wrapper { padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
        .card { box-shadow: none !important; border: 1px solid #eee !important; margin-bottom: 2rem !important; }
        .rounded-4, .rounded-3, .rounded-pill, .rounded-circle { border-radius: 0 !important; }
        .bg-light { background-color: #f8f9fa !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
@endsection
@extends('layouts.app')

@section('title', 'Antrian Resep')
@section('page-title', 'Dispensing Farmasi')
@section('page-subtitle', 'Manajemen antrean resep elektronik dan penyiapan obat')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-6 col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-receipt fs-4"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Total Resep Antre</div>
                    <h3 class="fw-bold text-dark mb-0">{{ $prescriptions->total() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-8 d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
        <a href="{{ route('farmasi.inventory.index') }}" class="btn btn-light border bg-white px-4 py-2 fw-medium shadow-sm rounded-3 text-muted hover-bg-gray transition-hover">
            <i class="fa-solid fa-boxes-stacked me-2"></i>Manajemen Stok
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-prescription fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Daftar Tunggu Dispensing</h5>
                <p class="small text-muted mb-0 fw-medium">Monitoring real-time resep dari poliklinik & IGD</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Resep / Waktu</th>
                    <th class="py-3">Pasien & Unit</th>
                    <th class="py-3">DPJP Pengirim</th>
                    <th class="py-3" style="width: 300px;">Rincian Obat</th>
                    <th class="py-3 text-end">Estimasi Biaya</th>
                    <th class="py-3 text-center">Status</th>
                    <th class="pe-4 py-3 text-center rounded-end">Aksi</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($prescriptions as $prescription)
                @php($total = $prescription->details->sum('subtotal'))
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-primary mb-1 h6 font-monospace">{{ $prescription->no_resep }}</div>
                        <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock opacity-50"></i>
                            {{ $prescription->created_at?->format('H:i') }} <span class="opacity-25 mx-1">|</span> {{ $prescription->created_at?->diffForHumans() }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $prescription->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted fw-medium font-monospace d-flex align-items-center gap-2">
                            <span class="badge bg-light text-secondary border px-2 py-0" style="font-size: 0.65rem;">{{ $prescription->encounter->patient->no_rkm_medis }}</span>
                            <span class="opacity-75">{{ $prescription->encounter->department?->nama_depart }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-medium text-dark small mb-1">{{ $prescription->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;">Verified Practitioner</div>
                    </td>
                    <td class="py-3">
                        <div class="prescription-items-container p-2 rounded-3 bg-light border border-light">
                            @foreach($prescription->details as $detail)
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-1 last-mb-0">
                                    <span class="small fw-semibold text-dark text-truncate" style="max-width: 200px;">{{ $detail->nama_obat }}</span>
                                    <span class="badge bg-white text-primary border border-primary border-opacity-25 font-monospace fw-semibold" style="font-size: 0.65rem;">x{{ rtrim(rtrim(number_format($detail->jumlah, 1, ',', '.'), '0'), ',') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-end py-3">
                        <div class="fw-bold text-dark">Rp {{ number_format($total, 0, ',', '.') }}</div>
                        <div class="small text-muted fw-semibold text-uppercase" style="font-size: 0.65rem;">Total Billing</div>
                    </td>
                    <td class="text-center py-3">
                        @php($cfg = match($prescription->status) {
                            'baru' => ['bg' => 'warning', 'text' => 'PENDING'],
                            'proses' => ['bg' => 'info', 'text' => 'PREPARING'],
                            'selesai' => ['bg' => 'success', 'text' => 'DISPENSED'],
                            default => ['bg' => 'secondary', 'text' => strtoupper($prescription->status)]
                        })
                        <div class="badge bg-{{ $cfg['bg'] }} bg-opacity-10 text-{{ $cfg['bg'] }} rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ $cfg['text'] }}
                        </div>
                    </td>
                    <td class="pe-4 text-center py-3">
                        @if($prescription->status !== 'selesai')
                            <form action="{{ route('farmasi.dispense', $prescription) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary btn-sm px-3 fw-medium rounded-pill shadow-sm transition-hover">
                                    <i class="fa-solid fa-capsules me-1"></i>Dispense
                                </button>
                            </form>
                        @else
                            <div class="d-inline-flex flex-column align-items-center">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-1" style="width: 32px; height: 32px;">
                                    <i class="fa-solid fa-check fs-6"></i>
                                </div>
                                <div class="small text-muted fw-bold" style="font-size: 0.65rem;">{{ $prescription->dispensed_at?->format('H:i') }}</div>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-receipt fs-2 text-muted opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-dark">Antrean Kosong</h6>
                            <p class="text-muted small">Tidak ada resep tertunda yang perlu diproses.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($prescriptions->hasPages())
        <div class="p-4 border-top border-light bg-white rounded-bottom-4">
            {{ $prescriptions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .prescription-items-container { max-height: 100px; overflow-y: auto; scrollbar-width: none; }
    .prescription-items-container::-webkit-scrollbar { display: none; }
    .last-mb-0:last-child { margin-bottom: 0 !important; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
</style>
@endsection

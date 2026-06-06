@extends('layouts.app')

@section('title', 'Antrian Resep')
@section('page-title', 'Dispensing Farmasi')
@section('page-subtitle', 'Manajemen antrean resep elektronik dan penyiapan obat')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-teal-soft text-primary">
                    <i class="fa-solid fa-receipt"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Total Resep Antre</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ $prescriptions->total() }}</h3>
                </div>
            </div>
            <i class="fa-solid fa-pills position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
    <div class="col-md-8 text-md-end d-flex align-items-center justify-content-md-end gap-3">
        <a href="{{ route('farmasi.inventory.index') }}" class="btn-premium btn-light border bg-white px-4">
            <i class="fa-solid fa-boxes-stacked opacity-50"></i>MANAJEMEN STOK
        </a>
    </div>
</div>

<div class="card-premium border-0 bg-white overflow-hidden">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-prescription fs-5"></i>
            </div>
            <div>
                <h5 class="fw-800 text-slate mb-0">Daftar Tunggu Dispensing</h5>
                <p class="small text-muted mb-0 fw-medium">Monitoring real-time resep dari poliklinik & IGD</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                    <th class="ps-4 border-0 py-3">No. Resep / Waktu</th>
                    <th class="border-0 py-3">Pasien & Unit</th>
                    <th class="border-0 py-3">DPJP Pengirim</th>
                    <th class="border-0 py-3" style="width: 300px;">Rincian Obat</th>
                    <th class="border-0 py-3 text-end">Estimasi Biaya</th>
                    <th class="border-0 py-3 text-center">Status</th>
                    <th class="pe-4 border-0 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($prescriptions as $prescription)
                @php($total = $prescription->details->sum('subtotal'))
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="text-mono fw-800 text-primary mb-1">{{ $prescription->no_resep }}</div>
                        <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock opacity-50"></i>
                            {{ $prescription->created_at?->format('H:i') }} <span class="opacity-25 mx-1">|</span> {{ $prescription->created_at?->diffForHumans() }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-800 text-slate mb-1">{{ $prescription->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted fw-bold font-monospace d-flex align-items-center gap-2">
                            <span class="badge bg-light text-slate border">{{ $prescription->encounter->patient->no_rkm_medis }}</span>
                            <span class="opacity-75">{{ $prescription->encounter->department?->nama_depart }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-slate small mb-1">{{ $prescription->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;">Verified Practitioner</div>
                    </td>
                    <td class="py-3">
                        <div class="prescription-items-container p-2 rounded-3 bg-light bg-opacity-50 border">
                            @foreach($prescription->details as $detail)
                                <div class="d-flex justify-content-between align-items-center gap-2 mb-1 last-mb-0">
                                    <span class="small fw-700 text-slate text-truncate" style="max-width: 200px;">{{ $detail->nama_obat }}</span>
                                    <span class="badge bg-white text-primary border border-primary border-opacity-10 font-monospace" style="font-size: 0.65rem;">x{{ rtrim(rtrim(number_format($detail->jumlah, 1, ',', '.'), '0'), ',') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-end py-3">
                        <div class="fw-800 text-slate">Rp {{ number_format($total, 0, ',', '.') }}</div>
                        <div class="small text-muted fw-bold text-uppercase" style="font-size: 0.6rem;">Total Billing</div>
                    </td>
                    <td class="text-center py-3">
                        @php($cfg = match($prescription->status) {
                            'baru' => ['bg' => 'bg-amber', 'text' => 'PENDING'],
                            'proses' => ['bg' => 'bg-blue', 'text' => 'PREPARING'],
                            'selesai' => ['bg' => 'bg-success', 'text' => 'DISPENSED'],
                            default => ['bg' => 'bg-slate', 'text' => strtoupper($prescription->status)]
                        })
                        <div class="badge {{ $cfg['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $cfg['bg']) }} rounded-pill px-3 py-2 fw-800" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                            {{ $cfg['text'] }}
                        </div>
                    </td>
                    <td class="pe-4 text-center py-3">
                        @if($prescription->status !== 'selesai')
                            <form action="{{ route('farmasi.dispense', $prescription) }}" method="POST">
                                @csrf
                                <button class="btn btn-primary btn-sm px-4 fw-800 rounded-pill shadow-sm transition-bounce-hover">
                                    <i class="fa-solid fa-capsules me-1"></i>Dispense
                                </button>
                            </form>
                        @else
                            <div class="d-inline-flex flex-column align-items-center">
                                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center mb-1" style="width: 28px; height: 28px;">
                                    <i class="fa-solid fa-check fs-6"></i>
                                </div>
                                <div class="small text-muted fw-800" style="font-size: 0.6rem;">{{ $prescription->dispensed_at?->format('H:i') }}</div>
                            </div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-receipt fs-1 text-muted opacity-25"></i>
                            </div>
                            <h6 class="fw-800 text-slate">Antrean Kosong</h6>
                            <p class="text-muted small">Tidak ada resep tertunda yang perlu diproses.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($prescriptions->hasPages())
        <div class="p-4 border-top bg-white">
            {{ $prescriptions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .prescription-items-container {
        max-height: 100px;
        overflow-y: auto;
        scrollbar-width: none;
    }
    .prescription-items-container::-webkit-scrollbar { display: none; }
    .last-mb-0:last-child { margin-bottom: 0 !important; }
    .kpi-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bg-teal-soft { background: #F0FDFA; }
    .text-slate { color: #1E293B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.05); }
    .transition-hover:hover { background-color: #F8FAFC !important; }
</style>
@endsection

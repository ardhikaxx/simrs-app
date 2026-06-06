@extends('layouts.app')

@section('title', 'Laboratorium')
@section('page-title', 'Laboratorium Patologi Klinik')
@section('page-subtitle', 'Manajemen antrean spesimen, validasi hasil, dan monitoring order layanan')

@section('content')
<div class="row g-4 mb-5">
    <!-- Stats Row -->
    <div class="col-md-3">
        <div class="simrs-card mb-0 border-0 shadow-sm overflow-hidden bg-white h-100">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-list-check fs-4"></i>
                </div>
                <div>
                    <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Total Order</div>
                    <h3 class="fw-800 mb-0 text-dark">{{ $orders->total() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="simrs-card mb-0 border-0 shadow-sm overflow-hidden bg-white h-100">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-danger bg-opacity-10 text-danger rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-bolt-lightning fs-4"></i>
                </div>
                <div>
                    <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Prioritas CITO</div>
                    <h3 class="fw-800 mb-0 text-dark">{{ $orders->where('prioritas', 'cito')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="simrs-card mb-0 border-0 shadow-sm overflow-hidden bg-white h-100">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-warning bg-opacity-10 text-warning rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-vial-circle-check fs-4"></i>
                </div>
                <div>
                    <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Sedang Proses</div>
                    <h3 class="fw-800 mb-0 text-dark">{{ $orders->where('status', 'proses')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="simrs-card mb-0 border-0 shadow-sm overflow-hidden bg-white h-100">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-success bg-opacity-10 text-success rounded-4 d-flex align-items-center justify-content-center shadow-sm" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-check-double fs-4"></i>
                </div>
                <div>
                    <div class="small text-muted fw-bold text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Tervalidasi</div>
                    <h3 class="fw-800 mb-0 text-dark">{{ $orders->where('status', 'selesai')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="simrs-card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
    <div class="card-header bg-white border-bottom p-4">
        <div class="row align-items-center g-3">
            <div class="col-md-4">
                <div class="simrs-card-title text-dark">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-microscope fs-6"></i>
                    </div>
                    <div>
                        <h5 class="fw-800 mb-0">Antrean Pemeriksaan</h5>
                        <p class="small text-muted mb-0 fw-medium">Monitoring real-time spesimen laboratorium</p>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <form class="d-flex gap-2 justify-content-md-end" method="GET">
                    <div class="input-group shadow-none" style="max-width: 400px;">
                        <span class="input-group-text bg-light border-0 text-muted ps-3"><i class="fa-solid fa-magnifying-glass small"></i></span>
                        <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-light border-0 shadow-none ps-2" placeholder="Cari No. Order, nama pasien...">
                    </div>
                    <select name="prioritas" class="form-select bg-light border-0 shadow-none fw-bold text-dark" style="max-width: 150px;">
                        <option value="">Prioritas</option>
                        <option value="rutin" @selected(request('prioritas') === 'rutin')>Rutin</option>
                        <option value="cito" @selected(request('prioritas') === 'cito')>CITO</option>
                    </select>
                    <button class="btn btn-primary px-4 fw-bold shadow-sm rounded-3">
                        <i class="fa-solid fa-filter me-2 small"></i>FILTER
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="bg-light text-muted small fw-bold text-uppercase tracking-wider">
                    <th class="ps-4 border-0 py-3">No. Order / Waktu</th>
                    <th class="border-0 py-3">Informasi Pasien</th>
                    <th class="border-0 py-3">Pemeriksaan</th>
                    <th class="border-0 py-3 text-center">Prioritas</th>
                    <th class="border-0 py-3">Pengirim</th>
                    <th class="border-0 py-3 text-center">Status Alur</th>
                    <th class="pe-4 border-0 py-3 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="text-mono fw-800 text-primary mb-1">{{ $order->no_order }}</div>
                        <div class="small text-muted d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock opacity-50"></i>
                            {{ $order->ordered_at?->format('H:i') }} <span class="opacity-25 mx-1">|</span> {{ $order->ordered_at?->format('d/m/Y') }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-800 text-dark mb-1">{{ $order->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace d-flex align-items-center gap-2">
                            <span class="badge bg-light text-dark fw-bold border border-light-subtle">{{ $order->encounter->patient->no_rkm_medis }}</span>
                            <span>{{ $order->encounter->department?->nama_depart }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-dark mb-1">{{ $order->jenis_pemeriksaan }}</div>
                        <div class="badge bg-primary bg-opacity-10 text-primary small fw-bold" style="font-size: 0.65rem;">
                            <i class="fa-solid fa-vial me-1"></i>{{ $order->results->count() }} PARAMETER
                        </div>
                    </td>
                    <td class="text-center py-3">
                        @if($order->prioritas === 'cito')
                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm" style="font-size: 0.65rem; letter-spacing: 1px;">
                                <i class="fa-solid fa-bolt-lightning me-1"></i>CITO
                            </span>
                        @else
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 px-3 py-2 rounded-pill" style="font-size: 0.65rem;">
                                RUTIN
                            </span>
                        @endif
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-dark small">{{ $order->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                        <div class="small text-muted opacity-75" style="font-size: 0.7rem;">DPJP / Pengirim</div>
                    </td>
                    <td class="text-center py-3">
                        @php
                            $statusConfig = [
                                'order' => ['bg' => 'bg-secondary', 'icon' => 'fa-file-invoice'],
                                'sampel' => ['bg' => 'bg-warning', 'icon' => 'fa-vial'],
                                'proses' => ['bg' => 'bg-info', 'icon' => 'fa-spinner fa-spin'],
                                'selesai' => ['bg' => 'bg-success', 'icon' => 'fa-check-double'],
                                'batal' => ['bg' => 'bg-danger', 'icon' => 'fa-xmark'],
                            ];
                            $cfg = $statusConfig[$order->status] ?? ['bg' => 'bg-dark', 'icon' => 'fa-question'];
                        @endphp
                        <div class="d-inline-flex align-items-center gap-2 px-3 py-2 rounded-3 {{ $cfg['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $cfg['bg']) }} border border-{{ str_replace('bg-', '', $cfg['bg']) }} border-opacity-25">
                            <i class="fa-solid {{ $cfg['icon'] }} small"></i>
                            <span class="fw-800 small text-uppercase" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ $order->status }}</span>
                        </div>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('lab.hasil.edit', $order) }}" class="btn btn-sm btn-white border shadow-sm px-3 py-2 rounded-3 fw-bold text-primary transition-hover">
                            <i class="fa-solid fa-file-signature me-1"></i>Input Hasil
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-vials fs-1 text-muted opacity-25"></i>
                            </div>
                            <h6 class="fw-800 text-dark">Antrean Kosong</h6>
                            <p class="text-muted small">Tidak ada permintaan pemeriksaan laboratorium yang ditemukan.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
        <div class="card-footer bg-white border-0 p-4">
            <div class="d-flex justify-content-between align-items-center">
                <div class="small text-muted fw-bold">Menampilkan {{ $orders->firstItem() }} - {{ $orders->lastItem() }} dari {{ $orders->total() }} data</div>
                <div>
                    {{ $orders->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    @endif
</div>

<style>
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { background-color: var(--simrs-gray-50) !important; transform: scale(1.002); }
    .text-mono { font-family: 'JetBrains Mono', monospace; }
</style>
@endsection

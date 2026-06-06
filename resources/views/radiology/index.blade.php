@extends('layouts.app')

@section('title', 'Radiologi & Imaging')
@section('page-title', 'Instalasi Radiologi')
@section('page-subtitle', 'Manajemen order imaging, monitoring status pengerjaan, dan validasi ekspertise')

@section('content')
<div class="row g-4 mb-5">
    <!-- Stats Row -->
    <div class="col-md-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-teal-soft text-primary">
                    <i class="fa-solid fa-list-check"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Total Order</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ $orders->total() }}</h3>
                </div>
            </div>
            <i class="fa-solid fa-x-ray position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-rose-soft text-danger">
                    <i class="fa-solid fa-bolt-lightning"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Prioritas CITO</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ $orders->where('prioritas', 'cito')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-blue-soft text-info">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Sedang Proses</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ $orders->where('status', 'pengerjaan')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-teal-soft text-primary">
                    <i class="fa-solid fa-file-circle-check"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Tervalidasi</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ $orders->where('status', 'selesai')->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card-premium border-0 bg-white overflow-hidden">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-radiation fs-5"></i>
            </div>
            <div>
                <h5 class="fw-800 text-slate mb-0">Antrean Pemeriksaan Imaging</h5>
                <p class="small text-muted mb-0 fw-medium">Real-time monitoring spesimen radiologi</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2" method="GET">
                <div class="header-search bg-light border-0" style="max-width: 300px;">
                    <i class="fa-solid fa-magnifying-glass opacity-40"></i>
                    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari Pasien / No. Order...">
                </div>
                <select name="prioritas" class="form-select bg-light border-0 fw-bold small" style="width: 130px;">
                    <option value="">Prioritas</option>
                    <option value="rutin" @selected(request('prioritas') === 'rutin')>Rutin</option>
                    <option value="cito" @selected(request('prioritas') === 'cito')>CITO</option>
                </select>
                <button class="btn btn-primary px-3 fw-800 rounded-3">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                    <th class="ps-4 border-0 py-3">No. Order / Waktu</th>
                    <th class="border-0 py-3">Informasi Pasien</th>
                    <th class="border-0 py-3">Jenis Pemeriksaan</th>
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
                        <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock opacity-50"></i>
                            {{ $order->ordered_at?->format('H:i') }} <span class="opacity-25 mx-1">|</span> {{ $order->ordered_at?->format('d/m/Y') }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-800 text-slate mb-1">{{ $order->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted fw-bold font-monospace d-flex align-items-center gap-2">
                            <span class="badge bg-light text-slate border">{{ $order->encounter->patient->no_rkm_medis }}</span>
                            <span class="opacity-75">{{ $order->encounter->department?->nama_depart }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-slate mb-1">{{ $order->jenis_pemeriksaan }}</div>
                        @if($order->result)
                            <div class="badge bg-success bg-opacity-10 text-success small fw-bold" style="font-size: 0.65rem;">
                                <i class="fa-solid fa-circle-check me-1"></i>HASIL TERSEDIA
                            </div>
                        @else
                            <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Menunggu Ekspertise</div>
                        @endif
                    </td>
                    <td class="text-center py-3">
                        @if($order->prioritas === 'cito')
                            <span class="badge bg-danger px-3 py-2 rounded-pill shadow-sm fw-800 animate-pulse" style="font-size: 0.6rem; letter-spacing: 1px;">
                                <i class="fa-solid fa-bolt-lightning me-1"></i>CITO
                            </span>
                        @else
                            <span class="badge bg-light text-slate border px-3 py-2 rounded-pill fw-800" style="font-size: 0.6rem;">
                                RUTIN
                            </span>
                        @endif
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-slate small mb-1">{{ $order->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;">Verified Practitioner</div>
                    </td>
                    <td class="text-center py-3">
                        @php($cfg = match($order->status) {
                            'order' => ['bg' => 'bg-slate', 'text' => 'PENDING'],
                            'pengerjaan' => ['bg' => 'bg-blue', 'text' => 'SCANNING'],
                            'selesai' => ['bg' => 'bg-success', 'text' => 'FINISHED'],
                            'batal' => ['bg' => 'bg-danger', 'text' => 'CANCELLED'],
                            default => ['bg' => 'bg-slate', 'text' => strtoupper($order->status)]
                        })
                        <div class="badge {{ $cfg['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $cfg['bg']) }} rounded-pill px-3 py-2 fw-800" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                            {{ $cfg['text'] }}
                        </div>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('rad.hasil.edit', $order) }}" class="btn btn-primary btn-sm px-4 fw-800 rounded-pill shadow-sm transition-bounce-hover">
                            <i class="fa-solid fa-file-signature me-1"></i>Ekspertise
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-x-ray fs-1 text-muted opacity-25"></i>
                            </div>
                            <h6 class="fw-800 text-slate">Antrean Kosong</h6>
                            <p class="text-muted small">Tidak ada permintaan pemeriksaan radiologi saat ini.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
        <div class="p-4 border-top bg-white">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .kpi-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bg-teal-soft { background: #F0FDFA; }
    .bg-rose-soft { background: #FFF1F2; }
    .bg-blue-soft { background: #EFF6FF; }
    .text-slate { color: #1E293B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.05); }
    .transition-hover:hover { background-color: #F8FAFC !important; }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .5; } }
</style>
@endsection

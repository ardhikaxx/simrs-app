@extends('layouts.app')

@section('title', 'Radiologi & Imaging')
@section('page-title', 'Instalasi Radiologi')
@section('page-subtitle', 'Manajemen order imaging, monitoring status pengerjaan, dan validasi ekspertise')

@section('content')
<div class="row g-4 mb-5">
    <!-- Stats Row -->
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Total Order</div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-list-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $orders->total() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Prioritas CITO</div>
                    <div class="bg-danger bg-opacity-10 text-danger rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-bolt-lightning fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-danger mb-0">{{ $orders->where('prioritas', 'cito')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Sedang Proses</div>
                    <div class="bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-spinner fa-spin fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-dark mb-0">{{ $orders->where('status', 'pengerjaan')->count() }}</h3>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Tervalidasi</div>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-file-circle-check fs-5"></i>
                    </div>
                </div>
                <h3 class="fw-bold text-success mb-0">{{ $orders->where('status', 'selesai')->count() }}</h3>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-radiation fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold text-dark mb-0">Antrean Pemeriksaan Imaging</h5>
                <p class="small text-muted mb-0 fw-medium">Real-time monitoring spesimen radiologi</p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <form class="d-flex gap-2 flex-wrap" method="GET">
                <div class="input-group input-group-sm bg-light rounded-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0" placeholder="Cari Pasien / No. Order..." style="min-width: 200px;">
                </div>
                <select name="prioritas" class="form-select form-select-sm bg-light border-0 fw-medium shadow-none focus-ring-0 rounded-3 text-muted" style="width: 130px;">
                    <option value="">Semua Prioritas</option>
                    <option value="rutin" @selected(request('prioritas') === 'rutin')>Rutin</option>
                    <option value="cito" @selected(request('prioritas') === 'cito')>CITO</option>
                </select>
                <button class="btn btn-sm btn-primary px-3 fw-medium rounded-3 shadow-sm">
                    <i class="fa-solid fa-filter"></i>
                </button>
            </form>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Order / Waktu</th>
                    <th class="py-3">Informasi Pasien</th>
                    <th class="py-3">Jenis Pemeriksaan</th>
                    <th class="py-3 text-center">Prioritas</th>
                    <th class="py-3">Pengirim</th>
                    <th class="py-3 text-center">Status Alur</th>
                    <th class="pe-4 py-3 text-end rounded-end">Aksi</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($orders as $order)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-primary mb-1 h6">{{ $order->no_order }}</div>
                        <div class="small text-muted fw-medium d-flex align-items-center gap-1">
                            <i class="fa-regular fa-clock opacity-50"></i>
                            {{ $order->ordered_at?->format('H:i') }} <span class="opacity-25 mx-1">|</span> {{ $order->ordered_at?->format('d/m/Y') }}
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $order->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted fw-medium font-monospace d-flex align-items-center gap-2">
                            <span class="badge bg-light text-secondary border px-2 py-0" style="font-size: 0.65rem;">{{ $order->encounter->patient->no_rkm_medis }}</span>
                            <span class="opacity-75">{{ $order->encounter->department?->nama_depart }}</span>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="fw-medium text-dark mb-1">{{ $order->jenis_pemeriksaan }}</div>
                        @if($order->result)
                            <div class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 fw-semibold" style="font-size: 0.65rem;">
                                <i class="fa-solid fa-circle-check me-1"></i>HASIL TERSEDIA
                            </div>
                        @else
                            <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Menunggu Ekspertise</div>
                        @endif
                    </td>
                    <td class="text-center py-3">
                        @if($order->prioritas === 'cito')
                            <span class="badge bg-danger px-3 py-1 rounded-pill shadow-sm fw-bold animate-pulse" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-bolt-lightning me-1"></i>CITO
                            </span>
                        @else
                            <span class="badge bg-light text-secondary border px-3 py-1 rounded-pill fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                RUTIN
                            </span>
                        @endif
                    </td>
                    <td class="py-3">
                        <div class="fw-medium text-dark small mb-1">{{ $order->doctor?->display_name ?? 'Dokter Jaga' }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;">Verified Practitioner</div>
                    </td>
                    <td class="text-center py-3">
                        @php($cfg = match($order->status) {
                            'order' => ['bg' => 'secondary', 'text' => 'PENDING'],
                            'pengerjaan' => ['bg' => 'info', 'text' => 'SCANNING'],
                            'selesai' => ['bg' => 'success', 'text' => 'FINISHED'],
                            'batal' => ['bg' => 'danger', 'text' => 'CANCELLED'],
                            default => ['bg' => 'dark', 'text' => strtoupper($order->status)]
                        })
                        <div class="badge bg-{{ $cfg['bg'] }} bg-opacity-10 text-{{ $cfg['bg'] }} rounded-pill px-3 py-1 fw-semibold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ $cfg['text'] }}
                        </div>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('rad.hasil.edit', $order) }}" class="btn btn-light border btn-sm px-3 fw-medium rounded-pill shadow-sm text-primary transition-hover">
                            <i class="fa-solid fa-file-signature me-1"></i>Ekspertise
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-x-ray fs-2 text-muted opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-dark">Antrean Kosong</h6>
                            <p class="text-muted small">Tidak ada permintaan pemeriksaan radiologi saat ini.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($orders->hasPages())
        <div class="p-4 border-top border-light bg-white rounded-bottom-4">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
    .animate-pulse { animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite; }
    @keyframes pulse { 0%, 100% { opacity: 1; } 50% { opacity: .7; } }
</style>
@endsection

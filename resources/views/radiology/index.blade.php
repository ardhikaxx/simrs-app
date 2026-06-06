@extends('layouts.app')

@section('title', 'Radiologi & Imaging')
@section('page-title', 'Antrian Instalasi Radiologi')
@section('page-subtitle', 'Monitoring order pemeriksaan imaging, status pengerjaan, dan ekspertise')

@section('content')
<div class="page-header-bar mb-3">
    <form class="d-flex gap-2 flex-grow-1" method="GET">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari No. Order, nama pasien, atau jenis pemeriksaan...">
        </div>
        <select name="prioritas" class="form-select shadow-sm" style="max-width: 150px;">
            <option value="">Semua Prioritas</option>
            <option value="rutin" @selected(request('prioritas') === 'rutin')>Rutin</option>
            <option value="cito" @selected(request('prioritas') === 'cito')>CITO</option>
        </select>
        <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
    </form>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-x-ray"></i>
            <span>Daftar Order Pemeriksaan Imaging</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. Order / Waktu</th>
                    <th>Identitas Pasien</th>
                    <th>Jenis Pemeriksaan</th>
                    <th class="text-center">Prioritas</th>
                    <th>Dokter Pengirim</th>
                    <th class="text-center">Status</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-simrs-primary small">{{ $order->no_order }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $order->ordered_at?->format('d/m/Y H:i') }}</div>
                    </td>
                    <td>
                        <div class="fw-bold text-simrs-gray-900">{{ $order->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.75rem;">RM: {{ $order->encounter->patient->no_rkm_medis }} | {{ $order->encounter->department?->nama_depart }}</div>
                    </td>
                    <td>
                        <div class="fw-600 text-simrs-gray-800">{{ $order->jenis_pemeriksaan }}</div>
                        @if($order->result)
                            <div class="small text-success fw-700" style="font-size: 0.65rem;">
                                <i class="fa-solid fa-circle-check me-1"></i>HASIL TERSEDIA
                            </div>
                        @else
                            <div class="small text-muted" style="font-size: 0.65rem;">Belum ada ekspertise</div>
                        @endif
                    </td>
                    <td class="text-center">
                        <span class="badge {{ $order->prioritas === 'cito' ? 'bg-danger' : 'bg-primary-subtle text-primary border border-primary-subtle' }} px-3 py-1" style="font-size: 0.7rem; font-weight: 800; letter-spacing: 0.5px;">
                            {{ strtoupper($order->prioritas) }}
                        </span>
                    </td>
                    <td>
                        <div class="small fw-600 text-simrs-secondary">
                            <i class="fa-solid fa-user-doctor me-1 opacity-50"></i>{{ $order->doctor?->display_name ?? '-' }}
                        </div>
                    </td>
                    <td class="text-center">
                        @php
                            $statusMap = [
                                'order' => ['label' => 'ORDER', 'class' => 'status-baru'],
                                'pengerjaan' => ['label' => 'PROSES', 'class' => 'status-sedang-jalan'],
                                'selesai' => ['label' => 'SELESAI', 'class' => 'status-aman'],
                                'batal' => ['label' => 'BATAL', 'class' => 'status-kritis'],
                            ];
                            $s = $statusMap[$order->status] ?? ['label' => strtoupper($order->status), 'class' => 'status-baru'];
                        @endphp
                        <span class="badge-status {{ $s['class'] }} shadow-none py-1 px-3" style="font-size: 0.7rem;">
                            {{ $s['label'] }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <a href="{{ route('rad.hasil.edit', $order) }}" class="btn btn-sm btn-simrs-primary shadow-sm px-3">
                            <i class="fa-solid fa-file-signature me-1"></i>Ekspertise
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fa-solid fa-x-ray fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Tidak ada order radiologi dalam daftar antrean saat ini.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $orders->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

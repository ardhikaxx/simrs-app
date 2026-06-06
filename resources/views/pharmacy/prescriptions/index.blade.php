@extends('layouts.app')

@section('title', 'Antrian Resep')
@section('page-title', 'Antrian Resep Elektronik')
@section('page-subtitle', 'Verifikasi, penyiapan, dan penyerahan obat kepada pasien')

@section('content')
<div class="page-header-bar mb-3">
    <div class="d-flex align-items-center gap-3">
        <div class="kpi-card py-2 px-3 shadow-none border bg-white d-flex align-items-center gap-3">
            <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 32px; height: 32px; font-size: 0.8rem;">
                <i class="fa-solid fa-receipt"></i>
            </div>
            <div>
                <div class="small text-muted fw-700 text-uppercase" style="font-size: 0.6rem;">Total Resep</div>
                <div class="fw-800 text-simrs-gray-900">{{ $prescriptions->total() }}</div>
            </div>
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('farmasi.inventory.index') }}" class="btn btn-simrs-outline shadow-sm">
            <i class="fa-solid fa-boxes-stacked me-2"></i>Manajemen Stok
        </a>
    </div>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-pills"></i>
            <span>Daftar Tunggu Dispensing Obat</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. Resep / Waktu</th>
                    <th>Informasi Pasien</th>
                    <th>Dokter DPJP</th>
                    <th>Rincian Item Obat</th>
                    <th class="text-end">Estimasi Biaya</th>
                    <th class="text-center">Status</th>
                    <th class="pe-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($prescriptions as $prescription)
                @php($total = $prescription->details->sum('subtotal'))
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-simrs-primary">{{ $prescription->no_resep }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $prescription->created_at?->format('d/m H:i') }} ({{ $prescription->created_at?->diffForHumans() }})</div>
                    </td>
                    <td>
                        <div class="fw-bold text-simrs-gray-900">{{ $prescription->encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.75rem;">RM: {{ $prescription->encounter->patient->no_rkm_medis }} | {{ $prescription->encounter->department?->nama_depart }}</div>
                    </td>
                    <td>
                        <div class="small fw-600 text-simrs-secondary">
                            <i class="fa-solid fa-user-doctor me-1 opacity-50"></i>{{ $prescription->doctor?->display_name ?? '-' }}
                        </div>
                    </td>
                    <td>
                        <div class="prescription-items-list">
                            @foreach($prescription->details as $detail)
                                <div class="small lh-sm mb-1 d-flex justify-content-between gap-3">
                                    <span class="fw-600 text-simrs-gray-800"><i class="fa-solid fa-caret-right me-1 text-primary small"></i>{{ $detail->nama_obat }}</span>
                                    <span class="text-mono text-muted bg-light px-1 rounded" style="font-size: 0.65rem;">x{{ rtrim(rtrim(number_format($detail->jumlah, 1, ',', '.'), '0'), ',') }}</span>
                                </div>
                            @endforeach
                        </div>
                    </td>
                    <td class="text-end fw-bold text-simrs-gray-900">
                        Rp {{ number_format($total, 0, ',', '.') }}
                    </td>
                    <td class="text-center">
                        <span class="badge-status status-{{ $prescription->status }} shadow-none py-1 px-3" style="font-size: 0.7rem;">
                            {{ strtoupper($prescription->status) }}
                        </span>
                    </td>
                    <td class="pe-4 text-center">
                        @if($prescription->status !== 'selesai')
                            <form action="{{ route('farmasi.dispense', $prescription) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-simrs-primary shadow-sm px-3 py-1">
                                    <i class="fa-solid fa-box-check me-1"></i>Dispense
                                </button>
                            </form>
                        @else
                            <div class="small text-success fw-800">
                                <i class="fa-solid fa-circle-check me-1"></i>SELESAI
                            </div>
                            <div class="small text-muted" style="font-size: 0.65rem;">{{ $prescription->dispensed_at?->format('H:i') }}</div>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fa-solid fa-prescription fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Tidak ada antrean resep elektronik saat ini.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($prescriptions->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $prescriptions->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .prescription-items-list {
        max-height: 80px;
        overflow-y: auto;
        padding-right: 5px;
    }
    .prescription-items-list::-webkit-scrollbar { width: 3px; }
    .prescription-items-list::-webkit-scrollbar-thumb { background: #E2E8F0; border-radius: 10px; }
</style>
@endsection

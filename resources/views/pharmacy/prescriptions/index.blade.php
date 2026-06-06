@extends('layouts.app')

@section('title', 'Farmasi')
@section('page-title', 'Antrian Resep')
@section('page-subtitle', 'Verifikasi dan dispensing resep elektronik')

@section('content')
<div class="page-header-bar">
    <div class="section-label">Total resep: {{ $prescriptions->total() }}</div>
    <a href="{{ route('farmasi.inventory.index') }}" class="btn btn-simrs-outline"><i class="fa-solid fa-boxes-stacked me-1"></i>Inventory</a>
</div>
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>No Resep</th><th>Pasien</th><th>Dokter</th><th>Obat</th><th>Total</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($prescriptions as $prescription)
                @php($total = $prescription->details->sum('subtotal'))
                <tr>
                    <td class="text-mono">{{ $prescription->no_resep }}<div class="small text-muted">{{ $prescription->created_at?->format('d/m/Y H:i') }}</div></td>
                    <td><strong>{{ $prescription->encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $prescription->encounter->department?->nama_depart }}</div></td>
                    <td>{{ $prescription->doctor?->display_name ?? '-' }}</td>
                    <td>
                        @foreach($prescription->details as $detail)
                            <div>{{ $detail->nama_obat }} <span class="text-muted">x{{ rtrim(rtrim(number_format($detail->jumlah, 2, ',', '.'), '0'), ',') }}</span></div>
                        @endforeach
                    </td>
                    <td>Rp {{ number_format($total, 0, ',', '.') }}</td>
                    <td><span class="badge-status status-{{ $prescription->status }}">{{ ucfirst($prescription->status) }}</span></td>
                    <td>
                        @if($prescription->status !== 'selesai')
                            <form action="{{ route('farmasi.dispense', $prescription) }}" method="POST">
                                @csrf
                                <button class="btn btn-sm btn-simrs-primary"><i class="fa-solid fa-check me-1"></i>Dispense</button>
                            </form>
                        @else
                            <span class="text-muted small">Selesai {{ $prescription->dispensed_at?->format('d/m H:i') }}</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada resep.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $prescriptions->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

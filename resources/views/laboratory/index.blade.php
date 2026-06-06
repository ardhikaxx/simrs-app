@extends('layouts.app')

@section('title', 'Laboratorium')
@section('page-title', 'Antrian Laboratorium')
@section('page-subtitle', 'Order pemeriksaan, status sampel, dan hasil kritis')

@section('content')
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>No Order</th><th>Pasien</th><th>Pemeriksaan</th><th>Prioritas</th><th>Dokter</th><th>Status</th><th>Hasil</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($orders as $order)
                <tr>
                    <td class="text-mono">{{ $order->no_order }}<div class="small text-muted">{{ $order->ordered_at?->format('d/m/Y H:i') }}</div></td>
                    <td><strong>{{ $order->encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $order->encounter->department?->nama_depart }}</div></td>
                    <td>{{ $order->jenis_pemeriksaan }}</td>
                    <td><span class="badge-status {{ $order->prioritas === 'cito' ? 'status-kritis' : 'status-baru' }}">{{ strtoupper($order->prioritas) }}</span></td>
                    <td>{{ $order->doctor?->display_name ?? '-' }}</td>
                    <td><span class="badge-status status-{{ $order->status }}">{{ ucfirst($order->status) }}</span></td>
                    <td>{{ $order->results->count() }} parameter</td>
                    <td><a href="{{ route('lab.hasil.edit', $order) }}" class="btn btn-sm btn-simrs-primary"><i class="fa-solid fa-vial-circle-check me-1"></i>Input</a></td>
                </tr>
            @empty
                <tr><td colspan="8" class="text-center text-muted py-4">Tidak ada order laboratorium.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $orders->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

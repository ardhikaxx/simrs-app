@extends('layouts.app')

@section('title', 'Audit Trail')
@section('page-title', 'Log Aktivitas Sistem')
@section('page-subtitle', 'Jejak audit seluruh operasi manipulasi data dan akses sistem')

@section('content')
<div class="simrs-card">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-shield-halved"></i>
            <span>System Audit Trail</span>
        </div>
        <div class="page-header-actions">
            <button class="btn btn-sm btn-simrs-outline"><i class="fa-solid fa-download me-1"></i>Ekspor Log</button>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">Waktu Kejadian</th>
                    <th>Aktor / User</th>
                    <th>Aksi & Metode</th>
                    <th>Objek / URL</th>
                    <th>IP Address</th>
                    <th class="pe-4">Keterangan Aktivitas</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td class="ps-4">
                        <div class="fw-bold">{{ $log->created_at?->format('d/m/Y') }}</div>
                        <div class="text-mono small text-muted">{{ $log->created_at?->format('H:i:s') }}</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <i class="fa-solid fa-circle-user text-muted opacity-50"></i>
                            <span class="fw-600">{{ $log->user?->display_name ?: 'SYSTEM' }}</span>
                        </div>
                    </td>
                    <td>
                        <div class="mb-1">
                            @php
                                $badgeClass = match($log->action) {
                                    'login' => 'status-aman',
                                    'logout' => 'status-baru',
                                    'create', 'store' => 'status-terdaftar',
                                    'update' => 'status-peringatan',
                                    'delete', 'destroy' => 'status-kritis',
                                    'mutasi_data' => 'status-peringatan',
                                    default => 'status-baru'
                                };
                            @endphp
                            <span class="badge-status {{ $badgeClass }} small" style="font-weight: 700;">
                                {{ strtoupper(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </div>
                        <span class="text-mono badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-1" style="font-size: 0.65rem;">
                            {{ $log->method }}
                        </span>
                    </td>
                    <td>
                        <div class="text-truncate text-muted small" style="max-width: 250px;" title="{{ $log->url }}">
                            <i class="fa-solid fa-link me-1 opacity-50"></i>{{ $log->url }}
                        </div>
                    </td>
                    <td>
                        <span class="text-mono small bg-light px-2 py-1 rounded border">{{ $log->ip_address }}</span>
                    </td>
                    <td class="pe-4">
                        <div class="small lh-sm text-simrs-gray-700">{{ $log->description }}</div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center py-5">
                        <i class="fa-solid fa-fingerprint fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Data log audit belum tersedia di database.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
@endsection

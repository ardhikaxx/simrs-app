@extends('layouts.app')

@section('title', 'System Audit Trail')
@section('page-title', 'Log Aktivitas Sistem (Audit Trail)')
@section('page-subtitle', 'Jejak rekam aktivitas kritis pengguna dan mutasi data SIMRS')

@section('content')
<!-- Filter Analitik -->
<div class="row g-4 mb-4">
    <div class="col-xl-12">
        <div class="simrs-card bg-white border-0 shadow-sm mb-0">
            <div class="simrs-card-body p-3">
                <form class="row g-3 align-items-end" method="GET">
                    <div class="col-md-3">
                        <label class="form-label-custom small">Rentang Tanggal</label>
                        <div class="input-group shadow-none">
                            <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-calendar-day text-muted small"></i></span>
                            <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}" class="form-control border-start-0 bg-light">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom small">Filter Aksi</label>
                        <select name="action" class="form-select bg-light shadow-none">
                            <option value="">Semua Aktivitas</option>
                            <option value="login">Otentikasi (Login/Logout)</option>
                            <option value="create">Penambahan Data (Create)</option>
                            <option value="update">Perubahan Data (Update)</option>
                            <option value="delete">Penghapusan (Delete)</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label-custom small">Cari Keterangan / IP / URL</label>
                        <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-light shadow-none" placeholder="Masukkan kata kunci...">
                    </div>
                    <div class="col-md-2 d-grid">
                        <button class="btn btn-dark fw-800 shadow-sm border-0"><i class="fa-solid fa-filter me-2"></i>Filter Log</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="simrs-card border-0 shadow-sm">
    <div class="simrs-card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <div class="simrs-card-title text-simrs-primary mb-0">
            <i class="fa-solid fa-list-check"></i>
            <span>Daftar Rekaman Audit (Waktu Nyata)</span>
        </div>
        <button class="btn btn-sm btn-simrs-outline shadow-sm px-3 fw-bold border-0">
            <i class="fa-solid fa-file-export me-1"></i>Ekspor ke CSV
        </button>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr class="small text-muted text-uppercase fw-bold">
                    <th class="ps-4">Waktu Kejadian</th>
                    <th>Aktor Pengguna</th>
                    <th>Klasifikasi Aksi</th>
                    <th>Detail Aktivitas</th>
                    <th class="pe-4">Informasi Sistem</th>
                </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td class="ps-4 py-3">
                        <div class="fw-800 text-simrs-gray-900">{{ $log->created_at?->format('d/m/Y') }}</div>
                        <div class="text-mono small text-muted"><i class="fa-regular fa-clock me-1 opacity-50"></i>{{ $log->created_at?->format('H:i:s') }} WIB</div>
                    </td>
                    <td>
                        <div class="d-flex align-items-center gap-2">
                            <div class="brand-icon shadow-none bg-light text-muted" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div>
                                <span class="fw-700 text-simrs-gray-800">{{ $log->user?->display_name ?: 'SYSTEM_AUTO' }}</span>
                                @if($log->user)
                                    <div class="small text-muted text-mono" style="font-size: 0.65rem;">UID: {{ $log->user_id }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="mb-1">
                            @php
                                $actionProps = match($log->action) {
                                    'login' => ['bg' => 'bg-info-subtle', 'text' => 'text-info', 'border' => 'border-info-subtle', 'icon' => 'fa-right-to-bracket'],
                                    'logout' => ['bg' => 'bg-secondary-subtle', 'text' => 'text-secondary', 'border' => 'border-secondary-subtle', 'icon' => 'fa-right-from-bracket'],
                                    'create', 'store' => ['bg' => 'bg-success-subtle', 'text' => 'text-success', 'border' => 'border-success-subtle', 'icon' => 'fa-plus'],
                                    'update' => ['bg' => 'bg-warning-subtle', 'text' => 'text-warning', 'border' => 'border-warning-subtle', 'icon' => 'fa-pen'],
                                    'delete', 'destroy' => ['bg' => 'bg-danger-subtle', 'text' => 'text-danger', 'border' => 'border-danger-subtle', 'icon' => 'fa-trash'],
                                    'mutasi_data' => ['bg' => 'bg-primary-subtle', 'text' => 'text-primary', 'border' => 'border-primary-subtle', 'icon' => 'fa-database'],
                                    default => ['bg' => 'bg-light', 'text' => 'text-muted', 'border' => 'border-secondary-subtle', 'icon' => 'fa-bolt']
                                };
                            @endphp
                            <span class="badge {{ $actionProps['bg'] }} {{ $actionProps['text'] }} border {{ $actionProps['border'] }} px-2 py-1" style="font-size: 0.65rem; font-weight: 800; letter-spacing: 0.5px;">
                                <i class="fa-solid {{ $actionProps['icon'] }} me-1 opacity-75"></i>{{ strtoupper(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </div>
                        <span class="text-mono badge bg-light text-muted border px-2 py-0" style="font-size: 0.6rem;">
                            {{ $log->method }}
                        </span>
                    </td>
                    <td>
                        <div class="fw-600 text-simrs-gray-900 lh-sm mb-1" style="max-width: 350px;">{{ $log->description }}</div>
                        <div class="text-truncate text-muted text-mono small" style="max-width: 350px; font-size: 0.7rem;" title="{{ $log->url }}">
                            <i class="fa-solid fa-link me-1 opacity-50"></i>{{ str_replace(url('/'), '', $log->url) }}
                        </div>
                    </td>
                    <td class="pe-4">
                        <div class="small fw-600 text-muted"><i class="fa-solid fa-network-wired me-1 opacity-50"></i>IP: <span class="text-mono">{{ $log->ip_address }}</span></div>
                        <div class="small text-muted text-truncate mt-1" style="max-width: 150px; font-size: 0.65rem;" title="{{ request()->userAgent() }}">
                            <i class="fa-brands fa-chrome me-1 opacity-50"></i>Client Browser
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="brand-icon shadow-none bg-light text-muted mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem;">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h5 class="fw-800 text-simrs-gray-900 mb-1">Log Bersih</h5>
                        <div class="text-muted small">Tidak ada aktivitas sistem yang tercatat pada kriteria ini.</div>
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

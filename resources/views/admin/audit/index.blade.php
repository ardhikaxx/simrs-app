@extends('layouts.app')

@section('title', 'System Audit Trail')
@section('page-title', 'Log Aktivitas Sistem (Audit Trail)')
@section('page-subtitle', 'Jejak rekam aktivitas kritis pengguna dan mutasi data SIMRS')

@section('content')
<!-- Filter Analitik -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <form class="row g-3 align-items-center" method="GET">
            <div class="col-md-3">
                <label class="form-label text-muted fw-semibold small mb-1">Rentang Tanggal</label>
                <div class="input-group bg-light rounded-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-calendar-day text-muted small"></i></span>
                    <input type="date" name="date" value="{{ request('date', now()->format('Y-m-d')) }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0">
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label text-muted fw-semibold small mb-1">Filter Aksi</label>
                <select name="action" class="form-select bg-light border-light shadow-none focus-ring-0 fw-medium rounded-3 text-muted">
                    <option value="">Semua Aktivitas</option>
                    <option value="login">Otentikasi (Login/Logout)</option>
                    <option value="create">Penambahan Data (Create)</option>
                    <option value="update">Perubahan Data (Update)</option>
                    <option value="delete">Penghapusan (Delete)</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label text-muted fw-semibold small mb-1">Cari Keterangan / IP / URL</label>
                <div class="input-group bg-light rounded-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted small"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0" placeholder="Ketik kata kunci...">
                </div>
            </div>
            <div class="col-md-2">
                <label class="form-label d-none d-md-block">&nbsp;</label>
                <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-3 px-4 w-100 transition-hover py-2">
                    <i class="fa-solid fa-filter me-2"></i>Filter Log
                </button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-list-check fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Daftar Rekaman Audit (Waktu Nyata)</h5>
                <p class="text-muted small mb-0 fw-medium">Memantau keamanan dan integritas data sistem</p>
            </div>
        </div>
        <button class="btn btn-sm btn-light border border-light-subtle text-muted fw-bold px-3 rounded-pill shadow-sm transition-hover hover-bg-gray">
            <i class="fa-solid fa-file-export me-1"></i>Ekspor CSV
        </button>
    </div>
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">Waktu Kejadian</th>
                    <th class="py-3">Aktor Pengguna</th>
                    <th class="py-3">Klasifikasi Aksi</th>
                    <th class="py-3">Detail Aktivitas</th>
                    <th class="pe-4 py-3 text-end rounded-end">Informasi Sistem</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($logs as $log)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-dark mb-1">{{ $log->created_at?->format('d/m/Y') }}</div>
                        <div class="small text-muted fw-medium font-monospace"><i class="fa-regular fa-clock me-1 opacity-50"></i>{{ $log->created_at?->format('H:i:s') }} WIB</div>
                    </td>
                    <td class="py-3">
                        <div class="d-flex align-items-center gap-2">
                            <div class="bg-light text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0 shadow-xs" style="width: 34px; height: 34px; font-size: 0.8rem;">
                                <i class="fa-solid fa-user-shield"></i>
                            </div>
                            <div>
                                <div class="fw-semibold text-dark">{{ $log->user?->display_name ?: 'SYSTEM_AUTO' }}</div>
                                @if($log->user)
                                    <div class="small text-muted font-monospace" style="font-size: 0.65rem;">UID: {{ $log->user_id }}</div>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="mb-1">
                            @php
                                $actionProps = match($log->action) {
                                    'login' => ['bg' => 'info', 'icon' => 'fa-right-to-bracket'],
                                    'logout' => ['bg' => 'secondary', 'icon' => 'fa-right-from-bracket'],
                                    'create', 'store' => ['bg' => 'success', 'icon' => 'fa-plus'],
                                    'update' => ['bg' => 'warning', 'icon' => 'fa-pen'],
                                    'delete', 'destroy' => ['bg' => 'danger', 'icon' => 'fa-trash'],
                                    'mutasi_data' => ['bg' => 'primary', 'icon' => 'fa-database'],
                                    default => ['bg' => 'dark', 'icon' => 'fa-bolt']
                                };
                            @endphp
                            <span class="badge bg-{{ $actionProps['bg'] }} bg-opacity-10 text-{{ $actionProps['bg'] }} border border-{{ $actionProps['bg'] }} border-opacity-10 px-2 py-1 fw-bold" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                <i class="fa-solid {{ $actionProps['icon'] }} me-1 opacity-75"></i>{{ strtoupper(str_replace('_', ' ', $log->action)) }}
                            </span>
                        </div>
                        <span class="badge bg-light text-muted border border-light-subtle px-2 py-0 fw-medium font-monospace" style="font-size: 0.6rem;">
                            {{ $log->method }}
                        </span>
                    </td>
                    <td class="py-3">
                        <div class="fw-medium text-dark lh-sm mb-1" style="max-width: 350px;">{{ $log->description }}</div>
                        <div class="text-truncate text-muted font-monospace small opacity-75" style="max-width: 350px; font-size: 0.7rem;" title="{{ $log->url }}">
                            <i class="fa-solid fa-link me-1 opacity-50"></i>{{ str_replace(url('/'), '', $log->url) }}
                        </div>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <div class="small fw-bold text-dark font-monospace"><i class="fa-solid fa-network-wired me-1 opacity-50"></i>IP: {{ $log->ip_address }}</div>
                        <div class="small text-muted text-truncate mt-1" style="max-width: 150px; font-size: 0.65rem;" title="{{ request()->userAgent() }}">
                            <i class="fa-brands fa-chrome me-1 opacity-50"></i>Client Agent
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-5">
                        <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 70px; height: 70px;">
                            <i class="fa-solid fa-shield-halved fs-3 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Log Bersih</h6>
                        <p class="text-muted small mb-0 fw-medium">Tidak ada aktivitas sistem yang tercatat pada kriteria ini.</p>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($logs->hasPages())
        <div class="p-4 border-top border-light bg-white rounded-bottom-4 d-flex justify-content-center">
            {{ $logs->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
    .shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
</style>
@endsection

@extends('layouts.app')

@section('title', 'Audit Trail')
@section('page-title', 'Audit Trail')
@section('page-subtitle', 'Jejak akses dan mutasi data SIMRS')

@section('content')
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Waktu</th><th>User</th><th>Action</th><th>Method</th><th>URL</th><th>IP</th><th>Deskripsi</th></tr></thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{{ $log->created_at?->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $log->user?->display_name ?: 'System' }}</td>
                    <td><span class="badge-status {{ $log->action === 'mutasi_data' ? 'status-peringatan' : 'status-baru' }}">{{ $log->action }}</span></td>
                    <td class="text-mono">{{ $log->method }}</td>
                    <td class="text-truncate" style="max-width:280px">{{ $log->url }}</td>
                    <td class="text-mono">{{ $log->ip_address }}</td>
                    <td>{{ $log->description }}</td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada audit log.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $logs->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

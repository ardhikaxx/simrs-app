@extends('layouts.app')

@section('title', 'Laporan Morbiditas')
@section('page-title', 'Laporan Morbiditas')
@section('page-subtitle', 'Distribusi diagnosis utama berdasarkan ICD-10')

@section('content')
<form method="GET" class="simrs-card">
    <div class="simrs-card-body d-flex flex-wrap gap-2 align-items-end">
        <div><label class="form-label-custom">Dari</label><input type="date" name="from" value="{{ $from }}" class="form-control"></div>
        <div><label class="form-label-custom">Sampai</label><input type="date" name="to" value="{{ $to }}" class="form-control"></div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-filter me-1"></i>Filter</button>
        <a href="{{ route('laporan.export', 'morbiditas') }}" class="btn btn-simrs-outline"><i class="fa-solid fa-file-csv me-1"></i>Export CSV</a>
    </div>
</form>
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Peringkat</th><th>ICD-10</th><th>Diagnosis</th><th>Total Kasus</th></tr></thead>
            <tbody>
            @forelse($rows as $row)
                <tr>
                    <td>{{ $loop->iteration + (($rows->currentPage() - 1) * $rows->perPage()) }}</td>
                    <td class="text-mono fw-bold">{{ $row->icd10_primer }}</td>
                    <td>{{ $row->diagnosis_kerja }}</td>
                    <td><span class="kpi-value" style="font-size:1rem">{{ number_format($row->total) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data morbiditas.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $rows->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

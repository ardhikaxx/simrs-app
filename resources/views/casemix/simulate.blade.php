@extends('layouts.app')

@section('title', 'Simulasi INA-CBG')
@section('page-title', 'Simulasi Casemix')
@section('page-subtitle', $encounter->patient->nama_pasien . ' - ' . ($result['icd10'] ?? 'Diagnosis belum tersedia'))

@section('content')
<div class="row g-3">
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-label">Total Riil</div>
            <div class="kpi-value">Rp {{ number_format($result['total_riil'] ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-label">Tarif INA-CBG</div>
            <div class="kpi-value">Rp {{ number_format($result['tarif_ina_cbg'] ?? 0, 0, ',', '.') }}</div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="kpi-card">
            <div class="kpi-label">Utilisasi</div>
            <div class="kpi-value">{{ $result['persen'] ?? 0 }}%</div>
            <span class="badge-status status-{{ $result['status'] ?? 'draft' }}">{{ $result['status'] ?? 'no_tarif' }}</span>
        </div>
    </div>
</div>
<div class="alert-medical alert-medical-{{ ($result['status'] ?? '') === 'kritis' ? 'critical' : (($result['status'] ?? '') === 'peringatan' ? 'warning' : 'info') }} mt-3">
    <div><i class="fa-solid fa-circle-info"></i></div>
    <div><strong>{{ $result['kode_inacbg'] ?? 'Tarif belum tersedia' }}</strong><div class="small">{{ $result['pesan'] ?? $result['message'] ?? 'Lengkapi diagnosis dan invoice untuk simulasi.' }}</div></div>
</div>
<div class="simrs-card">
    <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-list"></i>Komponen Biaya</div></div>
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Kategori</th><th>Deskripsi</th><th>Subtotal</th></tr></thead>
            <tbody>
            @foreach($encounter->billingInvoice?->billingDetails ?? [] as $detail)
                <tr><td>{{ $detail->kategori }}</td><td>{{ $detail->deskripsi }}</td><td>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</td></tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

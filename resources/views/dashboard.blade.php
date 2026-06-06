@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard Operasional')
@section('page-subtitle', 'Ringkasan real-time pelayanan rumah sakit')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-md-6 col-xl-3"><div class="kpi-card d-flex gap-3 align-items-center"><div class="kpi-icon" style="background:var(--simrs-primary-pale);color:var(--simrs-primary)"><i class="fa-solid fa-user-injured"></i></div><div><div class="kpi-label">Total Pasien</div><div class="kpi-value">{{ number_format($metrics['patients']) }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="kpi-card d-flex gap-3 align-items-center"><div class="kpi-icon" style="background:var(--simrs-info-pale);color:var(--simrs-info)"><i class="fa-solid fa-calendar-day"></i></div><div><div class="kpi-label">Kunjungan Hari Ini</div><div class="kpi-value">{{ number_format($metrics['visits_today']) }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="kpi-card d-flex gap-3 align-items-center"><div class="kpi-icon" style="background:var(--simrs-warning-pale);color:var(--simrs-warning)"><i class="fa-solid fa-bed-pulse"></i></div><div><div class="kpi-label">Encounter Aktif</div><div class="kpi-value">{{ number_format($metrics['active_encounters']) }}</div></div></div></div>
    <div class="col-md-6 col-xl-3"><div class="kpi-card d-flex gap-3 align-items-center"><div class="kpi-icon" style="background:var(--simrs-success-pale);color:var(--simrs-success)"><i class="fa-solid fa-rupiah-sign"></i></div><div><div class="kpi-label">Pendapatan Hari Ini</div><div class="kpi-value">Rp {{ number_format($metrics['revenue_today'], 0, ',', '.') }}</div></div></div></div>
</div>

<div class="row g-3">
    <div class="col-xl-8">
        <div class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-chart-line"></i>Tren Kunjungan 7 Hari</div></div>
            <div class="simrs-card-body"><div style="height:260px"><canvas id="visitChart"></canvas></div></div>
        </div>
        <div class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-list-ol"></i>Antrian Aktif</div><a href="{{ route('pendaftaran.antrian') }}" class="btn btn-sm btn-simrs-outline">Lihat Semua</a></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>No Reg</th><th>Pasien</th><th>Unit</th><th>Dokter</th><th>Status</th></tr></thead>
                    <tbody>
                    @forelse($queue as $encounter)
                        <tr>
                            <td class="text-mono">{{ $encounter->no_registrasi }}</td>
                            <td><strong>{{ $encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $encounter->patient->no_rkm_medis }}</div></td>
                            <td>{{ $encounter->department->nama_depart }}</td>
                            <td>{{ $encounter->doctor?->display_name ?? '-' }}</td>
                            <td><span class="badge-status status-{{ str_replace('_','-',$encounter->status_antrian) }}">{{ str_replace('_',' ',ucfirst($encounter->status_antrian)) }}</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">Tidak ada antrian aktif.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xl-4">
        @if($criticalLabs->count())
            <div class="alert-medical alert-medical-critical">
                <div><i class="fa-solid fa-circle-radiation"></i></div>
                <div><strong>Nilai kritis lab</strong><div class="small">{{ $criticalLabs->count() }} hasil perlu tindak lanjut klinis.</div></div>
            </div>
        @endif
        <div class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-prescription-bottle-medical"></i>Resep Menunggu</div></div>
            <div class="simrs-card-body">
                @forelse($pendingPrescriptions as $rx)
                    <div class="d-flex justify-content-between border-bottom pb-2 mb-2">
                        <div><strong>{{ $rx->encounter->patient->nama_pasien }}</strong><div class="small text-muted text-mono">{{ $rx->no_resep }}</div></div>
                        <span class="badge-status status-{{ $rx->status }}">{{ ucfirst($rx->status) }}</span>
                    </div>
                @empty
                    <div class="text-muted small">Tidak ada resep tertunda.</div>
                @endforelse
            </div>
        </div>
        <div class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-triangle-exclamation"></i>Stok Minimum</div></div>
            <div class="simrs-card-body">
                @forelse($lowStock as $medicine)
                    <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
                        <div><strong>{{ $medicine->nama_obat }}</strong><div class="small text-muted">{{ $medicine->kategori }}</div></div>
                        <span class="text-mono fw-bold text-danger">{{ $medicine->stok }} {{ $medicine->satuan }}</span>
                    </div>
                @empty
                    <div class="text-muted small">Stok aman.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    new Chart(document.getElementById('visitChart'), {
        type: 'line',
        data: {labels: @json($visitLabels), datasets: [{label:'Kunjungan', data:@json($visitSeries), borderColor:'#0B6477', backgroundColor:'rgba(11,100,119,.1)', fill:true, tension:.35, borderWidth:2}]},
        options: {responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true, grid:{color:'rgba(226,232,240,.7)'}}, x:{grid:{display:false}}}}
    });
</script>
@endsection

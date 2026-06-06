@extends('layouts.app')

@section('title', 'Detail Pasien')
@section('page-title', $patient->nama_pasien)
@section('page-subtitle', $patient->no_rkm_medis . ' - ' . $patient->nik)

@section('content')
<div class="page-header-bar">
    <div>
        <span class="patient-info-chip"><i class="fa-solid fa-droplet"></i>Golongan darah {{ $patient->golongan_darah ?: '-' }}</span>
        <span class="patient-info-chip"><i class="fa-solid fa-cake-candles"></i>{{ $patient->age }} tahun</span>
        @if($patient->alergi)<span class="patient-info-chip" style="background:var(--simrs-danger-pale);color:var(--simrs-danger)"><i class="fa-solid fa-triangle-exclamation"></i>{{ $patient->alergi }}</span>@endif
    </div>
    <a href="{{ route('pendaftaran.kunjungan.create', ['patient_id' => $patient->id]) }}" class="btn btn-simrs-primary"><i class="fa-solid fa-calendar-plus me-1"></i>Daftar Kunjungan</a>
</div>

<div class="row g-3">
    <div class="col-lg-4">
        <div class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-id-card"></i>Profil Pasien</div></div>
            <div class="simrs-card-body">
                <dl class="row mb-0">
                    <dt class="col-5 text-muted">Nama</dt><dd class="col-7 fw-bold">{{ $patient->nama_pasien }}</dd>
                    <dt class="col-5 text-muted">Kelamin</dt><dd class="col-7">{{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</dd>
                    <dt class="col-5 text-muted">TTL</dt><dd class="col-7">{{ $patient->tempat_lahir }}, {{ $patient->tgl_lahir?->format('d/m/Y') }}</dd>
                    <dt class="col-5 text-muted">BPJS</dt><dd class="col-7 text-mono">{{ $patient->no_bpjs ?: '-' }}</dd>
                    <dt class="col-5 text-muted">Telepon</dt><dd class="col-7">{{ $patient->no_telp_pasien ?: '-' }}</dd>
                    <dt class="col-5 text-muted">Alamat</dt><dd class="col-7">{{ $patient->alamat_lengkap }}</dd>
                    <dt class="col-5 text-muted">Kontak Darurat</dt><dd class="col-7">{{ $patient->kontak_darurat_nama ?: '-' }}<br>{{ $patient->kontak_darurat_telp ?: '' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-clock-rotate-left"></i>Riwayat Kunjungan</div></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>No Registrasi</th><th>Tanggal</th><th>Unit</th><th>Dokter</th><th>Penjamin</th><th>Status</th><th>Invoice</th></tr></thead>
                    <tbody>
                    @forelse($patient->encounters->sortByDesc('waktu_masuk') as $encounter)
                        <tr>
                            <td class="text-mono">{{ $encounter->no_registrasi }}</td>
                            <td>{{ $encounter->waktu_masuk?->format('d/m/Y H:i') }}</td>
                            <td>{{ $encounter->department?->nama_depart }}</td>
                            <td>{{ $encounter->doctor?->display_name ?? '-' }}</td>
                            <td>{{ strtoupper($encounter->cara_bayar) }}</td>
                            <td><span class="badge-status status-{{ str_replace('_','-',$encounter->status_encounter) }}">{{ str_replace('_',' ',ucfirst($encounter->status_encounter)) }}</span></td>
                            <td>
                                @if($encounter->billingInvoice)
                                    <a href="{{ route('keuangan.invoice.show', $encounter->billingInvoice) }}" class="btn btn-sm btn-simrs-outline">Lihat</a>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada riwayat kunjungan.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

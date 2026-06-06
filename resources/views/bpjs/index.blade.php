@extends('layouts.app')

@section('title', 'BPJS')
@section('page-title', 'BPJS VClaim & SEP')
@section('page-subtitle', 'Simulasi lokal cek peserta, pembuatan SEP, dan e-Claim')

@section('content')
<div class="row g-3 mb-3">
    <div class="col-lg-4">
        <form method="GET" class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-id-card-clip"></i>Cek Peserta</div></div>
            <div class="simrs-card-body">
                <label class="form-label-custom">Nomor Kartu BPJS</label>
                <div class="d-flex gap-2">
                    <input name="no_kartu" value="{{ request('no_kartu') }}" class="form-control text-mono" placeholder="0001961000000001">
                    <button class="btn btn-simrs-primary"><i class="fa-solid fa-magnifying-glass"></i></button>
                </div>
                @if($participant)
                    <div class="alert-medical alert-medical-info mt-3 mb-0">
                        <div><i class="fa-solid fa-circle-check"></i></div>
                        <div><strong>{{ $participant['response']['statusPeserta'] }}</strong><div class="small">{{ $participant['response']['hakKelas'] }} - {{ $participant['response']['jenisPeserta'] }}</div></div>
                    </div>
                @endif
            </div>
        </form>
    </div>
    <div class="col-lg-8">
        <div class="alert-medical alert-medical-warning">
            <div><i class="fa-solid fa-plug-circle-bolt"></i></div>
            <div><strong>Mode simulasi lokal</strong><div class="small">Service VClaim saat ini memakai response lokal sesuai dokumen rule-simrs untuk demo dan pengembangan.</div></div>
        </div>
    </div>
</div>
<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead><tr><th>Registrasi</th><th>Pasien</th><th>BPJS</th><th>Unit</th><th>Diagnosis</th><th>SEP</th><th>Aksi</th></tr></thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr>
                    <td class="text-mono">{{ $encounter->no_registrasi }}</td>
                    <td><strong>{{ $encounter->patient->nama_pasien }}</strong><div class="small text-muted">{{ $encounter->patient->no_rkm_medis }}</div></td>
                    <td class="text-mono">{{ $encounter->patient->no_bpjs ?: '-' }}</td>
                    <td>{{ $encounter->department?->nama_depart }}</td>
                    <td>{{ $encounter->medicalRecord?->icd10_primer ?: '-' }}</td>
                    <td>{{ $encounter->sepDocument?->no_sep ?: 'Belum dibuat' }}</td>
                    <td class="d-flex gap-2">
                        <form action="{{ route('bpjs.sep.store', $encounter) }}" method="POST">
                            @csrf
                            <input type="hidden" name="diagnosis_awal" value="{{ $encounter->medicalRecord?->icd10_primer ?: 'Z00.0' }}">
                            <button class="btn btn-sm btn-simrs-outline"><i class="fa-solid fa-file-circle-plus me-1"></i>SEP</button>
                        </form>
                        <form action="{{ route('bpjs.eclaim.simulate', $encounter) }}" method="POST">
                            @csrf
                            <button class="btn btn-sm btn-simrs-primary">e-Claim</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center text-muted py-4">Belum ada encounter BPJS.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $encounters->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

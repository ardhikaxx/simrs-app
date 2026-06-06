@extends('layouts.app')

@section('title', 'Asesmen Keperawatan')
@section('page-title', 'Pemeriksaan Awal (Keperawatan)')
@section('page-subtitle', $encounter->patient->nama_pasien . ' [' . $encounter->patient->no_rkm_medis . ']')

@section('content')
@php($assessment = $encounter->nursingAssessment)
<div class="row g-4 mb-4">
    <div class="col-lg-12">
        <div class="simrs-card mb-0 border-0 shadow-sm overflow-hidden">
            <div class="simrs-card-body p-0">
                <div class="d-flex align-items-center bg-light border-bottom p-3 gap-3">
                    <div class="brand-icon shadow-none bg-primary text-white" style="width: 48px; height: 48px;">
                        <i class="fa-solid fa-user-nurse"></i>
                    </div>
                    <div>
                        <h6 class="fw-800 mb-0">{{ $encounter->patient->nama_pasien }}</h6>
                        <div class="small text-muted text-mono">Reg: {{ $encounter->no_registrasi }} | Unit: {{ $encounter->department->nama_depart }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('keperawatan.asesmen.store', $encounter) }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="simrs-card">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary">
                        <i class="fa-solid fa-heart-pulse"></i>
                        <span>Observasi Tanda-Tanda Vital (Vitals)</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light border text-center h-100">
                                <label class="form-label-custom small text-uppercase mb-2">Tekanan Darah (mmHg)</label>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="number" name="tekanan_darah_sistolik" value="{{ old('tekanan_darah_sistolik', $assessment?->tekanan_darah_sistolik) }}" class="form-control form-control-lg text-center fw-800" placeholder="Sys">
                                    <span class="fs-4 text-muted">/</span>
                                    <input type="number" name="tekanan_darah_diastolik" value="{{ old('tekanan_darah_diastolik', $assessment?->tekanan_darah_diastolik) }}" class="form-control form-control-lg text-center fw-800" placeholder="Dia">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light border text-center h-100">
                                <label class="form-label-custom small text-uppercase mb-2">Frekuensi Nadi (bpm)</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="nadi" value="{{ old('nadi', $assessment?->nadi) }}" class="form-control text-center fw-800">
                                    <span class="input-group-text bg-white border-start-0"><i class="fa-solid fa-wave-square text-danger small"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-3 rounded-3 bg-light border text-center h-100">
                                <label class="form-label-custom small text-uppercase mb-2">Suhu Tubuh (°C)</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.1" name="suhu_tubuh" value="{{ old('suhu_tubuh', $assessment?->suhu_tubuh) }}" class="form-control text-center fw-800">
                                    <span class="input-group-text bg-white border-start-0"><i class="fa-solid fa-temperature-half text-warning small"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Pernapasan (x/mnt)</label>
                            <input type="number" name="pernapasan" value="{{ old('pernapasan', $assessment?->pernapasan) }}" class="form-control text-center">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Saturasi O2 (%)</label>
                            <input type="number" name="saturasi_oksigen" value="{{ old('saturasi_oksigen', $assessment?->saturasi_oksigen) }}" class="form-control text-center">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Skala Nyeri (0-10)</label>
                            <input type="number" name="skala_nyeri" value="{{ old('skala_nyeri', $assessment?->skala_nyeri) }}" class="form-control text-center" min="0" max="10">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Triase Pasien</label>
                            <select name="triase" class="form-select fw-bold shadow-sm">
                                @foreach(['hijau','kuning','merah','hitam'] as $triage)
                                    <option value="{{ $triage }}" @selected(old('triase', $assessment?->triase) === $triage)>{{ strtoupper($triage) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6 border-top pt-3">
                            <label class="form-label-custom">Berat Badan (kg)</label>
                            <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan', $assessment?->berat_badan) }}" class="form-control">
                        </div>
                        <div class="col-md-6 border-top pt-3">
                            <label class="form-label-custom">Tinggi Badan (cm)</label>
                            <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan', $assessment?->tinggi_badan) }}" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Catatan Asesmen / Tindakan Keperawatan</label>
                            <textarea name="catatan_keperawatan" class="form-control" rows="5" placeholder="Tuliskan hasil anamnesa singkat, tindakan awal, atau instruksi perawat lainnya...">{{ old('catatan_keperawatan', $assessment?->catatan_keperawatan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="simrs-card sticky-top" style="top: 80px; z-index: 100;">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary small">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Finalisasi Asesmen</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="p-3 rounded-3 bg-primary-subtle border border-primary-subtle mb-4">
                        <div class="small fw-700 text-simrs-primary mb-1">PETUNJUK:</div>
                        <p class="small text-simrs-primary-dark mb-0 lh-sm">Pastikan seluruh tanda vital telah diukur dengan benar. Data ini akan menjadi acuan dasar bagi dokter dalam melakukan diagnosa medis (RME).</p>
                    </div>
                    
                    <button class="btn btn-simrs-primary w-100 py-3 fw-800 shadow-sm border-0 mb-3">
                        <i class="fa-solid fa-check-circle me-2"></i>SIMPAN ASESMEN
                    </button>
                    
                    <a href="{{ route('keperawatan.antrian') }}" class="btn btn-simrs-outline w-100 fw-bold border-0 text-muted">
                        <i class="fa-solid fa-xmark me-2"></i>Batalkan & Kembali
                    </a>
                </div>
                <div class="simrs-card-body bg-light border-top p-3 text-center">
                    <div class="small text-muted">© SIMRS Core Clinical Interface</div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

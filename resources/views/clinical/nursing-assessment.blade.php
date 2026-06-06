@extends('layouts.app')

@section('title', 'Asesmen Keperawatan')
@section('page-title', 'Pemeriksaan Awal (Keperawatan)')
@section('page-subtitle', $encounter->patient->nama_pasien . ' [' . $encounter->patient->no_rkm_medis . ']')

@section('content')
@php($assessment = $encounter->nursingAssessment)
<div class="row g-4 mb-4">
    <div class="col-lg-12">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
            <div class="card-body p-0">
                <div class="d-flex align-items-center bg-primary bg-opacity-10 border-bottom border-primary border-opacity-10 p-4 gap-4">
                    <div class="d-flex align-items-center justify-content-center bg-primary text-white rounded-circle shadow-sm flex-shrink-0" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fa-solid fa-user-nurse"></i>
                    </div>
                    <div>
                        <h4 class="fw-bolder mb-1 text-dark">{{ $encounter->patient->nama_pasien }}</h4>
                        <div class="text-muted font-monospace small fw-semibold">
                            Reg: <span class="text-primary">{{ $encounter->no_registrasi }}</span> <span class="mx-2 text-black-50">&bull;</span> 
                            Unit: <span class="text-dark">{{ $encounter->department->nama_depart }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('keperawatan.asesmen.store', $encounter) }}" method="POST" class="needs-validation" novalidate>
    @csrf
    <div class="row g-4">
        <div class="col-xl-8 d-flex flex-column gap-4">
            <div class="card border-0 shadow-sm rounded-4 bg-white flex-grow-1">
                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-heart-pulse fs-6"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Observasi Tanda-Tanda Vital (Vitals)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border border-light-subtle text-center h-100 transition-hover">
                                <label class="form-label text-muted fw-bold small text-uppercase tracking-wider mb-3">Tekanan Darah (mmHg)</label>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <input type="number" name="tekanan_darah_sistolik" value="{{ old('tekanan_darah_sistolik', $assessment?->tekanan_darah_sistolik) }}" class="form-control form-control-lg text-center fw-bolder text-primary shadow-none border-secondary border-opacity-25" placeholder="Sys" style="font-size: 1.25rem;">
                                    <span class="fs-3 text-muted fw-light">/</span>
                                    <input type="number" name="tekanan_darah_diastolik" value="{{ old('tekanan_darah_diastolik', $assessment?->tekanan_darah_diastolik) }}" class="form-control form-control-lg text-center fw-bolder text-primary shadow-none border-secondary border-opacity-25" placeholder="Dia" style="font-size: 1.25rem;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border border-light-subtle text-center h-100 transition-hover">
                                <label class="form-label text-muted fw-bold small text-uppercase tracking-wider mb-3">Frekuensi Nadi (bpm)</label>
                                <div class="input-group input-group-lg shadow-none">
                                    <input type="number" name="nadi" value="{{ old('nadi', $assessment?->nadi) }}" class="form-control text-center fw-bolder text-danger shadow-none border-secondary border-opacity-25 border-end-0" style="font-size: 1.25rem;">
                                    <span class="input-group-text bg-white border-secondary border-opacity-25 border-start-0"><i class="fa-solid fa-wave-square text-danger opacity-75"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border border-light-subtle text-center h-100 transition-hover">
                                <label class="form-label text-muted fw-bold small text-uppercase tracking-wider mb-3">Suhu Tubuh (°C)</label>
                                <div class="input-group input-group-lg shadow-none">
                                    <input type="number" step="0.1" name="suhu_tubuh" value="{{ old('suhu_tubuh', $assessment?->suhu_tubuh) }}" class="form-control text-center fw-bolder text-warning shadow-none border-secondary border-opacity-25 border-end-0" style="font-size: 1.25rem;">
                                    <span class="input-group-text bg-white border-secondary border-opacity-25 border-start-0"><i class="fa-solid fa-temperature-half text-warning opacity-75"></i></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" name="pernapasan" value="{{ old('pernapasan', $assessment?->pernapasan) }}" class="form-control fw-bold" id="floatNap" placeholder="x/mnt">
                                <label for="floatNap" class="text-muted fw-semibold">Pernapasan (x/mnt)</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" name="saturasi_oksigen" value="{{ old('saturasi_oksigen', $assessment?->saturasi_oksigen) }}" class="form-control fw-bold text-info" id="floatSpo2" placeholder="%">
                                <label for="floatSpo2" class="text-muted fw-semibold">Saturasi O2 (%)</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" name="skala_nyeri" value="{{ old('skala_nyeri', $assessment?->skala_nyeri) }}" class="form-control fw-bold" id="floatPain" min="0" max="10" placeholder="0-10">
                                <label for="floatPain" class="text-muted fw-semibold">Skala Nyeri (0-10)</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select name="triase" class="form-select fw-bolder" id="floatTriase">
                                    @foreach(['hijau','kuning','merah','hitam'] as $triage)
                                        <option value="{{ $triage }}" class="text-uppercase" @selected(old('triase', $assessment?->triase) === $triage)>{{ strtoupper($triage) }}</option>
                                    @endforeach
                                </select>
                                <label for="floatTriase" class="text-muted fw-semibold">Triase Pasien</label>
                            </div>
                        </div>
                        
                        <div class="col-12"><hr class="border-secondary opacity-10 my-0"></div>
                        
                        <div class="col-md-6">
                            <div class="input-group input-group-lg shadow-none">
                                <span class="input-group-text bg-light border-end-0 text-muted fw-bold" style="font-size: 0.85rem;">Berat Badan</span>
                                <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan', $assessment?->berat_badan) }}" class="form-control border-start-0 border-end-0 fw-bold bg-light shadow-none">
                                <span class="input-group-text bg-light border-start-0 text-muted fw-bold" style="font-size: 0.85rem;">kg</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-lg shadow-none">
                                <span class="input-group-text bg-light border-end-0 text-muted fw-bold" style="font-size: 0.85rem;">Tinggi Badan</span>
                                <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan', $assessment?->tinggi_badan) }}" class="form-control border-start-0 border-end-0 fw-bold bg-light shadow-none">
                                <span class="input-group-text bg-light border-start-0 text-muted fw-bold" style="font-size: 0.85rem;">cm</span>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <label class="form-label text-dark fw-bold mb-2">Catatan Asesmen / Tindakan Keperawatan</label>
                            <textarea name="catatan_keperawatan" class="form-control shadow-sm border-light-subtle rounded-3 p-3" rows="5" placeholder="Tuliskan hasil anamnesa singkat, tindakan awal, atau instruksi perawat lainnya...">{{ old('catatan_keperawatan', $assessment?->catatan_keperawatan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 90px; z-index: 10;">
                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-floppy-disk fs-6"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Finalisasi Asesmen</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-primary bg-primary bg-opacity-10 border-primary border-opacity-25 d-flex gap-3 p-3 mb-4 rounded-3">
                        <i class="fa-solid fa-circle-info text-primary fs-4 mt-1"></i>
                        <div>
                            <div class="fw-bold text-primary small mb-1 tracking-wider text-uppercase">Petunjuk Klinis</div>
                            <p class="small text-primary opacity-75 fw-medium mb-0 lh-sm">Pastikan seluruh tanda vital telah diukur dengan benar. Data ini akan menjadi acuan dasar bagi dokter dalam melakukan diagnosa medis (RME).</p>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-pill mb-3 transition-hover" style="background: linear-gradient(135deg, var(--simrs-primary), var(--simrs-primary-light)); border: none;">
                        <i class="fa-solid fa-check-circle me-2"></i>SIMPAN ASESMEN
                    </button>
                    
                    <a href="{{ route('keperawatan.antrian') }}" class="btn btn-light btn-lg w-100 fw-bold border border-light-subtle text-muted rounded-pill transition-hover">
                        <i class="fa-solid fa-xmark me-2"></i>Batalkan & Kembali
                    </a>
                </div>
                <div class="card-footer bg-light bg-opacity-50 border-0 p-3 text-center rounded-bottom-4">
                    <div class="small text-muted fw-semibold">© SIMRS Core Clinical Interface</div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.08) !important; }
    .tracking-wider { letter-spacing: 0.1em; }
    .form-floating > label { font-size: 0.85rem; padding-left: 1.25rem; }
    .form-control:focus, .form-select:focus { border-color: var(--simrs-primary); box-shadow: 0 0 0 0.25rem rgba(11, 100, 119, 0.1) !important; }
</style>
@endsection

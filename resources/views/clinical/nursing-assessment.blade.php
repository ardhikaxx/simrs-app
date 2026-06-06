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
                <div class="d-flex align-items-center bg-primary bg-gradient text-white p-4 gap-4">
                    <div class="d-flex align-items-center justify-content-center bg-white bg-opacity-25 rounded-circle shadow-sm flex-shrink-0" style="width: 60px; height: 60px; font-size: 1.5rem;">
                        <i class="fa-solid fa-user-nurse"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1 text-white">{{ $encounter->patient->nama_pasien }}</h4>
                        <div class="text-white-50 font-monospace small fw-medium">
                            Reg: <span class="text-white">{{ $encounter->no_registrasi }}</span> <span class="mx-2 opacity-50">&bull;</span> 
                            Unit: <span class="text-white">{{ $encounter->department->nama_depart }}</span>
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
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center gap-3">
                    <div class="bg-danger bg-opacity-10 text-danger rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-heart-pulse fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Observasi Tanda-Tanda Vital (Vitals)</h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border border-light text-center h-100 transition-hover">
                                <label class="form-label text-muted fw-semibold small text-uppercase mb-3" style="letter-spacing: 0.5px;">Tekanan Darah (mmHg)</label>
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <input type="number" name="tekanan_darah_sistolik" value="{{ old('tekanan_darah_sistolik', $assessment?->tekanan_darah_sistolik) }}" class="form-control form-control-lg text-center fw-bold text-primary shadow-none border-light focus-ring-0 bg-white" placeholder="Sys" style="font-size: 1.25rem;">
                                    <span class="fs-4 text-muted fw-light">/</span>
                                    <input type="number" name="tekanan_darah_diastolik" value="{{ old('tekanan_darah_diastolik', $assessment?->tekanan_darah_diastolik) }}" class="form-control form-control-lg text-center fw-bold text-primary shadow-none border-light focus-ring-0 bg-white" placeholder="Dia" style="font-size: 1.25rem;">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border border-light text-center h-100 transition-hover">
                                <label class="form-label text-muted fw-semibold small text-uppercase mb-3" style="letter-spacing: 0.5px;">Frekuensi Nadi (bpm)</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" name="nadi" value="{{ old('nadi', $assessment?->nadi) }}" class="form-control text-center fw-bold text-danger shadow-none border-light border-end-0 focus-ring-0 bg-white" style="font-size: 1.25rem;">
                                    <span class="input-group-text bg-white border-light border-start-0 text-muted"><i class="fa-solid fa-wave-square text-danger opacity-75"></i></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="p-4 rounded-4 bg-light border border-light text-center h-100 transition-hover">
                                <label class="form-label text-muted fw-semibold small text-uppercase mb-3" style="letter-spacing: 0.5px;">Suhu Tubuh (°C)</label>
                                <div class="input-group input-group-lg">
                                    <input type="number" step="0.1" name="suhu_tubuh" value="{{ old('suhu_tubuh', $assessment?->suhu_tubuh) }}" class="form-control text-center fw-bold text-warning shadow-none border-light border-end-0 focus-ring-0 bg-white" style="font-size: 1.25rem;">
                                    <span class="input-group-text bg-white border-light border-start-0 text-muted"><i class="fa-solid fa-temperature-half text-warning opacity-75"></i></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" name="pernapasan" value="{{ old('pernapasan', $assessment?->pernapasan) }}" class="form-control bg-light border-light shadow-none focus-ring-0 fw-bold" id="floatNap" placeholder="x/mnt">
                                <label for="floatNap" class="text-muted fw-semibold" style="font-size: 0.85rem;">Pernapasan (x/mnt)</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" name="saturasi_oksigen" value="{{ old('saturasi_oksigen', $assessment?->saturasi_oksigen) }}" class="form-control bg-light border-light shadow-none focus-ring-0 fw-bold text-info" id="floatSpo2" placeholder="%">
                                <label for="floatSpo2" class="text-muted fw-semibold" style="font-size: 0.85rem;">Saturasi O2 (%)</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <input type="number" name="skala_nyeri" value="{{ old('skala_nyeri', $assessment?->skala_nyeri) }}" class="form-control bg-light border-light shadow-none focus-ring-0 fw-bold" id="floatPain" min="0" max="10" placeholder="0-10">
                                <label for="floatPain" class="text-muted fw-semibold" style="font-size: 0.85rem;">Skala Nyeri (0-10)</label>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-floating">
                                <select name="triase" class="form-select bg-light border-light shadow-none focus-ring-0 fw-bold" id="floatTriase">
                                    @foreach(['hijau','kuning','merah','hitam'] as $triage)
                                        <option value="{{ $triage }}" class="text-uppercase" @selected(old('triase', $assessment?->triase) === $triage)>{{ strtoupper($triage) }}</option>
                                    @endforeach
                                </select>
                                <label for="floatTriase" class="text-muted fw-semibold" style="font-size: 0.85rem;">Triase Pasien</label>
                            </div>
                        </div>
                        
                        <div class="col-12"><hr class="border-light my-2"></div>
                        
                        <div class="col-md-6">
                            <div class="input-group input-group-lg shadow-none">
                                <span class="input-group-text bg-light border-light border-end-0 text-muted fw-semibold" style="font-size: 0.85rem;">Berat Badan</span>
                                <input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan', $assessment?->berat_badan) }}" class="form-control border-light border-start-0 border-end-0 fw-bold shadow-none focus-ring-0 text-center">
                                <span class="input-group-text bg-light border-light border-start-0 text-muted fw-semibold" style="font-size: 0.85rem;">kg</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="input-group input-group-lg shadow-none">
                                <span class="input-group-text bg-light border-light border-end-0 text-muted fw-semibold" style="font-size: 0.85rem;">Tinggi Badan</span>
                                <input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan', $assessment?->tinggi_badan) }}" class="form-control border-light border-start-0 border-end-0 fw-bold shadow-none focus-ring-0 text-center">
                                <span class="input-group-text bg-light border-light border-start-0 text-muted fw-semibold" style="font-size: 0.85rem;">cm</span>
                            </div>
                        </div>
                        
                        <div class="col-12 mt-4">
                            <label class="form-label text-dark fw-bold mb-2">Catatan Asesmen / Tindakan Keperawatan</label>
                            <textarea name="catatan_keperawatan" class="form-control shadow-none focus-ring-0 border-light bg-light rounded-3 p-3" rows="5" placeholder="Tuliskan hasil anamnesa singkat, tindakan awal, atau instruksi perawat lainnya...">{{ old('catatan_keperawatan', $assessment?->catatan_keperawatan) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 position-sticky bg-white" style="top: 90px; z-index: 10;">
                <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-floppy-disk fs-5"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Finalisasi Asesmen</h5>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-primary bg-primary bg-opacity-10 border-0 d-flex gap-3 p-3 mb-4 rounded-4">
                        <i class="fa-solid fa-circle-info text-primary fs-5 mt-1"></i>
                        <div>
                            <div class="fw-bold text-primary small mb-1 text-uppercase" style="letter-spacing: 0.5px;">Petunjuk Klinis</div>
                            <p class="small text-dark fw-medium mb-0 lh-sm opacity-75">Pastikan seluruh tanda vital telah diukur dengan benar. Data ini akan menjadi acuan dasar bagi dokter dalam melakukan diagnosa medis (RME).</p>
                        </div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-pill mb-3 transition-hover">
                        <i class="fa-solid fa-check-circle me-2"></i>SIMPAN ASESMEN
                    </button>
                    
                    <a href="{{ route('keperawatan.antrian') }}" class="btn btn-light btn-lg w-100 fw-bold border border-light text-muted rounded-pill transition-hover hover-bg-gray">
                        <i class="fa-solid fa-xmark me-2"></i>Batalkan & Kembali
                    </a>
                </div>
                <div class="card-footer bg-light border-0 p-3 text-center rounded-bottom-4">
                    <div class="small text-muted fw-semibold">© SIMRS Core Clinical Interface</div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); }
</style>
@endsection

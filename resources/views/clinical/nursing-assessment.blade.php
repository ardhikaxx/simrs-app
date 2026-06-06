@extends('layouts.app')

@section('title', 'Asesmen Keperawatan')
@section('page-title', 'Asesmen Awal Keperawatan')
@section('page-subtitle', $encounter->patient->nama_pasien . ' - ' . $encounter->no_registrasi)

@section('content')
@php($assessment = $encounter->nursingAssessment)
<div class="alert-medical alert-medical-info">
    <div><i class="fa-solid fa-user-injured"></i></div>
    <div>
        <strong>{{ $encounter->patient->nama_pasien }}</strong>
        <div class="small">{{ $encounter->department->nama_depart }} - {{ $encounter->keluhan_awal ?: 'Keluhan belum diisi' }}</div>
    </div>
</div>

<form action="{{ route('keperawatan.asesmen.store', $encounter) }}" method="POST" class="simrs-card">
    @csrf
    <div class="simrs-card-header">
        <div class="simrs-card-title"><i class="fa-solid fa-heart-pulse"></i>Tanda Vital & Triase</div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Asesmen</button>
    </div>
    <div class="simrs-card-body">
        <div class="row g-3">
            <div class="col-md-2"><label class="form-label-custom">Sistolik</label><input type="number" name="tekanan_darah_sistolik" value="{{ old('tekanan_darah_sistolik', $assessment?->tekanan_darah_sistolik) }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label-custom">Diastolik</label><input type="number" name="tekanan_darah_diastolik" value="{{ old('tekanan_darah_diastolik', $assessment?->tekanan_darah_diastolik) }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label-custom">Nadi</label><input type="number" name="nadi" value="{{ old('nadi', $assessment?->nadi) }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label-custom">Suhu</label><input type="number" step="0.1" name="suhu_tubuh" value="{{ old('suhu_tubuh', $assessment?->suhu_tubuh) }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label-custom">Pernapasan</label><input type="number" name="pernapasan" value="{{ old('pernapasan', $assessment?->pernapasan) }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label-custom">SpO2</label><input type="number" name="saturasi_oksigen" value="{{ old('saturasi_oksigen', $assessment?->saturasi_oksigen) }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label-custom">Nyeri</label><input type="number" name="skala_nyeri" value="{{ old('skala_nyeri', $assessment?->skala_nyeri) }}" class="form-control" min="0" max="10"></div>
            <div class="col-md-2"><label class="form-label-custom">Berat Badan</label><input type="number" step="0.1" name="berat_badan" value="{{ old('berat_badan', $assessment?->berat_badan) }}" class="form-control"></div>
            <div class="col-md-2"><label class="form-label-custom">Tinggi Badan</label><input type="number" step="0.1" name="tinggi_badan" value="{{ old('tinggi_badan', $assessment?->tinggi_badan) }}" class="form-control"></div>
            <div class="col-md-3">
                <label class="form-label-custom">Triase</label>
                <select name="triase" class="form-select">
                    @foreach(['hijau','kuning','merah','hitam'] as $triage)
                        <option value="{{ $triage }}" @selected(old('triase', $assessment?->triase) === $triage)>{{ ucfirst($triage) }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-12"><label class="form-label-custom">Catatan Keperawatan</label><textarea name="catatan_keperawatan" class="form-control" rows="4">{{ old('catatan_keperawatan', $assessment?->catatan_keperawatan) }}</textarea></div>
        </div>
    </div>
</form>
@endsection

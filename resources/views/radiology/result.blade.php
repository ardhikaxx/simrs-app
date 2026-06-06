@extends('layouts.app')

@section('title', 'Hasil Radiologi')
@section('page-title', 'Input Hasil Radiologi')
@section('page-subtitle', $radiologyOrder->no_order . ' - ' . $radiologyOrder->encounter->patient->nama_pasien)

@section('content')
@php($result = $radiologyOrder->result)
<form action="{{ route('rad.hasil.update', $radiologyOrder) }}" method="POST" class="simrs-card">
    @csrf
    <div class="simrs-card-header">
        <div>
            <div class="simrs-card-title"><i class="fa-solid fa-x-ray"></i>{{ $radiologyOrder->jenis_pemeriksaan }}</div>
            <div class="small text-muted">{{ $radiologyOrder->doctor?->display_name }} - {{ strtoupper($radiologyOrder->prioritas) }}</div>
        </div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-check me-1"></i>Simpan Hasil</button>
    </div>
    <div class="simrs-card-body">
        <div class="row g-3">
            <div class="col-md-6"><label class="form-label-custom">Temuan</label><textarea name="temuan" class="form-control" rows="8" required>{{ old('temuan', $result?->temuan) }}</textarea></div>
            <div class="col-md-6"><label class="form-label-custom">Kesan</label><textarea name="kesan" class="form-control" rows="8" required>{{ old('kesan', $result?->kesan) }}</textarea></div>
            <div class="col-12"><label class="form-label-custom">Path Gambar / PACS</label><input name="image_path" value="{{ old('image_path', $result?->image_path) }}" class="form-control" placeholder="storage/radiology/hasil.jpg"></div>
        </div>
    </div>
</form>
@endsection

@extends('layouts.app')

@section('title', 'Rekam Medis')
@section('page-title', 'Rekam Medis Elektronik')
@section('page-subtitle', $encounter->patient->nama_pasien . ' - ' . $encounter->no_registrasi)

@section('content')
@php($record = $encounter->medicalRecord)
<div class="row g-3 mb-3">
    <div class="col-lg-8">
        <div class="alert-medical alert-medical-info">
            <div><i class="fa-solid fa-user-doctor"></i></div>
            <div><strong>{{ $encounter->patient->nama_pasien }}</strong><div class="small">{{ $encounter->patient->no_rkm_medis }} - {{ $encounter->department->nama_depart }} - {{ strtoupper($encounter->cara_bayar) }}</div></div>
        </div>
    </div>
    <div class="col-lg-4">
        @if($encounter->patient->alergi)
            <div class="alert-medical alert-medical-critical">
                <div><i class="fa-solid fa-triangle-exclamation"></i></div>
                <div><strong>Alergi</strong><div class="small">{{ $encounter->patient->alergi }}</div></div>
            </div>
        @endif
    </div>
</div>

<form action="{{ route('rekam-medis.update', $encounter) }}" method="POST" class="simrs-card">
    @csrf
    <div class="simrs-card-header">
        <div class="simrs-card-title"><i class="fa-solid fa-file-medical"></i>SOAP & Order Pelayanan</div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-signature me-1"></i>Simpan Rekam Medis</button>
    </div>
    <div class="simrs-card-body">
        <ul class="nav nav-tabs mb-3" role="tablist">
            <li class="nav-item"><button type="button" class="nav-link active" data-bs-toggle="tab" data-bs-target="#tab-soap">SOAP</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-rx">Resep</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-support">Penunjang</button></li>
            <li class="nav-item"><button type="button" class="nav-link" data-bs-toggle="tab" data-bs-target="#tab-history">Riwayat Order</button></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="tab-soap">
                <div class="row g-3">
                    <div class="col-md-6"><label class="form-label-custom">Keluhan Utama</label><textarea name="keluhan_utama" class="form-control" rows="3" required>{{ old('keluhan_utama', $record?->keluhan_utama ?? $encounter->keluhan_awal) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label-custom">Riwayat Penyakit Sekarang</label><textarea name="riwayat_penyakit_sekarang" class="form-control" rows="3">{{ old('riwayat_penyakit_sekarang', $record?->riwayat_penyakit_sekarang) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label-custom">Riwayat Penyakit Dahulu</label><textarea name="riwayat_penyakit_dahulu" class="form-control" rows="3">{{ old('riwayat_penyakit_dahulu', $record?->riwayat_penyakit_dahulu) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label-custom">Pemeriksaan Fisik</label><textarea name="pemeriksaan_fisik" class="form-control" rows="3">{{ old('pemeriksaan_fisik', $record?->pemeriksaan_fisik) }}</textarea></div>
                    <div class="col-md-6"><label class="form-label-custom">Diagnosis Kerja</label><textarea name="diagnosis_kerja" class="form-control" rows="3" required>{{ old('diagnosis_kerja', $record?->diagnosis_kerja) }}</textarea></div>
                    <div class="col-md-3">
                        <label class="form-label-custom">ICD-10 Primer</label>
                        <select name="icd10_primer" class="form-select" required>
                            @foreach($icd10 as $code)
                                <option value="{{ $code->kode }}" @selected(old('icd10_primer', $record?->icd10_primer) === $code->kode)>{{ $code->kode }} - {{ $code->nama_diagnosis }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label-custom">Kondisi Pulang</label>
                        <select name="kondisi_saat_pulang" class="form-select">
                            <option value="">Belum pulang</option>
                            @foreach(['membaik','sembuh','dirujuk','meninggal'] as $condition)
                                <option value="{{ $condition }}" @selected(old('kondisi_saat_pulang', $record?->kondisi_saat_pulang) === $condition)>{{ ucfirst($condition) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12"><label class="form-label-custom">Rencana Terapi</label><textarea name="rencana_terapi" class="form-control" rows="3" required>{{ old('rencana_terapi', $record?->rencana_terapi) }}</textarea></div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-rx">
                <div class="row g-3">
                    @for($i = 0; $i < 4; $i++)
                        <div class="col-md-5">
                            <label class="form-label-custom">Obat {{ $i + 1 }}</label>
                            <select name="medicine_id[]" class="form-select">
                                <option value="">Tidak dipilih</option>
                                @foreach($medicines as $medicine)
                                    <option value="{{ $medicine->id }}">{{ $medicine->nama_obat }} - stok {{ $medicine->stok }} {{ $medicine->satuan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2"><label class="form-label-custom">Jumlah</label><input type="number" step="0.01" name="jumlah[]" class="form-control" value="1"></div>
                        <div class="col-md-5"><label class="form-label-custom">Aturan Pakai</label><input name="aturan_pakai[]" class="form-control" placeholder="3x1 sesudah makan"></div>
                    @endfor
                </div>
            </div>
            <div class="tab-pane fade" id="tab-support">
                <div class="row g-3">
                    <div class="col-lg-6">
                        <div class="section-label mb-2">Order Laboratorium</div>
                        @foreach(['Darah Lengkap','Glukosa Darah Sewaktu','Fungsi Ginjal','Elektrolit','Urinalisis'] as $item)
                            <label class="form-check mb-2"><input class="form-check-input" type="checkbox" name="lab_items[]" value="{{ $item }}"> <span class="form-check-label">{{ $item }}</span></label>
                        @endforeach
                        <select name="lab_prioritas" class="form-select mt-2"><option value="rutin">Rutin</option><option value="cito">CITO</option></select>
                    </div>
                    <div class="col-lg-6">
                        <div class="section-label mb-2">Order Radiologi</div>
                        @foreach(['Thorax PA','USG Abdomen','Foto Ekstremitas','CT Scan Kepala'] as $item)
                            <label class="form-check mb-2"><input class="form-check-input" type="checkbox" name="radiology_items[]" value="{{ $item }}"> <span class="form-check-label">{{ $item }}</span></label>
                        @endforeach
                        <select name="radiology_prioritas" class="form-select mt-2"><option value="rutin">Rutin</option><option value="cito">CITO</option></select>
                    </div>
                </div>
            </div>
            <div class="tab-pane fade" id="tab-history">
                <div class="row g-3">
                    <div class="col-lg-4">
                        <div class="section-label mb-2">Resep</div>
                        @forelse($encounter->prescriptions as $rx)
                            <div class="border-bottom pb-2 mb-2"><strong class="text-mono">{{ $rx->no_resep }}</strong><div class="small text-muted">{{ $rx->details->count() }} item - {{ $rx->status }}</div></div>
                        @empty <div class="text-muted small">Belum ada resep.</div> @endforelse
                    </div>
                    <div class="col-lg-4">
                        <div class="section-label mb-2">Laboratorium</div>
                        @forelse($encounter->labOrders as $order)
                            <div class="border-bottom pb-2 mb-2"><strong>{{ $order->jenis_pemeriksaan }}</strong><div class="small text-muted">{{ $order->no_order }} - {{ $order->status }}</div></div>
                        @empty <div class="text-muted small">Belum ada order lab.</div> @endforelse
                    </div>
                    <div class="col-lg-4">
                        <div class="section-label mb-2">Radiologi</div>
                        @forelse($encounter->radiologyOrders as $order)
                            <div class="border-bottom pb-2 mb-2"><strong>{{ $order->jenis_pemeriksaan }}</strong><div class="small text-muted">{{ $order->no_order }} - {{ $order->status }}</div></div>
                        @empty <div class="text-muted small">Belum ada order radiologi.</div> @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

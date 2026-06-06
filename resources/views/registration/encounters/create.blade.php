@extends('layouts.app')

@section('title', 'Kunjungan Baru')
@section('page-title', 'Registrasi Kunjungan')
@section('page-subtitle', 'Buat encounter dan nomor antrean pelayanan')

@section('content')
<form action="{{ route('pendaftaran.kunjungan.store') }}" method="POST" class="simrs-card">
    @csrf
    <div class="simrs-card-header">
        <div class="simrs-card-title"><i class="fa-solid fa-calendar-plus"></i>Data Kunjungan</div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-check me-1"></i>Daftarkan</button>
    </div>
    <div class="simrs-card-body">
        <div class="row g-3">
            <div class="col-md-6">
                <label class="form-label-custom">Pasien</label>
                <select name="patient_id" class="form-select" required>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @selected((int) old('patient_id', $selectedPatient) === $patient->id)>{{ $patient->no_rkm_medis }} - {{ $patient->nama_pasien }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Jenis Kunjungan</label>
                <select name="jenis_kunjungan" class="form-select" required>
                    <option value="rawat_jalan">Rawat Jalan</option>
                    <option value="igd">IGD</option>
                    <option value="rawat_inap">Rawat Inap</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Cara Bayar</label>
                <select name="cara_bayar" class="form-select" required>
                    <option value="umum">Umum</option>
                    <option value="bpjs">BPJS</option>
                    <option value="asuransi">Asuransi</option>
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Unit Tujuan</label>
                <select name="department_id" class="form-select" required>
                    @foreach($departments as $department)
                        <option value="{{ $department->id }}">{{ $department->kode_depart }} - {{ $department->nama_depart }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Dokter DPJP</label>
                <select name="doctor_id" class="form-select">
                    <option value="">Pilih otomatis / belum ditentukan</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->display_name }} - {{ $doctor->department?->nama_depart }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Cara Masuk</label>
                <select name="cara_masuk" class="form-select">
                    <option value="datang_sendiri">Datang Sendiri</option>
                    <option value="rujukan_puskesmas">Rujukan Puskesmas</option>
                    <option value="rujukan_rs">Rujukan RS</option>
                    <option value="ambulans">Ambulans</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Kelas Rawat</label>
                <select name="kelas_rawat" class="form-select">
                    <option value="">Tidak rawat inap</option>
                    <option>Kelas I</option>
                    <option>Kelas II</option>
                    <option>Kelas III</option>
                    <option>VIP</option>
                </select>
            </div>
            <div class="col-md-3"><label class="form-label-custom">Kamar</label><input name="kamar" class="form-control" value="{{ old('kamar') }}"></div>
            <div class="col-md-3"><label class="form-label-custom">Bed</label><input name="bed" class="form-control" value="{{ old('bed') }}"></div>
            <div class="col-md-6"><label class="form-label-custom">Rujukan Dari</label><input name="rujukan_dari" class="form-control" value="{{ old('rujukan_dari') }}"></div>
            <div class="col-md-6"><label class="form-label-custom">Keluhan Awal</label><textarea name="keluhan_awal" class="form-control" rows="3">{{ old('keluhan_awal') }}</textarea></div>
        </div>
    </div>
</form>
@endsection

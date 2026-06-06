@extends('layouts.app')

@section('title', 'Pasien Baru')
@section('page-title', 'Registrasi Pasien Baru')
@section('page-subtitle', 'Input identitas pasien sesuai dokumen kependudukan')

@section('content')
<form action="{{ route('pendaftaran.pasien.store') }}" method="POST" class="simrs-card">
    @csrf
    <div class="simrs-card-header">
        <div class="simrs-card-title"><i class="fa-solid fa-user-injured"></i>Form Identitas Pasien</div>
        <button class="btn btn-simrs-primary"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan</button>
    </div>
    <div class="simrs-card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label-custom">No Rekam Medis</label>
                <input name="no_rkm_medis" value="{{ old('no_rkm_medis', $noRm) }}" class="form-control text-mono" required>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">NIK</label>
                <input name="nik" value="{{ old('nik') }}" class="form-control text-mono" required maxlength="20">
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">No BPJS</label>
                <input name="no_bpjs" value="{{ old('no_bpjs') }}" class="form-control text-mono">
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">No Telepon</label>
                <input name="no_telp_pasien" value="{{ old('no_telp_pasien') }}" class="form-control">
            </div>
            <div class="col-md-6">
                <label class="form-label-custom">Nama Pasien</label>
                <input name="nama_pasien" value="{{ old('nama_pasien') }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Jenis Kelamin</label>
                <select name="jenis_kelamin" class="form-select" required>
                    <option value="L" @selected(old('jenis_kelamin') === 'L')>Laki-laki</option>
                    <option value="P" @selected(old('jenis_kelamin') === 'P')>Perempuan</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Tanggal Lahir</label>
                <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" class="form-control" required>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Tempat Lahir</label>
                <input name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Golongan Darah</label>
                <select name="golongan_darah" class="form-select">
                    <option value="">-</option>
                    @foreach(['A','B','AB','O'] as $blood)
                        <option value="{{ $blood }}" @selected(old('golongan_darah') === $blood)>{{ $blood }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Agama</label>
                <input name="agama" value="{{ old('agama', 'Islam') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Status Perkawinan</label>
                <select name="status_perkawinan" class="form-select">
                    @foreach(['Belum menikah','Menikah','Cerai hidup','Cerai mati'] as $status)
                        <option value="{{ $status }}" @selected(old('status_perkawinan') === $status)>{{ $status }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Pekerjaan</label>
                <input name="pekerjaan" value="{{ old('pekerjaan') }}" class="form-control">
            </div>
            <div class="col-md-3">
                <label class="form-label-custom">Pendidikan</label>
                <input name="pendidikan" value="{{ old('pendidikan') }}" class="form-control">
            </div>
            <div class="col-12">
                <label class="form-label-custom">Alamat Lengkap</label>
                <textarea name="alamat_lengkap" class="form-control" rows="3" required>{{ old('alamat_lengkap') }}</textarea>
            </div>
            <div class="col-md-3"><label class="form-label-custom">Kelurahan</label><input name="kelurahan" value="{{ old('kelurahan') }}" class="form-control"></div>
            <div class="col-md-3"><label class="form-label-custom">Kecamatan</label><input name="kecamatan" value="{{ old('kecamatan') }}" class="form-control"></div>
            <div class="col-md-3"><label class="form-label-custom">Kota</label><input name="kota" value="{{ old('kota') }}" class="form-control"></div>
            <div class="col-md-3"><label class="form-label-custom">Provinsi</label><input name="provinsi" value="{{ old('provinsi') }}" class="form-control"></div>
            <div class="col-md-6"><label class="form-label-custom">Kontak Darurat</label><input name="kontak_darurat_nama" value="{{ old('kontak_darurat_nama') }}" class="form-control" placeholder="Nama keluarga"></div>
            <div class="col-md-6"><label class="form-label-custom">Telepon Kontak Darurat</label><input name="kontak_darurat_telp" value="{{ old('kontak_darurat_telp') }}" class="form-control"></div>
            <div class="col-12"><label class="form-label-custom">Alergi</label><textarea name="alergi" class="form-control" rows="2">{{ old('alergi') }}</textarea></div>
        </div>
    </div>
</form>
@endsection

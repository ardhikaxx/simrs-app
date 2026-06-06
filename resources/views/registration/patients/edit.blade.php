@extends('layouts.app')

@section('title', 'Update Pasien')
@section('page-title', 'Master Registry Pasien')
@section('page-subtitle', 'Pembaruan data demografi pasien ' . $patient->no_rkm_medis)

@section('content')
<form action="{{ route('pendaftaran.pasien.update', $patient) }}" method="POST" class="needs-validation">
    @csrf @method('PATCH')
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="card-premium border-0 bg-white p-4 mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-user-pen fs-6"></i>
                    </div>
                    <h5 class="fw-800 text-slate mb-0">Update Identitas Utama</h5>
                </div>

                <div class="row g-4 mb-5">
                    <div class="col-md-3">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">No. Rekam Medis</label>
                        <input value="{{ $patient->no_rkm_medis }}" class="form-control border-0 bg-light fw-800 text-primary font-monospace" readonly>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">NIK (Sesuai KTP)</label>
                        <input name="nik" value="{{ old('nik', $patient->nik) }}" class="form-control border-0 bg-light fw-bold font-monospace py-2" placeholder="32xxxxxxxxxxxxxx" required maxlength="20">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">No. Kartu BPJS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted ps-3"><i class="fa-solid fa-shield-halved small"></i></span>
                            <input name="no_bpjs" value="{{ old('no_bpjs', $patient->no_bpjs) }}" class="form-control border-0 bg-light font-monospace py-2" placeholder="0001xxxxxxxx">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">WhatsApp / Telepon</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0 text-muted ps-3"><i class="fa-solid fa-phone small"></i></span>
                            <input name="no_telp_pasien" value="{{ old('no_telp_pasien', $patient->no_telp_pasien) }}" class="form-control border-0 bg-light py-2" placeholder="08xxxxxxxxxx">
                        </div>
                    </div>

                    <div class="col-md-6 border-top border-light pt-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Nama Lengkap Pasien</label>
                        <input name="nama_pasien" value="{{ old('nama_pasien', $patient->nama_pasien) }}" class="form-control border-0 bg-light py-3 fw-800 text-slate fs-5" placeholder="Masukkan nama sesuai kartu identitas" required>
                    </div>
                    <div class="col-md-3 border-top border-light pt-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Jenis Kelamin</label>
                        <div class="d-flex gap-4 pt-2">
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" id="jkL" @checked(old('jenis_kelamin', $patient->jenis_kelamin) === 'L')>
                                <label class="form-check-label fw-bold text-slate" for="jkL">Laki-laki</label>
                            </div>
                            <div class="form-check custom-radio">
                                <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" id="jkP" @checked(old('jenis_kelamin', $patient->jenis_kelamin) === 'P')>
                                <label class="form-check-label fw-bold text-slate" for="jkP">Perempuan</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 border-top border-light pt-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Golongan Darah</label>
                        <select name="golongan_darah" class="form-select border-0 bg-light py-2 fw-bold">
                            <option value="">Tidak Tahu / -</option>
                            @foreach(['A','B','AB','O'] as $blood)
                                <option value="{{ $blood }}" @selected(old('golongan_darah', $patient->golongan_darah) === $blood)>{{ $blood }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Tempat Lahir</label>
                        <input name="tempat_lahir" value="{{ old('tempat_lahir', $patient->tempat_lahir) }}" class="form-control border-0 bg-light py-2" placeholder="Kota lahir">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $patient->tgl_lahir?->format('Y-m-d')) }}" class="form-control border-0 bg-light py-2 fw-bold" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Agama</label>
                        <select name="agama" class="form-select border-0 bg-light py-2">
                            @foreach(['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agm)
                                <option value="{{ $agm }}" @selected(old('agama', $patient->agama) === $agm)>{{ $agm }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Pendidikan</label>
                        <input name="pendidikan" value="{{ old('pendidikan', $patient->pendidikan) }}" class="form-control border-0 bg-light py-2" placeholder="SMA / S1 / dst">
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-map-location-dot fs-6"></i>
                    </div>
                    <h5 class="fw-800 text-slate mb-0">Update Domisili & Kontak</h5>
                </div>

                <div class="row g-4 mb-4">
                    <div class="col-12">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Alamat Lengkap (Sesuai KTP)</label>
                        <textarea name="alamat_lengkap" class="form-control border-0 bg-light py-3" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW..." required>{{ old('alamat_lengkap', $patient->alamat_lengkap) }}</textarea>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">Kelurahan</label>
                        <input name="kelurahan" value="{{ old('kelurahan', $patient->kelurahan) }}" class="form-control border-0 bg-light py-2">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">Kecamatan</label>
                        <input name="kecamatan" value="{{ old('kecamatan', $patient->kecamatan) }}" class="form-control border-0 bg-light py-2">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">Kota / Kab</label>
                        <input name="kota" value="{{ old('kota', $patient->kota) }}" class="form-control border-0 bg-light py-2">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label fw-bold small text-muted">Provinsi</label>
                        <input name="provinsi" value="{{ old('provinsi', $patient->provinsi) }}" class="form-control border-0 bg-light py-2">
                    </div>
                </div>

                <div class="row g-4 border-top border-light pt-5">
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Kontak Darurat / Penanggung Jawab</label>
                        <input name="kontak_darurat_nama" value="{{ old('kontak_darurat_nama', $patient->kontak_darurat_nama) }}" class="form-control border-0 bg-light py-2" placeholder="Nama lengkap keluarga">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Telepon Darurat</label>
                        <input name="kontak_darurat_telp" value="{{ old('kontak_darurat_telp', $patient->kontak_darurat_telp) }}" class="form-control border-0 bg-light py-2" placeholder="Nomor aktif">
                    </div>
                    <div class="col-12">
                        <div class="alert alert-danger bg-rose-soft border-0 rounded-4 p-3 d-flex align-items-center gap-3">
                            <i class="fa-solid fa-triangle-exclamation text-danger fs-4"></i>
                            <div class="grow">
                                <label class="form-label fw-800 text-danger small text-uppercase tracking-wider mb-1">Riwayat Alergi (Drug / Food Allergy)</label>
                                <textarea name="alergi" class="form-control border-0 bg-white py-2 text-danger fw-bold" rows="2" placeholder="Tuliskan 'TIDAK ADA' jika tidak ada riwayat alergi spesifik...">{{ old('alergi', $patient->alergi) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card-premium border-0 bg-white p-4 sticky-top" style="top: 100px;">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-floppy-disk fs-6"></i>
                    </div>
                    <h6 class="fw-800 text-slate mb-0">Simpan Perubahan</h6>
                </div>

                <div class="p-3 rounded-4 bg-teal-soft border-0 mb-4 text-center">
                    <div class="small fw-800 text-primary mb-2 text-uppercase">Informasi Sistem:</div>
                    <div class="small fw-bold text-slate opacity-75">Update terakhir pada: {{ $patient->updated_at->format('d/m/Y H:i') }}</div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-800 rounded-pill shadow-sm transition-bounce-hover mb-3">
                    <i class="fa-solid fa-user-check me-2"></i>SIMPAN PERUBAHAN
                </button>
                
                <a href="{{ route('pendaftaran.pasien.show', $patient) }}" class="btn btn-light border w-100 fw-800 py-3 rounded-pill">
                    BATALKAN
                </a>
            </div>
        </div>
    </div>
</form>

<style>
    .bg-teal-soft { background: #F0FDFA; }
    .bg-rose-soft { background: #FFF1F2; }
    .text-slate { color: #1E293B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.02); }
    .custom-radio .form-check-input { width: 1.25rem; height: 1.25rem; border: 2px solid #CBD5E1; }
    .custom-radio .form-check-input:checked { background-color: var(--simrs-primary); border-color: var(--simrs-primary); }
</style>
@endsection

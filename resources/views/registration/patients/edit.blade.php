@extends('layouts.app')

@section('title', 'Update Pasien')
@section('page-title', 'Master Data Pasien')
@section('page-subtitle', 'Pembaruan identitas demografi pasien ' . $patient->no_rkm_medis)

@section('content')
<form action="{{ route('pendaftaran.pasien.update', $patient) }}" method="POST">
    @csrf @method('PATCH')
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="simrs-card">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary">
                        <i class="fa-solid fa-user-pen"></i>
                        <span>Edit Formulir Demografi & Identitas</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="row g-4">
                        <!-- Identitas Utama -->
                        <div class="col-md-3">
                            <label class="form-label-custom">No. Rekam Medis</label>
                            <input value="{{ $patient->no_rkm_medis }}" class="form-control text-mono fw-800 bg-light" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom small text-uppercase">NIK (Sesuai KTP)</label>
                            <input name="nik" value="{{ old('nik', $patient->nik) }}" class="form-control text-mono fw-bold shadow-sm" placeholder="32xxxxxxxxxxxxxx" required maxlength="20">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom small text-uppercase">No. Kartu BPJS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-shield-halved text-muted small"></i></span>
                                <input name="no_bpjs" value="{{ old('no_bpjs', $patient->no_bpjs) }}" class="form-control border-start-0 text-mono" placeholder="0001xxxxxxxx">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom small text-uppercase">No. Telepon / WhatsApp</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-phone text-muted small"></i></span>
                                <input name="no_telp_pasien" value="{{ old('no_telp_pasien', $patient->no_telp_pasien) }}" class="form-control border-start-0" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="col-md-6 border-top pt-3">
                            <label class="form-label-custom">Nama Lengkap Pasien</label>
                            <input name="nama_pasien" value="{{ old('nama_pasien', $patient->nama_pasien) }}" class="form-control form-control-lg fw-800 text-simrs-gray-900 shadow-sm" placeholder="Masukkan nama sesuai KTP" required>
                        </div>
                        <div class="col-md-3 border-top pt-3">
                            <label class="form-label-custom">Jenis Kelamin</label>
                            <div class="d-flex gap-3 pt-1">
                                <div class="form-check custom-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" id="jkL" @checked(old('jenis_kelamin', $patient->jenis_kelamin) === 'L')>
                                    <label class="form-check-label fw-600" for="jkL">Laki-laki</label>
                                </div>
                                <div class="form-check custom-check">
                                    <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" id="jkP" @checked(old('jenis_kelamin', $patient->jenis_kelamin) === 'P')>
                                    <label class="form-check-label fw-600" for="jkP">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 border-top pt-3">
                            <label class="form-label-custom">Golongan Darah</label>
                            <select name="golongan_darah" class="form-select">
                                <option value="">Tidak Tahu / -</option>
                                @foreach(['A','B','AB','O'] as $blood)
                                    <option value="{{ $blood }}" @selected(old('golongan_darah', $patient->golongan_darah) === $blood)>{{ $blood }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label-custom">Tempat Lahir</label>
                            <input name="tempat_lahir" value="{{ old('tempat_lahir', $patient->tempat_lahir) }}" class="form-control" placeholder="Kota lahir">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $patient->tgl_lahir?->format('Y-m-d')) }}" class="form-control shadow-sm" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Agama</label>
                            <select name="agama" class="form-select">
                                @foreach(['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agm)
                                    <option value="{{ $agm }}" @selected(old('agama', $patient->agama) === $agm)>{{ $agm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label-custom">Pendidikan Terakhir</label>
                            <input name="pendidikan" value="{{ old('pendidikan', $patient->pendidikan) }}" class="form-control" placeholder="Contoh: SMA / S1">
                        </div>

                        <!-- Data Alamat -->
                        <div class="col-12 border-top pt-3">
                            <label class="form-label-custom"><i class="fa-solid fa-map-location-dot me-1"></i> Alamat Domisili Sesuai KTP</label>
                            <textarea name="alamat_lengkap" class="form-control mb-3" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW..." required>{{ old('alamat_lengkap', $patient->alamat_lengkap) }}</textarea>
                            <div class="row g-3">
                                <div class="col-md-3"><label class="form-label-custom small text-muted">Kelurahan</label><input name="kelurahan" value="{{ old('kelurahan', $patient->kelurahan) }}" class="form-control form-control-sm"></div>
                                <div class="col-md-3"><label class="form-label-custom small text-muted">Kecamatan</label><input name="kecamatan" value="{{ old('kecamatan', $patient->kecamatan) }}" class="form-control form-control-sm"></div>
                                <div class="col-md-3"><label class="form-label-custom small text-muted">Kota/Kabupaten</label><input name="kota" value="{{ old('kota', $patient->kota) }}" class="form-control form-control-sm"></div>
                                <div class="col-md-3"><label class="form-label-custom small text-muted">Provinsi</label><input name="provinsi" value="{{ old('provinsi', $patient->provinsi) }}" class="form-control form-control-sm"></div>
                            </div>
                        </div>

                        <!-- Data Tambahan -->
                        <div class="col-md-6 border-top pt-3">
                            <label class="form-label-custom">Nama Kontak Darurat / Keluarga</label>
                            <input name="kontak_darurat_nama" value="{{ old('kontak_darurat_nama', $patient->kontak_darurat_nama) }}" class="form-control" placeholder="Nama penanggung jawab">
                        </div>
                        <div class="col-md-6 border-top pt-3">
                            <label class="form-label-custom">Telepon Kontak Darurat</label>
                            <input name="kontak_darurat_telp" value="{{ old('kontak_darurat_telp', $patient->kontak_darurat_telp) }}" class="form-control" placeholder="Nomor yang bisa dihubungi">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom text-danger fw-800"><i class="fa-solid fa-triangle-exclamation"></i> Riwayat Alergi Obat / Makanan</label>
                            <textarea name="alergi" class="form-control border-danger-subtle" rows="2" placeholder="Tuliskan 'Tidak Ada' jika tidak memiliki alergi spesifik...">{{ old('alergi', $patient->alergi) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="simrs-card sticky-top" style="top: 80px; z-index: 100;">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary small">
                        <i class="fa-solid fa-floppy-disk"></i>
                        <span>Konfirmasi Perubahan</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <button class="btn btn-simrs-primary w-100 py-3 fw-800 shadow-sm border-0 mb-3">
                        <i class="fa-solid fa-user-check me-2"></i>SIMPAN PERUBAHAN
                    </button>
                    
                    <a href="{{ route('pendaftaran.pasien.show', $patient) }}" class="btn btn-simrs-outline w-100 fw-bold border-0 text-muted">
                        <i class="fa-solid fa-xmark me-2"></i>Batal & Kembali
                    </a>
                </div>
                <div class="simrs-card-body bg-light border-top p-3 text-center">
                    <div class="small text-muted">Pastikan data telah diverifikasi kembali.</div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

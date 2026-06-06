@extends('layouts.app')

@section('title', 'Update Pasien')
@section('page-title', 'Master Data Pasien')
@section('page-subtitle', 'Pembaruan identitas demografi pasien ' . $patient->no_rkm_medis)

@section('content')
<form action="{{ route('pendaftaran.pasien.update', $patient) }}" method="POST" class="needs-validation" novalidate>
    @csrf @method('PATCH')
    <div class="row g-4">
        <div class="col-xl-9">
            <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
                <div class="card-header bg-white border-bottom p-4">
                    <div class="d-flex align-items-center gap-3">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                            <i class="fa-solid fa-user-pen fs-6"></i>
                        </div>
                        <h5 class="fw-bold mb-0 text-dark">Edit Formulir Demografi & Identitas</h5>
                    </div>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Identitas Utama -->
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">No. Rekam Medis</label>
                            <input value="{{ $patient->no_rkm_medis }}" class="form-control font-monospace fw-bold bg-light border-light-subtle shadow-none" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">NIK (Sesuai KTP)</label>
                            <input name="nik" value="{{ old('nik', $patient->nik) }}" class="form-control font-monospace fw-bold shadow-none" placeholder="32xxxxxxxxxxxxxx" required maxlength="20">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">No. Kartu BPJS</label>
                            <div class="input-group shadow-none">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-shield-halved"></i></span>
                                <input name="no_bpjs" value="{{ old('no_bpjs', $patient->no_bpjs) }}" class="form-control border-start-0 font-monospace shadow-none" placeholder="0001xxxxxxxx">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">No. Telepon / WhatsApp</label>
                            <div class="input-group shadow-none">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-phone"></i></span>
                                <input name="no_telp_pasien" value="{{ old('no_telp_pasien', $patient->no_telp_pasien) }}" class="form-control border-start-0 shadow-none" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="col-12"><hr class="border-secondary opacity-10 my-1"></div>

                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Nama Lengkap Pasien</label>
                            <input name="nama_pasien" value="{{ old('nama_pasien', $patient->nama_pasien) }}" class="form-control form-control-lg fw-bold text-dark shadow-none" placeholder="Masukkan nama sesuai KTP" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Jenis Kelamin</label>
                            <div class="d-flex gap-3 pt-2">
                                <div class="form-check custom-check">
                                    <input class="form-check-input shadow-none" type="radio" name="jenis_kelamin" value="L" id="jkL" @checked(old('jenis_kelamin', $patient->jenis_kelamin) === 'L')>
                                    <label class="form-check-label fw-semibold" for="jkL">Laki-laki</label>
                                </div>
                                <div class="form-check custom-check">
                                    <input class="form-check-input shadow-none" type="radio" name="jenis_kelamin" value="P" id="jkP" @checked(old('jenis_kelamin', $patient->jenis_kelamin) === 'P')>
                                    <label class="form-check-label fw-semibold" for="jkP">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Golongan Darah</label>
                            <select name="golongan_darah" class="form-select shadow-none fw-semibold">
                                <option value="">Tidak Tahu / -</option>
                                @foreach(['A','B','AB','O'] as $blood)
                                    <option value="{{ $blood }}" @selected(old('golongan_darah', $patient->golongan_darah) === $blood)>{{ $blood }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Tempat Lahir</label>
                            <input name="tempat_lahir" value="{{ old('tempat_lahir', $patient->tempat_lahir) }}" class="form-control shadow-none" placeholder="Kota lahir">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir', $patient->tgl_lahir?->format('Y-m-d')) }}" class="form-control shadow-none fw-medium" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Agama</label>
                            <select name="agama" class="form-select shadow-none">
                                @foreach(['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agm)
                                    <option value="{{ $agm }}" @selected(old('agama', $patient->agama) === $agm)>{{ $agm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Pendidikan Terakhir</label>
                            <input name="pendidikan" value="{{ old('pendidikan', $patient->pendidikan) }}" class="form-control shadow-none" placeholder="Contoh: SMA / S1">
                        </div>

                        <!-- Data Alamat -->
                        <div class="col-12 border-top pt-4">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-3"><i class="fa-solid fa-map-location-dot me-2"></i>Alamat Domisili Sesuai KTP</label>
                            <textarea name="alamat_lengkap" class="form-control mb-3 shadow-none" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW..." required>{{ old('alamat_lengkap', $patient->alamat_lengkap) }}</textarea>
                            <div class="row g-3">
                                <div class="col-md-3"><label class="text-muted fw-semibold small mb-1">Kelurahan</label><input name="kelurahan" value="{{ old('kelurahan', $patient->kelurahan) }}" class="form-control form-control-sm shadow-none bg-light"></div>
                                <div class="col-md-3"><label class="text-muted fw-semibold small mb-1">Kecamatan</label><input name="kecamatan" value="{{ old('kecamatan', $patient->kecamatan) }}" class="form-control form-control-sm shadow-none bg-light"></div>
                                <div class="col-md-3"><label class="text-muted fw-semibold small mb-1">Kota/Kabupaten</label><input name="kota" value="{{ old('kota', $patient->kota) }}" class="form-control form-control-sm shadow-none bg-light"></div>
                                <div class="col-md-3"><label class="text-muted fw-semibold small mb-1">Provinsi</label><input name="provinsi" value="{{ old('provinsi', $patient->provinsi) }}" class="form-control form-control-sm shadow-none bg-light"></div>
                            </div>
                        </div>

                        <!-- Data Tambahan -->
                        <div class="col-12"><hr class="border-secondary opacity-10 my-1"></div>
                        
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Nama Kontak Darurat</label>
                            <input name="kontak_darurat_nama" value="{{ old('kontak_darurat_nama', $patient->kontak_darurat_nama) }}" class="form-control shadow-none" placeholder="Nama penanggung jawab">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-bold small text-uppercase mb-2">Telepon Kontak Darurat</label>
                            <input name="kontak_darurat_telp" value="{{ old('kontak_darurat_telp', $patient->kontak_darurat_telp) }}" class="form-control shadow-none" placeholder="Nomor yang bisa dihubungi">
                        </div>
                        <div class="col-12">
                            <label class="form-label text-danger fw-bold small text-uppercase mb-2"><i class="fa-solid fa-triangle-exclamation me-1"></i> Riwayat Alergi Obat / Makanan</label>
                            <textarea name="alergi" class="form-control border-danger-subtle bg-danger bg-opacity-10 shadow-none text-danger fw-medium" rows="2" placeholder="Tuliskan 'Tidak Ada' jika tidak memiliki alergi spesifik...">{{ old('alergi', $patient->alergi) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 90px; z-index: 10;">
                <div class="card-header bg-white border-bottom p-4 d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-floppy-disk fs-6"></i>
                    </div>
                    <h5 class="fw-bold mb-0 text-dark">Konfirmasi</h5>
                </div>
                <div class="card-body p-4">
                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-pill mb-3 transition-hover" style="background: linear-gradient(135deg, var(--simrs-primary), var(--simrs-primary-light)); border: none;">
                        <i class="fa-solid fa-user-check me-2"></i>SIMPAN PERUBAHAN
                    </button>
                    
                    <a href="{{ route('pendaftaran.pasien.show', $patient) }}" class="btn btn-light btn-lg w-100 fw-bold border border-light-subtle text-muted rounded-pill transition-hover">
                        <i class="fa-solid fa-xmark me-2"></i>Batal & Kembali
                    </a>
                </div>
                <div class="card-footer bg-light bg-opacity-50 border-0 p-3 text-center rounded-bottom-4">
                    <div class="small text-muted fw-semibold">Pastikan data diverifikasi ulang.</div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important; }
    .form-control:focus, .form-select:focus { border-color: var(--simrs-primary); box-shadow: 0 0 0 0.25rem rgba(11, 100, 119, 0.1) !important; }
</style>
@endsection

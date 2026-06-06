@extends('layouts.app')

@section('title', 'Registrasi Pasien Baru')
@section('page-title', 'Master Registry Pasien')
@section('page-subtitle', 'Input identitas demografi pusat sesuai data kependudukan (Dukcapil)')

@section('content')
<form action="{{ route('pendaftaran.pasien.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-9 d-flex flex-column gap-4">
            <!-- Identitas Utama -->
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-id-card fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Identitas Utama Pasien</h5>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">No. Rekam Medis</label>
                            <input name="no_rkm_medis" value="{{ old('no_rkm_medis', $noRm) }}" class="form-control border-light bg-light fw-bold text-primary font-monospace py-2" readonly>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">NIK (Sesuai KTP)</label>
                            <input name="nik" value="{{ old('nik') }}" class="form-control border-light bg-light fw-medium font-monospace py-2 shadow-none focus-ring-0" placeholder="32xxxxxxxxxxxxxx" required maxlength="20">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">No. Kartu BPJS</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light border-end-0 text-muted"><i class="fa-solid fa-shield-halved"></i></span>
                                <input name="no_bpjs" value="{{ old('no_bpjs') }}" class="form-control border-light border-start-0 ps-0 bg-light font-monospace py-2 shadow-none focus-ring-0" placeholder="0001xxxxxxxx">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">WhatsApp / Telepon</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-light border-end-0 text-muted"><i class="fa-solid fa-phone"></i></span>
                                <input name="no_telp_pasien" value="{{ old('no_telp_pasien') }}" class="form-control border-light border-start-0 ps-0 bg-light py-2 shadow-none focus-ring-0" placeholder="08xxxxxxxxxx">
                            </div>
                        </div>

                        <div class="col-md-6 border-top border-light pt-4">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Nama Lengkap Pasien</label>
                            <input name="nama_pasien" value="{{ old('nama_pasien') }}" class="form-control border-light bg-light py-3 fw-bold text-dark fs-5 shadow-none focus-ring-0" placeholder="Masukkan nama sesuai kartu identitas" required>
                        </div>
                        <div class="col-md-3 border-top border-light pt-4">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Jenis Kelamin</label>
                            <div class="d-flex gap-4 pt-2">
                                <div class="form-check custom-radio">
                                    <input class="form-check-input focus-ring-0" type="radio" name="jenis_kelamin" value="L" id="jkL" @checked(old('jenis_kelamin') === 'L' || !old('jenis_kelamin'))>
                                    <label class="form-check-label fw-medium text-dark" for="jkL">Laki-laki</label>
                                </div>
                                <div class="form-check custom-radio">
                                    <input class="form-check-input focus-ring-0" type="radio" name="jenis_kelamin" value="P" id="jkP" @checked(old('jenis_kelamin') === 'P')>
                                    <label class="form-check-label fw-medium text-dark" for="jkP">Perempuan</label>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 border-top border-light pt-4">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Golongan Darah</label>
                            <select name="golongan_darah" class="form-select border-light bg-light py-2 fw-medium shadow-none focus-ring-0">
                                <option value="">Tidak Tahu / -</option>
                                @foreach(['A','B','AB','O'] as $blood)
                                    <option value="{{ $blood }}" @selected(old('golongan_darah') === $blood)>{{ $blood }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Tempat Lahir</label>
                            <input name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="form-control border-light bg-light py-2 shadow-none focus-ring-0" placeholder="Kota lahir">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Tanggal Lahir</label>
                            <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}" class="form-control border-light bg-light py-2 fw-medium shadow-none focus-ring-0" required>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Agama</label>
                            <select name="agama" class="form-select border-light bg-light py-2 shadow-none focus-ring-0">
                                @foreach(['Islam','Kristen','Katolik','Hindu','Budha','Konghucu'] as $agm)
                                    <option value="{{ $agm }}" @selected(old('agama', 'Islam') === $agm)>{{ $agm }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Pendidikan</label>
                            <input name="pendidikan" value="{{ old('pendidikan') }}" class="form-control border-light bg-light py-2 shadow-none focus-ring-0" placeholder="SMA / S1 / dst">
                        </div>
                    </div>

                    <!-- Data Domisili & Kontak -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-map-location-dot fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Data Domisili & Kontak</h5>
                    </div>

                    <div class="row g-4 mb-4">
                        <div class="col-12">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Alamat Lengkap (Sesuai KTP)</label>
                            <textarea name="alamat_lengkap" class="form-control border-light bg-light py-2 shadow-none focus-ring-0" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW..." required>{{ old('alamat_lengkap') }}</textarea>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted">Kelurahan</label>
                            <input name="kelurahan" value="{{ old('kelurahan') }}" class="form-control border-light bg-light py-2 shadow-none focus-ring-0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted">Kecamatan</label>
                            <input name="kecamatan" value="{{ old('kecamatan') }}" class="form-control border-light bg-light py-2 shadow-none focus-ring-0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted">Kota / Kab</label>
                            <input name="kota" value="{{ old('kota') }}" class="form-control border-light bg-light py-2 shadow-none focus-ring-0">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold small text-muted">Provinsi</label>
                            <input name="provinsi" value="{{ old('provinsi') }}" class="form-control border-light bg-light py-2 shadow-none focus-ring-0">
                        </div>
                    </div>

                    <!-- Kontak Darurat & Alergi -->
                    <div class="row g-4 border-top border-light pt-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Kontak Darurat / Penanggung Jawab</label>
                            <input name="kontak_darurat_nama" value="{{ old('kontak_darurat_nama') }}" class="form-control border-light bg-light py-2 shadow-none focus-ring-0" placeholder="Nama lengkap keluarga">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Telepon Darurat</label>
                            <input name="kontak_darurat_telp" value="{{ old('kontak_darurat_telp') }}" class="form-control border-light bg-light py-2 shadow-none focus-ring-0" placeholder="Nomor aktif">
                        </div>
                        <div class="col-12">
                            <div class="alert alert-danger bg-danger bg-opacity-10 border-0 rounded-4 p-4 d-flex gap-3 mb-0">
                                <i class="fa-solid fa-triangle-exclamation text-danger fs-4 flex-shrink-0 mt-1"></i>
                                <div class="w-100">
                                    <label class="form-label text-danger fw-bold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Riwayat Alergi (Drug / Food Allergy)</label>
                                    <textarea name="alergi" class="form-control border-0 bg-white shadow-none py-2 text-danger fw-medium" rows="2" placeholder="Tuliskan 'TIDAK ADA' jika tidak ada riwayat alergi spesifik...">{{ old('alergi') }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-floppy-disk fs-5"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Aksi Simpan</h6>
                    </div>

                    <div class="p-3 rounded-3 bg-info bg-opacity-10 border-0 mb-4 text-center">
                        <div class="small fw-bold text-info mb-2 text-uppercase" style="letter-spacing: 0.5px;">Checklist Akhir:</div>
                        <div class="d-flex flex-column gap-2 text-start px-2">
                            <div class="small fw-medium text-dark opacity-75"><i class="fa-solid fa-check-circle me-2 text-success"></i> NIK 16 Digit</div>
                            <div class="small fw-medium text-dark opacity-75"><i class="fa-solid fa-check-circle me-2 text-success"></i> Nama Sesuai KTP</div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm transition-hover mb-3">
                        <i class="fa-solid fa-user-plus me-2"></i>SIMPAN DATA PASIEN
                    </button>
                    
                    <a href="{{ route('pendaftaran.pasien.index') }}" class="btn btn-light border w-100 fw-bold py-3 rounded-pill text-muted hover-bg-gray transition-hover">
                        BATALKAN
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa; }
    .transition-hover { transition: transform 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); }
    .custom-radio .form-check-input { width: 1.25rem; height: 1.25rem; border: 2px solid #cbd5e1; cursor: pointer; }
    .custom-radio .form-check-input:checked { background-color: #3b82f6; border-color: #3b82f6; }
    .custom-radio .form-check-label { cursor: pointer; }
</style>
@endsection

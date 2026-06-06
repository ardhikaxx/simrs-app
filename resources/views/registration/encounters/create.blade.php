@extends('layouts.app')

@section('title', 'Registrasi Kunjungan')
@section('page-title', 'Admission & Patient Intake')
@section('page-subtitle', 'Buat data kunjungan baru (encounter) dan alokasi unit pelayanan')

@section('content')
<form action="{{ route('pendaftaran.kunjungan.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-8 d-flex flex-column gap-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-user-check fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Identifikasi Pasien</h5>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Cari Pasien Terdaftar</label>
                        <select name="patient_id" class="form-select border-light bg-light py-2 select2-init shadow-none" required>
                            <option value="">Ketik Nama, No. RM, atau NIK untuk mencari...</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" @selected((int) old('patient_id', $selectedPatient) === $patient->id)>
                                    {{ $patient->no_rkm_medis }} — {{ $patient->nama_pasien }} (NIK: {{ $patient->nik }})
                                </option>
                            @endforeach
                        </select>
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <span class="small text-muted fw-medium">Pasien belum terdaftar?</span>
                            <a href="{{ route('pendaftaran.pasien.create') }}" class="small fw-bold text-primary text-decoration-none">
                                <i class="fa-solid fa-user-plus me-1"></i>Daftarkan Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                            <i class="fa-solid fa-clipboard-list fs-5"></i>
                        </div>
                        <h5 class="fw-bold text-dark mb-0">Detail Kunjungan & Penjaminan</h5>
                    </div>

                    <div class="row g-4">
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Tipe Pelayanan</label>
                            <select name="jenis_kunjungan" class="form-select border-light bg-light py-2 fw-medium shadow-none focus-ring-0" required>
                                <option value="rawat_jalan">Rawat Jalan</option>
                                <option value="igd">IGD (Emergency)</option>
                                <option value="rawat_inap">Rawat Inap</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Metode Bayar</label>
                            <select name="cara_bayar" class="form-select border-light bg-light py-2 fw-medium shadow-none focus-ring-0" required>
                                <option value="umum">Mandiri (Umum)</option>
                                <option value="bpjs">BPJS Kesehatan</option>
                                <option value="asuransi">Asuransi / Rekanan</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Cara Masuk</label>
                            <select name="cara_masuk" class="form-select border-light bg-light py-2 fw-medium shadow-none focus-ring-0">
                                <option value="datang_sendiri">Datang Sendiri</option>
                                <option value="rujukan_puskesmas">Rujukan Puskesmas</option>
                                <option value="rujukan_rs">Rujukan RS Lain</option>
                                <option value="ambulans">Ambulans / EMS</option>
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Unit / Klinik Tujuan</label>
                            <select name="department_id" class="form-select border-light bg-light py-2 select2-init shadow-none" required>
                                <option value="">Pilih Departemen...</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Dokter DPJP</label>
                            <select name="doctor_id" class="form-select border-light bg-light py-2 select2-init shadow-none">
                                <option value="">Pilih Praktisi...</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->display_name }} ({{ $doctor->department?->nama_depart }})</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <div class="p-4 rounded-4 bg-light border border-dashed">
                                <h6 class="fw-bold text-dark mb-3 small text-uppercase" style="letter-spacing: 0.5px;">Detail Rawat Inap & Rujukan (Opsional)</h6>
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-muted">Kelas Rawat</label>
                                        <select name="kelas_rawat" class="form-select border-0 shadow-none focus-ring-0 bg-white">
                                            <option value="">- Non Inap -</option>
                                            <option>Kelas I</option><option>Kelas II</option><option>Kelas III</option><option>VIP</option><option>VVIP</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label fw-semibold small text-muted">Kamar</label>
                                        <input name="kamar" class="form-control border-0 shadow-none focus-ring-0 bg-white" placeholder="Nama Kamar">
                                    </div>
                                    <div class="col-md-2">
                                        <label class="form-label fw-semibold small text-muted">Bed</label>
                                        <input name="bed" class="form-control border-0 shadow-none focus-ring-0 bg-white text-center" placeholder="00">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold small text-muted">Asal Rujukan</label>
                                        <input name="rujukan_dari" class="form-control border-0 shadow-none focus-ring-0 bg-white" placeholder="Nama Faskes">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label text-muted fw-semibold small mb-2 text-uppercase" style="letter-spacing: 0.5px;">Keluhan Utama / Alasan Kunjungan</label>
                            <textarea name="keluhan_awal" class="form-control border-light bg-light py-2 shadow-none focus-ring-0" rows="3" placeholder="Tuliskan keluhan singkat pasien untuk rujukan asesmen perawat..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 sticky-top" style="top: 90px;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px;">
                            <i class="fa-solid fa-shield-check fs-5"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-0">Protokol Registrasi</h6>
                    </div>

                    <div class="p-3 rounded-3 bg-info bg-opacity-10 border-0 mb-4">
                        <div class="d-flex gap-3 align-items-start">
                            <i class="fa-solid fa-print text-info mt-1"></i>
                            <div>
                                <div class="small fw-bold text-info mb-1">DOKUMEN OUTPUT:</div>
                                <ul class="list-unstyled mb-0 small text-dark fw-medium opacity-75">
                                    <li class="mb-1">• Lembar Tracer RM</li>
                                    <li class="mb-1">• Nomor Antrean Unit</li>
                                    <li>• Surat Bukti Registrasi</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm transition-hover mb-3">
                        <i class="fa-solid fa-calendar-check me-2"></i>SELESAIKAN REGISTRASI
                    </button>
                    
                    <a href="{{ route('pendaftaran.antrian') }}" class="btn btn-light border w-100 fw-bold py-3 rounded-pill text-muted hover-bg-gray transition-hover">
                        BATALKAN PROSES
                    </a>

                    <div class="mt-4 pt-4 border-top border-light text-center">
                        <div class="small text-muted fw-bold">Admission Intelligence v1.2</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Mohon verifikasi sediaan jadwal dokter</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .border-dashed { border-style: dashed !important; border-color: #cbd5e1 !important; }
    .hover-bg-gray:hover { background-color: #f8f9fa; }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); }
</style>
@endsection

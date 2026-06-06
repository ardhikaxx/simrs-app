@extends('layouts.app')

@section('title', 'Registrasi Kunjungan')
@section('page-title', 'Admission & Patient Intake')
@section('page-subtitle', 'Buat data kunjungan baru (encounter) dan alokasi unit pelayanan')

@section('content')
<form action="{{ route('pendaftaran.kunjungan.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="card-premium border-0 bg-white p-4 mb-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-user-check fs-6"></i>
                    </div>
                    <h5 class="fw-800 text-slate mb-0">Identifikasi Pasien</h5>
                </div>

                <div class="mb-5">
                    <label class="form-label fw-800 text-slate small text-uppercase tracking-wider mb-2">Cari Pasien Terdaftar</label>
                    <select name="patient_id" class="form-select border-0 bg-light py-3 select2-init" required>
                        <option value="">Ketik Nama, No. RM, atau NIK untuk mencari...</option>
                        @foreach($patients as $patient)
                            <option value="{{ $patient->id }}" @selected((int) old('patient_id', $selectedPatient) === $patient->id)>
                                {{ $patient->no_rkm_medis }} — {{ $patient->nama_pasien }} (NIK: {{ $patient->nik }})
                            </option>
                        @endforeach
                    </select>
                    <div class="mt-2 d-flex align-items-center gap-2">
                        <span class="small text-muted fw-medium">Pasien belum terdaftar?</span>
                        <a href="{{ route('pendaftaran.pasien.create') }}" class="small fw-800 text-primary text-decoration-none">
                            <i class="fa-solid fa-user-plus me-1"></i>DAFTARKAN BARU
                        </a>
                    </div>
                </div>

                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-clipboard-list fs-6"></i>
                    </div>
                    <h5 class="fw-800 text-slate mb-0">Detail Kunjungan & Penjaminan</h5>
                </div>

                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Tipe Pelayanan</label>
                        <select name="jenis_kunjungan" class="form-select border-0 bg-light py-3 fw-bold" required>
                            <option value="rawat_jalan">RAWAT JALAN</option>
                            <option value="igd">IGD (EMERGENCY)</option>
                            <option value="rawat_inap">RAWAT INAP</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Metode Bayar</label>
                        <select name="cara_bayar" class="form-select border-0 bg-light py-3 fw-bold" required>
                            <option value="umum">MANDIRI (UMUM)</option>
                            <option value="bpjs">BPJS KESEHATAN</option>
                            <option value="asuransi">ASURANSI / REKANAN</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Cara Masuk</label>
                        <select name="cara_masuk" class="form-select border-0 bg-light py-3 fw-bold">
                            <option value="datang_sendiri">DATANG SENDIRI</option>
                            <option value="rujukan_puskesmas">RUJUKAN PUSKESMAS</option>
                            <option value="rujukan_rs">RUJUKAN RS LAIN</option>
                            <option value="ambulans">AMBULANS / EMS</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Unit / Klinik Tujuan</label>
                        <select name="department_id" class="form-select border-0 bg-light py-3 select2-init" required>
                            <option value="">Pilih Departemen...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Dokter DPJP</label>
                        <select name="doctor_id" class="form-select border-0 bg-light py-3 select2-init">
                            <option value="">Pilih Praktisi...</option>
                            @foreach($doctors as $doctor)
                                <option value="{{ $doctor->id }}">{{ $doctor->display_name }} ({{ $doctor->department?->nama_depart }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="p-4 rounded-4 bg-light bg-opacity-50 border border-dashed">
                            <h6 class="fw-800 text-slate mb-3 small text-uppercase tracking-widest">Detail Rawat Inap & Rujukan (Opsional)</h6>
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">Kelas Rawat</label>
                                    <select name="kelas_rawat" class="form-select border-0 bg-white">
                                        <option value="">- Non Inap -</option>
                                        <option>Kelas I</option><option>Kelas II</option><option>Kelas III</option><option>VIP</option><option>VVIP</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label fw-bold small text-muted">Kamar</label>
                                    <input name="kamar" class="form-control border-0 bg-white" placeholder="Nama Kamar">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-bold small text-muted">Bed</label>
                                    <input name="bed" class="form-control border-0 bg-white text-center" placeholder="00">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label fw-bold small text-muted">Asal Rujukan</label>
                                    <input name="rujukan_dari" class="form-control border-0 bg-white" placeholder="Nama Faskes">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-800 text-slate small text-uppercase tracking-wider">Keluhan Utama / Alasan Kunjungan</label>
                        <textarea name="keluhan_awal" class="form-control border-0 bg-light py-3" rows="3" placeholder="Tuliskan keluhan singkat pasien untuk rujukan asesmen perawat..."></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card-premium border-0 bg-white p-4 sticky-top" style="top: 100px;">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px;">
                        <i class="fa-solid fa-shield-check fs-6"></i>
                    </div>
                    <h6 class="fw-800 text-slate mb-0">Protokol Registrasi</h6>
                </div>

                <div class="p-3 rounded-4 bg-teal-soft border-0 mb-4">
                    <div class="d-flex gap-3 align-items-start">
                        <i class="fa-solid fa-print text-primary mt-1"></i>
                        <div>
                            <div class="small fw-800 text-primary mb-1">DOKUMEN OUTPUT:</div>
                            <ul class="list-unstyled mb-0 small text-slate fw-medium opacity-75">
                                <li class="mb-1">• Lembar Tracer RM</li>
                                <li class="mb-1">• Nomor Antrean Unit</li>
                                <li>• Surat Bukti Registrasi</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3 fw-800 rounded-pill shadow-sm transition-bounce-hover mb-3">
                    <i class="fa-solid fa-calendar-check me-2"></i>SELESAIKAN REGISTRASI
                </button>
                
                <a href="{{ route('pendaftaran.antrian') }}" class="btn btn-light border w-100 fw-800 py-3 rounded-pill">
                    BATALKAN PROSES
                </a>

                <div class="mt-4 pt-4 border-top text-center">
                    <div class="small text-muted fw-bold">Admission Intelligence v1.2</div>
                    <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Mohon verifikasi sediaan jadwal dokter</div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    .bg-teal-soft { background: #F0FDFA; }
    .text-slate { color: #1E293B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.02); }
</style>
@endsection

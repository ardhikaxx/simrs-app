@extends('layouts.app')

@section('title', 'Registrasi Kunjungan')
@section('page-title', 'Registrasi Pelayanan Pasien')
@section('page-subtitle', 'Buat data encounter dan nomor antrean unit tujuan')

@section('content')
<form action="{{ route('pendaftaran.kunjungan.store') }}" method="POST">
    @csrf
    <div class="row g-4">
        <div class="col-xl-8">
            <div class="simrs-card">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary">
                        <i class="fa-solid fa-hospital-user"></i>
                        <span>Data Registrasi Kunjungan (Encounter)</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="row g-4">
                        <!-- Pilih Pasien -->
                        <div class="col-md-12">
                            <label class="form-label-custom">Pilih Pasien Terdaftar</label>
                            <select name="patient_id" class="form-select select2-init" required>
                                <option value="">Cari berdasarkan Nama atau No. RM...</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient->id }}" @selected((int) old('patient_id', $selectedPatient) === $patient->id)>
                                        {{ $patient->no_rkm_medis }} - {{ $patient->nama_pasien }} ({{ $patient->nik }})
                                    </option>
                                @endforeach
                            </select>
                            <div class="form-text small">Pasien tidak ditemukan? <a href="{{ route('pendaftaran.pasien.create') }}" class="text-simrs-primary fw-bold">Daftarkan Pasien Baru</a></div>
                        </div>

                        <!-- Tipe Kunjungan -->
                        <div class="col-md-4 border-top pt-3">
                            <label class="form-label-custom">Jenis Pelayanan</label>
                            <select name="jenis_kunjungan" class="form-select fw-bold shadow-sm" required>
                                <option value="rawat_jalan">Rawat Jalan (Poliklinik)</option>
                                <option value="igd">IGD (Gawat Darurat)</option>
                                <option value="rawat_inap">Rawat Inap</option>
                            </select>
                        </div>
                        <div class="col-md-4 border-top pt-3">
                            <label class="form-label-custom">Metode Penjaminan / Bayar</label>
                            <select name="cara_bayar" class="form-select fw-bold shadow-sm" required>
                                <option value="umum">UMUM (Mandiri)</option>
                                <option value="bpjs">BPJS Kesehatan</option>
                                <option value="asuransi">Asuransi Swasta / Rekanan</option>
                            </select>
                        </div>
                        <div class="col-md-4 border-top pt-3">
                            <label class="form-label-custom">Cara Masuk</label>
                            <select name="cara_masuk" class="form-select shadow-sm">
                                <option value="datang_sendiri">Datang Sendiri</option>
                                <option value="rujukan_puskesmas">Rujukan Puskesmas</option>
                                <option value="rujukan_rs">Rujukan RS Lain</option>
                                <option value="ambulans">Ambulans / EMS</option>
                            </select>
                        </div>

                        <!-- Unit & Dokter -->
                        <div class="col-md-6">
                            <label class="form-label-custom">Unit Pelayanan Tujuan</label>
                            <select name="department_id" class="form-select select2-init" required>
                                <option value="">Pilih Klinik / Bangsal...</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->kode_depart }} - {{ $department->nama_depart }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">Dokter Penanggung Jawab (DPJP)</label>
                            <select name="doctor_id" class="form-select select2-init">
                                <option value="">Pilih Dokter...</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">{{ $doctor->display_name }} ({{ $doctor->department?->nama_depart }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Detail Rawat Inap (Conditional) -->
                        <div class="col-12 border-top pt-3">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label-custom">Kelas Rawat</label>
                                    <select name="kelas_rawat" class="form-select form-select-sm">
                                        <option value="">- Non Inap -</option>
                                        <option>Kelas I</option><option>Kelas II</option><option>Kelas III</option><option>VIP</option><option>VVIP</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label-custom">Kamar / Bangsal</label>
                                    <input name="kamar" class="form-control form-control-sm" placeholder="Nama Kamar">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label-custom">Nomor Bed</label>
                                    <input name="bed" class="form-control form-control-sm" placeholder="Bed">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label-custom">Rujukan Dari (Nama RS/Faskes)</label>
                                    <input name="rujukan_dari" class="form-control form-control-sm" placeholder="Nama Faskes Perujuk">
                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <label class="form-label-custom">Keluhan Awal (Singkat)</label>
                            <textarea name="keluhan_awal" class="form-control shadow-sm" rows="3" placeholder="Tuliskan keluhan utama pasien saat ini untuk rujukan perawat..."></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="simrs-card sticky-top" style="top: 80px; z-index: 100;">
                <div class="simrs-card-header bg-white">
                    <div class="simrs-card-title text-simrs-primary small">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Validasi Kunjungan</span>
                    </div>
                </div>
                <div class="simrs-card-body">
                    <div class="p-3 rounded-3 bg-light border mb-4">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <div class="brand-icon shadow-none bg-primary text-white" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                <i class="fa-solid fa-print"></i>
                            </div>
                            <div class="small fw-800 text-simrs-gray-900">Output Registrasi:</div>
                        </div>
                        <ul class="list-unstyled mb-0 small opacity-75">
                            <li class="mb-1"><i class="fa-solid fa-caret-right me-1 text-primary"></i> Cetak Tracer RM Pasien</li>
                            <li class="mb-1"><i class="fa-solid fa-caret-right me-1 text-primary"></i> Cetak Nomor Antrean</li>
                            <li><i class="fa-solid fa-caret-right me-1 text-primary"></i> Terbitkan Invoice Billing</li>
                        </ul>
                    </div>

                    <button class="btn btn-simrs-primary w-100 py-3 fw-800 shadow-sm border-0 mb-3">
                        <i class="fa-solid fa-calendar-check me-2"></i>DAFTARKAN KUNJUNGAN
                    </button>
                    
                    <a href="{{ route('pendaftaran.antrian') }}" class="btn btn-simrs-outline w-100 fw-bold border-0 text-muted">
                        <i class="fa-solid fa-xmark me-2"></i>Batal & Kembali
                    </a>
                </div>
                <div class="simrs-card-body bg-light border-top p-3 text-center">
                    <div class="small text-muted">Pastikan dokter tujuan tersedia pada jadwal hari ini.</div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

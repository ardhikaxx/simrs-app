@extends('layouts.app')

@section('title', 'Profil Pasien')
@section('page-title', 'Detail Data Pasien')
@section('page-subtitle', $patient->nama_pasien . ' - ' . $patient->no_rkm_medis)

@section('content')
<div class="row g-4">
    <!-- Sidebar Profil -->
    <div class="col-xl-4">
        <div class="simrs-card mb-4 border-0 shadow-sm overflow-hidden">
            <div class="simrs-card-body bg-primary text-white text-center py-5 position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fa-solid fa-id-card fs-1" style="transform: rotate(15deg);"></i>
                </div>
                <div class="user-avatar-sm mx-auto mb-3 bg-white text-primary shadow-lg" style="width: 80px; height: 80px; font-size: 2rem;">
                    {{ strtoupper(substr($patient->nama_pasien, 0, 1)) }}
                </div>
                <h4 class="fw-800 mb-1">{{ $patient->nama_pasien }}</h4>
                <div class="text-mono small opacity-75 mb-3">No. RM: {{ $patient->no_rkm_medis }}</div>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge bg-white text-primary px-3 py-1">{{ $patient->age }} Tahun</span>
                    <span class="badge bg-white text-primary px-3 py-1">{{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
            </div>
            <div class="simrs-card-body bg-white">
                <div class="row g-3">
                    <div class="col-12 border-bottom pb-2">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">NIK (KTP)</div>
                        <div class="fw-800 text-simrs-gray-900">{{ $patient->nik }}</div>
                    </div>
                    <div class="col-12 border-bottom pb-2">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Nomor BPJS Kesehatan</div>
                        <div class="fw-800 text-simrs-primary">{{ $patient->no_bpjs ?: 'Tidak Terdaftar' }}</div>
                    </div>
                    <div class="col-6 border-bottom pb-2">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Golongan Darah</div>
                        <div class="fw-800 text-danger"><i class="fa-solid fa-droplet me-1"></i>{{ $patient->golongan_darah ?: '-' }}</div>
                    </div>
                    <div class="col-6 border-bottom pb-2">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Agama</div>
                        <div class="fw-800">{{ $patient->agama }}</div>
                    </div>
                    <div class="col-12 border-bottom pb-2">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Tempat, Tgl Lahir</div>
                        <div class="fw-800">{{ $patient->tempat_lahir }}, {{ $patient->tgl_lahir?->format('d/m/Y') }}</div>
                    </div>
                    <div class="col-12 border-bottom pb-2">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Telepon / Kontak</div>
                        <div class="fw-800"><i class="fa-solid fa-phone me-1 small opacity-50"></i>{{ $patient->no_telp_pasien ?: '-' }}</div>
                    </div>
                    <div class="col-12 border-bottom pb-2">
                        <div class="small text-muted fw-700 text-uppercase tracking-wider mb-1">Alamat Domisili</div>
                        <div class="small fw-600 lh-sm">{{ $patient->alamat_lengkap }}, {{ $patient->kelurahan }}, {{ $patient->kecamatan }}, {{ $patient->kota }}</div>
                    </div>
                </div>
                
                @if($patient->alergi)
                <div class="mt-4 p-3 rounded-3 bg-danger-subtle border border-danger-subtle">
                    <div class="small fw-800 text-danger mb-1 text-uppercase tracking-wider"><i class="fa-solid fa-triangle-exclamation"></i> Alert Alergi</div>
                    <div class="fw-700 text-danger lh-sm">{{ $patient->alergi }}</div>
                </div>
                @endif

                <div class="mt-4 d-grid gap-2">
                    <a href="{{ route('pendaftaran.kunjungan.create', ['patient_id' => $patient->id]) }}" class="btn btn-simrs-primary py-2 shadow-sm">
                        <i class="fa-solid fa-calendar-plus me-2"></i>Daftarkan Kunjungan
                    </a>
                    <button class="btn btn-simrs-outline py-2 border-0 shadow-none text-muted fw-bold">
                        <i class="fa-solid fa-user-pen me-2"></i>Update Profil Pasien
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten Utama: Riwayat Kunjungan -->
    <div class="col-xl-8">
        <div class="simrs-card">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title text-simrs-primary">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                    <span>Riwayat Kunjungan & Pelayanan</span>
                </div>
                <div class="small text-muted fw-normal">Total: {{ $patient->encounters->count() }} Kunjungan</div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">No. Registrasi</th>
                            <th>Waktu Masuk</th>
                            <th>Unit Pelayanan</th>
                            <th>Dokter DPJP</th>
                            <th>Cara Bayar</th>
                            <th class="text-center">Status</th>
                            <th class="pe-4 text-end">Invoice</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($patient->encounters->sortByDesc('waktu_masuk') as $encounter)
                        <tr>
                            <td class="ps-4">
                                <div class="text-mono fw-bold text-simrs-primary small">{{ $encounter->no_registrasi }}</div>
                            </td>
                            <td>
                                <div class="fw-600 small">{{ $encounter->waktu_masuk?->format('d/m/Y') }}</div>
                                <div class="small text-muted" style="font-size: 0.7rem;">{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                            </td>
                            <td>
                                <div class="small fw-700 text-simrs-gray-800">{{ $encounter->department?->nama_depart }}</div>
                                <div class="small text-muted" style="font-size: 0.65rem;">{{ strtoupper($encounter->jenis_kunjungan) }}</div>
                            </td>
                            <td>
                                <div class="small fw-600">{{ $encounter->doctor?->display_name ?? '-' }}</div>
                            </td>
                            <td>
                                <span class="badge bg-light text-simrs-secondary border border-simrs-gray-200 px-2 py-0" style="font-size: 0.65rem;">
                                    {{ strtoupper($encounter->cara_bayar) }}
                                </span>
                            </td>
                            <td class="text-center">
                                <span class="badge-status status-{{ str_replace('_','-',$encounter->status_encounter) }} shadow-none small">
                                    {{ str_replace('_',' ',ucfirst($encounter->status_encounter)) }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                @if($encounter->billingInvoice)
                                    <a href="{{ route('keuangan.invoice.show', $encounter->billingInvoice) }}" class="btn btn-sm btn-simrs-outline shadow-sm px-3">
                                        <i class="fa-solid fa-file-invoice-dollar me-1"></i>Billing
                                    </a>
                                @else
                                    <span class="text-muted small italic">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center py-5">
                                <i class="fa-solid fa-calendar-xmark fs-1 text-muted opacity-25 mb-3 d-block"></i>
                                <div class="text-muted">Pasien ini belum memiliki riwayat kunjungan.</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center bg-white p-3 rounded-3 border shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-info-subtle text-info" style="width: 38px; height: 38px;">
                    <i class="fa-solid fa-circle-info"></i>
                </div>
                <div class="small text-muted lh-sm">Data rekam medis ini bersifat rahasia. <br> Akses tercatat otomatis dalam log sistem.</div>
            </div>
            <a href="{{ route('pendaftaran.pasien.index') }}" class="btn btn-sm btn-simrs-outline px-4">
                <i class="fa-solid fa-arrow-left me-2"></i>Kembali ke Master Pasien
            </a>
        </div>
    </div>
</div>
@endsection

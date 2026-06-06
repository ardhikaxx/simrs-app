@extends('layouts.app')

@section('title', 'Profil Pasien')
@section('page-title', 'Patient 360 Intelligence')
@section('page-subtitle', 'Tinjauan komprehensif identitas, demografi, dan riwayat klinis pasien')

@section('content')
<div class="row g-4">
    <!-- Sidebar Profil -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="bg-dark p-5 text-center position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-10">
                    <i class="fa-solid fa-id-card" style="font-size: 5rem; transform: rotate(15deg);"></i>
                </div>
                <div class="mx-auto mb-4 bg-primary text-white shadow-sm border border-4 border-white border-opacity-10 rounded-circle d-flex align-items-center justify-content-center" style="width: 96px; height: 96px; font-size: 2.5rem; font-weight: 700;">
                    {{ strtoupper(substr($patient->nama_pasien, 0, 1)) }}
                </div>
                <h4 class="fw-bold text-white mb-1">{{ $patient->nama_pasien }}</h4>
                <div class="text-info small fw-semibold font-monospace mb-4" style="letter-spacing: 1px;">NO. RM: {{ $patient->no_rkm_medis }}</div>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill fw-medium small">{{ $patient->age }} TAHUN</span>
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-25 px-3 py-2 rounded-pill fw-medium small">{{ $patient->jenis_kelamin === 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</span>
                </div>
            </div>
            <div class="p-4 bg-white">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Identitas Legal (KTP/NIK)</div>
                        <div class="fw-bold text-dark fs-5 font-monospace">{{ $patient->nik }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Nomor Jaminan (BPJS)</div>
                        <div class="fw-bold text-primary fs-5 font-monospace">{{ $patient->no_bpjs ?: 'TIDAK TERDAFTAR' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Gol. Darah</div>
                        <div class="fw-bold text-danger"><i class="fa-solid fa-droplet me-2"></i>{{ $patient->golongan_darah ?: '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Agama</div>
                        <div class="fw-bold text-dark">{{ $patient->agama }}</div>
                    </div>
                    <div class="col-12 border-top border-light pt-3">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Tempat, Tanggal Lahir</div>
                        <div class="fw-semibold text-dark">{{ $patient->tempat_lahir }}, {{ $patient->tgl_lahir?->format('d F Y') }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Kontak Seluler</div>
                        <div class="fw-semibold text-dark"><i class="fa-solid fa-phone me-2 text-muted"></i>{{ $patient->no_telp_pasien ?: '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted fw-semibold text-uppercase mb-1" style="font-size: 0.7rem; letter-spacing: 0.5px;">Domisili Sesuai KTP</div>
                        <div class="small fw-medium text-dark lh-sm opacity-75">{{ $patient->alamat_lengkap }}, {{ $patient->kelurahan }}, {{ $patient->kecamatan }}, {{ $patient->kota }}</div>
                    </div>
                </div>
                
                @if($patient->alergi)
                <div class="mt-4 p-3 rounded-3 bg-danger bg-opacity-10 border border-danger border-opacity-10 d-flex gap-3 align-items-center">
                    <i class="fa-solid fa-triangle-exclamation text-danger fs-4"></i>
                    <div>
                        <div class="small fw-bold text-danger text-uppercase" style="font-size: 0.7rem; letter-spacing: 0.5px;">Klinis: Alergi</div>
                        <div class="fw-semibold text-danger small lh-sm">{{ $patient->alergi }}</div>
                    </div>
                </div>
                @endif

                <div class="mt-4 pt-4 border-top border-light d-grid gap-3">
                    <a href="{{ route('pendaftaran.kunjungan.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary py-2 fw-medium rounded-pill shadow-sm transition-hover">
                        <i class="fa-solid fa-calendar-plus me-2"></i>Registrasi Kunjungan
                    </a>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('pendaftaran.pasien.edit', $patient) }}" class="btn btn-light border w-100 fw-medium py-2 rounded-3 text-dark hover-bg-gray transition-hover">
                                <i class="fa-solid fa-user-pen me-2"></i>Edit Profil
                            </a>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('pendaftaran.pasien.destroy', $patient) }}" method="POST" class="delete-form h-100">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-light border border-danger border-opacity-25 w-100 fw-medium py-2 rounded-3 text-danger hover-bg-gray transition-hover">
                                    <i class="fa-solid fa-trash-can me-2"></i>Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Konten Utama: Riwayat Kunjungan -->
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
            <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                        <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-bold text-dark mb-0">Timeline Pelayanan & Kunjungan</h5>
                        <p class="small text-muted mb-0 fw-medium">Histori lengkap interaksi medis pasien</p>
                    </div>
                </div>
                <span class="badge bg-light text-dark border px-3 py-2 rounded-pill shadow-sm">Total: {{ $patient->encounters->count() }} Kunjungan</span>
            </div>
            
            <div class="table-responsive bg-white">
                <table class="table table-hover table-borderless align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                            <th class="ps-4 py-3 rounded-start">Waktu Masuk</th>
                            <th class="py-3">No. Registrasi</th>
                            <th class="py-3">Unit / Klinik</th>
                            <th class="py-3">Praktisi DPJP</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="pe-4 py-3 text-end rounded-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-light">
                    @forelse($patient->encounters->sortByDesc('waktu_masuk') as $encounter)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <div class="fw-semibold text-dark mb-1">{{ $encounter->waktu_masuk?->format('d M Y') }}</div>
                                <div class="small text-muted"><i class="fa-regular fa-clock me-1"></i>{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-3">
                                <div class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 font-monospace px-2 py-1 rounded-2">{{ $encounter->no_registrasi }}</div>
                            </td>
                            <td class="py-3">
                                <div class="fw-medium text-dark mb-1">{{ $encounter->department?->nama_depart }}</div>
                                <div class="badge bg-light text-secondary border fw-medium px-2 py-0" style="font-size: 0.65rem;">{{ strtoupper($encounter->jenis_kunjungan) }}</div>
                            </td>
                            <td class="py-3">
                                <div class="fw-medium text-dark small mb-1">{{ $encounter->doctor?->display_name ?? 'NOT ASSIGNED' }}</div>
                                <div class="small text-muted" style="font-size: 0.7rem;">Verified Specialist</div>
                            </td>
                            <td class="text-center py-3">
                                @php
                                    $statusCfg = match($encounter->status_encounter) {
                                        'terdaftar' => ['bg' => 'secondary', 'text' => 'REGISTERED'],
                                        'pelayanan' => ['bg' => 'primary', 'text' => 'IN SERVICE'],
                                        'selesai' => ['bg' => 'success', 'text' => 'FINISHED'],
                                        'batal' => ['bg' => 'danger', 'text' => 'CANCELLED'],
                                        default => ['bg' => 'dark', 'text' => strtoupper($encounter->status_encounter)]
                                    };
                                @endphp
                                <div class="badge bg-{{ $statusCfg['bg'] }} bg-opacity-10 text-{{ $statusCfg['bg'] }} rounded-pill px-3 py-1 fw-semibold d-inline-block" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                    {{ $statusCfg['text'] }}
                                </div>
                            </td>
                            <td class="pe-4 text-end py-3">
                                @if($encounter->billingInvoice)
                                    <a href="{{ route('keuangan.invoice.show', $encounter->billingInvoice) }}" class="btn btn-light border btn-sm px-3 fw-medium rounded-pill shadow-sm text-primary transition-hover">
                                        Billing <i class="fa-solid fa-arrow-right ms-1 small"></i>
                                    </a>
                                @else
                                    <span class="text-muted small fw-medium">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                        <i class="fa-solid fa-calendar-xmark fs-2 text-muted opacity-50"></i>
                                    </div>
                                    <h6 class="fw-bold text-dark mb-1">Belum Ada Kunjungan</h6>
                                    <p class="small fw-medium mb-0">Pasien ini belum memiliki histori pelayanan di sistem.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="p-4 rounded-4 bg-info bg-opacity-10 border-0 d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <div class="fw-bold text-info small text-uppercase" style="letter-spacing: 0.5px;">Informasi Privasi</div>
                    <div class="small text-dark fw-medium opacity-75">Data rekam medis ini bersifat rahasia dan dilindungi oleh UU ITE & Kesehatan.</div>
                </div>
            </div>
            <a href="{{ route('pendaftaran.pasien.index') }}" class="btn btn-info text-white btn-sm px-4 py-2 fw-medium rounded-pill shadow-sm transition-hover">Kembali</a>
        </div>
    </div>
</div>

<style>
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease, background-color 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; }
</style>

@section('scripts')
<script>
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Data Pasien?',
                text: "Seluruh riwayat medis dan identitas akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, HAPUS PERMANEN',
                cancelButtonText: 'BATAL',
                customClass: { popup: 'rounded-4 border-0 shadow-sm', title: 'fw-bold' }
            }).then((result) => {
                if (result.isConfirmed) { this.submit(); }
            });
        });
    });
</script>
@endsection

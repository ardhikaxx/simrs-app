@extends('layouts.app')

@section('title', 'Profil Pasien')
@section('page-title', 'Patient 360 Intelligence')
@section('page-subtitle', 'Tinjauan komprehensif identitas, demografi, dan riwayat klinis pasien')

@section('content')
<div class="row g-4">
    <!-- Sidebar Profil -->
    <div class="col-xl-4">
        <div class="card-premium border-0 bg-white overflow-hidden mb-4">
            <div class="bg-slate p-5 text-center position-relative">
                <div class="position-absolute top-0 end-0 p-3 opacity-5">
                    <i class="fa-solid fa-id-card fs-huge" style="transform: rotate(15deg);"></i>
                </div>
                <div class="user-avatar mx-auto mb-4 bg-primary text-white shadow-lg border border-4 border-white border-opacity-10" style="width: 100px; height: 100px; font-size: 2.5rem;">
                    {{ strtoupper(substr($patient->nama_pasien, 0, 1)) }}
                </div>
                <h4 class="fw-800 text-white mb-1">{{ $patient->nama_pasien }}</h4>
                <div class="text-primary-light small fw-bold font-monospace tracking-widest mb-4">NO. RM: {{ $patient->no_rkm_medis }}</div>
                <div class="d-flex justify-content-center gap-2">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3 py-2 rounded-pill fw-800" style="font-size: 0.65rem;">{{ $patient->age }} TAHUN</span>
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 px-3 py-2 rounded-pill fw-800" style="font-size: 0.65rem;">{{ $patient->jenis_kelamin === 'L' ? 'LAKI-LAKI' : 'PEREMPUAN' }}</span>
                </div>
            </div>
            <div class="p-4">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.6rem;">Identitas Legal (KTP/NIK)</div>
                        <div class="fw-800 text-slate fs-5 font-monospace">{{ $patient->nik }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.6rem;">Nomor Jaminan (BPJS)</div>
                        <div class="fw-800 text-primary fs-5 font-monospace">{{ $patient->no_bpjs ?: 'TIDAK TERDAFTAR' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.6rem;">Gol. Darah</div>
                        <div class="fw-800 text-danger"><i class="fa-solid fa-droplet me-1"></i>{{ $patient->golongan_darah ?: '-' }}</div>
                    </div>
                    <div class="col-6">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.6rem;">Agama</div>
                        <div class="fw-800 text-slate">{{ $patient->agama }}</div>
                    </div>
                    <div class="col-12 border-top border-light pt-3">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.6rem;">Tempat, Tanggal Lahir</div>
                        <div class="fw-800 text-slate">{{ $patient->tempat_lahir }}, {{ $patient->tgl_lahir?->format('d F Y') }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.6rem;">Kontak Seluler</div>
                        <div class="fw-800 text-slate"><i class="fa-solid fa-phone me-2 text-primary opacity-50"></i>{{ $patient->no_telp_pasien ?: '-' }}</div>
                    </div>
                    <div class="col-12">
                        <div class="small text-muted fw-800 text-uppercase tracking-wider mb-1" style="font-size: 0.6rem;">Domisili Sesuai KTP</div>
                        <div class="small fw-700 text-slate lh-sm opacity-75">{{ $patient->alamat_lengkap }}, {{ $patient->kelurahan }}, {{ $patient->kecamatan }}, {{ $patient->kota }}</div>
                    </div>
                </div>
                
                @if($patient->alergi)
                <div class="mt-4 p-3 rounded-4 bg-rose-soft border border-danger border-opacity-10 d-flex gap-3 align-items-center">
                    <i class="fa-solid fa-triangle-exclamation text-danger fs-4"></i>
                    <div>
                        <div class="small fw-800 text-danger text-uppercase tracking-wider" style="font-size: 0.6rem;">Klinis: Alergi</div>
                        <div class="fw-800 text-danger small lh-sm">{{ $patient->alergi }}</div>
                    </div>
                </div>
                @endif

                <div class="mt-5 d-grid gap-3">
                    <a href="{{ route('pendaftaran.kunjungan.create', ['patient_id' => $patient->id]) }}" class="btn btn-primary py-3 fw-800 rounded-pill shadow-sm transition-bounce-hover">
                        <i class="fa-solid fa-calendar-plus me-2"></i>REGISTRASI KUNJUNGAN
                    </a>
                    <div class="row g-2">
                        <div class="col-6">
                            <a href="{{ route('pendaftaran.pasien.edit', $patient) }}" class="btn btn-light border w-100 fw-800 py-2 rounded-3 small">
                                <i class="fa-solid fa-user-pen me-1"></i>EDIT PROFIL
                            </a>
                        </div>
                        <div class="col-6">
                            <form action="{{ route('pendaftaran.pasien.destroy', $patient) }}" method="POST" class="delete-form h-100">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-white border w-100 fw-800 py-2 rounded-3 small text-danger">
                                    <i class="fa-solid fa-trash-can me-1"></i>HAPUS
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
        <div class="card-premium border-0 bg-white overflow-hidden">
            <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="fa-solid fa-clock-rotate-left fs-5"></i>
                    </div>
                    <div>
                        <h5 class="fw-800 text-slate mb-0">Timeline Pelayanan & Kunjungan</h5>
                        <p class="small text-muted mb-0 fw-medium">Histori lengkap interaksi medis pasien</p>
                    </div>
                </div>
                <span class="badge bg-light text-slate border fw-800 px-3 py-2 rounded-pill">TOTAL: {{ $patient->encounters->count() }} KUNJUNGAN</span>
            </div>
            
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                            <th class="ps-4 border-0 py-3">Waktu Masuk</th>
                            <th class="border-0 py-3">No. Registrasi</th>
                            <th class="border-0 py-3">Unit / Klinik</th>
                            <th class="border-0 py-3">Praktisi DPJP</th>
                            <th class="border-0 py-3 text-center">Status</th>
                            <th class="pe-4 border-0 py-3 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($patient->encounters->sortByDesc('waktu_masuk') as $encounter)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <div class="fw-800 text-slate mb-0">{{ $encounter->waktu_masuk?->format('d M Y') }}</div>
                                <div class="small text-muted fw-bold opacity-75">{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                            </td>
                            <td class="py-3">
                                <div class="text-mono fw-800 text-primary small">{{ $encounter->no_registrasi }}</div>
                            </td>
                            <td class="py-3">
                                <div class="fw-800 text-slate mb-0">{{ $encounter->department?->nama_depart }}</div>
                                <div class="badge bg-light text-slate border fw-800" style="font-size: 0.6rem;">{{ strtoupper($encounter->jenis_kunjungan) }}</div>
                            </td>
                            <td class="py-3">
                                <div class="small fw-800 text-slate mb-0">{{ $encounter->doctor?->display_name ?? 'NOT ASSIGNED' }}</div>
                                <div class="small text-muted fw-medium" style="font-size: 0.65rem;">Verified Specialist</div>
                            </td>
                            <td class="text-center py-3">
                                @php
                                    $statusCfg = match($encounter->status_encounter) {
                                        'terdaftar' => ['bg' => 'bg-slate', 'text' => 'REGISTERED'],
                                        'pelayanan' => ['bg' => 'bg-blue', 'text' => 'IN SERVICE'],
                                        'selesai' => ['bg' => 'bg-success', 'text' => 'FINISHED'],
                                        'batal' => ['bg' => 'bg-danger', 'text' => 'CANCELLED'],
                                        default => ['bg' => 'bg-dark', 'text' => strtoupper($encounter->status_encounter)]
                                    };
                                @endphp
                                <div class="badge {{ $statusCfg['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusCfg['bg']) }} rounded-pill px-3 py-1 fw-800" style="font-size: 0.6rem;">
                                    {{ $statusCfg['text'] }}
                                </div>
                            </td>
                            <td class="pe-4 text-end py-3">
                                @if($encounter->billingInvoice)
                                    <a href="{{ route('keuangan.invoice.show', $encounter->billingInvoice) }}" class="btn btn-white border btn-sm px-3 fw-800 rounded-3 shadow-sm text-primary transition-bounce-hover">
                                        <i class="fa-solid fa-file-invoice-dollar me-1"></i>BILLING
                                    </a>
                                @else
                                    <span class="text-muted small italic fw-bold">N/A</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <div class="py-4">
                                    <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                        <i class="fa-solid fa-calendar-xmark fs-1 opacity-25"></i>
                                    </div>
                                    <h6 class="fw-800 text-slate">Belum Ada Kunjungan</h6>
                                    <p class="small fw-medium">Pasien ini belum memiliki histori pelayanan di sistem.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 p-4 rounded-4 bg-teal-soft border-0 d-flex justify-content-between align-items-center shadow-sm">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center shadow-sm" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
                <div>
                    <div class="fw-800 text-primary small text-uppercase">Informasi Privasi</div>
                    <div class="small text-slate fw-medium opacity-75">Data rekam medis ini bersifat rahasia dan dilindungi oleh UU ITE & Kesehatan.</div>
                </div>
            </div>
            <a href="{{ route('pendaftaran.pasien.index') }}" class="btn btn-primary btn-sm px-4 fw-800 rounded-pill">KEMBALI</a>
        </div>
    </div>
</div>

<style>
    .bg-slate { background: #0F172A; }
    .text-slate { color: #1E293B; }
    .text-primary-light { color: #2DD4BF; }
    .fs-huge { font-size: 5rem; }
    .bg-rose-soft { background: #FFF1F2; }
    .bg-teal-soft { background: #F0FDFA; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: translateY(-3px); }
    .transition-hover:hover { background-color: #F8FAFC !important; }
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
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'YA, HAPUS PERMANEN',
                cancelButtonText: 'BATAL',
                customClass: { popup: 'rounded-4 border-0 shadow-lg', title: 'fw-800' }
            }).then((result) => {
                if (result.isConfirmed) { this.submit(); }
            });
        });
    });
</script>
@endsection
@endsection

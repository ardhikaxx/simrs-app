@extends('layouts.app')

@section('title', 'Antrian Terpadu')
@section('page-title', 'Antrean & Kunjungan Pelayanan')
@section('page-subtitle', 'Monitoring real-time alur pasien dari registrasi hingga loket pembayaran')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-4 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-users-viewfinder fs-4"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Kunjungan Aktif</div>
                    <h3 class="fw-bold text-dark mb-0">{{ $encounters->total() }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-8 col-xl-9 d-flex align-items-center justify-content-md-end gap-2 flex-wrap">
        <a href="{{ route('pendaftaran.pasien.index') }}" class="btn btn-light border bg-white px-4 py-2 fw-medium shadow-sm rounded-3 text-muted hover-bg-gray transition-hover">
            <i class="fa-solid fa-address-book me-2"></i>Master Pasien
        </a>
        <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn btn-primary px-4 py-2 fw-medium shadow-sm rounded-3 transition-hover">
            <i class="fa-solid fa-calendar-plus me-2"></i>Registrasi Kunjungan
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-list-ol fs-5"></i>
            </div>
            <div>
                <h6 class="fw-bold text-dark mb-0">Daftar Antrean Pasien Berjalan</h6>
                <p class="small text-muted mb-0 fw-medium">Real-time status pelayanan per unit kerja</p>
            </div>
        </div>
        <form class="d-flex gap-2" method="GET">
            <div class="input-group input-group-sm bg-light rounded-3" style="max-width: 300px;">
                <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0" placeholder="Cari Pasien / No. Reg...">
            </div>
            <button class="btn btn-sm btn-primary px-3 fw-medium rounded-3 shadow-sm">
                <i class="fa-solid fa-filter"></i>
            </button>
        </form>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Antrean / Reg</th>
                    <th class="py-3">Informasi Pasien</th>
                    <th class="py-3">Unit Pelayanan</th>
                    <th class="py-3">Dokter DPJP</th>
                    <th class="py-3">Waktu & Penjamin</th>
                    <th class="py-3 text-center">Status Alur</th>
                    <th class="pe-4 py-3 text-end rounded-end">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($encounters as $encounter)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="fw-bold text-primary mb-1 h6">{{ $encounter->no_antrian }}</div>
                        <div class="small text-muted fw-semibold font-monospace">{{ $encounter->no_registrasi }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-medium text-dark mb-1">{{ $encounter->department->nama_depart }}</div>
                        <div class="badge bg-light text-secondary border fw-medium px-2 py-1" style="font-size: 0.65rem;">{{ str_replace('_', ' ', strtoupper($encounter->jenis_kunjungan)) }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-medium text-dark small mb-1">{{ $encounter->doctor?->display_name ?? 'BELUM DITENTUKAN' }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">Verified Practitioner</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark small mb-1">{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                        <div class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-10 px-2 py-1 fw-semibold" style="font-size: 0.65rem;">
                            {{ strtoupper($encounter->cara_bayar) }}
                        </div>
                    </td>
                    <td class="text-center py-3">
                        @php
                            $statusCfg = match($encounter->status_antrian) {
                                'terdaftar' => ['bg' => 'secondary', 'text' => 'REGISTERED'],
                                'asesmen_perawat' => ['bg' => 'warning', 'text' => 'NURSING'],
                                'pemeriksaan_dokter' => ['bg' => 'info', 'text' => 'CLINICAL'],
                                'menunggu_kasir', 'menunggu_farmasi', 'menunggu_lab', 'menunggu_rad' => ['bg' => 'primary', 'text' => 'WAITING'],
                                'selesai' => ['bg' => 'success', 'text' => 'DONE'],
                                'batal' => ['bg' => 'danger', 'text' => 'CANCELLED'],
                                default => ['bg' => 'dark', 'text' => strtoupper($encounter->status_antrian)]
                            };
                        @endphp
                        <div class="badge bg-{{ $statusCfg['bg'] }} bg-opacity-10 text-{{ $statusCfg['bg'] }} rounded-pill px-3 py-1 fw-semibold d-inline-block" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ $statusCfg['text'] }}
                        </div>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle border-0 shadow-none d-flex align-items-center justify-content-center mx-auto me-md-0 ms-md-auto" style="width: 32px; height: 32px;" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-2">
                                <li><a class="dropdown-item py-2 rounded-3 small fw-medium" href="{{ route('keperawatan.antrian') }}"><i class="fa-solid fa-user-nurse me-2 opacity-50"></i>Input Asesmen</a></li>
                                <li><a class="dropdown-item py-2 rounded-3 small fw-medium" href="{{ route('rekam-medis.antrian') }}"><i class="fa-solid fa-stethoscope me-2 opacity-50"></i>Input RME</a></li>
                                <li><hr class="dropdown-divider opacity-10"></li>
                                <li><a class="dropdown-item py-2 rounded-3 small fw-medium" href="{{ route('pendaftaran.pasien.show', $encounter->patient) }}"><i class="fa-solid fa-folder-open me-2 opacity-50"></i>Profil Pasien</a></li>
                                <li>
                                    <form action="{{ route('pendaftaran.kunjungan.cancel', $encounter) }}" method="POST" class="cancel-form">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="dropdown-item py-2 rounded-3 small fw-semibold text-danger">
                                            <i class="fa-solid fa-calendar-xmark me-2"></i>Batalkan Kunjungan
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5 text-muted">
                        <div class="py-4">
                            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                <i class="fa-solid fa-clipboard-list fs-2 text-muted opacity-50"></i>
                            </div>
                            <h6 class="fw-bold text-dark mb-1">Antrean Kosong</h6>
                            <p class="small fw-medium mb-0">Tidak ada pelayanan pasien yang sedang berjalan.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($encounters->hasPages())
        <div class="p-4 border-top border-light bg-white rounded-bottom-4">
            {{ $encounters->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa; }
    .transition-hover { transition: all 0.2s ease; }
    .transition-hover:hover { background-color: #f8f9fa !important; transform: translateY(-3px); }
    .card.transition-hover:hover { transform: translateY(-3px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important; background-color: #fff !important; }
    table .transition-hover:hover { transform: none; }
</style>
@endsection

@section('scripts')
<script>
    document.querySelectorAll('.cancel-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Batalkan Kunjungan?',
                text: "Status antrean akan diubah menjadi Batal. Tindakan ini tidak dapat dikembalikan.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'YA, BATALKAN',
                cancelButtonText: 'TUTUP',
                customClass: { popup: 'rounded-4 border-0 shadow-sm', title: 'fw-bold' }
            }).then((result) => {
                if (result.isConfirmed) { this.submit(); }
            });
        });
    });
</script>
@endsection

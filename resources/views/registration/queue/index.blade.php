@extends('layouts.app')

@section('title', 'Antrian Terpadu')
@section('page-title', 'Antrean & Kunjungan Pelayanan')
@section('page-subtitle', 'Monitoring real-time alur pasien dari registrasi hingga loket pembayaran')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card-premium border-0 h-100 p-4 transition-bounce-hover position-relative overflow-hidden bg-white">
            <div class="d-flex align-items-center gap-4">
                <div class="kpi-icon bg-teal-soft text-primary">
                    <i class="fa-solid fa-users-viewfinder"></i>
                </div>
                <div>
                    <div class="text-muted fw-800 small text-uppercase tracking-wider mb-1" style="font-size: 0.65rem;">Kunjungan Aktif</div>
                    <h3 class="fw-800 mb-0 text-slate">{{ $encounters->total() }}</h3>
                </div>
            </div>
            <i class="fa-solid fa-hospital-user position-absolute top-50 end-0 translate-middle-y opacity-5 fs-1 me-4"></i>
        </div>
    </div>
    <div class="col-md-9 text-md-end d-flex align-items-center justify-content-md-end gap-3">
        <a href="{{ route('pendaftaran.pasien.index') }}" class="btn-premium btn-light border bg-white px-4">
            <i class="fa-solid fa-address-book opacity-50"></i>MASTER PASIEN
        </a>
        <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn-premium btn-primary px-4">
            <i class="fa-solid fa-calendar-plus"></i>REGISTRASI KUNJUNGAN
        </a>
    </div>
</div>

<div class="card-premium border-0 bg-white overflow-hidden">
    <div class="p-4 border-bottom d-flex justify-content-between align-items-center bg-white">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                <i class="fa-solid fa-list-ol fs-5"></i>
            </div>
            <div>
                <h5 class="fw-800 text-slate mb-0">Daftar Antrean Pasien Berjalan</h5>
                <p class="small text-muted mb-0 fw-medium">Real-time status pelayanan per unit kerja</p>
            </div>
        </div>
        <form class="d-flex gap-2" method="GET">
            <div class="header-search bg-light border-0" style="max-width: 300px;">
                <i class="fa-solid fa-magnifying-glass opacity-40"></i>
                <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari Pasien / No. Reg...">
            </div>
            <button class="btn btn-primary px-3 fw-800 rounded-3">
                <i class="fa-solid fa-filter"></i>
            </button>
        </form>
    </div>
    
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr class="bg-light bg-opacity-50 text-muted small fw-800 text-uppercase tracking-wider">
                    <th class="ps-4 border-0 py-3">No. Antrean / Reg</th>
                    <th class="border-0 py-3">Informasi Pasien</th>
                    <th class="border-0 py-3">Unit Pelayanan</th>
                    <th class="border-0 py-3">Dokter DPJP</th>
                    <th class="border-0 py-3">Waktu & Penjamin</th>
                    <th class="border-0 py-3 text-center">Status Alur</th>
                    <th class="pe-4 border-0 py-3 text-end">Aksi Cepat</th>
                </tr>
            </thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="text-mono fw-800 text-primary h5 mb-1">{{ $encounter->no_antrian }}</div>
                        <div class="small text-muted fw-bold font-monospace opacity-75">{{ $encounter->no_registrasi }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-800 text-slate mb-1">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted fw-bold font-monospace">RM: {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-slate mb-1">{{ $encounter->department->nama_depart }}</div>
                        <div class="badge bg-light text-slate border small fw-800" style="font-size: 0.6rem;">{{ str_replace('_', ' ', strtoupper($encounter->jenis_kunjungan)) }}</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-slate small mb-1">{{ $encounter->doctor?->display_name ?? 'BELUM DITENTUKAN' }}</div>
                        <div class="small text-muted fw-medium" style="font-size: 0.7rem;">Verified Practitioner</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-slate small mb-1">{{ $encounter->waktu_masuk?->format('H:i') }} WIB</div>
                        <div class="badge bg-blue bg-opacity-10 text-blue rounded px-2 py-1 fw-800" style="font-size: 0.6rem;">
                            {{ strtoupper($encounter->cara_bayar) }}
                        </div>
                    </td>
                    <td class="text-center py-3">
                        @php
                            $statusCfg = match($encounter->status_antrian) {
                                'terdaftar' => ['bg' => 'bg-slate', 'text' => 'REGISTERED'],
                                'asesmen_perawat' => ['bg' => 'bg-amber', 'text' => 'NURSING'],
                                'pemeriksaan_dokter' => ['bg' => 'bg-teal', 'text' => 'CLINICAL'],
                                'menunggu_kasir', 'menunggu_farmasi', 'menunggu_lab', 'menunggu_rad' => ['bg' => 'bg-blue', 'text' => 'WAITING'],
                                'selesai' => ['bg' => 'bg-success', 'text' => 'DONE'],
                                'batal' => ['bg' => 'bg-danger', 'text' => 'CANCELLED'],
                                default => ['bg' => 'bg-dark', 'text' => strtoupper($encounter->status_antrian)]
                            };
                        @endphp
                        <div class="badge {{ $statusCfg['bg'] }} bg-opacity-10 text-{{ str_replace('bg-', '', $statusCfg['bg']) }} rounded-pill px-3 py-2 fw-800" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                            {{ $statusCfg['text'] }}
                        </div>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle border-0 shadow-none p-0" style="width: 32px; height: 32px;" data-bs-toggle="dropdown">
                                <i class="fa-solid fa-ellipsis-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 rounded-4 p-2">
                                <li><a class="dropdown-item py-2 rounded-3 small fw-bold" href="{{ route('keperawatan.antrian') }}"><i class="fa-solid fa-user-nurse me-2 opacity-50"></i>Input Asesmen</a></li>
                                <li><a class="dropdown-item py-2 rounded-3 small fw-bold" href="{{ route('rekam-medis.antrian') }}"><i class="fa-solid fa-stethoscope me-2 opacity-50"></i>Input RME</a></li>
                                <li><hr class="dropdown-divider opacity-5"></li>
                                <li><a class="dropdown-item py-2 rounded-3 small fw-bold" href="{{ route('pendaftaran.pasien.show', $encounter->patient) }}"><i class="fa-solid fa-folder-open me-2 opacity-50"></i>Profil Pasien</a></li>
                                <li>
                                    <form action="{{ route('pendaftaran.kunjungan.cancel', $encounter) }}" method="POST" class="cancel-form">
                                        @csrf @method('PATCH')
                                        <button type="submit" class="dropdown-item py-2 rounded-3 small fw-800 text-danger">
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
                                <i class="fa-solid fa-clipboard-list fs-1 opacity-25"></i>
                            </div>
                            <h6 class="fw-800 text-slate">Antrean Kosong</h6>
                            <p class="small fw-medium">Tidak ada pelayanan pasien yang sedang berjalan.</p>
                        </div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    
    @if($encounters->hasPages())
        <div class="p-4 border-top bg-white">
            {{ $encounters->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>

<style>
    .kpi-icon { width: 56px; height: 56px; border-radius: 14px; display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .bg-teal-soft { background: #F0FDFA; }
    .text-slate { color: #1E293B; }
    .text-blue { color: #3B82F6; }
    .text-amber { color: #F59E0B; }
    .transition-bounce-hover { transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    .transition-bounce-hover:hover { transform: scale(1.05); }
    .transition-hover:hover { background-color: #F8FAFC !important; }
    .header-search { background: #F1F5F9; border-radius: 12px; padding: 0.6rem 1rem; display: flex; align-items: center; gap: 0.75rem; }
    .header-search input { border: none; background: transparent; outline: none; width: 100%; font-size: 0.9rem; font-weight: 600; }
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
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'YA, BATALKAN',
                cancelButtonText: 'TUTUP',
                customClass: { popup: 'rounded-4 border-0 shadow-lg', title: 'fw-800' }
            }).then((result) => {
                if (result.isConfirmed) { this.submit(); }
            });
        });
    });
</script>
@endsection

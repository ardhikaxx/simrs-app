@extends('layouts.app')

@section('title', 'Antrian Dokter')
@section('page-title', 'Antrean Pemeriksaan Medis')
@section('page-subtitle', 'Daftar pasien menunggu pemeriksaan dokter dan pengisian RME')

@section('content')
<!-- Filter & Action Bar -->
<div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
    <div class="card-body p-4">
        <form class="row g-3 align-items-center" method="GET">
            <div class="col-md-5">
                <div class="input-group bg-light rounded-3">
                    <span class="input-group-text bg-transparent border-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0 py-2" placeholder="Cari No. Registrasi, nama pasien, atau No. RM...">
                </div>
            </div>
            <div class="col-md-4">
                <select name="status" class="form-select bg-light border-light shadow-none focus-ring-0 fw-medium py-2 rounded-3 text-muted">
                    <option value="">Semua Status Antrean</option>
                    <option value="menunggu" @selected(request('status') === 'menunggu')>Menunggu Pemeriksaan</option>
                    <option value="pemeriksaan_dokter" @selected(request('status') === 'pemeriksaan_dokter')>Sedang Diperiksa</option>
                    <option value="diperiksa" @selected(request('status') === 'diperiksa')>Sudah Selesai</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary fw-bold shadow-sm rounded-3 px-4 py-2 w-100 transition-hover">
                    <i class="fa-solid fa-filter me-2"></i>Filter Data
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Main Table Card -->
<div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white">
    <div class="p-4 border-bottom border-light bg-white d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                <i class="fa-solid fa-notes-medical fs-5"></i>
            </div>
            <div>
                <h5 class="fw-bold mb-0 text-dark">Antrean Pasien Poliklinik & IGD</h5>
                <p class="text-muted small mb-0 fw-medium">Total {{ $encounters->total() }} pasien menunggu</p>
            </div>
        </div>
    </div>
    
    <div class="table-responsive bg-white">
        <table class="table table-hover table-borderless align-middle mb-0 custom-table">
            <thead class="bg-light">
                <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                    <th class="ps-4 py-3 rounded-start">No. Registrasi</th>
                    <th class="py-3">Informasi Pasien</th>
                    <th class="py-3">Unit / DPJP</th>
                    <th class="py-3 text-center">Asesmen Awal</th>
                    <th class="py-3 text-center">E-Prescription</th>
                    <th class="py-3 text-center">Status Antrean</th>
                    <th class="pe-4 py-3 text-end rounded-end">Aksi Medis</th>
                </tr>
            </thead>
            <tbody class="border-top border-light">
            @forelse($encounters as $encounter)
                <tr class="transition-hover">
                    <td class="ps-4 py-3">
                        <div class="text-primary font-monospace fw-bold bg-primary bg-opacity-10 px-2 py-1 rounded-2 border border-primary border-opacity-10 d-inline-block mb-1">{{ $encounter->no_registrasi }}</div>
                        <div class="small text-muted fw-medium d-flex align-items-center gap-1" style="font-size: 0.7rem;"><i class="fa-regular fa-clock opacity-50"></i>{{ $encounter->waktu_masuk?->format('d/m H:i') }} ({{ $encounter->waktu_masuk?->diffForHumans() }})</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-bold text-dark mb-1">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted font-monospace"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>RM: {{ $encounter->patient->no_rkm_medis }} <span class="mx-1 text-black-50">&bull;</span> {{ $encounter->patient->age }} Th</div>
                    </td>
                    <td class="py-3">
                        <div class="fw-semibold text-dark mb-1">{{ $encounter->department?->nama_depart }}</div>
                        <div class="text-muted small d-flex align-items-center gap-1" style="font-size: 0.75rem;">
                            <i class="fa-solid fa-user-doctor opacity-50"></i>{{ $encounter->doctor?->display_name ?? 'Belum Ditentukan' }}
                        </div>
                    </td>
                    <td class="text-center py-3">
                        @if($encounter->nursingAssessment)
                            <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-check-double me-1"></i>LENGKAP
                            </span>
                        @else
                            <span class="badge bg-warning bg-opacity-10 text-warning border border-warning border-opacity-25 rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-clock me-1"></i>BELUM
                            </span>
                        @endif
                    </td>
                    <td class="text-center py-3">
                        @if($encounter->prescriptions->count() > 0)
                            <span class="badge bg-info bg-opacity-10 text-info border border-info border-opacity-25 rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                                <i class="fa-solid fa-pills me-1"></i>{{ $encounter->prescriptions->count() }} RESEP
                            </span>
                        @else
                            <span class="text-muted opacity-50 fst-italic small">Belum Ada</span>
                        @endif
                    </td>
                    <td class="text-center py-3">
                        @php
                            $badgeStatusClass = match($encounter->status_antrian) {
                                'menunggu' => 'bg-warning text-dark border-warning border-opacity-25',
                                'pemeriksaan_dokter' => 'bg-primary text-primary',
                                'diperiksa', 'selesai' => 'bg-success text-success',
                                default => 'bg-secondary text-secondary'
                            };
                        @endphp
                        <span class="badge {{ $badgeStatusClass }} bg-opacity-10 border rounded-pill px-3 py-1 fw-bold" style="font-size: 0.7rem; letter-spacing: 0.5px;">
                            {{ str_replace('_',' ',strtoupper($encounter->status_antrian)) }}
                        </span>
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('rekam-medis.resume', $encounter) }}" class="btn btn-sm btn-light border-light-subtle text-muted shadow-sm rounded-circle d-flex align-items-center justify-content-center transition-hover hover-bg-gray" style="width: 34px; height: 34px;" title="Lihat Resume Medis">
                                <i class="fa-solid fa-file-medical"></i>
                            </a>
                            <button type="button" class="btn btn-sm btn-info bg-info bg-opacity-10 text-info border-0 shadow-sm rounded-circle d-flex align-items-center justify-content-center transition-hover" style="width: 34px; height: 34px;" onclick="panggilAntrean('{{ $encounter->no_antrian }}', '{{ $encounter->department->nama_depart }}')" title="Panggil Pasien">
                                <i class="fa-solid fa-volume-high"></i>
                            </button>
                            <a href="{{ route('rekam-medis.edit', $encounter) }}" class="btn btn-sm btn-primary shadow-sm rounded-pill px-3 fw-semibold transition-hover">
                                <i class="fa-solid fa-stethoscope me-1"></i> Periksa
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3 shadow-sm" style="width: 80px; height: 80px;">
                            <i class="fa-solid fa-user-doctor fs-2 text-muted opacity-50"></i>
                        </div>
                        <h6 class="fw-bold text-dark mb-1">Antrean Kosong</h6>
                        <p class="text-muted small mb-0">Tidak ada pasien dalam daftar antrean pemeriksaan dokter.</p>
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
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
</style>

@section('scripts')
<script>
    function panggilAntrean(nomor, unit) {
        if (!'speechSynthesis' in window) {
            alert("Browser Anda tidak mendukung pemanggilan suara.");
            return;
        }

        const msg = new SpeechSynthesisUtterance();
        msg.text = `Nomor antrean ${nomor.split('').join(' ')}, silakan menuju ke ${unit}`;
        msg.lang = 'id-ID';
        msg.rate = 0.8;
        msg.pitch = 1;
        
        // Panggil suara
        window.speechSynthesis.speak(msg);

        // Notifikasi visual kecil
        Swal.fire({
            title: 'Memanggil Pasien...',
            text: `Nomor: ${nomor} - ${unit}`,
            icon: 'info',
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'rounded-4 border-0 shadow-sm' }
        });
    }
</script>
@endsection
@endsection
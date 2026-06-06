@extends('layouts.app')

@section('title', 'Antrian Dokter')
@section('page-title', 'Antrian Pemeriksaan Medis')
@section('page-subtitle', 'Daftar pasien menunggu pemeriksaan dokter dan pengisian RME')

@section('content')
<div class="page-header-bar mb-3">
    <form class="d-flex gap-2 grow" method="GET">
        <div class="input-group shadow-sm">
            <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
            <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari No. Registrasi, nama pasien, atau No. RM...">
        </div>
        <select name="status" class="form-select shadow-sm" style="max-width: 200px;">
            <option value="">Semua Status</option>
            <option value="menunggu" @selected(request('status') === 'menunggu')>Menunggu</option>
            <option value="pemeriksaan_dokter" @selected(request('status') === 'pemeriksaan_dokter')>Pemeriksaan</option>
            <option value="diperiksa" @selected(request('status') === 'diperiksa')>Sudah Periksa</option>
        </select>
        <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
    </form>
</div>

<div class="simrs-card">
    <div class="simrs-card-header bg-white">
        <div class="simrs-card-title text-simrs-primary">
            <i class="fa-solid fa-notes-medical"></i>
            <span>Antrean Pasien Poliklinik & IGD</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="bg-light">
                <tr>
                    <th class="ps-4">No. Registrasi</th>
                    <th>Informasi Pasien</th>
                    <th>Unit / DPJP</th>
                    <th class="text-center">Asesmen Awal</th>
                    <th class="text-center">E-Prescription</th>
                    <th>Status Antrean</th>
                    <th class="pe-4 text-end">Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($encounters as $encounter)
                <tr>
                    <td class="ps-4">
                        <div class="text-mono fw-bold text-simrs-primary small">{{ $encounter->no_registrasi }}</div>
                        <div class="small text-muted" style="font-size: 0.7rem;">{{ $encounter->waktu_masuk?->format('d/m H:i') }} ({{ $encounter->waktu_masuk?->diffForHumans() }})</div>
                    </td>
                    <td>
                        <div class="fw-bold text-simrs-gray-900">{{ $encounter->patient->nama_pasien }}</div>
                        <div class="small text-muted text-mono" style="font-size: 0.75rem;">{{ $encounter->patient->age }} th - {{ $encounter->patient->no_rkm_medis }}</div>
                    </td>
                    <td>
                        <div class="small fw-600">{{ $encounter->department?->nama_depart }}</div>
                        <div class="text-muted small" style="font-size: 0.7rem;">{{ $encounter->doctor?->display_name ?? '-' }}</div>
                    </td>
                    <td class="text-center">
                        @if($encounter->nursingAssessment)
                            <span class="badge bg-success-subtle text-success border border-success-subtle px-2 py-1" style="font-size: 0.65rem;">
                                <i class="fa-solid fa-check-double me-1"></i>LENGKAP
                            </span>
                        @else
                            <span class="badge bg-warning-subtle text-warning border border-warning-subtle px-2 py-1" style="font-size: 0.65rem;">
                                <i class="fa-solid fa-clock me-1"></i>BELUM
                            </span>
                        @endif
                    </td>
                    <td class="text-center">
                        @if($encounter->prescriptions->count() > 0)
                            <span class="badge bg-info-subtle text-info border border-info-subtle px-2 py-1" style="font-size: 0.65rem;">
                                <i class="fa-solid fa-pills me-1"></i>{{ $encounter->prescriptions->count() }} RESEP
                            </span>
                        @else
                            <span class="text-muted opacity-50 small">-</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-status status-{{ str_replace('_','-',$encounter->status_antrian) }} shadow-none">
                            {{ str_replace('_',' ',ucfirst($encounter->status_antrian)) }}
                        </span>
                    </td>
                    <td class="pe-4 text-end">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('rekam-medis.resume', $encounter) }}" class="btn btn-sm btn-simrs-outline shadow-sm px-3">
                                <i class="fa-solid fa-file-medical me-1"></i>Resume
                            </a>
                            <button type="button" class="btn btn-sm btn-info text-white shadow-sm px-3" onclick="panggilAntrean('{{ $encounter->no_antrian }}', '{{ $encounter->department->nama_depart }}')">
                                <i class="fa-solid fa-volume-high me-1"></i>Panggil
                            </button>
                            <a href="{{ route('rekam-medis.edit', $encounter) }}" class="btn btn-sm btn-simrs-primary shadow-sm px-3">
                                <i class="fa-solid fa-stethoscope me-1"></i>Periksa Pasien
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fa-solid fa-user-doctor fs-1 text-muted opacity-25 mb-3 d-block"></i>
                        <div class="text-muted">Tidak ada pasien dalam daftar antrean pemeriksaan dokter.</div>
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
    @if($encounters->hasPages())
        <div class="p-3 border-top bg-light">
            {{ $encounters->links('pagination::bootstrap-5') }}
        </div>
    @endif
</div>
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
            timerProgressBar: true
        });
    }
</script>
@endsection
@endsection

@extends('layouts.app')

@section('title', 'Data Pasien')
@section('page-title', 'Master Pasien')
@section('page-subtitle', 'Identitas pasien, nomor RM, BPJS, dan riwayat kunjungan')

@section('content')
<div class="page-header-bar">
    <form class="d-flex gap-2 flex-grow-1" method="GET">
        <input type="search" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama, no RM, NIK, atau BPJS">
        <button class="btn btn-simrs-outline"><i class="fa-solid fa-magnifying-glass"></i></button>
    </form>
    <div class="page-header-actions d-flex gap-2">
        <a href="{{ route('pendaftaran.kunjungan.create') }}" class="btn btn-simrs-outline"><i class="fa-solid fa-calendar-plus me-1"></i>Kunjungan</a>
        <a href="{{ route('pendaftaran.pasien.create') }}" class="btn btn-simrs-primary"><i class="fa-solid fa-user-plus me-1"></i>Pasien Baru</a>
    </div>
</div>

<div class="simrs-card">
    <div class="table-responsive">
        <table class="table mb-0">
            <thead>
                <tr>
                    <th>No RM</th>
                    <th>Pasien</th>
                    <th>NIK / BPJS</th>
                    <th>Usia</th>
                    <th>Kontak</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            @forelse($patients as $patient)
                <tr>
                    <td class="text-mono">{{ $patient->no_rkm_medis }}</td>
                    <td>
                        <strong>{{ $patient->nama_pasien }}</strong>
                        <div class="small text-muted">{{ $patient->jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan' }} - {{ $patient->tempat_lahir }}, {{ $patient->tgl_lahir?->format('d/m/Y') }}</div>
                    </td>
                    <td>
                        <div class="text-mono">{{ $patient->nik }}</div>
                        <div class="small text-muted text-mono">{{ $patient->no_bpjs ?: 'Non BPJS' }}</div>
                    </td>
                    <td>{{ $patient->age }} tahun</td>
                    <td>
                        {{ $patient->no_telp_pasien ?: '-' }}
                        <div class="small text-muted">{{ $patient->kota }}</div>
                    </td>
                    <td>
                        <a href="{{ route('pendaftaran.pasien.show', $patient) }}" class="btn btn-sm btn-simrs-outline"><i class="fa-solid fa-eye me-1"></i>Detail</a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="6" class="text-center text-muted py-4">Data pasien belum tersedia.</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
    <div class="p-3">{{ $patients->links('pagination::bootstrap-5') }}</div>
</div>
@endsection

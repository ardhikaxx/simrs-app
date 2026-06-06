@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Akses Staff')
@section('page-subtitle', 'Kelola akun, hak akses, dan departemen staff rumah sakit')

@section('content')
<div class="row g-4">
    <!-- Form Tambah User -->
    <div class="col-xl-4">
        <div class="simrs-card sticky-top" style="top: 80px; z-index: 100;">
            <div class="simrs-card-header bg-light">
                <div class="simrs-card-title">
                    <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 32px; height: 32px; font-size: 0.8rem;">
                        <i class="fa-solid fa-user-plus"></i>
                    </div>
                    <span>Registrasi Staff Baru</span>
                </div>
            </div>
            <div class="simrs-card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom">Nomor Induk (NIP)</label>
                            <input name="nip" class="form-control" placeholder="Contoh: 1980..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom">No. Telepon</label>
                            <input name="no_telepon" class="form-control" placeholder="0812...">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Nama Lengkap & Gelar</label>
                            <input name="nama_lengkap" class="form-control" placeholder="Masukkan nama lengkap" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Alamat Email Kerja</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted small"></i></span>
                                <input type="email" name="email" class="form-control border-start-0" placeholder="staff@rs-sehat.com" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Password Awal</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted small"></i></span>
                                <input type="password" name="password" class="form-control border-start-0" value="password" required>
                            </div>
                            <div class="form-text text-mono small">Default: password</div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Departemen / Unit Kerja</label>
                            <select name="department_id" class="form-select select2-init" required>
                                <option value="">Pilih Departemen</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom">Peran Utama (Role)</label>
                            <select name="role_id" class="form-select" required>
                                <option value="">Pilih Role Akses</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-simrs-primary w-100 mt-4 py-2 fw-bold shadow-sm">
                        <i class="fa-solid fa-floppy-disk me-2"></i>Daftarkan Staff
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar User -->
    <div class="col-xl-8">
        <div class="page-header-bar mb-3">
            <form class="d-flex gap-2 flex-grow-1" method="GET">
                <div class="input-group shadow-sm">
                    <span class="input-group-text bg-white border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0" placeholder="Cari staff berdasarkan nama, NIP, atau email...">
                </div>
                <button class="btn btn-simrs-outline shadow-sm px-3">Filter</button>
            </form>
        </div>

        <div class="simrs-card">
            <div class="simrs-card-header bg-white">
                <div class="simrs-card-title">
                    <i class="fa-solid fa-users text-simrs-primary"></i>
                    <span>Direktori Staff SIMRS</span>
                </div>
                <div class="small text-muted fw-normal">Total: {{ $users->total() }} Akun</div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead>
                        <tr>
                            <th class="ps-4">Identitas Staff</th>
                            <th>Unit Kerja</th>
                            <th>Akses</th>
                            <th>Login Terakhir</th>
                            <th>Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar-sm shadow-sm" style="background: var(--simrs-primary-pale); color: var(--simrs-primary); border: 1px solid var(--simrs-primary-light);">
                                        {{ strtoupper(substr($user->display_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-simrs-gray-900">{{ $user->display_name }}</div>
                                        <div class="text-mono small text-muted">{{ $user->nip ?: 'NIP -' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="badge-status status-baru shadow-none" style="font-weight: 600;">
                                    {{ $user->department?->nama_depart ?: 'Internal' }}
                                </div>
                            </td>
                            <td>
                                <div class="small fw-600 text-simrs-secondary">
                                    <i class="fa-solid fa-shield-halved me-1 opacity-50"></i>
                                    {{ $user->roles->pluck('nama_peran')->first() ?: 'Guest' }}
                                </div>
                            </td>
                            <td>
                                <div class="small text-muted">
                                    @if($user->last_login_at)
                                        {{ $user->last_login_at->format('d/m/Y') }}
                                        <div class="text-mono" style="font-size: 0.75rem;">{{ $user->last_login_at->format('H:i') }} WIB</div>
                                    @else
                                        <span class="opacity-50">- Belum login -</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge-status {{ $user->is_active ? 'status-aman' : 'status-kritis' }}">
                                    <i class="fa-solid fa-circle {{ $user->is_active ? 'text-success' : 'text-danger' }} me-1 small"></i>
                                    {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="pe-4 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-simrs-outline shadow-none border-0 p-1" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical fs-5"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                                        <li><a class="dropdown-item py-2" href="#"><i class="fa-solid fa-user-pen me-2 small text-muted"></i>Edit Profil</a></li>
                                        <li><a class="dropdown-item py-2" href="#"><i class="fa-solid fa-key me-2 small text-muted"></i>Reset Password</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button class="dropdown-item py-2 {{ $user->is_active ? 'text-danger' : 'text-success' }}">
                                                    @if($user->is_active)
                                                        <i class="fa-solid fa-user-lock me-2 small"></i>Nonaktifkan Akun
                                                    @else
                                                        <i class="fa-solid fa-user-check me-2 small"></i>Aktifkan Akun
                                                    @endif
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center py-5">
                                <i class="fa-solid fa-user-slash fs-1 text-muted opacity-25 mb-3"></i>
                                <div class="text-muted">Staff tidak ditemukan dengan kriteria pencarian tersebut.</div>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-3 border-top bg-light">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

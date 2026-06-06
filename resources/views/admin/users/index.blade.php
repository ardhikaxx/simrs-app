@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Akses Staff')
@section('page-subtitle', 'Kelola akun, hak akses, dan departemen staff rumah sakit')

@section('content')
<!-- Top Stats Row -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="simrs-card bg-white border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                <i class="fa-solid fa-users fs-1"></i>
            </div>
            <div class="simrs-card-body d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-primary-subtle text-primary" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider">Total Staff Aktif</div>
                    <div class="h3 fw-800 text-simrs-gray-900 mb-0">{{ $users->total() }} <span class="fs-6 fw-normal text-muted">Akun</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="simrs-card bg-white border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                <i class="fa-solid fa-building-user fs-1"></i>
            </div>
            <div class="simrs-card-body d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-info-subtle text-info" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider">Departemen</div>
                    <div class="h3 fw-800 text-simrs-gray-900 mb-0">{{ count($departments) }} <span class="fs-6 fw-normal text-muted">Unit Terdaftar</span></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="simrs-card bg-white border-0 shadow-sm h-100 overflow-hidden position-relative">
            <div class="position-absolute top-0 end-0 p-3 opacity-10">
                <i class="fa-solid fa-shield-halved fs-1"></i>
            </div>
            <div class="simrs-card-body d-flex align-items-center gap-3">
                <div class="brand-icon shadow-none bg-warning-subtle text-warning" style="width: 48px; height: 48px;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <div class="small fw-700 text-muted text-uppercase tracking-wider">Role Sistem</div>
                    <div class="h3 fw-800 text-simrs-gray-900 mb-0">{{ count($roles) }} <span class="fs-6 fw-normal text-muted">Tingkat Akses</span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Form Tambah User -->
    <div class="col-xl-4">
        <div class="simrs-card sticky-top border-0 shadow-sm" style="top: 80px; z-index: 100;">
            <div class="simrs-card-header bg-white border-bottom py-3">
                <div class="simrs-card-title text-simrs-primary">
                    <i class="fa-solid fa-user-plus"></i>
                    <span>Registrasi Staff Baru</span>
                </div>
            </div>
            <div class="simrs-card-body">
                <form action="{{ route('admin.users.store') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-custom small">Nomor Induk (NIP)</label>
                            <input name="nip" class="form-control text-mono fw-bold bg-light" placeholder="1980..." required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-custom small">No. Telepon</label>
                            <input name="no_telepon" class="form-control bg-light" placeholder="0812...">
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom small">Nama Lengkap & Gelar</label>
                            <input name="nama_lengkap" class="form-control fw-600 bg-light" placeholder="Nama lengkap" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom small">Email Kerja</label>
                            <div class="input-group shadow-none">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-envelope text-muted small"></i></span>
                                <input type="email" name="email" class="form-control border-start-0 bg-light" placeholder="staff@rs-sehat.com" required>
                            </div>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom small">Password Awal</label>
                            <div class="input-group shadow-none">
                                <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-key text-muted small"></i></span>
                                <input type="password" name="password" class="form-control border-start-0 bg-light" value="password" required>
                            </div>
                        </div>
                        <div class="col-12 border-top pt-2 mt-3">
                            <label class="form-label-custom small">Departemen / Unit Kerja</label>
                            <select name="department_id" class="form-select select2-init" required>
                                <option value="">Pilih Departemen</option>
                                @foreach($departments as $department)
                                    <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label-custom small">Peran Utama (Role)</label>
                            <select name="role_id" class="form-select fw-bold shadow-none" required>
                                <option value="">Pilih Role Akses</option>
                                @foreach($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <button class="btn btn-simrs-primary w-100 mt-4 py-3 fw-800 shadow-sm border-0">
                        <i class="fa-solid fa-plus-circle me-2"></i>Daftarkan Staff
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tabel Daftar User -->
    <div class="col-xl-8">
        <div class="page-header-bar mb-3 p-3 bg-white border rounded-3 shadow-sm d-flex align-items-center justify-content-between">
            <h5 class="fw-800 text-simrs-gray-900 mb-0 d-none d-md-block"><i class="fa-solid fa-users me-2 text-simrs-primary"></i>Direktori Staff</h5>
            <form class="d-flex gap-2 flex-grow-1 flex-md-grow-0" style="min-width: 300px;" method="GET">
                <div class="input-group shadow-none">
                    <span class="input-group-text bg-light border-end-0"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                    <input type="search" name="q" value="{{ request('q') }}" class="form-control border-start-0 bg-light" placeholder="Cari NIP atau nama...">
                    <button class="btn btn-dark px-3 fw-bold border-0">Cari</button>
                </div>
            </form>
        </div>

        <div class="simrs-card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                        <tr class="small text-muted text-uppercase fw-bold">
                            <th class="ps-4">Profil Staff</th>
                            <th>Unit & Akses</th>
                            <th>Aktivitas Terakhir</th>
                            <th class="text-center">Status</th>
                            <th class="pe-4 text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="user-avatar-sm shadow-sm" style="background: var(--simrs-primary-pale); color: var(--simrs-primary); width: 42px; height: 42px; font-size: 1.1rem; border: 1px solid var(--simrs-primary-light);">
                                        {{ strtoupper(substr($user->display_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-800 text-simrs-gray-900">{{ $user->display_name }}</div>
                                        <div class="text-mono small text-muted"><i class="fa-solid fa-id-badge me-1 opacity-50"></i>{{ $user->nip ?: 'NIP -' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="small fw-700 text-simrs-gray-800 mb-1">{{ $user->department?->nama_depart ?: 'Internal' }}</div>
                                <span class="badge bg-secondary-subtle text-secondary border border-secondary-subtle px-2 py-0" style="font-size: 0.65rem;">
                                    <i class="fa-solid fa-shield-halved me-1 opacity-50"></i>{{ $user->roles->pluck('nama_peran')->first() ?: 'Guest' }}
                                </span>
                            </td>
                            <td>
                                @if($user->last_login_at)
                                    <div class="small fw-600 text-simrs-gray-800">{{ $user->last_login_at->format('d/m/Y') }}</div>
                                    <div class="text-mono small text-muted">{{ $user->last_login_at->format('H:i') }} WIB</div>
                                @else
                                    <span class="badge bg-light text-muted border px-2 py-1 small italic">Belum pernah login</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <div class="form-check form-switch d-inline-block">
                                    <input class="form-check-input status-toggle" type="checkbox" role="switch" @checked($user->is_active) data-id="{{ $user->id }}">
                                </div>
                            </td>
                            <td class="pe-4 text-end">
                                <button class="btn btn-sm btn-light border shadow-sm px-3 fw-600 text-simrs-primary" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#modalEditUser" 
                                    data-user='@json($user->load(['roles', 'department']))'>
                                    Edit
                                </button>
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form d-inline-block ms-1">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger-subtle text-danger border border-danger-subtle shadow-sm px-2">
                                        <i class="fa-solid fa-trash-can"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="brand-icon shadow-none bg-light text-muted mx-auto mb-3" style="width: 64px; height: 64px; font-size: 2rem;">
                                    <i class="fa-solid fa-user-slash"></i>
                                </div>
                                <h5 class="fw-800 text-simrs-gray-900 mb-1">Data Tidak Ditemukan</h5>
                                <div class="text-muted small">Coba gunakan kata kunci pencarian yang lain.</div>
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

<!-- Modal Edit User -->
<div class="modal fade" id="modalEditUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditUser" method="POST" class="modal-content border-0 shadow-lg">
            @csrf @method('PATCH')
            <div class="modal-header bg-primary text-white border-0">
                <h5 class="modal-title fw-800"><i class="fa-solid fa-user-gear me-2"></i>Update Data Staff</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-custom">NIP</label>
                        <input name="nip" id="edit_nip" class="form-control text-mono fw-bold" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">No. Telepon</label>
                        <input name="no_telepon" id="edit_telp" class="form-control">
                    </div>
                    <div class="col-12">
                        <label class="form-label-custom">Nama Lengkap</label>
                        <input name="nama_lengkap" id="edit_nama" class="form-control fw-bold" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label-custom">Email</label>
                        <input type="email" name="email" id="edit_email" class="form-control" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label-custom">Password Baru (Kosongkan jika tidak ganti)</label>
                        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Departemen</label>
                        <select name="department_id" id="edit_dept" class="form-select" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-custom">Role Akses</label>
                        <select name="role_id" id="edit_role" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-simrs-outline px-4 fw-bold border-0" data-bs-dismiss="modal">Batal</button>
                <button class="btn btn-simrs-primary px-4 fw-800">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

@section('scripts')
<script>
    // Handle Edit Modal Data
    const modalEditUser = document.getElementById('modalEditUser');
    modalEditUser.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const user = JSON.parse(button.getAttribute('data-user'));
        const form = document.getElementById('formEditUser');
        
        form.action = `/admin/users/${user.id}/update`;
        document.getElementById('edit_nip').value = user.nip;
        document.getElementById('edit_telp').value = user.no_telepon || '';
        document.getElementById('edit_nama').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_dept').value = user.department_id;
        document.getElementById('edit_role').value = user.roles.length ? user.roles[0].id : '';
    });

    // Handle Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Akun Staff?',
                text: "Data yang telah dihapus tidak dapat dipulihkan kembali.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#C5372C',
                cancelButtonColor: '#64748B',
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4 border-0 shadow-lg',
                    title: 'fw-800',
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    });
</script>
@endsection

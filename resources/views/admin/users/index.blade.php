@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Akses & Staf')
@section('page-subtitle', 'Pusat kontrol kredensial, peran pengguna, dan departemen sistem')

@section('content')
<!-- Metric Cards Row -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative bg-white" style="transition: transform 0.3s ease;">
            <div class="position-absolute top-0 inset-e-0 p-3 opacity-10">
                <i class="fa-solid fa-users-rays" style="font-size: 5rem;"></i>
            </div>
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="d-flex align-items-center justify-content-center bg-primary bg-gradient text-white rounded-circle shadow" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-user-doctor"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Total Staf Terdaftar</div>
                    <div class="h2 fw-bolder text-dark mb-0 lh-1">{{ $users->total() }}</div>
                </div>
            </div>
            <div class="card-footer bg-light border-0 px-4 py-2 small text-muted fw-semibold">
                <i class="fa-solid fa-arrow-trend-up text-success me-1"></i> Data diperbarui secara real-time
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative bg-white">
            <div class="position-absolute top-0 inset-e-0 p-3 opacity-10">
                <i class="fa-solid fa-network-wired" style="font-size: 5rem;"></i>
            </div>
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="d-flex align-items-center justify-content-center bg-info bg-gradient text-white rounded-circle shadow" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-sitemap"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Struktur Unit</div>
                    <div class="h2 fw-bolder text-dark mb-0 lh-1">{{ count($departments) }}</div>
                </div>
            </div>
            <div class="card-footer bg-light border-0 px-4 py-2 small text-muted fw-semibold">
                <i class="fa-solid fa-building me-1"></i> Departemen & Instalasi Medis
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 position-relative bg-white">
            <div class="position-absolute top-0 inset-e-0 p-3 opacity-10">
                <i class="fa-solid fa-shield-virus" style="font-size: 5rem;"></i>
            </div>
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="d-flex align-items-center justify-content-center bg-warning bg-gradient text-white rounded-circle shadow" style="width: 60px; height: 60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-user-shield"></i>
                </div>
                <div>
                    <div class="text-uppercase fw-bold text-muted mb-1" style="font-size: 0.75rem; letter-spacing: 1px;">Level Otorisasi</div>
                    <div class="h2 fw-bolder text-dark mb-0 lh-1">{{ count($roles) }}</div>
                </div>
            </div>
            <div class="card-footer bg-light border-0 px-4 py-2 small text-muted fw-semibold">
                <i class="fa-solid fa-key me-1"></i> Berbasis Role-Based Access Control
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="row g-4">
    <!-- Registration Form Panel -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 position-sticky" style="top: 90px; z-index: 10;">
            <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4">
                <div class="d-flex align-items-center gap-2">
                    <span class="bg-primary text-white rounded-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                        <i class="fa-solid fa-user-plus small"></i>
                    </span>
                    <h5 class="fw-bold mb-0 text-dark">Registrasi Staf</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="form-floating mb-3">
                        <input type="text" name="nip" class="form-control fw-bold text-primary" id="floatingNip" placeholder="NIP" required>
                        <label for="floatingNip" class="text-muted fw-semibold">Nomor Induk Pegawai (NIP)</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="text" name="nama_lengkap" class="form-control fw-bold" id="floatingName" placeholder="Nama Lengkap" required>
                        <label for="floatingName" class="text-muted fw-semibold">Nama Lengkap & Gelar</label>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-12">
                            <div class="form-floating">
                                <input type="email" name="email" class="form-control" id="floatingEmail" placeholder="Email" required>
                                <label for="floatingEmail" class="text-muted fw-semibold">Alamat Email Aktif</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="tel" name="no_telepon" class="form-control" id="floatingTelp" placeholder="Telepon">
                                <label for="floatingTelp" class="text-muted fw-semibold">Nomor Telepon</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <input type="password" name="password" class="form-control" id="floatingPass" value="password" required>
                                <label for="floatingPass" class="text-muted fw-semibold">Password Default</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-bold small text-uppercase mb-2">Penempatan Unit Kerja</label>
                        <select name="department_id" class="form-select form-select-lg shadow-none border-light-subtle bg-light fw-semibold" required style="font-size: 0.95rem;">
                            <option value="" disabled selected>Pilih Departemen...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-bold small text-uppercase mb-2">Hak Akses Sistem</label>
                        <select name="role_id" class="form-select form-select-lg shadow-none border-light-subtle bg-light fw-semibold" required style="font-size: 0.95rem;">
                            <option value="" disabled selected>Pilih Role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg w-100 fw-bold shadow-sm rounded-3 transition-hover" style="background: linear-gradient(135deg, var(--simrs-primary), var(--simrs-primary-light)); border: none;">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>Simpan Data Staf
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Directory Panel -->
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 h-100">
            <!-- Table Action Bar -->
            <div class="card-header bg-white border-bottom p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Direktori Pengguna Sistem</h5>
                        <p class="text-muted small mb-0">Manajemen status aktif dan hak akses setiap individu.</p>
                    </div>
                    <form class="d-flex" method="GET" style="min-width: 320px;">
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0 text-muted px-3 rounded-start-pill"><i class="fa-solid fa-magnifying-glass"></i></span>
                            <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-light border-start-0 ps-0 shadow-none" placeholder="Cari NIP, Nama, atau Email...">
                            <button type="submit" class="btn btn-dark fw-semibold px-4 rounded-end-pill">Cari</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.95rem;">
                    <thead class="bg-light bg-opacity-75">
                        <tr>
                            <th class="border-0 px-4 py-3 text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Profil & Kredensial</th>
                            <th class="border-0 py-3 text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Posisi / Otoritas</th>
                            <th class="border-0 py-3 text-muted fw-bold text-uppercase text-center" style="font-size: 0.75rem; letter-spacing: 0.5px;">Status Akun</th>
                            <th class="border-0 px-4 py-3 text-end text-muted fw-bold text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="border-top-0">
                    @forelse($users as $user)
                        <tr>
                            <td class="px-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="d-flex align-items-center justify-content-center rounded-circle bg-primary bg-opacity-10 text-primary fw-bolder shadow-sm" style="width: 48px; height: 48px; font-size: 1.2rem;">
                                        {{ strtoupper(substr($user->display_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-1">{{ $user->display_name }}</div>
                                        <div class="d-flex align-items-center gap-2 small text-muted">
                                            <span class="text-primary font-monospace fw-semibold"><i class="fa-regular fa-id-badge me-1"></i>{{ $user->nip ?: 'N/A' }}</span>
                                            <span class="text-black-50">&bull;</span>
                                            <span>{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-dark mb-1">{{ $user->department?->nama_depart ?: 'Tidak Terikat Unit' }}</div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1" style="font-weight: 700; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-shield-cat me-1 opacity-75"></i> {{ strtoupper($user->roles->pluck('nama_peran')->first() ?: 'GUEST') }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex flex-column align-items-center justify-content-center">
                                    <div class="form-check form-switch mb-1">
                                        <input class="form-check-input shadow-none" type="checkbox" role="switch" style="cursor: pointer; width: 2.5em; height: 1.25em;" @checked($user->is_active) disabled>
                                    </div>
                                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $user->is_active ? 'text-success' : 'text-danger' }} rounded-pill px-2" style="font-size: 0.65rem;">
                                        {{ $user->is_active ? 'ACTIVE' : 'SUSPENDED' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-end">
                                <div class="btn-group shadow-sm">
                                    <button type="button" class="btn btn-sm btn-light border-light-subtle text-primary fw-semibold px-3" data-bs-toggle="modal" data-bs-target="#modalEditUser" data-user='@json($user->load(['roles', 'department']))'>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    
                                    <button type="button" class="btn btn-sm btn-light border-light-subtle text-secondary dropdown-toggle dropdown-toggle-split px-2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <span class="visually-hidden">Toggle Dropdown</span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 p-2">
                                        <li><h6 class="dropdown-header text-uppercase fw-bold text-primary" style="font-size: 0.7rem; letter-spacing: 1px;">Keamanan Akun</h6></li>
                                        <li>
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="dropdown-item py-2 fw-medium rounded-2 {{ $user->is_active ? 'text-warning' : 'text-success' }}">
                                                    <i class="fa-solid {{ $user->is_active ? 'fa-lock' : 'fa-lock-open' }} me-2 w-15px text-center"></i>
                                                    {{ $user->is_active ? 'Blokir Sementara' : 'Buka Blokir Akun' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider opacity-10"></li>
                                        <li><h6 class="dropdown-header text-uppercase fw-bold text-danger" style="font-size: 0.7rem; letter-spacing: 1px;">Zona Berbahaya</h6></li>
                                        <li>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 fw-medium rounded-2 text-danger">
                                                    <i class="fa-solid fa-trash-can me-2 w-15px text-center"></i>Hapus Permanen
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-2 text-muted" style="font-size: 0.65rem;">
                                    Login: {{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Belum pernah' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="d-inline-flex align-items-center justify-content-center bg-light rounded-circle mb-3" style="width: 80px; height: 80px;">
                                    <i class="fa-solid fa-users-slash fs-1 text-muted opacity-50"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-1">Pencarian Tidak Membuahkan Hasil</h5>
                                <p class="text-muted small">Silakan coba dengan kata kunci atau parameter yang berbeda.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="card-footer bg-white border-top p-4 d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edit User (Redesigned) -->
<div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditUser" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf @method('PATCH')
            <div class="modal-header bg-primary bg-gradient text-white border-0 px-4 py-3">
                <h5 class="modal-title fw-bold d-flex align-items-center gap-2" id="modalEditUserLabel">
                    <i class="fa-solid fa-user-pen opacity-75"></i> Perbarui Kredensial Staf
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 bg-light">
                <div class="card border-0 shadow-sm rounded-3">
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="text" name="nip" id="edit_nip" class="form-control fw-bold" placeholder="NIP" required>
                                    <label class="text-muted fw-semibold">Nomor Induk (NIP)</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-floating">
                                    <input type="tel" name="no_telepon" id="edit_telp" class="form-control" placeholder="Telepon">
                                    <label class="text-muted fw-semibold">Telepon / Kontak</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="text" name="nama_lengkap" id="edit_nama" class="form-control fw-bold" placeholder="Nama" required>
                                    <label class="text-muted fw-semibold">Nama Lengkap Sesuai Gelar</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-floating">
                                    <input type="email" name="email" id="edit_email" class="form-control" placeholder="Email" required>
                                    <label class="text-muted fw-semibold">Alamat Email Akses</label>
                                </div>
                            </div>
                            
                            <div class="col-12">
                                <hr class="border-secondary opacity-10 my-1">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold small text-uppercase mb-2">Penempatan Unit</label>
                                <select name="department_id" id="edit_dept" class="form-select shadow-none bg-light fw-semibold" required>
                                    @foreach($departments as $department)
                                        <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label text-muted fw-bold small text-uppercase mb-2">Otoritas Role</label>
                                <select name="role_id" id="edit_role" class="form-select shadow-none bg-light fw-semibold" required>
                                    @foreach($roles as $role)
                                        <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-warning bg-warning bg-opacity-10 border-warning border-opacity-25 d-flex align-items-center gap-3 py-2 px-3 mb-0 rounded-3">
                                    <i class="fa-solid fa-triangle-exclamation text-warning fs-4"></i>
                                    <div>
                                        <label class="fw-bold text-dark small mb-1">Reset Password Sistem</label>
                                        <input type="password" name="password" class="form-control form-control-sm bg-white" placeholder="Biarkan kosong jika tidak diubah">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer bg-white border-top p-3 d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-light fw-semibold border px-4 rounded-3" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-3 shadow-sm transition-hover">
                    <i class="fa-solid fa-check me-2"></i>Terapkan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .transition-hover { transition: all 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-2px); }
    .form-floating > label { font-size: 0.85rem; }
    .form-control:focus, .form-select:focus { border-color: var(--simrs-primary); box-shadow: 0 0 0 0.25rem rgba(11, 100, 119, 0.1); }
</style>
@endsection

@section('scripts')
<script>
    // Handle Edit Modal Data Initialization
    const modalEditUser = document.getElementById('modalEditUser');
    modalEditUser?.addEventListener('show.bs.modal', function (event) {
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

    // Handle Delete Confirmation (SweetAlert2)
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Kredensial?',
                html: "Anda akan menghapus data staf secara <b>permanen</b>.<br>Pastikan staf ini tidak memiliki riwayat audit sistem.",
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#EF4444',
                cancelButtonColor: '#cbd5e1',
                confirmButtonText: 'Ya, Eksekusi!',
                cancelButtonText: 'Batal',
                customClass: {
                    popup: 'rounded-4 border-0 shadow-lg',
                    title: 'fw-bolder text-dark',
                    htmlContainer: 'text-muted small',
                    confirmButton: 'fw-bold rounded-pill px-4',
                    cancelButton: 'fw-bold rounded-pill px-4 text-dark'
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
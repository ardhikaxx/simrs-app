@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen Akses & Staf')
@section('page-subtitle', 'Pusat kontrol kredensial, peran pengguna, dan departemen sistem')

@section('content')
<!-- Metric Cards Row -->
<div class="row g-4 mb-5">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover bg-white">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-user-doctor fs-4"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Total Staf Terdaftar</div>
                    <h3 class="fw-bold text-dark mb-0">{{ $users->total() }} <span class="fs-6 fw-medium text-muted">User</span></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover bg-white">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-info bg-opacity-10 text-info rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-sitemap fs-4"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Struktur Unit</div>
                    <h3 class="fw-bold text-dark mb-0">{{ count($departments) }} <span class="fs-6 fw-medium text-muted">Unit</span></h3>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 h-100 transition-hover bg-white">
            <div class="card-body p-4 d-flex align-items-center gap-4">
                <div class="bg-warning bg-opacity-10 text-warning rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 56px; height: 56px;">
                    <i class="fa-solid fa-user-shield fs-4"></i>
                </div>
                <div>
                    <div class="text-muted fw-semibold small text-uppercase mb-1" style="letter-spacing: 0.5px;">Level Otorisasi</div>
                    <h3 class="fw-bold text-dark mb-0">{{ count($roles) }} <span class="fs-6 fw-medium text-muted">Role</span></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Area -->
<div class="row g-4">
    <!-- Registration Form Panel -->
    <div class="col-xl-4">
        <div class="card border-0 shadow-sm rounded-4 position-sticky bg-white" style="top: 90px; z-index: 10;">
            <div class="card-header bg-white border-bottom border-light p-4 d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 text-primary rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 44px; height: 44px;">
                    <i class="fa-solid fa-user-plus fs-5"></i>
                </div>
                <h5 class="fw-bold mb-0 text-dark">Registrasi Staf</h5>
            </div>
            <div class="card-body p-4">
                <form action="{{ route('admin.users.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    
                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Nomor Induk Pegawai (NIP)</label>
                        <input type="text" name="nip" class="form-control bg-light border-light shadow-none focus-ring-0 py-2 fw-bold text-primary font-monospace" placeholder="199XXXXXXXX" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Nama Lengkap & Gelar</label>
                        <input type="text" name="nama_lengkap" class="form-control bg-light border-light shadow-none focus-ring-0 py-2 fw-semibold" placeholder="Contoh: dr. Budi Santoso, Sp.PD" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Alamat Email Akses</label>
                        <input type="email" name="email" class="form-control bg-light border-light shadow-none focus-ring-0 py-2" placeholder="nama@rs-core.id" required>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Telepon</label>
                            <input type="tel" name="no_telepon" class="form-control bg-light border-light shadow-none focus-ring-0 py-2" placeholder="08XXXXXXXXX">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Password Default</label>
                            <input type="password" name="password" class="form-control bg-light border-light shadow-none focus-ring-0 py-2" value="password" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Penempatan Unit Kerja</label>
                        <select name="department_id" class="form-select bg-light border-light shadow-none focus-ring-0 py-2 select2-init" required>
                            <option value="">Pilih Departemen...</option>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label text-muted fw-semibold small text-uppercase mb-2" style="letter-spacing: 0.5px;">Hak Akses Sistem</label>
                        <select name="role_id" class="form-select bg-light border-light shadow-none focus-ring-0 py-2 select2-init" required>
                            <option value="">Pilih Role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-3 fw-bold rounded-pill shadow-sm transition-hover">
                        <i class="fa-solid fa-cloud-arrow-up me-2"></i>SIMPAN DATA STAF
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Data Directory Panel -->
    <div class="col-xl-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden bg-white h-100">
            <!-- Table Action Bar -->
            <div class="card-header bg-white border-bottom border-light p-4">
                <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                    <div>
                        <h5 class="fw-bold mb-1 text-dark">Direktori Pengguna Sistem</h5>
                        <p class="text-muted small mb-0 fw-medium">Manajemen status aktif dan hak akses setiap individu.</p>
                    </div>
                    <form class="d-flex" method="GET" style="min-width: 320px;">
                        <div class="input-group bg-light rounded-pill px-3 py-1 border border-light">
                            <span class="input-group-text bg-transparent border-0 text-muted"><i class="fa-solid fa-magnifying-glass small"></i></span>
                            <input type="search" name="q" value="{{ request('q') }}" class="form-control bg-transparent border-0 shadow-none focus-ring-0 py-2 small" placeholder="Cari NIP, Nama, atau Email...">
                        </div>
                    </form>
                </div>
            </div>

            <!-- Main Table -->
            <div class="table-responsive bg-white">
                <table class="table table-hover table-borderless align-middle mb-0 custom-table">
                    <thead class="bg-light">
                        <tr class="text-muted small fw-semibold text-uppercase" style="letter-spacing: 0.5px;">
                            <th class="ps-4 py-3 rounded-start">Profil & Kredensial</th>
                            <th class="py-3">Posisi / Otoritas</th>
                            <th class="py-3 text-center">Status Akun</th>
                            <th class="pe-4 py-3 text-end rounded-end">Tindakan</th>
                        </tr>
                    </thead>
                    <tbody class="border-top border-light">
                    @forelse($users as $user)
                        <tr class="transition-hover">
                            <td class="ps-4 py-3">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center fw-bold shadow-xs flex-shrink-0" style="width: 48px; height: 48px; font-size: 1.25rem;">
                                        {{ strtoupper(substr($user->display_name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark mb-1">{{ $user->display_name }}</div>
                                        <div class="small text-muted fw-medium font-monospace d-flex align-items-center gap-2">
                                            <span class="text-primary opacity-75"><i class="fa-regular fa-id-badge me-1"></i>{{ $user->nip ?: 'N/A' }}</span>
                                            <span class="opacity-25">|</span>
                                            <span>{{ $user->email }}</span>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="fw-semibold text-dark mb-1">{{ $user->department?->nama_depart ?: 'Tidak Terikat Unit' }}</div>
                                <span class="badge bg-secondary bg-opacity-10 text-secondary border border-secondary border-opacity-25 px-2 py-1 fw-semibold" style="font-size: 0.65rem; letter-spacing: 0.5px;">
                                    <i class="fa-solid fa-shield-halved me-1 opacity-75"></i> {{ strtoupper($user->roles->pluck('nama_peran')->first() ?: 'GUEST') }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <div class="form-check form-switch p-0 m-0">
                                        <input class="form-check-input ms-0 shadow-none border-light" type="checkbox" role="switch" style="cursor: pointer; width: 2.2em; height: 1.1em;" @checked($user->is_active) disabled>
                                    </div>
                                    <span class="badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }} bg-opacity-10 {{ $user->is_active ? 'text-success' : 'text-danger' }} rounded-pill px-2 fw-bold" style="font-size: 0.6rem;">
                                        {{ $user->is_active ? 'ACTIVE' : 'SUSPENDED' }}
                                    </span>
                                </div>
                            </td>
                            <td class="pe-4 py-3 text-end">
                                <div class="dropdown">
                                    <button class="btn btn-light btn-sm rounded-circle border-0 shadow-none d-flex align-items-center justify-content-center mx-auto me-md-0 ms-md-auto" style="width: 32px; height: 32px;" data-bs-toggle="dropdown">
                                        <i class="fa-solid fa-ellipsis-vertical text-muted"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 rounded-4 p-2">
                                        <li>
                                            <button class="dropdown-item py-2 rounded-3 small fw-medium" data-bs-toggle="modal" data-bs-target="#modalEditUser" data-user='@json($user->load(['roles', 'department']))'>
                                                <i class="fa-solid fa-user-pen me-2 opacity-50 text-primary"></i>Perbarui Data
                                            </button>
                                        </li>
                                        <li>
                                            <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                                @csrf @method('PATCH')
                                                <button type="submit" class="dropdown-item py-2 rounded-3 small fw-medium {{ $user->is_active ? 'text-warning' : 'text-success' }}">
                                                    <i class="fa-solid {{ $user->is_active ? 'fa-user-lock' : 'fa-user-check' }} me-2 opacity-50"></i>
                                                    {{ $user->is_active ? 'Blokir Sementara' : 'Buka Blokir' }}
                                                </button>
                                            </form>
                                        </li>
                                        <li><hr class="dropdown-divider opacity-10"></li>
                                        <li>
                                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="delete-form">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="dropdown-item py-2 rounded-3 small fw-semibold text-danger">
                                                    <i class="fa-solid fa-trash-can me-2"></i>Hapus Permanen
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                                <div class="mt-2 text-muted fw-medium" style="font-size: 0.65rem;">
                                    <i class="fa-regular fa-clock me-1"></i>{{ $user->last_login_at ? $user->last_login_at->diffForHumans() : 'Never Active' }}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-5">
                                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center mb-3 shadow-sm" style="width: 80px; height: 80px;">
                                    <i class="fa-solid fa-users-slash fs-1 text-muted opacity-50"></i>
                                </div>
                                <h6 class="fw-bold text-dark mb-1">Data Tidak Ditemukan</h6>
                                <p class="text-muted small mb-0">Coba kata kunci pencarian yang lain.</p>
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            @if($users->hasPages())
                <div class="p-4 border-top border-light bg-white rounded-bottom-4 d-flex justify-content-center">
                    {{ $users->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Modal Edit User -->
<div class="modal fade" id="modalEditUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form id="formEditUser" method="POST" class="modal-content border-0 shadow-lg rounded-4 overflow-hidden">
            @csrf @method('PATCH')
            <div class="modal-header bg-primary bg-gradient text-white border-0 p-4">
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white bg-opacity-25 rounded-3 d-flex align-items-center justify-content-center flex-shrink-0" style="width: 40px; height: 40px;">
                        <i class="fa-solid fa-user-pen fs-5"></i>
                    </div>
                    <h5 class="modal-title fw-bold">Perbarui Kredensial Staf</h5>
                </div>
                <button type="button" class="btn-close btn-close-white shadow-none" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body p-4 bg-white">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">NIP</label>
                        <input type="text" name="nip" id="edit_nip" class="form-control bg-light border-light shadow-none focus-ring-0 fw-bold" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Telepon</label>
                        <input type="tel" name="no_telepon" id="edit_telp" class="form-control bg-light border-light shadow-none focus-ring-0">
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" id="edit_nama" class="form-control bg-light border-light shadow-none focus-ring-0 fw-bold" required>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Email Akses</label>
                        <input type="email" name="email" id="edit_email" class="form-control bg-light border-light shadow-none focus-ring-0" required>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Unit</label>
                        <select name="department_id" id="edit_dept" class="form-select bg-light border-light shadow-none focus-ring-0 fw-semibold" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted fw-semibold small text-uppercase" style="letter-spacing: 0.5px;">Role</label>
                        <select name="role_id" id="edit_role" class="form-select bg-light border-light shadow-none focus-ring-0 fw-semibold" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-12 pt-2">
                        <div class="alert alert-warning bg-warning bg-opacity-10 border-0 rounded-4 d-flex align-items-center gap-3 p-3 mb-0">
                            <i class="fa-solid fa-key text-warning fs-4"></i>
                            <div class="w-100">
                                <label class="fw-bold text-dark small mb-1">Reset Password</label>
                                <input type="password" name="password" class="form-control form-control-sm bg-white border-0 shadow-none" placeholder="Isi hanya jika ingin reset password">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 p-4 bg-light bg-opacity-50">
                <button type="button" class="btn btn-light border border-light-subtle text-muted fw-bold px-4 rounded-pill transition-hover hover-bg-gray" data-bs-dismiss="modal">Tutup</button>
                <button type="submit" class="btn btn-primary fw-bold px-4 rounded-pill shadow-sm transition-hover">
                    <i class="fa-solid fa-check-double me-2"></i>Terapkan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    .focus-ring-0:focus { box-shadow: none; border-color: #dee2e6; }
    .hover-bg-gray:hover { background-color: #f8f9fa !important; }
    .transition-hover { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .transition-hover:hover { transform: translateY(-2px); box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05) !important; }
    table .transition-hover:hover { background-color: #f8f9fa !important; transform: none; box-shadow: none !important; }
    .shadow-xs { box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); }
    .w-15px { width: 15px; }
</style>
@endsection

@section('scripts')
<script>
    // Handle Edit Modal Data
    const modalEditUser = document.getElementById('modalEditUser');
    modalEditUser?.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const user = JSON.parse(button.getAttribute('data-user'));
        const form = document.getElementById('formEditUser');
        
        form.action = `/admin/users/${user.id}/update`;
        document.getElementById('edit_nip').value = user.nip;
        document.getElementById('edit_telp').value = user.no_telepon || '';
        document.getElementById('edit_nama').value = user.display_name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_dept').value = user.department_id;
        document.getElementById('edit_role').value = user.roles.length ? user.roles[0].id : '';
    });

    // Handle Delete Confirmation
    document.querySelectorAll('.delete-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Hapus Data Staf?',
                text: "Kredensial dan riwayat akses akan dihapus secara permanen dari sistem.",
                icon: 'error',
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
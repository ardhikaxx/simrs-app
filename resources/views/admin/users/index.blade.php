@extends('layouts.app')

@section('title', 'Manajemen User')
@section('page-title', 'Manajemen User & Role')
@section('page-subtitle', 'Akun staff, role, departemen, dan status akses')

@section('content')
<div class="row g-3">
    <div class="col-xl-4">
        <form action="{{ route('admin.users.store') }}" method="POST" class="simrs-card">
            @csrf
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-user-plus"></i>Tambah Staf</div></div>
            <div class="simrs-card-body">
                <div class="row g-3">
                    <div class="col-6"><label class="form-label-custom">NIP</label><input name="nip" class="form-control" required></div>
                    <div class="col-6"><label class="form-label-custom">Telepon</label><input name="no_telepon" class="form-control"></div>
                    <div class="col-12"><label class="form-label-custom">Nama Lengkap</label><input name="nama_lengkap" class="form-control" required></div>
                    <div class="col-12"><label class="form-label-custom">Email</label><input type="email" name="email" class="form-control" required></div>
                    <div class="col-12"><label class="form-label-custom">Password</label><input type="password" name="password" class="form-control" value="password" required></div>
                    <div class="col-12">
                        <label class="form-label-custom">Departemen</label>
                        <select name="department_id" class="form-select" required>
                            @foreach($departments as $department)
                                <option value="{{ $department->id }}">{{ $department->nama_depart }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="form-label-custom">Role</label>
                        <select name="role_id" class="form-select" required>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->nama_peran }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button class="btn btn-simrs-primary w-100 mt-3"><i class="fa-solid fa-floppy-disk me-1"></i>Simpan Staf</button>
            </div>
        </form>
    </div>
    <div class="col-xl-8">
        <div class="simrs-card">
            <div class="simrs-card-header"><div class="simrs-card-title"><i class="fa-solid fa-users-gear"></i>Daftar Staf</div></div>
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead><tr><th>Staf</th><th>Departemen</th><th>Role</th><th>Login Terakhir</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                    @forelse($users as $user)
                        <tr>
                            <td><strong>{{ $user->display_name }}</strong><div class="small text-muted text-mono">{{ $user->nip }} - {{ $user->email }}</div></td>
                            <td>{{ $user->department?->nama_depart ?: '-' }}</td>
                            <td>{{ $user->roles->pluck('nama_peran')->join(', ') ?: '-' }}</td>
                            <td>{{ $user->last_login_at?->format('d/m/Y H:i') ?: '-' }}</td>
                            <td><span class="badge-status {{ $user->is_active ? 'status-aman' : 'status-kritis' }}">{{ $user->is_active ? 'Aktif' : 'Nonaktif' }}</span></td>
                            <td>
                                <form action="{{ route('admin.users.toggle', $user) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <button class="btn btn-sm btn-simrs-outline">{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada staf.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="p-3">{{ $users->links('pagination::bootstrap-5') }}</div>
        </div>
    </div>
</div>
@endsection

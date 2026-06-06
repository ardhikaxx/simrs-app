<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserManagementController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index', [
            'users' => User::with(['department', 'roles'])->latest()->paginate(15),
            'departments' => Department::where('is_active', true)->orderBy('nama_depart')->get(),
            'roles' => Role::orderBy('level')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nip' => ['required', 'string', 'max:20', 'unique:users,nip'],
            'nama_lengkap' => ['required', 'string', 'max:150'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'no_telepon' => ['nullable', 'string', 'max:20'],
            'department_id' => ['required', 'exists:departments,id'],
            'role_id' => ['required', 'exists:roles,id'],
        ]);

        $roleId = $data['role_id'];
        unset($data['role_id']);
        $data['name'] = $data['nama_lengkap'];
        $data['is_active'] = true;

        $user = User::create($data);
        $user->roles()->attach($roleId, [
            'assigned_by' => auth('staff')->id(),
            'assigned_at' => now(),
        ]);

        return back()->with('swal_success', 'Staf baru berhasil ditambahkan.');
    }

    public function toggle(User $user): RedirectResponse
    {
        abort_if($user->id === auth('staff')->id(), 403, 'Tidak bisa menonaktifkan akun sendiri.');

        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('swal_success', 'Status akun berhasil diperbarui.');
    }
}

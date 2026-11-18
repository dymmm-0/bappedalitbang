<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bidang;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class User_Controller extends Controller
{
    private const MANAGED_ROLES = [
        'kepala_badan',
        'super_admin',
        'kepala_sub_bagian_umum',
        'admin_media',
        'admin_sekretariat',
        'admin_ppepd',
        'admin_ppm',
        'admin_litbang',
        'admin_psda',
        'admin_ikw',
    ];

    public function index()
    {
        abort_if(auth()->user()->cannot('manage_users'), 403);

        $title = 'Data User';
        $data = User::with(['roles', 'bidang'])
            ->where('hidden', false)
            ->whereDoesntHave('roles', fn ($q) => $q->where('name', 'developer'))
            ->orderBy('id')
            ->get();
        $roles = Role::whereIn('name', self::MANAGED_ROLES)->orderBy('id')->get();

        return view('admin.user.user_index', compact('title', 'data', 'roles'));
    }

    public function add()
    {
        abort_if(auth()->user()->cannot('manage_users'), 403);

        $title = 'Tambah User';
        $roles = Role::whereIn('name', self::MANAGED_ROLES)->orderBy('id')->get();
        $bidangList = Bidang::orderBy('nama')->get();

        return view('admin.user.user_add', compact('title', 'roles', 'bidangList'));
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()->cannot('manage_users'), 403);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|in:' . implode(',', self::MANAGED_ROLES),
            'bidang_id' => 'nullable|integer|exists:bidang,id',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'bidang_id' => $data['bidang_id'] ?? null,
        ]);

        $user->syncRoles($data['role']);

        return redirect('admin/user')->with('flash_success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        abort_if(auth()->user()->cannot('manage_users'), 403);

        $title = 'Edit User';
        $dt = User::findOrFail($id);

        abort_if($this->isUntouchable($dt), 404);

        $roles = Role::whereIn('name', self::MANAGED_ROLES)->orderBy('id')->get();
        $bidangList = Bidang::orderBy('nama')->get();

        return view('admin.user.user_edit', compact('dt', 'title', 'roles', 'bidangList'));
    }

    public function update(Request $request, $id)
    {
        abort_if(auth()->user()->cannot('manage_users'), 403);

        $user = User::findOrFail($id);

        abort_if($this->isUntouchable($user), 404);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8',
            'role' => 'required|string|in:' . implode(',', self::MANAGED_ROLES),
            'bidang_id' => 'nullable|integer|exists:bidang,id',
        ]);

        $user->update([
            'name' => $data['name'],
            'bidang_id' => $data['bidang_id'] ?? null,
        ]);

        if (! empty($data['password'])) {
            $user->update(['password' => bcrypt($data['password'])]);
        }

        $user->syncRoles($data['role']);

        return redirect('admin/user')->with('flash_success', 'User berhasil diperbarui.');
    }

    public function delete($id)
    {
        abort_if(auth()->user()->cannot('manage_users'), 403);

        if ($id == auth()->id()) {
            return redirect('admin/user')->with('flash_error', 'Anda tidak dapat menghapus akun sendiri.');
        }

        $user = User::findOrFail($id);

        abort_if($this->isUntouchable($user), 404);

        $user->delete();

        return redirect('admin/user')->with('flash_success', 'User dihapus.');
    }

    /**
     * Akun yang tidak boleh disentuh admin/superadmin: akun developer
     * (kecuali pemiliknya sendiri) dan akun tersembunyi (hidden).
     */
    private function isUntouchable(User $user): bool
    {
        if ($user->hidden) {
            return true;
        }

        return $user->isDeveloper() && $user->id !== auth()->id();
    }
}

<?php

namespace Database\Seeders;

use App\Models\Bidang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        app()['cache']->forget('spatie.permission.cache');

        $this->seedBidang();

        $permissions = $this->allPermissionNames();

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $this->syncRolesFromConfig($permissions);

        $this->seedLegacyRoles();

        $this->migrateLegacyUsers();

        $this->seedKepalaAccount();
        $this->seedSuperAdminAccount();
        $this->seedSubBagianUmumAccount();
        $this->seedAdminMediaAccount();
        $this->seedBidangAdmins();
    }

    /**
     * Pemetaan singkatan bidang -> slug role admin bidang.
     */
    private function bidangRoleMap(): array
    {
        return [
            'SEKRETARIAT' => 'admin_sekretariat',
            'PPEPD' => 'admin_ppepd',
            'PPM' => 'admin_ppm',
            'LITBANG' => 'admin_litbang',
            'IKW' => 'admin_ikw',
            'PSDA' => 'admin_psda',
        ];
    }

    /**
     * Akun Kepala Badan: admin.kepala@admin.com (dulunya admin@admin.com,
     * email lama otomatis dipindahkan ke pola baru).
     */
    private function seedKepalaAccount(): void
    {
        $old = User::where('email', 'admin@admin.com')->first();
        if ($old && ! User::where('email', 'admin.kepala@admin.com')->exists()) {
            $old->update(['email' => 'admin.kepala@admin.com', 'name' => 'Kepala Badan']);
        }

        $user = User::firstOrCreate(
            ['email' => 'admin.kepala@admin.com'],
            ['name' => 'Kepala Badan', 'password' => bcrypt('admin123')]
        );
        $user->update(['name' => 'Kepala Badan']);
        $user->assignRole('kepala_badan');
    }

    /**
     * Akun Super Admin: admin.super@admin.com (akses penuh).
     */
    private function seedSuperAdminAccount(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin.super@admin.com'],
            ['name' => 'Super Admin', 'password' => bcrypt('admin123')]
        );
        $user->update(['name' => 'Super Admin', 'bidang_id' => null]);
        $user->syncRoles('super_admin');
    }

    /**
     * Akun Admin Sekretariat: admin.umum@admin.com (Bidang Sekretariat).
     */
    private function seedSubBagianUmumAccount(): void
    {
        $bidang = Bidang::where('singkatan', 'Sekretariat')->first();

        $user = User::firstOrCreate(
            ['email' => 'admin.umum@admin.com'],
            ['name' => 'Sekretariat', 'password' => bcrypt('admin123')]
        );
        $user->update(['name' => 'Sekretariat', 'bidang_id' => $bidang?->id]);
        $user->syncRoles('admin_sekretariat');
    }

    /**
     * Akun Admin Media: admin.media@admin.com (kelola artikel & dokumentasi).
     * Bidangnya di kosongkan (null) karena kerjanya lintas-bidang/seluruh situs.
     */
    private function seedAdminMediaAccount(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin.media@admin.com'],
            ['name' => 'Admin Media', 'password' => bcrypt('admin123')]
        );
        $user->update(['name' => 'Admin Media', 'bidang_id' => null]);
        $user->syncRoles('admin_media');
    }

    /**
     * Hapus akun developer/hidden, migrasikan user role lama ke role
     * baru, lalu hapus role lama (superadmin, admin_bidang, developer).
     */
    private function migrateLegacyUsers(): void
    {
        User::whereHas('roles', fn ($q) => $q->where('name', 'developer'))->delete();
        User::where('hidden', true)->delete();

        $roleMap = $this->bidangRoleMap();
        $bidangSingkatan = Bidang::pluck('singkatan', 'id')->toArray();

        foreach (User::all() as $user) {
            if ($user->hasRole('superadmin')) {
                $user->syncRoles('kepala_badan');
            } elseif ($user->hasRole('admin_bidang')) {
                $singkatan = $user->bidang_id ? (strtoupper($bidangSingkatan[$user->bidang_id] ?? '')) : '';
                $role = $roleMap[$singkatan] ?? 'admin_sekretariat';
                $user->syncRoles($role);
            }
        }

        Role::whereIn('name', ['superadmin', 'admin_bidang', 'developer', 'kepala_bidang'])->delete();
    }

    /**
     * Nama permission: manage_<slug> untuk tiap modul CMS + permission khusus.
     */
    private function allPermissionNames(): array
    {
        $names = array_map(
            fn ($slug) => 'manage_' . $slug,
            array_keys(config('cms.modules', []))
        );

        return array_values(array_unique(array_merge($names, [
            'manage_settings',
            'manage_media',
            'manage_menus',
            'manage_home_sections',
            'manage_artikel',
            'manage_kategori',
            'manage_komentar',
            'manage_users',
            'manage_roles',
        ])));
    }

    private function syncRolesFromConfig(array $allPermissions): void
    {
        foreach (config('rbac.roles', []) as $roleName => $config) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);

            $perms = $config['permissions'] ?? [];

            if (in_array('*', $perms, true)) {
                $role->syncPermissions($allPermissions);
            } else {
                $role->syncPermissions($perms);
            }
        }
    }

    /**
     * Role lama yang masih dipakai user yang sudah ada, dipertahankan
     * agar akun lama tidak kehilangan akses.
     */
    private function seedLegacyRoles(): void
    {
        $editor = Role::firstOrCreate(['name' => 'admin_konten', 'guard_name' => 'web']);
        $editor->syncPermissions(array_filter($this->allPermissionNames(), fn ($p) => $p !== 'manage_users' && $p !== 'manage_roles' && $p !== 'manage_settings' && $p !== 'manage_kepala'));

        $operator = Role::firstOrCreate(['name' => 'operator', 'guard_name' => 'web']);
        $operator->syncPermissions(['manage_artikel', 'manage_komentar']);
    }

    private function seedBidang(): void
    {
        $defaults = [
            ['nama' => 'Sekretariat', 'singkatan' => 'Sekretariat', 'urutan' => 1],
            ['nama' => 'Bidang Perencanaan Pengendalian dan Evaluasi Pembangunan Daerah', 'singkatan' => 'PPEPD', 'urutan' => 2],
            ['nama' => 'Bidang Pemerintahan dan Pembangunan Manusia', 'singkatan' => 'PPM', 'urutan' => 3],
            ['nama' => 'Bidang Penelitian & Pengembangan', 'singkatan' => 'Litbang', 'urutan' => 4],
            ['nama' => 'Bidang Infrastruktur dan Kewilayahan', 'singkatan' => 'IKW', 'urutan' => 5],
            ['nama' => 'Bidang Ekonomi dan Sumber Daya Alam', 'singkatan' => 'PSDA', 'urutan' => 6],
        ];

        foreach ($defaults as $bidang) {
            Bidang::firstOrCreate(
                ['nama' => $bidang['nama'], 'singkatan' => $bidang['singkatan']],
                array_merge($bidang, [
                    'urutan' => $bidang['urutan'],
                    'enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }

    /**
     * Satu akun admin bidang untuk setiap bidang, dengan role sesuai
     * bidangnya (admin_ikw, admin_ppepd, dll). Email
     * admin.{singkatan}@admin.com; singkatan kembar diberi suffix angka.
     * Password default: admin123 (bisa diubah via menu Data User).
     */
    private function seedBidangAdmins(): void
    {
        $roleMap = $this->bidangRoleMap();
        $used = [];

        foreach (Bidang::orderBy('id')->get() as $bidang) {
            $base = 'admin.' . strtolower($bidang->singkatan);
            $count = $used[$base] = ($used[$base] ?? 0) + 1;
            $email = $base . ($count > 1 ? $count : '') . '@admin.com';

            $role = $roleMap[strtoupper($bidang->singkatan)] ?? 'admin_sekretariat';

            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'name' => $bidang->nama,
                    'password' => bcrypt('admin123'),
                ]
            );

            $user->update(['name' => $bidang->nama, 'bidang_id' => $bidang->id]);
            $user->syncRoles($role);
        }
    }
}

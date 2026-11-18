<?php

/*
|--------------------------------------------------------------------------
| Role-Based Access Control (RBAC)
|--------------------------------------------------------------------------
|
| Sumber kebenaran untuk role, permission, dan modul konten yang
| di-scope berdasarkan bidang. Tambahkan role baru di array `roles`
| lalu jalankan `php artisan db:seed --class=RolePermissionSeeder`.
|
*/

return [

    /*
    | Modul konten yang datanya milik per-bidang. Model-nya memakai trait
    | App\Models\Concerns\BelongsToBidang sehingga semua query otomatis
    | difilter sesuai bidang admin yang sedang login.
    */
    'bidang_modules' => [
        'artikel',
        'pengumuman',
        'galeri',
        'galeri_video',
        'dokumen',
        'pegawai',
    ],

    /*
    | Definisi role. `permissions` berisi nama permission yang dimiliki
    | role; '*' berarti seluruh permission. List permission di-generate
    | otomatis dari `config/cms.php` (manage_<slug>) plus permission
    | khusus sistem.
    */
    'roles' => [

        // Kepala Bappedalitbang: pemberi persetujuan + akses penuh.
        'kepala_badan' => [
            'label' => 'Kepala Bappedalitbang',
            'permissions' => ['*'],
        ],

        // Super Admin: akses penuh (kelola user, role, seluruh konten).
        'super_admin' => [
            'label' => 'Super Admin',
            'permissions' => ['*'],
        ],

        // Admin Media: kelola konten publik & dokumentasi kegiatan
        // (artikel + galeri foto + video). Hanya modul yang benar-benar
        // dibutuhkan; tidak ada akses ke pengaturan/menus/users/kepegawaian
        // maupun modul konten di luar tugasnya.
        'admin_media' => [
            'label' => 'Admin Media',
            'permissions' => [
                'manage_artikel',
                'manage_galeri',
                'manage_galeri_video',
                'manage_media',
            ],
        ],

        // Admin Sekretariat (Bidang Sekretariat).
        'admin_sekretariat' => [
            'label' => 'Admin Sekretariat',
            'permissions' => [
                'manage_artikel',
                'manage_pengumuman',
                'manage_dokumen',
                    'manage_pegawai',
                'manage_media',
            ],
        ],

        // Admin PPEPD.
        'admin_ppepd' => [
            'label' => 'Admin PPEPD',
            'permissions' => [
                'manage_artikel',
                'manage_pengumuman',
                'manage_dokumen',
                    'manage_pegawai',
                'manage_media',
            ],
        ],

        // Admin PPM.
        'admin_ppm' => [
            'label' => 'Admin PPM',
            'permissions' => [
                'manage_artikel',
                'manage_pengumuman',
                'manage_dokumen',
                    'manage_pegawai',
                'manage_media',
            ],
        ],

        // Admin Litbang.
        'admin_litbang' => [
            'label' => 'Admin Litbang',
            'permissions' => [
                'manage_artikel',
                'manage_pengumuman',
                'manage_dokumen',
                    'manage_pegawai',
                'manage_media',
            ],
        ],

        // Admin PSDA.
        'admin_psda' => [
            'label' => 'Admin PSDA',
            'permissions' => [
                'manage_artikel',
                'manage_pengumuman',
                'manage_dokumen',
                    'manage_pegawai',
                'manage_media',
            ],
        ],

        // Admin IKW.
        'admin_ikw' => [
            'label' => 'Admin IKW',
            'permissions' => [
                'manage_artikel',
                'manage_pengumuman',
                'manage_dokumen',
                    'manage_pegawai',
                'manage_media',
            ],
        ],

        // Kepala Sub Bagian Umum: kelola konten + sebagian utilitas situs.
        'kepala_sub_bagian_umum' => [
            'label' => 'Kepala Sub Bagian Umum',
            'permissions' => [
                'manage_artikel',
                'manage_kategori',
                'manage_pengumuman',
                'manage_galeri',
                'manage_galeri_video',
                'manage_dokumen',
                'manage_bidang',
                'manage_pegawai',
                'manage_komentar',
                'manage_media',
                'manage_menus',
                'manage_home_sections',
                'manage_layanan',
                'manage_inovasi_fitur',
            ],
        ],

    ],

    /*
     | Role yang melekat pada satu bidang (admin bidang). Dipakai untuk
     | menampilkan field "Bidang" di form user dan menentukan scope data.
     */
    'bidang_roles' => [
        'admin_sekretariat',
        'admin_ppepd',
        'admin_ppm',
        'admin_litbang',
        'admin_psda',
        'admin_ikw',
    ],

];

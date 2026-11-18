<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menu = Menu::updateOrCreate(
            ['location' => 'header'],
            ['name' => 'Menu Utama', 'slug' => 'menu-utama']
        );

        // Jangan reset menu yang sudah dikustomisasi via panel admin.
        if (MenuItem::where('menu_id', $menu->id)->exists()) {
            return;
        }

        MenuItem::where('menu_id', $menu->id)->delete();

        $items = [
            ['label' => 'Beranda', 'url' => '/', 'sort_order' => 1, 'children' => []],
            [
                'label' => 'Profil',
                'url' => 'profil/struktur',
                'sort_order' => 2,
                'children' => [
                    ['label' => 'Struktur Organisasi', 'url' => 'profil/struktur', 'sort_order' => 1],
                    ['label' => 'Pegawai', 'url' => 'profil/pegawai', 'sort_order' => 2],
                    ['label' => 'Bidang & Tugas', 'url' => 'profil/bidang', 'sort_order' => 3],
                    ['label' => 'Maklumat Pelayanan', 'url' => 'profil/maklumat-pelayanan', 'sort_order' => 4],
                ],
            ],
            ['label' => 'Pengumuman', 'url' => 'pengumuman', 'sort_order' => 3, 'children' => []],
            ['label' => 'Penelitian', 'url' => 'penelitian', 'sort_order' => 4, 'children' => []],
            ['label' => 'Dokumen', 'url' => 'dokumen', 'sort_order' => 5, 'children' => []],
            ['label' => 'Galeri', 'url' => 'galeri', 'sort_order' => 6, 'children' => []],
            ['label' => 'FAQ', 'url' => 'faq', 'sort_order' => 7, 'children' => []],
        ];

        foreach ($items as $item) {
            $parent = MenuItem::create([
                'menu_id' => $menu->id,
                'label' => $item['label'],
                'url' => $item['url'],
                'target' => '_self',
                'parent_id' => null,
                'sort_order' => $item['sort_order'],
                'enabled' => true,
            ]);

            foreach ($item['children'] as $child) {
                MenuItem::create([
                    'menu_id' => $menu->id,
                    'label' => $child['label'],
                    'url' => $child['url'],
                    'target' => '_self',
                    'parent_id' => $parent->id,
                    'sort_order' => $child['sort_order'],
                    'enabled' => true,
                ]);
            }
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HomeSectionSeeder extends Seeder
{
    public function run(): void
    {
        $registry = config('cms.home_sections');

        foreach ($registry as $type => $definition) {
            // Hanya buat section yang belum ADA sama sekali (termasuk yang
            // soft-deleted); JANGAN mengubah/menghidupkan kembali section yang
            // sudah dinonaktifkan/dihapus via panel admin (Section Halaman Depan).
            $exists = DB::table('home_sections')->where('type', $type)->exists();

            if ($exists) {
                continue;
            }

            DB::table('home_sections')->insert([
                'type' => $type,
                'title' => $definition['label'],
                'enabled' => true,
                'sort_order' => array_search($type, array_keys($registry)),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

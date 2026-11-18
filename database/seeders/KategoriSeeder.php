<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KategoriSeeder extends Seeder
{
    public function run(): void
    {
        $kategoris = ['Berita', 'Teknologi', 'Olahraga', 'Kesehatan', 'Pendidikan', 'Ekonomi'];

        foreach ($kategoris as $k) {
            DB::table('kategori')->insert([
                'nama' => $k,
                'user_id' => '1',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}

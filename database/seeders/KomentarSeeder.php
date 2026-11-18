<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KomentarSeeder extends Seeder
{
    public function run(): void
    {
        $komentars = [
            ['artikel_id' => 1, 'nama' => 'Budi Santoso', 'email' => 'budi@email.com', 'website' => 'https://budi.my.id', 'isi' => 'Program digitalisasi ini sangat bagus! Semoga bisa merata sampai ke pelosok desa.'],
            ['artikel_id' => 1, 'nama' => 'Siti Rahmawati', 'email' => 'siti@email.com', 'website' => '', 'isi' => 'Setuju, akses internet di daerah terpencil masih perlu ditingkatkan.'],
            ['artikel_id' => 2, 'nama' => 'Ahmad Hidayat', 'email' => 'ahmad@email.com', 'website' => 'https://ahmad.com', 'isi' => 'Wah, ini inovasi yang ditunggu-tunggu oleh pelaku UMKM!'],
            ['artikel_id' => 2, 'nama' => 'Dewi Lestari', 'email' => 'dewi@email.com', 'website' => '', 'isi' => 'Semoga bisa membantu UMKM naik kelas. Bahasa daerahnya juga lengkap!'],
            ['artikel_id' => 3, 'nama' => 'Rudi Hartono', 'email' => 'rudi@email.com', 'website' => '', 'isi' => 'GOOOOOL!!! Indonesia juara!'],
            ['artikel_id' => 3, 'nama' => 'Mega Putri', 'email' => 'mega@email.com', 'website' => 'https://megaputri.com', 'isi' => 'Bangga banget sama perjuangan timnas. Semoga terus konsisten.'],
        ];

        foreach ($komentars as $k) {
            $k['created_at'] = now();
            DB::table('komentar')->insert($k);
        }
    }
}

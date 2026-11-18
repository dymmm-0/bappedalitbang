<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        // Non-destruktif: jangan timpa/hapus konten yang sudah ada & diedit
        // melalui panel admin. Default hanya di-seed bila tabel masih kosong.

        // Struktur organisasi (singleton)
        if (! DB::table('struktur')->exists()) {
            DB::table('struktur')->insert([
                'judul' => 'Struktur Organisasi',
                'gambar' => null,
                'isi' => '<p>Bappedalitbang Kabupaten Karo dipimpin oleh seorang Kepala Badan yang membawahi Sekretariat serta beberapa Bidang, yaitu:</p><ul><li><strong>Bidang Perencanaan, Pengendalian dan Evaluasi Pembangunan Daerah</strong></li><li><strong>Bidang Infrastruktur dan Kewilayahan</strong></li><li><strong>Bidang Pemerintahan dan Pembangunan Manusia</strong></li><li><strong>Bidang Ekonomi dan Sumber Daya Alam</strong></li><li><strong>Bidang Penelitian dan Pengembangan</strong></li></ul><p>Setiap bidang didukung oleh sub bagian dan jabatan fungsional sesuai dengan tugas dan fungsinya masing-masing. Bagan struktur dapat diunduh melalui menu Dokumen.</p>',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Pegawai (contoh) — hanya saat kosong
        if (DB::table('pegawai')->count() === 0) {
            $pegawai = [
                ['Pegawai A', '198501012010012001', 'Perencana Ahli Madya', 'Bidang Perencanaan', 'pegawai.a@karokab.go.id'],
                ['Pegawai B', '198701012012012001', 'Perencana Ahli Muda', 'Bidang Infrastruktur dan Kewilayahan', 'pegawai.b@karokab.go.id'],
                ['Pegawai C', '199001012015012001', 'Peneliti Ahli Muda', 'Bidang Penelitian dan Pengembangan', 'pegawai.c@karokab.go.id'],
                ['Pegawai D', '199201012018012001', 'Analis Kebijakan', 'Sekretariat', 'pegawai.d@karokab.go.id'],
                ['Pegawai E', '199401012019012001', 'Pengelola Data', 'Bidang Pemerintahan dan Pembangunan Manusia', 'pegawai.e@karokab.go.id'],
                ['Pegawai F', '199601012020012001', 'Penyusun Laporan', 'Bidang Ekonomi dan Sumber Daya Alam', 'pegawai.f@karokab.go.id'],
            ];

            foreach ($pegawai as $i => $p) {
                DB::table('pegawai')->insert([
                    'nama' => $p[0],
                    'nip' => $p[1],
                    'jabatan' => $p[2],
                    'bagian' => $p[3],
                    'email' => $p[4],
                    'foto' => null,
                    'urutan' => $i + 1,
                    'enabled' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 | Tabel "layanan terkait" (tautan resmi di beranda). Data di-kelola dari
 | panel admin (CRUD config-driven `layanan`), ditampilkan sebagai slider
 | horizontal di section #tautan-resmi welcome.blade.php.
 |
 | logo = path gambar di public/uploads (opsional), ikon = path SVG inline
 | (opsional, dipakai bila logo kosong). enabled = status aktif, urutan =
 | urutan tampilan.
 */
class CreateLayananTable extends Migration
{
    public function up()
    {
        Schema::create('layanan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('url');
            $table->string('logo')->nullable();
            $table->text('ikon')->nullable();
            $table->boolean('enabled')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Seed awal (satu kali, saat tabel dibuat) — sesuai data lama yang
        // sebelumnya di-hardcode di welcome.blade.php.
        // Logo Bappedalitbang memakai berkas lama `uploads/Logo/LogoBappeda.png`; 
        // layanan lain memakai ikon SVG yang sebelumnya ditulis inline.
        $ikon = [
            'portal' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l9-9 9 9M5 10v10a1 1 0 001 1h3m10-11v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
            'jdih' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>',
            'ppid' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'lpse' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>',
            'spbe' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>',
            'eoffice' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>',
        ];

        $defaults = [
            ['nama' => 'Bappedalitbang', 'url' => 'https://bappeda.karokab.go.id', 'logo' => 'uploads/Logo/LogoBappeda.png', 'ikon' => null],
            ['nama' => 'Portal Pemkab Karo', 'url' => 'https://karokab.go.id', 'logo' => null, 'ikon' => $ikon['portal']],
            ['nama' => 'JDIH Kab. Karo', 'url' => 'https://jdih.karokab.go.id', 'logo' => null, 'ikon' => $ikon['jdih']],
            ['nama' => 'PPID Kab. Karo', 'url' => 'https://ppid.karokab.go.id', 'logo' => null, 'ikon' => $ikon['ppid']],
            ['nama' => 'LPSE Kab. Karo', 'url' => 'https://lpse.karokab.go.id', 'logo' => null, 'ikon' => $ikon['lpse']],
            ['nama' => 'SPBE Kab. Karo', 'url' => 'https://spbe.karokab.go.id', 'logo' => null, 'ikon' => $ikon['spbe']],
            ['nama' => 'E-Office', 'url' => 'https://eoffice.karokab.go.id', 'logo' => null, 'ikon' => $ikon['eoffice']],
        ];

        foreach ($defaults as $i => $row) {
            DB::table('layanan')->insert([
                'nama' => $row['nama'],
                'url' => $row['url'],
                'logo' => $row['logo'],
                'ikon' => $row['ikon'],
                'enabled' => true,
                'urutan' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('layanan');
    }
}

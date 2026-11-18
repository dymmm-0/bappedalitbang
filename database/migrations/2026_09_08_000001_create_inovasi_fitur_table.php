<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 | Kartu fitur section "Inovasi 5LMAN" di beranda (#inovasi).
 | Sebelumnya di-hardcode sebagai array $inovasi5lman di
 | resources/views/welcome.blade.php; kini dikelola dari panel admin
 | (modul CRUD config-driven `inovasi_fitur`) dan ditampilkan via DB.
 |
 | ikon = path SVG inline (stroke, viewBox 0 0 24 24). enabled = status
 | aktif, urutan = urutan tampilan kartu.
 */
class CreateInovasiFiturTable extends Migration
{
    public function up()
    {
        Schema::create('inovasi_fitur', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul');
            $table->text('deskripsi')->nullable();
            $table->text('ikon')->nullable();
            $table->boolean('enabled')->default(true);
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });

        // Seed awal — sama dengan 4 kartu placeholder yang sebelumnya
        // hardcoded di welcome.blade.php (belum ada isi resmi 5LMAN).
        $isi = [
            ['Fitur 1 — [Nama Fitur]', '[Placeholder — tulis deskripsi fitur pertama Inovasi 5LMAN yang akan ditampilkan di sini.]', 'M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.517 0c.85.493 1.509 1.333 1.509 2.316V18'],
            ['Fitur 2 — [Nama Fitur]', '[Placeholder — tulis deskripsi fitur kedua Inovasi 5LMAN yang akan ditampilkan di sini.]', 'M3.75 13.5l10.5-11.25L12 10.5h8.25L9.75 21.75 12 13.5H3.75z'],
            ['Fitur 3 — [Nama Fitur]', '[Placeholder — tulis deskripsi fitur ketiga Inovasi 5LMAN yang akan ditampilkan di sini.]', 'M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z'],
            ['Fitur 4 — [Nama Fitur]', '[Placeholder — tulis deskripsi fitur keempat Inovasi 5LMAN yang akan ditampilkan di sini.]', 'M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z'],
        ];

        foreach ($isi as $i => $row) {
            DB::table('inovasi_fitur')->insert([
                'judul' => $row[0],
                'deskripsi' => $row[1],
                'ikon' => $row[2],
                'enabled' => true,
                'urutan' => $i,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        Schema::dropIfExists('inovasi_fitur');
    }
}

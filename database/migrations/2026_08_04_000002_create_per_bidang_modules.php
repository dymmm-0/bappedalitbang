<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePerBidangModules extends Migration
{
    public function up()
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bidang_id')->nullable()->index();
            $table->string('judul');
            $table->longText('deskripsi')->nullable();
            $table->dateTime('mulai')->nullable();
            $table->dateTime('selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('status', 20)->default('published');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('galeri_video', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bidang_id')->nullable()->index();
            $table->string('judul');
            $table->string('url')->nullable();
            $table->string('deskripsi')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('inovasi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bidang_id')->nullable()->index();
            $table->string('judul');
            $table->longText('deskripsi');
            $table->string('tahun')->nullable();
            $table->string('file')->nullable();
            $table->string('gambar')->nullable();
            $table->string('status', 20)->default('published');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bidang_id')->nullable()->index();
            $table->string('judul')->nullable();
            $table->string('gambar')->nullable();
            $table->string('url')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('profil_bidang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bidang_id')->unique();
            $table->longText('deskripsi')->nullable();
            $table->text('tupoksi')->nullable();
            $table->longText('visi')->nullable();
            $table->text('misi')->nullable();
            $table->text('program_kerja')->nullable();
            $table->string('struktur_judul')->nullable();
            $table->string('struktur_gambar')->nullable();
            $table->longText('struktur_isi')->nullable();
            $table->string('kontak_nama')->nullable();
            $table->string('kontak_email')->nullable();
            $table->string('kontak_telepon')->nullable();
            $table->string('kontak_lokasi')->nullable();
            $table->timestamps();
        });

        Schema::table('statistik', function (Blueprint $table) {
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->index('bidang_id');
        });

        // Pindahkan deskripsi & poin (tugas) dari tabel bidang ke profil_bidang
        // agar dapat dikelola oleh admin bidang. Single source: profil_bidang.
        $bidangs = DB::table('bidang')->select('id', 'deskripsi', 'poin')->get();

        foreach ($bidangs as $b) {
            DB::table('profil_bidang')->insert([
                'bidang_id' => $b->id,
                'deskripsi' => $b->deskripsi ?? '',
                'tupoksi' => $b->poin,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        Schema::table('bidang', function (Blueprint $table) {
            $table->dropColumn(['deskripsi', 'poin']);
        });
    }

    public function down()
    {
        Schema::table('statistik', function (Blueprint $table) {
            $table->dropIndex(['bidang_id']);
            $table->dropColumn('bidang_id');
        });

        Schema::table('bidang', function (Blueprint $table) {
            $table->text('deskripsi')->nullable();
            $table->text('poin')->nullable();
        });

        DB::table('profil_bidang')->get()->each(function ($p) {
            DB::table('bidang')->where('id', $p->bidang_id)->update([
                'deskripsi' => $p->deskripsi,
                'poin' => $p->tupoksi,
            ]);
        });

        Schema::dropIfExists('profil_bidang');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('inovasi');
        Schema::dropIfExists('galeri_video');
        Schema::dropIfExists('agenda');
    }
}

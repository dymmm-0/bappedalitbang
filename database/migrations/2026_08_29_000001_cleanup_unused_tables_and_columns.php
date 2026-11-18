<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 | Pembersihan skema: tabel & kolom yang sudah tidak dipakai lagi (orphan).
 |
 | Tabel dihapus (data yatim, tidak ada konsumen di kode):
 |   iklan, banner, partner, pimpinan, sejarah, media, program
 |
 | Kolom dihapus:
 |   artikel.excerpt            -> tidak ada view/controller yang membacanya (hanya metode mati excerptOr)
 |   artikel.approved_by/at     -> alur persetujuan artikel hanya menyetel status; kolom tak pernah ditulis
 |   statistik.ikon             -> hanya field form admin, tidak dirender di halaman publik
 |
 | Permission yatim ikut dihapus (pivot role_has_permissions terhapus otomatis via FK cascade).
 |   manage_agenda, manage_banner, manage_iklan, manage_partner,
 |   manage_pimpinan, manage_sejarah, manage_program
 */
class CleanupUnusedTablesAndColumns extends Migration
{
    public function up()
    {
        // Tabel orphan
        Schema::dropIfExists('iklan');
        Schema::dropIfExists('banner');
        Schema::dropIfExists('partner');
        Schema::dropIfExists('pimpinan');
        Schema::dropIfExists('sejarah');
        Schema::dropIfExists('media');
        Schema::dropIfExists('program');

        // Kolom orphan / tak terpakai
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'approved_by', 'approved_at']);
        });

        Schema::table('statistik', function (Blueprint $table) {
            $table->dropColumn('ikon');
        });

        // Permission yatim
        DB::table('permissions')
            ->whereIn('name', [
                'manage_agenda',
                'manage_banner',
                'manage_iklan',
                'manage_partner',
                'manage_pimpinan',
                'manage_sejarah',
                'manage_program',
            ])
            ->delete();

        app('cache')->forget(config('permission.cache.key'));
    }

    public function down()
    {
        // Permission dikembalikan
        $names = [
            'manage_agenda',
            'manage_banner',
            'manage_iklan',
            'manage_partner',
            'manage_pimpinan',
            'manage_sejarah',
            'manage_program',
        ];
        $now = now();
        foreach ($names as $name) {
            DB::table('permissions')->insertOrIgnore([
                'name' => $name,
                'guard_name' => 'web',
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        app('cache')->forget(config('permission.cache.key'));

        // Kolom dikembalikan
        Schema::table('statistik', function (Blueprint $table) {
            $table->string('ikon')->nullable()->after('nilai');
        });

        Schema::table('artikel', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('isi');
            $table->unsignedBigInteger('approved_by')->nullable()->after('submitted_at');
            $table->timestamp('approved_at')->nullable()->after('approved_by');
        });

        // Tabel dikembalikan
        Schema::create('program', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->index('bidang_id');
            $table->string('nama');
            $table->string('tahun')->nullable();
            $table->text('deskripsi')->nullable();
            $table->integer('persen')->default(0);
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('path');
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->string('alt')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
        });

        Schema::create('sejarah', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('isi');
            $table->timestamps();
        });

        Schema::create('pimpinan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('nip')->nullable();
            $table->string('jabatan');
            $table->text('bio')->nullable();
            $table->string('foto')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('partner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('banner', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bidang_id')->nullable();
            $table->index('bidang_id');
            $table->string('judul')->nullable();
            $table->string('gambar')->nullable();
            $table->string('url')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('iklan', function (Blueprint $table) {
            $table->increments('iklan_id');
            $table->text('url');
            $table->text('gambar');
        });
    }
}

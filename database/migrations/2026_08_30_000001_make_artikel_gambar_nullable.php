<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 | `artikel.gambar` di skema lama NOT NULL tanpa default (text). Menyimpan
 | artikel TANPA mengunggah gambar gagal di MySQL strict (error 1364).
 | Kolom dijadikan nullable agar artikel tetap bisa dibuat/diubah.
 */
class MakeArtikelGambarNullable extends Migration
{
    public function up()
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->text('gambar')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->text('gambar')->nullable(false)->change();
        });
    }
}

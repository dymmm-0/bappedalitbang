<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 | Hapus visi-misi & struktur organisasi dari profil tiap bidang.
 | Visi & Misi serta Struktur Organisasi hanya hidup di level instansi
 | (modul Visi Misi + Struktur Organisasi), tidak lagi per-bidang.
 */
class DropVisiMisiStrukturFromProfilBidang extends Migration
{
    public function up()
    {
        Schema::table('profil_bidang', function (Blueprint $table) {
            $table->dropColumn([
                'visi',
                'misi',
                'struktur_judul',
                'struktur_gambar',
                'struktur_isi',
            ]);
        });
    }

    public function down()
    {
        Schema::table('profil_bidang', function (Blueprint $table) {
            $table->longText('visi')->nullable()->after('tupoksi');
            $table->text('misi')->nullable()->after('visi');
            $table->string('struktur_judul')->nullable()->after('program_kerja');
            $table->string('struktur_gambar')->nullable()->after('struktur_judul');
            $table->longText('struktur_isi')->nullable()->after('struktur_gambar');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 | Menyesuaikan tabel `layanan` menjadi image-slider:
 | - Hapus kolom `ikon` (SVG line-icon) & `urutan` (urutan manual).
 | - Urutan tampilan kini otomatis = id ASC (data lama tampil lebih dulu),
 |   sehingga admin tidak perlu mengelola nomor urutan.
 | - Setiap layanan memakai satu gambar/banner (kolom `logo`) yang di-upload
 |   dari panel admin. Semua baris seed diberi banner seragam yang dibundel
 |   di public/uploads/layanan/2026/.
 */
class LayananImageSlider extends Migration
{
    public function up()
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->dropColumn('ikon');
            $table->dropColumn('urutan');
        });

        $banners = [
            'Bappedalitbang'     => 'layanan/2026/bappedalitbang.webp',
            'Portal Pemkab Karo' => 'layanan/2026/portal-pemkab-karo.webp',
            'JDIH Kab. Karo'     => 'layanan/2026/jdih.webp',
            'PPID Kab. Karo'     => 'layanan/2026/ppid.webp',
            'LPSE Kab. Karo'     => 'layanan/2026/lpse.webp',
            'SPBE Kab. Karo'     => 'layanan/2026/spbe.webp',
            'E-Office'           => 'layanan/2026/e-office.webp',
        ];

        foreach ($banners as $nama => $logo) {
            DB::table('layanan')->where('nama', $nama)->update(['logo' => $logo]);
        }
    }

    public function down()
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->text('ikon')->nullable()->after('logo');
            $table->integer('urutan')->default(0)->after('enabled');
        });
    }
}

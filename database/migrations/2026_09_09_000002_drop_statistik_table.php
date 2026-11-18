<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

/*
 | Menghapus tabel `statistik` (input manual label+nilai) beserta modul
 | admin & tampilan band angka di halaman bidang. Statistik kunjungan di
 | beranda menggunakan tabel `visits` (otomatis), tidak terpengaruh.
 */
class DropStatistikTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('statistik');
    }

    public function down()
    {
        // Tabel lama tidak dibuat ulang (skema historis ada di migration awal).
    }
}

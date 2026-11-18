<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBidangIdColumnsForRbac extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('bidang_id')->nullable()->after('password');
            $table->foreign('bidang_id')->references('id')->on('bidang')->nullOnDelete();
        });

        $tables = ['artikel', 'pengumuman', 'penelitian', 'program', 'galeri', 'dokumen', 'pegawai'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('bidang_id')->nullable();
                $table->index('bidang_id');
            });
        }
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['bidang_id']);
            $table->dropColumn('bidang_id');
        });

        $tables = ['artikel', 'pengumuman', 'penelitian', 'program', 'galeri', 'dokumen', 'pegawai'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropIndex(['bidang_id']);
                $table->dropColumn('bidang_id');
            });
        }
    }
}

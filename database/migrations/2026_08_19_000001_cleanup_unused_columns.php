<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CleanupUnusedColumns extends Migration
{
    public function up()
    {
        Schema::dropIfExists('sliders');

        Schema::table('artikel', function (Blueprint $table) {
            $table->dropColumn('featured');
        });

        Schema::table('tim_pengembang', function (Blueprint $table) {
            $table->dropColumn('sosmed');
        });

        Schema::table('visits', function (Blueprint $table) {
            $table->dropColumn('path');
        });
    }

    public function down()
    {
        Schema::table('visits', function (Blueprint $table) {
            $table->string('path', 255)->nullable()->after('id');
        });

        Schema::table('tim_pengembang', function (Blueprint $table) {
            $table->json('sosmed')->nullable()->after('email');
        });

        Schema::table('artikel', function (Blueprint $table) {
            $table->boolean('featured')->default(false)->after('status');
        });

        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('judul')->nullable();
            $table->string('subjudul')->nullable();
            $table->string('gambar');
            $table->string('tombol_label')->nullable();
            $table->string('tombol_url')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddThumbnailToGaleriVideoTable extends Migration
{
    public function up()
    {
        Schema::table('galeri_video', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->after('file');
        });
    }

    public function down()
    {
        Schema::table('galeri_video', function (Blueprint $table) {
            $table->dropColumn('thumbnail');
        });
    }
}

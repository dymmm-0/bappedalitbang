<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileToGaleriVideoTable extends Migration
{
    public function up()
    {
        Schema::table('galeri_video', function (Blueprint $table) {
            $table->string('file')->nullable()->after('url');
        });
    }

    public function down()
    {
        Schema::table('galeri_video', function (Blueprint $table) {
            $table->dropColumn('file');
        });
    }
}

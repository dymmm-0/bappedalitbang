<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSosmedToTimPengembangTable extends Migration
{
    public function up()
    {
        Schema::table('tim_pengembang', function (Blueprint $table) {
            $table->longText('sosmed')->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('tim_pengembang', function (Blueprint $table) {
            $table->dropColumn('sosmed');
        });
    }
}
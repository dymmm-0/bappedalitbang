<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimPengembangTable extends Migration
{
    public function up()
    {
        Schema::create('tim_pengembang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->longText('bio')->nullable();
            $table->string('foto')->nullable();
            $table->string('email')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tim_pengembang');
    }
}

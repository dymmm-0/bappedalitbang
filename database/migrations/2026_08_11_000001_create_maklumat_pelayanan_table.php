<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMaklumatPelayananTable extends Migration
{
    public function up()
    {
        Schema::create('maklumat_pelayanan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('judul')->nullable();
            $table->string('gambar')->nullable();
            $table->longText('isi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('maklumat_pelayanan');
    }
}

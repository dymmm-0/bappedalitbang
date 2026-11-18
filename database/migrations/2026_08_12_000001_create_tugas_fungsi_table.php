<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTugasFungsiTable extends Migration
{
    public function up()
    {
        Schema::create('tugas_fungsi', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('pembukaan')->nullable();
            $table->longText('tugas')->nullable();
            $table->longText('fungsi')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tugas_fungsi');
    }
}
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtToHomeSectionsTable extends Migration
{
    public function up()
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });
    }
}
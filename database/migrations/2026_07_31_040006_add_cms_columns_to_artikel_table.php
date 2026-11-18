<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCmsColumnsToArtikelTable extends Migration
{
    public function up()
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->text('excerpt')->nullable()->after('isi');
            $table->string('status', 20)->default('published')->after('gambar');
            $table->timestamp('published_at')->nullable()->after('status');
            $table->boolean('featured')->default(false)->after('published_at');
            $table->softDeletes()->after('featured');
        });
    }

    public function down()
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropColumn(['excerpt', 'status', 'published_at', 'featured', 'deleted_at']);
        });
    }
}

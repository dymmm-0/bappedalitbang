<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // ponytail: one nullable FK-less link column so a Galeri Video can be
        // attached to a legacy artikel on its detail page. No new table.
        Schema::table('galeri_video', function (Blueprint $table) {
            $table->unsignedBigInteger('artikel_id')->nullable()->after('bidang_id')->index();
        });
    }

    public function down(): void
    {
        Schema::table('galeri_video', function (Blueprint $table) {
            $table->dropColumn('artikel_id');
        });
    }
};

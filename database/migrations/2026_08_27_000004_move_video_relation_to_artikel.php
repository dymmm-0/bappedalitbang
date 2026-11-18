<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Relasi "Video Terkait" dipindah ke sisi konten: artikel.video_id -> galeri_video.id.
     * Sebelumnya link disimpan di galeri_video.artikel_id; dipindahkan dan kolom lama dihapus.
     */
    public function up(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->unsignedBigInteger('video_id')->nullable()->index();
        });

        if (Schema::hasColumn('galeri_video', 'artikel_id')) {
            // pindahkan link lama (satu video terbit per artikel)
            DB::statement(
                "UPDATE artikel a SET a.video_id = (
                    SELECT g.id FROM galeri_video g
                    WHERE g.artikel_id = a.artikel_id AND g.enabled = 1
                    ORDER BY g.id ASC LIMIT 1
                ) WHERE a.artikel_id IN (SELECT artikel_id FROM galeri_video WHERE artikel_id IS NOT NULL)"
            );

            Schema::table('galeri_video', function (Blueprint $table) {
                $table->dropColumn('artikel_id');
            });
        }
    }

    public function down(): void
    {
        Schema::table('artikel', function (Blueprint $table) {
            $table->dropColumn('video_id');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            // Tahun/periode fleksibel: "2026" (RKPD), "2021-2026" (RPJMD), "2025-2045" (RPJPD).
            $table->string('tahun', 50)->nullable()->after('kategori');
            $table->text('deskripsi')->nullable()->after('tahun');
        });

        // Backfill: tebak tahun/periode dari angka tahun pada judul (mis. "RPJMD ... 2021-2026").
        $rows = DB::table('dokumen')->whereNull('tahun')->get(['id', 'judul']);
        foreach ($rows as $row) {
            preg_match_all('/\b(?:19|20)\d{2}\b/', (string) $row->judul, $m);
            $years = array_values(array_unique($m[0]));
            $tahun = count($years) >= 2 ? $years[0] . '-' . $years[1] : ($years[0] ?? null);

            if ($tahun !== null) {
                DB::table('dokumen')->where('id', $row->id)->update(['tahun' => $tahun]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('dokumen', function (Blueprint $table) {
            $table->dropColumn(['tahun', 'deskripsi']);
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Kategori lama (Peraturan/SOP/Laporan/Panduan/Dokumen Perencanaan/File Pendukung)
        // dipetakan ke bucket baru "Dokumen Pendukung & Evaluasi" agar tidak ada dokumen yang hilang.
        DB::table('dokumen')
            ->whereNotIn('kategori', array_keys(App\Models\Dokumen::CATEGORIES))
            ->whereNotNull('kategori')
            ->update(['kategori' => 'Dokumen Pendukung & Evaluasi']);
    }

    public function down(): void
    {
        // Tidak bisa dipulihkan (nilai asli sudah diganti); biarkan no-op.
    }
};

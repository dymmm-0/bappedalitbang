<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/*
 | Workflow persetujuan diperluas ke Dokumen & Pegawai (sama seperti pengumuman/inovasi):
 | - status            : draft / pending / published / rejected / revision
 | - submitted_by/at   : admin bidang yang mengajukan
 | - approved_by/at    : Kepala Bappedalitbang yang menyetujui
 | - rejection_reason  : catatan penolakan / perbaikan
 | - pegawai.pending_changes : snapshot nilai lama saat admin bidang mengubah
 |   (agar data aktif publik tidak langsung tertimpa sebelum disetujui).
 |
 | Backfill status='published' untuk baris existing (sudah enabled=1) agar
 | tetap tampil di publik. Semua kolom nullable -> aman.
 */
class AddApprovalToDokumenPegawai extends Migration
{
    public function up()
    {
        foreach (['dokumen', 'pegawai'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->string('status')->nullable()->after('enabled');
                $table->unsignedBigInteger('submitted_by')->nullable()->after('status');
                $table->timestamp('submitted_at')->nullable()->after('submitted_by');
                $table->unsignedBigInteger('approved_by')->nullable()->after('submitted_at');
                $table->timestamp('approved_at')->nullable()->after('approved_by');
                $table->text('rejection_reason')->nullable()->after('approved_at');
            });
        }

        Schema::table('pegawai', function (Blueprint $table) {
            $table->json('pending_changes')->nullable()->after('rejection_reason');
        });

        DB::table('dokumen')->whereNull('status')->update(['status' => 'published']);
        DB::table('pegawai')->whereNull('status')->update(['status' => 'published']);
    }

    public function down()
    {
        Schema::table('pegawai', function (Blueprint $table) {
            $table->dropColumn('pending_changes');
        });

        foreach (['dokumen', 'pegawai'] as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['status', 'submitted_by', 'submitted_at', 'approved_by', 'approved_at', 'rejection_reason']);
            });
        }
    }
}

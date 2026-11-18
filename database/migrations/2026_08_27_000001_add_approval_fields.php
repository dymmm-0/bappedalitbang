<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/*
 | Workflow persetujuan konten bidang:
 | - submitted_by / submitted_at : admin bidang yang mengajukan
 | - approved_by / approved_at   : Kepala Bappedalitbang yang menyetujui
 | - rejection_reason            : alasan penolakan (ditampilkan ke admin bidang)
 |
 | Semua kolom nullable -> migrasi aman, tidak merusak data existing.
 */
class AddApprovalFields extends Migration
{
    public function up()
    {
        $tables = ['pengumuman', 'inovasi', 'artikel'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->unsignedBigInteger('submitted_by')->nullable()->after('bidang_id');
                $table->timestamp('submitted_at')->nullable()->after('submitted_by');
                $table->unsignedBigInteger('approved_by')->nullable()->after('submitted_at');
                $table->timestamp('approved_at')->nullable()->after('approved_by');
                $table->text('rejection_reason')->nullable()->after('status');
            });
        }
    }

    public function down()
    {
        $tables = ['pengumuman', 'inovasi', 'artikel'];

        foreach ($tables as $table) {
            Schema::table($table, function (Blueprint $table) {
                $table->dropColumn(['submitted_by', 'submitted_at', 'approved_by', 'approved_at', 'rejection_reason']);
            });
        }
    }
}

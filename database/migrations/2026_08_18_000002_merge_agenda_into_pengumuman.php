<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class MergeAgendaIntoPengumuman extends Migration
{
    public function up()
    {
        Schema::table('pengumuman', function (Blueprint $table) {
            $table->string('jenis', 20)->default('pengumuman')->index();
            $table->dateTime('mulai')->nullable();
            $table->dateTime('selesai')->nullable();
            $table->string('lokasi')->nullable();
        });

        DB::table('agenda')->get()->each(function ($a) {
            DB::table('pengumuman')->insert([
                'bidang_id' => $a->bidang_id,
                'judul' => $a->judul,
                'isi' => $a->deskripsi ?? '',
                'tanggal' => $a->mulai ? date('Y-m-d', strtotime($a->mulai)) : null,
                'jenis' => 'agenda',
                'mulai' => $a->mulai,
                'selesai' => $a->selesai,
                'lokasi' => $a->lokasi,
                'status' => $a->status,
                'published_at' => $a->published_at,
                'deleted_at' => $a->deleted_at,
                'created_at' => $a->created_at ?: now(),
                'updated_at' => $a->updated_at ?: now(),
            ]);
        });

        Schema::dropIfExists('agenda');
    }

    public function down()
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bidang_id')->nullable()->index();
            $table->string('judul');
            $table->longText('deskripsi')->nullable();
            $table->dateTime('mulai')->nullable();
            $table->dateTime('selesai')->nullable();
            $table->string('lokasi')->nullable();
            $table->string('status', 20)->default('published');
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        DB::table('pengumuman')->where('jenis', 'agenda')->get()->each(function ($p) {
            DB::table('agenda')->insert([
                'bidang_id' => $p->bidang_id,
                'judul' => $p->judul,
                'deskripsi' => $p->isi,
                'mulai' => $p->mulai,
                'selesai' => $p->selesai,
                'lokasi' => $p->lokasi,
                'status' => $p->status,
                'published_at' => $p->published_at,
                'deleted_at' => $p->deleted_at,
                'created_at' => $p->created_at,
                'updated_at' => $p->updated_at,
            ]);
        });

        Schema::table('pengumuman', function (Blueprint $table) {
            $table->dropIndex(['jenis']);
            $table->dropColumn(['jenis', 'mulai', 'selesai', 'lokasi']);
        });
    }
}

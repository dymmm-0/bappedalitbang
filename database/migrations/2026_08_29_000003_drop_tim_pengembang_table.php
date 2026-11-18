<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 | Hapus fitur "Tim Pengembang" yang sudah tidak memiliki menu/CRUD di admin,
 | tidak tertaut di navbar publik, dan tidak dipakai siapa pun. Tabel + sisa
 | permission manage_tim_pengembang (dari seeder lama) ikut dihapus.
 */
class DropTimPengembangTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('tim_pengembang');

        $permissionIds = DB::table('permissions')
            ->where('name', 'manage_tim_pengembang')
            ->where('guard_name', 'web')
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('model_has_permissions')->whereIn('permission_id', $permissionIds)->delete();
        DB::table('permissions')->whereIn('id', $permissionIds)->delete();
    }

    public function down()
    {
        Schema::create('tim_pengembang', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nama');
            $table->string('jabatan')->nullable();
            $table->longText('bio')->nullable();
            $table->string('foto')->nullable();
            $table->string('email')->nullable();
            $table->longText('sosmed')->nullable();
            $table->string('instagram')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('tiktok')->nullable();
            $table->integer('urutan')->default(0);
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        app('cache')->forget(config('permission.cache.key'));
        DB::table('permissions')->insert([
            'name' => 'manage_tim_pengembang',
            'guard_name' => 'web',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddSosmedColumnsToTimPengembangTable extends Migration
{
    protected $platforms = ['instagram', 'facebook', 'twitter', 'youtube', 'tiktok'];

    public function up()
    {
        Schema::table('tim_pengembang', function (Blueprint $table) {
            foreach ($this->platforms as $platform) {
                $table->string($platform)->nullable()->after('email');
            }
        });

        foreach (DB::table('tim_pengembang')->whereNotNull('sosmed')->where('sosmed', '!=', '[]')->get() as $row) {
            $lines = json_decode($row->sosmed, true);
            if (! is_array($lines)) {
                continue;
            }

            $data = [];
            foreach ($lines as $line) {
                if (! is_string($line) || ! str_contains($line, '|')) {
                    continue;
                }
                [$platform, $url] = array_map('trim', explode('|', $line, 2));
                if (in_array($platform, $this->platforms, true) && $url) {
                    $data[$platform] = $url;
                }
            }

            if ($data) {
                DB::table('tim_pengembang')->where('id', $row->id)->update($data);
            }
        }
    }

    public function down()
    {
        Schema::table('tim_pengembang', function (Blueprint $table) {
            foreach ($this->platforms as $platform) {
                $table->dropColumn($platform);
            }
        });
    }
}
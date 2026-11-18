<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Struktur extends Model
{
    protected $table = 'struktur';

    protected $guarded = [];

    public static function firstOrCreateRow(): self
    {
        return static::firstOrCreate([], ['judul' => '', 'isi' => '']);
    }

    public function getGambarUrlAttribute()
    {
        return media_url($this->gambar);
    }
}

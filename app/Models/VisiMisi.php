<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisiMisi extends Model
{
    protected $table = 'visi_misi';

    protected $guarded = [];

    protected $casts = [
        'misi' => 'array',
    ];

    public static function firstOrCreateRow(): self
    {
        return static::firstOrCreate([], ['visi' => '', 'misi' => '[]']);
    }
}

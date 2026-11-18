<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TugasFungsi extends Model
{
    protected $table = 'tugas_fungsi';

    protected $guarded = [];

    protected $casts = [
        'fungsi' => 'array',
    ];
}
<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasRevisions;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Penelitian extends Model
{
    use BelongsToBidang, HasRevisions, HasStatus, SoftDeletes;

    protected $table = 'penelitian';

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    public function getFileUrlAttribute()
    {
        return media_url($this->file);
    }

    public function getGambarUrlAttribute()
    {
        return media_url($this->gambar);
    }
}

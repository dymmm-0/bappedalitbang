<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class Galeri extends Model
{
    use BelongsToBidang, HasSortOrder;

    protected $table = 'galeri';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function getGambarUrlAttribute()
    {
        return media_url($this->gambar);
    }
}

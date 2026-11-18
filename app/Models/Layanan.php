<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Layanan extends Model
{
    protected $table = 'layanan';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function getLogoUrlAttribute()
    {
        return media_url($this->logo);
    }
}

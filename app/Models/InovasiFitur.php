<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class InovasiFitur extends Model
{
    use HasSortOrder;

    protected $table = 'inovasi_fitur';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}

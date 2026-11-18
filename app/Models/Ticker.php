<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class Ticker extends Model
{
    use HasSortOrder;

    protected $table = 'ticker';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}

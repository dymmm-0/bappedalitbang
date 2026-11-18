<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{
    use HasSortOrder;

    protected $table = 'faq';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
    ];
}

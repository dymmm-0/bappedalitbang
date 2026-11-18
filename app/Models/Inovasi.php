<?php

namespace App\Models;

use App\Models\Concerns\Approvable;
use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasRevisions;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Inovasi extends Model
{
    use Approvable, BelongsToBidang, HasRevisions, HasStatus, SoftDeletes;

    protected $table = 'inovasi';

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
    ];
}
<?php

namespace App\Models;

use App\Models\Concerns\Approvable;
use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasRevisions;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pengumuman extends Model
{
    use Approvable, BelongsToBidang, HasRevisions, HasStatus, SoftDeletes;

    protected $table = 'pengumuman';

    protected $guarded = [];

    protected $casts = [
        'tanggal' => 'date',
        'mulai' => 'datetime',
        'selesai' => 'datetime',
        'published_at' => 'datetime',
    ];
}

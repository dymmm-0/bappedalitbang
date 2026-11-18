<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasRevisions;
use Illuminate\Database\Eloquent\Model;

class ProfilBidang extends Model
{
    use BelongsToBidang, HasRevisions;

    protected $table = 'profil_bidang';

    protected $guarded = [];

    protected $casts = [
        'tupoksi' => 'array',
        'program_kerja' => 'array',
    ];
}
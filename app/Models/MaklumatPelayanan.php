<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MaklumatPelayanan extends Model
{
    protected $table = 'maklumat_pelayanan';

    protected $guarded = [];

    public function getGambarUrlAttribute()
    {
        return media_url($this->gambar);
    }
}

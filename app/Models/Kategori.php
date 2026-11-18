<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    protected $guarded = [];

    public function artikel()
    {
        return $this->hasMany(Artikel::class, 'id_kategori');
    }
}

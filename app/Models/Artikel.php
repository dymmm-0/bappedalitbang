<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasRevisions;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Artikel extends Model
{
    use BelongsToBidang, HasRevisions, HasStatus, SoftDeletes;

    protected $table = 'artikel';

    protected $primaryKey = 'artikel_id';

    protected $guarded = [];

    protected $casts = [
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'id_kategori');
    }

    public function getGambarUrlAttribute()
    {
        return media_url($this->gambar);
    }
}

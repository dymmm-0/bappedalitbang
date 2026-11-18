<?php

namespace App\Models;

use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bidang extends Model
{
    use HasSortOrder;

    protected $table = 'bidang';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function profil(): HasOne
    {
        return $this->hasOne(ProfilBidang::class);
    }

    public function getGambarUrlAttribute()
    {
        return media_url($this->gambar);
    }

    /**
     * Deskripsi & tugas kini disimpan di tabel profil_bidang (dikelola
     * admin bidang). Accessor menjaga kode/view lama tetap berfungsi.
     */
    public function getDeskripsiAttribute()
    {
        return $this->profil ? $this->profil->deskripsi : null;
    }

    public function getPoinAttribute()
    {
        return $this->profil ? $this->profil->tupoksi : [];
    }
}
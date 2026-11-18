<?php

namespace App\Models;

use App\Models\Concerns\Approvable;
use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasSortOrder;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Dokumen extends Model
{
    use Approvable, BelongsToBidang, HasSortOrder, HasStatus;

    protected $table = 'dokumen';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
        'status' => 'string',
    ];

    /**
     * Visibilitas publik (enabled) mengikuti status persetujuan:
     * hanya status 'published' (disetujui) yang tampil di halaman publik.
     */
    protected static function booted()
    {
        static::saving(function (self $m) {
            $m->enabled = $m->status === self::STATUS_PUBLISHED;
        });
    }

    /** Kategori dokumen perencanaan: key = nilai tersimpan di DB, value = nama lengkap. */
    public const CATEGORIES = [
        'RPJPD' => 'Rencana Pembangunan Jangka Panjang Daerah',
        'RPJMD' => 'Rencana Pembangunan Jangka Menengah Daerah',
        'RKPD' => 'Rencana Kerja Pemerintah Daerah',
        'Dokumen Pendukung & Evaluasi' => 'Dokumen Pendukung & Evaluasi',
    ];

    public function getFileUrlAttribute()
    {
        return media_url($this->file);
    }
}

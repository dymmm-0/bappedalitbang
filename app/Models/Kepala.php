<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;

/**
 * "Kepala Bappedalitbang" dikelola lewat tabel pegawai yang sama namun
 * di-scope hanya ke baris dengan jabatan Kepala Bappedalitbang / Kepala Badan.
 * Logika persetujuan, status, urutan, dan bidang BUKAN bagian dari scope
 * karena data Kepala milik instansi (bidang_id null) dan hanya boleh dikelola
 * oleh Kepala Bappedalitbang / Super Admin (permission manage_kepala).
 */
class Kepala extends Pegawai
{
    protected $table = 'pegawai';

    protected static function booted()
    {
        parent::booted();

        static::addGlobalScope('kepala', function (Builder $query) {
            $query->whereRaw(
                "jabatan REGEXP 'kepala[[:space:]]+(bappedalitbang|badan)([^[:alnum:]]|$)'"
            );
        });

        // Jabatan selalu dikunci sebagai Kepala Bappedalitbang agar baris
        // tidak "keluar" dari scope bila user mengubah field jabatan.
        static::creating(function (Kepala $model) {
            if ($model->jabatan === null || $model->jabatan === '') {
                $model->setAttribute('jabatan', 'Kepala Bappedalitbang');
            }
        });
    }
}

<?php

namespace App\Models;

use App\Models\Concerns\Approvable;
use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasSortOrder;
use App\Models\Concerns\HasStatus;
use Illuminate\Database\Eloquent\Model;

class Pegawai extends Model
{
    use Approvable, BelongsToBidang, HasSortOrder, HasStatus;

    protected $table = 'pegawai';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
        'status' => 'string',
        'pending_changes' => 'array',
    ];

    /**
     * Visibilitas publik (enabled) mengikuti status persetujuan.
     */
    protected static function booted()
    {
        static::saving(function (self $m) {
            $m->enabled = $m->status === self::STATUS_PUBLISHED;
        });

        // Admin bidang mengubah data -> jangan langsung menimpa data aktif:
        // simpan snapshot nilai lama ke pending_changes (untuk diff + rollback).
        // Baris berstatus 'pending' disembunyikan dari publik sampai disetujui.
        static::updating(function (self $m) {
            $user = auth()->user();

            if ($user && ! $user->isSuperadmin() && $m->isDirty()) {
                $skip = ['status', 'enabled', 'submitted_by', 'submitted_at', 'approved_by', 'approved_at', 'rejection_reason', 'pending_changes', 'updated_at'];
                $old = [];

                foreach ($m->getDirty() as $key => $value) {
                    if (in_array($key, $skip, true)) {
                        continue;
                    }
                    $old[$key] = $m->getOriginal($key);
                }

                if ($old !== []) {
                    $m->pending_changes = $old;
                }
            }
        });
    }

    public function getFotoUrlAttribute()
    {
        return media_url($this->foto);
    }

    /**
     * Kategori jabatan untuk identitas visual card pegawai.
     * Urutan pengecekan = hierarki struktur organisasi.
     */
    public function getKategoriAttribute(): array
    {
        foreach (self::daftarKategori() as [$key, $label, $color, $rank, $pattern]) {
            if (preg_match($pattern, (string) $this->jabatan)) {
                return [
                    'key' => $key, 'label' => $label, 'color' => $color,
                    'rank' => $rank, 'leader' => $key === 'kepala_badan',
                ];
            }
        }

        return ['key' => 'pelaksana', 'label' => 'Operator / Pelaksana', 'color' => '#64748B', 'rank' => 7, 'leader' => false];
    }

    private static function daftarKategori(): array
    {
        return [
            ['kepala_badan', 'Kepala Bappedalitbang', '#00B8D9', 1, '/kepala\s+(bappedalitbang|badan)\b/i'],
            ['sekretaris', 'Sekretaris', '#3F6B12', 2, '/sekretaris/i'],
            ['kabid', 'Kepala Bidang', '#8BC34A', 3, '/\bkabid\b|kepala\s+bidang/i'],
            ['kasubbag', 'Kepala Subbidang / Subbagian', '#E6D600', 4, '/sub\s*(bidang|bagian)|subbidang|subbagian/i'],
            ['perencana', 'Perencana', '#F39C12', 5, '/\bperencana\b/i'],
            ['analis', 'Analis / Pengadministrasi / Bendahara', '#E94FA3', 6, '/analis|pengadministrasi|bendahara/i'],
            ['pelaksana', 'Operator / Pelaksana', '#64748B', 7, '/operator|penata\s+layanan|pelaksana/i'],
        ];
    }

    public static function kategoriLegenda(): array
    {
        return collect(self::daftarKategori())
            ->map(fn ($k) => ['key' => $k[0], 'label' => $k[1], 'color' => $k[2]])
            ->all();
    }
}

<?php

namespace App\Models\Concerns;

use App\Models\Bidang;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait BelongsToBidang
{
    public function bidang(): BelongsTo
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Filter query hanya ke data milik bidang admin yang sedang login.
     * Super Admin (tanpa bidang_id) tidak terfilter. Hanya diterapkan di
     * dalam panel admin agar halaman publik tetap menampilkan semua data.
     */
    public function scopeForCurrentBidang(Builder $query): Builder
    {
        $user = auth()->user();

        if ($user && $user->bidang_id && is_admin_panel()) {
            $query->where($this->qualifyColumn('bidang_id'), $user->bidang_id);
        }

        return $query;
    }

    protected static function bootBelongsToBidang(): void
    {
        static::addGlobalScope('bidang', fn (Builder $builder) => $builder->forCurrentBidang());

        static::creating(function (Model $model) {
            $user = auth()->user();

            if ($user && $user->bidang_id && is_admin_panel() && $model->getAttribute('bidang_id') === null) {
                $model->setAttribute('bidang_id', $user->bidang_id);
            }
        });
    }
}

<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasSortOrder
{
    /**
     * Scope: records ordered by the urutan (sort) column.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('urutan')->orderBy('id');
    }

    /**
     * Persist a new ordering from an array of ids (e.g. drag & drop).
     */
    public static function reorder(array $ids): void
    {
        foreach (array_values($ids) as $index => $id) {
            static::whereKey($id)->update(['urutan' => $index]);
        }
    }
}

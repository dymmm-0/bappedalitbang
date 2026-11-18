<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;

trait HasStatus
{
    public const STATUS_DRAFT = 'draft';
    public const STATUS_PENDING = 'pending';
    public const STATUS_SCHEDULED = 'scheduled';
    public const STATUS_PUBLISHED = 'published';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_REVISION = 'revision';

    /**
     * Scope: only content that should be publicly visible right now.
     * Hanya status "published" (disetujui) yang lolos ke halaman publik.
     * draft/pending/rejected/revision/scheduled tidak tampil.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PUBLISHED)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_DRAFT);
    }

    public function scopePending(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeRejected(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeNeedsRevision(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_REVISION);
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_SCHEDULED);
    }

    public function scopeAwaitingApproval(Builder $query): Builder
    {
        return $query->whereIn('status', [self::STATUS_PENDING, self::STATUS_REVISION]);
    }

    public function isPublished(): bool
    {
        if ($this->status !== self::STATUS_PUBLISHED) {
            return false;
        }

        return $this->published_at === null || $this->published_at <= now();
    }

    public function isDraft(): bool
    {
        return $this->status === self::STATUS_DRAFT;
    }

    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function isNeedsRevision(): bool
    {
        return $this->status === self::STATUS_REVISION;
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function requiresApproval(): bool
    {
        return in_array($this->status, [self::STATUS_PENDING, self::STATUS_REVISION], true);
    }

    public function statusLabel(): string
    {
        return match ($this->status) {
            self::STATUS_DRAFT => 'Draf',
            self::STATUS_PENDING => 'Menunggu Persetujuan',
            self::STATUS_REJECTED => 'Ditolak',
            self::STATUS_REVISION => 'Perlu Perbaikan',
            self::STATUS_SCHEDULED => 'Terjadwal',
            default => 'Disetujui',
        };
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'bidang_id', 'hidden',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'bidang_id' => 'integer',
        'hidden' => 'boolean',
    ];

    public function bidang()
    {
        return $this->belongsTo(Bidang::class);
    }

    /**
     * Kepala Bappedalitbang (kepala_badan) dan Super Admin (super_admin)
     * memiliki akses penuh sekaligus kewenangan menyetujui/menolak konten.
     */
    public function isSuperadmin(): bool
    {
        return $this->hasRole('kepala_badan') || $this->hasRole('super_admin');
    }

    /**
     * Admin bidang = user yang dibatasi pada satu bidang (bidang_id terisi).
     * Mereka tidak boleh menyetujui/mempublikasikan kontennya sendiri.
     */
    public function isAdminBidang(): bool
    {
        return ! is_null($this->bidang_id);
    }

    /**
     * Kepala Bappedalitbang / Super Admin adalah pihak yang berwenang
     * memeriksa, menyetujui, dan menolak pengajuan konten.
     */
    public function isApprover(): bool
    {
        return $this->isSuperadmin();
    }

    /**
     * Akun developer. Tidak boleh disentuh oleh admin/superadmin lain,
     * kecuali oleh pemilik akun dev itu sendiri.
     */
    public function isDeveloper(): bool
    {
        return $this->hasRole('developer');
    }
}

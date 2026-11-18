<?php

namespace Tests\Feature;

use App\Models\Dokumen;
use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

/**
 * Smoke test Persetujuan Konten (dokumen + pegawai + submenu + auth gate).
 * ponytail: jalankan sekali untuk verifikasi, bukan suite rutin.
 */
class PersetujuanSmokeTest extends TestCase
{
    use WithoutMiddleware;

    public function test_superadmin_can_open_persetujuan_and_detail(): void
    {
        $super = User::where('email', 'admin.super@admin.com')->firstOrFail();
        $this->actingAs($super);

        $this->get('/admin/persetujuan')->assertStatus(200);
        $this->get('/admin/persetujuan?type=dokumen')->assertStatus(200);
        $this->get('/admin/persetujuan?type=pegawai')->assertStatus(200);

        $dok = Dokumen::firstOrFail();
        $this->get("/admin/persetujuan/dokumen/{$dok->id}")->assertStatus(200);

        $peg = Pegawai::firstOrFail();
        $this->get("/admin/persetujuan/pegawai/{$peg->id}")->assertStatus(200);
    }

    public function test_bidang_admin_blocked_from_persetujuan(): void
    {
        $ikw = User::where('email', 'admin.ikw@admin.com')->firstOrFail();
        $this->actingAs($ikw);

        $this->get('/admin/persetujuan')->assertStatus(403);
    }
}

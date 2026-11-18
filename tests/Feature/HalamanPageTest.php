<?php

namespace Tests\Feature;

use Tests\TestCase;

class HalamanPageTest extends TestCase
{
    public function test_profil_pages_load(): void
    {
        $this->get('/profil/struktur')->assertStatus(200);
        $this->get('/profil/pegawai')->assertStatus(200);
        $this->get('/profil/bidang')->assertStatus(200);
        $this->get('/profil/alamat')->assertStatus(200);
        $this->get('/profil/sejarah')->assertStatus(404);
        $this->get('/profil/pimpinan')->assertStatus(404);
    }

    public function test_informasi_pages_load(): void
    {
        $this->get('/pengumuman')->assertStatus(200);
        $this->get('/penelitian')->assertStatus(200);
        $this->get('/dokumen')->assertStatus(200);
        $this->get('/galeri')->assertStatus(200);
        $this->get('/faq')->assertStatus(200);
    }

    public function test_detail_pages_load(): void
    {
        $pengumuman = \DB::table('pengumuman')->first();
        $this->assertNotNull($pengumuman, 'No pengumuman seeded for detail page test.');

        $this->get('/pengumuman/' . $pengumuman->id)->assertStatus(200);
    }

    public function test_penelitian_detail_page_loads(): void
    {
        $penelitian = \DB::table('penelitian')->first();
        $this->assertNotNull($penelitian, 'No penelitian seeded for detail page test.');

        $this->get('/penelitian/' . $penelitian->id)->assertStatus(200);
    }

    public function test_search_page_loads(): void
    {
        $this->get('/search')->assertStatus(200);
        $this->get('/search?search=' . urlencode('Karo'))->assertStatus(200);
        $this->get('/search?search=' . urlencode('zzzznotfound'))->assertStatus(200);
    }

    public function test_bidang_detail_page_loads(): void
    {
        $bidang = \DB::table('bidang')->first();
        $this->assertNotNull($bidang, 'No bidang seeded for detail page test.');

        $this->get('/profil/bidang/' . $bidang->id)->assertStatus(200);
        $this->get('/profil/bidang/99999')->assertStatus(404);
    }
}

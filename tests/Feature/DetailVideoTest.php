<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Video terkait di halaman detail artikel (/detail/{id}).
 * Relasi disimpan di artikel.video_id -> galeri_video.id.
 * Tanpa RefreshDatabase (tes against DB produksi, bersihkan row + video_id temp).
 */
class DetailVideoTest extends TestCase
{
    use WithoutMiddleware;

    public function test_instagram_video_shows_as_link_card(): void
    {
        // artikel 18 ("Taptu") dipasangkan ke video Instagram #23 via migrasi.
        $res = $this->get('/detail/18');

        $res->assertStatus(200)
            ->assertSee('Video Terkait')
            ->assertSee('instagram.com/reel');
    }

    public function test_youtube_video_embeds_as_responsive_iframe(): void
    {
        $vid = DB::table('galeri_video')->insertGetId([
            'judul' => 'TEST YT DETAIL',
            'url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'enabled' => true,
            'urutan' => 99,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        try {
            DB::table('artikel')->where('artikel_id', 14)->update(['video_id' => $vid]);

            $res = $this->get('/detail/14');

            $res->assertStatus(200)
                ->assertSee('Video Terkait')
                ->assertSee('youtube.com/embed/dQw4w9WgXcQ')
                ->assertSee('aspect-ratio: 16 / 9;')
                ->assertSee('allowfullscreen');
        } finally {
            DB::table('artikel')->where('artikel_id', 14)->update(['video_id' => null]);
            DB::table('galeri_video')->where('id', $vid)->delete();
        }
    }

    public function test_no_video_section_when_unlinked(): void
    {
        DB::table('artikel')->where('artikel_id', 14)->update(['video_id' => null]);

        $res = $this->get('/detail/14');

        $res->assertStatus(200)->assertDontSee('Video Terkait');
    }
}

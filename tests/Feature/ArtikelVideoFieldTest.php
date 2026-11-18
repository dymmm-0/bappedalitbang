<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Field "Video Terkait" di form admin Artikel: admin mengetik URL langsung.
 * URL disimpan sebagai data galeri_video (sumber sama dengan menu Galeri Video),
 * dipakai ulang bila URL sudah ada (tidak duplikat), dan dihubungkan via artikel.video_id.
 */
class ArtikelVideoFieldTest extends TestCase
{
    use WithoutMiddleware;

    private function user()
    {
        return User::where('email', 'admin.kepala@admin.com')->firstOrFail();
    }

    private function storeArticle($judul, $videoUrl)
    {
        $kat = DB::table('kategori')->first()->id;

        return $this->call('POST', '/admin/artikel/add', [
            'judul' => $judul,
            'isi' => '<p>isi</p>',
            'id_kategori' => $kat,
            'video_url' => $videoUrl,
        ], [], ['image' => UploadedFile::fake()->image('cover.jpg')]);
    }

    public function test_add_form_has_url_input(): void
    {
        $this->actingAs($this->user());

        $this->get('/admin/artikel/add')
            ->assertStatus(200)
            ->assertSee('Video Terkait')
            ->assertSee('name="video_url"', false);
    }

    public function test_store_url_creates_galeri_video_and_links(): void
    {
        $this->actingAs($this->user());
        $url = 'https://www.youtube.com/watch?v=' . uniqid();

        $res = $this->storeArticle('TEST URL STORE ' . uniqid(), $url);
        $res->assertStatus(302);

        $video = DB::table('galeri_video')->where('url', $url)->first();
        $this->assertNotNull($video, 'galeri_video record harus dibuat dari URL');

        $art = DB::table('artikel')->where('video_id', $video->id)->first();
        $this->assertNotNull($art);
        $this->assertEquals($video->id, $art->video_id);

        DB::table('artikel')->where('artikel_id', $art->artikel_id)->delete();
        DB::table('galeri_video')->where('id', $video->id)->delete();
    }

    public function test_duplicate_url_reuses_same_record(): void
    {
        $this->actingAs($this->user());
        $url = 'https://www.youtube.com/watch?v=' . uniqid();

        $this->storeArticle('TEST DUP A ' . uniqid(), $url);
        $this->storeArticle('TEST DUP B ' . uniqid(), $url);

        $videos = DB::table('galeri_video')->where('url', $url)->get();
        $this->assertCount(1, $videos, 'URL sama tidak boleh jadi 2 record');

        $arts = DB::table('artikel')->where('video_id', $videos->first()->id)->get();
        $this->assertCount(2, $arts);
        $this->assertEquals($videos->first()->id, $arts[0]->video_id);
        $this->assertEquals($videos->first()->id, $arts[1]->video_id);

        foreach ($arts as $a) {
            DB::table('artikel')->where('artikel_id', $a->artikel_id)->delete();
        }
        DB::table('galeri_video')->where('url', $url)->delete();
    }

    public function test_edit_prefills_url_and_can_change_or_remove(): void
    {
        $this->actingAs($this->user());
        $kat = DB::table('kategori')->first()->id;
        $oldUrl = 'https://www.youtube.com/watch?v=' . uniqid();
        $vid = DB::table('galeri_video')->insertGetId([
            'bidang_id' => $this->user()->bidang_id, 'judul' => 'TEST EDIT', 'url' => $oldUrl,
            'urutan' => 0, 'enabled' => 1, 'created_at' => now(), 'updated_at' => now(),
        ]);
        $judul = 'TEST EDIT VIDEO ' . uniqid();
        $id = DB::table('artikel')->insertGetId([
            'judul' => $judul, 'isi' => '<p>x</p>', 'id_kategori' => $kat,
            'user_id' => $this->user()->id, 'bidang_id' => $this->user()->bidang_id,
            'status' => 'published', 'video_id' => $vid, 'gambar' => 'dummy.jpg',
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $this->get("/admin/artikel/{$id}")
            ->assertStatus(200)
            ->assertSee('value="' . $oldUrl . '"', false);

        // change URL -> hubungan diperbarui, record lama tidak dihapus
        $newUrl = 'https://www.youtube.com/watch?v=' . uniqid();
        $this->call('PUT', "/admin/artikel/{$id}", [
            'judul' => $judul, 'isi' => '<p>x</p>', 'id_kategori' => $kat, 'video_url' => $newUrl,
        ])->assertStatus(302);

        $newVid = DB::table('galeri_video')->where('url', $newUrl)->first();
        $this->assertNotNull($newVid);
        $this->assertEquals($newVid->id, DB::table('artikel')->where('artikel_id', $id)->value('video_id'));
        $this->assertNotNull(DB::table('galeri_video')->where('id', $vid)->first(), 'record lama tidak boleh dihapus otomatis');

        // remove (kosong) -> lepas hubungan
        $this->call('PUT', "/admin/artikel/{$id}", [
            'judul' => $judul, 'isi' => '<p>x</p>', 'id_kategori' => $kat, 'video_url' => '',
        ])->assertStatus(302);
        $this->assertNull(DB::table('artikel')->where('artikel_id', $id)->value('video_id'));

        DB::table('artikel')->where('artikel_id', $id)->delete();
        DB::table('galeri_video')->whereIn('id', [$vid, $newVid->id])->delete();
    }
}

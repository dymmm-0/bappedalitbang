<?php

namespace App\Models;

use App\Models\Concerns\BelongsToBidang;
use App\Models\Concerns\HasSortOrder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class GaleriVideo extends Model
{
    use BelongsToBidang, HasSortOrder;

    protected $table = 'galeri_video';

    protected $guarded = [];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (GaleriVideo $model) {
            if (empty($model->thumbnail) && $model->url) {
                $model->thumbnail = $model->fetchThumbnail();
            }
        });
    }

    /**
     * Ambil thumbnail (YouTube: img.youtube.com; Instagram: og:image dari
     * halaman post) dan simpan sebagai file lokal agar panel tetap tampil
     * walau tanpa koneksi ke platform. TikTok tidak didukung (anti-bot).
     */
    protected function fetchThumbnail(): ?string
    {
        $url = $this->url;

        if (! $url) {
            return null;
        }

        if (preg_match('/(?:youtu\.be\/|v=|embed\/|shorts\/)([\w-]+)/', $url, $m)) {
            $remote = 'https://img.youtube.com/vi/' . $m[1] . '/hqdefault.jpg';
            $name = $m[1] . '_' . uniqid() . '.jpg';
        } elseif (preg_match('#instagram\.com/(?:p|reel|tv)/([\w-]+)#', $url, $mi)) {
            $remote = $this->instagramOgImage('https://www.instagram.com/reel/' . $mi[1] . '/');
            if (! $remote) {
                return null;
            }
            $name = $mi[1] . '_' . uniqid() . '.jpg';
        } else {
            return null;
        }

        try {
            $dir = 'uploads/galeri_video/thumbs';
            File::ensureDirectoryExists(public_path($dir));

            $ctx = stream_context_create([
                'http' => [
                    'timeout' => 15,
                    'ignore_errors' => true,
                    'follow_location' => 1,
                    'header' => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0 Safari/537.36\r\nAccept: text/html\r\n",
                ],
            ]);
            $data = @file_get_contents($remote, false, $ctx);

            if ($data === false || $data === '') {
                return null;
            }

            File::put(public_path($dir . '/' . $name), $data);

            return $dir . '/' . $name;
        } catch (\Throwable $e) {
            return null;
        }
    }

    private function instagramOgImage(string $pageUrl): ?string
    {
        // UA curl memicu SSR penuh dengan og:image; UA Chrome malah dapat
        // login wall / shell JS tanpa meta tersebut.
        $ctx = stream_context_create([
            'http' => [
                'timeout' => 15,
                'ignore_errors' => true,
                'follow_location' => 1,
                'header' => "User-Agent: curl/8.0\r\n",
            ],
        ]);
        $html = @file_get_contents($pageUrl, false, $ctx);
        if ($html === false || $html === '') {
            return null;
        }

        if (preg_match('/<meta[^>]+property="og:image"[^>]+content="([^"]+)"/i', $html, $m)) {
            return html_entity_decode($m[1], ENT_QUOTES);
        }
        if (preg_match('/<meta[^>]+content="([^"]+)"[^>]+property="og:image"/i', $html, $m)) {
            return html_entity_decode($m[1], ENT_QUOTES);
        }

        return null;
    }

    public function getThumbnailUrlAttribute()
    {
        return media_url($this->thumbnail);
    }
}

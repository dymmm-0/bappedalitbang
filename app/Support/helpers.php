<?php

use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\HomeSection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;

if (! function_exists('all_settings')) {
    /**
     * Return the full settings collection, cached.
     *
     * @return \Illuminate\Support\Collection
     */
    function all_settings()
    {
        return Cache::remember('cms.settings', 3600, function () {
            try {
                return DB::table('settings')->pluck('value', 'key');
            } catch (\Throwable $e) {
                return collect();
            }
        });
    }
}

if (! function_exists('setting')) {
    /**
     * Get a site setting by key, with a default fallback.
     *
     * @param  string  $key
     * @param  mixed  $default
     * @return mixed
     */
    function setting($key, $default = null)
    {
        return all_settings()->get($key, $default);
    }
}

if (! function_exists('settings_forget')) {
    /**
     * Clear the cached settings collection.
     *
     * @return void
     */
    function settings_forget()
    {
        Cache::forget('cms.settings');
    }
}

if (! function_exists('visits_total')) {
    /**
     * Total kunjungan website (tabel visits), di-cache 1 jam.
     *
     * @return int
     */
    function visits_total()
    {
        return (int) Cache::remember('cms.visits_total', 3600, function () {
            return DB::table('visits')->count();
        });
    }
}

if (! function_exists('visits_today')) {
    /**
     * Jumlah kunjungan hari ini (tabel visits).
     *
     * @return int
     */
    function visits_today()
    {
        return (int) Cache::remember('cms.visits_today', 600, function () {
            return DB::table('visits')->whereDate('created_at', now()->toDateString())->count();
        });
    }
}

if (! function_exists('visits_active')) {
    /**
     * Jumlah pengunjung aktif (kunjungan dalam 5 menit terakhir).
     *
     * @return int
     */
    function visits_active()
    {
        return (int) Cache::remember('cms.visits_active', 60, function () {
            return DB::table('visits')->where('created_at', '>=', now()->subMinutes(5))->count();
        });
    }
}

if (! function_exists('menu')) {
    /**
     * Get the ordered menu items (as a tree) for a given location.
     *
     * @param  string  $location
     * @return \Illuminate\Support\Collection
     */
    function menu($location)
    {
        $menu = Menu::where('location', $location)->first();

        if (! $menu) {
            return collect();
        }

        return MenuItem::where('menu_id', $menu->id)
            ->where('enabled', true)
            ->whereNull('parent_id')
            ->orderBy('sort_order')
            ->with('children')
            ->get();
    }
}

if (! function_exists('sections')) {
    /**
     * Get enabled homepage sections, ordered.
     *
     * @return \Illuminate\Support\Collection
     */
    function sections()
    {
        return HomeSection::where('enabled', true)
            ->orderBy('sort_order')
            ->get();
    }
}

if (! function_exists('media_url')) {
    /**
     * Build a public URL for a file stored in the uploads directory.
     *
     * @param  string|null  $path
     * @return string|null
     */
    function media_url($path)
    {
        if (! $path) {
            return null;
        }

        $path = ltrim($path, '/');

        if (str_starts_with($path, 'uploads/')) {
            return asset($path);
        }

        return asset('uploads/' . $path);
    }
}

if (! function_exists('is_external_url')) {
    /**
     * True jika URL menuju situs lain (http/https lengkap),
     * false untuk tautan internal seperti "/profil" atau "#".
     *
     * @param  string|null  $url
     * @return bool
     */
    /**
 * Konversi URL video apa pun menjadi URL embed YouTube yang valid, atau null
 * bila bukan URL YouTube yang dikenali. Menangani youtu.be, watch?v=, /embed/,
 * /shorts/, dan URL embed yang sudah jadi. Dipakai di halaman detail artikel.
 */
function youtube_embed_url(?string $url): ?string
{
    if (! $url) {
        return null;
    }

    if (preg_match('/(?:youtu\.be\/|v=|embed\/|shorts\/)([\w-]+)/', $url, $m)) {
        return 'https://www.youtube.com/embed/' . $m[1];
    }

    if (str_contains($url, 'youtube.com/embed/')) {
        return $url;
    }

    return null;
}

function is_external_url($url)
    {
        return is_string($url) && preg_match('#^https?://#i', $url) === 1;
    }
}

if (! function_exists('is_admin_panel')) {
    /**
     * True saat request berada di dalam panel administrasi (prefix /admin).
     * Dipakai agar scope bidang hanya berlaku untuk pengelolaan data,
     * bukan untuk halaman publik.
     *
     * @return bool
     */
    function is_admin_panel()
    {
        if (app()->runningInConsole()) {
            return false;
        }

        return request()->is('admin') || request()->is('admin/*');
    }
}

if (! function_exists('hex_to_rgb')) {
    /**
     * Convert a hex color to a "R G B" triplet for use in CSS variables.
     *
     * @param  string|null  $hex
     * @param  string  $fallback
     * @return string
     */
    function hex_to_rgb($hex, $fallback = '15 23 42')
    {
        $hex = ltrim((string) $hex, '#');

        if (strlen($hex) === 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return $fallback;
        }

        return hexdec(substr($hex, 0, 2)) . ' ' . hexdec(substr($hex, 2, 2)) . ' ' . hexdec(substr($hex, 4, 2));
    }
}

if (! function_exists('store_upload')) {
    /**
     * Pindahkan file upload ke public/uploads/<dir> dan konversi gambar
     * raster (jpg/png/gif/bmp/webp) ke .webp agar ringan.
     * Whitelist ekstensi & MIME (config/uploads.php) dipaksakan di sini;
     * file di luar whitelist ditolak dengan ValidationException.
     *
     * Dengan musim semi, semua path upload (Crud_Controller, MediaController,
     * Admin\Artikel_controller, Admin\Iklan_controller) meneruskan lewat
     * fungsi ini sehingga konversi webp jadi di satu tempat saja.
     *
     * @return string relative path di dalam /uploads, contoh "artikel/2024/foto.webp"
     */
    function store_upload(UploadedFile $file, string $dir): string
    {
        $dir = trim($dir, '/');
        File::ensureDirectoryExists(public_path('uploads/' . $dir));

        $allowedImages = array_map('strtolower', (array) config('uploads.allowed_image_extensions', []));
        $allowedFiles  = array_map('strtolower', (array) config('uploads.allowed_file_extensions', []));
        $blockedMimes  = (array) config('uploads.blocked_mimes', []);

        // Ekstensi di-whitelist (tidak mempercayai ekstensi yang dikirim client).
        $ext  = strtolower((string) $file->getClientOriginalExtension());
        $mime = strtolower((string) $file->getMimeType());

        if (! in_array($ext, $allowedImages, true) && ! in_array($ext, $allowedFiles, true)) {
            upload_fail('Tipe file .' . $ext . ' tidak diizinkan untuk diunggah.');
        }

        // Blokir MIME berbahaya walau ekstensinya tampak sah (polyglot).
        foreach ($blockedMimes as $pattern) {
            if ($mime !== '' && ($mime === $pattern || fnmatch($pattern, $mime))) {
                upload_fail('Isi berkas tidak diizinkan untuk diunggah.');
            }
        }

        $base = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . uniqid();

        // File raster dibangun ulang menjadi webp agar konten berbahaya dibuang.
        if (in_array($ext, $allowedImages, true)) {
            if (! str_starts_with($mime, 'image/')) {
                upload_fail('Berkas tidak dikenali sebagai gambar yang valid.');
            }

            $webp = $base . '.webp';
            if (function_exists('imagewebp') && convert_to_webp($file, public_path('uploads/' . $dir . '/' . $webp))) {
                return $dir . '/' . $webp;
            }

            // Konversi tak tersedia/gagal -> simpan raster asli (masih aman:
            // ekstensi + MIME sudah lolos whitelist, tanpa script yang bisa jalan).
        }

        $name = $base . '.' . $ext;
        $file->move(public_path('uploads/' . $dir), $name);

        return $dir . '/' . $name;
    }
}

if (! function_exists('upload_fail')) {
    /**
     * Tolak upload dengan pesan validasi yang ramah user.
     */
    function upload_fail(string $message): void
    {
        throw \Illuminate\Validation\ValidationException::withMessages(['file' => $message]);
    }
}

if (! function_exists('convert_to_webp')) {
    /**
     * Konversi satu file gambar menjadi WebP via GD. Return true jika berhasil.
     */
    function convert_to_webp(UploadedFile $file, string $outPath): bool
    {
        if (! function_exists('imagecreatefromstring') || ! function_exists('imagewebp')) {
            return false;
        }

        $img = @imagecreatefromstring($file->get());

        if ($img === false) {
            return false;
        }

        // alpha transparan (png/gif/webp) dipertahankan; jpg -> putih
        $ok = @imagewebp($img, $outPath, 80);
        imagedestroy($img);

        return $ok;
    }
}

// Warna teks kontras (hitam/putih) untuk badge berlatar warna kategori jabatan.
function warna_teks_kontras(string $hex): string
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) < 6) {
        return '#FFFFFF';
    }

    $r = hexdec(substr($hex, 0, 2));
    $g = hexdec(substr($hex, 2, 2));
    $b = hexdec(substr($hex, 4, 2));

    return (0.299 * $r + 0.587 * $g + 0.114 * $b) > 160 ? '#0F172A' : '#FFFFFF';
}

// Campur warna hex ke arah putih ($t = 0..1) untuk background card kategori pegawai.
function warna_tint(string $hex, float $t = 0.8): string
{
    $hex = ltrim($hex, '#');
    if (strlen($hex) < 6) {
        return '#FFFFFF';
    }

    $mix = fn (int $c) => (int) round($c * (1 - $t) + 255 * $t);
    $r = $mix(hexdec(substr($hex, 0, 2)));
    $g = $mix(hexdec(substr($hex, 2, 2)));
    $b = $mix(hexdec(substr($hex, 4, 2)));

    return sprintf('#%02X%02X%02X', $r, $g, $b);
}

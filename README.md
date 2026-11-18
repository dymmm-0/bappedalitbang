# BAPPEDALITBANG Kabupaten Karo — CMS Portal

Website resmi Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan (Bappedalitbang) Kabupaten Karo, lengkap dengan panel admin untuk mengelola seluruh konten tanpa menyentuh kode.

## Stack

| Komponen | Keterangan |
|---|---|
| Framework | Laravel 13 (PHP `^8.5`) |
| Database | MySQL 8 (utf8) |
| Styling | Tailwind CSS via CLI standalone (`resources/assets/css/app.css` → `public/css/app.css`) |
| Auth & otorisasi | `spatie/laravel-permission`, konfigurasi di `config/rbac.php` |
| Editor | TinyMCE + upload media internal |
| Upload | `public/uploads/` (bukan Storage), gambar otomatis dikonversi ke WebP |

Tidak ada GSAP/SPA — animasi pakai vanilla JS (IntersectionObserver, `prefers-reduced-motion` aware).

## Halaman publik

`/` beranda · `/artikel` + `/detail/{id}` · `/search` · `/profil/struktur` · `/profil/tugas-fungsi` · `/profil/maklumat-pelayanan` · `/profil/pegawai` · `/profil/alamat` (peta) · `/profil/bidang/{id}` · `/pengumuman` · `/penelitian` · `/dokumen` · `/galeri` · `/faq` · `/kontak`

## Panel admin (`/admin`)

- **CRUD config-driven** — `config/cms.php` memetakan slug modul ke model, kolom, dan field. `Crud_Controller` generik membaca config itu; menambah layar admin = menambah entri di config, bukan menulis controller baru.
- **Modul**: visi & misi, bidang, struktur organisasi, tugas & fungsi, maklumat pelayanan, pegawai, kepala, pengumuman, galeri, video, dokumen, FAQ, ticker, layanan terkait, inovasi 5LMAN, artikel, kategori, komentar, iklan.
- **Persetujuan konten** (`/admin/persetujuan`) — konten berstatus `pending` dari admin bidang/media direview Kepala Badan sebelum tayang.
- **Pengaturan situs** (`/admin/settings`, `/admin/alamat`) — identitas, sosmed, SEO, warna tema, footer, koordinat peta. Nilai dibaca lewat helper `setting()` (cache 1 jam).
- **Menu & home section** (`/admin/menu`, `/admin/home-sections`) — navigasi header sepenuhnya dari DB (`menu_items`), urutan blok beranda bisa diatur.
- **Media & revisi** — semua upload lewat `store_upload()` (whitelist ekstensi di `config/uploads.php`), model dengan trait `HasRevisions` menyimpan snapshot tiap perubahan.

### Role (RBAC bidang)

`kepala_badan` dan `super_admin` (akses penuh, tanpa batas bidang) · `kepala_sub_bagian_umum` (semua konten) · `admin_media` (artikel, galeri, video, media) · `admin_sekretariat`, `admin_ppepd`, `admin_ppm`, `admin_litbang`, `admin_psda`, `admin_ikw` (satu bidang).

Konten bidang-scoped otomatis difilter lewat trait `BelongsToBidang` (global scope + auto-isi `bidang_id`), termasuk saat akses langsung ke URL ID bidang lain → 404.

## Struktur penting

```
app/Http/Controllers/Beranda_Controller.php   halaman publik
app/Http/Controllers/Halaman_Controller.php   halaman profil/konten publik
app/Http/Controllers/Crud_Controller.php      admin CRUD generik (config-driven)
app/Models/                                   model Eloquent + traits (HasRevisions, HasStatus, BelongsToBidang)
config/cms.php                                sumber kebenaran modul admin & setting
config/rbac.php                               role + daftar modul bidang-scoped
resources/views/halaman/                      view publik
resources/views/admin/                        view panel admin
```

## Instalasi

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan db:seed --class=RolePermissionSeeder   # wajib: role & permission admin
npm install && npm run tailwind
php artisan serve
```

Panduan lengkap (vhost Laragon, Apache/PHP, akun admin, troubleshooting) ada di **[PANDUAN_INSTALASI.md](PANDUAN_INSTALASI.md)**.

## Perintah harian

```bash
npm run tailwind          # build CSS sekali
npm run tailwind:watch    # rebuild saat file CSS berubah
vendor/bin/phpunit        # jalankan test
```
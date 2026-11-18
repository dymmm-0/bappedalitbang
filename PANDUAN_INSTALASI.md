# Panduan Instalasi — Website Bappedalitbang Kab. Karo (Laravel)

Proyek: CMS Website Bappedalitbang Kabupaten Karo.
Stack: Laravel 13, PHP >= 8.4.1 (direkomendasikan 8.5.x), MySQL 8, Tailwind CSS.

## Persyaratan
- Windows + **Laragon** (atau server Apache + PHP + MySQL senilai).
- **PHP >= 8.4.1** (composer.lock menguncinya). Pakai PHP dari Laragon yang versinya >= 8.4.
- **Composer** (dibutuhkan untuk meng-install dependency).
- Internet saat `composer install` pertama kali.

> Folder `vendor/` dan `node_modules/` **tidak** ikut di-zip — harus di-install sendiri.

## Langkah Instalasi

1. **Ekstrak** zip proyek ke folder web Laragon, mis. `D:\Laragon\www\bappedalitbang`.

2. **Buat file `.env`** dari template:
   ```
   copy .env.example .env
   ```
   Sesuaikan isi (contoh untuk Laragon lokal):
   ```
   APP_NAME="Bappedalitbang Karo"
   APP_ENV=local
   APP_DEBUG=true
   APP_URL=http://bappedalitbang.test
   DB_DATABASE=portal
   DB_USERNAME=root
   DB_PASSWORD=
   ```
   (Ikuti nama host/vhost yang dipakai di Laragon Anda.)

3. **Install dependency PHP:**
   ```
   composer install
   ```

4. **Buat kunci aplikasi:**
   ```
   php artisan key:generate
   ```

5. **Buat database** `portal` di MySQL (Laragon: MySQL > Start, lalu buat DB `portal`).

6. **Import data situs** (artikel, galeri, dokumen, menu, akun admin, dll.):
   ```
   mysql -uroot portal < backup/portal_dump_serah_terima_2026-09-09.sql
   ```
   > Jalankan perintah ini dari prompt yang `mysql` tersedia (Laragon → Tools → Terminal).
   > Jika file dump tidak cocok dengan struktur migration terbaru, jalankan juga `php artisan migrate` dulu *(hanya tambahan kolom/tabel, aman)*.

7. **Akses situs:** DocumentRoot harus menunjuk ke folder `public` (Laragon: buat vhost atau arahkan root ke `...\public`). Atau cepat: `php artisan serve` lalu buka `http://127.0.0.1:8000`.

8. **(Opsional) Rebuild CSS** jika ingin mengubah tampilan:
   ```
   npm install
   npm run dev
   ```
   (File `public/css/app.css` sudah jadi, sehingga situs langsung jalan tanpa langkah ini.)

## Akun Admin
- **Kepala / akses penuh:** `admin.kepala@admin.com` / `admin123`
- Sub bagian umum: `admin.umum@admin.com` / `admin123`
- Admin bidang (mis. IKW): `admin.ikw@admin.com` / `admin123`

## Peringatan Penting
- **JANGAN jalankan `php artisan migrate --seed` / `db:seed` tanpa `--class`** pada DB berisi data:
  seeder menu/section bersifat non-destruktif sekarang, tetapi tetap lebih aman pakai dump.
  Seed role/permission yang aman: `php artisan db:seed --class=RolePermissionSeeder`.
- **JANGAN jalankan `php artisan migrate:fresh` / `migrate:refresh`** (menghapus semua tabel).
- Setelah mengubah `.env`, bersihkan cache: `php artisan optimize:clear`.

## Struktur Penting
- `config/cms.php` — daftar modul CRUD admin (tambah menu CRUD di sini).
- `config/rbac.php` — daftar role & permission.
- `public/uploads/` — gambar/konten situs (wajib ikut di-backup untuk full backup).
- `backup/*.sql` — dump database.

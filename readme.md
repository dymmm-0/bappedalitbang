# BAPPEDALITBANG Kab. Karo — CMS Portal

Website + panel admin Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo.

## Stack

- Laravel 13 (PHP ^8.4), MySQL
- Tailwind CSS (CLI standalone) — `npm run tailwind`
- Auth + RBAC: `spatie/laravel-permission`, config di `config/rbac.php`

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

Detail lengkap (vhost Laragon, akun admin, troubleshooting): `PANDUAN_INSTALASI.md`.
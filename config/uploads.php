<?php

/*
|--------------------------------------------------------------------------
| Upload whitelist
|--------------------------------------------------------------------------
|
| Sumber kebenaran untuk apa saja yang boleh di-upload ke public/uploads.
| Fungsi store_upload() (app/Support/helpers.php) MEMAKSA whitelist ini:
| file di luar daftar extension / MIME dilarang (ValidationException).
| Controller (Crud_Controller, MediaController) juga mengirimkan aturan
| `mimes:` dari daftar ini agar user mendapat pesan kesalahan yang jelas.
|
| Catatan keamanan: SCRIPT (php/phtml/phar/cgi/exe/shell/html/js) sengaja
| TIDAK ada di daftar. JANGAN menambahkan ekstensi executable di sini.
|
*/

return [

    /*
    | Ekstensi gambar yang boleh di-upload. File raster direkonstruksi
    | menjadi WebP oleh GD, sehingga konten berbahaya di dalamnya dibuang.
    */
    'allowed_image_extensions' => [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'bmp',
        'webp',
    ],

    /*
    | Ekstensi file non-gambar yang boleh di-upload (dokumen, arsip, video).
    */
    'allowed_file_extensions' => [
        // dokumen
        'pdf',
        'doc',
        'docx',
        'xls',
        'xlsx',
        'ppt',
        'pptx',
        'rtf',
        'txt',
        'csv',
        'odt',
        'ods',
        // arsip
        'zip',
        'rar',
        '7z',
        'tar',
        'gz',
        // audio
        'mp3',
        'wav',
        'm4a',
        // video
        'mp4',
        'm4v',
        'mov',
        'avi',
        'mkv',
        'webm',
        'wmv',
    ],

    /*
    | MIME yang selalu dilarang, walau ekstensinya tampak aman (polyglot /
    | ekstensi yang dimanipulasi). Pattern boleh memakai wildcard.
    */
    'blocked_mimes' => [
        'application/x-httpd-php',
        'application/x-php',
        'application/php',
        'text/x-php',
        'text/html',
        'application/xhtml+xml',
        'text/javascript',
        'application/javascript',
        'application/x-sh',
        'text/x-shellscript',
        'application/x-msdownload',
        'application/x-dosexec',
        'application/vnd.microsoft.portable-executable',
        'application/x-msi',
        'image/svg+xml',
    ],

];
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Site settings (managed from the admin panel)
    |--------------------------------------------------------------------------
    */

    'settings' => [
        'identitas' => [
            'label' => 'Identitas Situs',
            'fields' => [
                ['key' => 'nama_situs', 'label' => 'Nama Situs', 'type' => 'text', 'default' => 'Bappedalitbang Kabupaten Karo'],
                ['key' => 'tagline', 'label' => 'Tagline', 'type' => 'text', 'default' => 'Perencanaan Pembangunan yang Terpadu dan Berkualitas'],
                ['key' => 'deskripsi', 'label' => 'Deskripsi Singkat', 'type' => 'textarea', 'default' => ''],
                ['key' => 'alamat', 'label' => 'Alamat Kantor', 'type' => 'textarea', 'default' => ''],
                ['key' => 'alamat_map', 'label' => 'Koordinat Peta (lat, lng)', 'type' => 'text', 'default' => '', 'placeholder' => 'cth: 3.0929, 98.5039'],
                ['key' => 'telepon', 'label' => 'Telepon', 'type' => 'text', 'default' => ''],
                ['key' => 'email', 'label' => 'Email Resmi', 'type' => 'text', 'default' => ''],
            ],
        ],
        'sosmed' => [
            'label' => 'Media Sosial',
            'fields' => [
                ['key' => 'facebook', 'label' => 'Facebook', 'type' => 'url', 'default' => ''],
                ['key' => 'instagram', 'label' => 'Instagram', 'type' => 'url', 'default' => ''],
                ['key' => 'twitter', 'label' => 'Twitter / X', 'type' => 'url', 'default' => ''],
                ['key' => 'youtube', 'label' => 'YouTube', 'type' => 'url', 'default' => ''],
                ['key' => 'tiktok', 'label' => 'TikTok', 'type' => 'url', 'default' => ''],
            ],
        ],
        'seo' => [
            'label' => 'SEO & Meta',
            'fields' => [
                ['key' => 'meta_description', 'label' => 'Meta Description', 'type' => 'textarea', 'default' => ''],
                ['key' => 'meta_keywords', 'label' => 'Meta Keywords', 'type' => 'text', 'default' => ''],
                ['key' => 'google_analytics', 'label' => 'Google Analytics (ID)', 'type' => 'text', 'default' => ''],
            ],
        ],
        'theme' => [
            'label' => 'Tampilan',
            'fields' => [
                ['key' => 'warna_utama', 'label' => 'Warna Utama', 'type' => 'color', 'default' => '#0F172A'],
                ['key' => 'warna_sekunder', 'label' => 'Warna Sekunder', 'type' => 'color', 'default' => '#334155'],
                ['key' => 'warna_aksen', 'label' => 'Warna Aksen', 'type' => 'color', 'default' => '#0369A1'],
                ['key' => 'warna_aksen_emas', 'label' => 'Warna Emas (Aksen)', 'type' => 'color', 'default' => '#C9A227'],
            ],
        ],
        'footer' => [
            'label' => 'Footer',
            'fields' => [
                ['key' => 'footer_tentang', 'label' => 'Tentang (Footer)', 'type' => 'textarea', 'default' => ''],
                ['key' => 'footer_alamat', 'label' => 'Alamat (Footer)', 'type' => 'textarea', 'default' => ''],
                ['key' => 'footer_telepon', 'label' => 'Telepon (Footer)', 'type' => 'text', 'default' => ''],
                ['key' => 'footer_email', 'label' => 'Email (Footer)', 'type' => 'text', 'default' => ''],
                ['key' => 'footer_copyright', 'label' => 'Teks Hak Cipta', 'type' => 'text', 'default' => ''],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Homepage section registry (enable / order / headings)
    |--------------------------------------------------------------------------
    */

    'home_sections' => [
        'ticker' => ['label' => 'Ticker / Running Text', 'heading' => false],
        'slider' => ['label' => 'Slider Banner', 'heading' => false],
        'beranda' => ['label' => 'Sambutan Beranda', 'heading' => false],
        'visi_misi' => ['label' => 'Visi & Misi', 'heading' => true],
        'bidang' => ['label' => 'Bidang', 'heading' => true],
        'penelitian' => ['label' => 'Penelitian', 'heading' => true],
        'program' => ['label' => 'Program', 'heading' => true],
        'galeri' => ['label' => 'Galeri', 'heading' => true],
        'pengumuman' => ['label' => 'Pengumuman', 'heading' => true],
        'kontak' => ['label' => 'Kontak', 'heading' => false],
    ],

    /*
    |--------------------------------------------------------------------------
    | CMS content modules (driven by Crud_Controller)
    |--------------------------------------------------------------------------
    |
    | Each module maps to an Eloquent model + a set of editable fields.
    | "singleton" modules hold a single editable row (visi/misi, sejarah, dll).
    | Field types: text, textarea, editor, image, file, select, checkbox, number,
    | date, datetime, repeater (list of lines stored as JSON).
    |
    */

    'modules' => [

        'visi_misi' => [
            'label' => 'Visi & Misi',
            'label_plural' => 'Visi & Misi',
            'model' => App\Models\VisiMisi::class,
            'icon' => 'fa-bullseye',
            'singleton' => true,
            'columns' => [],
            'fields' => [
                ['name' => 'visi', 'label' => 'Visi', 'type' => 'editor'],
                ['name' => 'misi', 'label' => 'Misi (satu per baris)', 'type' => 'repeater'],
            ],
        ],

        'bidang' => [
            'label' => 'Bidang',
            'label_plural' => 'Bidang',
            'model' => App\Models\Bidang::class,
            'icon' => 'fa-sitemap',
            'columns' => [
                ['name' => 'nama', 'label' => 'Nama'],
                ['name' => 'singkatan', 'label' => 'Singkatan'],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'boolean'],
            ],
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama Bidang', 'type' => 'text', 'required' => true],
                ['name' => 'singkatan', 'label' => 'Singkatan', 'type' => 'text', 'required' => true],
                ['name' => 'ikon', 'label' => 'Ikon (FontAwesome)', 'type' => 'text'],
                ['name' => 'gambar', 'label' => 'Gambar', 'type' => 'image'],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'checkbox'],
            ],
            'sortable' => true,
            'searchable' => ['nama', 'singkatan'],
        ],

        'struktur' => [
            'label' => 'Struktur Organisasi',
            'label_plural' => 'Struktur Organisasi',
            'model' => App\Models\Struktur::class,
            'icon' => 'fa-diagram-project',
            'singleton' => true,
            'columns' => [],
            'fields' => [
                ['name' => 'judul', 'label' => 'Judul', 'type' => 'text'],
                ['name' => 'gambar', 'label' => 'Bagan Struktur (Gambar)', 'type' => 'image'],
                ['name' => 'isi', 'label' => 'Keterangan', 'type' => 'editor'],
            ],
        ],

        'tugas_fungsi' => [
            'label' => 'Tugas dan Fungsi',
            'label_plural' => 'Tugas dan Fungsi',
            'model' => App\Models\TugasFungsi::class,
            'icon' => 'fa-briefcase',
            'singleton' => true,
            'columns' => [],
            'fields' => [
                ['name' => 'pembukaan', 'label' => 'Pembukaan (Kedudukan)', 'type' => 'editor'],
                ['name' => 'tugas', 'label' => 'Tugas Pokok', 'type' => 'editor'],
                ['name' => 'fungsi', 'label' => 'Fungsi (satu per baris)', 'type' => 'repeater'],
            ],
        ],

        'maklumat_pelayanan' => [
            'label' => 'Maklumat Pelayanan',
            'label_plural' => 'Maklumat Pelayanan',
            'model' => App\Models\MaklumatPelayanan::class,
            'icon' => 'fa-file-lines',
            'singleton' => true,
            'columns' => [],
            'fields' => [
                ['name' => 'judul', 'label' => 'Judul', 'type' => 'text'],
                ['name' => 'gambar', 'label' => 'Gambar / Scan Maklumat', 'type' => 'image'],
                ['name' => 'isi', 'label' => 'Keterangan', 'type' => 'editor'],
            ],
        ],

        'pegawai' => [
            'label' => 'Pegawai',
            'label_plural' => 'Pegawai',
            'model' => App\Models\Pegawai::class,
            'icon' => 'fa-users',
            'columns' => [
                ['name' => 'foto', 'label' => 'Foto', 'type' => 'image'],
                ['name' => 'nama', 'label' => 'Nama'],
                ['name' => 'jabatan', 'label' => 'Jabatan'],
                ['name' => 'status', 'label' => 'Status', 'type' => 'status'],
            ],
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama', 'type' => 'text', 'required' => true],
                ['name' => 'nip', 'label' => 'NIP', 'type' => 'text'],
                ['name' => 'jabatan', 'label' => 'Jabatan', 'type' => 'text'],
                ['name' => 'bagian', 'label' => 'Bagian', 'type' => 'text'],
                ['name' => 'bidang_id', 'label' => 'Kategori (Bidang)', 'type' => 'select', 'options' => ['' => '— Pilih Bidang —', '1' => 'Sekretariat', '2' => 'PPEPD', '3' => 'PPM', '4' => 'Litbang', '5' => 'IKW', '6' => 'PSDA']],
                ['name' => 'foto', 'label' => 'Foto', 'type' => 'image'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'text'],
                ['name' => 'status', 'label' => 'Status Persetujuan', 'type' => 'select', 'options' => ['draft' => 'Draf', 'pending' => 'Menunggu Persetujuan', 'published' => 'Disetujui / Terbit', 'rejected' => 'Ditolak', 'revision' => 'Perlu Perbaikan']],
                ['name' => 'rejection_reason', 'label' => 'Alasan Penolakan / Perbaikan', 'type' => 'textarea'],
            ],
            'sortable' => true,
            'searchable' => ['nama', 'nip', 'jabatan'],
        ],

        'kepala' => [
            'label' => 'Kepala',
            'label_plural' => 'Kepala',
            'model' => App\Models\Kepala::class,
            'icon' => 'fa-user-tie',
            'columns' => [
                ['name' => 'foto', 'label' => 'Foto', 'type' => 'image'],
                ['name' => 'nama', 'label' => 'Nama'],
                ['name' => 'nip', 'label' => 'NIP'],
                ['name' => 'status', 'label' => 'Status', 'type' => 'status'],
            ],
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama Lengkap', 'type' => 'text', 'required' => true],
                ['name' => 'nip', 'label' => 'NIP', 'type' => 'text'],
                ['name' => 'bagian', 'label' => 'Eselon / Golongan', 'type' => 'text'],
                ['name' => 'foto', 'label' => 'Foto', 'type' => 'image'],
                ['name' => 'email', 'label' => 'Email', 'type' => 'text'],
                ['name' => 'status', 'label' => 'Status Persetujuan', 'type' => 'select', 'options' => ['draft' => 'Draf', 'pending' => 'Menunggu Persetujuan', 'published' => 'Disetujui / Terbit', 'rejected' => 'Ditolak', 'revision' => 'Perlu Perbaikan']],
                ['name' => 'rejection_reason', 'label' => 'Alasan Penolakan / Perbaikan', 'type' => 'textarea'],
            ],
            'sortable' => true,
            'searchable' => ['nama', 'nip', 'jabatan'],
        ],

        'pengumuman' => [
            'label' => 'Pengumuman',
            'label_plural' => 'Pengumuman',
            'model' => App\Models\Pengumuman::class,
            'icon' => 'fa-bullhorn',
            'columns' => [
                ['name' => 'judul', 'label' => 'Judul'],
                ['name' => 'jenis', 'label' => 'Jenis'],
                ['name' => 'tanggal', 'label' => 'Tanggal'],
                ['name' => 'status', 'label' => 'Status', 'type' => 'status'],
            ],
            'fields' => [
                ['name' => 'judul', 'label' => 'Judul', 'type' => 'text', 'required' => true],
                ['name' => 'jenis', 'label' => 'Jenis', 'type' => 'select', 'options' => ['pengumuman' => 'Pengumuman', 'agenda' => 'Agenda Kegiatan']],
                ['name' => 'isi', 'label' => 'Isi', 'type' => 'editor', 'required' => true],
                ['name' => 'tanggal', 'label' => 'Tanggal', 'type' => 'date'],
                ['name' => 'mulai', 'label' => 'Waktu Mulai (Agenda)', 'type' => 'datetime'],
                ['name' => 'selesai', 'label' => 'Waktu Selesai (Agenda)', 'type' => 'datetime'],
                ['name' => 'lokasi', 'label' => 'Lokasi (Agenda)', 'type' => 'text'],
                ['name' => 'status', 'label' => 'Status', 'type' => 'select', 'options' => ['draft' => 'Draf', 'pending' => 'Menunggu Persetujuan', 'published' => 'Disetujui / Terbit', 'rejected' => 'Ditolak', 'revision' => 'Perlu Perbaikan', 'scheduled' => 'Terjadwal']],
                ['name' => 'published_at', 'label' => 'Jadwal Terbit', 'type' => 'datetime'],
                ['name' => 'rejection_reason', 'label' => 'Alasan Penolakan / Perbaikan', 'type' => 'textarea'],
            ],
            'sortable' => false,
            'searchable' => ['judul', 'isi', 'lokasi'],
        ],

        'galeri' => [
            'label' => 'Galeri',
            'label_plural' => 'Galeri',
            'model' => App\Models\Galeri::class,
            'icon' => 'fa-images',
            'columns' => [
                ['name' => 'gambar', 'label' => 'Gambar', 'type' => 'image'],
                ['name' => 'judul', 'label' => 'Judul'],
                ['name' => 'album', 'label' => 'Album'],
            ],
            'fields' => [
                ['name' => 'judul', 'label' => 'Judul', 'type' => 'text'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'text'],
                ['name' => 'gambar', 'label' => 'Gambar', 'type' => 'image', 'required' => true],
                ['name' => 'album', 'label' => 'Album', 'type' => 'text'],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'checkbox'],
            ],
            'sortable' => true,
            'searchable' => ['judul', 'album'],
        ],

        'galeri_video' => [
            'label' => 'Video',
            'label_plural' => 'Galeri Video',
            'model' => App\Models\GaleriVideo::class,
            'icon' => 'fa-video',
            'columns' => [
                ['name' => 'judul', 'label' => 'Judul'],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'boolean'],
            ],
            'fields' => [
                ['name' => 'judul', 'label' => 'Judul', 'type' => 'text', 'required' => true],
                ['name' => 'url', 'label' => 'URL Video (YouTube)', 'type' => 'text'],
                ['name' => 'file', 'label' => 'Upload File Video (MP4)', 'type' => 'file'],
                ['name' => 'thumbnail', 'label' => 'Cover / Thumbnail (otomatis dari YouTube; upload manual untuk video media sosial)', 'type' => 'image'],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea'],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'checkbox'],
            ],
            'sortable' => true,
            'searchable' => ['judul'],
        ],

        'dokumen' => [
            'label' => 'Dokumen',
            'label_plural' => 'Dokumen',
            'model' => App\Models\Dokumen::class,
            'icon' => 'fa-folder-open',
            'columns' => [
                ['name' => 'judul', 'label' => 'Judul'],
                ['name' => 'kategori', 'label' => 'Kategori'],
                ['name' => 'tahun', 'label' => 'Tahun/Periode'],
                ['name' => 'file', 'label' => 'File', 'type' => 'file'],
                ['name' => 'status', 'label' => 'Status', 'type' => 'status'],
            ],
            'fields' => [
                ['name' => 'judul', 'label' => 'Nama Dokumen', 'type' => 'text', 'required' => true],
                ['name' => 'kategori', 'label' => 'Kategori', 'type' => 'select', 'required' => true, 'options' => App\Models\Dokumen::CATEGORIES],
                ['name' => 'tahun', 'label' => 'Tahun / Periode (mis. 2026 atau 2021-2026)', 'type' => 'text', 'required' => true],
                ['name' => 'deskripsi', 'label' => 'Deskripsi Singkat', 'type' => 'textarea'],
                ['name' => 'file', 'label' => 'File Dokumen', 'type' => 'file', 'required' => true],
                ['name' => 'status', 'label' => 'Status Persetujuan', 'type' => 'select', 'options' => ['draft' => 'Draf', 'pending' => 'Menunggu Persetujuan', 'published' => 'Disetujui / Terbit', 'rejected' => 'Ditolak', 'revision' => 'Perlu Perbaikan']],
                ['name' => 'rejection_reason', 'label' => 'Alasan Penolakan / Perbaikan', 'type' => 'textarea'],
            ],
            'sortable' => true,
            'searchable' => ['judul', 'kategori', 'tahun'],
        ],

        'faq' => [
            'label' => 'FAQ',
            'label_plural' => 'FAQ',
            'model' => App\Models\Faq::class,
            'icon' => 'fa-circle-question',
            'columns' => [
                ['name' => 'pertanyaan', 'label' => 'Pertanyaan'],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'boolean'],
            ],
            'fields' => [
                ['name' => 'pertanyaan', 'label' => 'Pertanyaan', 'type' => 'text', 'required' => true],
                ['name' => 'jawaban', 'label' => 'Jawaban', 'type' => 'editor', 'required' => true],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'checkbox'],
            ],
            'sortable' => true,
            'searchable' => ['pertanyaan', 'jawaban'],
        ],

        'ticker' => [
            'label' => 'Ticker',
            'label_plural' => 'Ticker (Running Text)',
            'model' => App\Models\Ticker::class,
            'icon' => 'fa-arrow-trend-up',
            'columns' => [
                ['name' => 'teks', 'label' => 'Teks'],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'boolean'],
            ],
            'fields' => [
                ['name' => 'teks', 'label' => 'Teks', 'type' => 'text', 'required' => true],
                ['name' => 'url', 'label' => 'URL', 'type' => 'text'],
                ['name' => 'enabled', 'label' => 'Aktif', 'type' => 'checkbox'],
            ],
            'sortable' => true,
            'searchable' => ['teks'],
        ],

        'layanan' => [
            'label' => 'Layanan Terkait',
            'label_plural' => 'Layanan Terkait',
            'model' => App\Models\Layanan::class,
            'icon' => 'fa-link',
            'numbering' => true,
            'togglable' => true,
            'columns' => [
                ['name' => 'logo', 'label' => 'Preview Gambar', 'type' => 'image'],
                ['name' => 'nama', 'label' => 'Nama Layanan'],
                ['name' => 'url', 'label' => 'URL'],
                ['name' => 'enabled', 'label' => 'Status', 'type' => 'boolean'],
            ],
            'fields' => [
                ['name' => 'nama', 'label' => 'Nama Layanan', 'type' => 'text', 'required' => true],
                ['name' => 'url', 'label' => 'URL / Link', 'type' => 'url', 'required' => true],
                ['name' => 'logo', 'label' => 'Gambar / Banner', 'type' => 'image', 'required' => true],
                ['name' => 'enabled', 'label' => 'Status Aktif', 'type' => 'checkbox'],
            ],
            'searchable' => ['nama', 'url'],
        ],

        'inovasi_fitur' => [
            'label' => 'Inovasi 5LMAN',
            'label_plural' => 'Inovasi 5LMAN',
            'model' => App\Models\InovasiFitur::class,
            'icon' => 'fa-lightbulb',
            'togglable' => true,
            'columns' => [
                ['name' => 'ikon', 'label' => 'Ikon', 'type' => 'icon'],
                ['name' => 'judul', 'label' => 'Nama Fitur'],
                ['name' => 'enabled', 'label' => 'Status', 'type' => 'boolean'],
                ['name' => 'urutan', 'label' => 'Urutan', 'type' => 'number'],
            ],
            'fields' => [
                ['name' => 'judul', 'label' => 'Nama Fitur', 'type' => 'text', 'required' => true],
                ['name' => 'deskripsi', 'label' => 'Deskripsi', 'type' => 'textarea'],
                ['name' => 'ikon', 'label' => 'Ikon SVG (path, opsional)', 'type' => 'textarea'],
                ['name' => 'enabled', 'label' => 'Status Aktif', 'type' => 'checkbox'],
                ['name' => 'urutan', 'label' => 'Urutan Tampilan', 'type' => 'number'],
            ],
            'sortable' => true,
            'searchable' => ['judul'],
        ],

    ],

];

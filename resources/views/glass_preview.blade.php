<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Preview Glassmorphism {{ ($variant ?? 'gov') === 'neon' ? 'Neon' : 'Governance' }} — Bappedalitbang</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <style>
        .glass-demo { min-height: 100vh; }
        .glass-chip {
            display: inline-flex; align-items: center; padding: 8px 18px; border-radius: 999px;
            font-size: 12px; font-weight: 700; letter-spacing: 0.08em; color: rgb(var(--glp));
            background: rgba(255,255,255,0.55); border: 1px solid rgba(255,255,255,0.8);
            backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
            box-shadow: 0 4px 20px var(--g-shadow);
        }
        .glass-select-field label { font-size: 12px; font-weight: 600; color: #475569; margin-bottom: 6px; display: block; }
        .glass-select-field select {
            width: 100%; padding: 12px 16px; border-radius: 14px;
            border: 1px solid rgba(255,255,255,0.7); background: rgba(255,255,255,0.55);
            color: var(--g-ink); font-size: 14px; outline: none; font-family: inherit;
        }
        .glass-check { display: inline-flex; align-items: center; gap: 8px; font-size: 14px; color: var(--g-mut); }
        .glass-check input { accent-color: rgb(var(--glp)); }
        @media (prefers-reduced-motion: reduce) {
            .glass-blob { animation: none !important; }
        }
    </style>
</head>
<body>
@php
    $bidangList = App\Models\Bidang::where('enabled', true)->ordered()->limit(4)->get();
    $quickStats = collect([
        ['label' => 'Bidang', 'value' => 4],
        ['label' => 'Kategori', 'value' => 3],
        ['label' => 'Artikel', 'value' => 6],
        ['label' => 'Pengumuman', 'value' => 2],
    ]);
    $artikel = \DB::table('artikel as a')->join('kategori as k','k.id','=','a.id_kategori')->select('a.judul','a.isi','a.created_at','k.nama as kategori')->orderby('created_at','desc')->limit(3)->get();
@endphp
<div class="glass-demo {{ ($variant ?? 'gov') === 'neon' ? 'neon' : 'gov' }}">

    {{-- ============ NAVBAR ============ --}}
    <header class="glass-nav">
        <div class="glass-nav-inner">
            <a href="#" class="glass-logo">
                <span class="glass-logo-mark">B</span>
                <span>
                    <span class="glass-logo-title">Bappedalitbang</span>
                    <span class="glass-logo-sub">Kabupaten Karo</span>
                </span>
            </a>
            <nav class="glass-nav-links">
                <a href="#hero" class="glass-nav-link active">Beranda</a>
                <a href="#cards" class="glass-nav-link">Bidang</a>
                <a href="#forms" class="glass-nav-link">Layanan</a>
                <a href="#admin" class="glass-nav-link">Admin</a>
                <a href="#table" class="glass-nav-link">Data</a>
            </nav>
            <a href="#forms" class="glass-btn glass-btn-primary">Masuk</a>
        </div>
    </header>

    {{-- ============ HERO ============ --}}
    <section id="hero" class="glass-hero">
        <div class="glass-hero-bg" aria-hidden="true">
            <span class="glass-blob" style="--x:12%"></span>
            <span class="glass-blob" style="--x:78%"></span>
            <span class="glass-blob" style="--x:88%"></span>
        </div>
        <div class="glass-hero-inner">
            <div class="glass-chip">Portal Resmi Pemerintah</div>
            <h1 class="glass-hero-title">Badan Perencanaan Pembangunan Daerah,<br><span>Penelitian & Pengembangan</span></h1>
            <p class="glass-hero-sub">Merencanakan, meneliti, dan mengembangkan potensi daerah secara terpadu, berkualitas, dan berkelanjutan.</p>
            <div class="glass-hero-actions">
                <a href="#cards" class="glass-btn glass-btn-primary">Profil Lembaga</a>
                <a href="#cards" class="glass-btn glass-btn-outline">Program Pembangunan</a>
            </div>

            <div class="glass-search">
                <svg class="glass-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                <input type="text" placeholder="Cari artikel, berita, informasi publik...">
                <button class="glass-btn glass-btn-primary glass-search-btn">Cari</button>
            </div>
        </div>
    </section>

    {{-- ============ STATS ============ --}}
    <section class="glass-section" id="stats">
        <div class="glass-grid glass-grid-4">
            @foreach($quickStats as $st)
            <div class="glass-card glass-stat">
                <div class="glass-stat-num" data-counter="{{ $st['value'] }}">{{ $st['value'] }}</div>
                <div class="glass-stat-label">{{ $st['label'] }}</div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ============ BIDANG CARDS ============ --}}
    <section class="glass-section" id="cards">
        <div class="glass-section-head">
            <span class="glass-eyebrow">Struktur & Tugas</span>
            <h2 class="glass-section-title">Bidang Layanan</h2>
        </div>
        <div class="glass-grid glass-grid-4">
            @forelse($bidangList as $b)
            <article class="glass-card glass-hover">
                <div class="glass-icon" style="background:rgba(147,51,234,.12);color:#7c3aed">⚙</div>
                <h3 class="glass-card-title">{{ $b->nama }}</h3>
                <p class="glass-card-text">{{ $b->deskripsi }}</p>
                <span class="glass-badge">{{ $b->singkatan }}</span>
            </article>
            @empty
            <article class="glass-card glass-hover">
                <div class="glass-icon" style="background:rgba(59,130,246,.12);color:#2563eb">🏛</div>
                <h3 class="glass-card-title">Perencanaan & Evaluasi</h3>
                <p class="glass-card-text">Menyusun dan memantau dokumen perencanaan pembangunan daerah.</p>
                <span class="glass-badge">Bidang</span>
            </article>
            @endforelse
        </div>
    </section>

    {{-- ============ NEWS ============ --}}
    <section class="glass-section" id="news">
        <div class="glass-section-head">
            <span class="glass-eyebrow">Informasi Terkini</span>
            <h2 class="glass-section-title">Berita & Kegiatan</h2>
        </div>
        <div class="glass-grid glass-grid-3">
            @foreach($artikel as $at)
            <article class="glass-card glass-hover glass-news">
                <div class="glass-news-meta">
                    <span class="glass-badge">{{ $at->kategori }}</span>
                    <span class="glass-news-date">{{ date('d M Y', strtotime($at->created_at)) }}</span>
                </div>
                <h3 class="glass-card-title">{{ $at->judul }}</h3>
                <p class="glass-card-text">{!! strip_tags(substr($at->isi, 0, 150)) !!}…</p>
                <a href="#" class="glass-link">Baca selengkapnya <span>→</span></a>
            </article>
            @endforeach
        </div>
    </section>

    {{-- ============ FORMS ============ --}}
    <section class="glass-section" id="forms">
        <div class="glass-section-head">
            <span class="glass-eyebrow">Layanan</span>
            <h2 class="glass-section-title">Formulir & Komponen</h2>
        </div>
        <div class="glass-grid glass-grid-2">
            <div class="glass-card glass-form">
                <h3 class="glass-card-title">Kirim Pesan</h3>
                <div class="glass-field">
                    <label>Nama Lengkap</label>
                    <input type="text" placeholder="Nama lengkap">
                </div>
                <div class="glass-field">
                    <label>Email</label>
                    <input type="email" placeholder="email@contoh.com">
                </div>
                <div class="glass-field">
                    <label>Subjek</label>
                    <input type="text" placeholder="Perihal pesan">
                </div>
                <div class="glass-field">
                    <label>Pesan</label>
                    <textarea rows="3" placeholder="Tulis pesan Anda..."></textarea>
                </div>
                <button class="glass-btn glass-btn-primary glass-btn-block">Kirim Pesan</button>
            </div>
            <div class="glass-card glass-form">
                <h3 class="glass-card-title">Contoh Tombol & Badge</h3>
                <div style="display:flex;flex-wrap:wrap;gap:12px">
                    <button class="glass-btn glass-btn-primary">Tombol Utama</button>
                    <button class="glass-btn glass-btn-gold">Tombol Gold</button>
                    <button class="glass-btn glass-btn-ghost">Tombol Ghost</button>
                    <button class="glass-btn glass-btn-danger">Hapus</button>
                </div>
                <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:18px">
                    <span class="glass-badge">Berita</span>
                    <span class="glass-badge glass-badge-blue">Pengumuman</span>
                    <span class="glass-badge glass-badge-pink">Kegiatan</span>
                    <span class="glass-badge glass-badge-yellow">Draft</span>
                    <span class="glass-badge glass-badge-green">Terbit</span>
                </div>
                <div style="margin-top:24px">
                    <div class="glass-select-field">
                        <label>Pilih Bidang</label>
                        <select>
                            <option>Bidang Perencanaan</option>
                            <option>Bidang Infrastruktur</option>
                            <option>Bidang Litbang</option>
                        </select>
                    </div>
                </div>
                <div style="display:flex;gap:16px;flex-wrap:wrap;margin-top:18px">
                    <label class="glass-check"><input type="checkbox"> Terbitkan</label>
                    <label class="glass-check"><input type="radio" name="g"> Ringkas</label>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ TABLE ============ --}}
    <section class="glass-section" id="table">
        <div class="glass-section-head">
            <span class="glass-eyebrow">Data</span>
            <h2 class="glass-section-title">Tabel Data</h2>
        </div>
        <div class="glass-card glass-table-card">
            <table class="glass-table">
                <thead>
                    <tr>
                        <th>#</th><th>Nama</th><th>Email</th><th>Role</th><th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach([
                        ['John','john@admin.com','Super Admin','Aktif'],
                        ['Siti','siti@admin.com','Admin Bidang','Aktif'],
                        ['Budi','budi@admin.com','Pegawai','Nonaktif'],
                        ['Rina','rina@admin.com','Operator','Aktif'],
                    ] as $i => $r)
                    <tr>
                        <td>{{ $i+1 }}</td>
                        <td>{{ $r[0] }}</td>
                        <td>{{ $r[1] }}</td>
                        <td><span class="glass-badge glass-badge-blue">{{ $r[2] }}</span></td>
                        <td><span class="glass-badge {{ $r[3] === 'Aktif' ? 'glass-badge-green' : 'glass-badge-grey' }}">{{ $r[3] }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>

    {{-- ============ ADMIN SIDEBAR ============ --}}
    <section class="glass-section" id="admin">
        <div class="glass-section-head">
            <span class="glass-eyebrow">Admin</span>
            <h2 class="glass-section-title">Sidebar & Dashboard</h2>
        </div>
        <div class="glass-grid glass-grid-2">
            <div class="glass-card glass-admin">
                <div class="glass-admin-head">
                    <span class="glass-logo-mark">B</span>
                    <div>
                        <div class="glass-admin-title">Panel Admin</div>
                        <div class="glass-admin-sub">Bappedalitbang</div>
                    </div>
                </div>
                <div class="glass-admin-menu">
                    <a class="glass-admin-item active"><span class="glass-dot"></span>Dashboard</a>
                    <a class="glass-admin-item"><span class="glass-dot"></span>Artikel</a>
                    <a class="glass-admin-item"><span class="glass-dot"></span>Pengumuman</a>
                    <a class="glass-admin-item"><span class="glass-dot"></span>Bidang</a>
                    <a class="glass-admin-item"><span class="glass-dot"></span>Pengguna</a>
                    <a class="glass-admin-item"><span class="glass-dot"></span>Pengaturan</a>
                </div>
            </div>
            <div class="glass-card glass-form">
                <h3 class="glass-card-title">Ringkasan Statistik Admin</h3>
                <div class="glass-grid glass-grid-2" style="gap:14px">
                    <div class="glass-mini-stat" style="--pc:#7c3aed"><span class="glass-mini-num">24</span><span>Artikel</span></div>
                    <div class="glass-mini-stat" style="--pc:#2563eb"><span class="glass-mini-num">8</span><span>Bidang</span></div>
                    <div class="glass-mini-stat" style="--pc:#db2777"><span class="glass-mini-num">12</span><span>Pengumuman</span></div>
                    <div class="glass-mini-stat" style="--pc:#d97706"><span class="glass-mini-num">5</span><span>Media</span></div>
                </div>
            </div>
        </div>
    </section>

    {{-- ============ FOOTER ============ --}}
    <footer class="glass-footer">
        <div>
            <div class="glass-footer-title">Bappedalitbang Kabupaten Karo</div>
            <p>Jl. Letjend Djamin Ginting, Kabanjahe, Kabupaten Karo, Sumatera Utara</p>
        </div>
        <div class="glass-footer-link">Hubungi Kami</div>
    </footer>
</div>

<script>
    document.querySelectorAll('[data-counter]').forEach(el => {
        const target = parseInt(el.textContent || '0', 10);
        el.textContent = '0';
        const start = performance.now(), dur = 900;
        const step = (now) => {
            const p = Math.min((now - start) / dur, 1);
            el.textContent = Math.round(target * p);
            if (p < 1) requestAnimationFrame(step);
        };
        requestAnimationFrame(step);
    });
</script>
</body>
</html>
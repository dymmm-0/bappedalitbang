<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <title>@yield('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo'))</title>
    <meta name="description" content="{{ setting('meta_description', 'Portal resmi Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo') }}">
    <meta name="keywords" content="{{ setting('meta_keywords', 'Bappedalitbang, perencanaan pembangunan, Kabupaten Karo') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <style>
        :root {
            --c-primary: {{ hex_to_rgb(setting('warna_utama'), '15 23 42') }};
            --c-secondary: {{ hex_to_rgb(setting('warna_sekunder'), '51 65 85') }};
            --c-accent: {{ hex_to_rgb(setting('warna_aksen'), '3 105 161') }};
            --c-gold: {{ hex_to_rgb(setting('warna_aksen_emas'), '201 162 39') }};
            --c-gold-soft: {{ hex_to_rgb(setting('warna_aksen_emas'), '227 194 78') }};
            --c-gold-dark: {{ hex_to_rgb(setting('warna_aksen_emas'), '154 122 26') }};
        }
    </style>
    <link href="{{ asset('css/app.css') }}?v={{ filemtime(public_path('css/app.css')) }}" rel="stylesheet">
    @stack('styles')
</head>
<body class="min-h-screen flex flex-col">
    <a href="#konten" class="sr-only focus:not-sr-only focus:fixed focus:top-3 focus:left-3 focus:z-[100] focus:px-4 focus:py-2 focus:bg-accent focus:text-white focus:rounded-lg focus:text-sm">Langsung ke konten utama</a>

    @php
        $navItems = menu('header')->map(function ($item) {
            return [
                'label' => $item->label,
                'href' => url($item->url),
                'target' => $item->target ?? '_self',
                'children' => $item->children->map(function ($child) {
                    return ['label' => $child->label, 'href' => url($child->url), 'target' => $child->target ?? '_self'];
                }),
            ];
        });
        if ($navItems->isEmpty()) {
            $navItems = collect([
                ['label' => 'Beranda', 'href' => url('/'), 'target' => '_self', 'children' => collect()],
                ['label' => 'Profil', 'href' => url('profil/struktur'), 'target' => '_self', 'children' => collect([
                    ['label' => 'Struktur Organisasi', 'href' => url('profil/struktur'), 'target' => '_self'],
                    ['label' => 'Pegawai', 'href' => url('profil/pegawai'), 'target' => '_self'],
                    ['label' => 'Bidang & Tugas', 'href' => url('profil/bidang'), 'target' => '_self'],
                ])],
                ['label' => 'Berita', 'href' => url('artikel/kategori/1'), 'target' => '_self', 'children' => collect()],
                ['label' => 'Pengumuman', 'href' => url('pengumuman'), 'target' => '_self', 'children' => collect()],
                    ['label' => 'Bidang', 'href' => url('profil/bidang'), 'target' => '_self', 'children' => collect([
                    ['label' => 'Sekretariat', 'href' => url('profil/bidang/1'), 'target' => '_self'],
                    ['label' => 'PPEPD', 'href' => url('profil/bidang/2'), 'target' => '_self'],
                    ['label' => 'PPM', 'href' => url('profil/bidang/3'), 'target' => '_self'],
                    ['label' => 'Litbang', 'href' => url('profil/bidang/4'), 'target' => '_self'],
                    ['label' => 'IKW', 'href' => url('profil/bidang/5'), 'target' => '_self'],
                    ['label' => 'PSDA', 'href' => url('profil/bidang/6'), 'target' => '_self'],
                ])],
                ['label' => 'Regulasi', 'href' => url('dokumen?kategori=Regulasi'), 'target' => '_self', 'children' => collect()],
                ['label' => 'Galeri', 'href' => url('galeri'), 'target' => '_self', 'children' => collect()],
                ['label' => 'FAQ', 'href' => url('faq'), 'target' => '_self', 'children' => collect()],
            ]);
        }
        $kategoris = \DB::table('kategori')->orderBy('nama')->get();
    @endphp

    <div id="app" class="flex flex-col min-h-screen">
        {{-- Top Bar --}}
        <div class="bg-primary text-slate-300 text-xs">
            <div class="container-gov flex items-center justify-between py-2">
                <div class="flex items-center gap-4">
                    <span class="hidden sm:flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        {{ setting('alamat', 'Jl. Letjend Djamin Ginting, Kabanjahe, Karo') }}
                    </span>
                    <span class="flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        {{ setting('telepon', '(0628) 91234') }}
                    </span>
                    <span class="hidden md:flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                        {{ setting('email', 'bappedalitbang@karokab.go.id') }}
                    </span>
                </div>
                <div class="flex items-center gap-4">
                    <a href="{{ route('login') }}" class="hover:text-white transition-colors duration-200 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/></svg>
                        Admin
                    </a>
                </div>
            </div>
        </div>

        {{-- Main Header --}}
        <header id="siteHeader" class="sticky top-0 z-50 px-2 sm:px-4 lg:px-6 pt-2 transition-shadow duration-300">
            <div class="mx-auto w-full max-w-[1440px]">
                <div id="siteHeaderBar" class="rounded-2xl bg-white/75 backdrop-blur-xl border border-white/70 shadow-sm">
                    <div class="flex items-center justify-between h-16 md:h-[72px] px-3 sm:px-5">
                        {{-- Logo --}}
                        <a href="{{ url('/') }}" class="flex items-center gap-3 shrink-0" aria-label="Beranda Bappedalitbang">
                            <span class="relative inline-flex">
                                <img src="{{ asset('uploads/Logo/LogoBappeda.png') }}" alt="Logo Bappeda Kabupaten Karo"
                                     class="h-10 w-10 md:h-11 md:w-11 object-contain rounded-xl bg-white p-1 ring-1 ring-slate-200">
                                <span class="absolute -inset-0.5 -z-10 rounded-2xl bg-gradient-to-br from-accent/30 to-gold/30 blur-[6px]"></span>
                            </span>
                            <span class="hidden sm:block">
                                <span class="block text-base md:text-lg font-heading font-bold text-primary leading-tight">{{ setting('nama_situs_short', 'Bappedalitbang') }}</span>
                                <span class="block text-[10px] md:text-xs text-slate-500 leading-tight -mt-0.5">{{ setting('nama_daerah', 'Kabupaten Karo') }}</span>
                            </span>
                        </a>

                        {{-- Desktop Nav --}}
                        <nav class="hidden xl:flex items-center gap-1" aria-label="Navigasi utama">
                            @foreach($navItems as $item)
                            @if($item['children']->isNotEmpty())
                            <div class="relative group">
                                <a href="{{ $item['href'] }}" target="{{ $item['target'] }}" class="nav-link px-3 inline-flex items-center gap-1 rounded-lg">
                                    {{ $item['label'] }}
                                    <svg class="w-3 h-3 transition-transform duration-200 group-hover:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </a>
                                <div class="absolute left-0 top-full pt-2 invisible opacity-0 translate-y-1 group-hover:visible group-hover:opacity-100 group-hover:translate-y-0 transition-all duration-200 z-50">
                                    <div class="relative bg-white/75 backdrop-blur-xl rounded-2xl border border-white/70 shadow-xl shadow-slate-900/10 p-2 min-w-[220px]">
                                        <span class="absolute -top-1 left-6 w-3 h-3 bg-white/75 border-t border-l border-white/70 rotate-45"></span>
                                        <span class="absolute top-0 left-4 right-4 h-px bg-gradient-to-r from-transparent via-accent/40 to-transparent"></span>
                                        @foreach($item['children'] as $child)
                                        <a href="{{ $child['href'] }}" target="{{ $child['target'] }}" class="block px-4 py-2 text-sm text-slate-600 hover:text-primary hover:bg-slate-100/80 rounded-lg transition-colors">{{ $child['label'] }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            @else
                            <a href="{{ $item['href'] }}" target="{{ $item['target'] }}" class="nav-link px-3 rounded-lg">{{ $item['label'] }}</a>
                            @endif
                            @endforeach
                        </nav>

                        {{-- Search + Mobile Toggle --}}
                        <div class="flex items-center gap-1.5">
                            <a href="{{ url('/').'#kontak' }}" class="hidden md:inline-flex btn-primary !py-2.5 rounded-full">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                                Hubungi Kami
                            </a>
                            <button id="searchToggle" class="p-2.5 text-slate-500 hover:text-accent transition-colors duration-200 rounded-full hover:bg-slate-100" aria-label="Buka pencarian" aria-expanded="false" aria-controls="searchBar">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </button>
                            <button id="menuToggle" class="xl:hidden p-2.5 text-slate-500 hover:text-accent transition-colors duration-200 rounded-full hover:bg-slate-100" aria-label="Buka menu" aria-expanded="false" aria-controls="mobileMenu">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                            </button>
                        </div>
                    </div>

                    {{-- Search Bar --}}
                    <div id="searchBar" class="hidden border-t border-slate-100 px-3 sm:px-5 pb-3 pt-3">
                        <form method="get" action="{{ url('search') }}" class="relative" role="search">
                            <label for="siteSearch" class="sr-only">Cari di situs</label>
                            <input id="siteSearch" type="text" name="search" placeholder="Cari artikel, berita, informasi..." class="w-full pl-12 pr-24 py-3 bg-slate-50/80 border border-border rounded-full text-sm focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 text-white text-sm font-semibold rounded-full px-4 py-1.5 transition-all duration-200 hover:-translate-y-[calc(50%+2px)] shadow-md hover:shadow-lg" style="background:linear-gradient(135deg,#0369a1,#0e7490)">Cari</button>
                        </form>
                    </div>

                    {{-- Mobile Menu --}}
                    <div id="mobileMenu" class="hidden xl:hidden border-t border-slate-100 px-3 py-3 max-h-[70vh] overflow-y-auto">
                        <div class="space-y-1">
                            @foreach($navItems as $item)
                            @if($item['children']->isNotEmpty())
                            <div class="mobile-submenu">
                                <button type="button" class="mobile-submenu-btn w-full flex items-center justify-between gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-accent rounded-full transition-colors" aria-expanded="false">
                                    <span>{{ $item['label'] }}</span>
                                    <svg class="mobile-submenu-arrow w-3.5 h-3.5 shrink-0 text-slate-400 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                </button>
                                <div class="mobile-submenu-list hidden pl-4 pb-1 space-y-1">
                                    @foreach($item['children'] as $child)
                                    <a href="{{ $child['href'] }}" target="{{ $child['target'] }}" class="block px-4 py-2 text-sm text-slate-400 hover:bg-slate-50 hover:text-accent rounded-full transition-colors">{{ $child['label'] }}</a>
                                    @endforeach
                                </div>
                            </div>
                            @else
                            <a href="{{ $item['href'] }}" target="{{ $item['target'] }}" class="block px-4 py-2.5 text-sm font-medium text-slate-600 hover:bg-slate-50 hover:text-accent rounded-full transition-colors">{{ $item['label'] }}</a>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </header>

        {{-- Ticker --}}
        @if(optional(\App\Models\HomeSection::where('type','ticker')->first())->enabled && \App\Models\Ticker::where('enabled', true)->count() > 0)
        <div class="bg-accent text-white text-sm" role="marquee">
            <div class="container-gov flex items-center gap-4 py-2 overflow-hidden">
                <span class="shrink-0 inline-flex items-center gap-1.5 text-xs font-bold uppercase tracking-wider">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Info
                </span>
                <div class="relative flex-1 overflow-hidden whitespace-nowrap">
                    <div class="inline-flex gap-10 animate-marquee">
                        @for($copy = 0; $copy < 2; $copy++)
                            @foreach(\App\Models\Ticker::where('enabled', true)->ordered()->get() as $t)
                            <span class="inline-flex items-center gap-10">
                                @if($t->url)
                                <a href="{{ url($t->url) }}" target="_blank" rel="noopener" class="hover:underline">{{ $t->teks }}</a>
                                @else
                                <span>{{ $t->teks }}</span>
                                @endif
                            </span>
                            @endforeach
                        @endfor
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Hero Section (only on homepage) --}}
        @if(request()->is('/'))
        @section('hero')
        @show
        @endif

        {{-- Main Content --}}
        <main id="konten" class="flex-1">
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-primary text-slate-400">
            <div class="container-gov py-10 md:py-12">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 lg:gap-10">
                    {{-- About --}}
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-10 h-10 bg-primary rounded-lg flex items-center justify-center ring-1 ring-gold/60">
                                <svg class="w-6 h-6 text-gold" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M19.5 3.5L18 2l-1.5 1.5L15 2l-1.5 1.5L12 2l-1.5 1.5L9 2 7.5 3.5 6 2 4.5 3.5 3 2v20l1.5-1.5L6 22l1.5-1.5L9 22l1.5-1.5L12 22l1.5-1.5L15 22l1.5-1.5L18 22l1.5-1.5L21 22V2l-1.5 1.5zM19 19.09H5V4.91h14v14.18z"/>
                                    <path d="M7 7h10v2H7zm0 4h10v2H7zm0 4h7v2H7z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-lg font-heading font-bold text-white">{{ setting('nama_situs_short', 'Bappedalitbang') }}</div>
                                <div class="text-xs text-slate-500">{{ setting('nama_daerah', 'Kabupaten Karo') }}</div>
                            </div>
                        </div>
                        <p class="text-sm text-slate-400 leading-relaxed mb-4">
                            {!! setting('footer_tentang', 'Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo — mewujudkan perencanaan pembangunan daerah yang terpadu, berkualitas, dan berkelanjutan.') !!}
                        </p>
                        @php
                            $socials = [
                                'facebook' => ['label' => 'Facebook'],
                                'twitter' => ['label' => 'Twitter / X'],
                                'instagram' => ['label' => 'Instagram'],
                                'youtube' => ['label' => 'YouTube'],
                                'tiktok' => ['label' => 'TikTok'],
                            ];
                        @endphp
                        @if(collect($socials)->some(fn ($s, $key) => setting($key)))
                        <div class="flex items-center gap-2">
                            @foreach($socials as $key => $s)
                            @if(setting($key))
                            <a href="{{ setting($key) }}" target="_blank" rel="noopener" aria-label="{{ $s['label'] }}" class="w-8 h-8 rounded-lg bg-white/5 border border-slate-700 flex items-center justify-center text-slate-400 hover:text-gold hover:border-gold/50 transition-colors">
                                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><x-social-svg :platform="$key" /></svg>
                            </a>
                            @endif
                            @endforeach
                        </div>
                        @endif
                    </div>

                    {{-- Navigasi --}}
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Navigasi</h3>
                        <ul class="space-y-2.5">
                            @foreach($navItems->filter(fn ($item) => in_array(strtolower($item['label']), ['beranda', 'profil', 'berita', 'pengumuman', 'galeri'])) as $item)
                            <li><a href="{{ $item['href'] }}" class="footer-link">{{ $item['label'] }}</a></li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- Contact --}}
                    <div>
                        <h3 class="text-sm font-bold text-white uppercase tracking-wider mb-4">Kontak</h3>
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                <span class="text-slate-400">{!! setting('footer_alamat', 'Jl. Letjend Djamin Ginting, Kabanjahe, Kabupaten Karo, Sumatera Utara 22115') !!}</span>
                            </li>
                            @if(setting('footer_telepon'))
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                <span class="text-slate-400">{{ setting('footer_telepon') }}</span>
                            </li>
                            @endif
                            <li class="flex items-start gap-2.5">
                                <svg class="w-4 h-4 mt-0.5 shrink-0 text-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="text-slate-400">{{ setting('footer_email', 'bappedalitbang@karokab.go.id') }}</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="border-t border-slate-800 mt-8 pt-6 text-xs text-slate-500">
                    <span>{!! setting('footer_copyright', '&copy; ' . date('Y') . ' Bappedalitbang Kabupaten Karo. All Rights Reserved.') !!}</span>
                </div>
            </div>
        </footer>
    </div>

    <script>
        // ===== Search Toggle =====
        const searchToggle = document.getElementById('searchToggle');
        const searchBar = document.getElementById('searchBar');
        if (searchToggle && searchBar) {
            searchToggle.addEventListener('click', function() {
                const isHidden = searchBar.classList.toggle('hidden');
                this.setAttribute('aria-expanded', String(!isHidden));
                if (!isHidden) searchBar.querySelector('input').focus();
            });
        }

        // ===== Mobile Menu Toggle =====
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        if (menuToggle && mobileMenu) {
            menuToggle.addEventListener('click', function() {
                const isHidden = mobileMenu.classList.toggle('hidden');
                this.setAttribute('aria-expanded', String(!isHidden));
                const bar = this.querySelector('path');
                if (bar) {
                    bar.setAttribute('d', isHidden ? 'M4 6h16M4 12h16M4 18h16' : 'M6 18L18 6M6 6l12 12');
                }
            });
            mobileMenu.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
                mobileMenu.classList.add('hidden');
                menuToggle.setAttribute('aria-expanded', 'false');
            }));
            mobileMenu.querySelectorAll('.mobile-submenu-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const list = this.closest('.mobile-submenu').querySelector('.mobile-submenu-list');
                    const isOpen = this.getAttribute('aria-expanded') === 'true';
                    this.setAttribute('aria-expanded', String(!isOpen));
                    list.classList.toggle('hidden');
                    const arrow = this.querySelector('.mobile-submenu-arrow');
                    if (arrow) arrow.style.transform = isOpen ? '' : 'rotate(180deg)';
                });
            });
        }

        // ===== Reveal on scroll =====
        const revealEls = document.querySelectorAll('.reveal');
        if ('IntersectionObserver' in window && revealEls.length) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('reveal-visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -40px 0px' });
            revealEls.forEach(el => observer.observe(el));
        } else {
            revealEls.forEach(el => el.classList.add('reveal-visible'));
        }

        // ===== Glow spotlight cards (tim pengembang) =====
        const glowCards = document.querySelectorAll('.glow-card');
        if (glowCards.length && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            glowCards.forEach(function (card) {
                if (!card.querySelector('.glow-card-spot')) return;
                let raf = null;
                card.addEventListener('pointermove', function (e) {
                    if (raf) return;
                    raf = requestAnimationFrame(function () {
                        raf = null;
                        const r = card.getBoundingClientRect();
                        card.style.setProperty('--glow-x', (e.clientX - r.left).toFixed(2));
                        card.style.setProperty('--glow-y', (e.clientY - r.top).toFixed(2));
                        card.style.setProperty('--glow-xp', (e.clientX / window.innerWidth).toFixed(3));
                    });
                });
                card.addEventListener('pointerenter', function () {
                    card.classList.add('is-glow');
                });
                card.addEventListener('pointerleave', function () {
                    card.classList.remove('is-glow');
                });
            });
        }

        // ===== Animated counters =====
        const counters = document.querySelectorAll('[data-counter]');
        if ('IntersectionObserver' in window && counters.length) {
            const animate = (el) => {
                const target = parseFloat(el.dataset.counter);
                const decimals = el.dataset.decimals ? parseInt(el.dataset.decimals, 10) : 0;
                const duration = 1800;
                const start = performance.now();
                const step = (now) => {
                    const p = Math.min((now - start) / duration, 1);
                    const eased = 1 - Math.pow(1 - p, 3);
                    el.textContent = (target * eased).toLocaleString('id-ID', {
                        minimumFractionDigits: decimals,
                        maximumFractionDigits: decimals
                    });
                    if (p < 1) requestAnimationFrame(step);
                };
                requestAnimationFrame(step);
            };
            const counterObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animate(entry.target);
                        counterObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.4 });
            counters.forEach(el => counterObserver.observe(el));
        } else {
            counters.forEach(el => el.textContent = parseFloat(el.dataset.counter).toLocaleString('id-ID'));
        }

        // ===== Progress bars =====
        const bars = document.querySelectorAll('[data-progress]');
        if ('IntersectionObserver' in window && bars.length) {
            const barObserver = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.style.width = entry.target.dataset.progress + '%';
                        barObserver.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.3 });
            bars.forEach(el => barObserver.observe(el));
        }

        // ===== Universal button micro-interactions =====
        document.addEventListener('mousedown', (e) => {
            const t = e.target.closest('a, button');
            if (!t || t.disabled || e.button !== 0) return;
            if (t.classList.contains('nav-link') || t.classList.contains('lbx-btn')) return;
            t.classList.add('is-pressing');
            const done = () => t.classList.remove('is-pressing');
            t.addEventListener('mouseup', done, { once: true });
            t.addEventListener('mouseleave', done, { once: true });
        });
    </script>
    @stack('scripts')
</body>
</html>

@extends('layouts.master')

@php
    $sec = sections()->keyBy('type');
    $visiMisi = App\Models\VisiMisi::first();
    $bidangList = App\Models\Bidang::with('profil')->where('enabled', true)->ordered()->get();
    $galeriList = App\Models\Galeri::where('enabled', true)->latest('id')->limit(7)->get();
    $penelitianList = App\Models\Penelitian::published()->latest('id')->limit(4)->get();
    $pengumumanList = App\Models\Pengumuman::published()->latest('tanggal')->limit(5)->get();
    $dokumenList = App\Models\Dokumen::where('enabled', true)->ordered()->limit(6)->get();
    $layananList = App\Models\Layanan::where('enabled', true)->whereNotNull('logo')->orderBy('id')->get();
    $fiturInovasi = App\Models\InovasiFitur::where('enabled', true)->ordered()->get();
@endphp

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Beranda')

@section('hero')
@if($sec->has('slider') || $sec->has('beranda'))
<section id="beranda" class="relative bg-primary overflow-hidden">
    <div class="absolute inset-0">
        @forelse($heroBerita as $i => $hb)
        <div class="hero-slide {{ $i === 0 ? 'active' : '' }} absolute inset-0">
            @if($hb->gambar)
            <img src="{{ media_url($hb->gambar) }}" alt="{{ $hb->judul }}" class="w-full h-full object-cover">
            @endif
            <div class="absolute inset-0 bg-gradient-to-t from-primary/95 via-primary/40 to-primary/25"></div>
        </div>
        @empty
        <img src="{{ asset('uploads/blur-calm-waters-dawn-395198.jpg') }}" alt="" aria-hidden="true" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary/85 to-primary/70"></div>
        @endforelse
        <div class="absolute inset-0 bg-grid opacity-70"></div>
    </div>
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-accent/20 to-transparent"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-gold/10 blur-3xl"></div>

    <div class="container-gov relative py-20 md:py-24">
        <div class="grid max-w-3xl min-h-[420px] md:min-h-[460px] items-end">
            @forelse($heroBerita as $i => $hb)
            <div class="hero-slide-content {{ $i === 0 ? 'active' : '' }}">
                <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mb-5">
                    <span class="badge-gold">{{ $hb->kategori }}</span>
                    <span class="text-xs md:text-sm text-slate-300 flex items-center gap-1.5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ date('d M Y', strtotime($hb->created_at)) }}
                    </span>
                </div>
                <h1 class="text-3xl md:text-4xl lg:text-5xl font-heading font-bold text-white leading-[1.15] text-balance mb-4">
                    {{ $hb->judul }}
                </h1>
                <p class="text-sm md:text-base text-slate-300 leading-relaxed max-w-2xl mb-8 line-clamp-2">
                    {{ \Illuminate\Support\Str::limit(strip_tags($hb->isi), 180) }}
                </p>
                <a href="{{ url('detail/'.$hb->artikel_id) }}" class="btn-gold">
                    Baca Selengkapnya
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @empty
            @endforelse
        </div>
    </div>

    @if($heroBerita->count() > 1)
    <button type="button" id="heroPrev" class="hero-arrow left-4 md:left-6" aria-label="Slide sebelumnya">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </button>
    <button type="button" id="heroNext" class="hero-arrow right-4 md:right-6" aria-label="Slide berikutnya">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </button>
    <div class="relative flex items-center justify-center gap-2 pb-6">
        @foreach($heroBerita as $i => $hb)
        <button type="button" class="hero-dot {{ $i === 0 ? 'active' : '' }}" data-slide="{{ $i }}" aria-label="Slide {{ $i + 1 }}"></button>
        @endforeach
    </div>
    @endif
</section>
@endif
@endsection

@section('content')
{{-- ============ VISI & MISI ============ --}}
@if($sec->has('visi_misi') && $visiMisi)
<section id="visi-misi" class="py-16 md:py-24 bg-white">
    <div class="container-gov">
        <div class="reveal text-center max-w-3xl mx-auto mb-14">
            <span class="eyebrow justify-center">{{ $sec->get('visi_misi')->badge ?? 'Visi & Misi' }}</span>
            <h2 class="section-title">{{ $sec->get('visi_misi')->title ?? 'Arah Pembangunan Kabupaten Karo' }}</h2>
            @if($sec->get('visi_misi')->subtitle)<p class="section-subtitle mx-auto">{{ $sec->get('visi_misi')->subtitle }}</p>@endif
            <div class="gold-rule mx-auto mt-5"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 lg:gap-8">
            {{-- Visi --}}
            <div class="reveal relative bg-primary rounded-2xl p-8 md:p-10 text-white overflow-hidden shadow-card-hover">
                <div class="absolute inset-0 bg-grid opacity-60"></div>
                <div class="absolute -top-16 -right-16 w-48 h-48 rounded-full bg-gold/10 blur-2xl"></div>
                <div class="relative">
                    <div class="w-14 h-14 rounded-xl bg-gold/15 text-gold flex items-center justify-center mb-6">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-heading font-bold mb-2">Visi</h3>
                    <div class="w-12 h-1 bg-gold rounded-full mb-6"></div>
                    <div class="text-slate-300 leading-relaxed text-sm md:text-base prose-invert max-w-none">
                        {!! $visiMisi->visi !!}
                    </div>
                </div>
            </div>

            {{-- Misi --}}
            @if(! empty($visiMisi->misi))
            <div class="reveal bg-surface border border-border rounded-2xl p-8 md:p-10">
                <div class="flex items-center gap-4 mb-6">
                    <div class="w-14 h-14 rounded-xl bg-accent/10 text-accent flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <h3 class="text-2xl md:text-3xl font-heading font-bold text-primary">Misi</h3>
                </div>
                <ul class="space-y-4">
                    @foreach($visiMisi->misi as $i => $misi)
                    <li class="flex gap-3 items-start">
                        <span class="w-7 h-7 shrink-0 rounded-full bg-gold/15 text-gold-dark flex items-center justify-center text-sm font-bold">{{ $i + 1 }}</span>
                        <span class="text-sm md:text-base text-slate-600 leading-relaxed">{{ $misi }}</span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif
        </div>
    </div>
</section>
@endif

{{-- ============ BIDANG ============ --}}
@if($sec->has('bidang') && $bidangList->isNotEmpty())
<section id="bidang" class="py-16 md:py-24 bg-surface">
    <div class="container-gov">
        <div class="reveal text-center max-w-3xl mx-auto mb-14">
            <span class="eyebrow justify-center">{{ $sec->get('bidang')->badge ?? 'Struktur & Tugas' }}</span>
            <h2 class="section-title">{{ $sec->get('bidang')->title ?? 'Bidang Layanan' }}</h2>
            @if($sec->get('bidang')->subtitle)<p class="section-subtitle mx-auto">{{ $sec->get('bidang')->subtitle }}</p>@endif
            <div class="gold-rule mx-auto mt-5"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($bidangList as $bidang)
            <a href="{{ url('profil/bidang/'.$bidang->id) }}" aria-label="Buka halaman detail {{ $bidang->nama }}" class="reveal card group p-6 md:p-7 flex flex-col items-center justify-center text-center hover:-translate-y-1.5 min-h-[180px]">
                <div class="icon-tile">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
                @if($bidang->singkatan)
                <span class="mt-4 text-[11px] font-bold tracking-widest text-gold-dark bg-gold/10 px-2.5 py-1 rounded-full">{{ $bidang->singkatan }}</span>
                @endif
                <h3 class="card-title text-base mt-3">{{ $bidang->nama }}</h3>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ PENGUMUMAN ============ --}}
@if($sec->has('pengumuman') && $pengumumanList->isNotEmpty())
<section id="pengumuman" class="py-16 md:py-24 bg-white">
    <div class="container-gov">
        <div class="reveal text-center max-w-3xl mx-auto mb-14">
            <span class="eyebrow justify-center">{{ $sec->get('pengumuman')->badge ?? 'Informasi Resmi' }}</span>
            <h2 class="section-title">{{ $sec->get('pengumuman')->title ?? 'Pengumuman' }}</h2>
            <div class="gold-rule mx-auto mt-5"></div>
        </div>
        <div class="max-w-3xl mx-auto space-y-3">
            @foreach($pengumumanList as $pg)
            <div class="reveal group flex items-center gap-3 sm:gap-4 rounded-xl border border-border bg-white px-4 py-3 transition-all duration-200 hover:border-accent/50 hover:bg-slate-50 hover:shadow-sm">
                <div class="w-9 h-9 shrink-0 rounded-lg bg-gold/10 text-gold-dark flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                </div>
                <div class="flex-1 min-w-0">
                    <h3 class="text-sm md:text-[15px] font-heading font-bold text-primary leading-snug line-clamp-2 transition-colors duration-200 group-hover:text-accent">{{ $pg->judul }}</h3>
                    @if($pg->tanggal)
                    <p class="mt-0.5 flex items-center gap-1.5 text-[11px] font-medium uppercase tracking-wider text-slate-400">
                        <svg class="w-3 h-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        {{ \Illuminate\Support\Carbon::parse($pg->tanggal)->format('d M Y') }}
                    </p>
                    @endif
                </div>
                <a href="{{ url('pengumuman/'.$pg->id) }}" aria-label="Lihat detail {{ $pg->judul }}" class="shrink-0 inline-flex items-center justify-center gap-1 min-h-[44px] px-2.5 rounded-lg text-xs font-semibold text-accent transition-colors duration-200 hover:bg-accent/10">
                    Detail
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ============ PENELITIAN & PUBLIKASI ============ --}}
@if($sec->has('penelitian') && ($penelitianList->isNotEmpty() || $dokumenList->isNotEmpty()))
<section id="penelitian" class="py-16 md:py-24 bg-primary relative overflow-hidden">
    <div class="absolute inset-0 bg-grid opacity-40"></div>
    <div class="absolute -top-32 -left-32 w-96 h-96 rounded-full bg-accent/10 blur-3xl"></div>

    <div class="container-gov relative">
        <div class="reveal text-center max-w-3xl mx-auto mb-14">
            <span class="eyebrow justify-center !text-gold-soft">{{ $sec->get('penelitian')->badge ?? 'Hasil Karya' }}</span>
            <h2 class="section-title text-white">{{ $sec->get('penelitian')->title ?? 'Penelitian & Publikasi' }}</h2>
            @if($sec->get('penelitian')->subtitle)<p class="text-sm md:text-base text-slate-400 leading-relaxed max-w-2xl mx-auto">{{ $sec->get('penelitian')->subtitle }}</p>@endif
            <div class="gold-rule mx-auto mt-5"></div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                @forelse($penelitianList as $pub)
                <div class="reveal group flex gap-4 p-5 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:border-gold/40 transition-all duration-300">
                    <div class="w-12 h-14 shrink-0 bg-accent/20 text-accent rounded-lg flex flex-col items-center justify-center">
                        <svg class="w-5 h-5 text-gold-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1.5">
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gold-soft">{{ $pub->jenis ?? 'Publikasi' }}</span>
                            @if($pub->tahun)<span class="text-[10px] text-slate-500">{{ $pub->tahun }}</span>@endif
                        </div>
                        <h3 class="text-sm font-semibold text-white leading-snug group-hover:text-gold-soft transition-colors">{{ $pub->judul }}</h3>
                        @if($pub->file || $pub->url)
                        <span class="text-[11px] text-accent inline-flex items-center gap-1 mt-2">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            @if($pub->file)<a href="{{ media_url($pub->file) }}" target="_blank">Unduh</a>@endif
                            @if($pub->url)<a href="{{ $pub->url }}" target="_blank" rel="noopener">Lihat</a>@endif
                        </span>
                        @endif
                    </div>
                </div>
                @empty
                    @foreach($dokumenList as $doc)
                    <div class="reveal group flex gap-4 p-5 bg-white/5 border border-white/10 rounded-xl hover:bg-white/10 hover:border-gold/40 transition-all duration-300">
                        <div class="w-12 h-14 shrink-0 bg-accent/20 text-accent rounded-lg flex flex-col items-center justify-center">
                            <svg class="w-5 h-5 text-gold-soft" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        </div>
                        <div>
                            <span class="text-[10px] font-bold uppercase tracking-wider text-gold-soft">{{ $doc->kategori ?? 'Dokumen' }}</span>
                            <h3 class="text-sm font-semibold text-white leading-snug group-hover:text-gold-soft transition-colors mt-1">{{ $doc->judul }}</h3>
                            <a href="{{ media_url($doc->file) }}" target="_blank" class="text-[11px] text-accent inline-flex items-center gap-1 mt-2">Unduh Dokumen</a>
                        </div>
                    </div>
                    @endforeach
                @endforelse
            </div>

            <div class="reveal bg-white rounded-2xl p-6 md:p-7">
                <h3 class="text-lg font-heading font-bold text-primary mb-5 flex items-center gap-2">
                    <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    {{ $sec->get('penelitian')->subtitle ?: 'Publikasi Lainnya' }}
                </h3>
                @if($dokumenList->isNotEmpty())
                <ul class="space-y-4">
                    @foreach($dokumenList as $doc)
                    <li>
                        <a href="{{ media_url($doc->file) }}" target="_blank" class="block group">
                            <span class="text-xs text-slate-400">{{ $doc->kategori }}</span>
                            <span class="block text-sm font-semibold text-primary group-hover:text-accent transition-colors leading-snug mt-1">{{ $doc->judul }}</span>
                        </a>
                    </li>
                    @endforeach
                </ul>
                @else
                <p class="text-sm text-slate-500 leading-relaxed">Publikasi kajian dan hasil penelitian akan segera hadir. Pantau terus halaman ini untuk informasi terbaru.</p>
                @endif
            </div>
        </div>
    </div>
</section>
@endif

@if($sec->has('program'))
{{-- ============ INOVASI 5LMAN ============ --}}
<section id="inovasi" class="py-16 md:py-24 bg-surface">
    <div class="container-gov">
        <div class="reveal text-center max-w-3xl mx-auto mb-14">
            <span class="eyebrow justify-center">Inovasi</span>
            <h2 class="section-title">5LMAN</h2>
            <p class="section-subtitle mx-auto">Informasi inovasi 5LMAN yang dikembangkan oleh Bappedalitbang Kabupaten Karo</p>
            <div class="gold-rule mx-auto mt-5"></div>
        </div>

@php
    // Kartu fitur Inovasi 5LMAN dikelola dari panel admin
    // (menu Konten → Inovasi 5LMAN / admin/crud/inovasi_fitur).
@endphp

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @forelse($fiturInovasi as $f)
            <div class="reveal card group p-6 md:p-7 flex flex-col h-full hover:-translate-y-1.5">
                <div class="icon-tile mb-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $f->ikon }}"/></svg>
                </div>
                <h3 class="card-title text-lg">{{ $f->judul }}</h3>
                @if($f->deskripsi)
                <p class="text-sm text-slate-500 leading-relaxed mt-3">{{ $f->deskripsi }}</p>
                @endif
            </div>
            @empty
            <p class="text-sm text-slate-500 py-8 col-span-full text-center">Belum ada fitur inovasi yang ditambahkan.</p>
            @endforelse
        </div>
    </div>
</section>
@endif


{{-- ============ GALERI ============ --}}
@if($sec->has('galeri') && $galeriList->isNotEmpty())
<section id="galeri" class="py-16 md:py-24 bg-surface">
    <div class="container-gov">
        <div class="reveal text-center max-w-3xl mx-auto mb-14">
            <span class="eyebrow justify-center">{{ $sec->get('galeri')->badge ?? 'Dokumentasi' }}</span>
            <h2 class="section-title">{{ $sec->get('galeri')->title ?? 'Galeri Kegiatan' }}</h2>
            @if($sec->get('galeri')->subtitle)<p class="section-subtitle mx-auto">{{ $sec->get('galeri')->subtitle }}</p>@endif
            <div class="gold-rule mx-auto mt-5"></div>
        </div>

        <x-bento-gallery :items="$galeriList" />

        <div class="reveal text-center mt-10">
            <a href="{{ url('galeri') }}" class="btn-gold">
                Lihat Semua Galeri
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H8"/></svg>
            </a>
        </div>
    </div>
</section>
@endif

{{-- ============ TAUTAN RESMI ============ --}}
<section id="tautan-resmi" class="py-16 md:py-24 bg-surface">
    <div class="container-gov">
        <div class="reveal text-center max-w-3xl mx-auto mb-12">
            <span class="eyebrow justify-center">Layanan Terkait</span>
            <h2 class="section-title">Tautan Resmi Kabupaten Karo</h2>
            <p class="section-subtitle mx-auto">Layanan dan informasi resmi dari Pemerintah Kabupaten Karo yang dapat Anda akses langsung.</p>
            <div class="gold-rule mx-auto mt-5"></div>
        </div>

        <div class="relative" data-layanan-slider>
            <div class="overflow-hidden">
                <div class="flex items-stretch transition-transform duration-500 ease-out will-change-transform" data-layanan-track>
                    @forelse($layananList as $layanan)
                    <div class="layanan-slide shrink-0 w-1/2 md:w-1/3 lg:w-1/4 xl:w-1/5 px-1.5 md:px-2 flex">
                        <a href="{{ $layanan->url }}" target="_blank" rel="noopener" aria-label="Buka {{ $layanan->nama }}"
                           class="group block w-full h-[110px] md:h-[130px] lg:h-[150px] overflow-hidden rounded-lg bg-white ring-1 ring-slate-200 transition-opacity duration-300 hover:opacity-85 focus:outline-none focus:ring-2 focus:ring-accent">
                            <img src="{{ media_url($layanan->logo) }}" alt="{{ $layanan->nama }}" loading="lazy"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        </a>
                    </div>
                    @empty
                    <p class="text-sm text-slate-500 py-8">Belum ada layanan terkait.</p>
                    @endforelse
                </div>
            </div>

            @if($layananList->count() > 1)
            <button type="button" data-layanan-prev aria-label="Layanan sebelumnya" aria-controls="layanan-slider"
                    class="absolute left-0 top-1/2 -translate-y-1/2 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 shadow-card transition-all hover:text-accent hover:border-accent disabled:opacity-40 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <button type="button" data-layanan-next aria-label="Layanan berikutnya" aria-controls="layanan-slider"
                    class="absolute right-0 top-1/2 -translate-y-1/2 z-10 flex h-10 w-10 items-center justify-center rounded-full bg-white border border-slate-200 text-slate-600 shadow-card transition-all hover:text-accent hover:border-accent disabled:opacity-40 disabled:cursor-not-allowed">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
            </button>
            @endif
        </div>
    </div>
</section>

{{-- ============ KONTAK ============ --}}
@if($sec->has('kontak'))
<section id="kontak" class="py-16 md:py-24 bg-white">
    <div class="container-gov">
        <div class="reveal text-center max-w-3xl mx-auto mb-14">
            <span class="eyebrow justify-center">Hubungi Kami</span>
            <h2 class="section-title">{{ $sec->get('kontak')->title ?? 'Kontak & Layanan Pengaduan' }}</h2>
            @if($sec->get('kontak')->subtitle)<p class="section-subtitle mx-auto">{{ $sec->get('kontak')->subtitle }}</p>@endif
            <div class="gold-rule mx-auto mt-5"></div>
        </div>

        @if(session('success'))
        <div class="reveal max-w-2xl mx-auto mb-8 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-4" role="status">
            <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            {{ session('success') }}
        </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            {{-- Info --}}
            <div class="reveal space-y-5">
                @foreach([
                    ['title' => 'Alamat Kantor', 'desc' => setting('alamat', 'Jl. Letjend Djamin Ginting, Kabanjahe, Kabupaten Karo, Sumatera Utara 22115'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                    ['title' => 'Telepon', 'desc' => setting('telepon', '(0628) 91234'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>'],
                    ['title' => 'Email', 'desc' => setting('email', 'bappedalitbang@karokab.go.id'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                ] as $c)
                <div class="flex gap-4 p-5 bg-surface border border-border rounded-xl hover:shadow-card transition-all duration-300">
                    <div class="w-12 h-12 shrink-0 rounded-xl bg-accent/10 text-accent flex items-center justify-center">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $c['icon'] !!}</svg>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-primary mb-1">{{ $c['title'] }}</h3>
                        <p class="text-sm text-slate-500">{!! $c['desc'] !!}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Form --}}
            <div class="reveal bg-surface border border-border rounded-2xl p-6 md:p-8">
                <h3 class="text-xl font-heading font-bold text-primary mb-6">Saran &amp; Masukan</h3>
                <form method="post" action="{{ url('kontak') }}" class="space-y-4">
                    {{ csrf_field() }}
                    @if($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-lg px-4 py-3">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    @endif
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label for="nama" class="block text-xs font-semibold text-slate-700 mb-1.5">Nama <span class="text-red-500">*</span></label>
                            <input id="nama" type="text" name="nama" value="{{ old('nama') }}" required class="input-field" placeholder="Nama lengkap">
                        </div>
                        <div>
                            <label for="email" class="block text-xs font-semibold text-slate-700 mb-1.5">Email <span class="text-red-500">*</span></label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required class="input-field" placeholder="email@contoh.com">
                        </div>
                    </div>
                    <div>
                        <label for="subjek" class="block text-xs font-semibold text-slate-700 mb-1.5">Subjek <span class="text-red-500">*</span></label>
                        <input id="subjek" type="text" name="subjek" value="{{ old('subjek') }}" required class="input-field" placeholder="Perihal pesan">
                    </div>
                    <div>
                        <label for="pesan" class="block text-xs font-semibold text-slate-700 mb-1.5">Pesan <span class="text-red-500">*</span></label>
                        <textarea id="pesan" name="pesan" rows="5" required class="input-field resize-none" placeholder="Tulis pesan Anda...">{{ old('pesan') }}</textarea>
                    </div>
                    <button type="submit" class="btn-primary w-full">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Kirim Saran &amp; Masukan
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endif

{{-- ============ STATISTIK ============ --}}
<section id="statistik" class="py-16 md:py-24 bg-white">
    <div class="container-gov">
        <div class="reveal text-center max-w-3xl mx-auto mb-14">
            <span class="eyebrow justify-center">Data Kunjungan</span>
            <h2 class="section-title">Statistik</h2>
            <p class="section-subtitle mx-auto">Ringkasan statistik kunjungan website Bappedalitbang Kabupaten Karo.</p>
            <div class="gold-rule mx-auto mt-5"></div>
        </div>

        @php
            $statCards = [
                ['value' => number_format(visits_total()), 'label' => 'Jumlah Pengunjung', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>'],
                ['value' => number_format(visits_today()), 'label' => 'Pengunjung Hari Ini', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>'],
                ['value' => number_format(visits_active()), 'label' => 'Pengunjung Aktif', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M22 12h-4l-3 9L9 3l-3 9H2"/>'],
                ['value' => number_format(visits_total()), 'label' => 'Jumlah Halaman Dikunjungi', 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>'],
            ];
        @endphp
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($statCards as $sc)
            <div class="reveal card p-6 md:p-7 flex flex-col items-center text-center">
                <span class="w-14 h-14 rounded-2xl bg-accent/10 text-accent flex items-center justify-center mb-4">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $sc['icon'] !!}</svg>
                </span>
                <div class="text-3xl md:text-4xl font-heading font-bold text-primary leading-none">{{ $sc['value'] }}</div>
                <div class="mt-2 text-sm font-medium text-slate-500">{{ $sc['label'] }}</div>
            </div>
            @endforeach
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
    // Hero slider crossfade (berita terbaru)
    const slides = document.querySelectorAll('.hero-slide');
    const contentSlides = document.querySelectorAll('.hero-slide-content');
    const dots = document.querySelectorAll('.hero-dot');
    const heroNext = document.getElementById('heroNext');
    const heroPrev = document.getElementById('heroPrev');
    if (slides.length > 1) {
        let current = 0;
        let timer = null;
        const show = (i) => {
            slides.forEach((s, idx) => s.classList.toggle('active', idx === i));
            contentSlides.forEach((s, idx) => s.classList.toggle('active', idx === i));
            dots.forEach((d, idx) => d.classList.toggle('active', idx === i));
            current = i;
        };
        const next = () => show((current + 1) % slides.length);
        const prev = () => show((current - 1 + slides.length) % slides.length);
        const restart = () => { clearInterval(timer); timer = setInterval(next, 6000); };
        dots.forEach((dot) => dot.addEventListener('click', () => { show(parseInt(dot.dataset.slide, 10)); restart(); }));
        heroNext?.addEventListener('click', () => { next(); restart(); });
        heroPrev?.addEventListener('click', () => { prev(); restart(); });
        restart();
    }
</script>
<script>
    // Slider "Layanan Terkait" (gambar/banner) — vanilla, tanpa dependensi.
    (function () {
        const root = document.querySelector('[data-layanan-slider]');
        if (!root) return;

        const track = root.querySelector('[data-layanan-track]');
        const slides = Array.from(track.children).filter(el => el.classList.contains('layanan-slide'));
        const prev = root.querySelector('[data-layanan-prev]');
        const next = root.querySelector('[data-layanan-next]');

        if (slides.length <= 1) return;

        const reduceMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        let index = 0;
        let timer = null;
        let touchX = null;

        // Lebar satu item = track / perView (lebar relatif), sehingga di
        // posisi diam SEMUA kartu tampil penuh — tidak ada yang setengah
        // terpotong di tepi container.
        const step = () => slides[0].getBoundingClientRect().width;
        const perView = () => Math.max(1, Math.round(track.clientWidth / step()));

        const update = () => {
            const pv = perView();
            const max = Math.max(0, slides.length - pv);
            index = Math.min(index, max);
            if (max <= 0) {
                // Semua kartu muat penuh → ratakan di tengah, tanpa geser.
                track.classList.add('justify-center');
                track.style.transform = 'translateX(0px)';
            } else {
                track.classList.remove('justify-center');
                track.style.transform = 'translateX(-' + (index * (100 / pv)) + '%)';
            }
            if (prev) prev.disabled = index <= 0;
            if (next) next.disabled = index >= max;
        };

        const restart = () => {
            clearInterval(timer);
            timer = null;
            if (!reduceMotion) timer = setInterval(() => go(1), 4000);
        };
        const stop = () => { clearInterval(timer); timer = null; };

        function go(dir) {
            const max = Math.max(0, slides.length - perView());
            index = Math.min(max, Math.max(0, index + dir));
            update();
            restart();
        }

        prev && prev.addEventListener('click', () => go(-1));
        next && next.addEventListener('click', () => go(1));
        root.addEventListener('mouseenter', stop);
        root.addEventListener('mouseleave', restart);
        root.addEventListener('focusin', stop);
        root.addEventListener('focusout', restart);

        // Swipe/touch untuk perangkat seluler.
        root.addEventListener('touchstart', (e) => { touchX = e.touches[0].clientX; }, { passive: true });
        root.addEventListener('touchend', (e) => {
            if (touchX === null) return;
            const dx = e.changedTouches[0].clientX - touchX;
            touchX = null;
            if (Math.abs(dx) > 40) go(dx < 0 ? 1 : -1);
        }, { passive: true });

        let resizeT = null;
        window.addEventListener('resize', () => {
            clearTimeout(resizeT);
            resizeT = setTimeout(update, 120);
        });

        update();
        restart();
    })();
</script>
<style>
    .hero-slide { opacity: 0; transition: opacity 1s ease; }
    .hero-slide.active { opacity: 1; }
    .hero-slide-content { grid-area: 1 / 1; opacity: 0; visibility: hidden; transition: opacity .8s ease; }
    .hero-slide-content.active { opacity: 1; visibility: visible; }
    .hero-dot { width: 10px; height: 10px; border-radius: 9999px; background: rgba(255,255,255,0.35); transition: all .3s; }
    .hero-dot.active { background: var(--c-gold, #C9A227); width: 26px; }
    .hero-arrow { position: absolute; top: 50%; transform: translateY(-50%); z-index: 20; width: 44px; height: 44px; display: flex; align-items: center; justify-content: center; border-radius: 9999px; background: rgba(15,23,42,0.45); color: #fff; border: 1px solid rgba(255,255,255,0.2); backdrop-filter: blur(4px); transition: all .3s; }
    .hero-arrow:hover { background: var(--c-gold, #C9A227); border-color: var(--c-gold, #C9A227); color: #0F172A; }
</style>
@endpush

@include('components.lightbox')

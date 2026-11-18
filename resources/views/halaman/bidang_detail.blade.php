@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — ' . $bidang->nama)

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Bidang',
        'pageTitle' => $bidang->nama,
        'pageDescription' => $bidang->singkatan ? 'Bidang dengan singkatan ' . $bidang->singkatan . ' di lingkungan Bappedalitbang Kabupaten Karo.' : null,
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Bidang', 'url' => url('profil/bidang')],
            ['label' => $bidang->nama, 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2">
                    <div class="reveal card p-6 md:p-10">
                        @if($bidang->gambar)
                        <img src="{{ $bidang->gambar_url }}" alt="{{ $bidang->nama }}" class="w-full h-64 md:h-80 object-cover rounded-xl mb-8">
                        @endif
                        <div class="flex items-center gap-3 mb-6">
                            <div class="icon-tile">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            </div>
                            <div>
                                <h2 class="text-2xl font-heading font-bold text-primary leading-snug">{{ $bidang->nama }}</h2>
                                @if($bidang->singkatan)
                                <span class="text-xs font-bold tracking-widest text-gold-dark bg-gold/10 px-2.5 py-1 rounded-full">{{ $bidang->singkatan }}</span>
                                @endif
                            </div>
                        </div>

                        @if($profil && $profil->deskripsi)
                        <div class="prose prose-slate max-w-none">
                            {!! $profil->deskripsi !!}
                        </div>
                        @else
                        <p class="text-sm text-slate-500">Deskripsi bidang ini belum tersedia.</p>
                        @endif

                        @if($profil && ! empty($profil->tupoksi))
                        <h3 class="text-lg font-heading font-bold text-primary mt-8 mb-4">Tugas dan Fungsi</h3>
                        <ul class="space-y-3">
                            @foreach($profil->tupoksi as $poin)
                            <li class="flex items-start gap-3 text-sm text-slate-600 leading-relaxed">
                                <svg class="w-5 h-5 text-gold shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                                {{ $poin }}
                            </li>
                            @endforeach
                        </ul>
                        @endif
                    </div>

                    @if($profil && ! empty($profil->program_kerja))
                    <div class="reveal card p-6 md:p-8 mt-6">
                        <h3 class="text-lg font-heading font-bold text-primary mb-4">Program Kerja</h3>
                        <ul class="space-y-3">
                            @foreach($profil->program_kerja as $item)
                            <li class="flex items-start gap-3 text-sm text-slate-600">
                                <svg class="w-4 h-4 text-gold shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                    @endif

                    @if($profil && ($profil->kontak_nama || $profil->kontak_email || $profil->kontak_telepon || $profil->kontak_lokasi))
                    <div class="reveal card p-6 md:p-8 mt-6">
                        <h3 class="text-lg font-heading font-bold text-primary mb-4">Kontak Bidang</h3>
                        <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
                            @if($profil->kontak_nama)
                            <div>
                                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Penanggung Jawab</dt>
                                <dd class="mt-1 text-slate-700">{{ $profil->kontak_nama }}</dd>
                            </div>
                            @endif
                            @if($profil->kontak_email)
                            <div>
                                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Email</dt>
                                <dd class="mt-1 text-accent"><a href="mailto:{{ $profil->kontak_email }}" class="underline">{{ $profil->kontak_email }}</a></dd>
                            </div>
                            @endif
                            @if($profil->kontak_telepon)
                            <div>
                                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Telepon</dt>
                                <dd class="mt-1 text-slate-700">{{ $profil->kontak_telepon }}</dd>
                            </div>
                            @endif
                            @if($profil->kontak_lokasi)
                            <div>
                                <dt class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Lokasi</dt>
                                <dd class="mt-1 text-slate-700">{{ $profil->kontak_lokasi }}</dd>
                            </div>
                            @endif
                        </dl>
                    </div>
                    @endif
                </div>

                <aside class="lg:col-span-1">
                    <div class="reveal card p-6 lg:sticky lg:top-28">
                        <h3 class="text-sm font-bold text-primary uppercase tracking-wider mb-4">Bidang Lainnya</h3>
                        <ul class="space-y-2">
                            @foreach($lainnya as $unit)
                            <li>
                                <a href="{{ url('profil/bidang/'.$unit->id) }}" class="flex items-center justify-between px-4 py-3 rounded-lg text-sm font-medium {{ $unit->id === $bidang->id ? 'bg-accent text-white' : 'text-slate-600 hover:text-accent hover:bg-slate-50' }} transition-colors">
                                    {{ $unit->nama }}
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
            </div>
        </div>
    </section>

    @if($agenda->isNotEmpty())
    <section class="py-16 md:py-24 bg-surface">
        <div class="container-gov">
            <div class="text-center mb-12">
                <span class="eyebrow justify-center">Kegiatan</span>
                <h2 class="section-title">Agenda Kegiatan</h2>
            </div>
            <div class="space-y-4 max-w-3xl mx-auto">
                @foreach($agenda as $item)
                <article class="reveal card p-5 flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="shrink-0 text-center w-20">
                        <p class="text-2xl font-heading font-bold text-accent">{{ $item->mulai ? $item->mulai->format('d') : '—' }}</p>
                        <p class="text-[11px] font-semibold uppercase tracking-wider text-slate-500">{{ $item->mulai ? $item->mulai->format('M Y') : '' }}</p>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-base font-heading font-bold text-primary leading-snug">{{ $item->judul }}</h3>
                        @if($item->lokasi || $item->mulai)
                        <p class="mt-1 text-xs text-slate-500">
                            @if($item->mulai)
                            {{ $item->mulai->format('H:i') }} WIB{{ $item->selesai ? ' – ' . $item->selesai->format('H:i') . ' WIB' : '' }}
                            @endif
                            @if($item->lokasi) Â· {{ $item->lokasi }}@endif
                        </p>
                        @endif
                        @if($item->isi)
                        <p class="mt-2 text-sm text-slate-600 leading-relaxed">{{ \Illuminate\Support\Str::limit(strip_tags($item->isi), 180) }}</p>
                        @endif
                    </div>
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($inovasi->isNotEmpty())
    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="text-center mb-12">
                <span class="eyebrow justify-center">Karya</span>
                <h2 class="section-title">Inovasi Daerah</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($inovasi as $inv)
                <article class="reveal card p-6 flex flex-col">
                    @if($inv->gambar)
                    <img src="{{ media_url($inv->gambar) }}" alt="{{ $inv->judul }}" class="w-full h-40 object-cover rounded-lg mb-4">
                    @endif
                    <h3 class="text-base font-heading font-bold text-primary leading-snug mb-2">{{ $inv->judul }}</h3>
                    @if($inv->tahun)
                    <span class="self-start text-[11px] font-bold tracking-widest text-gold-dark bg-gold/10 px-2.5 py-1 rounded-full mb-3">{{ $inv->tahun }}</span>
                    @endif
                    <p class="text-sm text-slate-600 leading-relaxed flex-1">{{ \Illuminate\Support\Str::limit(strip_tags($inv->deskripsi), 140) }}</p>
                    @if($inv->file)
                    <a href="{{ media_url($inv->file) }}" target="_blank" class="mt-4 text-sm font-semibold text-accent hover:underline">Unduh Dokumen →</a>
                    @endif
                </article>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @if($dokumen->isNotEmpty())
    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="text-center mb-12">
                <span class="eyebrow justify-center">Unduhan</span>
                <h2 class="section-title">Dokumen Bidang</h2>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 max-w-4xl mx-auto">
                @foreach($dokumen as $doc)
                <a href="{{ media_url($doc->file) }}" target="_blank" class="reveal card p-5 flex items-center gap-4 hover:-translate-y-0.5 transition-transform">
                    <div class="shrink-0 w-11 h-11 rounded-lg bg-accent/10 text-accent flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-semibold text-primary truncate">{{ $doc->judul }}</p>
                        @if($doc->kategori)
                        <p class="text-xs text-slate-500">{{ $doc->kategori }}</p>
                        @endif
                    </div>
                    <svg class="w-5 h-5 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @endsection

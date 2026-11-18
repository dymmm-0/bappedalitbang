@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Struktur Organisasi')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Profil',
        'pageTitle' => 'Struktur Organisasi',
        'pageDescription' => 'Bagan organisasi Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Struktur Organisasi', 'url' => null],
        ]),
    ])

    <div class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="max-w-5xl mx-auto">
                @if($struktur && $struktur->judul)
                <div class="reveal text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-heading font-bold text-primary">{{ $struktur->judul }}</h2>
                    <div class="gold-rule mx-auto mt-4"></div>
                </div>
                @endif

                @if($struktur && $struktur->gambar)
                <figure class="reveal mb-10">
                    <img src="{{ media_url($struktur->gambar) }}" alt="{{ $struktur->judul ?? 'Struktur Organisasi' }}" class="w-full h-auto rounded-2xl border border-border shadow-card-hover">
                </figure>
                @endif

                @if($struktur && $struktur->isi)
                <div class="reveal prose prose-slate max-w-none leading-relaxed">
                    {!! $struktur->isi !!}
                </div>
                @endif

                @if((!$struktur) || (!$struktur->gambar && !$struktur->isi))
                <div class="reveal card p-10 text-center">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 5a2 2 0 012-2h12a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm8 4a2 2 0 100-4 2 2 0 000 4zm0 6a2 2 0 100-4 2 2 0 000 4zm6 0a2 2 0 100-4 2 2 0 000 4zM8 8l4 4m0 0l4-4m-8 6l4-4"/></svg>
                    <h2 class="text-lg font-heading font-bold text-primary mb-2">Bagan belum tersedia</h2>
                    <p class="text-sm text-slate-500">Bagan struktur organisasi akan segera ditambahkan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

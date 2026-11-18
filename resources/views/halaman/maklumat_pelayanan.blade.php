@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Maklumat Pelayanan')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Profil',
        'pageTitle' => 'Maklumat Pelayanan',
        'pageDescription' => 'Komitmen Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo dalam memberikan pelayanan publik.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Maklumat Pelayanan', 'url' => null],
        ]),
    ])

    <div class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="max-w-5xl mx-auto">
                @if($maklumat && $maklumat->judul)
                <div class="reveal text-center mb-10">
                    <h2 class="text-2xl md:text-3xl font-heading font-bold text-primary">{{ $maklumat->judul }}</h2>
                    <div class="gold-rule mx-auto mt-4"></div>
                </div>
                @endif

                @if($maklumat && $maklumat->gambar)
                <figure class="reveal mb-10">
                    <a href="{{ media_url($maklumat->gambar) }}" data-lightbox="{{ media_url($maklumat->gambar) }}" data-type="image" data-caption="{{ $maklumat->judul ?? 'Maklumat Pelayanan' }}" class="block">
                        <img src="{{ media_url($maklumat->gambar) }}" alt="{{ $maklumat->judul ?? 'Maklumat Pelayanan' }}" class="w-full h-auto rounded-2xl border border-border shadow-card-hover cursor-zoom-in" loading="lazy">
                    </a>
                    <figcaption class="mt-3 text-center text-sm text-slate-500">Klik gambar untuk memperbesar</figcaption>
                </figure>
                @endif

                @if($maklumat && $maklumat->isi)
                <div class="reveal prose prose-slate max-w-none leading-relaxed">
                    {!! $maklumat->isi !!}
                </div>
                @endif

                @if((!$maklumat) || (!$maklumat->gambar && !$maklumat->isi))
                <div class="reveal card p-10 text-center">
                    <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    <h2 class="text-lg font-heading font-bold text-primary mb-2">Maklumat belum tersedia</h2>
                    <p class="text-sm text-slate-500">Dokumen maklumat pelayanan akan segera diunggah.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    @include('components.lightbox')
@endpush

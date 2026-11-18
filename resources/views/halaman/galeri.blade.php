@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Galeri')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Dokumentasi',
        'pageTitle' => 'Galeri',
        'pageDescription' => 'Dokumentasi kegiatan dan momen Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Galeri', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-surface">
        <div class="container-gov">
            @if($albumList->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-2 mb-12">
                <a href="{{ url('galeri') }}" class="px-4 py-2 text-xs font-semibold rounded-full border transition-colors {{ ! request('album') ? 'bg-accent text-white border-accent' : 'bg-white text-slate-600 border-border hover:border-accent' }}">
                    Semua
                </a>
                @foreach($albumList as $album)
                <a href="{{ url('galeri') }}?album={{ urlencode($album) }}" class="px-4 py-2 text-xs font-semibold rounded-full border transition-colors {{ request('album') === $album ? 'bg-accent text-white border-accent' : 'bg-white text-slate-600 border-border hover:border-accent' }}">
                    {{ $album }}
                </a>
                @endforeach
            </div>
            @endif

            @if($galeri->isNotEmpty())
            <x-bento-gallery :items="$galeri" />

            <div class="mt-10">
                {{ $galeri->links() }}
            </div>
            @else
            <div class="reveal card p-10 text-center max-w-2xl mx-auto">
                <h2 class="text-lg font-heading font-bold text-primary mb-2">Belum ada foto galeri</h2>
                <p class="text-sm text-slate-500">Dokumentasi kegiatan akan segera ditambahkan.</p>
            </div>
            @endif
        </div>
    </section>

    @if($video->isNotEmpty())
    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <x-bento-gallery :items="$video" eyebrow="Multimedia" title="Galeri Video" description="Video kegiatan dan dokumentasi Bappedalitbang Kabupaten Karo." />
        </div>
    </section>
    @endif
@endsection

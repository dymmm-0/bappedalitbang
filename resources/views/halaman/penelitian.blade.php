@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Penelitian')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Litbang',
        'pageTitle' => 'Penelitian & Kajian',
        'pageDescription' => 'Hasil penelitian, kajian, dan riset yang menjadi dasar perencanaan pembangunan daerah.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Penelitian', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            @if($jenisList->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-2 mb-12">
                <a href="{{ url('penelitian') }}" class="px-4 py-2 text-xs font-semibold rounded-full border transition-colors {{ ! request('jenis') ? 'bg-accent text-white border-accent' : 'bg-white text-slate-600 border-border hover:border-accent' }}">
                    Semua
                </a>
                @foreach($jenisList as $jenis)
                <a href="{{ url('penelitian') }}?jenis={{ urlencode($jenis) }}" class="px-4 py-2 text-xs font-semibold rounded-full border transition-colors {{ request('jenis') === $jenis ? 'bg-accent text-white border-accent' : 'bg-white text-slate-600 border-border hover:border-accent' }}">
                    {{ $jenis }}
                </a>
                @endforeach
            </div>
            @endif

            @if($penelitian->isNotEmpty())
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($penelitian as $pn)
                <a href="{{ url('penelitian/'.$pn->id) }}" class="reveal card overflow-hidden flex flex-col group hover:-translate-y-1.5">
                    <div class="relative h-44 bg-primary/5 overflow-hidden">
                        @if($pn->gambar)
                        <img src="{{ media_url($pn->gambar) }}" alt="{{ $pn->judul }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        @else
                        <div class="w-full h-full flex items-center justify-center text-slate-300">
                            <svg class="w-14 h-14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        @endif
                        <div class="absolute top-3 left-3">
                            <span class="text-[11px] font-bold px-2.5 py-1 rounded-full bg-gold text-primary">{{ $pn->jenis }}</span>
                        </div>
                    </div>
                    <div class="p-6 flex flex-col flex-1">
                        <div class="flex items-center gap-2 text-xs text-slate-400 mb-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ $pn->tahun }}
                        </div>
                        <h2 class="card-title text-base mb-3">{{ $pn->judul }}</h2>
                        @if($pn->ringkasan)
                        <div class="prose prose-slate max-w-none prose-sm flex-1 line-clamp-3">{!! $pn->ringkasan !!}</div>
                        @endif
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent mt-auto pt-5">
                            Baca Ringkasan
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $penelitian->links() }}
            </div>
            @else
            <div class="reveal card p-10 text-center max-w-2xl mx-auto">
                <h2 class="text-lg font-heading font-bold text-primary mb-2">Belum ada penelitian</h2>
                <p class="text-sm text-slate-500">Publikasi penelitian akan segera ditambahkan.</p>
            </div>
            @endif
        </div>
    </section>
@endsection

@extends('layouts.master')

@php use Illuminate\Support\Str; @endphp

@section('title', $penelitian->judul . ' — ' . setting('nama_situs', 'Bappedalitbang Kabupaten Karo'))

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => $penelitian->jenis ?: 'Penelitian',
        'pageTitle' => $penelitian->judul,
        'pageDescription' => $penelitian->tahun ? 'Tahun ' . $penelitian->tahun : null,
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Penelitian', 'url' => url('penelitian')],
            ['label' => Str::limit($penelitian->judul, 30), 'url' => null],
        ]),
    ])

    <div class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2">
                    @if($penelitian->gambar)
                    <figure class="reveal mb-8">
                        <img src="{{ media_url($penelitian->gambar) }}" alt="{{ $penelitian->judul }}" class="w-full h-auto rounded-2xl border border-border shadow-card-hover">
                    </figure>
                    @endif

                    @if($penelitian->ringkasan)
                    <div class="reveal prose prose-slate max-w-none leading-relaxed">
                        {!! $penelitian->ringkasan !!}
                    </div>
                    @endif

                    <div class="mt-10 flex flex-wrap gap-3">
                        @if($penelitian->file)
                        <a href="{{ media_url($penelitian->file) }}" target="_blank" rel="noopener" class="btn-primary">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                            Unduh Dokumen
                        </a>
                        @endif
                        @if($penelitian->url)
                        <a href="{{ $penelitian->url }}" target="_blank" rel="noopener" class="btn-outline">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Lihat Sumber
                        </a>
                        @endif
                    </div>
                </div>

                @if($lainnya->isNotEmpty())
                <aside class="lg:sticky lg:top-24 self-start">
                    <div class="card p-6">
                        <h3 class="text-sm font-bold text-primary uppercase tracking-wider mb-5">Publikasi Lainnya</h3>
                        <ul class="space-y-4">
                            @foreach($lainnya as $l)
                            <li>
                                <a href="{{ url('penelitian/'.$l->id) }}" class="block group">
                                    <span class="block text-sm font-semibold text-slate-700 group-hover:text-accent transition-colors leading-snug">{{ $l->judul }}</span>
                                    <span class="block text-xs text-slate-400 mt-1">{{ $l->jenis }} · {{ $l->tahun }}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </aside>
                @endif
            </div>
        </div>
    </div>
@endsection

@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Bidang')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Profil',
        'pageTitle' => 'Bidang & Tugas',
        'pageDescription' => 'Struktur bidang beserta tugas dan fungsi di lingkungan Bappedalitbang Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Bidang', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            @if($bidangList->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                @foreach($bidangList as $bidang)
                <article class="reveal card p-6 md:p-7 flex flex-col hover:-translate-y-1.5">
                    <div class="flex items-center justify-between mb-5">
                        <div class="icon-tile">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <span class="text-[11px] font-bold tracking-widest text-gold-dark bg-gold/10 px-2.5 py-1 rounded-full">{{ $bidang->singkatan }}</span>
                    </div>
                    <h3 class="card-title text-lg mb-3">{{ $bidang->nama }}</h3>
                    @if($bidang->deskripsi)
                    <div class="prose prose-slate max-w-none prose-sm flex-1 mb-5 line-clamp-4">
                        {!! $bidang->deskripsi !!}
                    </div>
                    @endif
                    @if(! empty($bidang->poin))
                    <ul class="space-y-2">
                        @foreach($bidang->poin as $poin)
                        <li class="flex items-center gap-2 text-xs font-medium text-slate-600">
                            <svg class="w-3.5 h-3.5 text-gold shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                            {{ $poin }}
                        </li>
                        @endforeach
                    </ul>
                    @endif
                    <a href="{{ url('profil/bidang/'.$bidang->id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-accent mt-auto pt-5">
                        Selengkapnya
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </a>
                </article>
                @endforeach
            </div>
            @else
            <div class="reveal card p-10 text-center max-w-2xl mx-auto">
                <h2 class="text-lg font-heading font-bold text-primary mb-2">Belum ada data bidang</h2>
                <p class="text-sm text-slate-500">Data bidang akan segera ditambahkan.</p>
            </div>
            @endif
        </div>
    </section>
@endsection

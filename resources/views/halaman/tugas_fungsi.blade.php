@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Tugas dan Fungsi')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Profil',
        'pageTitle' => 'Tugas dan Fungsi',
        'pageDescription' => 'Tugas pokok dan fungsi Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Tugas dan Fungsi', 'url' => null],
        ]),
    ])

    @php
        $hasContent = $tugasFungsi && (
            trim(strip_tags($tugasFungsi->pembukaan ?? '')) !== ''
            || trim(strip_tags($tugasFungsi->tugas ?? '')) !== ''
            || ! empty((array) ($tugasFungsi->fungsi ?? []))
        );
    @endphp

    @if(! $hasContent)
    <div class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="reveal card p-10 text-center max-w-3xl mx-auto">
                <svg class="w-12 h-12 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                <h2 class="text-lg font-heading font-bold text-primary mb-2">Konten belum tersedia</h2>
                <p class="text-sm text-slate-500">Naskah tugas dan fungsi akan segera ditambahkan.</p>
            </div>
        </div>
    </div>
    @else
    <div class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="max-w-4xl mx-auto space-y-14">

                @if(trim(strip_tags($tugasFungsi->pembukaan ?? '')) !== '')
                <div class="reveal prose prose-slate max-w-none leading-relaxed text-[15px] md:text-base">
                    {!! $tugasFungsi->pembukaan !!}
                </div>
                @endif

                @if(trim(strip_tags($tugasFungsi->tugas ?? '')) !== '')
                <div class="reveal">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-11 h-11 rounded-xl bg-primary text-white flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </span>
                        <div>
                            <h2 class="text-xl md:text-2xl font-heading font-bold text-primary">Tugas Pokok</h2>
                            <div class="gold-rule mt-2"></div>
                        </div>
                    </div>
                    <div class="card p-8 md:p-10 border-l-4 border-l-accent bg-surface">
                        <div class="prose prose-slate max-w-none leading-relaxed text-[15px] md:text-base">
                            {!! $tugasFungsi->tugas !!}
                        </div>
                    </div>
                </div>
                @endif

                @if(! empty((array) ($tugasFungsi->fungsi ?? [])))
                <div class="reveal">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="w-11 h-11 rounded-xl bg-accent text-white flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        </span>
                        <div>
                            <h2 class="text-xl md:text-2xl font-heading font-bold text-primary">Fungsi</h2>
                            <div class="gold-rule mt-2"></div>
                        </div>
                    </div>
                    <ol class="space-y-4">
                        @foreach((array) $tugasFungsi->fungsi as $index => $fungsi)
                        <li class="card p-6 md:p-7 flex items-start gap-4 hover:border-accent/50">
                            <span class="w-10 h-10 rounded-full bg-gold/15 text-gold-dark border border-gold/40 text-sm font-bold flex items-center justify-center shrink-0 mt-0.5">
                                {{ $index + 1 }}
                            </span>
                            <p class="text-slate-600 leading-relaxed text-[15px] md:text-base">{{ $fungsi }}</p>
                        </li>
                        @endforeach
                    </ol>
                </div>
                @endif

            </div>
        </div>
    </div>
    @endif
@endsection
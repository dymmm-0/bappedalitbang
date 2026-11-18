@php use App\Models\Dokumen; @endphp
@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Dokumen Perencanaan')

@php
    $kategoriInfo = [
        'RPJPD' => ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z', 'desc' => 'Arah pembangunan daerah untuk periode jangka panjang (20 tahun).'],
        'RPJMD' => ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'desc' => 'Arah pembangunan daerah untuk periode jangka menengah (5 tahun).'],
        'RKPD' => ['icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4', 'desc' => 'Rencana kerja dan prioritas pembangunan daerah tahunan.'],
        'Dokumen Pendukung & Evaluasi' => ['icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'desc' => 'Dokumen evaluasi, renstra, dan pendukung perencanaan lainnya.'],
    ];
@endphp

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Unduhan',
        'pageTitle' => 'Dokumen Perencanaan',
        'pageDescription' => 'Telusuri dokumen perencanaan berdasarkan kategori dan tahun/periode, atau cari langsung dokumen yang Anda butuhkan.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Dokumen Perencanaan', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <x-dokumen-search :q="request('q')" :tahunList="[]" />

            <div class="grid sm:grid-cols-2 gap-4 max-w-3xl mx-auto">
                @foreach(Dokumen::CATEGORIES as $key => $nama)
                <a href="{{ url('dokumen') }}?kategori={{ urlencode($key) }}" class="reveal card p-5 md:p-6 flex flex-col gap-2 hover:border-accent/40 hover:shadow-card-hover transition-all group">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 shrink-0 rounded-xl bg-accent/10 text-accent flex items-center justify-center" aria-hidden="true">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kategoriInfo[$key]['icon'] }}"/></svg>
                        </div>
                        <h2 class="text-base md:text-lg font-heading font-bold text-primary leading-tight">{{ $key }}</h2>
                    </div>
                    <p class="text-xs md:text-sm text-slate-500 leading-relaxed">{{ $kategoriInfo[$key]['desc'] }}</p>
                    <div class="mt-auto pt-2 flex items-center justify-between">
                        <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">{{ $kategoriCount[$key] ?? 0 }} Dokumen</span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold text-accent">
                            Lihat
                            <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </section>
@endsection

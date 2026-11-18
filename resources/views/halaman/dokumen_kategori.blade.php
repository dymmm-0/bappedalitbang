@php use App\Models\Dokumen; @endphp
@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — ' . $kategori)

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Kategori Dokumen',
        'pageTitle' => $kategori,
        'pageDescription' => Dokumen::CATEGORIES[$kategori] . ' — pilih tahun/periode untuk melihat daftar dokumen.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Dokumen Perencanaan', 'url' => url('dokumen')],
            ['label' => $kategori, 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <x-dokumen-search :kategori="$kategori" :tahunList="[]" />

            @if($tahunList->isNotEmpty())
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 max-w-3xl mx-auto">
                @foreach($tahunList as $item)
                <a href="{{ url('dokumen') }}?kategori={{ urlencode($kategori) }}&tahun={{ urlencode($item->tahun ?? 'lainnya') }}" class="reveal card p-5 flex flex-col items-center gap-1.5 text-center hover:border-accent/40 hover:shadow-card-hover transition-all group">
                    <svg class="w-6 h-6 text-accent mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    <span class="text-lg font-heading font-bold text-primary tabular-nums leading-tight">{{ $item->tahun ?? 'Lainnya' }}</span>
                    <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">{{ $item->jumlah }} Dokumen</span>
                </a>
                @endforeach
            </div>
            @else
            <div class="reveal card p-10 text-center max-w-2xl mx-auto">
                <h2 class="text-lg font-heading font-bold text-primary mb-2">Belum ada dokumen</h2>
                <p class="text-sm text-slate-500">Dokumen untuk kategori ini akan segera tersedia.</p>
            </div>
            @endif

            <div class="mt-10 text-center">
                <a href="{{ url('dokumen') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Semua Kategori
                </a>
            </div>
        </div>
    </section>
@endsection

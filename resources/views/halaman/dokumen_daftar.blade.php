@php use App\Models\Dokumen; @endphp
@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — ' . $kategori . ' ' . $tahun)

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Daftar Dokumen',
        'pageTitle' => $tahun === 'lainnya' ? $kategori : $kategori . ' ' . $tahun,
        'pageDescription' => Dokumen::CATEGORIES[$kategori] . ($tahun === 'lainnya' ? '' : ' — periode ' . $tahun),
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Dokumen Perencanaan', 'url' => url('dokumen')],
            ['label' => $kategori, 'url' => url('dokumen') . '?kategori=' . urlencode($kategori)],
            ['label' => $tahun === 'lainnya' ? 'Lainnya' : $tahun, 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <x-dokumen-search :q="$q" :kategori="$kategori" :tahun="$tahun" :kategoriList="Dokumen::CATEGORIES" :tahunList="$tahunList" />

            @if($dokumen->isNotEmpty())
            <div class="max-w-3xl mx-auto space-y-3">
                @foreach($dokumen as $d)
                <x-dokumen-card :dokumen="$d" />
                @endforeach
            </div>

            <div class="mt-10">
                {{ $dokumen->links() }}
            </div>
            @else
            <div class="reveal card p-10 text-center max-w-2xl mx-auto">
                <h2 class="text-lg font-heading font-bold text-primary mb-2">Belum ada dokumen</h2>
                <p class="text-sm text-slate-500">Dokumen untuk periode ini belum tersedia.</p>
            </div>
            @endif

            <div class="mt-10 text-center">
                <a href="{{ url('dokumen') }}?kategori={{ urlencode($kategori) }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:underline">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Semua Periode {{ $kategori }}
                </a>
            </div>
        </div>
    </section>
@endsection

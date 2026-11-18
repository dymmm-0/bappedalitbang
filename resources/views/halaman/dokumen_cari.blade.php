@php use App\Models\Dokumen; @endphp
@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Pencarian Dokumen')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Pencarian',
        'pageTitle' => 'Pencarian Dokumen',
        'pageDescription' => $dokumen->total() . ' dokumen ditemukan' . ($q !== '' ? ' untuk kata kunci "' . $q . '"' : '') . '.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Dokumen Perencanaan', 'url' => url('dokumen')],
            ['label' => 'Pencarian', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <x-dokumen-search :q="$q" :kategori="$aktifKategori" :tahun="$aktifTahun" :kategoriList="Dokumen::CATEGORIES" :tahunList="$tahunList" />

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
                <h2 class="text-lg font-heading font-bold text-primary mb-2">Tidak ditemukan</h2>
                <p class="text-sm text-slate-500">Tidak ada dokumen yang sesuai dengan kata kunci atau filter. Coba kata kunci lain.</p>
            </div>
            @endif
        </div>
    </section>
@endsection

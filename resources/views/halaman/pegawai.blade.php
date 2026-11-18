@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Pegawai')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Profil',
        'pageTitle' => 'Pegawai',
        'pageDescription' => 'Aparatur sipil negara di lingkungan Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Pegawai', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="max-w-md mx-auto mb-8">
                <form method="get" action="{{ url('profil/pegawai') }}" class="relative" role="search">
                    @if($selected && ! $isKepala)<input type="hidden" name="bidang" value="{{ $selected->id }}">@endif
                    @if($isKepala)<input type="hidden" name="kepala" value="1">@endif
                    <label for="cariPegawai" class="sr-only">Cari pegawai</label>
                    <input id="cariPegawai" type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama atau jabatan..." class="w-full pl-12 pr-4 py-3 bg-surface border border-border rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </form>
            </div>

            @if($bidangList->isNotEmpty())
            <div class="flex flex-wrap justify-center gap-2 mb-12">
                <a href="{{ url('profil/pegawai') }}" class="px-4 py-2 text-xs font-semibold rounded-full border transition-colors {{ ! $selected && ! $isKepala ? 'bg-accent text-white border-accent' : 'bg-white text-slate-600 border-border hover:border-accent' }}">
                    Semua
                </a>
                <a href="{{ url('profil/pegawai') }}?kepala=1" class="px-4 py-2 text-xs font-semibold rounded-full border transition-colors {{ $isKepala ? 'bg-accent text-white border-accent' : 'bg-white text-slate-600 border-border hover:border-accent' }}">
                    KEPALA
                </a>
                @foreach($bidangList as $bidang)
                <a href="{{ url('profil/pegawai') }}?bidang={{ $bidang->id }}" class="px-4 py-2 text-xs font-semibold rounded-full border transition-colors {{ $selected && ! $isKepala && $selected->id == $bidang->id ? 'bg-accent text-white border-accent' : 'bg-white text-slate-600 border-border hover:border-accent' }}">
                    {{ $bidang->singkatan === 'Sekretariat' ? 'Sekretariat / Sekretaris' : $bidang->singkatan }}
                </a>
                @endforeach
                @if(($counts[''] ?? 0) > 0)
                <a href="{{ url('profil/pegawai') }}?bidang=lainnya" class="px-4 py-2 text-xs font-semibold rounded-full border transition-colors {{ $selected && $selected->id === 'lainnya' ? 'bg-accent text-white border-accent' : 'bg-white text-slate-600 border-border hover:border-accent' }}">
                    Lainnya
                </a>
                @endif
            </div>
            @endif

            @php
                $legenda = \App\Models\Pegawai::kategoriLegenda();
            @endphp
            <div class="flex flex-wrap justify-center gap-x-5 gap-y-2 mb-10">
                @foreach($legenda as $k)
                <span class="inline-flex items-center gap-1.5 text-[11px] font-semibold text-slate-500">
                    <span class="w-3 h-3 rounded-full border" style="background: {{ warna_tint($k['color']) }}; border-color: {{ $k['color'] }}" aria-hidden="true"></span>
                    {{ $k['label'] }}
                </span>
                @endforeach
            </div>

            @if($isKepala)
            <p class="text-center text-sm text-slate-500 mb-10">
                Menampilkan pegawai <strong class="text-primary">Kepala Bappedalitbang</strong> ({{ $pegawai->total() }} pegawai)
            </p>
            @elseif($selected)
            <p class="text-center text-sm text-slate-500 mb-10">
                Menampilkan pegawai <strong class="text-primary">{{ $selected->nama }}</strong> ({{ $counts[$selected->id] ?? $pegawai->total() }} pegawai)
            </p>
            @endif

            @if($pegawai->isNotEmpty())
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5 md:gap-6">
                @foreach($pegawai as $pw)
                <div class="reveal h-full">
                    <x-pegawai-card :person="$pw" />
                </div>
                @endforeach
            </div>

            <div class="mt-10">
                {{ $pegawai->links() }}
            </div>
            @else
            <div class="reveal card p-10 text-center max-w-2xl mx-auto">
                <h2 class="text-lg font-heading font-bold text-primary mb-2">Data pegawai tidak ditemukan</h2>
                <p class="text-sm text-slate-500">Belum ada pegawai di kategori ini, atau coba ubah kata kunci pencarian Anda.</p>
            </div>
            @endif
        </div>
    </section>
@endsection

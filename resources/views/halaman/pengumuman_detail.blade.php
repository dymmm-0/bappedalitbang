@extends('layouts.master')

@php use Illuminate\Support\Str; @endphp

@section('title', $pengumuman->judul . ' — ' . setting('nama_situs', 'Bappedalitbang Kabupaten Karo'))

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Pengumuman',
        'pageTitle' => $pengumuman->judul,
        'pageDescription' => $pengumuman->tanggal ? 'Diterbitkan ' . \Illuminate\Support\Carbon::parse($pengumuman->tanggal)->translatedFormat('d F Y') : null,
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Pengumuman', 'url' => url('pengumuman')],
            ['label' => Str::limit($pengumuman->judul, 30), 'url' => null],
        ]),
    ])

    <div class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                <div class="lg:col-span-2">
                    <article class="reveal prose prose-slate max-w-none leading-relaxed">
                        {!! $pengumuman->isi !!}
                    </article>
                    @if($pengumuman->tanggal)
                    <div class="mt-10 border-t border-slate-100 pt-6 space-y-2">
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs text-slate-400">
                            @if($pengumuman->jenis === 'agenda')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-accent/10 text-accent">Agenda</span>
                            @endif
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ \Illuminate\Support\Carbon::parse($pengumuman->tanggal)->translatedFormat('l, d F Y') }}
                            </span>
                            @if($pengumuman->mulai)
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $pengumuman->mulai->format('H:i') }} WIB{{ $pengumuman->selesai ? ' – ' . $pengumuman->selesai->format('H:i') . ' WIB' : '' }}
                            </span>
                            @endif
                            @if($pengumuman->lokasi)
                            <span class="inline-flex items-center gap-1.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                {{ $pengumuman->lokasi }}
                            </span>
                            @endif
                        </div>
                    </div>
                    @endif
                </div>

                @if($lainnya->isNotEmpty())
                <aside class="lg:sticky lg:top-24 self-start">
                    <div class="card p-6">
                        <h3 class="text-sm font-bold text-primary uppercase tracking-wider mb-5">Pengumuman Lainnya</h3>
                        <ul class="space-y-4">
                            @foreach($lainnya as $l)
                            <li>
                                <a href="{{ url('pengumuman/'.$l->id) }}" class="block group">
                                    <span class="block text-sm font-semibold text-slate-700 group-hover:text-accent transition-colors leading-snug">{{ $l->judul }}</span>
                                    @if($l->tanggal)<span class="block text-xs text-slate-400 mt-1">{{ \Illuminate\Support\Carbon::parse($l->tanggal)->translatedFormat('d M Y') }}</span>@endif
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

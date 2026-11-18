@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Pengumuman')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Informasi',
        'pageTitle' => 'Pengumuman',
        'pageDescription' => 'Pengumuman resmi dari Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Pengumuman', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="max-w-3xl mx-auto">
                @if(isset($bulanList))
                {{-- Mode indeks: pilih bulan dulu, mirip halaman Dokumen Perencanaan --}}
                @if($bulanList->isNotEmpty())
                <div class="grid sm:grid-cols-2 gap-4">
                    @foreach($bulanList as $b)
                    @php $label = \Illuminate\Support\Carbon::parse($b->ym.'-01')->translatedFormat('F Y'); @endphp
                    <a href="{{ url('pengumuman') }}?bulan={{ $b->ym }}" class="reveal card p-5 md:p-6 flex flex-col gap-2 hover:border-accent/40 hover:shadow-card-hover transition-all group">
                        <div class="flex items-center gap-3">
                            <div class="w-11 h-11 shrink-0 rounded-xl bg-gold/10 text-gold-dark flex items-center justify-center" aria-hidden="true">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            </div>
                            <h2 class="text-base md:text-lg font-heading font-bold text-primary leading-tight">{{ $label }}</h2>
                        </div>
                        <div class="mt-auto pt-2 flex items-center justify-between">
                            <span class="text-[11px] font-semibold uppercase tracking-wide text-slate-400">{{ $b->jml }} Pengumuman</span>
                            <span class="inline-flex items-center gap-1 text-xs font-semibold text-accent">
                                Lihat
                                <svg class="w-3.5 h-3.5 transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </span>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <div class="reveal card p-10 text-center">
                    <h2 class="text-lg font-heading font-bold text-primary mb-2">Belum ada pengumuman</h2>
                    <p class="text-sm text-slate-500">Pengumuman akan muncul di halaman ini.</p>
                </div>
                @endif
                @else
                {{-- Mode daftar: pengumuman satu bulan --}}
                @php $bulanLabel = \Illuminate\Support\Carbon::parse($bulan.'-01')->translatedFormat('F Y'); @endphp
                <div class="reveal flex items-center justify-between gap-3 mb-6">
                    <h2 class="text-sm font-heading font-bold uppercase tracking-wider text-accent">{{ $bulanLabel }}</h2>
                    <a href="{{ url('pengumuman') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-slate-500 hover:text-accent transition-colors">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Semua Bulan
                    </a>
                </div>

                @if($pengumuman->isNotEmpty())
                <div class="space-y-3">
                    @foreach($pengumuman as $pg)
                    <a href="{{ url('pengumuman/'.$pg->id) }}" class="reveal card p-5 flex flex-col sm:flex-row sm:items-center gap-3 justify-between hover:border-accent/40 hover:shadow-card-hover transition-all">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 shrink-0 rounded-xl bg-gold/10 text-gold-dark flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                            </div>
                            <div>
                                <h3 class="text-base font-heading font-bold text-primary leading-snug">{{ $pg->judul }}</h3>
                                @if($pg->tanggal)<span class="text-xs text-slate-400">{{ \Illuminate\Support\Carbon::parse($pg->tanggal)->translatedFormat('d F Y') }}</span>@endif
                                @if($pg->jenis === 'agenda' && ($pg->mulai || $pg->lokasi))
                                <p class="mt-1 text-xs text-slate-500">
                                    @if($pg->mulai){{ $pg->mulai->format('H:i') }} WIB{{ $pg->selesai ? ' – ' . $pg->selesai->format('H:i') . ' WIB' : '' }}@endif
                                    @if($pg->lokasi) · {{ $pg->lokasi }}@endif
                                </p>
                                @endif
                            </div>
                        </div>
                        @if($pg->jenis === 'agenda')
                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-accent/10 text-accent shrink-0">Agenda</span>
                        @endif
                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent shrink-0">
                            Selengkapnya
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </a>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $pengumuman->links() }}
                </div>
                @else
                <div class="reveal card p-10 text-center">
                    <h3 class="text-lg font-heading font-bold text-primary mb-2">Belum ada pengumuman</h3>
                    <p class="text-sm text-slate-500">Belum ada pengumuman pada bulan {{ $bulanLabel }}.</p>
                </div>
                @endif
                @endif
            </div>
        </div>
    </section>
@endsection

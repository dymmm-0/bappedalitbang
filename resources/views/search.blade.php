@extends('layouts.master')

@section('title', (request('search') ? 'Pencarian: ' . request('search') . ' — ' : '') . setting('nama_situs', 'Bappedalitbang Kabupaten Karo'))

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Informasi Publik',
        'pageTitle' => 'Hasil Pencarian',
        'pageDescription' => $q !== '' ? 'Hasil pencarian untuk "'.$q.'" di seluruh informasi publik Bappedalitbang Kabupaten Karo.' : 'Cari artikel, pengumuman, penelitian, dan dokumen di situs resmi Bappedalitbang Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Pencarian', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="max-w-3xl mx-auto">
                <form method="get" action="{{ url('search') }}" role="search" class="relative mb-10">
                    <label for="pageSearch" class="sr-only">Cari di situs</label>
                    <input id="pageSearch" type="text" name="search" value="{{ $q }}" placeholder="Cari artikel, pengumuman, penelitian, dokumen..." class="w-full pl-12 pr-32 py-4 bg-surface border border-border rounded-xl text-sm focus:ring-2 focus:ring-accent focus:border-accent outline-none transition-all">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 px-5 py-2.5 text-white font-semibold text-sm rounded-lg transition-all duration-200 hover:-translate-y-[calc(50%+2px)] shadow-md hover:shadow-lg" style="background:linear-gradient(135deg,#0369a1,#0e7490)">Cari</button>
                </form>

                @if($q === '')
                <div class="reveal card p-10 text-center">
                    <h2 class="text-lg font-heading font-bold text-primary mb-2">Silakan masukkan kata kunci</h2>
                    <p class="text-sm text-slate-500">Gunakan kolom pencarian di atas untuk menemukan informasi yang Anda butuhkan.</p>
                </div>
                @elseif($total === 0)
                <div class="reveal card p-10 text-center">
                    <h2 class="text-lg font-heading font-bold text-primary mb-2">Tidak ada hasil untuk "{{ $q }}"</h2>
                    <p class="text-sm text-slate-500">Coba gunakan kata kunci lain atau periksa ejaan Anda.</p>
                </div>
                @else
                <p class="text-sm text-slate-500 mb-6"><strong class="text-primary">{{ $total }}</strong> hasil ditemukan untuk "{{ $q }}"</p>

                <div class="flex flex-wrap gap-2 mb-8">
                    @foreach($groupCounts as $tipe => $jumlah)
                    @if($jumlah > 0)
                    <span class="badge">
                        {{ ucfirst($tipe) }}
                        <span class="ml-1 inline-flex items-center justify-center min-w-5 h-5 px-1 text-[10px] font-bold rounded-full bg-accent text-white">{{ $jumlah }}</span>
                    </span>
                    @endif
                    @endforeach
                </div>

                <div class="space-y-4">
                    @foreach($results as $r)
                    <a href="{{ $r['url'] }}" target="{{ $r['tipe'] === 'dokumen' ? '_blank' : '_self' }}" class="reveal card p-5 flex items-start gap-4 hover:border-accent/40 transition-all">
                        @if($r['gambar'])
                        <img src="{{ media_url($r['gambar']) }}" alt="" class="hidden sm:block w-24 h-20 object-cover rounded-lg shrink-0 border border-border">
                        @else
                        <div class="hidden sm:flex w-24 h-20 rounded-lg bg-accent/10 text-accent items-center justify-center shrink-0 border border-border">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-gold-dark bg-gold/10 px-2 py-0.5 rounded-full">{{ $r['tipe'] }}</span>
                                @if($r['meta'])<span class="text-xs text-slate-400 truncate">{{ $r['meta'] }}</span>@endif
                            </div>
                            <h3 class="text-sm md:text-base font-heading font-bold text-primary leading-snug group-hover:text-accent">{{ $r['judul'] }}</h3>
                            @if($r['konten'])
                            <p class="text-sm text-slate-500 leading-relaxed mt-1 line-clamp-2">{!! nl2br(e(\Illuminate\Support\Str::limit($r['konten'], 180))) !!}</p>
                            @endif
                        </div>
                    </a>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $results->links() }}
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection

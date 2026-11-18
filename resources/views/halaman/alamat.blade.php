@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — Alamat')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Profil',
        'pageTitle' => 'Alamat Bappedalitbang',
        'pageDescription' => 'Lokasi dan kontak kantor Badan Perencanaan Pembangunan Daerah, Penelitian dan Pengembangan Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'Alamat Bappedalitbang', 'url' => null],
        ]),
    ])

    <div class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-start">
                {{-- Info --}}
                <div class="reveal space-y-5">
                    @php
                        $alamat = setting('alamat', 'Jl. Letjend Djamin Ginting, Kabanjahe, Kabupaten Karo, Sumatera Utara 22115');
                        $alamatMap = setting('alamat_map', '');
                        $mapQ = $alamatMap ? trim($alamatMap) : $alamat;
                        $info = [
                            ['title' => 'Alamat Kantor', 'desc' => $alamat, 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>'],
                            ['title' => 'Telepon', 'desc' => setting('telepon', '(0628) 91234'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>'],
                            ['title' => 'Email', 'desc' => setting('email', 'bappedalitbang@karokab.go.id'), 'icon' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>'],
                        ];
                    @endphp
                    @foreach($info as $c)
                    <div class="flex gap-4 p-5 bg-surface border border-border rounded-xl hover:shadow-card transition-all duration-300">
                        <div class="w-12 h-12 shrink-0 rounded-xl bg-accent/10 text-accent flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $c['icon'] !!}</svg>
                        </div>
                        <div>
                            <h3 class="text-sm font-bold text-primary mb-1">{{ $c['title'] }}</h3>
                            <p class="text-sm text-slate-500">{!! $c['desc'] !!}</p>
                        </div>
                    </div>
                    @endforeach

                    <a href="https://www.google.com/maps?q={{ urlencode($mapQ) }}" target="_blank" rel="noopener"
                       class="btn-primary mt-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        Buka di Google Maps
                    </a>
                </div>

                {{-- Peta --}}
                <div class="reveal">
                    <div class="rounded-2xl overflow-hidden border border-border shadow-card">
                        <iframe
                            src="https://www.google.com/maps?q={{ urlencode($mapQ) }}&output=embed"
                            width="100%" height="420" style="border:0;display:block"
                            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            title="Peta lokasi {{ setting('nama_situs', 'Bappedalitbang Kabupaten Karo') }}"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

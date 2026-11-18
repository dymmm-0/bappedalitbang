@props(['person' => null])

@php
    $kat = $person->kategori;
    $bg = warna_tint($kat['color']);
@endphp

<article class="h-full flex flex-col rounded-2xl overflow-hidden shadow-sm hover:shadow-md hover:-translate-y-0.5 transition-all duration-300" style="background: {{ $bg }}">
    @if($person->foto)
        <img src="{{ $person->foto_url }}" alt="Foto {{ $person->nama }}" loading="lazy" class="w-full aspect-[4/3] object-cover object-top">
    @else
        <div class="w-full aspect-[4/3] flex items-center justify-center bg-white/50" aria-hidden="true">
            <svg class="w-16 h-16 text-slate-400/70" fill="currentColor" viewBox="0 0 24 24">
                <path d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
    @endif

    <div class="flex-1 flex flex-col p-4 md:p-5">
        <p class="text-[11px] md:text-xs font-semibold uppercase tracking-wide text-slate-600 leading-snug line-clamp-2">{{ $person->jabatan }}</p>
        <h3 class="mt-1.5 text-base md:text-lg font-bold text-primary leading-snug line-clamp-3">{{ $person->nama }}</h3>
        @if($person->nip)
            <p class="mt-auto pt-2 text-xs text-slate-500 tabular-nums">NIP. {{ $person->nip }}</p>
        @endif
    </div>
</article>

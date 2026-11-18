@props([
    'person' => null,
    'foto' => null,
    'nama' => '',
    'jabatan' => null,
    'nip' => null,
    'bagian' => null,
    'bio' => null,
    'link' => null,
    'glow' => false,
])

@php
    $photo = $foto ?: ($person->foto ?? null);
    $name = $nama ?: ($person->nama ?? '');
    $position = $jabatan ?: ($person->jabatan ?? null);
    $nipValue = $nip ?: ($person->nip ?? null);
    $bagianValue = $bagian ?: ($person->bagian ?? null);
    $bioValue = $bio ?: ($person->bio ?? null);
    if ($bioValue) {
        $bioValue = \Illuminate\Support\Str::limit(trim(strip_tags($bioValue)), 140);
    }
    $cardLink = $link ?: ($person->link ?? null);
    $platformLabels = [
        'instagram' => 'Instagram',
        'facebook' => 'Facebook',
        'twitter' => 'Twitter / X',
        'youtube' => 'YouTube',
        'tiktok' => 'TikTok',
    ];
    $sosmedList = [];
    foreach ($platformLabels as $platform => $label) {
        $url = trim((string) ($person->{$platform} ?? ''));
        if ($url) {
            $sosmedList[] = ['platform' => $platform, 'url' => $url, 'label' => $label];
        }
    }

    $isGlow = !empty($glow);
    $glowBases = ['blue' => 220, 'purple' => 280, 'green' => 140, 'red' => 0, 'orange' => 30];
    $glowColor = is_string($glow) && array_key_exists($glow, $glowBases) ? $glow : 'blue';
@endphp

@if($name)
    @if($isGlow)
        <div class="glow-card glow-base-{{ $glowColor }}">
            <article class="profile-card">
    @else
        <article class="profile-card">
    @endif
        @if($photo)
            <img src="{{ media_url($photo) }}" alt="{{ $name }}" loading="lazy" class="profile-card-photo">
        @else
            <div class="profile-card-photo flex items-center justify-center bg-gradient-to-br from-primary via-secondary to-accent">
                <svg class="w-20 h-20 text-white/70" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        @endif

        @if($isGlow)
            <span class="glow-card-spot" aria-hidden="true"></span>
        @endif

        <div class="profile-card-overlay" aria-hidden="true"></div>

        <div class="profile-card-body">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0">
                    <h3 class="text-base md:text-lg font-heading font-bold text-white leading-snug">{{ $name }}</h3>
                    @if($position)
                        <p class="text-xs md:text-sm font-semibold text-gold mt-1">{{ $position }}</p>
                    @endif
                </div>

                @if($cardLink)
                    <a href="{{ $cardLink }}"
                       aria-label="Lihat profil {{ $name }}"
                       class="shrink-0 w-9 h-9 rounded-full bg-white/15 border border-white/25 flex items-center justify-center backdrop-blur-sm transition-all duration-300 hover:bg-gold hover:text-primary">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endif
            </div>

            @if($bagianValue || $nipValue || $bioValue)
                <div class="mt-2">
                    @if($bagianValue)
                        <p class="text-[11px] text-slate-300 leading-snug">{{ $bagianValue }}</p>
                    @endif

                    @if($nipValue)
                        <span class="inline-flex mt-2 px-2.5 py-1 text-[11px] font-medium bg-white/10 border border-white/15 rounded-full backdrop-blur-sm tabular-nums">
                            NIP. {{ $nipValue }}
                        </span>
                    @endif

                    @if($bioValue)
                        <p class="text-xs text-slate-300 mt-2 leading-relaxed line-clamp-2">{{ $bioValue }}</p>
                    @endif
                </div>
            @endif

            @if(! empty($sosmedList))
                <div class="flex items-center gap-2 mt-3">
                    @foreach($sosmedList as $s)
                    <a href="{{ $s['url'] }}" target="_blank" rel="noopener" aria-label="{{ $s['label'] }} {{ $name }}" title="{{ $s['label'] }} {{ $name }}"
                       class="w-9 h-9 rounded-full bg-white/15 border border-white/25 flex items-center justify-center backdrop-blur-sm transition-all duration-300 hover:bg-gold hover:border-gold hover:text-primary">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><x-social-svg :platform="$s['platform']" /></svg>
                    </a>
                    @endforeach
                </div>
            @endif
        </div>
        </article>
    @if($isGlow)
        </div>
    @endif
@endif

@php
    $pageEyebrow = $pageEyebrow ?? 'Informasi';
    $pageDescription = $pageDescription ?? null;
    $pageBreadcrumb = $pageBreadcrumb ?? collect();
@endphp

<section class="relative bg-primary overflow-hidden">
    <div class="absolute inset-0">
        <img src="{{ asset('uploads/blur-calm-waters-dawn-395198.jpg') }}" alt="" aria-hidden="true" class="w-full h-full object-cover opacity-30">
        <div class="absolute inset-0 bg-gradient-to-br from-primary via-primary/85 to-primary/70"></div>
    </div>
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-accent/20 to-transparent"></div>
    <div class="absolute -bottom-24 -right-24 w-96 h-96 rounded-full bg-gold/10 blur-3xl"></div>

    <div class="container-gov relative py-14 md:py-20">
        @if($pageBreadcrumb->isNotEmpty())
        <nav aria-label="Breadcrumb" class="mb-4">
            <ol class="flex flex-wrap items-center gap-1.5 text-xs text-slate-400">
                @foreach($pageBreadcrumb as $crumb)
                @if($crumb['url'])
                <li class="flex items-center gap-1.5">
                    <a href="{{ $crumb['url'] }}" class="hover:text-gold transition-colors">{{ $crumb['label'] }}</a>
                    <svg class="w-3 h-3 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                </li>
                @else
                <li aria-current="page" class="text-gold font-medium">{{ $crumb['label'] }}</li>
                @endif
                @endforeach
            </ol>
        </nav>
        @endif
        <div class="hero-fade flex items-center gap-2 text-gold text-sm font-semibold tracking-wide mb-3">
            <span class="w-10 h-0.5 bg-gold rounded-full"></span>
            <span>{{ $pageEyebrow }}</span>
        </div>
        <h1 class="hero-rise hero-rise-delay-1 text-3xl md:text-4xl lg:text-5xl font-heading font-bold text-white leading-tight text-balance">
            {{ $pageTitle }}
        </h1>
        @if($pageDescription)
        <p class="hero-rise hero-rise-delay-2 mt-4 text-slate-300 max-w-2xl text-sm md:text-base leading-relaxed">{{ $pageDescription }}</p>
        @endif
    </div>
</section>

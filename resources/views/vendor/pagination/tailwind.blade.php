{{-- Pagination klasik, dipakai otomatis oleh semua ->links() (override pagination::tailwind) --}}
@if ($paginator->hasPages())
    <style>
        .cpag{display:flex;flex-wrap:wrap;justify-content:center;align-items:center;gap:.5rem;margin:2.5rem 0 1rem;font-family:Lato,sans-serif;font-size:.875rem}
        .cpag-btn{display:inline-flex;align-items:center;justify-content:center;min-width:2.5rem;height:2.5rem;padding:0 .875rem;border:1px solid rgb(var(--c-border,226 232 240));border-radius:.375rem;background:#fff;color:rgb(var(--c-secondary,51 65 85));line-height:1;text-decoration:none;transition:background-color .15s ease,border-color .15s ease,color .15s ease}
        a.cpag-btn:hover{background:rgb(var(--c-surface,248 250 252));border-color:#94a3b8;color:rgb(var(--c-primary,15 23 42))}
        .cpag-active{background:rgb(var(--c-accent,3 105 161));border-color:rgb(var(--c-accent,3 105 161));color:#fff;font-weight:700}
        .cpag-disabled{opacity:.45;cursor:not-allowed}
        .cpag-dots{display:inline-flex;align-items:center;height:2.5rem;padding:0 .25rem;color:#64748b;user-select:none}
    </style>

    <nav role="navigation" aria-label="Navigasi halaman" class="cpag">
        @if ($paginator->onFirstPage())
            <span class="cpag-btn cpag-disabled" aria-disabled="true">&lsaquo; Sebelumnya</span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="cpag-btn">&lsaquo; Sebelumnya</a>
        @endif

        @foreach ($elements as $element)
            @if (is_string($element))
                <span class="cpag-dots">{{ $element }}</span>
            @endif

            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="cpag-btn cpag-active" aria-current="page">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="cpag-btn">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="cpag-btn">Berikutnya &rsaquo;</a>
        @else
            <span class="cpag-btn cpag-disabled" aria-disabled="true">Berikutnya &rsaquo;</span>
        @endif
    </nav>
@endif

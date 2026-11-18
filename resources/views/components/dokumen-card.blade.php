@props(['dokumen'])

@php
    $ext = $dokumen->file ? strtoupper(pathinfo($dokumen->file, PATHINFO_EXTENSION)) : 'FILE';
    $path = $dokumen->file ? public_path('uploads/' . $dokumen->file) : null;
    $bytes = ($path && is_file($path)) ? \Illuminate\Support\Facades\File::size($path) : null;
    $ukuran = $bytes ? ($bytes >= 1048576 ? number_format($bytes / 1048576, 1, ',', '.') . ' MB' : number_format(max(1, (int) round($bytes / 1024)), 0, ',', '.') . ' KB') : null;
@endphp

<article class="reveal card p-4 md:p-5 flex flex-col gap-3 hover:border-accent/40 hover:shadow-card-hover transition-all">
    <div class="flex items-start gap-4">
        <div class="w-12 h-14 shrink-0 rounded-lg bg-accent/10 text-accent relative flex items-center justify-center" aria-hidden="true">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            <span class="absolute -bottom-1.5 left-1/2 -translate-x-1/2 px-1.5 py-px text-[9px] font-bold rounded bg-accent text-white">{{ $ext }}</span>
        </div>
        <div class="flex-1 min-w-0">
            <h3 class="text-sm md:text-base font-heading font-bold text-primary leading-snug">{{ $dokumen->judul }}</h3>
            <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                @if($dokumen->kategori)
                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-primary/5 text-secondary border border-border">{{ $dokumen->kategori }}</span>
                @endif
                @if($dokumen->tahun)
                <span class="px-2 py-0.5 text-[10px] font-semibold rounded-full bg-accent/10 text-accent border border-accent/20 tabular-nums">{{ $dokumen->tahun }}</span>
                @endif
            </div>
        </div>
    </div>

    @if($dokumen->deskripsi)
    <p class="text-xs md:text-sm text-slate-500 leading-relaxed">{{ $dokumen->deskripsi }}</p>
    @endif

    <div class="mt-auto pt-1 flex items-center justify-between gap-3 border-t border-border/60">
        <span class="text-[11px] text-slate-400 tabular-nums">
            {{ $ext }}@if($ukuran) &middot; {{ $ukuran }}@endif
        </span>
        <a href="{{ media_url($dokumen->file) }}" download class="inline-flex items-center gap-1.5 px-3.5 py-2 text-xs font-semibold rounded-full bg-accent text-white hover:bg-accent/90 transition-colors shrink-0">
            Download
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
        </a>
    </div>
</article>

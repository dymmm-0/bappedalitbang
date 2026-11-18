@props(['q' => null, 'kategori' => null, 'tahun' => null, 'kategoriList' => [], 'tahunList' => []])

<form action="{{ url('dokumen') }}" method="GET" role="search" class="max-w-3xl mx-auto flex flex-col md:flex-row gap-2 mb-10">
    <div class="relative flex-1">
        <svg class="w-4 h-4 text-slate-400 absolute left-3.5 top-1/2 -translate-y-1/2 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        <input type="search" name="q" value="{{ $q }}" placeholder="Cari dokumen..." aria-label="Kata kunci dokumen" class="w-full rounded-lg border border-border bg-white pl-10 pr-4 py-2.5 text-sm text-primary placeholder:text-slate-400 focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20 transition-colors">
    </div>
    <select name="kategori" aria-label="Filter kategori" class="rounded-lg border border-border bg-white px-3 py-2.5 text-sm text-secondary focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20 transition-colors">
        <option value="">Semua Kategori</option>
        @foreach($kategoriList as $key => $nama)
        <option value="{{ $key }}" @selected($kategori === $key)>{{ $key }}</option>
        @endforeach
    </select>
    <select name="tahun" aria-label="Filter tahun" class="rounded-lg border border-border bg-white px-3 py-2.5 text-sm text-secondary focus:border-accent focus:outline-none focus:ring-2 focus:ring-accent/20 transition-colors">
        <option value="">Semua Tahun</option>
        @foreach($tahunList as $t)
        <option value="{{ $t }}" @selected($tahun === $t)>{{ $t }}</option>
        @endforeach
    </select>
    <button type="submit" class="inline-flex items-center justify-center gap-1.5 px-5 py-2.5 text-sm font-semibold rounded-lg bg-accent text-white hover:bg-accent/90 transition-colors shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        Cari
    </button>
</form>

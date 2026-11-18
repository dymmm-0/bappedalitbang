@extends('layouts.master')

@section('content')
<section class="py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-10">
            {{-- Article List --}}
            <div class="lg:col-span-2">
                @php
                $kategoriName = $kategoriName ?? (count($data) > 0 ? $data[0]->kategori : 'Kategori');
                $total = method_exists($data, 'total') ? $data->total() : count($data);
                $items = method_exists($data, 'items') ? $data->items() : $data;
                @endphp
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 bg-accent/10 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    </div>
                    <div>
                        <h1 class="text-2xl md:text-3xl font-heading font-bold text-primary">{{ $kategoriName }}</h1>
                        <p class="text-sm text-slate-500">{{ $total }} artikel</p>
                    </div>
                </div>

                @if($total > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-2 gap-6">
                    @foreach($items as $at)
                    <article class="card group flex flex-col hover:-translate-y-1.5">
                        <div class="relative h-52 overflow-hidden">
                            <img src="{{ asset('uploads/'.$at->gambar) }}" alt="{{ $at->judul }}" loading="lazy" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            <span class="absolute top-3 left-3 badge-gold !bg-white/95">{{ $at->kategori }}</span>
                        </div>
                        <div class="p-5 md:p-6 flex flex-col flex-1">
                            <span class="text-xs text-slate-400 flex items-center gap-1.5 mb-2">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                {{ date('d M Y', strtotime($at->created_at)) }}
                            </span>
                            <h3 class="card-title text-lg mb-3 group-hover:text-accent transition-colors">
                                <a href="{{ url('detail/'.$at->artikel_id) }}">{{ $at->judul }}</a>
                            </h3>
                            <p class="card-desc text-sm text-slate-500 leading-relaxed mb-5 flex-1">{{ \Illuminate\Support\Str::limit(strip_tags($at->isi), 260) }}</p>
                            <a href="{{ url('detail/'.$at->artikel_id) }}" class="inline-flex items-center gap-1.5 text-sm font-semibold text-accent group-hover:gap-3 transition-all">
                                Baca Selengkapnya
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                            </a>
                        </div>
                    </article>
                    @endforeach
                </div>
                @else
                <div class="text-center py-16">
                    <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/></svg>
                    </div>
                    <h3 class="text-lg font-heading font-bold text-primary mb-2">Belum Ada Artikel</h3>
                    <p class="text-sm text-slate-500">Belum ada artikel dalam kategori ini.</p>
                </div>
                @endif

                @if(method_exists($data, 'hasPages') && $data->hasPages())
                <div class="reveal mt-12 flex justify-center">
                    {{ $data->links() }}
                </div>
                @endif
            </div>

            {{-- Sidebar --}}
            <aside class="mt-10 lg:mt-0 space-y-8">
                <div class="card p-5 md:p-6">
                    <h3 class="text-lg font-heading font-bold text-primary mb-4">Kategori</h3>
                    <div class="space-y-1">
                        @php $kategoris = \DB::table('kategori')->get(); @endphp
                        @foreach($kategoris as $kt)
                        @php $total = \DB::table('artikel')->where('id_kategori', $kt->id)->count(); @endphp
                        <a href="{{ url('artikel/kategori/'.$kt->id) }}" class="flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-50 transition-colors group">
                            <span class="text-sm font-medium text-slate-700 group-hover:text-accent transition-colors">{{ $kt->nama }}</span>
                            <span class="text-xs bg-slate-100 text-slate-500 px-2 py-0.5 rounded-full group-hover:bg-accent/10 group-hover:text-accent transition-colors">{{ $total }}</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection
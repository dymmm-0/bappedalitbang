@extends('layouts.master')

@section('content')
<section class="py-8 md:py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="lg:grid lg:grid-cols-3 lg:gap-10">
            {{-- Main Article --}}
            <div class="lg:col-span-2">
                <article class="card p-0">
                    <div class="relative h-64 md:h-96 overflow-hidden">
                        <img src="{{ asset('uploads/'.$artikel->gambar) }}" alt="{{ $artikel->judul }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
                    </div>
                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-4">
                            <span class="badge">{{ $artikel->nama ?? 'Admin' }}</span>
                            <span class="text-xs text-slate-400">{{ date('d M Y', strtotime($artikel->created_at)) }}</span>
                        </div>
                        <h1 class="text-2xl md:text-3xl lg:text-4xl font-heading font-bold text-primary mb-6 leading-tight">{{ $artikel->judul }}</h1>
                        <div class="prose prose-slate max-w-none text-sm md:text-base leading-relaxed">
                            {!! $artikel->isi !!}
                        </div>

                        {{-- Share Buttons --}}
                        <div class="flex items-center gap-2 mt-8 pt-6 border-t border-border">
                            <span class="text-xs font-medium text-slate-500 uppercase tracking-wider">Bagikan:</span>
                            <button class="p-2 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-all" onclick="window.open('https://facebook.com/sharer/sharer.php?u='+encodeURIComponent(window.location.href))">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.77 7.46H14.5v-1.9c0-.9.6-1.1 1-1.1h3V.5h-4.33C10.24.5 9.35 3.24 9.35 5.47v1.99H6.77v3.7h2.58v11.08h3.15V11.17h3.12l.52-3.71z"/></svg>
                            </button>
                            <button class="p-2 text-slate-400 hover:text-sky-500 hover:bg-sky-50 rounded-lg transition-all" onclick="window.open('https://twitter.com/intent/tweet?text='+encodeURIComponent(document.title)+'&url='+encodeURIComponent(window.location.href))">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.44 4.83c-.8.37-1.5.38-2.22.02.93-.56.98-.96 1.32-2.02-.88.52-1.86.9-2.9 1.1-.82-.88-2-1.43-3.3-1.43-2.5 0-4.55 2.04-4.55 4.54 0 .36.03.7.1 1.04-3.77-.2-7.12-2-9.36-4.75-.4.67-.6 1.45-.6 2.3 0 1.56.8 2.95 2.03 3.77-.74-.03-1.44-.23-2.05-.57v.06c0 2.2 1.56 4.03 3.64 4.44-.67.2-1.37.2-2.06.08.58 1.8 2.26 3.12 4.25 3.16C5.78 18.1 3.37 18.74 1 18.46c2 1.3 4.4 2.04 6.97 2.04 8.35 0 12.92-6.92 12.92-12.93 0-.2 0-.4-.02-.6.9-.63 1.96-1.22 2.56-2.14z"/></svg>
                            </button>
                            <button class="p-2 text-slate-400 hover:text-green-600 hover:bg-green-50 rounded-lg transition-all" onclick="window.open('https://wa.me/?text='+encodeURIComponent(document.title)+'%20'+encodeURIComponent(window.location.href))">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </button>
                        </div>
                    </div>
                </article>

                @php
                    $ytEmbed = $video ? youtube_embed_url($video->url) : null;
                @endphp
                @if($video && ($ytEmbed || $video->url))
                <section class="mt-8 card p-5 md:p-6">
                    <h3 class="text-xl font-heading font-bold text-primary mb-4">Video Terkait</h3>
                    @if($ytEmbed)
                    <div class="relative w-full overflow-hidden rounded-xl border border-border bg-black" style="aspect-ratio: 16 / 9; overflow: hidden;">
                        <iframe src="{{ $ytEmbed }}" style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" title="{{ $video->judul ?? 'Video Terkait' }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                    </div>
                    @else
                    <a href="{{ $video->url }}" target="_blank" rel="noopener" class="group block">
                        <div class="relative w-full overflow-hidden rounded-xl border border-border bg-slate-100" style="aspect-ratio: 16 / 9; overflow: hidden;">
                            @if($video->thumbnail)
                            <img src="{{ media_url($video->thumbnail) }}" alt="{{ $video->judul ?? 'Video Terkait' }}" style="width:100%; height:100%; object-fit:cover;" class="group-hover:scale-105 transition-transform duration-300">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-sm text-slate-400">Buka video di media sosial</div>
                            @endif
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors flex items-center justify-center">
                                <span class="px-4 py-2 bg-white/90 rounded-full text-sm font-medium text-primary shadow">Tonton Video</span>
                            </div>
                        </div>
                    </a>
                    @endif
                </section>
                @endif

                {{-- Comments Section --}}
                <div class="mt-8 card p-5 md:p-6">
                    <h3 class="text-xl font-heading font-bold text-primary mb-6">
                        Komentar
                        <span class="text-sm font-normal text-slate-400 ml-2">({{ $total_komentar }})</span>
                    </h3>

                    @if(count($komentar) > 0)
                    <div class="space-y-6">
                        @foreach($komentar as $km)
                        <div class="flex gap-4 pb-6 border-b border-border last:border-0 last:pb-0">
                            <div class="w-10 h-10 rounded-full bg-accent/10 flex items-center justify-center shrink-0">
                                <span class="text-sm font-bold text-accent">{{ strtoupper(substr($km->nama, 0, 1)) }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-sm font-semibold text-primary">{{ $km->nama }}</span>
                                    @if($km->website)
                                    <a href="{{ $km->website }}" target="_blank" class="text-xs text-accent hover:underline">website</a>
                                    @endif
                                </div>
                                <p class="text-sm text-slate-600 leading-relaxed">{{ $km->isi }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p class="text-sm text-slate-400">Belum ada komentar. Jadilah yang pertama!</p>
                    @endif

                    {{-- Comment Form --}}
                    <form method="post" action="{{ url('komentar/'.$artikel->artikel_id) }}" class="mt-6 pt-6 border-t border-border">
                        {{ csrf_field() }}
                        <h4 class="text-base font-bold text-primary mb-4">Tinggalkan Komentar</h4>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-4">
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Nama <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" required class="input-field">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" required class="input-field">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-slate-700 mb-1">Website</label>
                                <input type="text" name="website" class="input-field">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs font-medium text-slate-700 mb-1">Komentar <span class="text-red-500">*</span></label>
                            <textarea name="isi" rows="4" required class="input-field resize-none"></textarea>
                        </div>
                        <button type="submit" class="btn-primary text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                            Kirim Komentar
                        </button>
                    </form>
                </div>
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

                <div class="card p-5 md:p-6">
                    <h3 class="text-lg font-heading font-bold text-primary mb-4">Tentang</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">
                        Bappedalitbang Kabupaten Karo bertugas melaksanakan perencanaan pembangunan daerah, penelitian, dan pengembangan untuk kemajuan Kabupaten Karo.
                    </p>
                </div>
            </aside>
        </div>
    </div>
</section>
@endsection

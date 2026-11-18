{{--
    Bento gallery (foto + video dalam satu grid interaktif).
    Terima koleksi model Galeri (memiliki `gambar`) dan/atau GaleriVideo (memiliki `url`/`file`).
    - Tile video lokal (mp4) autoplay mute saat masuk viewport.
    - Klik tile -> modal pratinjau + dock thumbnail bisa digeser.
    - Video YouTube -> iframe di modal (autoplay).
    - Instagram/TikTok -> kartu tautan eksternal (tidak bisa diembed, lihat AGENTS.md).
    - Tile bisa di-drag untuk mengubah urutan (sesi, tidak disimpan).
--}}
@props([
    'items' => collect(),
    'id' => null,
    'eyebrow' => null,
    'title' => null,
    'description' => null,
])

@php
    $spans = [
        'md:col-span-1 md:row-span-3',
        'md:col-span-2 md:row-span-2',
        'md:col-span-1 md:row-span-3',
        'md:col-span-2 md:row-span-2',
        'md:col-span-1 md:row-span-3',
        'md:col-span-2 md:row-span-2',
        'md:col-span-1 md:row-span-3',
    ];
    // ponytail: pin 7 tile ke 3 kolom berdiri sendiri (2 kiri | 3 tengah | 2 kanan);
    // auto-placement bikin tile ke-7 nyangkut satu baris di bawah. >7 tile (halaman Galeri) tetap siklus lama.
    $spans7 = [
        'md:col-start-1 md:row-start-1 md:row-span-3',
        'md:col-start-1 md:row-start-4 md:row-span-3',
        'md:col-start-2 md:col-span-2 md:row-start-1 md:row-span-2',
        'md:col-start-2 md:col-span-2 md:row-start-3 md:row-span-2',
        'md:col-start-2 md:col-span-2 md:row-start-5 md:row-span-2',
        'md:col-start-4 md:row-start-1 md:row-span-3',
        'md:col-start-4 md:row-start-4 md:row-span-3',
    ];
    $pinned7 = $items->count() === 7;
    $list = [];
    $n = 0;
    foreach ($items as $m) {
        $n++;
        $span = $pinned7 ? $spans7[$n - 1] : $spans[($n - 1) % count($spans)];
        $isImage = ! empty($m->gambar);

        if ($isImage) {
            $list[] = [
                'id' => $n,
                'type' => 'image',
                'title' => $m->judul ?? 'Foto',
                'desc' => $m->album ? ('Album: ' . $m->album) : '',
                'src' => media_url($m->gambar),
                'thumb' => media_url($m->gambar),
                'brand' => '',
                'span' => $span,
            ];
            continue;
        }

        $youtubeId = null;
        if ($m->url && preg_match('/(?:youtu\.be\/|v=|embed\/|shorts\/)([\w-]+)/', $m->url, $mm)) {
            $youtubeId = $mm[1];
        }
        $videoThumb = $m->thumbnail
            ? $m->thumbnail_url
            : ($youtubeId ? 'https://img.youtube.com/vi/' . $youtubeId . '/hqdefault.jpg' : null);

        if ($m->file) {
            $src = media_url($m->file);
            $type = 'file';
            $brand = '';
        } elseif ($youtubeId) {
            $src = 'https://www.youtube.com/embed/' . $youtubeId;
            $type = 'youtube';
            $brand = '';
        } elseif ($m->url && preg_match('#instagram\.com/(?:p|reel|tv)/([\w-]+)#', $m->url)) {
            $src = $m->url;
            $type = 'external';
            $brand = 'Instagram';
        } elseif ($m->url && preg_match('#tiktok\.com#', $m->url)) {
            $src = $m->url;
            $type = 'external';
            $brand = 'TikTok';
        } else {
            $src = $m->url;
            $type = 'file';
            $brand = '';
        }

        $list[] = [
            'id' => $n,
            'type' => $type,
            'title' => $m->judul ?? 'Video',
            'desc' => $m->deskripsi ?? '',
            'src' => $src,
            'thumb' => $videoThumb,
            'brand' => $brand,
            'span' => $span,
        ];
    }
@endphp

@if($list)
    @if($title || $eyebrow || $description)
    <div class="text-center mb-10 md:mb-12">
        @if($eyebrow)<span class="eyebrow justify-center">{{ $eyebrow }}</span>@endif
        @if($title)<h2 class="section-title">{{ $title }}</h2>@endif
        @if($description)<p class="section-subtitle mx-auto">{{ $description }}</p>@endif
        @if($title || $eyebrow)<div class="gold-rule mx-auto mt-5"></div>@endif
    </div>
    @endif

    <div class="bento-grid" id="{{ $id }}" data-bento>
        @foreach($list as $it)
        <div class="bento-tile {{ $it['span'] }} aspect-[4/3] md:aspect-auto"
             draggable="true"
             data-bento-item
             data-id="{{ $it['id'] }}"
             data-type="{{ $it['type'] }}"
             data-title="{{ $it['title'] }}"
             data-desc="{{ $it['desc'] }}"
             data-src="{{ $it['src'] }}"
             data-thumb="{{ $it['thumb'] }}"
             data-brand="{{ $it['brand'] }}"
             role="group"
             aria-label="{{ $it['title'] }}">

            @if($it['type'] === 'external')
                <a href="{{ $it['src'] }}" target="_blank" rel="noopener"
                   class="absolute inset-0 block" aria-label="Buka {{ $it['title'] }} di {{ $it['brand'] }}">
                    @if($it['thumb'])
                        <img src="{{ $it['thumb'] }}" alt="{{ $it['title'] }}" loading="lazy" draggable="false">
                    @endif
                    <span class="bento-play">
                        <span class="bento-play-badge">
                            <svg class="w-5 h-5 text-slate-800 ml-0.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                    </span>
                    <span class="bento-brand">
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        {{ $it['brand'] }}
                    </span>
                </a>
            @else
                @if($it['type'] === 'file')
                    <video class="bento-autoplay" muted loop playsinline preload="metadata" data-autoplay draggable="false" aria-label="{{ $it['title'] }}">
                        <source src="{{ $it['src'] }}" type="video/mp4">
                    </video>
                    <span class="bento-buffering" hidden>
                        <span class="bento-buffering-spin"></span>
                    </span>
                @elseif($it['thumb'])
                    <img src="{{ $it['thumb'] }}" alt="{{ $it['title'] }}" loading="lazy" draggable="false">
                @endif

                @if($it['type'] !== 'image')
                    <span class="bento-play">
                        <span class="bento-play-badge">
                            <svg class="w-5 h-5 text-slate-800 ml-0.5" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path d="M8 5v14l11-7z"/></svg>
                        </span>
                    </span>
                @endif

                <span class="bento-overlay">
                    <span class="w-7 h-0.5 bg-gold rounded-full mb-2"></span>
                    <span class="bento-title">{{ $it['title'] }}</span>
                    @if($it['desc'])<span class="bento-desc">{{ $it['desc'] }}</span>@endif
                </span>
            @endif
        </div>
        @endforeach
    </div>

    <script>
    (function () {
        if (window.__bentoInit) return;
        window.__bentoInit = true;

        function init() {
        var reduced = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- modal pratinjau bersama ---------- */
        var modal = null;
        var currentItems = [];
        var currentIndex = 0;
        var lastFocused = null;
        var draggedRecently = false;

        function buildModal() {
            if (modal) return modal;
            modal = document.createElement('div');
            modal.className = 'bento-modal';
            modal.hidden = true;
            modal.setAttribute('role', 'dialog');
            modal.setAttribute('aria-modal', 'true');
            modal.setAttribute('aria-label', 'Pratinjau media');
            modal.innerHTML =
                '<div class="bento-modal-box">' +
                    '<div class="bento-modal-stage" data-bento-stage></div>' +
                    '<div class="bento-modal-cap">' +
                        '<h3 class="bento-modal-title" data-bento-mtitle></h3>' +
                        '<p class="bento-modal-desc" data-bento-mdesc></p>' +
                    '</div>' +
                    '<button type="button" class="bento-btn bento-btn-close" data-bento-close aria-label="Tutup">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>' +
                    '</button>' +
                    '<button type="button" class="bento-btn bento-btn-nav bento-btn-prev" data-bento-prev aria-label="Sebelumnya">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>' +
                    '</button>' +
                    '<button type="button" class="bento-btn bento-btn-nav bento-btn-next" data-bento-next aria-label="Berikutnya">' +
                        '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>' +
                    '</button>' +
                    '<div class="bento-dock" data-bento-dock></div>' +
                '</div>';
            document.body.appendChild(modal);

            modal.querySelector('[data-bento-close]').addEventListener('click', closeModal);
            modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
            modal.querySelector('[data-bento-prev]').addEventListener('click', function () {
                if (currentItems.length > 1) {
                    currentIndex = (currentIndex - 1 + currentItems.length) % currentItems.length;
                    renderModal();
                }
            });
            modal.querySelector('[data-bento-next]').addEventListener('click', function () {
                if (currentItems.length > 1) {
                    currentIndex = (currentIndex + 1) % currentItems.length;
                    renderModal();
                }
            });

            return modal;
        }

        function renderModal() {
            if (!currentItems.length) return;
            var item = currentItems[currentIndex];
            var stage = modal.querySelector('[data-bento-stage]');
            var dock = modal.querySelector('[data-bento-dock]');

            modal.querySelector('[data-bento-mtitle]').textContent = item.title || '';
            modal.querySelector('[data-bento-mdesc]').textContent = item.desc || '';
            modal.querySelector('[data-bento-prev]').style.visibility = currentItems.length > 1 ? 'visible' : 'hidden';
            modal.querySelector('[data-bento-next]').style.visibility = currentItems.length > 1 ? 'visible' : 'hidden';

            stage.innerHTML = '';
            if (item.type === 'youtube') {
                var f = document.createElement('iframe');
                f.src = item.src + (item.src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1&rel=0';
                f.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
                f.setAttribute('allowfullscreen', '');
                f.title = item.title || 'Video';
                stage.appendChild(f);
            } else if (item.type === 'file') {
                var v = document.createElement('video');
                v.controls = true;
                v.autoplay = true;
                v.playsInline = true;
                v.src = item.src;
                stage.appendChild(v);
            } else if (item.type === 'external') {
                var w = document.createElement('div');
                w.className = 'bento-modal-external';
                if (item.thumb) {
                    var im = document.createElement('img');
                    im.src = item.thumb;
                    im.alt = item.title || '';
                    im.loading = 'lazy';
                    w.appendChild(im);
                }
                var t = document.createElement('p');
                t.textContent = (item.title || 'Media') + (item.brand ? ' dibagikan di ' + item.brand : '');
                w.appendChild(t);
                var a = document.createElement('a');
                a.href = item.src;
                a.target = '_blank';
                a.rel = 'noopener';
                a.className = 'bento-modal-open';
                a.textContent = 'Buka di ' + (item.brand || 'halaman asli');
                w.appendChild(a);
                stage.appendChild(w);
            } else {
                var img = document.createElement('img');
                img.src = item.src;
                img.alt = item.title || '';
                img.loading = 'lazy';
                stage.appendChild(img);
            }

            dock.innerHTML = '';
            currentItems.forEach(function (it, i) {
                var d = document.createElement('button');
                d.type = 'button';
                d.className = 'bento-dock-item' + (i === currentIndex ? ' active' : '');
                d.setAttribute('aria-label', it.title);
                d.setAttribute('aria-current', i === currentIndex ? 'true' : 'false');
                if (it.thumb) {
                    var di = document.createElement('img');
                    di.src = it.thumb;
                    di.alt = '';
                    di.loading = 'lazy';
                    di.draggable = false;
                    d.appendChild(di);
                } else {
                    d.textContent = it.brand || (it.type === 'video' || it.type === 'file' ? 'P' : 'M');
                }
                (function (i) {
                    d.addEventListener('click', function () { currentIndex = i; renderModal(); });
                })(i);
                dock.appendChild(d);
            });

            var active = dock.querySelector('.bento-dock-item.active');
            if (active && active.scrollIntoView) {
                try { active.scrollIntoView({ behavior: reduced ? 'auto' : 'smooth', block: 'nearest', inline: 'center' }); } catch (e) {}
            }
        }

        function openModal(items, index) {
            currentItems = items;
            currentIndex = index;
            lastFocused = document.activeElement;
            var m = buildModal();
            renderModal();
            m.hidden = false;
            document.body.style.overflow = 'hidden';
            var first = m.querySelector('button');
            if (first) first.focus();
        }

        function closeModal() {
            if (!modal) return;
            modal.hidden = true;
            var stage = modal.querySelector('[data-bento-stage]');
            stage.innerHTML = '';
            document.body.style.overflow = '';
            if (lastFocused && lastFocused.focus) lastFocused.focus();
        }

        document.addEventListener('keydown', function (e) {
            if (!modal || modal.hidden) return;
            if (e.key === 'Escape') { closeModal(); e.preventDefault(); }
            else if (e.key === 'ArrowLeft') { modal.querySelector('[data-bento-prev]').click(); e.preventDefault(); }
            else if (e.key === 'ArrowRight') { modal.querySelector('[data-bento-next]').click(); e.preventDefault(); }
        });

        /* ---------- klik tile -> buka modal ---------- */
        document.addEventListener('click', function (e) {
            var tile = e.target.closest('[data-bento-item]');
            if (!tile) return;
            if (tile.dataset.type === 'external') return;
            if (draggedRecently) return;
            e.preventDefault();
            var grid = tile.closest('[data-bento]');
            if (!grid) return;
            var items = [];
            var tId = parseInt(tile.dataset.id, 10) || 0;
            var idx = 0;
            Array.prototype.forEach.call(grid.querySelectorAll('[data-bento-item]'), function (t) {
                if (t.dataset.type === 'external') return;
                var it = {
                    id: parseInt(t.dataset.id, 10) || 0,
                    type: t.dataset.type,
                    title: t.dataset.title || '',
                    desc: t.dataset.desc || '',
                    src: t.dataset.src || '',
                    thumb: t.dataset.thumb || '',
                    brand: t.dataset.brand || ''
                };
                if (it.id === tId) idx = items.length;
                items.push(it);
            });
            if (!items.length) return;
            openModal(items, idx);
        });

        /* ---------- autoplay video lokal dalam viewport ---------- */
        var vids = document.querySelectorAll('video.bento-autoplay[data-autoplay]');
        if (vids.length && 'IntersectionObserver' in window && !reduced) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (en) {
                    var v = en.target;
                    var spin = v.closest('.bento-tile').querySelector('.bento-buffering');
                    if (en.isIntersecting) {
                        var p = v.play();
                        if (p && p.catch) p.catch(function () {});
                    } else {
                        v.pause();
                    }
                });
            }, { rootMargin: '120px 0px', threshold: 0.1 });
            vids.forEach(function (v) { io.observe(v); });
        }

        document.addEventListener('waiting', function (e) {
            if (e.target.tagName === 'VIDEO') {
                var spin = e.target.closest('.bento-tile') && e.target.closest('.bento-tile').querySelector('.bento-buffering');
                if (spin) spin.hidden = false;
            }
        }, true);
        document.addEventListener('playing', function (e) {
            if (e.target.tagName === 'VIDEO') {
                var spin = e.target.closest('.bento-tile') && e.target.closest('.bento-tile').querySelector('.bento-buffering');
                if (spin) spin.hidden = true;
            }
        }, true);

        /* ---------- drag untuk mengubah urutan tile ---------- */
        var dragging = null;
        document.querySelectorAll('[data-bento]').forEach(function (grid) {
            grid.addEventListener('dragstart', function (e) {
                var tile = e.target.closest('[data-bento-item]');
                if (!tile) return;
                dragging = tile;
                tile.classList.add('dragging');
                e.dataTransfer.effectAllowed = 'move';
                try { e.dataTransfer.setData('text/plain', tile.dataset.title || ''); } catch (err) {}
            });
            grid.addEventListener('dragover', function (e) {
                if (!dragging) return;
                e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
            });
            grid.addEventListener('drop', function (e) {
                if (!dragging) return;
                e.preventDefault();
                var target = e.target.closest('[data-bento-item]');
                if (target && target !== dragging) {
                    var rect = target.getBoundingClientRect();
                    var after = (e.clientX - rect.left) > rect.width / 2;
                    if (after) {
                        if (target.nextElementSibling) grid.insertBefore(dragging, target.nextElementSibling);
                        else grid.appendChild(dragging);
                    } else {
                        grid.insertBefore(dragging, target);
                    }
                }
            });
            grid.addEventListener('dragend', function () {
                if (dragging) dragging.classList.remove('dragging');
                dragging = null;
                draggedRecently = true;
                setTimeout(function () { draggedRecently = false; }, 120);
            });
        });
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', init);
        } else {
            init();
        }
    })();
    </script>
@endif

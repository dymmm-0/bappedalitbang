{{-- Lightbox: klik gambar/video -> popup elegan, tetap di halaman sama. --}}
{{-- Data attrs: data-lightbox (src), data-caption, data-type="image|video|file" (video=iframe embed, file=<video>). --}}
<div id="lbx" class="lbx" hidden role="dialog" aria-modal="true" aria-label="Pratinjau media">
    <button type="button" class="lbx-btn lbx-close" data-lbx-close aria-label="Tutup">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M18 6L6 18M6 6l12 12"/></svg>
    </button>
    <button type="button" class="lbx-btn lbx-nav lbx-prev" data-lbx-prev aria-label="Sebelumnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 18l-6-6 6-6"/></svg>
    </button>
    <button type="button" class="lbx-btn lbx-nav lbx-next" data-lbx-next aria-label="Berikutnya">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 6l6 6-6 6"/></svg>
    </button>

    <figure class="lbx-figure">
        <div class="lbx-stage">
            <img src="" alt="">
        </div>
        <figcaption>
            <div class="lbx-caption-text"></div>
            <div class="lbx-counter"></div>
        </figcaption>
    </figure>
</div>

<style>
.lbx{position:fixed;inset:0;z-index:9999;display:flex;align-items:center;justify-content:center;padding:20px;background:rgba(8,12,26,.88);backdrop-filter:blur(10px) saturate(140%);-webkit-backdrop-filter:blur(10px) saturate(140%)}
.lbx[hidden]{display:none}
.lbx-stage{display:flex;align-items:center;justify-content:center}
.lbx-stage img{max-width:min(92vw,1080px);max-height:78vh;object-fit:contain;border-radius:14px;box-shadow:0 24px 80px rgba(0,0,0,.55)}
.lbx-stage video{max-width:min(92vw,960px);max-height:78vh;border-radius:14px;background:#000;box-shadow:0 24px 80px rgba(0,0,0,.55)}
.lbx-stage iframe{width:min(92vw,960px);aspect-ratio:16/9;max-height:78vh;border:none;border-radius:14px;box-shadow:0 24px 80px rgba(0,0,0,.55);background:#000}
.lbx-figure{text-align:center;margin:0}
.lbx figcaption{color:#cbd5e1;font-size:14px;margin-top:16px;max-width:min(92vw,1080px);margin-left:auto;margin-right:auto;display:flex;align-items:center;justify-content:space-between;gap:12px;padding:0 56px}
.lbx-caption-text{min-width:0;line-height:1.4}
.lbx-counter{flex-shrink:0;font-size:12px;font-variant-numeric:tabular-nums;color:#64748b}
.lbx-btn{position:absolute;width:46px;height:46px;border-radius:50%;border:1px solid rgba(255,255,255,.14);background:rgba(255,255,255,.07);color:#e2e8f0;display:flex;align-items:center;justify-content:center;cursor:pointer;transition:background .2s,transform .15s,opacity .2s}
.lbx-btn svg{width:22px;height:22px}
.lbx-btn:hover{background:rgba(255,255,255,.18)}
.lbx-btn:active{transform:scale(.92)}
.lbx-close{top:18px;right:18px}
.lbx-nav{top:50%;transform:translateY(-50%)}
.lbx-prev{left:18px}.lbx-next{right:18px}
.lbx-nav[disabled]{opacity:.25;cursor:default;pointer-events:none}
.lbx-btn:focus-visible{outline:2px solid rgba(255,255,255,.6);outline-offset:2px}
@media (max-width:640px){
  .lbx{flex-direction:column}
  .lbx figcaption{flex-direction:column;padding:0 8px;text-align:center}
  .lbx-btn{width:40px;height:40px}
}
@media (prefers-reduced-motion:reduce){.lbx{transition:none}}
</style>

<script>
(function () {
    var box = document.getElementById('lbx');
    if (!box) return;
    var img = box.querySelector('img');
    var stage = box.querySelector('.lbx-stage');
    var capText = box.querySelector('.lbx-caption-text');
    var counter = box.querySelector('.lbx-counter');
    var items = [];
    var idx = -1;
    var activeMedia = null;

    function open(list, index) {
        items = list;
        idx = index;
        render();
        box.hidden = false;
        document.body.style.overflow = 'hidden';
    }

    function render() {
        if (!items.length) return;
        var item = items[idx];
        var boxPrev = box.querySelector('.lbx-prev');
        var boxNext = box.querySelector('.lbx-next');
        boxPrev.hidden = boxNext.hidden = items.length < 2;
        counter.textContent = (idx + 1) + ' / ' + items.length;
        capText.textContent = item.cap || '';

        if (activeMedia) { activeMedia.remove(); activeMedia = null; }

        if (item.type === 'video') {
            var f = document.createElement('iframe');
            f.src = item.src + (item.src.indexOf('?') > -1 ? '&' : '?') + 'autoplay=1&rel=0';
            f.setAttribute('allow', 'autoplay; encrypted-media; picture-in-picture');
            f.setAttribute('allowfullscreen', '');
            f.title = item.cap || 'Video';
            stage.appendChild(f);
            activeMedia = f;
            img.hidden = true;
        } else if (item.type === 'file') {
            var v = document.createElement('video');
            v.controls = true;
            v.autoplay = true;
            v.src = item.src;
            stage.appendChild(v);
            activeMedia = v;
            img.hidden = true;
        } else {
            img.hidden = false;
            img.src = item.src;
            img.alt = item.cap || '';
        }
    }

    function close() {
        box.hidden = true;
        img.src = '';
        img.hidden = false;
        if (activeMedia) { activeMedia.remove(); activeMedia = null; }
        document.body.style.overflow = '';
    }

    document.addEventListener('click', function (e) {
        var t = e.target.closest('[data-lightbox]');
        if (!t) return;
        e.preventDefault();
        var group = t.dataset.group || '__single';
        var list = Array.prototype.map.call(
            document.querySelectorAll('[data-lightbox][data-group="' + group + '"]'),
            function (el) {
                return {
                    src: el.dataset.lightbox,
                    cap: el.dataset.caption || '',
                    type: el.dataset.type || 'image'
                };
            }
        );
        var i = list.findIndex(function (x) { return x.src === t.dataset.lightbox; });
        open(list.length ? list : [{ src: t.dataset.lightbox, cap: t.dataset.caption || '', type: 'image' }], Math.max(i, 0));
    });

    box.querySelector('.lbx-close').addEventListener('click', close);
    box.addEventListener('click', function (e) { if (e.target === box) close(); });
    box.querySelector('.lbx-prev').addEventListener('click', function () {
        idx = (idx - 1 + items.length) % items.length;
        render();
    });
    box.querySelector('.lbx-next').addEventListener('click', function () {
        idx = (idx + 1) % items.length;
        render();
    });
    document.addEventListener('keydown', function (e) {
        if (box.hidden) return;
        if (e.key === 'Escape') close();
        if (e.key === 'ArrowLeft') box.querySelector('.lbx-prev').click();
        if (e.key === 'ArrowRight') box.querySelector('.lbx-next').click();
    });
})();
</script>
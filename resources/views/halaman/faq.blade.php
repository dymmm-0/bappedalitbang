@extends('layouts.master')

@section('title', setting('nama_situs', 'Bappedalitbang Kabupaten Karo') . ' — FAQ')

@section('content')
    @include('layouts.partials.page-header', [
        'pageEyebrow' => 'Bantuan',
        'pageTitle' => 'Tanya Jawab (FAQ)',
        'pageDescription' => 'Pertanyaan yang sering diajukan beserta jawabannya seputar layanan Bappedalitbang Kabupaten Karo.',
        'pageBreadcrumb' => collect([
            ['label' => 'Beranda', 'url' => url('/')],
            ['label' => 'FAQ', 'url' => null],
        ]),
    ])

    <section class="py-16 md:py-24 bg-white">
        <div class="container-gov">
            <div class="max-w-3xl mx-auto">
                @if($faqs->isNotEmpty())
                <div class="space-y-3" id="faqAccordion">
                    @foreach($faqs as $i => $faq)
                    <div class="reveal card overflow-hidden">
                        <button type="button" class="faq-toggle w-full flex items-center justify-between gap-4 p-5 text-left" aria-expanded="false" aria-controls="faqPanel{{ $i }}">
                            <span class="text-sm md:text-base font-heading font-bold text-primary leading-snug">{{ $faq->pertanyaan }}</span>
                            <svg class="w-5 h-5 shrink-0 text-accent transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div id="faqPanel{{ $i }}" class="faq-panel hidden px-5 pb-5">
                            <div class="prose prose-slate max-w-none text-sm leading-relaxed border-t border-slate-100 pt-4">
                                {!! $faq->jawaban !!}
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="reveal card p-10 text-center">
                    <h2 class="text-lg font-heading font-bold text-primary mb-2">Belum ada pertanyaan</h2>
                    <p class="text-sm text-slate-500">FAQ akan segera ditambahkan.</p>
                </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('.faq-toggle').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const panel = document.getElementById(btn.getAttribute('aria-controls'));
            const expanded = btn.getAttribute('aria-expanded') === 'true';
            btn.setAttribute('aria-expanded', String(!expanded));
            btn.querySelector('svg').classList.toggle('rotate-180', !expanded);
            panel.classList.toggle('hidden', expanded);
        });
    });
</script>
@endpush

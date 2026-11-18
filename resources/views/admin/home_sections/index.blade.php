@extends('admin.layouts.master')

@section('title', 'Section Halaman Depan — Admin')
@section('page-title', 'Section Halaman Depan')

@section('content')
    <div class="max-w-3xl space-y-4" id="sectionsList">
        @foreach($sections as $type => $section)
            <div data-section-type="{{ $type }}" class="bg-white rounded-xl border border-border overflow-hidden">
                <div class="px-5 py-4 flex items-center justify-between gap-3 border-b border-slate-100 bg-slate-50">
                    <div class="flex items-center gap-2">
                        <button type="button" data-sort-up class="text-slate-400 hover:text-accent">↑</button>
                        <button type="button" data-sort-down class="text-slate-400 hover:text-accent">↓</button>
                        <h3 class="font-heading font-bold text-primary">{{ $registry[$type]['label'] ?? $type }}</h3>
                    </div>
                    <form action="{{ route('admin.home_sections.update', $section->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="title" value="{{ $section->title }}">
                        <input type="hidden" name="subtitle" value="{{ $section->subtitle }}">
                        <input type="hidden" name="badge" value="{{ $section->badge }}">
                        <button type="submit" name="enabled" value="{{ $section->enabled ? '0' : '1' }}"
                                class="text-xs font-semibold px-3 py-1.5 rounded-lg transition-colors {{ $section->enabled ? 'bg-green-100 text-green-700' : 'bg-slate-200 text-slate-600' }}">
                            {{ $section->enabled ? 'Aktif' : 'Nonaktif' }}
                        </button>
                    </form>
                </div>
                <form action="{{ route('admin.home_sections.update', $section->id) }}" method="POST" class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="enabled" value="{{ $section->enabled ? '1' : '0' }}">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Judul Section</label>
                        <input type="text" name="title" value="{{ $section->title }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Sub Judul</label>
                        <input type="text" name="subtitle" value="{{ $section->subtitle }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Badge</label>
                        <input type="text" name="badge" value="{{ $section->badge }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div class="flex items-end gap-2">
                        <button type="submit" class="btn-sm">Simpan</button>
                        <form action="{{ route('admin.home_sections.destroy', $section->id) }}" method="POST" onsubmit="return confirm('Hapus section ini dari halaman depan?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-50 text-red-600 border border-red-200 hover:bg-red-100 transition-colors">Hapus</button>
                        </form>
                    </div>
                </form>
            </div>
        @endforeach
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('#sectionsList [data-sort-up], #sectionsList [data-sort-down]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const card = btn.closest('[data-section-type]');
                const parent = card.parentElement;
                const cards = Array.from(parent.children).filter(c => c.hasAttribute('data-section-type'));
                const idx = cards.indexOf(card);
                const dir = btn.hasAttribute('data-sort-up') ? -1 : 1;
                const targetIdx = idx + dir;
                if (targetIdx < 0 || targetIdx >= cards.length) return;
                parent.insertBefore(card, dir < 0 ? cards[targetIdx] : cards[targetIdx + 1]);
                const types = Array.from(parent.children).filter(c => c.hasAttribute('data-section-type')).map(c => c.dataset.sectionType);
                fetch('{{ route('admin.home_sections.reorder') }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ types })
                });
            });
        });
    </script>
@endpush

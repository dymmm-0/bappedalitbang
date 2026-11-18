@extends('admin.layouts.master')

@section('title', $module['label_plural'] . ' — Admin')

@section('page-title', $module['label_plural'])

@section('content')
    <div class="flex flex-col gap-6">
        <div class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-heading font-bold text-primary">{{ $module['label_plural'] }}</h2>
                <p class="text-sm text-slate-500">Kelola konten {{ strtolower($module['label_plural']) }}.</p>
            </div>
            <a href="{{ route('admin.crud.create', $slug) }}"
               class="inline-flex items-center gap-2 bg-accent hover:bg-slate-800 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah {{ $module['label'] }}
            </a>
        </div>

        <form method="GET" class="max-w-sm">
            @if($records->count() || request()->filled('q'))
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari {{ strtolower($module['label_plural']) }}..."
                           class="w-full rounded-lg border border-slate-300 pl-9 pr-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent">
                    <svg class="w-4 h-4 text-slate-400 absolute left-3 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            @endif
        </form>

        <div class="bg-white rounded-xl border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            @if($module['numbering'] ?? false)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">No</th>
                            @endif
                            @if($module['sortable'] ?? false)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider w-10">#</th>
                            @endif
                            @foreach($module['columns'] as $column)
                                <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $column['label'] }}</th>
                            @endforeach
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-32">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($records as $record)
                            <tr class="hover:bg-slate-50 transition-colors">
                                @if($module['numbering'] ?? false)
                                    <td class="px-4 py-3 text-sm text-slate-400">{{ $loop->iteration }}</td>
                                @endif
                                @if($module['sortable'] ?? false)
                                    <td class="px-4 py-3">
                                        <button type="button" data-sort-up data-id="{{ $record->id }}" class="text-slate-400 hover:text-accent mr-1" title="Naik">↑</button>
                                        <button type="button" data-sort-down data-id="{{ $record->id }}" class="text-slate-400 hover:text-accent" title="Turun">↓</button>
                                    </td>
                                @endif
                                @foreach($module['columns'] as $column)
                                    <td class="px-4 py-3 text-sm text-slate-700">
                                        @if(($column['type'] ?? '') === 'image')
                                            @if($record->{$column['name']})
                                                <img src="{{ media_url($record->{$column['name']}) }}" class="h-10 w-14 object-cover rounded border border-slate-200" alt="">
                                            @else
                                                <span class="text-slate-300">—</span>
                                            @endif
                                        @elseif(($column['type'] ?? '') === 'icon')
                                            @if($record->logo)
                                                <img src="{{ media_url($record->logo) }}" class="h-10 w-12 object-cover rounded-lg border border-slate-200 bg-white" alt="">
                                            @elseif($record->ikon)
                                                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg bg-accent/10 text-accent">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">{!! $record->ikon !!}</svg>
                                                </span>
                                            @else
                                                <span class="text-slate-300">—</span>
                                            @endif
                                        @elseif(($column['type'] ?? '') === 'boolean')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold {{ $record->{$column['name']} ? 'bg-green-100 text-green-700' : 'bg-slate-100 text-slate-500' }}">
                                                {{ $record->{$column['name']} ? 'Aktif' : 'Nonaktif' }}
                                            </span>
                                        @elseif(($column['type'] ?? '') === 'status')
                                            <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold
                                                @if($record->status === 'published') bg-green-100 text-green-700
                                                @elseif($record->status === 'scheduled') bg-amber-100 text-amber-700
                                                @else bg-slate-200 text-slate-600 @endif">
                                                {{ $record->statusLabel() ?? $record->status }}
                                            </span>
                                        @elseif(($column['type'] ?? '') === 'file')
                                            <span class="text-xs text-accent">{{ basename((string) $record->{$column['name']}) }}</span>
                                        @else
                                            {{ \Illuminate\Support\Str::limit(strip_tags((string) $record->{$column['name']}), 60) }}
                                        @endif
                                    </td>
                                @endforeach
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    @if($module['togglable'] ?? false)
                                    <button type="button" data-toggle data-id="{{ $record->id }}"
                                            class="inline-flex items-center gap-1 text-xs font-semibold mr-3 {{ $record->enabled ? 'text-green-600 hover:text-green-700' : 'text-slate-400 hover:text-slate-600' }}"
                                            title="{{ $record->enabled ? 'Klik untuk menonaktifkan' : 'Klik untuk mengaktifkan' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                        {{ $record->enabled ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                    @endif
                                    <a href="{{ route('admin.crud.edit', [$slug, $record->id]) }}"
                                       class="inline-flex items-center gap-1 text-xs font-semibold text-accent hover:underline mr-3">Edit</a>
                                    <form action="{{ route('admin.crud.destroy', [$slug, $record->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirmAction('Hapus data ini? Tindakan tidak dapat dibatalkan.', this.form);" class="text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-10 text-center text-sm text-slate-400">
                                    Belum ada data. Klik "Tambah {{ $module['label'] }}" untuk membuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="flex justify-center">
            {{ $records->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-sort-up], [data-sort-down]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = btn.dataset.id;
                const dir = btn.hasAttribute('data-sort-up') ? -1 : 1;
                const row = btn.closest('tr');
                const rows = Array.from(row.parentElement.children);
                const idx = rows.indexOf(row);
                const targetIdx = idx + dir;
                if (targetIdx < 0 || targetIdx >= rows.length) return;
                row.parentElement.insertBefore(dir < 0 ? row : rows[targetIdx + 1] ? row : null, dir < 0 ? rows[targetIdx] : rows[targetIdx + 1]);
                const ids = Array.from(row.parentElement.children).map(r => r.querySelector('[data-id]')?.dataset.id).filter(Boolean);
                fetch('{{ route('admin.crud.reorder', $slug) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ ids })
                }).then(r => r.json()).then(d => { if (!d.ok) location.reload(); });
            });
        });

        document.querySelectorAll('[data-toggle]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = btn.dataset.id;
                const url = '{{ url('admin/crud/' . $slug . '/__ID__/toggle') }}'.replace('__ID__', id);
                btn.disabled = true;
                fetch(url, {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                }).then(r => r.json()).then(() => location.reload()).catch(() => { btn.disabled = false; });
            });
        });
    </script>
@endpush

@extends('admin.layouts.master')

@section('title', 'Kelola Menu: ' . $menu->name . ' — Admin')
@section('page-title', 'Kelola Menu: ' . $menu->name)

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div>
            <a href="{{ route('admin.menus.index') }}" class="text-xs text-accent hover:underline mb-4 inline-block">← Kembali ke Menu</a>
            <div class="bg-white rounded-xl border border-border p-5">
                <h3 class="font-heading font-bold text-primary mb-4">Tambah Item</h3>
                <form action="{{ route('admin.menus.storeItem', $menu->id) }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Label</label>
                        <input type="text" name="label" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">URL</label>
                        <input type="text" name="url" required placeholder="/detail/1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Induk (sub-menu)</label>
                        <select name="parent_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                            <option value="">— Tidak ada (level atas) —</option>
                            @foreach($items as $item)
                                <option value="{{ $item->id }}">{{ $item->label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Target</label>
                        <select name="target" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                            <option value="_self">Tab yang sama</option>
                            <option value="_blank">Tab baru</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full btn-sm">Tambah</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-border overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                    <h3 class="font-heading font-bold text-primary">Item Menu</h3>
                    <span class="text-xs text-slate-400">{{ $menu->name }}</span>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($items as $item)
                        <div class="px-5 py-4">
                            <div class="flex items-center justify-between gap-3">
                                <div class="flex items-center gap-2">
                                    <button type="button" data-sort-up data-id="{{ $item->id }}" class="text-slate-400 hover:text-accent">↑</button>
                                    <button type="button" data-sort-down data-id="{{ $item->id }}" class="text-slate-400 hover:text-accent">↓</button>
                                    <span class="text-sm font-semibold text-slate-800">{{ $item->label }}</span>
                                    @if(! $item->enabled)
                                        <span class="text-[10px] uppercase bg-slate-200 text-slate-500 px-2 py-0.5 rounded-full">Nonaktif</span>
                                    @endif
                                </div>
                                <div class="flex items-center gap-3">
                                    <button type="button" data-edit-item data-id="{{ $item->id }}" data-child="0"
                                            data-action="{{ route('admin.menus.updateItem', [$menu->id, $item->id]) }}"
                                            data-label="{{ $item->label }}" data-url="{{ $item->url }}"
                                            data-parent="{{ $item->parent_id }}" data-target="{{ $item->target }}"
                                            data-enabled="{{ $item->enabled ? '1' : '0' }}"
                                            class="text-xs font-semibold text-slate-500 hover:text-accent">Edit</button>
                                    <form action="{{ route('admin.menus.updateItem', [$menu->id, $item->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="label" value="{{ $item->label }}">
                                        <input type="hidden" name="url" value="{{ $item->url }}">
                                        <input type="hidden" name="parent_id" value="{{ $item->parent_id }}">
                                        <input type="hidden" name="target" value="{{ $item->target }}">
                                        <input type="hidden" name="enabled" value="{{ $item->enabled ? '0' : '1' }}">
                                        <button type="submit" class="text-xs font-semibold text-slate-500 hover:text-accent">
                                            {{ $item->enabled ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.menus.deleteItem', [$menu->id, $item->id]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirmAction('Hapus item ini?', this.form);" class="text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                                    </form>
                                </div>
                            </div>
                            @foreach($item->children as $child)
                                <div class="ml-10 mt-2 flex items-center justify-between gap-3 bg-slate-50 rounded-lg px-4 py-2.5">
                                    <span class="text-sm text-slate-600">↳ {{ $child->label }}</span>
                                    <div class="flex items-center gap-3">
                                        <button type="button" data-edit-item data-id="{{ $child->id }}" data-child="1"
                                                data-parent-label="{{ $item->label }}"
                                                data-action="{{ route('admin.menus.updateItem', [$menu->id, $child->id]) }}"
                                                data-label="{{ $child->label }}" data-url="{{ $child->url }}"
                                                data-parent="{{ $child->parent_id }}" data-target="{{ $child->target }}"
                                                data-enabled="{{ $child->enabled ? '1' : '0' }}"
                                                class="text-xs font-semibold text-slate-500 hover:text-accent">Edit</button>
                                        <form action="{{ route('admin.menus.deleteItem', [$menu->id, $child->id]) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" onclick="return confirmAction('Hapus item ini?', this.form);" class="text-xs font-semibold text-red-600 hover:underline">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @empty
                        <p class="px-5 py-10 text-center text-sm text-slate-400">Belum ada item menu.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Edit Item --}}
    <div id="editItemModal" class="hidden fixed inset-0 z-[105] flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="editItemTitle">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeEditItemModal()"></div>
        <div class="relative w-full max-w-md rounded-xl bg-white p-6 shadow-2xl max-h-[90vh] overflow-y-auto">
            <h3 id="editItemTitle" class="text-base font-heading font-bold text-primary">Edit Item Menu</h3>
            <p id="editItemSub" class="mt-0.5 mb-4 text-xs text-slate-500"></p>
            <form id="editItemForm" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_label" class="block text-sm font-semibold text-slate-700 mb-1.5">Label</label>
                    <input type="text" name="label" id="edit_label" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="edit_url" class="block text-sm font-semibold text-slate-700 mb-1.5">URL</label>
                    <input type="text" name="url" id="edit_url" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                </div>
                <div>
                    <label for="edit_parent_id" class="block text-sm font-semibold text-slate-700 mb-1.5">Induk (sub-menu)</label>
                    <select name="parent_id" id="edit_parent_id" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                        <option value="">— Tidak ada (level atas) —</option>
                        @foreach($items as $item)
                            <option value="{{ $item->id }}">{{ $item->label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="edit_target" class="block text-sm font-semibold text-slate-700 mb-1.5">Target</label>
                    <select name="target" id="edit_target" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm bg-white">
                        <option value="_self">Tab yang sama</option>
                        <option value="_blank">Tab baru</option>
                    </select>
                </div>
                <label class="flex items-center gap-2 text-sm text-slate-700 cursor-pointer">
                    <input type="checkbox" name="enabled" id="edit_enabled" value="1" class="rounded border-slate-300 text-accent focus:ring-accent">
                    Aktif
                </label>
                <div class="flex gap-3 pt-1">
                    <button type="button" onclick="closeEditItemModal()" class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
                    <button type="submit" class="flex-1 btn-sm">Simpan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.querySelectorAll('[data-sort-up], [data-sort-down]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id = btn.dataset.id;
                const dir = btn.hasAttribute('data-sort-up') ? -1 : 1;
                const row = btn.closest('.divide-y > div');
                const parent = row.parentElement;
                const rows = Array.from(parent.children);
                const idx = rows.indexOf(row);
                const targetIdx = idx + dir;
                if (targetIdx < 0 || targetIdx >= rows.length) return;
                parent.insertBefore(row, dir < 0 ? rows[targetIdx] : rows[targetIdx + 1]);
                const ids = Array.from(parent.children).map(r => r.querySelector('[data-id]')?.dataset.id).filter(Boolean);
                fetch('{{ route('admin.menus.reorder', $menu->id) }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                    body: JSON.stringify({ ids })
                });
            });
        });
    </script>
    <script>
        const editModal = document.getElementById('editItemModal');
        const editForm = document.getElementById('editItemForm');
        const editParent = document.getElementById('edit_parent_id');

        function closeEditItemModal() {
            editModal.classList.add('hidden');
        }

        document.querySelectorAll('[data-edit-item]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                editForm.action = btn.dataset.action;
                document.getElementById('edit_label').value = btn.dataset.label;
                document.getElementById('edit_url').value = btn.dataset.url;
                editParent.value = btn.dataset.parent || '';
                document.getElementById('edit_target').value = btn.dataset.target;
                document.getElementById('edit_enabled').checked = btn.dataset.enabled === '1';

                Array.prototype.forEach.call(editParent.options, function (o) {
                    o.disabled = (o.value === btn.dataset.id);
                });

                const isChild = btn.dataset.child === '1';
                editParent.disabled = isChild;
                document.getElementById('editItemSub').textContent = isChild
                    ? 'Sub-menu dari "' + (btn.dataset.parentLabel || '') + '"'
                    : 'Item level atas';

                editModal.classList.remove('hidden');
                document.getElementById('edit_label').focus();
            });
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeEditItemModal();
        });
    </script>
@endpush

@php
    $name = $field['name'];
    $label = $field['label'];
    $required = $field['required'] ?? false;
    $current = $record ? ($record->{$name} ?? null) : null;
    $oldValue = old($name, $current);
    $errors = $errors ?? collect();
@endphp

<div class="mb-5">
    <label for="field_{{ $name }}" class="block text-sm font-semibold text-slate-700 mb-1.5">
        {{ $label }} @if($required)<span class="text-red-500">*</span>@endif
    </label>

    @if($errors->has($name))
        <p class="mb-1 text-xs text-red-600 font-medium">{{ $errors->first($name) }}</p>
    @endif

    @if($field['name'] === 'rejection_reason')
        @php
            $approver = auth()->user()->isSuperadmin();
        @endphp
        @if($approver)
            <textarea name="{{ $name }}" id="field_{{ $name }}" rows="3" placeholder="Tulis alasan penolakan / catatan perbaikan untuk admin bidang" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">{{ old($name, is_string($current) ? $current : '') }}</textarea>
            <p class="mt-1 text-xs text-slate-400">Diisi saat menolak atau meminta perbaikan. Akan ditampilkan ke admin bidang.</p>
        @elseif($current)
            <div class="rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700">
                <span class="font-semibold">Alasan:</span> {{ $current }}
            </div>
        @else
            <p class="text-sm text-slate-400">Belum ada catatan dari Kepala Bappedalitbang.</p>
        @endif
    @elseif($field['type'] === 'editor')
        <textarea name="{{ $name }}" id="field_{{ $name }}" class="summernote w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm min-h-[220px]">{{ old($name, is_string($current) ? $current : '') }}</textarea>
    @elseif($field['type'] === 'textarea')
        <textarea name="{{ $name }}" id="field_{{ $name }}" rows="4" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">{{ $oldValue }}</textarea>
    @elseif($field['type'] === 'repeater')
        <textarea name="{{ $name }}" id="field_{{ $name }}" rows="6" placeholder="Satu item per baris" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm font-mono">{{ is_array($current) ? implode("\n", $current) : (is_string($current) ? $current : '') }}</textarea>
        <p class="mt-1 text-xs text-slate-400">Tulis setiap item pada baris baru. Item akan disimpan sebagai daftar.</p>
    @elseif($field['type'] === 'select')
        @if($name === 'status')
            @php
                $approver = auth()->user()->isSuperadmin();
                $statusOptions = $approver
                    ? ['draft' => 'Draf (belum diajukan)', 'pending' => 'Menunggu Persetujuan', 'published' => 'Disetujui / Terbit', 'rejected' => 'Ditolak', 'revision' => 'Perlu Perbaikan', 'scheduled' => 'Terjadwal']
                    : ['draft' => 'Simpan sebagai Draf', 'pending' => 'Ajukan (Menunggu Persetujuan)'];
            @endphp
            <select name="{{ $name }}" id="field_{{ $name }}" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm bg-white">
                @foreach($statusOptions as $value => $optionLabel)
                    <option value="{{ $value }}" @selected($oldValue == $value)>{{ $optionLabel }}</option>
                @endforeach
            </select>
            @if(! $approver)
                <p class="mt-1 text-xs text-slate-400">Konten akan tampil di publik hanya setelah disetujui Kepala Bappedalitbang.</p>
            @endif
        @else
            <select name="{{ $name }}" id="field_{{ $name }}" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm bg-white">
                @foreach($field['options'] ?? [] as $value => $optionLabel)
                    <option value="{{ $value }}" @selected($oldValue == $value)>{{ $optionLabel }}</option>
                @endforeach
            </select>
        @endif
    @elseif($field['type'] === 'checkbox')
        <label class="inline-flex items-center gap-2 cursor-pointer">
            <input type="checkbox" name="{{ $name }}" value="1" class="rounded border-slate-300 text-accent focus:ring-accent h-4 w-4" @checked((bool) $oldValue)>
            <span class="text-sm text-slate-600">Aktif / Tampilkan</span>
        </label>
        <input type="hidden" name="{{ $name }}_hidden" value="1">
    @elseif($field['type'] === 'image')
        @if($current)
            <div class="mb-2">
                <a href="{{ media_url($current) }}" data-lightbox="{{ media_url($current) }}" data-caption="{{ $label }}" data-group="crud-{{ $name }}"
                   class="inline-block" aria-label="Pratinjau {{ $label }}">
                    <img src="{{ media_url($current) }}" alt="{{ $label }}" class="h-28 w-40 object-cover rounded-lg border border-slate-200 cursor-zoom-in">
                </a>
                <p class="mt-1 text-xs text-slate-400">{{ $current }}</p>
            </div>
        @endif
        <input type="file" name="{{ $name }}" id="field_{{ $name }}" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-slate-800 file:text-white hover:file:bg-slate-700">
    @elseif($field['type'] === 'file')
        @if($current)
            <p class="mb-2 text-xs text-accent">
                <a href="{{ media_url($current) }}" target="_blank" class="underline">Lihat file saat ini</a>
                <span class="text-slate-400">· {{ $current }}</span>
            </p>
        @endif
        <input type="file" name="{{ $name }}" id="field_{{ $name }}" class="block w-full text-sm text-slate-500 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-slate-800 file:text-white hover:file:bg-slate-700">
    @elseif($field['type'] === 'number')
        <input type="number" name="{{ $name }}" id="field_{{ $name }}" value="{{ $oldValue }}" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">
    @elseif($field['type'] === 'date')
        <input type="date" name="{{ $name }}" id="field_{{ $name }}" value="{{ $oldValue ? \Illuminate\Support\Carbon::parse($oldValue)->format('Y-m-d') : '' }}" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">
    @elseif($field['type'] === 'datetime')
        <input type="datetime-local" name="{{ $name }}" id="field_{{ $name }}" value="{{ $oldValue ? \Illuminate\Support\Carbon::parse($oldValue)->format('Y-m-d\TH:i') : '' }}" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">
    @else
        <input type="text" name="{{ $name }}" id="field_{{ $name }}" value="{{ $oldValue }}" placeholder="{{ $field['placeholder'] ?? '' }}" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">
    @endif
</div>

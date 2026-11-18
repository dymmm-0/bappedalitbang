@extends('admin.layouts.master')

@section('title', 'Pengaturan Situs — Admin')
@section('page-title', 'Pengaturan Situs')

@section('content')
    <form action="{{ route('admin.settings.store') }}" method="POST">
        @csrf
        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
            @foreach($groups as $groupKey => $group)
                <div class="bg-white rounded-xl border border-border overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                        <h3 class="font-heading font-bold textx`-primary">{{ $group['label'] }}</h3>
                    </div>
                    <div class="p-5 space-y-4">
                        @foreach($group['fields'] as $field)
                            @php
                                $value = $values->get($field['key'], $field['default'] ?? '');
                                $errors = $errors ?? collect();
                            @endphp
                            <div>
                                <label for="setting_{{ $field['key'] }}" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $field['label'] }}</label>
                                @if($field['type'] === 'textarea')
                                    <textarea name="{{ $field['key'] }}" id="setting_{{ $field['key'] }}" rows="3"
                                              class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">{{ $value }}</textarea>
                                @elseif($field['type'] === 'color')
                                    <div class="flex items-center gap-3">
                                        <input type="color" name="{{ $field['key'] }}" id="setting_{{ $field['key'] }}" value="{{ $value }}" class="h-10 w-16 border border-slate-300 rounded-lg cursor-pointer">
                                        <input type="text" id="setting_{{ $field['key'] }}_text" value="{{ $value }}"
                                               readonly tabindex="-1"
                                               class="w-32 rounded-lg border border-slate-300 bg-slate-50 px-3 py-2 text-sm font-mono">
                                    </div>
                                    <script>
                                        document.getElementById('setting_{{ $field['key'] }}').addEventListener('input', function () {
                                            document.getElementById('setting_{{ $field['key'] }}_text').value = this.value;
                                        });
                                    </script>
                                @else
                                    <input type="{{ $field['type'] === 'url' ? 'url' : 'text' }}" name="{{ $field['key'] }}"
                                           id="setting_{{ $field['key'] }}" value="{{ $value }}"
                                           class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <button type="submit" class="btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Pengaturan
            </button>
        </div>
    </form>
@endsection

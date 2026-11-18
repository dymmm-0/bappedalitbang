@extends('admin.layouts.master')

@section('title', 'Alamat — Admin')
@section('page-title', 'Alamat & Peta')

@section('content')
    @php
        $fields = [
            ['key' => 'alamat', 'label' => 'Alamat Kantor', 'type' => 'textarea'],
            ['key' => 'alamat_map', 'label' => 'Koordinat Peta (lat, lng)', 'type' => 'text'],
            ['key' => 'telepon', 'label' => 'Telepon', 'type' => 'text'],
            ['key' => 'email', 'label' => 'Email Resmi', 'type' => 'text'],
        ];
        $defaults = [
            'alamat' => 'Jl. Letjend Djamin Ginting, Kabanjahe, Kabupaten Karo, Sumatera Utara 22115',
            'alamat_map' => '',
            'telepon' => '(0628) 91234',
            'email' => 'bappedalitbang@karokab.go.id',
        ];
        $mapQ = trim($values->get('alamat_map', ''));
    @endphp

    @if(session('flash_success'))
    <div class="mb-6 flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 text-sm rounded-xl px-5 py-4" role="status">
        <svg class="w-5 h-5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        {{ session('flash_success') }}
    </div>
    @endif

    <form action="{{ route('admin.settings.store') }}" method="POST">
        @csrf
        <input type="hidden" name="_alamat_group" value="identitas">
        <input type="hidden" name="_alamat_map_group" value="identitas">
        <input type="hidden" name="_telepon_group" value="identitas">
        <input type="hidden" name="_email_group" value="identitas">

        <div class="grid grid-cols-1 xl:grid-cols-2 gap-6 items-start">
            <div class="bg-white rounded-xl border border-border overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-heading font-bold text-primary">Detail Alamat</h3>
                </div>
                <div class="p-5 space-y-4">
                    @foreach($fields as $field)
                        <div>
                            <label for="setting_{{ $field['key'] }}" class="block text-sm font-semibold text-slate-700 mb-1.5">{{ $field['label'] }}</label>
                            @if($field['type'] === 'textarea')
                                <textarea name="{{ $field['key'] }}" id="setting_{{ $field['key'] }}" rows="3"
                                          class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">{{ $values->get($field['key'], $defaults[$field['key']] ?? '') }}</textarea>
                            @else
                                <input type="text" name="{{ $field['key'] }}" id="setting_{{ $field['key'] }}"
                                       value="{{ $values->get($field['key'], $defaults[$field['key']] ?? '') }}"
                                       class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm">
                            @endif
                            @if($field['key'] === 'alamat_map')
                            <p class="mt-1.5 text-xs text-slate-400">Format: <code>lat, lng</code> — cth: <code>3.1109722, 98.5013056</code>. Peta di bawah ikut berubah.</p>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-xl border border-border overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50 flex items-center justify-between">
                    <h3 class="font-heading font-bold text-primary">Pratinjau Peta</h3>
                    <span id="mapStatus" class="text-xs font-semibold {{ $mapQ ? 'text-green-600' : 'text-amber-600' }}">{{ $mapQ ? 'Memakai koordinat' : 'Memakai alamat teks' }}</span>
                </div>
                <div class="p-5">
                    <iframe id="mapPreview" src="https://www.google.com/maps?q={{ urlencode($mapQ ?: $values->get('alamat', $defaults['alamat'])) }}&output=embed"
                            width="100%" height="360" style="border:0;display:block"
                            allowfullscreen loading="lazy" referrerpolicy="no-referrer-when-downgrade"
                            title="Pratinjau peta lokasi"></iframe>
                </div>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Simpan Alamat
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('input', function (e) {
            if (e.target.id !== 'setting_alamat_map') return;
            var coords = e.target.value.trim();
            var iframe = document.getElementById('mapPreview');
            var status = document.getElementById('mapStatus');
            var alamat = document.getElementById('setting_alamat').value.trim();
            iframe.src = 'https://www.google.com/maps?q=' + encodeURIComponent(coords || alamat) + '&output=embed';
            if (coords) {
                status.textContent = 'Memakai koordinat';
                status.className = 'text-xs font-semibold text-green-600';
            } else {
                status.textContent = 'Memakai alamat teks';
                status.className = 'text-xs font-semibold text-amber-600';
            }
        });
    </script>
@endsection

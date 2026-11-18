@extends('admin.layouts.master')

@section('title', 'Periksa Pengajuan — Admin')
@section('page-title', 'Periksa Pengajuan')

@section('content')
    <div class="flex flex-col gap-6 max-w-3xl">
        <a href="{{ route('admin.persetujuan.index', ['type' => $type]) }}" class="text-slate-400 hover:text-accent transition-colors text-sm">
            ← Kembali ke Persetujuan Konten
        </a>

        <div class="bg-white rounded-xl border border-border overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100 flex items-start justify-between gap-4">
                <div>
                    <h2 class="text-lg font-heading font-bold text-primary">{{ $item->judul }}</h2>
                    <p class="text-sm text-slate-500 mt-0.5">
                        {{ ucfirst($item->type) }}
                        · Bidang: {{ $item->bidangSingkatan ?? $item->bidang ?? '—' }}
                        @if($item->submitter) · Pengaju: {{ $item->submitter }} @endif
                    </p>
                </div>
                <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 shrink-0">{{ $item->statusLabel }}</span>
            </div>

            <div class="px-6 py-5">
                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-5">
                    <div>
                        <dt class="text-slate-400">Bidang Asal</dt>
                        <dd class="font-medium text-slate-700">{{ $item->bidang ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Pengaju</dt>
                        <dd class="font-medium text-slate-700">{{ $item->submitter ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Tanggal Pengajuan</dt>
                        <dd class="font-medium text-slate-700">{{ $item->submittedAt ? \Illuminate\Support\Carbon::parse($item->submittedAt)->format('d/m/Y H:i') : ($item->createdAt ? \Illuminate\Support\Carbon::parse($item->createdAt)->format('d/m/Y H:i') : '—') }}</dd>
                    </div>
                    <div>
                        <dt class="text-slate-400">Status</dt>
                        <dd class="font-medium text-slate-700">{{ $item->statusLabel }}</dd>
                    </div>
                    @if($item->rejectionReason)
                    <div class="sm:col-span-2">
                        <dt class="text-slate-400">Catatan / Alasan</dt>
                        <dd class="font-medium text-red-700">{!! nl2br(e($item->rejectionReason)) !!}</dd>
                    </div>
                    @endif
                </dl>

                @if(! empty($item->detailFields))
                    <dl class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-sm mb-5 border-t border-slate-100 pt-4">
                        @foreach($item->detailFields as $label => $value)
                            <div>
                                <dt class="text-slate-400">{{ $label }}</dt>
                                <dd class="font-medium text-slate-700">{!! $value !!}</dd>
                            </div>
                        @endforeach
                    </dl>
                @endif

                @if($item->pendingChanges)
                    <div class="mb-5 border-t border-slate-100 pt-4">
                        <h3 class="text-sm font-semibold text-slate-700 mb-2">Perubahan Sebelum → Sesudah</h3>
                        <div class="overflow-x-auto rounded-lg border border-slate-200">
                            <table class="min-w-full text-sm">
                                <thead class="bg-slate-50">
                                    <tr>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500">Field</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500">Sebelum (Data Aktif)</th>
                                        <th class="px-3 py-2 text-left text-xs font-semibold text-slate-500">Sesudah (Usulan)</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100">
                                    @foreach($item->pendingChanges as $field => $oldValue)
                                        <tr>
                                            <td class="px-3 py-2 font-medium text-slate-600">{{ \Illuminate\Support\Str::title(str_replace('_', ' ', $field)) }}</td>
                                            <td class="px-3 py-2 text-slate-700">{{ $oldValue === '' || is_null($oldValue) ? '—' : e($oldValue) }}</td>
                                            <td class="px-3 py-2 text-accent font-medium">{{ $model->{$field} === '' || is_null($model->{$field}) ? '—' : e($model->{$field}) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif

                @if($item->isi)
                    <div class="prose max-w-none text-sm text-slate-700 border-t border-slate-100 pt-4">
                        {!! $item->isi !!}
                    </div>
                @endif

                @if(isset($video) && $video)
                @php
                    $ytEmbed = youtube_embed_url($video->url);
                @endphp
                <div class="border-t border-slate-100 pt-4 mt-4">
                    <h3 class="text-sm font-semibold text-slate-700 mb-2">Video Terkait</h3>
                    @if($ytEmbed)
                    <div class="relative w-full overflow-hidden rounded-xl border border-border bg-black" style="aspect-ratio: 16 / 9; overflow: hidden;">
                        <iframe src="{{ $ytEmbed }}" style="position:absolute; top:0; left:0; width:100%; height:100%; border:0;" title="{{ $video->judul ?? 'Video Terkait' }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen loading="lazy"></iframe>
                    </div>
                    @else
                    <a href="{{ $video->url }}" target="_blank" rel="noopener" class="group block">
                        <div class="relative w-full overflow-hidden rounded-xl border border-border bg-slate-100" style="aspect-ratio: 16 / 9; overflow: hidden;">
                            @if($video->thumbnail)
                            <img src="{{ media_url($video->thumbnail) }}" alt="{{ $video->judul ?? 'Video Terkait' }}" style="width:100%; height:100%; object-fit:cover;">
                            @else
                            <div class="w-full h-full flex items-center justify-center text-sm text-slate-400">Buka video di media sosial</div>
                            @endif
                            <div class="absolute inset-0 bg-black/30 group-hover:bg-black/40 transition-colors flex items-center justify-center">
                                <span class="px-4 py-2 bg-white/90 rounded-full text-sm font-medium text-primary shadow">Tonton Video</span>
                            </div>
                        </div>
                    </a>
                    @endif
                </div>
                @endif
            </div>

            @if(isset($module) && isset($model))
                <div class="px-6 py-4 bg-slate-50 border-t border-slate-100">
                    <p class="text-sm font-semibold text-slate-700 mb-3">Edit Langsung (Kepala Bappedalitbang)</p>
                    <form action="{{ route('admin.crud.update', [$type, $model->id]) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-4">
                            @foreach($module['fields'] as $field)
                                @include('admin.crud._fields', ['field' => $field, 'record' => $model])
                            @endforeach
                        </div>
                        <div class="mt-2">
                            <button type="submit" class="inline-flex items-center gap-2 bg-accent hover:bg-slate-800 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            @endif

            <div class="px-6 py-4 bg-white border-t border-slate-100 flex flex-wrap items-center gap-3">
                <form action="{{ route('admin.persetujuan.approve', [$type, $item->id]) }}" method="POST">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors"
                            onclick="return confirm('Setujui konten ini? Konten akan tampil di halaman publik.');">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Setujui
                    </button>
                </form>

                <button type="button" onclick="document.getElementById('rejectForm').classList.toggle('hidden')"
                        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    Tolak
                </button>

                <button type="button" onclick="document.getElementById('revisionForm').classList.toggle('hidden')"
                        class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
                    Perlu Perbaikan
                </button>
            </div>

            <div id="rejectForm" class="hidden px-6 py-4 border-t border-slate-100 bg-white">
                <form action="{{ route('admin.persetujuan.reject', [$type, $item->id]) }}" method="POST" class="space-y-3">
                    @csrf
                    <label class="block text-sm font-semibold text-slate-700">Alasan Penolakan</label>
                    <textarea name="reason" rows="3" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm" placeholder="Tulis alasan agar admin bidang dapat merevisi..."></textarea>
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-sm font-semibold px-4 py-2.5 rounded-lg">Kirim Penolakan</button>
                </form>
            </div>

            <div id="revisionForm" class="hidden px-6 py-4 border-t border-slate-100 bg-white">
                <form action="{{ route('admin.persetujuan.revision', [$type, $item->id]) }}" method="POST" class="space-y-3">
                    @csrf
                    <label class="block text-sm font-semibold text-slate-700">Catatan Perbaikan</label>
                    <textarea name="reason" rows="3" class="w-full rounded-lg border border-slate-300 focus:ring-2 focus:ring-accent focus:border-accent px-3 py-2 text-sm" placeholder="Tulis catatan perbaikan untuk admin bidang..."></textarea>
                    <button type="submit" class="bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold px-4 py-2.5 rounded-lg">Kembalikan untuk Diperbaiki</button>
                </form>
            </div>
        </div>
    </div>
@endsection

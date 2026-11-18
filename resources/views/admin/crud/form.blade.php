@php
    $isSingleton = $module['singleton'] ?? false;
    $formAction = $isSingleton
        ? route('admin.crud.store', $slug)
        : ($record ? route('admin.crud.update', [$slug, $record->id]) : route('admin.crud.store', $slug));
    $formMethod = $record && ! $isSingleton ? 'PUT' : 'POST';
    $heading = $isSingleton
        ? $module['label_plural']
        : ($record ? 'Edit ' . $module['label'] : 'Tambah ' . $module['label']);
@endphp

@extends('admin.layouts.master')

@section('title', $heading . ' — Admin')

@section('page-title', $heading)

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="max-w-3xl">
        <div class="flex items-center gap-3 mb-6">
            <a href="{{ route('admin.crud.index', $slug) }}" class="text-slate-400 hover:text-accent transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div>
                <h2 class="text-xl font-heading font-bold text-primary">{{ $heading }}</h2>
                <p class="text-sm text-slate-500">{{ $module['label_plural'] }}</p>
            </div>
        </div>

        <form action="{{ $formAction }}" method="POST" enctype="multipart/form-data"
              class="bg-white rounded-xl border border-border p-6">
            @csrf
            @if($formMethod === 'PUT')
                @method('PUT')
            @endif

            @foreach($module['fields'] as $field)
                @include('admin.crud._fields', ['field' => $field, 'record' => $record])
            @endforeach

            <div class="flex items-center gap-3 mt-6 pt-5 border-t border-slate-100">
                <button type="submit"
                        class="inline-flex items-center gap-2 bg-accent hover:bg-slate-800 text-white text-sm font-semibold px-5 py-2.5 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan
                </button>
                <a href="{{ route('admin.crud.index', $slug) }}" class="text-sm text-slate-500 hover:text-slate-700">Batal</a>
            </div>
        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.20/dist/summernote-lite.min.js"></script>
    <script>
        $(function () {
            $('.summernote').summernote({
                height: 240,
                lang: 'en-US',
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link', 'picture']],
                    ['view', ['codeview']]
                ],
                callbacks: {
                    onImageUpload: function (files) {
                        var data = new FormData();
                        data.append('file', files[0]);
                        data.append('_token', document.querySelector('meta[name="csrf-token"]').content);
                        $.ajax({
                            url: '{{ route('admin.media.upload') }}',
                            method: 'POST',
                            data: data,
                            processData: false,
                            contentType: false,
                            success: function (res) { $(this).summernote('insertImage', res.url); },
                            error: function () { showToast('error', 'Gagal mengunggah gambar.'); }
                        });
                    }
                }
            });
        });
    </script>
@endpush

@extends('admin.layouts.master')
@section('content')
<div class="max-w-4xl">
    <div class="card p-5 md:p-6">
        <h2 class="text-lg font-heading font-bold text-primary mb-6">Edit Artikel</h2>
        <form role="form" enctype="multipart/form-data" method="post" action="{{ url('admin/artikel/'.$dt->artikel_id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Judul Artikel</label>
                    <input type="text" name="judul" class="input-field" value="{{ $dt->judul }}" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Isi Artikel</label>
                    <textarea name="isi" class="input-field summernote" rows="12">{{ $dt->isi }}</textarea>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Kategori</label>
                        <select name="id_kategori" class="input-field" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($kategori as $kt)
                            <option value="{{ $kt->id }}" {{ ($dt->id_kategori == $kt->id) ? 'selected' : '' }}>{{ $kt->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-slate-700 mb-1.5">Gambar (opsional)</label>
                        <input type="file" name="image" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-accent/10 file:text-accent hover:file:bg-accent/20">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Video Terkait (URL, opsional)</label>
                    <input type="url" name="video_url" value="{{ old('video_url', $videoUrl ?? '') }}" class="input-field" placeholder="https://www.youtube.com/watch?v=...">
                    <p class="text-xs text-slate-400 mt-1">Tempel URL YouTube / Instagram / TikTok langsung. URL akan disimpan sebagai data Galeri Video (otomatis dipakai ulang bila sudah ada). Kosongkan untuk melepas video dari artikel ini (record Galeri Video tidak dihapus).</p>
                </div>
            </div>
            <div class="mt-6 pt-6 border-t border-border flex items-center gap-3">
                <button type="submit" class="btn-primary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Update Artikel
                </button>
                <a href="{{ url('admin/artikel') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layouts.master')
@section('content')
<div class="max-w-2xl">
    <div class="card p-5 md:p-6">
        <h2 class="text-lg font-heading font-bold text-primary mb-6">Edit Kategori</h2>
        <form method="post" action="{{ url('admin/kategori/'.$kategori->id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="mb-5">
                <label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Kategori</label>
                <input type="text" name="nama" class="input-field" value="{{ $kategori->nama }}" required>
            </div>
            <div class="pt-5 border-t border-border flex items-center gap-3">
                <button type="submit" class="btn-primary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Update
                </button>
                <a href="{{ url('admin/kategori') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

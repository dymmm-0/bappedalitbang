@extends('admin.layouts.master')
@section('title', 'Komentar')
@section('content')
<div class="card p-0">
    <div class="p-4 md:p-6 border-b border-border">
        <h2 class="text-lg font-heading font-bold text-primary">Daftar Komentar</h2>
    </div>
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left">
                    <th class="px-4 py-3 font-semibold text-slate-600">#</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Artikel</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Nama</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Email</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Isi</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Tanggal</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $e=>$dt)
                <tr class="border-t border-border hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">{{ $e+1 }}</td>
                    <td class="px-4 py-3 font-medium text-primary max-w-[200px] truncate">{{ $dt->judul }}</td>
                    <td class="px-4 py-3">{{ $dt->nama }}</td>
                    <td class="px-4 py-3 text-xs text-slate-500">{{ $dt->email }}</td>
                    <td class="px-4 py-3 text-slate-600 max-w-[200px] truncate">{{ $dt->isi }}</td>
                    <td class="px-4 py-3 text-xs text-slate-500">{{ date('d M Y', strtotime($dt->created_at)) }}</td>
                    <td class="px-4 py-3">
                        <a href="{{ url('hapus/komentar/'.$dt->komentar_id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-medium text-red-600 bg-red-50 hover:bg-red-100 rounded-lg transition-colors" onclick="return confirmAction('Hapus komentar ini?', this.href);">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Hapus
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@extends('admin.layouts.master')

@section('title', 'Persetujuan Konten — Admin')
@section('page-title', 'Persetujuan Konten')

@section('content')
    <div class="flex flex-col gap-6">
        <div>
            <h2 class="text-xl font-heading font-bold text-primary">Persetujuan Konten</h2>
            <p class="text-sm text-slate-500">Daftar pengajuan dari seluruh bidang yang menunggu pemeriksaan Kepala Bappedalitbang.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @foreach($submenus ?? ['semua' => 'Semua', 'pengumuman' => 'Pengumuman', 'dokumen' => 'Dokumen', 'pegawai' => 'Pegawai'] as $key => $label)
                <a href="{{ route('admin.persetujuan.index', ['type' => $key]) }}"
                   class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-semibold transition-colors
                   {{ ($typeFilter ?? 'semua') === $key ? 'bg-accent text-white' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-xl border border-border overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Judul</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Bidang</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengaju</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider w-28">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($items as $item)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-4 py-3 text-sm text-slate-700 font-medium">{{ \Illuminate\Support\Str::limit($item->judul, 60) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ ucfirst($item->type) }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $item->bidang }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $item->submitter ?? '—' }}</td>
                                <td class="px-4 py-3 text-sm text-slate-600">{{ $item->tanggal ? \Illuminate\Support\Carbon::parse($item->tanggal)->format('d/m/Y H:i') : '—' }}</td>
                                <td class="px-4 py-3 text-sm">
                                    <span class="inline-flex px-2 py-0.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700">{{ $item->statusLabel }}</span>
                                </td>
                                <td class="px-4 py-3 text-right whitespace-nowrap">
                                    <a href="{{ $item->url_show }}" class="inline-flex items-center gap-1 text-xs font-semibold text-accent hover:underline">Periksa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-10 text-center text-sm text-slate-400">
                                    Tidak ada pengajuan yang menunggu persetujuan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@extends('admin.layouts.master')
@section('content')
<div class="card p-0">
    <div class="p-4 md:p-6 border-b border-border flex items-center justify-between">
        <h2 class="text-lg font-heading font-bold text-primary">Daftar Artikel</h2>
        <a href="{{ url('admin/artikel/add') }}" class="btn-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Tambah Artikel
        </a>
    </div>
    <div class="overflow-x-auto">
        <table class="myTable w-full text-sm">
            <thead>
                <tr class="bg-slate-50 text-left">
                    <th class="px-4 py-3 font-semibold text-slate-600">#</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Judul</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Kategori</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Penulis</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Tanggal</th>
                    <th class="px-4 py-3 font-semibold text-slate-600">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $e=>$dt)
                <tr class="border-t border-border hover:bg-slate-50 transition-colors">
                    <td class="px-4 py-3">{{ $e+1 }}</td>
                    <td class="px-4 py-3 font-medium text-primary">{{ $dt->judul }}</td>
                    <td class="px-4 py-3"><span class="badge">{{ $dt->kategori }}</span></td>
                    <td class="px-4 py-3 text-slate-600">{{ $dt->name }}</td>
                    <td class="px-4 py-3 text-slate-500 text-xs">{{ date('d M Y', strtotime($dt->created_at)) }}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center gap-2">
                            <a href="{{ url('admin/artikel/'.$dt->artikel_id) }}" class="p-1.5 text-accent hover:bg-accent/10 rounded-lg transition-colors" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </a>
                            <a href="#" class="btn-hapus p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" data-url="{{ url('admin/artikel/'.$dt->artikel_id) }}" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Modal Hapus --}}
<div id="modalHapus" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="text-center">
            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-7 h-7 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"/></svg>
            </div>
            <h3 class="text-lg font-heading font-bold text-primary mb-2">Hapus Artikel</h3>
            <p class="text-sm text-slate-500 mb-6">Apakah kamu yakin ingin menghapus artikel ini? Tindakan ini tidak dapat dibatalkan.</p>
            <form id="formHapus" action="" method="post" class="flex items-center justify-center gap-3">
                {{ csrf_field() }}
                {{ method_field('delete') }}
                <button type="button" onclick="closeModal()" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</button>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-500 hover:bg-red-600 rounded-lg transition-colors">Ya, Hapus</button>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function closeModal() {
        document.getElementById('modalHapus').classList.remove('flex');
        document.getElementById('modalHapus').classList.add('hidden');
    }
    document.querySelectorAll('.btn-hapus').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('formHapus').action = this.dataset.url;
            document.getElementById('modalHapus').classList.remove('hidden');
            document.getElementById('modalHapus').classList.add('flex');
        });
    });
    document.getElementById('modalHapus')?.addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    $(document).ready(function() { $('.myTable').DataTable(); });
</script>
@endpush

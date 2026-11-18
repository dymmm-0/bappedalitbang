@extends('admin.layouts.master')
@section('content')
<div class="max-w-2xl">
    <div class="card p-5 md:p-6">
        <h2 class="text-lg font-heading font-bold text-primary mb-6">Tambah Pengguna Baru</h2>
        <form method="post" action="{{ url('admin/user') }}">
            {{ csrf_field() }}
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" class="input-field" placeholder="Masukkan nama" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" class="input-field" placeholder="email@example.com" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" class="input-field" placeholder="Masukkan password" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="input-field" placeholder="Ulangi password" required>
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Role / Hak Akses</label>
                    <select name="role" id="roleSelect" class="input-field" required>
                        <option value="">— Pilih role —</option>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}">{{ config("rbac.roles.{$role->name}.label", $role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="bidangWrap" class="hidden">
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Bidang</label>
                    <select name="bidang_id" class="input-field">
                        <option value="">— Pilih bidang —</option>
                        @foreach($bidangList as $bidang)
                            <option value="{{ $bidang->id }}">{{ $bidang->nama }} ({{ $bidang->singkatan }})</option>
                        @endforeach
                    </select>
                </div>
                @php $bidangRoles = ['admin_sekretariat','admin_ppepd','admin_ppm','admin_litbang','admin_psda','admin_ikw']; @endphp
                <script>
                    const bidangRoles = @json($bidangRoles);
                    function toggleBidang() {
                        document.getElementById('bidangWrap').classList.toggle('hidden', !bidangRoles.includes(document.getElementById('roleSelect').value));
                    }
                    document.getElementById('roleSelect').addEventListener('change', toggleBidang);
                    toggleBidang();
                </script>
            </div>
            <div class="mt-6 pt-6 border-t border-border flex items-center gap-3">
                <button type="submit" class="btn-primary text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
                    Simpan
                </button>
                <a href="{{ url('admin/user') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

@extends('admin.layouts.master')
@section('content')
<div class="max-w-2xl">
    <div class="card p-5 md:p-6">
        <h2 class="text-lg font-heading font-bold text-primary mb-6">Edit Pengguna</h2>
        <form method="post" action="{{ url('admin/user/'.$dt->id) }}">
            {{ csrf_field() }}
            {{ method_field('put') }}
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" class="input-field" value="{{ $dt->name }}" required>
                </div>
                <!-- <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" class="input-field" value="{{ $dt->email }}" required>
                </div> -->
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Password Baru (kosongkan jika tidak diubah)</label>
                    <input type="password" name="password" class="input-field" placeholder="Biarkan kosong jika tidak diubah">
                </div>
                <div>
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Role / Hak Akses</label>
                    <select name="role" id="roleSelect" class="input-field" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->name }}" @selected($dt->hasRole($role->name))>{{ config("rbac.roles.{$role->name}.label", $role->name) }}</option>
                        @endforeach
                    </select>
                </div>
                <div id="bidangWrap" class="{{ $dt->roles->contains(fn ($r) => in_array($r->name, ['admin_sekretariat','admin_ppepd','admin_ppm','admin_litbang','admin_psda','admin_ikw'])) ? '' : 'hidden' }}">
                    <label class="block text-xs font-medium text-slate-700 mb-1.5">Bidang</label>
                    <select name="bidang_id" class="input-field">
                        <option value="">— Pilih bidang —</option>
                        @foreach($bidangList as $bidang)
                            <option value="{{ $bidang->id }}" @selected($dt->bidang_id == $bidang->id)>{{ $bidang->nama }} ({{ $bidang->singkatan }})</option>
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
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                    Update
                </button>
                <a href="{{ url('admin/user') }}" class="px-4 py-2 text-sm font-medium text-slate-600 hover:bg-slate-100 rounded-lg transition-colors">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection

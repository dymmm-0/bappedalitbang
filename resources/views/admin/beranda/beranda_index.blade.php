@extends('admin.layouts.master')
@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('content')
@php
    $cards = [
        ['key' => 'kunjungan', 'perm' => null, 'label' => 'Kunjungan Harian', 'desc' => 'Lihat statistik kunjungan website per hari', 'route' => 'admin/kunjungan',
         'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['key' => 'artikel', 'perm' => 'manage_artikel', 'label' => 'Artikel', 'desc' => 'Kelola berita & artikel website', 'route' => 'admin/artikel',
         'icon' => 'M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z'],
        ['key' => 'kategori', 'perm' => 'manage_kategori', 'label' => 'Kategori', 'desc' => 'Kelola kategori artikel', 'route' => 'admin/kategori',
         'icon' => 'M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z'],
        ['key' => 'komentar', 'perm' => 'manage_komentar', 'label' => 'Komentar', 'desc' => 'Kelola komentar pengunjung', 'route' => 'admin/komentar',
         'icon' => 'M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z'],
        ['key' => 'pengumuman', 'perm' => 'manage_pengumuman', 'label' => 'Pengumuman', 'desc' => 'Kelola pengumuman resmi', 'route' => 'admin/crud/pengumuman',
         'icon' => 'M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z'],
        ['key' => 'dokumen', 'perm' => 'manage_dokumen', 'label' => 'Dokumen', 'desc' => 'Kelola dokumen & unduhan', 'route' => 'admin/crud/dokumen',
         'icon' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z'],
        ['key' => 'bidang', 'perm' => 'manage_bidang', 'label' => 'Bidang', 'desc' => 'Kelola profil unit kerja', 'route' => 'admin/crud/bidang',
         'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
        ['key' => 'kepala', 'perm' => 'manage_kepala', 'label' => 'Kepala', 'desc' => 'Kelola data Kepala Bappedalitbang', 'route' => 'admin/crud/kepala',
         'icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0zm6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
        ['key' => 'galeri', 'perm' => 'manage_galeri', 'label' => 'Galeri Foto', 'desc' => 'Kelola dokumentasi foto kegiatan', 'route' => 'admin/crud/galeri',
         'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
        ['key' => 'galeri_video', 'perm' => 'manage_galeri_video', 'label' => 'Video', 'desc' => 'Kelola dokumentasi video kegiatan', 'route' => 'admin/crud/galeri_video',
         'icon' => 'M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z'],
        ['key' => 'users', 'perm' => 'manage_users', 'label' => 'Pengguna', 'desc' => 'Kelola akun pengguna & hak akses', 'route' => 'admin/user',
         'icon' => 'M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z'],
    ];
    $showCards = array_values(array_filter($cards, fn ($c) => ! $c['perm'] || \Auth::user()->can($c['perm'])));
@endphp

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 mb-8">
    @foreach($showCards as $card)
    <a href="{{ url($card['route']) }}" class="card p-4 md:p-5 flex items-center justify-between gap-4 hover:shadow-md transition-shadow group">
        <div class="flex items-center gap-4 min-w-0">
            <div class="w-12 h-12 bg-accent/10 rounded-xl flex items-center justify-center shrink-0">
                <svg class="w-6 h-6 text-accent" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $card['icon'] }}"/></svg>
            </div>
            <div class="min-w-0">
                <p class="font-heading font-bold text-primary">{{ $card['label'] }}</p>
                <p class="text-xs text-slate-500 truncate">{{ $card['desc'] }}</p>
            </div>
        </div>
        <svg class="w-5 h-5 text-slate-400 group-hover:text-accent transition-colors shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
    </a>
    @endforeach
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="card p-5 md:p-6 lg:col-span-2">
        <h3 class="text-lg font-heading font-bold text-primary mb-4">Selamat Datang, {{ \Auth::user()->name }}!</h3>
        <p class="text-sm text-slate-600 leading-relaxed">Ini adalah panel administrasi Bappedalitbang Kabupaten Karo.
            @if(\Auth::user()->isAdminBidang())
            Anda adalah <strong>Kepala Bidang</strong> — data yang ditampilkan hanya milik bidang Anda.
            @elseif(\Auth::user()->hasRole('admin_media'))
            Anda adalah <strong>Admin Media</strong> — kelola artikel, galeri foto, dan dokumentasi video untuk seluruh situs.
            @else
            Anda memiliki akses penuh untuk mengelola seluruh konten website.
            @endif
        </p>
    </div>
    <div class="card p-5 md:p-6">
        <h3 class="text-lg font-heading font-bold text-primary mb-4">Alamat &amp; Peta</h3>
        <div class="rounded-lg overflow-hidden border border-slate-200 mb-3">
            <iframe src="https://maps.google.com/maps?q={{ urlencode($settings['alamat_map'] ?? ($settings['alamat'] ?? '')) }}&output=embed" class="w-full h-44" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Lokasi Bappedalitbang Kabupaten Karo"></iframe>
        </div>
        <p class="text-xs text-slate-500 leading-relaxed">{{ $settings['alamat'] ?? 'Alamat belum diatur.' }}</p>
        @can('manage_settings')
        <a href="{{ url('admin/alamat') }}" class="mt-3 inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:underline">Perbarui Alamat &amp; Peta →</a>
        @endcan
    </div>
</div>

<div class="card p-5 md:p-6 mb-8">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <div>
            <h3 class="text-lg font-heading font-bold text-primary">Tren Kunjungan</h3>
            <p class="text-sm text-slate-500 mt-0.5">Perkembangan kunjungan 7 hari terakhir (data real).</p>
        </div>
        <a href="{{ url('admin/kunjungan') }}" class="inline-flex items-center gap-1.5 text-xs font-semibold text-accent hover:underline">Grafik lengkap &amp; periode →</a>
    </div>
    <div class="relative h-48 min-w-0">
        <canvas id="dashboardVisitsChart" style="max-width:100%;" aria-label="Grafik tren kunjungan 7 hari terakhir" role="img"></canvas>
    </div>
</div>

@php
    $statusBadge = function (string $status): string {
        return match ($status) {
            'published' => 'bg-green-100 text-green-700',
            'pending' => 'bg-amber-100 text-amber-700',
            'rejected' => 'bg-red-100 text-red-700',
            'revision' => 'bg-orange-100 text-orange-700',
            default => 'bg-slate-200 text-slate-600',
        };
    };
@endphp

@if(\Auth::user()->isAdminBidang())
    @php
        $kontenSaya = \App\Models\Pengumuman::where('bidang_id', \Auth::user()->bidang_id)
            ->latest()->limit(10)->get();
    @endphp
    <div class="card p-5 md:p-6">
        <h3 class="text-lg font-heading font-bold text-primary mb-1">Pengumuman Saya</h3>
        <p class="text-sm text-slate-500 mb-4">Status pengajuan konten yang Anda buat. Konten hanya tampil di publik setelah disetujui Kepala Bappedalitbang.</p>
        @if($kontenSaya->isEmpty())
            <p class="text-sm text-slate-400">Belum ada pengumuman yang Anda buat.</p>
        @else
            <ul class="divide-y divide-slate-100">
                @foreach($kontenSaya as $p)
                    <li class="py-3 flex items-center justify-between gap-3">
                        <div class="min-w-0">
                            <p class="text-sm font-medium text-slate-700 truncate">{{ $p->judul }}</p>
                            <p class="text-xs text-slate-400">{{ $p->tanggal ? $p->tanggal->format('d/m/Y') : '' }}</p>
                        </div>
                        <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-semibold shrink-0 {{ $statusBadge($p->status) }}">{{ $p->statusLabel() }}</span>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>
@elseif(\Auth::user()->isSuperadmin())
    @php
        $pendingCount = \App\Models\Pengumuman::pending()->count()
            + \App\Models\Inovasi::pending()->count()
            + \DB::table('artikel')->where('status', 'pending')->whereNull('deleted_at')->count();
    @endphp
    <a href="{{ url('admin/persetujuan') }}" class="card p-5 md:p-6 flex items-center justify-between gap-4 hover:shadow-md transition-shadow group">
        <div>
            <h3 class="text-lg font-heading font-bold text-primary">Persetujuan Konten</h3>
            <p class="text-sm text-slate-500">Periksa, setujui, atau tolak pengajuan dari seluruh bidang.</p>
        </div>
        <span class="inline-flex items-center justify-center h-10 min-w-10 px-3 rounded-full text-base font-bold {{ $pendingCount > 0 ? 'bg-amber-100 text-amber-700' : 'bg-slate-100 text-slate-500' }}">
            {{ $pendingCount }}
        </span>
    </a>
@else
    @php
        $statsKonten = [];
        if (\Auth::user()->can('manage_artikel')) {
            $statsKonten[] = ['label' => 'Artikel', 'jml' => \DB::table('artikel')->count(), 'route' => 'admin/artikel'];
        }
        if (\Auth::user()->can('manage_galeri')) {
            $statsKonten[] = ['label' => 'Foto Galeri', 'jml' => \App\Models\Galeri::count(), 'route' => 'admin/crud/galeri'];
        }
        if (\Auth::user()->can('manage_galeri_video')) {
            $statsKonten[] = ['label' => 'Video', 'jml' => \App\Models\GaleriVideo::count(), 'route' => 'admin/crud/galeri_video'];
        }
        if (\Auth::user()->can('manage_dokumen')) {
            $statsKonten[] = ['label' => 'Dokumen', 'jml' => \App\Models\Dokumen::count(), 'route' => 'admin/crud/dokumen'];
        }
        $pendingSaya = \DB::table('artikel')
            ->where('submitted_by', \Auth::user()->id)
            ->where('status', 'pending')
            ->whereNull('deleted_at')
            ->count();
    @endphp
    <div class="card p-5 md:p-6">
        <h3 class="text-lg font-heading font-bold text-primary mb-1">Konten & Dokumentasi</h3>
        <p class="text-sm text-slate-500 mb-5">Ringkasan konten yang dapat Anda kelola. Konten yang diajukan akan menunggu persetujuan Kepala Bappedalitbang sebelum tampil di publik.</p>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
            <div class="rounded-xl border border-border bg-surface p-4">
                <p class="text-2xl font-heading font-bold text-primary tabular-nums">{{ visits_total() }}</p>
                <p class="text-xs text-slate-500 mt-1">Total Kunjungan</p>
                <a href="{{ url('admin/kunjungan') }}" class="inline-block mt-2 text-xs font-semibold text-accent hover:underline">Lihat statistik →</a>
            </div>
            @foreach($statsKonten as $s)
            <a href="{{ url($s['route']) }}" class="rounded-xl border border-border bg-surface p-4 hover:shadow-md transition-shadow group">
                <p class="text-2xl font-heading font-bold text-primary tabular-nums">{{ number_format($s['jml']) }}</p>
                <p class="text-xs text-slate-500 mt-1">{{ $s['label'] }}</p>
                <span class="inline-block mt-2 text-xs font-semibold text-accent group-hover:underline">Kelola →</span>
            </a>
            @endforeach
        </div>
        @if($pendingSaya > 0)
        <p class="mt-5 text-sm text-amber-800 bg-amber-50 border border-amber-200 rounded-lg px-4 py-2.5">
            {{ $pendingSaya }} artikel Anda menunggu persetujuan Kepala Bappedalitbang.
        </p>
        @endif
    </div>
@endif
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('dashboardVisitsChart');
    if (!canvas || typeof Chart === 'undefined') return;

    const chart = new Chart(canvas, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Kunjungan',
                data: [],
                borderColor: '#0369A1',
                backgroundColor: 'rgba(3, 105, 161, 0.12)',
                fill: true,
                tension: 0.35,
                borderWidth: 2,
                pointRadius: 3,
                pointBackgroundColor: '#0369A1',
                pointHoverRadius: 5,
            }],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#0F172A',
                    titleColor: '#F8FAFC',
                    bodyColor: '#F8FAFC',
                    callbacks: {
                        label: function (item) {
                            return ' Kunjungan: ' + Number(item.parsed.y).toLocaleString('id-ID');
                        },
                    },
                },
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: { color: '#64748B', font: { family: 'Lato, sans-serif', size: 11 }, maxRotation: 45 },
                },
                y: {
                    beginAtZero: true,
                    grid: { color: 'rgba(148, 163, 184, 0.2)' },
                    ticks: { color: '#64748B', precision: 0, font: { family: 'Lato, sans-serif', size: 11 } },
                },
            },
        },
    });

    fetch('{{ url('admin/kunjungan/data') }}?periode=7d', { headers: { 'Accept': 'application/json' } })
        .then(function (r) { return r.json(); })
        .then(function (d) {
            chart.data.labels = d.labels;
            chart.data.datasets[0].data = d.values;
            chart.update();
        })
        .catch(function () {});
});
</script>
@endpush
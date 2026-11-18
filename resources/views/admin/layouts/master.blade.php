<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">
    <title>@yield('title', 'Admin') — Bappedalitbang Karo</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
    @stack('styles')
</head>
<body class="bg-slate-100 min-h-screen">
    <div class="min-h-screen flex" x-data="{ sidebarOpen: true }">
        {{-- Sidebar --}}
        <aside class="bg-primary w-64 shrink-0 min-h-screen hidden lg:flex flex-col">
            <div class="p-4 border-b border-slate-800">
                <a href="{{ url('/') }}" class="flex items-center gap-3">
                    <img src="{{ asset('uploads/Logo/LogoBappeda.png') }}" alt="Logo Bappeda Kabupaten Karo"
                         class="w-9 h-9 object-contain rounded-lg bg-white p-1 ring-1 ring-slate-700 shrink-0">
                    <div>
                        <div class="text-sm font-heading font-bold text-white leading-tight">Bappedalitbang</div>
                        <div class="text-[10px] text-slate-500">Admin Panel</div>
                    </div>
                </a>
            </div>
            <nav class="flex-1 p-3 space-y-1 overflow-y-auto">
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-3 py-2">Menu</p>
                <a href="{{ url('admin') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin') && !request()->is('admin/*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ url('admin/kunjungan') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/kunjungan*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                    Kunjungan
                </a>
                @if(\Auth::user()->isSuperadmin())
                <a href="{{ url('admin/persetujuan') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/persetujuan*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Persetujuan Konten
                </a>
                @endif
                @if(\Auth::user()->can('manage_kategori'))
                <a href="{{ url('admin/kategori') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/kategori*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/></svg>
                    Kategori
                </a>
                @endif
                @if(\Auth::user()->can('manage_artikel'))
                <a href="{{ url('admin/artikel') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/artikel*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    Artikel
                </a>
                @endif
                @php
                    $cmsModules = config('cms.modules');
                @endphp
                @if(\Auth::user()->can('manage_visi_misi') || \Auth::user()->can('manage_bidang') || \Auth::user()->can('manage_struktur') || \Auth::user()->can('manage_tugas_fungsi') || \Auth::user()->can('manage_pegawai') || \Auth::user()->can('manage_kepala') || \Auth::user()->can('manage_pengumuman') || \Auth::user()->can('manage_galeri') || \Auth::user()->can('manage_galeri_video') || \Auth::user()->can('manage_dokumen') || \Auth::user()->can('manage_faq') || \Auth::user()->can('manage_ticker'))
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-3 py-2 mt-4">Konten</p>
                @foreach($cmsModules as $moduleSlug => $moduleConfig)
                    @if(\Auth::user()->can('manage_' . $moduleSlug))
                    <a href="{{ url('admin/crud/' . $moduleSlug) }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/crud/' . $moduleSlug) ? 'bg-slate-800 text-white' : '' }}">
                        <i class="fa-solid {{ $moduleConfig['icon'] ?? 'fa-folder-open' }} w-4 text-center text-xs"></i>
                        {{ $moduleConfig['label_plural'] }}
                    </a>
                    @endif
                @endforeach
                @endif
                @if(\Auth::user()->can('manage_home_sections') || \Auth::user()->can('manage_menus') || \Auth::user()->can('manage_settings'))
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-3 py-2 mt-4">Tampilan</p>
                @if(\Auth::user()->can('manage_home_sections'))
                <a href="{{ url('admin/home-sections') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/home-sections*') ? 'bg-slate-800 text-white' : '' }}">
                    <i class="fa-solid fa-layer-group w-4 text-center text-xs"></i>
                    Section Halaman Depan
                </a>
                @endif
                @if(\Auth::user()->can('manage_menus'))
                <a href="{{ url('admin/menu') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/menu*') ? 'bg-slate-800 text-white' : '' }}">
                    <i class="fa-solid fa-bars-staggered w-4 text-center text-xs"></i>
                    Menu Navigasi
                </a>
                @endif
                @if(\Auth::user()->can('manage_settings'))
                <a href="{{ url('admin/settings') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/settings*') ? 'bg-slate-800 text-white' : '' }}">
                    <i class="fa-solid fa-sliders w-4 text-center text-xs"></i>
                    Pengaturan Situs
                </a>
                @endif
                @if(\Auth::user()->can('manage_settings'))
                <a href="{{ url('admin/alamat') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/alamat') ? 'bg-slate-800 text-white' : '' }}">
                    <i class="fa-solid fa-location-dot w-4 text-center text-xs"></i>
                    Alamat & Peta
                </a>
                @endif
                @endif
                @if(\Auth::user()->can('manage_komentar'))
                <a href="{{ url('admin/komentar') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/komentar*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Komentar
                </a>
                @endif
                @if(\Auth::user()->can('manage_users'))
                <a href="{{ url('admin/user') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors {{ request()->is('admin/user*') ? 'bg-slate-800 text-white' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/></svg>
                    Pengguna
                </a>
                @endif
                <p class="text-[10px] font-semibold text-slate-500 uppercase tracking-wider px-3 py-2 mt-4">Lainnya</p>
                <a href="{{ url('/keluar') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </a>
            </nav>
        </aside>

        {{-- Main Area --}}
        <div class="flex-1 min-w-0 flex flex-col min-h-screen">
            {{-- Top Bar --}}
            <header class="bg-white border-b border-border h-16 flex items-center px-4 md:px-6 sticky top-0 z-40">
                <div class="flex items-center justify-between w-full">
                    <div class="flex items-center gap-3">
                        <button id="sidebarToggle" class="lg:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                        </button>
                        <h1 class="text-lg font-heading font-bold text-primary">@yield('page-title', $title ?? 'Dashboard')</h1>
                    </div>
                    <div class="flex items-center gap-3">
                        <a href="{{ url('/') }}" class="text-xs text-slate-500 hover:text-accent transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                            Lihat Situs
                        </a>
                        <span class="text-xs text-slate-400">{{ \Auth::user()->name }}</span>
                    </div>
                </div>
            </header>

            {{-- Content --}}
            <main class="flex-1 p-4 md:p-6">
                @yield('content')
            </main>

            {{-- Footer --}}
            <footer class="bg-white border-t border-border px-6 py-4">
                <p class="text-xs text-slate-400 text-center">&copy; {{ date('Y') }} Bappedalitbang Kabupaten Karo. All rights reserved.</p>
            </footer>
        </div>
    </div>

    {{-- Mobile Sidebar --}}
    <div id="mobileSidebarOverlay" class="fixed inset-0 bg-black/50 z-50 hidden lg:hidden" onclick="toggleMobileSidebar()"></div>
    <div id="mobileSidebar" class="fixed inset-y-0 left-0 w-64 bg-primary z-50 transform -translate-x-full transition-transform duration-300 lg:hidden overflow-y-auto">
        <div class="p-4 border-b border-slate-800 flex items-center justify-between">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <img src="{{ asset('uploads/Logo/LogoBappeda.png') }}" alt="Logo Bappeda Kabupaten Karo"
                     class="w-9 h-9 object-contain rounded-lg bg-white p-1 ring-1 ring-slate-700 shrink-0">
                <div class="text-sm font-heading font-bold text-white leading-tight">Admin</div>
            </a>
            <button onclick="toggleMobileSidebar()" class="p-1 text-slate-400 hover:text-white">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <nav class="p-3 space-y-1">
            <a href="{{ url('admin') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg">Dashboard</a>
            <a href="{{ url('admin/kunjungan') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg">Kunjungan</a>
            @if(\Auth::user()->can('manage_kategori'))
            <a href="{{ url('admin/kategori') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg">Kategori</a>
            @endif
            @if(\Auth::user()->can('manage_artikel'))
            <a href="{{ url('admin/artikel') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg">Artikel</a>
            @endif
            @if(\Auth::user()->can('manage_komentar'))
            <a href="{{ url('admin/komentar') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg">Komentar</a>
            @endif
            @if(\Auth::user()->can('manage_users'))
            <a href="{{ url('admin/user') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-slate-300 hover:text-white hover:bg-slate-800 rounded-lg">Pengguna</a>
            @endif
            <a href="{{ url('/keluar') }}" class="flex items-center gap-3 px-3 py-2.5 text-sm text-red-400 hover:text-red-300 hover:bg-slate-800 rounded-lg">Logout</a>
        </nav>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script type="text/javascript" src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            const overlay = document.getElementById('mobileSidebarOverlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }
        document.getElementById('sidebarToggle')?.addEventListener('click', toggleMobileSidebar);
    </script>

    {{-- Toast Notifications --}}
    <div id="toastContainer" class="pointer-events-none fixed top-4 right-4 z-[110] flex w-full max-w-sm flex-col gap-3" aria-live="polite"></div>

    {{-- Confirm Modal --}}
    <div id="confirmModal" class="hidden fixed inset-0 z-[100] flex items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="confirmTitle">
        <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-sm" onclick="closeConfirmModal()"></div>
        <div class="relative w-full max-w-sm rounded-xl bg-white p-6 shadow-2xl">
            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-red-100">
                <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/></svg>
            </div>
            <h3 id="confirmTitle" class="mt-4 text-center text-base font-heading font-bold text-primary">Konfirmasi Hapus</h3>
            <p id="confirmMessage" class="mt-2 text-center text-sm text-slate-600"></p>
            <div class="mt-6 flex gap-3">
                <button type="button" id="confirmCancel" onclick="closeConfirmModal()" class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Batal</button>
                <button type="button" id="confirmSubmit" onclick="doConfirmAction()" class="flex-1 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white hover:bg-red-700 transition-colors">Ya, Hapus</button>
            </div>
        </div>
    </div>

    @php
        $flashData = [
            'sukses' => Session::get('sukses'),
            'gagal' => Session::get('gagal'),
            'flash_success' => Session::get('flash_success'),
            'flash_error' => Session::get('flash_error'),
        ];
    @endphp

    <script type="application/json" id="flashData">@json($flashData)</script>

    <script>
        function escapeHtml(str) {
            return String(str ?? '').replace(/[&<>"']/g, function (m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[m];
            });
        }

        function showToast(type, message) {
            const container = document.getElementById('toastContainer');
            if (!container || !message) return;
            const styles = {
                success: { title: 'Sukses', ring: 'border-green-200', text: 'text-green-800', icon: 'text-green-500', iconBg: 'bg-green-100',
                    path: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
                error: { title: 'Gagal', ring: 'border-red-200', text: 'text-red-800', icon: 'text-red-500', iconBg: 'bg-red-100',
                    path: '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>' },
            };
            const s = styles[type] || styles.success;
            const el = document.createElement('div');
            el.setAttribute('role', 'status');
            el.className = 'pointer-events-auto flex items-start gap-3 rounded-xl border bg-white p-4 shadow-lg shadow-slate-900/10 opacity-0 translate-y-2 transition-all duration-300 ' + s.ring;
            el.innerHTML = '' +
                '<div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full ' + s.iconBg + '">' +
                '<svg class="h-5 w-5 ' + s.icon + '" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">' + s.path + '</svg></div>' +
                '<div class="min-w-0 flex-1 pt-0.5">' +
                '<p class="text-sm font-semibold ' + s.text + '">' + s.title + '</p>' +
                '<p class="mt-0.5 text-sm text-slate-600 break-words">' + escapeHtml(message) + '</p></div>' +
                '<button type="button" onclick="this.parentElement.remove()" class="shrink-0 text-slate-400 hover:text-slate-600" aria-label="Tutup">' +
                '<svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>';
            container.appendChild(el);
            requestAnimationFrame(function () { el.classList.remove('opacity-0', 'translate-y-2'); });
            setTimeout(function () {
                el.classList.add('opacity-0', 'translate-y-2');
                setTimeout(function () { el.remove(); }, 300);
            }, 4500);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const flashEl = document.getElementById('flashData');
            if (!flashEl) return;
            const flashes = JSON.parse(flashEl.textContent || '{}');
            Object.entries(flashes).forEach(function ([key, msg]) {
                if (!msg) return;
                const type = (key === 'sukses' || key === 'flash_success') ? 'success' : 'error';
                showToast(type, msg);
            });
        });
    </script>

    <script>
        let confirmCallback = null;

        function confirmAction(message, target, actionLabel) {
            document.getElementById('confirmMessage').textContent = message;
            if (actionLabel) document.getElementById('confirmSubmit').textContent = actionLabel;
            confirmCallback = function () {
                if (target instanceof HTMLFormElement) target.submit();
                else if (typeof target === 'string') window.location.href = target;
            };
            document.getElementById('confirmModal').classList.remove('hidden');
            document.getElementById('confirmCancel').focus();
            return false;
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.add('hidden');
            confirmCallback = null;
        }

        function doConfirmAction() {
            const cb = confirmCallback;
            closeConfirmModal();
            if (cb) cb();
        }

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeConfirmModal();
        });
    </script>
    @stack('scripts')

    @include('components.lightbox')
</body>
</html>

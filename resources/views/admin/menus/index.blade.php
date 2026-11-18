@extends('admin.layouts.master')

@section('title', 'Menu Navigasi — Admin')
@section('page-title', 'Menu Navigasi')

@section('content')
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div>
            <div class="bg-white rounded-xl border border-border p-5">
                <h3 class="font-heading font-bold text-primary mb-4">Buat Menu Baru</h3>
                <form action="{{ route('admin.menus.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Menu</label>
                        <input type="text" name="name" required class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm focus:ring-2 focus:ring-accent focus:border-accent">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Slug <span class="text-xs text-slate-400">(opsional)</span></label>
                        <input type="text" name="slug" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1.5">Lokasi <span class="text-xs text-slate-400">(opsional)</span></label>
                        <input type="text" name="location" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    </div>
                    <button type="submit" class="w-full btn-sm">Simpan</button>
                </form>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-border overflow-hidden">
                <div class="px-5 py-4 border-b border-slate-100 bg-slate-50">
                    <h3 class="font-heading font-bold text-primary">Daftar Menu</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse($menus as $menu)
                        <div class="px-5 py-4 flex items-center justify-between gap-3 hover:bg-slate-50">
                            <div>
                                <p class="text-sm font-semibold text-slate-800">{{ $menu->name }}</p>
                                <p class="text-xs text-slate-400">slug: {{ $menu->slug }} Â· lokasi: {{ $menu->location }} Â· {{ $menu->items_count }} item</p>
                            </div>
                            <a href="{{ route('admin.menus.manage', $menu->id) }}"
                               class="inline-flex items-center gap-1 text-xs font-semibold text-accent hover:underline">
                                Kelola Item
                            </a>
                        </div>
                    @empty
                        <p class="px-5 py-10 text-center text-sm text-slate-400">Belum ada menu. Buat menu pertama Anda.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
@endsection

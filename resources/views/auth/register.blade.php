@extends('layouts.app')
@section('title', 'Register')
@section('content')
<h2 class="text-2xl font-heading font-bold text-primary text-center mb-2">Daftar Akun Baru</h2>
<p class="text-sm text-slate-500 text-center mb-6">Silakan isi data untuk mendaftar</p>

<form method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <div class="mb-4">
        <label class="block text-xs font-medium text-slate-700 mb-1.5">Nama Lengkap</label>
        <input type="text" name="name" value="{{ old('name') }}" required autofocus
            class="input-field @error('name') ring-2 ring-red-500 border-red-500 @enderror"
            placeholder="Nama Anda">
        @error('name')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block text-xs font-medium text-slate-700 mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
            class="input-field @error('email') ring-2 ring-red-500 border-red-500 @enderror"
            placeholder="email@example.com">
        @error('email')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-4">
        <label class="block text-xs font-medium text-slate-700 mb-1.5">Password</label>
        <input type="password" name="password" required
            class="input-field @error('password') ring-2 ring-red-500 border-red-500 @enderror"
            placeholder="Minimal 8 karakter">
        @error('password')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label class="block text-xs font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required
            class="input-field"
            placeholder="Ulangi password">
    </div>

    <button type="submit" class="w-full btn-primary justify-center text-base py-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/></svg>
        Daftar
    </button>

    <p class="text-xs text-slate-500 text-center mt-4">
        Sudah punya akun? <a href="{{ route('login') }}" class="text-accent hover:underline">Masuk</a>
    </p>
</form>
@endsection

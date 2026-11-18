@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<h2 class="text-2xl font-heading font-bold text-primary text-center mb-2">Reset Password</h2>
<p class="text-sm text-slate-500 text-center mb-6">Buat password baru untuk akun Anda</p>

<form method="POST" action="{{ route('password.request') }}">
    {{ csrf_field() }}
    <input type="hidden" name="token" value="{{ $token }}">

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
        <label class="block text-xs font-medium text-slate-700 mb-1.5">Password Baru</label>
        <input type="password" name="password" required
            class="input-field @error('password') ring-2 ring-red-500 border-red-500 @enderror"
            placeholder="Minimal 8 karakter">
        @error('password')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="mb-6">
        <label class="block text-xs font-medium text-slate-700 mb-1.5">Konfirmasi Password</label>
        <input type="password" name="password_confirmation" required class="input-field" placeholder="Ulangi password">
    </div>

    <button type="submit" class="w-full btn-primary justify-center text-base py-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        Reset Password
    </button>
</form>
@endsection

@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<h2 class="text-2xl font-heading font-bold text-primary text-center mb-2">Lupa Password?</h2>
<p class="text-sm text-slate-500 text-center mb-6">Masukkan email Anda untuk menerima link reset password</p>

@if (session('status'))
<div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-sm text-green-700">{{ session('status') }}</div>
@endif

<form method="POST" action="{{ route('password.email') }}">
    {{ csrf_field() }}

    <div class="mb-6">
        <label class="block text-xs font-medium text-slate-700 mb-1.5">Email</label>
        <input type="email" name="email" value="{{ old('email') }}" required
            class="input-field @error('email') ring-2 ring-red-500 border-red-500 @enderror"
            placeholder="email@example.com">
        @error('email')
        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
        @enderror
    </div>

    <button type="submit" class="w-full btn-primary justify-center text-base py-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        Kirim Link Reset
    </button>

    <p class="text-xs text-slate-500 text-center mt-4">
        <a href="{{ route('login') }}" class="text-accent hover:underline">Kembali ke Login</a>
    </p>
</form>
@endsection

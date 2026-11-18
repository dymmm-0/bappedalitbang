@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="auth-head">
    <span class="auth-eyebrow">Portal Resmi Pemerintah</span>
    <h2 class="auth-title">Selamat Datang</h2>
    <p class="auth-sub">Silakan masuk ke akun admin Anda</p>
</div>

<form method="POST" action="{{ route('login') }}" class="auth-form">
    {{ csrf_field() }}

    @if($errors->any())
    <div class="auth-alert" role="alert">
        @foreach($errors->all() as $e)
        <p>{{ $e }}</p>
        @endforeach
    </div>
    @endif

    <div class="inputbox">
        <svg class="inputbox-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        <input type="email" name="email" value="{{ old('email') }}" required autofocus>
        <label>Email</label>
    </div>

    <div class="inputbox">
        <svg class="inputbox-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        <input type="password" name="password" required>
        <label>Password</label>
    </div>

    <div class="forget">
        <label class="forget-check"><input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Ingat saya</label>
    </div>

    <button type="submit" class="auth-btn">Masuk</button>

    <div class="auth-foot">
        <a href="{{ url('/') }}">Kembali ke Beranda</a>
    </div>
</form>
@endsection
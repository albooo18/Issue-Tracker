@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <section class="mx-auto grid max-w-5xl gap-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-stretch">
        <div class="auth-hero">
            <p class="auth-hero__label">Access</p>
            <h1 class="auth-hero__title">Welcome back.</h1>
            <p class="auth-hero__copy">Sign in to manage project ownership, protected edits, and team assignments from one secure workspace.</p>
            <div class="mt-6 rounded-[1.5rem] bg-slate-950/70 p-5 text-sm text-slate-300 border border-white/10">
                <p class="font-semibold text-white">Why sign in?</p>
                <ul class="mt-4 space-y-3 text-slate-400">
                    <li>Manage projects and issues in one place.</li>
                    <li>Keep your edits secure with authentication.</li>
                    <li>Track team activity and comments effortlessly.</li>
                </ul>
            </div>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-6 shadow-xl shadow-slate-950/20 backdrop-blur lg:p-8">
            <form class="space-y-4" method="POST" action="{{ route('login.store') }}">
                @csrf
                <label class="field">
                    <span class="field-label">Email</span>
                    <input class="control" type="email" name="email" value="{{ old('email') }}" placeholder="owner@example.com" required>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="field">
                    <span class="field-label">Password</span>
                    <input class="control" type="password" name="password" placeholder="password" required>
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="flex items-center gap-3 rounded-2xl border border-white/10 bg-white/5 px-4 py-3 text-sm text-slate-300">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    <span>Remember me</span>
                </label>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button class="btn btn-primary" type="submit">Sign in</button>
                    <a class="btn btn-secondary" href="{{ route('register') }}">Create account</a>
                </div>
            </form>
        </div>
    </section>
@endsection

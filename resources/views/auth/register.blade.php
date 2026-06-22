@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <section class="mx-auto grid max-w-5xl gap-6 lg:grid-cols-[0.9fr_1.1fr] lg:items-stretch">
        <div class="auth-hero">
            <p class="auth-hero__label">Access</p>
            <h1 class="auth-hero__title">Join the workspace.</h1>
            <p class="auth-hero__copy">Create an account to manage projects, protected edits, and team assignments from one secure dashboard.</p>
            <div class="mt-6 rounded-[1.5rem] bg-slate-950/70 p-5 text-sm text-slate-300 border border-white/10">
                <p class="font-semibold text-white">What you get</p>
                <ul class="mt-4 space-y-3 text-slate-400">
                    <li>Secure access to your projects and issues.</li>
                    <li>Protected updates for your workflows.</li>
                    <li>Team ownership, comments, and tracking.</li>
                </ul>
            </div>
        </div>

        <div class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-6 shadow-xl shadow-slate-950/20 backdrop-blur lg:p-8">
            <form class="space-y-4" method="POST" action="{{ route('register.store') }}">
                @csrf
                <label class="field">
                    <span class="field-label">Name</span>
                    <input class="control" type="text" name="name" value="{{ old('name') }}" placeholder="Alex Rivera" required>
                    @error('name')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="field">
                    <span class="field-label">Email</span>
                    <input class="control" type="email" name="email" value="{{ old('email') }}" placeholder="alex@example.com" required>
                    @error('email')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="field">
                    <span class="field-label">Password</span>
                    <input class="control" type="password" name="password" required>
                    @error('password')<p class="field-error">{{ $message }}</p>@enderror
                </label>

                <label class="field">
                    <span class="field-label">Confirm password</span>
                    <input class="control" type="password" name="password_confirmation" required>
                </label>

                <div class="flex flex-wrap gap-3 pt-2">
                    <button class="btn btn-primary" type="submit">Create account</button>
                    <a class="btn btn-secondary" href="{{ route('login') }}">Back to login</a>
                </div>
            </form>
        </div>
    </section>
@endsection

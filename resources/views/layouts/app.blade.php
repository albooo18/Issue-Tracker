<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Issue Tracker'))</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="relative min-h-screen overflow-x-hidden bg-slate-950 text-slate-100">
    <div class="pointer-events-none fixed inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(59,130,246,0.28),_transparent_30%),radial-gradient(circle_at_top_right,_rgba(14,165,233,0.18),_transparent_24%),linear-gradient(180deg,_rgba(15,23,42,1)_0%,_rgba(2,6,23,1)_100%)]"></div>
    <div class="pointer-events-none fixed inset-0 opacity-40 [background-image:linear-gradient(rgba(148,163,184,0.08)_1px,transparent_1px),linear-gradient(90deg,rgba(148,163,184,0.08)_1px,transparent_1px)] [background-size:56px_56px] [mask-image:radial-gradient(circle_at_center,black,transparent_78%)]"></div>

    <div class="relative z-10 mx-auto flex min-h-screen w-full max-w-7xl flex-col gap-6 px-4 py-6 lg:px-8">
        <header class="rounded-[2rem] border border-white/10 bg-slate-900/70 px-5 py-4 shadow-[0_30px_80px_rgba(2,6,23,0.45)] backdrop-blur-xl lg:px-6">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="space-y-2">
                    <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Pritechr</p>
                    <a href="{{ route('projects.index') }}" class="block text-3xl font-black tracking-[-0.06em] text-white lg:text-4xl">{{ config('app.name', 'Issue Tracker') }}</a>
                    <p class="max-w-2xl text-sm text-slate-400">Projects, issues, tags, members, and comments in one clear dashboard.</p>
                </div>

                <div class="flex flex-col gap-3 lg:items-end">
                    <nav class="flex flex-wrap gap-2">
                        <a class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-slate-200 transition hover:-translate-y-0.5 hover:border-sky-400/50 hover:bg-slate-800 {{ request()->routeIs('projects.*') ? 'border-sky-400/60 bg-sky-500/10 text-white' : '' }}" href="{{ route('projects.index') }}">Projects</a>
                        <a class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-slate-200 transition hover:-translate-y-0.5 hover:border-sky-400/50 hover:bg-slate-800 {{ request()->routeIs('issues.*') ? 'border-sky-400/60 bg-sky-500/10 text-white' : '' }}" href="{{ route('issues.index') }}">Issues</a>
                        <a class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-slate-200 transition hover:-translate-y-0.5 hover:border-sky-400/50 hover:bg-slate-800 {{ request()->routeIs('tags.*') ? 'border-sky-400/60 bg-sky-500/10 text-white' : '' }}" href="{{ route('tags.index') }}">Tags</a>
                    </nav>

                    @auth
                        <div class="flex items-center gap-3 rounded-full border border-white/10 bg-slate-800/70 px-3 py-2 text-sm text-slate-200 shadow-lg shadow-slate-950/20">
                            <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-gradient-to-br from-sky-400 to-blue-600 text-sm font-black text-white">{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}</span>
                            <span class="hidden font-semibold sm:inline">{{ auth()->user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="rounded-full border border-white/10 bg-white/5 px-3 py-1.5 text-xs font-bold text-slate-100 transition hover:bg-white/10" type="submit">Logout</button>
                            </form>
                        </div>
                    @else
                        <div class="auth-split">
                            <div class="auth-split__icon">👤</div>
                            <div class="auth-split__menu">
                                <a class="auth-split__item" href="{{ route('login') }}">Login</a>
                                <a class="auth-split__item auth-split__item--secondary" href="{{ route('register') }}">Register</a>
                            </div>
                        </div>
                    @endauth
                </div>
            </div>
        </header>

        @include('partials.alerts')

        <main class="flex-1 space-y-6 pb-6">
            @yield('content')
        </main>
    </div>
</body>
</html>

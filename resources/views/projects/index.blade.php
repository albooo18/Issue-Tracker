@extends('layouts.app')

@section('title', 'Projects')

@section('content')
    <section class="overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-900/90 via-slate-900/80 to-slate-800/70 p-6 shadow-[0_25px_70px_rgba(2,6,23,0.35)] backdrop-blur-xl lg:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.6fr_0.9fr] lg:items-end">
            <div class="space-y-4">
                <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Overview</p>
                <h1 class="text-4xl font-black tracking-[-0.06em] text-white lg:text-6xl">Projects</h1>
                <p class="max-w-2xl text-sm text-slate-400 lg:text-base">Track workstreams, active issues, owners, deadlines, and delivery health from one focused dashboard.</p>
            </div>

            <div class="flex flex-col gap-3 rounded-[1.75rem] border border-white/10 bg-white/5 p-4 text-sm text-slate-200">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Total projects</span>
                    <span class="text-xl font-black text-white">{{ $projects->total() }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Open workstreams</span>
                    <span class="text-xl font-black text-sky-300">{{ $projects->where('issues_count', '>', 0)->count() }}</span>
                </div>
                <a class="mt-2 inline-flex items-center justify-center rounded-full bg-sky-500 px-4 py-3 text-sm font-bold text-white shadow-lg shadow-sky-500/20 transition hover:-translate-y-0.5 hover:bg-sky-400" href="{{ route('projects.create') }}">New project</a>
            </div>
        </div>
    </section>

    <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-3">
        @forelse ($projects as $project)
            <article class="group overflow-hidden rounded-[1.75rem] border border-white/10 bg-slate-900/70 p-5 shadow-[0_20px_60px_rgba(2,6,23,0.25)] transition duration-300 hover:-translate-y-1 hover:border-sky-400/40 hover:bg-slate-900/90">
                <div class="space-y-4">
                    <div class="flex items-start justify-between gap-3">
                        <div class="space-y-2">
                            <span class="inline-flex rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-[0.7rem] font-black uppercase tracking-[0.24em] text-emerald-300">{{ $project->issues_count }} issues</span>
                            <h2 class="text-xl font-black tracking-[-0.04em] text-white">{{ $project->name }}</h2>
                        </div>
                        <div class="rounded-2xl border border-white/10 bg-white/5 px-3 py-2 text-right text-xs text-slate-400">
                            <div class="font-bold text-slate-200">Start</div>
                            <div>{{ optional($project->start_date)->format('M j') ?? 'Unset' }}</div>
                        </div>
                    </div>

                    <p class="line-clamp-3 text-sm leading-6 text-slate-400">{{ $project->description ?: 'No project description yet.' }}</p>

                    <div class="flex flex-wrap gap-2 text-xs text-slate-300">
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Owner: {{ $project->owner?->name ?? 'Unassigned' }}</span>
                        <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Deadline: {{ optional($project->deadline)->format('M j, Y') ?? 'Unset' }}</span>
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <a class="inline-flex flex-1 items-center justify-center rounded-full bg-white px-4 py-3 text-sm font-bold text-slate-950 transition hover:-translate-y-0.5 hover:bg-slate-100" href="{{ route('projects.show', $project) }}">View</a>
                        @can('update', $project)
                            <a class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/10" href="{{ route('projects.edit', $project) }}">Edit</a>
                        @endcan
                    </div>
                </div>
            </article>
        @empty
            <div class="rounded-[1.75rem] border border-dashed border-white/15 bg-white/5 p-10 text-center md:col-span-2 xl:col-span-3">
                <h2 class="text-2xl font-black tracking-[-0.04em] text-white">No projects yet</h2>
                <p class="mt-2 text-sm text-slate-400">Create the first project to start tracking issues and deadlines.</p>
                <a class="mt-6 inline-flex rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white transition hover:bg-sky-400" href="{{ route('projects.create') }}">Create project</a>
            </div>
        @endforelse
    </section>

    <div class="pagination-shell">
        {{ $projects->links() }}
    </div>
@endsection

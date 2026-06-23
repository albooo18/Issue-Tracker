@extends('layouts.app')

@section('title', $project->name)

@section('content')
    <section class="overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-900/90 via-slate-900/80 to-slate-800/70 p-6 shadow-[0_25px_70px_rgba(2,6,23,0.35)] backdrop-blur-xl lg:p-8">
        <div class="grid gap-6 lg:grid-cols-[1.7fr_0.9fr] lg:items-end">
            <div class="space-y-4">
                <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Project detail</p>
                <h1 class="text-4xl font-black tracking-[-0.06em] text-white lg:text-6xl">{{ $project->name }}</h1>
                <p class="max-w-3xl text-sm leading-7 text-slate-400 lg:text-base">{{ $project->description ?: 'No project description yet.' }}</p>
                <div class="flex flex-wrap gap-2 text-xs text-slate-300">
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Owner: {{ $project->owner?->name ?? 'Unassigned' }}</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Start: {{ optional($project->start_date)->format('M j, Y') ?? 'Unset' }}</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Deadline: {{ optional($project->deadline)->format('M j, Y') ?? 'Unset' }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-3 rounded-[1.75rem] border border-white/10 bg-white/5 p-4">
                <div class="flex items-center justify-between text-sm text-slate-300">
                    <span>Issues</span>
                    <span class="text-2xl font-black text-white">{{ $project->issues->count() }}</span>
                </div>
                <a class="inline-flex items-center justify-center rounded-full bg-sky-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-sky-400" href="{{ route('issues.create', ['project_id' => $project->id]) }}">New issue</a>
                @can('update', $project)
                    <div class="grid grid-cols-2 gap-2">
                        <a class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/10" href="{{ route('projects.edit', $project) }}">Edit</a>
                        <form method="POST" action="{{ route('projects.destroy', $project) }}" onsubmit="return confirm('Delete this project?')">
                            @csrf
                            @method('DELETE')
                            <button class="inline-flex w-full items-center justify-center rounded-full border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm font-bold text-rose-200 transition hover:bg-rose-500/20" type="submit">Delete</button>
                        </form>
                    </div>
                @endcan
            </div>
        </div>
    </section>

    <section class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_rgba(2,6,23,0.2)] backdrop-blur-xl lg:p-8">
        <div class="mb-5 flex items-center justify-between gap-3">
            <div>
                <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Issues</p>
                <h2 class="text-2xl font-black tracking-[-0.04em] text-white">Project issues</h2>
            </div>
            <span class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-black uppercase tracking-[0.24em] text-emerald-300">{{ $project->issues->count() }} total</span>
        </div>

        <div class="grid gap-4 xl:grid-cols-2">
            @forelse ($project->issues as $issue)
                @include('issues.partials.card', ['issue' => $issue, 'showProject' => false])
            @empty
                <div class="rounded-[1.5rem] border border-dashed border-white/15 bg-white/5 p-8 text-center xl:col-span-2">
                    <div class="text-lg font-bold text-white">No issues have been added to this project yet.</div>
                    <p class="mt-2 text-sm text-slate-400">Create the first issue to get the team moving.</p>
                </div>
            @endforelse
        </div>
    </section>
@endsection

@extends('layouts.app')

@section('title', 'Edit Project')

@section('content')
    <section class="overflow-hidden rounded-[2rem] border border-white/10 bg-slate-900/70 p-6 shadow-xl shadow-slate-950/20 backdrop-blur lg:p-8">
        <div class="flex items-end justify-between gap-4">
            <div class="space-y-2">
                <p class="text-[0.7rem] font-bold uppercase tracking-[0.28em] text-sky-200">Projects</p>
                <h1 class="text-3xl font-black tracking-tight text-white sm:text-4xl">Edit project</h1>
            </div>
            <a class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-white transition hover:bg-white/10" href="{{ route('projects.show', $project) }}">Back</a>
        </div>

        <div class="mt-6 rounded-[1.75rem] border border-white/10 bg-white/5 p-5 lg:p-6">
            @include('projects._form', [
                'project' => $project,
                'action' => route('projects.update', $project),
                'method' => 'PUT',
                'button' => 'Update project',
            ])
        </div>
    </section>
@endsection

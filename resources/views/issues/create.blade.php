@extends('layouts.app')

@section('title', 'Create Issue')

@section('content')
    <section class="panel panel-content">
        <div class="section-head">
            <div>
                <p class="eyebrow">Issues</p>
                <h1 class="panel-title">Create issue</h1>
                <p class="subtle">Describe your issue, choose a project, and tag it for fast team collaboration.</p>
            </div>
            <a class="btn btn-secondary" href="{{ route('issues.index') }}">Back</a>
        </div>

        <div class="stack">
            <div class="grid gap-4 lg:grid-cols-[1fr_0.9fr] lg:items-start">
                <div class="panel rounded-[1.5rem] border border-white/10 bg-slate-900/70 p-5">
                    @include('issues._form', [
                        'issue' => $issue,
                        'projects' => $projects,
                        'tags' => $tags,
                        'action' => route('issues.store'),
                        'method' => 'POST',
                        'button' => 'Save issue',
                    ])
                </div>
                <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 text-sm text-slate-300">
                    <p class="font-semibold text-slate-100">Issue creation tips</p>
                    <ul class="mt-4 space-y-3 text-slate-400">
                        <li>Choose the most relevant project for this issue.</li>
                        <li>Use clear and concise language in the title.</li>
                        <li>Assign tags to improve search and filtering.</li>
                        <li>Set a deadline only if it helps track delivery.</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
@endsection

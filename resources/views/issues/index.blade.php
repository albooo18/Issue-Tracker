@extends('layouts.app')

@section('title', 'Issues')

@section('content')
    <section class="overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-900/90 via-slate-900/80 to-slate-800/70 p-6 shadow-[0_25px_70px_rgba(2,6,23,0.35)] backdrop-blur-xl lg:p-8" data-issues-page data-search-url="{{ route('issues.search') }}">
        <div class="grid gap-6 lg:grid-cols-[1.6fr_0.8fr] lg:items-end">
            <div class="space-y-4">
                <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Workflow</p>
                <h1 class="text-4xl font-black tracking-[-0.06em] text-white lg:text-6xl">Issues</h1>
                <p class="max-w-2xl text-sm text-slate-400 lg:text-base">Filter by status, priority, tag, or search text. Results update live so you can move fast without leaving the page.</p>
            </div>
            <a class="inline-flex items-center justify-center rounded-full bg-sky-500 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-sky-500/20 transition hover:-translate-y-0.5 hover:bg-sky-400" href="{{ route('issues.create') }}">New issue</a>
        </div>

        <form class="mt-6 grid gap-4 rounded-[1.75rem] border border-white/10 bg-white/5 p-4 lg:grid-cols-4" method="GET" action="{{ route('issues.index') }}" data-search-form>
            <label class="field lg:col-span-4">
                <span class="field-label">Search</span>
                <input class="control" type="search" name="q" value="{{ request('q') }}" placeholder="Search title or description">
            </label>

            <label class="field">
                <span class="field-label">Status</span>
                <select class="control control-select" name="status">
                    <option value="">All</option>
                    @foreach (['open', 'in_progress', 'closed'] as $status)
                        <option value="{{ $status }}" @selected(request('status') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                <span class="field-label">Priority</span>
                <select class="control control-select" name="priority">
                    <option value="">All</option>
                    @foreach (['low', 'medium', 'high'] as $priority)
                        <option value="{{ $priority }}" @selected(request('priority') === $priority)>{{ $priority }}</option>
                    @endforeach
                </select>
            </label>

            <label class="field">
                <span class="field-label">Tag</span>
                <select class="control control-select" name="tag_id">
                    <option value="">All</option>
                    @foreach ($tags as $tag)
                        <option value="{{ $tag->id }}" @selected((string) request('tag_id') === (string) $tag->id)>{{ $tag->name }}</option>
                    @endforeach
                </select>
            </label>

            <div class="flex items-end gap-2">
                <button class="inline-flex flex-1 items-center justify-center rounded-full bg-white px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-slate-100" type="submit">Apply</button>
                <a class="inline-flex items-center justify-center rounded-full border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold text-slate-100 transition hover:bg-white/10" href="{{ route('issues.index') }}">Reset</a>
            </div>
        </form>
    </section>

    <section class="space-y-4" data-issues-results>
        @include('issues.partials.cards', ['issues' => $issues, 'showProject' => true])
    </section>

    <div class="pagination-shell rounded-[1.5rem] border border-white/10 bg-slate-900/70 px-4 py-3 backdrop-blur-xl" data-issues-pagination>
        @if ($issues instanceof \Illuminate\Pagination\AbstractPaginator)
            {{ $issues->links() }}
        @endif
    </div>
@endsection

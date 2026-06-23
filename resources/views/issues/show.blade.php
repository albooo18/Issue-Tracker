@extends('layouts.app')

@section('title', $issue->title)

@section('content')
    <section class="overflow-hidden rounded-[2rem] border border-white/10 bg-gradient-to-br from-slate-900/90 via-slate-900/80 to-slate-800/70 p-6 shadow-[0_25px_70px_rgba(2,6,23,0.35)] backdrop-blur-xl lg:p-8" data-issue-page data-comments-url="{{ route('issues.comments.index', $issue) }}">
        <div class="grid gap-6 lg:grid-cols-[1.8fr_0.9fr] lg:items-end">
            <div class="space-y-4">
                <div class="flex flex-wrap gap-2">
                    <span class="badge badge-{{ $issue->status }}">{{ str_replace('_', ' ', $issue->status) }}</span>
                    <span class="badge badge-{{ $issue->priority }}">{{ $issue->priority }}</span>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-black uppercase tracking-[0.24em] text-slate-300">{{ $issue->comments_count }} comments</span>
                </div>
                <h1 class="text-4xl font-black tracking-[-0.06em] text-white lg:text-6xl">{{ $issue->title }}</h1>
                <p class="max-w-3xl text-sm leading-7 text-slate-400 lg:text-base">{{ $issue->description ?: 'No issue description yet.' }}</p>
                <div class="flex flex-wrap gap-2 text-xs text-slate-300">
                    <a class="rounded-full border border-white/10 bg-white/5 px-3 py-1 hover:bg-white/10" href="{{ route('projects.show', $issue->project) }}">Project: {{ $issue->project->name }}</a>
                    <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1">Due: {{ optional($issue->due_date)->format('M j, Y') ?? 'Unset' }}</span>
                </div>
            </div>

            <div class="flex flex-col gap-3 rounded-[1.75rem] border border-white/10 bg-white/5 p-4">
                <a class="inline-flex items-center justify-center rounded-full bg-white px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-slate-100" href="{{ route('issues.edit', $issue) }}">Edit issue</a>
                <form method="POST" action="{{ route('issues.destroy', $issue) }}" onsubmit="return confirm('Delete this issue?')">
                    @csrf
                    @method('DELETE')
                    <button class="inline-flex w-full items-center justify-center rounded-full border border-rose-500/30 bg-rose-500/10 px-4 py-3 text-sm font-bold text-rose-200 transition hover:bg-rose-500/20" type="submit">Delete issue</button>
                </form>
            </div>
        </div>
    </section>

    <div class="grid gap-4 xl:grid-cols-[0.95fr_0.95fr_1.2fr]">
        <section class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_rgba(2,6,23,0.2)] backdrop-blur-xl">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Tags</p>
                    <h2 class="text-2xl font-black tracking-[-0.04em] text-white">Manage tags</h2>
                </div>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-black uppercase tracking-[0.24em] text-slate-300">Live</span>
            </div>

            <div class="space-y-4" data-tags-wrapper>
                @include('issues.partials.tags', ['issue' => $issue])
            </div>

            <form class="mt-5 space-y-4 rounded-[1.5rem] border border-white/10 bg-white/5 p-4" data-tag-form data-tag-url="{{ route('issues.tags.store', $issue) }}">
                @csrf
                <label class="field">
                    <span class="field-label">Attach tag</span>
                    <select class="control control-select" name="tag_id" required>
                        <option value="">Choose a tag</option>
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}">{{ $tag->name }}</option>
                        @endforeach
                    </select>
                </label>
                <button class="inline-flex w-full items-center justify-center rounded-full bg-white px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-slate-100" type="submit">Attach tag</button>
                <p class="field-hint" data-tags-status></p>
            </form>
        </section>

        <section class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_rgba(2,6,23,0.2)] backdrop-blur-xl">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Members</p>
                    <h2 class="text-2xl font-black tracking-[-0.04em] text-white">Assigned users</h2>
                </div>
                <span class="rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1 text-xs font-black uppercase tracking-[0.24em] text-emerald-300">{{ $issue->users->count() }} active</span>
            </div>

            <div class="space-y-4" data-members-wrapper>
                @include('issues.partials.members', ['issue' => $issue])
            </div>

            @auth
                <form class="mt-5 space-y-4 rounded-[1.5rem] border border-white/10 bg-white/5 p-4" data-member-form data-member-url="{{ route('issues.members.store', $issue) }}">
                    @csrf
                    <label class="field">
                        <span class="field-label">Assign user</span>
                        <select class="control control-select" name="user_id" required>
                            <option value="">Choose a user</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </label>
                    <button class="inline-flex w-full items-center justify-center rounded-full bg-white px-4 py-3 text-sm font-bold text-slate-950 transition hover:bg-slate-100" type="submit">Assign member</button>
                    <p class="field-hint" data-members-status></p>
                </form>
            @else
                <p class="rounded-[1.25rem] border border-white/10 bg-white/5 p-4 text-sm text-slate-300">Log in to attach or detach issue members.</p>
            @endauth
        </section>

        <section class="rounded-[2rem] border border-white/10 bg-slate-900/70 p-6 shadow-[0_20px_60px_rgba(2,6,23,0.2)] backdrop-blur-xl">
            <div class="mb-4 flex items-center justify-between gap-3">
                <div>
                    <p class="text-[0.7rem] font-black uppercase tracking-[0.35em] text-sky-300">Comments</p>
                    <h2 class="text-2xl font-black tracking-[-0.04em] text-white">Discussion</h2>
                </div>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-black uppercase tracking-[0.24em] text-slate-300">AJAX</span>
            </div>

            <div class="space-y-4">
                <div class="text-sm text-slate-400" data-comments-status></div>
                <div class="space-y-3" data-comments-list>
                    <div class="rounded-[1.5rem] border border-dashed border-white/10 bg-white/5 p-6 text-sm text-slate-400">Loading comments...</div>
                </div>
                <div class="pager rounded-[1.25rem] border border-white/10 bg-white/5 px-4 py-3" data-comments-pagination></div>
            </div>

            <form class="mt-5 space-y-4 rounded-[1.5rem] border border-white/10 bg-white/5 p-4" data-comment-form data-comment-url="{{ route('issues.comments.store', $issue) }}">
                @csrf
                <label class="field">
                    <span class="field-label">Author name</span>
                    <input class="control" type="text" name="author_name" placeholder="Alex" required>
                </label>

                <label class="field">
                    <span class="field-label">Comment</span>
                    <textarea class="control control-textarea" name="body" rows="4" placeholder="Leave a comment" required></textarea>
                </label>

                <button class="inline-flex w-full items-center justify-center rounded-full bg-sky-500 px-4 py-3 text-sm font-bold text-white transition hover:bg-sky-400" type="submit">Add comment</button>
            </form>
        </section>
    </div>
@endsection

<article class="group overflow-hidden rounded-[1.75rem] border border-white/10 bg-white/5 p-5 shadow-xl shadow-slate-950/20 backdrop-blur transition hover:-translate-y-1 hover:border-sky-400/30 hover:bg-white/8">
    <div class="flex flex-wrap items-center gap-2">
        <span class="badge badge-{{ $issue->status }}">{{ str_replace('_', ' ', $issue->status) }}</span>
        <span class="badge badge-{{ $issue->priority }}">{{ $issue->priority }}</span>
        <span class="rounded-full border border-white/10 bg-slate-950/40 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-300">{{ $issue->comments_count ?? 0 }} comments</span>
    </div>

    <div class="mt-4 space-y-3">
        <h3 class="text-xl font-black tracking-tight text-white">
            <a class="transition hover:text-sky-300" href="{{ route('issues.show', $issue) }}">{{ $issue->title }}</a>
        </h3>

        @if (($showProject ?? true) && $issue->project)
            <p class="text-sm text-slate-400">Project: <a class="font-semibold text-slate-200 hover:text-sky-300" href="{{ route('projects.show', $issue->project) }}">{{ $issue->project->name }}</a></p>
        @endif

        <p class="text-sm leading-6 text-slate-400">{{ $issue->description ?: 'No issue description yet.' }}</p>

        <div class="flex flex-wrap gap-2">
            @forelse ($issue->tags as $tag)
                <span class="tag-pill" style="--tag-color: {{ $tag->color ?: '#4f46e5' }}">{{ $tag->name }}</span>
            @empty
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-bold uppercase tracking-[0.2em] text-slate-400">No tags</span>
            @endforelse
        </div>

        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-400">Due: {{ optional($issue->due_date)->format('M j, Y') ?? 'Unset' }}</p>

        <div class="flex flex-wrap gap-2 pt-2">
            <a class="rounded-full bg-white px-4 py-2 text-sm font-bold text-slate-950 transition hover:bg-slate-100" href="{{ route('issues.show', $issue) }}">View</a>
            <a class="rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-bold text-white transition hover:bg-white/10" href="{{ route('issues.edit', $issue) }}">Edit</a>
            <form method="POST" action="{{ route('issues.destroy', $issue) }}" onsubmit="return confirm('Delete this issue?')">
                @csrf
                @method('DELETE')
                <button class="rounded-full border border-rose-500/30 bg-rose-500/10 px-4 py-2 text-sm font-bold text-rose-200 transition hover:bg-rose-500/20" type="submit">Delete</button>
            </form>
        </div>
    </div>
</article>

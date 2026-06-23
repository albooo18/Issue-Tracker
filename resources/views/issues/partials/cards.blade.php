@forelse ($issues as $issue)
    @include('issues.partials.card', ['issue' => $issue, 'showProject' => $showProject ?? true])
@empty
    <div class="panel empty-state">
        <h2 class="panel-title">No issues found</h2>
        <p class="subtle">Try clearing the filters or create the first issue.</p>
        <a class="btn btn-primary" href="{{ route('issues.create') }}">Create issue</a>
    </div>
@endforelse

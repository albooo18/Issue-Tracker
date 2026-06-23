@php
    $selectedTagIds = old('tag_ids', $issue->exists ? $issue->tags->pluck('id')->all() : []);
@endphp

<form class="space-y-5" method="POST" action="{{ $action }}">
    @csrf
    @if (($method ?? 'POST') === 'PUT')
        @method('PUT')
    @endif

    <div class="grid gap-5">
        <label class="field">
            <span class="field-label">Project</span>
            <select class="control control-select" name="project_id" required>
                <option value="">Choose a project</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" @selected((string) old('project_id', $issue->project_id) === (string) $project->id)>{{ $project->name }}</option>
                @endforeach
            </select>
            @include('partials.field-error', ['name' => 'project_id'])
        </label>

        <label class="field">
            <span class="field-label">Title</span>
            <input class="control" type="text" name="title" value="{{ old('title', $issue->title) }}" placeholder="Fix login flow" required>
            @include('partials.field-error', ['name' => 'title'])
        </label>

        <label class="field">
            <span class="field-label">Description</span>
            <textarea class="control control-textarea" name="description" rows="4" placeholder="Describe the issue">{{ old('description', $issue->description) }}</textarea>
            @include('partials.field-error', ['name' => 'description'])
        </label>

        <div class="grid gap-5 md:grid-cols-3">
            <label class="field">
                <span class="field-label">Status</span>
                <select class="control control-select" name="status" required>
                    @foreach (['open', 'in_progress', 'closed'] as $status)
                        <option value="{{ $status }}" @selected(old('status', $issue->status ?? 'open') === $status)>{{ str_replace('_', ' ', $status) }}</option>
                    @endforeach
                </select>
                @include('partials.field-error', ['name' => 'status'])
            </label>

            <label class="field">
                <span class="field-label">Priority</span>
                <select class="control control-select" name="priority" required>
                    @foreach (['low', 'medium', 'high'] as $priority)
                        <option value="{{ $priority }}" @selected(old('priority', $issue->priority ?? 'medium') === $priority)>{{ $priority }}</option>
                    @endforeach
                </select>
                @include('partials.field-error', ['name' => 'priority'])
            </label>

            <label class="field">
                <span class="field-label">Due date</span>
                <input class="control" type="date" name="due_date" value="{{ old('due_date', optional($issue->due_date)->format('Y-m-d')) }}">
                @include('partials.field-error', ['name' => 'due_date'])
            </label>
        </div>

        <label class="field">
            <span class="field-label">Tags</span>
            <select class="control control-select" name="tag_ids[]" multiple size="6">
                @foreach ($tags as $tag)
                    <option value="{{ $tag->id }}" @selected(in_array($tag->id, $selectedTagIds, false))>{{ $tag->name }}</option>
                @endforeach
            </select>
            <p class="field-hint">Hold Ctrl or Command to select multiple tags.</p>
            @include('partials.field-error', ['name' => 'tag_ids'])
        </label>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="rounded-full bg-white px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-slate-100" type="submit">{{ $button }}</button>
        <a class="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10" href="{{ route('issues.index') }}">Cancel</a>
    </div>
</form>

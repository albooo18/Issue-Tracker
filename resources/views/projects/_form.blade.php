<form class="space-y-5" method="POST" action="{{ $action }}">
    @csrf
    @if (($method ?? 'POST') === 'PUT')
        @method('PUT')
    @endif

    <div class="grid gap-5">
        <label class="field">
            <span class="field-label">Name</span>
            <input class="control" type="text" name="name" value="{{ old('name', $project->name) }}" placeholder="Website revamp" required>
            @include('partials.field-error', ['name' => 'name'])
        </label>

        <label class="field">
            <span class="field-label">Description</span>
            <textarea class="control control-textarea" name="description" rows="4" placeholder="What this project covers">{{ old('description', $project->description) }}</textarea>
            @include('partials.field-error', ['name' => 'description'])
        </label>

        <div class="grid gap-5 md:grid-cols-2">
            <label class="field">
                <span class="field-label">Start date</span>
                <input class="control" type="date" name="start_date" value="{{ old('start_date', optional($project->start_date)->format('Y-m-d')) }}">
                @include('partials.field-error', ['name' => 'start_date'])
            </label>

            <label class="field">
                <span class="field-label">Deadline</span>
                <input class="control" type="date" name="deadline" value="{{ old('deadline', optional($project->deadline)->format('Y-m-d')) }}">
                @include('partials.field-error', ['name' => 'deadline'])
            </label>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button class="rounded-full bg-white px-5 py-3 text-sm font-bold text-slate-950 transition hover:bg-slate-100" type="submit">{{ $button }}</button>
        <a class="rounded-full border border-white/10 bg-white/5 px-5 py-3 text-sm font-bold text-white transition hover:bg-white/10" href="{{ route('projects.index') }}">Cancel</a>
    </div>
</form>

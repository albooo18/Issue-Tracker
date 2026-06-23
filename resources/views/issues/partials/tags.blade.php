<div class="tag-row">
    @forelse ($issue->tags as $tag)
        <span class="tag-pill" style="--tag-color: {{ $tag->color ?: '#4f46e5' }}">
            {{ $tag->name }}
            <button
                type="button"
                class="tag-pill__remove"
                data-detach-tag
                data-detach-url="{{ route('issues.tags.destroy', [$issue, $tag]) }}"
                aria-label="Remove {{ $tag->name }}"
            >
                ×
            </button>
        </span>
    @empty
        <div class="empty-inline">No tags attached yet.</div>
    @endforelse
</div>

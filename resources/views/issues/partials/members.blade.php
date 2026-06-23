<div class="tag-row">
    @forelse ($issue->users as $user)
        <span class="tag-pill tag-pill--member">
            {{ $user->name }}
            @auth
                <button
                    type="button"
                    class="tag-pill__remove"
                    data-detach-member
                    data-detach-url="{{ route('issues.members.destroy', [$issue, $user]) }}"
                    aria-label="Remove {{ $user->name }}"
                >
                    ×
                </button>
            @endauth
        </span>
    @empty
        <div class="empty-inline">No members assigned yet.</div>
    @endforelse
</div>

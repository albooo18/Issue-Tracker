@forelse ($comments as $comment)
    @include('issues.partials.comment', ['comment' => $comment])
@empty
    <div class="empty-inline">No comments yet. Start the conversation below.</div>
@endforelse

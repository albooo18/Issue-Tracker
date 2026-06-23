<article class="comment-card">
    <div class="issue-card__meta">
        <strong>{{ $comment->author_name }}</strong>
        <span class="subtle">{{ $comment->created_at?->format('M j, Y g:i a') }}</span>
    </div>
    <p class="comment-card__body">{{ $comment->body }}</p>
</article>

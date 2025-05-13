@foreach($comments as $comment)
    <div class="d-flex mb-4 {{ $comment->parent_id ? 'ms-md-5 mt-4' : '' }} comment-item" id="comment-{{ $comment->id }}">
        <div class="flex-shrink-0">
            <img src="https://www.gravatar.com/avatar/{{ md5(strtolower(trim($comment->email ?? $comment->user->email ?? 'default@example.com'))) }}?d=mp&s=60" alt="{{ $comment->name }}" class="rounded-circle" width="60" height="60">
        </div>
        <div class="ms-3 flex-grow-1">
            <div class="d-flex justify-content-between align-items-center">
                <span class="fw-bold">{{ $comment->name }}</span>
                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
            </div>
            <div class="comment-body mt-1">
                {!! nl2br(e($comment->body)) !!}
            </div>

            {{-- Bouton de réponse (simplifié pour l'instant, peut être amélioré avec JS pour afficher le formulaire en ligne) --}}
            {{-- <a href="#comment-form" onclick="setParentId({{ $comment->id }})" class="btn btn-sm btn-outline-secondary mt-2">{{__('Reply')}}</a> --}}

            @if($comment->replies->count() > 0)
                <div class="replies mt-3">
                    @include('frontend.posts._comments_display', ['comments' => $comment->replies, 'post_id' => $post_id])
                </div>
            @endif
        </div>
    </div>
@endforeach

{{-- Script simple pour un futur formulaire de réponse (à améliorer) --}}
{{-- 
@pushOnce('scripts')
<script>
    function setParentId(commentId) {
        const parentIdInput = document.querySelector('form[action*="/comments"] input[name="parent_id"]');
        if (parentIdInput) {
            parentIdInput.value = commentId;
            // Optionnel: Faire défiler jusqu'au formulaire ou le mettre en évidence
            document.getElementById('comment_body').focus(); 
            // Optionnel: Ajouter une indication visuelle qu'on répond à un commentaire spécifique
            // const replyToIndicator = document.getElementById('reply-to-indicator');
            // if(replyToIndicator) replyToIndicator.innerHTML = `{{__('Replying to comment')}} #${commentId}`;
        }
    }
</script>
@endPushOnce 
--}}
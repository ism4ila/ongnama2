<div class="btn-group btn-group-sm" role="group" aria-label="{{ __('Actions pour l\'article') }}">
    {{-- Bouton Voir sur le site (si publié) --}}
    @if($post->status === 'published' && Route::has('frontend.posts.show'))
        <a href="{{ route('frontend.posts.show', $post->getTranslation('slug', app()->getLocale()) ?: $post->getTranslation('slug', config('app.fallback_locale')) ) }}"
           class="btn btn-sm btn-outline-success"
           title="{{ __('Voir sur le site') }}"
           data-toggle="tooltip"
           target="_blank">
            <i class="fas fa-eye"></i>
        </a>
    @endif
    
    {{-- Bouton Modifier --}}
    <a href="{{ route('admin.posts.edit', $post) }}" 
       class="btn btn-sm btn-outline-primary" 
       title="{{ __('Modifier') }}"
       data-toggle="tooltip">
        <i class="fas fa-edit"></i>
    </a>
    
    {{-- Bouton Supprimer avec modal --}}
    <button type="button" 
            class="btn btn-sm btn-outline-danger" 
            title="{{ __('Supprimer') }}"
            data-toggle="modal" 
            data-target="#deleteModal-{{ $post->id }}">
        <i class="fas fa-trash-alt"></i>
    </button>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deleteModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel-{{ $post->id }}" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel-{{ $post->id }}">
                    <i class="fas fa-exclamation-triangle mr-2"></i>{{ __('Confirmation de suppression') }}
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('Êtes-vous sûr de vouloir supprimer cet article ?') }}</p>
                <p class="font-weight-bold mb-1">{{ $post->getTranslation('title', app()->getLocale(), false) ?: $post->getTranslation('title', config('app.fallback_locale'), false) }}</p>
                <p class="text-danger font-weight-bold mb-0 mt-3">
                    <i class="fas fa-exclamation-circle mr-1"></i>
                    {{ __('Cette action est irréversible !') }}
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times mr-1"></i>{{ __('Annuler') }}
                </button>
                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="fas fa-trash-alt mr-1"></i>{{ __('Confirmer la suppression') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Activation des tooltips Bootstrap
    $(function () {
        $('[data-toggle="tooltip"]').tooltip()
    });
</script>
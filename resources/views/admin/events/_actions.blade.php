<div class="btn-group" role="group" aria-label="{{ __('Actions d\'événement') }}">
    <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-primary" title="{{ __('Modifier') }}">
        <i class="fas fa-edit"></i>
    </a>
    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteEventModal-{{ $event->id }}" title="{{ __('Supprimer') }}">
        <i class="fas fa-trash"></i>
    </button>
</div>

{{-- Modal de confirmation de suppression --}}
<div class="modal fade" id="deleteEventModal-{{ $event->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteEventModalLabel-{{ $event->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteEventModalLabel-{{ $event->id }}">{{ __('Confirmer la suppression') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Êtes-vous sûr de vouloir supprimer l\'événement :') }} 
                <strong>{{ $event->getTranslation('title', app()->getLocale()) }}</strong> ?
                <br><small>{{ __('Cette action est irréversible.') }}</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Annuler') }}</button>
                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Supprimer définitivement') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
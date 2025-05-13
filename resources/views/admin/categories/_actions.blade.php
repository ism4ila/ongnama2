<div class="btn-group" role="group" aria-label="{{ __('Actions de catégorie') }}">
    <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-primary">
        <i class="fas fa-edit"></i> {{-- __('Modifier') --}}
    </a>
    <button type="button" class="btn btn-sm btn-danger" data-toggle="modal" data-target="#deleteCategoryModal-{{ $category->id }}">
        <i class="fas fa-trash"></i> {{-- __('Supprimer') --}}
    </button>
</div>

<div class="modal fade" id="deleteCategoryModal-{{ $category->id }}" tabindex="-1" role="dialog" aria-labelledby="deleteCategoryModalLabel-{{ $category->id }}" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteCategoryModalLabel-{{ $category->id }}">{{ __('Confirmer la suppression') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                {{ __('Êtes-vous sûr de vouloir supprimer la catégorie :') }} <strong>{{ $category->getTranslation('name', app()->getLocale()) }}</strong> ?
                <br><small>{{ __('Cette action est irréversible.') }}</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Annuler') }}</button>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">{{ __('Supprimer définitivement') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
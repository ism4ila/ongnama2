{{-- resources/views/admin/posts/_actions.blade.php --}}
@props(['post'])

<div class="d-flex justify-content-end">
    {{-- Bouton Modifier --}}
    <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-sm btn-info me-1" data-bs-toggle="tooltip" title="{{ __('Modifier') }}">
        <i class="fas fa-edit"></i>
    </a>

    {{-- Bouton Supprimer --}}
    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" class="d-inline"
          onsubmit="return confirm('{{ __('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.') }}');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="{{ __('Supprimer') }}">
            <i class="fas fa-trash"></i>
        </button>
    </form>
</div>
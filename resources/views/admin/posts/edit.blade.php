@extends('admin.layouts.app')

@section('title', __('Modifier l\'Article') . ' : ' . $post->title)

@section('breadcrumbs')
<ol class="breadcrumb bg-white shadow-sm rounded px-3 py-2 mb-3">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> {{ __('Tableau de bord') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}"><i class="fas fa-newspaper"></i> {{ __('Articles') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Modifier') }} #{{ $post->id }}</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800 mb-0">
                <i class="fas fa-edit text-primary mr-2"></i>{{ __('Modifier l\'Article') }}
                <span class="badge badge-pill badge-light border ml-2">#{{ $post->id }}</span>
            </h1>
            <div>
                @if($post->status === 'published' && Route::has('frontend.posts.show'))
                    <a href="{{ route('frontend.posts.show', $post->getTranslation('slug', app()->getLocale()) ?: $post->getTranslation('slug', config('app.fallback_locale')) ) }}"
                       class="btn btn-sm btn-outline-info mr-2"
                       target="_blank">
                        <i class="fas fa-eye mr-1"></i> {{ __('Voir sur le site') }}
                    </a>
                @endif
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left mr-1"></i>{{ __('Retour à la liste') }}
                </a>
            </div>
        </div>
        
        <div class="card shadow mb-4 border-0 rounded-lg">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between bg-gradient-light">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit mr-1"></i>{{ __('Formulaire de modification') }}
                </h6>
                <span class="badge badge-{{ $post->status === 'published' ? 'success' : ($post->status === 'draft' ? 'warning' : 'info') }} px-3 py-2">
                    <i class="fas fa-{{ $post->status === 'published' ? 'check-circle' : ($post->status === 'draft' ? 'save' : 'clock') }} mr-1"></i>
                    {{ $post->status === 'published' ? __('Publié') : ($post->status === 'draft' ? __('Brouillon') : __('En attente')) }}
                </span>
            </div>
            <div class="card-body">
                <div class="alert alert-secondary rounded-lg shadow-sm border-left border-primary border-4 mb-4 d-flex">
                    <i class="fas fa-info-circle fa-2x mr-3 text-primary"></i>
                    <div>
                        <h5 class="alert-heading">{{ __('Informations sur cet article') }}</h5>
                        <p class="mb-0">
                            {{ __('Créé le') }}: <strong>{{ $post->created_at->format('d M Y à H:i') }}</strong> |
                            {{ __('Dernière modification') }}: <strong>{{ $post->updated_at->format('d M Y à H:i') }}</strong> |
                            {{ __('Auteur') }}: <strong>{{ $post->user ? $post->user->name : __('N/A') }}</strong>
                        </p>
                    </div>
                </div>
                
                <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @include('admin.posts._form', ['post' => $post])
                    
                    <div class="mt-4 d-flex justify-content-between border-top pt-4">
                        <div>
                            <a href="{{ route('admin.posts.index') }}" class="btn btn-light border">
                                <i class="fas fa-arrow-left mr-1"></i>{{ __('Retour') }}
                            </a>
                            <button type="button" class="btn btn-danger ml-2" data-toggle="modal" data-target="#deletePostModal">
                                <i class="fas fa-trash-alt mr-1"></i>{{ __('Supprimer') }}
                            </button>
                        </div>
                        <div>
                            <button type="submit" name="status" value="draft" class="btn btn-secondary">
                                <i class="fas fa-save mr-1"></i>{{ __('Enregistrer comme brouillon') }}
                            </button>
                            <button type="submit" name="status" value="published" class="btn btn-success ml-2">
                                <i class="fas fa-check-circle mr-1"></i>{{ __('Publier') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmation de suppression -->
<div class="modal fade" id="deletePostModal" tabindex="-1" role="dialog" aria-labelledby="deletePostModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deletePostModalLabel"><i class="fas fa-exclamation-triangle mr-2"></i>{{ __('Confirmation de suppression') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('Êtes-vous sûr de vouloir supprimer cet article ?') }}</p>
                <p class="mb-0"><strong>{{ __('Titre') }}:</strong> {{ $post->getTranslation('title', app()->getLocale(), false) }}</p>
                <p class="text-danger font-weight-bold mb-0 mt-3">{{ __('Cette action est irréversible !') }}</p>
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
@endsection

@push('styles')
    <style>
        .language-tab.active {
            border-bottom: 3px solid #4e73df;
        }
        .custom-file-label::after {
            content: "{{ __('Parcourir') }}";
        }
    </style>
@endpush

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Affichage du nom de fichier pour l'input type file
            document.querySelector('.custom-file-input').addEventListener('change', function(e) {
                const fileName = e.target.files[0]?.name;
                const label = e.target.nextElementSibling;
                label.innerText = fileName || "{{ __('Choisir une image...') }}";
            });
            
            // Gestion du toggle de la date de publication basé sur le statut
            const statusSelect = document.getElementById('status');
            const publishedAtWrapper = document.getElementById('published_at_wrapper');
            
            function togglePublishedAt() {
                if (statusSelect.value === 'published') {
                    publishedAtWrapper.style.display = 'block';
                } else {
                    publishedAtWrapper.style.display = 'none';
                }
            }
            
            statusSelect.addEventListener('change', togglePublishedAt);
            togglePublishedAt(); // Exécuter au chargement
        });
        
        function previewImage(event) {
            const reader = new FileReader();
            reader.onload = function(){
                const output = document.getElementById('featured_image_preview');
                const container = document.getElementById('image-preview-container');
                output.src = reader.result;
                output.style.display = 'block';
                output.style.opacity = '1';
                document.getElementById('remove_featured_image_label').style.display = 'block';
                document.getElementById('remove_featured_image_checkbox').checked = false;
                
                // Supprimer le texte "Aucune image" s'il existe
                const noImageText = container.querySelector('.position-absolute');
                if (noImageText) {
                    noImageText.style.display = 'none';
                }
            };
            
            if(event.target.files[0]){
                reader.readAsDataURL(event.target.files[0]);
            } else {
                resetImagePreview();
            }
        }
        
        function removeImage() {
            document.getElementById('featured_image').value = "";
            resetImagePreview();
            document.getElementById('remove_featured_image_checkbox').checked = true;
        }
        
        function resetImagePreview() {
            const output = document.getElementById('featured_image_preview');
            const currentImagePath = document.getElementById('current_featured_image_path_for_display');
            
            if (currentImagePath && currentImagePath.value) {
                output.src = "{{ asset('img/placeholder-image.jpg') }}";
                output.style.opacity = '0.5';
                
                // Afficher le texte "Aucune image"
                const container = document.getElementById('image-preview-container');
                let noImageText = container.querySelector('.position-absolute');
                if (!noImageText) {
                    noImageText = document.createElement('div');
                    noImageText.className = 'position-absolute';
                    noImageText.style = 'top: 50%; left: 50%; transform: translate(-50%, -50%);';
                    noImageText.innerHTML = '<span class="text-muted">{{ __("Image supprimée") }}</span>';
                    container.querySelector('.position-relative').appendChild(noImageText);
                } else {
                    noImageText.style.display = 'block';
                }
            }
            
            document.querySelector('.custom-file-label').innerText = "{{ __('Choisir une image...') }}";
        }
    </script>
@endpush
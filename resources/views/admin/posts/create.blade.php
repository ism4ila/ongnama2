@extends('admin.layouts.app')

@section('title', __('Créer un Nouvel Article'))

@section('breadcrumbs')
<ol class="breadcrumb bg-white shadow-sm rounded px-3 py-2 mb-3">
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt"></i> {{ __('Tableau de bord') }}</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.posts.index') }}"><i class="fas fa-newspaper"></i> {{ __('Articles') }}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{ __('Créer') }}</li>
</ol>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-12">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 text-gray-800 mb-0">
                <i class="fas fa-plus-circle text-primary mr-2"></i>{{ __('Nouvel Article') }}
            </h1>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left mr-1"></i>{{ __('Retour à la liste') }}
            </a>
        </div>
        
        <div class="card shadow mb-4 border-0 rounded-lg">
            <div class="card-header py-3 d-flex flex-row align-items-center bg-gradient-light">
                <h6 class="m-0 font-weight-bold text-primary">
                    <i class="fas fa-edit mr-1"></i>{{ __('Formulaire de création d\'article') }}
                </h6>
            </div>
            <div class="card-body">
                <div class="alert alert-info rounded-lg shadow-sm border-left border-info border-4 mb-4 d-flex">
                    <i class="fas fa-info-circle fa-2x mr-3 text-info"></i>
                    <div>
                        <h5 class="alert-heading">{{ __('Conseils pour la création d\'article') }}</h5>
                        <p class="mb-0">{{ __('Commencez par choisir une catégorie et rédigez un titre accrocheur. Les champs marqués d\'un') }} <span class="text-danger">*</span> {{ __('sont obligatoires.') }}</p>
                    </div>
                </div>
                
                <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @include('admin.posts._form', ['post' => new \App\Models\Post()])
                    
                    <div class="mt-4 d-flex justify-content-between border-top pt-4">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-light border">
                            <i class="fas fa-times mr-1"></i>{{ __('Annuler') }}
                        </a>
                        <div>
                            <button type="submit" name="status" value="draft" class="btn btn-secondary">
                                <i class="fas fa-save mr-1"></i>{{ __('Enregistrer comme brouillon') }}
                            </button>
                            <button type="submit" name="status" value="published" class="btn btn-primary ml-2">
                                <i class="fas fa-paper-plane mr-1"></i>{{ __('Publier maintenant') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    {{-- Styles pour les éditeurs de texte Riche (TinyMCE, CKEditor, etc.) --}}
    <link href="https://cdn.jsdelivr.net/npm/bs-stepper/dist/css/bs-stepper.min.css" rel="stylesheet">
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
    {{-- Script pour prévisualiser l'image sélectionnée --}}
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
            const container = document.getElementById('image-preview-container');
            output.src = "{{ asset('img/placeholder-image.jpg') }}";
            output.style.opacity = '0.5';
            
            // Afficher le texte "Aucune image"
            let noImageText = container.querySelector('.position-absolute');
            if (!noImageText) {
                noImageText = document.createElement('div');
                noImageText.className = 'position-absolute';
                noImageText.style = 'top: 50%; left: 50%; transform: translate(-50%, -50%);';
                noImageText.innerHTML = '<span class="text-muted">{{ __("Aucune image") }}</span>';
                container.querySelector('.position-relative').appendChild(noImageText);
            } else {
                noImageText.style.display = 'block';
            }
            
            document.getElementById('remove_featured_image_label').style.display = 'none';
            document.querySelector('.custom-file-label').innerText = "{{ __('Choisir une image...') }}";
        }
    </script>
@endpush
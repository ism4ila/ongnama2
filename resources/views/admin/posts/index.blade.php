@extends('admin.layouts.app')

@section('title', __('Gestion des Articles'))

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
    <h1 class="h3 text-gray-800 mb-0">
        <i class="fas fa-newspaper text-primary mr-2"></i>{{ __('Gestion des Articles') }}
    </h1>
    <div class="d-flex">
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus-circle mr-1"></i> {{ __('Nouvel Article') }}
        </a>
    </div>
</div>

<div class="card shadow mb-4 border-0 rounded-lg">
    <div class="card-header py-3 d-flex flex-wrap justify-content-between align-items-center bg-gradient-light">
        <h6 class="m-0 font-weight-bold text-primary">
            <i class="fas fa-list-alt mr-1"></i>{{ __('Liste des Articles') }}
            @if($posts->total() > 0)
                <span class="badge badge-pill badge-light ml-2">{{ $posts->total() }}</span>
            @endif
        </h6>
        
        <!-- Filtres de recherche -->
        <div class="mt-2 mt-md-0">
            <button class="btn btn-sm btn-outline-primary" type="button" data-toggle="collapse" data-target="#filtersCollapse" aria-expanded="false" aria-controls="filtersCollapse">
                <i class="fas fa-filter mr-1"></i>{{ __('Filtres') }}
                @if(request()->has('search') || request()->has('category') || request()->has('status'))
                    <span class="badge badge-primary ml-1">{{ __('Actifs') }}</span>
                @endif
            </button>
        </div>
    </div>
    
    <!-- Section des filtres (collapsible) -->
    <div class="collapse {{ (request()->has('search') || request()->has('category') || request()->has('status')) ? 'show' : '' }}" id="filtersCollapse">
        <div class="card-body bg-light border-bottom">
            <form action="{{ route('admin.posts.index') }}" method="GET" class="mb-0">
                <div class="row">
                    <div class="col-md-4 mb-2 mb-md-0">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-search"></i></span>
                            </div>
                            <input type="text" name="search" class="form-control" placeholder="{{ __('Rechercher un article...') }}" value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="category" class="form-control">
                            <option value="">{{ __('Toutes les catégories') }}</option>
                            @foreach(\App\Models\Category::orderBy('name->'.app()->getLocale())->get() as $category)
                                <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3 mb-2 mb-md-0">
                        <select name="status" class="form-control">
                            <option value="">{{ __('Tous les statuts') }}</option>
                            <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>{{ __('Publié') }}</option>
                            <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>{{ __('Brouillon') }}</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>{{ __('En attente') }}</option>
                        </select>
                    </div>
                    <div class="col-md-2 d-flex">
                        <button type="submit" class="btn btn-primary flex-grow-1">
                            <i class="fas fa-filter mr-1"></i>{{ __('Filtrer') }}
                        </button>
                    </div>
                </div>
                @if(request()->has('search') || request()->has('category') || request()->has('status'))
                    <div class="mt-2 text-right">
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-times mr-1"></i>{{ __('Réinitialiser les filtres') }}
                        </a>
                    </div>
                @endif
            </form>
        </div>
    </div>
    
    <div class="card-body">
        @if($posts->isEmpty() && !request()->has('search') && !request()->has('category') && !request()->has('status'))
            <div class="text-center py-5">
                <img src="{{ asset('img/empty-state.svg') }}" alt="Aucun article" class="img-fluid mb-3" style="max-height: 200px;">
                <h4 class="text-muted">{{ __('Aucun article n\'a encore été créé.') }}</h4>
                <p class="text-muted mb-4">{{ __('Commencez dès maintenant à enrichir votre blog.') }}</p>
                <a href="{{ route('admin.posts.create') }}" class="btn btn-primary px-4">
                    <i class="fas fa-plus-circle mr-1"></i> {{ __('Créer votre premier article') }}
                </a>
            </div>
        @elseif($posts->isEmpty())
            <div class="text-center py-5">
                <img src="{{ asset('img/no-results.svg') }}" alt="Aucun résultat" class="img-fluid mb-3" style="max-height: 150px;">
                <h4 class="text-muted">{{ __('Aucun article ne correspond à vos critères de recherche.') }}</h4>
                <p class="text-muted mb-4">{{ __('Essayez de modifier vos filtres pour obtenir des résultats.') }}</p>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-primary px-4">
                    <i class="fas fa-list mr-1"></i> {{ __('Voir tous les articles') }}
                </a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="posts-table" width="100%" cellspacing="0">
                    <thead class="thead-light">
                        <tr>
                            <th class="text-center" width="60">{{ __('ID') }}</th>
                            <th class="text-center" width="100">{{ __('Image') }}</th>
                            <th>{{ __('Titre') }}</th>
                            <th width="120">{{ __('Catégorie') }}</th>
                            <th width="120">{{ __('Auteur') }}</th>
                            <th class="text-center" width="120">{{ __('Statut') }}</th>
                            <th width="160">{{ __('Publié le') }}</th>
                            <th class="text-center" width="120">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr class="{{ $post->status === 'published' ? 'bg-white' : 'bg-light' }}">
                                <td class="align-middle text-center">
                                    <span class="badge badge-pill badge-secondary">{{ $post->id }}</span>
                                </td>
                                <td class="text-center align-middle">
                                    @if($post->featured_image)
                                        <div class="position-relative">
                                            <img src="{{ Storage::url($post->featured_image) }}"
                                                alt="{{ $post->getTranslation('title', app()->getLocale(), false) }}"
                                                class="img-fluid rounded shadow-sm"
                                                style="width: 80px; height: 50px; object-fit: cover;">
                                        </div>
                                    @else
                                        <div class="bg-light rounded text-center p-3">
                                            <i class="fas fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td class="align-middle">
                                    <a href="{{ route('admin.posts.edit', $post) }}" class="font-weight-bold text-decoration-none text-primary">
                                        {{ Str::limit($post->getTranslation('title', app()->getLocale(), false) ?: $post->getTranslation('title', config('app.fallback_locale'), false), 60) }}
                                    </a>
                                    <div class="small text-muted mt-1">
                                        {{ Str::limit($post->getTranslation('excerpt', app()->getLocale(), false) ?: $post->getTranslation('excerpt', config('app.fallback_locale'), false), 80) ?: __('Aucun extrait') }}
                                    </div>
                                    
                                    <!-- Badge pour les langues disponibles -->
                                    <div class="mt-2">
                                        @foreach(config('app.available_locales', ['en' => ['native' => 'English'], 'fr' => ['native' => 'Français'], 'ar' => ['native' => 'العربية']]) as $localeCode => $properties)
                                            @if($post->getTranslation('title', $localeCode, false))
                                                <span class="badge badge-light border mr-1" title="{{ $properties['native'] }}">
                                                    {{ strtoupper($localeCode) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </td>
                                <td class="align-middle">
                                    @if($post->category)
                                        <span class="badge badge-light border px-2 py-1">
                                            <i class="fas fa-folder mr-1 text-primary"></i>
                                            {{ $post->category->name }}
                                        </span>
                                    @else
                                        <span class="text-muted small">{{ __('N/A') }}</span>
                                    @endif
                                </td>
                                <td class="align-middle small">
                                    <div class="d-flex align-items-center">
                                        <div class="rounded-circle bg-secondary text-white text-center mr-2" style="width: 30px; height: 30px; line-height: 30px;">
                                            {{ substr($post->user ? $post->user->name : 'U', 0, 1) }}
                                        </div>
                                        <div>{{ $post->user ? $post->user->name : __('N/A') }}</div>
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    @if($post->status == 'published')
                                        <span class="badge badge-success px-3 py-2">
                                            <i class="fas fa-check-circle mr-1"></i>{{ __('Publié') }}
                                        </span>
                                    @elseif($post->status == 'draft')
                                        <span class="badge badge-warning px-3 py-2">
                                            <i class="fas fa-save mr-1"></i>{{ __('Brouillon') }}
                                        </span>
                                    @elseif($post->status == 'pending')
                                        <span class="badge badge-info px-3 py-2">
                                            <i class="fas fa-clock mr-1"></i>{{ __('En attente') }}
                                        </span>
                                    @else
                                        <span class="badge badge-secondary px-3 py-2">
                                            <i class="fas fa-question-circle mr-1"></i>{{ Str::ucfirst($post->status) }}
                                        </span>
                                    @endif
                                </td>
                                <td class="align-middle small">
                                    @if($post->published_at)
                                        <div class="text-nowrap">
                                            <i class="far fa-calendar-alt mr-1 text-muted"></i>
                                            {{ $post->published_at->format('d M Y') }}
                                        </div>
                                        <div class="text-muted">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $post->published_at->format('H:i') }}
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                    <div class="text-muted mt-1 smaller">
                                        <i class="fas fa-history mr-1"></i>
                                        {{ __('Modifié') }}: {{ $post->updated_at->diffForHumans() }}
                                    </div>
                                </td>
                                <td class="text-center align-middle">
                                    <div class="btn-group btn-group-sm" role="group">
                                        @if($post->status === 'published' && Route::has('frontend.posts.show'))
                                            <a href="{{ route('frontend.posts.show', $post->getTranslation('slug', app()->getLocale()) ?: $post->getTranslation('slug', config('app.fallback_locale')) ) }}"
                                               class="btn btn-outline-info"
                                               title="{{ __('Voir sur le site') }}"
                                               target="_blank">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        @endif
                                        
                                        <a href="{{ route('admin.posts.edit', $post) }}" 
                                           class="btn btn-outline-primary" 
                                           title="{{ __('Modifier') }}">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        
                                        <button type="button" 
                                                class="btn btn-outline-danger" 
                                                title="{{ __('Supprimer') }}"
                                                data-toggle="modal" 
                                                data-target="#deleteModal-{{ $post->id }}">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                    
                                    <!-- Modal de confirmation de suppression -->
                                    <div class="modal fade" id="deleteModal-{{ $post->id }}" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header bg-danger text-white">
                                                    <h5 class="modal-title">
                                                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ __('Confirmation de suppression') }}
                                                    </h5>
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
                                                    <form action="{{ route('admin.posts.destroy', $post) }}" method="POST">
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
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            @if ($posts->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $posts->appends(request()->query())->links() }}
                </div>
            @endif
            
            <div class="d-flex justify-content-between align-items-center mt-3">
                <div class="text-muted small">
                    {{ __('Affichage de') }} {{ $posts->firstItem() ?? 0 }} {{ __('à') }} {{ $posts->lastItem() ?? 0 }} {{ __('sur') }} {{ $posts->total() }} {{ __('articles') }}
                </div>
                <div>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-plus-circle mr-1"></i> {{ __('Nouvel Article') }}
                    </a>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

@push('styles')
    <style>
        .smaller {
            font-size: 0.8rem;
        }
        .table td {
            vertical-align: middle;
        }
        tr.bg-light {
            background-color: rgba(0,0,0,0.03) !important;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Toggle des filtres avec mémorisation de l'état via localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const filtersCollapse = document.getElementById('filtersCollapse');
            
            // Vérifie si le filtre est actuellement actif
            const isFilterActive = {{ (request()->has('search') || request()->has('category') || request()->has('status')) ? 'true' : 'false' }};
            
            // Initialise l'état des filtres en fonction de localStorage sauf si un filtre est actif
            if (!isFilterActive) {
                const filtersState = localStorage.getItem('postsFiltersState');
                if (filtersState === 'show') {
                    $(filtersCollapse).collapse('show');
                }
            }
            
            // Écoute les événements pour mettre à jour localStorage
            $(filtersCollapse).on('shown.bs.collapse', function () {
                localStorage.setItem('postsFiltersState', 'show');
            });
            
            $(filtersCollapse).on('hidden.bs.collapse', function () {
                localStorage.setItem('postsFiltersState', 'hide');
            });
        });
    </script>
@endpush
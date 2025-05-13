@extends('admin.layouts.app')

@section('title', __('Gestion des Catégories'))
@section('page-title', __('Liste des Catégories'))

@section('page-actions')
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-plus-circle"></i></span>
        <span class="text">{{ __('Nouvelle Catégorie') }}</span>
    </a>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-tags me-2"></i>{{ __('Toutes les catégories') }}</h6>
            {{-- Espace pour filtres futurs --}}
        </div>
        <div class="card-body">
            {{-- Assurez-vous que ce partiel utilise la syntaxe Bootstrap 4 pour les boutons close si votre admin layout utilise BS4 --}}
            {{-- Exemple: <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> --}}
            @include('layouts.partials.messages')

            @if($categories->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-folder-open fa-3x text-gray-400 mb-3"></i>
                    <p class="text-muted">{{ __('Aucune catégorie trouvée pour le moment.') }}</p>
                    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> {{ __('Créer la première catégorie') }}
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="categoriesTable" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                @php
                                    $defaultLocale = config('app.fallback_locale', 'en');
                                    $otherLocales = array_filter(config('app.available_locales', ['en', 'fr', 'ar']), function($locale) use ($defaultLocale) {
                                        return $locale !== $defaultLocale;
                                    });
                                @endphp
                                <th style="width: 25%;">{{ __('Nom') }} ({{ strtoupper($defaultLocale) }}) <span class="badge bg-secondary" style="font-size: 0.7em;">{{ __('Défaut') }}</span></th>

                                @if(!empty($otherLocales))
                                    <th colspan="{{ count($otherLocales) }}" class="text-center">{{ __('Autres Traductions du Nom') }}</th>
                                @endif

                                <th style="width: 20%;">{{ __('Slug Actuel') }} ({{ strtoupper(app()->getLocale()) }})</th>
                                <th class="text-center" style="width: 120px;">{{ __('Actions') }}</th> {{-- Largeur fixe pour les actions --}}
                            </tr>
                            @if(!empty($otherLocales))
                            <tr class="table-light"> {{-- Sous-en-tête pour les langues secondaires --}}
                                <th></th> {{-- Colonne vide pour l'ID --}}
                                <th></th> {{-- Colonne vide pour le nom par défaut --}}
                                @foreach($otherLocales as $locale)
                                    <th class="text-center fw-normal" style="font-size:0.9em;">{{ strtoupper($locale) }}</th>
                                @endforeach
                                <th></th> {{-- Colonne vide pour le slug --}}
                                <th></th> {{-- Colonne vide pour les actions --}}
                            </tr>
                            @endif
                        </thead>
                        <tbody>
                            @foreach ($categories as $category)
                                <tr>
                                    <td>{{ $category->id }}</td>
                                    <td>
                                        <strong>{{ $category->getTranslation('name', $defaultLocale) }}</strong>
                                        @if(!$category->hasTranslation('name', $defaultLocale) && $category->getTranslation('name', $defaultLocale) === null)
                                            <i class="fas fa-exclamation-triangle text-danger ms-1" title="{{__('Nom manquant dans la langue par défaut !')}}"></i>
                                        @endif
                                    </td>

                                    @foreach($otherLocales as $locale)
                                    <td>
                                        {{ $category->getTranslation('name', $locale, false) ?: '-' }}
                                        @if($category->getTranslation('name', $defaultLocale) && !$category->hasTranslation('name', $locale, false) && $category->getTranslation('name', $locale, false) === null)
                                            <span class="badge bg-warning text-dark" title="{{__('Traduction suggérée')}}"><i class="fas fa-language"></i> {{__('Manquante')}}</span>
                                        @elseif($category->hasTranslation('name', $locale, false))
                                            <i class="fas fa-check-circle text-success ms-1" title="{{__('Traduit')}}"></i>
                                        @endif
                                    </td>
                                    @endforeach

                                    <td><small class="text-muted">{{ $category->getTranslation('slug', app()->getLocale()) }}</small></td>
                                    <td class="text-center">
                                        @include('admin.categories._actions', ['category' => $category])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $categories->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    /* Style optionnel pour mieux espacer les boutons d'action s'ils sont nombreux */
    .btn-group .btn + .btn {
        margin-left: 5px;
    }
    /* Amélioration visuelle pour les badges de traduction */
    .badge.bg-warning {
        font-size: 0.75em;
        padding: 0.3em 0.5em;
    }
    .table th, .table td {
        vertical-align: middle;
    }
</style>
@endpush
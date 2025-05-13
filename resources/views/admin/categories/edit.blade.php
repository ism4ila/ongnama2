@extends('admin.layouts.app')

@section('title', __('Modifier la Catégorie') . ': ' . $category->getTranslation('name', config('app.fallback_locale', 'en')))
@section('page-title')
    <i class="fas fa-edit me-2"></i>{{ __('Modifier la Catégorie') }}: <span class="text-primary">{{ $category->getTranslation('name', config('app.fallback_locale', 'en')) }}</span>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-pencil-alt me-2"></i>{{ __('Formulaire de modification de catégorie') }}</h6>
        </div>
        <div class="card-body">
            @include('layouts.partials.messages') {{-- Assurez-vous que $locales et $category sont définis dans CategoryController@edit --}}

            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @method('PUT')
                @include('admin.categories._form', ['category' => $category, 'locales' => $locales])
            </form>
        </div>
    </div>
@endsection
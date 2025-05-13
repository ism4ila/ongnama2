@extends('admin.layouts.app')

@section('title', __('Nouvelle Catégorie'))
@section('page-title', __('Créer une nouvelle catégorie'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary"><i class="fas fa-pencil-alt me-2"></i>{{ __('Formulaire de création de catégorie') }}</h6>
        </div>
        <div class="card-body">
            @include('layouts.partials.messages') {{-- Assurez-vous que $locales est défini dans le contrôleur CategoryController@create --}}

            <form action="{{ route('admin.categories.store') }}" method="POST">
                @include('admin.categories._form', ['locales' => $locales])
            </form>
        </div>
    </div>
@endsection
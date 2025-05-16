{{-- resources/views/admin/posts/create.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('Créer un nouvel article'))

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm me-1"></i> {{ __('Retour à la liste') }}
        </a>
    </div>

    @include('layouts.partials.messages')

    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        {{-- Les variables $locales, $categories, $statuses sont passées par PostController@create via getFormData() --}}
        @include('admin.posts._form', [
            'post' => null,
            'locales' => $locales,
            'categories' => $categories,
            'statuses' => $statuses
        ])
    </form>
@endsection
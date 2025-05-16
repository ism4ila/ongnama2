{{-- resources/views/admin/posts/edit.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('Modifier l\'article : ') . $post->getTranslation('title', config('app.fallback_locale', 'en'), false) )

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
        <a href="{{ route('admin.posts.index') }}" class="btn btn-outline-secondary shadow-sm">
            <i class="fas fa-arrow-left fa-sm me-1"></i> {{ __('Retour à la liste') }}
        </a>
    </div>

    @include('layouts.partials.messages')

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        {{-- Les variables $post, $locales, $categories, $statuses sont passées par PostController@edit via getFormData($post) --}}
        @include('admin.posts._form', [
            'post' => $post,
            'locales' => $locales,
            'categories' => $categories,
            'statuses' => $statuses
        ])
    </form>
@endsection
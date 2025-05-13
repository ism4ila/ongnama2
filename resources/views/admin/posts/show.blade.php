@extends('layouts.admin')
@section('title', __('View Post') . ': ' . $post->title )

@section('main-content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">{{ __('Post Details:') }} {{ $post->title }}</h1>

    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">{{ $post->title }}</h6>
            <div>
                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-sm">{{ __('Edit') }}</a>
                <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary btn-sm">{{ __('Back to list') }}</a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    @foreach(['en', 'fr', 'ar'] as $locale)
                        @if($post->hasTranslation('title', $locale))
                            <div class="mb-3">
                                <h5>{{ __('Title') }} ({{ strtoupper($locale) }})</h5>
                                <p>{{ $post->getTranslation('title', $locale) }}</p>
                            </div>
                            @if($post->hasTranslation('slug', $locale))
                            <div class="mb-3">
                                <h6>{{ __('Slug') }} ({{ strtoupper($locale) }})</h6>
                                <p><code>{{ $post->getTranslation('slug', $locale) }}</code></p>
                            </div>
                            @endif
                            @if($post->hasTranslation('excerpt', $locale) && $post->getTranslation('excerpt', $locale))
                            <div class="mb-3">
                                <h6>{{ __('Excerpt') }} ({{ strtoupper($locale) }})</h6>
                                <p><em>{{ $post->getTranslation('excerpt', $locale) }}</em></p>
                            </div>
                            @endif
                            @if($post->hasTranslation('body', $locale))
                            <div class="mb-3">
                                <h6>{{ __('Body') }} ({{ strtoupper($locale) }})</h6>
                                <div>{!! $post->getTranslation('body', $locale) !!}</div> {{-- Use {!! !!} if body contains HTML --}}
                            </div>
                            @endif
                            @if(!$loop->last) <hr> @endif
                        @endif
                    @endforeach
                </div>
                <div class="col-md-4">
                    @if ($post->featured_image)
                        <div class="mb-3">
                            <h6>{{ __('Featured Image') }}</h6>
                            <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid rounded">
                        </div>
                    @endif
                    <div class="mb-3">
                        <h6>{{ __('Category') }}</h6>
                        <p><a href="{{ route('admin.categories.show', $post->category_id) }}">{{ $post->category->name }}</a></p>
                    </div>
                     <div class="mb-3">
                        <h6>{{ __('Author') }}</h6>
                        <p>{{ $post->user->name }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>{{ __('Status') }}</h6>
                        <p><span class="badge badge-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'secondary' : 'warning') }}">{{ ucfirst($post->status) }}</span></p>
                    </div>
                    @if ($post->published_at)
                    <div class="mb-3">
                        <h6>{{ __('Published At') }}</h6>
                        <p>{{ $post->published_at->format('d/m/Y H:i') }}</p>
                    </div>
                    @endif
                    <div class="mb-3">
                        <h6>{{ __('Created At') }}</h6>
                        <p>{{ $post->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="mb-3">
                        <h6>{{ __('Updated At') }}</h6>
                        <p>{{ $post->updated_at->format('d/m/Y H:i') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
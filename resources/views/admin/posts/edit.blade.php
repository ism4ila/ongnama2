@extends('layouts.admin')
@section('title', __('Edit Post') . ': ' . $post->title)

@section('main-content')
<div class="container-fluid">
    <h1 class="h3 mb-2 text-gray-800">{{ __('Edit Post') }}: {{ $post->getTranslation('title', config('app.locale'), false) }}</h1>

    <form action="{{ route('admin.posts.update', $post) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('Post Content') }}</h6>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="languageTabs" role="tablist">
                            @foreach($supportedLocales as $locale)
                                <li class="nav-item">
                                    <a class="nav-link {{ $locale === config('app.locale') ? 'active' : '' }}" id="{{ $locale }}-tab-post" data-toggle="tab" href="#{{ $locale }}-content-post" role="tab">{{ strtoupper($locale) }}</a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content" id="languageTabsContentPost">
                            @foreach($supportedLocales as $locale)
                                <div class="tab-pane fade {{ $locale === config('app.locale') ? 'show active' : '' }}" id="{{ $locale }}-content-post" role="tabpanel">
                                    <div class="form-group mt-3">
                                        <label for="title_{{ $locale }}">{{ __('Title') }} ({{ strtoupper($locale) }}) <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('title.'.$locale) is-invalid @enderror" id="title_{{ $locale }}" name="title[{{ $locale }}]" value="{{ old('title.'.$locale, $post->getTranslation('title', $locale, false)) }}" {{ $locale === 'ar' ? 'dir=rtl' : '' }}>
                                        @error('title.'.$locale) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="slug_{{ $locale }}">{{ __('Slug') }} ({{ strtoupper($locale) }})</label>
                                        <input type="text" class="form-control @error('slug.'.$locale) is-invalid @enderror" id="slug_{{ $locale }}" name="slug[{{ $locale }}]" value="{{ old('slug.'.$locale, $post->getTranslation('slug', $locale, false)) }}" placeholder="{{ __('Slug will be generated from name if left empty') }}" {{ $locale === 'ar' ? 'dir=rtl' : '' }}>
                                        @error('slug.'.$locale) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="body_{{ $locale }}">{{ __('Body') }} ({{ strtoupper($locale) }}) <span class="text-danger">*</span></label>
                                        <textarea class="form-control wysiwyg-editor @error('body.'.$locale) is-invalid @enderror" id="body_{{ $locale }}" name="body[{{ $locale }}]" rows="10" {{ $locale === 'ar' ? 'dir=rtl' : '' }}>{{ old('body.'.$locale, $post->getTranslation('body', $locale, false)) }}</textarea>
                                        @error('body.'.$locale) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="form-group">
                                        <label for="excerpt_{{ $locale }}">{{ __('Excerpt') }} ({{ strtoupper($locale) }})</label>
                                        <textarea class="form-control @error('excerpt.'.$locale) is-invalid @enderror" id="excerpt_{{ $locale }}" name="excerpt[{{ $locale }}]" rows="3" {{ $locale === 'ar' ? 'dir=rtl' : '' }}>{{ old('excerpt.'.$locale, $post->getTranslation('excerpt', $locale, false)) }}</textarea>
                                        @error('excerpt.'.$locale) <span class="invalid-feedback">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow mb-4">
                     <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">{{ __('Post Details') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="category_id">{{ __('Category') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id">
                                <option value="">-- {{ __('Select Category') }} --</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id', $post->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="status">{{ __('Status') }} <span class="text-danger">*</span></label>
                            <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                                @foreach($statuses as $key => $value)
                                    <option value="{{ $key }}" {{ old('status', $post->status) == $key ? 'selected' : '' }}>{{ __($value) }}</option>
                                @endforeach
                            </select>
                            @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="published_at">{{ __('Published At') }}</label>
                            <input type="datetime-local" class="form-control @error('published_at') is-invalid @enderror" id="published_at" name="published_at" value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                            @error('published_at') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="featured_image">{{ __('Featured Image') }}</label>
                            <input type="file" class="form-control-file @error('featured_image') is-invalid @enderror" id="featured_image" name="featured_image">
                            @error('featured_image') <span class="invalid-feedback">{{ $message }}</span> @enderror
                            @if ($post->featured_image)
                                <div class="mt-2">
                                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" width="150" class="img-thumbnail">
                                    <div class="form-check mt-1">
                                        <input class="form-check-input" type="checkbox" name="remove_featured_image" id="remove_featured_image" value="1">
                                        <label class="form-check-label" for="remove_featured_image">
                                            {{ __('Remove featured image') }}
                                        </label>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                     <div class="card-footer">
                        <button type="submit" class="btn btn-success btn-icon-split">
                            <span class="icon text-white-50"><i class="fas fa-check"></i></span>
                            <span class="text">{{ __('Update Post') }}</span>
                        </button>
                        <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">{{ __('Cancel') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
{{-- <script src="https://cdn.ckeditor.com/4.16.0/standard/ckeditor.js"></script> --}}
{{-- <script> --}}
{{--  document.addEventListener("DOMContentLoaded", function() { --}}
{{--    document.querySelectorAll('.wysiwyg-editor').forEach(function(editorElement) { --}}
{{--      CKEDITOR.replace(editorElement.id); --}}
{{--    }); --}}
{{--  }); --}}
{{-- </script> --}}
@endpush
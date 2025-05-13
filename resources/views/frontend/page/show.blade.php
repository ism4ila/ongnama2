{{-- resources/views/frontend/page/show.blade.php --}}
@extends('frontend.frontend')

@section('title'){{ $page->getTranslation('title', app()->getLocale()) }} - {{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) }}@endsection
@section('meta_description'){{ Str::limit(strip_tags($page->getTranslation('meta_description', app()->getLocale()) ?? $page->getTranslation('body', app()->getLocale())), 160) }}@endsection
{{-- Ajoutez d'autres meta tags si nécessaire --}}

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-12">
            <h1 class="mb-4">{{ $page->getTranslation('title', app()->getLocale()) }}</h1>
            <div>
                {!! $page->getTranslation('body', app()->getLocale()) !!}
            </div>
        </div>
    </div>
</div>
@endsection
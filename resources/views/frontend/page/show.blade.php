@extends('frontend.frontend')

@section('title', $page->title) {{-- Utilise le titre de la page --}}

{{-- Ajout des méta-tags SEO si disponibles --}}
@section('meta_title', $page->meta_title ?: $page->title)
@section('meta_description', $page->meta_description)
@section('meta_keywords', $page->meta_keywords)

@section('content')
    <header class="page-header bg-light py-5">
        <div class="container">
            <h1 class="page-title">@yield('title')</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home', app()->getLocale()) }}">{{ __('Accueil') }}</a></li>
                    {{-- Vous pourriez ajouter des parents si vous avez une hiérarchie de pages --}}
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
    </header>

    <main class="container py-5">
        <div class="row">
            {{-- Déterminez si vous voulez une sidebar ou une page pleine largeur --}}
            <div class="col-lg-12"> {{-- Ou col-lg-8 si sidebar --}}
                <article class="page-content">
                    {!! $page->body !!} {{-- Affiche le contenu HTML de la page --}}
                </article>
            </div>
            {{-- <div class="col-lg-4"> --}}
                {{-- @include('frontend.partials._sidebar_pages') --}}
            {{-- </div> --}}
        </div>
    </main>
@endsection
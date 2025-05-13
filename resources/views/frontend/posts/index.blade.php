@extends('frontend.frontend') {{-- Ou le nom de ton layout frontend principal, par exemple 'layouts.frontend' --}}

@section('title', __('Actualités'))

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8">
            <div class="mb-4 pb-2 border-bottom">
                <h1 class="section-title mb-0">{{ __('Toutes les Actualités') }}</h1>
                {{-- Optionnel: Afficher le nom de la catégorie si filtré --}}
                @if(request('category'))
                    @php
                        // Essaie de retrouver la catégorie pour afficher son nom
                        // Note: Ceci est une petite requête, pour des cas plus complexes, passer la catégorie depuis le contrôleur
                        $_category = \App\Models\Category::where('slug->'.app()->getLocale(), request('category'))
                                        ->orWhere('slug->'.config('app.fallback_locale'), request('category'))
                                        ->first();
                    @endphp
                    @if($_category)
                        <p class="text-muted">{{ __('Filtré par catégorie') }}: {{ $_category->name }}</p>
                    @endif
                @endif
            </div>

            @if($posts->isNotEmpty())
                @foreach ($posts as $post)
                    <div class="card article-card mb-4 shadow-sm fade-in">
                        <div class="row g-0">
                            <div class="col-md-4">
                                @if($post->featured_image)
                                    <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                        <img src="{{ Storage::url($post->featured_image) }}" class="img-fluid rounded-start h-100" alt="{{ $post->title }}" style="object-fit: cover;">
                                    </a>
                                @else
                                    <a href="{{ route('frontend.posts.show', $post->slug) }}">
                                        <img src="{{ asset('images/placeholder_post_long.jpg') }}" class="img-fluid rounded-start h-100" alt="{{ $post->title }}" style="object-fit: cover;">
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-8">
                                <div class="card-body d-flex flex-column h-100">
                                    <h5 class="card-title mb-1">
                                        <a href="{{ route('frontend.posts.show', $post->slug) }}" class="text-decoration-none" style="color: var(--primary-color);">{{ $post->title }}</a>
                                    </h5>
                                    <div class="mb-2 text-muted">
                                        <small>
                                            <i class="fas fa-user me-1"></i>{{ $post->user->name ?? __('Auteur inconnu') }}
                                            <span class="mx-1">|</span>
                                            <i class="fas fa-calendar-alt me-1"></i>{{ $post->published_at ? $post->published_at->translatedFormat('d M Y') : '' }}
                                            <span class="mx-1">|</span>
                                            <i class="fas fa-tag me-1"></i><a href="{{ route('frontend.posts.index', ['category' => $post->category->slug]) }}" class="text-decoration-none text-muted">{{ $post->category->name ?? __('Non classé') }}</a>
                                        </small>
                                    </div>
                                    <p class="card-text flex-grow-1">
                                        {{ $post->excerpt ?? Str::limit(strip_tags($post->body), 150) }}
                                    </p>
                                    <a href="{{ route('frontend.posts.show', $post->slug) }}" class="btn btn-outline-secondary btn-sm align-self-start">
                                        {{ __('Lire la suite') }} <i class="fas fa-arrow-right ms-1"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="mt-4">
                    {{ $posts->appends(request()->query())->links() }} {{-- Garde les paramètres de l'URL (comme ?category=...) lors de la pagination --}}
                </div>
            @else
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i> {{ __('Aucune actualité à afficher pour le moment.') }}
                    @if(request('category'))
                        {{ __('dans cette catégorie.') }}
                    @endif
                </div>
            @endif
        </div>

        {{-- Sidebar (Optionnel) --}}
        <div class="col-lg-4">
            @include('frontend.posts._sidebar', ['categories' => $categories]) {{-- Passe les catégories à la sidebar --}}
        </div>
    </div>
</div>
@endsection

@push('styles_frontend') {{-- Ou le nom de stack que tu utilises dans ton layout frontend --}}
<style>
    .article-card .card-title a:hover {
        color: var(--secondary-color) !important;
    }
    .pagination .page-link {
        color: var(--primary-color);
    }
    .pagination .page-item.active .page-link {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
        color: white;
    }
    .sidebar-widget {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: var(--light-gray);
        border-radius: 8px;
    }
    .sidebar-widget .widget-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--accent-color);
    }
    .sidebar-widget ul {
        list-style: none;
        padding-left: 0;
    }
    .sidebar-widget ul li a {
        text-decoration: none;
        color: var(--dark-gray);
        transition: color 0.2s ease;
    }
     .sidebar-widget ul li a:hover {
        color: var(--primary-color);
    }
    .img-fluid.rounded-start { /* Pour s'assurer que l'image prend toute la hauteur de la carte en mode row */
        object-fit: cover;
        height: 100%;
    }
</style>
@endpush
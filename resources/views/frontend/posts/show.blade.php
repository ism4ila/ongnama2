@extends('frontend.frontend') {{-- Ou le nom de ton layout frontend principal --}}

@section('title', $post->title) {{-- Le titre traduit de l'article --}}

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- Colonne principale de l'article --}}
        <div class="col-lg-8">
            <article class="post-single fade-in">
                @if ($post->featured_image)
                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" class="img-fluid rounded mb-4 shadow-sm" style="width: 100%; max-height: 450px; object-fit: cover;">
                @endif

                <h1 class="mb-3" style="color: var(--primary-color); font-weight: 700;">{{ $post->title }}</h1>
                
                <div class="post-meta mb-4 text-muted border-bottom pb-3">
                    <small>
                        <i class="fas fa-user me-1"></i>{{ $post->user->name ?? __('Auteur inconnu') }}
                        <span class="mx-2">|</span>
                        <i class="fas fa-calendar-alt me-1"></i>{{ $post->published_at ? $post->published_at->translatedFormat('d F Y, H:i') : '' }}
                        <span class="mx-2">|</span>
                        <i class="fas fa-tag me-1"></i><a href="{{ route('frontend.posts.index', ['category' => $post->category->slug]) }}" class="text-decoration-none text-muted">{{ $post->category->name ?? __('Non classé') }}</a>
                    </small>
                </div>

                <div class="post-content">
                    {!! $post->body !!} {{-- Utilise {!! !!} pour afficher le HTML du corps de l'article --}}
                </div>

                {{-- Optionnel: Tags, Partage social, etc. --}}
                {{-- 
                <div class="mt-5 pt-4 border-top">
                    <h5>Partager cet article :</h5>
                    <a href="#" class="btn btn-outline-primary btn-sm"><i class="fab fa-facebook-f"></i> Facebook</a>
                    <a href="#" class="btn btn-outline-info btn-sm"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#" class="btn btn-outline-success btn-sm"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                </div>
                --}}

            </article>
        </div>

        {{-- Sidebar (Optionnel) --}}
        <div class="col-lg-4">
            @include('frontend.posts._sidebar', ['categories' => $categories, 'recentPosts' => $recentPosts, 'currentPost' => $post])
        </div>
    </div>
</div>
@endsection

@push('styles_frontend')
<style>
    .post-single .post-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin-top: 0.5rem;
        margin-bottom: 0.5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    .post-single .post-content h2,
    .post-single .post-content h3,
    .post-single .post-content h4 {
        color: var(--secondary-color);
        margin-top: 1.5em;
        margin-bottom: 0.5em;
    }
    .post-single .post-content p {
        margin-bottom: 1em;
        line-height: 1.7;
    }
    .post-single .post-content ul,
    .post-single .post-content ol {
        padding-left: 1.5em; /* Ou padding-inline-start en CSS logique */
        margin-bottom: 1em;
    }
    .post-single .post-content blockquote {
        border-left: 4px solid var(--primary-color); /* Ou border-inline-start */
        padding-left: 1em; /* Ou padding-inline-start */
        margin-left: 0; /* Ou margin-inline-start */
        margin-right: 0; /* Ou margin-inline-end */
        font-style: italic;
        color: var(--dark-gray);
    }
    [dir="rtl"] .post-single .post-content blockquote {
        border-right: 4px solid var(--primary-color);
        border-left: none;
        padding-right: 1em;
        padding-left: 0;
    }

    /* Styles de la sidebar (déjà définis dans index, mais peuvent être spécifiques ici aussi) */
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
        display: block;
        padding: 0.3rem 0;
    }
     .sidebar-widget ul li a:hover {
        color: var(--primary-color);
    }
</style>
@endpush
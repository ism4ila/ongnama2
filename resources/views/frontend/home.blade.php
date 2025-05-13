@extends('frontend.frontend')

@section('title')
{{ $homePageSettings->getTranslation('hero_title', app()->getLocale()) ?? __('Accueil') }} - {{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }}
@endsection

{{-- Supprimé le @push('styles_frontend') ici car nous allons tout mettre dans frontend.css --}}

@section('content')
<div class="container homepage-content"> {{-- Ajout d'une classe pour un ciblage CSS spécifique --}}
    {{-- HERO SECTION (EXISTANT) --}}
    <div class="row mb-5">
        <div class="col-12 text-center hero-section animate__animated animate__fadeIn"
            style="{{ $homePageSettings->hero_background_image ? 'background-image: linear-gradient(rgba(255, 255, 255, 0.85), rgba(255, 255, 255, 0.75)), url(' . Storage::url($homePageSettings->hero_background_image) . ');' : '' }}">
            <h1 class="hero-title">
                {{ $homePageSettings->getTranslation('hero_title', app()->getLocale()) ?? __('Bienvenue à l\'Organisation Nama') }}
            </h1>
            <p class="hero-subtitle">
                {{ $homePageSettings->getTranslation('hero_subtitle', app()->getLocale()) ?? __('Agir ensemble pour un avenir meilleur.') }}
            </p>
            @php
            $heroButtonLink = $homePageSettings->hero_button_link;
            if ($heroButtonLink && !filter_var($heroButtonLink, FILTER_VALIDATE_URL) && Route::has($heroButtonLink)) {
            try { $heroButtonLink = route($heroButtonLink, ['locale' => app()->getLocale()]); }
            catch (\Exception $e) { $heroButtonLink = url($heroButtonLink); }
            } elseif ($heroButtonLink) { $heroButtonLink = url($heroButtonLink); }
            else { $heroButtonLink = route('frontend.projects.index', ['locale' => app()->getLocale()]); }
            @endphp
            <a href="{{ $heroButtonLink }}" class="btn btn-primary btn-lg hero-button"> {{-- Ajout btn-lg et classe --}}
                {{ $homePageSettings->getTranslation('hero_button_text', app()->getLocale()) ?? __('Découvrir nos Projets') }}
                <i class="fas fa-arrow-right btn-icon-right"></i>
            </a>
        </div>
    </div>

    {{-- LATEST PROJECTS (EXISTANT - PEUT ÊTRE STYLÉ DE FAÇON SIMILAIRE AUX ARTICLES/ÉVÉNEMENTS CI-DESSOUS) --}}
    @if(isset($latestProjects) && $latestProjects->isNotEmpty())
    <section class="home-section latest-projects-section mb-5 py-4" data-aos="fade-up">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title styled-title"><span>{{ $homePageSettings->getTranslation('latest_projects_title', app()->getLocale()) ?? __('Nos Derniers Projets') }}</span></h2>
            </div>
        </div>
        <div class="row">
            @foreach ($latestProjects as $project)
            <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                <div class="card project-card h-100">
                    <a href="{{ route('frontend.projects.show', ['locale' => app()->getLocale(), 'project' => $project->getTranslation('slug', app()->getLocale(), $project->slug)]) }}" class="card-image-link">
                        <div class="card-image-container">
                            <img src="{{ $project->image ? Storage::url($project->image) : asset('images/placeholder_project_card.jpg') }}"
                                class="card-img-top" alt="{{ $project->getTranslation('title', app()->getLocale()) }}">
                            @if($project->status)
                            <span class="card-status-badge badge bg-primary">{{ __(ucfirst($project->status)) }}</span>
                            @endif
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('frontend.projects.show', ['locale' => app()->getLocale(), 'project' => $project->getTranslation('slug', app()->getLocale(), $project->slug)]) }}">
                                {{ Str::limit($project->getTranslation('title', app()->getLocale()), 55) }}
                            </a>
                        </h5>
                        <p class="card-text small text-muted">
                            @if($project->start_date)
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ \Carbon\Carbon::parse($project->start_date)->translatedFormat('M Y') }}
                            @if($project->end_date)
                            - {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('M Y') }}
                            @else
                            - {{__('Ongoing')}}
                            @endif
                            <br>
                            @endif
                            @if($project->getTranslation('location', app()->getLocale()))
                            <i class="fas fa-map-marker-alt me-1 mt-1"></i> {{ Str::limit($project->getTranslation('location', app()->getLocale()), 30) }}
                            @endif
                        </p>
                        <p class="card-excerpt flex-grow-1">{{ Str::limit(strip_tags($project->getTranslation('description', app()->getLocale())), 100) }}</p>
                        <a href="{{ route('frontend.projects.show', ['locale' => app()->getLocale(), 'project' => $project->getTranslation('slug', app()->getLocale(), $project->slug)]) }}" class="btn btn-outline-primary mt-auto align-self-start">
                            {{ __('En savoir plus') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}" class="btn btn-primary btn-lg">
                    {{ __('Voir tous nos projets') }} <i class="fas fa-arrow-right btn-icon-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif


    {{-- LATEST POSTS (NOUVELLE PRÉSENTATION SUGGÉRÉE) --}}
    @if(isset($latestPosts) && $latestPosts->isNotEmpty())
    <section class="home-section latest-posts-section mb-5 py-4" data-aos="fade-up" data-aos-delay="100">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title styled-title"><span>{{ $homePageSettings->getTranslation('latest_posts_title', app()->getLocale()) ?? __('Dernières Actualités') }}</span></h2>
            </div>
        </div>
        <div class="row">
            @foreach ($latestPosts as $post)
            <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                <div class="card post-card h-100">
                    <a href="{{ route('frontend.posts.show', ['locale' => app()->getLocale(), 'post' => $post->getTranslation('slug', app()->getLocale(), $post->slug)]) }}" class="card-image-link">
                        <div class="card-image-container">
                            @if($post->featured_image)
                            <img src="{{ Storage::url($post->featured_image) }}" class="card-img-top" alt="{{ $post->getTranslation('title', app()->getLocale()) }}">
                            @else
                            <img src="{{ asset('images/placeholder_post_card.jpg') }}" class="card-img-top" alt="{{ $post->getTranslation('title', app()->getLocale()) }}">
                            @endif
                            @if($post->category)
                            <span class="card-category-badge badge">{{ $post->category->getTranslation('name', app()->getLocale()) }}</span>
                            @endif
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('frontend.posts.show', ['locale' => app()->getLocale(), 'post' => $post->getTranslation('slug', app()->getLocale(), $post->slug)]) }}">
                                {{ Str::limit($post->getTranslation('title', app()->getLocale()), 55) }}
                            </a>
                        </h5>
                        @if($post->published_at)
                        <p class="card-date text-muted small mb-2">
                            <i class="fas fa-calendar-alt me-1"></i>
                            {{ $post->published_at->translatedFormat('d F Y') }}
                        </p>
                        @endif
                        <p class="card-excerpt flex-grow-1">
                            {{ Str::limit(strip_tags($post->getTranslation('excerpt', app()->getLocale(), $post->getTranslation('body', app()->getLocale()))), 110) }}
                        </p>
                        <a href="{{ route('frontend.posts.show', ['locale' => app()->getLocale(), 'post' => $post->getTranslation('slug', app()->getLocale(), $post->slug)]) }}" class="btn btn-outline-secondary mt-auto align-self-start">
                            {{ __('Lire la suite') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}" class="btn btn-secondary btn-lg">
                    {{ __('Toutes nos actualités') }} <i class="fas fa-arrow-right btn-icon-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif

    {{-- UPCOMING EVENTS (NOUVELLE PRÉSENTATION SUGGÉRÉE) --}}
    @if(isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
    <section class="home-section upcoming-events-section mb-5 py-4" data-aos="fade-up" data-aos-delay="200">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title styled-title"><span>{{ $homePageSettings->getTranslation('upcoming_events_title', app()->getLocale()) ?? __('Événements à Venir') }}</span></h2>
            </div>
        </div>
        <div class="row">
            @foreach ($upcomingEvents as $event) 
            <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                <div class="card event-card h-100">
                    {{-- LIGNE CORRIGÉE CI-DESSOUS --}}
                    <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'event_slug' => $event->getTranslation('slug', app()->getLocale()) ?? $event->id ]) }}" class="card-image-link">
                        <div class="card-image-container">
                            @if($event->image)
                            <img src="{{ Storage::url($event->image) }}" class="card-img-top" alt="{{ $event->getTranslation('title', app()->getLocale()) }}">
                            @else
                            <img src="{{ asset('images/placeholder_event_card.jpg') }}" class="card-img-top" alt="{{ $event->getTranslation('title', app()->getLocale()) }}">
                            @endif
                            <div class="card-event-date-badge">
                                <span class="day">{{ $event->start_datetime->format('d') }}</span>
                                <span class="month">{{ $event->start_datetime->translatedFormat('M') }}</span>
                                <span class="year">{{ $event->start_datetime->format('Y') }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            {{-- LIGNE CORRIGÉE CI-DESSOUS --}}
                            <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'event_slug' => $event->getTranslation('slug', app()->getLocale()) ?? $event->id ]) }}">
                                {{ Str::limit($event->getTranslation('title', app()->getLocale()), 55) }}
                            </a>
                        </h5>
                        <p class="card-details text-muted small mb-2">
                            <i class="fas fa-clock me-1"></i> {{ $event->start_datetime->translatedFormat('H:i') }}
                            @if($event->getTranslation('location_text', app()->getLocale()))
                            <br><i class="fas fa-map-marker-alt me-1 mt-1"></i> {{ Str::limit($event->getTranslation('location_text', app()->getLocale()), 30) }}
                            @endif
                        </p>
                        <p class="card-excerpt flex-grow-1">{{ Str::limit(strip_tags($event->getTranslation('description', app()->getLocale())), 100) }}</p>
                        {{-- LIGNE CORRIGÉE CI-DESSOUS --}}
                        <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'event_slug' => $event->getTranslation('slug', app()->getLocale()) ?? $event->id ]) }}" class="btn btn-outline-info mt-auto align-self-start">
                            {{ __('Voir les détails') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('frontend.events.index', ['locale' => app()->getLocale()]) }}" class="btn btn-info text-white btn-lg">
                    {{ __('Tous nos événements') }} <i class="fas fa-arrow-right btn-icon-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif
    {{-- UPCOMING EVENTS (NOUVELLE PRÉSENTATION SUGGÉRÉE) --}}
    @if(isset($upcomingEvents) && $upcomingEvents->isNotEmpty())
    <section class="home-section upcoming-events-section mb-5 py-4" data-aos="fade-up" data-aos-delay="200">
        <div class="row">
            <div class="col-12 text-center mb-4">
                <h2 class="section-title styled-title"><span>{{ $homePageSettings->getTranslation('upcoming_events_title', app()->getLocale()) ?? __('Événements à Venir') }}</span></h2>
            </div>
        </div>
        <div class="row">
            @foreach ($upcomingEvents as $event)
            <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                <div class="card event-card h-100">
                    {{-- LIGNE CORRIGÉE CI-DESSOUS --}}
                    <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'event_slug' => $event->getTranslation('slug', app()->getLocale()) ?? $event->id ]) }}" class="card-image-link">
                        <div class="card-image-container">
                            @if($event->featured_image_url)
                            <img src="{{ $event->image_url }}" class="card-img-top" alt="{{ $event->getTranslation('title', app()->getLocale()) }}">
                            @else
                            <img src="{{ asset('images/placeholder_event_card.jpg') }}" class="card-img-top" alt="{{ $event->getTranslation('title', app()->getLocale()) }}">
                            @endif
                            <div class="card-event-date-badge">
                                <span class="day">{{ $event->start_datetime->format('d') }}</span>
                                <span class="month">{{ $event->start_datetime->translatedFormat('M') }}</span>
                                <span class="year">{{ $event->start_datetime->format('Y') }}</span>
                            </div>
                        </div>
                    </a>
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            {{-- LIGNE CORRIGÉE CI-DESSOUS --}}
                            <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'event_slug' => $event->getTranslation('slug', app()->getLocale()) ?? $event->id ]) }}">
                                {{ Str::limit($event->getTranslation('title', app()->getLocale()), 55) }}
                            </a>
                        </h5>
                        <p class="card-details text-muted small mb-2">
                            <i class="fas fa-clock me-1"></i> {{ $event->start_datetime->translatedFormat('H:i') }}
                            @if($event->getTranslation('location_text', app()->getLocale()))
                            <br><i class="fas fa-map-marker-alt me-1 mt-1"></i> {{ Str::limit($event->getTranslation('location_text', app()->getLocale()), 30) }}
                            @endif
                        </p>
                        <p class="card-excerpt flex-grow-1">{{ Str::limit(strip_tags($event->getTranslation('description', app()->getLocale())), 100) }}</p>
                        {{-- LIGNE CORRIGÉE CI-DESSOUS --}}
                        <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'event_slug' => $event->getTranslation('slug', app()->getLocale()) ?? $event->id ]) }}" class="btn btn-outline-info mt-auto align-self-start">
                            {{ __('Voir les détails') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        <div class="row mt-4">
            <div class="col-12 text-center">
                <a href="{{ route('frontend.events.index', ['locale' => app()->getLocale()]) }}" class="btn btn-info text-white btn-lg">
                    {{ __('Tous nos événements') }} <i class="fas fa-arrow-right btn-icon-right"></i>
                </a>
            </div>
        </div>
    </section>
    @endif
</div> {{-- Fin du container homepage-content --}}

{{-- NEWSLETTER SECTION (EXISTANT - PEUT ÊTRE STYLÉ PLUS EN PROFONDEUR) --}}
<section class="newsletter-section mt-5 py-5" data-aos="fade-up"> {{-- Ajout de py-5 --}}
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8 text-center">
                <div class="newsletter-icon">
                    <i class="fas fa-envelope-open-text"></i> {{-- Icône changée --}}
                </div>
                <h2 class="newsletter-title">{{ $homePageSettings->getTranslation('newsletter_title', app()->getLocale()) ?? __('Restez informé de nos activités') }}</h2>
                <p class="newsletter-text mb-4">{{ $homePageSettings->getTranslation('newsletter_text', app()->getLocale()) ?? __('Inscrivez-vous à notre newsletter pour recevoir les dernières nouvelles et mises à jour.') }}</p>
                <form class="row g-2 justify-content-center newsletter-form-inline" action="#" method="POST">
                    @csrf
                    <div class="col-sm-8 col-md-7 col-lg-6">
                        <label for="newsletter_email" class="visually-hidden">{{ __('Votre adresse email') }}</label>
                        <input type="email" name="email" class="form-control form-control-lg" id="newsletter_email" placeholder="{{ __('Votre adresse email') }}" required>
                    </div>
                    <div class="col-sm-4 col-md-auto">
                        <button class="btn btn-primary btn-lg w-100" type="submit"> {{-- w-100 pour mobile --}}
                            {{ __('S\'inscrire') }} <i class="fas fa-paper-plane ms-1"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@push('scripts_frontend')
{{-- Le script pour les logos des partenaires est déjà là, nous pourrions l'améliorer ou le généraliser pour d'autres effets si besoin --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Effet de survol pour les logos des partenaires (le CSS le gère mieux maintenant)
        // const partnerLogos = document.querySelectorAll('.partner-logo-item img');
        // partnerLogos.forEach(logo => {
        //     logo.addEventListener('mouseover', () => {
        //         logo.style.filter = 'grayscale(0%)';
        //         logo.style.opacity = '1';
        //         logo.style.transform = 'scale(1.05)';
        //     });
        //     logo.addEventListener('mouseout', () => {
        //         logo.style.filter = 'grayscale(80%)'; /* Un peu moins gris par défaut */
        //         logo.style.opacity = '0.8';
        //         logo.style.transform = 'scale(1)';
        //     });
        // });
    });
</script>
@endpush
@extends('frontend.frontend')

@section('title', __('À Propos') . ' - ' . ($siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name')))
@section('meta_description', __('Découvrez qui nous sommes, notre mission et nos valeurs. L\'organisation NAMA œuvre pour un développement durable et l\'autonomisation des communautés.'))

@section('content')
<div class="container py-5">
    {{-- Hero Section --}}
    <div class="row mb-5">
        <div class="col-12 text-center" data-aos="fade-up">
            <h1 class="section-title styled-title mb-4">
                <span>{{ __('À Propos de Nous') }}</span>
            </h1>
            <p class="lead text-muted max-width-700 mx-auto">
                {{ __('Découvrez notre histoire, notre mission et notre engagement envers les communautés que nous servons.') }}
            </p>
        </div>
    </div>

    {{-- Mission Section --}}
    <section class="mb-5" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <img src="{{ asset('images/about/mission.jpg') }}" alt="{{ __('Notre Mission') }}" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6">
                <h2 class="h3 mb-4" style="color: var(--primary-color);">
                    <i class="fas fa-bullseye me-2"></i>{{ __('Notre Mission') }}
                </h2>
                <p class="mb-3">
                    {{ __('NAMA s\'engage à promouvoir le développement durable et à autonomiser les communautés défavorisées à travers des projets innovants et impactants.') }}
                </p>
                <p>
                    {{ __('Nous croyons fermement que chaque individu mérite l\'opportunité de réaliser son plein potentiel et de vivre dans la dignité, indépendamment de ses origines ou de sa situation.') }}
                </p>
                <div class="mt-4">
                    <a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}" class="btn btn-primary">
                        {{ __('Voir nos projets') }} <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Vision Section --}}
    <section class="mb-5" data-aos="fade-up">
        <div class="row align-items-center">
            <div class="col-lg-6 order-lg-2 mb-4 mb-lg-0">
                <img src="{{ asset('images/about/vision.jpg') }}" alt="{{ __('Notre Vision') }}" class="img-fluid rounded shadow">
            </div>
            <div class="col-lg-6 order-lg-1">
                <h2 class="h3 mb-4" style="color: var(--secondary-color);">
                    <i class="fas fa-eye me-2"></i>{{ __('Notre Vision') }}
                </h2>
                <p class="mb-3">
                    {{ __('Nous envisageons un monde où chaque communauté a accès aux ressources et opportunités nécessaires pour prospérer de manière durable.') }}
                </p>
                <p>
                    {{ __('Un monde où l\'égalité des chances, la justice sociale et la protection de l\'environnement ne sont plus des idéaux, mais une réalité tangible pour tous.') }}
                </p>
            </div>
        </div>
    </section>

    {{-- Values Section --}}
    <section class="mb-5" data-aos="fade-up">
        <div class="row">
            <div class="col-12 text-center mb-5">
                <h2 class="section-title styled-title">
                    <span>{{ __('Nos Valeurs') }}</span>
                </h2>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-handshake fa-3x" style="color: var(--primary-color);"></i>
                        </div>
                        <h5 class="card-title">{{ __('Intégrité') }}</h5>
                        <p class="card-text">{{ __('Nous agissons avec transparence, honnêteté et responsabilité dans toutes nos interventions.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-users fa-3x" style="color: var(--secondary-color);"></i>
                        </div>
                        <h5 class="card-title">{{ __('Collaboration') }}</h5>
                        <p class="card-text">{{ __('Nous travaillons en partenariat avec les communautés locales pour un impact maximal.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-lightbulb fa-3x" style="color: var(--accent-color);"></i>
                        </div>
                        <h5 class="card-title">{{ __('Innovation') }}</h5>
                        <p class="card-text">{{ __('Nous recherchons constamment des solutions créatives et efficaces aux défis contemporains.') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-3 mb-4">
                <div class="card h-100 text-center border-0 shadow-sm">
                    <div class="card-body p-4">
                        <div class="mb-3">
                            <i class="fas fa-leaf fa-3x" style="color: var(--primary-color);"></i>
                        </div>
                        <h5 class="card-title">{{ __('Durabilité') }}</h5>
                        <p class="card-text">{{ __('Nous mettons en œuvre des projets pérennes qui respectent l\'environnement.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Statistics Section --}}
    <section class="bg-light py-5 rounded mb-5" data-aos="fade-up">
        <div class="container">
            <div class="row text-center">
                <div class="col-6 col-md-3 mb-4">
                    <div class="stat-item">
                        <h3 class="stat-number mb-2" style="color: var(--primary-color);">50+</h3>
                        <p class="stat-label text-muted">{{ __('Projets Réalisés') }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <div class="stat-item">
                        <h3 class="stat-number mb-2" style="color: var(--secondary-color);">10,000+</h3>
                        <p class="stat-label text-muted">{{ __('Vies Impactées') }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <div class="stat-item">
                        <h3 class="stat-number mb-2" style="color: var(--accent-color);">15+</h3>
                        <p class="stat-label text-muted">{{ __('Partenaires') }}</p>
                    </div>
                </div>
                <div class="col-6 col-md-3 mb-4">
                    <div class="stat-item">
                        <h3 class="stat-number mb-2" style="color: var(--primary-color);">8</h3>
                        <p class="stat-label text-muted">{{ __('Années d\'Expérience') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="text-center" data-aos="fade-up">
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <h2 class="mb-4">{{ __('Rejoignez-nous dans notre mission') }}</h2>
                <p class="lead mb-4 text-muted">
                    {{ __('Ensemble, nous pouvons créer un impact positif et durable dans nos communautés.') }}
                </p>
                <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                    <a href="{{ route('frontend.contact.index', ['locale' => app()->getLocale()]) }}" class="btn btn-primary btn-lg">
                        <i class="fas fa-envelope me-2"></i>{{ __('Nous Contacter') }}
                    </a>
                    <a href="{{ route('frontend.events.index', ['locale' => app()->getLocale()]) }}" class="btn btn-outline-secondary btn-lg">
                        <i class="fas fa-calendar-alt me-2"></i>{{ __('Nos Événements') }}
                    </a>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
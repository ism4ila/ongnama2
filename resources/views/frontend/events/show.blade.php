@extends('frontend.frontend')

@section('title', $event->getTranslation('title', app()->getLocale()) . ' - ' . config('app.name'))
@section('meta_description', Str::limit(strip_tags($event->getTranslation('description', app()->getLocale())), 160))

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- Colonne principale --}}
        <div class="col-lg-8">
            <article class="event-single">
                {{-- Image principale --}}
                @if($event->featured_image_url)
                    <img src="{{ asset($event->featured_image_url) }}" 
                         alt="{{ $event->getTranslation('title', app()->getLocale()) }}" 
                         class="img-fluid rounded mb-4 shadow-sm w-100" 
                         style="max-height: 400px; object-fit: cover;">
                @endif

                {{-- Titre --}}
                <h1 class="mb-3" style="color: var(--primary-color); font-weight: 700;">
                    {{ $event->getTranslation('title', app()->getLocale()) }}
                </h1>
                
                {{-- Métadonnées de l'événement --}}
                <div class="event-meta mb-4 p-3 bg-light rounded border-start border-4" style="border-color: var(--primary-color) !important;">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>
                                <strong>{{ __('Date') }} :</strong><br>
                                <span class="ms-4">{{ $event->start_datetime->translatedFormat('l, d F Y') }}</span>
                            </p>
                            <p class="mb-2">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <strong>{{ __('Heure') }} :</strong><br>
                                <span class="ms-4">
                                    {{ $event->start_datetime->translatedFormat('H:i') }}
                                    @if($event->end_datetime)
                                        - {{ $event->end_datetime->translatedFormat('H:i') }}
                                    @endif
                                </span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            @if($event->getTranslation('location_text', app()->getLocale()))
                            <p class="mb-2">
                                <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                <strong>{{ __('Lieu') }} :</strong><br>
                                <span class="ms-4">{{ $event->getTranslation('location_text', app()->getLocale()) }}</span>
                            </p>
                            @endif
                            
                            {{-- Statut de l'événement --}}
                            <p class="mb-0">
                                <i class="fas fa-info-circle text-primary me-2"></i>
                                <strong>{{ __('Statut') }} :</strong><br>
                                <span class="ms-4">
                                    @if($event->start_datetime->isFuture())
                                        <span class="badge bg-success">{{ __('À venir') }}</span>
                                    @elseif($event->start_datetime->isToday())
                                        <span class="badge bg-warning">{{ __('Aujourd\'hui') }}</span>
                                    @else
                                        <span class="badge bg-secondary">{{ __('Terminé') }}</span>
                                    @endif
                                </span>
                            </p>
                        </div>
                    </div>
                </div>

                {{-- Contenu de l'événement --}}
                <div class="event-content">
                    {!! $event->getTranslation('description', app()->getLocale()) !!}
                </div>

                {{-- Section de partage social --}}
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-3">{{ __('Partager cet événement') }} :</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook-f me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($event->getTranslation('title', app()->getLocale())) }}" 
                           target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="fab fa-twitter me-1"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($event->getTranslation('title', app()->getLocale())) }}" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-linkedin-in me-1"></i> LinkedIn
                        </a>
                        <a href="whatsapp://send?text={{ urlencode($event->getTranslation('title', app()->getLocale()) . ' - ' . url()->current()) }}" 
                           class="btn btn-outline-success btn-sm">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </article>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Autres événements --}}
            @if($otherEvents->isNotEmpty())
            <div class="sidebar-widget">
                <h4 class="widget-title">{{ __('Autres événements à venir') }}</h4>
                <div class="list-group list-group-flush">
                    @foreach($otherEvents as $otherEvent)
                        <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'slug' => $otherEvent->getTranslation('slug', app()->getLocale())]) }}" 
                           class="list-group-item list-group-item-action border-0 px-0">
                            <div class="d-flex align-items-start">
                                @if($otherEvent->featured_image_url)
                                    <img src="{{ asset($otherEvent->featured_image_url) }}" 
                                         alt="{{ $otherEvent->getTranslation('title', app()->getLocale()) }}" 
                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-calendar-alt text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($otherEvent->getTranslation('title', app()->getLocale()), 50) }}</h6>
                                    <small class="text-muted">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ $otherEvent->start_datetime->translatedFormat('d M Y, H:i') }}
                                    </small>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('frontend.events.index', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary btn-sm">
                        {{ __('Voir tous les événements') }}
                    </a>
                </div>
            </div>
            @endif

            {{-- Widget d'information --}}
            <div class="sidebar-widget">
                <h4 class="widget-title">{{ __('Rejoignez-nous !') }}</h4>
                <p class="text-muted">
                    {{ __('Participez à nos événements et contribuez à faire une différence dans votre communauté.') }}
                </p>
                <a href="{{ route('frontend.contact.index', ['locale' => app()->getLocale()]) }}" 
                   class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-envelope me-1"></i>
                    {{ __('Nous contacter') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles_frontend')
<style>
    .event-single .event-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .event-single .event-content h2,
    .event-single .event-content h3,
    .event-single .event-content h4 {
        color: var(--secondary-color);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .event-single .event-content p {
        margin-bottom: 1rem;
        line-height: 1.7;
    }
    
    .sidebar-widget {
        margin-bottom: 2rem;
        padding: 1.5rem;
        background-color: var(--light-gray, #F8F9FA);
        border-radius: 10px;
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .sidebar-widget .widget-title {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 1rem;
        padding-bottom: 0.5rem;
        border-bottom: 2px solid var(--accent-color, #E9ECEF);
    }
    
    .sidebar-widget .list-group-item:hover {
        background-color: rgba(var(--primary-color-rgb, 0,123,255), 0.05);
    }
    
    .sidebar-widget .list-group-item:hover h6 {
        color: var(--primary-color);
    }
    
    [dir="rtl"] .sidebar-widget img,
    [dir="rtl"] .sidebar-widget .bg-light {
        margin-right: 0;
        margin-left: 1rem;
    }
</style>
@endpush
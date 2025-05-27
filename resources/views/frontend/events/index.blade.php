@extends('frontend.frontend')

@section('title', __('Événements') . ' - ' . config('app.name'))
@section('meta_description', __('Découvrez tous nos événements passés et à venir. Rejoignez-nous pour faire une différence dans votre communauté.'))

@section('content')
<div class="container py-5">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="section-title styled-title mb-4">
                <span>{{ __('Nos Événements') }}</span>
            </h1>
            <p class="lead text-muted">{{ __('Découvrez nos activités passées et rejoignez-nous pour les prochaines !') }}</p>
        </div>
    </div>

    {{-- Section Événements à venir --}}
    @if($upcomingEvents->isNotEmpty())
    <section class="mb-5">
        <div class="row mb-4">
            <div class="col-12">
                <h2 class="h3 mb-3" style="color: var(--primary-color);">
                    <i class="fas fa-calendar-plus me-2"></i>{{ __('Événements à venir') }}
                </h2>
            </div>
        </div>
        <div class="row">
            @foreach($upcomingEvents as $event)
            <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                <div class="card event-card h-100 shadow-sm">
                    <div class="card-image-container">
                        @if($event->featured_image_url)
                            <img src="{{ asset($event->featured_image_url) }}" 
                                 class="card-img-top" 
                                 alt="{{ $event->getTranslation('title', app()->getLocale()) }}"
                                 style="height: 200px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-calendar-alt fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        {{-- Badge de date --}}
                        <div class="card-event-date-badge">
                            <span class="day">{{ $event->start_datetime->format('d') }}</span>
                            <span class="month">{{ $event->start_datetime->translatedFormat('M') }}</span>
                            <span class="year">{{ $event->start_datetime->format('Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'slug' => $event->getTranslation('slug', app()->getLocale())]) }}" 
                               class="text-decoration-none">
                                {{ Str::limit($event->getTranslation('title', app()->getLocale()), 60) }}
                            </a>
                        </h5>
                        
                        <p class="card-details text-muted small mb-2">
                            <i class="fas fa-clock me-1"></i> 
                            {{ $event->start_datetime->translatedFormat('H:i') }}
                            @if($event->end_datetime)
                                - {{ $event->end_datetime->translatedFormat('H:i') }}
                            @endif
                            
                            @if($event->getTranslation('location_text', app()->getLocale()))
                                <br><i class="fas fa-map-marker-alt me-1 mt-1"></i> 
                                {{ Str::limit($event->getTranslation('location_text', app()->getLocale()), 40) }}
                            @endif
                        </p>
                        
                        <p class="card-excerpt flex-grow-1">
                            {{ Str::limit(strip_tags($event->getTranslation('description', app()->getLocale())), 120) }}
                        </p>
                        
                        <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'slug' => $event->getTranslation('slug', app()->getLocale())]) }}" 
                           class="btn btn-outline-primary mt-auto align-self-start">
                            {{ __('Voir les détails') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($upcomingEvents->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $upcomingEvents->appends(['past_page' => request('past_page')])->links() }}
            </div>
        </div>
        @endif
    </section>
    @endif

    {{-- Section Événements passés --}}
    @if($pastEvents->isNotEmpty())
    <section>
        <div class="row mb-4 mt-5">
            <div class="col-12">
                <h2 class="h3 mb-3" style="color: var(--secondary-color);">
                    <i class="fas fa-history me-2"></i>{{ __('Événements passés') }}
                </h2>
            </div>
        </div>
        <div class="row">
            @foreach($pastEvents as $event)
            <div class="col-md-6 col-lg-4 mb-4 d-flex align-items-stretch">
                <div class="card event-card h-100 shadow-sm opacity-75">
                    <div class="card-image-container">
                        @if($event->featured_image_url)
                            <img src="{{ asset($event->featured_image_url) }}" 
                                 class="card-img-top" 
                                 alt="{{ $event->getTranslation('title', app()->getLocale()) }}"
                                 style="height: 200px; object-fit: cover; filter: grayscale(30%);">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                <i class="fas fa-calendar-check fa-3x text-muted"></i>
                            </div>
                        @endif
                        
                        <div class="card-event-date-badge bg-secondary">
                            <span class="day">{{ $event->start_datetime->format('d') }}</span>
                            <span class="month">{{ $event->start_datetime->translatedFormat('M') }}</span>
                            <span class="year">{{ $event->start_datetime->format('Y') }}</span>
                        </div>
                    </div>
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">
                            <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'slug' => $event->getTranslation('slug', app()->getLocale())]) }}" 
                               class="text-decoration-none text-muted">
                                {{ Str::limit($event->getTranslation('title', app()->getLocale()), 60) }}
                            </a>
                        </h5>
                        
                        <p class="card-details text-muted small mb-2">
                            <i class="fas fa-clock me-1"></i> 
                            {{ $event->start_datetime->translatedFormat('H:i') }}
                            @if($event->end_datetime)
                                - {{ $event->end_datetime->translatedFormat('H:i') }}
                            @endif
                        </p>
                        
                        <p class="card-excerpt flex-grow-1 text-muted">
                            {{ Str::limit(strip_tags($event->getTranslation('description', app()->getLocale())), 120) }}
                        </p>
                        
                        <a href="{{ route('frontend.events.show', ['locale' => app()->getLocale(), 'slug' => $event->getTranslation('slug', app()->getLocale())]) }}" 
                           class="btn btn-outline-secondary mt-auto align-self-start">
                            {{ __('Voir les détails') }} <i class="fas fa-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        @if($pastEvents->hasPages())
        <div class="row mt-4">
            <div class="col-12 d-flex justify-content-center">
                {{ $pastEvents->appends(['upcoming_page' => request('upcoming_page')])->links() }}
            </div>
        </div>
        @endif
    </section>
    @endif

    {{-- Message si aucun événement --}}
    @if($upcomingEvents->isEmpty() && $pastEvents->isEmpty())
    <div class="row">
        <div class="col-12 text-center py-5">
            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
            <h3 class="text-muted">{{ __('Aucun événement pour le moment') }}</h3>
            <p class="text-muted">{{ __('Revenez bientôt pour découvrir nos prochaines activités !') }}</p>
        </div>
    </div>
    @endif
</div>
@endsection

@push('styles_frontend')
<style>
    .event-card {
        transition: transform 0.3s ease-in-out, box-shadow 0.3s ease-in-out;
    }
    
    .event-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }
    
    .card-event-date-badge {
        position: absolute;
        bottom: 10px;
        right: 10px;
        background-color: var(--primary-color);
        color: white;
        padding: 8px 12px;
        border-radius: 8px;
        text-align: center;
        line-height: 1.1;
        box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        z-index: 2;
    }
    
    .card-event-date-badge.bg-secondary {
        background-color: var(--secondary-color) !important;
    }
    
    .card-event-date-badge .day {
        display: block;
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .card-event-date-badge .month {
        display: block;
        font-size: 0.75rem;
        text-transform: uppercase;
        font-weight: 500;
    }
    
    .card-event-date-badge .year {
        display: block;
        font-size: 0.7rem;
        opacity: 0.8;
    }
    
    .card-image-container {
        position: relative;
        overflow: hidden;
    }
    
    .card-image-container img {
        transition: transform 0.3s ease;
    }
    
    .event-card:hover .card-image-container img {
        transform: scale(1.05);
    }
    
    [dir="rtl"] .card-event-date-badge {
        right: auto;
        left: 10px;
    }
</style>
@endpush
@extends('frontend.frontend')

@php
    $breadcrumbs = [
        // Correction ici: 'frontend.home'
        ['name' => __('Home'), 'url' => route('frontend.home', ['locale' => app()->getLocale()])],
        // Correction ici: 'frontend.events.index'
        ['name' => __('Events'), 'url' => route('frontend.events.index', ['locale' => app()->getLocale()])],
        ['name' => Str::limit($event->getTranslation('title', app()->getLocale()), 40), 'url' => null]
    ];
@endphp

@section('title'){{ $event->getTranslation('title', app()->getLocale()) }} - {{ config('app.name', 'ONG NAMA') }}@endsection
@section('meta_description'){{ Str::limit(strip_tags($event->getTranslation('description', app()->getLocale())), 160) }}@endsection

@section('main-content')
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s" style="background: url({{ $event->image ? asset('storage/' . $event->image) : asset('frontend/img/page-header-events.jpg') }}) center center no-repeat; background-size: cover;">
        <div class="container text-center py-5">
            <h1 class="display-4 text-white mb-4 animated slideInDown">{{ $event->getTranslation('title', app()->getLocale()) }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0">
                     @foreach($breadcrumbs as $breadcrumb)
                        @if($breadcrumb['url'])
                            <li class="breadcrumb-item"><a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a></li>
                        @else
                            <li class="breadcrumb-item text-white active" aria-current="page">{{ $breadcrumb['name'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </div>
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-5">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    @if($event->image)
                        <img class="img-fluid rounded w-100 mb-4" src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->getTranslation('title', app()->getLocale()) }}">
                    @endif
                    <h1 class="display-5 mb-3">{{ $event->getTranslation('title', app()->getLocale()) }}</h1>
                    
                    <div class="event-meta mb-4 border-bottom pb-3">
                        <p class="mb-1"><i class="fa fa-calendar-alt text-primary me-2"></i><strong>{{ __('Date & Time') }}:</strong> {{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('l, d F Y, H:i') }}</p>
                        @if($event->location)
                        <p class="mb-0"><i class="fa fa-map-marker-alt text-primary me-2"></i><strong>{{ __('Location') }}:</strong> {{ $event->getTranslation('location', app()->getLocale()) }}</p>
                        @endif
                    </div>

                    <div class="event-content-full">
                        {!! $event->getTranslation('description', app()->getLocale()) !!}
                    </div>

                    {{-- Section de partage social (optionnel) --}}
                    <div class="mt-4 pt-3 border-top">
                        <h5>{{ __('Share this event') }}:</h5>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i> Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($event->getTranslation('title', app()->getLocale())) }}" target="_blank" class="btn btn-outline-info me-2"><i class="fab fa-twitter"></i> Twitter</a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($event->getTranslation('title', app()->getLocale())) }}" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                        <a href="whatsapp://send?text={{ urlencode($event->getTranslation('title', app()->getLocale()) . ' - ' . url()->current()) }}" data-action="share/whatsapp/share" class="btn btn-outline-success"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                   {{-- Sidebar pour événements --}}
                   <div class="sidebar-widget">
                        @if(isset($otherEvents) && $otherEvents->count() > 0)
                        <h4 class="mb-4">{{__('Other Events')}}</h4>
                        <ul class="list-unstyled">
                            @foreach($otherEvents as $otherEvent)
                                <li class="mb-3 pb-3 border-bottom">
                                    <a href="{{ route('events.show', ['locale' => app()->getLocale(), 'event' => $otherEvent->slug]) }}">
                                        @if($otherEvent->image)
                                        <img src="{{ asset('storage/' . $otherEvent->image) }}" alt="{{ $otherEvent->getTranslation('title', app()->getLocale()) }}" class="img-fluid rounded mb-2" style="max-height: 80px; width: auto;">
                                        @endif
                                        <h6 class="mb-1">{{$otherEvent->getTranslation('title', app()->getLocale())}}</h6>
                                        <small class="text-muted"><i class="fa fa-calendar-alt me-1"></i> {{ \Carbon\Carbon::parse($otherEvent->event_date)->translatedFormat('d M Y') }}</small>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @else
                            <p>{{__('No other events at the moment.')}}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

@push('styles')
<style>
    .event-meta p {
        font-size: 1rem;
    }
    .event-content-full img {
        max-width: 100%;
        height: auto;
        border-radius: 0.25rem;
        margin-bottom: 1rem;
    }
    .sidebar-widget h4 {
        border-bottom: 2px solid var(--primary);
        padding-bottom: 10px;
    }
    .sidebar-widget ul li a:hover h6 {
        color: var(--primary);
    }
</style>
@endpush
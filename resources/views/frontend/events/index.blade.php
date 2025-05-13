@extends('frontend.frontend')

@php
    $breadcrumbs = [
        ['name' => __('Home'), 'url' => route('home', ['locale' => app()->getLocale()])],
        ['name' => __('Events'), 'url' => null]
    ];
@endphp

@section('title'){{ __('Events') }} - {{ config('app.name', 'ONG NAMA') }}@endsection

@section('main-content')
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s" style="background: url({{ asset('frontend/img/page-header-events.jpg') }}) center center no-repeat; background-size: cover;">
        <div class="container text-center py-5">
            <h1 class="display-2 text-white mb-4 animated slideInDown">{{ __('Our Events') }}</h1>
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
            <div class="text-center mx-auto wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                <p class="fs-5 fw-medium text-primary">{{ __('Discover Our Activities') }}</p>
                <h1 class="display-5 mb-5">{{ __('Upcoming & Past Events') }}</h1>
            </div>
            @if($events->count() > 0)
            <div class="row g-4 justify-content-center">
                @foreach($events as $event)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->iteration) }}s">
                    <div class="service-item rounded h-100">
                        <a href="{{ route('events.show', ['locale' => app()->getLocale(), 'event' => $event->slug]) }}">
                            <div class="position-relative">
                                <img class="img-fluid rounded-top" src="{{ $event->image ? asset('storage/' . $event->image) : asset('frontend/img/event-placeholder.jpg') }}" alt="{{ $event->getTranslation('title', app()->getLocale()) }}" style="height: 230px; width:100%; object-fit: cover;">
                                <div class="service-overlay">
                                    <a class="btn btn-lg-square btn-light_brand rounded-circle m-1" href="{{ $event->image ? asset('storage/' . $event->image) : asset('frontend/img/event-placeholder.jpg') }}" data-lightbox="event-{{$event->id}}"><i class="fa fa-eye"></i></a>
                                    <a class="btn btn-lg-square btn-light_brand rounded-circle m-1" href="{{ route('events.show', ['locale' => app()->getLocale(), 'event' => $event->slug]) }}"><i class="fa fa-link"></i></a>
                                </div>
                            </div>
                        </a>
                        <div class="p-4">
                            <p class="text-primary fw-medium mb-2">{{ \Carbon\Carbon::parse($event->event_date)->translatedFormat('d F Y, H:i') }}</p>
                            @if($event->location)
                            <p class="text-muted small mb-2"><i class="fa fa-map-marker-alt text-primary me-2"></i>{{ $event->getTranslation('location', app()->getLocale()) }}</p>
                            @endif
                            <h5 class="lh-base mb-3">
                                <a href="{{ route('events.show', ['locale' => app()->getLocale(), 'event' => $event->slug]) }}">{{ Str::limit($event->getTranslation('title', app()->getLocale()), 50) }}</a>
                            </h5>
                            <p>{{ Str::limit(strip_tags($event->getTranslation('description', app()->getLocale())), 100) }}</p>
                            <a class="btn btn-primary px-3 mt-2" href="{{ route('events.show', ['locale' => app()->getLocale(), 'event' => $event->slug]) }}">{{ __('Details') }}<i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
            <div class="mt-5 wow fadeInUp" data-wow-delay="0.7s">
                {{ $events->links() }}
            </div>
            @else
            <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                <p class="fs-5">{{ __('No events found at the moment. Please check back later!') }}</p>
            </div>
            @endif
        </div>
    </div>
    @endsection

@push('styles')
<style>
    .service-item {
        box-shadow: 0 0 45px rgba(0, 0, 0, .07);
        transition: .5s;
    }
    .service-item:hover {
        background: var(--primary);
    }
    .service-item:hover .service-overlay {
        opacity: 1;
    }
    .service-item:hover h5 a,
    .service-item:hover p,
    .service-item:hover .text-primary,
    .service-item:hover .text-muted {
        color: #FFFFFF !important;
    }
    .service-item:hover .btn-primary {
        background: #FFFFFF;
        color: var(--primary);
    }
    .service-overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 0, 0, 0.5); /* Un peu plus sombre pour un meilleur contraste */
        opacity: 0;
        transition: .5s;
    }
</style>
@endpush
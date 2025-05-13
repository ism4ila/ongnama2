@extends('frontend.frontend')

@php
    $breadcrumbs = [
        ['name' => __('Home'), 'url' => route('home', ['locale' => app()->getLocale()])],
        ['name' => __('Projects'), 'url' => route('projects.index', ['locale' => app()->getLocale()])],
        ['name' => Str::limit($project->getTranslation('title', app()->getLocale()), 40), 'url' => null]
    ];
@endphp

@section('title'){{ $project->getTranslation('title', app()->getLocale()) }} - {{ config('app.name', 'ONG NAMA') }}@endsection
@section('meta_description'){{ Str::limit(strip_tags($project->getTranslation('description', app()->getLocale())), 160) }}@endsection


@section('main-content')
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s" style="background: url({{ $project->image ? asset('storage/' . $project->image) : asset('frontend/img/page-header-projects.jpg') }}) center center no-repeat; background-size: cover;">
        <div class="container text-center py-5">
            <h1 class="display-4 text-white mb-4 animated slideInDown">{{ $project->getTranslation('title', app()->getLocale()) }}</h1>
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
                    @if($project->image)
                        <img class="img-fluid rounded w-100 mb-4" src="{{ asset('storage/' . $project->image) }}" alt="{{ $project->getTranslation('title', app()->getLocale()) }}">
                    @endif
                    <h1 class="display-5 mb-3">{{ $project->getTranslation('title', app()->getLocale()) }}</h1>
                    
                    <div class="project-meta mb-4 border-bottom pb-3">
                        @if($project->start_date)
                            <p class="mb-1"><i class="fa fa-calendar-alt text-primary me-2"></i><strong>{{ __('Duration') }}:</strong> 
                                {{ \Carbon\Carbon::parse($project->start_date)->translatedFormat('F Y') }}
                                @if($project->end_date)
                                    - {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('F Y') }}
                                @else
                                    - {{ __('Ongoing') }}
                                @endif
                            </p>
                        @endif
                        @if($project->location)
                        <p class="mb-0"><i class="fa fa-map-marker-alt text-primary me-2"></i><strong>{{ __('Location') }}:</strong> {{ $project->getTranslation('location', app()->getLocale()) }}</p>
                        @endif
                         @if($project->status)
                        <p class="mb-0 mt-1"><i class="fa fa-info-circle text-primary me-2"></i><strong>{{ __('Status') }}:</strong> {{ __(ucfirst($project->status)) }}</p>
                        @endif
                    </div>

                    <div class="project-content-full">
                        {!! $project->getTranslation('description', app()->getLocale()) !!}
                    </div>
                    
                    {{-- Galerie d'images pour le projet (si vous avez un modèle MediaItem lié) --}}
                    @if($project->mediaItems && $project->mediaItems->count() > 0)
                        <h4 class="mt-5 mb-3">{{__('Project Gallery')}}</h4>
                        <div class="row g-3 popup-gallery">
                            @foreach($project->mediaItems as $media)
                                <div class="col-md-4">
                                    <a href="{{ asset('storage/' . $media->file_path) }}" class="gallery-item">
                                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->getTranslation('alt_text', app()->getLocale()) ?? $project->getTranslation('title', app()->getLocale()) }}" class="img-fluid rounded">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif


                    <div class="mt-4 pt-3 border-top">
                        <h5>{{ __('Share this project') }}:</h5>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-facebook-f"></i> Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($project->getTranslation('title', app()->getLocale())) }}" target="_blank" class="btn btn-outline-info me-2"><i class="fab fa-twitter"></i> Twitter</a>
                         <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($project->getTranslation('title', app()->getLocale())) }}" target="_blank" class="btn btn-outline-primary me-2"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                        <a href="whatsapp://send?text={{ urlencode($project->getTranslation('title', app()->getLocale()) . ' - ' . url()->current()) }}" data-action="share/whatsapp/share" class="btn btn-outline-success"><i class="fab fa-whatsapp"></i> WhatsApp</a>
                    </div>
                </div>

                <div class="col-lg-4 wow fadeInUp" data-wow-delay="0.3s">
                   {{-- Sidebar pour projets --}}
                   <div class="sidebar-widget">
                        @if(isset($otherProjects) && $otherProjects->count() > 0)
                        <h4 class="mb-4">{{__('Other Projects')}}</h4>
                        <ul class="list-unstyled">
                            @foreach($otherProjects as $otherProject)
                                <li class="mb-3 pb-3 border-bottom">
                                    <a href="{{ route('projects.show', ['locale' => app()->getLocale(), 'project' => $otherProject->slug]) }}">
                                        @if($otherProject->image)
                                        <img src="{{ asset('storage/' . $otherProject->image) }}" alt="{{ $otherProject->getTranslation('title', app()->getLocale()) }}" class="img-fluid rounded mb-2" style="max-height: 80px; width: auto;">
                                        @endif
                                        <h6 class="mb-1">{{$otherProject->getTranslation('title', app()->getLocale())}}</h6>
                                        @if($otherProject->start_date)
                                        <small class="text-muted"><i class="fa fa-calendar-alt me-1"></i> 
                                            {{ \Carbon\Carbon::parse($otherProject->start_date)->translatedFormat('M Y') }}
                                            @if($otherProject->status == 'completed' && $otherProject->end_date) - {{ \Carbon\Carbon::parse($otherProject->end_date)->translatedFormat('M Y') }} @endif
                                        </small>
                                        @endif
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        @else
                            <p>{{__('No other projects at the moment.')}}</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endsection

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css">
<style>
    .project-meta p {
        font-size: 1rem;
    }
    .project-content-full img {
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
    .gallery-item img {
        transition: transform .3s ease-in-out;
    }
    .gallery-item:hover img {
        transform: scale(1.05);
    }
</style>
@endpush

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
<script>
$(document).ready(function() {
    $('.popup-gallery').magnificPopup({
        delegate: 'a.gallery-item',
        type: 'image',
        gallery:{
            enabled:true
        },
        mainClass: 'mfp-with-zoom', // this class is for CSS animation below
        zoom: {
            enabled: true, 
            duration: 300, // duration of the effect, in milliseconds
            easing: 'ease-in-out', // CSS transition easing function
             opener: function(openerElement) {
                return openerElement.is('img') ? openerElement : openerElement.find('img');
            }
        }
    });
});
</script>
@endpush
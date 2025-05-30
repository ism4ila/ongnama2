@extends('frontend.frontend')

@section('title', $project->getTranslation('title', app()->getLocale()) . ' - ' . ($siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name')))
@section('meta_description', Str::limit(strip_tags($project->getTranslation('description', app()->getLocale())), 160))

@section('content')
<div class="container py-5">
    <div class="row">
        {{-- Main Content --}}
        <div class="col-lg-8">
            <article class="project-single">
                {{-- Featured Image --}}
                @if($project->featured_image_url)
                    <img src="{{ asset('images/' . $project->featured_image_url) }}" 
                         alt="{{ $project->getTranslation('title', app()->getLocale()) }}" 
                         class="img-fluid rounded mb-4 shadow-sm w-100" 
                         style="max-height: 400px; object-fit: cover;">
                @endif

                {{-- Title --}}
                <h1 class="mb-3" style="color: var(--primary-color); font-weight: 700;">
                    {{ $project->getTranslation('title', app()->getLocale()) }}
                </h1>
                
                {{-- Project Meta --}}
                <div class="project-meta mb-4 p-3 bg-light rounded border-start border-4" style="border-color: var(--primary-color) !important;">
                    <div class="row">
                        <div class="col-md-6">
                            @if($project->start_date)
                                <p class="mb-2">
                                    <i class="fas fa-calendar-alt text-primary me-2"></i>
                                    <strong>{{ __('Période') }} :</strong><br>
                                    <span class="ms-4">
                                        {{ \Carbon\Carbon::parse($project->start_date)->translatedFormat('F Y') }}
                                        @if($project->end_date)
                                            - {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('F Y') }}
                                        @else
                                            - {{ __('En cours') }}
                                        @endif
                                    </span>
                                </p>
                            @endif
                            
                            @if($project->status)
                                <p class="mb-0">
                                    <i class="fas fa-info-circle text-primary me-2"></i>
                                    <strong>{{ __('Statut') }} :</strong><br>
                                    <span class="ms-4">
                                        @php
                                            $statusClass = 'bg-primary';
                                            $statusText = ucfirst($project->status);
                                            
                                            switch($project->status) {
                                                case 'ongoing':
                                                    $statusClass = 'bg-success';
                                                    $statusText = __('En cours');
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'bg-info';
                                                    $statusText = __('Terminé');
                                                    break;
                                                case 'planned':
                                                    $statusClass = 'bg-warning';
                                                    $statusText = __('Planifié');
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'bg-secondary';
                                                    $statusText = __('Annulé');
                                                    break;
                                            }
                                        @endphp
                                        <span class="badge {{ $statusClass }}">{{ $statusText }}</span>
                                    </span>
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($project->location_latitude && $project->location_longitude)
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <strong>{{ __('Localisation') }} :</strong><br>
                                    <span class="ms-4">
                                        {{ __('Lat') }}: {{ number_format($project->location_latitude, 4) }}<br>
                                        {{ __('Lng') }}: {{ number_format($project->location_longitude, 4) }}
                                    </span>
                                </p>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Project Content --}}
                <div class="project-content">
                    {!! $project->getTranslation('description', app()->getLocale()) !!}
                </div>

                {{-- Social Share --}}
                <div class="mt-5 pt-4 border-top">
                    <h5 class="mb-3">{{ __('Partager ce projet') }} :</h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(url()->current()) }}" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-facebook-f me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text={{ urlencode($project->getTranslation('title', app()->getLocale())) }}" 
                           target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="fab fa-twitter me-1"></i> Twitter
                        </a>
                        <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(url()->current()) }}&title={{ urlencode($project->getTranslation('title', app()->getLocale())) }}" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fab fa-linkedin-in me-1"></i> LinkedIn
                        </a>
                        <a href="whatsapp://send?text={{ urlencode($project->getTranslation('title', app()->getLocale()) . ' - ' . url()->current()) }}" 
                           class="btn btn-outline-success btn-sm">
                            <i class="fab fa-whatsapp me-1"></i> WhatsApp
                        </a>
                    </div>
                </div>
            </article>
        </div>

        {{-- Sidebar --}}
        <div class="col-lg-4">
            {{-- Other Projects --}}
            @if(isset($otherProjects) && $otherProjects->isNotEmpty())
            <div class="sidebar-widget">
                <h4 class="widget-title">{{ __('Autres projets') }}</h4>
                <div class="list-group list-group-flush">
                    @foreach($otherProjects as $otherProject)
                        <a href="{{ route('frontend.projects.show', ['locale' => app()->getLocale(), 'project' => $otherProject->id]) }}" 
                           class="list-group-item list-group-item-action border-0 px-0">
                            <div class="d-flex align-items-start">
                                @if($otherProject->featured_image_url)
                                    <img src="{{ asset('images/' . $otherProject->featured_image_url) }}" 
                                         alt="{{ $otherProject->getTranslation('title', app()->getLocale()) }}" 
                                         class="rounded me-3" style="width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                         style="width: 60px; height: 60px;">
                                        <i class="fas fa-project-diagram text-muted"></i>
                                    </div>
                                @endif
                                <div class="flex-grow-1">
                                    <h6 class="mb-1">{{ Str::limit($otherProject->getTranslation('title', app()->getLocale()), 50) }}</h6>
                                    @if($otherProject->start_date)
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            {{ \Carbon\Carbon::parse($otherProject->start_date)->translatedFormat('M Y') }}
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
                <div class="text-center mt-3">
                    <a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}" 
                       class="btn btn-outline-primary btn-sm">
                        {{ __('Voir tous les projets') }}
                    </a>
                </div>
            </div>
            @endif

            {{-- CTA Widget --}}
            <div class="sidebar-widget">
                <h4 class="widget-title">{{ __('Soutenez nos projets') }}</h4>
                <p class="text-muted">
                    {{ __('Votre soutien nous permet de continuer notre mission et d\'étendre notre impact positif.') }}
                </p>
                <a href="{{ route('frontend.contact.index', ['locale' => app()->getLocale()]) }}" 
                   class="btn btn-primary btn-sm w-100">
                    <i class="fas fa-heart me-1"></i>
                    {{ __('Nous soutenir') }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles_frontend')
<style>
    .project-single .project-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        margin: 1rem 0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }
    
    .project-single .project-content h2,
    .project-single .project-content h3,
    .project-single .project-content h4 {
        color: var(--secondary-color);
        margin-top: 2rem;
        margin-bottom: 1rem;
    }
    
    .project-single .project-content p {
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
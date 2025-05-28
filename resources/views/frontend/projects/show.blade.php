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
                    <img src="{{ $project->featured_image_url }}" 
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
                                        <span class="badge bg-primary">
                                            {{ __($project->status === 'ongoing' ? 'En cours' : ($project->status === 'completed' ? 'Terminé' : ($project->status === 'planned' ? 'Planifié' : ucfirst($project->status)))) }}
                                        </span>
                                    </span>
                                </p>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if($project->location_latitude && $project->location_longitude)
                                <p class="mb-2">
                                    <i class="fas fa-map-marker-alt text-primary me-2"></i>
                                    <strong>{{ __('Localisation') }} :</strong><br>
                                    <span class="ms-4">{{ $project->location_latitude }}, {{ $project->location_longitude }}</span>
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
                                    <img src="{{ $otherProject->featured_image_url }}" 
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
@extends('frontend.frontend')

@section('title', __('Nos Projets') . ' - ' . ($siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name')))
@section('meta_description', __('Découvrez tous nos projets de développement durable. Des initiatives qui transforment des vies et renforcent les communautés.'))

@section('content')
<div class="container py-5">
    {{-- Header Section --}}
    <div class="row mb-5">
        <div class="col-12 text-center" data-aos="fade-up">
            <h1 class="section-title styled-title mb-4">
                <span>{{ __('Nos Projets') }}</span>
            </h1>
            <p class="lead text-muted">
                {{ __('Découvrez nos initiatives qui transforment des vies et renforcent les communautés à travers le monde.') }}
            </p>
        </div>
    </div>

    {{-- Filters --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-wrap justify-content-center gap-2 mb-4">
                <a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}" 
                   class="btn {{ !request('status') ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                    {{ __('Tous') }}
                </a>
                <a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale(), 'status' => 'ongoing']) }}" 
                   class="btn {{ request('status') === 'ongoing' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                    {{ __('En cours') }}
                </a>
                <a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale(), 'status' => 'completed']) }}" 
                   class="btn {{ request('status') === 'completed' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                    {{ __('Terminés') }}
                </a>
                <a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale(), 'status' => 'planned']) }}" 
                   class="btn {{ request('status') === 'planned' ? 'btn-primary' : 'btn-outline-primary' }} btn-sm">
                    {{ __('Planifiés') }}
                </a>
            </div>
        </div>
    </div>

    {{-- Projects Grid --}}
    @if($projects->isNotEmpty())
        <div class="row">
            @foreach($projects as $project)
                <div class="col-lg-4 col-md-6 mb-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="{{ ($loop->index % 3) * 100 }}">
                    <div class="card project-card h-100 shadow-sm">
                        <div class="card-image-container">
                            @if($project->featured_image_url)
                                <img src="{{ $project->featured_image_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $project->getTranslation('title', app()->getLocale()) }}"
                                     style="height: 250px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                                    <i class="fas fa-project-diagram fa-3x text-muted"></i>
                                </div>
                            @endif
                            
                            {{-- Status Badge --}}
                            @if($project->status)
                                <span class="card-status-badge badge">
                                    {{ __($project->status === 'ongoing' ? 'En cours' : ($project->status === 'completed' ? 'Terminé' : ($project->status === 'planned' ? 'Planifié' : ucfirst($project->status)))) }}
                                </span>
                            @endif
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title">
                                <a href="{{ route('frontend.projects.show', ['locale' => app()->getLocale(), 'project' => $project->id]) }}" 
                                   class="text-decoration-none">
                                    {{ Str::limit($project->getTranslation('title', app()->getLocale()), 60) }}
                                </a>
                            </h5>
                            
                            <div class="project-meta mb-3">
                                @if($project->start_date)
                                    <small class="text-muted d-block">
                                        <i class="fas fa-calendar-alt me-1"></i>
                                        {{ \Carbon\Carbon::parse($project->start_date)->translatedFormat('M Y') }}
                                        @if($project->end_date)
                                            - {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('M Y') }}
                                        @elseif($project->status === 'ongoing')
                                            - {{ __('En cours') }}
                                        @endif
                                    </small>
                                @endif
                            </div>
                            
                            <p class="card-text flex-grow-1">
                                {{ Str::limit(strip_tags($project->getTranslation('description', app()->getLocale())), 120) }}
                            </p>
                            
                            <a href="{{ route('frontend.projects.show', ['locale' => app()->getLocale(), 'project' => $project->id]) }}" 
                               class="btn btn-outline-primary mt-auto align-self-start">
                                {{ __('En savoir plus') }} <i class="fas fa-arrow-right ms-1"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        {{-- Pagination --}}
        @if($projects->hasPages())
            <div class="row mt-5">
                <div class="col-12 d-flex justify-content-center">
                    {{ $projects->appends(request()->query())->links() }}
                </div>
            </div>
        @endif
    @else
        <div class="row">
            <div class="col-12 text-center py-5">
                <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">{{ __('Aucun projet trouvé') }}</h3>
                <p class="text-muted">
                    @if(request('status'))
                        {{ __('Aucun projet avec le statut sélectionné.') }}
                    @else
                        {{ __('Revenez bientôt pour découvrir nos prochains projets !') }}
                    @endif
                </p>
            </div>
        </div>
    @endif
</div>
@endsection
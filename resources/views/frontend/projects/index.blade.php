@extends('frontend.frontend')

@php
    $breadcrumbs = [
        ['name' => __('Home'), 'url' => route('home', ['locale' => app()->getLocale()])],
        ['name' => __('Projects'), 'url' => null]
    ];
@endphp

@section('title'){{ __('Our Projects') }} - {{ config('app.name', 'ONG NAMA') }}@endsection

@section('main-content')
    <div class="container-fluid page-header py-5 mb-5 wow fadeIn" data-wow-delay="0.1s" style="background: url({{ asset('frontend/img/page-header-projects.jpg') }}) center center no-repeat; background-size: cover;">
        <div class="container text-center py-5">
            <h1 class="display-2 text-white mb-4 animated slideInDown">{{ __('Our Projects') }}</h1>
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
                <p class="fs-5 fw-medium text-primary">{{ __('Making a Difference') }}</p>
                <h1 class="display-5 mb-5">{{ __('Completed & Ongoing Projects') }}</h1>
            </div>
             @if($projects->count() > 0)
            <div class="row g-4">
                @foreach($projects as $project)
                <div class="col-lg-4 col-md-6 wow fadeInUp" data-wow-delay="{{ 0.1 * ($loop->iteration) }}s">
                    <div class="project-item rounded overflow-hidden h-100">
                        <div class="position-relative">
                            <img class="img-fluid" src="{{ $project->image ? asset('storage/' . $project->image) : asset('frontend/img/project-placeholder.jpg') }}" alt="{{ $project->getTranslation('title', app()->getLocale()) }}" style="height: 250px; width:100%; object-fit: cover;">
                            <div class="project-overlay">
                                <a class="btn btn-lg-square btn-light_brand rounded-circle m-1" href="{{ $project->image ? asset('storage/' . $project->image) : asset('frontend/img/project-placeholder.jpg') }}" data-lightbox="project-{{$project->id}}"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-lg-square btn-light_brand rounded-circle m-1" href="{{ route('projects.show', ['locale' => app()->getLocale(), 'project' => $project->slug]) }}"><i class="fa fa-link"></i></a>
                            </div>
                        </div>
                        <div class="p-4">
                             @if($project->start_date)
                                <p class="text-muted small mb-1">
                                    <i class="fa fa-calendar-alt text-primary me-1"></i>
                                    {{ \Carbon\Carbon::parse($project->start_date)->translatedFormat('M Y') }}
                                    @if($project->end_date)
                                        - {{ \Carbon\Carbon::parse($project->end_date)->translatedFormat('M Y') }}
                                    @else
                                        - {{ __('Ongoing') }}
                                    @endif
                                </p>
                            @endif
                            <h5 class="lh-base mb-3">
                                <a href="{{ route('projects.show', ['locale' => app()->getLocale(), 'project' => $project->slug]) }}">{{ Str::limit($project->getTranslation('title', app()->getLocale()), 50) }}</a>
                            </h5>
                            <p>{{ Str::limit(strip_tags($project->getTranslation('description', app()->getLocale())), 100) }}</p>
                            <a href="{{ route('projects.show', ['locale' => app()->getLocale(), 'project' => $project->slug]) }}" class="btn btn-primary mt-2 px-3">{{ __('Learn More') }}<i class="fa fa-arrow-right ms-2"></i></a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
             <div class="mt-5 wow fadeInUp" data-wow-delay="0.7s">
                {{ $projects->links() }}
            </div>
            @else
            <div class="col-12 text-center wow fadeInUp" data-wow-delay="0.1s">
                <p class="fs-5">{{ __('No projects found at the moment. Please check back later!') }}</p>
            </div>
            @endif
        </div>
    </div>
    @endsection

@push('styles')
<style>
    .project-item {
        box-shadow: 0 0 45px rgba(0, 0, 0, .07);
        transition: .5s;
    }
     .project-item:hover {
        background: var(--light); /* Un léger fond au survol */
    }
    .project-item:hover .project-overlay {
        opacity: 1;
    }
    .project-overlay {
        position: absolute;
        width: 100%;
        height: 100%;
        top: 0;
        left: 0;
        display: flex;
        justify-content: center;
        align-items: center;
        background: rgba(0, 0, 0, 0.5);
        opacity: 0;
        transition: .5s;
    }
    .btn-light_brand {
        background-color: rgba(255,255,255,0.8);
        color: var(--primary);
    }
    .btn-light_brand:hover {
        background-color: var(--primary);
        color: #fff;
    }
</style>
@endpush
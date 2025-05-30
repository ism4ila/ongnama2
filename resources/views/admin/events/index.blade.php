@extends('admin.layouts.app')

@section('title', __('Gestion des Événements'))
@section('page-title', __('Liste des Événements'))

@section('page-actions')
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-icon-split">
        <span class="icon text-white-50"><i class="fas fa-plus-circle"></i></span>
        <span class="text">{{ __('Nouvel Événement') }}</span>
    </a>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-calendar-alt me-2"></i>{{ __('Tous les événements') }}
            </h6>
        </div>
        <div class="card-body">
            @include('layouts.partials.messages')

            @if($events->isEmpty())
                <div class="text-center py-4">
                    <i class="fas fa-calendar-times fa-3x text-gray-400 mb-3"></i>
                    <p class="text-muted">{{ __('Aucun événement trouvé pour le moment.') }}</p>
                    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus-circle me-1"></i> {{ __('Créer le premier événement') }}
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 15%;">{{ __('Image') }}</th>
                                <th style="width: 25%;">{{ __('Titre') }}</th>
                                <th style="width: 20%;">{{ __('Date de début') }}</th>
                                <th style="width: 20%;">{{ __('Date de fin') }}</th>
                                <th style="width: 15%;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($events as $event)
                                <tr>
                                    <td>{{ $loop->iteration + ($events->currentPage() - 1) * $events->perPage() }}</td>
                                    <td>
                                        @if($event->featured_image_url)
                                            <img src="{{ asset($event->featured_image_url) }}" 
                                                 alt="{{ $event->getTranslation('title', config('app.fallback_locale', 'en')) }}" 
                                                 class="img-thumbnail"
                                                 style="width: 80px; height: 60px; object-fit: cover;">
                                        @else
                                            <div class="bg-light border d-flex align-items-center justify-content-center text-muted"
                                                 style="width: 80px; height: 60px; font-size: 0.8rem;">
                                                <i class="fas fa-calendar-alt fa-lg"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <strong>{{ Str::limit($event->getTranslation('title', config('app.fallback_locale', 'en')), 40) }}</strong>
                                        @if($event->getTranslation('location_text', config('app.fallback_locale', 'en')))
                                            <br><small class="text-muted">
                                                <i class="fas fa-map-marker-alt"></i> 
                                                {{ Str::limit($event->getTranslation('location_text', config('app.fallback_locale', 'en')), 30) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $event->start_datetime->isFuture() ? 'bg-success' : 'bg-secondary' }}">
                                            {{ $event->start_datetime->format('d/m/Y H:i') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($event->end_datetime)
                                            {{ $event->end_datetime->format('d/m/Y H:i') }}
                                        @else
                                            <span class="text-muted">{{ __('Non définie') }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @include('admin.events._actions', ['event' => $event])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $events->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<link href="{{ asset('css/admin-events.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-events.js') }}"></script>
@endpush
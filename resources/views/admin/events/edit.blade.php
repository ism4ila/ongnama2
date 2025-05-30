@extends('admin.layouts.app')

@section('title', __('Modifier l\'Événement') . ': ' . $event->getTranslation('title', config('app.fallback_locale', 'en')))
@section('page-title')
    <i class="fas fa-edit me-2"></i>{{ __('Modifier l\'Événement') }}: 
    <span class="text-primary">{{ $event->getTranslation('title', config('app.fallback_locale', 'en')) }}</span>
@endsection

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-edit me-2"></i>{{ __('Formulaire de modification d\'événement') }}
            </h6>
        </div>
        <div class="card-body">
            @include('layouts.partials.messages')

            <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                @method('PUT')
                @include('admin.events._form', ['event' => $event, 'locales' => $locales])
            </form>
        </div>
    </div>
@endsection

@push('styles')
<link href="{{ asset('css/admin-events.css') }}" rel="stylesheet">
@endpush

@push('scripts')
<script src="{{ asset('js/admin-events.js') }}"></script>
@endpush
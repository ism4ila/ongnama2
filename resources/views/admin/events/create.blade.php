@extends('admin.layouts.app')

@section('title', __('Nouvel Événement'))
@section('page-title', __('Créer un nouvel événement'))

@section('content')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-plus-circle me-2"></i>{{ __('Formulaire de création d\'événement') }}
            </h6>
        </div>
        <div class="card-body">
            @include('layouts.partials.messages')

            <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
                @include('admin.events._form', ['locales' => $locales])
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
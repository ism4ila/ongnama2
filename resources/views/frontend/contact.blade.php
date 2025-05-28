@extends('frontend.frontend')

@section('title', __('Contactez-Nous'))

@section('content')
    <header class="page-header bg-light py-5">
        <div class="container">
            <h1 class="page-title">@yield('title')</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('frontend.home', app()->getLocale()) }}">{{ __('Accueil') }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                </ol>
            </nav>
        </div>
    </header>

    <main class="container py-5">
        <div class="row">
            <div class="col-lg-6 mb-4 mb-lg-0">
                <h2>{{ __('Envoyez-nous un Message') }}</h2>
                <p>{{ __("N'hésitez pas à nous contacter via le formulaire ci-dessous.") }}</p>

                {{-- Affichage des messages de session --}}
                @include('layouts.partials.messages') {{-- Ou votre partiel de messages --}}

                <form action="{{ route('frontend.contact.submit', app()->getLocale()) }}" method="POST">
                    @csrf {{-- Protection CSRF --}}

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Votre Nom') }}</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Votre Email') }}</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="subject" class="form-label">{{ __('Sujet') }}</label>
                        <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject" name="subject" value="{{ old('subject') }}" required>
                        @error('subject')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">{{ __('Votre Message') }}</label>
                        <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5" required>{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Optionnel: reCAPTCHA --}}

                    <button type="submit" class="btn btn-primary">{{ __('Envoyer') }}</button>
                </form>
            </div>
            <div class="col-lg-6">
                <h2>{{ __('Nos Coordonnées') }}</h2>
                <p><strong>{{ __('Adresse') }}:</strong> {{ setting('site_address', 'Votre Adresse Complète') }}</p>
                <p><strong>{{ __('Téléphone') }}:</strong> {{ setting('site_phone', '+123 456 7890') }}</p>
                <p><strong>{{ __('Email') }}:</strong> <a href="mailto:{{ setting('site_email', 'info@example.com') }}">{{ setting('site_email', 'info@example.com') }}</a></p>
                <p><strong>{{ __('Horaires') }}:</strong> {{ setting('site_hours', 'Lundi - Vendredi: 9h00 - 17h00') }}</p>

                {{-- Optionnel: Carte Google Maps --}}
                <div class="map-container mt-4">
                    {{-- Intégrez ici le code de votre carte Google Maps --}}
                    <iframe src="https://www.google.com/maps/embed?pb=..." width="100%" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                </div>
            </div>
        </div>
    </main>
@endsection
@extends('frontend.frontend')

@section('title', __('Contact') . ' - ' . ($siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name')))
@section('meta_description', __('Entrez en contact avec nous. Nous sommes là pour répondre à vos questions et discuter de nos projets et initiatives.'))

@section('content')
<div class="container py-5">
    {{-- Header --}}
    <div class="row mb-5">
        <div class="col-12 text-center" data-aos="fade-up">
            <h1 class="section-title styled-title mb-4">
                <span>{{ __('Contactez-nous') }}</span>
            </h1>
            <p class="lead text-muted">
                {{ __('Nous sommes là pour répondre à vos questions et discuter de nos projets et initiatives.') }}
            </p>
        </div>
    </div>

    <div class="row">
        {{-- Contact Form --}}
        <div class="col-lg-8 mb-5">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-envelope me-2"></i>
                        {{ __('Envoyez-nous un message') }}
                    </h4>
                </div>
                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('frontend.contact.store', ['locale' => app()->getLocale()]) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">{{ __('Nom complet') }} <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">{{ __('Adresse e-mail') }} <span class="text-danger">*</span></label>
                                <input type="email" 
                                       class="form-control @error('email') is-invalid @enderror" 
                                       id="email" 
                                       name="email" 
                                       value="{{ old('email') }}" 
                                       required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">{{ __('Téléphone') }}</label>
                                <input type="tel" 
                                       class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" 
                                       name="phone" 
                                       value="{{ old('phone') }}">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="subject" class="form-label">{{ __('Sujet') }} <span class="text-danger">*</span></label>
                                <select class="form-select @error('subject') is-invalid @enderror" id="subject" name="subject" required>
                                    <option value="">{{ __('Choisir un sujet') }}</option>
                                    <option value="general" {{ old('subject') === 'general' ? 'selected' : '' }}>{{ __('Demande générale') }}</option>
                                    <option value="partnership" {{ old('subject') === 'partnership' ? 'selected' : '' }}>{{ __('Partenariat') }}</option>
                                    <option value="volunteering" {{ old('subject') === 'volunteering' ? 'selected' : '' }}>{{ __('Bénévolat') }}</option>
                                    <option value="donation" {{ old('subject') === 'donation' ? 'selected' : '' }}>{{ __('Don/Soutien') }}</option>
                                    <option value="media" {{ old('subject') === 'media' ? 'selected' : '' }}>{{ __('Média/Presse') }}</option>
                                </select>
                                @error('subject')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="mb-3">
                          <label for="message" class="form-label">{{ __('Message') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control @error('message') is-invalid @enderror" 
                                      id="message" 
                                      name="message" 
                                      rows="6" 
                                      required 
                                      placeholder="{{ __('Décrivez votre demande ou votre message...') }}">{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3 form-check">
                            <input type="checkbox" 
                                   class="form-check-input @error('privacy') is-invalid @enderror" 
                                   id="privacy" 
                                   name="privacy" 
                                   value="1" 
                                   {{ old('privacy') ? 'checked' : '' }} 
                                   required>
                            <label class="form-check-label" for="privacy">
                                {{ __('J\'accepte que mes données soient utilisées pour répondre à ma demande') }} <span class="text-danger">*</span>
                            </label>
                            @error('privacy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-paper-plane me-2"></i>
                            {{ __('Envoyer le message') }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Contact Info --}}
        <div class="col-lg-4">
            {{-- Contact Details --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-secondary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        {{ __('Nos coordonnées') }}
                    </h5>
                </div>
                <div class="card-body">
                    @if($siteSettingsGlobal->getTranslation('contact_address', app()->getLocale()))
                        <div class="contact-item mb-3">
                            <i class="fas fa-map-marker-alt text-primary me-3"></i>
                            <div>
                                <strong>{{ __('Adresse') }}</strong><br>
                                {{ $siteSettingsGlobal->getTranslation('contact_address', app()->getLocale()) }}
                            </div>
                        </div>
                    @endif
                    
                    @if($siteSettingsGlobal->contact_phone)
                        <div class="contact-item mb-3">
                            <i class="fas fa-phone text-primary me-3"></i>
                            <div>
                                <strong>{{ __('Téléphone') }}</strong><br>
                                <a href="tel:{{ $siteSettingsGlobal->contact_phone }}" class="text-decoration-none">
                                    {{ $siteSettingsGlobal->contact_phone }}
                                </a>
                            </div>
                        </div>
                    @endif
                    
                    @if($siteSettingsGlobal->contact_email)
                        <div class="contact-item mb-3">
                            <i class="fas fa-envelope text-primary me-3"></i>
                            <div>
                                <strong>{{ __('E-mail') }}</strong><br>
                                <a href="mailto:{{ $siteSettingsGlobal->contact_email }}" class="text-decoration-none">
                                    {{ $siteSettingsGlobal->contact_email }}
                                </a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Social Media --}}
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-info text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-share-alt me-2"></i>
                        {{ __('Suivez-nous') }}
                    </h5>
                </div>
                <div class="card-body text-center">
                    <div class="social-links d-flex justify-content-center gap-3">
                        @if($siteSettingsGlobal->social_facebook_url)
                            <a href="{{ $siteSettingsGlobal->social_facebook_url }}" target="_blank" class="btn btn-outline-primary btn-lg">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                        @endif
                        @if($siteSettingsGlobal->social_twitter_url)
                            <a href="{{ $siteSettingsGlobal->social_twitter_url }}" target="_blank" class="btn btn-outline-info btn-lg">
                                <i class="fab fa-twitter"></i>
                            </a>
                        @endif
                        @if($siteSettingsGlobal->social_instagram_url)
                            <a href="{{ $siteSettingsGlobal->social_instagram_url }}" target="_blank" class="btn btn-outline-danger btn-lg">
                                <i class="fab fa-instagram"></i>
                            </a>
                        @endif
                        @if($siteSettingsGlobal->social_linkedin_url)
                            <a href="{{ $siteSettingsGlobal->social_linkedin_url }}" target="_blank" class="btn btn-outline-primary btn-lg">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Office Hours --}}
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        {{ __('Horaires d\'ouverture') }}
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Lundi - Vendredi') }}</span>
                        <span>08:00 - 17:00</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>{{ __('Samedi') }}</span>
                        <span>09:00 - 12:00</span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span>{{ __('Dimanche') }}</span>
                        <span class="text-muted">{{ __('Fermé') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Map Section --}}
    @if($siteSettingsGlobal->contact_map_iframe_url)
        <div class="row mt-5">
            <div class="col-12" data-aos="fade-up">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h4 class="mb-0">
                            <i class="fas fa-map me-2"></i>
                            {{ __('Notre localisation') }}
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="map-container" style="height: 400px;">
                            <iframe src="{{ $siteSettingsGlobal->contact_map_iframe_url }}" 
                                    width="100%" 
                                    height="400" 
                                    style="border:0;" 
                                    allowfullscreen="" 
                                    loading="lazy" 
                                    referrerpolicy="no-referrer-when-downgrade">
                            </iframe>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles_frontend')
<style>
    .contact-item {
        display: flex;
        align-items: flex-start;
    }
    .contact-item i {
        margin-top: 3px;
        flex-shrink: 0;
    }
    .social-links a {
        transition: transform 0.3s ease;
    }
    .social-links a:hover {
        transform: translateY(-2px);
    }
</style>
@endpush
@extends('admin.layouts.app')

@section('title', __('Paramètres de la Page d\'Accueil'))

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
    </div>

    @include('layouts.partials.messages')

    <form method="POST" action="{{ route('admin.settings.homepage.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Section Hero --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                 <h6 class="m-0 font-weight-bold text-primary">{{ __('Section Héros') }}</h6>
                 <ul class="nav nav-tabs" id="heroTabs" role="tablist">
                    @foreach($locales as $localeCode => $localeProperties)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="hero-{{ $localeCode }}-tab" data-toggle="tab" href="#hero-{{ $localeCode }}" role="tab" aria-controls="hero-{{ $localeCode }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $localeProperties['native'] }} @if($localeProperties['is_fallback']) ({{__('Défaut')}}) @endif</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                 <div class="tab-content" id="heroTabsContent">
                    @foreach($locales as $localeCode => $localeProperties)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="hero-{{ $localeCode }}" role="tabpanel" aria-labelledby="hero-{{ $localeCode }}-tab">
                            {{-- Hero Title --}}
                            <div class="form-group">
                                <label for="hero_title_{{ $localeCode }}">{{ __('Titre du Héros') }} ({{ strtoupper($localeCode) }}) <span class="text-danger @if($localeProperties['is_fallback']) @else d-none @endif">*</span></label>
                                <input type="text" name="hero_title[{{ $localeCode }}]" id="hero_title_{{ $localeCode }}" class="form-control @error('hero_title.' . $localeCode) is-invalid @enderror" value="{{ old('hero_title.' . $localeCode, $homePageSettings->getTranslation('hero_title', $localeCode, false)) }}">
                                @error('hero_title.' . $localeCode) <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            {{-- Hero Subtitle --}}
                            <div class="form-group">
                                <label for="hero_subtitle_{{ $localeCode }}">{{ __('Sous-titre du Héros') }} ({{ strtoupper($localeCode) }})</label>
                                <textarea name="hero_subtitle[{{ $localeCode }}]" id="hero_subtitle_{{ $localeCode }}" class="form-control @error('hero_subtitle.' . $localeCode) is-invalid @enderror" rows="2">{{ old('hero_subtitle.' . $localeCode, $homePageSettings->getTranslation('hero_subtitle', $localeCode, false)) }}</textarea>
                                @error('hero_subtitle.' . $localeCode) <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            {{-- Hero Button Text --}}
                            <div class="form-group">
                                <label for="hero_button_text_{{ $localeCode }}">{{ __('Texte du Bouton du Héros') }} ({{ strtoupper($localeCode) }})</label>
                                <input type="text" name="hero_button_text[{{ $localeCode }}]" id="hero_button_text_{{ $localeCode }}" class="form-control @error('hero_button_text.' . $localeCode) is-invalid @enderror" value="{{ old('hero_button_text.' . $localeCode, $homePageSettings->getTranslation('hero_button_text', $localeCode, false)) }}">
                                @error('hero_button_text.' . $localeCode) <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
                <hr>
                 {{-- Hero Button Link --}}
                <div class="form-group">
                    <label for="hero_button_link">{{ __('Lien du Bouton du Héros') }}</label>
                    <input type="text" name="hero_button_link" id="hero_button_link" class="form-control @error('hero_button_link') is-invalid @enderror" value="{{ old('hero_button_link', $homePageSettings->hero_button_link) }}" placeholder="/projects ou {{ route('frontend.projects.index') }}">
                    @error('hero_button_link') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                {{-- Hero Background Image --}}
                <div class="form-group">
                    <label for="hero_background_image">{{ __('Image de Fond du Héros') }}</label>
                    <input type="file" name="hero_background_image" id="hero_background_image" class="form-control-file @error('hero_background_image') is-invalid @enderror">
                    @if($homePageSettings->hero_background_image)
                        <img src="{{ Storage::url($homePageSettings->hero_background_image) }}" alt="Image de fond actuelle" class="img-thumbnail mt-2" style="max-height: 150px;">
                        <div class="form-check mt-1">
                            <input class="form-check-input" type="checkbox" name="remove_hero_background_image" id="remove_hero_background_image" value="1">
                            <label class="form-check-label" for="remove_hero_background_image">
                                {{ __('Supprimer l\'image actuelle') }}
                            </label>
                        </div>
                    @endif
                    @error('hero_background_image') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        {{-- Section Newsletter & Titres des sections --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Titres des Sections & Newsletter') }}</h6>
                 <ul class="nav nav-tabs" id="sectionsTabs" role="tablist">
                    @foreach($locales as $localeCode => $localeProperties)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="sections-{{ $localeCode }}-tab" data-toggle="tab" href="#sections-{{ $localeCode }}" role="tab" aria-controls="sections-{{ $localeCode }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $localeProperties['native'] }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                 <div class="tab-content" id="sectionsTabsContent">
                     @foreach($locales as $localeCode => $localeProperties)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="sections-{{ $localeCode }}" role="tabpanel" aria-labelledby="sections-{{ $localeCode }}-tab">
                            {{-- Newsletter Title --}}
                            <div class="form-group">
                                <label for="newsletter_title_{{ $localeCode }}">{{ __('Titre Newsletter') }} ({{ strtoupper($localeCode) }})</label>
                                <input type="text" name="newsletter_title[{{ $localeCode }}]" id="newsletter_title_{{ $localeCode }}" class="form-control" value="{{ old('newsletter_title.' . $localeCode, $homePageSettings->getTranslation('newsletter_title', $localeCode, false)) }}">
                            </div>
                            {{-- Newsletter Text --}}
                            <div class="form-group">
                                <label for="newsletter_text_{{ $localeCode }}">{{ __('Texte Newsletter') }} ({{ strtoupper($localeCode) }})</label>
                                <textarea name="newsletter_text[{{ $localeCode }}]" id="newsletter_text_{{ $localeCode }}" class="form-control" rows="2">{{ old('newsletter_text.' . $localeCode, $homePageSettings->getTranslation('newsletter_text', $localeCode, false)) }}</textarea>
                            </div>
                            <hr>
                            {{-- Latest Projects Title --}}
                            <div class="form-group"><label for="latest_projects_title_{{ $localeCode }}">{{ __('Titre Projets Récents') }} ({{ strtoupper($localeCode) }})</label><input type="text" name="latest_projects_title[{{ $localeCode }}]" class="form-control" value="{{ old('latest_projects_title.' . $localeCode, $homePageSettings->getTranslation('latest_projects_title', $localeCode, false)) }}"></div>
                            {{-- Latest Posts Title --}}
                            <div class="form-group"><label for="latest_posts_title_{{ $localeCode }}">{{ __('Titre Articles Récents') }} ({{ strtoupper($localeCode) }})</label><input type="text" name="latest_posts_title[{{ $localeCode }}]" class="form-control" value="{{ old('latest_posts_title.' . $localeCode, $homePageSettings->getTranslation('latest_posts_title', $localeCode, false)) }}"></div>
                            {{-- Upcoming Events Title --}}
                            <div class="form-group"><label for="upcoming_events_title_{{ $localeCode }}">{{ __('Titre Événements à Venir') }} ({{ strtoupper($localeCode) }})</label><input type="text" name="upcoming_events_title[{{ $localeCode }}]" class="form-control" value="{{ old('upcoming_events_title.' . $localeCode, $homePageSettings->getTranslation('upcoming_events_title', $localeCode, false)) }}"></div>
                            {{-- Partners Title --}}
                            <div class="form-group"><label for="partners_title_{{ $localeCode }}">{{ __('Titre Partenaires') }} ({{ strtoupper($localeCode) }})</label><input type="text" name="partners_title[{{ $localeCode }}]" class="form-control" value="{{ old('partners_title.' . $localeCode, $homePageSettings->getTranslation('partners_title', $localeCode, false)) }}"></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">{{ __('Enregistrer les Paramètres') }}</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    // Activer les onglets Bootstrap
    $(function () {
        $('#siteSettingsTabs a, #heroTabs a, #sectionsTabs a').on('click', function (e) {
            e.preventDefault();
            $(this).tab('show');
        });
    });
</script>
@endpush
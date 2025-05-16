@extends('admin.layouts.app') {{-- Ou votre layout admin principal --}}

@section('title', __('Paramètres du Site'))

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
    </div>

    @include('layouts.partials.messages') {{-- Pour afficher les messages de succès/erreur --}}

    <form method="POST" action="{{ route('admin.settings.site.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Informations Générales') }}</h6>
                <ul class="nav nav-tabs" id="siteSettingsTabs" role="tablist">
                    @foreach($locales as $localeCode => $localeProperties)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" id="site-{{ $localeCode }}-tab" data-toggle="tab" href="#site-{{ $localeCode }}" role="tab" aria-controls="site-{{ $localeCode }}" aria-selected="{{ $loop->first ? 'true' : 'false' }}">{{ $localeProperties['native'] }} @if($localeProperties['is_fallback']) ({{__('Défaut')}}) @endif</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="siteSettingsTabsContent">
                    @foreach($locales as $localeCode => $localeProperties)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="site-{{ $localeCode }}" role="tabpanel" aria-labelledby="site-{{ $localeCode }}-tab">
                            {{-- Site Name --}}
                            <div class="form-group">
                                <label for="site_name_{{ $localeCode }}">{{ __('Nom du Site') }} ({{ strtoupper($localeCode) }}) <span class="text-danger @if($localeProperties['is_fallback']) @else d-none @endif">*</span></label>
                                <input type="text" name="site_name[{{ $localeCode }}]" id="site_name_{{ $localeCode }}" class="form-control @error('site_name.' . $localeCode) is-invalid @enderror" value="{{ old('site_name.' . $localeCode, $siteSettings->getTranslation('site_name', $localeCode, false)) }}">
                                @error('site_name.' . $localeCode) <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            {{-- Footer Description --}}
                            <div class="form-group">
                                <label for="footer_description_{{ $localeCode }}">{{ __('Description du Pied de Page') }} ({{ strtoupper($localeCode) }})</label>
                                <textarea name="footer_description[{{ $localeCode }}]" id="footer_description_{{ $localeCode }}" class="form-control @error('footer_description.' . $localeCode) is-invalid @enderror" rows="3">{{ old('footer_description.' . $localeCode, $siteSettings->getTranslation('footer_description', $localeCode, false)) }}</textarea>
                                @error('footer_description.' . $localeCode) <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                            
                            {{-- Contact Address --}}
                            <div class="form-group">
                                <label for="contact_address_{{ $localeCode }}">{{ __('Adresse de Contact') }} ({{ strtoupper($localeCode) }})</label>
                                <input type="text" name="contact_address[{{ $localeCode }}]" id="contact_address_{{ $localeCode }}" class="form-control @error('contact_address.' . $localeCode) is-invalid @enderror" value="{{ old('contact_address.' . $localeCode, $siteSettings->getTranslation('contact_address', $localeCode, false)) }}">
                                @error('contact_address.' . $localeCode) <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>

                            {{-- Footer Copyright Text --}}
                            <div class="form-group">
                                <label for="footer_copyright_text_{{ $localeCode }}">{{ __('Texte de Copyright du Pied de Page') }} ({{ strtoupper($localeCode) }})</label>
                                <input type="text" name="footer_copyright_text[{{ $localeCode }}]" id="footer_copyright_text_{{ $localeCode }}" class="form-control @error('footer_copyright_text.' . $localeCode) is-invalid @enderror" value="{{ old('footer_copyright_text.' . $localeCode, $siteSettings->getTranslation('footer_copyright_text', $localeCode, false)) }}">
                                <small class="form-text text-muted">{{ __("Utilisez {year} pour afficher l'année actuelle.") }}</small>
                                @error('footer_copyright_text.' . $localeCode) <span class="invalid-feedback">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                 <h6 class="m-0 font-weight-bold text-primary">{{ __('Coordonnées & Réseaux Sociaux') }}</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        {{-- Contact Phone --}}
                        <div class="form-group">
                            <label for="contact_phone">{{ __('Téléphone de Contact') }}</label>
                            <input type="text" name="contact_phone" id="contact_phone" class="form-control @error('contact_phone') is-invalid @enderror" value="{{ old('contact_phone', $siteSettings->contact_phone) }}">
                            @error('contact_phone') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Contact Email --}}
                        <div class="form-group">
                            <label for="contact_email">{{ __('Email de Contact') }}</label>
                            <input type="email" name="contact_email" id="contact_email" class="form-control @error('contact_email') is-invalid @enderror" value="{{ old('contact_email', $siteSettings->contact_email) }}">
                            @error('contact_email') <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>
                 {{-- Contact Map Iframe URL --}}
                <div class="form-group">
                    <label for="contact_map_iframe_url">{{ __('URL Iframe Google Maps') }}</label>
                    <textarea name="contact_map_iframe_url" id="contact_map_iframe_url" class="form-control @error('contact_map_iframe_url') is-invalid @enderror" rows="3">{{ old('contact_map_iframe_url', $siteSettings->contact_map_iframe_url) }}</textarea>
                    @error('contact_map_iframe_url') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                {{-- Social Links --}}
                <div class="row">
                     <div class="col-md-6 form-group"><label for="social_facebook_url">{{__('Facebook URL')}}</label><input type="url" name="social_facebook_url" class="form-control" value="{{ old('social_facebook_url', $siteSettings->social_facebook_url) }}"></div>
                     <div class="col-md-6 form-group"><label for="social_twitter_url">{{__('Twitter URL')}}</label><input type="url" name="social_twitter_url" class="form-control" value="{{ old('social_twitter_url', $siteSettings->social_twitter_url) }}"></div>
                     <div class="col-md-6 form-group"><label for="social_instagram_url">{{__('Instagram URL')}}</label><input type="url" name="social_instagram_url" class="form-control" value="{{ old('social_instagram_url', $siteSettings->social_instagram_url) }}"></div>
                     <div class="col-md-6 form-group"><label for="social_linkedin_url">{{__('LinkedIn URL')}}</label><input type="url" name="social_linkedin_url" class="form-control" value="{{ old('social_linkedin_url', $siteSettings->social_linkedin_url) }}"></div>
                     <div class="col-md-6 form-group"><label for="social_youtube_url">{{__('Youtube URL')}}</label><input type="url" name="social_youtube_url" class="form-control" value="{{ old('social_youtube_url', $siteSettings->social_youtube_url) }}"></div>
                </div>
            </div>
        </div>

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                 <h6 class="m-0 font-weight-bold text-primary">{{ __('Apparence & Identité Visuelle') }}</h6>
            </div>
             <div class="card-body">
                <div class="row">
                    {{-- Favicon --}}
                    <div class="col-md-4 form-group">
                        <label for="favicon">{{ __('Favicon') }} (.png, .ico, .gif)</label>
                        <input type="file" name="favicon" id="favicon" class="form-control-file @error('favicon') is-invalid @enderror">
                         @if($siteSettings->favicon) <img src="{{ Storage::url($siteSettings->favicon) }}" alt="Favicon actuel" class="img-thumbnail mt-2" style="max-height: 50px;"> <input type="checkbox" name="remove_favicon" value="1"> {{__('Supprimer actuel')}} @endif
                        @error('favicon') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                    </div>
                    {{-- Logo Principal --}}
                     <div class="col-md-4 form-group">
                        <label for="logo_path">{{ __('Logo Principal') }} (.png, .svg, .jpg)</label>
                        <input type="file" name="logo_path" id="logo_path" class="form-control-file @error('logo_path') is-invalid @enderror">
                         @if($siteSettings->logo_path) <img src="{{ Storage::url($siteSettings->logo_path) }}" alt="Logo actuel" class="img-thumbnail mt-2 bg-dark" style="max-height: 70px;">  <input type="checkbox" name="remove_logo_path" value="1"> {{__('Supprimer actuel')}} @endif
                        @error('logo_path') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                    </div>
                    {{-- Footer Logo --}}
                    <div class="col-md-4 form-group">
                        <label for="footer_logo_path">{{ __('Logo du Pied de Page') }}</label>
                        <input type="file" name="footer_logo_path" id="footer_logo_path" class="form-control-file @error('footer_logo_path') is-invalid @enderror">
                        @if($siteSettings->footer_logo_path) <img src="{{ Storage::url($siteSettings->footer_logo_path) }}" alt="Logo pied de page actuel" class="img-thumbnail mt-2 bg-dark" style="max-height: 70px;"> <input type="checkbox" name="remove_footer_logo_path" value="1"> {{__('Supprimer actuel')}} @endif
                        @error('footer_logo_path') <span class="invalid-feedback d-block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <hr>
                {{-- Couleurs --}}
                <div class="row">
                    <div class="col-md-3 form-group"> <label for="primary_color">{{__('Couleur Principale')}}</label><input type="color" name="primary_color" class="form-control" value="{{ old('primary_color', $siteSettings->primary_color) }}"></div>
                    <div class="col-md-3 form-group"> <label for="secondary_color">{{__('Couleur Secondaire')}}</label><input type="color" name="secondary_color" class="form-control" value="{{ old('secondary_color', $siteSettings->secondary_color) }}"></div>
                    <div class="col-md-3 form-group"> <label for="accent_color">{{__('Couleur d\'Accentuation')}}</label><input type="color" name="accent_color" class="form-control" value="{{ old('accent_color', $siteSettings->accent_color) }}"></div>
                     <div class="col-md-3 form-group">
                        <label for="default_direction">{{ __('Direction du texte par défaut') }}</label>
                        <select name="default_direction" id="default_direction" class="form-control @error('default_direction') is-invalid @enderror">
                            <option value="ltr" {{ old('default_direction', $siteSettings->default_direction) == 'ltr' ? 'selected' : '' }}>{{ __('Gauche à Droite (LTR)') }}</option>
                            <option value="rtl" {{ old('default_direction', $siteSettings->default_direction) == 'rtl' ? 'selected' : '' }}>{{ __('Droite à Gauche (RTL)') }}</option>
                        </select>
                        @error('default_direction') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="card-footer text-right">
            <button type="submit" class="btn btn-primary">{{ __('Enregistrer les Paramètres du Site') }}</button>
        </div>
    </form>
</div>
@endsection
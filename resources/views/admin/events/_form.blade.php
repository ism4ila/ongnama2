@csrf
<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <p class="text-muted">
                <i class="fas fa-info-circle"></i> {{ __('Les champs marqués d\'un') }} <span class="text-danger">*</span> {{ __('sont obligatoires au moins dans la langue par défaut.') }}
            </p>
        </div>

        {{-- Onglets pour les langues --}}
        <ul class="nav nav-tabs" id="eventLocaleTabs" role="tablist">
            @php $defaultLocale = config('app.fallback_locale', 'en'); @endphp
            @foreach($locales as $localeCode => $properties)
                <li class="nav-item">
                    <a class="nav-link {{ $loop->first ? 'active' : '' }} @if($properties['is_fallback']) fw-bold @endif"
                       id="tab-{{ $localeCode }}-link" data-toggle="tab" href="#content-{{ $localeCode }}"
                       role="tab" aria-controls="content-{{ $localeCode }}"
                       aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                        {{ $properties['native'] }}
                        @if($properties['is_fallback'])
                            <span class="badge bg-secondary ms-1" style="font-size: 0.7em;">{{ __('Défaut') }}</span>
                        @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="eventLocaleTabsContent">
            @foreach($locales as $localeCode => $properties)
                <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                     id="content-{{ $localeCode }}" role="tabpanel"
                     aria-labelledby="tab-{{ $localeCode }}-link"
                     @if($localeCode == 'ar') dir="rtl" @endif>
                    <div class="pt-3">
                        {{-- Titre --}}
                        <div class="form-group mb-3">
                            <label for="title_{{ $localeCode }}" class="form-label">
                                {{ __('Titre') }} ({{ strtoupper($localeCode) }})
                                @if($properties['is_fallback']) <span class="text-danger">*</span> @endif
                            </label>
                            <input type="text"
                                   class="form-control @error('title.' . $localeCode) is-invalid @enderror"
                                   id="title_{{ $localeCode }}"
                                   name="title[{{ $localeCode }}]"
                                   value="{{ old('title.' . $localeCode, isset($event) ? $event->getTranslation('title', $localeCode, false) : '') }}"
                                   placeholder="{{ __('Titre de l\'événement en') }} {{ strtolower($properties['native']) }}">
                            @error('title.' . $localeCode)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group mb-3">
                            <label for="description_{{ $localeCode }}" class="form-label">
                                {{ __('Description') }} ({{ strtoupper($localeCode) }})
                                @if($properties['is_fallback']) <span class="text-danger">*</span> @endif
                            </label>
                            <textarea class="form-control @error('description.' . $localeCode) is-invalid @enderror"
                                      id="description_{{ $localeCode }}"
                                      name="description[{{ $localeCode }}]"
                                      rows="5"
                                      placeholder="{{ __('Description de l\'événement en') }} {{ strtolower($properties['native']) }}">{{ old('description.' . $localeCode, isset($event) ? $event->getTranslation('description', $localeCode, false) : '') }}</textarea>
                            @error('description.' . $localeCode)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Lieu --}}
                        <div class="form-group mb-3">
                            <label for="location_text_{{ $localeCode }}" class="form-label">
                                {{ __('Lieu') }} ({{ strtoupper($localeCode) }})
                            </label>
                            <input type="text"
                                   class="form-control @error('location_text.' . $localeCode) is-invalid @enderror"
                                   id="location_text_{{ $localeCode }}"
                                   name="location_text[{{ $localeCode }}]"
                                   value="{{ old('location_text.' . $localeCode, isset($event) ? $event->getTranslation('location_text', $localeCode, false) : '') }}"
                                   placeholder="{{ __('Lieu de l\'événement en') }} {{ strtolower($properties['native']) }}">
                            @error('location_text.' . $localeCode)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Dates et Image --}}
<div class="row mt-4">
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="start_datetime" class="form-label">{{ __('Date et heure de début') }} <span class="text-danger">*</span></label>
            <input type="datetime-local"
                   class="form-control @error('start_datetime') is-invalid @enderror"
                   id="start_datetime"
                   name="start_datetime"
                   value="{{ old('start_datetime', isset($event) && $event->start_datetime ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}"
                   required>
            @error('start_datetime')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group mb-3">
            <label for="end_datetime" class="form-label">{{ __('Date et heure de fin') }}</label>
            <input type="datetime-local"
                   class="form-control @error('end_datetime') is-invalid @enderror"
                   id="end_datetime"
                   name="end_datetime"
                   value="{{ old('end_datetime', isset($event) && $event->end_datetime ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}">
            @error('end_datetime')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
</div>

{{-- Image mise en avant --}}
<div class="row">
    <div class="col-12">
        <div class="form-group mb-3">
            <label for="featured_image" class="form-label">{{ __('Image mise en avant') }}</label>
            <input type="file"
                   class="form-control @error('featured_image') is-invalid @enderror"
                   id="featured_image"
                   name="featured_image"
                   accept="image/*">
            <div class="form-text">{{ __('Formats acceptés: JPG, PNG, GIF, WEBP. Taille max: 5MB.') }}</div>
            @error('featured_image')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Aperçu de la nouvelle image --}}
        <div id="imagePreview" style="display: none;" class="mb-3">
            <img id="previewImg" src="#" alt="{{ __('Aperçu') }}" class="img-thumbnail" style="max-height: 200px;">
        </div>

        {{-- Image actuelle si modification --}}
        @if(isset($event) && $event->featured_image_url)
            <div class="mb-3">
                <label class="form-label">{{ __('Image actuelle') }}:</label><br>
                <img src="{{ asset($event->featured_image_url) }}" 
                     alt="{{ __('Image actuelle') }}" 
                     class="img-thumbnail mb-2" 
                     style="max-height: 150px;">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remove_featured_image" id="remove_featured_image" value="1">
                    <label class="form-check-label" for="remove_featured_image">
                        {{ __('Supprimer l\'image actuelle') }}
                    </label>
                </div>
            </div>
        @endif
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> {{ isset($event) ? __('Mettre à jour') : __('Enregistrer') }}
    </button>
    <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
        <i class="fas fa-times-circle me-1"></i> {{ __('Annuler') }}
    </a>
</div>
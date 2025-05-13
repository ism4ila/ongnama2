@csrf
<div class="row">
    <div class="col-12">
        <div class="mb-3">
            <p class="text-muted">
                <i class="fas fa-info-circle"></i> {{ __('Les champs marqués d\'un') }} <span class="text-danger">*</span> {{ __('sont obligatoires au moins dans la langue par défaut (EN).') }}
            </p>
        </div>

        {{-- Onglets pour les langues (Bootstrap 4) --}}
        <ul class="nav nav-tabs" id="categoryLocaleTabs" role="tablist">
            @php $defaultLocale = config('app.fallback_locale', 'en'); @endphp
            @foreach($locales as $index => $locale)
                <li class="nav-item">
                    <a class="nav-link {{ $index == 0 ? 'active' : '' }} @if($locale == $defaultLocale) fw-bold @endif"
                       id="tab-{{ $locale }}-link" data-toggle="tab" href="#content-{{ $locale }}"
                       role="tab" aria-controls="content-{{ $locale }}"
                       aria-selected="{{ $index == 0 ? 'true' : 'false' }}">
                        {{ strtoupper($locale) }}
                        @if($locale == $defaultLocale)
                            <span class="badge bg-secondary ms-1" style="font-size: 0.7em;">{{ __('Défaut') }}</span>
                        @endif
                        @if($locale == 'ar') <i class="fas fa-language ms-1" title="{{__('Arabe - RTL')}}"></i> @endif
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content" id="categoryLocaleTabsContent">
            @foreach($locales as $index => $locale)
                <div class="tab-pane fade {{ $index == 0 ? 'show active' : '' }}"
                     id="content-{{ $locale }}" role="tabpanel"
                     aria-labelledby="tab-{{ $locale }}-link"
                     @if($locale == 'ar') dir="rtl" @endif >   {{-- Ajout de dir="rtl" pour l'arabe --}}
                    <div class="pt-3">
                        {{-- Champ Nom --}}
                        <div class="form-group mb-3">
                            <label for="name_{{ $locale }}" class="form-label @if($locale == 'ar') float-end @endif">
                                {{ __('Nom') }} ({{ strtoupper($locale) }})
                                @if($locale == $defaultLocale) <span class="text-danger">*</span> @endif
                            </label>
                            <input type="text"
                                   class="form-control @error('name.' . $locale) is-invalid @enderror @if($locale == 'ar') text-right @endif"
                                   id="name_{{ $locale }}"
                                   name="name[{{ $locale }}]"
                                   value="{{ old('name.' . $locale, isset($category) ? $category->getTranslation('name', $locale, false) : '') }}"
                                   placeholder="{{ __('Nom de la catégorie en') }} {{ strtolower($locale) }}">
                            @error('name.' . $locale)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Champ Description --}}
                        <div class="form-group mb-3">
                            <label for="description_{{ $locale }}" class="form-label @if($locale == 'ar') float-end @endif">
                                {{ __('Description') }} ({{ strtoupper($locale) }})
                            </label>
                            <textarea class="form-control @error('description.' . $locale) is-invalid @enderror @if($locale == 'ar') text-right @endif"
                                      id="description_{{ $locale }}"
                                      name="description[{{ $locale }}]"
                                      rows="4" {{-- Augmenté un peu la hauteur --}}
                                      placeholder="{{ __('Description de la catégorie en') }} {{ strtolower($locale) }}">{{ old('description.' . $locale, isset($category) ? $category->getTranslation('description', $locale, false) : '') }}</textarea>
                            @error('description.' . $locale)
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-success">
        <i class="fas fa-save me-1"></i> {{ isset($category) ? __('Mettre à jour') : __('Enregistrer') }}
    </button>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
        <i class="fas fa-times-circle me-1"></i> {{ __('Annuler') }}
    </a>
</div>
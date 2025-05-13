@csrf
<div class="row">
    <div class="col-md-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Détails de l'Événement</h6>
            </div>
            <div class="card-body">
                {{-- Onglets pour les langues --}}
                <ul class="nav nav-tabs" id="langTabs" role="tablist">
                    @foreach ($locales as $i => $locale)
                    <li class="nav-item" role="presentation">
                        <button class="nav-link {{ $i == 0 ? 'active' : '' }}" id="{{ $locale }}-tab" data-bs-toggle="tab" data-bs-target="#{{ $locale }}-tab-pane" type="button" role="tab" aria-controls="{{ $locale }}-tab-pane" aria-selected="{{ $i == 0 ? 'true' : 'false' }}">{{ strtoupper($locale) }}</button>
                    </li>
                    @endforeach
                </ul>
                <div class="tab-content pt-3" id="langTabsContent">
                    @foreach ($locales as $i => $locale)
                    <div class="tab-pane fade {{ $i == 0 ? 'show active' : '' }}" id="{{ $locale }}-tab-pane" role="tabpanel" aria-labelledby="{{ $locale }}-tab" tabindex="0">
                        {{-- Titre --}}
                        <div class="form-group mb-3">
                            <label for="title_{{ $locale }}">Titre ({{ strtoupper($locale) }}) <span class="{{ $locale == config('app.fallback_locale') ? 'text-danger' : '' }}">*</span></label>
                            <input type="text" name="title[{{ $locale }}]" id="title_{{ $locale }}" class="form-control @error('title.'.$locale) is-invalid @enderror" value="{{ old('title.'.$locale, $event->getTranslation('title', $locale, false) ?? '') }}">
                            @error('title.'.$locale) <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        {{-- Description --}}
                        <div class="form-group mb-3">
                            <label for="description_{{ $locale }}">Description ({{ strtoupper($locale) }})</label>
                            <textarea name="description[{{ $locale }}]" id="description_{{ $locale }}" class="form-control trumbowyg-editor @error('description.'.$locale) is-invalid @enderror" rows="5">{{ old('description.'.$locale, $event->getTranslation('description', $locale, false) ?? '') }}</textarea>
                            @error('description.'.$locale) <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>
                        
                        {{-- Lieu --}}
                        <div class="form-group mb-3">
                            <label for="location_text_{{ $locale }}">Lieu ({{ strtoupper($locale) }})</label>
                            <input type="text" name="location_text[{{ $locale }}]" id="location_text_{{ $locale }}" class="form-control @error('location_text.'.$locale) is-invalid @enderror" value="{{ old('location_text.'.$locale, $event->getTranslation('location_text', $locale, false) ?? '') }}">
                            @error('location_text.'.$locale) <span class="invalid-feedback">{{ $message }}</span> @enderror
                        </div>

                        {{-- Slug (Affiché si existant, mais généré automatiquement) --}}
                        @if(isset($event) && $event->getTranslation('slug', $locale, false))
                        <div class="form-group mb-3">
                            <label>Slug ({{ strtoupper($locale) }})</label>
                            <input type="text" class="form-control" value="{{ $event->getTranslation('slug', $locale, false) }}" disabled readonly>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Dates et Image</h6>
            </div>
            <div class="card-body">
                {{-- Date de début --}}
                <div class="form-group mb-3">
                    <label for="start_datetime">Date et Heure de Début <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="start_datetime" id="start_datetime" class="form-control @error('start_datetime') is-invalid @enderror" value="{{ old('start_datetime', $event->start_datetime ? $event->start_datetime->format('Y-m-d\TH:i') : '') }}" required>
                    @error('start_datetime') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Date de fin --}}
                <div class="form-group mb-3">
                    <label for="end_datetime">Date et Heure de Fin (Optionnel)</label>
                    <input type="datetime-local" name="end_datetime" id="end_datetime" class="form-control @error('end_datetime') is-invalid @enderror" value="{{ old('end_datetime', $event->end_datetime ? $event->end_datetime->format('Y-m-d\TH:i') : '') }}">
                    @error('end_datetime') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>

                {{-- Champ pour URL d'image externe OU Upload --}}
                <div class="form-group mb-3">
                    <label for="featured_image_url_text">URL de l'Image (Optionnel)</label>
                    <input type="url" name="featured_image_url_text" id="featured_image_url_text" class="form-control @error('featured_image_url_text') is-invalid @enderror" value="{{ old('featured_image_url_text', (isset($event) && strpos($event->featured_image_url, 'http') === 0) ? $event->featured_image_url : '') }}" placeholder="https://example.com/image.jpg">
                    @error('featured_image_url_text') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                
                <div class="form-group mb-3">
                    <label for="featured_image_file">OU Télécharger une Image (Optionnel)</label>
                    <input type="file" name="featured_image_file" id="featured_image_file" class="form-control @error('featured_image_file') is-invalid @enderror">
                    @error('featured_image_file') <span class="invalid-feedback">{{ $message }}</span> @enderror
                    @if(isset($event) && $event->featured_image_url)
                        <div class="mt-2">
                            <small>Image actuelle :</small><br>
                            @if(strpos($event->featured_image_url, 'http') === 0)
                                <img src="{{ $event->featured_image_url }}" alt="Image actuelle" style="max-width: 150px; max-height: 100px; border-radius: 5px;">
                            @else
                                <img src="{{ Storage::url($event->featured_image_url) }}" alt="Image actuelle" style="max-width: 150px; max-height: 100px; border-radius: 5px;">
                            @endif
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" name="remove_featured_image" id="remove_featured_image" value="1">
                                <label class="form-check-label" for="remove_featured_image">
                                    Supprimer l'image actuelle
                                </label>
                            </div>
                        </div>
                    @endif
                </div>
                
                {{-- Statut (si vous l'ajoutez) --}}
                {{-- <div class="form-group mb-3">
                    <label for="status">Statut <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                        <option value="draft" {{ old('status', $event->status ?? '') == 'draft' ? 'selected' : '' }}>Brouillon</option>
                        <option value="published" {{ old('status', $event->status ?? '') == 'published' ? 'selected' : '' }}>Publié</option>
                        <option value="cancelled" {{ old('status', $event->status ?? '') == 'cancelled' ? 'selected' : '' }}>Annulé</option>
                    </select>
                    @error('status') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div> --}}
            </div>
        </div>
        <button type="submit" class="btn btn-primary">{{ isset($event) ? 'Mettre à jour' : 'Créer' }} l'Événement</button>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Annuler</a>
    </div>
</div>
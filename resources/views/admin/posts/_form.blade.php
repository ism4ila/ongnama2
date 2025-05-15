{{-- Affichage des erreurs de validation générales (non liées à un champ spécifique) --}}
@php
    $fieldsToExclude = [];
    if (isset($locales)) {
        $localeKeys = array_keys($locales); // Obtenir les clés 'en', 'fr', 'ar', etc.
        // Pour les champs 'title' au lieu de 'name' (basé sur votre migration de Post)
        $fieldsToExclude = array_merge(
            array_map(fn($locKey) => "title.$locKey", $localeKeys),
            array_map(fn($locKey) => "body.$locKey", $localeKeys),
            array_map(fn($locKey) => "excerpt.$locKey", $localeKeys)
        );
    } else {
        // Si $locales n'est pas défini, valeurs par défaut (adaptez si nécessaire)
        $fieldsToExclude = ['title', 'body', 'excerpt'];
    }
    // Ajoutez d'autres champs non traduits spécifiques à Post ici
    $fieldsToExclude = array_merge($fieldsToExclude, ['category_id', 'status', 'published_at', 'featured_image']);
@endphp

@if ($errors->any() && !$errors->hasAny($fieldsToExclude))
    <div class="alert alert-danger rounded-lg shadow-sm border-left border-danger border-4 alert-dismissible fade show" role="alert">
        <div class="d-flex">
            <div class="mr-3">
                <i class="fas fa-exclamation-circle fa-2x text-danger"></i>
            </div>
            <div>
                <h5 class="alert-heading">{{ __('Veuillez corriger les erreurs suivantes') }}</h5>
                <ul class="mb-0 pl-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button> {{-- Pour BS4 --}}
        {{-- <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> --}} {{-- Pour BS5 --}}
    </div>
@endif

<div class="row">
    {{-- ... le reste du fichier _form.blade.php ... --}}

    <div class="col-md-8">
        {{-- Champs traduisibles --}}
        <div class="card card-outline card-primary shadow-sm rounded-lg">
            <div class="card-header bg-gradient-light">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-pen-fancy mr-2"></i>{{ __('Contenu de l\'Article') }}
                </h3>
                <div class="card-tools">
                    <ul class="nav nav-pills nav-sm ml-auto">
                        {{-- Ici $locales est un tableau associatif clé => tableau de propriétés --}}
                        {{-- $localeCode sera 'en', 'fr', 'ar' --}}
                        {{-- $properties sera ['native' => 'English'], ['native' => 'Français'], etc. --}}
                        @foreach($locales as $localeCode => $properties)
                        <li class="nav-item">
                            <a class="nav-link {{ $loop->first ? 'active' : '' }} rounded-pill px-3"
                                href="#content-{{ $localeCode }}" {{-- $localeCode est bien une chaîne ici ('en', 'fr', ...) --}}
                                data-toggle="tab"
                                style="font-size: 0.9rem;">
                                <img src="{{ asset('vendor/blade-flags/country-'.($localeCode == 'en' ? 'us' : $localeCode).'.svg') }}"
                                    width="18"
                                    alt="{{ $localeCode }}" {{-- $localeCode est bien une chaîne --}}
                                    class="mr-1" />
                                {{ $properties['native'] }} {{-- UTILISEZ CECI --}}
                            </a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
            <div class="card-body">
                <div class="tab-content">
                    {{-- $localeCode sera 'en', 'fr', 'ar' --}}
                    {{-- $properties sera ['native' => 'English'], ['native' => 'Français'], etc. --}}
                    @foreach($locales as $localeCode => $properties)
                    <div class="tab-pane p-2 {{ $loop->first ? 'active' : '' }}" id="content-{{ $localeCode }}">
                        <div class="bg-light p-2 mb-3 rounded border-left border-primary border-4">
                            <small class="text-muted">{{ __('Vous éditez la version') }} <strong>{{ strtoupper($localeCode) }}</strong>{{ __(' de cet article') }}</small>
                        </div>

                        {{-- Titre --}}
                        <div class="form-group">
                            <label for="title-{{ $localeCode }}" class="font-weight-bold">
                                {{ __('Titre') }}
                                <span class="badge badge-info">{{ strtoupper($localeCode) }}</span>
                                @if(isset($properties['is_fallback']) && $properties['is_fallback']) <span class="text-danger">*</span> @endif
                                {{-- Ou si vous voulez toujours un * pour la première langue dans la boucle : --}}
                                {{-- @if($loop->first) <span class="text-danger">*</span> @endif --}}
                            </label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-heading"></i></span>
                                </div>
                                <input type="text"
                                    name="title[{{ $localeCode }}]"
                                    id="title-{{ $localeCode }}"
                                    class="form-control @error('title.' . $localeCode) is-invalid @enderror"
                                    placeholder="{{ __('Entrez le titre de l\'article en') }} {{ $properties['native'] }}"
                                    value="{{ old('title.' . $localeCode, $post->getTranslation('title', $localeCode, false)) }}">
                                @error('title.' . $localeCode)
                                <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i>
                                {{ __('Un bon titre est court, descriptif et contient des mots-clés importants.') }}
                            </small>
                        </div>

                        {{-- Corps de l'article (Avec éditeur Riche) --}}
                        <div class="form-group">
                            <label for="body-{{ $localeCode }}" class="font-weight-bold">
                                {{ __('Corps de l\'article') }}
                                <span class="badge badge-info">{{ strtoupper($localeCode) }}</span>
                                @if(isset($properties['is_fallback']) && $properties['is_fallback']) <span class="text-danger">*</span> @endif
                                {{-- @if($loop->first) <span class="text-danger">*</span> @endif --}}
                            </label>

                            {{-- ... Votre barre d'outils d'éditeur ... --}}
                            <div class="editor-toolbar border rounded p-1 mb-1 bg-light">
                                {{-- ... Vos boutons de barre d'outils ... --}}
                            </div>

                            <textarea name="body[{{ $localeCode }}]"
                                id="body-{{ $localeCode }}"
                                class="form-control @error('body.' . $localeCode) is-invalid @enderror"
                                rows="15"
                                placeholder="{{ __('Écrivez le contenu de votre article ici en') }} {{ $properties['native'] }}...">{{ old('body.' . $localeCode, $post->getTranslation('body', $localeCode, false)) }}</textarea>
                            @error('body.' . $localeCode)
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <small class="form-text text-muted mt-1">
                                <i class="fas fa-keyboard"></i>
                                {{ __('Astuce : Utilisez Markdown ou HTML pour formater votre texte. Ctrl+B pour gras, Ctrl+I pour italique.') }}
                            </small>
                        </div>

                        {{-- Extrait --}}
                        <div class="form-group">
                            <label for="excerpt-{{ $localeCode }}" class="font-weight-bold">
                                {{ __('Extrait (Résumé)') }}
                                <span class="badge badge-info">{{ strtoupper($localeCode) }}</span>
                            </label>
                            <textarea name="excerpt[{{ $localeCode }}]"
                                id="excerpt-{{ $localeCode }}"
                                class="form-control @error('excerpt.' . $localeCode) is-invalid @enderror"
                                rows="3"
                                placeholder="{{ __('Écrivez un court résumé de votre article en') }} {{ $properties['native'] }}...">{{ old('excerpt.' . $localeCode, $post->getTranslation('excerpt', $localeCode, false)) }}</textarea>
                            @error('excerpt.' . $localeCode)
                            <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                            @enderror
                            <small class="form-text text-muted">
                                <i class="fas fa-info-circle"></i>
                                {{ __('L\'extrait est utilisé dans les listes d\'articles et les résultats de recherche.') }}
                            </small>
                        </div>

                        @if(!(isset($properties['is_fallback']) && $properties['is_fallback']))
                        {{-- Ou si vous voulez toujours un * pour la première langue dans la boucle : --}}
                        {{-- @if(!$loop->first) --}}
                        <div class="alert alert-info d-flex align-items-center rounded-lg border-left border-info border-4 mt-2 mb-0">
                            <i class="fas fa-lightbulb mr-3 fa-lg"></i>
                            <div>
                                {{ __('Si vous ne renseignez pas cette langue, les visiteurs verront la version par défaut de l\'article.') }} ({{ config('app.fallback_locale') }})
                            </div>
                        </div>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ... Colonne de droite avec les paramètres de publication ... --}}
    <div class="col-md-4">
        <div class="sticky-top" style="top: 15px;">
            {{-- Paramètres de Publication --}}
            <div class="card card-outline card-secondary shadow-sm rounded-lg mb-4">
                {{-- ... Contenu de la carte des paramètres ... --}}
            </div>

            {{-- Image Mise en Avant --}}
            <div class="card card-outline card-secondary shadow-sm rounded-lg">
                {{-- ... Contenu de la carte de l'image ... --}}
            </div>
        </div>
    </div>

</div>

<div class="col-md-4">
    <div class="sticky-top" style="top: 15px;">
        {{-- Paramètres de Publication --}}
        <div class="card card-outline card-secondary shadow-sm rounded-lg mb-4">
            <div class="card-header bg-gradient-light">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-cog mr-2"></i>{{ __('Paramètres de Publication') }}
                </h3>
            </div>
            <div class="card-body">
                {{-- Boutons d'action rapide --}}
                <div class="d-flex justify-content-between mb-4">
                    <button type="submit" name="status" value="draft" class="btn btn-outline-secondary">
                        <i class="far fa-save mr-1"></i> {{ __('Enregistrer comme brouillon') }}
                    </button>
                    <button type="submit" name="status" value="published" class="btn btn-success">
                        <i class="fas fa-check-circle mr-1"></i> {{ __('Publier') }}
                    </button>
                </div>

                {{-- Catégorie --}}
                <div class="form-group">
                    <label for="category_id" class="font-weight-bold">
                        {{ __('Catégorie') }} <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-folder"></i></span>
                        </div>
                        <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                            <option value="">{{ __('Sélectionnez une catégorie') }}</option>
                            @foreach($categories as $id => $name)
                            <option value="{{ $id }}" {{ old('category_id', $post->category_id) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                {{-- Statut --}}
                <div class="form-group">
                    <label for="status" class="font-weight-bold">
                        {{ __('Statut') }} <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-toggle-on"></i></span>
                        </div>
                        <select name="status" id="status" class="form-control @error('status') is-invalid @enderror" required>
                            @foreach($statuses as $value => $label)
                            <option value="{{ $value }}" {{ old('status', $post->status ?? 'draft') == $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('status')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                </div>

                {{-- Date de publication --}}
                <div class="form-group" id="published_at_wrapper">
                    <label for="published_at" class="font-weight-bold">
                        {{ __('Date de publication') }}
                    </label>
                    <div class="input-group">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                        </div>
                        <input type="datetime-local"
                            name="published_at"
                            id="published_at"
                            class="form-control @error('published_at') is-invalid @enderror"
                            value="{{ old('published_at', $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                        @error('published_at')
                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>
                    <small class="form-text text-muted">
                        <i class="fas fa-info-circle"></i>
                        {{ __('Laisser vide pour publier immédiatement (si le statut est "Publié").') }}
                    </small>
                </div>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex align-items-center">
                    <i class="fas fa-user-edit text-muted mr-2"></i>
                    <span class="text-muted">{{ __('Auteur') }}: {{ auth()->user()->name }}</span>
                </div>
            </div>
        </div>

        {{-- Image Mise en Avant --}}
        <div class="card card-outline card-secondary shadow-sm rounded-lg">
            <div class="card-header bg-gradient-light">
                <h3 class="card-title font-weight-bold">
                    <i class="fas fa-image mr-2"></i>{{ __('Image Mise en Avant') }}
                </h3>
            </div>
            <div class="card-body">
                <div class="text-center mb-3" id="image-preview-container">
                    <div class="position-relative d-inline-block">
                        <img id="featured_image_preview"
                            src="{{ $post->featured_image ? Storage::url($post->featured_image) : asset('img/placeholder-image.jpg') }}"
                            alt="{{ __('Aperçu de l\'image') }}"
                            class="img-fluid rounded shadow-sm"
                            style="max-height: 200px; {{ $post->featured_image ? '' : 'opacity: 0.5;' }}">
                        @if(!$post->featured_image)
                        <div class="position-absolute" style="top: 50%; left: 50%; transform: translate(-50%, -50%);">
                            <span class="text-muted">{{ __('Aucune image') }}</span>
                        </div>
                        @endif
                    </div>
                </div>

                <div class="custom-file mb-3">
                    <input type="file"
                        name="featured_image"
                        id="featured_image"
                        class="custom-file-input @error('featured_image') is-invalid @enderror"
                        onchange="previewImage(event)">
                    <label class="custom-file-label" for="featured_image">{{ __('Choisir une image...') }}</label>
                    @error('featured_image')
                    <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                @if($post->featured_image)
                <input type="hidden" id="current_featured_image_path_for_display" value="{{ Storage::url($post->featured_image) }}">
                <div class="form-check mt-2" id="remove_featured_image_label">
                    <input class="form-check-input" type="checkbox" name="remove_featured_image" id="remove_featured_image_checkbox" value="1" onclick="if(this.checked) { removeImage(); }">
                    <label class="form-check-label" for="remove_featured_image_checkbox">
                        {{ __('Supprimer l\'image mise en avant actuelle') }}
                    </label>
                </div>
                @else
                <div class="form-check mt-2" id="remove_featured_image_label" style="display:none;">
                    <input class="form-check-input" type="checkbox" name="remove_featured_image" id="remove_featured_image_checkbox" value="1" onclick="if(this.checked) { removeImage(); }">
                    <label class="form-check-label" for="remove_featured_image_checkbox">
                        {{ __('Supprimer l\'image sélectionnée') }}
                    </label>
                </div>
                @endif

                <small class="form-text text-muted mt-2">
                    <i class="fas fa-info-circle"></i>
                    {{ __('Formats recommandés: JPG, PNG. Taille idéale: 1200×630 pixels.') }}
                </small>
            </div>
        </div>
    </div>
</div>
</div>
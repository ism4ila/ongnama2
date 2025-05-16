{{-- resources/views/admin/posts/_form.blade.php --}}

@props(['post' => null, 'locales', 'categories', 'statuses'])

<div class="row">
    {{-- Colonne principale pour le contenu --}}
    <div class="col-lg-8">
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Contenu de l\'article') }}</h6>
            </div>
            <div class="card-body">
                {{-- Onglets pour les langues --}}
                <ul class="nav nav-tabs" id="localeTabs" role="tablist">
                    @foreach ($locales as $localeCode => $properties)
                        <li class="nav-item" role="presentation">
                            <button class="nav-link {{ $loop->first ? 'active' : '' }}"
                                id="tab-{{ $localeCode }}" data-bs-toggle="tab"
                                data-bs-target="#content-{{ $localeCode }}" type="button" role="tab"
                                aria-controls="content-{{ $localeCode }}"
                                aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                {{ $properties['native'] }}
                                @if ($properties['is_fallback'])
                                    <span class="badge bg-light text-dark ms-1 border" data-bs-toggle="tooltip" title="{{ __('Langue par défaut (obligatoire)') }}">{{ __('Défaut')}}</span>
                                @endif
                            </button>
                        </li>
                    @endforeach
                </ul>

                <div class="tab-content pt-3 border border-top-0 rounded-bottom p-3" id="localeTabsContent">
                    @foreach ($locales as $localeCode => $properties)
                        <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}"
                            id="content-{{ $localeCode }}" role="tabpanel"
                            aria-labelledby="tab-{{ $localeCode }}">

                            {{-- Titre --}}
                            <div class="mb-3">
                                <label for="title_{{ $localeCode }}" class="form-label">
                                    {{ __('Titre') }} ({{ strtoupper($localeCode) }})
                                    @if ($properties['is_fallback'])
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <input type="text"
                                    name="title[{{ $localeCode }}]"
                                    id="title_{{ $localeCode }}"
                                    class="form-control @error('title.' . $localeCode) is-invalid @enderror"
                                    value="{{ old('title.' . $localeCode, $post ? $post->getTranslation('title', $localeCode, false) : '') }}"
                                    {{ $properties['is_fallback'] ? 'required' : '' }}>
                                @error('title.' . $localeCode)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Slug --}}
                            <div class="mb-3">
                                <label for="slug_{{ $localeCode }}" class="form-label">
                                    {{ __('Slug') }} ({{ strtoupper($localeCode) }})
                                </label>
                                <input type="text"
                                    name="slug[{{ $localeCode }}]"
                                    id="slug_{{ $localeCode }}"
                                    class="form-control @error('slug.' . $localeCode) is-invalid @enderror"
                                    value="{{ old('slug.' . $localeCode, $post ? $post->getTranslation('slug', $localeCode, false) : '') }}"
                                    readonly {{-- Le slug est généré par le backend à partir du titre --}}
                                    aria-describedby="slugHelp{{ $localeCode }}">
                                <div id="slugHelp{{ $localeCode }}" class="form-text">{{ __('Le slug est généré automatiquement à partir du titre.') }}</div>
                                @error('slug.' . $localeCode)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Corps de l'article --}}
                            <div class="mb-3">
                                <label for="body_{{ $localeCode }}" class="form-label">
                                    {{ __('Corps de l\'article') }} ({{ strtoupper($localeCode) }})
                                    @if ($properties['is_fallback'])
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                <textarea name="body[{{ $localeCode }}]"
                                    id="body_{{ $localeCode }}"
                                    class="form-control @error('body.' . $localeCode) is-invalid @enderror"
                                    rows="10"
                                    {{ $properties['is_fallback'] ? 'required' : '' }}>{{ old('body.' . $localeCode, $post ? $post->getTranslation('body', $localeCode, false) : '') }}</textarea>
                                {{-- Pour un éditeur riche, vous pouvez initialiser TinyMCE ou autre ici ciblant cette textarea par son ID --}}
                                @error('body.' . $localeCode)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            {{-- Extrait --}}
                            <div class="mb-3">
                                <label for="excerpt_{{ $localeCode }}" class="form-label">
                                    {{ __('Extrait') }} ({{ strtoupper($localeCode) }})
                                </label>
                                <textarea name="excerpt[{{ $localeCode }}]"
                                    id="excerpt_{{ $localeCode }}"
                                    class="form-control @error('excerpt.' . $localeCode) is-invalid @enderror"
                                    rows="3">{{ old('excerpt.' . $localeCode, $post ? $post->getTranslation('excerpt', $localeCode, false) : '') }}</textarea>
                                @error('excerpt.' . $localeCode)
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- Colonne latérale pour les métadonnées et options --}}
    <div class="col-lg-4">
        {{-- Options de publication --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Options de publication') }}</h6>
            </div>
            <div class="card-body">
                {{-- Catégorie --}}
                <div class="mb-3">
                    <label for="category_id" class="form-label">{{ __('Catégorie') }} <span class="text-danger">*</span></label>
                    <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                        <option value="">{{ __('Sélectionner une catégorie') }}</option>
                        @foreach ($categories as $id => $name)
                            <option value="{{ $id }}" {{ old('category_id', $post->category_id ?? null) == $id ? 'selected' : '' }}>
                                {{ $name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Statut --}}
                <div class="mb-3">
                    <label for="status" class="form-label">{{ __('Statut') }} <span class="text-danger">*</span></label>
                    <select name="status" id="status" class="form-select @error('status') is-invalid @enderror" required>
                        @foreach ($statuses as $key => $value)
                            <option value="{{ $key }}" {{ old('status', $post->status ?? 'draft') == $key ? 'selected' : '' }}>
                                {{ $value }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                {{-- Date de publication --}}
                <div class="mb-3">
                    <label for="published_at" class="form-label">{{ __('Date de publication') }}</label>
                    <input type="datetime-local" name="published_at" id="published_at"
                        class="form-control @error('published_at') is-invalid @enderror"
                        value="{{ old('published_at', $post && $post->published_at ? $post->published_at->format('Y-m-d\TH:i') : '') }}">
                    <div class="form-text">{{ __('Laisser vide pour publier immédiatement si le statut est "Publié" (lors de la création).') }}</div>
                    @error('published_at')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        {{-- Image mise en avant --}}
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">{{ __('Image mise en avant') }}</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label for="featured_image" class="form-label">{{ __('Télécharger une nouvelle image') }}</label>
                    <input type="file" name="featured_image" id="featured_image"
                        class="form-control @error('featured_image') is-invalid @enderror" accept="image/*">
                    <div class="form-text">{{ __('Max. 5MB. Formats suggérés: JPG, PNG, WEBP.') }}</div>
                    @error('featured_image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <img id="imagePreview" src="#" alt="{{ __('Aperçu de la nouvelle image') }}" class="img-fluid rounded mb-2 border" style="max-height: 200px; display: none;"/>

                @if (isset($post) && $post->featured_image)
                    <div class="mb-3">
                        <label class="form-label d-block">{{ __('Image actuelle :') }}</label>
                        <img src="{{ Storage::url($post->featured_image) }}" alt="{{ __('Image actuelle') }}"
                            class="img-fluid rounded mb-2 border" style="max-height: 150px;">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remove_featured_image" id="remove_featured_image" value="1">
                            <label class="form-check-label" for="remove_featured_image">
                                {{ __('Supprimer l\'image mise en avant actuelle') }}
                            </label>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<div class="row mt-0"> {{-- mt-0 pour rapprocher de la carte de droite si elle est plus longue --}}
    <div class="col-12">
        <div class="card shadow mb-4">
            <div class="card-body text-end"> {{-- text-end pour aligner les boutons à droite --}}
                 <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary me-2">
                    <i class="fas fa-times me-1"></i>
                    {{ __('Annuler') }}
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save me-1"></i>
                    {{ isset($post) ? __('Mettre à jour l\'article') : __('Enregistrer l\'article') }}
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Activation des tooltips Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Script simple pour la prévisualisation de l'image
    const imageInput = document.getElementById('featured_image');
    const imagePreview = document.getElementById('imagePreview');

    if (imageInput && imagePreview) {
        imageInput.addEventListener('change', function(event) {
            if (event.target.files && event.target.files[0]) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    imagePreview.src = e.target.result;
                    imagePreview.style.display = 'block';
                }
                reader.readAsDataURL(event.target.files[0]);
            } else {
                // Si l'utilisateur déselectionne le fichier (par exemple, en cliquant sur Annuler dans la boîte de dialogue)
                // Ou si le navigateur ne supporte pas event.target.files[0] après une déselection
                imagePreview.src = '#'; // Réinitialiser src
                imagePreview.style.display = 'none'; // Cacher l'aperçu
            }
        });
    }
});
</script>
@endpush
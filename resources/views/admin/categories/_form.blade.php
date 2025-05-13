@csrf
<div class="mb-3">
    <label for="name" class="form-label">Nom de la catégorie ({{ strtoupper(app()->getLocale()) }})</label>
    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $category->getTranslation('name', app()->getLocale()) ?? '') }}" required>
    @error('name')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<div class="mb-3">
    <label for="slug" class="form-label">Slug ({{ strtoupper(app()->getLocale()) }}) <small class="text-muted">(Optionnel - sera généré à partir du nom si laissé vide)</small></label>
    <input type="text" class="form-control @error('slug') is-invalid @enderror" id="slug" name="slug" value="{{ old('slug', $category->getTranslation('slug', app()->getLocale()) ?? '') }}">
    @error('slug')
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>

<button type="submit" class="btn btn-primary">
    <i class="fas fa-save"></i> {{ $category->exists ? 'Mettre à jour' : 'Créer' }}
</button>
<a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Annuler</a>
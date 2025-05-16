{{-- resources/views/admin/posts/index.blade.php --}}
@extends('admin.layouts.app')

@section('title', __('Liste des Articles'))

@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50 me-1"></i> {{ __('Créer un nouvel article') }}
        </a>
    </div>

    {{-- Affichage des messages de session --}}
    @include('layouts.partials.messages') {{-- Adaptez le chemin si nécessaire --}}

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Tous les articles') }}</h6>
        </div>
        <div class="card-body">
            @if ($posts->isEmpty())
                <div class="text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-gray-400 mb-3"></i>
                    <h4 class="text-gray-600 mb-3">{{ __('Aucun article trouvé.') }}</h4>
                    <p class="text-muted mb-4">{{ __('Commencez par créer votre premier article pour le voir apparaître ici.') }}</p>
                    <a href="{{ route('admin.posts.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-1"></i> {{ __('Créer un article') }}
                    </a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="dataTablePosts" width="100%" cellspacing="0">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th style="width: 80px;">{{ __('Image') }}</th>
                                <th>{{ __('Titre') }}</th>
                                <th>{{ __('Catégorie') }}</th>
                                <th>{{ __('Auteur') }}</th>
                                <th>{{ __('Statut') }}</th>
                                <th>{{ __('Publié le') }}</th>
                                <th class="text-end" style="width: 100px;">{{ __('Actions') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($posts as $post)
                                <tr>
                                    <td>{{ $loop->iteration + ($posts->currentPage() - 1) * $posts->perPage() }}</td>
                                    <td>
                                        @if ($post->featured_image)
                                            <img src="{{ Storage::url($post->featured_image) }}"
                                                 alt="{{ $post->getTranslation('title', config('app.fallback_locale', 'en'), false) }}" class="img-thumbnail"
                                                 style="width: 70px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light border d-flex align-items-center justify-content-center text-muted"
                                                 style="width: 70px; height: 50px; font-size: 0.8rem;">
                                                <i class="fas fa-image fa-lg"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.posts.edit', $post) }}" class="fw-bold text-dark">
                                            {{ Str::limit($post->title, 45) }} {{-- $post->title prendra la locale actuelle --}}
                                        </a>
                                        <small class="d-block text-muted">
                                           {{-- ID: {{ $post->id }} --}}
                                        </small>
                                    </td>
                                    <td>{{ $post->category->name ?? __('N/A') }}</td> {{-- Assumant que category.name est traduisible --}}
                                    <td>{{ $post->user->name ?? __('N/A') }}</td>
                                    <td>
                                        @php
                                            $statusConfig = [
                                                'published' => ['class' => 'success', 'icon' => 'fa-check-circle'],
                                                'draft' => ['class' => 'secondary', 'icon' => 'fa-edit'],
                                                'pending' => ['class' => 'warning', 'icon' => 'fa-clock'],
                                            ];
                                            $currentStatus = $statusConfig[$post->status] ?? ['class' => 'light', 'icon' => 'fa-question-circle'];
                                        @endphp
                                        <span class="badge bg-{{ $currentStatus['class'] }} text-dark-emphasis">
                                            <i class="fas {{ $currentStatus['icon'] }} me-1"></i>
                                            {{ __(ucfirst($post->status)) }}
                                        </span>
                                    </td>
                                    <td>
                                        {{ $post->published_at ? $post->published_at->isoFormat('D MMM YYYY, HH:mm') : __('Non publié') }}
                                    </td>
                                    <td>
                                        @include('admin.posts._actions', ['post' => $post])
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if ($posts->hasPages())
                    <div class="mt-4 d-flex justify-content-center">
                        {{ $posts->links() }} {{-- S'assure que la pagination utilise Bootstrap --}}
                    </div>
                @endif
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
// S'assurer que les tooltips Bootstrap sont initialisés si ce n'est pas fait globalement
document.addEventListener('DOMContentLoaded', function () {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })
});
</script>
@endpush
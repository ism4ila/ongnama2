@extends('layouts.admin')
@section('title', __('Posts'))

@section('main-content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ __('Posts') }}</h1>
        <a href="{{ route('admin.posts.create') }}" class="btn btn-primary btn-icon-split">
            <span class="icon text-white-50"><i class="fas fa-plus"></i></span>
            <span class="text">{{ __('Add Post') }}</span>
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">{{ __('Posts List') }}</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>{{ __('ID') }}</th>
                            <th>{{ __('Featured Image') }}</th>
                            <th>{{ __('Title') }}</th>
                            <th>{{ __('Category') }}</th>
                            <th>{{ __('Author') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Published At') }}</th>
                            <th>{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($posts as $post)
                        <tr>
                            <td>{{ $post->id }}</td>
                            <td>
                                @if ($post->featured_image)
                                    <img src="{{ Storage::url($post->featured_image) }}" alt="{{ $post->title }}" width="100" class="img-thumbnail">
                                @else
                                    N/A
                                @endif
                            </td>
                            <td>
                                {{ $post->title }} {{-- Affiche le titre dans la locale actuelle --}}
                                <br>
                                <small class="text-muted">
                                    @foreach (['en', 'fr', 'ar'] as $locale)
                                        @if ($post->getTranslation('title', $locale, false) && app()->getLocale() !== $locale)
                                            <span class="badge badge-light">{{ strtoupper($locale) }}: {{ Str::limit($post->getTranslation('title', $locale), 20) }}</span>
                                        @endif
                                    @endforeach
                                </small>
                            </td>
                            <td>{{ $post->category ? $post->category->name : 'N/A' }}</td> {{-- Affiche le nom de la catégorie dans la locale actuelle --}}
                            <td>{{ $post->user ? $post->user->name : 'N/A' }}</td>
                            <td>
                                <span class="badge badge-{{ $post->status == 'published' ? 'success' : ($post->status == 'draft' ? 'secondary' : 'warning') }}">
                                    {{ ucfirst($post->status) }}
                                </span>
                            </td>
                            <td>{{ $post->published_at ? $post->published_at->format('d/m/Y H:i') : 'Not published' }}</td>
                            <td>
                                <a href="{{ route('admin.posts.show', $post) }}" class="btn btn-info btn-circle btn-sm" title="{{ __('View') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.posts.edit', $post) }}" class="btn btn-warning btn-circle btn-sm" title="{{ __('Edit') }}">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.posts.destroy', $post) }}" method="POST" style="display:inline;" onsubmit="return confirm('{{ __('Confirm Delete Post') }}');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-circle btn-sm" title="{{ __('Delete') }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('No posts found.') }}</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center">
                {{ $posts->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
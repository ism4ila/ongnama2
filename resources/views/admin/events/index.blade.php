@extends('layouts.admin') {{-- Ou le nom de votre layout SB Admin --}}

@section('title', 'Gestion des Événements')

@section('content')
<div class="container-fluid">

    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary btn-sm shadow-sm">
            <i class="fas fa-plus fa-sm text-white-50"></i> Créer un Événement
        </a>
    </div>

    @include('layouts.partials.messages') {{-- Pour afficher les messages de succès/erreur --}}

    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Événements</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="dataTableEvents" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Titre ({{ strtoupper(config('app.fallback_locale')) }})</th>
                            <th>Date de Début</th>
                            <th>Date de Fin</th>
                            <th>Lieu ({{ strtoupper(config('app.fallback_locale')) }})</th>
                            {{-- <th>Statut</th> --}} {{-- Si vous ajoutez le statut --}}
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($events as $event)
                        <tr>
                            <td>{{ $event->id }}</td>
                            <td>
                                <a href="{{ route('admin.events.edit', $event) }}">
                                    {{ $event->getTranslation('title', config('app.fallback_locale')) }}
                                </a>
                                <br>
                                <small class="text-muted">
                                    @foreach(app('laravellocalization_supported_locales') as $localeCode => $properties)
                                        @if($localeCode != config('app.fallback_locale') && $event->getTranslation('title', $localeCode, false))
                                        [{{ strtoupper($localeCode) }}] {{ $event->getTranslation('title', $localeCode) }} <br>
                                        @endif
                                    @endforeach
                                </small>
                            </td>
                            <td>{{ $event->start_datetime ? $event->start_datetime->translatedFormat('d M Y H:i') : 'N/A' }}</td>
                            <td>{{ $event->end_datetime ? $event->end_datetime->translatedFormat('d M Y H:i') : 'N/A' }}</td>
                            <td>{{ $event->getTranslation('location_text', config('app.fallback_locale')) }}</td>
                            {{-- <td><span class="badge badge-{{ $event->status == 'published' ? 'success' : 'warning' }}">{{ ucfirst($event->status) }}</span></td> --}}
                            <td>
                                <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-info btn-sm" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.events.destroy', $event) }}" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet événement ?');" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" title="Supprimer">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun événement trouvé.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="mt-3">
                {{ $events->links() }} {{-- Pagination --}}
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
    {{-- Si vous utilisez DataTables pour le tri/recherche --}}
    {{-- <link href="{{ asset('vendor/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet"> --}}
@endpush

@push('scripts')
    {{-- Si vous utilisez DataTables --}}
    {{-- <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script> --}}
    {{-- <script> $(document).ready(function() { $('#dataTableEvents').DataTable(); }); </script> --}}
@endpush
@extends('layouts.admin')

@section('title', 'Créer un Nouvel Événement')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
    </div>

    @include('layouts.partials.messages') {{-- Pour les erreurs de validation globales si besoin --}}

    <form action="{{ route('admin.events.store') }}" method="POST" enctype="multipart/form-data">
        @php $event = new \App\Models\Event(); @endphp {{-- Instance vide pour le formulaire partiel --}}
        @include('admin.events._form', ['event' => $event, 'locales' => $locales])
    </form>
</div>
@endsection

@push('styles')
    {{-- CSS pour l'éditeur WYSIWYG si utilisé --}}
    {{-- <link rel="stylesheet" href="{{ asset('vendor/trumbowyg/ui/trumbowyg.min.css') }}"> --}}
@endpush

@push('scripts')
    {{-- JS pour l'éditeur WYSIWYG si utilisé --}}
    {{-- <script src="{{ asset('vendor/trumbowyg/trumbowyg.min.js') }}"></script> --}}
    {{-- <script src="{{ asset('vendor/trumbowyg/langs/fr.min.js') }}"></script> --}}
    {{-- <script> $('.trumbowyg-editor').trumbowyg({ lang: 'fr' }); </script> --}}
    <script>
        // Script pour les onglets de langue Bootstrap 5
        var triggerTabList = [].slice.call(document.querySelectorAll('#langTabs button'))
        triggerTabList.forEach(function (triggerEl) {
            var tabTrigger = new bootstrap.Tab(triggerEl)
            triggerEl.addEventListener('click', function (event) {
                event.preventDefault()
                tabTrigger.show()
            })
        })
    </script>
@endpush
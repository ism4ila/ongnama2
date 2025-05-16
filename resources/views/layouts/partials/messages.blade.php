{{-- resources/views/layouts/partials/messages.blade.php --}}

@php
    $messageTypes = ['success', 'error', 'warning', 'info', 'message']; // 'message' pour le premier bloc que vous aviez
@endphp

@foreach ($messageTypes as $type)
    @if (session()->has($type))
        @php
            $alertClass = 'alert-info'; // Classe par défaut
            if ($type === 'success' || $type === 'message') $alertClass = 'alert-success';
            if ($type === 'error') $alertClass = 'alert-danger';
            if ($type === 'warning') $alertClass = 'alert-warning';
            
            $messages = session()->get($type);
            if (!is_array($messages)) {
                $messages = [$messages]; // Convertit en tableau pour un traitement uniforme
            }
        @endphp
        <div class="alert {{ $alertClass }} alert-dismissible fade show" role="alert">
            @if (count($messages) > 1)
                <ul>
                    @foreach ($messages as $msg)
                        <li>{{ $msg }}</li>
                    @endforeach
                </ul>
            @else
                {{ $messages[0] }}
            @endif
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@endforeach

{{-- Handle Laravel validation errors --}}
@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <h4 class="alert-heading">{{ __('Erreurs de validation') }}</h4>
        <ul>
            @foreach ($errors->all() as $validationError)
                {{-- On s'attend à ce que $validationError soit une chaîne. 
                     Si ce n'est pas le cas, il faudrait une logique plus complexe ici.
                     Normalement, $errors->all() retourne un tableau plat de chaînes. --}}
                <li>{{ $validationError }}</li>
            @endforeach
        </ul>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
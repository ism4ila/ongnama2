@extends('layouts.admin_new')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de Bord</h1>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Bienvenue !</div>
                <div class="card-body">
                    C'est la page principale de votre tableau de bord administrateur.
                    <p class="mt-3">
                        Utilisez la barre latérale pour naviguer vers les différentes sections, comme la gestion des <a href="{{ route('admin.categories.index') }}">Catégories</a>.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@extends('layouts.admin_new')

@section('content')
<div class="container-fluid">
    <h1>Créer une Nouvelle Catégorie</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @include('admin.categories._form', ['category' => new \App\Models\Category()])
            </form>
        </div>
    </div>
</div>
@endsection
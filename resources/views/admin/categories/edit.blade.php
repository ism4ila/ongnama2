@extends('layouts.admin_new')

@section('content')
<div class="container-fluid">
    <h1>Modifier la Catégorie : {{ $category->name }}</h1>

    <div class="card shadow">
        <div class="card-body">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @method('PUT')
                @include('admin.categories._form', ['category' => $category])
            </form>
        </div>
    </div>
</div>
@endsection
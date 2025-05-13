@extends('admin.layouts.app')

@section('title', 'Tableau de bord Admin')
@section('page-title', 'Tableau de bord')

@section('content')
    <div class="row">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Nombre de Catégories</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{-- Remplacer par la vraie donnée plus tard --}}
                                {{ \App\Models\Category::count() }} 
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tags fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Nombre d'Articles (Exemple)</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{-- Remplacer par \App\Models\Post::count() si le modèle existe et est pertinent --}}
                                0 
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-newspaper fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        </div>

    <div class="row">
        <div class="col-lg-12 mb-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Bienvenue !</h6>
                </div>
                <div class="card-body">
                    <div class="text-center">
                        <img class="img-fluid px-3 px-sm-4 mt-3 mb-4" style="width: 25rem;"
                            src="{{ asset('vendor/sb-admin/undraw_posting_photo.svg') }}" alt="..."> 
                            {{-- Assurez-vous que cette image existe ou changez le chemin --}}
                    </div>
                    <p>Bienvenue sur le tableau de bord de l'administration. Vous pouvez utiliser le menu latéral pour naviguer entre les différentes sections de gestion.</p>
                    <p>Pour commencer, vous pourriez vouloir gérer les <a target="_blank" href="{{ route('admin.categories.index') }}">catégories</a>.</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    {{-- <script>
        console.log('Dashboard script chargé');
    </script> --}}
@endpush
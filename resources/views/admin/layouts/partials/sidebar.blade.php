<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('admin.dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            {{-- Vous pouvez changer cette icône si vous le souhaitez --}}
            <i class="fas fa-cogs"></i>
        </div>
        <div class="sidebar-brand-text mx-3">{{ config('app.name', 'Laravel') }} <sup>Admin</sup></div>
    </a>

    <hr class="sidebar-divider my-0">

    <li class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>{{ __('Tableau de bord') }}</span></a>
    </li>

    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        {{ __('Gestion du Contenu') }}
    </div>

    <li class="nav-item {{ request()->routeIs('admin.pages.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.pages.index') }}">
            <i class="fas fa-fw fa-file-alt"></i>
            <span>{{ __('Pages') }}</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.posts.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.posts.index') }}">
            <i class="fas fa-fw fa-newspaper"></i>
            <span>{{ __('Articles') }}</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.categories.index') }}">
            <i class="fas fa-fw fa-tags"></i>
            <span>{{ __('Catégories') }}</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.projects.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.projects.index') }}">
            <i class="fas fa-fw fa-project-diagram"></i>
            <span>{{ __('Projets') }}</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.events.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.events.index') }}">
            <i class="fas fa-fw fa-calendar-alt"></i>
            <span>{{ __('Événements') }}</span></a>
    </li>

  

     {{--
    <li class="nav-item {{ request()->routeIs('admin.media-items.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.media-items.index') }}">
            <i class="fas fa-fw fa-photo-video"></i>
            <span>{{ __('Médiathèque') }}</span></a>
    </li>
    --}}


    <hr class="sidebar-divider">

    <div class="sidebar-heading">
        {{ __('Gestion du Site') }}
    </div>

    <li class="nav-item {{ request()->routeIs('admin.team-members.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.team-members.index') }}">
            <i class="fas fa-fw fa-users-cog"></i>
            <span>{{ __('Équipe') }}</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.partners.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.partners.index') }}">
            <i class="fas fa-fw fa-handshake"></i>
            <span>{{ __('Partenaires') }}</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('admin.users.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>{{ __('Utilisateurs') }}</span></a>
    </li>

    <li class="nav-item {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSettings"
            aria-expanded="{{ request()->routeIs('admin.settings.*') ? 'true' : 'false' }}" aria-controls="collapseSettings">
            <i class="fas fa-fw fa-cogs"></i>
            <span>{{ __('Paramètres') }}</span>
        </a>
        <div id="collapseSettings" class="collapse {{ request()->routeIs('admin.settings.*') ? 'show' : '' }}" aria-labelledby="headingSettings"
            data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">{{ __('Options de Configuration') }}:</h6>
                <a class="collapse-item {{ request()->routeIs('admin.settings.site.edit') ? 'active' : '' }}" href="{{ route('admin.settings.site.edit') }}">{{ __('Paramètres du Site') }}</a>
                <a class="collapse-item {{ request()->routeIs('admin.settings.homepage.edit') ? 'active' : '' }}" href="{{ route('admin.settings.homepage.edit') }}">{{ __('Page d\'Accueil') }}</a>
                {{-- Ajoutez d'autres liens de paramètres ici si nécessaire --}}
            </div>
        </div>
    </li>

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
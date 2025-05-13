<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $siteSettingsGlobal->getDirection(app()->getLocale()) ?? (app()->getLocale() == 'ar' ? 'rtl' : 'ltr') }}"> {{-- Ajout d'une méthode getDirection au modèle SiteSetting pour plus de flexibilité --}}
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', $siteSettingsGlobal->getTranslation('footer_description', app()->getLocale(), config('app.description', 'Organisation Nama - Agir ensemble pour un avenir meilleur')))">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama'))</title>

    @if($siteSettingsGlobal->favicon)
    <link rel="icon" href="{{ Storage::url($siteSettingsGlobal->favicon) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ Storage::url($siteSettingsGlobal->favicon) }}" type="image/x-icon">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    {{-- Police Poppins et Cairo --}}
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"> {{-- Assurez-vous que cette version est à jour --}}
    
    {{-- Bootstrap CSS --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @if (app()->getLocale() == 'ar') {{-- Ou utilisez $siteSettingsGlobal->getDirection() --}}
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @endif
    
    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet"> {{-- Votre CSS principal --}}
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" /> {{-- AOS pour animations au scroll --}}


    {{-- Styles spécifiques à la page (poussés par les vues enfants) --}}
    @stack('styles_frontend')

    <style>
        /* Variables CSS Globales (si non définies dans frontend.css) */
        :root {
            --primary-color: {{ $siteSettingsGlobal->primary_color ?? '#4CAF50' }}; /* Exemple: rendre les couleurs dynamiques */
            --secondary-color: {{ $siteSettingsGlobal->secondary_color ?? '#2E7D32' }};
            /* ... autres variables ... */
            --primary-color-rgb: {{ implode(',', sscanf($siteSettingsGlobal->primary_color ?? '#4CAF50', "#%02x%02x%02x")) }};
            --secondary-color-rgb: {{ implode(',', sscanf($siteSettingsGlobal->secondary_color ?? '#2E7D32', "#%02x%02x%02x")) }};
            --accent-color-rgb: {{ implode(',', sscanf($siteSettingsGlobal->accent_color ?? '#8BC34A', "#%02x%02x%02x")) }};


            --text-color: #374151; 
            --light-gray: #F1F8E9; 
            --dark-gray: #4B5563; 
        }
        body {
            font-family: 'Poppins', 'Cairo', sans-serif; /* Cairo pour l'Arabe */
            color: var(--text-color);
            line-height: 1.6;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #FAFAFA; /* Fond général du corps */
        }
        main {
            flex-grow: 1;
            padding-top: 2rem; /* Espace après la navbar sticky */
            padding-bottom: 2rem; /* Espace avant le footer */
        }
        .navbar-brand img {
             transition: height 0.3s ease;
             max-height: 45px; /* Taille max pour le logo */
        }
        .navbar.scrolled .navbar-brand img {
            max-height: 40px; /* Logo légèrement plus petit au scroll */
        }
        .navbar {
            transition: all 0.3s ease;
            padding: 1rem 0;
            background-color: rgba(255, 255, 255, 0.98) !important; /* Fond initial */
        }
        .navbar.scrolled {
            padding: 0.5rem 0;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }
        .nav-link {
            position: relative;
            font-weight: 500;
            padding: 0.5rem 1rem !important;
            margin: 0 0.2rem;
            transition: all 0.3s ease;
            color: var(--dark-gray);
        }
        .nav-link:hover, .nav-link.active {
            color: var(--primary-color) !important;
        }
        .nav-link:after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -2px; /* Légèrement en dessous du texte */
            left: 50%;
            background-color: var(--primary-color);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        .nav-link:hover:after,
        .nav-link.active:after {
            width: 70%; /* Soulignement partiel */
        }
        [dir="rtl"] .nav-link:after {
            right: 50%;
            left: auto;
            transform: translateX(50%);
        }
        /* Footer styles */
        footer {
            background-color: #fff; /* Fond blanc pour le footer */
            padding-top: 4rem;
            padding-bottom: 1.5rem; /* Moins de padding en bas */
            border-top: 1px solid #E5E7EB; /* Bordure discrète */
            margin-top: auto; /* Pousse le footer en bas */
        }
        .footer-heading {
            font-size: 1.3rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            color: var(--secondary-color);
            position: relative;
            padding-bottom: 12px;
        }
        .footer-heading:after {
            content: '';
            position: absolute;
            width: 30px;
            height: 2px;
            background-color: var(--primary-color);
            bottom: 0;
            left: 0;
        }
        [dir="rtl"] .footer-heading:after {
            right: 0;
            left: auto;
        }
        .footer-links { list-style: none; padding-left:0; }
        .footer-links a {
            color: var(--dark-gray);
            text-decoration: none;
            margin-bottom: 0.8rem;
            transition: all 0.3s ease;
            position: relative;
            padding-left: 18px; /* Espace pour l'icône */
            display: inline-block; /* Pour que le padding s'applique bien */
        }
        .footer-links a:before {
            content: '\f105'; /* FontAwesome chevron-right */
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            left: 0;
            color: var(--primary-color);
            font-size: 0.9rem; /* Taille de l'icône */
            line-height: inherit;
            transition: all 0.3s ease;
        }
        .footer-links a:hover {
            color: var(--primary-color);
            padding-left: 22px; /* Décalage au survol */
        }
        .footer-links a:hover:before {
            left: 5px; /* Mouvement de l'icône */
        }
        [dir="rtl"] .footer-links a { padding-left: 0; padding-right: 18px; }
        [dir="rtl"] .footer-links a:before { content: '\f104'; /* FontAwesome chevron-left */ left:auto; right:0; }
        [dir="rtl"] .footer-links a:hover { padding-left:0; padding-right: 22px; }
        [dir="rtl"] .footer-links a:hover:before { right: 5px; left:auto; }

        .social-links { display: flex; gap: 15px; padding-left:0; list-style:none; }
        .social-links a {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: var(--light-gray);
            color: var(--dark-gray);
            border-radius: 50%;
            transition: all 0.3s ease;
            text-decoration: none;
            font-size: 1.1rem;
        }
        .social-links a:hover {
            background-color: var(--primary-color);
            color: white;
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(var(--primary-color-rgb), 0.3);
        }
        footer address p {
            margin-bottom: 0.8rem;
            display: flex;
            align-items: center;
            color: var(--dark-gray);
        }
        footer address p i.fas { /* Cibler spécifiquement les icônes FA */
            color: var(--primary-color);
            width: 25px; /* Espace pour l'icône */
            text-align: center;
        }
        .copyright {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 1px solid #E5E7EB;
            color: var(--dark-gray);
            text-align: center;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
            <div class="container">
                <a class="navbar-brand" href="{{ route('frontend.home', ['locale' => app()->getLocale()]) }}">
                    @if($siteSettingsGlobal->logo_path)
                        <img src="{{ Storage::url($siteSettingsGlobal->logo_path) }}" alt="{{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }} Logo">
                    @else
                        {{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }}
                    @endif
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    {{-- Navigation Principale Dynamique --}}
                    <ul class="navbar-nav {{ app()->getLocale() == 'ar' ? 'ms-auto' : 'me-auto' }} mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home', ['locale' => app()->getLocale()]) }}">{{ __('Accueil') }}</a>
                        </li>
                        @if(isset($globalNavLinks) && $globalNavLinks->isNotEmpty())
                            @foreach($globalNavLinks as $navLink)
                                <li class="nav-item">
                                    <a class="nav-link {{ Str::contains(request()->url(), $navLink->getTranslation('slug', app()->getLocale())) ? 'active' : '' }}"
                                       href="{{ route('frontend.page.show', ['locale' => app()->getLocale(), 'slug' => $navLink->getTranslation('slug', app()->getLocale())]) }}">
                                        {{ $navLink->getTranslation('title', app()->getLocale()) }}
                                    </a>
                                </li>
                            @endforeach
                        @else
                            {{-- Liens de fallback si $globalNavLinks n'est pas disponible ou vide --}}
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}" href="{{ route('frontend.about', ['locale' => app()->getLocale()]) }}">{{ __('À Propos') }}</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.projects.index') || request()->routeIs('frontend.projects.show') ? 'active' : '' }}" href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}">{{ __('Projets') }}</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.posts.index') || request()->routeIs('frontend.posts.show') ? 'active' : '' }}" href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}">{{ __('Actualités') }}</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.events.index') || request()->routeIs('frontend.events.show') ? 'active' : '' }}" href="{{ route('frontend.events.index', ['locale' => app()->getLocale()]) }}">{{ __('Événements') }}</a></li>
                            {{-- Pensez à créer une route et une page pour Contact, ou à la rendre dynamique via le modèle Page --}}
                            <li class="nav-item"><a class="nav-link" href="#">{{ __('Contact') }}</a></li>
                        @endif
                    </ul>

                    {{-- Sélecteur de langue et liens Auth --}}
                    <ul class="navbar-nav {{ app()->getLocale() == 'ar' ? 'me-auto' : 'ms-auto' }}">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdownLang" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-globe me-1"></i> 
                                @if(app()->getLocale() == 'en') English @endif
                                @if(app()->getLocale() == 'fr') Français @endif
                                @if(app()->getLocale() == 'ar') العربية @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLang">
                                <a class="dropdown-item @if(app()->getLocale() == 'en') active disabled @endif" href="{{ url()->current() }}?lang=en">English</a>
                                <a class="dropdown-item @if(app()->getLocale() == 'fr') active disabled @endif" href="{{ url()->current() }}?lang=fr">Français</a>
                                <a class="dropdown-item @if(app()->getLocale() == 'ar') active disabled @endif" href="{{ url()->current() }}?lang=ar">العربية</a>
                            </div>
                        </li>
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}"><i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}"><i class="fas fa-user-plus me-1"></i> {{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-1"></i> {{ __('Admin') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('logout') }}"
                                   onclick="event.preventDefault(); document.getElementById('logout-form-frontend').submit();">
                                    <i class="fas fa-sign-out-alt me-1"></i> {{ __('Logout') }}
                                </a>
                                <form id="logout-form-frontend" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main>
            @yield('content')
        </main>

        <footer>
            <div class="container">
                <div class="row">
                    <div class="col-md-4 mb-4 mb-md-0">
                        @if($siteSettingsGlobal->footer_logo_path)
                            <img src="{{ Storage::url($siteSettingsGlobal->footer_logo_path) }}" alt="{{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) }}" style="max-height: 50px; margin-bottom: 1rem;">
                        @else
                            <h4 class="footer-heading">{{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }}</h4>
                        @endif
                        <p class="mt-2">{{ $siteSettingsGlobal->getTranslation('footer_description', app()->getLocale()) }}</p>
                        <div class="social-links mt-3">
                            @if($siteSettingsGlobal->social_facebook_url)<a href="{{ $siteSettingsGlobal->social_facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>@endif
                            @if($siteSettingsGlobal->social_twitter_url)<a href="{{ $siteSettingsGlobal->social_twitter_url }}" target="_blank" rel="noopener" aria-label="Twitter"><i class="fab fa-twitter"></i></a>@endif
                            @if($siteSettingsGlobal->social_instagram_url)<a href="{{ $siteSettingsGlobal->social_instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram"><i class="fab fa-instagram"></i></a>@endif
                            @if($siteSettingsGlobal->social_linkedin_url)<a href="{{ $siteSettingsGlobal->social_linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn"><i class="fab fa-linkedin-in"></i></a>@endif
                            @if($siteSettingsGlobal->social_youtube_url)<a href="{{ $siteSettingsGlobal->social_youtube_url }}" target="_blank" rel="noopener" aria-label="YouTube"><i class="fab fa-youtube"></i></a>@endif
                        </div>
                    </div>
                    <div class="col-md-2 mb-4 mb-md-0">
                        <h4 class="footer-heading">{{ __('Navigation') }}</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('frontend.home', ['locale' => app()->getLocale()]) }}">{{ __('Accueil') }}</a></li>
                             @php
                                $footerNavLinks = $globalNavLinks->where('show_in_footer_navigation', true)->sortBy('footer_navigation_order');
                             @endphp
                            @foreach($footerNavLinks as $link)
                                <li><a href="{{ route('frontend.page.show', ['locale' => app()->getLocale(), 'slug' => $link->getTranslation('slug', app()->getLocale())]) }}">{{ $link->getTranslation('title', app()->getLocale()) }}</a></li>
                            @endforeach
                            {{-- Fallback si pas de liens dynamiques pour le footer --}}
                             @if($footerNavLinks->isEmpty() && !$globalNavLinks->where('show_in_footer_navigation', true)->exists())
                                <li><a href="{{ route('frontend.about', ['locale' => app()->getLocale()]) }}">{{ __('À Propos') }}</a></li>
                                <li><a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}">{{ __('Projets') }}</a></li>
                                <li><a href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}">{{ __('Actualités') }}</a></li>
                                <li><a href="#">{{ __('Contact') }}</a></li> {{-- Mettez la bonne route pour contact --}}
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h4 class="footer-heading">{{ __('Liens Utiles') }}</h4>
                        <ul class="footer-links">
                             @php
                                $footerUsefulLinks = $globalNavLinks->where('show_in_footer_useful_links', true)->sortBy('footer_useful_links_order');
                             @endphp
                            @foreach($footerUsefulLinks as $link)
                                 <li><a href="{{ route('frontend.page.show', ['locale' => app()->getLocale(), 'slug' => $link->getTranslation('slug', app()->getLocale())]) }}">{{ $link->getTranslation('title', app()->getLocale()) }}</a></li>
                            @endforeach
                             {{-- Fallback ou liens fixes --}}
                            @if($footerUsefulLinks->isEmpty())
                                <li><a href="#">{{ __('Faire un don') }}</a></li>
                                <li><a href="#">{{ __('Devenir bénévole') }}</a></li>
                                <li><a href="#">{{ __('FAQ') }}</a></li>
                                <li><a href="#">{{ __('Mentions légales') }}</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h4 class="footer-heading">{{ __('Contact') }}</h4>
                        <address>
                            @if($siteSettingsGlobal->contact_address)<p><i class="fas fa-map-marker-alt"></i> {{ $siteSettingsGlobal->getTranslation('contact_address', app()->getLocale()) }}</p>@endif
                            @if($siteSettingsGlobal->contact_phone)<p><i class="fas fa-phone"></i> {{ $siteSettingsGlobal->contact_phone }}</p>@endif
                            @if($siteSettingsGlobal->contact_email)<p><i class="fas fa-envelope"></i> <a href="mailto:{{ $siteSettingsGlobal->contact_email }}" class="text-decoration-none" style="color:var(--dark-gray);">{{ $siteSettingsGlobal->contact_email }}</a></p>@endif
                        </address>
                        @if($siteSettingsGlobal->contact_map_iframe_url)
                            <div class="mt-2" style="max-width:100%; overflow:hidden; border-radius: 8px;">
                                <iframe src="{{ $siteSettingsGlobal->contact_map_iframe_url }}" width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="copyright">
                    <p>&copy; {{ date('Y') }} {{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }}. {{ $siteSettingsGlobal->getTranslation('footer_copyright_text', app()->getLocale()) ?? __('Tous droits réservés.') }}</p>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out-once', // plus fluide
                once: true // animation une seule fois
            });

            const navbar = document.querySelector('.navbar.sticky-top');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                    } else {
                        navbar.classList.remove('scrolled');
                    }
                });
            }
            // Gestion du dropdown de langue sur mobile pour qu'il ne sorte pas de l'écran
            const langDropdown = document.querySelector('#navbarDropdownLang');
            if(langDropdown) {
                langDropdown.addEventListener('show.bs.dropdown', function () {
                    if (window.innerWidth < 992) { // lg breakpoint
                        const menu = this.nextElementSibling;
                        menu.classList.remove('dropdown-menu-end');
                    } else {
                         const menu = this.nextElementSibling;
                         menu.classList.add('dropdown-menu-end');
                    }
                });
            }
        });
    </script>
    @stack('scripts_frontend')
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $siteSettingsGlobal->getDirection(app()->getLocale()) ?? (app()->getLocale() == 'ar' ? 'rtl' : 'ltr') }}">

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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @if ($siteSettingsGlobal->getDirection(app()->getLocale()) == 'rtl' || (empty($siteSettingsGlobal->getDirection(app()->getLocale())) && app()->getLocale() == 'ar') )
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @endif

    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet"> {{-- Votre CSS principal --}}

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />

    {{-- Styles spécifiques à la page (poussés par les vues enfants) --}}
    @stack('styles_frontend')

    {{-- Uniquement les variables CSS dynamiques restent ici --}}
    <style>
        :root {
            --primary-color: {{ $siteSettingsGlobal->primary_color ?? '#4CAF50' }};
            --secondary-color: {{ $siteSettingsGlobal->secondary_color ?? '#2E7D32' }};
            --accent-color: {{ $siteSettingsGlobal->accent_color ?? '#8BC34A' }}; /* Ajout de la variable accent-color si vous l'utilisez */

            /* Génération des composantes RGB pour les ombres avec opacité */
            @php
                $primary_rgb = implode(',', sscanf($siteSettingsGlobal->primary_color ?? '#4CAF50', "#%02x%02x%02x"));
                $secondary_rgb = implode(',', sscanf($siteSettingsGlobal->secondary_color ?? '#2E7D32', "#%02x%02x%02x"));
                $accent_rgb = implode(',', sscanf($siteSettingsGlobal->accent_color ?? '#8BC34A', "#%02x%02x%02x"));
            @endphp
            --primary-color-rgb: {{ $primary_rgb }};
            --secondary-color-rgb: {{ $secondary_rgb }};
            --accent-color-rgb: {{ $accent_rgb }};

            /* Définissez d'autres variables statiques ici si elles ne sont pas dans frontend.css,
               ou déplacez-les vers frontend.css si elles sont vraiment statiques.
               Pour cet exemple, je suppose que --text-color etc. seront dans frontend.css.
            */
        }
    </style>
</head>

<body>
    <div id="app">
        {{-- Votre barre de navigation existante (déjà corrigée) --}}
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
                    <ul class="navbar-nav {{ $siteSettingsGlobal->getDirection(app()->getLocale()) == 'rtl' ? 'ms-auto' : 'me-auto' }} mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home', ['locale' => app()->getLocale()]) }}">{{ __('Accueil') }}</a>
                        </li>
                        @if(isset($globalNavLinks) && $globalNavLinks->isNotEmpty())
                            @foreach($globalNavLinks as $navLink)
                                @php
                                    $pageSlug = $navLink->getTranslation('slug', app()->getLocale());
                                    $expectedUrl = $pageSlug ? route('frontend.page.show', ['locale' => app()->getLocale(), 'page' => $pageSlug]) : '#';
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->url() == $expectedUrl && $expectedUrl !== '#' ? 'active' : (request()->is($siteSettingsGlobal->getDirection(app()->getLocale()) . '/pages/' . $pageSlug . '*') ? 'active' : '') }}"
                                       href="{{ $expectedUrl }}">
                                        {{ $navLink->getTranslation('title', app()->getLocale()) }}
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}" href="{{ route('frontend.about', ['locale' => app()->getLocale()]) }}">{{ __('À Propos') }}</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.projects.index') || request()->routeIs('frontend.projects.show') ? 'active' : '' }}" href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}">{{ __('Projets') }}</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.posts.index') || request()->routeIs('frontend.posts.show') ? 'active' : '' }}" href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}">{{ __('Actualités') }}</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.events.index') || request()->routeIs('frontend.events.show') ? 'active' : '' }}" href="{{ route('frontend.events.index', ['locale' => app()->getLocale()]) }}">{{ __('Événements') }}</a></li>
                            <li class="nav-item"><a class="nav-link {{ request()->routeIs('frontend.contact.index') ? 'active' : '' }}" href="{{ route('frontend.contact.index', ['locale' => app()->getLocale()]) }}">{{ __('Contact') }}</a></li>
                        @endif
                    </ul>

                    <ul class="navbar-nav {{ $siteSettingsGlobal->getDirection(app()->getLocale()) == 'rtl' ? 'me-auto' : 'ms-auto' }}">
                        <li class="nav-item dropdown">
                            <a id="navbarDropdownLang" class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-globe me-1"></i>
                                @if(app()->getLocale() == 'en') English @endif
                                @if(app()->getLocale() == 'fr') Français @endif
                                @if(app()->getLocale() == 'ar') العربية @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownLang">
                                <a class="dropdown-item @if(app()->getLocale() == 'en') active disabled @endif" href="{{ route('language.switch', ['locale' => 'en']) }}">English</a>
                                <a class="dropdown-item @if(app()->getLocale() == 'fr') active disabled @endif" href="{{ route('language.switch', ['locale' => 'fr']) }}">Français</a>
                                <a class="dropdown-item @if(app()->getLocale() == 'ar') active disabled @endif" href="{{ route('language.switch', ['locale' => 'ar']) }}">العربية</a>
                            </div>
                        </li>
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login', ['locale' => app()->getLocale()]) }}"><i class="fas fa-sign-in-alt me-1"></i> {{ __('Login') }}</a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register', ['locale' => app()->getLocale()]) }}"><i class="fas fa-user-plus me-1"></i> {{ __('Register') }}</a>
                                </li>
                            @endif
                        @else
                            @if(Auth::user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-1"></i> {{ __('Admin') }}</a>
                            </li>
                            @endif
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

        {{-- Votre footer existant --}}
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
                                $footerNavLinksCollection = collect($globalNavLinks ?? []);
                                $footerNavLinks = $footerNavLinksCollection->where('show_in_footer_navigation', true)->sortBy('footer_navigation_order');
                            @endphp
                            @foreach($footerNavLinks as $link)
                                @php
                                    $footerPageSlug = $link->getTranslation('slug', app()->getLocale());
                                @endphp
                                <li><a href="{{ $footerPageSlug ? route('frontend.page.show', ['locale' => app()->getLocale(), 'page' => $footerPageSlug]) : '#' }}">{{ $link->getTranslation('title', app()->getLocale()) }}</a></li>
                            @endforeach
                            @if($footerNavLinks->isEmpty() && !$footerNavLinksCollection->where('show_in_footer_navigation', true)->exists())
                                <li><a href="{{ route('frontend.about', ['locale' => app()->getLocale()]) }}">{{ __('À Propos') }}</a></li>
                                <li><a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}">{{ __('Projets') }}</a></li>
                                <li><a href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}">{{ __('Actualités') }}</a></li>
                                <li><a href="{{ route('frontend.contact.index', ['locale' => app()->getLocale()]) }}">{{ __('Contact') }}</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-3 mb-4 mb-md-0">
                        <h4 class="footer-heading">{{ __('Liens Utiles') }}</h4>
                        <ul class="footer-links">
                            @php
                                $footerUsefulLinksCollection = collect($globalNavLinks ?? []);
                                $footerUsefulLinks = $footerUsefulLinksCollection->where('show_in_footer_useful_links', true)->sortBy('footer_useful_links_order');
                            @endphp
                            @foreach($footerUsefulLinks as $link)
                                @php
                                    $usefulPageSlug = $link->getTranslation('slug', app()->getLocale());
                                @endphp
                                <li><a href="{{ $usefulPageSlug ? route('frontend.page.show', ['locale' => app()->getLocale(), 'page' => $usefulPageSlug]) : '#' }}">{{ $link->getTranslation('title', app()->getLocale()) }}</a></li>
                            @endforeach
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
                            @if($siteSettingsGlobal->contact_email)<p><i class="fas fa-envelope"></i> <a href="mailto:{{ $siteSettingsGlobal->contact_email }}" class="text-decoration-none" style="color:inherit;">{{ $siteSettingsGlobal->contact_email }}</a></p>@endif
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
                easing: 'ease-in-out-once',
                once: true
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
            const langDropdown = document.querySelector('#navbarDropdownLang');
            if (langDropdown) {
                langDropdown.addEventListener('show.bs.dropdown', function() {
                    if (window.innerWidth < 992) { // lg breakpoint
                        const menu = this.nextElementSibling;
                        if (menu) menu.classList.remove('dropdown-menu-end');
                    } else {
                        const menu = this.nextElementSibling;
                        if (menu) menu.classList.add('dropdown-menu-end');
                    }
                });
            }
        });
    </script>
    @stack('scripts_frontend')
</body>
</html>
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ $siteSettingsGlobal->getDirection(app()->getLocale()) ?? (app()->getLocale() == 'ar' ? 'rtl' : 'ltr') }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('meta_description', $siteSettingsGlobal->getTranslation('footer_description', app()->getLocale(), config('app.description', 'Organisation Nama - Agir ensemble pour un avenir meilleur')))">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama'))</title>

    @if($siteSettingsGlobal->favicon)
    <link rel="icon" href="{{ asset($siteSettingsGlobal->favicon) }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset($siteSettingsGlobal->favicon) }}" type="image/x-icon">
    @endif

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Cairo:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @if ($siteSettingsGlobal->getDirection(app()->getLocale()) == 'rtl' || (empty($siteSettingsGlobal->getDirection(app()->getLocale())) && app()->getLocale() == 'ar') )
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    @endif

    <link href="{{ asset('css/frontend.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" />

    @stack('styles_frontend')

    <style>
        :root {
            --primary-color: {{ $siteSettingsGlobal->primary_color ?? '#4CAF50' }};
            --secondary-color: {{ $siteSettingsGlobal->secondary_color ?? '#2E7D32' }};
            --accent-color: {{ $siteSettingsGlobal->accent_color ?? '#8BC34A' }};

            @php
                $primary_rgb = implode(',', sscanf($siteSettingsGlobal->primary_color ?? '#4CAF50', "#%02x%02x%02x"));
                $secondary_rgb = implode(',', sscanf($siteSettingsGlobal->secondary_color ?? '#2E7D32', "#%02x%02x%02x"));
                $accent_rgb = implode(',', sscanf($siteSettingsGlobal->accent_color ?? '#8BC34A', "#%02x%02x%02x"));
            @endphp
            --primary-color-rgb: {{ $primary_rgb }};
            --secondary-color-rgb: {{ $secondary_rgb }};
            --accent-color-rgb: {{ $accent_rgb }};
        }
    </style>
</head>

<body>
    <div id="app">
        {{-- Navigation --}}
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center" href="{{ route('frontend.home', ['locale' => app()->getLocale()]) }}">
                    @if($siteSettingsGlobal->logo_path)
                    <img src="{{ asset($siteSettingsGlobal->logo_path) }}" 
                         alt="{{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }} Logo"
                         style="max-height: 45px; width: auto;">
                    @else
                    <span class="fw-bold fs-4" style="color: var(--primary-color);">
                        {{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }}
                    </span>
                    @endif
                </a>
                
                <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" 
                        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav {{ $siteSettingsGlobal->getDirection(app()->getLocale()) == 'rtl' ? 'ms-auto' : 'me-auto' }} mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" 
                               href="{{ route('frontend.home', ['locale' => app()->getLocale()]) }}">
                                <i class="fas fa-home me-1"></i>{{ __('Accueil') }}
                            </a>
                        </li>
                        
                        @if(isset($globalNavLinks) && $globalNavLinks->isNotEmpty())
                            @foreach($globalNavLinks as $navLink)
                                @php
                                    $pageSlug = $navLink->getTranslation('slug', app()->getLocale());
                                    $expectedUrl = $pageSlug ? route('frontend.page.show', ['locale' => app()->getLocale(), 'page' => $pageSlug]) : '#';
                                @endphp
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->url() == $expectedUrl && $expectedUrl !== '#' ? 'active' : '' }}"
                                       href="{{ $expectedUrl }}">
                                        {{ $navLink->getTranslation('title', app()->getLocale()) }}
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}" 
                                   href="{{ route('frontend.about', ['locale' => app()->getLocale()]) }}">
                                    <i class="fas fa-info-circle me-1"></i>{{ __('À Propos') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('frontend.projects.*') ? 'active' : '' }}" 
                                   href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}">
                                    <i class="fas fa-project-diagram me-1"></i>{{ __('Projets') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('frontend.posts.*') ? 'active' : '' }}" 
                                   href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}">
                                    <i class="fas fa-newspaper me-1"></i>{{ __('Actualités') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('frontend.events.*') ? 'active' : '' }}" 
                                   href="{{ route('frontend.events.index', ['locale' => app()->getLocale()]) }}">
                                    <i class="fas fa-calendar-alt me-1"></i>{{ __('Événements') }}
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('frontend.contact.*') ? 'active' : '' }}" 
                                   href="{{ route('frontend.contact.index', ['locale' => app()->getLocale()]) }}">
                                    <i class="fas fa-envelope me-1"></i>{{ __('Contact') }}
                                </a>
                            </li>
                        @endif
                    </ul>

                    <ul class="navbar-nav {{ $siteSettingsGlobal->getDirection(app()->getLocale()) == 'rtl' ? 'me-auto' : 'ms-auto' }}">
                        {{-- Sélecteur de langue --}}
                        <li class="nav-item dropdown">
                            <a id="navbarDropdownLang" class="nav-link dropdown-toggle d-flex align-items-center" 
                               href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-globe me-2"></i>
                                <span class="d-none d-md-inline">
                                    @if(app()->getLocale() == 'en') English @endif
                                    @if(app()->getLocale() == 'fr') Français @endif
                                    @if(app()->getLocale() == 'ar') العربية @endif
                                </span>
                                <span class="d-md-none">
                                    {{ strtoupper(app()->getLocale()) }}
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="navbarDropdownLang">
                                <a class="dropdown-item d-flex align-items-center @if(app()->getLocale() == 'en') active @endif" 
                                   href="{{ route('language.switch', ['locale' => 'en']) }}">
                                    <span class="me-2">🇺🇸</span> English
                                </a>
                                <a class="dropdown-item d-flex align-items-center @if(app()->getLocale() == 'fr') active @endif" 
                                   href="{{ route('language.switch', ['locale' => 'fr']) }}">
                                    <span class="me-2">🇫🇷</span> Français
                                </a>
                                <a class="dropdown-item d-flex align-items-center @if(app()->getLocale() == 'ar') active @endif" 
                                   href="{{ route('language.switch', ['locale' => 'ar']) }}">
                                    <span class="me-2">🇸🇦</span> العربية
                                </a>
                            </div>
                        </li>

                        {{-- Menu utilisateur --}}
                        @guest
                            @if (Route::has('login'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('login') }}">
                                        <i class="fas fa-sign-in-alt me-1"></i> {{ __('Connexion') }}
                                    </a>
                                </li>
                            @endif
                            @if (Route::has('register'))
                                <li class="nav-item">
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="fas fa-user-plus me-1"></i> {{ __('Inscription') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdownUser" class="nav-link dropdown-toggle d-flex align-items-center" 
                                   href="#" role="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" 
                                         style="width: 32px; height: 32px; font-size: 0.875rem;">
                                        {{ substr(Auth::user()->name, 0, 1) }}
                                    </div>
                                    <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="navbarDropdownUser">
                                    @if(Auth::user()->isAdmin())
                                    <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="fas fa-tachometer-alt me-2 text-primary"></i> {{ __('Administration') }}
                                    </a>
                                    <div class="dropdown-divider"></div>
                                    @endif
                                    <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form-frontend').submit();">
                                        <i class="fas fa-sign-out-alt me-2 text-secondary"></i> {{ __('Déconnexion') }}
                                    </a>
                                    <form id="logout-form-frontend" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        {{-- Contenu principal --}}
        <main>
            @yield('content')
        </main>

        {{-- Footer --}}
        <footer class="bg-white border-top">
            <div class="container py-5">
                <div class="row">
                    {{-- Colonne À propos --}}
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="mb-3">
                            @if($siteSettingsGlobal->footer_logo_path)
                            <img src="{{ asset($siteSettingsGlobal->footer_logo_path) }}" 
                                 alt="{{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) }}" 
                                 style="max-height: 50px; width: auto;">
                            @else
                            <h4 class="footer-heading">{{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }}</h4>
                            @endif
                        </div>
                        <p class="text-muted mb-3">{{ $siteSettingsGlobal->getTranslation('footer_description', app()->getLocale()) }}</p>
                        
                        {{-- Réseaux sociaux --}}
                        <div class="social-links">
                            @if($siteSettingsGlobal->social_facebook_url)
                            <a href="{{ $siteSettingsGlobal->social_facebook_url }}" target="_blank" rel="noopener" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            @endif
                            @if($siteSettingsGlobal->social_twitter_url)
                            <a href="{{ $siteSettingsGlobal->social_twitter_url }}" target="_blank" rel="noopener" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            @endif
                            @if($siteSettingsGlobal->social_instagram_url)
                            <a href="{{ $siteSettingsGlobal->social_instagram_url }}" target="_blank" rel="noopener" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            @endif
                            @if($siteSettingsGlobal->social_linkedin_url)
                            <a href="{{ $siteSettingsGlobal->social_linkedin_url }}" target="_blank" rel="noopener" aria-label="LinkedIn">
                                <i class="fab fa-linkedin-in"></i>
                            </a>
                            @endif
                            @if($siteSettingsGlobal->social_youtube_url)
                            <a href="{{ $siteSettingsGlobal->social_youtube_url }}" target="_blank" rel="noopener" aria-label="YouTube">
                                <i class="fab fa-youtube"></i>
                            </a>
                            @endif
                        </div>
                    </div>

                    {{-- Colonne Navigation --}}
                    <div class="col-lg-2 col-md-6 mb-4">
                        <h5 class="footer-heading">{{ __('Navigation') }}</h5>
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
                            @if($footerNavLinks->isEmpty())
                                <li><a href="{{ route('frontend.about', ['locale' => app()->getLocale()]) }}">{{ __('À Propos') }}</a></li>
                                <li><a href="{{ route('frontend.projects.index', ['locale' => app()->getLocale()]) }}">{{ __('Projets') }}</a></li>
                                <li><a href="{{ route('frontend.posts.index', ['locale' => app()->getLocale()]) }}">{{ __('Actualités') }}</a></li>
                                <li><a href="{{ route('frontend.events.index', ['locale' => app()->getLocale()]) }}">{{ __('Événements') }}</a></li>
                            @endif
                        </ul>
                    </div>

                    {{-- Colonne Liens utiles --}}
                    <div class="col-lg-3 col-md-6 mb-4">
                        <h5 class="footer-heading">{{ __('Liens Utiles') }}</h5>
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
                                <li><a href="#">{{ __('Politique de confidentialité') }}</a></li>
                            @endif
                        </ul>
                    </div>

                    {{-- Colonne Contact --}}
                    <div class="col-lg-3 col-md-6 mb-4">
                        <h5 class="footer-heading">{{ __('Contact') }}</h5>
                        <address class="mb-0">
                            @if($siteSettingsGlobal->getTranslation('contact_address', app()->getLocale()))
                            <p><i class="fas fa-map-marker-alt"></i> {{ $siteSettingsGlobal->getTranslation('contact_address', app()->getLocale()) }}</p>
                            @endif
                            @if($siteSettingsGlobal->contact_phone)
                            <p><i class="fas fa-phone"></i> <a href="tel:{{ $siteSettingsGlobal->contact_phone }}" class="text-decoration-none" style="color:inherit;">{{ $siteSettingsGlobal->contact_phone }}</a></p>
                            @endif
                            @if($siteSettingsGlobal->contact_email)
                            <p><i class="fas fa-envelope"></i> <a href="mailto:{{ $siteSettingsGlobal->contact_email }}" class="text-decoration-none" style="color:inherit;">{{ $siteSettingsGlobal->contact_email }}</a></p>
                            @endif
                        </address>

                        {{-- Bouton CTA --}}
                        <div class="mt-3">
                            <a href="{{ route('frontend.contact.index', ['locale' => app()->getLocale()]) }}" 
                               class="btn btn-primary btn-sm">
                                <i class="fas fa-paper-plane me-1"></i>
                                {{ __('Nous contacter') }}
                            </a>
                        </div>
                    </div>
                </div>

                {{-- Copyright --}}
                <div class="copyright text-center pt-4 mt-4 border-top">
                    <p class="mb-0 text-muted">
                        &copy; {{ date('Y') }} {{ $siteSettingsGlobal->getTranslation('site_name', app()->getLocale()) ?? config('app.name', 'Nama') }}. 
                        {{ $siteSettingsGlobal->getTranslation('footer_copyright_text', app()->getLocale()) ?? __('Tous droits réservés.') }}
                    </p>
                </div>
            </div>
        </footer>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation AOS
            AOS.init({
                duration: 800,
                easing: 'ease-in-out-once',
                once: true,
                offset: 100
            });

            // Navbar scroll effect
            const navbar = document.querySelector('.navbar.sticky-top');
            if (navbar) {
                window.addEventListener('scroll', function() {
                    if (window.scrollY > 50) {
                        navbar.classList.add('scrolled');
                        navbar.classList.add('shadow');
                    } else {
                        navbar.classList.remove('scrolled');
                        navbar.classList.remove('shadow');
                    }
                });
            }

            // Responsive language dropdown
            const langDropdown = document.querySelector('#navbarDropdownLang');
            if (langDropdown) {
                langDropdown.addEventListener('show.bs.dropdown', function() {
                    const menu = this.nextElementSibling;
                    if (window.innerWidth < 992) {
                        menu.classList.remove('dropdown-menu-end');
                    } else {
                        menu.classList.add('dropdown-menu-end');
                    }
                });
            }

            // Smooth scroll pour les ancres
            document.querySelectorAll('a[href^="#"]').forEach(anchor => {
                anchor.addEventListener('click', function (e) {
                    const target = document.querySelector(this.getAttribute('href'));
                    if (target) {
                        e.preventDefault();
                        target.scrollIntoView({
                            behavior: 'smooth',
                            block: 'start'
                        });
                    }
                });
            });

            // Auto-hide alerts après 5 secondes
            setTimeout(() => {
                document.querySelectorAll('.alert').forEach(alert => {
                    if (alert.classList.contains('alert-success')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
        });
    </script>

    @stack('scripts_frontend')
</body>
</html>
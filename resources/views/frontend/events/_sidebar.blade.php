{{-- resources/views/frontend/posts/_sidebar.blade.php --}}

{{-- Widget Recherche (Optionnel) --}}
<div class="sidebar-widget">
    <h5 class="widget-title">{{ __('Search') }}</h5>
    <form action="{{ route('frontend.posts.index') }}" method="GET">
        <div class="input-group">
            <input type="text" name="search" class="form-control" placeholder="{{ __('Search posts...') }}" value="{{ request('search') }}">
            <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
        </div>
    </form>
</div>


{{-- Widget Catégories --}}
@if (isset($categories) && $categories->isNotEmpty())
<div class="sidebar-widget">
    <h5 class="widget-title">{{ __('Categories') }}</h5>
    <ul class="list-unstyled">
        @foreach ($categories as $category)
            <li>
                <a href="{{ route('frontend.posts.index', ['category' => $category->slug]) }}" 
                   class="{{ request('category') == $category->slug ? 'fw-bold text-primary' : '' }}">
                    {{ $category->name }} 
                    {{-- <span class="badge bg-secondary float-end">{{ $category->posts_count }}</span> --}}
                    {{-- Pour posts_count, il faudrait charger la relation avec un count dans le contrôleur --}}
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endif

{{-- Widget Articles Récents --}}
@if (isset($recentPosts) && $recentPosts->isNotEmpty())
<div class="sidebar-widget">
    <h5 class="widget-title">{{ __('Recent Posts') }}</h5>
    <ul class="list-unstyled">
        @foreach ($recentPosts as $recentPost)
            <li class="mb-2">
                <a href="{{ route('frontend.posts.show', $recentPost->slug) }}" class="d-flex align-items-start text-decoration-none">
                    @if($recentPost->featured_image)
                        <img src="{{ Storage::url($recentPost->featured_image) }}" alt="{{ $recentPost->title }}" width="60" height="60" class="rounded me-2 {{ app()->getLocale() == 'ar' ? 'ms-2 me-0' : '' }}" style="object-fit: cover;">
                    @else
                        <div class="bg-secondary rounded me-2 {{ app()->getLocale() == 'ar' ? 'ms-2 me-0' : '' }}" style="width: 60px; height: 60px; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-image text-white"></i>
                        </div>
                    @endif
                    <div style="line-height: 1.3;">
                        <span class="d-block text-dark fw-medium">{{ Str::limit($recentPost->title, 50) }}</span>
                        @if($recentPost->published_at)
                        <small class="text-muted">{{ $recentPost->published_at->diffForHumans() }}</small>
                        @endif
                    </div>
                </a>
            </li>
        @endforeach
    </ul>
</div>
@endif

{{-- Widget Archives (Optionnel) --}}
{{-- 
<div class="sidebar-widget">
    <h5 class="widget-title">{{ __('Archives') }}</h5>
    <ul class="list-unstyled">
        <li><a href="#">Mai 2025</a></li>
        <li><a href="#">Avril 2025</a></li>
        <li><a href="#">Mars 2025</a></li>
    </ul>
</div>
--}}

{{-- Widget Tags/Mots-clés (Optionnel, si tu implémentes des tags) --}}
{{--
<div class="sidebar-widget">
    <h5 class="widget-title">{{ __('Tags') }}</h5>
    <div>
        <a href="#" class="badge bg-light text-dark me-1 mb-1 text-decoration-none">Laravel</a>
        <a href="#" class="badge bg-light text-dark me-1 mb-1 text-decoration-none">PHP</a>
        <a href="#" class="badge bg-light text-dark me-1 mb-1 text-decoration-none">Web Development</a>
    </div>
</div>
--}}
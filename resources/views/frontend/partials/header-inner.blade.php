<div class="header-top header-top-inner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <div class="header-top-left">
                    <div class="top-social">
                        @if ($socialMap->get('facebook'))
                            <a href="{{ $socialMap->get('facebook') }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a>
                        @endif
                        @if ($socialMap->get('youtube'))
                            <a href="{{ $socialMap->get('youtube') }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a>
                        @endif
                    </div>
                    <div class="top-contact-info">
                        <ul>
                            <li>
                                @if ($hasDisplayValue($hotline))
                                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $hotline) }}"><i class="far fa-phone-arrow-down-left"></i>{{ $displayValue($hotline) }}</a>
                                @else
                                    <span><i class="far fa-phone-arrow-down-left"></i>{{ $displayValue($hotline) }}</span>
                                @endif
                            </li>
                            <li>
                                @if ($hasDisplayValue($email))
                                    <a href="mailto:{{ $email }}"><i class="far fa-envelopes"></i>{{ $displayValue($email) }}</a>
                                @else
                                    <span><i class="far fa-envelopes"></i>{{ $displayValue($email) }}</span>
                                @endif
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-md-5">
                <div class="header-top-right">
                    <div class="account">
                        <a href="{{ route('frontend.wishlist.index') }}" class="position-relative wishlist-header-link" style="margin-right: 15px; {{ count(array_filter(session('wishlist', []))) > 0 ? '' : 'display: none;' }}">
                            <i class="far fa-heart" style="font-size: 18px; margin-right: 0;"></i>
                            <span id="wishlist-counter" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 10px; padding: 3px 5px; border: 1px solid #fff; margin-top: 5px;">
                                {{ count(array_filter(session('wishlist', []))) }}
                            </span>
                        </a>
                        @auth
                            <a href="{{ route('frontend.profile') }}"><i class="far fa-user"></i> {{ auth()->user()->name }}</a>
                            
                        @else
                            <a href="{{ route('frontend.login') }}"><i class="far fa-sign-in"></i> Đăng nhập</a>
                            <a href="{{ route('frontend.register') }}"><i class="far fa-user-tie"></i> Đăng ký</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<header class="header sticky">
    <div class="main-navigation">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="{{ route('frontend.home') }}">
                    @if ($logoDark || $logo)
                        <img src="{{ $logoDark ?: $logo }}" class="logo-inner-page" alt="{{ $displayValue($settings->company_name ?? null, 'Logo') }}">
                    @endif
                </a>
                <div class="mobile-menu-right">
                    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas"
                        data-bs-target="#main_nav" aria-controls="main_nav" aria-expanded="false" aria-label="Mo menu dieu huong">
                        <span class="navbar-toggler-btn-icon"><i class="fas fa-bars"></i></span>
                    </button>
                </div>
                <div class="offcanvas-lg offcanvas-end" tabindex="-1" id="main_nav" aria-labelledby="main_nav_label">
                    <div class="offcanvas-header border-bottom">
                        <h5 class="offcanvas-title" id="main_nav_label">
                            @if ($logoDark)
                                <img src="{{ $logoDark }}" alt="Logo" style="height: 30px;">
                            @else
                                Menu
                            @endif
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" data-bs-target="#main_nav" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <ul class="navbar-nav w-100">
                        @forelse ($menuTree ?? collect() as $menu)
                            @include('frontend.partials.tourit-menu-item', ['menu' => $menu, 'level' => 0])
                        @empty
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('frontend.home') ? 'active' : '' }}" href="{{ route('frontend.home') }}">Trang chu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('frontend.about') ? 'active' : '' }}" href="{{ route('frontend.about') }}">Gioi thieu</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('frontend.contact') ? 'active' : '' }}" href="{{ route('frontend.contact') }}">Lien he</a>
                            </li>
                        @endforelse
                    </ul>
                    </div>
                </div>
            </div>
        </nav>
    </div>
</header>

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
                        <a href="#"><i class="far fa-sign-in"></i>Dang nhap</a>
                        <a href="#"><i class="far fa-user-tie"></i>Dang ky</a>
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
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                        data-bs-target="#main_nav" aria-expanded="false" aria-label="Mo menu dieu huong">
                        <span class="navbar-toggler-btn-icon"><i class="far fa-bars"></i></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="main_nav">
                    <ul class="navbar-nav">
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
        </nav>
    </div>
</header>

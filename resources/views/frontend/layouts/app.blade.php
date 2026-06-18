@php
    $frontendBase = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
    $logo = $settings ? \App\Support\MediaManager::publicUrl($settings->logo) : null;
    $logoDark = $settings ? \App\Support\MediaManager::publicUrl($settings->footer_logo) : null;
    $favicon = $settings ? \App\Support\MediaManager::publicUrl($settings->favicon) : null;
    $hotline = $settings->hotline ?? null;
    $email = $settings->email ?? null;
    $socialMap = collect($settings->social ?? [])->mapWithKeys(function ($item) {
        return [strtolower($item['label'] ?? '') => $item['url'] ?? null];
    });
    $isHomePage = request()->routeIs('frontend.home');
    $useHomeHeader = $isHomePage || request()->routeIs('frontend.about') || request()->routeIs('frontend.contact');
@endphp
<!DOCTYPE html>
<html lang="vi">
<head>
    <base href="{{ $frontendBase }}/tourit/">
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="@yield('meta_description', $pageDescription ?? '')">
    <meta name="keywords" content="@yield('meta_keywords', $pageKeywords ?? '')">
    <title>@yield('title', $pageTitle ?? 'Vietnam homes Tourist')</title>
    @if ($favicon)
        <link rel="icon" href="{{ $favicon }}">
        <link rel="shortcut icon" href="{{ $favicon }}">
        <link rel="apple-touch-icon" href="{{ $favicon }}">
    @endif
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/all-fontawesome.min.css">
    <link rel="stylesheet" href="assets/css/animate.min.css">
    <link rel="stylesheet" href="assets/css/magnific-popup.min.css">
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css">
    <link rel="stylesheet" href="assets/css/nice-select.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/jquery.timepicker.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/local-fixes.css">
    @stack('styles')
</head>
<body class="{{ $useHomeHeader ? 'frontend-home' : 'frontend-inner' }}">
    <div class="wrapper-class">
        @include($useHomeHeader ? 'frontend.partials.header-home' : 'frontend.partials.header-inner')
        @yield('content')
        @include('frontend.partials.footer')
    </div>

    <script src="assets/js/jquery-3.7.1.min.js"></script>
    <script src="assets/js/modernizr.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/imagesloaded.pkgd.min.js"></script>
    <script src="assets/js/jquery.magnific-popup.min.js"></script>
    <script src="assets/js/isotope.pkgd.min.js"></script>
    <script src="assets/js/jquery.appear.min.js"></script>
    <script src="assets/js/jquery.easing.min.js"></script>
    <script src="assets/js/owl.carousel.min.js"></script>
    <script src="assets/js/counter-up.js"></script>
    <script src="assets/js/masonry.pkgd.min.js"></script>
    <script src="assets/js/jquery.nice-select.min.js"></script>
    <script src="assets/js/jquery-ui.min.js"></script>
    <script src="assets/js/jquery.timepicker.min.js"></script>
    <script src="assets/js/wow.min.js"></script>
    <script src="assets/js/main.js"></script>
    <script src="assets/js/local-fixes.js"></script>
    @stack('scripts')
</body>
</html>

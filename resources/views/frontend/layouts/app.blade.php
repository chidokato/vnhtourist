@php
    $frontendBase = rtrim(request()->getSchemeAndHttpHost() . request()->getBaseUrl(), '/');
    $logo = $settings ? \App\Support\MediaManager::publicUrl($settings->logo) : null;
    $logoDark = $settings ? \App\Support\MediaManager::publicUrl($settings->footer_logo) : null;
    $favicon = $settings ? \App\Support\MediaManager::publicUrl($settings->favicon) : null;
    $owlCarouselCssVersion = @filemtime(public_path('tourit/assets/css/owl.carousel.min.css')) ?: time();
    $tourCardCssVersion = @filemtime(public_path('tourit/assets/css/tour-card.css')) ?: time();
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
    <link rel="stylesheet" href="assets/css/owl.carousel.min.css?v={{ $owlCarouselCssVersion }}">
    <link rel="stylesheet" href="assets/css/nice-select.min.css">
    <link rel="stylesheet" href="assets/css/jquery-ui.min.css">
    <link rel="stylesheet" href="assets/css/jquery.timepicker.min.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/tour-card.css?v={{ $tourCardCssVersion }}">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#3085d6'
                });
            @endif

            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi!',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#d33'
                });
            @endif

            @if(session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Cảnh báo!',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#f8bb86'
                });
            @endif

            @if(session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Thông tin',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#3fc3ee'
                });
            @endif

            @if($errors->any())
                let errorMessages = '';
                @foreach($errors->all() as $error)
                    errorMessages += '{{ $error }}\n';
                @endforeach
                Swal.fire({
                    icon: 'error',
                    title: 'Có lỗi xảy ra!',
                    text: errorMessages,
                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#d33'
                });
            @endif
        });

        function toggleWishlist(element, productId) {
            fetch('{{ route('frontend.wishlist.toggle') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    if(data.is_added) {
                        element.classList.add('active');
                        element.querySelector('i').classList.remove('far');
                        element.querySelector('i').classList.add('fas');
                    } else {
                        element.classList.remove('active');
                        element.querySelector('i').classList.remove('fas');
                        element.querySelector('i').classList.add('far');
                    }
                    
                    // Update header counter if exists
                    const counters = document.querySelectorAll('#wishlist-counter, #wishlist-counter-home');
                    counters.forEach(c => c.innerText = data.count);
                    
                    const wishlistLinks = document.querySelectorAll('.wishlist-header-link');
                    wishlistLinks.forEach(link => {
                        if (data.count > 0) {
                            link.style.display = '';
                        } else {
                            link.style.display = 'none';
                        }
                    });

                    // Optional: Show toast or alert
                    // Swal.fire({
                    //     icon: 'success',
                    //     title: 'Thành công',
                    //     text: data.message,
                    //     timer: 1500,
                    //     showConfirmButton: false
                    // });
                }
            })
            .catch(error => console.error('Error:', error));
        }
    </script>
    @stack('scripts')
</body>
</html>

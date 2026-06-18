@extends('frontend.layouts.app')

@php
    $partnerLogos = [
        '/tourit/img/logdoitac.webp',
        '/tourit/img/logdoitac1.webp',
        '/tourit/img/logdoitac2.webp',
        '/tourit/img/logdoitac3.webp',
        '/tourit/img/logdoitac4.webp',
        '/tourit/img/logdoitac5.webp',
        '/tourit/img/logdoitac6.webp',
    ];
@endphp

@section('title', $pageTitle ?? 'Giới thiệu')
@section('meta_description', $pageDescription ?? '')
@section('meta_keywords', $pageKeywords ?? '')

@section('content')
    <main class="main">
        <div class="site-breadcrumb" style="background: url(assets/img/banner/04.jpg)">
            <div class="container">
                <h2 class="breadcrumb-title">Giới thiệu</h2>
                <ul class="breadcrumb-menu">
                    <li><a href="{{ route('frontend.home') }}">Trang chủ</a></li>
                    <li class="active">Giới thiệu</li>
                </ul>
            </div>
        </div>

        <div class="about-area py-120">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="about-left wow fadeInLeft" data-wow-delay=".25s">
                            <div class="about-img">
                                <div class="row">
                                    <div class="col-6">
                                        <img class="img-1" src="assets/img/about/01.jpg" alt="Giới thiệu 1">
                                    </div>
                                    <div class="col-6">
                                        <img class="img-2" src="assets/img/about/02.jpg" alt="Giới thiệu 2">
                                    </div>
                                </div>
                            </div>
                            <div class="about-experience">
                                <h5>30<span>+</span></h5>
                                <p>Năm kinh nghiệm</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="about-right wow fadeInUp" data-wow-delay=".25s">
                            <div class="site-heading mb-3">
                                <span class="site-title-tagline"><i class="far fa-plane"></i> Về chúng tôi</span>
                                <h2 class="site-title">Đồng hành cùng bạn trong mọi <span>hành trình du lịch</span></h2>
                            </div>
                            <p class="about-text">
                                Chúng tôi phát triển nền tảng đặt tour, khách sạn, xe và du thuyền với mục tiêu giúp khách hàng
                                dễ chọn, dễ đặt và dễ theo dõi toàn bộ kế hoạch chuyến đi ở một nơi.
                            </p>
                            <p class="about-text">
                                Mỗi dịch vụ đều được tối ưu theo nhu cầu thực tế: từ chuyến đi gia đình, nghỉ dưỡng ngắn ngày,
                                tour đoàn, đến các gói dịch vụ trọn bộ dành cho khách hàng cần sự thuận tiện và rõ ràng.
                            </p>
                            <div class="about-content">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="about-item">
                                            <div class="icon">
                                                <img src="assets/img/icon1.png" alt="Ưu đãi">
                                            </div>
                                            <div class="content">
                                                <h6>Giá rõ ràng</h6>
                                                <p>Dễ so sánh gói dịch vụ, chi phí và lựa chọn phù hợp với ngân sách.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="about-item">
                                            <div class="icon">
                                                <img src="assets/img/icon2.svg" alt="Đặt chỗ">
                                            </div>
                                            <div class="content">
                                                <h6>Đăng ký nhanh</h6>
                                                <p>Rút gọn thao tác đăng ký đặt dịch vụ để khách hàng chốt lịch thuận tiện hơn.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="about-item">
                                            <div class="icon">
                                                <img src="assets/img/world.svg" alt="Mạng lưới">
                                            </div>
                                            <div class="content">
                                                <h6>Mạng lưới đa dạng</h6>
                                                <p>Kết nối nhiều lựa chọn về điểm đến, lưu trú và phương tiện cho từng nhu cầu.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="about-item">
                                            <div class="icon">
                                                <img src="assets/img/support.svg" alt="Hỗ trợ">
                                            </div>
                                            <div class="content">
                                                <h6>Hỗ trợ tận tâm</h6>
                                                <p>Đội ngũ luôn sẵn sàng đồng hành trước, trong và sau khi khách đặt dịch vụ.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('frontend.contact') }}" class="theme-btn">Liên hệ ngay <i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="counter-area pb-90">
            <div class="container">
                <div class="counter-wrap about-counter-frame">
                    <div class="row g-4">
                        <div class="col-md-6 col-lg-3">
                            <div class="counter-box">
                                <div class="icon"><img src="assets/img/quality.svg" alt="Chất lượng"></div>
                                <div>
                                    <div class="counter-number"><span class="counter" data-from="0" data-to="1500" data-speed="1500">1500</span><span class="counter-sign">+</span></div>
                                    <h6 class="title">Khách hàng đăng ký</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="counter-box">
                                <div class="icon"><img src="assets/img/price.svg" alt="Giá tốt"></div>
                                <div>
                                    <div class="counter-number"><span class="counter" data-from="0" data-to="320" data-speed="1500">320</span><span class="counter-sign">+</span></div>
                                    <h6 class="title">Gói dịch vụ nổi bật</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="counter-box">
                                <div class="icon"><img src="assets/img/safety.svg" alt="An toàn"></div>
                                <div>
                                    <div class="counter-number"><span class="counter" data-from="0" data-to="98" data-speed="1500">98</span><span class="counter-sign">%</span></div>
                                    <h6 class="title">Tỷ lệ hài lòng</h6>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-3">
                            <div class="counter-box">
                                <div class="icon"><img src="assets/img/support.svg" alt="Hỗ trợ"></div>
                                <div>
                                    <div class="counter-number"><span class="counter" data-from="0" data-to="24" data-speed="1500">24</span><span class="counter-sign">/7</span></div>
                                    <h6 class="title">Hỗ trợ khách hàng</h6>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="testimonial-area ts-bg py-120">
            <div class="shadow-text">Vietnam homes Tourist</div>
            <div class="container pb-30">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center mb-4">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Cảm nhận khách hàng</span>
                            <h2 class="site-title text-white">Khách hàng nói gì về chúng tôi?</h2>
                        </div>
                    </div>
                </div>
                <div class="testimonial-slider owl-carousel owl-theme wow fadeInUp" data-wow-duration="1s"
                    data-wow-delay=".25s">
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="assets/img/testimonial/01.jpg" alt="">
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <span class="count">01</span>
                            <div class="testimonial-author-info">
                                <h4>Diana Carter</h4>
                                <p>Khách hàng của chúng tôi</p>
                            </div>
                            <p>There are many variations passages of available but to the majority have suffered for the alteration in some form injected words which look even slig believable.</p>
                            <div class="testimonial-quote-icon">
                                <img src="assets/img/icon/quote.svg" alt="">
                            </div>
                            <div class="testimonial-rate">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="assets/img/testimonial/02.jpg" alt="">
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <span class="count">02</span>
                            <div class="testimonial-author-info">
                                <h4>Brandon Wigfall</h4>
                                <p>Khách hàng của chúng tôi</p>
                            </div>
                            <p>There are many variations passages of available but to the majority have suffered for the alteration in some form injected words which look even slig believable.</p>
                            <div class="testimonial-quote-icon">
                                <img src="assets/img/icon/quote.svg" alt="">
                            </div>
                            <div class="testimonial-rate">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="assets/img/testimonial/03.jpg" alt="">
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <span class="count">03</span>
                            <div class="testimonial-author-info">
                                <h4>Sylvia Green</h4>
                                <p>Khách hàng của chúng tôi</p>
                            </div>
                            <p>There are many variations passages of available but to the majority have suffered for the alteration in some form injected words which look even slig believable.</p>
                            <div class="testimonial-quote-icon">
                                <img src="assets/img/icon/quote.svg" alt="">
                            </div>
                            <div class="testimonial-rate">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                    <div class="testimonial-single">
                        <div class="testimonial-content">
                            <div class="testimonial-author-img">
                                <img src="assets/img/testimonial/04.jpg" alt="">
                            </div>
                        </div>
                        <div class="testimonial-quote">
                            <span class="count">04</span>
                            <div class="testimonial-author-info">
                                <h4>Miguel Woodworth</h4>
                                <p>Khách hàng của chúng tôi</p>
                            </div>
                            <p>There are many variations passages of available but to the majority have suffered for the alteration in some form injected words which look even slig believable.</p>
                            <div class="testimonial-quote-icon">
                                <img src="assets/img/icon/quote.svg" alt="">
                            </div>
                            <div class="testimonial-rate">
                                <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="banner-area bg pt-50 pb-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Ưu đãi</span>
                            <h2 class="site-title">Khám phá ưu đãi đặc quyền</h2>
                        </div>
                    </div>
                </div>
                <div class="banner-slider owl-carousel owl-theme">
                    <div class="banner-item">
                        <div class="banner-img">
                            <img src="assets/img/banner/01.jpg" alt="">
                        </div>
                        <div class="banner-content">
                            <h6>Nhận ưu đãi đến <span>70%</span>!</h6>
                            <p>It is a long established fact that reader distracted.</p>
                            <a href="#" class="theme-btn">Tìm hiểu thêm<i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="banner-item">
                        <div class="banner-img">
                            <img src="assets/img/banner/02.jpg" alt="">
                        </div>
                        <div class="banner-content">
                            <h6>Nhận ưu đãi đến <span>70%</span>!</h6>
                            <p>It is a long established fact that reader distracted.</p>
                            <a href="#" class="theme-btn">Tìm hiểu thêm<i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="banner-item">
                        <div class="banner-img">
                            <img src="assets/img/banner/03.jpg" alt="">
                        </div>
                        <div class="banner-content">
                            <h6>Nhận ưu đãi đến <span>70%</span>!</h6>
                            <p>It is a long established fact that reader distracted.</p>
                            <a href="#" class="theme-btn">Tìm hiểu thêm<i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                    <div class="banner-item">
                        <div class="banner-img">
                            <img src="assets/img/banner/04.jpg" alt="">
                        </div>
                        <div class="banner-content">
                            <h6>Nhận ưu đãi đến <span>70%</span>!</h6>
                            <p>It is a long established fact that reader distracted.</p>
                            <a href="#" class="theme-btn">Tìm hiểu thêm<i class="fas fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="team-area pb-120">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mx-auto wow fadeInDown" data-wow-duration="1s" data-wow-delay=".25s">
                        <div class="site-heading text-center">
                            <span class="site-title-tagline"><i class="far fa-plane"></i> Đội ngũ</span>
                            <h2 class="site-title">Những người tạo nên trải nghiệm dịch vụ</h2>
                        </div>
                    </div>
                </div>
                <div class="row g-5">
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".25s">
                            <div class="team-img">
                                <img src="assets/img/team/01.jpg" alt="Edna Craig">
                            </div>
                            <div class="team-content">
                                <div class="team-bio">
                                    <h5><a href="#">Edna Craig</a></h5>
                                    <span>Head of Design</span>
                                </div>
                                <div class="team-social">
                                    <ul class="team-social-btn">
                                        <li><span><i class="far fa-share-alt"></i></span></li>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".50s">
                            <div class="team-img">
                                <img src="assets/img/team/02.jpg" alt="Jeffrey Cox">
                            </div>
                            <div class="team-content">
                                <div class="team-bio">
                                    <h5><a href="#">Jeffrey Cox</a></h5>
                                    <span>Founder & Director</span>
                                </div>
                                <div class="team-social">
                                    <ul class="team-social-btn">
                                        <li><span><i class="far fa-share-alt"></i></span></li>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay=".75s">
                            <div class="team-img">
                                <img src="assets/img/team/03.jpg" alt="Audrey Gadis">
                            </div>
                            <div class="team-content">
                                <div class="team-bio">
                                    <h5><a href="#">Audrey Gadis</a></h5>
                                    <span>Hỗ trợ tư vấn</span>
                                </div>
                                <div class="team-social">
                                    <ul class="team-social-btn">
                                        <li><span><i class="far fa-share-alt"></i></span></li>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4 col-xl-3">
                        <div class="team-item wow fadeInUp" data-wow-duration="1s" data-wow-delay="1s">
                            <div class="team-img">
                                <img src="assets/img/team/04.jpg" alt="Rodger Garza">
                            </div>
                            <div class="team-content">
                                <div class="team-bio">
                                    <h5><a href="#">Rodger Garza</a></h5>
                                    <span>Account Manager</span>
                                </div>
                                <div class="team-social">
                                    <ul class="team-social-btn">
                                        <li><span><i class="far fa-share-alt"></i></span></li>
                                        <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                        <li><a href="#"><i class="fab fa-x-twitter"></i></a></li>
                                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                        <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="partner-area">
            <div class="col-lg-8">
                <div class="partner-wrap partner-negative">
                    <div class="col-lg-11 mx-auto">
                        <div class="partner-marquee" aria-label="Danh sách đối tác">
                            <div class="partner-marquee-track">
                                @foreach ($partnerLogos as $index => $logoPath)
                                    <div class="partner-card">
                                        <img src="{{ url($logoPath) }}" alt="Logo đối tác {{ $index + 1 }}">
                                    </div>
                                @endforeach
                                @foreach ($partnerLogos as $index => $logoPath)
                                    <div class="partner-card" aria-hidden="true">
                                        <img src="{{ url($logoPath) }}" alt="">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

<footer class="footer-area ft-bg">
    <div class="footer-widget pt-60">
        <div class="container">
            <div class="row footer-widget-wrapper pt-100 pb-70">
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box about-us">
                        <a href="{{ route('frontend.home') }}" class="footer-logo">
                            @if ($logo)
                                <img src="{{ $logo }}" alt="{{ $displayValue($settings->company_name ?? null, 'Logo') }}">
                            @endif
                        </a>
                        <ul class="footer-contact">
                            <li>
                                <div class="footer-call">
                                    <div class="footer-call-icon">
                                        <i class="fal fa-headset"></i>
                                    </div>
                                    <div class="footer-call-info">
                                        <h6>Hỗ trợ 24/7</h6>
                                        @if ($hasDisplayValue($hotline))
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $hotline) }}">{{ $displayValue($hotline) }}</a>
                                        @else
                                            <span>{{ $displayValue($hotline) }}</span>
                                        @endif
                                    </div>
                                </div>
                            </li>
                            <li><i class="far fa-map-marker-alt"></i>{{ $displayValue($settings->address ?? null) }}</li>
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
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Công ty</h4>
                        <ul class="footer-list">
                            <li><a href="{{ route('frontend.about') }}"><i class="fas fa-angle-double-right"></i> Về chúng tôi</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Gặp đội ngũ</a></li>
                            <li><a href="{{ route('frontend.contact') }}"><i class="fas fa-angle-double-right"></i> Liên hệ</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Chương trình cộng tác viên</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Quảng cáo cùng chúng tôi</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Tuyển dụng</a></li>
                            <li><a href="{{ route('frontend.news.index') }}"><i class="fas fa-angle-double-right"></i> Blog của chúng tôi</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Dịch vụ khác</h4>
                        <ul class="footer-list">
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Chương trình ưu đãi</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Đối tác</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Chương trình cộng đồng</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Quan hệ nhà đầu tư</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Hướng dẫn nhà phát triển</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> API du lịch</a></li>
                            <li><a href="#"><i class="fas fa-angle-double-right"></i> Điểm thưởng PLUS</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-6 col-lg-3">
                    <div class="footer-widget-box list">
                        <h4 class="footer-widget-title">Bản tin</h4>
                        <div class="footer-newsletter">
                            <p>Đăng ký bản tin để nhận cập nhật và tin tức mới nhất</p>
                            <div class="subscribe-form">
                                <form action="#">
                                    <div class="form-group">
                                        <div class="form-group-icon">
                                            <input type="email" class="form-control" placeholder="Email của bạn">
                                            <i class="far fa-envelopes"></i>
                                        </div>
                                    </div>
                                    <button class="theme-btn" type="submit">
                                        Đăng ký ngay <i class="far fa-paper-plane"></i>
                                    </button>
                                    <p><i class="far fa-lock"></i> Thông tin của bạn luôn được bảo mật.</p>
                                </form>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-md-6 align-self-center">
                    <p class="copyright-text">
                        &copy; Bản quyền <span id="date"></span> <a href="#"> Vietnam homes Tourist </a> Đã đăng ký mọi quyền.
                    </p>
                </div>
                <div class="col-md-6 align-self-center">
                    <ul class="footer-social">
                        @if ($socialMap->get('facebook'))
                            <li><a href="{{ $socialMap->get('facebook') }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-facebook-f"></i></a></li>
                        @endif
                        @if ($socialMap->get('youtube'))
                            <li><a href="{{ $socialMap->get('youtube') }}" target="_blank" rel="noopener noreferrer"><i class="fab fa-youtube"></i></a></li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>


</footer>

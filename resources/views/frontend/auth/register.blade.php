@extends('frontend.layouts.app')

@section('title', 'Đăng ký tài khoản')

@section('content')
<main class="main news-category-page">

    <div class="login-area py-120 bg ">
        <div class="container">
            <div class="col-md-5 mx-auto">
                <div class="login-form">
                    <div class="login-header">
                        @if (isset($settings) && $settings->footer_logo)
                            <img src="{{ \App\Support\MediaManager::publicUrl($settings->footer_logo) }}" alt="Logo">
                        @else
                            <img src="{{ asset('tourit/assets/img/logo/logo-dark.png') }}" alt="Logo">
                        @endif
                        <p>Tạo tài khoản mới để nhận nhiều ưu đãi</p>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('frontend.register.submit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Họ và tên <span class="text-danger">*</span></label>
                            <div class="form-group-icon">
                                <input type="text" name="name" class="form-control" placeholder="Họ và tên của bạn" value="{{ old('name') }}" required>
                                <i class="far fa-user"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Số điện thoại</label>
                            <div class="form-group-icon">
                                <input type="text" name="phone" class="form-control" placeholder="Số điện thoại của bạn" value="{{ old('phone') }}">
                                <i class="far fa-phone"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Địa chỉ Email <span class="text-danger">*</span></label>
                            <div class="form-group-icon">
                                <input type="email" name="email" class="form-control" placeholder="Email của bạn" value="{{ old('email') }}" required>
                                <i class="far fa-envelope"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu <span class="text-danger">*</span></label>
                            <div class="form-group-icon">
                                <input type="password" name="password" class="form-control" placeholder="Mật khẩu của bạn" required>
                                <i class="far fa-lock"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Nhập lại mật khẩu <span class="text-danger">*</span></label>
                            <div class="form-group-icon">
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Nhập lại mật khẩu của bạn" required>
                                <i class="far fa-lock"></i>
                            </div>
                        </div>
                        <div class="d-flex align-items-center mt-4">
                            <button type="submit" class="theme-btn"><i class="far fa-user-plus"></i> Đăng ký</button>
                        </div>
                    </form>
                    <div class="login-footer mt-4">
                        <div class="login-divider"><span>Hoặc</span></div>
                        <div class="social-login">
                            <a href="{{ route('frontend.login.google') }}" class="btn-gl"><i class="fab fa-google"></i> Đăng nhập với Google</a>
                        </div>
                        <p>Đã có tài khoản? <a href="{{ route('frontend.login') }}">Đăng nhập.</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

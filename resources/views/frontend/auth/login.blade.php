@extends('frontend.layouts.app')

@section('title', 'Đăng nhập')

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
                        <p>Đăng nhập vào tài khoản của bạn</p>
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

                    <form action="{{ route('frontend.login.submit') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label>Địa chỉ Email</label>
                            <div class="form-group-icon">
                                <input type="email" name="email" class="form-control" placeholder="Email của bạn" value="{{ old('email') }}" required>
                                <i class="far fa-envelope"></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Mật khẩu</label>
                            <div class="form-group-icon">
                                <input type="password" name="password" class="form-control" placeholder="Mật khẩu của bạn" required>
                                <i class="far fa-lock"></i>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mb-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    Ghi nhớ đăng nhập
                                </label>
                            </div>
                            <a href="#" class="forgot-pass">Quên mật khẩu?</a>
                        </div>
                        <div class="d-flex align-items-center">
                            <button type="submit" class="theme-btn"><i class="far fa-sign-in"></i> Đăng nhập</button>
                        </div>
                    </form>
                    <div class="login-footer">
                        <div class="login-divider"><span>Hoặc</span></div>
                        <div class="social-login">
                            <a href="{{ route('frontend.login.google') }}" class="btn-gl"><i class="fab fa-google"></i> Đăng nhập với Google</a>
                        </div>
                        <p>Chưa có tài khoản? <a href="{{ route('frontend.register') }}">Đăng ký ngay.</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
